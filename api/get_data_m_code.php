<?php

// Set the Content-Type header to application/json to ensure the browser knows to expect JSON data.
header('Content-Type: application/json');

// Include your database connection file.
require_once('../core/db/db.php');

// Define the API response structure.
$response = [
    'success' => false,
    'message' => 'An unknown error occurred.',
    'data' => null
];

// Check if the 'm_code' parameter is present in the request.
// Using $_GET is common for read-only requests, but you could also use $_POST for more security.
if (isset($_GET['m'])) {
    $m_code = trim($_GET['m']);

    // Check if the m_code is not empty.
    if (!empty($m_code)) {
        // Prepare a secure SQL query to prevent SQL injection. We join the two tables on product_id.
        $sql = "SELECT pl.*, p.*
                FROM product_lots pl
                JOIN products p ON pl.product_id = p.id
                WHERE pl.x_code = ? LIMIT 1";

        // Use a try-catch block to handle database errors gracefully.
        try {
            // Prepare the statement.
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("SQL statement preparation failed: " . $conn->error);
            }

            // Bind the m_code parameter to the prepared statement. The 's' indicates a string.
            $stmt->bind_param("s", $m_code);

            // Execute the statement.
            if ($stmt->execute()) {
                // Get the result set from the executed statement.
                $result = $stmt->get_result();

                // Check if a row was found.
                if ($result->num_rows > 0) {
                    // Fetch the single row as an associative array.
                    $row = $result->fetch_assoc();
                    $response['success'] = true;
                    $response['message'] = 'Data retrieved successfully.';
                    $response['data'] = $row;
                } else {
                    $response['message'] = 'No product found with that m_code.';
                }
            } else {
                throw new Exception("Statement execution failed: " . $stmt->error);
            }

            // Close the statement to free up resources.
            $stmt->close();
        } catch (Exception $e) {
            // Log the error and set a generic message for the user.
            error_log($e->getMessage());
            $response['message'] = 'An internal server error occurred.';
        }
    } else {
        $response['message'] = 'm_code cannot be empty.';
    }
} else {
    $response['message'] = 'm_code parameter is missing.';
}

// Close the database connection.
$conn->close();

// Encode the response array into a JSON string and send it.
echo json_encode($response);
