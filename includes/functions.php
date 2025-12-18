<?php


//  Sanitize Input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

//  Check if user logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

//  Require specific role
function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../auth/login.php");
        exit;
    }
}

//  Get user details
function get_user_by_id($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

//  Password helpers
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hashed) {
    return password_verify($password, $hashed);
}

//  Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}

//  Flash messages
function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}
