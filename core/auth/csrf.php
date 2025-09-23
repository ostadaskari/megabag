<?php
/**
 * CSRF Token Management Functions.
 *
 * This file contains functions to generate and validate CSRF tokens
 * to protect against Cross-Site Request Forgery (CSRF) attacks.
 */

// We will need a session for storing the token.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generates a CSRF token and a hidden form input field.
 *
 * The token is stored in the user's session and the input field
 * is returned as a string to be placed inside a form.
 */
function generate_csrf_token() {
    // Generate a new, random token on every call to prevent token reuse across different forms.
    // This is the key change for enhanced security.
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    
    // Echo the hidden input field with the token value.
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

/**
 * Validates the CSRF token submitted with a form.
 *
 * @param string $token The token submitted from the form.
 * @return bool True if the token is valid, false otherwise.
 */
function validate_csrf_token($token) {
    // Check if the token exists in the session and if it matches the submitted token.
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // A valid token has been used. Invalidate it to prevent replay attacks.
        unset($_SESSION['csrf_token']);
        return true;
    }
    return false;
}