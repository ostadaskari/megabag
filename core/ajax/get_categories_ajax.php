<?php
require_once("../db/db.php");

header('Content-Type: application/json');

// Check if a search query is provided
$searchTerm = $_GET['query'] ?? '';

// Sanitize the search term to prevent SQL injection
$searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";

// Prepare the SQL query to search for categories
$stmt = $conn->prepare("SELECT id, name, parent_id FROM categories WHERE name LIKE ? ORDER BY name ASC");
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all results into an associative array
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Return the categories as a JSON response
echo json_encode($categories);

$stmt->close();
$conn->close();

exit;