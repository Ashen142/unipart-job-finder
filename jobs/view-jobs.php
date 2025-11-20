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
                        <i class="fas fa-briefcase"></i> Showing <strong>24</strong> jobs
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

                <!-- Job Cards -->
                <div class="job-card" style="position: relative;">
                    <span class="job-type-badge badge-remote">Remote</span>
                    <div class="job-card-header">
                        <div class="job-info">
                            <a href="../jobs/job-details.php" class="job-title">Web Developer Intern</a>
                            <div class="company-name">
                                Tech Solutions Inc.
                            </div>
                        </div>

                    </div>
                    <p class="job-description">
                        Looking for a motivated Web Developer Intern to join our development team. 
                        Great opportunity for university students to gain hands-on experience in web development.
                    </p>
                    <div class="job-meta-tags">
                        <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> Remote</span>
                        <span class="meta-tag"><i class="fas fa-clock"></i> Posted 2 days ago</span>
                        <span class="meta-tag"><i class="fas fa-users"></i> 12 applicants</span>
                    </div>
                    <div class="job-tags">
                        <span class="tag">PHP</span>
                        <span class="tag">MySQL</span>
                        <span class="tag">JavaScript</span>
                        <span class="tag">HTML/CSS</span>
                    </div>
                    <div class="job-footer">
                        <div class="salary">$15-20/hr</div>
                        <div class="job-actions">
                            <a href="../applications/apply-job.php"><button class="btn-apply"><i class="fas fa-paper-plane"></i> Apply Now</button></a>
                            <button class="btn-save"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="job-card" style="position: relative;">
                    <span class="job-type-badge badge-parttime">Part-Time</span>
                    <div class="job-card-header">
                        <div class="job-info">
                            <a href="../jobs/job-details.php" class="job-title">Graphic Designer</a>
                            <div class="company-name">
                                Creative Studio
                            </div>
                        </div>
                    </div>
                    <p class="job-description">
                        Seeking a creative graphic designer to work on branding projects. Flexible hours, 
                        perfect for students with design skills and a passion for visual storytelling.
                    </p>
                    <div class="job-meta-tags">
                        <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> Colombo</span>
                        <span class="meta-tag"><i class="fas fa-clock"></i> Posted 1 week ago</span>
                        <span class="meta-tag"><i class="fas fa-users"></i> 8 applicants</span>
                    </div>
                    <div class="job-tags">
                        <span class="tag">Photoshop</span>
                        <span class="tag">Illustrator</span>
                        <span class="tag">Figma</span>
                    </div>
                    <div class="job-footer">
                        <div class="salary">$18-25/hr</div>
                        <div class="job-actions">
                             <a href="../applications/apply-job.php"><button class="btn-apply"><i class="fas fa-paper-plane"></i> Apply Now</button></a>
                            <button class="btn-save"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="job-card" style="position: relative;">
                    <span class="job-type-badge badge-fulltime">Internship</span>
                    <div class="job-card-header">
                        <div class="job-info">
                            <a href="../jobs/job-details.php" class="job-title">Content Writer</a>
                            <div class="company-name">
                                Digital Marketing Hub
                            </div>
                        </div>
                    </div>
                    <p class="job-description">
                        Join our content team and create engaging blog posts, articles, and social media content. 
                        Perfect for journalism or English major students.
                    </p>
                    <div class="job-meta-tags">
                        <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> Remote</span>
                        <span class="meta-tag"><i class="fas fa-clock"></i> Posted 3 days ago</span>
                        <span class="meta-tag"><i class="fas fa-users"></i> 15 applicants</span>
                    </div>
                    <div class="job-tags">
                        <span class="tag">Content Writing</span>
                        <span class="tag">SEO</span>
                        <span class="tag">Research</span>
                    </div>
                    <div class="job-footer">
                        <div class="salary">$12-18/hr</div>
                        <div class="job-actions">
                             <a href="../applications/apply-job.php"><button class="btn-apply"><i class="fas fa-paper-plane"></i> Apply Now</button></a>
                            <button class="btn-save"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="job-card" style="position: relative;">
                    <span class="job-type-badge badge-parttime">Part-Time</span>
                    <div class="job-card-header">
                        <div class="job-info">
                            <a href="../jobs/job-details.php" class="job-title">Social Media Manager</a>
                            <div class="company-name">
                                Startup Ventures
                            </div>
                        </div>
                    </div>
                    <p class="job-description">
                        Manage social media accounts for multiple clients. Create posts, engage with followers, 
                        and track analytics. Flexible schedule that works around your classes.
                    </p>
                    <div class="job-meta-tags">
                        <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> Hybrid</span>
                        <span class="meta-tag"><i class="fas fa-clock"></i> Posted 5 days ago</span>
                        <span class="meta-tag"><i class="fas fa-users"></i> 20 applicants</span>
                    </div>
                    <div class="job-tags">
                        <span class="tag">Social Media</span>
                        <span class="tag">Marketing</span>
                        <span class="tag">Analytics</span>
                    </div>
                    <div class="job-footer">
                        <div class="salary">$15-22/hr</div>
                        <div class="job-actions">
                             <a href="../applications/apply-job.php"><button class="btn-apply"><i class="fas fa-paper-plane"></i> Apply Now</button></a>
                            <button class="btn-save"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="job-card" style="position: relative;">
                    <span class="job-type-badge badge-remote">Remote</span>
                    <div class="job-card-header">
                        <div class="job-info">
                            <a href="../jobs/job-details.php" class="job-title">Data Entry Specialist</a>
                            <div class="company-name">
                                Business Services Co.
                            </div>
                        </div>
                    </div>
                    <p class="job-description">
                        Entry-level position for students. Accurate data entry and basic spreadsheet management. 
                        Work from anywhere with flexible hours.
                    </p>
                    <div class="job-meta-tags">
                        <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> Remote</span>
                        <span class="meta-tag"><i class="fas fa-clock"></i> Posted 1 day ago</span>
                        <span class="meta-tag"><i class="fas fa-users"></i> 5 applicants</span>
                    </div>
                    <div class="job-tags">
                        <span class="tag">Excel</span>
                        <span class="tag">Data Entry</span>
                        <span class="tag">Attention to Detail</span>
                    </div>
                    <div class="job-footer">
                        <div class="salary">$10-15/hr</div>
                        <div class="job-actions">
                            <a href="../applications/apply-job.php"><button class="btn-apply"><i class="fas fa-paper-plane"></i> Apply Now</button></a>
                            <button class="btn-save"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
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
                window.location.href = '../jobs/job-details.php';
            });
        });

        // Clear filters functionality
        const clearBtn = document.querySelector('.clear-btn');
        clearBtn.addEventListener('click', function() {
            document.getElementById('keyword-search').value = '';
            document.getElementById('category-filter').value = '';
            document.getElementById('location-filter').value = '';
            document.getElementById('salary-filter').value = '';
            document.querySelectorAll('.checkbox-item input').forEach(cb => {
                cb.checked = false;
            });
            alert('All filters cleared!');
        });

        // Pagination functionality
        const pageBtns = document.querySelectorAll('.page-btn');
        pageBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.querySelector('i')) { // If not a navigation arrow
                    pageBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    </script>


<?php include __DIR__ . '/../includes/footer.php'; ?>