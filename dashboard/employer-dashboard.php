<?php
// Include backend setup
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "employee-dashboard to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/dashboard.css'];
$body_class = 'dashboard-page';
$page_type = 'employee';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="main-content">
            <div class="header">
                <h1>Hello, [Stuploer Name]</h1>
                <a href="#" class="view-all">View All</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-title">Total Job Posts</div>
                    <div class="stat-number">12</div>
                    <div class="stat-footer">
                        <span>3 Active</span>
                        <span class="stat-icon">💼</span>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-title">Total Applicants</div>
                    <div class="stat-number">98</div>
                    <div class="stat-footer">
                        <span>3 Active</span>
                        <span class="stat-icon">📄</span>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-title">Pending Reviews</div>
                    <div class="stat-number">17</div>
                    <div class="stat-footer">
                        <span>7 active</span>
                        <span class="stat-icon">☑️</span>
                    </div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-title">Active Interviews</div>
                    <div class="stat-number">5</div>
                    <div class="stat-footer">
                        <span>5call</span>
                        <span class="stat-icon">👤</span>
                    </div>
                </div>
            </div>

            <div class="content-grid">
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Your Job Listings</h2>
                        <a href="#" class="view-all">View All</a>
                    </div>

                    <div class="job-card">
                        <div class="job-icon">☕</div>
                        <div class="job-details">
                            <h3 class="job-title">Software Developer Intern</h3>
                            <p class="job-meta">Posted: 3 days ago</p>
                            <p class="job-meta">Research Assistant</p>
                            <div class="job-footer">
                                <span class="applicants">45 Apw Paid</span>
                                <button class="apply-btn">Apply Now</button>
                            </div>
                        </div>
                    </div>

                    <div class="job-card">
                        <div class="job-icon">💺</div>
                        <div class="job-details">
                            <h3 class="job-title">Marketing Specialist</h3>
                            <p class="job-meta">Posted: 45 week ago</p>
                            <p class="job-meta">Research Assistant</p>
                            <div class="job-footer">
                                <span class="applicants">23 Apektats</span>
                                <button class="apply-btn">Apply Now</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Applications</h2>
                    </div>

                    <div class="application-item">
                        <span class="check-icon">📄</span>
                        <div class="application-text">
                            John Doe applied for Campus Tutor Specialist
                        </div>
                    </div>

                    <div class="application-item">
                        <span class="check-icon">✓</span>
                        <div class="application-text">
                            Jane Dee applied for Marketing Specialist Speciallist
                        </div>
                    </div>

                    <div class="review-item">
                        <span class="check-icon">✓</span>
                        <div class="application-text">Review</div>
                        <button class="edit-btn">Edlire</button>
                    </div>

                    <div class="quick-links">
                        <h2 class="section-title">Quick Links</h2>
                        <button class="quick-link-btn">Post New Job</button>
                        <button class="quick-link-btn">System Reports</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
