<?php
//this page is used for generating ivite links and show all users with their roles and accessibilties..

require_once('../middleware/auth.php');

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

require_once('../db/db.php');

$inviteLink = '';
$errors = [];
$success = '';
$invites = [];

// Handle delete request first
if (isset($_POST['delete_code_id'])) {
    $delete_id = intval($_POST['delete_code_id']);

    $checkStmt = $conn->prepare("SELECT is_used FROM invite_codes WHERE id = ?");
    $checkStmt->bind_param("i", $delete_id);
    $checkStmt->execute();
    $checkStmt->bind_result($is_used);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($is_used) {
        $errors[] = "This code has already been used and cannot be deleted.";
    } else {
        $delStmt = $conn->prepare("DELETE FROM invite_codes WHERE id = ?");
        $delStmt->bind_param("i", $delete_id);
        if ($delStmt->execute()) {
            $success = "Invite code deleted.";
        } else {
            $errors[] = "Failed to delete invite code.";
        }
    }
}

// Handle invite code generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nickname']) && isset($_POST['role'])) {
    $nickname = trim($_POST['nickname']);
    $role = trim($_POST['role']);
    $created_by = $_SESSION['user_id'];

    if (!$nickname || !$role) {
        $errors[] = "Nickname and role are required.";
    } else {
        // Check if nickname already exists in invite_codes
        $checkStmt = $conn->prepare("SELECT id FROM invite_codes WHERE nickname = ?");
        $checkStmt->bind_param("s", $nickname);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = "This nickname is already in use. Please choose another.";
        } else {
            $code = bin2hex(random_bytes(5)); // 10-char random code
            $stmt = $conn->prepare("INSERT INTO invite_codes (code, nickname, role, created_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $code, $nickname, $role, $created_by);

            if ($stmt->execute()) {
                $inviteLink = "http://localhost/megabag/core/auth/register.php?code=$code";
                $success = "Invite code generated.";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }

        $checkStmt->close();
    }
}

// Fetch invite code list with creator and user info
$sql = "
    SELECT 
        ic.*,
        used_by_user.nickname AS used_by_nickname,
        creator.nickname AS created_by_nickname
    FROM invite_codes ic
    LEFT JOIN users used_by_user ON ic.used_by = used_by_user.id
    LEFT JOIN users creator ON ic.created_by = creator.id
    ORDER BY ic.generated_at DESC
";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $invites[] = $row;
    }
}

// Render view
include('../../design/views/manager/manage_users_view.php');



