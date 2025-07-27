<?php
require_once '../db/db.php';


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch by ID (for preloading)
if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

// Fetch leaf categories only
$sql = "SELECT c1.id, c1.name
        FROM categories c1
        LEFT JOIN categories c2 ON c2.parent_id = c1.id
        WHERE c2.id IS NULL";

$params = [];
$types = '';

// Add search condition if needed
if ($search !== '') {
    $sql .= " AND c1.name LIKE ?";
    $params[] = '%' . $search . '%';
    $types .= 's';
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>

