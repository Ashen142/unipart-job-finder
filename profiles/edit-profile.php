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
 <!-- Profile Type Switcher -->
    <div class="profile-switcher">
        <div class="switch-buttons">
            <button class="switch-btn active" onclick="switchProfile('student')">
                <i class="fas fa-graduation-cap"></i> Student Profile
            </button>
            <button class="switch-btn" onclick="switchProfile('employer')">
                <i class="fas fa-building"></i> Employer Profile
            </button>
        </div>
    </div>

    <!-- Student Profile -->
    <div class="container profile-view active" id="student-profile">
        <div class="profile-card">
            <!-- Student Header -->
            <div class="profile-head student">
                <div class="profile-avataar student">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>Edit Student Profile</h1>
                <p class="profile-role">Update your academic and personal information</p>
            </div>

            <!-- Student Form -->
            <div class="form-container">
                <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="role" value="student">

                    <!-- Personal Information -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-user-edit"></i> Personal Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="student_name">Full Name <span class="required">*</span></label>
                                <input type="text" id="student_name" name="name"  required>
                            </div>
                            <div class="form-group">
                                <label for="student_email">Email Address <span class="required">*</span></label>
                                <input type="email" id="student_email" name="email"  required>
                            </div>
                            <div class="form-group">
                                <label for="student_phone">Phone Number</label>
                                <input type="tel" id="student_phone" name="phone"  placeholder="+94771234567">
                            </div>
                            <div class="form-group">
                                <label for="student_location">Location</label>
                                <input type="text" id="student_location" name="location"  placeholder="City, Province">
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> Academic Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="department">Department/Major <span class="required">*</span></label>
                                <select id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Engineering" selected>Software Engineering</option>
                                    <option value="Computer Science" >Computer Science</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Business Administration">Business Administration</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Physics">Physics</option>
                                    <option value="Biology">Biology</option>
                                    <option value="Economics">Economics</option>
                                    <option value="Psychology">Psychology</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Year of Study <span class="required">*</span></label>
                                <select id="year" name="year" required>
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2" selected>2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                    <option value="graduate">Graduate</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="student_id">Student ID</label>
                                <input type="text" id="student_id" name="student_id"  placeholder="Your student ID">
                            </div>
                            <div class="form-group">
                                <label for="gpa">GPA/CGPA</label>
                                <input type="text" id="gpa" name="gpa"  placeholder="e.g., 3.75">
                            </div>
                        </div>
                    </div>

                    <!-- Skills & Experience -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-tools"></i> Skills & Experience</h2>
                        <div class="form-group">
                            <label for="skills">Skills (comma separated)</label>
                            <textarea id="skills" name="skills" placeholder="e.g., Python, JavaScript, Web Development, Data Analysis">Python, JavaScript, Web Development, UI/UX Design, SQL, Git</textarea>
                            <p class="file-info">List your technical and soft skills</p>
                        </div>
                        <div class="form-group">
                            <label for="experience">Work Experience (if any)</label>
                            <textarea id="experience" name="experience" placeholder="Describe any previous work experience, internships, or projects">Completed a 3-month internship at Tech Solutions as a Junior Developer. Worked on e-commerce website development using PHP and MySQL.</textarea>
                        </div>
                    </div>

                    <!-- Resume Upload -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-file-pdf"></i> Resume/CV</h2>
                        <div class="form-group">
                            <label for="resume">Upload Resume (PDF only)</label>
                            <input type="file" id="resume" name="resume" accept=".pdf">
                            <p class="file-info">Maximum file size: 5MB | Supported format: PDF</p>
                            <div class="current-file">
                                <i class="fas fa-file-pdf"></i>
                                <span>Current: <a href="uploads/resumes/john_doe_resume.pdf" target="_blank">wimansa_samudinee_resume.pdf</a></span>
                            </div>
                        </div>
                    </div>

                    <!-- About Me -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-info-circle"></i> About Me</h2>
                        <div class="form-group">
                            <label for="student_bio">Bio / Personal Statement</label>
                            <textarea id="student_bio" name="bio" placeholder="Tell employers about yourself, your goals, and what you're looking for...">Passionate computer science student with a strong interest in web development and data analytics. Looking for part-time opportunities to apply my skills and gain real-world experience while completing my degree.</textarea>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-link"></i> Social Links</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="linkedin">LinkedIn Profile</label>
                                <input type="url" id="linkedin" name="linkedin"  placeholder="https://linkedin.com/in/yourprofile">
                            </div>
                            <div class="form-group">
                                <label for="github">GitHub Profile</label>
                                <input type="url" id="github" name="github"  placeholder="https://github.com/yourusername">
                            </div>
                            <div class="form-group">
                                <label for="portfolio">Portfolio Website</label>
                                <input type="url" id="portfolio" name="portfolio" placeholder="https://yourportfolio.com">
                            </div>
                        </div>
                    </div>

                   
                    <!-- Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="profiles/student-profile.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Employer Profile -->
    <div class="container profile-view" id="employer-profile">
        <div class="profile-card">
            <!-- Employer Header -->
            <div class="profile-head employer">
                <div class="profile-avataar employer">
                    <i class="fas fa-building"></i>
                </div>
                <h1>Edit Employer Profile</h1>
                <p class="profile-role">Update your company information and details</p>
            </div>

            <!-- Employer Form -->
            <div class="form-container">
                <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="role" value="employer">

                    <!-- Contact Person Information -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-user-tie"></i> Contact Person Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="employer_name">Contact Person Name <span class="required">*</span></label>
                                <input type="text" id="employer_name" name="name"  required>
                            </div>
                            <div class="form-group">
                                <label for="employer_email">Email Address <span class="required">*</span></label>
                                <input type="email" id="employer_email" name="email"  required>
                            </div>
                            <div class="form-group">
                                <label for="employer_phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="employer_phone" name="phone"  placeholder="+94112345678" required>
                            </div>
                            <div class="form-group">
                                <label for="position">Position/Title</label>
                                <input type="text" id="position" name="position"  placeholder="e.g., HR Manager, Recruiter">
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-building"></i> Company Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="company_name">Company Name <span class="required">*</span></label>
                                <input type="text" id="company_name" name="company_name" value="TechCorp Solutions" required>
                            </div>
                            <div class="form-group">
                                <label for="industry">Industry <span class="required">*</span></label>
                                <select id="industry" name="industry" required>
                                    <option value="">Select Industry</option>
                                    <option value="Technology" selected>Technology</option>
                                    <option value="Finance">Finance & Banking</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                    <option value="Retail">Retail & E-commerce</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Hospitality">Hospitality & Tourism</option>
                                    <option value="Marketing">Marketing & Advertising</option>
                                    <option value="Real Estate">Real Estate</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="company_size">Company Size</label>
                                <select id="company_size" name="company_size">
                                    <option value="">Select Size</option>
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50" selected>11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-500">201-500 employees</option>
                                    <option value="501+">501+ employees</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="established_year">Established Year</label>
                                <input type="text" id="established_year" name="established_year"  placeholder="e.g., 2015">
                            </div>
                            <div class="form-group full-width">
                                <label for="company_website">Company Website</label>
                                <input type="url" id="company_website" name="company_website"  placeholder="https://www.yourcompany.com">
                            </div>
                        </div>
                    </div>

                    <!-- Company Address -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Company Address</h2>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="address">Street Address <span class="required">*</span></label>
                                <input type="text" id="address" name="address"  required>
                            </div>
                            <div class="form-group">
                                <label for="city">City <span class="required">*</span></label>
                                <input type="text" id="city" name="city"  required>
                            </div>
                            <div class="form-group">
                                <label for="province">Province/State</label>
                                <input type="text" id="province" name="province" >
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" >
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" >
                            </div>
                        </div>
                    </div>

                    <!-- Company Description -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-info-circle"></i> Company Description</h2>
                        <div class="form-group">
                            <label for="company_description">About Your Company <span class="required">*</span></label>
                            <textarea id="company_description" name="company_description" required placeholder="Describe your company, what you do, your mission, and what makes you unique...">TechCorp Solutions is a leading software development company specializing in web and mobile applications. We provide innovative technology solutions to businesses across various industries. Our team is dedicated to creating cutting-edge products that solve real-world problems.</textarea>
                            <p class="file-info">This will be visible to students when viewing your job posts</p>
                        </div>
                    </div>

                    <!-- Company Logo -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-image"></i> Company Logo</h2>
                        <div class="form-group">
                            <label for="logo">Upload Company Logo</label>
                            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg">
                            <p class="file-info">Maximum file size: 2MB | Supported formats: JPG, PNG</p>
                            <div class="current-file">
                                <i class="fas fa-image"></i>
                                <span>Current: <a href="uploads/logos/techcorp_logo.png" target="_blank">techcorp_logo.png</a></span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-share-alt"></i> Social Media & Links</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="employer_linkedin">LinkedIn Page</label>
                                <input type="url" id="employer_linkedin" name="linkedin"  placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                            <div class="form-group">
                                <label for="facebook">Facebook Page</label>
                                <input type="url" id="facebook" name="facebook" placeholder="https://facebook.com/yourcompany">
                            </div>
                            <div class="form-group">
                                <label for="twitter">Twitter/X Handle</label>
                                <input type="url" id="twitter" name="twitter" placeholder="https://twitter.com/yourcompany">
                            </div>
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <input type="url" id="instagram" name="instagram" placeholder="https://instagram.com/yourcompany">
                            </div>
                        </div>
                    </div>

                    <!-- Verification Documents (Optional) -->
                    <div class="form-section">
                        <h2 class="section-title"><i class="fas fa-certificate"></i> Verification Documents</h2>
                        <div class="form-group">
                            <label for="business_registration">Business Registration Document</label>
                            <input type="file" id="business_registration" name="business_registration" accept=".pdf">
                            <p class="file-info">Upload company registration certificate for verification (Optional)</p>
                        </div>
                    </div>

                    

                    <!-- Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="profiles/employer-profile.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Switch between student and employer profiles
        function switchProfile(profileType) {
            const studentProfile = document.getElementById('student-profile');
            const employerProfile = document.getElementById('employer-profile');
            const buttons = document.querySelectorAll('.switch-btn');

            if (profileType === 'student') {
                studentProfile.classList.add('active');
                employerProfile.classList.remove('active');
                buttons[0].classList.add('active');
                buttons[1].classList.remove('active');
            } else {
                studentProfile.classList.remove('active');
                employerProfile.classList.add('active');
                buttons[0].classList.remove('active');
                buttons[1].classList.add('active');
            }
        }

        // Form validation for student profile
        document.querySelector('#student-profile form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('student_new_password').value;
            const confirmPassword = document.getElementById('student_confirm_password').value;
            
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match!');
                return false;
            }

            if (newPassword && newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });

        // Form validation for employer profile
        document.querySelector('#employer-profile form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('employer_new_password').value;
            const confirmPassword = document.getElementById('employer_confirm_password').value;
            
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match!');
                return false;
            }

            if (newPassword && newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });

        // File upload validation
        function validateFileUpload(input, maxSizeMB, allowedTypes) {
            const file = input.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // Convert to MB
                const fileType = file.type;
                
                if (fileSize > maxSizeMB) {
                    alert(`File size must be less than ${maxSizeMB}MB`);
                    input.value = '';
                    return false;
                }
                
                if (allowedTypes && !allowedTypes.includes(fileType)) {
                    alert('Invalid file type. Please upload the correct format.');
                    input.value = '';
                    return false;
                }
            }
            return true;
        }

        // Resume validation (5MB, PDF only)
        document.getElementById('resume')?.addEventListener('change', function() {
            validateFileUpload(this, 5, ['application/pdf']);
        });

        // Logo validation (2MB, images only)
        document.getElementById('logo')?.addEventListener('change', function() {
            validateFileUpload(this, 2, ['image/jpeg', 'image/png', 'image/jpg']);
        });

        // Business registration validation (5MB, PDF only)
        document.getElementById('business_registration')?.addEventListener('change', function() {
            validateFileUpload(this, 5, ['application/pdf']);
        });
    </script>

<?php include __DIR__ . '/../includes/footer.php'; ?>