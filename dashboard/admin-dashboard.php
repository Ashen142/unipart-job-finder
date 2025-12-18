<?php
// ===============================
// UniPart - Admin Dashboard
// ===============================

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restrict to admin role only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Unipart-job-finder/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// -------------------------------
// 1️⃣ Admin Info
// -------------------------------
$stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_name = $admin['name'] ?? 'Admin';

// -------------------------------
// 2️⃣ Stats Section
// -------------------------------
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$pending_users = $conn->query("SELECT COUNT(*) AS total FROM employers WHERE verified = 0")->fetch_assoc()['total'] ?? 0;

$total_jobs = $conn->query("SELECT COUNT(*) AS total FROM jobs")->fetch_assoc()['total'] ?? 0;
$unverified_jobs = $conn->query("SELECT COUNT(*) AS total FROM jobs WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;

$total_applications = $conn->query("SELECT COUNT(*) AS total FROM applications")->fetch_assoc()['total'] ?? 0;

// -------------------------------
// 3️⃣ Recent Users
// -------------------------------
$users_sql = "
    SELECT u.user_id, u.name, u.email, u.role, e.verified 
    FROM users u 
    LEFT JOIN employers e ON u.user_id = e.user_id 
    ORDER BY u.user_id DESC 
    LIMIT 5
";
$users_result = $conn->query($users_sql);

// -------------------------------
// 4️⃣ Recent Job Posts
// -------------------------------
$jobs_sql = "
    SELECT j.job_id, j.title, j.posted_at, e.company_name 
    FROM jobs j 
    JOIN employers e ON j.employer_id = e.employer_id 
    ORDER BY j.job_id DESC 
    LIMIT 5
";
$jobs_result = $conn->query($jobs_sql);

// -------------------------------
// 5️⃣ System Logs (optional)
// -------------------------------
$logs_sql = "SELECT action, DATE_FORMAT(date, '%d %b %Y %h:%i %p') AS log_date FROM admin_logs ORDER BY log_id DESC LIMIT 5";
$logs_result = $conn->query($logs_sql);

// -------------------------------
// Page settings
// -------------------------------
$page_title = "Admin Dashboard - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/dashboard.css'];
$body_class = 'dashboard-page';
$page_type = 'admin';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Welcome, <?= htmlspecialchars($admin_name) ?></h1>
        <a href="/Unipart-job-finder/admin/manage-users.php" class="view-all">View All</a>
    </div>

    <!-- Dashboard Stats -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-title">Total Users</div>
            <div class="stat-number"><?= $total_users ?></div>
            <div class="stat-footer">
                <span><?= $pending_users ?> Pending Approval</span>
                <span class="stat-icon">👥</span>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-title">Total Jobs</div>
            <div class="stat-number"><?= $total_jobs ?></div>
            <div class="stat-footer">
                <span><?= $unverified_jobs ?> Unverified Jobs</span>
                <span class="stat-icon">💼</span>
            </div>
        </div>

        <div class="stat-card teal">
            <div class="stat-title">Total Applications</div>
            <div class="stat-number"><?= $total_applications ?></div>
            <div class="stat-footer">
                <span>Across all users</span>
                <span class="stat-icon">📄</span>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-title">Pending Verifications</div>
            <div class="stat-number"><?= $pending_users ?></div>
            <div class="stat-footer">
                <span>Employers awaiting approval</span>
                <span class="stat-icon">⚠️</span>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Recent User Registrations -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent User Registrations</h2>
                <a href="/Unipart-job-finder/admin/manage-users.php" class="view-all">View All</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users_result && $users_result->num_rows > 0): ?>
                        <?php while ($u = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $u['user_id'] ?></td>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= ucfirst($u['role']) ?></td>
                                <td>
                                    <?php if ($u['role'] === 'employer'): ?>
                                        <?= ($u['verified'] == 1) ? '✅ Verified' : '⏳ Pending' ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- System Activity Log -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">System Activity Log</h2>
            </div>

            <?php if ($logs_result && $logs_result->num_rows > 0): ?>
                <?php while ($log = $logs_result->fetch_assoc()): ?>
                    <div class="activity-log">
                        <div class="activity-icon">📋</div>
                        <div class="activity-text">
                            <?= htmlspecialchars($log['action']) ?> <br>
                            <small><?= htmlspecialchars($log['log_date']) ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No recent admin logs.</p>
            <?php endif; ?>

            <div class="quick-links">
                <h2 class="section-title">Quick Links</h2>
                <button class="quick-link-btn"><a href="../admin/reports.php">System Reports</a></button>
                <button class="quick-link-btn"><a href="../admin/manage-users.php">Manage Users</a></button>
                <button class="quick-link-btn"><a href="../admin/manage-jobs.php">Manage Jobs</a></button>
            </div>
        </div>
    </div>

    <div class="bottom-section">
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Latest Job Postings</h2>
                <a href="/Unipart-job-finder/admin/manage-jobs.php" class="view-all">View All</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Date Posted</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
                        <?php while ($job = $jobs_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($job['title']) ?></td>
                                <td><?= htmlspecialchars($job['company_name']) ?></td>
                                <td><?= htmlspecialchars($job['posted_at']) ?></td>
                                <td>Active</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No recent job postings.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
