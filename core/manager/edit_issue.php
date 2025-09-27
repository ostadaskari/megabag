<?php
require_once '../db/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$issueData = null;
$errorMessage = ''; 
$successMessage = ''; 

// Handle POST request to update an issue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_issue') {
    $issueId = filter_input(INPUT_POST, 'issue_id', FILTER_VALIDATE_INT);
    $newQty = filter_input(INPUT_POST, 'qty_issued', FILTER_VALIDATE_INT);
    $newRemarks = filter_input(INPUT_POST, 'remarks', FILTER_SANITIZE_STRING);

    if (!$issueId || $newQty === false) {
        $errorMessage = "Invalid input for issue ID or quantity. The update was cancelled.";
        // If the ID is invalid in a POST, redirect to the list page with an error.
        header("Location: ../auth/dashboard.php?page=list_issues&error=" . urlencode($errorMessage));
        exit;
    } else {
        // Start a database transaction for data integrity
        $conn->begin_transaction();
        try {
            // Step 1: Get original quantity, product lot ID, and product details 
            $stmt = $conn->prepare("
                SELECT si.qty_issued, si.product_lot_id, pl.product_id 
                FROM stock_issues si 
                JOIN product_lots pl ON si.product_lot_id = pl.id 
                WHERE si.id = ?
            ");
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Original issue not found.");
            }
            $originalData = $result->fetch_assoc();
            $originalQty = $originalData['qty_issued'];
            $productId = $originalData['product_id'];
            $productLotId = $originalData['product_lot_id']; // <-- Retrieved lot ID
            $stmt->close();

            // Calculate the difference: (New Issued Qty - Original Issued Qty)
            // If positive, stock decreases. If negative, stock increases (returned).
            $qtyDifference = $newQty - $originalQty;

            // Step 2: Update the products table (total quantity)
            $updateProductStmt = $conn->prepare("UPDATE products SET qty = qty - ? WHERE id = ?");
            $updateProductStmt->bind_param("ii", $qtyDifference, $productId);
            if (!$updateProductStmt->execute()) {
                throw new Exception("Failed to update product total quantity.");
            }
            $updateProductStmt->close();

            // Step 2.5: Update the product_lots table (qty_available)
            // Subtracting the difference achieves the correct result for both increases and decreases.
            $updateLotStmt = $conn->prepare("UPDATE product_lots SET qty_available = qty_available - ? WHERE id = ?");
            $updateLotStmt->bind_param("ii", $qtyDifference, $productLotId);
            if (!$updateLotStmt->execute()) {
                throw new Exception("Failed to update product lot available quantity.");
            }
            $updateLotStmt->close();

            // Step 3: Update the stock_issues table with the new quantity and remarks
            $updateIssueStmt = $conn->prepare("UPDATE stock_issues SET qty_issued = ?, remarks = ? WHERE id = ?");
            $updateIssueStmt->bind_param("isi", $newQty, $newRemarks, $issueId);
            if (!$updateIssueStmt->execute()) {
                throw new Exception("Failed to update issue details.");
            }
            $updateIssueStmt->close();

            // Commit the transaction
            $conn->commit();
            $successMessage = "Issue updated successfully!";

            // Redirect back to the issue list with a success message
            header("Location: ../auth/dashboard.php?page=edit_issue&id={$issueId}&success=" . urlencode($successMessage));
            exit;

        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            $errorMessage = "Error updating issue: " . $e->getMessage();
            error_log($errorMessage);
            
            // Redirect back to the edit page with the error
            header("Location: ../auth/dashboard.php?page=edit_issue&id={$issueId}&error=" . urlencode($errorMessage));
            exit;
        }
    }
}

// Handle GET request to fetch issue data for the form
if (isset($_GET['id'])) {
    $issueId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($issueId) {
        // Query to get all necessary data for the form
        $stmt = $conn->prepare("
            SELECT 
                si.id, si.qty_issued, si.remarks,
                pl.id AS product_lot_id, pl.x_code, pl.qty_available,
                p.part_number,
                u.id AS user_id, u.name, u.nickname
            FROM stock_issues si
            JOIN product_lots pl ON si.product_lot_id = pl.id
            JOIN products p ON pl.product_id = p.id
            JOIN users u ON si.issued_to = u.id
            WHERE si.id = ?
        ");
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
        $result = $stmt->get_result();
        $issueData = $result->fetch_assoc();
        $stmt->close();

        if (!$issueData) {
            $errorMessage = "Issue not found.";
        }
    } else {
        $errorMessage = "Invalid issue ID.";
    }
} else {
    $errorMessage = "No issue ID provided.";
}

// Check for URL parameters errors from a failed redirect after POST submission
if (isset($_GET['error'])) {
    $errorMessage = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
}

include '../../design/views/manager/edit_issue_view.php';
