<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default page title
$page_title = $page_title ?? "UniPart - Part-Time Job Finder";

// Site root used for root-relative asset URLs
$rootFolder = '/Unipart-job-finder';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Main Styles -->
    <?php
    $mainCss = $rootFolder . '/assets/css/style.css';
    $mainCssFile = $_SERVER['DOCUMENT_ROOT'] . $mainCss;
    $mainCssUrl = $mainCss;
    if (file_exists($mainCssFile)) {
        $mainCssUrl .= '?v=' . filemtime($mainCssFile);
    }
    ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($mainCssUrl) ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Page-specific CSS -->
    <?php
    if (isset($extraCSS) && is_array($extraCSS)) {
        foreach ($extraCSS as $cssFile) {
            // Ensure root-relative path so it works from any folder
            if (strpos($cssFile, '/') !== 0) {
                $cssFile = rtrim($rootFolder, '/') . '/' . ltrim($cssFile, '/');
            }

            $cssUrl = $cssFile;
            $cssFilePath = $_SERVER['DOCUMENT_ROOT'] . $cssFile;
            if (file_exists($cssFilePath)) {
                // Append filemtime for cache-busting
                $cssUrl .= '?v=' . filemtime($cssFilePath);
            }

            echo '<link rel="stylesheet" href="' . htmlspecialchars($cssUrl) . '">' . PHP_EOL;
        }
    }
    ?>

    <!--  SweetAlert2 for nicer alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //  alert message
        function loginAlert() {
            //  Modern popup
            Swal.fire('Login Required', 'Please login first to access this page.', 'info');

        }
    </script>
</head>

<body class="<?= htmlspecialchars($body_class ?? '') ?>">

    <!-- Navigation -->
    <header class="navbar">
        <div class="logo">
            <a href="<?= $rootFolder ?>/index.php" class="logo-text">
                UniPart <i class="fa fa-briefcase"></i>
            </a>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php
            // Get user role from session
            $role = strtolower($_SESSION['role'] ?? '');
            // Set dashboard and profile URLs based on role
            if ($role === 'student') {
                $dashboardLink = $rootFolder . '/dashboard/student-dashboard.php';
                $profileLink = $rootFolder . '/profiles/student-profile.php';
            } elseif ($role === 'employer') {
                $dashboardLink = $rootFolder . '/dashboard/employer-dashboard.php';
                $profileLink = $rootFolder . '/profiles/employer-profile.php';
            } elseif ($role === 'admin') {
                $dashboardLink = $rootFolder . '/dashboard/admin-dashboard.php';
                $profileLink = $rootFolder . '/admin/manage-users.php'; // or admin profile if you have one
            } else {
                $dashboardLink = '#';
                $profileLink = '#';
            }
            ?>

            <!--  Logged-in Navbar -->
            <nav>
                <a href="<?= $rootFolder ?>/index.php">Home</a>
                <a href="<?= $rootFolder ?>/jobs/view-jobs.php">Jobs</a>
                <a href="<?= $dashboardLink ?>">Dashboard</a>
                <a href="<?= $profileLink ?>">Profile</a>
            </nav>
            <a href="<?= $rootFolder ?>/auth/logout.php" class="nav-button">Logout</a>


        <?php else: ?>
            <!--  Not logged in – show alert on protected links -->
            <nav>
                <a href="<?= $rootFolder ?>/index.php">Home</a>
                <a href="#" onclick="loginAlert()">Jobs</a>
                <a href="#" onclick="loginAlert()">Dashboard</a>
                <a href="#" onclick="loginAlert()">Profile</a>
            </nav>
            <a href="<?= $rootFolder ?>/auth/login.php" class="nav-button">Login</a>
        <?php endif; ?>
    </header>

    <main class="page-background">