<?php
require_once '../db/db.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$results = [];

if ($keyword !== '') {
    $stmt = $conn->prepare("SELECT id, part_number, tag FROM products 
                            WHERE part_number LIKE CONCAT('%', ?, '%') 
                               OR tag LIKE CONCAT('%', ?, '%') 
                            LIMIT 10");
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($results);
