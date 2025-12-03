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

            <!-- Navigation Tabs -->
            <div class="dashboard-tabs">
                <button class="tab-btn active" data-tab="organizations">
                    <i class="fas fa-building"></i> Organization Management
                </button>
                <button class="tab-btn" data-tab="students">
                    <i class="fas fa-user-graduate"></i> Student Monitoring
                </button>
                <button class="tab-btn" data-tab="users">
                    <i class="fas fa-users"></i> User Management
                </button>
                <button class="tab-btn" data-tab="transactions">
                    <i class="fas fa-money-bill-wave"></i> Transactions & Payments
                </button>
            </div>

            <!-- Tab Content: Organizations -->
            <div class="tab-content active" id="organizations">
                <div class="content-card" id="section-pending" data-section="pending">
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

                <div class="content-card" id="section-all-orgs" data-section="all">
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
                                                <td><?= esc($org['approved_date']) ?></td>
                                                <td>
                                                    <button class="btn-action view" onclick="viewOrgDetails(<?= esc($org['id']) ?>, 'organizations')" title="View"><i class="fas fa-eye"></i> View</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No approved organizations found.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Students -->
            <div class="tab-content" id="students">
                <div class="content-grid">
                    <div class="content-card" id="section-all-students" data-section="all">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-user-graduate"></i>
                                Active Student Accounts
                            </h2>
                            <?php if (isset($total_students_count) && $total_students_count > 2): ?>
                                <button class="btn-view-all" id="viewAllStudentsBtn" onclick="toggleAllStudents()">
                                    <i class="fas fa-list"></i> View All Accounts (<?= number_format($total_students_count) ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="data-table" id="studentsTable">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Course</th>
                                            <th>Status</th>
                                            <th>Joined Orgs</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsTableBody">
                                        <?php if (!empty($students)): ?>
                                            <?php foreach ($students as $index => $student): ?>
                                                <tr class="student-row <?= $index >= 2 ? 'student-row-hidden' : '' ?>">
                                                    <td><?= esc($student['name']) ?></td>
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
                                                <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                                                    No active students found.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="content-card" id="section-comments" data-section="all">
                        <div class="card-header">
                            <h2>
                                <i class="fas fa-comments"></i>
                                Recent Student Comments
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="comments-list">
                                <?php if (!empty($recent_comments)): ?>
                                    <?php foreach ($recent_comments as $comment): ?>
                                        <div class="comment-item">
                                            <div class="comment-avatar">
                                                <?php if (!empty($comment['profile_image'])): ?>
                                                    <img src="<?= esc($comment['profile_image']) ?>" alt="<?= esc($comment['student_name']) ?>" onerror="this.style.display='none'; this.parentElement.querySelector('i').style.display='flex';">
                                                <?php endif; ?>
                                                <i class="fas fa-user" <?= !empty($comment['profile_image']) ? 'style="display: none;"' : '' ?>></i>
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-header">
                                                    <strong><?= esc($comment['student_name']) ?></strong>
                                                    <span class="comment-time"><?= esc($comment['time_ago']) ?></span>
                                                </div>
                                                <p><?= esc($comment['content']) ?></p>
                                                <span class="comment-context">
                                                    On: <?= esc($comment['post_title']) ?> 
                                                    (<?= esc($comment['post_type']) ?>) • 
                                                    <?= esc($comment['organization_name']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 2rem; color: #64748b;">
                                        No comments found.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card" id="section-activity" data-section="activity">
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

            </div>

            <!-- Tab Content: User Management -->
            <div class="tab-content" id="users">
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
                            <table class="data-table">
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
            </div>

            <!-- Tab Content: Transactions & Payments -->
            <div class="tab-content" id="transactions">
                <div class="content-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-money-bill-wave"></i>
                            Student Transactions & Payments
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Transaction Type</th>
                                        <th>Amount</th>
                                        <th>Organization</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)): ?>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?= esc($transaction['student_name']) ?></td>
                                                <td><?= esc($transaction['transaction_type']) ?></td>
                                                <td>₱<?= esc($transaction['amount']) ?></td>
                                                <td><?= esc($transaction['organization_name']) ?></td>
                                                <td><?= esc($transaction['date']) ?></td>
                                                <td><span class="status-badge <?= esc($transaction['status_class']) ?>"><?= esc($transaction['status_text']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No transactions found.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            function switchTab(tabId) {
                // Remove active class from all tabs and contents
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to target tab and corresponding content
                const targetBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
                const targetContent = document.getElementById(tabId);
                
                if (targetBtn && targetContent) {
                    targetBtn.classList.add('active');
                    targetContent.classList.add('active');
                }
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    switchTab(targetTab);
                    // Clear section filter when clicking tabs directly
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.delete('section');
                    window.history.replaceState({}, '', window.location.pathname + '?' + urlParams.toString());
                    filterSections(targetTab, null);
                });
            });

            // Function to filter sections - SIMPLE AND DIRECT
            function filterSections(tabId, sectionName) {
                const tabContent = document.getElementById(tabId);
                if (!tabContent) {
                    return;
                }
                
                // Get all sections with data-section attribute in this tab
                const allSections = tabContent.querySelectorAll('[data-section]');
                
                if (sectionName) {
                    // Hide all sections first
                    allSections.forEach(section => {
                        section.style.display = 'none';
                    });
                    
                    // Show only matching sections
                    allSections.forEach(section => {
                        const sectionData = section.getAttribute('data-section');
                        if (sectionData === sectionName) {
                            section.style.display = '';
                        }
                    });
                } else {
                    // Show all sections (no filter)
                    allSections.forEach(section => {
                        section.style.display = '';
                    });
                }
            }
            
            // Handle URL parameters for tab navigation (from sidebar links)
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const sectionParam = urlParams.get('section');
            
            // Apply tab switching and section filtering
            if (tabParam) {
                switchTab(tabParam);
                
                // Filter sections based on URL parameter
                if (sectionParam) {
                    filterSections(tabParam, sectionParam);
                } else {
                    filterSections(tabParam, null);
                }
            }

            // Handle hash navigation (for returning from detail pages)
            if (window.location.hash) {
                const hash = window.location.hash.substring(1); // Remove #
                switchTab(hash);
            }
        }); // End DOMContentLoaded

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

        // Toggle view all students
        let showAllStudents = false;
        function toggleAllStudents() {
            showAllStudents = !showAllStudents;
            const hiddenRows = document.querySelectorAll('.student-row-hidden');
            const viewAllBtn = document.getElementById('viewAllStudentsBtn');
            
            if (showAllStudents) {
                hiddenRows.forEach(row => {
                    row.classList.remove('student-row-hidden');
                    row.style.display = '';
                });
                if (viewAllBtn) {
                    viewAllBtn.innerHTML = '<i class="fas fa-chevron-up"></i> Show Less';
                }
            } else {
                hiddenRows.forEach(row => {
                    row.classList.add('student-row-hidden');
                    row.style.display = 'none';
                });
                if (viewAllBtn) {
                    viewAllBtn.innerHTML = '<i class="fas fa-list"></i> View All Accounts (<?= isset($total_students_count) ? number_format($total_students_count) : 0 ?>)';
                }
            }
        }

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
    </script>
</body>
</html>
