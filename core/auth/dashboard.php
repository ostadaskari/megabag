<?php
session_start();
ob_start();

// Include the database connection
require_once('../db/db.php');

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

// --- NEW BLOCK: Check if the user is blocked by admin ---
try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT is_blocked FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if ($user_data && $user_data['is_blocked'] == 1) {
        // User is blocked! Log out, destroy session, and redirect.
        session_unset();
        session_destroy();
        
        // Start a new session briefly to set the message flag for login.php
        session_start(); 
        $_SESSION['blocked_by_admin'] = true; // Flag for the SweetAlert message
        
        header("Location: ../auth/login.php");
        exit;
    }

} catch (Exception $e) {
    // Log the error if the database query fails, but allow the user to continue if possible
    error_log("Database error during user block check: " . $e->getMessage());
}
// --------------------------------------------------------


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
