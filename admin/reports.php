<?php
// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Reports - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/admin.css'];
$body_class = 'dashboard-page';
$page_type = 'admin';

// Include header
include __DIR__ . '/../includes/header.php';

/** * 1. FETCH OVERVIEW STATISTICS [cite: 195]
 */
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_students = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetch_row()[0];
$total_employers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'employer'")->fetch_row()[0];
$total_jobs = $conn->query("SELECT COUNT(*) FROM jobs")->fetch_row()[0];
$active_jobs = $conn->query("SELECT COUNT(*) FROM jobs WHERE status = 'active'")->fetch_row()[0];
$total_apps = $conn->query("SELECT COUNT(*) FROM applications")->fetch_row()[0];

// Acceptance Rate calculation [cite: 12]
$accepted_apps = $conn->query("SELECT COUNT(*) FROM applications WHERE status = 'accepted'")->fetch_row()[0];
$acceptance_rate = ($total_apps > 0) ? round(($accepted_apps / $total_apps) * 100, 1) : 0;

/** * 2. FETCH CHART DATA [cite: 195, 204]
 */
// Application Status (Doughnut)
$app_status_query = $conn->query("SELECT status, COUNT(*) as count FROM applications GROUP BY status");
$app_labels = []; $app_counts = [];
while($row = $app_status_query->fetch_assoc()){
    $app_labels[] = ucfirst($row['status']);
    $app_counts[] = $row['count'];
}

// Jobs by Type (Pie)
$job_type_query = $conn->query("SELECT type, COUNT(*) as count FROM jobs GROUP BY type");
$type_labels = []; $type_counts = [];
while($row = $job_type_query->fetch_assoc()){
    $type_labels[] = $row['type'];
    $type_counts[] = $row['count'];
}

// Jobs by Category (Bar)
$cat_query = $conn->query("SELECT category, COUNT(*) as count FROM jobs GROUP BY category ORDER BY count DESC LIMIT 8");
$cat_labels = []; $cat_counts = [];
while($row = $cat_query->fetch_assoc()){
    $cat_labels[] = $row['category'];
    $cat_counts[] = $row['count'];
}

/** * 3. FETCH TABLE DATA [cite: 195]
 */
// Top Employers
$top_employers = $conn->query("SELECT e.company_name, COUNT(j.job_id) as job_count 
                               FROM employers e 
                               LEFT JOIN jobs j ON e.employer_id = j.employer_id 
                               GROUP BY e.employer_id 
                               ORDER BY job_count DESC LIMIT 5");

// Most Popular Jobs
$popular_jobs = $conn->query("SELECT j.title, e.company_name, COUNT(a.application_id) as app_count 
                              FROM jobs j 
                              JOIN employers e ON j.employer_id = e.employer_id 
                              LEFT JOIN applications a ON j.job_id = a.job_id 
                              GROUP BY j.job_id 
                              ORDER BY app_count DESC LIMIT 5");

// Recent Admin Logs 
$logs = $conn->query("SELECT * FROM admin_logs ORDER BY date DESC LIMIT 10");
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container">
    <div class="page-header">
        <h1>System Reports & Analytics</h1>
        <p>Comprehensive statistics and insights about platform activity</p>
    </div>

    <button class="export-btn" onclick="window.print()">📊 Export Report (Print)</button>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="number"><?= number_format($total_users) ?></div>
            <div class="label">Registered users</div>
        </div>
        <div class="stat-card">
            <h3>Students</h3>
            <div class="number"><?= number_format($total_students) ?></div>
            <div class="label">Active students</div>
        </div>
        <div class="stat-card">
            <h3>Employers</h3>
            <div class="number"><?= number_format($total_employers) ?></div>
            <div class="label">Registered companies</div>
        </div>
        <div class="stat-card">
            <h3>Total Jobs</h3>
            <div class="number"><?= number_format($total_jobs) ?></div>
            <div class="label"><?= $active_jobs ?> active</div>
        </div>
        <div class="stat-card">
            <h3>Applications</h3>
            <div class="number"><?= number_format($total_apps) ?></div>
            <div class="label">Total submissions</div>
        </div>
        <div class="stat-card">
            <h3>Acceptance Rate</h3>
            <div class="number"><?= $acceptance_rate ?>%</div>
            <div class="label">Application success</div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-container">
            <h2>Application Status Distribution</h2>
            <canvas id="applicationStatusChart"></canvas>
        </div>
        <div class="chart-container">
            <h2>Jobs by Type</h2>
            <canvas id="jobsByTypeChart"></canvas>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-container">
            <h2>Jobs by Category</h2>
            <canvas id="jobsByCategoryChart"></canvas>
        </div>
        <div class="chart-container">
            <h2>User Registrations (Last 6 Months)</h2>
            <canvas id="monthlyRegistrationsChart"></canvas>
        </div>
    </div>

    <div class="chart-container">
        <h2>Most Active Employers</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Jobs Posted</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while($emp = $top_employers->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($emp['company_name']) ?></td>
                    <td><?= $emp['job_count'] ?></td>
                    <td><span class="badge badge-success"><?= $emp['job_count'] > 10 ? 'Highly Active' : 'Active' ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="chart-container">
        <h2>Most Popular Jobs</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Applications</th>
                    <th>Popularity</th>
                </tr>
            </thead>
            <tbody>
                <?php $j = 1; while($job = $popular_jobs->fetch_assoc()): ?>
                <tr>
                    <td><?= $j++ ?></td>
                    <td><?= htmlspecialchars($job['title']) ?></td>
                    <td><?= htmlspecialchars($job['company_name']) ?></td>
                    <td><?= $job['app_count'] ?></td>
                    <td><span class="badge badge-success">High Demand</span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="chart-container">
        <h2>Recent Admin Activity</h2>
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Action</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php if($logs->num_rows > 0): ?>
                    <?php while($log = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('M d, Y H:i', strtotime($log['date'])) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td>
                            <?php 
                                $type_class = strpos(strtolower($log['action']), 'delete') !== false ? 'badge-danger' : 'badge-info';
                                if(strpos(strtolower($log['action']), 'approve') !== false) $type_class = 'badge-success';
                            ?>
                            <span class="badge <?= $type_class ?>">System Log</span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">No recent activity found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Application Status Chart
    new Chart(document.getElementById('applicationStatusChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($app_labels) ?>,
            datasets: [{
                data: <?= json_encode($app_counts) ?>,
                backgroundColor: ['#FD7E14', '#28A745', '#DC3545']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Jobs by Type Chart
    new Chart(document.getElementById('jobsByTypeChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($type_labels) ?>,
            datasets: [{
                data: <?= json_encode($type_counts) ?>,
                backgroundColor: ['#007BFF', '#28A745', '#FD7E14', '#17A2B8', '#6C757D']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Jobs by Category Chart
    new Chart(document.getElementById('jobsByCategoryChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($cat_labels) ?>,
            datasets: [{
                label: 'Number of Jobs',
                data: <?= json_encode($cat_counts) ?>,
                backgroundColor: '#007BFF'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });

    // Monthly Registrations (Static sample for layout)
    new Chart(document.getElementById('monthlyRegistrationsChart'), {
        type: 'line',
        data: {
            labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Users',
                data: [145, 178, 203, 189, 224, 198],
                borderColor: '#007BFF',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(0, 123, 255, 0.1)'
            }]
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>