<?php
require_once("../db/db.php");

// Enable MySQLi to throw exceptions on errors, making try...catch more effective
// If your environment doesn't allow this, you must rely on checking execute() return values.
// For this security check, we assume an environment where exceptions are thrown or we check returns inside try.
if (defined('MYSQLI_REPORT_ALL')) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

// Check if a session has already been started before starting a new one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../middleware/auth.php'); // Assumes this handles redirection if not logged in

// === CSRF FUNCTIONS ===
function generate_csrf_token() {
    // Only generate if one doesn't exist
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
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

$errors = [];
$success = "";

$user_id = $_SESSION['user_id'] ?? null;
$user = null; // Initialize user data as null

// === 1. FETCH CURRENT USER DATA ===
try {
    if (!$user_id) {
        // If the user_id is somehow missing after passing auth.php, handle it
        throw new Exception("User session ID is missing.");
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Failed to prepare user fetch statement: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute user fetch: " . $stmt->error);
    }
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
         throw new Exception("User not found in database.");
    }

} catch (Exception $e) {
    // Log the error and set a general message, but don't stop execution yet (we still want to show the profile view)
    error_log("Critical User Fetch Error: " . $e->getMessage());
    $errors[] = "A critical error occurred while fetching user data. Please contact support.";
}

// === 2. UPDATE PERSONAL INFO ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'info') {
    try {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            error_log('CSRF token validation failed for info update.');
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        // Input Sanitization and Retrieval
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $family = filter_input(INPUT_POST, 'family', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        // Basic Validation
        if (empty($name) || empty($family) || empty($nickname) || empty($email)) {
             throw new Exception("All fields are required.");
        }

        // Check nickname uniqueness
        $checkNick = $conn->prepare("SELECT id FROM users WHERE nickname = ? AND id != ?");
        if (!$checkNick) {
            throw new Exception("Failed to prepare nickname check statement: " . $conn->error);
        }
        $checkNick->bind_param("si", $nickname, $user_id);
        $checkNick->execute();
        $checkNick->store_result();

        if ($checkNick->num_rows > 0) {
            $errors[] = "Nickname already exists.";
        } else {
            // Update the user's information
            $update = $conn->prepare("UPDATE users SET name = ?, family = ?, nickname = ?, email = ? WHERE id = ?");
            if (!$update) {
                throw new Exception("Failed to prepare update statement: " . $conn->error);
            }
            $update->bind_param("ssssi", $name, $family, $nickname, $email, $user_id);
            
            if ($update->execute()) {
                $success = "Profile updated successfully.";
                
                // Refresh user data from DB for immediate display:
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc(); // Refreshes $user array
                $stmt->close();
                
            } else {
                throw new Exception("Error executing profile update: " . $update->error);
            }
            $update->close();
        }
        $checkNick->close();

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        error_log("Profile Info Update Error: " . $e->getMessage());
    }
}

// === 3. CHANGE PASSWORD ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'password') {
    try {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            error_log('CSRF token validation failed for password update.');
            throw new Exception("Invalid or missing CSRF token. Request denied.");
        }

        // Input Sanitization and Retrieval
        $current = filter_input(INPUT_POST, 'current_password', FILTER_UNSAFE_RAW); // Use raw for password comparison/hashing
        $new = filter_input(INPUT_POST, 'new_password', FILTER_UNSAFE_RAW);
        $confirm = filter_input(INPUT_POST, 'confirm_password', FILTER_UNSAFE_RAW);
        
        // Basic Validation
        if (empty($current) || empty($new) || empty($confirm)) {
             throw new Exception("All password fields are required.");
        }
        if (!isset($user['password'])) {
             throw new Exception("Cannot verify password; user data unavailable.");
        }
        
        if (!password_verify($current, $user['password'])) {
            $errors[] = "Current password is incorrect.";
        } elseif ($new !== $confirm) {
            $errors[] = "New password and confirmation do not match.";
        } elseif (strlen($new) < 8) { // Good practice: enforce minimum length
            $errors[] = "New password must be at least 8 characters long.";
        } else {
            // Hash and update
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            if (!$update) {
                throw new Exception("Failed to prepare password update statement: " . $conn->error);
            }
            $update->bind_param("si", $hash, $user_id);
            
            if ($update->execute()) {
                $success = "Password updated successfully.";
            } else {
                throw new Exception("Failed to execute password update: " . $update->error);
            }
            $update->close();
        }
        
    } catch (Exception $e) {
        // Only show a general error for security, log the details
        $errors[] = "Failed to update password due to an internal error.";
        error_log("Password Change Error: " . $e->getMessage());
    }
}

include("../../design/views/auth/profile_view.php");
