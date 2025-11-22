<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// ------------------------------------------------------
// FETCH JOB DETAILS
// ------------------------------------------------------
if (!isset($_GET['id'])) {
    die("Job ID missing.");
}

$job_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Get employer_id from employers table
$employer_check = "SELECT employer_id FROM employers WHERE user_id = ?";
$stmt_check = $conn->prepare($employer_check);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$employer_result = $stmt_check->get_result();

if ($employer_result->num_rows === 0) {
    die("Employer profile not found.");
}

$employer_data = $employer_result->fetch_assoc();
$employer_id = $employer_data['employer_id'];
$stmt_check->close();

// Get job details
$sql = "SELECT * FROM jobs WHERE job_id = ? AND employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $employer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Unauthorized or Job not found.");
}

$job = $result->fetch_assoc();

// ------------------------------------------------------
// PROCESS FORM SUBMISSION
// ------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $job_title = $_POST['job_title'];
    $job_type = $_POST['job_type'];
    $job_category = $_POST['job_category'];
    $job_pay = $_POST['job_pay'];
    $job_location = $_POST['job_location'];
    $job_description = $_POST['job_description'];
    $job_requirements = $_POST['job_requirements'];
    $job_status = $_POST['job_status'];
    $deadline = $_POST['deadline'] ?? NULL;

    // Keep old image unless replaced/deleted
    $newImageName = $job['image'] ?? NULL;

    // If user removed image manually
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == "1") {
        if (!empty($job['image']) && file_exists("../uploads/job-images/" . $job['image'])) {
            unlink("../uploads/job-images/" . $job['image']);
        }
        $newImageName = NULL;
    }

    // If new image uploaded
    if (!empty($_FILES['job_image']['name'])) {
        $imgName = time() . "_" . basename($_FILES['job_image']['name']);
        $target = "../uploads/job-images/" . $imgName;

        if (move_uploaded_file($_FILES['job_image']['tmp_name'], $target)) {
            // Delete previous image from folder
            if (!empty($job['image']) && file_exists("../uploads/job-images/" . $job['image'])) {
                unlink("../uploads/job-images/" . $job['image']);
            }
            $newImageName = $imgName;
        }
    }

    // Update job
    $update = "UPDATE jobs SET 
        title=?, type=?, category=?, pay=?, location=?, description=?, 
        requirements=?, status=?, deadline=?, image=?
        WHERE job_id=? AND employer_id=?";

    $stmt2 = $conn->prepare($update);
    $stmt2->bind_param(
        "ssssssssssii",
        $job_title,
        $job_type,
        $job_category,
        $job_pay,
        $job_location,
        $job_description,
        $job_requirements,
        $job_status,
        $deadline,
        $newImageName,
        $job_id,
        $employer_id
    );

    if ($stmt2->execute()) {
        header("Location: edit-job.php?id=$job_id&success=1");
        exit();
    } else {
        echo "<script>alert('Error updating job');</script>";
    }
}

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

    <!-- Form Card -->
    <div class="form-card">
        <form action="edit-job.php?id=<?= $job_id ?>" method="POST" enctype="multipart/form-data" id="editJobForm">
            
            <input type="hidden" name="remove_image" id="remove_image" value="0">

            <!-- Job Title -->
            <div class="form-group">
                <label for="job_title">Job Title <span class="required">*</span></label>
                <input type="text" id="job_title" name="job_title" class="form-control"
                    value="<?= htmlspecialchars($job['title']) ?>" required>
            </div>

            <!-- Type & Category -->
            <div class="form-row">
                <div class="form-group">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option <?= $job['type']=="Part-Time"?"selected":"" ?>>Part-Time</option>
                        <option <?= $job['type']=="Full-Time"?"selected":"" ?>>Full-Time</option>
                        <option <?= $job['type']=="Freelance"?"selected":"" ?>>Freelance</option>
                        <option <?= $job['type']=="Remote"?"selected":"" ?>>Remote</option>
                        <option <?= $job['type']=="Internship"?"selected":"" ?>>Internship</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Job Category</label>
                    <select name="job_category" class="form-control">
                        <option <?= $job['category']=="IT & Software"?"selected":"" ?>>IT & Software</option>
                        <option <?= $job['category']=="Marketing"?"selected":"" ?>>Marketing</option>
                        <option <?= $job['category']=="Design"?"selected":"" ?>>Design</option>
                        <option <?= $job['category']=="Sales"?"selected":"" ?>>Sales</option>
                        <option <?= $job['category']=="Customer Service"?"selected":"" ?>>Customer Service</option>
                        <option <?= $job['category']=="Writing & Content"?"selected":"" ?>>Writing & Content</option>
                        <option <?= $job['category']=="Teaching & Tutoring"?"selected":"" ?>>Teaching & Tutoring</option>
                        <option <?= $job['category']=="Other"?"selected":"" ?>>Other</option>
                    </select>
                </div>
            </div>

            <!-- Pay & Location -->
            <div class="form-row">
                <div class="form-group">
                    <label>Pay Rate</label>
                    <input type="text" name="job_pay" class="form-control"
                        value="<?= htmlspecialchars($job['pay']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="job_location" class="form-control"
                        value="<?= htmlspecialchars($job['location']) ?>" required>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="job_description" class="form-control" required><?= htmlspecialchars($job['description']) ?></textarea>
            </div>

            <!-- Requirements -->
            <div class="form-group">
                <label>Requirements</label>
                <textarea name="job_requirements" class="form-control"><?= htmlspecialchars($job['requirements']) ?></textarea>
            </div>

            <!-- Status & Deadline -->
            <div class="form-row">
                <div class="form-group">
                    <label>Status</label>
                    <select name="job_status" class="form-control">
                        <option value="active" <?= $job['status']=="active"?"selected":"" ?>>Active</option>
                        <option value="closed" <?= $job['status']=="closed"?"selected":"" ?>>Closed</option>
                        <option value="pending" <?= $job['status']=="pending"?"selected":"" ?>>Pending Review</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Application Deadline</label>
                    <input type="date" name="deadline" class="form-control"
                        value="<?= $job['deadline'] ?>">
                </div>
            </div>

            <!-- Current Image -->
            <div class="form-group">
                <label>Current Image</label>
                <?php if (!empty($job['image'])) { ?>
                    <div id="currentImageDiv" class="current-image">
                        <img src="../uploads/job-images/<?= htmlspecialchars($job['image']) ?>" style="max-width:200px;">
                        <button type="button" id="removeImageBtn" class="remove-image-btn">Remove</button>
                    </div>
                <?php } else { ?>
                    <p>No image uploaded.</p>
                <?php } ?>
            </div>

            <!-- Upload New Image -->
            <div class="form-group">
                <label>Upload New Image</label>
                <input type="file" name="job_image" accept="image/*">
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="../applications/employer-applications.php?job_id=<?= $job_id ?>" class="btn btn-secondary">View Applicants</a>
            </div>

        </form>
    </div>
</div>

<script>
document.getElementById('removeImageBtn')?.addEventListener('click', function () {
    if (confirm("Remove image?")) {
        document.getElementById('currentImageDiv').style.display = 'none';
        document.getElementById('remove_image').value = "1";
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>