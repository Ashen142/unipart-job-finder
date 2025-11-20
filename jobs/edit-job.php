<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "admin-dashboard to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/jobs.css'];
$body_class = 'dashboard-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<!-- Main Container -->
    <div class="container1">

        <!-- Page Header -->
        <div class="page-header1">
            <h1>
                <i class="fas fa-edit"></i> Edit Job Posting
                <span class="job-status-badge1 status-active">Active</span>
            </h1>
            <p>Update job details and manage your posting</p>
        </div>

        <!-- Job Statistics -->
        <div class="stats-card">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Total Applicants</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">3</div>
                    <div class="stat-label">Accepted</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">5</div>
                    <div class="stat-label">Days Posted</div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <!-- <div class="alert alert-info" style="display: none;" id="successAlert">
            <i class="fas fa-check-circle"></i>
            <span>Job updated successfully!</span>
        </div>

        <div class="alert alert-error" style="display: none;" id="errorAlert">
            <i class="fas fa-exclamation-circle"></i>
            <span id="errorMessage">Error updating job. Please try again.</span>
        </div> -->

        <!-- Form Card -->
        <div class="form-card">
            <form action="edit-job.php" method="POST" enctype="multipart/form-data" id="editJobForm">
                <input type="hidden" name="job_id" value="1">
                
                <!-- Job Title -->
                <div class="form-group">
                    <label for="job_title">Job Title <span class="required">*</span></label>
                    <input type="text" id="job_title" name="job_title" class="form-control" 
                           placeholder="e.g. Social Media Manager" required 
                           value="Web Developer - Part Time">
                </div>

                <!-- Job Type and Category Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_type">Job Type <span class="required">*</span></label>
                        <select id="job_type" name="job_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="Part-Time" selected>Part-Time</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Remote">Remote</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="job_category">Job Category <span class="required">*</span></label>
                        <select id="job_category" name="job_category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="IT & Software" selected>IT & Software</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Design">Design</option>
                            <option value="Sales">Sales</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="Writing & Content">Writing & Content</option>
                            <option value="Teaching & Tutoring">Teaching & Tutoring</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Pay and Location Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_pay">Pay Rate <span class="required">*</span></label>
                        <input type="text" id="job_pay" name="job_pay" class="form-control" 
                               placeholder="e.g. $15/hour or $500/month" required
                               value="LKR 50,000 - 75,000 /month">
                        <small class="helper-text">Specify hourly, monthly, or project-based pay</small>
                    </div>

                    <div class="form-group">
                        <label for="job_location">Location <span class="required">*</span></label>
                        <input type="text" id="job_location" name="job_location" class="form-control" 
                               placeholder="e.g. Remote or City Name" required
                               value="Colombo, Sri Lanka">
                    </div>
                </div>

                <!-- Job Description -->
                <div class="form-group">
                    <label for="job_description">Job Description <span class="required">*</span></label>
                    <textarea id="job_description" name="job_description" class="form-control" 
                              placeholder="Describe the job responsibilities, requirements, and qualifications..." required>We are looking for a talented and motivated Web Developer to join our team on a part-time basis. This position is perfect for university students who want to gain real-world experience while continuing their studies. You will work on exciting projects, developing and maintaining web applications using modern technologies.</textarea>
                    <small class="helper-text">Provide detailed information about the role</small>
                </div>

                <!-- Requirements -->
                <div class="form-group">
                    <label for="job_requirements">Requirements</label>
                    <textarea id="job_requirements" name="job_requirements" class="form-control" 
                              placeholder="List any specific skills, experience, or qualifications needed...">Strong knowledge of HTML5, CSS3, and JavaScript. Experience with PHP and MySQL databases. Familiarity with responsive design principles.</textarea>
                </div>

                <!-- Status and Deadline Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_status">Job Status <span class="required">*</span></label>
                        <select id="job_status" name="job_status" class="form-control" required>
                            <option value="active" selected>Active</option>
                            <option value="closed">Closed</option>
                            <option value="pending">Pending Review</option>
                        </select>
                        <small class="helper-text">Change status to close applications</small>
                    </div>

                    <div class="form-group">
                        <label for="deadline">Application Deadline</label>
                        <input type="date" id="deadline" name="deadline" class="form-control"
                               value="2025-12-17"
                               min="2025-11-20">
                    </div>
                </div>

                <!-- Current Image -->
                <div class="form-group">
                    <label>Current Job Image</label>
                    <div class="current-image" id="currentImageDiv">
                        <img src="../assets/images/placeholder-job.jpg" alt="Current job image" id="currentImage">
                        <div class="current-image-text">Current image uploaded on Nov 17, 2025</div>
                        <button type="button" class="remove-image-btn" id="removeImageBtn">
                            <i class="fas fa-trash"></i> Remove Image
                        </button>
                    </div>
                </div>

                <!-- Job Image Upload -->
                <div class="form-group">
                    <label for="job_image">Update Job Image (Optional)</label>
                    <div class="file-upload">
                        <input type="file" id="job_image" name="job_image" accept="image/*">
                        <label for="job_image" class="file-upload-label">
                            <div style="text-align: center;">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">
                                    <strong>Click to upload new image</strong> or drag and drop<br>
                                    PNG, JPG or JPEG (Max 5MB)
                                </div>
                            </div>
                        </label>
                    </div>
                    <small class="helper-text">Leave empty to keep current image</small>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="../applications/employer-applications.php?job_id=1" class="btn btn-secondary">
                        <i class="fas fa-users"></i> View Applicants
                    </a>
                    <button type="button" class="btn btn-warning" onclick="toggleJobStatus()">
                        <i class="fas fa-pause-circle"></i> Close Applications
                    </button>
                    <button type="button" class="btn btn-danger" id="deleteJobBtn">
                        <i class="fas fa-trash-alt"></i> Delete Job
                    </button>
                </div>

            </form>
        </div>
    </div>

   

    <script>
        // File upload preview
        document.getElementById('job_image').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('.file-upload-label');
                label.innerHTML = `
                    <div style="text-align: center;">
                        <div class="file-upload-icon">
                            <i class="fas fa-check-circle" style="color: #28A745;"></i>
                        </div>
                        <div class="file-upload-text">
                            <strong>${fileName}</strong><br>
                            <small>Click to change file</small>
                        </div>
                    </div>
                `;
            }
        });

        // Remove current image
        document.getElementById('removeImageBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the current image?')) {
                document.getElementById('currentImageDiv').style.display = 'none';
                // In actual implementation, set a hidden field to mark image for deletion
            }
        });

        // Toggle job status
        function toggleJobStatus() {
            const statusSelect = document.getElementById('job_status');
            if (statusSelect.value === 'active') {
                if (confirm('Close applications for this job? No new applications will be accepted.')) {
                    statusSelect.value = 'closed';
                    document.querySelector('.status-active').classList.remove('status-active');
                    document.querySelector('.job-status-badge').classList.add('status-closed');
                    document.querySelector('.job-status-badge').textContent = 'Closed';
                }
            } else {
                if (confirm('Reopen applications for this job?')) {
                    statusSelect.value = 'active';
                    document.querySelector('.status-closed').classList.remove('status-closed');
                    document.querySelector('.job-status-badge').classList.add('status-active');
                    document.querySelector('.job-status-badge').textContent = 'Active';
                }
            }
        }

        // Delete job modal
        const deleteModal = document.getElementById('deleteModal');
        const deleteBtn = document.getElementById('deleteJobBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        deleteBtn.addEventListener('click', function() {
            deleteModal.classList.add('active');
        });

        cancelBtn.addEventListener('click', function() {
            deleteModal.classList.remove('active');
        });

        confirmBtn.addEventListener('click', function() {
            // In actual implementation, submit delete request
            alert('Job will be deleted. Redirecting to dashboard...');
            // window.location.href = '../dashboard/employer-dashboard.php';
        });

        // Close modal on outside click
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });

        // Form validation
        document.getElementById('editJobForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = document.getElementById('job_title').value.trim();
            const type = document.getElementById('job_type').value;
            const category = document.getElementById('job_category').value;
            const pay = document.getElementById('job_pay').value.trim();
            const location = document.getElementById('job_location').value.trim();
            const description = document.getElementById('job_description').value.trim();
            
            if (!title || !type || !category || !pay || !location || !description) {
                const errorAlert = document.getElementById('errorAlert');
                document.getElementById('errorMessage').textContent = 'Please fill in all required fields!';
                errorAlert.style.display = 'flex';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return false;
            }

            // Show success message (in actual implementation, submit form)
            const successAlert = document.getElementById('successAlert');
            successAlert.style.display = 'flex';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // In actual implementation:
            // this.submit();
        });

        // Status select change
        document.getElementById('job_status').addEventListener('change', function() {
            const badge = document.querySelector('.job-status-badge');
            badge.className = 'job-status-badge';
            
            if (this.value === 'active') {
                badge.classList.add('status-active');
                badge.textContent = 'Active';
            } else if (this.value === 'closed') {
                badge.classList.add('status-closed');
                badge.textContent = 'Closed';
            } else if (this.value === 'pending') {
                badge.classList.add('status-pending');
                badge.textContent = 'Pending Review';
            }
        });
    </script>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

