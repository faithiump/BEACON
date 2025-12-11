<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Profile</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/sidebar.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">
    <style>
        /* Align content beside fixed sidebar */
        .dashboard-container {
            display: flex;
            padding-left: 260px;
            background: #f8fafc;
        }
        .dashboard-wrapper {
            flex: 1;
            min-height: 100vh;
            padding: 80px 24px 32px;
        }
        .dashboard-main {
            max-width: 1200px;
            margin: 0 auto;
        }
        @media (max-width: 992px) {
            .dashboard-container {
                padding-left: 0;
            }
            .dashboard-wrapper {
                padding: 80px 16px 24px;
            }
        }
        .profile-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; }
        .profile-card { display: flex; gap: 1rem; align-items: center; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 16px; background: #fff; }
        .profile-avatar-lg {
            width: 88px; height: 88px; border-radius: 999px;
            background: #0f172a; color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.9rem; overflow: hidden; text-transform: uppercase;
        }
        .profile-avatar-lg img { width: 100%; height: 100%; object-fit: cover; }
        .profile-meta { display: flex; flex-direction: column; gap: 0.35rem; }
        .profile-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; line-height: 1.1; }
        .profile-acronym { font-size: 0.95rem; font-weight: 700; color: #475569; }
        .badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.4rem 0.7rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600; }
        .badge.type { background: #eef2ff; color: #4338ca; }
        .badge.category { background: #ecfeff; color: #0e7490; }
        .badge.status { background: #ecfdf3; color: #166534; }
        .meta-row { display: flex; flex-direction: column; gap: 0.15rem; }
        .meta-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.02em; }
        .meta-value { font-size: 1rem; font-weight: 700; color: #0f172a; }
        .actions { margin-top: 1rem; }
        .btn-edit-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            color: #0f172a;
            background: #fff;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-edit-outline i { color: #64116e; }
        .btn-edit-outline:hover {
            border-color: #64116e;
            color: #64116e;
            box-shadow: 0 6px 18px rgba(100, 17, 110, 0.12);
            transform: translateY(-1px);
        }
        .section-card { padding: 1rem; border: 1px solid #e2e8f0; border-radius: 14px; background: #fff; }
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
                        <h2>Organization Profile</h2>
                    </div>
                    <div class="card-body">
                        <?php
                            $org = $organization ?? [];
                            $orgName = $org['organization_name'] ?? $org['name'] ?? 'Organization';
                            $orgAcronym = strtoupper($org['organization_acronym'] ?? $org['acronym'] ?? 'ORG');
                            $photoUrl = $photo ?? ($org['photo'] ?? null);
                            $initials = strtoupper(substr($orgAcronym, 0, 1) ?: substr($orgName, 0, 1) ?: 'O');
                        ?>
                        <div class="profile-card">
                            <div class="profile-avatar-lg">
                                <?php if (!empty($photoUrl)): ?>
                                    <img src="<?= esc($photoUrl) ?>" alt="Organization logo">
                                <?php else: ?>
                                    <?= esc($initials ?: $orgAcronym) ?>
                                <?php endif; ?>
                            </div>
                            <div class="profile-meta">
                                <div class="profile-name"><?= esc($orgName) ?></div>
                                <div class="profile-acronym"><?= esc($orgAcronym) ?></div>
                                <div class="profile-row" style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                                    <span class="badge type"><?= esc($org['type'] ?? '—') ?></span>
                                    <span class="badge category"><?= esc($org['category'] ?? '—') ?></span>
                                    <span class="badge status"><?= isset($org['status']) ? esc(ucfirst($org['status'])) : 'Active' ?></span>
                                </div>
                                <div class="actions">
                                    <a class="btn-edit-outline" href="<?= base_url('organization/profile/edit') ?>">
                                        <i class="fas fa-pen"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="profile-grid" style="margin-top:1.5rem;">
                            <div class="content-card" style="box-shadow:none; border:1px solid #e2e8f0;">
                                <div class="card-header" style="padding:0.75rem 0; border:none;">
                                    <h3>Contact</h3>
                                </div>
                                <div class="card-body" style="gap:0.75rem;">
                                    <div class="meta-row">
                                        <span class="meta-label">Email</span>
                                        <span class="meta-value"><?= esc($org['email'] ?? '—') ?></span>
                                    </div>
                                    <div class="meta-row">
                                        <span class="meta-label">Phone</span>
                                        <span class="meta-value"><?= esc($org['phone'] ?? '—') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="content-card" style="box-shadow:none; border:1px solid #e2e8f0;">
                                <div class="card-header" style="padding:0.75rem 0; border:none;">
                                    <h3>Details</h3>
                                </div>
                                <div class="card-body" style="gap:0.75rem;">
                                    <div class="meta-row">
                                        <span class="meta-label">Department</span>
                                        <span class="meta-value"><?= esc($org['department'] ?? '—') ?></span>
                                    </div>
                                    <div class="meta-row">
                                        <span class="meta-label">Advisor</span>
                                        <span class="meta-value"><?= esc($org['advisor_name'] ?? '—') ?></span>
                                    </div>
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

