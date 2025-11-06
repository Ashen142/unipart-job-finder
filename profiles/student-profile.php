<?php
// Include backend setup
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/functions.php';

// Page settings
$page_title = "student-profile to UniPart";
$extraCSS = ['/Unipart-job-finder/assets/css/profiles.css'];
$body_class = 'student-profile-page';
$page_type = 'student';

// Include header
include __DIR__ . '/../includes/header.php';
?>

<!-- Main Container -->
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>My Profile</h1>
        </div>


        <!-- Profile Layout -->
        <div class="profile-layout">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">WS</div>
                    <h3 class="profile-name">Wimansa Samudinee</h3>
                    <p class="profile-role">Software Engineering Student</p>
                    <button class="upload-btn">
                        <i class="fas fa-camera"></i> Change Photo
                    </button>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total Applications</span>
                        <span class="stat-value">12</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Jobs Completed</span>
                        <span class="stat-value">5</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Pending Applications</span>
                        <span class="stat-value">3</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Member Since</span>
                        <span class="stat-value">Jan 2024</span>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <!-- Personal Information -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2 class="card-title">Personal Information</h2>
                        <button class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>

                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-input" value="Wimansa Samudinee" placeholder="Enter your full name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-input" value="wimansasamudinee@gmail.com" placeholder="Enter your email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-input" value="+94 77 123 4567" placeholder="Enter phone number">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-input" value="FC115501" placeholder="Enter student ID">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Department</label>
                                <select class="form-select">
                                    <option>Software Engineering</option>
                                    <option>Computer Science</option>
                                    <option>Business Administration</option>
                                    <option>Arts & Humanities</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Year of Study</label>
                                <select class="form-select">
                                    <option>1st Year</option>
                                    <option selected>2nd Year</option>
                                    <option>3rd Year</option>
                                    <option>4th Year</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-input" value="123 Main Street, Mirigama" placeholder="Enter your address">
                        </div>

                        <div class="form-group">
                            <label class="form-label">About Me</label>
                            <textarea class="form-textarea" placeholder="Tell employers about yourself...">Motivated Software Engineering student with a passion for web development and problem-solving. Looking for part-time opportunities to gain practical experience while completing my degree.</textarea>
                        </div>
                    </form>
                </div>

                <!-- Skills Section -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2 class="card-title">Skills & Expertise</h2>
                        <button class="edit-btn">
                            <i class="fas fa-plus"></i> Add Skill
                        </button>
                    </div>

                    <div class="skills-container">
                        <div class="skill-tag">
                            PHP <i class="fas fa-times"></i>
                        </div>
                        <div class="skill-tag">
                            JavaScript <i class="fas fa-times"></i>
                        </div>
                        <div class="skill-tag">
                            MySQL <i class="fas fa-times"></i>
                        </div>
                        <div class="skill-tag">
                            HTML/CSS <i class="fas fa-times"></i>
                        </div>
                        <div class="skill-tag">
                            React <i class="fas fa-times"></i>
                        </div>
                        <div class="skill-tag">
                            Python <i class="fas fa-times"></i>
                        </div>
                        <div class="skill-tag">
                            Git <i class="fas fa-times"></i>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label class="form-label">Add New Skill</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" class="form-input" placeholder="Enter skill name">
                            <button class="add-skill-btn">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resume Section -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2 class="card-title">Resume / CV</h2>
                        <button class="edit-btn">
                            <i class="fas fa-upload"></i> Upload New
                        </button>
                    </div>

                    <div class="resume-item">
                        <div class="resume-info">
                            <i class="fas fa-file-pdf resume-icon"></i>
                            <div class="resume-details">
                                <h4>Wimansa_Samudinee_Resume_2024.pdf</h4>
                                <p>Uploaded on March 15, 2024 • 245 KB</p>
                            </div>
                        </div>
                        <div class="resume-actions">
                            <button class="btn-small btn-view">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn-small btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save Changes -->
                <div class="save-section">
                    <button class="save-btn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>