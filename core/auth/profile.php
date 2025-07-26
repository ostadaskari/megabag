<?php
require_once("../db/db.php");
//chick if login or not
require_once('../middleware/auth.php');

$errors = [];
$success = "";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();


// Update personal info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form'] === 'info') {
    $name = trim($_POST['name']);
    $family = trim($_POST['family']);
    $nickname = trim($_POST['nickname']);
    $email = trim($_POST['email']);

    // Check nickname uniqueness
    $checkNick = $conn->prepare("SELECT id FROM users WHERE nickname = ? AND id != ?");
    $checkNick->bind_param("si", $nickname, $user_id);
    $checkNick->execute();
    $checkNick->store_result();

    if ($checkNick->num_rows > 0) {
        $errors[] = "Nickname already exists.";
    } else {
        $update = $conn->prepare("UPDATE users SET name = ?, family = ?, nickname = ?, email = ? WHERE id = ?");
        $update->bind_param("ssssi", $name, $family, $nickname, $email, $user_id);
        if ($update->execute()) {
            $success = "Profile updated successfully.";
              // 🔁 Refresh user data from DB:
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc(); // Refreshes $user array
        } else {
            $errors[] = "Error updating profile.";
        }
    }
}

// Change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form'] === 'password') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($current, $user['password'])) {
        $errors[] = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $errors[] = "New password and confirmation do not match.";
    } else {
        $hash = password_hash($new, PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $hash, $user_id);
        if ($update->execute()) {
            $success = "Password updated successfully.";
        } else {
            $errors[] = "Failed to update password.";
        }
    }
}

include("../../design/views/auth/profile_view.php");
