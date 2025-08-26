<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../db/db.php");

header('Content-Type: application/json');

// Check for the part number in the GET request
if (!isset($_GET['pn']) || empty($_GET['pn'])) {
    echo json_encode(['status' => 'error', 'message' => 'Part number is missing.']);
    exit;
}

$part_number = trim($_GET['pn']);

// Prepare a statement to get product details, including the category name
// We join the 'products' table with the 'categories' table on their respective IDs.
$stmt = $conn->prepare("
    SELECT p.*, c.name AS category_name 
    FROM products p
    INNER JOIN categories c ON p.category_id = c.id
    WHERE p.part_number = ?
");
$stmt->bind_param("s", $part_number);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows > 0) {
    $product_data = $product_result->fetch_assoc();
    $product_id = $product_data['id'];

    // Fetch product features
    $features_stmt = $conn->prepare("SELECT feature_id, value, unit FROM product_feature_values WHERE product_id = ?");
    $features_stmt->bind_param("i", $product_id);
    $features_stmt->execute();
    $features_result = $features_stmt->get_result();
    $features = [];
    while ($row = $features_result->fetch_assoc()) {
        $features[] = $row;
    }
    $product_data['features'] = $features;
    $features_stmt->close();
    
    // Fetch product images
    $images_stmt = $conn->prepare("SELECT file_name, is_cover FROM images WHERE product_id = ?");
    $images_stmt->bind_param("i", $product_id);
    $images_stmt->execute();
    $images_result = $images_stmt->get_result();
    $images = [];
    while ($row = $images_result->fetch_assoc()) {
        $images[] = $row;
    }
    $product_data['images'] = $images;
    $images_stmt->close();
    
    // Fetch product pdfs
    $pdfs_stmt = $conn->prepare("SELECT file_name FROM pdfs WHERE product_id = ?");
    $pdfs_stmt->bind_param("i", $product_id);
    $pdfs_stmt->execute();
    $pdfs_result = $pdfs_stmt->get_result();
    $pdfs = [];
    while ($row = $pdfs_result->fetch_assoc()) {
        $pdfs[] = $row;
    }
    $product_data['pdfs'] = $pdfs;
    $pdfs_stmt->close();

    echo json_encode(['status' => 'success', 'data' => $product_data]);
} else {
    // Product not found
    echo json_encode(['status' => 'not_found', 'message' => 'Product not found.']);
}

$stmt->close();
$conn->close();
