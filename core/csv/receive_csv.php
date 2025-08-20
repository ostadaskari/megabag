<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}



// Load view
include '../../design/views/manager/receive_stock_csv_view.php';