<?php
require_once("../db/db.php");
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Restrict to logged-in users with role 'user'
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager' && $_SESSION['role'] !== 'user')) {
    header("Location: ../auth/login.php");
    exit;
}

// Handle AJAX search
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $keyword = '%' . trim($_GET['q'] ?? '') . '%';
    

    $stmt = $conn->prepare("
        SELECT p.id, p.name, p.part_number, p.tag, p.mfg, p.qty, c.name AS category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.name LIKE ? OR p.part_number LIKE ? OR p.tag LIKE ?
        ORDER BY p.name ASC
        LIMIT 50
    ");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $res = $stmt->get_result();

    $results = [];
    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
    }

    echo json_encode(['success' => true, 'products' => $results]);
    exit;
}

include("../../design/views/user/search_products_view.php");