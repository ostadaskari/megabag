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

// SweetAlert variables
$errors = [];
$success = '';
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $errors[] = $_GET['error'];
}
// IF post method 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $products = $_POST['products'] ?? [];

    foreach ($products as $index => $item) {
        $product_lot_id = (int) ($item['product_lot_id'] ?? 0);
        $qty_issued = (int) ($item['qty_issued'] ?? 0);
        $issued_to = (int) ($item['issued_to_id'] ?? 0);
        $remarks = trim($item['remarks'] ?? '');

        if (!$product_lot_id || !$qty_issued || !$issued_to) {
            $errors[] = "Row #" . ($index + 1) . " is missing required fields.";
            continue;
        }

        // Check available stock in product lot
        $check = $conn->prepare("SELECT product_id, qty_available FROM product_lots WHERE id = ?");
        $check->bind_param("i", $product_lot_id);
        $check->execute();
        $res = $check->get_result()->fetch_assoc();

        if (!$res || $res['qty_available'] < $qty_issued) {
            $errors[] = "Not enough stock in row #" . ($index + 1) . ".";
            continue;
        }
        
        $product_id = $res['product_id'];

        // Insert stock issue log
        $stmt = $conn->prepare("INSERT INTO stock_issues (product_lot_id, user_id, issued_to, qty_issued, remarks) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiis", $product_lot_id, $user_id, $issued_to, $qty_issued, $remarks);
        $stmt->execute();

        // Update stock in product lots table
        $updateLot = $conn->prepare("UPDATE product_lots SET qty_available = qty_available - ? WHERE id = ?");
        $updateLot->bind_param("ii", $qty_issued, $product_lot_id);
        $updateLot->execute();

        // Update total stock in products table
        $updateProduct = $conn->prepare("UPDATE products SET qty = qty - ? WHERE id = ?");
        $updateProduct->bind_param("ii", $qty_issued, $product_id);
        $updateProduct->execute();
    }

    // Redirect to avoid resubmission
    if (empty($errors)) {
        header("Location: ../auth/dashboard.php?page=stock_issue&success=" . urlencode("Stock issued successfully."));
        exit;
    } else {
        header("Location: ../auth/dashboard.php?page=stock_issue&error=" . urlencode(implode(' | ', $errors)));
        exit;
    }
}

// Load view
include '../../design/views/manager/stock_issue_view.php';
