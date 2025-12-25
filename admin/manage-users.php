<?php
// ===============================
// UniPart - manage users
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

// Handle POST requests for user management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = intval($_POST['user_id'] ?? 0);

    if (!$user_id) {
        $_SESSION['error'] = "Invalid user ID.";
        header("Location: /Unipart-job-finder/admin/manage-users.php");
        exit();
    }

    switch ($action) {
        case 'approve':
            // For employers, set verified = 1
            $stmt = $conn->prepare("UPDATE employers SET verified = 1 WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Log admin action
            logAdminAction($_SESSION['user_id'], "Approved user ID $user_id");
            $_SESSION['success'] = "User approved successfully!";
            break;

        case 'suspend':
            // Add a status field to users table or use a different approach
            // For now, we'll use a simple approach - could add status to users table later
            $_SESSION['error'] = "Suspend functionality not yet implemented.";
            break;

        case 'reactivate':
            // Reactivate suspended user
            $_SESSION['error'] = "Reactivate functionality not yet implemented.";
            break;

        case 'delete':
            // Check if user has associated data
            $checkStmt = $conn->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM students WHERE user_id = ?) +
                    (SELECT COUNT(*) FROM employers WHERE user_id = ?) +
                    (SELECT COUNT(*) FROM applications WHERE student_id IN (SELECT student_id FROM students WHERE user_id = ?)) +
                    (SELECT COUNT(*) FROM jobs WHERE employer_id IN (SELECT employer_id FROM employers WHERE user_id = ?)) as total_records
            ");
            $checkStmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
            $checkStmt->execute();
            $result = $checkStmt->get_result()->fetch_assoc();

            if ($result['total_records'] > 0) {
                $_SESSION['error'] = "Cannot delete user with associated data. User has active records.";
            } else {
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                if ($stmt->execute()) {
                    // Log admin action
                    logAdminAction($_SESSION['user_id'], "Deleted user ID $user_id");
                    $_SESSION['success'] = "User deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete user.";
                }
            }
            break;

        default:
            $_SESSION['error'] = "Invalid action.";
    }

    header("Location: /Unipart-job-finder/admin/manage-users.php");
    exit();
}

// Get statistics
$stats_query = $conn->query("
    SELECT
        COUNT(*) as total_users,
        SUM(CASE WHEN role = 'Student' THEN 1 ELSE 0 END) as total_students,
        SUM(CASE WHEN role = 'Employer' THEN 1 ELSE 0 END) as total_employers,
        SUM(CASE WHEN role = 'Admin' THEN 1 ELSE 0 END) as total_admins,
        SUM(CASE WHEN u.user_id IN (SELECT user_id FROM employers WHERE verified = 0) THEN 1 ELSE 0 END) as pending_employers
    FROM users u
");
$stats = $stats_query ? $stats_query->fetch_assoc() : [
    'total_users' => 0,
    'total_students' => 0,
    'total_employers' => 0,
    'total_admins' => 0,
    'pending_employers' => 0
];

// Get users with pagination
$page = intval($_GET['page'] ?? 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Filters
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? '';
$status_filter = $_GET['status'] ?? '';
$verified_filter = $_GET['verified'] ?? '';

// Build query
$sql = "
    SELECT
        u.user_id,
        u.name,
        u.email,
        u.role,
        u.member_since,
        u.created_at,
        CASE
            WHEN u.role = 'Employer' THEN COALESCE(e.verified, 0)
            ELSE 1
        END as verified,
        CASE
            WHEN u.role = 'Employer' THEN e.company_name
            ELSE NULL
        END as company_name,
        CASE
            WHEN u.role = 'Student' THEN s.university_id
            ELSE NULL
        END as university_id
    FROM users u
    LEFT JOIN employers e ON u.user_id = e.user_id
    LEFT JOIN students s ON u.user_id = s.user_id
    WHERE 1=1
";

$params = [];
$types = '';

if ($search) {
    $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR e.company_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'sss';
}

if ($role_filter) {
    $sql .= " AND u.role = ?";
    $params[] = $role_filter;
    $types .= 's';
}

if ($verified_filter === 'verified') {
    $sql .= " AND (u.role != 'Employer' OR e.verified = 1)";
    // No additional parameters needed
} elseif ($verified_filter === 'unverified') {
    $sql .= " AND u.role = 'Employer' AND e.verified = 0";
    // No additional parameters needed
}

$sql .= " ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$users = $stmt->get_result();

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM users u LEFT JOIN employers e ON u.user_id = e.user_id WHERE 1=1";

$count_params = [];
$count_types = '';

if ($search) {
    $count_sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR e.company_name LIKE ?)";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
    $count_params[] = "%$search%";
    $count_types .= 'sss';
}

if ($role_filter) {
    $count_sql .= " AND u.role = ?";
    $count_params[] = $role_filter;
    $count_types .= 's';
}

if ($verified_filter === 'verified') {
    $count_sql .= " AND (u.role != 'Employer' OR e.verified = 1)";
} elseif ($verified_filter === 'unverified') {
    $count_sql .= " AND u.role = 'Employer' AND e.verified = 0";
}

$count_stmt = $conn->prepare($count_sql);
if (!empty($count_params)) {
    $count_stmt->bind_param($count_types, ...$count_params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
$total_users = $count_result ? $count_result['total'] : 0;
$total_pages = ceil($total_users / $per_page);

// Page settings
$page_title = "Manage users - UniPart";
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
            <h1><i class="fas fa-users"></i> Manage Users</h1>
            <p>View, approve, and manage all registered users on the platform</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['total_students']; ?></div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['total_employers']; ?></div>
                        <div class="stat-label">Employers</div>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['pending_employers']; ?></div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- Filter Section -->
        <form method="GET" class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="searchUser">Search Users</label>
                    <input type="text" id="searchUser" name="search" class="filter-control search-input" placeholder="Search by name, email, or company..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="filter-group">
                    <label for="filterRole">User Role</label>
                    <select id="filterRole" name="role" class="filter-control">
                        <option value="">All Roles</option>
                        <option value="Student" <?php echo $role_filter === 'Student' ? 'selected' : ''; ?>>Student</option>
                        <option value="Employer" <?php echo $role_filter === 'Employer' ? 'selected' : ''; ?>>Employer</option>
                        <option value="Admin" <?php echo $role_filter === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" name="status" class="filter-control">
                        <option value="">All Status</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="suspended" <?php echo $status_filter === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterVerified">Verification</label>
                    <select id="filterVerified" name="verified" class="filter-control">
                        <option value="">All</option>
                        <option value="verified" <?php echo $verified_filter === 'verified' ? 'selected' : ''; ?>>Verified</option>
                        <option value="unverified" <?php echo $verified_filter === 'unverified' ? 'selected' : ''; ?>>Unverified</option>
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

        <!-- All Users Tab -->
        <div class="tab-content active" id="allTab">
            <div class="table-container">
                <div class="table-header">
                    <h3>All Registered Users</h3>
                    <div class="table-actions">
                        <button class="btn-export" onclick="exportData()">
                            <i class="fas fa-download"></i> Export CSV
                        </button>
                    </div>
                </div>

                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Verification</th>
                            <th>Joined Date</th>
                            <th>Last Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php if ($users->num_rows > 0): ?>
                            <?php while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td>#U<?php echo str_pad($user['user_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar" style="background-color: <?php
                                            $colors = ['#007BFF', '#28A745', '#FD7E14', '#6F42C1', '#DC3545', '#17A2B8', '#FFC107', '#6C757D'];
                                            echo $colors[$user['user_id'] % count($colors)];
                                        ?>;">
                                            <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                                            <span class="user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                                            <?php if ($user['company_name']): ?>
                                                <span class="user-company"><?php echo htmlspecialchars($user['company_name']); ?></span>
                                            <?php elseif ($user['university_id']): ?>
                                                <span class="user-university"><?php echo htmlspecialchars($user['university_id']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="role-badge role-<?php echo strtolower($user['role']); ?>"><?php echo htmlspecialchars($user['role']); ?></span></td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>
                                    <?php if ($user['role'] === 'Employer'): ?>
                                        <span class="status-badge status-<?php echo $user['verified'] ? 'verified' : 'pending'; ?>">
                                            <?php echo $user['verified'] ? 'Verified' : 'Pending'; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-verified">Verified</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date("M d, Y", strtotime($user['member_since'])); ?></td>
                                <td><?php echo date("M d, Y", strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" onclick="viewUser(<?php echo $user['user_id']; ?>)" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($user['role'] === 'Employer' && !$user['verified']): ?>
                                        <button class="btn-action btn-approve" onclick="approveUser(<?php echo $user['user_id']; ?>)" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn-action btn-suspend" onclick="suspendUser(<?php echo $user['user_id']; ?>)" title="Suspend">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="deleteUser(<?php echo $user['user_id']; ?>)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-users" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                    <p style="color: #666; margin: 0;">No users found matching your criteria.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $per_page, $total_users); ?> of <?php echo $total_users; ?> users
                    </div>
                    <div class="pagination-buttons">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo ($page - 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?><?php echo $verified_filter ? '&verified=' . urlencode($verified_filter) : ''; ?>" class="page-btn">
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
                            <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?><?php echo $verified_filter ? '&verified=' . urlencode($verified_filter) : ''; ?>" class="page-btn <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo ($page + 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?><?php echo $verified_filter ? '&verified=' . urlencode($verified_filter) : ''; ?>" class="page-btn">
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
    </div>

    <!-- Hidden Forms for Actions -->
    <form id="approveForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="approve">
        <input type="hidden" name="user_id" id="approveUserId">
    </form>

    <form id="suspendForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="suspend">
        <input type="hidden" name="user_id" id="suspendUserId">
    </form>

    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="user_id" id="deleteUserId">
    </form>

    <!-- View User Details Modal -->
    <div class="modal" id="viewUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <span>User Profile</span>
                <span class="modal-close" onclick="closeModal('viewUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-user-avatar">AS</div>
                <h3 style="text-align: center; color: #007BFF; margin-bottom: 20px;">Amal Silva</h3>
                
                <div class="modal-info-grid">
                    <div class="modal-info-label">User ID:</div>
                    <div class="modal-info-value">#U001</div>
                    
                    <div class="modal-info-label">Email:</div>
                    <div class="modal-info-value">amal.silva@email.com</div>
                    
                    <div class="modal-info-label">Role:</div>
                    <div class="modal-info-value"><span class="role-badge role-student">Student</span></div>
                    
                    <div class="modal-info-label">Status:</div>
                    <div class="modal-info-value"><span class="status-badge status-active">Active</span></div>
                    
                    <div class="modal-info-label">Verification:</div>
                    <div class="modal-info-value"><span class="status-badge status-verified">Verified</span></div>
                    
                    <div class="modal-info-label">Department:</div>
                    <div class="modal-info-value">Computer Science</div>
                    
                    <div class="modal-info-label">Skills:</div>
                    <div class="modal-info-value">HTML, CSS, JavaScript, PHP</div>
                    
                    <div class="modal-info-label">Phone:</div>
                    <div class="modal-info-value">+94 77 123 4567</div>
                    
                    <div class="modal-info-label">Joined Date:</div>
                    <div class="modal-info-value">Oct 15, 2025</div>
                    
                    <div class="modal-info-label">Last Active:</div>
                    <div class="modal-info-value">2 hours ago</div>
                    
                    <div class="modal-info-label">Applications:</div>
                    <div class="modal-info-value">15 Applications Submitted</div>
                    
                    <div class="modal-info-label">Rating:</div>
                    <div class="modal-info-value">
                        <i class="fas fa-star" style="color: #FD7E14;"></i>
                        <i class="fas fa-star" style="color: #FD7E14;"></i>
                        <i class="fas fa-star" style="color: #FD7E14;"></i>
                        <i class="fas fa-star" style="color: #FD7E14;"></i>
                        <i class="fas fa-star-half-alt" style="color: #FD7E14;"></i>
                        4.5/5
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('viewUserModal')">
                    <i class="fas fa-times"></i> Close
                </button>
                <button class="btn btn-primary" onclick="editUser()">
                    <i class="fas fa-edit"></i> Edit User
                </button>
            </div>
        </div>
    </div>

    <!-- Approve User Modal -->
    <div class="modal" id="approveUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #28A745;">
                    <i class="fas fa-check-circle"></i> Approve User
                </span>
                <span class="modal-close" onclick="closeModal('approveUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this user account?</p>
                <p><strong>User:</strong> <span id="approveUserName">Nimal Perera</span></p>
                <p><strong>Email:</strong> <span id="approveUserEmail">nimal.p@email.com</span></p>
                <p><strong>Role:</strong> <span id="approveUserRole">Student</span></p>
                <p style="margin-top: 15px; color: #6C757D;">
                    Once approved, this user will have full access to the platform.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('approveUserModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-success" onclick="confirmApprove()">
                    <i class="fas fa-check"></i> Approve User
                </button>
            </div>
        </div>
    </div>

    <!-- Suspend User Modal -->
    <div class="modal" id="suspendUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #FD7E14;">
                    <i class="fas fa-ban"></i> Suspend User
                </span>
                <span class="modal-close" onclick="closeModal('suspendUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to suspend this user account?</p>
                <p><strong>User:</strong> <span id="suspendUserName">Amal Silva</span></p>
                <p><strong>Email:</strong> <span id="suspendUserEmail">amal.silva@email.com</span></p>
                
                <div style="margin-top: 20px;">
                    <label for="suspendReason" style="display: block; font-weight: 600; margin-bottom: 8px;">
                        Reason for Suspension <span style="color: #DC3545;">*</span>
                    </label>
                    <textarea id="suspendReason" class="filter-control" rows="4" 
                              placeholder="Enter reason for suspension..." required></textarea>
                </div>
                
                <div style="margin-top: 15px;">
                    <label for="suspendDuration" style="display: block; font-weight: 600; margin-bottom: 8px;">
                        Suspension Duration
                    </label>
                    <select id="suspendDuration" class="filter-control">
                        <option value="7">7 Days</option>
                        <option value="14">14 Days</option>
                        <option value="30">30 Days</option>
                        <option value="permanent">Permanent</option>
                    </select>
                </div>
                
                <p style="margin-top: 15px; color: #6C757D;">
                    The user will be notified via email and will not be able to access the platform during suspension.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('suspendUserModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-warning" onclick="confirmSuspend()">
                    <i class="fas fa-ban"></i> Suspend User
                </button>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal" id="deleteUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #DC3545;">
                    <i class="fas fa-exclamation-triangle"></i> Delete User
                </span>
                <span class="modal-close" onclick="closeModal('deleteUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p style="color: #DC3545; font-weight: 600;">⚠️ Warning: This action cannot be undone!</p>
                <p>Are you sure you want to permanently delete this user account?</p>
                <p><strong>User:</strong> <span id="deleteUserName">Amal Silva</span></p>
                <p><strong>Email:</strong> <span id="deleteUserEmail">amal.silva@email.com</span></p>
                
                <div style="margin-top: 20px; padding: 15px; background-color: #FFF3CD; border-radius: 6px;">
                    <p style="color: #856404; font-weight: 600;">All user data will be permanently deleted:</p>
                    <ul style="margin-left: 20px; margin-top: 10px; color: #856404;">
                        <li>User profile and personal information</li>
                        <li>Job applications and history</li>
                        <li>Ratings and feedback</li>
                        <li>All associated records</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('deleteUserModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>

    <!-- Reactivate User Modal -->
    <div class="modal" id="reactivateUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <span style="color: #28A745;">
                    <i class="fas fa-undo"></i> Reactivate User
                </span>
                <span class="modal-close" onclick="closeModal('reactivateUserModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reactivate this suspended user account?</p>
                <p><strong>User:</strong> <span id="reactivateUserName">Kasun Jayawardena</span></p>
                <p><strong>Email:</strong> <span id="reactivateUserEmail">kasun.j@email.com</span></p>
                <p style="margin-top: 15px; color: #6C757D;">
                    This user will regain full access to the platform immediately.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('reactivateUserModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-success" onclick="confirmReactivate()">
                    <i class="fas fa-undo"></i> Reactivate User
                </button>
            </div>
        </div>
    </div>

    <script>
        // Current user ID
        let currentUserId = null;

        // Switch tabs
        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // In actual implementation: filter users based on tab
            showAlert('success', `Switched to ${tab} view`);
        }

        // View user details
        function viewUser(userId) {
            currentUserId = userId;
            document.getElementById('viewUserModal').classList.add('active');
        }

        // Approve user
        function approveUser(userId) {
            currentUserId = userId;
            document.getElementById('approveUserModal').classList.add('active');
        }

        // Suspend user
        function suspendUser(userId) {
            currentUserId = userId;
            document.getElementById('suspendUserModal').classList.add('active');
        }

        // Delete user
        function deleteUser(userId) {
            currentUserId = userId;
            document.getElementById('deleteUserModal').classList.add('active');
        }

        // Reactivate user
        function reactivateUser(userId) {
            currentUserId = userId;
            document.getElementById('reactivateUserModal').classList.add('active');
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
            document.getElementById('approveUserId').value = currentUserId;
            document.getElementById('approveForm').submit();
        }

        // Confirm suspend
        function confirmSuspend() {
            const reason = document.getElementById('suspendReason').value;
            const duration = document.getElementById('suspendDuration').value;
            
            if (!reason.trim()) {
                alert('Please provide a reason for suspension.');
                return;
            }
            
            // For now, just show not implemented
            alert('Suspend functionality not yet implemented.');
            closeModal('suspendUserModal');
        }

        // Confirm delete
        function confirmDelete() {
            document.getElementById('deleteUserId').value = currentUserId;
            document.getElementById('deleteForm').submit();
        }

        // Confirm reactivate
        function confirmReactivate() {
            // For now, just show not implemented
            alert('Reactivate functionality not yet implemented.');
            closeModal('reactivateUserModal');
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

        // Edit user
        function editUser() {
            // In actual implementation: redirect to edit page
            showAlert('success', 'Redirecting to edit user page...');
        }

        // Search functionality
        document.getElementById('searchUser').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });



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