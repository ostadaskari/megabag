<?php
// ... your existing session check logic ...

if (!isset($_SESSION['user_id'])) {
    // Check if the request is an AJAX request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // For AJAX requests, return a JSON error
        http_response_code(401); // 401 Unauthorized status code
        echo json_encode(['success' => false, 'message' => 'Authentication required.']);
        exit();
    } else {
        // For standard browser requests, redirect to the login page
        header('Location: ../auth/login.php');
        exit();
    }
}