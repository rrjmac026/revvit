<?php
/**
 * Centralized authentication check function
 * Redirects to login page if user is not authenticated
 * 
 * @param bool $redirect Whether to redirect or just return authentication status
 * @return bool True if authenticated, false otherwise
 */
function checkAuthentication($redirect = true, $start_session = false) {
    // Start session if not already started and requested
    if ($start_session && session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        if ($redirect) {
            // Store the current page URL for post-login redirection
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header("Location: " . dirname($_SERVER['PHP_SELF']) . "/login.php");
            exit();
        }
        return false;
    }
    return true;
}

// Optional: Add a function to check admin authentication if needed
function checkAdminAuthentication($redirect = true) {
    if (!checkAuthentication($redirect)) {
        return false;
    }

    // Add additional admin-specific checks here
    // For example, checking a role in the database
    return true;
}

function require_login() {
    return checkAuthentication(true, true);
}
?>
