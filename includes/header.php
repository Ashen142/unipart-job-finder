<?php
 
$home = "../index.php"; 

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . ($page_title ?? "UniPart - Part-Time Job Finder") . '</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     if (isset($extraCSS)) {
        foreach ($extraCSS as $cssFile) {
            echo '<link rel="stylesheet" href="' . $cssFile . '">';
        }
    }
</head>
<body>
    <header class="navbar">
        <div class="logo">UniPart <i class="fa fa-briefcase"></i></div>
        <nav>
            <a href="' . $home . '">Home</a>
            <a href="../jobs/view-jobs.php">Jobs</a>
            <a href="../dashboard/student-dashboard.php">Dashboard</a>
            <a href="../profiles/student-profile.php">Profile</a>
            </nav>
        <a href="register.php" class="nav-button">Login / Register</a>
    </header>
    <main class="page-background">'; 
?>