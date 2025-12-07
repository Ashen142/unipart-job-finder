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

// Get job_id from URL
if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: ../jobs/view-jobs.php");
    exit();
}

$job_id = intval($_GET['job_id']);
$user_id = $_SESSION['user_id'];

// Get student_id from students table and user info from users table
$student_query = "SELECT s.student_id, u.name, u.email 
                  FROM students s 
                  JOIN users u ON s.user_id = u.user_id 
                  WHERE s.user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows === 0) {
    die("Student profile not found!");
}

$student_data = $student_result->fetch_assoc();
$student_id = $student_data['student_id'];
$student_name = $student_data['name'];
$student_email = $student_data['email'];
$student_phone = ''; // Phone will be entered in the form

// Check if already applied
$check_query = "SELECT application_id FROM applications WHERE student_id = ? AND job_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $student_id, $job_id);
$stmt->execute();
$check_result = $stmt->get_result();

if ($check_result->num_rows > 0) {
    $_SESSION['error_message'] = "You have already applied for this job!";
    header("Location: ../jobs/job-details.php?job_id=" . $job_id);
    exit();
}

// Fetch job details
$job_query = "SELECT j.*, e.company_name 
              FROM jobs j 
              JOIN employers e ON j.employer_id = e.employer_id 
              WHERE j.job_id = ? AND j.status = 'active'";
$stmt = $conn->prepare($job_query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job_result = $stmt->get_result();

if ($job_result->num_rows === 0) {
    die("Job not found or no longer available!");
}

$job = $job_result->fetch_assoc();

// Initialize variables
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $availability = mysqli_real_escape_string($conn, trim($_POST['availability']));
    $cover_letter = mysqli_real_escape_string($conn, trim($_POST['cover_letter']));
    
    // Validate required fields
    if (empty($phone)) {
        $error_message = "Phone number is required!";
    } else {
        // Handle resume upload
        $resume_path = NULL;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            $file_name = $_FILES['resume']['name'];
            $file_size = $_FILES['resume']['size'];
            $file_tmp = $_FILES['resume']['tmp_name'];
            $file_type = $_FILES['resume']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                $error_message = "Only PDF, DOC, and DOCX files are allowed!";
            } elseif ($file_size > $max_size) {
                $error_message = "File size must be less than 5MB!";
            } else {
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $unique_name = uniqid('resume_', true) . '.' . $file_extension;
                $upload_path = '../uploads/resumes/' . $unique_name;
                
                if (!file_exists('../uploads/resumes/')) {
                    mkdir('../uploads/resumes/', 0777, true);
                }
                
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $resume_path = $unique_name;
                } else {
                    $error_message = "Failed to upload resume!";
                }
            }
        }
        
        // Insert application if no errors
        if (empty($error_message)) {
            $status = 'Pending';
            $date_applied = date('Y-m-d H:i:s');
            
            $insert_query = "INSERT INTO applications (student_id, job_id, phone, availability, cover_letter, resume, status, date_applied) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iissssss", $student_id, $job_id, $phone, $availability, $cover_letter, $resume_path, $status, $date_applied);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Application submitted successfully!";
                header("Location: ../applications/my-applications.php");
                exit();
            } else {
                $error_message = "Error submitting application: " . $conn->error;
            }
        }
    }
}

// Page settings
$page_title = "Apply for Job | UniPart";
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
    <?php if (!empty($success_message)): ?>
        <div id="successAlert" class="alert alert-success show">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $success_message; ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div id="errorAlert" class="alert alert-error show">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $error_message; ?></span>
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

            <form id="applicationForm" method="POST" enctype="multipart/form-data">
                
                <!-- Name (Pre-filled, disabled) -->
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" 
                           value="<?php echo htmlspecialchars($student_name); ?>" disabled>
                </div>

                <!-- Email (Pre-filled, disabled) -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($student_email); ?>" disabled>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           placeholder="e.g. +94 77 123 4567" 
                           value="<?php echo htmlspecialchars($student_phone); ?>" required>
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
                        <i class="fas fa-info-circle"></i> Upload your latest resume for this application.
                    </small>
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

// Form validation
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    const phone = document.getElementById('phone').value.trim();
    
    if (!phone) {
        e.preventDefault();
        alert('Please enter your phone number!');
        return false;
    }
    
    // Validate file size if uploaded
    const resumeFile = document.getElementById('resume').files[0];
    if (resumeFile) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (resumeFile.size > maxSize) {
            e.preventDefault();
            alert('File size must be less than 5MB!');
            return false;
        }
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>