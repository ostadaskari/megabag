<?php
require_once("../db/db.php");

// Start session if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ACL check
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];
$success = '';

// --- Handle POST Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $parent_id = $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : null;
    $action = $_POST['action'] ?? '';
    $category_id = intval($_POST['category_id'] ?? 0);

    // Generate slug from name
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    // Ensure slug uniqueness
    $baseSlug = $slug;
    $i = 1;
    $checkStmt = $conn->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
    do {
        $checkSlug = $slug;
        $checkStmt->bind_param("si", $checkSlug, $category_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $slug = $baseSlug . '-' . $i++;
        }
    } while ($checkResult->num_rows > 0);
    $checkStmt->close();

    try {
        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO categories (name, parent_id, slug) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $name, $parent_id, $slug);
            $stmt->execute();
            $success = "Category added successfully.";
        } elseif ($action === 'edit' && $category_id) {
            $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ?, slug = ? WHERE id = ?");
            $stmt->bind_param("sisi", $name, $parent_id, $slug, $category_id);
            $stmt->execute();
            $success = "Category updated successfully.";
        } elseif ($action === 'delete' && $category_id) {
            deleteCategoryRecursive($conn, $category_id);
            $success = "Category and its subcategories deleted successfully.";
        } else {
            $errors[] = "Invalid action or missing category ID.";
        }
    } catch (Exception $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }

    // Build query string
    $query = '';
    if (!empty($success)) {
        $query = 'success=' . urlencode($success);
    } elseif (!empty($errors)) {
        $query = 'error=' . urlencode(implode(' | ', $errors));
    }

    // Redirect with PRG pattern
    header("Location: ../auth/dashboard.php?page=manage_categories" . ($query ? "&$query" : ""));
    exit;
}

// --- Handle PRG messages ---
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $errors = explode(' | ', $_GET['error']);
}

// --- Recursive Category Fetcher ---
function fetchCategories($conn) {
    $stmt = $conn->prepare("SELECT id, name, parent_id, slug FROM categories ORDER BY name ASC");
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

// --- Recursive Delete Helper ---
function deleteCategoryRecursive($conn, $catId) {
    $childStmt = $conn->prepare("SELECT id FROM categories WHERE parent_id = ?");
    $childStmt->bind_param("i", $catId);
    $childStmt->execute();
    $children = $childStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $childStmt->close();

    foreach ($children as $child) {
        deleteCategoryRecursive($conn, $child['id']);
    }

    $delStmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $delStmt->bind_param("i", $catId);
    $delStmt->execute();
    $delStmt->close();
}

// --- Fetch All Categories for Form and Tree ---
$allCategories = fetchCategories($conn);

// --- Load View ---
include("../../design/views/manager/manage_categories_view.php");
?>
