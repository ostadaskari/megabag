<?php
session_start();
require_once("../db/db.php");

$errors = [];
$success = false;

// --- Handle POST Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $parent_id = $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : null;
    $action = $_POST['action'] ?? '';
    $category_id = intval($_POST['category_id'] ?? 0);

    if ($action === 'add') {
        try {
            $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
            $stmt->bind_param("si", $name, $parent_id);
            $stmt->execute();
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Failed to add category.";
        }
    }

    if ($action === 'edit' && $category_id) {
        try {
            $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $name, $parent_id, $category_id);
            $stmt->execute();
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Failed to update category.";
        }
    }

    if ($action === 'delete' && $category_id) {
        try {
            // Delete current and all subcategories recursively
            deleteCategoryRecursive($conn, $category_id);
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Failed to delete category.";
        }
    }

    // Redirect to clear POST data and avoid resubmission
    header("Location: manage_categories.php");
    exit;
}

// --- Recursive Category Fetcher ---
function fetchCategories($conn) {
    $stmt = $conn->prepare("SELECT id, name, parent_id FROM categories ORDER BY name ASC");
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

// --- Recursive Delete Helper ---
function deleteCategoryRecursive($conn, $catId) {
    // Find children
    $childStmt = $conn->prepare("SELECT id FROM categories WHERE parent_id = ?");
    $childStmt->bind_param("i", $catId);
    $childStmt->execute();
    $children = $childStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($children as $child) {
        deleteCategoryRecursive($conn, $child['id']);
    }

    // Delete current category
    $delStmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $delStmt->bind_param("i", $catId);
    $delStmt->execute();
}

// --- Fetch All Categories for Form and Tree ---
$allCategories = fetchCategories($conn);

// --- Load View ---
include("../../design/views/manager/manage_categories_view.php");
