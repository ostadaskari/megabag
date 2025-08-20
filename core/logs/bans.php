<?php
require_once("../db/db.php");
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user has permission (e.g., admin or manager)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    echo "Access denied.";
    exit;
}

$ban_list = [];

try {
    
    //pagination info
    //set limit and offset for for showing how many banned users row per each page 
    $limit= 20; //number of rows per page 
    // We now use 'pg' for pagination to avoid conflict with the 'page' view parameter
    $page = isset($_GET['pg']) && is_numeric($_GET['pg']) ? (int) $_GET['pg'] : 1;
    $offset= ($page -1) * $limit;
    //COUNT TOTAL ROWS FOR bans table for pagination
    $result = $conn->query("SELECT COUNT(*) AS total FROM bans");
    $totalRows = $result->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    $stmt = $conn->prepare("SELECT * FROM bans ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param('ii', $limit, $offset);
    $stmt->execute();
    $ban_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $ban_list = [];
    $error = "Could not fetch ban list.";
}

include("../../design/views/logs/ban_list_view.php");