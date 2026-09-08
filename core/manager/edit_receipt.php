<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../db/db.php';
require_once '../auth/csrf.php';

// (ACL) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$receiptData = null;
$error = null;

// Handle form submission for updating a receipt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();

    try {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        $receiptId = (int)($_POST['receipt_id'] ?? 0);
        $newQty = (int)($_POST['qty_received'] ?? 0);
        $newPurchaseCode = !empty(trim($_POST['purchase_code'] ?? '')) ? trim($_POST['purchase_code']) : null;
        $newVrmXCode = !empty(trim($_POST['vrm_x_code'] ?? '')) ? trim($_POST['vrm_x_code']) : null;
        $newDateCode = (int)($_POST['date_code'] ?? 0);
        $newLotLocation = !empty(trim($_POST['lot_location'] ?? '')) ? trim($_POST['lot_location']) : null;
        $newProjectName = !empty(trim($_POST['project_name'] ?? '')) ? trim($_POST['project_name']) : null;
        $isLocked = isset($_POST['lock']) ? 1 : 0;
        $newRemarks = trim($_POST['remarks'] ?? '');

        if ($receiptId <= 0 || $newQty <= 0) {
            throw new Exception("Invalid receipt ID or quantity.");
        }

        // Step 1: Get current receipt data
        $stmt = $conn->prepare("SELECT product_lot_id, qty_received FROM stock_receipts WHERE id = ?");
        if ($stmt === false) {
            throw new Exception("Failed to prepare receipt data query: " . $conn->error);
        }
        $stmt->bind_param("i", $receiptId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("Receipt not found.");
        }
        $oldReceiptData = $result->fetch_assoc();
        $oldQty = (int)$oldReceiptData['qty_received'];
        $lotId = (int)$oldReceiptData['product_lot_id'];
        $stmt->close();

        // Step 2: Get product lot details
        $stmt = $conn->prepare("SELECT product_id, qty_available FROM product_lots WHERE id = ?");
        if ($stmt === false) {
            throw new Exception("Failed to prepare product lot query: " . $conn->error);
        }
        $stmt->bind_param("i", $lotId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("Product lot not found.");
        }
        $lotData = $result->fetch_assoc();
        $productId = (int)$lotData['product_id'];
        $currentQtyAvailable = (int)$lotData['qty_available'];
        $stmt->close();

        $qtyDifference = $newQty - $oldQty;

        // Prevent negative stock availability in lot
        if (($currentQtyAvailable + $qtyDifference) < 0) {
            throw new Exception("Cannot reduce quantity below what has already been issued from this lot.");
        }

        // Step 3: Update products table
        $updateProduct = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
        if ($updateProduct === false) {
            throw new Exception("Failed to prepare product quantity update.");
        }
        $updateProduct->bind_param("ii", $qtyDifference, $productId);
        if (!$updateProduct->execute()) {
            throw new Exception("Failed to update product quantity.");
        }
        $updateProduct->close();
        
        // Step 4: Update stock_receipts table
        $updateReceipt = $conn->prepare("UPDATE stock_receipts SET qty_received = ?, remarks = ? WHERE id = ?");
        if ($updateReceipt === false) {
            throw new Exception("Failed to prepare stock receipt update.");
        }
        $updateReceipt->bind_param("isi", $newQty, $newRemarks, $receiptId);
        if (!$updateReceipt->execute()) {
            throw new Exception("Failed to update stock receipt.");
        }
        $updateReceipt->close();

        // Step 5: Update product_lots table
        $updateLot = $conn->prepare("UPDATE product_lots SET qty_received = ?, qty_available = qty_available + ?, purchase_code = ?, vrm_x_code = ?, date_code = ?, lot_location = ?, project_name = ?, `lock` = ? WHERE id = ?");
        if ($updateLot === false) {
            throw new Exception("Failed to prepare product lot update.");
        }

        // ارسال مستقیم مقادیر (حاوی NULL یا string)
        $updateLot->bind_param("iississii", $newQty, $qtyDifference, $newPurchaseCode, $newVrmXCode, $newDateCode, $newLotLocation, $newProjectName, $isLocked, $lotId);

        if (!$updateLot->execute()) {
            throw new Exception("Failed to update product lot: " . $updateLot->error);
        }
        $updateLot->close();

        $conn->commit();
        header("Location: ../auth/dashboard.php?page=list_receipts&status=updated");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $error = "Failed to update receipt: " . $e->getMessage();
        error_log("Receipt update failed: " . $e->getMessage());
        header("Location: ../auth/dashboard.php?page=edit_receipt&id={$receiptId}&error=" . urlencode($error));
        exit;
    }
}

// Fetch receipt data for display (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $receiptId = (int)$_GET['id'];
    if ($receiptId > 0) {
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
        if ($stmt === false) {
            $error = "Failed to prepare receipt data query for display: " . $conn->error;
        } else {
            $stmt->bind_param("i", $receiptId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $receiptData = $result->fetch_assoc();
            } else {
                $error = "Receipt not found.";
            }
            $stmt->close();
        }
    } else {
        $error = "Invalid receipt ID.";
    }
}

// Load view
include '../../design/views/manager/edit_receipt_view.php';