<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Employer Profile - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/profiles.css'];
$body_class = 'student-profile-page';
$page_type = 'employee';

// Include header
include __DIR__ . '/../includes/header.php';

// Get logged-in employer ID
$employer_user_id = $_SESSION['user_id'];

// Fetch employer data
$sql = "SELECT u.name AS user_name, u.email AS primary_email,u.phone,
               e.company_name, e.verified, e.rating,
               e.industry, e.size, e.founded_year, e.website, e.hr_email,
               e.office_address, u.member_since, e.description
        FROM users u
        JOIN employers e ON u.user_id = e.user_id
        WHERE u.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employer_user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $employer = $result->fetch_assoc();
} else {
    echo "<p>Employer profile not found.</p>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

// Example stats (replace with dynamic queries if you want real numbers)
$total_jobs_posted = 15;
$active_applicants = 47;
$students_hired = 32;

$sql_jobs = "SELECT COUNT(*) AS total_jobs FROM jobs WHERE employer_id = (SELECT employer_id FROM employers WHERE user_id = ?)";
$stmt_jobs = $conn->prepare($sql_jobs);
$stmt_jobs->bind_param("i", $employer_user_id);
$stmt_jobs->execute();
$result_jobs = $stmt_jobs->get_result();
$total_jobs_posted = $result_jobs->fetch_assoc()['total_jobs'];

$sql_applicants = "SELECT COUNT(*) AS active_applicants
                   FROM applications a
                   JOIN jobs j ON a.job_id = j.job_id
                   WHERE j.employer_id = (SELECT employer_id FROM employers WHERE user_id = ?)
                   AND a.status IN ('applied', 'pending')";
$stmt_applicants = $conn->prepare($sql_applicants);
$stmt_applicants->bind_param("i", $employer_user_id);
$stmt_applicants->execute();
$result_applicants = $stmt_applicants->get_result();
$active_applicants = $result_applicants->fetch_assoc()['active_applicants'];

$sql_hired = "SELECT COUNT(*) AS students_hired
              FROM applications a
              JOIN jobs j ON a.job_id = j.job_id
              WHERE j.employer_id = (SELECT employer_id FROM employers WHERE user_id = ?)
              AND a.status = 'accepted'";
$stmt_hired = $conn->prepare($sql_hired);
$stmt_hired->bind_param("i", $employer_user_id);
$stmt_hired->execute();
$result_hired = $stmt_hired->get_result();
$students_hired = $result_hired->fetch_assoc()['students_hired'];

?>


<div class="container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="avatar-circle"><?php echo substr($employer['company_name'], 0, 3); ?></div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($employer['company_name']); ?></h1>
            <?php if($employer['verified']): ?>
                <span class="verified-badge"><i class="fas fa-check-circle"></i> Verified Employer</span>
            <?php endif; ?>
            <div class="profile-meta">
                <div class="meta-item"><i class="fas fa-envelope"></i><span><?php echo htmlspecialchars($employer['primary_email']); ?></span></div>
                <div class="meta-item"><i class="fas fa-phone"></i><span><?php echo htmlspecialchars($employer['phone']); ?></span></div>
                <div class="meta-item"><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars($employer['office_address']); ?></span></div>
                <div class="meta-item rating"><i class="fas fa-star"></i><span><strong><?php echo $employer['rating']; ?></strong> (124 reviews)</span></div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
   <div class="stats-container">
    <div class="stat-card"><i class="fas fa-briefcase"></i><h3><?php echo $total_jobs_posted; ?></h3><p>Total Jobs Posted</p></div>
    <div class="stat-card"><i class="fas fa-users"></i><h3><?php echo $active_applicants; ?></h3><p>Active Applicants</p></div>
    <div class="stat-card"><i class="fas fa-user-check"></i><h3><?php echo $students_hired; ?></h3><p>Students Hired</p></div>
</div>


    <!-- Company Details -->
    <div class="profile-section">
        <h2 class="section-title">Company Details</h2>
        <div class="detail-grid">
            <div class="detail-item"><span class="detail-label">Company Name</span><span class="detail-value"><?php echo htmlspecialchars($employer['company_name']); ?></span></div>
            <div class="detail-item"><span class="detail-label">Industry</span><span class="detail-value"><?php echo htmlspecialchars($employer['industry']); ?></span></div>
            <div class="detail-item"><span class="detail-label">Company Size</span><span class="detail-value"><?php echo htmlspecialchars($employer['size']); ?></span></div>
            <div class="detail-item"><span class="detail-label">Founded Year</span><span class="detail-value"><?php echo htmlspecialchars($employer['founded_year']); ?></span></div>
            <div class="detail-item"><span class="detail-label">Website</span><span class="detail-value"><a href="<?php echo htmlspecialchars($employer['website']); ?>" style="color: #007BFF;"><?php echo htmlspecialchars($employer['website']); ?></a></span></div>
            <div class="detail-item"><span class="detail-label">Member Since</span><span class="detail-value"><?php echo date('F Y', strtotime($employer['member_since'])); ?></span></div>
        </div>
    </div>

    <!-- About Company -->
    <div class="profile-section">
        <h2 class="section-title">About Company</h2>
        <p class="description"><?php echo nl2br(htmlspecialchars($employer['description'])); ?></p>
    </div>

    <!-- Contact Information -->
    <div class="profile-section">
        <h2 class="section-title">Contact Information</h2>
        <div class="detail-grid">
            <div class="detail-item"><span class="detail-label">Primary Email</span><span class="detail-value"><?php echo htmlspecialchars($employer['primary_email']); ?></span></div>
            <div class="detail-item"><span class="detail-label">HR Email</span><span class="detail-value"><?php echo htmlspecialchars($employer['hr_email']); ?></span></div>
            <div class="detail-item"><span class="detail-label">Phone Number</span><span class="detail-value"><?php echo htmlspecialchars($employer['phone']); ?></span></div>
            <div class="detail-item"><span class="detail-label">Office Address</span><span class="detail-value"><?php echo htmlspecialchars($employer['office_address']); ?></span></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="profile-section">
        <h2 class="section-title">Account Actions</h2>
        <div class="btn-container">
            <a href="edit-profile.php" class="btn-primary"><i class="fas fa-edit"></i> Edit Profile</a>
            <a href="../jobs/add-job.php" class="btn-primary"><i class="fas fa-plus"></i> Post New Job</a>
            <button class="btn-secondary"><i class="fas fa-key"></i> Change Password</button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
