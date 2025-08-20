<?php
require_once("core/db/db.php");

$code = $_GET['code'] ?? '';
$errors = [];
$inviteValid = false;
$inviteData = null;

if ($code) {
    $stmt = $conn->prepare("SELECT * FROM invite_codes WHERE code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $inviteData = $result->fetch_assoc();

    if (!$inviteData) {
        $errors[] = "Invalid invite code.";
    } elseif ($inviteData['is_used']) {
        $errors[] = "This invite code has already been used. <a href='login.php'>Login now</a>.";
    } else {
        $inviteValid = true;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'])) {
    $name = trim($_POST['name']);
    $family = trim($_POST['family']);
    $nickname = trim($_POST['nickname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $code = trim($_POST['code']);

    // Double check invite again
    $stmt = $conn->prepare("SELECT * FROM invite_codes WHERE code = ? AND is_used = 0");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $inviteResult = $stmt->get_result();
    $invite = $inviteResult->fetch_assoc();

    if (!$invite) {
        $errors[] = "Invite code invalid or already used.";
    } else {
        // Check username uniqueness again (for safety)
        $checkUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkUser->bind_param("s", $username);
        $checkUser->execute();
        $checkUser->store_result();
        if ($checkUser->num_rows > 0) {
            $errors[] = "Username already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, family, nickname, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $family, $nickname, $email, $username, $password, $invite['role']);
            if ($stmt->execute()) {
                $userId = $stmt->insert_id;

                // Mark invite code as used
                $update = $conn->prepare("UPDATE invite_codes SET is_used = 1, used_by = ?, used_at = NOW() WHERE code = ?");
                $update->bind_param("is", $userId, $code);
                $update->execute();

                echo "<script>
                    window.location.href = 'core/auth/login.php?registered=1';
                </script>";
                exit;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}

include("design/views/auth/user_register_form.php");
