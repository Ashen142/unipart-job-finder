<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "employer-profile to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/profiles.css'];
$body_class = 'student-profile-page';
$page_type = 'employee';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<!-- Main Container -->
    <div class="container">
        

        <!-- Profile Header -->
        <div class="profile-header">
            <!-- <img src="../assets/images/company-logo.jpg" alt="Company Logo" class="profile-logo"> -->
            <div class="avatar-circle">TSI</div>
            <div class="profile-info">
                <h1>Tech Solutions Inc.</h1>
                <span class="verified-badge">
                    <i class="fas fa-check-circle"></i>
                    Verified Employer
                </span>
                <div class="profile-meta">
                    <div class="meta-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@techsolutions.com</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-phone"></i>
                        <span>+94 77 123 4567</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Colombo, Sri Lanka</span>
                    </div>
                    <div class="meta-item rating">
                        <i class="fas fa-star"></i>
                        <span><strong>4.8</strong> (124 reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-briefcase"></i>
                <h3>15</h3>
                <p>Total Jobs Posted</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>47</h3>
                <p>Active Applicants</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-check"></i>
                <h3>32</h3>
                <p>Students Hired</p>
            </div>
        </div>

        <!-- Company Details -->
        <div class="profile-section">
            <h2 class="section-title">Company Details</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Company Name</span>
                    <span class="detail-value">Tech Solutions Inc.</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Industry</span>
                    <span class="detail-value">Information Technology</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Company Size</span>
                    <span class="detail-value">50-200 Employees</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Founded Year</span>
                    <span class="detail-value">2015</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Website</span>
                    <span class="detail-value"><a href="https://techsolutions.com" style="color: #007BFF;">www.techsolutions.com</a></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Member Since</span>
                    <span class="detail-value">January 2024</span>
                </div>
            </div>
        </div>

        <!-- About Company -->
        <div class="profile-section">
            <h2 class="section-title">About Company</h2>
            <p class="description">
                Tech Solutions Inc. is a leading software development company specializing in web and mobile application development. We work with startups and enterprises to build innovative digital solutions. Our team is passionate about technology and committed to delivering high-quality products.
            </p>
            <p class="description" style="margin-top: 15px;">
                We believe in nurturing young talent and regularly hire university students for part-time positions, internships, and freelance projects. Join our team and gain real-world experience while working on exciting projects!
            </p>
        </div>

        <!-- Contact Information -->
        <div class="profile-section">
            <h2 class="section-title">Contact Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Primary Email</span>
                    <span class="detail-value">contact@techsolutions.com</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">HR Email</span>
                    <span class="detail-value">hr@techsolutions.com</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone Number</span>
                    <span class="detail-value">+94 77 123 4567</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Office Address</span>
                    <span class="detail-value">123 Galle Road, Colombo 03, Sri Lanka</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="profile-section">
            <h2 class="section-title">Account Actions</h2>
            <div class="btn-container">
                <a href="edit-profile.php" class="btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </a>
                <a href="../jobs/add-job.php" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Post New Job
                </a>
                <button class="btn-secondary">
                    <i class="fas fa-key"></i>
                    Change Password
                </button>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>