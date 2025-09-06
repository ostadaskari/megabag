<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../db/db.php';

// (ACL) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$receiptData = null;
$error = null;

// Handle form submission for updating a receipt
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // Sanitize and validate input
    $receiptId = $_POST['receipt_id'] ?? '';
    $newQty = $_POST['qty_received'] ?? '';
    $newPurchaseCode = $_POST['purchase_code'] ?? '';
    $newVrmXCode = $_POST['vrm_x_code'] ?? '';
    $newDateCode = $_POST['date_code'] ?? '';
    $newLotLocation = $_POST['lot_location'] ?? '';
    $newProjectName = $_POST['project_name'] ?? '';
    $isLocked = isset($_POST['lock']) ? 1 : 0;
    $newRemarks = $_POST['remarks'] ?? '';
    
    // Ensure all required fields are present
    if ($receiptId && $newQty !== false) {

        try {
            // Step 1: Get current receipt data (old quantity and product lot ID)
            $stmt = $conn->prepare("SELECT product_lot_id, qty_received FROM stock_receipts WHERE id = ?");
            $stmt->bind_param("i", $receiptId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Receipt not found.");
            }
            $oldReceiptData = $result->fetch_assoc();
            $oldQty = $oldReceiptData['qty_received'];
            $lotId = $oldReceiptData['product_lot_id'];
            $stmt->close();

            // Step 2: Get product ID from product lots
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

            // Step 3: Update products table by adjusting the quantity
            $qtyDifference = $newQty - $oldQty;
            $updateProduct = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
            $updateProduct->bind_param("ii", $qtyDifference, $productId);
            if (!$updateProduct->execute()) {
                throw new Exception("Failed to update product quantity.");
            }
            $updateProduct->close();
            
            // Step 4: Update the stock_receipts table
            $updateReceipt = $conn->prepare("UPDATE stock_receipts SET qty_received = ?, remarks = ? WHERE id = ?");
            $updateReceipt->bind_param("isi", $newQty, $newRemarks, $receiptId);
            if (!$updateReceipt->execute()) {
                throw new Exception("Failed to update stock receipt.");
            }
            $updateReceipt->close();

            // Step 5: Update the product_lots table with new values
            $updateLot = $conn->prepare("UPDATE product_lots SET qty_received = ?, qty_available = ?, purchase_code = ?, vrm_x_code = ?, date_code = ?, lot_location = ?, project_name = ?, `lock` = ? WHERE id = ?");
            $updateLot->bind_param("iississii", $newQty, $newQty, $newPurchaseCode, $newVrmXCode, $newDateCode, $newLotLocation, $newProjectName, $isLocked, $lotId);
            if (!$updateLot->execute()) {
                throw new Exception("Failed to update product lot.");
            }
            $updateLot->close();

            // Commit the transaction
            $conn->commit();
            header("Location: ../auth/dashboard.php?page=list_receipts&status=updated");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            error_log("Receipt update failed: " . $e->getMessage());
            $error = "Failed to update receipt: " . $e->getMessage();
        } finally {
            if ($conn) {
                $conn->close();
            }
        }
    } else {
        $error = "Invalid receipt ID or quantity.";
    }
}

// Fetch receipt data for display (on initial page load)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $receiptId = (int)$_GET['id'];
    if ($receiptId) {
        $stmt = $conn->prepare("
            SELECT 
                sr.id, 
                sr.qty_received, 
                sr.remarks,
                pl.product_id,
                pl.purchase_code, 
                pl.vrm_x_code, 
                pl.date_code,
                pl.x_code,
                pl.lot_location,
                pl.project_name,
                pl.lock AS is_locked,
                p.part_number
            FROM stock_receipts sr
            JOIN product_lots pl ON sr.product_lot_id = pl.id
            JOIN products p ON pl.product_id = p.id
            WHERE sr.id = ?
        ");
        $stmt->bind_param("i", $receiptId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $receiptData = $result->fetch_assoc();
        } else {
            $error = "Receipt not found.";
        }
        $stmt->close();
        if ($conn) {
            $conn->close();
        }
    } else {
        $error = "Invalid receipt ID.";
    }
}


include '../../design/views/manager/edit_receipt_view.php';
