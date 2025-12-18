<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Student Profile | UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/profiles.css'];
$body_class = 'student-profile-page';
$page_type = 'student';
include __DIR__ . '/../includes/header.php';

// Check if logged in and student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student info including dynamic fields from users table
$sql = "SELECT u.name, u.email, u.phone, u.location, u.member_since,s.university_id,
               s.student_id, s.department, s.skills, s.resume, s.rating
        FROM users u
        JOIN students s ON u.user_id = s.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Fetch application stats
$sql_stats = "SELECT 
                COUNT(*) AS total_applications, 
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) AS completed_jobs,
                SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) AS pending_applications
              FROM applications 
              WHERE student_id=?";
$stmt_stats = $conn->prepare($sql_stats);
$stmt_stats->bind_param("i", $student['student_id']);
$stmt_stats->execute();
$stats = $stmt_stats->get_result()->fetch_assoc();

// Prepare skills array
$skills = explode(',', $student['skills']); // assuming skills stored as comma-separated string
?>

<div class="container">

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="avatar-circle"><?php echo strtoupper(substr($student['name'], 0, 2)); ?></div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($student['name']); ?></h1>
            <p class="profile-subtitle">
                <?php echo htmlspecialchars($student['department']); ?> Student 
                
            </p>
            <span class="status-badge">
                <i class="fas fa-circle"></i>
                Available for Work
            </span>
            <div class="profile-meta">
                <div class="meta-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>University of Jayewardenapura</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($student['email']); ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($student['phone']); ?></span>
                </div>
                <div class="meta-item rating">
                    <i class="fas fa-star"></i>
                    <span><strong><?php echo $student['rating']; ?></strong> (18 reviews)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <i class="fas fa-paper-plane"></i>
            <h3><?php echo $stats['total_applications']; ?></h3>
            <p>Applications Sent</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle"></i>
            <h3><?php echo $stats['completed_jobs']; ?></h3>
            <p>Jobs Completed</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <h3><?php echo $stats['pending_applications']; ?></h3>
            <p>Pending Applications</p>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="profile-section">
        <h2 class="section-title">Personal Information</h2>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Full Name</span>
                <span class="detail-value"><?php echo htmlspecialchars($student['name']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">University ID</span>
                <span class="detail-value"><?php echo htmlspecialchars($student['university_id']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Department</span>
                <span class="detail-value"><?php echo htmlspecialchars($student['department']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email Address</span>
                <span class="detail-value"><?php echo htmlspecialchars($student['email']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone Number</span>
                <span class="detail-value"><?php echo htmlspecialchars($student['phone']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Location</span>
                <span class="detail-value"><?php echo htmlspecialchars($student['location']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Member Since</span>
                <span class="detail-value"><?php echo date('F Y', strtotime($student['member_since'])); ?></span>
            </div>
        </div>
    </div>

    <!-- Skills -->
    <div class="profile-section">
        <h2 class="section-title">Skills & Expertise</h2>
        <div class="skills-container">
            <?php foreach($skills as $skill): ?>
                <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Resume -->
    <div class="profile-section">
        <h2 class="section-title">Resume / CV</h2>
        <div class="resume-box">
            <i class="fas fa-file-pdf"></i>
            <p class="resume-info">
                <strong><?php echo htmlspecialchars($student['resume']); ?></strong>
            </p>
            <div class="btn-container" style="justify-content: center;">
                <a href="../uploads/resumes/<?php echo htmlspecialchars($student['resume']); ?>" class="btn-success" download>
                    <i class="fas fa-download"></i>
                    Download Resume
                </a>
                <a href="edit-profile.php" class="btn-primary">
                    <i class="fas fa-upload"></i>
                    Upload New Resume
                </a>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="profile-section">
        <h2 class="section-title">Account Actions</h2>
        <div class="btn-container">
            <a href="edit-profile.php" class="btn-primary">
                <i class="fas fa-edit"></i>
                Edit Profile
            </a>
            <a href="../jobs/view-jobs.php" class="btn-primary">
                <i class="fas fa-search"></i>
                Browse Jobs
            </a>
            <a href="../applications/student-applications.php" class="btn-primary">
                <i class="fas fa-file-alt"></i>
                My Applications
            </a>
            <a href="change-password.php" class="btn-secondary">
                <i class="fas fa-key"></i>
                Change Password
            </a>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
