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
    <div class="auth-container">
        <div class="auth-form-container">
            <div class="auth-form-card admin-card">
                <div class="auth-header">
                    <div class="logo-placeholder">
                        <span class="logo-text">BEACONS <strong>ADMIN</strong></span>
                    </div>
                    <p class="logo-subtitle">Administrator Portal</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
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
