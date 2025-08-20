<?php
require_once('../db/db.php');
// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;
$csvs = [];

// Initialize the session variable if it doesn't exist
if (!isset($_SESSION['uploaded_csv_ids'])) {
    $_SESSION['uploaded_csv_ids'] = [];
}

// Only proceed if there are CSV IDs to fetch
if (count($_SESSION['uploaded_csv_ids']) > 0) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['uploaded_csv_ids']), '?'));
    $types = str_repeat('i', count($_SESSION['uploaded_csv_ids']));
    $params = $_SESSION['uploaded_csv_ids'];

    $stmt = $conn->prepare("SELECT id, original_name, file_name, file_size, created_at, status FROM uploaded_csvs WHERE id IN ($placeholders) AND user_id = ?");
    $types .= 'i';
    $params[] = $user_id;

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $row['file_type'] = strtoupper(pathinfo($row['original_name'], PATHINFO_EXTENSION));
        $row['file_size_readable'] = formatBytes($row['file_size']);
        $csvs[] = $row;
    }
    $stmt->close();
}

echo json_encode(['success' => true, 'csvs' => $csvs]);

// Convert bytes to KB/MB
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}

