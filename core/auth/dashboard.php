<?php
session_start();
ob_start();

// Set the session timeout duration in seconds (e.g., 3600 seconds = 1 hour)
$session_timeout = 3600;

// Check for session inactivity and log out if necessary
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    // Session has expired, so we destroy it and redirect to the login page.
    session_unset();
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

// Check if user is logged in. If not, redirect to login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Update the last activity timestamp on every page load
$_SESSION['last_activity'] = time();


$msg = $_SESSION['msg'] ?? '';
$type = $_SESSION['type'] ?? 'info';
$username = $_SESSION['username'] ?? 'Guest';
$role = $_SESSION['role'] ?? 'user';

$page = $_GET['page'] ?? 'home';

// Allow only specific internal pages
$allowed_pages = [
    'home' => '../manager/home.php',
    'product_feature_values' => '../manager/product_feature_values.php', 
    'add_category_feature' => '../manager/add_category_feature.php', 
    'invite_users' => '../admin/invite_users.php',
    'manage_users' => '../admin/manage_users.php',
    'manage_categories' => '../manager/manage_categories.php',
    'receive_stock' => '../manager/receive_stock.php',
    'list_receipts' => '../manager/list_receipts.php',
    'print_receipt' => '../print/print_receipt.php',
    'edit_receipt' => '../manager/edit_receipt.php',
    'receive_csv' => '../csv/receive_csv.php',
    'stock_issue' => '../manager/stock_issue.php',
    'list_issues' => '../manager/list_issues.php',
    'create_product' => '../manager/create_product.php', 
    'create_project' => '../manager/create_project.php', 
    'edit_product' => '../manager/edit_product.php',
    'edit_project' => '../manager/edit_project.php',
    'edit_issue' => '../manager/edit_issue.php',
    'products_list' => '../manager/products_list.php',
    'projects_list' => '../manager/projects_list.php',
    'login_logs' => '../logs/login_logs.php',
    'bans' => '../logs/bans.php',
    'profile' => '../auth/profile.php',
    'user_search_products' => '../user/user_search_products.php',
    'mouser_search' => '../user/mouser_search.php',
    'filter_search' => '../user/filter_search.php'
];

// Map `?page=...` to the actual core file
$content_file = isset($allowed_pages[$page]) ? $allowed_pages[$page] : null;

include('../../design/views/auth/dashboard_view.php');
ob_end_flush();
