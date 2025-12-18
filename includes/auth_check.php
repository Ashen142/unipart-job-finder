<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If no user is logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: /Unipart-job-finder/auth/login.php?message=login_required");
    exit;
}
?>
