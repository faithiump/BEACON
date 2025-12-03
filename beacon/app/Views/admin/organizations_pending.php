<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approvals - Admin - BEACON</title>
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
                            <i class="fas fa-clipboard-check"></i>
                            Pending Organization Approvals
                        </h2>
                        <span class="badge badge-warning"><?= isset($pending_organizations) ? count($pending_organizations) : 0 ?></span>
                    </div>
                    <div class="card-body">
                        <div class="approval-list">
                            <?php if (!empty($pending_organizations)): ?>
                                <?php foreach ($pending_organizations as $org): ?>
                                    <div class="approval-item">
                                        <div class="approval-avatar organization">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="approval-info">
                                            <h4><?= esc($org['name']) ?></h4>
                                            <p><?= esc($org['type']) ?> • Registered <?= esc($org['submitted_at']) ?></p>
                                            <div class="approval-details">
                                                <span><i class="fas fa-envelope"></i> <?= esc($org['email']) ?></span>
                                                <span><i class="fas fa-phone"></i> <?= esc($org['phone']) ?></span>
                                            </div>
                                        </div>
                                        <div class="approval-actions">
                                            <button class="btn-action approve" onclick="approveOrg(<?= esc($org['id']) ?>)" title="Approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="btn-action reject" onclick="rejectOrg(<?= esc($org['id']) ?>)" title="Reject">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                            <button class="btn-action view" onclick="viewOrgDetails(<?= esc($org['id']) ?>, 'organizations')" title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="text-align: center; padding: 2rem; color: #64748b;">
                                    No pending organization applications.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function approveOrg(id) {
            if (confirm('Are you sure you want to approve this organization?')) {
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
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/organizations/view') ?>/' + id + returnParam;
        }
    </script>
</body>
</html>

