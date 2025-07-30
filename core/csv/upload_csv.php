<?php
require_once('../db/db.php');
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    if ($ext !== 'csv' || $file['error'] !== 0) {
        die(json_encode(['success' => false, 'message' => 'Invalid file']));
    }

    $uniqueName = uniqid('csv_') . '.csv';
    $path = '../../uploads/csv/' . $uniqueName;

    if (move_uploaded_file($file['tmp_name'], $path)) {
        $stmt = $conn->prepare("INSERT INTO uploaded_csvs (user_id, file_name, original_name, file_size) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $_SESSION['user_id'], $uniqueName, $file['name'], $file['size']);
        $stmt->execute();

        $csv_id = $stmt->insert_id;

        // Save ID in session for current uploads if needed
        if (!isset($_SESSION['uploaded_csv_ids'])) {
            $_SESSION['uploaded_csv_ids'] = [];
        }
        $_SESSION['uploaded_csv_ids'][] = $csv_id;

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
}
