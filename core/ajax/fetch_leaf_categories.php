<?php
require_once '../db/db.php';

header('Content-Type: application/json');

$stmt = $conn->prepare("
    SELECT c1.id, c1.name
    FROM categories c1
    LEFT JOIN categories c2 ON c1.id = c2.parent_id
    WHERE c2.id IS NULL
");
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($categories);
