<?php
require_once("../db/db.php");
// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ACL: Only admin/manager allowed
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

    try {
        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
            $stmt->bind_param("si", $name, $parent_id);
            $stmt->execute();
            $success = "Category added successfully.";
        } elseif ($action === 'edit' && $category_id) {
            $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $name, $parent_id, $category_id);
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

    // Redirect with PRG, fixing the double '?'
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
    $stmt = $conn->prepare("SELECT id, name, parent_id FROM categories ORDER BY name ASC");
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