<?php
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../db/db.php');

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = intval($data['user_id'] ?? 0);
$role = $data['role'] ?? '';

$validRoles = ['admin', 'manager', 'user'];
if (!in_array($role, $validRoles)) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $role, $user_id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
