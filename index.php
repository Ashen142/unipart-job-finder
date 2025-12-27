<?php
// Include backend setup
include __DIR__ . '/includes/db_connect.php';
include __DIR__ . '/includes/functions.php';

// Page settings
$page_title = "Home - UniPart";
// Use root-relative path so the stylesheet loads from any page
$extraCSS = ["/Unipart-job-finder/assets/css/index.css"];

// Fetch featured jobs from database
$featuredJobsQuery = "SELECT j.*, e.company_name, e.logo, e.industry 
                      FROM jobs j 
                      JOIN employers e ON j.employer_id = e.employer_id 
                      WHERE j.status = 'Active' 
                      ORDER BY j.posted_at DESC 
                      LIMIT 6";
$featuredJobsResult = $conn->query($featuredJobsQuery);
$featuredJobs = $featuredJobsResult->fetch_all(MYSQLI_ASSOC);

// Get statistics for the about section
$statsQuery = "SELECT 
                (SELECT COUNT(*) FROM users WHERE role = 'Student') as total_students,
                (SELECT COUNT(*) FROM employers) as total_employers,
                (SELECT COUNT(*) FROM jobs WHERE status = 'Active') as total_jobs";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult ? $statsResult->fetch_assoc() : null;
// Ensure defaults to avoid notices and provide safe integers
if (empty($stats) || !is_array($stats)) {
    $stats = [
        'total_students' => 0,
        'total_employers' => 0,
        'total_jobs' => 0
    ];
} else {
    $stats['total_students'] = intval($stats['total_students']);
    $stats['total_employers'] = intval($stats['total_employers']);
    $stats['total_jobs'] = intval($stats['total_jobs']);
}

// Calculate success rate: prefer accepted / (accepted + rejected), else accepted / total, default 0
$successQuery = "SELECT 
                 COUNT(*) as total_apps,
                 SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted_apps,
                 SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_apps
                 FROM applications";
$successResult = $conn->query($successQuery);
$successData = $successResult ? $successResult->fetch_assoc() : ['total_apps' => 0, 'accepted_apps' => 0, 'rejected_apps' => 0];

$totalApps = intval($successData['total_apps'] ?? 0);
$acceptedApps = intval($successData['accepted_apps'] ?? 0);
$rejectedApps = intval($successData['rejected_apps'] ?? 0);

if (($acceptedApps + $rejectedApps) > 0) {
    // Use decided applications (accepted + rejected) when available
    $successRate = (int) round(($acceptedApps / ($acceptedApps + $rejectedApps)) * 100);
} elseif ($totalApps > 0) {
    // Fallback to accepted / total if no decisions recorded
    $successRate = (int) round(($acceptedApps / $totalApps) * 100);
} else {
    // No data yet
    $successRate = 0;
}

// Allow forcing the displayed success rate from a central config constant for testing or demonstration
if (defined('FORCE_SUCCESS_RATE') && FORCE_SUCCESS_RATE !== null) {
    $successRate = (int) FORCE_SUCCESS_RATE;
}

// Get job categories with counts
$categoriesQuery = "SELECT 
                    category,
                    COUNT(*) as job_count
                    FROM jobs 
                    WHERE status = 'Active' AND category IS NOT NULL
                    GROUP BY category
                    ORDER BY job_count DESC";
$categoriesResult = $conn->query($categoriesQuery);
$categories = $categoriesResult->fetch_all(MYSQLI_ASSOC);

// Define category icons
$categoryIcons = [
    'Technology & IT' => '💻',
    'Digital Marketing' => '📱',
    'Content Writing' => '✏️',
    'Graphic Design' => '🎨',
    'Tutoring' => '🧑‍🏫',
    'Retail & Sales' => '🛍️',
    'Data Entry' => '📊',
    'Customer Service' => '📞',
    'Media & Video' => '🎬',
    'Research Assistant' => '🔬',
    'Fitness & Health' => '🏋️',
    'Online Jobs' => '🌐'
];


// Include header
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1 class="fade-in">Find Your Next Opportunity</h1>
        <p class="fade-in" style="animation-delay: 0.2s;">Connecting university students with part-time jobs and internships</p>

        <div class="search-container fade-in" style="animation-delay: 0.4s;">
            <input type="text" class="search-input" placeholder="Search keywords" id="searchKeywords">
            <input type="text" class="search-input" placeholder="Location" id="searchLocation">
            <button class="search-btn" onclick="searchJobs()">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>
</section>



<!-- Get Started in Simple Steps Section -->
<section class="steps-section">
    <div class="container">
        <h2 class="section-title scroll-reveal">Get Started in Simple Steps</h2>
        <p class="section-subtitle scroll-reveal">Your journey to the perfect part-time job begins here</p>
        
        <div class="steps-grid">
            <div class="step-card scroll-reveal" data-delay="0">
                <div class="step-number">1</div>
                <div class="step-icon">📝</div>
                <h3 class="step-title">Create Account</h3>
                <p class="step-description">Sign up as a student or employer in just minutes with your university email</p>
            </div>

            <div class="step-card scroll-reveal" data-delay="100">
                <div class="step-number">2</div>
                <div class="step-icon">🔍</div>
                <h3 class="step-title">Search & Filter</h3>
                <p class="step-description">Browse hundreds of part-time jobs tailored to your skills and schedule</p>
            </div>

            <div class="step-card scroll-reveal" data-delay="200">
                <div class="step-number">3</div>
                <div class="step-icon">📄</div>
                <h3 class="step-title">Apply Easily</h3>
                <p class="step-description">Submit your application with one click using your profile and resume</p>
            </div>

            <div class="step-card scroll-reveal" data-delay="300">
                <div class="step-number">4</div>
                <div class="step-icon">🎉</div>
                <h3 class="step-title">Get Hired</h3>
                <p class="step-description">Connect with employers and start earning while you learn</p>
            </div>
        </div>
    </div>
</section>

<!-- Select Your Account Type Section -->
<section class="account-type-section">
    <div class="container">
        <h2 class="section-title scroll-reveal">Select Your Account Type</h2>
        <p class="section-subtitle scroll-reveal">Choose the option that best describes you</p>
        
        <div class="account-types-grid">
            <div class="account-type-card student-card scroll-reveal" data-delay="0">
                <div class="account-icon">🎓</div>
                <h3 class="account-title">I'm a Student</h3>
                <p class="account-description">Looking for part-time work, internships, or online opportunities to gain experience and earn money</p>
                <ul class="account-features">
                    <li><i class="fa fa-check"></i> Browse thousands of jobs</li>
                    <li><i class="fa fa-check"></i> Apply with one click</li>
                    <li><i class="fa fa-check"></i> Track your applications</li>
                    <li><i class="fa fa-check"></i> Build your resume</li>
                </ul>
                <a href="auth/register.php?type=student" class="account-btn student-btn">Register as Student</a>
            </div>

            <div class="account-type-card employer-card scroll-reveal" data-delay="200">
                <div class="account-icon">💼</div>
                <h3 class="account-title">I'm an Employer</h3>
                <p class="account-description">Looking to hire talented university students for part-time positions, internships, or project-based work</p>
                <ul class="account-features">
                    <li><i class="fa fa-check"></i> Post unlimited jobs</li>
                    <li><i class="fa fa-check"></i> Reach qualified students</li>
                    <li><i class="fa fa-check"></i> Manage applications easily</li>
                    <li><i class="fa fa-check"></i> Build your brand</li>
                </ul>
                <a href="auth/register.php?type=employer" class="account-btn employer-btn">Register as Employer</a>
            </div>
        </div>
    </div>
</section>

<!-- Explore Job Categories Section -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title scroll-reveal">Explore Job Categories Across Multiple Fields</h2>
        <p class="section-subtitle scroll-reveal">Find opportunities in your area of interest</p>
        
        <div class="categories-grid">
            <?php 
            $delay = 0;
            
            // If we have categories from database, display them
            if (!empty($categories)) {
                foreach ($categories as $cat) {
                    $icon = $categoryIcons[$cat['category']] ?? '💻' ;
                    ?>
                    <a href="<?php echo BASE_URL; ?>jobs/view-jobs.php?category=<?php echo urlencode($cat['category']); ?>" class="category-card scroll-reveal" data-delay="<?php echo $delay; ?>">
                        <div class="category-icon"><?php echo $icon; ?></div>
                        <h3 class="category-title"><?php echo htmlspecialchars($cat['category']); ?></h3>
                        <p class="category-count"><?php echo $cat['job_count']; ?>+ jobs</p>
                    </a>
                    <?php
                    $delay += 50;
                }
            } else {
                // Default categories if database is empty
                $defaultCategories = [
                    ['name' => 'Technology & IT', 'icon' => '💻', 'count' => 0],
                    ['name' => 'Digital Marketing', 'icon' => '📱', 'count' => 0],
                    ['name' => 'Content Writing', 'icon' => '✏️', 'count' => 0],
                    ['name' => 'Graphic Design', 'icon' => '🎨', 'count' => 0],
                    ['name' => 'Tutoring', 'icon' => '🧑‍🏫', 'count' => 0],
                    ['name' => 'Retail & Sales', 'icon' => '🛍️', 'count' => 0],
                    ['name' => 'Data Entry', 'icon' => '📊', 'count' => 0],
                    ['name' => 'Customer Service', 'icon' => '📞', 'count' => 0],
                    ['name' => 'Media & Video', 'icon' => '🎬', 'count' => 0],
                    ['name' => 'Research Assistant', 'icon' => '🔬', 'count' => 0],
                    ['name' => 'Fitness & Health', 'icon' => '🏋️', 'count' => 0],
                    ['name' => 'Online Jobs', 'icon' => '🌐', 'count' => 0]
                ];
                
                foreach ($defaultCategories as $cat) {
                    ?>
                    <a href="<?php echo BASE_URL; ?>jobs/view-jobs.php?category=<?php echo urlencode($cat['name']); ?>" class="category-card scroll-reveal" data-delay="<?php echo $delay; ?>">
                        <div class="category-icon"><?php echo $cat['icon']; ?></div>
                        <h3 class="category-title"><?php echo $cat['name']; ?></h3>
                        <p class="category-count"><?php echo $cat['count']; ?> jobs</p>
                    </a>
                    <?php
                    $delay += 50;
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Featured Jobs Section -->
<section class="featured-section">
    <div class="container">
        <h2 class="section-title scroll-reveal">Featured Part-Time Opportunities</h2>
        <p class="section-subtitle scroll-reveal">Handpicked jobs from top employers</p>
        
        <div class="job-cards">
            <?php 
            if (!empty($featuredJobs)) {
                $delay = 0;
                foreach ($featuredJobs as $job) {
                    // Determine icon based on category
                    $jobIcon = '💼';
                    if (stripos($job['category'], 'technology') !== false || stripos($job['category'], 'IT') !== false) {
                        $jobIcon = '💻';
                    } elseif (stripos($job['category'], 'design') !== false) {
                        $jobIcon = '🎨';
                    } elseif (stripos($job['category'], 'marketing') !== false) {
                        $jobIcon = '📱';
                    } elseif (stripos($job['category'], 'writing') !== false) {
                        $jobIcon = '✏️';
                    } elseif (stripos($job['category'], 'tutoring') !== false || stripos($job['category'], 'education') !== false) {
                        $jobIcon = '🧑‍🏫';
                    } elseif (stripos($job['category'], 'retail') !== false || stripos($job['category'], 'sales') !== false) {
                        $jobIcon = '🛍️';
                    }
                    
                    // Extract skills/requirements for tags
                    $tags = [];
                    if (!empty($job['requirements'])) {
                        $reqLines = explode(',', $job['requirements']);
                        $tags = array_slice(array_map('trim', $reqLines), 0, 3);
                    }
                    if (empty($tags) && !empty($job['type'])) {
                        $tags[] = $job['type'];
                    }
                    ?>
                    <div class="job-card scroll-reveal" data-delay="<?php echo $delay; ?>">
                        <div class="job-card-header">
                            <div class="job-icon"><?php echo $jobIcon; ?></div>
                            <div class="salary-badge">$</div>
                        </div>
                        <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p class="job-info"><i class="fa fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
                        <p class="job-info"><i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
                        <?php if (!empty($job['type'])): ?>
                            <p class="job-info"><i class="fa fa-clock"></i> <?php echo htmlspecialchars($job['type']); ?></p>
                        <?php endif; ?>
                        <p class="job-description">
                            <?php 
                            $description = strip_tags($job['description']);
                            echo htmlspecialchars(substr($description, 0, 120)) . (strlen($description) > 120 ? '...' : ''); 
                            ?>
                        </p>
                        <?php if (!empty($tags)): ?>
                            <div class="job-tags">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="job-tag"><?php echo htmlspecialchars($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>jobs/job-details.php?job_id=<?php echo $job['job_id']; ?>" class="view-details-btn">View Details</a>
                    </div>
                    <?php
                    $delay += 100;
                }
            } else {
                echo '<p class="text-center" style="grid-column: 1/-1; color: #6c757d; font-size: 1.1rem;">No jobs available at the moment. Check back soon!</p>';
            }
            ?>
        </div>

        <div class="browse-all scroll-reveal">
            <a href="<?php echo BASE_URL; ?>jobs/view-jobs.php" class="browse-all-btn">Browse All Jobs</a>
        </div>
    </div>
</section>



<!-- Platform Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title scroll-reveal">Our Platform Features</h2>
        <p class="section-subtitle scroll-reveal">Everything you need for a seamless job search experience</p>
        
        <div class="features-grid">
            <div class="feature-card scroll-reveal" data-delay="0">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title">Secure & Verified</h3>
                <p class="feature-description">All employers are verified to ensure student safety. Your data is protected with industry-standard encryption.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="50">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Instant Applications</h3>
                <p class="feature-description">Apply to multiple jobs with one click using your saved profile and resume. No repetitive form filling.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="100">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Application Tracking</h3>
                <p class="feature-description">Monitor all your applications in one place. Get real-time updates on your application status.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="150">
                <div class="feature-icon">🎯</div>
                <h3 class="feature-title">Smart Matching</h3>
                <p class="feature-description">Our algorithm matches you with jobs that fit your skills, schedule, and career goals.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="200">
                <div class="feature-icon">💬</div>
                <h3 class="feature-title">Direct Communication</h3>
                <p class="feature-description">Chat directly with employers through our secure messaging system. Schedule interviews easily.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="250">
                <div class="feature-icon">⭐</div>
                <h3 class="feature-title">Ratings & Reviews</h3>
                <p class="feature-description">Read reviews from other students and rate your experience to help the community.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="300">
                <div class="feature-icon">📱</div>
                <h3 class="feature-title">Mobile Friendly</h3>
                <p class="feature-description">Access UniPart on any device. Search and apply for jobs on the go with our responsive design.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="350">
                <div class="feature-icon">🔔</div>
                <h3 class="feature-title">Smart Notifications</h3>
                <p class="feature-description">Get instant alerts for new job postings that match your preferences and application updates.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="400">
                <div class="feature-icon">📄</div>
                <h3 class="feature-title">Resume Builder</h3>
                <p class="feature-description">Create a professional resume using our built-in templates. Download and share anytime.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="450">
                <div class="feature-icon">🎓</div>
                <h3 class="feature-title">Student Focused</h3>
                <p class="feature-description">All jobs are curated specifically for university students with flexible schedules and fair pay.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="500">
                <div class="feature-icon">🌍</div>
                <h3 class="feature-title">Remote Options</h3>
                <p class="feature-description">Access hundreds of remote and online job opportunities you can do from anywhere.</p>
            </div>

            <div class="feature-card scroll-reveal" data-delay="550">
                <div class="feature-icon">📈</div>
                <h3 class="feature-title">Career Growth</h3>
                <p class="feature-description">Build your professional network and gain experience that launches your career.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title scroll-reveal">Ready to Start Your Journey?</h2>
            <p class="cta-description scroll-reveal">Join thousands of students who are already earning while learning on UniPart</p>
            <div class="cta-buttons scroll-reveal">
                <a href="<?php echo BASE_URL; ?>auth/register.php" class="cta-btn primary-cta">Get Started Now</a>
                <a href="<?php echo BASE_URL; ?>jobs/view-jobs.php" class="cta-btn secondary-cta">Browse Jobs</a>
            </div>
        </div>
    </div>
</section>
<!-- About UniPart Section -->
<section class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text scroll-reveal">
                <h2 class="section-title">About UniPart</h2>
                <p class="about-description">
                    UniPart is Sri Lanka's premier platform connecting university students with meaningful part-time employment opportunities. We understand the unique challenges students face in balancing academics with financial needs, and we're here to bridge that gap.
                </p>
                <p class="about-description">
                    Founded in 2024, our mission is to empower students by providing them access to flexible, skill-building jobs that complement their education. Whether you're looking for remote work, weekend shifts, or internships that align with your field of study, UniPart is your trusted partner in career development.
                </p>
                <div class="about-stats">
                    <div class="stat-item scroll-reveal" data-delay="0">
                        <h3 class="stat-number counter" data-target="<?= htmlspecialchars($stats['total_students'] ?? 0) ?>">0</h3>
                        <p class="stat-label"> 🎓 Active Students</p>
                    </div>
                    <div class="stat-item scroll-reveal" data-delay="100">
                        <h3 class="stat-number counter" data-target="<?= htmlspecialchars($stats['total_employers'] ?? 0) ?>">0</h3>
                        <p class="stat-label"> 💼 Employers</p>
                    </div>
                    <div class="stat-item scroll-reveal" data-delay="200">
                        <h3 class="stat-number counter" data-target="<?= htmlspecialchars($stats['total_jobs'] ?? 0) ?>">0</h3>
                        <p class="stat-label">📋 Jobs Posted</p>
                    </div>
                    <div class="stat-item scroll-reveal" data-delay="300">
                        <h3 class="stat-number"><span class="counter" data-target="<?= htmlspecialchars($successRate ?? 95) ?>">0</span>%</h3>
                        <p class="stat-label">✅ Success Rate</p>
                    </div>
                </div>
            </div>
            <div class="about-image scroll-reveal" data-delay="200">
                <img src="assets/images/about-illustration.svg" alt="About UniPart" onerror="this.style.display='none'">
                <div class="about-placeholder">
                    <i class="fa fa-users" style="font-size: 120px; color: #007BFF;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Search functionality
function searchJobs() {
    const keywords = document.getElementById('searchKeywords').value;
    const location = document.getElementById('searchLocation').value;
    window.location.href = `jobs/view-jobs.php?keywords=${encodeURIComponent(keywords)}&location=${encodeURIComponent(location)}`;
}

// Allow Enter key to trigger search
document.getElementById('searchKeywords').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchJobs();
});
document.getElementById('searchLocation').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchJobs();
});

// Scroll Reveal Animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const delay = entry.target.getAttribute('data-delay') || 0;
            setTimeout(() => {
                entry.target.classList.add('revealed');
            }, delay);
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe all elements with scroll-reveal class
document.addEventListener('DOMContentLoaded', function() {
    const revealElements = document.querySelectorAll('.scroll-reveal');
    revealElements.forEach(el => observer.observe(el));
});

// Counter Animation for Statistics
function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-target'));
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;

    const updateCounter = () => {
        current += increment;
        if (current < target) {
            element.textContent = Math.floor(current).toLocaleString();
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target.toLocaleString();
        }
    };
    updateCounter();
}

// Counter Observer
const counterObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const counter = entry.target.querySelector('.counter');
            if (counter && !counter.classList.contains('counted')) {
                counter.classList.add('counted');
                animateCounter(counter);
            }
            counterObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

// Observe stat items
document.addEventListener('DOMContentLoaded', function() {
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach(item => counterObserver.observe(item));
});
</script>


<?php include __DIR__ . '/includes/footer.php'; ?>

