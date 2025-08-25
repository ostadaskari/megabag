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

// Initialize variables for the view
$project = null;
$projectProducts = [];
$projectId = 0;

// Handle GET request to load project data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if a project ID is provided
    if (!isset($_GET['project_id']) || !is_numeric($_GET['project_id'])) {
        header("Location: ../auth/dashboard.php?page=projects_list&error=" . urlencode("Invalid project ID provided."));
        exit;
    }
    
    $projectId = (int)$_GET['project_id'];

    try {
        // Fetch project details
        $stmt_project = $conn->prepare("SELECT * FROM projects WHERE id = ?");
        if (!$stmt_project) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt_project->bind_param("i", $projectId);
        $stmt_project->execute();
        $result_project = $stmt_project->get_result();
        $project = $result_project->fetch_assoc();
        $stmt_project->close();

        // If no project found, redirect
        if (!$project) {
            header("Location: ../auth/dashboard.php?page=projects_list&error=" . urlencode("Project not found."));
            exit;
        }

        // Fetch associated products for the project
        $stmt_products = $conn->prepare("
            SELECT pp.project_id, pp.product_id, pp.used_qty, pp.remarks, p.part_number, p.name AS product_name, p.qty AS current_qty
            FROM project_products AS pp
            JOIN products AS p ON pp.product_id = p.id
            WHERE pp.project_id = ?
        ");
        if (!$stmt_products) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt_products->bind_param("i", $projectId);
        $stmt_products->execute();
        $result_products = $stmt_products->get_result();
        while ($row = $result_products->fetch_assoc()) {
            $projectProducts[] = $row;
        }
        $stmt_products->close();

    } catch (Exception $e) {
        header("Location: ../auth/dashboard.php?page=projects_list&error=" . urlencode($e->getMessage()));
        exit;
    }
}

// Handle POST request to update project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Default values and variables
    $user_id = $_SESSION['user_id'];
    $now = date('Y-m-d H:i:s');
    $errors = [];

    // Get project ID and details from the form
    $projectId = $_POST['project_id'] ?? 0;
    $projectName = $_POST['project_name'] ?? '';
    $dateCode = $_POST['date_code'] ?? '';
    $employer = $_POST['employer'] ?? '';
    $purchaseCode = $_POST['purchase_code'] ?? '';
    $designators = $_POST['designators'] ?? '';
    $products = $_POST['products'] ?? [];

    // Basic validation
    if ($projectId <= 0) {
        $errors[] = "Invalid project ID.";
    }
    if (empty($projectName)) {
        $errors[] = "Project Name is required.";
    }
    if (!empty($errors)) {
        header("Location: ../auth/dashboard.php?page=edit_project&project_id={$projectId}&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }

    try {
        // Fetch existing project products to compare with the new ones
        $stmt_old_products = $conn->prepare("SELECT product_id, used_qty FROM project_products WHERE project_id = ?");
        $stmt_old_products->bind_param("i", $projectId);
        $stmt_old_products->execute();
        $result_old_products = $stmt_old_products->get_result();
        $oldProductsMap = [];
        while ($row = $result_old_products->fetch_assoc()) {
            $oldProductsMap[$row['product_id']] = $row['used_qty'];
        }
        $stmt_old_products->close();

        // Begin a database transaction to ensure data integrity
        $conn->begin_transaction();

        // 1. Update the projects table
        $stmt_project = $conn->prepare("UPDATE projects SET project_name = ?, date_code = ?, employer = ?, purchase_code = ?, designators = ?, updated_at = ? WHERE id = ?");
        if (!$stmt_project) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt_project->bind_param("ssssssi", $projectName, $dateCode, $employer, $purchaseCode, $designators, $now, $projectId);
        
        if (!$stmt_project->execute()) {
            throw new Exception("Failed to update project: " . $stmt_project->error);
        }
        $stmt_project->close();

        // 2. Prepare statements for updating products and the join table
        $stmt_insert_product = $conn->prepare("INSERT INTO project_products (project_id, product_id, used_qty, remarks) VALUES (?, ?, ?, ?)");
        $stmt_update_product_used = $conn->prepare("UPDATE project_products SET used_qty = ?, remarks = ? WHERE project_id = ? AND product_id = ?");
        $stmt_update_product_stock = $conn->prepare("UPDATE products SET qty = qty - ?, used_qty = used_qty + ? WHERE id = ?");
        $stmt_check_qty = $conn->prepare("SELECT qty FROM products WHERE id = ?");

        $newProductIds = [];

        foreach ($products as $index => $productData) {
            $productId = isset($productData['product_id']) ? (int) $productData['product_id'] : 0;
            $usedQty = isset($productData['used_qty']) ? (int) $productData['used_qty'] : 0;
            $remarks = trim($productData['remarks'] ?? '');

            if ($productId <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Invalid product selection.";
                continue;
            }
            $newProductIds[] = $productId;

            if ($usedQty <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Quantity must be greater than 0.";
                continue;
            }

            // Get the old quantity for this product, if it exists
            $oldUsedQty = $oldProductsMap[$productId] ?? 0;
            $qty_difference = $usedQty - $oldUsedQty;
            
            // If the quantity has changed, update the main products table stock
            if ($qty_difference != 0) {
                // Check if sufficient quantity is available
                // This check is crucial to prevent negative stock when increasing quantity
                if ($qty_difference > 0) {
                    $stmt_check_qty->bind_param("i", $productId);
                    $stmt_check_qty->execute();
                    $result = $stmt_check_qty->get_result();
                    $row = $result->fetch_assoc();
                    $availableQty = $row['qty'] ?? 0;
                    $stmt_check_qty->close();
                    if ($availableQty < $qty_difference) {
                        $errors[] = "Row " . ($index + 1) . ": Insufficient stock. Available: " . $availableQty;
                        continue;
                    }
                }
                
                // Update the quantity in the main products table using the difference
                $stmt_update_product_stock->bind_param("iii", $qty_difference, $qty_difference, $productId);
                if (!$stmt_update_product_stock->execute()) {
                    throw new Exception("Error updating product stock at row " . ($index + 1) . ": " . $stmt_update_product_stock->error);
                }
            }

            // Update or insert into the project_products join table
            if ($oldUsedQty > 0) {
                // Update the existing record
                $stmt_update_product_used->bind_param("isii", $usedQty, $remarks, $projectId, $productId);
                if (!$stmt_update_product_used->execute()) {
                    throw new Exception("Error updating product in project at row " . ($index + 1) . ": " . $stmt_update_product_used->error);
                }
            } else {
                // Insert a new record
                $stmt_insert_product->bind_param("iiis", $projectId, $productId, $usedQty, $remarks);
                if (!$stmt_insert_product->execute()) {
                    throw new Exception("Error inserting product into project at row " . ($index + 1) . ": " . $stmt_insert_product->error);
                }
            }
        }

        // 3. Handle products that were removed from the project
        $productsToRemove = array_diff(array_keys($oldProductsMap), $newProductIds);
        if (!empty($productsToRemove)) {
            $stmt_delete_product_from_project = $conn->prepare("DELETE FROM project_products WHERE project_id = ? AND product_id = ?");
            $stmt_revert_product_stock = $conn->prepare("UPDATE products SET qty = qty + ?, used_qty = used_qty - ? WHERE id = ?");

            foreach ($productsToRemove as $productId) {
                $revertQty = $oldProductsMap[$productId];
                
                // Revert stock back to the products table
                $stmt_revert_product_stock->bind_param("iii", $revertQty, $revertQty, $productId);
                if (!$stmt_revert_product_stock->execute()) {
                    throw new Exception("Error reverting stock for removed product ID {$productId}: " . $stmt_revert_product_stock->error);
                }

                // Delete the product from the project_products join table
                $stmt_delete_product_from_project->bind_param("ii", $projectId, $productId);
                if (!$stmt_delete_product_from_project->execute()) {
                    throw new Exception("Error deleting removed product from project: " . $stmt_delete_product_from_project->error);
                }
            }
            $stmt_delete_product_from_project->close();
            $stmt_revert_product_stock->close();
        }

        // Check if any errors were found during the product loop
        if (!empty($errors)) {
            $conn->rollback();
            header("Location: ../auth/dashboard.php?page=edit_project&project_id={$projectId}&error=" . urlencode(implode(' | ', $errors)));
            exit;
        }

        // If everything is successful, commit the transaction
        $conn->commit();
        header("Location: ../auth/dashboard.php?page=projects_list&success=" . urlencode("Project '{$projectName}' updated successfully."));
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../auth/dashboard.php?page=edit_project&project_id={$projectId}&error=" . urlencode($e->getMessage()));
        exit;
    } finally {
        if (isset($stmt_insert_product)) $stmt_insert_product->close();
        if (isset($stmt_update_product_used)) $stmt_update_product_used->close();
        if (isset($stmt_update_product_stock)) $stmt_update_product_stock->close();
        if ($conn) $conn->close();
    }
}

// Load view
include '../../design/views/manager/edit_project_view.php';
