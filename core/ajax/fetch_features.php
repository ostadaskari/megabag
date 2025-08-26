<?php
header('Content-Type: application/json');

// Include your database connection file. This file creates a global $conn variable.
require_once("../db/db.php");

// If the connection fails, return an error and exit
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get the category ID from the GET request and ensure it's a valid integer
$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// If no category ID is provided or it's invalid, return an error
if ($categoryId === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Category ID not provided.']);
    exit;
}

$allFeatures = [];
$processedFeatureIds = [];
$currentCategoryId = $categoryId;

try {
    // We will use a loop to traverse up the category tree from the selected category
    // to its root parent, fetching features at each level.
    do {
        // Prepare and execute the query to get features for the current category,
        // now including the `unit` column from the features table.
        $stmtFeatures = $conn->prepare("
            SELECT id, name, data_type, unit, is_required FROM features WHERE category_id = ?
        ");
        $stmtFeatures->bind_param('i', $currentCategoryId);
        $stmtFeatures->execute();
        $result = $stmtFeatures->get_result();
        
        $features = [];
        while ($row = $result->fetch_assoc()) {
            $features[] = $row;
        }

        // Add fetched features to our main array, avoiding duplicates
        foreach ($features as $feature) {
            // Check if we've already added this feature to avoid duplicates
            if (!in_array($feature['id'], $processedFeatureIds)) {
                // Map the data_type from the database to an appropriate HTML input type
                $inputType = 'text'; // Default to text
                switch ($feature['data_type']) {
                    case 'decimal(12,3)':
                        $inputType = 'number';
                        break;
                    case 'TEXT':
                        $inputType = 'textarea';
                        break;
                    case 'boolean':
                        $inputType = 'checkbox';
                        break;
                    case 'varchar(50)':
                    default:
                        $inputType = 'text';
                        break;
                }
                
                $allFeatures[] = [
                    'id' => $feature['id'],
                    'name' => $feature['name'],
                    'input_type' => $inputType,
                    'unit' => $feature['unit'],
                    'is_required' => (bool)$feature['is_required']
                ];
                $processedFeatureIds[] = $feature['id'];
            }
        }

        // Free the result set and close the statement for features
        $result->free();
        $stmtFeatures->close();

        // Prepare and execute the query to get the parent ID of the current category
        $stmtParent = $conn->prepare("SELECT parent_id FROM categories WHERE id = ?");
        $stmtParent->bind_param('i', $currentCategoryId);
        $stmtParent->execute();
        $resultParent = $stmtParent->get_result();
        $parentCategory = $resultParent->fetch_assoc();
        
        // If a parent exists, update the current category ID for the next loop iteration.
        // If not, the loop will terminate.
        if ($parentCategory && $parentCategory['parent_id'] !== null) {
            $currentCategoryId = $parentCategory['parent_id'];
        } else {
            $currentCategoryId = 0; // Set to 0 to exit the loop
        }

        // Free the result set and close the statement for parents
        $resultParent->free();
        $stmtParent->close();

    } while ($currentCategoryId > 0);

    // Return the final list of features as a JSON response
    echo json_encode($allFeatures);

} catch (Exception $e) {
    // Handle any unexpected errors
    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
} finally {
    // Ensure the database connection is closed
    if ($conn) {
        $conn->close();
    }
}
?>
