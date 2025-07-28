<?php
require_once '../db/db.php';

header('Content-Type: application/json');

$keyword = $_GET['keyword'] ?? '';

if (strlen($keyword) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id, name, family, nickname 
    FROM users 
    WHERE name LIKE CONCAT('%', ?, '%') 
       OR family LIKE CONCAT('%', ?, '%') 
       OR nickname LIKE CONCAT('%', ?, '%') 
    LIMIT 10
");
$stmt->bind_param("sss", $keyword, $keyword, $keyword);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'name' => $row['name'] . ' ' . $row['family'],
        'nickname' => $row['nickname']
    ];
}

echo json_encode($data);
