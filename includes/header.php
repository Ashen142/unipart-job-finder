<?php
// ===============================
// UniPart - Header Include File
// ===============================

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
        // Compute main stylesheet URL and append file modification time for cache-busting
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
                // If the file is a root-relative path, try to append filemtime for cache-busting
                $cssUrl = $cssFile;
                if (strpos($cssFile, '/') === 0) {
                    $cssFilePath = $_SERVER['DOCUMENT_ROOT'] . $cssFile;
                    if (file_exists($cssFilePath)) {
                        $cssUrl .= '?v=' . filemtime($cssFilePath);
                    }
                }
                echo '<link rel="stylesheet" href="' . htmlspecialchars($cssUrl) . '">' . PHP_EOL;
            }
        }
    ?>
</head>
<body class="<?= htmlspecialchars($body_class ?? '') ?>">

    <!-- Navigation -->
    <header class="navbar">
        <div class="logo">
            <a href="<?= $rootFolder ?>/index.php" class="logo-text">
                UniPart <i class="fa fa-briefcase"></i>
            </a>
        </div>
        <nav>
            <a href="<?= $rootFolder ?>/index.php">Home</a>
            <a href="<?= $rootFolder ?>/jobs/view-jobs.php">Jobs</a>
            <a href="<?= $rootFolder ?>/dashboard/student-dashboard.php">Dashboard</a>
            <a href="<?= $rootFolder ?>/profiles/student-profile.php">Profile</a>
        </nav>
        <a href="<?= $rootFolder ?>/auth/login.php" class="nav-button">Login / Register</a>
    </header>

    <main class="page-background">
