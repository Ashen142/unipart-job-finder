<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "admin-dashboard to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/jobs.css'];
$body_class = 'dashboard-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';

// ==================== FETCH JOBS FROM DATABASE ====================
$sql = "SELECT jobs.*, employers.company_name 
        FROM jobs 
        JOIN employers ON jobs.employer_id = employers.employer_id
        WHERE jobs.status = 'active'
        ORDER BY jobs.created_at DESC";

$result = $conn->query($sql);

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
            
            <div class="filter-group">
                <h4>Search Keywords</h4>
                <input type="text" placeholder="e.g. Web Developer" id="keyword-search">
            </div>

            <div class="filter-group">
                <h4>Job Type</h4>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="part-time">
                        <label for="part-time">Part-Time</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="full-time">
                        <label for="full-time">Full-Time</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="remote">
                        <label for="remote">Remote</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="internship">
                        <label for="internship">Internship</label>
                    </div>
                </div>
            </div>

            <div class="filter-group">
                <h4>Category</h4>
                <select id="category-filter">
                    <option value="">All Categories</option>
                    <option value="it">IT & Development</option>
                    <option value="design">Design & Creative</option>
                    <option value="marketing">Marketing</option>
                    <option value="sales">Sales</option>
                    <option value="writing">Writing & Content</option>
                    <option value="customer">Customer Service</option>
                    <option value="admin">Administrative</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="filter-group">
                <h4>Location</h4>
                <select id="location-filter">
                    <option value="">All Locations</option>
                    <option value="remote">Remote</option>
                    <option value="colombo">Colombo</option>
                    <option value="kandy">Kandy</option>
                    <option value="galle">Galle</option>
                    <option value="negombo">Negombo</option>
                    <option value="jaffna">Jaffna</option>
                </select>
            </div>

            <div class="filter-group">
                <h4>Salary Range</h4>
                <select id="salary-filter">
                    <option value="">Any Salary</option>
                    <option value="0-10">Below $10/hr</option>
                    <option value="10-20">$10 - $20/hr</option>
                    <option value="20-30">$20 - $30/hr</option>
                    <option value="30+">Above $30/hr</option>
                </select>
            </div>

            <button class="filter-btn"><i class="fas fa-search"></i> Apply Filters</button>
            <button class="clear-btn"><i class="fas fa-redo"></i> Clear All</button>
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
document.querySelector('.clear-btn').addEventListener('click', function() {
    document.getElementById('keyword-search').value = '';
    document.getElementById('category-filter').value = '';
    document.getElementById('location-filter').value = '';
    document.getElementById('salary-filter').value = '';
    document.querySelectorAll('.checkbox-item input').forEach(cb => cb.checked = false);
    alert('All filters cleared!');
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
