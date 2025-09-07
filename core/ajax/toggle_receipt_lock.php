<?php
/**
 * Toggles the 'lock' status of a product lot.
 *
 * This script is called via an AJAX request from the frontend. It updates the `product_lots` table,
 * toggling the `lock` field from 0 to 1 or 1 to 0 based on its current value. It requires
 * a 'product_lot_id' to be passed in the POST request.
 *
 * @requires `db.php` for database connection.
 * @requires `check_manager.php` for manager/admin access control.
 */
require_once '../db/db.php';
require_once '../auth/check_manager.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check for POST request and the required lot ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_lot_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$lotId = filter_input(INPUT_POST, 'product_lot_id', FILTER_VALIDATE_INT);

// Validate the lot ID
if (!$lotId) {
    echo json_encode(['success' => false, 'message' => 'Invalid product lot ID.']);
    exit;
}

try {
    // Start transaction for atomicity
    $conn->begin_transaction();

    // Step 1: Get the current lock status
    $stmt = $conn->prepare("SELECT `lock` FROM product_lots WHERE id = ?");
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Product lot not found.");
    }

    $lotData = $result->fetch_assoc();
    $currentLockStatus = $lotData['lock'];
    $newLockStatus = $currentLockStatus == 1 ? 0 : 1;

    // Step 2: Update the lock status
    $updateStmt = $conn->prepare("UPDATE product_lots SET `lock` = ? WHERE id = ?");
    $updateStmt->bind_param("ii", $newLockStatus, $lotId);
    
    if (!$updateStmt->execute()) {
        throw new Exception("Failed to update lock status.");
    }
    
    // Commit the transaction
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Lock status updated successfully.']);

} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    error_log("Lock toggle failed for lot ID {$lotId}: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred.']);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($updateStmt)) $updateStmt->close();
    if ($conn) $conn->close();
}
?>
