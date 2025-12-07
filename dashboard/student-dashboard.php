<?php

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';



$user_id = $_SESSION['user_id'];


// Get Student Info

$stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$student_name = $user['name'] ?? 'Student';


//  Dashboard Stats

$total_apps = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE student_id = $user_id")->fetch_assoc()['total'] ?? 0;
$active_apps = $conn->query("SELECT COUNT(*) AS active FROM applications WHERE student_id = $user_id AND status IN ('Pending','Accepted')")->fetch_assoc()['active'] ?? 0;
$new_notif = 0; // Placeholder until notifications are added


//  Recent Job Listings

$jobs_sql = "
    SELECT j.job_id, j.title, j.type, j.category, j.pay, j.location, e.company_name 
    FROM jobs j 
    JOIN employers e ON j.employer_id = e.employer_id 
    WHERE j.status = 'Active' 
    ORDER BY j.job_id DESC 
    LIMIT 3
";
$jobs_result = $conn->query($jobs_sql);


//  Application Tracker

$track_sql = "
    SELECT a.status, j.title 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.job_id 
    WHERE a.student_id = $user_id 
    ORDER BY a.application_id DESC 
    LIMIT 3
";
$track_res = $conn->query($track_sql);


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
        <h1>Hello, <?= htmlspecialchars($student_name) ?></h1>
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

        <!-- Application Tracker -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Application Tracker</h2>
            </div>

            <?php if ($track_res && $track_res->num_rows > 0): ?>
                <?php while ($app = $track_res->fetch_assoc()): ?>
                    <div class="tracker-item">
                        <div class="tracker-content">
                            <div class="tracker-title"><?= htmlspecialchars($app['title']) ?></div>
                            <div class="tracker-subtitle"><?= htmlspecialchars($app['status']) ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No recent applications yet.</p>
            <?php endif; ?>

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