<?php
require_once '../db/db.php';
session_start();

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products']) && is_array($_POST['products'])) {
    $products = $_POST['products'];
    $user_id = $_SESSION['user_id'];
    $now = date('Y-m-d H:i:s');

    foreach ($products as $index => $product) {
        $product_id = isset($product['product_id']) ? (int) $product['product_id'] : 0;
        $quantity = isset($product['qty_received']) ? (int) $product['qty_received'] : 0;
        $notes = trim($product['remarks'] ?? '');

        if ($product_id <= 0) {
            $errors[] = "Row " . ($index + 1) . ": Invalid product selection.";
            continue;
        }

        if ($quantity <= 0) {
            $errors[] = "Row " . ($index + 1) . ": Quantity must be greater than 0.";
            continue;
        }

        // Insert into stock_receipts
        $stmt = $conn->prepare("INSERT INTO stock_receipts (product_id, user_id, qty_received, remarks, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $product_id, $user_id, $quantity, $notes, $now);
        if (!$stmt->execute()) {
            $errors[] = "Row " . ($index + 1) . ": Failed to insert into stock_receipts.";
            continue;
        }

        // Update product quantity
        $update = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
        $update->bind_param("ii", $quantity, $product_id);
        if (!$update->execute()) {
            $errors[] = "Row " . ($index + 1) . ": Failed to update product stock.";
            continue;
        }
    }

    if (empty($errors)) {
        $success = "Stock received successfully.";
    }
}

// Load view
include '../../design/views/manager/receive_stock_view.php';
