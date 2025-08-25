<?php
require_once '../db/db.php';
// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// (ACL) Restrict access to admins/managers only (access level)
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Project ID not provided.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        echo json_encode(['success' => true, 'project' => $project]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Project not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement.']);
}

$conn->close();
