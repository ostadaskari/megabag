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
    SELECT id
    FROM products p
    WHERE part_number = ?
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
    

    // Return the complete product data as a successful JSON response.
    echo json_encode(['status' => 'success', 'data' => $product_data]);
} else {
    // If no product was found with the given part number, return a 'not_found' status.
    echo json_encode(['status' => 'not_found', 'message' => 'Product not found.']);
}

// Close the main statement and the database connection to free up resources.
$stmt->close();
$conn->close();
