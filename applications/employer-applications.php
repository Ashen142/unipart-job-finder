<?php
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

// Get Employer ID
$stmt = $conn->prepare("SELECT employer_id FROM employers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$employer = $result->fetch_assoc();
$employer_id = $employer['employer_id'];

// -------------------------------
// Handle Status Updates (Accept/Reject)
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $application_id = intval($_POST['application_id']);
    $action = $_POST['action'];
    
    // Verify the application belongs to this employer's job
    $verify_sql = "SELECT a.application_id FROM applications a 
                   JOIN jobs j ON a.job_id = j.job_id 
                   WHERE a.application_id = ? AND j.employer_id = ?";
    $verify_stmt = $conn->prepare($verify_sql);
    $verify_stmt->bind_param("ii", $application_id, $employer_id);
    $verify_stmt->execute();
    
    if ($verify_stmt->get_result()->num_rows > 0) {
        if ($action === 'accept') {
            $update_sql = "UPDATE applications SET status = 'Accepted' WHERE application_id = ?";
            $message = "Application accepted successfully!";
            $message_type = "success";
        } elseif ($action === 'reject') {
            $update_sql = "UPDATE applications SET status = 'Rejected' WHERE application_id = ?";
            $message = "Application rejected successfully!";
            $message_type = "success";
        }
        
        if (isset($update_sql)) {
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $application_id);
            $update_stmt->execute();
        }
    }
}

// -------------------------------
// Get Filter Values
// -------------------------------
$job_filter = isset($_GET['job_id']) ? intval($_GET['job_id']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search_filter = isset($_GET['search']) ? trim($_GET['search']) : '';

// -------------------------------
// Get Statistics
// -------------------------------
$stats_sql = "SELECT 
    COUNT(*) as total_applications,
    SUM(CASE WHEN a.status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN a.status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count,
    SUM(CASE WHEN a.status = 'Rejected' THEN 1 ELSE 0 END) as rejected_count
    FROM applications a
    JOIN jobs j ON a.job_id = j.job_id
    WHERE j.employer_id = ?";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $employer_id);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();

// -------------------------------
// Get Applications with Filters
// -------------------------------
$app_sql = "SELECT 
    a.application_id,
    a.status,
    a.date_applied,
    a.phone,
    a.availability,
    a.cover_letter,
    a.resume,
    u.name as student_name,
    u.email as student_email,
    s.department,
    s.year,
    s.skills,
    j.job_id,
    j.title as job_title
    FROM applications a
    JOIN students s ON a.student_id = s.student_id
    JOIN users u ON s.user_id = u.user_id
    JOIN jobs j ON a.job_id = j.job_id
    WHERE j.employer_id = ?";

$params = [$employer_id];
$types = "i";

if ($job_filter) {
    $app_sql .= " AND j.job_id = ?";
    $params[] = $job_filter;
    $types .= "i";
}

if ($status_filter) {
    $app_sql .= " AND a.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if ($search_filter) {
    $app_sql .= " AND u.name LIKE ?";
    $params[] = "%$search_filter%";
    $types .= "s";
}

$app_sql .= " ORDER BY a.date_applied DESC";

$app_stmt = $conn->prepare($app_sql);
$app_stmt->bind_param($types, ...$params);
$app_stmt->execute();
$applications = $app_stmt->get_result();

// -------------------------------
// Get Jobs for Filter Dropdown
// -------------------------------
$jobs_sql = "SELECT job_id, title FROM jobs WHERE employer_id = ? AND status = 'Active' ORDER BY title";
$jobs_stmt = $conn->prepare($jobs_sql);
$jobs_stmt->bind_param("i", $employer_id);
$jobs_stmt->execute();
$jobs_for_filter = $jobs_stmt->get_result();

// Page settings
$page_title = "Employer Application | UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/applications.css'];
$body_class = 'employer-profile-page';
$page_type = 'employer';
include __DIR__ . '/../includes/header.php';
?>

<!-- Main Container -->
<div class="container1">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-users"></i> View Applicants</h1>
        <p>Manage job applications and connect with talented students</p>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($message)): ?>
        <div class="alert alert-<?= $message_type ?>">
            <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-file-alt stat-icon"></i>
            <h3>Total Applications</h3>
            <div class="stat-value"><?= $stats['total_applications'] ?></div>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock stat-icon"></i>
            <h3>Pending Review</h3>
            <div class="stat-value"><?= $stats['pending_count'] ?></div>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle stat-icon"></i>
            <h3>Accepted</h3>
            <div class="stat-value"><?= $stats['accepted_count'] ?></div>
        </div>
        <div class="stat-card">
            <i class="fas fa-times-circle stat-icon"></i>
            <h3>Rejected</h3>
            <div class="stat-value"><?= $stats['rejected_count'] ?></div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="job_filter">Filter by Job</label>
                    <select id="job_filter" name="job_id">
                        <option value="">All Jobs</option>
                        <?php while ($job = $jobs_for_filter->fetch_assoc()): ?>
                            <option value="<?= $job['job_id'] ?>" <?= $job_filter == $job['job_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($job['title']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status_filter">Filter by Status</label>
                    <select id="status_filter" name="status">
                        <option value="">All Status</option>
                        <option value="Pending" <?= $status_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Accepted" <?= $status_filter === 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                        <option value="Rejected" <?= $status_filter === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="search">Search Student</label>
                    <input type="text" id="search" name="search" placeholder="Enter student name..." value="<?= htmlspecialchars($search_filter) ?>">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="employer-applications.php" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="applications-section">
        <h2 class="section-title">Applications List</h2>
        
        <?php if ($applications->num_rows > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Job Title</th>
                        <th>Applied Date</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($app = $applications->fetch_assoc()): ?>
                        <?php
                        // Get initials for avatar
                        $names = explode(' ', $app['student_name']);
                        $initials = '';
                        foreach ($names as $name) {
                            $initials .= strtoupper(substr($name, 0, 1));
                        }
                        $initials = substr($initials, 0, 2);
                        
                        // Format date
                        $date = date('M d, Y', strtotime($app['date_applied']));
                        ?>
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar"><?= $initials ?></div>
                                    <div class="student-details">
                                        <h4><?= htmlspecialchars($app['student_name']) ?></h4>
                                        <p><?= htmlspecialchars($app['department'] ?? 'N/A') ?> - <?= htmlspecialchars($app['year'] ?? 'N/A') ?></p>
                                        <?php if ($app['skills']): ?>
                                            <p style="font-size: 11px; color: #666;">
                                                <i class="fas fa-tools"></i> <?= htmlspecialchars(substr($app['skills'], 0, 50)) ?>...
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($app['job_title']) ?></td>
                            <td><?= $date ?></td>
                            <td>
                                <div style="font-size: 13px;">
                                    <div><i class="fas fa-envelope"></i> <?= htmlspecialchars($app['student_email']) ?></div>
                                    <?php if ($app['phone']): ?>
                                        <div><i class="fas fa-phone"></i> <?= htmlspecialchars($app['phone']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($app['availability']): ?>
                                        <div><i class="fas fa-calendar"></i> <?= htmlspecialchars($app['availability']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-<?= strtolower($app['status']) ?>">
                                    <?= htmlspecialchars($app['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($app['status'] === 'Pending'): ?>
                                    <div class="action-buttons">
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to accept this application?');">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to reject this application?');">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                        <button class="btn btn-info btn-sm" onclick="viewDetails(<?= $app['application_id'] ?>)">
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                    </div>
                                <?php elseif ($app['status'] === 'Accepted'): ?>
                                    <button class="btn btn-info btn-sm" onclick="viewDetails(<?= $app['application_id'] ?>)">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                <?php else: ?>
                                    <span style="color: #6C757D; font-size: 13px;">No actions available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        
                        <!-- Hidden Details Row -->
                        <tr id="details-<?= $app['application_id'] ?>" style="display: none;">
                            <td colspan="6">
                                <div class="application-details">
                                    <h4>Application Details</h4>
                                    <div class="details-grid">
                                        <div>
                                            <strong>Student Name:</strong> <?= htmlspecialchars($app['student_name']) ?>
                                        </div>
                                        <div>
                                            <strong>Email:</strong> <?= htmlspecialchars($app['student_email']) ?>
                                        </div>
                                        <div>
                                            <strong>Phone:</strong> <?= htmlspecialchars($app['phone'] ?? 'Not provided') ?>
                                        </div>
                                        <div>
                                            <strong>Availability:</strong> <?= htmlspecialchars($app['availability'] ?? 'Not specified') ?>
                                        </div>
                                        <div>
                                            <strong>Department:</strong> <?= htmlspecialchars($app['department'] ?? 'N/A') ?>
                                        </div>
                                        <div>
                                            <strong>Year:</strong> <?= htmlspecialchars($app['year'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                    <?php if ($app['skills']): ?>
                                        <div style="margin-top: 10px;">
                                            <strong>Skills:</strong> <?= htmlspecialchars($app['skills']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($app['cover_letter']): ?>
                                        <div style="margin-top: 10px;">
                                            <strong>Cover Letter:</strong>
                                            <p style="white-space: pre-wrap; background: #f5f5f5; padding: 10px; border-radius: 4px; margin-top: 5px;">
                                                <?= htmlspecialchars($app['cover_letter']) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Applications Found</h3>
            <p>
                <?php if ($job_filter || $status_filter || $search_filter): ?>
                    No applications match your current filters. Try adjusting your search criteria.
                <?php else: ?>
                    You haven't received any applications for your jobs yet.
                <?php endif; ?>
            </p>
            <?php if ($job_filter || $status_filter || $search_filter): ?>
                <a href="employer-applications.php" class="btn btn-primary" style="margin-top: 15px;">
                    <i class="fas fa-redo"></i> Clear Filters
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function viewDetails(applicationId) {
        const detailsRow = document.getElementById('details-' + applicationId);
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    }

    // Auto-hide success messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
</script>



<?php include __DIR__ . '/../includes/footer.php'; ?>