<?php
require_once('../db/db.php');
session_start();

header('Content-Type: application/json');

// Parse raw JSON input
$data = json_decode(file_get_contents("php://input"), true);
$id = isset($data['id']) ? (int)$data['id'] : 0;
$user_id = $_SESSION['user_id'] ?? 0;

if (!$id || !$user_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Fetch file name from database
$stmt = $conn->prepare("SELECT file_name FROM uploaded_csvs WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$stmt->bind_result($file_name);

if ($stmt->fetch()) {
    $stmt->close();

    // Delete record from database
    $del = $conn->prepare("DELETE FROM uploaded_csvs WHERE id = ? AND user_id = ?");
    $del->bind_param("ii", $id, $user_id);
    $del->execute();
    $del->close();

    // Delete the physical file (.xlsx)
    $filePath = "../../uploads/csv/$file_name";
    if (file_exists($filePath)) {
        @unlink($filePath);
    }

    // Also remove from session uploaded ids
    if (!empty($_SESSION['uploaded_csv_ids'])) {
        $_SESSION['uploaded_csv_ids'] = array_values(array_filter(
            $_SESSION['uploaded_csv_ids'],
            fn($csvId) => $csvId != $id
        ));
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'File not found or access denied']);
}
