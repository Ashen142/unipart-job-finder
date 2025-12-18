<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

session_start(); // Required for session usage

// Page settings
$page_title = "Log In to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/auth.css'];
$body_class = 'auth-page';
$page_type = 'auth';


//  Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if email exists in users table
        $stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            //  Verify password
            if (password_verify($password, $user['password'])) {

                // Create session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = strtolower($user['role']); // make sure it's lowercase

                //  Redirect to the correct dashboard based on role
                if ($_SESSION['role'] === 'student') {
                    header("Location: ../dashboard/student-dashboard.php");
                } elseif ($_SESSION['role'] === 'employer') {
                    header("Location: ../dashboard/employer-dashboard.php");
                } elseif ($_SESSION['role'] === 'admin') {
                    header("Location: ../dashboard/admin-dashboard.php");
                } else {
                    $error = "Unknown user role!";
                }
                exit;
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}

// Include header 
include __DIR__ . '/../includes/header.php';
?>

<main class="main-content">
    <div class="login-container">
        <div class="login-card">
            <div class="header-text">
                <h1>Log In to Your Account</h1>
                <p>Connecting students with part-time jobs and internships</p>
            </div>

            <?php if (!empty($error)): ?>
                <p class="error-message" style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (isset($_GET['message']) && $_GET['message'] === 'logged_out'): ?>
                <p class="info-message" style="color: green;">You have been logged out successfully.</p>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" name="login_btn" class="login-btn">Login</button>

                <div class="register-link">
                    Don't have an account? <a href="register.php">Register Now</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>