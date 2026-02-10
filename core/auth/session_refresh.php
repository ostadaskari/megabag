<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Update session
$_SESSION['last_activity'] = time();

// Send response with expiry time (2 hours from NOW)
$session_timeout = 7200; // Must match dashboard.php
$expires_at = $_SESSION['last_activity'] + $session_timeout;

echo json_encode([
    'success' => true,
    'message' => 'Session refreshed',
    'new_time' => $_SESSION['last_activity'],
    'expires_at' => $expires_at,
    'human_time' => date('H:i:s', $expires_at)
]);
?>