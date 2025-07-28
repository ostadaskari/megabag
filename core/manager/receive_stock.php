<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $qty_received = intval($_POST['qty']);
    $remarks = trim($_POST['remarks']);
    $user_id = $_SESSION['user_id'];

    if ($qty_received <= 0) {
        $errors[] = "Quantity must be greater than zero.";
    }

    $stmt = $conn->prepare("SELECT qty FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $errors[] = "Product not found.";
    }

    if (empty($errors)) {
        $new_qty = $product['qty'] + $qty_received;

        // Update quantity in products table
        $stmt = $conn->prepare("UPDATE products SET qty = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $new_qty, $product_id);
        $stmt->execute();
        $stmt->close();

        // Insert into stock_receipts
        $stmt = $conn->prepare("INSERT INTO stock_receipts (product_id, user_id, qty_received, remarks) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $user_id, $qty_received, $remarks);
        $stmt->execute();
        $stmt->close();

        $success = "Stock received successfully.";
    }
}

require_once '../../design/views/manager/receive_stock_view.php';
