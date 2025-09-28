<?php
require_once '../db/db.php';

// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// (ACL) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $projectId = (int) $_POST['project_id'];

    // Start a transaction to ensure all database operations are atomic.
    $conn->begin_transaction();
    $transaction_successful = true;

    try {
        // --- Step 1: Return quantities to products and product_lots tables ---
        // Get the used_qty and product_lot_id for all products associated with the project.
        // We join with product_lots to get the product_id needed for the main products table update.
        $stmt_get_products = $conn->prepare("
            SELECT pp.used_qty, pp.product_lot_id, pl.product_id 
            FROM project_products AS pp
            JOIN product_lots AS pl ON pp.product_lot_id = pl.id
            WHERE pp.project_id = ?
        ");
        if (!$stmt_get_products) {
            throw new Exception("Prepare statement failed for fetching project products.");
        }
        $stmt_get_products->bind_param("i", $projectId);
        $stmt_get_products->execute();
        $result = $stmt_get_products->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $productLotId = $row['product_lot_id'];
            $productId = $row['product_id'];
            $usedQty = $row['used_qty'];
            
            // Add the quantity back to the respective product_lot
            $stmt_update_lot = $conn->prepare("UPDATE product_lots SET qty_available = qty_available + ? WHERE id = ?");
            if (!$stmt_update_lot) {
                throw new Exception("Prepare statement failed for updating product lots.");
            }
            $stmt_update_lot->bind_param("ii", $usedQty, $productLotId);
            $stmt_update_lot->execute();
            if ($stmt_update_lot->affected_rows === 0) {
                // This could happen if the lot was already deleted, we can log it but not fail the transaction.
                error_log("Failed to update product lot quantity for lot ID: " . $productLotId);
            }
            $stmt_update_lot->close();
            
            // Add the quantity back to the main products table's 'qty' and subtract from 'used_qty'
            $stmt_update_product = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
            if (!$stmt_update_product) {
                throw new Exception("Prepare statement failed for updating products.");
            }
            $stmt_update_product->bind_param("ii", $usedQty, $productId);
            $stmt_update_product->execute();
            if ($stmt_update_product->affected_rows === 0) {
                // This could happen if the product was already deleted, we can log it but not fail the transaction.
                error_log("Failed to update main product quantity for product ID: " . $productId);
            }
            $stmt_update_product->close();
        }
        $stmt_get_products->close();
        
        // --- Step 2: Delete project products ---
        $stmt_delete_project_products = $conn->prepare("DELETE FROM project_products WHERE project_id = ?");
        if (!$stmt_delete_project_products) {
            throw new Exception("Prepare statement failed for deleting project products.");
        }
        $stmt_delete_project_products->bind_param("i", $projectId);
        $stmt_delete_project_products->execute();
        $stmt_delete_project_products->close();
        
        // --- Step 3: Delete the project from the 'projects' table ---
        $stmt_delete_project = $conn->prepare("DELETE FROM projects WHERE id = ?");
        if (!$stmt_delete_project) {
            throw new Exception("Prepare statement failed for deleting project.");
        }
        $stmt_delete_project->bind_param("i", $projectId);
        $stmt_delete_project->execute();
        $stmt_delete_project->close();
        
        // If all steps were successful, commit the transaction
        $conn->commit();

    } catch (Exception $e) {
        // If any step failed, roll back the transaction
        $conn->rollback();
        $transaction_successful = false;
        // Optionally log the error or handle it gracefully
        error_log("Project deletion failed: " . $e->getMessage());

        // Display an error message to the user
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Project deletion failed. Please try again.',
            }).then(() => window.location.reload());
        </script>";
        // Redirect to avoid re-submission
        header("Location: ../auth/dashboard.php?page=projects_list");
        exit;
    }

    if ($transaction_successful) {
        // Show a success message if the transaction committed
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Project deleted successfully and quantities were returned.',
            }).then(() => window.location.reload());
        </script>";
    }

    // Redirect to avoid re-submission
    header("Location: ../auth/dashboard.php?page=projects_list");
    exit;
}

require_once '../../design/views/manager/projects_list_view.php';
