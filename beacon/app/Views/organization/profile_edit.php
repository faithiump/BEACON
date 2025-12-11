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
        .photo-actions { display:flex; gap:0.5rem; align-items:center; flex-wrap: wrap; margin-top:0.35rem; }
        .upload-hint { font-size:0.85rem; color:#64748b; margin-top:0.15rem; }
        .btn.linkish { background: #f1f5f9; color:#0f172a; border:1px solid #e2e8f0; }
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
                            $contactEmail = $org['contact_email'] ?? $email;
                            $phone = $org['phone'] ?? '';
                            $contactPhone = $org['contact_phone'] ?? $phone;
                            $mission = $org['mission'] ?? '';
                            $vision = $org['vision'] ?? '';
                            $objectives = $org['objectives'] ?? '';
                            $foundingDate = $org['founding_date'] ?? '';
                            $currentMembers = $org['current_members'] ?? '';
                            $advisorName = $org['advisor_name'] ?? '';
                            $advisorEmail = $org['advisor_email'] ?? '';
                            $advisorPhone = $org['advisor_phone'] ?? '';
                            $advisorDepartment = $org['advisor_department'] ?? '';
                            $officerPosition = $org['officer_position'] ?? '';
                            $officerName = $org['officer_name'] ?? '';
                            $officerEmail = $org['officer_email'] ?? '';
                            $officerPhone = $org['officer_phone'] ?? '';
                            $officerStudentId = $org['officer_student_id'] ?? '';
                            $initial = strtoupper(substr($orgAcronym ?: $orgName, 0, 1) ?: 'O');
                            $photoUrl = $photo ?? ($org['photo'] ?? null);
                            $advisorName = $org['advisor_name'] ?? '';
                            $advisorEmail = $org['advisor_email'] ?? '';
                            $advisorPhone = $org['advisor_phone'] ?? '';
                            $advisorDept = $org['advisor_department'] ?? '';
                            $officerPosition = $org['officer_position'] ?? '';
                            $officerName = $org['officer_name'] ?? '';
                            $officerEmail = $org['officer_email'] ?? '';
                            $officerPhone = $org['officer_phone'] ?? '';
                            $officerStudentId = $org['officer_student_id'] ?? '';
                        ?>
                        <div class="profile-edit-header">
                            <div class="avatar-preview">
                                <?php if (!empty($photoUrl)): ?>
                                    <img src="<?= esc($photoUrl) ?>" alt="Organization logo" id="photoPreview">
                                <?php else: ?>
                                    <span id="photoInitial"><?= esc($initial) ?></span>
                                    <img src="" alt="Organization logo" id="photoPreview" style="display:none;">
                                <?php endif; ?>
                            </div>
                            <div class="meta">
                                <div class="name"><?= esc($orgName ?: 'Organization') ?></div>
                                <div class="hint">Update your public profile details</div>
                                <div class="photo-actions">
                                    <button type="button" class="btn linkish btn-sm" onclick="document.getElementById('photo').click()">
                                        <i class="fas fa-camera"></i> Change Photo
                                    </button>
                                    <span class="upload-hint">JPG/PNG/GIF up to 5MB</span>
                                </div>
                            </div>
                        </div>
                        <form action="<?= base_url('organization/profile/edit') ?>" method="post" class="form-grid" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="file" id="photo" name="photo" accept="image/*" style="display:none;">
                            <div class="form-group" style="grid-column: 1 / -1; margin-top:0.25rem;">
                                <h3 style="margin:0; font-size:1.05rem; color:#0f172a;">Organization Information</h3>
                            </div>
                            <div class="form-group">
                                <label for="organization_name">Organization Name</label>
                                <input type="text" id="organization_name" name="organization_name" value="<?= esc($orgName) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="organization_acronym">Acronym</label>
                                <input type="text" id="organization_acronym" name="organization_acronym" value="<?= esc($orgAcronym) ?>" maxlength="10" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email (locked)</label>
                                <input type="email" id="email" name="email" value="<?= esc($email) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="contact_email">Contact Email (locked)</label>
                                <input type="email" id="contact_email" name="contact_email" value="<?= esc($contactEmail) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" id="phone" name="phone" value="<?= esc($phone) ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_phone">Contact Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone" value="<?= esc($contactPhone) ?>">
                            </div>
                            <div class="form-group">
                                <label for="founding_date">Founding Date</label>
                                <input type="date" id="founding_date" name="founding_date" value="<?= esc($foundingDate) ?>">
                            </div>
                            <div class="form-group">
                                <label for="current_members">Current Number of Officers</label>
                                <input type="number" min="0" id="current_members" name="current_members" value="<?= esc($currentMembers) ?>">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="mission">Mission</label>
                                <textarea id="mission" name="mission" rows="2" style="width:100%; padding:0.65rem 0.75rem; border:1px solid #e2e8f0; border-radius:10px;"><?= esc($mission) ?></textarea>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="vision">Vision</label>
                                <textarea id="vision" name="vision" rows="2" style="width:100%; padding:0.65rem 0.75rem; border:1px solid #e2e8f0; border-radius:10px;"><?= esc($vision) ?></textarea>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="objectives">Objectives</label>
                                <textarea id="objectives" name="objectives" rows="3" style="width:100%; padding:0.65rem 0.75rem; border:1px solid #e2e8f0; border-radius:10px;"><?= esc($objectives) ?></textarea>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1; margin-top:0.5rem;">
                                <hr style="border:0; border-top:1px solid #e2e8f0; margin:0;">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1; margin-top:0.25rem;">
                                <h3 style="margin:0; font-size:1.05rem; color:#0f172a;">Faculty Advisor Information</h3>
                            </div>
                            <div class="form-group">
                                <label for="advisor_name">Faculty Advisor Name</label>
                                <input type="text" id="advisor_name" name="advisor_name" value="<?= esc($advisorName) ?>">
                            </div>
                            <div class="form-group">
                                <label for="advisor_email">Faculty Advisor Email (locked)</label>
                                <input type="email" id="advisor_email" name="advisor_email" value="<?= esc($advisorEmail) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="advisor_phone">Faculty Advisor Phone</label>
                                <input type="text" id="advisor_phone" name="advisor_phone" value="<?= esc($advisorPhone) ?>">
                            </div>
                            <div class="form-group">
                                <label for="advisor_department">Faculty Advisor Department</label>
                                <input type="text" id="advisor_department" name="advisor_department" value="<?= esc($advisorDepartment) ?>">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1; margin-top:0.25rem;">
                                <h3 style="margin:0; font-size:1.05rem; color:#0f172a;">Primary Officer Information</h3>
                            </div>
                            <div class="form-group">
                                <label for="officer_position">Primary Officer Position</label>
                                <input type="text" id="officer_position" name="officer_position" value="<?= esc($officerPosition) ?>">
                            </div>
                            <div class="form-group">
                                <label for="officer_name">Primary Officer Name</label>
                                <input type="text" id="officer_name" name="officer_name" value="<?= esc($officerName) ?>">
                            </div>
                            <div class="form-group">
                                <label for="officer_email">Primary Officer Email (locked)</label>
                                <input type="email" id="officer_email" name="officer_email" value="<?= esc($officerEmail) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="officer_phone">Primary Officer Phone</label>
                                <input type="text" id="officer_phone" name="officer_phone" value="<?= esc($officerPhone) ?>">
                            </div>
                            <div class="form-group">
                                <label for="officer_student_id">Primary Officer Student ID</label>
                                <input type="text" id="officer_student_id" name="officer_student_id" value="<?= esc($officerStudentId) ?>">
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('photo');
        const previewImg = document.getElementById('photoPreview');
        const previewInitial = document.getElementById('photoInitial');

        fileInput?.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPG, PNG, GIF).');
                fileInput.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Image must be 5MB or smaller.');
                fileInput.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(evt) {
                if (previewImg) {
                    previewImg.src = evt.target.result;
                    previewImg.style.display = 'block';
                }
                if (previewInitial) {
                    previewInitial.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        });
    });
    </script>
</body>
</html>

