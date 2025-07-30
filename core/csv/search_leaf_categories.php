<?php
require_once('../db/db.php'); // Adjust as needed

$term = $_GET['term'] ?? '';
$term = trim($term);

$response = ['categories' => []];

if (strlen($term) >= 1) {
    $stmt = $conn->prepare("
        SELECT id, name 
        FROM categories 
        WHERE id NOT IN (
            SELECT DISTINCT parent_id 
            FROM categories 
            WHERE parent_id IS NOT NULL
        ) AND name LIKE CONCAT('%', ?, '%')
        LIMIT 20
    ");
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response['categories'][] = $row;
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
