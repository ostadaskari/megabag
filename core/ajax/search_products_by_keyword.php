<?php
require_once '../db/db.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$results = [];

if ($keyword !== '') {
    $stmt = $conn->prepare("SELECT id, name, part_number, tag FROM products 
                            WHERE name LIKE CONCAT('%', ?, '%') 
                               OR part_number LIKE CONCAT('%', ?, '%') 
                               OR tag LIKE CONCAT('%', ?, '%') 
                            LIMIT 10");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($results);
