<?php

include '../includes/db_connect.php'; 
include '../includes/functions.php';  
$page_title = "Home - UniPart ";
// $extraCSS = "../assets/css/index.css"; 
include '../includes/header.php'; 
?>  
<link rel="stylesheet" href="assets/css/index.css"> 
    <section class="hero">
        <h1>Find Your Next Opportunity</h1>
        <p>Connecting students with part-time jobs and internships</p>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search keywords">
            <input type="text" class="search-input" placeholder="Location">
            <button class="search-btn">
                🔍 Search
            </button>
        </div>
    </section>

    <section class="featured-section">
        <h2>Featured Jobs</h2>
        <div class="job-cards">
            <div class="job-card">
                <div class="job-card-header">
                    <div class="job-icon">🌐</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Part-Time Web Developer</h3>
                <p class="job-info">Company Name, 24px</p>
                <p class="job-info">📍 Location</p>
                <button class="view-details-btn">View Details</button>
            </div>

            <div class="job-card">
                <div class="job-card-header">
                    <div class="job-icon">🌐</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Marketing Intern</h3>
                <p class="job-info">Company Name, 16px</p>
                <p class="job-info">📍 Location</p>
                <button class="view-details-btn">View Details</button>
            </div>

            <div class="job-card">
                <div class="job-card-header">
                    <div class="job-icon">🌐</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Retail Sales Associate</h3>
                <p class="job-info">Company Name, 11px</p>
                <p class="job-info">📍 Location</p>
                <button class="view-details-btn">View Details</button>
            </div>
        </div>

        <div class="browse-all">
            <button class="browse-all-btn">Browse All Jobs</button>
        </div>
    </section>

<?php
include '../includes/footer.php'; 
?>