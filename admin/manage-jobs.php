<?php
// ===============================
// UniPart - manage jobs
// ===============================

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Restrict to Admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Unipart-job-finder/auth/login.php");
    exit();
}

// Handle POST requests for job management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $job_id = intval($_POST['job_id'] ?? 0);

    if (!$job_id) {
        $_SESSION['error'] = "Invalid job ID.";
        header("Location: /Unipart-job-finder/admin/manage-jobs.php");
        exit();
    }

    switch ($action) {
        case 'approve':
            $stmt = $conn->prepare("UPDATE jobs SET status = 'Active' WHERE job_id = ?");
            $stmt->bind_param("i", $job_id);
            if ($stmt->execute()) {
                // Log admin action
                logAdminAction($_SESSION['user_id'], "Approved job ID $job_id");
                $_SESSION['success'] = "Job approved successfully!";
            } else {
                $_SESSION['error'] = "Failed to approve job.";
            }
            break;

        case 'reject':
            $reason = $_POST['reason'] ?? '';
            $stmt = $conn->prepare("UPDATE jobs SET status = 'Inactive' WHERE job_id = ?");
            $stmt->bind_param("i", $job_id);
            if ($stmt->execute()) {
                // Log admin action
                logAdminAction($_SESSION['user_id'], "Rejected job ID $job_id" . ($reason ? ": $reason" : ""));
                $_SESSION['success'] = "Job rejected successfully!";
            } else {
                $_SESSION['error'] = "Failed to reject job.";
            }
            break;

        case 'delete':
            // First check if job has applications
            $checkStmt = $conn->prepare("SELECT COUNT(*) as app_count FROM applications WHERE job_id = ?");
            $checkStmt->bind_param("i", $job_id);
            $checkStmt->execute();
            $result = $checkStmt->get_result()->fetch_assoc();

            if ($result['app_count'] > 0) {
                $_SESSION['error'] = "Cannot delete job with existing applications. Close the job instead.";
            } else {
                $stmt = $conn->prepare("DELETE FROM jobs WHERE job_id = ?");
                $stmt->bind_param("i", $job_id);
                if ($stmt->execute()) {
                    // Log admin action
                    logAdminAction($_SESSION['user_id'], "Deleted job ID $job_id");
                    $_SESSION['success'] = "Job deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete job.";
                }
            }
            break;

        default:
            $_SESSION['error'] = "Invalid action.";
    }

    header("Location: /Unipart-job-finder/admin/manage-jobs.php");
    exit();
}

// Get statistics
$stats_query = $conn->query("
    SELECT
        COUNT(*) as total_jobs,
        SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_jobs,
        SUM(CASE WHEN status = 'Inactive' THEN 1 ELSE 0 END) as inactive_jobs,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_jobs
    FROM jobs
");
$stats = $stats_query ? $stats_query->fetch_assoc() : [
    'total_jobs' => 0,
    'active_jobs' => 0,
    'inactive_jobs' => 0,
    'pending_jobs' => 0
];

// Get jobs with pagination
$page = intval($_GET['page'] ?? 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$type_filter = $_GET['type'] ?? '';
$category_filter = $_GET['category'] ?? '';

// Build query
$sql = "
    SELECT
        j.job_id,
        j.title,
        j.type,
        j.category,
        j.status,
        j.posted_at,
        j.deadline,
        e.company_name,
        u.name as employer_name,
        COUNT(a.application_id) as application_count
    FROM jobs j
    LEFT JOIN employers e ON j.employer_id = e.employer_id
    LEFT JOIN users u ON e.user_id = u.user_id
    LEFT JOIN applications a ON j.job_id = a.job_id
    WHERE 1=1
";

$params = [];
$types = '';

if ($search) {
    $sql .= " AND (j.title LIKE ? OR e.company_name LIKE ? OR u.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'sss';
}

if ($status_filter) {
    $sql .= " AND j.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if ($type_filter) {
    $sql .= " AND j.type = ?";
    $params[] = $type_filter;
    $types .= 's';
}

if ($category_filter) {
    $sql .= " AND j.category = ?";
    $params[] = $category_filter;
    $types .= 's';
}

$sql .= " GROUP BY j.job_id ORDER BY j.posted_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$jobs = $stmt->get_result();

// Get total count for pagination
$count_sql = "SELECT COUNT(DISTINCT j.job_id) as total FROM jobs j LEFT JOIN employers e ON j.employer_id = e.employer_id LEFT JOIN users u ON e.user_id = u.user_id WHERE 1=1";

$count_params = [];
$count_types = '';

if ($search) {
    $count_sql .= " AND (j.title LIKE ? OR e.company_name LIKE ? OR u.name LIKE ?)";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
    $count_types .= 'sss';
}

if ($status_filter) {
    $count_sql .= " AND j.status = ?";
    $count_params[] = $status_filter;
    $count_types .= 's';
}

if ($type_filter) {
    $count_sql .= " AND j.type = ?";
    $count_params[] = $type_filter;
    $count_types .= 's';
}

if ($category_filter) {
    $count_sql .= " AND j.category = ?";
    $count_params[] = $category_filter;
    $count_types .= 's';
}

$count_stmt = $conn->prepare($count_sql);
if (!empty($count_params)) {
    $count_stmt->bind_param($count_types, ...$count_params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
$total_jobs = $count_result ? $count_result['total'] : 0;
$total_pages = ceil($total_jobs / $per_page);

// Page settings
$page_title = "Manage jobs - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/admin.css'];
$body_class = 'dashboard-page';
$page_type = 'admin';

// Include header
include __DIR__ . '/../includes/header.php';
?>

 <!-- Main Container -->
    <div class="container">

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-briefcase"></i> Manage All Jobs</h1>
            <p>Review, approve, and manage all job postings on the platform</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['total_jobs']; ?></div>
                        <div class="stat-label">Total Jobs</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['active_jobs']; ?></div>
                        <div class="stat-label">Active Jobs</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['pending_jobs']; ?></div>
                        <div class="stat-label">Pending Review</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['inactive_jobs']; ?></div>
                        <div class="stat-label">Closed Jobs</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <form method="GET" class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="searchJob">Search Jobs</label>
                    <input type="text" id="searchJob" name="search" class="filter-control search-input" placeholder="Search by job title or company..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" name="status" class="filter-control">
                        <option value="">All Status</option>
                        <option value="Active" <?php echo $status_filter === 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Inactive" <?php echo $status_filter === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterType">Job Type</label>
                    <select id="filterType" name="type" class="filter-control">
                        <option value="">All Types</option>
                        <option value="Part-Time" <?php echo $type_filter === 'Part-Time' ? 'selected' : ''; ?>>Part-Time</option>
                        <option value="Full-Time" <?php echo $type_filter === 'Full-Time' ? 'selected' : ''; ?>>Full-Time</option>
                        <option value="Remote" <?php echo $type_filter === 'Remote' ? 'selected' : ''; ?>>Remote</option>
                        <option value="Internship" <?php echo $type_filter === 'Internship' ? 'selected' : ''; ?>>Internship</option>
                        <option value="Freelance" <?php echo $type_filter === 'Freelance' ? 'selected' : ''; ?>>Freelance</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterCategory">Category</label>
                    <select id="filterCategory" name="category" class="filter-control">
                        <option value="">All Categories</option>
                        <option value="IT & Software" <?php echo $category_filter === 'IT & Software' ? 'selected' : ''; ?>>IT & Software</option>
                        <option value="Marketing" <?php echo $category_filter === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                        <option value="Design" <?php echo $category_filter === 'Design' ? 'selected' : ''; ?>>Design</option>
                        <option value="Sales" <?php echo $category_filter === 'Sales' ? 'selected' : ''; ?>>Sales</option>
                        <option value="Writing & Content" <?php echo $category_filter === 'Writing & Content' ? 'selected' : ''; ?>>Writing & Content</option>
                        <option value="Other" <?php echo $category_filter === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Jobs Table -->
        <div class="table-container">
            <div class="table-header">
                <h3>All Job Postings</h3>
                <div class="table-actions">
                    <button class="btn-export" onclick="exportData()">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                </div>
            </div>

            <table class="jobs-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Applications</th>
                        <th>Posted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="jobsTableBody">
                    <?php if ($jobs->num_rows > 0): ?>
                        <?php while ($job = $jobs->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo str_pad($job['job_id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['company_name'] ?? 'Unknown Company'); ?></td>
                            <td><span class="type-badge type-<?php echo strtolower(str_replace(' ', '-', $job['type'])); ?>"><?php echo htmlspecialchars($job['type']); ?></span></td>
                            <td><?php echo htmlspecialchars($job['category']); ?></td>
                            <td><span class="status-badge status-<?php echo strtolower($job['status']); ?>"><?php echo htmlspecialchars($job['status']); ?></span></td>
                            <td><?php echo $job['application_count']; ?></td>
                            <td><?php echo date("M d, Y", strtotime($job['posted_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewJob(<?php echo $job['job_id']; ?>)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($job['status'] !== 'Active'): ?>
                                    <button class="btn-action btn-approve" onclick="approveJob(<?php echo $job['job_id']; ?>)" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if ($job['status'] === 'Active'): ?>
                                    <button class="btn-action btn-reject" onclick="rejectJob(<?php echo $job['job_id']; ?>)" title="Reject/Close">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn-action btn-delete" onclick="deleteJob(<?php echo $job['job_id']; ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px;">
                                <i class="fas fa-briefcase" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="color: #666; margin: 0;">No jobs found matching your criteria.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info">
                    Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $per_page, $total_jobs); ?> of <?php echo $total_jobs; ?> jobs
                </div>
                <div class="pagination-buttons">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?><?php echo $type_filter ? '&type=' . urlencode($type_filter) : ''; ?><?php echo $category_filter ? '&category=' . urlencode($category_filter) : ''; ?>" class="page-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <button class="page-btn" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    <?php endif; ?>

                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);

                    for ($i = $start_page; $i <= $end_page; $i++):
                    ?>
                        <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?><?php echo $type_filter ? '&type=' . urlencode($type_filter) : ''; ?><?php echo $category_filter ? '&category=' . urlencode($category_filter) : ''; ?>" class="page-btn <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo ($page + 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?><?php echo $type_filter ? '&type=' . urlencode($type_filter) : ''; ?><?php echo $category_filter ? '&category=' . urlencode($category_filter) : ''; ?>" class="page-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button class="page-btn" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- View Job Details Modal -->
    <div class="modal" id="viewJobModal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Job Details</span>
                <span class="modal-close" onclick="closeModal('viewJobModal')">&times;</span>
            </div>
            <div class="modal-body">
                <h3 style="color: #007BFF; margin-bottom: 15px;">Web Developer - Part Time</h3>
                
                <div class="modal-info-grid">
                    <div class="modal-info-label">Company:</div>
                    <div class="modal-info-value">Tech Innovations Pvt Ltd</div>
                    
                    <div class="modal-info-label">Job Type:</div>
                    <div class="modal-info-value"><span class="type-badge type-part-time">Part-Time</span></div>
                    
                    <div class="modal-info-label">Category:</div>
                    <div class="modal-info-value">IT & Software</div>
                    
                    <div class="modal-info-label">Status:</div>
                    <div class="modal-info-value"><span class="status-badge status-active">Active</span></div>
                    
                    <div class="modal-info-label">Location:</div>
                    <div class="modal-info-value">Colombo, Sri Lanka</div>
                    
                    <div class="modal-info-label">Pay Rate:</div>
                    <div class="modal-info-value">LKR 50,000 - 75,000 /month</div>
                    
                    <div class="modal-info-label">Posted Date:</div>
                    <div class="modal-info-value">Nov 17, 2025</div>
                    
                    <div class="modal-info-label">Deadline:</div>
                    <div class="modal-info-value">Dec 17, 2025</div>
                    
                    <div class="modal-info-label">Applications:</div>
                    <div class="modal-info-value">12 Applications</div>
                    
                    <div class="modal-info-label">Employer Email:</div>
                    <div class="modal-info-value">hr@techinnovations.lk</div>
                </div>
                
                <div style="margin-top: 25px;">
                    <h4 style="color: #212529; margin-bottom: 10px;">Job Description:</h4>
                    <p style="color: #6C757D; line-height: 1.8;">
                        We are looking for a talented and motivated Web Developer to join our team on a part-time basis. 
                        This position is perfect for university students who want to gain real-world experience while 
                        continuing their studies.
                    </p>
                </div>
                
                <div style="margin-top: 20px;">
                    <h4 style="color: #212529; margin-bottom: 10px;">Requirements:</h4>
                    <p style="color: #6C757D; line-height: 1.8;">
                        Strong knowledge of HTML5, CSS3, and JavaScript. Experience with PHP and MySQL databases. 
                        Familiarity with responsive design principles.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('viewJobModal')">
                    <i class="fas fa-times"></i> Close
                </button>
                <a href="../jobs/job-details.php?id=1" class="btn btn-primary">
                    <i class="fas fa-external-link-alt"></i> View Full Page
                </a>
            </div>
        </div>
    </div>

    <!-- Approve Job Modal -->
    <div class="modal" id="approveJobModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #28A745;">
                    <i class="fas fa-check-circle"></i> Approve Job
                </span>
                <span class="modal-close" onclick="closeModal('approveJobModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this job posting?</p>
                <p><strong>Job Title:</strong> <span id="approveJobTitle">Web Developer - Part Time</span></p>
                <p><strong>Company:</strong> <span id="approveJobCompany">Tech Innovations Pvt Ltd</span></p>
                <p style="margin-top: 15px; color: #6C757D;">
                    Once approved, this job will be visible to all students on the platform.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('approveJobModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-success" onclick="confirmApprove()">
                    <i class="fas fa-check"></i> Approve Job
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Job Modal -->
    <div class="modal" id="rejectJobModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #DC3545;">
                    <i class="fas fa-times-circle"></i> Reject/Close Job
                </span>
                <span class="modal-close" onclick="closeModal('rejectJobModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject or close this job posting?</p>
                <p><strong>Job Title:</strong> <span id="rejectJobTitle">Web Developer - Part Time</span></p>
                <p><strong>Company:</strong> <span id="rejectJobCompany">Tech Innovations Pvt Ltd</span></p>
                
                <div style="margin-top: 20px;">
                    <label for="rejectReason" style="display: block; font-weight: 600; margin-bottom: 8px;">
                        Reason for Rejection (Optional):
                    </label>
                    <textarea id="rejectReason" class="filter-control" rows="4" 
                              placeholder="Enter reason for rejection..."></textarea>
                </div>
                
                <p style="margin-top: 15px; color: #6C757D;">
                    This job will be marked as closed and no longer visible to students.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('rejectJobModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-danger" onclick="confirmReject()">
                    <i class="fas fa-ban"></i> Reject Job
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Job Modal -->
    <div class="modal" id="deleteJobModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #DC3545;">
                    <i class="fas fa-exclamation-triangle"></i> Delete Job
                </span>
                <span class="modal-close" onclick="closeModal('deleteJobModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p style="color: #DC3545; font-weight: 600;">⚠️ Warning: This action cannot be undone!</p>
                <p>Are you sure you want to permanently delete this job posting?</p>
                <p><strong>Job Title:</strong> <span id="deleteJobTitle">Web Developer - Part Time</span></p>
                <p><strong>Company:</strong> <span id="deleteJobCompany">Tech Innovations Pvt Ltd</span></p>
                <p style="margin-top: 15px; color: #6C757D;">
                    All associated data including applications, statistics, and history will be permanently removed from the system.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('deleteJobModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Forms for Actions -->
    <form id="approveForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="approve">
        <input type="hidden" name="job_id" id="approveJobId">
    </form>

    <form id="rejectForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="reject">
        <input type="hidden" name="job_id" id="rejectJobId">
        <input type="hidden" name="reason" id="rejectReasonValue">
    </form>

    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="job_id" id="deleteJobId">
    </form>

    <script>
        // Sample job data
        let currentJobId = null;

        // View job details
        function viewJob(jobId) {
            currentJobId = jobId;
            document.getElementById('viewJobModal').classList.add('active');
        }

        // Approve job
        function approveJob(jobId) {
            currentJobId = jobId;
            document.getElementById('approveJobModal').classList.add('active');
        }

        // Reject job
        function rejectJob(jobId) {
            currentJobId = jobId;
            document.getElementById('rejectJobModal').classList.add('active');
        }

        // Delete job
        function deleteJob(jobId) {
            currentJobId = jobId;
            document.getElementById('deleteJobModal').classList.add('active');
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        // Confirm approve
        function confirmApprove() {
            document.getElementById('approveJobId').value = currentJobId;
            document.getElementById('approveForm').submit();
        }

        // Confirm reject
        function confirmReject() {
            const reason = document.getElementById('rejectReason').value;
            document.getElementById('rejectJobId').value = currentJobId;
            document.getElementById('rejectReasonValue').value = reason;
            document.getElementById('rejectForm').submit();
        }

        // Confirm delete
        function confirmDelete() {
            document.getElementById('deleteJobId').value = currentJobId;
            document.getElementById('deleteForm').submit();
        }



        // Apply filters - now handled by form submission
        function applyFilters() {
            // Filters are now applied via form submission
        }

        // Reset filters - now handled by link
        function resetFilters() {
            // Reset is now handled by link to current page without parameters
        }

        // Export data
        function exportData() {
            // In actual implementation: generate and download CSV file
            alert('Export functionality not yet implemented.');
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC key to close modals
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });
    </script>

<?php include __DIR__ . '/../includes/footer.php'; ?>