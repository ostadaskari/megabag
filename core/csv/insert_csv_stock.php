<?php 

require_once('../db/db.php');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$rows = $data['rows'];

foreach ($rows as $row) {
    $part = trim($row['part_number']);
    $qty = (int) $row['qty'];
    $name = trim($row['name']);
    $tag = trim($row['tag']);
    $remark = trim($row['remark']);
    $category_id = isset($row['category_id']) ? (int)$row['category_id'] : null;

    // Check if product already exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE part_number = ?");
    $stmt->bind_param("s", $part);
    $stmt->execute();
    $stmt->bind_result($pid);
    $exists = $stmt->fetch();
    $stmt->close();

    if ($exists) {
        // Update existing product's qty
        $conn->query("UPDATE products SET qty = qty + $qty WHERE id = $pid");

        // Log into stock_receipts
        $stmt = $conn->prepare("INSERT INTO stock_receipts (product_id, user_id, qty_received, remarks) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $pid, $_SESSION['user_id'], $qty, $remark);
        $stmt->execute();
    } else {
        // Insert new product
        $stmt = $conn->prepare("INSERT INTO products (name, part_number, tag, qty, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $name, $part, $tag, $qty, $category_id);
        $stmt->execute();
        $newId = $stmt->insert_id;

        // Log into stock_receipts
        $stmt = $conn->prepare("INSERT INTO stock_receipts (product_id, user_id, qty_received, remarks) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $newId, $_SESSION['user_id'], $qty, $remark);
        $stmt->execute();
    }
}

echo json_encode(['success' => true]);
