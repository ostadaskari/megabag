<?php
require_once '../db/db.php';

header('Content-Type: application/json');

$data = [];

// Check for an exact keyword first for the blur event
if (isset($_GET['exact_keyword']) && !empty($_GET['exact_keyword'])) {
    $keyword = $_GET['exact_keyword'];
    $stmt = $conn->prepare("
        SELECT pl.id, pl.x_code, pl.qty_available, p.part_number, pl.lock, pl.project_name
        FROM product_lots pl
        JOIN products p ON pl.product_id = p.id
        WHERE pl.x_code = ? AND pl.qty_available > 0
    ");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'x_code' => $row['x_code'],
            'part_number' => $row['part_number'],
            'qty_available' => $row['qty_available'],
            'lock' => $row['lock'],
            'project_name' => $row['project_name'],
        ];
    }
}
// Fall back to a fuzzy search for the input event
elseif (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $stmt = $conn->prepare("
        SELECT pl.id, pl.x_code, pl.qty_available, p.part_number, pl.lock, pl.project_name
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
            'lock' => $row['lock'],
            'project_name' => $row['project_name'],
        ];
    }
}

echo json_encode($data);
