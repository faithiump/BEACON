<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/sidebar.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">
    <style>
        .profile-card { display: flex; gap: 1.5rem; align-items: center; }
        .profile-card .avatar {
            width: 72px; height: 72px; border-radius: 18px;
            background: #0f172a; color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1.6rem; overflow: hidden;
        }
        .profile-card .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-meta { display: flex; flex-direction: column; gap: 0.35rem; }
        .profile-name { font-size: 1.4rem; font-weight: 700; color: #0f172a; }
        .profile-row { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600; }
        .badge.role { background: #eef2ff; color: #4338ca; }
        .badge.status { background: #ecfdf3; color: #166534; }
        .badge.status.inactive { background: #fef2f2; color: #b91c1c; }
        .meta-label { font-size: 0.9rem; color: #475569; }
        .meta-value { font-weight: 600; color: #0f172a; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <h2>User Profile</h2>
                    </div>
                    <div class="card-body">
                        <?php
                            $initials = '';
                            if (!empty($user['name'])) {
                                $parts = explode(' ', $user['name']);
                                $first = $parts[0] ?? '';
                                $last = $parts[count($parts) - 1] ?? '';
                                $initials = strtoupper(substr($first, 0, 1) . substr($last, 0, 1));
                            }
                        ?>
                        <div class="profile-card">
                            <div class="avatar">
                                <?php if (!empty($photo)): ?>
                                    <img src="<?= esc($photo) ?>" alt="Profile photo">
                                <?php else: ?>
                                    <?= esc($initials ?: 'US') ?>
                                <?php endif; ?>
                            </div>
                            <div class="profile-meta">
                                <div class="profile-name"><?= esc($user['name'] ?? 'User') ?></div>
                                <div class="profile-row">
                                    <span class="badge role"><i class="fas fa-user"></i><?= esc(ucfirst($user['role'] ?? '')) ?></span>
                                    <span class="badge status <?= ($user['status'] ?? '') === 'active' ? '' : 'inactive' ?>">
                                        <?= ucfirst($user['status'] ?? 'inactive') ?>
                                    </span>
                                </div>
                                <div class="profile-row">
                                    <span class="meta-label">Email:</span>
                                    <span class="meta-value"><?= esc($user['email'] ?? '') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

