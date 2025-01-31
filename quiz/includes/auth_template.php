<?php
// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../db.php';
require_once '../includes/auth_check.php';

// Use the centralized authentication function
if (!checkAuthentication(true, false)) {
    // This will redirect if not authenticated
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

require_once '../includes/course_functions.php';
require_once '../includes/achievement_functions.php';
require_once 'includes/quiz_template.php';
?>
