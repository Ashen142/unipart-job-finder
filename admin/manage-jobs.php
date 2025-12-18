<?php
// ===============================
// UniPart - manage jobs
// ===============================

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
// -------------------------------
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
            <h1><i class="fas fa-briefcase"></i> Manage All Jobs</h1>
            <p>Review, approve, and manage all job postings on the platform</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-value">48</div>
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
                        <div class="stat-value">32</div>
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
                        <div class="stat-value">8</div>
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
                        <div class="stat-value">8</div>
                        <div class="stat-label">Closed Jobs</div>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="searchJob">Search Jobs</label>
                    <input type="text" id="searchJob" class="filter-control search-input" placeholder="Search by job title or company...">
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" class="filter-control">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterType">Job Type</label>
                    <select id="filterType" class="filter-control">
                        <option value="">All Types</option>
                        <option value="part-time">Part-Time</option>
                        <option value="full-time">Full-Time</option>
                        <option value="remote">Remote</option>
                        <option value="internship">Internship</option>
                        <option value="freelance">Freelance</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterCategory">Category</label>
                    <select id="filterCategory" class="filter-control">
                        <option value="">All Categories</option>
                        <option value="it">IT & Software</option>
                        <option value="marketing">Marketing</option>
                        <option value="design">Design</option>
                        <option value="sales">Sales</option>
                        <option value="other">Other</option>
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
                    <tr>
                        <td>#001</td>
                        <td>Web Developer - Part Time</td>
                        <td>Tech Innovations Pvt Ltd</td>
                        <td><span class="type-badge type-part-time">Part-Time</span></td>
                        <td>IT & Software</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>12</td>
                        <td>Nov 17, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewJob(1)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-approve" onclick="approveJob(1)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectJob(1)" title="Reject/Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteJob(1)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>Social Media Manager</td>
                        <td>Digital Marketing Hub</td>
                        <td><span class="type-badge type-remote">Remote</span></td>
                        <td>Marketing</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>5</td>
                        <td>Nov 18, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewJob(2)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-approve" onclick="approveJob(2)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectJob(2)" title="Reject/Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteJob(2)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#003</td>
                        <td>Graphic Designer</td>
                        <td>Creative Studio LK</td>
                        <td><span class="type-badge type-freelance">Freelance</span></td>
                        <td>Design</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>18</td>
                        <td>Nov 16, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewJob(3)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-approve" onclick="approveJob(3)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectJob(3)" title="Reject/Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteJob(3)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#004</td>
                        <td>Data Entry Clerk</td>
                        <td>Business Solutions Inc</td>
                        <td><span class="type-badge type-part-time">Part-Time</span></td>
                        <td>Other</td>
                        <td><span class="status-badge status-closed">Closed</span></td>
                        <td>25</td>
                        <td>Nov 10, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewJob(4)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-approve" onclick="approveJob(4)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectJob(4)" title="Reject/Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteJob(4)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#005</td>
                        <td>Content Writer</td>
                        <td>Content Creation Agency</td>
                        <td><span class="type-badge type-remote">Remote</span></td>
                        <td>Writing & Content</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>8</td>
                        <td>Nov 19, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewJob(5)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-approve" onclick="approveJob(5)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectJob(5)" title="Reject/Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteJob(5)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#006</td>
                        <td>Software Engineering Intern</td>
                        <td>Tech Solutions Ltd</td>
                        <td><span class="type-badge type-internship">Internship</span></td>
                        <td>IT & Software</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>15</td>
                        <td>Nov 19, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewJob(6)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-approve" onclick="approveJob(6)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectJob(6)" title="Reject/Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteJob(6)" title="Delete">
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
                    Showing 1 to 6 of 48 jobs
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
            showAlert('success', 'Job approved successfully!');
            closeModal('approveJobModal');
            // In actual implementation: send AJAX request to approve job
            // Then update the table row status
        }

        // Confirm reject
        function confirmReject() {
            const reason = document.getElementById('rejectReason').value;
            showAlert('success', 'Job has been rejected and closed.');
            closeModal('rejectJobModal');
            // In actual implementation: send AJAX request with reason
        }

        // Confirm delete
        function confirmDelete() {
            showAlert('success', 'Job deleted permanently.');
            closeModal('deleteJobModal');
            // In actual implementation: send AJAX request to delete job
            // Then remove the table row
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
            const search = document.getElementById('searchJob').value.toLowerCase();
            const status = document.getElementById('filterStatus').value;
            const type = document.getElementById('filterType').value;
            const category = document.getElementById('filterCategory').value;
            
            // In actual implementation: filter table rows or make AJAX request
            showAlert('success', 'Filters applied successfully!');
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchJob').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterCategory').value = '';
            
            // In actual implementation: reload all jobs
            showAlert('success', 'Filters reset.');
        }

        // Export data
        function exportData() {
            // In actual implementation: generate and download CSV file
            showAlert('success', 'Exporting data to CSV...');
        }

        // Search functionality
        document.getElementById('searchJob').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#jobsTableBody tr');
            
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