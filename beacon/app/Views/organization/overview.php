<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Overview - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/overview.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <!-- Org profile summary -->
                <section class="content-card">
                    <div class="card-header">
                        <h2>Organization Profile</h2>
                    </div>
                    <div class="card-body">
                        <div style="display:flex; gap:1.5rem; flex-wrap:wrap; align-items:center;">
                            <div class="profile-badge" style="width:120px; height:120px; border-radius:18px; background:linear-gradient(135deg,#35283f,#4f46e5); display:flex; align-items:center; justify-content:center; color:#fff; font-size:2rem; font-weight:800;">
                                <?= strtoupper(substr($organization['acronym'] ?? 'ORG',0,2)) ?>
                            </div>
                            <div style="flex:1; min-width:220px;">
                                <h3 style="margin:0 0 0.35rem 0; font-size:1.4rem; color:#0f172a;"><?= esc($organization['name'] ?? 'Organization') ?></h3>
                                <p style="margin:0 0 0.35rem 0; color:#475569;"><?= esc($organization['category'] ?? '') ?></p>
                                <div style="display:flex; gap:1rem; flex-wrap:wrap; color:#475569;">
                                    <span><i class="fas fa-envelope"></i> <?= esc($organization['email'] ?? '') ?></span>
                                    <?php if (!empty($organization['phone'])): ?>
                                    <span><i class="fas fa-phone"></i> <?= esc($organization['phone']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                                <div class="stat-pill"><strong><?= number_format($stats['members'] ?? 0) ?></strong><span>Members</span></div>
                                <div class="stat-pill"><strong><?= number_format($stats['events'] ?? 0) ?></strong><span>Events</span></div>
                                <div class="stat-pill"><strong><?= number_format($stats['announcements'] ?? 0) ?></strong><span>Announcements</span></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Combined feed -->
                <section class="content-card">
                    <div class="card-header">
                        <h2>Recent Activity</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($allPosts)): ?>
                            <div class="feed-list">
                                <?php foreach ($allPosts as $post): ?>
                                    <div class="feed-item">
                                        <div class="feed-icon <?= $post['type'] === 'event' ? 'event' : 'announcement' ?>">
                                            <i class="fas <?= $post['type'] === 'event' ? 'fa-calendar-alt' : 'fa-bullhorn' ?>"></i>
                                        </div>
                                        <div class="feed-content">
                                            <h4><?= esc($post['data']['title'] ?? $post['data']['event_name'] ?? 'Untitled') ?></h4>
                                            <p class="feed-meta"><?= esc($post['type']) ?> • <?= date('M d, Y', $post['date']) ?></p>
                                            <?php if (!empty($post['data']['description'])): ?>
                                                <p class="feed-desc"><?= esc($post['data']['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color:#64748b;">No recent activity.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
