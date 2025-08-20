<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../db/db.php");

// (ACL) Restrict access to admin/manager
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header("Location: ../auth/login.php");
    exit;
}

// AJAX: search products
if (isset($_GET['search_product'])) {
    $term = '%' . $conn->real_escape_string($_GET['search_product']) . '%';
    $sql = "SELECT id, name, part_number, tag FROM products WHERE name LIKE ? OR tag LIKE ? OR part_number LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $term,$term, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($products);
    exit;
}

// AJAX: get features for product
if (isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];

    // Get category of product
    $sql = "SELECT category_id FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($category_id);
    $stmt->fetch();
    $stmt->close();

    if (!$category_id) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Product has no category']);
        exit;
    }

    // Recursive get all parent categories
    function getCategoryTree($conn, $category_id, &$categories)
    {
        $categories[] = $category_id;
        $sql = "SELECT parent_id FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();

        // Fix: Initialize the variable to null to prevent the "unassigned variable" warning
        $parent_id = null;
        $stmt->bind_result($parent_id);

        $has_parent = $stmt->fetch() && $parent_id;

        // Corrected: Close the statement immediately after use
        $stmt->close();

        if ($has_parent) {
            getCategoryTree($conn, $parent_id, $categories);
        }
    }

    $categories = [];
    getCategoryTree($conn, $category_id, $categories);

    // Get features from all categories (child + parents)
    $placeholders = implode(',', array_fill(0, count($categories), '?'));
    $types = str_repeat('i', count($categories));

    // NOTE: The table name in your view is `product_features`, but in your core script it's `features`.
    // I'm using `features` to match the provided PHP code. Please ensure this is the correct table.
    $sql = "SELECT id, name, data_type, unit, is_required FROM features WHERE category_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$categories);
    $stmt->execute();
    $result = $stmt->get_result();
    $features = [];
    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }
    $stmt->close();

    // Also get existing feature values for the product to pre-fill the form
    $sql = "SELECT feature_id, value, unit FROM product_feature_values WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_values = [];
    while ($row = $result->fetch_assoc()) {
        $existing_values[$row['feature_id']] = ['value' => $row['value'], 'unit' => $row['unit']];
    }
    $stmt->close();

    // Merge features with existing values
    $features_with_values = [];
    foreach ($features as $feature) {
        $feature['value'] = $existing_values[$feature['id']]['value'] ?? '';
        $feature['unit_value'] = $existing_values[$feature['id']]['unit'] ?? '';
        $features_with_values[] = $feature;
    }

    header('Content-Type: application/json');
    echo json_encode($features_with_values);
    exit;
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Set header for JSON response

    // Basic product ID validation
    $product_id = (int)$_POST['product_id'];
    if (!$product_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
        exit;
    }

    $features_data = $_POST['features'] ?? [];
    
    // Authorization Check: Does the user have permission to modify this product?
    // For now, the page-level ACL is sufficient.
    
    foreach ($features_data as $feature_id => $value_data) {
        $value = trim($value_data['value'] ?? '');
        $unit = trim($value_data['unit'] ?? '');

        // Server-side validation for required fields
        $sql_check = "SELECT is_required, data_type FROM features WHERE id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $feature_id);
        $stmt_check->execute();
        $stmt_check->bind_result($is_required, $data_type);
        $stmt_check->fetch();
        $stmt_check->close();

        // Check if a required field is empty
        if ($is_required && empty($value)) {
            // For boolean types, empty value is valid if it's not checked
            if ($data_type !== 'boolean' || $value !== '0') {
                echo json_encode(['status' => 'error', 'message' => 'A required field was left empty.']);
                exit;
            }
        }
        
        // If the value is empty and not required, we should delete any existing entry
        // and move to the next feature. This correctly "clears" the value.
        if (!$is_required && empty($value)) {
             $stmt = $conn->prepare("DELETE FROM product_feature_values WHERE product_id = ? AND feature_id = ?");
             $stmt->bind_param("ii", $product_id, $feature_id);
             $stmt->execute();
             $stmt->close();
             continue; // Move to the next feature in the loop
        }

        // Server-side data type validation and sanitization
        $value_to_save = $value;
        switch ($data_type) {
            case 'decimal(12,3)':
                // Sanitize and validate for a float number, but only if not empty
                if (!empty($value)) {
                    $value_to_save = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    // The core issue was here: is_numeric('') is false. We now handle the empty case above.
                    if ($value_to_save === false || !is_numeric($value_to_save)) {
                        echo json_encode(['status' => 'error', 'message' => 'Value for a decimal field is not a valid number.']);
                        exit;
                    }
                }
                break;
            case 'TEXT':
                // For TEXT, just sanitize the string
                $value_to_save = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                break;
            case 'boolean':
                // We expect '1' or '0' for a checkbox.
                if ($value !== '1' && $value !== '0') {
                    echo json_encode(['status' => 'error', 'message' => 'Value for a boolean field is not valid.']);
                    exit;
                }
                break;
            case 'varchar(50)':
            default:
                // For varchar(50), sanitize and truncate to 50 characters.
                $value_to_save = substr(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), 0, 50);
                break;
        }

        // Save the validated data using REPLACE INTO
        // This will insert a new row or replace an existing one based on the primary key (product_id, feature_id)
        $stmt = $conn->prepare("REPLACE INTO product_feature_values (product_id, feature_id, value, unit) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $product_id, $feature_id, $value_to_save, $unit);
        $stmt->execute();
        $stmt->close();
    }

    // On success, send a success JSON response
    echo json_encode(['status' => 'success', 'message' => 'Product features saved successfully']);
    exit;
}

// This line should be at the end, so it only runs if no other AJAX call or POST has exited
include("../../design/views/manager/product_feature_values_view.php");
