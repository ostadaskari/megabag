<?php
require_once('../db/db.php');
require_once('../middleware/auth.php');

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = intval($data['user_id'] ?? 0);

$stmt = $conn->prepare("UPDATE users SET is_blocked = NOT is_blocked WHERE id = ?");
$stmt->bind_param("i", $user_id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
