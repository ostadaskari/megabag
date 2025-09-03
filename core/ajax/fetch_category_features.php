<?php
/**
 * Fetches features for a given category, its parent categories, and the values for a specific product.
 */
header('Content-Type: application/json');

// Start output buffering to catch any unexpected output.
ob_start();

// Include your database connection file.
// IMPORTANT: Ensure this file contains logic to handle connection errors and doesn't output anything.
require_once("../db/db.php");

// End output buffering and clear any content that was unexpectedly sent before the JSON.
ob_end_clean();

/**
 * A helper function to handle and log errors consistently.
 */
function handle_error($conn, $message, $exit = true) {
    error_log("fetch_category_features.php error: " . $message);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $message]);
    if ($conn && $conn->ping()) {
        $conn->close();
    }
    if ($exit) {
        exit();
    }
}

// --- Input Validation ---
if (!isset($_REQUEST['category_id']) || !is_numeric($_REQUEST['category_id'])) {
    handle_error($conn, "Invalid or missing category_id.");
}
$categoryId = (int)$_REQUEST['category_id'];

// Check if a product ID is provided. If so, validate it.
$productId = null;
if (isset($_REQUEST['product_id']) && is_numeric($_REQUEST['product_id'])) {
    $productId = (int)$_REQUEST['product_id'];
}

// --- Get Category and Parent IDs ---
$categoryIds = [$categoryId];
$currentCategoryId = $categoryId;

try {
    while ($currentCategoryId !== null) {
        if ($stmt = $conn->prepare("SELECT parent_id FROM categories WHERE id = ?")) {
            $stmt->bind_param("i", $currentCategoryId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row && isset($row['parent_id'])) {
                $categoryIds[] = (int)$row['parent_id'];
                $currentCategoryId = (int)$row['parent_id'];
            } else {
                $currentCategoryId = null;
            }
        } else {
            handle_error($conn, "Failed to prepare statement for parent categories: " . $conn->error);
        }
    }
} catch (Exception $e) {
    handle_error($conn, "An unexpected error occurred while retrieving parent categories: " . $e->getMessage());
}

// --- Fetch Features and their Product-specific Values ---
// We'll use a prepared statement to safely handle the dynamic list of IDs in the IN clause.
$placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
$types = str_repeat('i', count($categoryIds));

$sql = "
    SELECT
        f.id AS feature_id,
        f.name AS feature_name,
        f.data_type,
        f.unit AS feature_unit,
        f.is_required,
        f.metadata,
        pfv.value
    FROM
        features f
    LEFT JOIN
        product_feature_values pfv ON f.id = pfv.feature_id AND pfv.product_id = ?
    WHERE
        f.category_id IN ($placeholders)
    ORDER BY
        f.name ASC;
";

try {
    if ($stmt = $conn->prepare($sql)) {
        // First param type string: product_id + category IDs
        $bindTypes = 'i' . str_repeat('i', count($categoryIds));
        $bindParams = array_merge([$bindTypes], [$productId], $categoryIds);

        // Reference trick for call_user_func_array
        $refs = [];
        foreach ($bindParams as $key => $value) {
            $refs[$key] = &$bindParams[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $features = [];
            while ($row = $result->fetch_assoc()) {
                
                // Decode metadata if it exists
                $metadata = $row['metadata'] ? json_decode($row['metadata'], true) : null;
                
                // Get the units from metadata if available, otherwise from the unit column
                $units = [];
                if (isset($metadata['units']) && is_array($metadata['units'])) {
                    $units = $metadata['units'];
                } else if (!empty($row['feature_unit'])) {
                    $units = array_map('trim', explode(',', $row['feature_unit']));
                }
                
                $features[] = [
                    'id' => $row['feature_id'],
                    'name' => $row['feature_name'],
                    'data_type' => $row['data_type'],
                    'value' => $row['value'], // Send raw JSON string to front-end for parsing
                    'unit' => $units, // Send units as an array
                    'is_required' => (bool)$row['is_required'],
                    'metadata' => $metadata
                ];
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'features' => $features
            ]);

            $result->free();
        } else {
            handle_error($conn, "Query execution failed: " . $stmt->error);
        }
        $stmt->close();
    } else {
        handle_error($conn, "Query preparation failed: " . $conn->error);
    }
} catch (Exception $e) {
    handle_error($conn, "An unexpected error occurred: " . $e->getMessage());
}


// Close the database connection.
$conn->close();
?>