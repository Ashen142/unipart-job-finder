<?php
// ===============================
// UniPart - manage users
// ===============================

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
// -------------------------------
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
        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-check-circle"></i>
            <span id="successMessage">Action completed successfully!</span>
        </div>

        <div class="alert alert-error" id="errorAlert">
            <i class="fas fa-exclamation-circle"></i>
            <span id="errorMessage">An error occurred. Please try again.</span>
        </div>

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
                        <div class="stat-value">156</div>
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
                        <div class="stat-value">98</div>
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
                        <div class="stat-value">55</div>
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
                        <div class="stat-value">12</div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="searchUser">Search Users</label>
                    <input type="text" id="searchUser" class="filter-control search-input" placeholder="Search by name, email, or company...">
                </div>

                <div class="filter-group">
                    <label for="filterRole">User Role</label>
                    <select id="filterRole" class="filter-control">
                        <option value="">All Roles</option>
                        <option value="student">Student</option>
                        <option value="employer">Employer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" class="filter-control">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterVerified">Verification</label>
                    <select id="filterVerified" class="filter-control">
                        <option value="">All</option>
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>&nbsp;</label>
                    <div>
                        <button class="btn-filter" onclick="applyFilters()">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <button class="btn-reset" onclick="resetFilters()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

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
                        <tr>
                            <td>#U001</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">AS</div>
                                    <div class="user-details">
                                        <span class="user-name">Amal Silva</span>
                                        <span class="user-email">amal.silva@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-student">Student</span></td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td><span class="status-badge status-verified">Verified</span></td>
                            <td>Oct 15, 2025</td>
                            <td>2 hours ago</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewUser(1)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-suspend" onclick="suspendUser(1)" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser(1)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#U002</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar" style="background-color: #28A745;">TI</div>
                                    <div class="user-details">
                                        <span class="user-name">Tech Innovations Pvt Ltd</span>
                                        <span class="user-email">hr@techinnovations.lk</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-employer">Employer</span></td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td><span class="status-badge status-verified">Verified</span></td>
                            <td>Oct 20, 2025</td>
                            <td>1 day ago</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewUser(2)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-suspend" onclick="suspendUser(2)" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser(2)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#U003</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar" style="background-color: #FD7E14;">NP</div>
                                    <div class="user-details">
                                        <span class="user-name">Nimal Perera</span>
                                        <span class="user-email">nimal.p@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-student">Student</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>Nov 18, 2025</td>
                            <td>3 hours ago</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewUser(3)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveUser(3)" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser(3)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#U004</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar" style="background-color: #6F42C1;">SF</div>
                                    <div class="user-details">
                                        <span class="user-name">Sanjana Fernando</span>
                                        <span class="user-email">sanjana.f@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-student">Student</span></td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td><span class="status-badge status-verified">Verified</span></td>
                            <td>Nov 01, 2025</td>
                            <td>5 hours ago</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewUser(4)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-suspend" onclick="suspendUser(4)" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser(4)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#U005</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar" style="background-color: #DC3545;">DM</div>
                                    <div class="user-details">
                                        <span class="user-name">Digital Marketing Hub</span>
                                        <span class="user-email">contact@digitalmarketing.lk</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-employer">Employer</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>Nov 19, 2025</td>
                            <td>1 hour ago</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewUser(5)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveUser(5)" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser(5)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#U006</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar" style="background-color: #17A2B8;">KJ</div>
                                    <div class="user-details">
                                        <span class="user-name">Kasun Jayawardena</span>
                                        <span class="user-email">kasun.j@email.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge role-student">Student</span></td>
                            <td><span class="status-badge status-suspended">Suspended</span></td>
                            <td><span class="status-badge status-verified">Verified</span></td>
                            <td>Sep 28, 2025</td>
                            <td>2 weeks ago</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewUser(6)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-approve" onclick="reactivateUser(6)" title="Reactivate">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser(6)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        Showing 1 to 6 of 156 users
                    </div>
                    <div class="pagination-buttons">
                        <button class="page-btn" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">4</button>
                        <button class="page-btn">5</button>
                        <button class="page-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            showAlert('success', 'User approved successfully!');
            closeModal('approveUserModal');
            // In actual implementation: send AJAX request
        }

        // Confirm suspend
        function confirmSuspend() {
            const reason = document.getElementById('suspendReason').value;
            const duration = document.getElementById('suspendDuration').value;
            
            if (!reason.trim()) {
                showAlert('error', 'Please provide a reason for suspension.');
                return;
            }
            
            showAlert('success', 'User has been suspended.');
            closeModal('suspendUserModal');
            // In actual implementation: send AJAX request with reason and duration
        }

        // Confirm delete
        function confirmDelete() {
            showAlert('success', 'User deleted permanently.');
            closeModal('deleteUserModal');
            // In actual implementation: send AJAX request to delete user
        }

        // Confirm reactivate
        function confirmReactivate() {
            showAlert('success', 'User account reactivated successfully!');
            closeModal('reactivateUserModal');
            // In actual implementation: send AJAX request
        }

        // Show alert
        function showAlert(type, message) {
            const alertId = type === 'success' ? 'successAlert' : 'errorAlert';
            const messageId = type === 'success' ? 'successMessage' : 'errorMessage';
            
            document.getElementById(messageId).textContent = message;
            document.getElementById(alertId).classList.add('show');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            setTimeout(() => {
                document.getElementById(alertId).classList.remove('show');
            }, 5000);
        }

        // Apply filters
        function applyFilters() {
            const search = document.getElementById('searchUser').value.toLowerCase();
            const role = document.getElementById('filterRole').value;
            const status = document.getElementById('filterStatus').value;
            const verified = document.getElementById('filterVerified').value;
            
            // In actual implementation: filter table rows or make AJAX request
            showAlert('success', 'Filters applied successfully!');
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchUser').value = '';
            document.getElementById('filterRole').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterVerified').value = '';
            
            // In actual implementation: reload all users
            showAlert('success', 'Filters reset.');
        }

        // Export data
        function exportData() {
            // In actual implementation: generate and download CSV file
            showAlert('success', 'Exporting data to CSV...');
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

        // Pagination buttons
        document.querySelectorAll('.page-btn').forEach((btn, index) => {
            btn.addEventListener('click', function() {
                if (!this.disabled && !this.classList.contains('active')) {
                    document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                    if (this.textContent.trim() && !isNaN(this.textContent.trim())) {
                        this.classList.add('active');
                    }
                    // In actual implementation: load the corresponding page data
                }
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