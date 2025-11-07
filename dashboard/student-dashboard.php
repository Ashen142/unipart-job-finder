<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "employee-dashboard to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/dashboard.css'];
$body_class = 'dashboard-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<div class="main-content">
            <div class="header">
                <h1>Hello, [Student Name]</h1>
                <a href="#" class="view-all">View All</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-title">Total Applications</div>
                    <div class="stat-number">15</div>
                </div>

                <div class="stat-card green">
                    <div class="stat-title">Active Applications</div>
                    <div class="stat-number">6</div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-title">New Notifications</div>
                    <div class="stat-number">3</div>
                </div>
            </div>

            <div class="content-grid">
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Job Listings</h2>
                        <a href="#" class="view-all">View All</a>
                    </div>

                    <div class="job-card">
                        <div class="job-icon">☕</div>
                        <div class="job-details">
                            <h3 class="job-title">Part-Time Barista</h3>
                            <p class="job-company">Basso Co.</p>
                            <div class="job-info">
                                <span>Location: 09K</span>
                                <span>₱135.30</span>
                            </div>
                            <div class="job-actions">
                                <a href="#" class="view-paid">View Paid</a>
                                <button class="apply-btn">Apply Now</button>
                            </div>
                        </div>
                    </div>

                    <div class="job-card">
                        <div class="job-icon">💺</div>
                        <div class="job-details">
                            <h3 class="job-title">Software Developer Intern</h3>
                            <p class="job-company">Research Assistant</p>
                            <div class="job-info">
                                <span>Research Assistant</span>
                            </div>
                            <div class="job-actions">
                                <a href="#" class="view-paid">View Paid</a>
                                <button class="apply-btn">Apply Now</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Application Tracker</h2>
                    </div>

                    <div class="tracker-item">
                        <div class="tracker-icon">LGG</div>
                        <div class="tracker-content">
                            <div class="tracker-title">LGG</div>
                        </div>
                    </div>

                    <div class="tracker-item">
                        <div class="tracker-icon">📅</div>
                        <div class="tracker-content">
                            <div class="tracker-title">Retail Associate</div>
                            <div class="tracker-subtitle">Interview Scheduled (Today!)</div>
                        </div>
                    </div>

                    <div class="tracker-item">
                        <div class="status-icon">✓</div>
                        <div class="tracker-content">
                            <div class="tracker-title">Accepted</div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        <div class="section-header">
                            <h2 class="section-title">New Notifications</h2>
                        </div>
                        <div class="notification-box">
                            Your application for Campus Tutor was accepted!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
