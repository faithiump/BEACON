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

                <!-- Comprehensive Organization Details Card -->
                <div class="content-card">
                    <div class="card-header">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <?php if (isset($isPending) && $isPending): ?>
                                <a href="<?= base_url('admin/organizations/pending') ?>" class="btn-action view" style="text-decoration: none;">
                                    <i class="fas fa-arrow-left"></i> Back to Pending Approvals
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('admin/' . ($returnTo ?? 'dashboard')) ?>" class="btn-action view" style="text-decoration: none;">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            <?php endif; ?>
                            <h2 style="margin: 0;">
                                <i class="fas fa-building"></i>
                                Organization Details
                            </h2>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <?php if (isset($isPending) && $isPending && $application): ?>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-action approve" onclick="approveOrg(<?= esc($application['id']) ?>)" title="Approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn-action reject" onclick="rejectOrg(<?= esc($application['id']) ?>)" title="Reject">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            <?php else: ?>
                                <?php if ($organization && isset($organization['id']) && $organization['id']): ?>
                                    <span class="status-badge <?= $organization['is_active'] ? 'approved' : 'inactive' ?>">
                                        <?= $organization['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <!-- Organization Header -->
                        <div style="margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 2px solid #e2e8f0;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 700; color: #0f172a;">
                                <?= esc($organization['organization_name']) ?>
                            </h3>
                            <p style="margin: 0; font-size: 1.125rem; color: #64748b; font-weight: 500;">
                                <?= esc($organization['organization_acronym']) ?>
                            </p>
                        </div>

                        <!-- Basic Information Section -->
                        <div style="margin-bottom: 2.5rem;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-info-circle" style="color: #35283f;"></i>
                                Basic Information
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
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

                        <?php if (!$isPending): ?>
                        <!-- Mission, Vision, Objectives Section (hidden for pending approvals) -->
                        <div style="margin-bottom: 2.5rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-bullseye" style="color: #35283f;"></i>
                                Mission, Vision & Objectives
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
                                <div>
                                    <h5 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #0f172a;">
                                        <i class="fas fa-bullseye" style="color: #35283f; font-size: 0.875rem;"></i>
                                        Mission
                                    </h5>
                                    <div style="white-space: pre-wrap; line-height: 1.7; color: #475569; font-size: 0.9375rem; padding: 1.25rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; max-width: 100%;">
                                        <?= esc($organization['mission']) ?>
                                    </div>
                                </div>
                                <div>
                                    <h5 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #0f172a;">
                                        <i class="fas fa-eye" style="color: #35283f; font-size: 0.875rem;"></i>
                                        Vision
                                    </h5>
                                    <div style="white-space: pre-wrap; line-height: 1.7; color: #475569; font-size: 0.9375rem; padding: 1.25rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; max-width: 100%;">
                                        <?= esc($organization['vision']) ?>
                                    </div>
                                </div>
                                <div>
                                    <h5 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #0f172a;">
                                        <i class="fas fa-list-check" style="color: #35283f; font-size: 0.875rem;"></i>
                                        Objectives
                                    </h5>
                                    <div style="white-space: pre-wrap; line-height: 1.7; color: #475569; font-size: 0.9375rem; padding: 1.25rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; max-width: 100%;">
                                        <?= esc($organization['objectives']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Advisor Information Section -->
                        <?php if ($advisor): ?>
                        <div style="margin-bottom: 2.5rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-user-tie" style="color: #35283f;"></i>
                                Faculty Advisor
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1.5rem; background: #f8fafc; border-radius: 8px;">
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
                        <?php endif; ?>

                        <!-- Officers Section -->
                        <?php if (!empty($officers)): ?>
                        <div style="margin-bottom: 2.5rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-users" style="color: #35283f;"></i>
                                Officers
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                                <?php foreach ($officers as $officer): ?>
                                <div style="padding: 1.25rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
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
                        <?php endif; ?>

                        <!-- Documents Section -->
                        <?php if (!empty($files)): ?>
                        <div style="margin-bottom: 2.5rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-file-alt" style="color: #35283f;"></i>
                                Submitted Documents
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                                <?php foreach ($files as $file): ?>
                                <div style="padding: 1.25rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
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
                                        <a href="<?= base_url('admin/organizations/file/' . $file['id'] . '/download') ?>" target="_blank" class="btn-action download" style="text-decoration: none;" title="Download File">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Statistics Section (if organization is approved) -->
                        <?php if ($organization && $organization['id'] && !$isPending): ?>
                        <div style="padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-chart-bar" style="color: #35283f;"></i>
                                Organization Statistics
                            </h4>
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
                        <?php endif; ?>
                    </div>
                </div>
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
