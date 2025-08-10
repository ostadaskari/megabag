<?php
session_start();
require_once("../db/db.php");

// Authorization check
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Corrected: Use 'query' to match the front-end request
$query = trim($_GET['query'] ?? '');
if ($query === '') {
    echo json_encode([]);
    exit;
}

// Search both parent and leaf categories
$stmt = $conn->prepare("
    SELECT id, name, parent_id
    FROM categories
    WHERE name LIKE CONCAT('%', ?, '%')
    ORDER BY name ASC
    LIMIT 20
");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'is_parent' => $row['parent_id'] === null ? 1 : 0
    ];
}

echo json_encode($categories);
$stmt->close();
$conn->close();