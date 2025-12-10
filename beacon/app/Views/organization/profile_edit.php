<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Organization Profile</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/sidebar.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">
    <style>
        .profile-edit-header { display:flex; align-items:center; gap:1rem; padding:1rem; border:1px solid #e2e8f0; border-radius: 16px; background:#fff; margin-bottom:1rem; }
        .avatar-preview {
            width: 72px; height: 72px; border-radius: 999px;
            background: #0f172a; color:#fff; display:flex; align-items:center; justify-content:center;
            font-weight: 800; font-size: 1.5rem; text-transform: uppercase; overflow:hidden;
        }
        .avatar-preview img { width:100%; height:100%; object-fit: cover; }
        .profile-edit-header .meta { display:flex; flex-direction:column; gap:0.25rem; }
        .profile-edit-header .name { font-size:1.1rem; font-weight:800; color:#0f172a; }
        .profile-edit-header .hint { font-size:0.9rem; color:#475569; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap: 1rem 1.25rem; }
        .form-group label { font-weight: 700; color: #0f172a; margin-bottom: 0.35rem; display: block; }
        .form-group input { width: 100%; padding: 0.65rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; font-size: 0.95rem; }
        .form-group input:focus { outline: none; border-color: #94a3b8; box-shadow: 0 0 0 3px rgba(148,163,184,0.25); }
        .actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; }
        .btn.secondary { background: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; }
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
                        <h2>Edit Organization Profile</h2>
                    </div>
                    <div class="card-body">
                        <?php
                            $org = $organization ?? [];
                            $orgName = $org['organization_name'] ?? $org['name'] ?? '';
                            $orgAcronym = $org['organization_acronym'] ?? $org['acronym'] ?? '';
                            $email = $org['email'] ?? '';
                            $phone = $org['phone'] ?? '';
                            $initial = strtoupper(substr($orgAcronym ?: $orgName, 0, 1) ?: 'O');
                        ?>
                        <div class="profile-edit-header">
                            <div class="avatar-preview">
                                <?= esc($initial) ?>
                            </div>
                            <div class="meta">
                                <div class="name"><?= esc($orgName ?: 'Organization') ?></div>
                                <div class="hint">Update your public profile details</div>
                            </div>
                        </div>
                        <form action="<?= base_url('organization/profile/edit') ?>" method="post" class="form-grid">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="organization_name">Organization Name</label>
                                <input type="text" id="organization_name" name="organization_name" value="<?= esc($orgName) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="organization_acronym">Acronym</label>
                                <input type="text" id="organization_acronym" name="organization_acronym" value="<?= esc($orgAcronym) ?>" maxlength="10" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?= esc($email) ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" id="phone" name="phone" value="<?= esc($phone) ?>">
                            </div>
                            <div class="actions" style="grid-column: 1 / -1;">
                                <button type="submit" class="btn primary">Save Changes</button>
                                <a class="btn secondary" href="<?= base_url('organization/profile') ?>">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

