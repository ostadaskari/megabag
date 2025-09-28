<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_issue') {

    $issueId = filter_input(INPUT_POST, 'issue_id', FILTER_VALIDATE_INT);

    if ($issueId) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Step 1: Get product_lot_id and qty_issued from stock_issues
            $stmt = $conn->prepare("SELECT product_lot_id, qty_issued FROM stock_issues WHERE id = ?");
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Issue not found.");
            }
            $issueData = $result->fetch_assoc();
            $lotId = $issueData['product_lot_id'];
            $qtyIssued = $issueData['qty_issued'];
            $stmt->close();

            // Step 2: Get product_id from product_lots
            $stmt = $conn->prepare("SELECT product_id FROM product_lots WHERE id = ?");
            $stmt->bind_param("i", $lotId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Product lot not found.");
            }
            $lotData = $result->fetch_assoc();
            $productId = $lotData['product_id'];
            $stmt->close();

            // Step 3: Update products table by adding the quantity back
            $update = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
            $update->bind_param("ii", $qtyIssued, $productId);
            if (!$update->execute()) {
                throw new Exception("Failed to update product quantity.");
            }
            $update->close();

            // Step 4: Delete from product_lots
            $updateLot = $conn->prepare("UPDATE product_lots SET qty_available = qty_available + ? WHERE id = ?");
            $updateLot->bind_param("ii", $qtyIssued, $lotId);
            if (!$updateLot->execute()) {
                throw new Exception("Failed to delete from product_lots.");
            }
            $updateLot->close();

            // Step 5: Delete from stock_issues
            $deleteIssue = $conn->prepare("DELETE FROM stock_issues WHERE id = ?");
            $deleteIssue->bind_param("i", $issueId);
            if (!$deleteIssue->execute()) {
                throw new Exception("Failed to delete from stock_issues.");
            }
            $deleteIssue->close();



            // Commit the transaction
            $conn->commit();
            header("Location: ../auth/dashboard.php?page=list_issues&status=deleted");
            exit;

        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            // Pass the error message to the view
            $errorMessage = urlencode($e->getMessage());
            error_log("Issue deletion failed: " . $e->getMessage());
            header("Location: ../auth/dashboard.php?page=list_issues&error=" . $errorMessage);
            exit;
        } finally {
            // Close the database connection
            if ($conn) {
                $conn->close();
            }
        }
    }
}

include '../../design/views/manager/stock_issues_list_view.php';
