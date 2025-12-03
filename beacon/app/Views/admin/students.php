<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Students - Admin - BEACON</title>
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
                            <i class="fas fa-user-graduate"></i>
                            Active Student Accounts
                        </h2>
                        <span class="badge"><?= isset($total_students_count) ? number_format($total_students_count) : 0 ?> Total</span>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Joined Orgs</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                    <?php if (!empty($students)): ?>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td><?= esc($student['name']) ?></td>
                                                <td><?= esc($student['email']) ?></td>
                                                <td><?= esc($student['course']) ?></td>
                                                <td><span class="status-badge active"><?= esc($student['status']) ?></span></td>
                                                <td><?= esc($student['org_count']) ?></td>
                                                <td>
                                                    <button class="btn-action view" onclick="viewStudentDetails(<?= esc($student['id']) ?>, 'students')" title="View"><i class="fas fa-eye"></i> View</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No active students found.
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
        function viewStudentDetails(id, returnTo) {
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/students/view') ?>/' + id + returnParam;
        }
    </script>
</body>
</html>

