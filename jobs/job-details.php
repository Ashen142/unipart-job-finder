<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Get job_id from URL
if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: view-jobs.php");
    exit();
}

$job_id = intval($_GET['job_id']);

// Fetch job details with company information
$job_query = "SELECT j.*, e.company_name, e.industry, e.size, e.founded_year,
              e.website, e.description as company_description, e.hr_email, e.office_address, e.verified
              FROM jobs j 
              JOIN employers e ON j.employer_id = e.employer_id 
              WHERE j.job_id = ?";
$stmt = $conn->prepare($job_query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job_result = $stmt->get_result();

if ($job_result->num_rows === 0) {
    die("Job not found!");
}

$job = $job_result->fetch_assoc();

// Count total applicants for this job
$applicants_query = "SELECT COUNT(*) as total FROM applications WHERE job_id = ?";
$stmt = $conn->prepare($applicants_query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$applicants_result = $stmt->get_result();
$total_applicants = $applicants_result->fetch_assoc()['total'];

// Check if current user has already applied (if logged in as student)
$has_applied = false;
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') {
    $user_id = $_SESSION['user_id'];
    $student_query = "SELECT student_id FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $student_result = $stmt->get_result();
    
    if ($student_result->num_rows > 0) {
        $student_id = $student_result->fetch_assoc()['student_id'];
        
        $check_query = "SELECT application_id FROM applications WHERE student_id = ? AND job_id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ii", $student_id, $job_id);
        $stmt->execute();
        $check_result = $stmt->get_result();
        $has_applied = ($check_result->num_rows > 0);
    }
}

// Fetch similar jobs (same category, excluding current job)
$similar_query = "SELECT job_id, title FROM jobs 
                  WHERE category = ? AND job_id != ? AND status = 'active' 
                  LIMIT 4";
$stmt = $conn->prepare($similar_query);
$stmt->bind_param("si", $job['category'], $job_id);
$stmt->execute();
$similar_result = $stmt->get_result();

// Time ago function
function timeAgo($dateTime) {
    $time = strtotime($dateTime);
    $diff = time() - $time;
    
    if ($diff < 60) return "just now";
    if ($diff < 3600) return floor($diff/60) . " minutes ago";
    if ($diff < 86400) return floor($diff/3600) . " hours ago";
    if ($diff < 2592000) return floor($diff/86400) . " days ago";
    return date('M d, Y', $time);
}

// Page settings
$page_title = htmlspecialchars($job['title']) . " | UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/jobs.css'];
$body_class = 'dashboard-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<!-- Main Container -->
<div class="container1">
    

    <!-- Alert for already applied -->
    <?php if ($has_applied): ?>
        <div class="alert alert-info">
            <i class="fas fa-check-circle"></i>
            You have already applied for this job. Check your <a href="../applications/my-applications.php" style="color: #FFFFFF; text-decoration: underline;">applications</a> for status updates.
        </div>
    <?php endif; ?>

    <!-- Alert for logged out users -->
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Please <a href="../auth/login.php" style="color: #FFFFFF; text-decoration: underline;">login</a> to apply for this job.
        </div>
    <?php endif; ?>

    <!-- Job Layout -->
    <div class="job-layout1">
        <!-- Main Content -->
        <div class="job-main1">
            <!-- Job Header -->
            <div class="job-header1">
                <h1 class="job-title1"><?php echo htmlspecialchars($job['title']); ?></h1>
                
                <div class="job-meta1">
                    <span class="job-type-badge1 badge-part-time">
                        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($job['type']); ?>
                    </span>
                    <div class="job-meta1-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($job['location']); ?></span>
                    </div>
                    <div class="job-meta1-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span><?php echo htmlspecialchars($job['pay']); ?></span>
                    </div>
                    <div class="job-meta1-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Posted <?php echo timeAgo($job['created_at']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Job Description -->
            <div class="job-section1">
                <h2><i class="fas fa-file-alt"></i> Job Description</h2>
                <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            </div>

            <!-- Requirements -->
            <?php if (!empty($job['requirements'])): ?>
            <div class="job-section1">
                <h3><i class="fas fa-check-circle"></i> Requirements</h3>
                <div><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></div>
            </div>
            <?php endif; ?>

            <!-- Share Job -->
            <div class="job-section1">
                <h3><i class="fas fa-share-alt"></i> Share This Job</h3>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                       class="share-btn share-facebook" title="Share on Facebook" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($job['title']); ?>" 
                       class="share-btn share-twitter" title="Share on Twitter" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&title=<?php echo urlencode($job['title']); ?>" 
                       class="share-btn share-linkedin" title="Share on LinkedIn" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode($job['title']); ?>&body=Check out this job: <?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                       class="share-btn share-email" title="Share via Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="job-sidebar">
            <!-- Apply Button -->
            <div class="sidebar-card">
                <div class="action-buttons">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student'): ?>
                        <?php if ($has_applied): ?>
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-check-circle"></i> Already Applied
                            </button>
                        <?php else: ?>
                            <a href="../applications/apply-job.php?job_id=<?php echo $job_id; ?>" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Apply Now
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="../auth/login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login to Apply
                        </a>
                    <?php endif; ?>
                    
                    <button class="btn btn-outline" id="saveJobBtn">
                        <i class="fas fa-bookmark"></i> Save Job
                    </button>
                </div>
            </div>

            <!-- Job Overview -->
            <div class="sidebar-card">
                <h3>Job Overview</h3>
                
                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-calendar-check"></i>
                        <span>Date Posted</span>
                    </div>
                    <div class="overview-value"><?php echo date('M d, Y', strtotime($job['created_at'])); ?></div>
                </div>

                <?php if (!empty($job['deadline'])): ?>
                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-hourglass-end"></i>
                        <span>Expiration</span>
                    </div>
                    <div class="overview-value"><?php echo date('M d, Y', strtotime($job['deadline'])); ?></div>
                </div>
                <?php endif; ?>

                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Location</span>
                    </div>
                    <div class="overview-value"><?php echo htmlspecialchars($job['location']); ?></div>
                </div>

                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-briefcase"></i>
                        <span>Job Type</span>
                    </div>
                    <div class="overview-value"><?php echo htmlspecialchars($job['type']); ?></div>
                </div>

                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Salary</span>
                    </div>
                    <div class="overview-value"><?php echo htmlspecialchars($job['pay']); ?></div>
                </div>

                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-users"></i>
                        <span>Applicants</span>
                    </div>
                    <div class="overview-value"><?php echo $total_applicants; ?> Applied</div>
                </div>

                <div class="overview-item">
                    <div class="overview-label">
                        <i class="fas fa-layer-group"></i>
                        <span>Category</span>
                    </div>
                    <div class="overview-value"><?php echo htmlspecialchars($job['category']); ?></div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="sidebar-card">
                <h3>Company Information</h3>
                
                <div class="company-logo"><?php echo strtoupper(substr($job['company_name'], 0, 2)); ?></div>
                <div class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></div>
                
                <?php if ($job['verified'] == 1): ?>
                <div class="company-verified">
                    <i class="fas fa-check-circle"></i>
                    Verified Company
                </div>
                <?php endif; ?>

                <?php if (!empty($job['industry'])): ?>
                <div class="company-info-item">
                    <i class="fas fa-building"></i>
                    <span><?php echo htmlspecialchars($job['industry']); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($job['size'])): ?>
                <div class="company-info-item">
                    <i class="fas fa-users"></i>
                    <span><?php echo htmlspecialchars($job['size']); ?> employees</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($job['founded_year'])): ?>
                <div class="company-info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Founded in <?php echo htmlspecialchars($job['founded_year']); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($job['office_address'])): ?>
                <div class="company-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($job['office_address']); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($job['hr_email'])): ?>
                <div class="company-info-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($job['hr_email']); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($job['website'])): ?>
                <div class="company-info-item">
                    <i class="fas fa-globe"></i>
                    <a href="<?php echo htmlspecialchars($job['website']); ?>" target="_blank" style="color: #007BFF; text-decoration: none;">
                        <?php echo htmlspecialchars($job['website']); ?>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (!empty($job['description'])): ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #E9ECEF;">
                    <p style="font-size: 14px; color: #6C757D; margin: 0;">
                        <?php echo htmlspecialchars(substr($job['description'], 0, 150)); ?>
                        <?php if (strlen($job['description']) > 150) echo '...'; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Similar Jobs -->
            <?php if ($similar_result->num_rows > 0): ?>
            <div class="sidebar-card">
                <h3>Similar Jobs</h3>
                
                <?php while ($similar_job = $similar_result->fetch_assoc()): ?>
                <div class="company-info-item">
                    <i class="fas fa-briefcase"></i>
                    <a href="job-details.php?job_id=<?php echo $similar_job['job_id']; ?>" 
                       style="color: #007BFF; text-decoration: none;">
                        <?php echo htmlspecialchars($similar_job['title']); ?>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Save Job Button
    document.getElementById('saveJobBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const icon = this.querySelector('i');
        if (icon.classList.contains('fa-bookmark')) {
            icon.classList.remove('fa-bookmark');
            icon.classList.add('fa-check');
            this.innerHTML = '<i class="fas fa-check"></i> Saved';
            this.style.backgroundColor = '#28A745';
            this.style.color = '#FFFFFF';
            this.style.borderColor = '#28A745';
            
            // In a real application, save to database via AJAX
            alert('Job saved! (This would save to your account in production)');
        } else {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-bookmark');
            this.innerHTML = '<i class="fas fa-bookmark"></i> Save Job';
            this.style.backgroundColor = 'transparent';
            this.style.color = '#007BFF';
            this.style.borderColor = '#007BFF';
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>