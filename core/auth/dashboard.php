<?php
require_once('../middleware/auth.php');

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Optional SweetAlert2 message via GET
$msg = $_GET['msg'] ?? null;
$type = $_GET['type'] ?? 'info';

// Now pass variables to view
include('../../design/views/auth/dashboard_view.php');

