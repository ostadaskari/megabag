<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_receipt') {
    
    $receiptId = filter_input(INPUT_POST, 'receipt_id', FILTER_VALIDATE_INT);

    if ($receiptId) {


        try {
            // Step 1: Get product_lot_id and qty_received from stock_receipts
            $stmt = $conn->prepare("SELECT product_lot_id, qty_received FROM stock_receipts WHERE id = ?");
            $stmt->bind_param("i", $receiptId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Receipt not found.");
            }
            $receiptData = $result->fetch_assoc();
            $lotId = $receiptData['product_lot_id'];
            $qtyReceived = $receiptData['qty_received'];
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

            // Step 3: Update products table by subtracting the quantity
            $update = $conn->prepare("UPDATE products SET qty = qty - ? WHERE id = ?");
            $update->bind_param("ii", $qtyReceived, $productId);
            if (!$update->execute()) {
                throw new Exception("Failed to update product quantity.");
            }
            $update->close();

            // Step 4: Delete from stock_receipts
            $deleteReceipt = $conn->prepare("DELETE FROM stock_receipts WHERE id = ?");
            $deleteReceipt->bind_param("i", $receiptId);
            if (!$deleteReceipt->execute()) {
                throw new Exception("Failed to delete from stock_receipts.");
            }
            $deleteReceipt->close();

            // Step 5: Delete from product_lots
            $deleteLot = $conn->prepare("DELETE FROM product_lots WHERE id = ?");
            $deleteLot->bind_param("i", $lotId);
            if (!$deleteLot->execute()) {
                throw new Exception("Failed to delete from product_lots.");
            }
            $deleteLot->close();

            // Commit the transaction
            $conn->commit();
            header("Location: ../auth/dashboard.php?page=list_receipts&status=deleted");
            exit;

        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            // Pass the error message to the view
            $errorMessage = urlencode($e->getMessage());
            error_log("Receipt deletion failed: " . $e->getMessage());
            header("Location: ../auth/dashboard.php?page=list_receipts&error=" . $errorMessage);
            exit;
        } finally {
            // Close the database connection
            if ($conn) {
                $conn->close();
            }
        }
    }
}

include '../../design/views/manager/receive_stock_list_view.php';
