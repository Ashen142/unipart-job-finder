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

 <!-- Main Container -->
    <div class="container1">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="../index.php"><i class="fas fa-home"></i> Home</a>
            <span>/</span>
            <a href="view-jobs.php">Jobs</a>
            <span>/</span>
            <span>Web Developer - Part Time</span>
        </div>

        <!-- Alert for logged out users -->
        <!-- Uncomment if user is not logged in -->
        <!--
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Please <a href="../auth/login.php" style="color: #FFFFFF; text-decoration: underline;">login</a> to apply for this job.
        </div>
        -->

        <!-- Job Layout -->
        <div class="job-layout1">
            <!-- Main Content -->
            <div class="job-main1">
                <!-- Job Header -->
                <div class="job-header1">
                    <h1 class="job-title1">Web Developer - Part Time</h1>
                    
                    <div class="job-meta1">
                        <span class="job-type-badge1 badge-part-time">
                            <i class="fas fa-clock"></i> Part Time
                        </span>
                        <div class="job-meta1-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Colombo, Sri Lanka</span>
                        </div>
                        <div class="job-meta1-item">
                            <i class="fas fa-dollar-sign"></i>
                            <span>LKR 50,000 - 75,000 /month</span>
                        </div>
                        <div class="job-meta1-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Posted 3 days ago</span>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="job-section1">
                    <h2><i class="fas fa-file-alt"></i> Job Description</h2>
                    <p>
                        We are looking for a talented and motivated Web Developer to join our team on a part-time basis. 
                        This position is perfect for university students who want to gain real-world experience while 
                        continuing their studies. You will work on exciting projects, developing and maintaining web 
                        applications using modern technologies.
                    </p>
                    <p>
                        As a Web Developer, you will collaborate with our design and development teams to create 
                        responsive, user-friendly websites and web applications. This is an excellent opportunity to 
                        build your portfolio and enhance your technical skills in a professional environment.
                    </p>
                </div>

                <!-- Key Responsibilities -->
                <div class="job-section1">
                    <h3><i class="fas fa-tasks"></i> Key Responsibilities</h3>
                    <ul>
                        <li>Develop and maintain responsive websites using HTML, CSS, and JavaScript</li>
                        <li>Work with PHP and MySQL to create dynamic web applications</li>
                        <li>Collaborate with designers to implement UI/UX designs</li>
                        <li>Debug and troubleshoot website issues</li>
                        <li>Optimize websites for maximum speed and scalability</li>
                        <li>Participate in code reviews and team meetings</li>
                        <li>Stay updated with emerging web technologies and best practices</li>
                    </ul>
                </div>

                <!-- Requirements -->
                <div class="job-section1">
                    <h3><i class="fas fa-check-circle"></i> Requirements</h3>
                    <ul>
                        <li>Currently enrolled in Computer Science, IT, or related field</li>
                        <li>Strong knowledge of HTML5, CSS3, and JavaScript</li>
                        <li>Experience with PHP and MySQL databases</li>
                        <li>Familiarity with responsive design principles</li>
                        <li>Basic understanding of version control (Git)</li>
                        <li>Good problem-solving and analytical skills</li>
                        <li>Ability to work 15-20 hours per week</li>
                        <li>Strong communication and teamwork skills</li>
                    </ul>
                </div>

                <!-- Preferred Skills -->
                <div class="job-section1">
                    <h3><i class="fas fa-star"></i> Preferred Skills</h3>
                    <div class="skills-container">
                        <span class="skill-tag">React.js</span>
                        <span class="skill-tag">Bootstrap</span>
                        <span class="skill-tag">jQuery</span>
                        <span class="skill-tag">RESTful APIs</span>
                        <span class="skill-tag">WordPress</span>
                        <span class="skill-tag">SASS/SCSS</span>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="job-section1">
                    <h3><i class="fas fa-gift"></i> Benefits</h3>
                    <ul>
                        <li>Flexible working hours to accommodate your class schedule</li>
                        <li>Competitive salary based on experience</li>
                        <li>Opportunity to work on real-world projects</li>
                        <li>Mentorship from experienced developers</li>
                        <li>Remote work options available</li>
                        <li>Certificate of completion after successful tenure</li>
                        <li>Potential for full-time position after graduation</li>
                    </ul>
                </div>

                <!-- Share Job -->
                <div class="job-section1">
                    <h3><i class="fas fa-share-alt"></i> Share This Job</h3>
                    <div class="share-buttons">
                        <a href="#" class="share-btn share-facebook" title="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="share-btn share-twitter" title="Share on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="share-btn share-linkedin" title="Share on LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="share-btn share-email" title="Share via Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="job-sidebar">
                <!-- Apply Button -->
                <div class="sidebar-card">
                    <div class="action-buttons">
                        <a href="../applications/apply-job.php?job_id=1" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Apply Now
                        </a>
                        <button class="btn btn-outline">
                            <i class="fas fa-bookmark"></i> Save Job
                        </button>
                    </div>
                </div>

                <!-- Job Overview -->
                <div class="sidebar-card">
                    <h3>Job Overview</h3>
                    
                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-calendar-check"></i>
                            <span>Date Posted</span>
                        </div>
                        <div class="overview-value">Nov 17, 2025</div>
                    </div>

                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-hourglass-end"></i>
                            <span>Expiration</span>
                        </div>
                        <div class="overview-value">Dec 17, 2025</div>
                    </div>

                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Location</span>
                        </div>
                        <div class="overview-value">Colombo</div>
                    </div>

                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-briefcase"></i>
                            <span>Job Type</span>
                        </div>
                        <div class="overview-value">Part Time</div>
                    </div>

                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Salary</span>
                        </div>
                        <div class="overview-value">50k - 75k</div>
                    </div>

                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-users"></i>
                            <span>Applicants</span>
                        </div>
                        <div class="overview-value">12 Applied</div>
                    </div>

                    <div class="overview-item">
                        <div class="overview-label">
                            <i class="fas fa-layer-group"></i>
                            <span>Category</span>
                        </div>
                        <div class="overview-value">IT & Software</div>
                    </div>
                </div>

                <!-- Company Info -->
                <div class="sidebar-card">
                    <h3>Company Information</h3>
                    
                    <div class="company-logo">TI</div>
                    <div class="company-name">Tech Innovations Pvt Ltd</div>
                    <div class="company-verified">
                        <i class="fas fa-check-circle"></i>
                        Verified Company
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-building"></i>
                        <span>Software Development</span>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Colombo 03, Sri Lanka</span>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-phone"></i>
                        <span>+94 11 234 5678</span>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-envelope"></i>
                        <span>hr@techinnovations.lk</span>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-globe"></i>
                        <span>www.techinnovations.lk</span>
                    </div>

                    <div style="margin-top: 15px;">
                        <a href="#" class="btn btn-secondary" style="width: 100%;">
                            <i class="fas fa-eye"></i> View Company Profile
                        </a>
                    </div>
                </div>

                <!-- Similar Jobs -->
                <div class="sidebar-card">
                    <h3>Similar Jobs</h3>
                    
                    <div class="company-info-item">
                        <i class="fas fa-briefcase"></i>
                        <a href="#" style="color: #007BFF; text-decoration: none;">Frontend Developer</a>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-briefcase"></i>
                        <a href="#" style="color: #007BFF; text-decoration: none;">PHP Developer Intern</a>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-briefcase"></i>
                        <a href="#" style="color: #007BFF; text-decoration: none;">Full Stack Developer</a>
                    </div>

                    <div class="company-info-item">
                        <i class="fas fa-briefcase"></i>
                        <a href="#" style="color: #007BFF; text-decoration: none;">WordPress Developer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Save Job Button
        document.querySelector('.btn-outline').addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bookmark')) {
                icon.classList.remove('fa-bookmark');
                icon.classList.add('fa-check');
                this.innerHTML = '<i class="fas fa-check"></i> Saved';
                this.style.backgroundColor = '#28A745';
                this.style.color = '#FFFFFF';
                this.style.borderColor = '#28A745';
            } else {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-bookmark');
                this.innerHTML = '<i class="fas fa-bookmark"></i> Save Job';
                this.style.backgroundColor = 'transparent';
                this.style.color = '#007BFF';
                this.style.borderColor = '#007BFF';
            }
        });

        // Share buttons
        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Share functionality will be implemented with actual URLs');
            });
        });
    </script>

<?php include __DIR__ . '/../includes/footer.php'; ?>