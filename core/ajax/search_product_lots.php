<?php

// A global try-catch block to ensure a JSON response is always returned,
// even if a fatal error occurs.
try {
    // Include the database connection file
    require_once('../db/db.php');

    // Set the content type header for JSON
    header('Content-Type: application/json');

    // Get the keyword from the GET request
    $keyword = $_GET['keyword'] ?? '';
    $products = []; // Initialize an empty array for the results

    // Proceed with the database search only if the keyword is at least 2 characters long
    if (strlen($keyword) >= 2) {
        // Search for product lots by part number, product tag, x-code, or vrm-x-code
        $stmt = $conn->prepare("
            SELECT 
                pl.id AS lot_id, 
                pl.x_code,
                pl.vrm_x_code,
                pl.qty_available,
                p.tag AS product_tag,
                p.part_number
            FROM product_lots pl
            JOIN products p ON pl.product_id = p.id
            WHERE 
                p.part_number LIKE ? OR
                p.tag LIKE ? OR
                pl.x_code LIKE ? OR
                pl.vrm_x_code LIKE ?
            LIMIT 10
        ");

        // Check if the prepare statement failed and throw an exception
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }

        $searchKeyword = '%' . $keyword . '%';
        $stmt->bind_param("ssss", $searchKeyword, $searchKeyword, $searchKeyword, $searchKeyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    // Echo the final JSON-encoded products array. This will be an empty array if
    // the keyword was too short or no results were found.
    echo json_encode($products);
    
} catch (Exception $e) {
    // If an exception occurs, set a 500 Internal Server Error status code
    // and return a JSON object with the error message.
    http_response_code(500);
    echo json_encode(['error' => 'An internal server error occurred: ' . $e->getMessage()]);
} finally {
    // Ensure the database connection is always closed
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
