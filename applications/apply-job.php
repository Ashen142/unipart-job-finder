<?php
// applications/apply-job.php
session_start();

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

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

// Initialize variables
$success_message = '';
$error_message = '';
$job = null;

// Get job_id from URL
if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: ../jobs/view-jobs.php");
    exit();
}

$job_id = mysqli_real_escape_string($conn, $_GET['job_id']);

// Fetch job details
$job_query = "SELECT j.*, e.company_name, u.name as employer_name 
              FROM jobs j 
              JOIN employers e ON j.employer_id = e.employer_id 
              JOIN users u ON e.user_id = u.user_id 
              WHERE j.job_id = '$job_id' AND j.status = 'active'";
$job_result = mysqli_query($conn, $job_query);

if (mysqli_num_rows($job_result) == 0) {
    header("Location: ../jobs/view-jobs.php");
    exit();
}

$job = mysqli_fetch_assoc($job_result);

// Get student_id from session
$user_id = $_SESSION['user_id'];
$student_query = "SELECT student_id FROM students WHERE user_id = '$user_id'";
$student_result = mysqli_query($conn, $student_query);
$student_data = mysqli_fetch_assoc($student_result);
$student_id = $student_data['student_id'];

// Check if already applied
$check_query = "SELECT * FROM applications WHERE job_id = '$job_id' AND student_id = '$student_id'";
$check_result = mysqli_query($conn, $check_query);
$already_applied = mysqli_num_rows($check_result) > 0;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already_applied) {
    $cover_letter = mysqli_real_escape_string($conn, trim($_POST['cover_letter']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $availability = mysqli_real_escape_string($conn, trim($_POST['availability']));
    
    // Validate required fields
    if (empty($phone)) {
        $error_message = "Phone number is required!";
    } else {
        // Handle resume upload (optional if student already has resume)
        $resume_file = NULL;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            $file_name = $_FILES['resume']['name'];
            $file_size = $_FILES['resume']['size'];
            $file_tmp = $_FILES['resume']['tmp_name'];
            $file_type = $_FILES['resume']['type'];
            
            // Validate file type and size
            if (!in_array($file_type, $allowed_types)) {
                $error_message = "Only PDF and DOC/DOCX files are allowed!";
            } elseif ($file_size > $max_size) {
                $error_message = "File size must be less than 5MB!";
            } else {
                // Generate unique filename
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $unique_name = uniqid('resume_', true) . '.' . $file_extension;
                $upload_path = '../uploads/resumes/' . $unique_name;
                
                // Create directory if it doesn't exist
                if (!file_exists('../uploads/resumes/')) {
                    mkdir('../uploads/resumes/', 0777, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $resume_file = $unique_name;
                } else {
                    $error_message = "Failed to upload resume!";
                }
            }
        }
        
        // Insert application into database if no errors
        if (empty($error_message)) {
            $status = 'pending'; // Default status
            $date_applied = date('Y-m-d H:i:s');
            
            $insert_query = "INSERT INTO applications (job_id, student_id, cover_letter, phone, availability, resume, status, date_applied) 
                            VALUES ('$job_id', '$student_id', '$cover_letter', '$phone', '$availability', " . 
                            ($resume_file ? "'$resume_file'" : "NULL") . ", '$status', '$date_applied')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Application submitted successfully!";
                $already_applied = true;
                
                // Log admin action
                $application_id = mysqli_insert_id($conn);
                $log_action = "Student ID $student_id applied for job ID $job_id";
                $log_query = "INSERT INTO admin_logs (action, date) VALUES ('$log_action', '$date_applied')";
                mysqli_query($conn, $log_query);
                
                // Redirect after 2 seconds
                header("refresh:2;url=../applications/student-applications.php");
            } else {
                $error_message = "Error submitting application: " . mysqli_error($conn);
            }
        }
    }
}

// Get student profile info for pre-filling
$profile_query = "SELECT s.*, u.name, u.email FROM students s 
                  JOIN users u ON s.user_id = u.user_id 
                  WHERE s.student_id = '$student_id'";
$profile_result = mysqli_query($conn, $profile_query);
$profile = mysqli_fetch_assoc($profile_result);
?>

 <!-- Main Container -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-paper-plane"></i> Apply for Job</h1>
            <p>Complete the application form below to apply for this position</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success_message; ?> Redirecting to My Applications...</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($already_applied && empty($success_message)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>You have already applied for this job. Check your application status in <a href="../applications/student-applications.php" style="color: #FFFFFF; text-decoration: underline;">My Applications</a></span>
            </div>
        <?php endif; ?>

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
                    <div class="job-detail-value"><?php echo htmlspecialchars($job['title']); ?></div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-building"></i>
                        <span>Company</span>
                    </div>
                    <div class="job-detail-value"><?php echo htmlspecialchars($job['company_name']); ?></div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-clock"></i>
                        <span>Job Type</span>
                    </div>
                    <div class="job-detail-value">
                        <span class="badge badge-primary"><?php echo htmlspecialchars($job['type']); ?></span>
                    </div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-tag"></i>
                        <span>Category</span>
                    </div>
                    <div class="job-detail-value"><?php echo htmlspecialchars($job['category']); ?></div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Pay Rate</span>
                    </div>
                    <div class="job-detail-value">
                        <span class="badge badge-success"><?php echo htmlspecialchars($job['pay']); ?></span>
                    </div>
                </div>

                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Location</span>
                    </div>
                    <div class="job-detail-value"><?php echo htmlspecialchars($job['location']); ?></div>
                </div>

                <?php if (!empty($job['deadline'])): ?>
                <div class="job-detail">
                    <div class="job-detail-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Application Deadline</span>
                    </div>
                    <div class="job-detail-value"><?php echo date('F d, Y', strtotime($job['deadline'])); ?></div>
                </div>
                <?php endif; ?>

                <div class="job-description">
                    <h3>Job Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                </div>

                <?php if (!empty($job['requirements'])): ?>
                <div class="job-description">
                    <h3>Requirements</h3>
                    <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Application Form -->
            <div class="form-card">
                <h2><i class="fas fa-file-alt"></i> Application Form</h2>

                <?php if ($already_applied): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i>
                        <span>Application Already Submitted</span>
                    </div>
                    <div class="btn-group">
                        <a href="../applications/student-applications.php" class="btn btn-success">
                            <i class="fas fa-list"></i> View My Applications
                        </a>
                        <a href="../jobs/view-jobs.php" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Browse More Jobs
                        </a>
                    </div>
                <?php else: ?>

                <form action="apply-job.php?job_id=<?php echo $job_id; ?>" method="POST" enctype="multipart/form-data">
                    
                    <!-- Name (Pre-filled, disabled) -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo htmlspecialchars($profile['name']); ?>" disabled>
                    </div>

                    <!-- Email (Pre-filled, disabled) -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($profile['email']); ?>" disabled>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               placeholder="e.g. +94 77 123 4567" required
                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        <small class="helper-text">We'll use this to contact you about your application</small>
                    </div>

                    <!-- Availability -->
                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <select id="availability" name="availability" class="form-control">
                            <option value="">Select your availability</option>
                            <option value="Immediate" <?php echo (isset($_POST['availability']) && $_POST['availability'] == 'Immediate') ? 'selected' : ''; ?>>Immediate</option>
                            <option value="Within 1 week" <?php echo (isset($_POST['availability']) && $_POST['availability'] == 'Within 1 week') ? 'selected' : ''; ?>>Within 1 week</option>
                            <option value="Within 2 weeks" <?php echo (isset($_POST['availability']) && $_POST['availability'] == 'Within 2 weeks') ? 'selected' : ''; ?>>Within 2 weeks</option>
                            <option value="Within 1 month" <?php echo (isset($_POST['availability']) && $_POST['availability'] == 'Within 1 month') ? 'selected' : ''; ?>>Within 1 month</option>
                        </select>
                    </div>

                    <!-- Cover Letter -->
                    <div class="form-group">
                        <label for="cover_letter">Cover Letter / Message to Employer</label>
                        <textarea id="cover_letter" name="cover_letter" class="form-control" 
                                  placeholder="Introduce yourself and explain why you're a good fit for this position..."><?php echo isset($_POST['cover_letter']) ? htmlspecialchars($_POST['cover_letter']) : ''; ?></textarea>
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
                        <?php if (!empty($profile['resume'])): ?>
                            <small class="helper-text">
                                <i class="fas fa-info-circle"></i> You have a resume in your profile. Upload a new one only if you want to use a different version.
                            </small>
                        <?php endif; ?>
                    </div>

                    <!-- Buttons -->
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                        <a href="../jobs/job-details.php?job_id=<?php echo $job_id; ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Job
                        </a>
                    </div>

                </form>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('resume')?.addEventListener('change', function(e) {
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

        // Form validation
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value.trim();
            
            if (!phone) {
                e.preventDefault();
                alert('Please enter your phone number!');
                return false;
            }
        });
    </script>
<?php include __DIR__ . '/../includes/footer.php'; ?>