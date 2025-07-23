<?php

session_start();
require_once("../db/db.php");

//
$errors = [];
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$captcha_input = trim($_POST['captcha'] ?? '');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';




// Track failures in session
if (!isset($_SESSION['failures'])) $_SESSION['failures'] = [];
if (!isset($_SESSION['failures'][$username])) $_SESSION['failures'][$username] = 0;

// Expire old bans
$conn->query("UPDATE bans SET is_active = 0 WHERE expires_at <= NOW()");

// Check if user is already banned
if ($username) {
    try {
        $ban_check = $conn->prepare("SELECT * FROM bans WHERE username = ? AND is_active = 1 AND expires_at > NOW() LIMIT 1");
        $ban_check->bind_param("s", $username);
        $ban_check->execute();
        $ban_result = $ban_check->get_result();

        if ($ban_result->num_rows > 0) {
            $errors[] = "You are banned for 1 hour because of multiple failed login attempts.";
            log_login_attempt($conn, $ip, $username, 'unknown');
            include("../../design/views/auth/login_form.php");
            exit;
        }
    } catch (Exception $e) {
        $errors[] = "System error occurred. Please try again later.";
        log_login_attempt($conn, $ip, $username, 'unknown');
        include("../../design/views/auth/login_form.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validate CAPTCHA
        $input = preg_replace('/[^A-Za-z0-9]/', '', strtolower($captcha_input));
        $stored = preg_replace('/[^A-Za-z0-9]/', '', strtolower($_SESSION['captcha'] ?? ''));
        unset($_SESSION['captcha']);

        if ($input !== $stored) {
            $errors[] = "Captcha is incorrect.";
            $_SESSION['failures'][$username]++;
            log_login_attempt($conn, $ip, $username, 'wrong captcha');
        } else {
            // Validate user credentials
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) { //successful login
                 // reset sessions before any login
                    session_unset();
                    session_destroy();
                    session_start();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nickname'] = $user['nickname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                log_login_attempt($conn, $ip, $username, 'ok');
                $_SESSION['failures'][$username] = 0;
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Invalid username or password.";
                $_SESSION['failures'][$username]++;
                log_login_attempt($conn, $ip, $username, 'wrong pass');
            }
        }

        // Ban if 3+ failures
        if ($_SESSION['failures'][$username] >= 3) {
            $user_id = $user['id'] ?? null;
            $ban_stmt = $conn->prepare("INSERT INTO bans (username, user_id, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
            $ban_stmt->bind_param("si", $username, $user_id);
            $ban_stmt->execute();

            $_SESSION['failures'][$username] = 0;
            $errors[] = "You are banned for 1 hour because of multiple failed login attempts.";
        }

    } catch (Exception $e) {
        $errors[] = "Unexpected system error. Please contact admin.";
        log_login_attempt($conn, $ip, $username, 'unknown');
    }
}

// Load form if not POST
include("../../design/views/auth/login_form.php");

//  LOG LOGIN function
function log_login_attempt($conn, $ip, $username, $status) {
    try {
        $stmt = $conn->prepare("INSERT INTO login_logs (ip, username, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $ip, $username, $status);
        $stmt->execute();
        
    } catch (Exception $e) {
        // Fails silently if logging itself fails
    }
}


