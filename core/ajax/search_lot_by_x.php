<?php
require_once '../db/db.php';

header('Content-Type: application/json');

$keyword = $_GET['keyword'] ?? '';
$data = [];

if ($keyword) {
    $stmt = $conn->prepare("
        SELECT pl.id, pl.x_code, pl.qty_available, p.part_number
        FROM product_lots pl
        JOIN products p ON pl.product_id = p.id
        WHERE pl.x_code LIKE ? AND pl.qty_available > 0
    ");
    $search = "%" . $keyword . "%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'x_code' => $row['x_code'],
            'part_number' => $row['part_number'],
            'qty_available' => $row['qty_available'],
        ];
    }
}

echo json_encode($data);