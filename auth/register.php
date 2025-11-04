<?php
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Register In to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/auth.css']; // Page-specific CSS
// Body class to allow page-specific header/footer styling
$body_class = 'auth-page';
// Page-specific JS (will be printed by footer.php)
$extraJS = ['/Unipart-job-finder/assets/js/register.js'];

// Include header
include __DIR__ . '/../includes/header.php';
?>

    <div class="auth-container">
        <div class="register-card">
            <h2 class="card-title">Create Your Account</h2>

            <form action="register.php" method="POST" class="auth-form">

                <div class="form-group role-selection full">
                    <label>Register as:</label>
                    <div class="radio-group">
                        <input type="radio" id="role-student" name="user_role" value="Student" required checked>
                        <label for="role-student">Student</label>
                        
                        <input type="radio" id="role-employer" name="user_role" value="Employer" required>
                        <label for="role-employer">Employer</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>

                <div class="form-group student-field full">
                    <label for="university_id">University ID/Roll No.:</label>
                    <input type="text" id="university_id" name="university_id" placeholder="Your University ID" required>
                </div>

                <div class="form-group employer-field full" style="display:none;">
                    <label for="company_name">Company Name:</label>
                    <input type="text" id="company_name" name="company_name" placeholder="Your Company Name" required disabled>
                </div>

                <div class="form-group full">
                    <button type="submit" name="register_btn" class="submit-btn">Register</button>
                </div>
            </form>

            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
