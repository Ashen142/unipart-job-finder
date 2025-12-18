<?php

// UniPart - Employer Dashboard

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';



// Restrict to employer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: /Unipart-job-finder/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// -------------------------------
// 1. Get Employer Info
// -------------------------------
$stmt = $conn->prepare("
    SELECT e.employer_id, u.name, e.company_name 
    FROM employers e 
    JOIN users u ON e.user_id = u.user_id 
    WHERE e.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$employer = $res->fetch_assoc();

$employer_id = $employer['employer_id'];
$employer_name = $employer['company_name'] ?? $employer['name'] ?? 'Employer';

// -------------------------------
// 2. Stats Section
// -------------------------------

// Total jobs posted
$total_jobs = $conn->query("SELECT COUNT(*) AS total FROM jobs WHERE employer_id = $employer_id")->fetch_assoc()['total'] ?? 0;

// Active jobs
$active_jobs = $conn->query("SELECT COUNT(*) AS active FROM jobs WHERE employer_id = $employer_id AND status='Active'")->fetch_assoc()['active'] ?? 0;

// Total applicants
$total_applicants = $conn->query("
    SELECT COUNT(*) AS total 
    FROM applications 
    WHERE job_id IN (SELECT job_id FROM jobs WHERE employer_id = $employer_id)
")->fetch_assoc()['total'] ?? 0;

// Pending reviews
$pending_reviews = $conn->query("
    SELECT COUNT(*) AS pending 
    FROM applications 
    WHERE status='Pending' AND job_id IN (SELECT job_id FROM jobs WHERE employer_id = $employer_id)
")->fetch_assoc()['pending'] ?? 0;

// Active interviews (optional feature placeholder)
$active_interviews = 0;

// -------------------------------
// 3. Recent Job Listings
// -------------------------------
$jobs_sql = "
    SELECT job_id, title, description, DATE_FORMAT(posted_at, '%d %b %Y') AS posted_date 
    FROM jobs 
    WHERE employer_id = $employer_id 
    ORDER BY job_id DESC 
    LIMIT 3
";

$jobs_result = $conn->query($jobs_sql);

// -------------------------------
// 4. Recent Applications
// -------------------------------
$app_sql = "
    SELECT u.name AS student_name, j.title AS job_title, a.status 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.job_id 
    JOIN students s ON a.student_id = s.student_id 
    JOIN users u ON s.user_id = u.user_id 
    WHERE j.employer_id = $employer_id 
    ORDER BY a.application_id DESC 
    LIMIT 3
";
$app_result = $conn->query($app_sql);

// -------------------------------
// Page settings
// -------------------------------
$page_title = "Employer Dashboard - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/dashboard.css'];
$body_class = 'dashboard-page';
$page_type = 'employer';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="main-content">
        <div class="header">
            <h1>Hello, <?= htmlspecialchars($employer_name) ?></h1>
            <a href="/Unipart-job-finder/jobs/view-jobs.php" class="view-all">View All</a>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-title">Total Job Posts</div>
                <div class="stat-number"><?= $total_jobs ?></div>
                <div class="stat-footer">
                    <span><?= $active_jobs ?> Active</span>
                    <span class="stat-icon">💼</span>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-title">Total Applicants</div>
                <div class="stat-number"><?= $total_applicants ?></div>
                <div class="stat-footer">
                    <span><?= $pending_reviews ?> Pending</span>
                    <span class="stat-icon">📄</span>
                </div>
            </div>

            <div class="stat-card purple">
                <div class="stat-title">Pending Reviews</div>
                <div class="stat-number"><?= $pending_reviews ?></div>
                <div class="stat-footer">
                    <span>Awaiting action</span>
                    <span class="stat-icon">☑️</span>
                </div>
            </div>

            <div class="stat-card orange">
                <div class="stat-title">Active Interviews</div>
                <div class="stat-number"><?= $active_interviews ?></div>
                <div class="stat-footer">
                    <span>—</span>
                    <span class="stat-icon">👤</span>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Job Listings Section -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Your Job Listings</h2>
                    <a href="/Unipart-job-finder/jobs/view-jobs.php" class="view-all">View All</a>
                </div>

                <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
                    <?php while ($job = $jobs_result->fetch_assoc()): ?>
                        <div class="job-card">
                            <div class="job-icon">💼</div>
                            <div class="job-details">
                                <h3 class="job-title"><?= htmlspecialchars($job['title']) ?></h3>
                                <p class="job-meta">Posted: <?= htmlspecialchars($job['posted_date']) ?></p>
                                <p class="job-meta"><?= htmlspecialchars(substr($job['description'], 0, 80)) ?>...</p>
                                <div class="job-footer">
                                    <a href="/Unipart-job-finder/jobs/edit-job.php?id=<?= $job['job_id'] ?>" class="apply-btn">Manage</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No jobs posted yet.</p>
                <?php endif; ?>
            </div>

            <!-- Recent Applications Section -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Recent Applications</h2>
                </div>

                <?php if ($app_result && $app_result->num_rows > 0): ?>
                    <?php while ($app = $app_result->fetch_assoc()): ?>
                        <div class="application-item">
                            <span class="check-icon">📄</span>
                            <div class="application-text">
                                <?= htmlspecialchars($app['student_name']) ?> applied for <?= htmlspecialchars($app['job_title']) ?> 
                                (<?= htmlspecialchars($app['status']) ?>)
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recent applications.</p>
                <?php endif; ?>

                <!-- Quick Links -->
                <div class="quick-links" style="margin-top: 2rem;">
                    <h2 class="section-title">Quick Links</h2>
                    <button class="quick-link-btn"><a href="../jobs/add-job.php">Post New Job</a></button>
                    <button class="quick-link-btn"><a href="../jobs/view-jobs.php">View All Jobs</a></button>
                    <button class="quick-link-btn"><a href="../applications/employer-applications.php">View Applicants</a></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
