<?php
session_start();
require_once("../db/db.php");


// (ACL) Restrict access to admins/managers only  (access level )
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    //pagination 
    //set page number and limit items per page 
    $limit = 3;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    // Count total rows from the last 2 months only
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM login_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 2 MONTH)");
    $stmt->execute();
    $stmt->bind_result($totalRows);
    $stmt->fetch();
    $stmt->close();

    $totalPages = ceil($totalRows / $limit);

        // Fetch logs only from the last 2 months
    $stmt = $conn->prepare("SELECT * FROM login_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 2 MONTH) ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param('ii', $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $logs = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $error = "Failed to fetch logs.";
    $logs = [];
}

include("../../design/views/logs/login_logs_view.php");
