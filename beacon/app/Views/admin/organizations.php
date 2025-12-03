<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Organizations - Admin - BEACON</title>
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
                            <i class="fas fa-check-circle"></i>
                            Approved Organizations
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Organization Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Members</th>
                                        <th>Events</th>
                                        <th>Approved Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($approved_organizations)): ?>
                                        <?php foreach ($approved_organizations as $org): ?>
                                            <tr>
                                                <td><?= esc($org['name']) ?></td>
                                                <td><?= esc($org['category']) ?></td>
                                                <td><span class="status-badge approved"><?= esc($org['status']) ?></span></td>
                                                <td><?= number_format($org['member_count']) ?></td>
                                                <td><?= number_format($org['event_count']) ?></td>
                                                <td><?= esc($org['approved_date']) ?></td>
                                                <td>
                                                    <button class="btn-action view" onclick="viewOrgDetails(<?= esc($org['id']) ?>, 'organizations')" title="View"><i class="fas fa-eye"></i> View</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No approved organizations found.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function viewOrgDetails(id, returnTo) {
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/organizations/view') ?>/' + id + returnParam;
        }
    </script>
</body>
</html>

