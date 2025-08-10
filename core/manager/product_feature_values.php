<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../db/db.php");

// (ACL) Restrict access to admin/manager
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','manager'])) {
    header("Location: ../auth/login.php");
    exit;
}

// AJAX: search products
if (isset($_GET['search_product'])) {
    $term = '%' . $conn->real_escape_string($_GET['search_product']) . '%';
    $sql = "SELECT id, name FROM products WHERE name LIKE ? OR part_number LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $term, $term);
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
    $product_id = (int) $_GET['product_id'];

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
    function getCategoryTree($conn, $category_id, &$categories) {
        $categories[] = $category_id;
        $sql = "SELECT parent_id FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
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

    $sql = "SELECT id, name, data_type, unit, is_required
            FROM features
            WHERE category_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$categories);
    $stmt->execute();
    $result = $stmt->get_result();
    $features = [];
    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($features);
    exit;
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int) $_POST['product_id'];
    $features_data = $_POST['features'] ?? [];

    foreach ($features_data as $feature_id => $value_data) {
        $value = trim($value_data['value'] ?? '');
        $unit = trim($value_data['unit'] ?? '');
        $stmt = $conn->prepare("REPLACE INTO product_feature_values (product_id, feature_id, value, unit) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $product_id, $feature_id, $value, $unit);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Product features saved successfully',
            timer: 1500,
            showConfirmButton: false
        }).then(() => { window.location.href = 'product_feature_values.php'; });
    </script>";
    exit;
}

include("../../design/views/manager/product_feature_values_view.php");