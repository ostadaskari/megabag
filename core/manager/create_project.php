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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Default values and variables
    $user_id = $_SESSION['user_id'];
    $now = date('Y-m-d H:i:s');
    $errors = [];

    // Get project details from the form
    $projectName = $_POST['project_name'] ?? '';
    $employer = $_POST['employer'] ?? '';
    $designators = $_POST['designators'] ?? '';
    $products = $_POST['products'] ?? [];

    // Basic validation
    if (empty($projectName)) {
        $errors[] = "Project Name is required.";
    }

    if (count($products) === 0) {
        $errors[] = "At least one product lot is required for a project.";
    }

    // If there are initial validation errors, redirect and exit
    if (!empty($errors)) {
        header("Location: ../auth/dashboard.php?page=create_project&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }

    try {
        // Begin a database transaction to ensure data integrity
        $conn->begin_transaction();

        // 1. Insert into the projects table
        // NOTE: The database schema provided does not have 'date_code' or 'purchase_code',
        // but the form does. Assuming your actual table structure includes them.
        $stmt_project = $conn->prepare("INSERT INTO projects (project_name, employer, designators, user_id, created_at) VALUES ( ?, ?, ?, ?, ?)");
        if (!$stmt_project) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt_project->bind_param("sssis", $projectName, $employer, $designators, $user_id, $now);
        
        if (!$stmt_project->execute()) {
            throw new Exception("Failed to insert project: " . $stmt_project->error);
        }
        $projectId = $conn->insert_id;
        $stmt_project->close();

        // 2. Loop through and process each product lot
        $stmt_insert_project_products = $conn->prepare("INSERT INTO project_products (project_id, product_lot_id, used_qty, remarks) VALUES (?, ?, ?, ?)");
        $stmt_update_product_lot = $conn->prepare("UPDATE product_lots SET qty_available = qty_available - ? WHERE id = ?");
        // NEW: Prepared statement for updating the main products table
        $stmt_update_product = $conn->prepare("UPDATE products SET qty = qty - ? WHERE id = ?");

        foreach ($products as $index => $productData) {
            $productLotId = isset($productData['product_lot_id']) ? (int) $productData['product_lot_id'] : 0;
            $usedQty = isset($productData['used_qty']) ? (int) $productData['used_qty'] : 0;
            $remarks = trim($productData['remarks'] ?? '');

            if ($productLotId <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Invalid product lot selection.";
                continue;
            }

            if ($usedQty <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Quantity must be greater than 0.";
                continue;
            }

            // Check if sufficient quantity is available
            $stmt_check_qty = $conn->prepare("SELECT qty_available, product_id FROM product_lots WHERE id = ?");
            $stmt_check_qty->bind_param("i", $productLotId);
            $stmt_check_qty->execute();
            $result = $stmt_check_qty->get_result();
            $row = $result->fetch_assoc();
            $stmt_check_qty->close();

            if (!$row || $row['qty_available'] < $usedQty) {
                $errors[] = "Row " . ($index + 1) . ": Insufficient stock. Available: " . ($row['qty_available'] ?? 0);
                continue;
            }

            // Insert into the project_products join table
            $stmt_insert_project_products->bind_param("iiis", $projectId, $productLotId, $usedQty, $remarks);
            if (!$stmt_insert_project_products->execute()) {
                throw new Exception("Error inserting product lot into project at row " . ($index + 1) . ": " . $stmt_insert_project_products->error);
            }

            // Update the quantity in the product_lots table
            $stmt_update_product_lot->bind_param("ii", $usedQty, $productLotId);
            if (!$stmt_update_product_lot->execute()) {
                throw new Exception("Error updating product lot stock at row " . ($index + 1) . ": " . $stmt_update_product_lot->error);
            }

            // NEW: Update the quantity in the products table
            $productId = $row['product_id'];
            $stmt_update_product->bind_param("ii", $usedQty, $productId);
            if (!$stmt_update_product->execute()) {
                throw new Exception("Error updating product stock at row " . ($index + 1) . ": " . $stmt_update_product->error);
            }
        }

        // Check if any errors were found during the product loop
        if (!empty($errors)) {
            // If errors occurred, rollback and redirect with error messages
            $conn->rollback();
            header("Location: ../auth/dashboard.php?page=create_project&error=" . urlencode(implode(' | ', $errors)));
            exit;
        }

        // If everything is successful, commit the transaction
        $conn->commit();
        header("Location: ../auth/dashboard.php?page=create_project&success=" . urlencode("Project '{$projectName}' created successfully."));
        exit;

    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();
        header("Location: ../auth/dashboard.php?page=create_project&error=" . urlencode($e->getMessage()));
        exit;
    } finally {
        // Close prepared statements and connection
        if (isset($stmt_insert_project_products)) $stmt_insert_project_products->close();
        if (isset($stmt_update_product_lot)) $stmt_update_product_lot->close();
        // NEW: Close the new prepared statement
        if (isset($stmt_update_product)) $stmt_update_product->close();
        if ($conn) $conn->close();
    }
}

// Load view
include '../../design/views/manager/create_project_view.php';
