<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

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

        <!-- Success/Error Messages (PHP will handle display logic) -->
        <!-- Uncomment when implementing PHP -->
        <!--
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Application status updated successfully!
        </div>
        -->

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-file-alt stat-icon"></i>
                <h3>Total Applications</h3>
                <div class="stat-value">47</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock stat-icon"></i>
                <h3>Pending Review</h3>
                <div class="stat-value">23</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle stat-icon"></i>
                <h3>Accepted</h3>
                <div class="stat-value">18</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-times-circle stat-icon"></i>
                <h3>Rejected</h3>
                <div class="stat-value">6</div>
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
                            <option value="1">Web Developer - Part Time</option>
                            <option value="2">Graphic Designer</option>
                            <option value="3">Content Writer</option>
                            <option value="4">Data Entry Clerk</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="status_filter">Filter by Status</label>
                        <select id="status_filter" name="status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="search">Search Student</label>
                        <input type="text" id="search" name="search" placeholder="Enter student name...">
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="applications-section">
            <h2 class="section-title">Applications List</h2>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Job Title</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Row 1 -->
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">JS</div>
                                    <div class="student-details">
                                        <h4>John Smith</h4>
                                        <p>Computer Science</p>
                                    </div>
                                </div>
                            </td>
                            <td>Web Developer - Part Time</td>
                            <td>Nov 15, 2025</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> View
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Row 2 -->
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">EJ</div>
                                    <div class="student-details">
                                        <h4>Emily Johnson</h4>
                                        <p>Graphic Design</p>
                                    </div>
                                </div>
                            </td>
                            <td>Graphic Designer</td>
                            <td>Nov 14, 2025</td>
                            <td><span class="status-badge status-accepted">Accepted</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> View
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info btn-sm">
                                        <i class="fas fa-envelope"></i> Message
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Row 3 -->
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">MB</div>
                                    <div class="student-details">
                                        <h4>Michael Brown</h4>
                                        <p>Business Administration</p>
                                    </div>
                                </div>
                            </td>
                            <td>Data Entry Clerk</td>
                            <td>Nov 13, 2025</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> View
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Row 4 -->
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">SD</div>
                                    <div class="student-details">
                                        <h4>Sarah Davis</h4>
                                        <p>English Literature</p>
                                    </div>
                                </div>
                            </td>
                            <td>Content Writer</td>
                            <td>Nov 12, 2025</td>
                            <td><span class="status-badge status-rejected">Rejected</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> View
                                </a>
                            </td>
                            <td>
                                <span style="color: #6C757D; font-size: 13px;">No actions available</span>
                            </td>
                        </tr>

                        <!-- Sample Row 5 -->
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">DW</div>
                                    <div class="student-details">
                                        <h4>David Wilson</h4>
                                        <p>Information Technology</p>
                                    </div>
                                </div>
                            </td>
                            <td>Web Developer - Part Time</td>
                            <td>Nov 11, 2025</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> View
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State (show when no applications) -->
            <!-- Uncomment when no data is available -->
            <!--
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Applications Yet</h3>
                <p>You haven't received any applications for your jobs yet.</p>
            </div>
            -->
        </div>
    </div>

    <script>
        // Add confirmation dialogs for actions
        document.querySelectorAll('.btn-success').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to accept this application?')) {
                    e.preventDefault();
                }
            });
        });

        document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to reject this application?')) {
                    e.preventDefault();
                }
            });
        });
    </script>


 <?php include __DIR__ . '/../includes/footer.php'; ?>