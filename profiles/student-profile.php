<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "student-profile to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/profiles.css'];
$body_class = 'student-profile-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';
?>


<!-- Main Container -->
    <div class="container">

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar-circle">WS</div>
            <!-- <img src="../assets/images/student-avatar.jpg" alt="Student Avatar" class="profile-avatar"> -->
            <div class="profile-info">
                <h1>Wimansa Samudinee</h1>
                <p class="profile-subtitle">software Engineering Student | Full Stack Developer</p>
                <span class="status-badge">
                    <i class="fas fa-circle"></i>
                    Available for Work
                </span>
                <div class="profile-meta">
                    <div class="meta-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>University of Jayewardenapura</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-envelope"></i>
                        <span>wimansa@student.cmb.ac.lk</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-phone"></i>
                        <span>+94 71 234 5678</span>
                    </div>
                    <div class="meta-item rating">
                        <i class="fas fa-star"></i>
                        <span><strong>4.9</strong> (18 reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-paper-plane"></i>
                <h3>12</h3>
                <p>Applications Sent</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>5</h3>
                <p>Jobs Completed</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>3</h3>
                <p>Pending Applications</p>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="profile-section">
            <h2 class="section-title">Personal Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value">Wimansa Samudinee</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Student ID</span>
                    <span class="detail-value">SE/2022/045</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Department</span>
                    <span class="detail-value">Software Engineer</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Year of Study</span>
                    <span class="detail-value">2nd Year</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email Address</span>
                    <span class="detail-value">wimansa@student.cmb.ac.lk</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone Number</span>
                    <span class="detail-value">+94 71 234 5678</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location</span>
                    <span class="detail-value">Colombo, Sri Lanka</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Member Since</span>
                    <span class="detail-value">March 2024</span>
                </div>
            </div>
        </div>

        <!-- About Me -->
        <div class="profile-section">
            <h2 class="section-title">About Me</h2>
            <p class="about-text">
                I am a dedicated Software Engineering student with a passion for web development and software engineering. I have experience working with modern web technologies including React, Node.js, and PHP. I'm eager to apply my skills in real-world projects and gain practical experience while pursuing my degree.
            </p>
            <p class="about-text" style="margin-top: 15px;">
                I'm looking for part-time opportunities that allow me to contribute to meaningful projects while balancing my academic commitments. I'm a fast learner, detail-oriented, and enjoy working in collaborative team environments.
            </p>
        </div>

        <!-- Skills -->
        <div class="profile-section">
            <h2 class="section-title">Skills & Expertise</h2>
            <div class="skills-container">
                <span class="skill-tag">HTML & CSS</span>
                <span class="skill-tag">JavaScript</span>
                <span class="skill-tag">React.js</span>
                <span class="skill-tag">Node.js</span>
                <span class="skill-tag">PHP</span>
                <span class="skill-tag">MySQL</span>
                <span class="skill-tag">Python</span>
                <span class="skill-tag">Git & GitHub</span>
                <span class="skill-tag">Responsive Design</span>
                <span class="skill-tag">REST APIs</span>
            </div>
        </div>

        <!-- Education -->
        <div class="profile-section">
            <h2 class="section-title">Education</h2>
            <div class="detail-item" style="margin-bottom: 15px;">
                <span class="detail-label">Bachelor of Science in Software Engineer</span>
                <span class="detail-value">University of Jayewardenapura</span>
                <span style="color: #6C757D; font-size: 14px; margin-top: 5px;">2025 - Present | CGPA: 3.0/4.0</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">G.C.E Advanced Level</span>
                <span class="detail-value">Bandaranayaka College, Veyangoda</span>
                <span style="color: #6C757D; font-size: 14px; margin-top: 5px;">2022 | Physical Science Stream - 3B's</span>
            </div>
        </div>

        <!-- Resume -->
        <div class="profile-section">
            <h2 class="section-title">Resume / CV</h2>
            <div class="resume-box">
                <i class="fas fa-file-pdf"></i>
                <p class="resume-info">
                    <strong>Wimansa_Samudinee_Resume.pdf</strong><br>
                    Uploaded on: March 15, 2024 | Size: 245 KB
                </p>
                <div class="btn-container" style="justify-content: center;">
                    <a href="../uploads/resumes/kamal_resume.pdf" class="btn-success" download>
                        <i class="fas fa-download"></i>
                        Download Resume
                    </a>
                    <button class="btn-primary">
                        <i class="fas fa-upload"></i>
                        Upload New Resume
                    </button>
                </div>
            </div>
        </div>

        <!-- Work Preferences -->
        <div class="profile-section">
            <h2 class="section-title">Work Preferences</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Job Type Preference</span>
                    <span class="detail-value">Part-time, Remote, Freelance</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Availability</span>
                    <span class="detail-value">15-20 hours per week</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Expected Hourly Rate</span>
                    <span class="detail-value">LKR 800 - 1200 per hour</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Work Location</span>
                    <span class="detail-value">Colombo or Remote</span>
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
                <a href="../jobs/view-jobs.php" class="btn-primary">
                    <i class="fas fa-search"></i>
                    Browse Jobs
                </a>
                <a href="../applications/student-applications.php" class="btn-primary">
                    <i class="fas fa-file-alt"></i>
                    My Applications
                </a>
                <button class="btn-secondary">
                    <i class="fas fa-key"></i>
                    Change Password
                </button>
            </div>
        </div>
    </div>


<?php include __DIR__ . '/../includes/footer.php'; ?>