<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "admin-dashboard to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/jobs.css'];
$body_class = 'dashboard-page';
$page_type = 'admin';

// Include header
include __DIR__ . '/../includes/header.php';


// Check if user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit();
}


// Initialize variables
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $job_title = mysqli_real_escape_string($conn, trim($_POST['job_title']));
    $job_type = mysqli_real_escape_string($conn, trim($_POST['job_type']));
    $job_category = mysqli_real_escape_string($conn, trim($_POST['job_category']));
    $job_pay = mysqli_real_escape_string($conn, trim($_POST['job_pay']));
    $job_location = mysqli_real_escape_string($conn, trim($_POST['job_location']));
    $job_description = mysqli_real_escape_string($conn, trim($_POST['job_description']));
    $job_requirements = mysqli_real_escape_string($conn, trim($_POST['job_requirements']));
    $deadline = !empty($_POST['deadline']) ? mysqli_real_escape_string($conn, $_POST['deadline']) : NULL;
    
    // Get employer_id from session
    $user_id = $_SESSION['user_id'];
    
    // Get employer_id from employers table
    $employer_query = "SELECT employer_id FROM employers WHERE user_id = '$user_id'";
    $employer_result = mysqli_query($conn, $employer_query);
    $employer_data = mysqli_fetch_assoc($employer_result);
    $employer_id = $employer_data['employer_id'];
    
    // Validate required fields
    if (empty($job_title) || empty($job_type) || empty($job_category) || empty($job_pay) || empty($job_location) || empty($job_description)) {
        $error_message = "Please fill in all required fields!";
    } else {
        // Handle image upload
        $job_image = NULL;
        if (isset($_FILES['job_image']) && $_FILES['job_image']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            $file_name = $_FILES['job_image']['name'];
            $file_size = $_FILES['job_image']['size'];
            $file_tmp = $_FILES['job_image']['tmp_name'];
            $file_type = $_FILES['job_image']['type'];
            
            // Validate file type and size
            if (!in_array($file_type, $allowed_types)) {
                $error_message = "Only JPG, JPEG, and PNG files are allowed!";
            } elseif ($file_size > $max_size) {
                $error_message = "File size must be less than 5MB!";
            } else {
                // Generate unique filename
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $unique_name = uniqid('job_', true) . '.' . $file_extension;
                $upload_path = '../uploads/job-images/' . $unique_name;
                
                // Create directory if it doesn't exist
                if (!file_exists('../uploads/job-images/')) {
                    mkdir('../uploads/job-images/', 0777, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $job_image = $unique_name;
                } else {
                    $error_message = "Failed to upload image!";
                }
            }
        }
        
        // Insert job into database if no errors
        if (empty($error_message)) {
            $status = 'active'; // Default status
            $created_at = date('Y-m-d H:i:s');
            
            $insert_query = "INSERT INTO jobs (employer_id, title, type, category, pay, location, description, requirements, deadline, image, status, created_at) 
                            VALUES ('$employer_id', '$job_title', '$job_type', '$job_category', '$job_pay', '$job_location', '$job_description', '$job_requirements', " . 
                            ($deadline ? "'$deadline'" : "NULL") . ", " . 
                            ($job_image ? "'$job_image'" : "NULL") . ", '$status', '$created_at')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Job posted successfully!";
                // Log admin action
                $job_id = mysqli_insert_id($conn);
                $log_action = "Employer ID $employer_id posted job ID $job_id: $job_title";
                $log_query = "INSERT INTO admin_logs (action, date) VALUES ('$log_action', '$created_at')";
                mysqli_query($conn, $log_query);
                
                // Redirect after 2 seconds
                header("refresh:2;url=../dashboard/employer-dashboard.php");
            } else {
                $error_message = "Error posting job: " . mysqli_error($conn);
            }
        }
    }
}
?>


    <!-- Main Container -->
    <div class="container1">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-plus-circle"></i> Post New Job</h1>
            <p>Fill in the details below to create a new job posting for students</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success_message; ?> Redirecting...</span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="form-card">
            <form action="add-job.php" method="POST" enctype="multipart/form-data">
                
                <!-- Job Title -->
                <div class="form-group">
                    <label for="job_title">Job Title <span class="required">*</span></label>
                    <input type="text" id="job_title" name="job_title" class="form-control" 
                           placeholder="e.g. Social Media Manager" required 
                           value="<?php echo isset($_POST['job_title']) ? htmlspecialchars($_POST['job_title']) : ''; ?>">
                </div>

                <!-- Job Type and Category Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_type">Job Type <span class="required">*</span></label>
                        <select id="job_type" name="job_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="Part-Time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'Part-Time') ? 'selected' : ''; ?>>Part-Time</option>
                            <option value="Full-Time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'Full-Time') ? 'selected' : ''; ?>>Full-Time</option>
                            <option value="Freelance" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'Freelance') ? 'selected' : ''; ?>>Freelance</option>
                            <option value="Remote" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'Remote') ? 'selected' : ''; ?>>Remote</option>
                            <option value="Internship" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="job_category">Job Category <span class="required">*</span></label>
                        <select id="job_category" name="job_category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="IT & Software" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'IT & Software') ? 'selected' : ''; ?>>IT & Software</option>
                            <option value="Marketing" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                            <option value="Design" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Design') ? 'selected' : ''; ?>>Design</option>
                            <option value="Sales" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                            <option value="Customer Service" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Customer Service') ? 'selected' : ''; ?>>Customer Service</option>
                            <option value="Writing & Content" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Writing & Content') ? 'selected' : ''; ?>>Writing & Content</option>
                            <option value="Teaching & Tutoring" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Teaching & Tutoring') ? 'selected' : ''; ?>>Teaching & Tutoring</option>
                            <option value="Other" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Pay and Location Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_pay">Pay Rate <span class="required">*</span></label>
                        <input type="text" id="job_pay" name="job_pay" class="form-control" 
                               placeholder="e.g. $15/hour or $500/month" required
                               value="<?php echo isset($_POST['job_pay']) ? htmlspecialchars($_POST['job_pay']) : ''; ?>">
                        <small class="helper-text">Specify hourly, monthly, or project-based pay</small>
                    </div>

                    <div class="form-group">
                        <label for="job_location">Location <span class="required">*</span></label>
                        <input type="text" id="job_location" name="job_location" class="form-control" 
                               placeholder="e.g. Remote or City Name" required
                               value="<?php echo isset($_POST['job_location']) ? htmlspecialchars($_POST['job_location']) : ''; ?>">
                    </div>
                </div>

                <!-- Job Description -->
                <div class="form-group">
                    <label for="job_description">Job Description <span class="required">*</span></label>
                    <textarea id="job_description" name="job_description" class="form-control" 
                              placeholder="Describe the job responsibilities, requirements, and qualifications..." required><?php echo isset($_POST['job_description']) ? htmlspecialchars($_POST['job_description']) : ''; ?></textarea>
                    <small class="helper-text">Provide detailed information about the role</small>
                </div>

                <!-- Requirements -->
                <div class="form-group">
                    <label for="job_requirements">Requirements</label>
                    <textarea id="job_requirements" name="job_requirements" class="form-control" 
                              placeholder="List any specific skills, experience, or qualifications needed..."><?php echo isset($_POST['job_requirements']) ? htmlspecialchars($_POST['job_requirements']) : ''; ?></textarea>
                </div>

                <!-- Application Deadline -->
                <div class="form-group">
                    <label for="deadline">Application Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="form-control"
                           value="<?php echo isset($_POST['deadline']) ? htmlspecialchars($_POST['deadline']) : ''; ?>"
                           min="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Job Image Upload -->
                <div class="form-group">
                    <label for="job_image">Job Image (Optional)</label>
                    <div class="file-upload">
                        <input type="file" id="job_image" name="job_image" accept="image/*">
                        <label for="job_image" class="file-upload-label">
                            <div style="text-align: center;">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">
                                    <strong>Click to upload</strong> or drag and drop<br>
                                    PNG, JPG or JPEG (Max 5MB)
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i> Post Job
                    </button>
                    <a href="../dashboard/employer-dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-times-circle"></i> Cancel
                    </a>
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

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = document.getElementById('job_title').value.trim();
            const type = document.getElementById('job_type').value;
            const category = document.getElementById('job_category').value;
            const pay = document.getElementById('job_pay').value.trim();
            const location = document.getElementById('job_location').value.trim();
            const description = document.getElementById('job_description').value.trim();
            
            if (!title || !type || !category || !pay || !location || !description) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
        });
    </script>

<?php include __DIR__ . '/../includes/footer.php'; ?>