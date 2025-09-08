<?php
// Start a session if one is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file
require_once('../db/db.php');

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

// Read messages from query string for SweetAlert
$success = isset($_GET['success']) ? $_GET['success'] : '';
$errors = isset($_GET['error']) ? explode(' | ', $_GET['error']) : [];

// Initialize project and products data
$project_data = null;
$project_products = [];
$project_id = $_GET['id'] ?? null;

// Handle GET request to fetch project data for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $project_id = (int)$_GET['id'];
    
    // Fetch project details using the correct 'id' column
    $stmt_project = $conn->prepare("SELECT id, project_name, employer, designators FROM projects WHERE id = ?");
    $stmt_project->bind_param("i", $project_id);
    $stmt_project->execute();
    $result_project = $stmt_project->get_result();
    $project_data = $result_project->fetch_assoc();
    $stmt_project->close();

    // Fetch associated product lots for the project using 'x_code'
    $stmt_products = $conn->prepare("
            SELECT 
            pp.project_id, 
            pp.product_lot_id, 
            pp.used_qty, 
            pp.remarks, 
            pl.product_id, 
            pl.x_code,
            p.part_number
        FROM 
            project_products pp 
        JOIN 
            product_lots pl ON pp.product_lot_id = pl.id 
        JOIN
            products p ON pl.product_id = p.id
        WHERE 
            pp.project_id = ?");
    $stmt_products->bind_param("i", $project_id);
    $stmt_products->execute();
    $result_products = $stmt_products->get_result();
    while ($row = $result_products->fetch_assoc()) {
        $project_products[] = $row;
    }
    $stmt_products->close();
}

// Handle POST request to update the project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $now = date('Y-m-d H:i:s');
    $errors = [];

    $projectId = $_POST['project_id'] ?? null;
    $projectName = $_POST['project_name'] ?? '';
    $employer = $_POST['employer'] ?? '';
    $designators = $_POST['designators'] ?? '';
    $products_submitted = $_POST['products'] ?? [];

    if (empty($projectId) || empty($projectName)) {
        $errors[] = "Project ID and Name are required.";
    }

    if (empty($errors)) {
        try {
            $conn->begin_transaction();

            // 1. Update the main projects table using the 'id' column
            $stmt_update_project = $conn->prepare("UPDATE projects SET project_name = ?, employer = ?, designators = ?, updated_at = ? WHERE id = ?");
            if (!$stmt_update_project) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            // Corrected bind_param: The SQL query has 5 placeholders, so you must bind 5 variables with 5 type specifiers.
            $stmt_update_project->bind_param("ssssi", $projectName, $employer, $designators, $now, $projectId);
            if (!$stmt_update_project->execute()) {
                throw new Exception("Failed to update project: " . $stmt_update_project->error);
            }
            $stmt_update_project->close();

            // 2. Fetch the current products linked to the project for comparison
            $current_products = [];
            $stmt_fetch_current = $conn->prepare("SELECT product_lot_id, used_qty FROM project_products WHERE project_id = ?");
            $stmt_fetch_current->bind_param("i", $projectId);
            $stmt_fetch_current->execute();
            $result_current = $stmt_fetch_current->get_result();
            while ($row = $result_current->fetch_assoc()) {
                $current_products[$row['product_lot_id']] = $row['used_qty'];
            }
            $stmt_fetch_current->close();

            // Prepared statements for product updates
            $stmt_insert = $conn->prepare("INSERT INTO project_products (project_id, product_lot_id, used_qty, remarks) VALUES (?, ?, ?, ?)");
            $stmt_update = $conn->prepare("UPDATE project_products SET used_qty = ?, remarks = ? WHERE project_id = ? AND product_lot_id = ?");
            $stmt_delete = $conn->prepare("DELETE FROM project_products WHERE project_id = ? AND product_lot_id = ?");
            
            // Prepared statements for stock updates
            $stmt_update_lot = $conn->prepare("UPDATE product_lots SET qty_available = qty_available - ? WHERE id = ?");
            // Update both 'qty' (available) and 'used_qty' in the products table
            $stmt_update_product = $conn->prepare("UPDATE products SET qty = qty - ?, used_qty = used_qty + ? WHERE id = ?");

            // Process submitted products
            $submitted_lot_ids = [];
            foreach ($products_submitted as $productData) {
                $productLotId = (int)$productData['product_lot_id'];
                $usedQty = (int)$productData['used_qty'];
                $remarks = trim($productData['remarks'] ?? '');

                if ($productLotId <= 0 || $usedQty <= 0) {
                    $errors[] = "Invalid product lot or quantity submitted.";
                    continue;
                }
                $submitted_lot_ids[] = $productLotId;

                // Fetch product ID from product lots for main product update
                $stmt_fetch_prod_id = $conn->prepare("SELECT product_id FROM product_lots WHERE id = ?");
                $stmt_fetch_prod_id->bind_param("i", $productLotId);
                $stmt_fetch_prod_id->execute();
                $product_id_row = $stmt_fetch_prod_id->get_result()->fetch_assoc();
                $main_product_id = $product_id_row['product_id'];
                $stmt_fetch_prod_id->close();
                
                if (isset($current_products[$productLotId])) {
                    // This is an existing product, check for quantity change
                    $original_qty = $current_products[$productLotId];
                    $qty_change = $usedQty - $original_qty;

                    if ($qty_change != 0) {
                        // Check if sufficient stock is available for positive changes
                        if ($qty_change > 0) {
                            $stmt_check_qty = $conn->prepare("SELECT qty_available FROM product_lots WHERE id = ?");
                            $stmt_check_qty->bind_param("i", $productLotId);
                            $stmt_check_qty->execute();
                            $result = $stmt_check_qty->get_result()->fetch_assoc();
                            if ($result['qty_available'] < $qty_change) {
                                $errors[] = "Row with lot ID {$productLotId}: Insufficient stock to add {$qty_change} units.";
                                continue;
                            }
                        }
                        
                        // Update product lots stock
                        $stmt_update_lot->bind_param("ii", $qty_change, $productLotId);
                        $stmt_update_lot->execute();
                        
                        // Update main product stock, adjusting for both qty and used_qty
                        $stmt_update_product->bind_param("iii", $qty_change, $qty_change, $main_product_id);
                        $stmt_update_product->execute();

                        // Update project_products
                        $stmt_update->bind_param("iisi", $usedQty, $remarks, $projectId, $productLotId);
                        $stmt_update->execute();
                    }
                    unset($current_products[$productLotId]); // Mark as processed
                } else {
                    // This is a new product, debit stock and insert
                    $stmt_check_qty = $conn->prepare("SELECT qty_available FROM product_lots WHERE id = ?");
                    $stmt_check_qty->bind_param("i", $productLotId);
                    $stmt_check_qty->execute();
                    $result = $stmt_check_qty->get_result()->fetch_assoc();
                    if ($result['qty_available'] < $usedQty) {
                        $errors[] = "New lot with ID {$productLotId}: Insufficient stock.";
                        continue;
                    }

                    // Insert into project_products
                    $stmt_insert->bind_param("iiis", $projectId, $productLotId, $usedQty, $remarks);
                    $stmt_insert->execute();

                    // Debit product lots stock
                    $stmt_update_lot->bind_param("ii", $usedQty, $productLotId);
                    $stmt_update_lot->execute();
                    
                    // Debit main product stock, adjusting for both qty and used_qty
                    $stmt_update_product->bind_param("iii", $usedQty, $usedQty, $main_product_id);
                    $stmt_update_product->execute();
                }
            }

            // Process products that were removed from the project
            foreach ($current_products as $productLotId => $usedQty) {
                // Fetch product ID from product lots for main product update
                $stmt_fetch_prod_id = $conn->prepare("SELECT product_id FROM product_lots WHERE id = ?");
                $stmt_fetch_prod_id->bind_param("i", $productLotId);
                $stmt_fetch_prod_id->execute();
                $product_id_row = $stmt_fetch_prod_id->get_result()->fetch_assoc();
                $main_product_id = $product_id_row['product_id'];
                $stmt_fetch_prod_id->close();
                
                // Add quantity back to stock, and decrease used_qty
                $qty_to_return = -$usedQty; // Negative to add back
                $stmt_update_lot->bind_param("ii", $qty_to_return, $productLotId);
                $stmt_update_lot->execute();
                
                // Add quantity back to main product stock, and decrease used_qty
                // The 'qty_to_return' is negative, so adding it back increases 'qty' and decreases 'used_qty'
                $stmt_update_product->bind_param("iii", $qty_to_return, $qty_to_return, $main_product_id);
                $stmt_update_product->execute();

                // Delete from project_products
                $stmt_delete->bind_param("ii", $projectId, $productLotId);
                $stmt_delete->execute();
            }

            // Close prepared statements
            $stmt_insert->close();
            $stmt_update->close();
            $stmt_delete->close();
            $stmt_update_lot->close();
            $stmt_update_product->close();

            if (!empty($errors)) {
                $conn->rollback();
                header("Location: ../auth/dashboard.php?page=edit_project&id={$projectId}&error=" . urlencode(implode(' | ', $errors)));
                exit;
            }

            $conn->commit();
            header("Location: ../auth/dashboard.php?page=edit_project&id={$projectId}&success=" . urlencode("Project '{$projectName}' updated successfully."));
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            header("Location: ../auth/dashboard.php?page=edit_project&id={$projectId}&error=" . urlencode($e->getMessage()));
            exit;
        } finally {
            if ($conn) $conn->close();
        }
    } else {
        header("Location: ../auth/dashboard.php?page=edit_project&id={$projectId}&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

// Load view
include '../../design/views/manager/edit_project_view.php';
