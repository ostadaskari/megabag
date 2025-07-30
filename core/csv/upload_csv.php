<?php
$uploadDir = '../../uploads/csv/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {
    $file = $_FILES['csv'];
    if ($file['type'] === 'text/csv' || pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
        $dest = $uploadDir . time() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $dest);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid CSV file.']);
    }
    exit;
}