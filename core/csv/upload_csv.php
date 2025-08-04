<?php
require_once('../db/db.php');
// Check if a session has already been started before starting a new one.
// This prevents the "Ignoring session_start()" notice.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $validExts = ['csv', 'xlsx'];
    $validMimeTypes = [
        'text/csv',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (
        !in_array($ext, $validExts) ||
        $file['error'] !== 0 ||
        !in_array(mime_content_type($file['tmp_name']), $validMimeTypes)
    ) {
        echo json_encode(['success' => false, 'message' => 'Invalid file']);
        exit;
    }

    $uniqueName = uniqid('upload_') . '.' . $ext;
    $path = '../../uploads/csv/' . $uniqueName;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO uploaded_csvs (user_id, file_name, original_name, file_size)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("issi", $_SESSION['user_id'], $uniqueName, $file['name'], $file['size']);
    $stmt->execute();

    $csv_id = $stmt->insert_id;

    // Track in session
    if (!isset($_SESSION['uploaded_csv_ids'])) {
        $_SESSION['uploaded_csv_ids'] = [];
    }
    $_SESSION['uploaded_csv_ids'][] = $csv_id;

    echo json_encode(['success' => true]);
}
