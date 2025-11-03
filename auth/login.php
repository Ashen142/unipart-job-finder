<?php

include '../includes/db_connect.php'; 
include '../includes/functions.php';  
$page_title = "Log In to UniPart";
include '../includes/header.php'; 
?>

<div class="login-container">
    <div class="card">
        <h1 class="heading-main">Log In To Your Account</h1>
        <p class="tagline">Connecting students with part-time jobs </p>
        
        <form action="login.php" method="POST" class="login-form">
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="form-options">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
                <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </form>

        <div class="register-link-section">
            <p>Don't have an account? <a href="register.php" class="register-now">Register Now</a></p>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php'; 
?>