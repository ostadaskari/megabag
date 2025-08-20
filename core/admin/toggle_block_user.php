<?php
session_start(); // Start the session to access session variables
require_once('../db/db.php');

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = intval($data['user_id'] ?? 0);

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("UPDATE users SET is_blocked = NOT is_blocked WHERE id = ?");
$stmt->bind_param("i", $user_id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>

