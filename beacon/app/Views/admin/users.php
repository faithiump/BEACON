<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users - Admin - BEACON</title>
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
                            <i class="fas fa-users-cog"></i>
                            All Users Management
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="filter-bar">
                            <input type="text" class="form-control" placeholder="Search users..." id="userSearch">
                            <select class="form-control" id="userFilter">
                                <option value="all">All Users</option>
                                <option value="student">Students</option>
                                <option value="organization">Organizations</option>
                            </select>
                        </div>
                        <div class="table-container">
                            <table class="data-table" id="users">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?= esc($user['name']) ?></td>
                                                <td><?= esc($user['email']) ?></td>
                                                <td><span class="role-badge <?= strtolower($user['role']) ?>"><?= esc($user['role']) ?></span></td>
                                                <td><span class="status-badge <?= strtolower($user['status']) === 'active' ? 'active' : 'pending' ?>"><?= esc($user['status']) ?></span></td>
                                                <td><?= esc($user['registration_date']) ?></td>
                                                <td>
                                                    <?php if (strtolower($user['role']) === 'student'): ?>
                                                        <button class="btn-action view" onclick="viewStudentDetails(<?= esc($user['id']) ?>, 'users')" title="View"><i class="fas fa-eye"></i> View</button>
                                                    <?php else: ?>
                                                        <?php if (!empty($user['organization_id'])): ?>
                                                            <button class="btn-action view" onclick="viewOrgDetails(<?= esc($user['organization_id']) ?>, 'users')" title="View"><i class="fas fa-eye"></i> View</button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No users found.
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
        // User Management Search and Filter
        const userSearchInput = document.getElementById('userSearch');
        const userFilterSelect = document.getElementById('userFilter');
        const userTableBody = document.querySelector('#users tbody');

        function filterUsers() {
            if (!userTableBody) return;

            const searchTerm = userSearchInput.value.toLowerCase().trim();
            const filterRole = userFilterSelect.value.toLowerCase();
            const rows = userTableBody.querySelectorAll('tr');
            
            // Remove existing "no results" message first
            const existingNoResults = userTableBody.querySelector('tr td[colspan="6"]');
            if (existingNoResults) {
                existingNoResults.parentElement.remove();
            }

            let visibleCount = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length === 0) return; // Skip empty rows

                const name = cells[0].textContent.toLowerCase();
                const email = cells[1].textContent.toLowerCase();
                const roleCell = cells[2];
                const roleText = roleCell.textContent.toLowerCase();
                const roleBadge = roleCell.querySelector('.role-badge');
                const roleClass = roleBadge ? roleBadge.className.toLowerCase() : '';
                
                // Check role filter - check both text and class
                let roleMatch = true;
                if (filterRole !== 'all') {
                    roleMatch = roleText.includes(filterRole) || roleClass.includes(filterRole);
                }
                
                // Check search filter
                let searchMatch = true;
                if (searchTerm) {
                    searchMatch = name.includes(searchTerm) || email.includes(searchTerm);
                }
                
                // Show/hide row based on both filters
                if (roleMatch && searchMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Only show "no results" message if there are no visible rows AND we have filters applied
            const hasFilter = searchTerm || filterRole !== 'all';
            if (visibleCount === 0 && hasFilter && rows.length > 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">No users found matching your criteria.</td>';
                userTableBody.appendChild(tr);
            }
        }

        // Add event listeners
        if (userSearchInput) {
            userSearchInput.addEventListener('input', filterUsers);
        }
        
        if (userFilterSelect) {
            userFilterSelect.addEventListener('change', filterUsers);
        }

        function viewStudentDetails(id, returnTo) {
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/students/view') ?>/' + id + returnParam;
        }

        function viewOrgDetails(id, returnTo) {
            const returnParam = returnTo ? '?return=' + returnTo : '';
            window.location.href = '<?= base_url('admin/organizations/view') ?>/' + id + returnParam;
        }
    </script>
</body>
</html>

