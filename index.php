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

<<<<<<< HEAD
=======
<!-- Job Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Explore Job Categories</h2>
            <p>Find opportunities in your field of interest</p>
        </div>

        <div class="categories-grid">
            <a href="jobs/view-jobs.php?category=IT" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>IT & Software</h3>
                <p>Development, design, and tech roles</p>
            </a>

            <a href="jobs/view-jobs.php?category=Marketing" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3>Marketing</h3>
                <p>Digital marketing and content creation</p>
            </a>

            <a href="jobs/view-jobs.php?category=Writing" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <h3>Writing & Content</h3>
                <p>Content writing and copywriting</p>
            </a>

            <a href="jobs/view-jobs.php?category=Sales" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Sales & Retail</h3>
                <p>Customer service and sales positions</p>
            </a>

            <a href="jobs/view-jobs.php?category=Design" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Design & Creative</h3>
                <p>Graphic design and creative roles</p>
            </a>

            <a href="jobs/view-jobs.php?category=Education" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Education</h3>
                <p>Tutoring and educational support</p>
            </a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_jobs']); ?>+</h3>
                    <p>Active Job Postings</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_students']); ?>+</h3>
                    <p>Registered Students</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_employers']); ?>+</h3>
                    <p>Partner Employers</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_applications']); ?>+</h3>
                    <p>Successful Connections</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of students and employers already using UniPart</p>
            <div class="cta-buttons">
                <a href="auth/register.php" class="cta-btn primary">
                    <i class="fas fa-user-plus"></i> Join as Student
                </a>
                <a href="auth/register.php" class="cta-btn secondary">
                    <i class="fas fa-building"></i> Register as Employer
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function performSearch() {
    const keywords = document.getElementById('search-keywords').value;
    const location = document.getElementById('search-location').value;

    let url = 'jobs/view-jobs.php?';
    if (keywords) url += 'search=' + encodeURIComponent(keywords);
    if (location) url += '&location=' + encodeURIComponent(location);

    window.location.href = url;
}

// Allow search on Enter key
document.getElementById('search-keywords').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performSearch();
});
document.getElementById('search-location').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performSearch();
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.background = 'rgba(0, 0, 0, 0.95)';
        navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.2)';
    } else {
        navbar.style.background = 'rgba(0, 0, 0, 0.8)';
        navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
    }
});
</script>

>>>>>>> parent of 72cb5f6 (Redesign site with light theme and update styles)
<?php include __DIR__ . '/includes/footer.php'; ?>
