<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "View Jobs - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/jobs.css'];
$body_class = 'dashboard-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';

// ==================== FETCH JOBS FROM DATABASE (with filters) ====================
// Prepare dynamic filters based on GET parameters: category, keywords, location, type, salary
$where = ["jobs.status = 'active'"];
$params = [];
$types = '';

// Category (allow full name from links) - use case-insensitive partial match for robustness
if (!empty($_GET['category'])) {
    $category = trim($_GET['category']);
    $where[] = 'jobs.category LIKE ?';
    $types .= 's';
    $params[] = "%$category%";
}

// Keywords - search title, description or company name
if (!empty($_GET['keywords'])) {
    $kw = trim($_GET['keywords']);
    $like = "%" . $kw . "%";
    $where[] = '(jobs.title LIKE ? OR jobs.description LIKE ? OR employers.company_name LIKE ?)';
    $types .= 'sss';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

// Location (partial match)
if (!empty($_GET['location'])) {
    $loc = trim($_GET['location']);
    $where[] = 'jobs.location LIKE ?';
    $types .= 's';
    $params[] = "%$loc%";
}

// Type (exact match)
if (!empty($_GET['type'])) {
    $typeFilter = trim($_GET['type']);
    $where[] = 'jobs.type = ?';
    $types .= 's';
    $params[] = $typeFilter;
}

// Salary range (basic handling)
if (!empty($_GET['salary'])) {
    $salary = $_GET['salary'];
    if ($salary === '0-10') { $where[] = 'jobs.pay < 10'; }
    elseif ($salary === '10-20') { $where[] = 'jobs.pay BETWEEN 10 AND 20'; }
    elseif ($salary === '20-30') { $where[] = 'jobs.pay BETWEEN 20 AND 30'; }
    elseif ($salary === '30+') { $where[] = 'jobs.pay > 30'; }
}

$sql = "SELECT jobs.*, employers.company_name 
        FROM jobs 
        JOIN employers ON jobs.employer_id = employers.employer_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY jobs.created_at DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

// Bind params dynamically if present
if (!empty($params)) {
    // bind_param requires references
    $bind_names = [];
    $bind_names[] = $types;
    for ($i = 0; $i < count($params); $i++) {
        $bind_names[] = & $params[$i];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

$stmt->execute();
$result = $stmt->get_result();

// Time Ago Function
function timeAgoJobs($dateTime) {
    $time = strtotime($dateTime);
    $diff = time() - $time;

    if ($diff < 60) return "just now";
    if ($diff < 3600) return floor($diff/60) . " minutes ago";
    if ($diff < 86400) return floor($diff/3600) . " hours ago";
    return floor($diff/86400) . " days ago";
}
?>

<!-- Page Header -->
<div class="page-header">
    <h1>Browse Part-Time Jobs</h1>
    <p>Find the perfect opportunity that fits your schedule and skills</p>
</div>

<!-- Main Container -->
<div class="container">
    <div class="content-wrapper">

        <!-- Filter Sidebar -->
        <aside class="filter-sidebar">
            <h3><i class="fas fa-filter"></i> Filters</h3>
            
            <!-- Use GET form so filters are server-side and bookmarkable -->
            <form method="GET" action="view-jobs.php" id="filter-form">

            <div class="filter-group">
                <h4>Search Keywords</h4>
                <input type="text" name="keywords" id="keyword-search" placeholder="e.g. Web Developer" value="<?= htmlspecialchars($_GET['keywords'] ?? '') ?>">
            </div>

            <div class="filter-group">
                <h4>Job Type</h4>
                <select name="type" id="type-filter">
                    <option value="">Any Type</option>
                    <option value="Part-Time" <?= (isset($_GET['type']) && $_GET['type'] === 'Part-Time') ? 'selected' : '' ?>>Part-Time</option>
                    <option value="Full-Time" <?= (isset($_GET['type']) && $_GET['type'] === 'Full-Time') ? 'selected' : '' ?>>Full-Time</option>
                    <option value="Remote" <?= (isset($_GET['type']) && $_GET['type'] === 'Remote') ? 'selected' : '' ?>>Remote</option>
                    <option value="Internship" <?= (isset($_GET['type']) && $_GET['type'] === 'Internship') ? 'selected' : '' ?>>Internship</option>
                </select>
            </div>

            <div class="filter-group">
                <h4>Category</h4>
                <select name="category" id="category-filter">
                    <option value="">All Categories</option>
                    <option value="Technology & IT" <?= (isset($_GET['category']) && $_GET['category'] === 'Technology & IT') ? 'selected' : '' ?>>IT & Development</option>
                    <option value="Design & Creative" <?= (isset($_GET['category']) && $_GET['category'] === 'Design & Creative') ? 'selected' : '' ?>>Design & Creative</option>
                    <option value="Marketing" <?= (isset($_GET['category']) && $_GET['category'] === 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                    <option value="Sales" <?= (isset($_GET['category']) && $_GET['category'] === 'Sales') ? 'selected' : '' ?>>Sales</option>
                    <option value="Writing & Content" <?= (isset($_GET['category']) && $_GET['category'] === 'Writing & Content') ? 'selected' : '' ?>>Writing & Content</option>
                    <option value="Customer Service" <?= (isset($_GET['category']) && $_GET['category'] === 'Customer Service') ? 'selected' : '' ?>>Customer Service</option>
                    <option value="Administrative" <?= (isset($_GET['category']) && $_GET['category'] === 'Administrative') ? 'selected' : '' ?>>Administrative</option>
                    <option value="Other" <?= (isset($_GET['category']) && $_GET['category'] === 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
            </div>

            <div class="filter-group">
                <h4>Location</h4>
                <input type="text" name="location" id="location-filter" placeholder="City or 'Remote'" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">
            </div>

            <div class="filter-group">
                <h4>Salary Range</h4>
                <select name="salary" id="salary-filter">
                    <option value="">Any Salary</option>
                    <option value="0-10" <?= (isset($_GET['salary']) && $_GET['salary'] === '0-10') ? 'selected' : '' ?>>Below $10/hr</option>
                    <option value="10-20" <?= (isset($_GET['salary']) && $_GET['salary'] === '10-20') ? 'selected' : '' ?>>$10 - $20/hr</option>
                    <option value="20-30" <?= (isset($_GET['salary']) && $_GET['salary'] === '20-30') ? 'selected' : '' ?>>$20 - $30/hr</option>
                    <option value="30+" <?= (isset($_GET['salary']) && $_GET['salary'] === '30+') ? 'selected' : '' ?>>Above $30/hr</option>
                </select>
            </div>

            <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Apply Filters</button>
            <button type="button" class="clear-btn" id="clear-filters"><i class="fas fa-redo"></i> Clear All</button>

            </form>
        </aside>

        <!-- Jobs Section -->
        <section class="jobs-section">

            <!-- Jobs Header -->
            <div class="jobs-header">
                <div class="jobs-count">
                    <i class="fas fa-briefcase"></i> Showing 
                    <strong><?= $result->num_rows ?></strong> jobs
                </div>

                <div class="sort-options">
                    <label for="sort">Sort by:</label>
                    <select id="sort">
                        <option value="recent">Most Recent</option>
                        <option value="salary-high">Highest Salary</option>
                        <option value="salary-low">Lowest Salary</option>
                        <option value="applicants">Most Applicants</option>
                    </select>
                </div>
            </div>

            <!-- ==================== DYNAMIC JOB CARDS ==================== -->
            <?php 
            if ($result->num_rows > 0):
                while ($job = $result->fetch_assoc()): 
                    
                    // Count applicants
                    $job_id = $job['job_id'];
                    $app_q = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE job_id = $job_id");
                    $applicants = $app_q->fetch_assoc()['total'];
            ?>
            <div class="job-card" style="position: relative;">
                
                <!-- Job Type Badge -->
                <span class="job-type-badge 
                    <?php
                        if ($job['type'] == 'Remote') echo 'badge-remote';
                        elseif ($job['type'] == 'Part-Time') echo 'badge-parttime';
                        elseif ($job['type'] == 'Full-Time') echo 'badge-fulltime';
                        else echo 'badge-internship';
                    ?>">
                    <?= htmlspecialchars($job['type']) ?>
                </span>

                <div class="job-card-header">
                    <div class="job-info">
                        <a href="../jobs/job-details.php?job_id=<?= $job['job_id'] ?>" class="job-title">
                            <?= htmlspecialchars($job['title']) ?>
                        </a>
                        <div class="company-name">
                            <?= htmlspecialchars($job['company_name']) ?>
                        </div>
                    </div>
                </div>

                <p class="job-description">
                    <?= htmlspecialchars(substr($job['description'], 0, 160)) . "..." ?>
                </p>

                <div class="job-meta-tags">
                    <span class="meta-tag">
                        <i class="fas fa-map-marker-alt"></i> 
                        <?= htmlspecialchars($job['location']) ?>
                    </span>

                    <span class="meta-tag">
                        <i class="fas fa-clock"></i> 
                        Posted <?= timeAgoJobs($job['created_at']) ?>
                    </span>

                    <span class="meta-tag">
                        <i class="fas fa-users"></i> 
                        <?= $applicants ?> applicants
                    </span>
                </div>

                <!-- NO SKILLS (removed because skills are in student table) -->
                <div class="job-tags">
                    <span class="tag"><?= htmlspecialchars($job['category']) ?></span>
                </div>

                <div class="job-footer">
                    <div class="salary">$<?= htmlspecialchars($job['pay']) ?>/hr</div>
                    <div class="job-actions">
                        <a href="../applications/apply-job.php?job_id=<?= $job['job_id'] ?>">
                            <button class="btn-apply"><i class="fas fa-paper-plane"></i> Apply Now</button>
                        </a>
                        <button class="btn-save"><i class="far fa-bookmark"></i></button>
                    </div>
                </div>
            </div>

            <?php 
                endwhile;
            else: 
            ?>

            <p>No jobs available right now.</p>

            <?php endif; ?>
        </section>
    </div>
</div>

<script>
// Save button functionality
const saveBtns = document.querySelectorAll('.btn-save');
saveBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const icon = this.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            this.style.color = '#FD7E14';
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            this.style.color = '#6C757D';
        }
    });
});

// Job card click functionality
const jobCards = document.querySelectorAll('.job-card');
jobCards.forEach(card => {
    card.addEventListener('click', function() {
        window.location.href = this.querySelector('.job-title').getAttribute('href');
    });
});

// Clear filters
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('filter-form').reset();
    // Redirect to base page without query string
    window.location.href = 'view-jobs.php';
});

// Pre-fill values from GET for usability (already handled by PHP rendering the inputs)
// Make job cards clickable (preserve existing behavior)
const jobCards = document.querySelectorAll('.job-card');
jobCards.forEach(card => {
    card.addEventListener('click', function(e) {
        // Ignore clicks on buttons inside card
        if (e.target.tagName.toLowerCase() === 'button' || e.target.closest('button') || e.target.tagName.toLowerCase() === 'a') return;
        window.location.href = this.querySelector('.job-title').getAttribute('href');
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
