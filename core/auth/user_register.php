<?php
// We only need the database connection here
require_once("core/db/db.php");

$code = $_GET['code'] ?? '';
$errors = [];
$inviteValid = false;
$inviteData = null;

try {
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
} catch (Exception $e) {
    // Log the exception privately for debugging
    error_log("Invite code validation failed: " . $e->getMessage());
    $errors[] = "An unexpected error occurred. Please try again later.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'])) {
    try {
        $name = trim($_POST['name']);
        $family = trim($_POST['family']);
        $nickname = trim($_POST['nickname']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password']; // Get plain password for validation
        $code = trim($_POST['code']);

        // Check for empty fields
        if (empty($name) || empty($family) || empty($email) || empty($username) || empty($password) || empty($code)) {
            $errors[] = "All fields are required.";
        }
        
        // Basic email and password validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (strlen($password) <= 5) {
            $errors[] = "Password must be at least 6 characters long.";
        }

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
            }
        }
        
        // If there are no errors, proceed with registration
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, family, nickname, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $family, $nickname, $email, $username, $hashed_password, $invite['role']);
            
            if ($stmt->execute()) {
                $userId = $stmt->insert_id;

                // Mark invite code as used
                $update = $conn->prepare("UPDATE invite_codes SET is_used = 1, used_by = ?, used_at = NOW() WHERE code = ?");
                $update->bind_param("is", $userId, $code);
                $update->execute();

                // Use a secure PHP header redirect instead of JavaScript
                header("Location: core/auth/login.php?registered=1");
                exit;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    } catch (Exception $e) {
        // Log the exception privately for debugging
        error_log("User registration failed: " . $e->getMessage());
        $errors[] = "An unexpected error occurred during registration. Please contact admin.";
    }
}

// Check if invite code is still valid after POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && $code && empty($errors)) {
    $inviteValid = true;
} else if ($_SERVER["REQUEST_METHOD"] === "POST" && $code && !empty($errors)) {
    // Re-check invite validity if there were other errors
    try {
        $stmt = $conn->prepare("SELECT * FROM invite_codes WHERE code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $inviteData = $result->fetch_assoc();

        if ($inviteData && !$inviteData['is_used']) {
            $inviteValid = true;
        } else {
            $inviteValid = false;
        }
    } catch (Exception $e) {
        error_log("Invite code re-check failed after POST: " . $e->getMessage());
        $inviteValid = false;
    }
}

include("design/views/auth/user_register_form.php");
