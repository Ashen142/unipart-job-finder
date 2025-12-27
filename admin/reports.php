<?php

// Include backend setup
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "Manage jobs - UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/admin.css'];
$body_class = 'dashboard-page';
$page_type = 'admin';

// Include header
include __DIR__ . '/../includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Main Container -->
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1>System Reports & Analytics</h1>
            <p>Comprehensive statistics and insights about platform activity</p>
        </div>

        <!-- Export Button -->
        <button class="export-btn" onclick="window.print()">📊 Export Report (Print)</button>

        <!-- Overview Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number">1,247</div>
                <div class="label">Registered users</div>
            </div>
            <div class="stat-card">
                <h3>Students</h3>
                <div class="number">892</div>
                <div class="label">Active students</div>
            </div>
            <div class="stat-card">
                <h3>Employers</h3>
                <div class="number">348</div>
                <div class="label">Registered companies</div>
            </div>
            <div class="stat-card">
                <h3>Total Jobs</h3>
                <div class="number">456</div>
                <div class="label">328 active</div>
            </div>
            <div class="stat-card">
                <h3>Applications</h3>
                <div class="number">2,834</div>
                <div class="label">Total submissions</div>
            </div>
            <div class="stat-card">
                <h3>Acceptance Rate</h3>
                <div class="number">42.5%</div>
                <div class="label">Application success</div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="charts-row">
            <!-- Application Status Chart -->
            <div class="chart-container">
                <h2>Application Status Distribution</h2>
                <canvas id="applicationStatusChart"></canvas>
            </div>

            <!-- Jobs by Type Chart -->
            <div class="chart-container">
                <h2>Jobs by Type</h2>
                <canvas id="jobsByTypeChart"></canvas>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="charts-row">
            <!-- Jobs by Category Chart -->
            <div class="chart-container">
                <h2>Jobs by Category</h2>
                <canvas id="jobsByCategoryChart"></canvas>
            </div>

            <!-- Monthly Registrations Chart -->
            <div class="chart-container">
                <h2>User Registrations (Last 6 Months)</h2>
                <canvas id="monthlyRegistrationsChart"></canvas>
            </div>
        </div>

        <!-- Top Employers Table -->
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
                    <tr>
                        <td>1</td>
                        <td>Tech Solutions Inc.</td>
                        <td>42</td>
                        <td><span class="badge badge-success">Highly Active</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Digital Marketing Co.</td>
                        <td>38</td>
                        <td><span class="badge badge-success">Highly Active</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Creative Studios</td>
                        <td>25</td>
                        <td><span class="badge badge-success">Highly Active</span></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>StartUp Hub</td>
                        <td>18</td>
                        <td><span class="badge badge-success">Highly Active</span></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>E-Commerce Plus</td>
                        <td>15</td>
                        <td><span class="badge badge-success">Highly Active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Most Applied Jobs Table -->
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
                    <tr>
                        <td>1</td>
                        <td>Social Media Manager</td>
                        <td>Digital Marketing Co.</td>
                        <td>156</td>
                        <td><span class="badge badge-success">High Demand</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Junior Web Developer</td>
                        <td>Tech Solutions Inc.</td>
                        <td>142</td>
                        <td><span class="badge badge-success">High Demand</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Content Writer</td>
                        <td>Creative Studios</td>
                        <td>128</td>
                        <td><span class="badge badge-success">High Demand</span></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Graphic Designer</td>
                        <td>StartUp Hub</td>
                        <td>98</td>
                        <td><span class="badge badge-success">High Demand</span></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Customer Support Agent</td>
                        <td>E-Commerce Plus</td>
                        <td>87</td>
                        <td><span class="badge badge-success">High Demand</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Recent Admin Activity -->
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
                    <tr>
                        <td>Dec 27, 2025 14:32</td>
                        <td>Approved new employer: Tech Innovations Ltd</td>
                        <td><span class="badge badge-success">Approval</span></td>
                    </tr>
                    <tr>
                        <td>Dec 27, 2025 13:15</td>
                        <td>Deleted inappropriate job posting</td>
                        <td><span class="badge badge-danger">Removal</span></td>
                    </tr>
                    <tr>
                        <td>Dec 27, 2025 11:45</td>
                        <td>Updated platform settings</td>
                        <td><span class="badge badge-info">Update</span></td>
                    </tr>
                    <tr>
                        <td>Dec 26, 2025 16:20</td>
                        <td>Approved student profile: John Smith</td>
                        <td><span class="badge badge-success">Approval</span></td>
                    </tr>
                    <tr>
                        <td>Dec 26, 2025 14:10</td>
                        <td>Suspended user account for policy violation</td>
                        <td><span class="badge badge-danger">Removal</span></td>
                    </tr>
                    <tr>
                        <td>Dec 26, 2025 10:30</td>
                        <td>Approved new employer: Digital Agency Pro</td>
                        <td><span class="badge badge-success">Approval</span></td>
                    </tr>
                    <tr>
                        <td>Dec 25, 2025 15:45</td>
                        <td>Generated monthly report</td>
                        <td><span class="badge badge-info">Update</span></td>
                    </tr>
                    <tr>
                        <td>Dec 25, 2025 12:20</td>
                        <td>Deleted spam job postings (3 items)</td>
                        <td><span class="badge badge-danger">Removal</span></td>
                    </tr>
                    <tr>
                        <td>Dec 24, 2025 16:00</td>
                        <td>Approved student profile: Sarah Johnson</td>
                        <td><span class="badge badge-success">Approval</span></td>
                    </tr>
                    <tr>
                        <td>Dec 24, 2025 13:30</td>
                        <td>Updated user permissions</td>
                        <td><span class="badge badge-info">Update</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        // Application Status Chart
        const appStatusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        new Chart(appStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Accepted', 'Rejected'],
                datasets: [{
                    data: [892, 1204, 738],
                    backgroundColor: ['#FD7E14', '#28A745', '#DC3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Jobs by Type Chart
        const jobTypeCtx = document.getElementById('jobsByTypeChart').getContext('2d');
        new Chart(jobTypeCtx, {
            type: 'pie',
            data: {
                labels: ['Part-Time', 'Full-Time', 'Remote', 'Internship', 'Freelance'],
                datasets: [{
                    data: [185, 92, 156, 78, 45],
                    backgroundColor: ['#007BFF', '#28A745', '#FD7E14', '#17A2B8', '#6C757D']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Jobs by Category Chart
        const jobCategoryCtx = document.getElementById('jobsByCategoryChart').getContext('2d');
        new Chart(jobCategoryCtx, {
            type: 'bar',
            data: {
                labels: ['IT & Software', 'Marketing', 'Design', 'Sales', 'Customer Service', 'Writing', 'Admin', 'Education'],
                datasets: [{
                    label: 'Number of Jobs',
                    data: [125, 98, 76, 54, 48, 38, 32, 25],
                    backgroundColor: '#007BFF'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 20
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Monthly Registrations Chart
        const monthlyRegCtx = document.getElementById('monthlyRegistrationsChart').getContext('2d');
        new Chart(monthlyRegCtx, {
            type: 'line',
            data: {
                labels: ['Jul 2025', 'Aug 2025', 'Sep 2025', 'Oct 2025', 'Nov 2025', 'Dec 2025'],
                datasets: [{
                    label: 'New Users',
                    data: [145, 178, 203, 189, 224, 198],
                    borderColor: '#007BFF',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 50
                        }
                    }
                }
            }
        });
    </script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
