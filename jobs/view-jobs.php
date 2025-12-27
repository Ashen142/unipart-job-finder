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

// ==================== 1. PREPARE FILTERS & SORTING ====================
$where = ["jobs.status = 'active'"];
$params = [];
$types = '';

// Filter: Category
if (!empty($_GET['category'])) {
    $where[] = 'jobs.category = ?';
    $types .= 's';
    $params[] = $_GET['category'];
}

// Filter: Keywords
if (!empty($_GET['keywords'])) {
    $kw = "%" . trim($_GET['keywords']) . "%";
    $where[] = '(jobs.title LIKE ? OR jobs.description LIKE ? OR employers.company_name LIKE ?)';
    $types .= 'sss';
    $params[] = $kw; $params[] = $kw; $params[] = $kw;
}

// Filter: Location
if (!empty($_GET['location'])) {
    $where[] = 'jobs.location LIKE ?';
    $types .= 's';
    $params[] = "%" . trim($_GET['location']) . "%";
}

// Filter: Type
if (!empty($_GET['type'])) {
    $where[] = 'jobs.type = ?';
    $types .= 's';
    $params[] = $_GET['type'];
}

// Filter: Salary
if (!empty($_GET['salary'])) {
    $salary = $_GET['salary'];
    if ($salary === '0-10') { $where[] = 'jobs.pay < 10'; }
    elseif ($salary === '10-20') { $where[] = 'jobs.pay BETWEEN 10 AND 20'; }
    elseif ($salary === '20-30') { $where[] = 'jobs.pay BETWEEN 20 AND 30'; }
    elseif ($salary === '30+') { $where[] = 'jobs.pay > 30'; }
}

// Sorting Logic
$sort_options = [
    'recent'       => 'jobs.created_at DESC',
    'salary-high'  => 'jobs.pay DESC',
    'salary-low'   => 'jobs.pay ASC',
    'applicants'   => 'applicants_count DESC'
];
$sort_key = $_GET['sort'] ?? 'recent';
$order_by = $sort_options[$sort_key] ?? 'jobs.created_at DESC';

// ==================== 2. EXECUTE QUERY ====================
// We use a subquery for applicant_count to keep it efficient
$sql = "SELECT jobs.*, employers.company_name, 
        (SELECT COUNT(*) FROM applications WHERE applications.job_id = jobs.job_id) as applicants_count
        FROM jobs 
        JOIN employers ON jobs.employer_id = employers.employer_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY $order_by";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Time Ago Function
function timeAgoJobs($dateTime) {
    $time = strtotime($dateTime);
    $diff = time() - $time;
    if ($diff < 60) return "just now";
    if ($diff < 3600) return floor($diff/60) . " mins ago";
    if ($diff < 86400) return floor($diff/3600) . " hours ago";
    return floor($diff/86400) . " days ago";
}
?>

<div class="page-header">
    <h1>Browse Part-Time Jobs</h1>
    <p>Find the perfect opportunity that fits your schedule and skills</p>
</div>

<div class="container">
    <div class="content-wrapper">
        <aside class="filter-sidebar">
            <h3><i class="fas fa-filter"></i> Filters</h3>
            <form method="GET" action="view-jobs.php" id="filter-form">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort_key) ?>">

                <div class="filter-group">
                    <h4>Search Keywords</h4>
                    <input type="text" name="keywords" placeholder="e.g. Developer" value="<?= htmlspecialchars($_GET['keywords'] ?? '') ?>">
                </div>

                <div class="filter-group">
                    <h4>Job Type</h4>
                    <select name="type" class="auto-submit">
                        <option value="">Any Type</option>
                        <?php foreach(['Part-Time', 'Full-Time', 'Remote', 'Internship'] as $t): ?>
                            <option value="<?= $t ?>" <?= (($_GET['type'] ?? '') === $t) ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <h4>Category</h4>
                    <select name="category" class="auto-submit">
                        <option value="">All Categories</option>
                        <option value="Technology & IT" <?= (($_GET['category'] ?? '') === 'Technology & IT') ? 'selected' : '' ?>>IT & Development</option>
                        <option value="Design & Creative" <?= (($_GET['category'] ?? '') === 'Design & Creative') ? 'selected' : '' ?>>Design & Creative</option>
                        <option value="Marketing" <?= (($_GET['category'] ?? '') === 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                    </select>
                </div>

                <div class="filter-group">
                    <h4>Location</h4>
                    <input type="text" name="location" placeholder="City or Remote" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">
                </div>

                <div class="filter-group">
                    <h4>Salary Range</h4>
                    <select name="salary" class="auto-submit">
                        <option value="">Any Salary</option>
                        <option value="0-10" <?= (($_GET['salary'] ?? '') === '0-10') ? 'selected' : '' ?>>Below $10/hr</option>
                        <option value="10-20" <?= (($_GET['salary'] ?? '') === '10-20') ? 'selected' : '' ?>>$10 - $20/hr</option>
                        <option value="30+" <?= (($_GET['salary'] ?? '') === '30+') ? 'selected' : '' ?>>Above $30/hr</option>
                    </select>
                </div>

                <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Apply Filters</button>
                <button type="button" class="clear-btn" id="clear-filters"><i class="fas fa-redo"></i> Clear All</button>
            </form>
        </aside>

        <section class="jobs-section">
            <div class="jobs-header">
                <div class="jobs-count">
                    <i class="fas fa-briefcase"></i> Showing <strong><?= $result->num_rows ?></strong> jobs
                </div>

                <div class="sort-options">
                    <label for="sort-dropdown">Sort by:</label>
                    <select id="sort-dropdown">
                        <option value="recent" <?= $sort_key === 'recent' ? 'selected' : '' ?>>Most Recent</option>
                        <option value="salary-high" <?= $sort_key === 'salary-high' ? 'selected' : '' ?>>Highest Salary</option>
                        <option value="salary-low" <?= $sort_key === 'salary-low' ? 'selected' : '' ?>>Lowest Salary</option>
                        <option value="applicants" <?= $sort_key === 'applicants' ? 'selected' : '' ?>>Most Applicants</option>
                    </select>
                </div>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($job = $result->fetch_assoc()): ?>
                    <div class="job-card" data-href="../jobs/job-details.php?job_id=<?= $job['job_id'] ?>">
                        <span class="job-type-badge badge-<?= strtolower(str_replace(' ', '', $job['type'])) ?>">
                            <?= htmlspecialchars($job['type']) ?>
                        </span>

                        <div class="job-card-header">
                            <div class="job-info">
                                <a href="../jobs/job-details.php?job_id=<?= $job['job_id'] ?>" class="job-title">
                                    <?= htmlspecialchars($job['title']) ?>
                                </a>
                                <div class="company-name"><?= htmlspecialchars($job['company_name']) ?></div>
                            </div>
                        </div>

                        <p class="job-description">
                            <?= htmlspecialchars(substr($job['description'], 0, 160)) ?>...
                        </p>

                        <div class="job-meta-tags">
                            <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location']) ?></span>
                            <span class="meta-tag"><i class="fas fa-clock"></i> <?= timeAgoJobs($job['created_at']) ?></span>
                            <span class="meta-tag"><i class="fas fa-users"></i> <?= $job['applicants_count'] ?> applicants</span>
                        </div>

                        <div class="job-footer">
                            <div class="salary">$<?= htmlspecialchars($job['pay']) ?>/hr</div>
                            <div class="job-actions">
                                <a href="../applications/apply-job.php?job_id=<?= $job['job_id'] ?>" class="btn-apply">
                                    <i class="fas fa-paper-plane"></i> Apply Now
                                </a>
                                <button class="btn-save"><i class="far fa-bookmark"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No jobs found matching your criteria.</p>
                    <a href="view-jobs.php">Reset all filters</a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    
    // 1. Auto-submit dropdowns inside the form
    document.querySelectorAll('.auto-submit').forEach(select => {
        select.addEventListener('change', () => filterForm.submit());
    });

    // 2. Handle Sort Dropdown (Syncs with hidden input in form)
    const sortDropdown = document.getElementById('sort-dropdown');
    sortDropdown.addEventListener('change', function() {
        filterForm.querySelector('input[name="sort"]').value = this.value;
        filterForm.submit();
    });

    // 3. Clear Filters
    document.getElementById('clear-filters').addEventListener('click', () => {
        window.location.href = 'view-jobs.php';
    });

    // 4. Clickable Cards (preventing button trigger)
    document.querySelectorAll('.job-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('button') || e.target.closest('a')) return;
            window.location.href = this.dataset.href;
        });
    });

    // 5. Save Button Toggle
    document.querySelectorAll('.btn-save').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            this.style.color = icon.classList.contains('fas') ? '#FD7E14' : '#6C757D';
        });
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>