<?php

session_start();
require_once("../db/db.php");

$errors = []; // Initialize $errors at the start

// --------------------------------------------------------
// --- NEW BLOCK: Check for Admin Block Message (from dashboard redirect) ---
if (isset($_SESSION['blocked_by_admin']) && $_SESSION['blocked_by_admin'] === true) {
    // Add the error message which will be displayed by SweetAlert
    $errors[] = "Access Denied: Your account has been blocked by an administrator. Please contact support.";
    unset($_SESSION['blocked_by_admin']); // Clear the flag immediately
}
// --------------------------------------------------------

// --------------------------------------------------------
// --- SECURITY CHECK: Redirect already logged-in users ---
// --------------------------------------------------------
// Check if the 'user_id' session variable is set, indicating an active login.
if (isset($_SESSION['user_id'])) {
    // Redirect to the main dashboard page (using 'dashboard.php' as per your existing successful login path).
    header("Location: dashboard.php");
    exit; // Stop script execution immediately after redirection
}
// --------------------------------------------------------

// Moved $errors initialization up, removing it from here
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$captcha_input = trim($_POST['captcha'] ?? '');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// set and Track failures in session for userrnames not IP. because we have just 2 ip in this projec
if (!isset($_SESSION['failures'])) $_SESSION['failures'] = [];
if (!isset($_SESSION['failures'][$username])) $_SESSION['failures'][$username] = 0;

// Expire old bans.. is_active column in database turn to 0 after 1hour )(expire date..)
$conn->query("UPDATE bans SET is_active = 0 WHERE expires_at <= NOW()");

// Check if user is already banned or no...
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
        // Validate CAPTCHA and turn it to lowerCase letters for having better UX
        $input = preg_replace('/[^A-Za-z0-9]/', '', strtolower($captcha_input));
        $stored = preg_replace('/[^A-Za-z0-9]/', '', strtolower($_SESSION['captcha'] ?? ''));
        unset($_SESSION['captcha']);

        if ($input !== $stored) {
            $errors[] = "Captcha is incorrect.";
            $_SESSION['failures'][$username]++;
            log_login_attempt($conn, $ip, $username, 'wrong captcha');
        } else {
            // Validate user credentials. Select the new 'is_blocked' column.
            $stmt = $conn->prepare("SELECT id, username, nickname, email, role, is_blocked, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                
                // --- NEW BLOCK: Check if user is blocked upon successful login ---
                if ($user['is_blocked'] == 1) {
                    $errors[] = "Access Denied: Your account has been blocked by an administrator. Please contact support.";
                    log_login_attempt($conn, $ip, $username, 'blocked');
                } else {
                    // Successful login
                    // Regenerate the session ID to prevent session fixation attacks
                    session_regenerate_id(true); //prevent session fixation attacks
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nickname'] = $user['nickname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['last_activity'] = time(); // Set initial activity timestamp

                    log_login_attempt($conn, $ip, $username, 'ok');
                    $_SESSION['failures'][$username] = 0;
                    header("Location: dashboard.php");
                    exit;
                }
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

//  LOG LOGIN function
function log_login_attempt($conn, $ip, $username, $status) {
    try {
        $stmt = $conn->prepare("INSERT INTO login_logs (ip, username, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $ip, $username, $status);
        $stmt->execute();
        
    } catch (Exception $e) {
        // Log the failure itself for debugging and security auditing.
        error_log("Failed to log login attempt for user '{$username}': " . $e->getMessage());
    }
}
