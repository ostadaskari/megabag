<?php
// This page is used for generating invite links and showing all users with their roles and accessibilities.
require_once('../middleware/auth.php');
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require_once('../db/db.php');

//csrf functions
function generate_csrf_token() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function validate_csrf_token($token) {
    // Check if the token exists in the session and if it matches the submitted token.
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // A valid token has been used. Invalidate it to prevent replay attacks.
        unset($_SESSION['csrf_token']);
        return true;
    }
    return false;
}

$inviteLink = '';
$errors = [];
$success = '';
$invites = [];

// --- Pagination settings ---
$itemsPerPage = 10;
$currentPage = isset($_GET['p']) ? intval($_GET['p']) : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $itemsPerPage;

// --- Handle POST requests (CREATE and DELETE) inside a try-catch block
try {
    // --- Handle DELETE Invite Code ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_code_id'])) {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            // Log the error for security monitoring purposes.
            error_log('CSRF token validation failed.');
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

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
                header("Location: ../auth/dashboard.php?page=invite_users&success=" . urlencode("Invite code deleted."));
                exit;
            } else {
                $errors[] = "Failed to delete invite code.";
            }
        }
    }

    // --- Handle CREATE Invite Code ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nickname']) && isset($_POST['role'])) {
        //validate csrf
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            // Log the error for security monitoring purposes.
            error_log('CSRF token validation failed.');
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        $nickname = trim($_POST['nickname']);
        $role = trim($_POST['role']);
        $created_by = $_SESSION['user_id'];

        if (!$nickname || !$role) {
            $errors[] = "Nickname and role are required.";
        } else {
            $checkStmt = $conn->prepare("SELECT id FROM invite_codes WHERE nickname = ?");
            $checkStmt->bind_param("s", $nickname);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $errors[] = "This nickname is already in use. Please choose another.";
            } else {
                $code = bin2hex(random_bytes(5)); // 10-character random code
                $stmt = $conn->prepare("INSERT INTO invite_codes (code, nickname, role, created_by) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $code, $nickname, $role, $created_by);

                if ($stmt->execute()) {
                    // Redirect with invite link and success
                    $inviteLink = "http://localhost/megabag/rgs.php?code=$code";
                    header("Location: ../auth/dashboard.php?page=invite_users&success=" . urlencode("Invite code generated.") . "&link=" . urlencode($inviteLink));
                    exit;
                } else {
                    $errors[] = "Error: " . $stmt->error;
                }
            }
            $checkStmt->close();
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

// Redirect if errors occurred during the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)) {
    header("Location: ../auth/dashboard.php?page=invite_users&error=" . urlencode(implode(' | ', $errors)));
    exit;
}

// --- Handle messages from URL ---
$success = $_GET['success'] ?? '';
$inviteLink = $_GET['link'] ?? '';
$errors = isset($_GET['error']) ? explode(' | ', $_GET['error']) : [];

// --- Fetch all invite codes for the current page ---
$sql = "
    SELECT 
        ic.*,
        used_by_user.nickname AS used_by_nickname,
        creator.nickname AS created_by_nickname
    FROM invite_codes ic
    LEFT JOIN users used_by_user ON ic.used_by = used_by_user.id
    LEFT JOIN users creator ON ic.created_by = creator.id
    ORDER BY ic.generated_at DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $itemsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $invites[] = $row;
    }
}
$stmt->close();

// --- Get total number of invites for pagination ---
$countSql = "SELECT COUNT(*) FROM invite_codes";
$countResult = $conn->query($countSql);
$totalInvites = $countResult->fetch_row()[0];
$totalPages = ceil($totalInvites / $itemsPerPage);

// --- Render view ---
include('../../design/views/admin/invite_users_view.php');
