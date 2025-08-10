<?php
session_start();
ob_start();

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
    'invite_users' => '../manager/invite_users.php',
    'manage_users' => '../admin/manage_users.php',
    'manage_categories' => '../manager/manage_categories.php',
    'receive_stock' => '../manager/receive_stock.php',
    'list_receipts' => '../manager/list_receipts.php',
    'receive_csv' => '../csv/receive_csv.php',
    'stock_issue' => '../manager/stock_issue.php',
    'list_issues' => '../manager/list_issues.php',
    'create_product' => '../manager/create_product.php',  
    'edit_product' => '../manager/edit_product.php',
    'products_list' => '../manager/products_list.php',
    'login_logs' => '../logs/login_logs.php',
    'bans' => '../logs/bans.php',
    'profile' => '../auth/profile.php',
    'user_search_products' => '../user/user_search_products.php'
];

// Map `?page=...` to the actual core file
$content_file = isset($allowed_pages[$page]) ? $allowed_pages[$page] : null;

include('../../design/views/auth/dashboard_view.php');
ob_end_flush();