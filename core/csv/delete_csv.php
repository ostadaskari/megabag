<?php
require_once('../db/db.php');
session_start();

// Read raw JSON input
$data = json_decode(file_get_contents("php://input"), true);
$id = isset($data['id']) ? (int)$data['id'] : 0;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

// Fetch file name to delete physical file
$stmt = $conn->prepare("SELECT file_name FROM uploaded_csvs WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($file);

if ($stmt->fetch()) {
    $stmt->close();

    // Delete DB record
    $del = $conn->prepare("DELETE FROM uploaded_csvs WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();

    // Delete actual file
    @unlink("../../uploads/csv/$file");

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'File not found or access denied']);
}
