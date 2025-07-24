<?php
session_start();
require_once("../db/db.php");

// (Optional) Restrict access to admins/managers only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM login_logs ORDER BY created_at DESC");
    $stmt->execute();
    $logs = $stmt->get_result();
} catch (Exception $e) {
    $error = "Failed to fetch logs.";
    $logs = [];
}

include("../../design/views/logs/logins_log_view.php");
