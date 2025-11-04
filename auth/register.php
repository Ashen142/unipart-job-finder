<?php
// Include backend setup
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Register In to UniPart";
$extraCSS = ["../assets/css/auth.css"]; // Optional: custom login/register styles

// Include header
include __DIR__ . '/../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UniPart</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/auth.css"> 
</head>
<body>
    

    <div class="auth-container">
        <div class="register-card">
            <h2 class="card-title">Create Your Account</h2>

            <form action="register.php" method="POST" class="auth-form">

                <div class="form-group role-selection">
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

                <div class="form-group student-field">
                    <label for="university_id">University ID/Roll No.:</label>
                    <input type="text" id="university_id" name="university_id" placeholder="Your University ID" required>
                </div>

                <div class="form-group employer-field" style="display:none;">
                    <label for="company_name">Company Name:</label>
                    <input type="text" id="company_name" name="company_name" placeholder="Your Company Name" required disabled>
                </div>

                <button type="submit" name="register_btn" class="submit-btn">Register</button>
            </form>

            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <?php include('../includes/footer.php'); // Adjust path as needed ?>
    
    <script>
        const studentRole = document.getElementById('role-student');
        const employerRole = document.getElementById('role-employer');
        const studentField = document.querySelector('.student-field');
        const employerField = document.querySelector('.employer-field');
        const companyNameInput = document.getElementById('company_name');
        const universityIdInput = document.getElementById('university_id');

        function toggleFields() {
            if (studentRole.checked) {
                studentField.style.display = 'block';
                universityIdInput.required = true;
                employerField.style.display = 'none';
                companyNameInput.required = false;
                companyNameInput.disabled = true; // Disable if hidden
                universityIdInput.disabled = false; // Enable if shown
            } else if (employerRole.checked) {
                studentField.style.display = 'none';
                universityIdInput.required = false;
                universityIdInput.disabled = true; // Disable if hidden
                employerField.style.display = 'block';
                companyNameInput.required = true;
                companyNameInput.disabled = false; // Enable if shown
            }
        }

        studentRole.addEventListener('change', toggleFields);
        employerRole.addEventListener('change', toggleFields);

        // Initial check on load
        toggleFields();
    </script>
</body>
</html>