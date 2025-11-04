<?php
// Include backend setup
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Log In to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/auth.css'];
$body_class = 'auth-page';

// Include header
include __DIR__ . '/../includes/header.php';
?>

 <main class="main-content">
        <div class="header-text">
            <h1>Log In to Your Account</h1>
            <p>Connecting students with part-time jobs and internships</p>
        </div>

        <div class="login-card">
            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" placeholder="Account" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Password" required>
                </div>
                

                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit" class="login-btn">Login</button>

                <div class="register-link">
                    Don't have you account? <a href="register.php">Register Now</a>
                </div>
            </form>
        </div>
    </main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
