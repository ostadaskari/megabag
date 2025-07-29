<?php
require_once '../db/db.php';
session_start();

// ACL: Allow only manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}



include '../../design/views/manager/stock_issues_list_view.php';