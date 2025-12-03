<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BEACON | CSPC</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nav.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>" type="text/css">
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
    <?= view('components/nav', ['active' => 'login']) ?>

    <div class="auth-container">
        <!-- Left Side - Branding and Engagement Cards -->
        <div class="auth-left-section">
            <div class="auth-left-content">
                <div class="auth-branding">
                    <h1 class="auth-welcome-title">Your beacon of hope</h1>
                    <p class="auth-welcome-text">Access the unified platform for events, announcements, and campus organizations. Connect with your community and stay engaged.</p>
                </div>
                
                <!-- Website Information -->
                <div class="website-info">
                    <div class="info-section">
                        <h2 class="info-title">About BEACON</h2>
                        <p class="info-text">BEACON is CSPC's centralized hub for organization management, event visibility, and student engagement. Stay informed, collaborate seamlessly, and keep the pulse of campus life in one place.</p>
                    </div>
                    
                    <div class="info-features">
                        <div class="info-feature">
                            <h3 class="feature-title">Unified Platform</h3>
                            <p class="feature-text">All campus organizations, events, and announcements in one convenient location.</p>
                        </div>
                        <div class="info-feature">
                            <h3 class="feature-title">Real-time Updates</h3>
                            <p class="feature-text">Get instant notifications about upcoming events, important announcements, and organization activities.</p>
                        </div>
                        <div class="info-feature">
                            <h3 class="feature-title">Community Connection</h3>
                            <p class="feature-text">Connect with over 40+ student organizations and find your community on campus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="auth-form-container">
            <div class="auth-form-card">
                <h1 class="auth-title">Welcome to BEACON!</h1>
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('auth/login') ?>" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <div class="select-wrapper">
                            <select name="role" id="role" class="form-control" required>
                                <option value="">Select your role</option>
                                <option value="organization">Organization</option>
                                <option value="student">Student</option>
                            </select>
                            <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <div class="form-group role-dependent">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Select your role first" required>
                    </div>

                    <div class="form-group role-dependent">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn-primary">Log In</button>
                </form>

                <div class="divider">
                    <span>or continue with</span>
                </div>

                <button type="button" class="btn-google" onclick="window.location='<?= base_url('auth/googleLogin') ?>'">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.64 9.20454C17.64 8.56636 17.5827 7.95272 17.4764 7.36363H9V10.8449H13.8436C13.635 11.9699 13.0009 12.9231 12.0477 13.5613V15.8195H15.9564C17.1582 14.2527 17.64 12.2181 17.64 9.20454Z" fill="#4285F4"/>
                        <path d="M9 18C11.43 18 13.467 17.1941 14.9564 15.8195L12.0477 13.5613C11.2418 14.1013 10.2109 14.4204 9 14.4204C6.65454 14.4204 4.67181 12.8372 3.96409 10.71H0.957275V13.0418C2.43818 15.9831 5.48182 18 9 18Z" fill="#34A853"/>
                        <path d="M3.96409 10.71C3.78409 10.17 3.68182 9.59318 3.68182 9C3.68182 8.40681 3.78409 7.83 3.96409 7.29V4.95818H0.957273C0.347727 6.17318 0 7.54772 0 9C0 10.4523 0.347727 11.8268 0.957273 13.0418L3.96409 10.71Z" fill="#FBBC05"/>
                        <path d="M9 3.57955C10.3214 3.57955 11.5077 4.03364 12.4405 4.92545L15.0218 2.34409C13.4632 0.891818 11.4259 0 9 0C5.48182 0 2.43818 2.01682 0.957275 4.95818L3.96409 7.29C4.67181 5.16273 6.65454 3.57955 9 3.57955Z" fill="#EA4335"/>
                    </svg>
                    <span>Sign In with Google</span>
                </button>

                <p class="auth-footer">
                    Don't have an account? <a href="<?= base_url('auth/register') ?>">Sign up.</a>
                </p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/nav.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const roleFields = document.querySelectorAll('.role-dependent');
            const emailInput = document.getElementById('email');

            const placeholderMap = {
                organization: 'Organization email',
                student: 'Student email'
            };

            function toggleFields() {
                const role = roleSelect.value;
                roleFields.forEach(field => {
                    field.classList.toggle('visible', !!role);
                });

                if (role && placeholderMap[role]) {
                    emailInput.placeholder = placeholderMap[role];
                } else {
                    emailInput.placeholder = 'Select your role first';
                }
            }

            toggleFields();
            roleSelect.addEventListener('change', toggleFields);
        });
    </script>
</body>
</html>