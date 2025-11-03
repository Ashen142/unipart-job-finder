<?php
$page_title = $page_title ?? "UniPart - Part-Time Job Finder";
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
    <link rel="stylesheet" href="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/css/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Page-specific CSS -->
    <?php
    if (isset($extraCSS) && is_array($extraCSS)) {
        foreach ($extraCSS as $cssFile) {
            echo '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">' . PHP_EOL;
        }
    }
    ?>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            UniPart <i class="fa fa-briefcase"></i>
        </div>
        <nav>
            <a href="/Unipart-job-finder/index.php">Home</a>
            <a href="/unipart-job-finder/jobs/view-jobs.php">Jobs</a>
            <a href="/unipart-job-finder/dashboard/student-dashboard.php">Dashboard</a>
            <a href="/unipart-job-finder/profiles/student-profile.php">Profile</a>
        </nav>
        <a href="/Unipart-job-finder/auth/login.php" class="nav-button">Login / Register</a>

    </header>

    <main class="page-background">
