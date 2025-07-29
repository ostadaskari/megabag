<?php
require_once('../db/db.php');
require_once('../middleware/auth.php');

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$search = trim($_GET['search'] ?? '');
$page = max((int)($_GET['page'] ?? 1), 1);
$limit = 10;
$offset = ($page - 1) * $limit;

$whereSql = '';
$params = [];
$types = '';

if ($search !== '') {
    $whereSql = "WHERE name LIKE ? OR family LIKE ? OR nickname LIKE ?";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
    $types = 'sss';
}

$countSql = "SELECT COUNT(*) FROM users " . $whereSql;
$countStmt = $conn->prepare($countSql);
if ($params) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$countStmt->bind_result($total);
$countStmt->fetch();
$countStmt->close();

$totalPages = ceil($total / $limit);

$userSql = "SELECT id, name, family, nickname, role, is_blocked FROM users $whereSql ORDER BY created_at DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= 'ii';

$userStmt = $conn->prepare($userSql);
$userStmt->bind_param($types, ...$params);
$userStmt->execute();
$result = $userStmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    echo json_encode([
        'success' => true,
        'users' => $users,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
    exit;
}

include('../../design/views/admin/manage_users_view.php');