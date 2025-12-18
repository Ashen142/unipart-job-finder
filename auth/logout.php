<?php

session_start(); // Start session (required to destroy it)

// Unset all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

//  prevent back button access after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirect to login page
header("Location: login.php?message=logged_out");
exit;
?>
