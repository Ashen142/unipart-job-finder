<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Student Application | UniPart";
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

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>5</h3>
                    <p>Pending Applications</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon accepted">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Accepted Applications</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon rejected">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>2</h3>
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
            </select>
        </div>

        <!-- Applications Table -->
        <div class="applications-container">
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
                        <tr data-status="pending">
                            <td><strong>Software Developer Intern</strong></td>
                            <td>Tech Solutions Ltd</td>
                            <td>Part-time</td>
                            <td>IT & Software</td>
                            <td>Rs. 50,000.00</td>
                            <td>Colombo</td>
                            <td>Nov 15, 2025</td>
                            <td>
                                <span class="status-badge status-pending">
                                    pending
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button onclick="withdrawApplication(1)" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Withdraw
                                </button>
                            </td>
                        </tr>
                        <tr data-status="accepted">
                            <td><strong>Marketing Assistant</strong></td>
                            <td>Creative Agency Inc</td>
                            <td>Internship</td>
                            <td>Marketing</td>
                            <td>Rs. 35,000.00</td>
                            <td>Kandy</td>
                            <td>Nov 10, 2025</td>
                            <td>
                                <span class="status-badge status-accepted">
                                    accepted
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr data-status="rejected">
                            <td><strong>Data Analyst</strong></td>
                            <td>Analytics Corp</td>
                            <td>Full-time</td>
                            <td>Data Science</td>
                            <td>Rs. 75,000.00</td>
                            <td>Galle</td>
                            <td>Nov 05, 2025</td>
                            <td>
                                <span class="status-badge status-rejected">
                                    rejected
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
            if (confirm('Are you sure you want to withdraw this application?')) {
                alert('Application withdrawn successfully!');
                location.reload();
            }
        }
    </script>
    <?php include __DIR__ . '/../includes/footer.php'; ?>