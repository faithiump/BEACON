<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Members - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/members.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Members</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentMembers)): ?>
                        <div class="members-grid">
                            <?php foreach ($recentMembers as $m): ?>
                                <?php
                                    $name = trim($m['name'] ?? 'Member');
                                    $initial = strtoupper(substr($name, 0, 1));
                                    $photo = $m['photo'] ?? null;
                                    $studentId = $m['student_id'] ?? '';
                                    $course = $m['course'] ?? '';
                                    $year = $m['year'] ?? '';
                                    $joined = !empty($m['joined_at']) ? date('M d, Y', strtotime($m['joined_at'])) : 'N/A';
                                    $status = strtolower($m['status'] ?? 'pending');
                                    $statusLabel = ucfirst($status);
                                    $statusClass = in_array($status, ['active','approved']) ? 'active' : ($status === 'pending' ? 'pending' : 'inactive');
                                ?>
                                <article class="member-card">
                                    <div class="member-card-header">
                                        <div class="member-avatar">
                                            <?php if (!empty($photo)): ?>
                                                <img src="<?= esc($photo) ?>" alt="<?= esc($name) ?>">
                                            <?php else: ?>
                                                <span><?= esc($initial) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="member-meta">
                                            <h3><?= esc($name) ?></h3>
                                            <p><?= esc($studentId ?: 'ID: N/A') ?></p>
                                        </div>
                                        <span class="member-status <?= esc($statusClass) ?>"><?= esc($statusLabel) ?></span>
                                    </div>
                                    <div class="member-card-body">
                                        <?php if ($course || $year): ?>
                                            <div class="member-info"><strong>Course/Year:</strong> <?= esc(trim($course . ' ' . $year)) ?></div>
                                        <?php endif; ?>
                                        <div class="member-info"><strong>Joined:</strong> <?= esc($joined) ?></div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p style="color:#64748b;">No members found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

