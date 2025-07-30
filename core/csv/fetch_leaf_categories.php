<?php
// core/stock/fetch_leaf_categories.php
require_once('../db/db.php');

$result = $conn->query("SELECT id, name FROM categories WHERE id NOT IN (SELECT DISTINCT parent_id FROM categories WHERE parent_id IS NOT NULL)");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
echo json_encode(['categories' => $categories]);
