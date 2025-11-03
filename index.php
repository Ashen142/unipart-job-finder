<?php
// Include backend setup
include __DIR__ . '/includes/db_connect.php';
include __DIR__ . '/includes/functions.php';

// Page settings
$page_title = "Home - UniPart";
$extraCSS = ["assets/css/index.css"];

// Include header
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Find Your Next Opportunity</h1>
        <p>Connecting university students with part-time jobs and internships</p>

        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search keywords">
            <input type="text" class="search-input" placeholder="Location">
            <button class="search-btn">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>
</section>

<!-- Featured Jobs Section -->
<section class="featured-section">
    <h2>Featured Jobs</h2>
    <div class="job-cards">

        <!-- Job Card 1 -->
        <div class="job-card">
            <div class="job-card-header">
                <div class="job-icon">🌐</div>
                <div class="salary-badge">$</div>
            </div>
            <h3 class="job-title">Part-Time Web Developer</h3>
            <p class="job-info">ABC Software Pvt Ltd</p>
            <p class="job-info"><i class="fa fa-map-marker-alt"></i> Colombo</p>
            <button class="view-details-btn">View Details</button>
        </div>

        <!-- Job Card 2 -->
        <div class="job-card">
            <div class="job-card-header">
                <div class="job-icon">💼</div>
                <div class="salary-badge">$</div>
            </div>
            <h3 class="job-title">Marketing Intern</h3>
            <p class="job-info">XYZ Marketing Agency</p>
            <p class="job-info"><i class="fa fa-map-marker-alt"></i> Kandy</p>
            <button class="view-details-btn">View Details</button>
        </div>

        <!-- Job Card 3 -->
        <div class="job-card">
            <div class="job-card-header">
                <div class="job-icon">🛍️</div>
                <div class="salary-badge">$</div>
            </div>
            <h3 class="job-title">Retail Sales Associate</h3>
            <p class="job-info">Sunrise Mall</p>
            <p class="job-info"><i class="fa fa-map-marker-alt"></i> Galle</p>
            <button class="view-details-btn">View Details</button>
        </div>

    </div>

    <div class="browse-all">
        <button class="browse-all-btn">Browse All Jobs</button>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
