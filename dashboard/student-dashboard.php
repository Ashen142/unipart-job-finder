<?php

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';



$user_id = $_SESSION['user_id'];


// Get Student Info and student_id
$stmt = $conn->prepare("SELECT u.name, s.student_id FROM users u 
                        LEFT JOIN students s ON u.user_id = s.user_id 
                        WHERE u.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$student_name = $user_data['name'] ?? 'Student';
$student_id = $user_data['student_id'] ?? 0;


//  Dashboard Stats

$total_apps_query = "SELECT COUNT(*) AS total FROM applications WHERE student_id = ?";
$stmt = $conn->prepare($total_apps_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$total_apps = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

$active_apps_query = "SELECT COUNT(*) AS active FROM applications 
                      WHERE student_id = ? AND status IN ('Pending', 'Accepted')";
$stmt = $conn->prepare($active_apps_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$active_apps = $stmt->get_result()->fetch_assoc()['active'] ?? 0;

$new_notif = 0; // Placeholder until notifications are added


//  Recent Job Listings

$jobs_sql = "SELECT j.job_id, j.title, j.type, j.category, j.pay, j.location, e.company_name 
             FROM jobs j 
             JOIN employers e ON j.employer_id = e.employer_id 
             WHERE j.status = 'active' 
             ORDER BY j.created_at DESC 
             LIMIT 3";
$jobs_result = $conn->query($jobs_sql);


//  Application Tracker

$track_sql = "SELECT a.status, j.title, a.date_applied
              FROM applications a 
              JOIN jobs j ON a.job_id = j.job_id 
              WHERE a.student_id = ? 
              ORDER BY a.date_applied DESC 
              LIMIT 3";
$stmt = $conn->prepare($track_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$track_res = $stmt->get_result();


// Page settings

$page_title = "Student Dashboard - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/dashboard.css'];
$body_class = 'dashboard-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Hello, <?= htmlspecialchars($student_name) ?>! 👋</h1>
        <a href="/Unipart-job-finder/jobs/view-jobs.php" class="view-all">View All</a>
    </div>

    <!-- Dashboard Stats -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-title">Total Applications</div>
            <div class="stat-number"><?= $total_apps ?></div>
        </div>

        <div class="stat-card green">
            <div class="stat-title">Active Applications</div>
            <div class="stat-number"><?= $active_apps ?></div>
        </div>

        <div class="stat-card purple">
            <div class="stat-title">New Notifications</div>
            <div class="stat-number"><?= $new_notif ?></div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Recent Job Listings -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Job Listings</h2>
                <a href="/Unipart-job-finder/jobs/view-jobs.php" class="view-all">View All</a>
            </div>

            <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
                <?php while ($job = $jobs_result->fetch_assoc()): ?>
                    <div class="job-card">
                        <div class="job-icon">💼</div>
                        <div class="job-details">
                            <h3 class="job-title"><?= htmlspecialchars($job['title']) ?></h3>
                            <p class="job-company"><?= htmlspecialchars($job['company_name']) ?></p>
                            <div class="job-info">
                                <span>Location: <?= htmlspecialchars($job['location']) ?></span>
                                <span>₱<?= htmlspecialchars($job['pay']) ?></span>
                            </div>
                            <div class="job-actions">
    <a href="/Unipart-job-finder/jobs/job-details.php?job_id=<?= $job['job_id'] ?>" class="view-paid">View</a>
    <a href="/Unipart-job-finder/applications/apply-job.php?job_id=<?= $job['job_id'] ?>" class="apply-btn" style="text-decoration: none;">Apply Now</a>
</div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No active job listings available.</p>
            <?php endif; ?>
        </div>

        <!-- Application Tracker & Notifications -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-tasks"></i> Application Tracker
                </h2>
                <a href="/Unipart-job-finder/applications/student-applications.php" class="view-all">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="tracker-list">
                <?php if ($track_res && $track_res->num_rows > 0): ?>
                    <?php while ($app = $track_res->fetch_assoc()): ?>
                        <div class="tracker-item">
                            <div class="tracker-icon">
                                <?php if ($app['status'] === 'Pending'): ?>
                                    <i class="fas fa-clock" style="color: #FFC107;"></i>
                                <?php elseif ($app['status'] === 'Accepted'): ?>
                                    <i class="fas fa-check-circle" style="color: #28A745;"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle" style="color: #DC3545;"></i>
                                <?php endif; ?>
                            </div>
                            <div class="tracker-content">
                                <div class="tracker-title"><?= htmlspecialchars($app['title']) ?></div>
                                <div class="tracker-subtitle">
                                    <span class="status-badge status-<?= strtolower($app['status']) ?>">
                                        <?= htmlspecialchars($app['status']) ?>
                                    </span>
                                    <span class="tracker-date">
                                        <i class="fas fa-calendar"></i>
                                        <?= date('M d, Y', strtotime($app['date_applied'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state" style="padding: 20px; text-align: center;">
                        <i class="fas fa-clipboard-list" style="font-size: 36px; color: #CCC; margin-bottom: 10px;"></i>
                        <p>No recent applications yet.</p>
                        <p style="font-size: 14px; color: #6C757D;">Start applying to jobs to track your progress here!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Notifications Section -->
            <div style="margin-top: 2rem;">
                <div class="section-header">
                    <h2 class="section-title">New Notifications</h2>
                </div>
                <div class="notification-box">
                    <?php if ($new_notif > 0): ?>
                        You have <?= $new_notif ?> new notifications.
                    <?php else: ?>
                        No new notifications.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>