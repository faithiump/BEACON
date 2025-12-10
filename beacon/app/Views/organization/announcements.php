<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Announcements - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/announcements.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Announcements</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentAnnouncements)): ?>
                        <div class="announcements-grid">
                            <?php foreach ($recentAnnouncements as $a): ?>
                                <?php
                                    $orgName = $a['org_name'] ?? 'Organization';
                                    $orgAcronym = $a['org_acronym'] ?? '';
                                    $orgLabel = $orgAcronym ? ($orgName . ' (' . $orgAcronym . ')') : $orgName;
                                    $orgPhoto = $a['org_photo'] ?? null;
                                    $orgInitial = strtoupper(substr($orgName, 0, 1));
                                    $created = !empty($a['created_at']) ? date('M d, Y', strtotime($a['created_at'])) : 'N/A';
                                    $title = trim($a['title'] ?? '') ?: 'Untitled Announcement';
                                    $content = trim($a['content'] ?? '');
                                    $excerpt = strlen($content) > 180 ? substr($content, 0, 180) . '…' : $content;
                                    $priority = strtolower($a['priority'] ?? 'normal');
                                    $priorityClass = in_array($priority, ['high','normal'], true) ? $priority : 'normal';
                                    $priorityLabel = $priority === 'high' ? 'High Priority' : 'Normal';
                                    $views = (int)($a['views'] ?? 0);
                                    $reactions = $a['reaction_counts'] ?? [];
                                    $totalReactions = is_array($reactions) ? array_sum($reactions) : 0;
                                    $commentCount = (int)($a['comment_count'] ?? 0);
                                ?>
                                <article class="announcement-card <?= esc($priorityClass) ?>">
                                    <header class="announcement-card-header">
                                        <div class="announcement-author">
                                            <div class="announcement-avatar">
                                                <?php if (!empty($orgPhoto)): ?>
                                                    <img src="<?= esc($orgPhoto) ?>" alt="<?= esc($orgLabel) ?>">
                                                <?php else: ?>
                                                    <span><?= esc($orgInitial) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="announcement-meta">
                                                <div class="announcement-org"><?= esc($orgLabel) ?></div>
                                                <div class="announcement-date"><?= esc($created) ?></div>
                                            </div>
                                        </div>
                                        <span class="announcement-badge">Announcement</span>
                                    </header>
                                    <div class="announcement-card-body">
                                        <h3><?= esc($title) ?></h3>
                                        <?php if (!empty($excerpt)): ?>
                                            <p><?= esc($excerpt) ?></p>
                                        <?php endif; ?>
                                        <div class="announcement-tags">
                                            <span class="priority-badge <?= esc($priorityClass) ?>"><?= esc($priorityLabel) ?></span>
                                        </div>
                                    </div>
                                    <footer class="announcement-card-footer">
                                        <div class="announcement-stats">
                                            <span><?= esc($views) ?> views</span>
                                            <span><?= esc($totalReactions) ?> reactions</span>
                                            <span><?= esc($commentCount) ?> comments</span>
                                        </div>
                                    </footer>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p style="color:#64748b;">No announcements available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

