<?php
// Backend setup
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // student or employer

// Fetch existing data
if ($role === 'student') {
    $sql = "SELECT u.name, u.email, u.phone, u.location, s.student_id, s.department, s.year, s.skills, s.gpa, s.resume, s.bio
            FROM users u
            JOIN students s ON u.user_id = s.user_id
            WHERE u.user_id=?";
} else { // employer
    $sql = "SELECT u.name, u.email, u.phone, e.company_name, e.industry, e.size, e.website, e.logo
            FROM users u
            JOIN employers e ON u.user_id = e.user_id
            WHERE u.user_id=?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($role === 'student') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'] ?? '';
        $location = $_POST['location'] ?? '';
        $department = $_POST['department'];
        $year = $_POST['year'];
        $student_id = $_POST['student_id'] ?? '';
        $gpa = $_POST['gpa'] ?? '';
        $skills = $_POST['skills'] ?? '';
        $bio = $_POST['bio'] ?? '';

        // Resume upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            $resumeName = time() . '_' . basename($_FILES['resume']['name']);
            move_uploaded_file($_FILES['resume']['tmp_name'], "../uploads/resumes/$resumeName");
        } else {
            $resumeName = $user['resume']; // keep existing
        }

        // Prepare the UPDATE statement
$stmt = $conn->prepare(
    "UPDATE users u
     JOIN students s ON u.user_id = s.user_id
     SET u.name = ?, 
         u.email = ?, 
         u.phone = ?, 
         u.location = ?, 
         s.department = ?, 
         s.year = ?, 
         s.student_id = ?, 
         s.gpa = ?, 
         s.skills = ?, 
         s.resume = ?, 
         s.bio = ?
     WHERE u.user_id = ?"
);

// Bind parameters (12 variables, 12 types)
$stmt->bind_param(
    "sssssssssssi", // 11 strings + 1 integer
    $name,
    $email,
    $phone,
    $location,
    $department,
    $year,
    $student_id,
    $gpa,
    $skills,
    $resumeName,
    $bio,
    $user_id
);

// Execute
$stmt->execute();

        header("Location: student-profile.php");
        exit();

    } else { // employer
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'] ?? '';
        $company_name = $_POST['company_name'];
        $industry = $_POST['industry'] ?? '';
        $size = $_POST['company_size'] ?? '';
        $website = $_POST['company_website'] ?? '';

        // Logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $logoName = time() . '_' . basename($_FILES['logo']['name']);
            move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/logos/$logoName");
        } else {
            $logoName = $user['logo']; // keep existing
        }

        // Update employer info
        $stmt = $conn->prepare("UPDATE users u
                                JOIN employers e ON u.user_id = e.user_id
                                SET u.name=?, u.email=?, u.phone=?,
                                    e.company_name=?, e.industry=?, e.size=?, e.website=?, e.logo=?
                                WHERE u.user_id=?");
        $stmt->bind_param("ssssssssi", $name, $email, $phone, $company_name, $industry, $size, $website, $logoName, $user_id);
        $stmt->execute();
        header("Location: employer-profile.php");
        exit();
    }
}

// Page settings
$page_title = "Edit Profile | UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/profiles.css'];
$body_class = 'edit-profile-page';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
<?php if ($role === 'student'): ?>
    <!-- STUDENT EDIT FORM -->
    <div class="profile-card">
        <!-- Student Header -->
        <div class="profile-header student">
            <div class="profile-avatar student">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1>Edit Student Profile</h1>
            <p class="profile-role">Update your academic and personal information</p>
        </div>

        <!-- Student Form -->
        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Personal Information -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user-edit"></i> Personal Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="+94771234567">
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>" placeholder="City, Province">
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-graduation-cap"></i> Academic Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Department/Major <span class="required">*</span></label>
                            <input type="text" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Year of Study <span class="required">*</span></label>
                            <input type="text" name="year" value="<?php echo htmlspecialchars($user['year']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Student ID</label>
                            <input type="text" name="student_id" value="<?php echo htmlspecialchars($user['student_id']); ?>" placeholder="Your student ID">
                        </div>
                        <div class="form-group">
                            <label>GPA/CGPA</label>
                            <input type="text" name="gpa" value="<?php echo htmlspecialchars($user['gpa']); ?>" placeholder="e.g., 3.75">
                        </div>
                    </div>
                </div>

                <!-- Skills & Bio -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-tools"></i> Skills & About Me</h2>
                    <div class="form-group">
                        <label>Skills (comma separated)</label>
                        <textarea name="skills" placeholder="e.g., Python, JavaScript, Web Development, Data Analysis"><?php echo htmlspecialchars($user['skills']); ?></textarea>
                        <p class="file-info">List your technical and soft skills</p>
                    </div>
                    <div class="form-group">
                        <label>Bio / Personal Statement</label>
                        <textarea name="bio" placeholder="Tell employers about yourself, your goals, and what you're looking for..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                </div>

                <!-- Resume Upload -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-file-pdf"></i> Resume/CV</h2>
                    <div class="form-group">
                        <label>Upload Resume (PDF only)</label>
                        <input type="file" name="resume" accept=".pdf">
                        <p class="file-info">Maximum file size: 5MB | Supported format: PDF</p>
                        <?php if(!empty($user['resume'])): ?>
                            <div class="current-file">
                                <i class="fas fa-file-pdf"></i>
                                <span>Current: <a href="../uploads/resumes/<?php echo htmlspecialchars($user['resume']); ?>" target="_blank"><?php echo htmlspecialchars($user['resume']); ?></a></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="student-profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- EMPLOYER EDIT FORM -->
    <div class="profile-card">
        <!-- Employer Header -->
        <div class="profile-header employer">
            <div class="profile-avatar employer">
                <i class="fas fa-building"></i>
            </div>
            <h1>Edit Employer Profile</h1>
            <p class="profile-role">Update your company information and details</p>
        </div>

        <!-- Employer Form -->
        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Contact Person Information -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user-tie"></i> Contact Person Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Contact Person Name <span class="required">*</span></label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="+94112345678" required>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-building"></i> Company Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Company Name <span class="required">*</span></label>
                            <input type="text" name="company_name" value="<?php echo htmlspecialchars($user['company_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Industry</label>
                            <input type="text" name="industry" value="<?php echo htmlspecialchars($user['industry']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Company Size</label>
                            <input type="text" name="company_size" value="<?php echo htmlspecialchars($user['size']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="url" name="company_website" value="<?php echo htmlspecialchars($user['website']); ?>" placeholder="https://www.yourcompany.com">
                        </div>
                    </div>
                </div>

                <!-- Company Logo -->
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-image"></i> Company Logo</h2>
                    <div class="form-group">
                        <label>Upload Company Logo</label>
                        <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg">
                        <p class="file-info">Maximum file size: 2MB | Supported formats: JPG, PNG</p>
                        <?php if(!empty($user['logo'])): ?>
                            <div class="current-file">
                                <i class="fas fa-image"></i>
                                <span>Current: <a href="../uploads/logos/<?php echo htmlspecialchars($user['logo']); ?>" target="_blank"><?php echo htmlspecialchars($user['logo']); ?></a></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="employer-profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
