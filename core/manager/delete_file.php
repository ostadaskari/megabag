<?php
require_once '../db/db.php';
session_start();
// (ACL) Restrict access to admins/managers only  (access level )
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header("Location: ../auth/login.php");
    exit;
}
$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['type'])) {
    $id = (int) $_POST['id'];
    $type = $_POST['type'];

    $table = ($type === 'image') ? 'images' : (($type === 'pdf') ? 'pdfs' : '');
    $folder = ($type === 'image') ? '../../uploads/images/' : (($type === 'pdf') ? '../../uploads/pdfs/' : '');

    if ($table && $folder) {
        $stmt = $conn->prepare("SELECT file_path FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res) {
           $filePath = $res['file_path'];

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $deleteStmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();

            $response = ['success' => true, 'message' => 'File deleted successfully.'];
        } else {
            $response['message'] = 'File not found.';
        }
    } else {
        $response['message'] = 'Invalid file type.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
