<?php
require_once('../db/db.php');
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$rows = $data['rows'] ?? [];
$csv_id = (int) ($data['csv_id'] ?? 0);
$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id || !$csv_id || empty($rows)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$conn->begin_transaction();

try {
    foreach ($rows as $row) {
        $part = trim($row['part_number']);
        $qty = (int) $row['qty'];
        $name = trim($row['name']);
        $tag = trim($row['tag']);
        $remark = trim($row['remark']);
        $category_id = isset($row['category_id']) ? (int)$row['category_id'] : null;

        // Check if product exists
        $stmt = $conn->prepare("SELECT id FROM products WHERE part_number = ?");
        $stmt->bind_param("s", $part);
        $stmt->execute();
        $stmt->bind_result($product_id);
        $exists = $stmt->fetch();
        $stmt->close();

        if ($exists) {
            // Update product quantity
            $stmt = $conn->prepare("UPDATE products SET qty = qty + ? WHERE id = ?");
            $stmt->bind_param("ii", $qty, $product_id);
            $stmt->execute();
            $stmt->close();

            // Log receipt
            $stmt = $conn->prepare("INSERT INTO stock_receipts (product_id, user_id, qty_received, remarks) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $product_id, $user_id, $qty, $remark);
            $stmt->execute();
            $stmt->close();
        } else {
            // New product must have category_id
            if (!$category_id) {
                throw new Exception("Missing category for new product: $part");
            }

            // Insert new product
            $stmt = $conn->prepare("INSERT INTO products (name, user_id, part_number, tag, qty, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissii", $name, $user_id, $part, $tag, $qty, $category_id);
            $stmt->execute();
            $new_product_id = $stmt->insert_id;
            $stmt->close();

            // Log receipt
            $stmt = $conn->prepare("INSERT INTO stock_receipts (product_id, user_id, qty_received, remarks) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $new_product_id, $user_id, $qty, $remark);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Update CSV status
    $stmt = $conn->prepare("UPDATE uploaded_csvs SET status = 'processed' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $csv_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
}
