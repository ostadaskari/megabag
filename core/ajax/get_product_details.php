<?php
// get_product_details.php
// This file fetches and returns a single product's details and associated images and PDFs as a JSON object.
// It includes robust error handling to prevent non-JSON output on failure.

header('Content-Type: application/json');

// Include database connection and session handling.
// This line might be the source of your error if the path is incorrect.
require_once '../db/db.php';
session_start();

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.',
    'product' => null,
    'images' => [],
    'pdfs' => []
];

try {
    // Check if the database connection is valid.
    if (!$conn) {
        throw new Exception("Database connection failed.");
    }

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $productId = (int)$_GET['id'];
        
        // Fetch product details
        $query = "SELECT products.*, users.nickname AS submitter, categories.name AS category_name
                  FROM products
                  LEFT JOIN users ON users.id = products.user_id
                  LEFT JOIN categories ON categories.id = products.category_id
                  WHERE products.id = ?
                  LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare product details query: " . $conn->error);
        }

        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $response['success'] = true;
            $response['message'] = 'Product details retrieved successfully.';
            $response['product'] = $product;
            
            // Fetch associated images and check for cover image
            $images_query = "SELECT file_path, file_name, is_cover FROM images WHERE product_id = ?";
            $images_stmt = $conn->prepare($images_query);
            if (!$images_stmt) {
                throw new Exception("Failed to prepare images query: " . $conn->error);
            }
            $images_stmt->bind_param("i", $productId);
            $images_stmt->execute();
            $images_result = $images_stmt->get_result();
            
            $all_images = [];
            $cover_image = null;
            while ($image = $images_result->fetch_assoc()) {
                if ($image['is_cover'] == 1) {
                    $cover_image = $image;
                } else {
                    $all_images[] = $image;
                }
            }
            $images_stmt->close();
            
            // Prioritize the cover image if one was found
            if ($cover_image) {
                array_unshift($all_images, $cover_image);
            }
            $response['images'] = $all_images;

            // Fetch associated PDFs
            $pdfs_query = "SELECT file_path, file_name FROM pdfs WHERE product_id = ?";
            $pdfs_stmt = $conn->prepare($pdfs_query);
            if (!$pdfs_stmt) {
                 throw new Exception("Failed to prepare PDFs query: " . $conn->error);
            }
            $pdfs_stmt->bind_param("i", $productId);
            $pdfs_stmt->execute();
            $pdfs_result = $pdfs_stmt->get_result();
            while ($pdf = $pdfs_result->fetch_assoc()) {
                $response['pdfs'][] = $pdf;
            }
            $pdfs_stmt->close();

        } else {
            $response['message'] = 'Product not found.';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Invalid product ID.';
    }
} catch (Exception $e) {
    // Catch any exceptions and provide a detailed error message in the JSON response.
    $response['success'] = false;
    $response['message'] = 'Server Error: ' . $e->getMessage();
}

echo json_encode($response);
if (isset($conn) && $conn) {
    $conn->close();
}
exit; // Ensure no other HTML is outputted after the JSON
?>