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
    <h1>Edit Student Profile</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Full Name *</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Email *</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Phone</label>
        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

        <label>Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>">

        <label>Department *</label>
        <input type="text" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required>

        <label>Year *</label>
        <input type="text" name="year" value="<?php echo htmlspecialchars($user['year']); ?>" required>

        <label>Student ID</label>
        <input type="text" name="student_id" value="<?php echo htmlspecialchars($user['student_id']); ?>">

        <label>GPA</label>
        <input type="text" name="gpa" value="<?php echo htmlspecialchars($user['gpa']); ?>">

        <label>Skills (comma separated)</label>
        <textarea name="skills"><?php echo htmlspecialchars($user['skills']); ?></textarea>

        <label>Bio</label>
        <textarea name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>

        <label>Resume (PDF)</label>
        <input type="file" name="resume">
        <?php if(!empty($user['resume'])): ?>
            <p>Current: <a href="../uploads/resumes/<?php echo htmlspecialchars($user['resume']); ?>" target="_blank"><?php echo htmlspecialchars($user['resume']); ?></a></p>
        <?php endif; ?>

        <button type="submit" class="save-btn">Save Changes</button>
    </form>

<?php else: ?>
    <!-- EMPLOYER EDIT FORM -->
    <h1>Edit Employer Profile</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Contact Person Name *</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Email *</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Phone *</label>
        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <label>Company Name *</label>
        <input type="text" name="company_name" value="<?php echo htmlspecialchars($user['company_name']); ?>" required>

        <label>Industry</label>
        <input type="text" name="industry" value="<?php echo htmlspecialchars($user['industry']); ?>">

        <label>Company Size</label>
        <input type="text" name="company_size" value="<?php echo htmlspecialchars($user['size']); ?>">

        <label>Website</label>
        <input type="url" name="company_website" value="<?php echo htmlspecialchars($user['website']); ?>">

        <label>Logo</label>
        <input type="file" name="logo">
        <?php if(!empty($user['logo'])): ?>
            <p>Current: <a href="../uploads/logos/<?php echo htmlspecialchars($user['logo']); ?>" target="_blank"><?php echo htmlspecialchars($user['logo']); ?></a></p>
        <?php endif; ?>

        <button type="submit">Save Changes</button>
    </form>
<?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
