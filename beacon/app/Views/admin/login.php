<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BEACON</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>" type="text/css">
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
        <div class="star large" style="top: 38%; left: 45%; animation-delay: 1.2s;"></div>
        <div class="star medium" style="top: 42%; left: 65%; animation-delay: 1.7s;"></div>
        <div class="star small" style="top: 45%; left: 80%; animation-delay: 2.2s;"></div>
        
        <div class="star small" style="top: 60%; left: 12%; animation-delay: 0.4s;"></div>
        <div class="star large" style="top: 65%; left: 30%; animation-delay: 0.9s;"></div>
        <div class="star medium" style="top: 62%; left: 50%; animation-delay: 1.4s;"></div>
        <div class="star small" style="top: 68%; left: 72%; animation-delay: 1.9s;"></div>
        <div class="star medium" style="top: 70%; left: 88%; animation-delay: 2.4s;"></div>
        
        <div class="star medium" style="top: 80%; left: 15%; animation-delay: 0.6s;"></div>
        <div class="star small" style="top: 85%; left: 35%; animation-delay: 1.1s;"></div>
        <div class="star large" style="top: 82%; left: 55%; animation-delay: 1.6s;"></div>
        <div class="star medium" style="top: 88%; left: 75%; animation-delay: 2.1s;"></div>
    </div>
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-left-section">
            <div class="auth-left-content">
                <div class="auth-branding">
                    <div class="admin-logo-container">
                        <img src="<?= base_url('assets/images/beacon-logo-v3.png') ?>" alt="BEACON Logo" class="admin-main-logo">
                    </div>
                    <h1 class="auth-welcome-title">WELCOME BACK<br>BEACON ADMIN</h1>
                    <p class="auth-welcome-text">Manage campus organizations, events, and student engagement from one centralized platform.</p>
                </div>
                
                <div class="admin-logos-footer">
                    <img src="<?= base_url('assets/images/cspc-logo.png') ?>" alt="CSPC Logo" class="admin-footer-logo">
                    <img src="<?= base_url('assets/images/cspc-ccs-logo.png') ?>" alt="CSPC CCS Logo" class="admin-footer-logo">
                </div>
            </div>
        </div>
        
        <!-- Right Side - Admin Login Form -->
        <div class="auth-form-container">
            <div class="auth-form-card admin-card">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('admin/login') ?>" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn-primary">Log In</button>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
