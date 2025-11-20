<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Student Profile | UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/applications.css'];
$body_class = 'student-profile-page';
$page_type = 'student';
include __DIR__ . '/../includes/header.php';


?>

<!-- Main Container -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-paper-plane"></i> Apply for Job</h1>
            <p>Complete the application form below to apply for this position</p>
        </div>

        <!-- Success/Error Messages -->
        <!-- <div id="successAlert" class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>Application submitted successfully! Redirecting to My Applications...</span>
        </div>

        <div id="errorAlert" class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span id="errorMessage"></span>
        </div> -->

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Job Information -->
            <div class="job-info-card">
                <h2><i class="fas fa-briefcase"></i> Job Details</h2>
                
                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-heading"></i>
                        <span>Job Title</span>
                    </div>
                    <div class="job-detail-value">Software Developer Intern</div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-building"></i>
                        <span>Company</span>
                    </div>
                    <div class="job-detail-value">Tech Solutions Ltd.</div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-clock"></i>
                        <span>Job Type</span>
                    </div>
                    <div class="job-detail-value">
                        <span class="badge badge-primary">Part-time</span>
                    </div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-tag"></i>
                        <span>Category</span>
                    </div>
                    <div class="job-detail-value">Information Technology</div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Pay Rate</span>
                    </div>
                    <div class="job-detail-value">
                        <span class="badge badge-success">Rs. 25,000/month</span>
                    </div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Location</span>
                    </div>
                    <div class="job-detail-value">Colombo, Sri Lanka</div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Application Deadline</span>
                    </div>
                    <div class="job-detail-value">December 31, 2024</div>
                </div>

                <div class="job-description">
                    <h3>Job Description</h3>
                    <p>We are looking for a motivated Software Developer Intern to join our development team. You will work on real-world projects, collaborate with experienced developers, and gain hands-on experience in modern web technologies. This is an excellent opportunity for students looking to build their portfolio and gain industry experience.</p>
                </div>

                <div class="job-description">
                    <h3>Requirements</h3>
                    <p>• Currently enrolled in a Computer Science or related program<br>
                    • Basic knowledge of HTML, CSS, and JavaScript<br>
                    • Familiarity with at least one programming language (Python, Java, or PHP)<br>
                    • Good communication and teamwork skills<br>
                    • Ability to work 20-25 hours per week</p>
                </div>
            </div>

            <!-- Application Form -->
            <div class="form-card">
                <h2><i class="fas fa-file-alt"></i> Application Form</h2>

                <form id="applicationForm">
                    
                    <!-- Name (Pre-filled, disabled) -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="John Doe" disabled>
                    </div>

                    <!-- Email (Pre-filled, disabled) -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="john.doe@student.edu" disabled>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               placeholder="e.g. +94 77 123 4567" required>
                        <small class="helper-text">We'll use this to contact you about your application</small>
                    </div>

                    <!-- Availability -->
                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <select id="availability" name="availability" class="form-control">
                            <option value="">Select your availability</option>
                            <option value="Immediate">Immediate</option>
                            <option value="Within 1 week">Within 1 week</option>
                            <option value="Within 2 weeks">Within 2 weeks</option>
                            <option value="Within 1 month">Within 1 month</option>
                        </select>
                    </div>

                    <!-- Cover Letter -->
                    <div class="form-group">
                        <label for="cover_letter">Cover Letter / Message to Employer</label>
                        <textarea id="cover_letter" name="cover_letter" class="form-control" 
                                  placeholder="Introduce yourself and explain why you're a good fit for this position..."></textarea>
                        <small class="helper-text">Optional but recommended - helps you stand out</small>
                    </div>

                    <!-- Resume Upload -->
                    <div class="form-group">
                        <label for="resume">Upload Resume (Optional)</label>
                        <div class="file-upload">
                            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                            <label for="resume" class="file-upload-label">
                                <div style="text-align: center;">
                                    <div class="file-upload-icon">
                                        <i class="fas fa-file-upload"></i>
                                    </div>
                                    <div class="file-upload-text">
                                        <strong>Click to upload resume</strong><br>
                                        PDF, DOC or DOCX (Max 5MB)
                                    </div>
                                </div>
                            </label>
                        </div>
                        <small class="helper-text">
                            <i class="fas fa-info-circle"></i> You have a resume in your profile. Upload a new one only if you want to use a different version.
                        </small>
                    </div>

                    <!-- Buttons -->
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                        <a href="#" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Job
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('resume').addEventListener('change', function(e) {
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

        // Form validation and submission
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const phone = document.getElementById('phone').value.trim();
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');
            const successAlert = document.getElementById('successAlert');
            
            // Hide any previous alerts
            errorAlert.classList.remove('show');
            successAlert.classList.remove('show');
            
            if (!phone) {
                errorMessage.textContent = 'Please enter your phone number!';
                errorAlert.classList.add('show');
                return false;
            }
            
            // Validate file size if uploaded
            const resumeFile = document.getElementById('resume').files[0];
            if (resumeFile) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (resumeFile.size > maxSize) {
                    errorMessage.textContent = 'File size must be less than 5MB!';
                    errorAlert.classList.add('show');
                    return false;
                }
            }
            
            // Show success message
            successAlert.classList.add('show');
            
            // Disable form
            const formInputs = this.querySelectorAll('input, textarea, select, button');
            formInputs.forEach(input => input.disabled = true);
            
            // Simulate redirect after 2 seconds
            setTimeout(() => {
                alert('Application submitted successfully! In a real application, you would be redirected to My Applications page.');
            }, 2000);
        });
    </script>
<?php include __DIR__ . '/../includes/footer.php'; ?>