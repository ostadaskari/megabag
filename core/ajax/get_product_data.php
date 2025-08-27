<?php
// Start the session if it's not already active. This is crucial for maintaining state.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file. The path is relative.
require_once("../db/db.php");

// Set the content type to JSON. This tells the browser that the response will be a JSON object.
header('Content-Type: application/json');

// Check if the 'pn' (part number) parameter is present in the GET request and is not empty.
if (!isset($_GET['pn']) || empty($_GET['pn'])) {
    // If the part number is missing, return an error message and exit.
    echo json_encode(['status' => 'error', 'message' => 'Part number is missing. Please provide a valid part number.']);
    exit;
}

// Sanitize and trim the part number to prevent SQL injection and unnecessary whitespace.
$part_number = trim($_GET['pn']);

// Prepare a SQL statement to fetch product details along with its category name.
// We use a prepared statement to prevent SQL injection.
$stmt = $conn->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    INNER JOIN categories c ON p.category_id = c.id
    WHERE p.part_number = ?
");

// Check if the statement was prepared successfully.
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement for product details.']);
    exit;
}

// Bind the part number to the prepared statement. 's' indicates a string type.
$stmt->bind_param("s", $part_number);

// Execute the statement.
$stmt->execute();

// Get the result set from the executed statement.
$product_result = $stmt->get_result();

// Check if a product was found.
if ($product_result->num_rows > 0) {
    // Fetch the product data as an associative array.
    $product_data = $product_result->fetch_assoc();
    $product_id = $product_data['id'];

    // Initialize arrays to hold nested data.
    $features = [];
    $images = [];
    $pdfs = [];

    // --- Fetch Product Features ---
    // Prepare a statement to get all features for the found product.
    $features_stmt = $conn->prepare("SELECT feature_id, value, unit FROM product_feature_values WHERE product_id = ?");
    if ($features_stmt) {
        $features_stmt->bind_param("i", $product_id); // 'i' indicates an integer type.
        $features_stmt->execute();
        $features_result = $features_stmt->get_result();
        // Loop through the results and add each feature to the features array.
        while ($row = $features_result->fetch_assoc()) {
            $features[] = $row;
        }
        $features_stmt->close();
    }

    // --- Fetch Product Images ---
    // Prepare a statement to get all images for the found product.
    $images_stmt = $conn->prepare("SELECT file_name, is_cover FROM images WHERE product_id = ?");
    if ($images_stmt) {
        $images_stmt->bind_param("i", $product_id);
        $images_stmt->execute();
        $images_result = $images_stmt->get_result();
        // Loop through the results and add each image to the images array.
        while ($row = $images_result->fetch_assoc()) {
            $images[] = $row;
        }
        $images_stmt->close();
    }

    // --- Fetch Product PDFs ---
    // Prepare a statement to get all PDFs for the found product.
    $pdfs_stmt = $conn->prepare("SELECT file_name FROM pdfs WHERE product_id = ?");
    if ($pdfs_stmt) {
        $pdfs_stmt->bind_param("i", $product_id);
        $pdfs_stmt->execute();
        $pdfs_result = $pdfs_stmt->get_result();
        // Loop through the results and add each PDF to the pdfs array.
        while ($row = $pdfs_result->fetch_assoc()) {
            $pdfs[] = $row;
        }
        $pdfs_stmt->close();
    }

    // Add the fetched nested data arrays to the main product data array.
    $product_data['features'] = $features;
    $product_data['images'] = $images;
    $product_data['pdfs'] = $pdfs;

    // Return the complete product data as a successful JSON response.
    echo json_encode(['status' => 'success', 'data' => $product_data]);
} else {
    // If no product was found with the given part number, return a 'not_found' status.
    echo json_encode(['status' => 'not_found', 'message' => 'Product not found.']);
}

// Close the main statement and the database connection to free up resources.
$stmt->close();
$conn->close();
