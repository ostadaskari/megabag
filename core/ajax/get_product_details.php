<?php
// get_product_details.php
// This file fetches and returns a single product's details and associated images, PDFs, and features as a JSON object.
// It includes robust error handling to prevent non-JSON output on failure.

header('Content-Type: application/json');

// Include database connection and session handling.
require_once '../db/db.php';
session_start();

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.',
    'product' => null,
    'images' => [],
    'pdfs' => [],
    'features' => []
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
            
            // Fetch associated images
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

            // Fetch product features
            $features_query = "SELECT 
                                   f.name,
                                   f.data_type, 
                                   pfv.value
                               FROM product_feature_values pfv
                               JOIN features f ON pfv.feature_id = f.id
                               WHERE pfv.product_id = ?";

            $features_stmt = $conn->prepare($features_query);
            if (!$features_stmt) {
                throw new Exception("Failed to prepare features query: " . $conn->error);
            }
            $features_stmt->bind_param("i", $productId);
            $features_stmt->execute();
            $features_result = $features_stmt->get_result();
            
            while ($feature = $features_result->fetch_assoc()) {
                $decoded_json = json_decode($feature['value'], true);
                
                $feature_value = null;
                $feature_unit = null;

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_json)) {
                    
                    if ($feature['data_type'] === 'range') {
                        // Handle range type (min/max)
                        $feature_value = isset($decoded_json['min'], $decoded_json['max']) 
                                       ? $decoded_json['min'] . ' to ' . $decoded_json['max'] 
                                       : 'N/A';
                    } elseif ($feature['data_type'] === 'boolean') {
                        // NEW: Handle boolean type
                        // We send a true boolean value for easier handling in JavaScript
                        $feature_value = isset($decoded_json['value']) ? (bool)$decoded_json['value'] : false;
                    } elseif ($feature['data_type'] === 'multiselect') {
                        // NEW: Handle multiselect type
                        // We look for the 'values' key which contains an array
                        $feature_value = isset($decoded_json['values']) && is_array($decoded_json['values']) 
                                       ? $decoded_json['values'] 
                                       : []; // Return an empty array if not found
                    } else {
                        // Default handler for other types (text, number) that use the 'value' key
                        $feature_value = $decoded_json['value'] ?? null;
                    }

                    // Universal unit handler
                    if (isset($decoded_json['unit'])) {
                        $feature_unit = $decoded_json['unit'];
                    }
                } else {
                    // Fallback to the original value if JSON decoding fails
                    $feature_value = $feature['value'];
                }

                $response['features'][] = [
                    'name' => $feature['name'],
                    'value' => $feature_value,
                    'unit' => $feature_unit
                ];
            }
            $features_stmt->close();

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
    // Optional: Log the error to a file for debugging instead of showing it to the user.
    // error_log($e->getMessage());
}

if (isset($conn) && $conn) {
    $conn->close();
}

echo json_encode($response);
exit; // Ensure no other output
?>