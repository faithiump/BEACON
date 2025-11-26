<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BEACON | CSPC</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v2.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nav.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/register.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="stars-container">
        <!-- Flares -->
        <div class="flare blue" style="width: 200px; height: 200px; top: 10%; left: 5%; animation-delay: 0s;"></div>
        <div class="flare purple" style="width: 250px; height: 250px; top: 60%; right: 8%; animation-delay: 3s;"></div>
        <div class="flare yellow" style="width: 180px; height: 180px; bottom: 15%; left: 15%; animation-delay: 6s;"></div>
        <div class="flare blue" style="width: 220px; height: 220px; top: 30%; right: 20%; animation-delay: 9s;"></div>
        
        <!-- Stars -->
        <div class="star small" style="top: 15%; left: 10%; animation-delay: 0s;"></div>
        <div class="star medium" style="top: 20%; left: 25%; animation-delay: 0.5s;"></div>
        <div class="star small" style="top: 12%; left: 40%; animation-delay: 1s;"></div>
        <div class="star large" style="top: 25%; left: 55%; animation-delay: 1.5s;"></div>
        <div class="star medium" style="top: 18%; left: 70%; animation-delay: 2s;"></div>
        <div class="star small" style="top: 22%; left: 85%; animation-delay: 2.5s;"></div>
        
        <div class="star medium" style="top: 35%; left: 8%; animation-delay: 0.3s;"></div>
        <div class="star small" style="top: 40%; left: 22%; animation-delay: 0.8s;"></div>
        <div class="star large" style="top: 38%; left: 38%; animation-delay: 1.3s;"></div>
        <div class="star small" style="top: 42%; left: 52%; animation-delay: 1.8s;"></div>
        <div class="star medium" style="top: 36%; left: 68%; animation-delay: 2.3s;"></div>
        <div class="star small" style="top: 39%; left: 82%; animation-delay: 2.8s;"></div>
        
        <div class="star small" style="top: 55%; left: 12%; animation-delay: 0.2s;"></div>
        <div class="star medium" style="top: 58%; left: 28%; animation-delay: 0.7s;"></div>
        <div class="star large" style="top: 56%; left: 45%; animation-delay: 1.2s;"></div>
        <div class="star small" style="top: 60%; left: 60%; animation-delay: 1.7s;"></div>
        <div class="star medium" style="top: 57%; left: 75%; animation-delay: 2.2s;"></div>
        <div class="star small" style="top: 59%; left: 90%; animation-delay: 2.7s;"></div>
        
        <div class="star medium" style="top: 72%; left: 5%; animation-delay: 0.4s;"></div>
        <div class="star small" style="top: 75%; left: 18%; animation-delay: 0.9s;"></div>
        <div class="star large" style="top: 73%; left: 32%; animation-delay: 1.4s;"></div>
        <div class="star small" style="top: 77%; left: 48%; animation-delay: 1.9s;"></div>
        <div class="star medium" style="top: 74%; left: 63%; animation-delay: 2.4s;"></div>
        <div class="star small" style="top: 76%; left: 78%; animation-delay: 2.9s;"></div>
        
        <div class="star small" style="top: 88%; left: 15%; animation-delay: 0.6s;"></div>
        <div class="star medium" style="top: 91%; left: 30%; animation-delay: 1.1s;"></div>
        <div class="star small" style="top: 89%; left: 50%; animation-delay: 1.6s;"></div>
        <div class="star large" style="top: 93%; left: 65%; animation-delay: 2.1s;"></div>
        <div class="star small" style="top: 90%; left: 80%; animation-delay: 2.6s;"></div>
    </div>
    
    <!-- Navigation Panel -->
    <?= view('components/nav', ['active' => 'register']) ?>

    <div class="auth-container">
        <!-- Left Side - Register Form -->
        <div class="auth-form-container">
            <div class="auth-form-card">
                <h1 class="auth-title">Create Account!</h1>
                <p class="auth-subtitle">Join the CSPC community and access events, announcements, and campus organizations.</p>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php if (is_array(session()->getFlashdata('errors'))): ?>
                            <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <?= esc(session()->getFlashdata('errors')) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('auth/register') ?>" method="POST" class="auth-form">
                    <!-- Hidden role field - automatically set to student -->
                    <input type="hidden" name="role" id="role" value="student">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3 class="section-title">Personal Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter your first name" required>
                            </div>
                            <div class="form-group form-group-half">
                                <label for="middlename">Middle Name</label>
                                <input type="text" name="middlename" id="middlename" class="form-control" placeholder="Enter your middle name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter your last name" required>
                        </div>

                        <div class="form-group">
                            <label for="birthday">Date of Birth</label>
                            <input type="date" name="birthday" id="birthday" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <div class="select-wrapper">
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                </select>
                                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required>
                        </div>

                        <div class="form-group">
                            <label for="region">Region</label>
                            <input type="text" name="region" id="region" class="form-control" placeholder="Enter your region" required>
                        </div>

                        <div class="form-group">
                            <label for="city_municipality">City/Municipality</label>
                            <input type="text" name="city_municipality" id="city_municipality" class="form-control" placeholder="Enter your city or municipality" required>
                        </div>

                        <div class="form-group">
                            <label for="barangay">Barangay</label>
                            <input type="text" name="barangay" id="barangay" class="form-control" placeholder="Enter your barangay" required>
                        </div>
                    </div>

                    <!-- Student Information Section -->
                    <div class="form-section student-fields visible">
                        <h3 class="section-title">Student Information</h3>
                        
                        <div class="form-group">
                            <label for="student_id">Student ID</label>
                            <input type="text" name="student_id" id="student_id" class="form-control" placeholder="Enter your student ID" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="department">Department</label>
                            <div class="select-wrapper">
                                <select id="department" name="department" class="form-control" required onchange="updateCourses()">
                                    <option value="">Select your department</option>
                                    <option value="ccs">College of Computer Studies</option>
                                    <option value="cea">College of Engineering and Architecture</option>
                                    <option value="cthbm">College of Tourism, Hospitality, and Business Management</option>
                                    <option value="chs">College of Health Sciences</option>
                                    <option value="ctde">College of Technological and Developmental Education</option>
                                    <option value="cas">College of Arts and Sciences</option>
                                    <option value="gs">Graduate School</option>
                                </select>
                                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="course">Course/Program</label>
                            <div class="select-wrapper">
                                <select name="course" id="course" class="form-control" required disabled>
                                    <option value="">Select your department first</option>
                                </select>
                                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="year_level">Year Level</label>
                            <div class="select-wrapper">
                                <select name="year_level" id="year_level" class="form-control" required disabled>
                                    <option value="">Select your department first</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                    <option value="5">5th Year</option>
                                </select>
                                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="in_organization">Are you part of an organization?</label>
                            <div class="select-wrapper">
                                <select name="in_organization" id="in_organization" class="form-control" required>
                                    <option value="">Select an option</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group" id="organization_name_group" style="display: none;">
                            <label for="organization_name">Organization Name</label>
                            <input type="text" name="organization_name" id="organization_name" class="form-control" placeholder="Enter your organization name">
                        </div>
                    </div>

                    <!-- Account Credentials Section -->
                    <div class="form-section role-dependent visible">
                        <h3 class="section-title">Account Credentials</h3>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Student email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter a strong password (min. 8 characters)" required minlength="8">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm your password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Sign Up</button>
                </form>

                <p class="auth-footer">
                    Already have an account? <a href="<?= base_url('auth/login') ?>">Log in.</a>
                </p>
            </div>
        </div>

    </div>

    <?= view('components/footer') ?>

    <script>
        // Course options for each department
        const departmentCourses = {
            ccs: [
                { value: 'bsit', label: 'Bachelor of Science in Information Technology' },
                { value: 'bscs', label: 'Bachelor of Science in Computer Science' },
                { value: 'bsis', label: 'Bachelor of Science in Information Systems' },
                { value: 'blis', label: 'Bachelor of Library Information Science' }
            ],
            cea: [
                { value: 'bsce', label: 'Bachelor of Science in Civil Engineering' },
                { value: 'bsme', label: 'Bachelor of Science in Mechanical Engineering' },
                { value: 'bsece', label: 'Bachelor of Science in Electronics Engineering' },
                { value: 'bsee', label: 'Bachelor of Science in Electrical Engineering' }
            ],
            cthbm: [
                { value: 'bsoa', label: 'Bachelor of Science in Office Administration' },
                { value: 'bstm', label: 'Bachelor of Science in Tourism Management' },
                { value: 'bsem', label: 'Bachelor of Science in Entrepreneurial Management' },
                { value: 'bshm', label: 'Bachelor of Science in Hospitality Management' }
            ],
            chs: [
                { value: 'bsn', label: 'Bachelor of Science in Nursing' },
                { value: 'bsm', label: 'Bachelor of Science in Midwifery' }
            ],
            ctde: [
                { value: 'bped', label: 'Bachelor of Physical Education' },
                { value: 'bcaed', label: 'Bachelor of Culture and Arts Education' },
                { value: 'bsne', label: 'Bachelor of Special Needs Education' },
                { value: 'btvted', label: 'Bachelor of Technological Vocational Teacher Education' }
            ],
            cas: [
                { value: 'baels', label: 'Bachelor of Arts in English Language Studies' },
                { value: 'bsmath', label: 'Bachelor of Science in Mathematics' },
                { value: 'bsam', label: 'Bachelor of Science in Applied Mathematics' },
                { value: 'bsdc', label: 'Bachelor of Science in Development Communication' },
                { value: 'bspa', label: 'Bachelor of Science in Public Administration' },
                { value: 'bshs', label: 'Bachelor in Human Services' }
            ],
            gs: [
                { value: 'dpbm', label: 'Doctor of Philosophy in Business Management' },
                { value: 'man', label: 'Master of Arts in Nursing' },
                { value: 'mbm', label: 'Master in Business Management' },
                { value: 'moe', label: 'Master of Engineering' }
            ]
        };

        function updateCourses() {
            const departmentSelect = document.getElementById('department');
            const courseSelect = document.getElementById('course');
            const yearLevelSelect = document.getElementById('year_level');
            const selectedDepartment = departmentSelect.value;

            if (!selectedDepartment) {
                // No department selected - disable both fields
                courseSelect.disabled = true;
                courseSelect.innerHTML = '<option value="">Select your department first</option>';
                courseSelect.required = false;
                
                yearLevelSelect.disabled = true;
                yearLevelSelect.value = '';
                yearLevelSelect.required = false;
                return;
            }

            // Enable course and year level fields
            courseSelect.disabled = false;
            courseSelect.required = true;
            yearLevelSelect.disabled = false;
            yearLevelSelect.required = true;

            // Clear existing course options
            courseSelect.innerHTML = '<option value="">Select your course</option>';

            // Reset year level when department changes
            yearLevelSelect.value = '';

            // Add courses based on selected department
            if (departmentCourses[selectedDepartment]) {
                departmentCourses[selectedDepartment].forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.value;
                    option.textContent = course.label;
                    courseSelect.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const inOrganizationSelect = document.getElementById('in_organization');
            const organizationNameGroup = document.getElementById('organization_name_group');

            // Handle organization membership for students
            if (inOrganizationSelect) {
                inOrganizationSelect.addEventListener('change', function() {
                    if (this.value === 'yes') {
                        organizationNameGroup.style.display = 'block';
                        document.getElementById('organization_name').required = true;
                    } else {
                        organizationNameGroup.style.display = 'none';
                        document.getElementById('organization_name').required = false;
                        document.getElementById('organization_name').value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>

