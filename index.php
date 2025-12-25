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
            <div class="category-card scroll-reveal" data-delay="0">
                <div class="category-icon">💻</div>
                <h3 class="category-title">Technology & IT</h3>
                <p class="category-count">150+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="50">
                <div class="category-icon">📱</div>
                <h3 class="category-title">Digital Marketing</h3>
                <p class="category-count">120+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="100">
                <div class="category-icon">✏️</div>
                <h3 class="category-title">Content Writing</h3>
                <p class="category-count">95+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="150">
                <div class="category-icon">🎨</div>
                <h3 class="category-title">Graphic Design</h3>
                <p class="category-count">80+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="200">
                <div class="category-icon">🧑‍🏫</div>
                <h3 class="category-title">Tutoring</h3>
                <p class="category-count">110+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="250">
                <div class="category-icon">🛍️</div>
                <h3 class="category-title">Retail & Sales</h3>
                <p class="category-count">140+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="300">
                <div class="category-icon">📊</div>
                <h3 class="category-title">Data Entry</h3>
                <p class="category-count">75+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="350">
                <div class="category-icon">📞</div>
                <h3 class="category-title">Customer Service</h3>
                <p class="category-count">105+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="400">
                <div class="category-icon">🎬</div>
                <h3 class="category-title">Media & Video</h3>
                <p class="category-count">60+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="450">
                <div class="category-icon">🔬</div>
                <h3 class="category-title">Research Assistant</h3>
                <p class="category-count">45+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="500">
                <div class="category-icon">🏋️</div>
                <h3 class="category-title">Fitness & Health</h3>
                <p class="category-count">55+ jobs</p>
            </div>

            <div class="category-card scroll-reveal" data-delay="550">
                <div class="category-icon">🌐</div>
                <h3 class="category-title">Online Jobs</h3>
                <p class="category-count">200+ jobs</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs Section -->
<section class="featured-section">
    <div class="container">
        <h2 class="section-title scroll-reveal">Featured Part-Time Opportunities</h2>
        <p class="section-subtitle scroll-reveal">Handpicked jobs from top employers</p>
        
        <div class="job-cards">
            <!-- Job Card 1 -->
            <div class="job-card scroll-reveal" data-delay="0">
                <div class="job-card-header">
                    <div class="job-icon">🌐</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Part-Time Web Developer</h3>
                <p class="job-info"><i class="fa fa-building"></i> ABC Software Pvt Ltd</p>
                <p class="job-info"><i class="fa fa-map-marker-alt"></i> Colombo</p>
                <p class="job-info"><i class="fa fa-clock"></i> 15-20 hrs/week</p>
                <p class="job-description">Looking for a skilled web developer to work on client projects using modern frameworks.</p>
                <div class="job-tags">
                    <span class="job-tag">React</span>
                    <span class="job-tag">PHP</span>
                    <span class="job-tag">Remote</span>
                </div>
                <a href="jobs/job-details.php?id=1" class="view-details-btn">View Details</a>
            </div>

            <!-- Job Card 2 -->
            <div class="job-card scroll-reveal" data-delay="100">
                <div class="job-card-header">
                    <div class="job-icon">💼</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Marketing Intern</h3>
                <p class="job-info"><i class="fa fa-building"></i> XYZ Marketing Agency</p>
                <p class="job-info"><i class="fa fa-map-marker-alt"></i> Kandy</p>
                <p class="job-info"><i class="fa fa-clock"></i> 20 hrs/week</p>
                <p class="job-description">Join our team to learn digital marketing strategies and social media management.</p>
                <div class="job-tags">
                    <span class="job-tag">Social Media</span>
                    <span class="job-tag">SEO</span>
                    <span class="job-tag">Flexible</span>
                </div>
                <a href="jobs/job-details.php?id=2" class="view-details-btn">View Details</a>
            </div>

            <!-- Job Card 3 -->
            <div class="job-card scroll-reveal" data-delay="200">
                <div class="job-card-header">
                    <div class="job-icon">🛍️</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Retail Sales Associate</h3>
                <p class="job-info"><i class="fa fa-building"></i> Sunrise Mall</p>
                <p class="job-info"><i class="fa fa-map-marker-alt"></i> Galle</p>
                <p class="job-info"><i class="fa fa-clock"></i> 12-15 hrs/week</p>
                <p class="job-description">Friendly and enthusiastic individuals needed for customer service and sales.</p>
                <div class="job-tags">
                    <span class="job-tag">Weekends</span>
                    <span class="job-tag">Commission</span>
                    <span class="job-tag">Training</span>
                </div>
                <a href="jobs/job-details.php?id=3" class="view-details-btn">View Details</a>
            </div>

            <!-- Job Card 4 -->
            <div class="job-card scroll-reveal" data-delay="300">
                <div class="job-card-header">
                    <div class="job-icon">🧑‍🏫</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Online Tutor - Mathematics</h3>
                <p class="job-info"><i class="fa fa-building"></i> EduHub Online</p>
                <p class="job-info"><i class="fa fa-map-marker-alt"></i> Remote</p>
                <p class="job-info"><i class="fa fa-clock"></i> Flexible hours</p>
                <p class="job-description">Help O/L and A/L students excel in mathematics through online tutoring sessions.</p>
                <div class="job-tags">
                    <span class="job-tag">Online</span>
                    <span class="job-tag">Flexible</span>
                    <span class="job-tag">High Pay</span>
                </div>
                <a href="jobs/job-details.php?id=4" class="view-details-btn">View Details</a>
            </div>

            <!-- Job Card 5 -->
            <div class="job-card scroll-reveal" data-delay="400">
                <div class="job-card-header">
                    <div class="job-icon">🎨</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Graphic Designer</h3>
                <p class="job-info"><i class="fa fa-building"></i> Creative Studio LK</p>
                <p class="job-info"><i class="fa fa-map-marker-alt"></i> Colombo</p>
                <p class="job-info"><i class="fa fa-clock"></i> 10-15 hrs/week</p>
                <p class="job-description">Design social media graphics, posters, and marketing materials for various clients.</p>
                <div class="job-tags">
                    <span class="job-tag">Photoshop</span>
                    <span class="job-tag">Illustrator</span>
                    <span class="job-tag">Portfolio</span>
                </div>
                <a href="jobs/job-details.php?id=5" class="view-details-btn">View Details</a>
            </div>

            <!-- Job Card 6 -->
            <div class="job-card scroll-reveal" data-delay="500">
                <div class="job-card-header">
                    <div class="job-icon">✏️</div>
                    <div class="salary-badge">$</div>
                </div>
                <h3 class="job-title">Content Writer</h3>
                <p class="job-info"><i class="fa fa-building"></i> BlogWorks Media</p>
                <p class="job-info"><i class="fa fa-map-marker-alt"></i> Remote</p>
                <p class="job-info"><i class="fa fa-clock"></i> 8-12 hrs/week</p>
                <p class="job-description">Write engaging blog posts and articles on various topics for our clients.</p>
                <div class="job-tags">
                    <span class="job-tag">Remote</span>
                    <span class="job-tag">Writing</span>
                    <span class="job-tag">Research</span>
                </div>
                <a href="jobs/job-details.php?id=6" class="view-details-btn">View Details</a>
            </div>
        </div>

        <div class="browse-all scroll-reveal">
            <a href="jobs/view-jobs.php" class="browse-all-btn">Browse All Jobs</a>
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
                        <h3 class="stat-number counter" data-target="5000">0</h3>
                        <p class="stat-label">Active Students</p>
                    </div>
                    <div class="stat-item scroll-reveal" data-delay="100">
                        <h3 class="stat-number counter" data-target="1200">0</h3>
                        <p class="stat-label">Employers</p>
                    </div>
                    <div class="stat-item scroll-reveal" data-delay="200">
                        <h3 class="stat-number counter" data-target="3500">0</h3>
                        <p class="stat-label">Jobs Posted</p>
                    </div>
                    <div class="stat-item scroll-reveal" data-delay="300">
                        <h3 class="stat-number"><span class="counter" data-target="95">0</span>%</h3>
                        <p class="stat-label">Success Rate</p>
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
                <a href="auth/register.php" class="cta-btn primary-cta">Get Started Now</a>
                <a href="jobs/view-jobs.php" class="cta-btn secondary-cta">Browse Jobs</a>
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

