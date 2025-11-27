<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BEACON | CSPC</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v2.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nav.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/register.css') ?>" type="text/css">
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
                                <label for="firstname">First Name <span style="color: red;">*</span></label>
                                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter your first name" required>
                            </div>
                            <div class="form-group form-group-half">
                                <label for="middlename">Middle Name <span style="color: red;">*</span></label>
                                <input type="text" name="middlename" id="middlename" class="form-control" placeholder="Enter your middle name" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lastname">Last Name <span style="color: red;">*</span></label>
                                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter your last name" required>
                            </div>
                            <div class="form-group">
                                <label for="birthday">Date of Birth <span style="color: red;">*</span></label>
                                <input type="date" name="birthday" id="birthday" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="gender">Gender <span style="color: red;">*</span></label>
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
                                <label for="phone">Phone Number <span style="color: red;">*</span></label>
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="position: relative;">
                                <label for="province">Province <span style="color: red;">*</span></label>
                                <input type="text" name="province" id="province" class="form-control" placeholder="Enter your province" required autocomplete="off">
                                <div id="province-suggestions" class="autocomplete-suggestions" style="display: none;"></div>
                            </div>
                            <div class="form-group" style="position: relative;">
                                <label for="city_municipality">City/Municipality <span style="color: red;">*</span></label>
                                <input type="text" name="city_municipality" id="city_municipality" class="form-control" placeholder="Enter your city or municipality" required autocomplete="off" disabled>
                                <div id="city-suggestions" class="autocomplete-suggestions" style="display: none;"></div>
                            </div>
                        </div>

                        <div class="form-group" style="position: relative;">
                            <label for="barangay">Barangay <span style="color: red;">*</span></label>
                            <input type="text" name="barangay" id="barangay" class="form-control" placeholder="Enter your barangay" required autocomplete="off" disabled>
                            <div id="barangay-suggestions" class="autocomplete-suggestions" style="display: none;"></div>
                        </div>
                    </div>

                    <!-- Student Information Section -->
                    <div class="form-section student-fields visible">
                        <h3 class="section-title">Student Information</h3>
                        
                        <div class="form-group">
                            <label for="student_id">Student ID <span style="color: red;">*</span></label>
                            <input type="text" name="student_id" id="student_id" class="form-control" placeholder="Enter your student ID" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="department">Department <span style="color: red;">*</span></label>
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
                            <label for="course">Course/Program <span style="color: red;">*</span></label>
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
                            <label for="year_level">Year Level <span style="color: red;">*</span></label>
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

                    </div>

                    <!-- Account Credentials Section -->
                    <div class="form-section role-dependent visible">
                        <h3 class="section-title">Account Credentials</h3>
                        
                        <div class="form-group">
                            <label for="email">Email <span style="color: red;">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Student email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password <span style="color: red;">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter a strong password (min. 8 characters)" required minlength="8">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password <span style="color: red;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm your password" required>
                                <label style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.875rem; color: #666;">
                                    <input type="checkbox" id="show_confirm_password" style="cursor: pointer;">
                                    <span>Show</span>
                                </label>
                            </div>
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

    <script src="<?= base_url('assets/js/nav.js') ?>"></script>
    <style>
        .form-group[style*="position: relative"] {
            position: relative !important;
        }
        .autocomplete-suggestions {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            max-height: 200px !important;
            overflow-y: auto !important;
            z-index: 9999 !important;
            margin-top: 4px !important;
            display: block !important;
        }
        .suggestion-item {
            padding: 0.75rem 1rem !important;
            cursor: pointer !important;
            border-bottom: 1px solid #f1f5f9 !important;
            transition: background-color 0.2s !important;
            font-size: 0.875rem !important;
            color: #1e293b !important;
        }
        .suggestion-item:hover {
            background-color: #f1f5f9 !important;
        }
        .suggestion-item:last-child {
            border-bottom: none !important;
        }
    </style>
    <script>
        const baseUrl = '<?= base_url() ?>';
        
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

        document.addEventListener('DOMContentLoaded', function() {
            // Autocomplete functionality for location fields
            let provinceTimeout, cityTimeout, barangayTimeout;
            let selectedProvince = '';
            let selectedCity = '';

            // Province autocomplete
            const provinceInput = document.getElementById('province');
            const provinceSuggestions = document.getElementById('province-suggestions');
            const cityInput = document.getElementById('city_municipality');
            const citySuggestions = document.getElementById('city-suggestions');
            const barangayInput = document.getElementById('barangay');
            const barangaySuggestions = document.getElementById('barangay-suggestions');

            if (provinceInput) {
            provinceInput.addEventListener('input', function() {
                clearTimeout(provinceTimeout);
                const query = this.value.trim();
                
                if (query.length < 1) {
                    provinceSuggestions.style.display = 'none';
                    cityInput.disabled = true;
                    cityInput.value = '';
                    barangayInput.disabled = true;
                    barangayInput.value = '';
                    selectedProvince = '';
                    selectedCity = '';
                    return;
                }

                provinceTimeout = setTimeout(() => {
                    fetch(baseUrl + 'locations/provinces?q=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                showSuggestions(provinceSuggestions, data, function(value) {
                                    provinceInput.value = value;
                                    selectedProvince = value;
                                    provinceSuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                    cityInput.disabled = false;
                                    cityInput.value = '';
                                    cityInput.focus();
                                    barangayInput.disabled = true;
                                    barangayInput.value = '';
                                    selectedCity = '';
                                }, function(allSuggestions) {
                                    showAllSuggestions(provinceSuggestions, allSuggestions, function(value) {
                                        provinceInput.value = value;
                                        selectedProvince = value;
                                        provinceSuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                        cityInput.disabled = false;
                                        cityInput.value = '';
                                        cityInput.focus();
                                        barangayInput.disabled = true;
                                        barangayInput.value = '';
                                        selectedCity = '';
                                    }, provinceInput);
                                });
                            } else {
                                provinceSuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching provinces:', error);
                            provinceSuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                        });
                }, 200);
            });

            provinceInput.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 1) {
                    fetch(baseUrl + 'locations/provinces?q=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                showSuggestions(provinceSuggestions, data, function(value) {
                                    provinceInput.value = value;
                                    selectedProvince = value;
                                    provinceSuggestions.style.display = 'none';
                                    cityInput.disabled = false;
                                    cityInput.value = '';
                                    cityInput.focus();
                                    barangayInput.disabled = true;
                                    barangayInput.value = '';
                                    selectedCity = '';
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching provinces:', error));
                }
            });

            provinceInput.addEventListener('blur', function(e) {
                // Don't hide if clicking on a suggestion
                setTimeout(() => {
                    if (!provinceSuggestions.contains(document.activeElement)) {
                        provinceSuggestions.style.display = 'none';
                    }
                }, 300);
            });
            
            // Keep suggestions visible when clicking on them
            provinceSuggestions.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        }

        // City/Municipality autocomplete
        if (cityInput) {
            cityInput.addEventListener('input', function() {
                if (!selectedProvince) {
                    this.value = '';
                    return;
                }

                clearTimeout(cityTimeout);
                const query = this.value.trim();
                
                if (query.length < 1) {
                    citySuggestions.style.display = 'none';
                    barangayInput.disabled = true;
                    barangayInput.value = '';
                    selectedCity = '';
                    return;
                }

                cityTimeout = setTimeout(() => {
                    fetch(baseUrl + 'locations/cities?q=' + encodeURIComponent(query) + '&province=' + encodeURIComponent(selectedProvince))
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                showSuggestions(citySuggestions, data, function(value) {
                                    cityInput.value = value;
                                    selectedCity = value;
                                    citySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                    barangayInput.disabled = false;
                                    barangayInput.value = '';
                                    barangayInput.focus();
                                }, function(allSuggestions) {
                                    showAllSuggestions(citySuggestions, allSuggestions, function(value) {
                                        cityInput.value = value;
                                        selectedCity = value;
                                        citySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                        barangayInput.disabled = false;
                                        barangayInput.value = '';
                                        barangayInput.focus();
                                    }, cityInput);
                                });
                            } else {
                                citySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                            citySuggestions.style.display = 'none';
                        });
                }, 200);
            });

            cityInput.addEventListener('focus', function() {
                if (!selectedProvince) return;
                const query = this.value.trim();
                if (query.length >= 1) {
                    fetch(baseUrl + 'locations/cities?q=' + encodeURIComponent(query) + '&province=' + encodeURIComponent(selectedProvince))
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                showSuggestions(citySuggestions, data, function(value) {
                                    cityInput.value = value;
                                    selectedCity = value;
                                    citySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                    barangayInput.disabled = false;
                                    barangayInput.value = '';
                                    barangayInput.focus();
                                }, function(allSuggestions) {
                                    showAllSuggestions(citySuggestions, allSuggestions, function(value) {
                                        cityInput.value = value;
                                        selectedCity = value;
                                        citySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                        barangayInput.disabled = false;
                                        barangayInput.value = '';
                                        barangayInput.focus();
                                    }, cityInput);
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching cities:', error));
                }
            });

            cityInput.addEventListener('blur', function(e) {
                // Don't hide if clicking on a suggestion
                setTimeout(() => {
                    if (!citySuggestions.contains(document.activeElement)) {
                        citySuggestions.style.display = 'none';
                    }
                }, 300);
            });
            
            // Keep suggestions visible when clicking on them
            citySuggestions.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        }

        // Barangay autocomplete
        if (barangayInput) {
            barangayInput.addEventListener('input', function() {
                if (!selectedProvince || !selectedCity) {
                    this.value = '';
                    return;
                }

                clearTimeout(barangayTimeout);
                const query = this.value.trim();
                
                if (query.length < 1) {
                    barangaySuggestions.style.display = 'none';
                    return;
                }

                barangayTimeout = setTimeout(() => {
                    fetch(baseUrl + 'locations/barangays?q=' + encodeURIComponent(query) + '&province=' + encodeURIComponent(selectedProvince) + '&city=' + encodeURIComponent(selectedCity))
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                showSuggestions(barangaySuggestions, data, function(value) {
                                    barangayInput.value = value;
                                    barangaySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                }, function(allSuggestions) {
                                    showAllSuggestions(barangaySuggestions, allSuggestions, function(value) {
                                        barangayInput.value = value;
                                        barangaySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                    }, barangayInput);
                                });
                            } else {
                                barangaySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching barangays:', error);
                            barangaySuggestions.style.display = 'none';
                        });
                }, 200);
            });

            barangayInput.addEventListener('focus', function() {
                if (!selectedProvince || !selectedCity) return;
                const query = this.value.trim();
                if (query.length >= 1) {
                    fetch(baseUrl + 'locations/barangays?q=' + encodeURIComponent(query) + '&province=' + encodeURIComponent(selectedProvince) + '&city=' + encodeURIComponent(selectedCity))
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                showSuggestions(barangaySuggestions, data, function(value) {
                                    barangayInput.value = value;
                                    barangaySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                }, function(allSuggestions) {
                                    showAllSuggestions(barangaySuggestions, allSuggestions, function(value) {
                                        barangayInput.value = value;
                                        barangaySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                                    }, barangayInput);
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching barangays:', error));
                }
            });

            barangayInput.addEventListener('blur', function(e) {
                // Don't hide if clicking on a suggestion
                setTimeout(() => {
                    if (!barangaySuggestions.contains(document.activeElement)) {
                        barangaySuggestions.style.display = 'none';
                    }
                }, 300);
            });
            
            // Keep suggestions visible when clicking on them
            barangaySuggestions.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            const isProvinceInput = provinceInput && (provinceInput.contains(e.target) || provinceSuggestions.contains(e.target));
            const isCityInput = cityInput && (cityInput.contains(e.target) || citySuggestions.contains(e.target));
            const isBarangayInput = barangayInput && (barangayInput.contains(e.target) || barangaySuggestions.contains(e.target));

            if (!isProvinceInput && provinceSuggestions) {
                provinceSuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
            }
            if (!isCityInput && citySuggestions) {
                citySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
            }
            if (!isBarangayInput && barangaySuggestions) {
                barangaySuggestions.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
            }
        });

        function showSuggestions(container, suggestions, onSelect, showAllCallback) {
            container.innerHTML = '';
            if (suggestions.length === 0) {
                container.style.display = 'none';
                return;
            }

            const displayCount = 10;
            const hasMore = suggestions.length > displayCount;
            const itemsToShow = hasMore ? suggestions.slice(0, displayCount) : suggestions;

            itemsToShow.forEach(suggestion => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.textContent = suggestion;
                item.style.cssText = 'padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; font-size: 0.875rem; color: #1e293b;';
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    onSelect(suggestion);
                    container.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                });
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                });
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f1f5f9';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
                container.appendChild(item);
            });

            // Show "View All" link if there are 10 or more suggestions
            if (suggestions.length >= 10 && showAllCallback) {
                const viewAllLink = document.createElement('div');
                viewAllLink.className = 'suggestion-item view-all-link';
                viewAllLink.innerHTML = '<a href="#" style="color: #3b82f6; text-decoration: none; font-weight: 500; display: block;">View All (' + suggestions.length + ' places)</a>';
                viewAllLink.style.cssText = 'padding: 0.75rem 1rem; cursor: pointer; border-top: 2px solid #e2e8f0; font-size: 0.875rem; text-align: center; background-color: #f8fafc;';
                viewAllLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (showAllCallback) {
                        showAllCallback(suggestions);
                    }
                });
                viewAllLink.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
                viewAllLink.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f1f5f9';
                });
                viewAllLink.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '#f8fafc';
                });
                container.appendChild(viewAllLink);
            }

            container.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 200px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: block !important;';
        }

        function showAllSuggestions(container, allSuggestions, onSelect, inputField) {
            if (!container || !allSuggestions || allSuggestions.length === 0) {
                return;
            }
            
            container.innerHTML = '';
            
            allSuggestions.forEach(suggestion => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.textContent = suggestion;
                item.style.cssText = 'padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; font-size: 0.875rem; color: #1e293b;';
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    onSelect(suggestion);
                    container.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 300px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: none !important;';
                });
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                });
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f1f5f9';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
                container.appendChild(item);
            });

            container.style.cssText = 'position: absolute !important; top: 100% !important; left: 0 !important; right: 0 !important; background: white !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important; max-height: 300px !important; overflow-y: auto !important; z-index: 9999 !important; margin-top: 4px !important; display: block !important;';
        }
        }); // End of DOMContentLoaded for autocomplete

        document.addEventListener('DOMContentLoaded', function () {
            // Handle show/hide confirm password
            const showConfirmPassword = document.getElementById('show_confirm_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            if (showConfirmPassword && confirmPasswordInput) {
                showConfirmPassword.addEventListener('change', function() {
                    if (this.checked) {
                        confirmPasswordInput.type = 'text';
                        this.parentElement.querySelector('span').textContent = 'Hide';
                    } else {
                        confirmPasswordInput.type = 'password';
                        this.parentElement.querySelector('span').textContent = 'Show';
                    }
                });
            }
        });
    </script>
</body>
</html>

