<?php
require_once '../db/db.php';

header('Content-Type: application/json');

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT qty FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    echo json_encode(['qty' => $res['qty'] ?? 0]);
} else {
    echo json_encode(['qty' => 0]);
}
