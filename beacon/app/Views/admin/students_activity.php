<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Activity - Admin - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?= view('admin/sidebar') ?>
        
        <!-- Main Content Area -->
        <div class="dashboard-wrapper">
            <!-- Top Bar -->
            <?= view('admin/topbar', ['pending_organizations' => $pending_organizations ?? []]) ?>
            
            <!-- Main Content -->
            <main class="dashboard-main">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-chart-line"></i>
                            Student Activity & Organization Memberships
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="activity-stats">
                            <?php if (!empty($student_activity)): ?>
                                <?php foreach ($student_activity as $activity): ?>
                                    <div class="activity-stat-item">
                                        <h4><?= esc($activity['student_name']) ?></h4>
                                        <p><?= esc($activity['course']) ?></p>
                                        <div class="org-memberships">
                                            <?php if (!empty($activity['organizations'])): ?>
                                                <?php foreach ($activity['organizations'] as $org): ?>
                                                    <span class="org-tag"><?= esc($org) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="org-tag" style="opacity: 0.6;">No memberships</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="activity-metrics">
                                            <span><i class="fas fa-comments"></i> <?= number_format($activity['comment_count']) ?> comment<?= $activity['comment_count'] != 1 ? 's' : '' ?></span>
                                            <span><i class="fas fa-heart"></i> <?= number_format($activity['event_likes_count']) ?> event<?= $activity['event_likes_count'] != 1 ? 's' : '' ?> liked</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="text-align: center; padding: 2rem; color: #64748b;">
                                    No student activity data available.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

