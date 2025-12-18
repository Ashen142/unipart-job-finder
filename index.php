<?php
// Include backend setup
include __DIR__ . '/includes/db_connect.php';
include __DIR__ . '/includes/functions.php';

// Page settings
$page_title = "Home - UniPart";
$extraCSS = ["/Unipart-job-finder/assets/css/index.css"];

// Fetch featured jobs from database
$query_featured = "SELECT j.job_id, j.title, j.location, j.pay, j.type, e.company_name, j.created_at
                   FROM jobs j
                   JOIN employers e ON j.employer_id = e.employer_id
                   WHERE j.status = 'Active'
                   ORDER BY j.created_at DESC
                   LIMIT 6";
$result_featured = mysqli_query($conn, $query_featured);
$featured_jobs = [];
while ($row = mysqli_fetch_assoc($result_featured)) {
    $featured_jobs[] = $row;
}

// Get statistics
$query_stats = "SELECT
    (SELECT COUNT(*) FROM jobs WHERE status = 'Active') as total_jobs,
    (SELECT COUNT(*) FROM users WHERE role = 'Student') as total_students,
    (SELECT COUNT(*) FROM users WHERE role = 'Employer') as total_employers,
    (SELECT COUNT(*) FROM applications) as total_applications";
$result_stats = mysqli_query($conn, $query_stats);
$stats = mysqli_fetch_assoc($result_stats);

// Include header
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Find Your Next Opportunity</h1>
        <p>Connecting university students with part-time jobs and internships across Sri Lanka</p>

        <div class="search-container">
            <input type="text" class="search-input" placeholder="Job title, keywords, or company" id="search-keywords">
            <input type="text" class="search-input" placeholder="Location" id="search-location">
            <button class="search-btn" onclick="performSearch()">
                <i class="fa fa-search"></i> Search Jobs
            </button>
        </div>

        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($stats['total_jobs']); ?>+</span>
                <span class="stat-label">Active Jobs</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($stats['total_students']); ?>+</span>
                <span class="stat-label">Students</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo number_format($stats['total_employers']); ?>+</span>
                <span class="stat-label">Employers</span>
            </div>
        </div>
    </div>
    <div class="hero-image">
        <img src="assets/images/hero-illustration.png" alt="Students working" onerror="this.style.display='none'">
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2>How UniPart Works</h2>
        <p class="section-subtitle">Get started in just 3 simple steps</p>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Create Account</h3>
                <p>Sign up as a student or employer and complete your profile</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Find or Post Jobs</h3>
                <p>Browse opportunities or post jobs that match your needs</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Connect & Succeed</h3>
                <p>Apply for jobs or hire talent and start your journey</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs Section -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2>Latest Job Opportunities</h2>
            <p>Discover exciting part-time and internship opportunities</p>
        </div>

        <div class="job-cards">
            <?php if (empty($featured_jobs)): ?>
                <div class="no-jobs">
                    <i class="fas fa-briefcase"></i>
                    <h3>No jobs available</h3>
                    <p>Check back later for new opportunities!</p>
                </div>
            <?php else: ?>
                <?php foreach ($featured_jobs as $job): ?>
                    <div class="job-card">
                        <div class="job-card-header">
                            <div class="job-type-badge <?php echo strtolower(str_replace(' ', '-', $job['type'])); ?>">
                                <?php echo htmlspecialchars($job['type']); ?>
                            </div>
                            <div class="job-salary">
                                <i class="fas fa-dollar-sign"></i>
                                <?php echo htmlspecialchars($job['pay']); ?>
                            </div>
                        </div>

                        <h3 class="job-title">
                            <a href="jobs/job-details.php?id=<?php echo $job['job_id']; ?>">
                                <?php echo htmlspecialchars($job['title']); ?>
                            </a>
                        </h3>

                        <div class="job-company">
                            <i class="fas fa-building"></i>
                            <?php echo htmlspecialchars($job['company_name']); ?>
                        </div>

                        <div class="job-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($job['location']); ?>
                        </div>

                        <div class="job-meta">
                            <span class="job-date">
                                <i class="fas fa-clock"></i>
                                <?php echo date('M d', strtotime($job['created_at'])); ?>
                            </span>
                        </div>

                        <a href="jobs/job-details.php?id=<?php echo $job['job_id']; ?>" class="view-details-btn">
                            View Details
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="browse-all">
            <a href="jobs/view-jobs.php" class="browse-all-btn">
                <i class="fas fa-list"></i> Browse All Jobs
            </a>
        </div>
    </div>
</section>

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

<?php include __DIR__ . '/includes/footer.php'; ?>
