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

include("../../design/views/user/mouser_search_view.php");