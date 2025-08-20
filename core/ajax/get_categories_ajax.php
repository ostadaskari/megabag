<?php
// Include your database connection file
require_once '../db/db.php';

header('Content-Type: application/json');

$query = $_GET['query'] ?? '';

try {
    // Sanitize the query to prevent SQL injection
    $query = '%' . $query . '%';

    // Prepare a SQL statement to search for categories
    $sql = "SELECT id, name, parent_id FROM categories WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode($categories);

} catch (Exception $e) {
    // Log the error and return an empty array or an error message
    error_log("Error fetching categories: " . $e->getMessage());
    echo json_encode([]);
}
?>
