<?php
require_once('../db/db.php');
require_once('../middleware/auth.php');

$user_id = $_SESSION['user_id'];
$csvs = [];

// Optional: use session to only show files uploaded "in this session"
if (!isset($_SESSION['uploaded_csv_ids'])) {
    $_SESSION['uploaded_csv_ids'] = [];
}

if (count($_SESSION['uploaded_csv_ids']) > 0) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['uploaded_csv_ids']), '?'));
    $types = str_repeat('i', count($_SESSION['uploaded_csv_ids']));
    $params = $_SESSION['uploaded_csv_ids'];

    $stmt = $conn->prepare("SELECT * FROM uploaded_csvs WHERE id IN ($placeholders) AND user_id = ?");
    $types .= 'i';
    $params[] = $user_id;

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $csvs[] = $row;
    }
    $stmt->close();
}

echo json_encode(['success' => true, 'csvs' => $csvs]);
