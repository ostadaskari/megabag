<?php
session_start();
require_once("../db/db.php");

//  Check if user has permission (e.g., admin or manager)
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    echo "Access denied.";
    exit;
}

$ban_list = [];

try {
    $query = "SELECT * FROM bans ORDER BY created_at DESC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ban_list[] = $row;
        }
    }
} catch (Exception $e) {
    $ban_list = [];
    $error = "Could not fetch ban list.";
}

include("../../design/views/logs/ban_list_view.php");

