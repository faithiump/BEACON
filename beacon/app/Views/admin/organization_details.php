<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Details - BEACON Admin</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">
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
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>

                <!-- Header Section -->
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-building"></i>
                            Organization Details
                        </h2>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <?php if (isset($isPending) && $isPending && $application): ?>
                                <span class="status-badge pending">Pending Approval</span>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-action approve" onclick="approveOrg(<?= esc($application['id']) ?>)" title="Approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn-action reject" onclick="rejectOrg(<?= esc($application['id']) ?>)" title="Reject">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            <?php else: ?>
                                <span class="status-badge <?= $organization['is_active'] ? 'approved' : 'inactive' ?>">
                                    <?= $organization['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                            <a href="<?= base_url('admin/' . ($returnTo ?? 'dashboard')) ?>" class="btn-action view" style="text-decoration: none;">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #0f172a;">
                                <?= esc($organization['organization_name']) ?>
                            </h3>
                            <span style="color: #64748b; font-size: 1rem;"><?= esc($organization['organization_acronym']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-info-circle"></i>
                            Basic Information
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Organization Name</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($organization['organization_name']) ?></div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Acronym</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($organization['organization_acronym']) ?></div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Type</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= ucfirst(str_replace('_', ' ', $organization['organization_type'])) ?></div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Category</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= ucfirst(str_replace('_', ' ', $organization['organization_category'])) ?></div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Founding Date</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= $organization['founding_date'] ? date('F d, Y', strtotime($organization['founding_date'])) : 'N/A' ?></div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Contact Email</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                    <a href="mailto:<?= esc($organization['contact_email']) ?>" style="color: #35283f; text-decoration: none;">
                                        <i class="fas fa-envelope"></i> <?= esc($organization['contact_email']) ?>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Contact Phone</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                    <a href="tel:<?= esc($organization['contact_phone']) ?>" style="color: #35283f; text-decoration: none;">
                                        <i class="fas fa-phone"></i> <?= esc($organization['contact_phone']) ?>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Submitted At</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= $organization['created_at'] ? date('F d, Y H:i', strtotime($organization['created_at'])) : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission, Vision, Objectives -->
                <div class="content-grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));">
                    <div class="content-card">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-bullseye"></i>
                                Mission
                            </h2>
                        </div>
                        <div class="card-body">
                            <div style="white-space: pre-wrap; line-height: 1.6; color: #475569; font-size: 0.9375rem; padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
                                <?= esc($organization['mission']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-eye"></i>
                                Vision
                            </h2>
                        </div>
                        <div class="card-body">
                            <div style="white-space: pre-wrap; line-height: 1.6; color: #475569; font-size: 0.9375rem; padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
                                <?= esc($organization['vision']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-list-check"></i>
                                Objectives
                            </h2>
                        </div>
                        <div class="card-body">
                            <div style="white-space: pre-wrap; line-height: 1.6; color: #475569; font-size: 0.9375rem; padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
                                <?= esc($organization['objectives']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advisor Information -->
                <?php if ($advisor): ?>
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-user-tie"></i>
                            Faculty Advisor
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Name</div>
                                <div style="font-size: 1rem; color: #0f172a; font-weight: 700; margin-bottom: 0.25rem;"><?= esc($advisor['name']) ?></div>
                                <div style="font-size: 0.875rem; color: #64748b; text-transform: uppercase;"><?= esc($advisor['department']) ?></div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Email</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                    <a href="mailto:<?= esc($advisor['email']) ?>" style="color: #35283f; text-decoration: none;">
                                        <i class="fas fa-envelope"></i> <?= esc($advisor['email']) ?>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Phone</div>
                                <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                    <a href="tel:<?= esc($advisor['phone']) ?>" style="color: #35283f; text-decoration: none;">
                                        <i class="fas fa-phone"></i> <?= esc($advisor['phone']) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Officers -->
                <?php if (!empty($officers)): ?>
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-users"></i>
                            Officers
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                            <?php foreach ($officers as $officer): ?>
                            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
                                <div style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; font-size: 1rem;"><?= esc($officer['name']) ?></div>
                                <div style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(53, 40, 63, 0.1); color: #35283f; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.75rem;">
                                    <i class="fas fa-user-tie"></i> <?= esc($officer['position']) ?>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-envelope" style="color: #35283f;"></i>
                                        <span><?= esc($officer['email']) ?></span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-phone" style="color: #35283f;"></i>
                                        <span><?= esc($officer['phone']) ?></span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-id-card" style="color: #35283f;"></i>
                                        <span><?= esc($officer['student_id']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Documents -->
                <?php if (!empty($files)): ?>
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-file-alt"></i>
                            Submitted Documents
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                            <?php foreach ($files as $file): ?>
                            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-weight: 600; color: #0f172a; font-size: 0.9375rem;">
                                    <i class="fas fa-file-<?= $file['file_type'] === 'constitution' ? 'contract' : 'certificate' ?>" style="color: #35283f;"></i>
                                    <?= ucfirst(str_replace('_', ' ', $file['file_type'])) ?>
                                </div>
                                <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.25rem;">
                                    <i class="fas fa-file"></i> <?= esc($file['file_name']) ?>
                                </div>
                                <?php if ($file['file_size']): ?>
                                <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.75rem;">
                                    <?= number_format($file['file_size'] / 1024, 2) ?> KB
                                </div>
                                <?php endif; ?>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="<?= base_url('admin/organizations/file/' . $file['id']) ?>" target="_blank" class="btn-action view" style="text-decoration: none;" title="View File">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="<?= base_url($file['file_path']) ?>" target="_blank" class="btn-action download" style="text-decoration: none;" title="Download File">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Statistics (if organization is approved) -->
                <?php if ($organization && $organization['id'] && !$isPending): ?>
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-chart-bar"></i>
                            Organization Statistics
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                            <div class="stat-card stat-card-blue">
                                <div class="stat-icon-wrapper">
                                    <div class="stat-icon-large">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-value"><?= number_format($stats['members']) ?></h3>
                                    <p class="stat-label">Members</p>
                                </div>
                            </div>
                            <div class="stat-card stat-card-purple">
                                <div class="stat-icon-wrapper">
                                    <div class="stat-icon-large">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-value"><?= number_format($stats['events']) ?></h3>
                                    <p class="stat-label">Events</p>
                                </div>
                            </div>
                            <div class="stat-card stat-card-green">
                                <div class="stat-icon-wrapper">
                                    <div class="stat-icon-large">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-value"><?= number_format($stats['announcements']) ?></h3>
                                    <p class="stat-label">Announcements</p>
                                </div>
                            </div>
                            <div class="stat-card stat-card-orange">
                                <div class="stat-icon-wrapper">
                                    <div class="stat-icon-large">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-value"><?= number_format($stats['products']) ?></h3>
                                    <p class="stat-label">Products</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        // Organization approval functions
        function approveOrg(id) {
            if (confirm('Are you sure you want to approve this organization?')) {
                fetch('<?= base_url('admin/organizations/approve') ?>/' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to approve organization'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while approving the organization');
                });
            }
        }

        function rejectOrg(id) {
            if (confirm('Are you sure you want to reject this organization?')) {
                fetch('<?= base_url('admin/organizations/reject') ?>/' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to reject organization'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while rejecting the organization');
                });
            }
        }
    </script>
</body>
</html>
