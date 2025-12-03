<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BEACON</title>
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
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card stat-card-blue">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon-large">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value"><?= isset($stats['active_students']) ? number_format($stats['active_students']) : '0' ?></h3>
                        <p class="stat-label">Active Students</p>
                    </div>
                </div>
                
                <div class="stat-card stat-card-purple">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon-large">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value"><?= isset($stats['approved_organizations']) ? number_format($stats['approved_organizations']) : '0' ?></h3>
                        <p class="stat-label">Approved Organizations</p>
                    </div>
                </div>
                
                <div class="stat-card stat-card-orange">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon-large">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value"><?= isset($stats['pending_organizations']) ? number_format($stats['pending_organizations']) : '0' ?></h3>
                        <p class="stat-label">Pending Approvals</p>
                    </div>
                </div>
                
                <div class="stat-card stat-card-green">
                    <div class="stat-icon-wrapper">
                        <div class="stat-icon-large">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value"><?= isset($stats['total_users']) ? number_format($stats['total_users']) : '0' ?></h3>
                        <p class="stat-label">Total Users</p>
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Content Grid -->
            <div class="dashboard-content-grid">
                <!-- Left Column -->
                <div class="dashboard-left">
                    <!-- Recent Activity -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-history"></i>
                                Recent Activity
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="activity-list">
                                <?php if (!empty($recent_activity)): ?>
                                    <?php foreach ($recent_activity as $activity): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon <?= $activity['type'] === 'pending_org' ? 'warning' : '' ?>">
                                                <?php if ($activity['type'] === 'pending_org' || $activity['type'] === 'approved_org'): ?>
                                                    <i class="fas fa-building"></i>
                                                <?php elseif ($activity['type'] === 'new_student'): ?>
                                                    <i class="fas fa-user-plus"></i>
                                                <?php elseif ($activity['type'] === 'comment'): ?>
                                                    <i class="fas fa-comment"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-circle"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="activity-content">
                                                <p class="activity-title"><?= esc($activity['title']) ?></p>
                                                <p class="activity-description"><?= esc($activity['description']) ?></p>
                                                <span class="activity-time"><?= esc($activity['time']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 2rem; color: #64748b;">
                                        No recent activity
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="dashboard-right">
                    <!-- Top Organizations -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-trophy"></i>
                                Top Organizations
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="data-table compact">
                                    <thead>
                                        <tr>
                                            <th>Organization</th>
                                            <th>Members</th>
                                            <th>Events</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($approved_organizations)): ?>
                                            <?php foreach (array_slice($approved_organizations, 0, 5) as $org): ?>
                                                <tr>
                                                    <td><strong><?= esc($org['name']) ?></strong></td>
                                                    <td><?= isset($org['member_count']) ? number_format($org['member_count']) : '0' ?></td>
                                                    <td><?= isset($org['event_count']) ? number_format($org['event_count']) : '0' ?></td>
                                                    <td><span class="status-badge approved">Active</span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" style="text-align: center; padding: 2rem; color: #64748b;">
                                                    No organizations found
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <script>
        // Organization approval functions
        function approveOrg(id) {
            if (confirm('Are you sure you want to approve this organization?')) {
                // AJAX call to approve organization
                fetch('<?= base_url('admin/organizations/approve') ?>/' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        function rejectOrg(id) {
            if (confirm('Are you sure you want to reject this organization?')) {
                // AJAX call to reject organization
                fetch('<?= base_url('admin/organizations/reject') ?>/' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        function viewOrgDetails(id, returnTo) {
            // Open modal or navigate to details page
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/organizations/view') ?>/' + id + returnParam;
        }

        function viewStudentDetails(id, returnTo) {
            // Open modal or navigate to details page
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/students/view') ?>/' + id + returnParam;
        }
    </script>
</body>
</html>
