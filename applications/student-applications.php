<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get student_id
$student_query = "SELECT student_id FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows === 0) {
    die("Student profile not found!");
}

$student_id = $student_result->fetch_assoc()['student_id'];

// Handle withdrawal request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw_application'])) {
    $application_id = intval($_POST['application_id']);
    
    // Verify this application belongs to current student
    $verify_query = "SELECT application_id FROM applications WHERE application_id = ? AND student_id = ? AND status = 'Pending'";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("ii", $application_id, $student_id);
    $stmt->execute();
    $verify_result = $stmt->get_result();
    
    if ($verify_result->num_rows > 0) {
        // Delete the application
        $delete_query = "DELETE FROM applications WHERE application_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $application_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Application withdrawn successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to withdraw application.";
        }
    } else {
        $_SESSION['error_message'] = "Cannot withdraw this application.";
    }

}

// Handle complete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_application'])) {
    $application_id = intval($_POST['application_id']);
    
    // Verify this application belongs to current student and is Accepted
    $verify_query = "SELECT application_id FROM applications WHERE application_id = ? AND student_id = ? AND status = 'Accepted'";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("ii", $application_id, $student_id);
    $stmt->execute();
    $verify_result = $stmt->get_result();
    
    if ($verify_result->num_rows > 0) {
        // Update the application status to 'Completed'
        $update_query = "UPDATE applications SET status = 'Completed' WHERE application_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $application_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Application marked as complete successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to complete application.";
        }
    } else {
        $_SESSION['error_message'] = "Cannot complete this application.";
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get statistics
$stats_query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted,
                    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
                FROM applications 
                WHERE student_id = ?";
$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats_result = $stmt->get_result();
$stats = $stats_result->fetch_assoc();

// Fetch all applications with job details
$applications_query = "SELECT a.*, j.title, j.type, j.category, j.pay, j.location, e.company_name
                       FROM applications a
                       JOIN jobs j ON a.job_id = j.job_id
                       JOIN employers e ON j.employer_id = e.employer_id
                       WHERE a.student_id = ?
                       ORDER BY a.date_applied DESC";
$stmt = $conn->prepare($applications_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$applications_result = $stmt->get_result();

// Page settings
$page_title = "My Applications | UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/applications.css'];
$body_class = 'student-profile-page';
$page_type = 'student';
include __DIR__ . '/../includes/header.php';
?>

<!-- Main Container -->
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-file-alt"></i> My Applications</h1>
        <p>Track and manage all your job applications</p>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success show">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-error show">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['pending'] ?? 0; ?></h3>
                <p>Pending Applications</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon accepted">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['accepted'] ?? 0; ?></h3>
                <p>Accepted Applications</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon rejected">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['rejected'] ?? 0; ?></h3>
                <p>Rejected Applications</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <label for="statusFilter"><i class="fas fa-filter"></i> Filter by Status:</label>
        <select id="statusFilter" class="filter-select">
            <option value="all">All Applications</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <!-- Applications Table -->
    <div class="applications-container">
        <?php if ($applications_result->num_rows > 0): ?>
            <div class="table-responsive">
                <table id="applicationsTable">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Pay</th>
                            <th>Location</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($app = $applications_result->fetch_assoc()): ?>
                            <tr data-status="<?php echo strtolower($app['status']); ?>">
                                <td><strong><?php echo htmlspecialchars($app['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($app['type']); ?></td>
                                <td><?php echo htmlspecialchars($app['category']); ?></td>
                                <td><?php echo htmlspecialchars($app['pay']); ?></td>
                                <td><?php echo htmlspecialchars($app['location']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($app['date_applied'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($app['status']); ?>">
                                        <?php echo htmlspecialchars($app['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/Unipart-job-finder/jobs/job-details.php?job_id=<?php echo $app['job_id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <?php if ($app['status'] === 'Pending'): ?>
                                        <button onclick="withdrawApplication(<?php echo $app['application_id']; ?>)" 
                                                class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Withdraw
                                        </button>
                                    <?php elseif ($app['status'] === 'Accepted'): ?>
                                        <button onclick="completeApplication(<?php echo $app['application_id']; ?>)" 
                                                class="btn btn-complete btn-sm">
                                            <i class="fas fa-check-circle"></i> Complete
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-inbox" style="font-size: 64px; color: #CCC; margin-bottom: 20px;"></i>
                <h3 style="color: #6C757D; margin-bottom: 10px;">No Applications Yet</h3>
                <p style="color: #999; margin-bottom: 20px;">You haven't applied to any jobs yet. Start exploring opportunities!</p>
                <a href="../jobs/view-jobs.php" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse Jobs
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Withdraw Form (Hidden) -->
<form id="withdrawForm" method="POST" style="display: none;">
    <input type="hidden" name="withdraw_application" value="1">
    <input type="hidden" name="application_id" id="withdrawApplicationId">
</form>

<!-- Complete Form (Hidden) -->
<form id="completeForm" method="POST" style="display: none;">
    <input type="hidden" name="complete_application" value="1">
    <input type="hidden" name="application_id" id="completeApplicationId">
</form>

<script>
    // Filter applications by status
    document.getElementById('statusFilter').addEventListener('change', function() {
        const filterValue = this.value;
        const rows = document.querySelectorAll('#applicationsTable tbody tr');
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filterValue === 'all' || status === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Withdraw application
    function withdrawApplication(applicationId) {
        if (confirm('Are you sure you want to withdraw this application? This action cannot be undone.')) {
            document.getElementById('withdrawApplicationId').value = applicationId;
            document.getElementById('withdrawForm').submit();
        }
    }

    // Complete application
    function completeApplication(applicationId) {
        if (confirm('Are you sure you want to mark this application as complete?')) {
            document.getElementById('completeApplicationId').value = applicationId;
            document.getElementById('completeForm').submit();
        }
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>