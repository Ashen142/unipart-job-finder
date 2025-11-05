<?php
// Include backend setup
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "admin-dashboard to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/dashboard.css'];
$body_class = 'dashboard-page';
$page_type = 'admin';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
                <a href="#" class="view-all">View All</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-title">Total Users</div>
                    <div class="stat-number">450</div>
                    <div class="stat-footer">
                        <span>12 Pending Approval</span>
                        <span class="stat-icon">👥</span>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-title">Total Jobs</div>
                    <div class="stat-number">180</div>
                    <div class="stat-footer">
                        <span>5 Unverified Jobs</span>
                        <span class="stat-icon">💼</span>
                    </div>
                </div>

                <div class="stat-card teal">
                    <div class="stat-title">Active Applications</div>
                    <div class="stat-number">6</div>
                    <div class="stat-footer">
                        <span>5 Unverified Jobs</span>
                        <span class="stat-icon">🏠</span>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-title">New Applications</div>
                    <div class="stat-number">920</div>
                    <div class="stat-footer">
                        <span>View Paid</span>
                        <span class="stat-icon">📄</span>
                    </div>
                </div>
            </div>

            <div class="content-grid">
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Recent User Registrations</h2>
                        <a href="#" class="view-all">View All</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                                <th>View All</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Gnn<br><small>Employer</small></td>
                                <td>Email</td>
                                <td>Remove</td>
                                <td><span class="view-btn">View</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Student<br><small>Employer</small></td>
                                <td>05P</td>
                                <td>2boas 07</td>
                                <td><button class="action-btn approve-btn">Approve</button></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Student<br><small>Employer</small></td>
                                <td>Engllent</td>
                                <td>Deoas 00</td>
                                <td><button class="action-btn suspend-btn">Suspend</button></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Student<br><small>Asudneat</small></td>
                                <td>05P</td>
                                <td>104 072928</td>
                                <td><button class="action-btn suspend-btn">Suspend</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">System Activity Log</h2>
                    </div>

                    <div class="activity-log">
                        <div class="activity-icon">📋</div>
                        <div class="activity-text">
                            Aoldin "Jshn Doe" approveot user horIst tup to1horrnsatt fartnt ol.ohara fa.nay'm tuttm
                        </div>
                    </div>

                    <div class="activity-log">
                        <div class="activity-icon">✓</div>
                        <div class="activity-text">
                            Aoldin "Jshn Doe" approved user Campirs Tø- or exoplliaslt
                        </div>
                    </div>

                    <div class="activity-log">
                        <div class="activity-icon">❌</div>
                        <div class="activity-text">
                            Roaltd bnnlurstitte Tor Cepoltt or accplllied
                        </div>
                    </div>

                    <div class="quick-links">
                        <h2 class="section-title">Quick Links</h2>
                        <button class="quick-link-btn">Manage Users</button>
                        <button class="quick-link-btn">System Reports</button>
                    </div>
                </div>
            </div>

            <div class="bottom-section">
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Latest Job Postings</h2>
                        <a href="#" class="view-all">View All</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Email</th>
                                <th>Date Posted</th>
                                <th>Date Posted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4</td>
                                <td>Student<br><small>Employer</small></td>
                                <td>Jane Smith</td>
                                <td>Remove</td>
                                <td><span class="view-btn">View</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Suodtet<br><small>Employer</small></td>
                                <td>15celt2</td>
                                <td>10110617</td>
                                <td><button class="action-btn approve-btn">Approve</button></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Student<br><small>Employer</small></td>
                                <td>Jane Smith</td>
                                <td>200.072117</td>
                                <td><button class="action-btn remove-btn">Remove</button></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Student<br><small>Employer</small></td>
                                <td>18ee09</td>
                                <td>2032002102</td>
                                <td><button class="action-btn remove-btn">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>