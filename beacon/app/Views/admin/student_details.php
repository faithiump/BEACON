<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - BEACON Admin</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization.css') ?>">
    <style>
        body {
            overflow-x: hidden;
        }
        
        .main-content {
            padding-top: var(--nav-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .back-nav-wrapper {
            width: 100%;
            padding: 0.75rem 1.25rem 0 1.25rem;
            background: transparent;
        }
        
        .feed-layout {
            grid-template-columns: 260px 1fr 280px;
            gap: 1rem;
            align-items: stretch;
        }
        
        .feed-sidebar-left {
            position: static;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            height: 100%;
        }
        
        .feed-sidebar-left > * {
            flex-shrink: 0;
        }
        
        .feed-sidebar-right {
            position: static;
            max-height: none;
            overflow-y: visible;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            height: 100%;
        }
        
        .feed-sidebar-right > * {
            flex-shrink: 0;
        }
        
        .feed-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 0.625rem;
        }
        
        .feed-main > * {
            flex-shrink: 0;
        }
        
        .content-area {
            padding: 0 1.25rem 1.25rem 1.25rem;
        }
        
        .sidebar-card {
            margin-bottom: 0;
            padding: 0.75rem;
        }
        
        .sidebar-title {
            font-size: 0.875rem;
            margin-bottom: 0.625rem;
        }
        
        .profile-card {
            margin-bottom: 0;
        }
        
        .profile-stats-row {
            padding: 0.75rem 1rem;
        }
        
        .profile-info {
            padding: 0 1rem 0.75rem;
        }
        
        .back-nav {
            background: white;
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            margin-bottom: 1rem;
            margin-top: -2.5rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        
        .back-nav-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .student-name-nav {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-600);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }
        
        .back-button:hover {
            color: var(--primary-700);
            gap: 0.75rem;
        }
        
        .back-button i {
            transition: transform 0.2s;
        }
        
        .back-button:hover i {
            transform: translateX(-4px);
        }
        
        .details-section {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0;
            box-shadow: var(--shadow);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .details-section.basic-info {
            padding: 0.875rem 1rem;
            justify-content: flex-start;
        }
        
        .details-section h3 {
            font-family: var(--font-display);
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.875rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .details-section.basic-info h3 {
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .details-section h3 i {
            color: var(--primary-500);
            font-size: 0.875rem;
        }
        
        .details-section.basic-info h3 i {
            font-size: 0.8125rem;
        }
        
        .detail-row {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 0.625rem;
            padding: 0.625rem 0;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .details-section.basic-info .detail-row {
            padding: 0.5rem 0;
            gap: 0.625rem;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .details-section.basic-info .detail-label {
            font-size: 0.75rem;
        }
        
        .detail-value {
            font-size: 0.875rem;
            color: var(--gray-900);
            font-weight: 500;
        }
        
        .details-section.basic-info .detail-value {
            font-size: 0.8125rem;
        }
        
        .membership-item {
            padding: 0.875rem;
            background: var(--gray-50);
            border-radius: 10px;
            margin-bottom: 0.625rem;
            transition: all 0.2s;
        }
        
        .membership-item:hover {
            background: var(--gray-100);
            transform: translateX(4px);
        }
        
        .membership-item:last-child {
            margin-bottom: 0;
        }
        
        .membership-name {
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .membership-acronym {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--primary-50);
            color: var(--primary-600);
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .membership-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .membership-info span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .membership-info i {
            color: var(--primary-500);
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-badge.approved,
        .status-badge.active {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .status-badge.inactive,
        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        @media (max-width: 900px) {
            .feed-layout {
                grid-template-columns: 1fr;
            }
            
            .feed-sidebar-left {
                position: static;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .feed-sidebar-right {
                position: static;
            }
            
            .profile-card {
                grid-column: 1 / -1;
            }
            
            .detail-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }
        
        @media (max-width: 600px) {
            .feed-sidebar-left {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main class="main-content">
        <div class="content-area">
            <!-- Back Navigation -->
            <div class="back-nav">
                <a href="<?= base_url('admin/dashboard') ?><?= isset($returnTo) && $returnTo ? '#' . $returnTo : '' ?>" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    <?php
                    $backText = 'Back to Dashboard';
                    if (isset($returnTo) && $returnTo) {
                        switch ($returnTo) {
                            case 'organizations':
                                $backText = 'Back to Organizations';
                                break;
                            case 'students':
                                $backText = 'Back to Student Monitoring';
                                break;
                            case 'users':
                                $backText = 'Back to User Management';
                                break;
                            default:
                                $backText = 'Back to Dashboard';
                        }
                    }
                    echo $backText;
                    ?>
                </a>
                <div class="back-nav-right">
                    <span class="student-name-nav"><?= esc($student['name']) ?></span>
                    <span class="status-badge <?= $student['is_active'] ? 'approved' : 'inactive' ?>">
                        <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>

            <!-- Feed Layout -->
            <div class="feed-layout">
                <!-- Left Sidebar -->
                <aside class="feed-sidebar-left">
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="profile-cover">
                            <div class="profile-cover-gradient"></div>
                        </div>
                        <div class="profile-info">
                            <div class="profile-avatar-large">
                                <div class="avatar-placeholder">
                                    <?= strtoupper(substr($student['firstname'] ?? 'S', 0, 1) . substr($student['lastname'] ?? 'T', 0, 1)) ?>
                                </div>
                            </div>
                            <h2 class="profile-name"><?= esc($student['name']) ?></h2>
                            <span class="profile-acronym"><?= esc($student['student_id']) ?></span>
                            <p class="profile-type">
                                <i class="fas fa-graduation-cap"></i> 
                                <?= esc($student['course']) ?>
                            </p>
                        </div>
                        <div class="profile-stats-row">
                            <div class="profile-stat">
                                <span class="stat-num"><?= $stats['organizations'] ?></span>
                                <span class="stat-text">ORGANIZATIONS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-info-circle"></i> Quick Info</h4>
                        <div class="quick-stat-item">
                            <div class="qs-icon primary"><i class="fas fa-building"></i></div>
                            <div class="qs-info">
                                <span class="qs-value"><?= ucfirst(str_replace('_', ' ', $student['department'])) ?></span>
                                <span class="qs-label">Department</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="qs-icon emerald"><i class="fas fa-calendar"></i></div>
                            <div class="qs-info">
                                <span class="qs-value">Year <?= esc($student['year_level']) ?></span>
                                <span class="qs-label">Year Level</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="qs-icon purple"><i class="fas fa-envelope"></i></div>
                            <div class="qs-info">
                                <span class="qs-value"><?= $student['email_verified'] ? 'Verified' : 'Not Verified' ?></span>
                                <span class="qs-label">Email Status</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-address-book"></i> Contact</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-envelope" style="color: var(--primary-500);"></i>
                                <span style="color: var(--gray-700);"><?= esc($student['email']) ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-phone" style="color: var(--primary-500);"></i>
                                <span style="color: var(--gray-700);"><?= esc($student['phone']) ?></span>
                            </div>
                            <?php if ($student['address']): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-map-marker-alt" style="color: var(--primary-500);"></i>
                                <span style="color: var(--gray-700);"><?= esc($student['address']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>

                <!-- Center Feed -->
                <div class="feed-main">
                    <!-- Basic Information -->
                    <div class="details-section basic-info">
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?= esc($student['student_id']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value"><?= esc($student['name']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">First Name</div>
                            <div class="detail-value"><?= esc($student['firstname']) ?></div>
                        </div>
                        <?php if ($student['middlename']): ?>
                        <div class="detail-row">
                            <div class="detail-label">Middle Name</div>
                            <div class="detail-value"><?= esc($student['middlename']) ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="detail-row">
                            <div class="detail-label">Last Name</div>
                            <div class="detail-value"><?= esc($student['lastname']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Course</div>
                            <div class="detail-value"><?= esc($student['course']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Department</div>
                            <div class="detail-value"><?= ucfirst(str_replace('_', ' ', $student['department'])) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Year Level</div>
                            <div class="detail-value">Year <?= esc($student['year_level']) ?></div>
                        </div>
                        <?php if ($student['birthday']): ?>
                        <div class="detail-row">
                            <div class="detail-label">Birthday</div>
                            <div class="detail-value"><?= date('F d, Y', strtotime($student['birthday'])) ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="detail-row">
                            <div class="detail-label">Gender</div>
                            <div class="detail-value"><?= ucfirst(str_replace('_', ' ', $student['gender'])) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Account Created</div>
                            <div class="detail-value"><?= $student['created_at'] ? date('F d, Y H:i', strtotime($student['created_at'])) : 'N/A' ?></div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <aside class="feed-sidebar-right">
                    <!-- Organization Memberships -->
                    <?php if (!empty($memberships)): ?>
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-users"></i> Organization Memberships</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <?php foreach ($memberships as $membership): ?>
                            <div class="membership-item">
                                <div class="membership-name"><?= esc($membership['organization_name']) ?></div>
                                <div class="membership-acronym">
                                    <i class="fas fa-building"></i> <?= esc($membership['organization_acronym']) ?>
                                </div>
                                <div class="membership-info">
                                    <span><i class="fas fa-tag"></i> <?= ucfirst(str_replace('_', ' ', $membership['organization_type'])) ?></span>
                                    <span><i class="fas fa-calendar"></i> Joined: <?= date('M d, Y', strtotime($membership['joined_at'])) ?></span>
                                    <span class="status-badge <?= $membership['status'] === 'active' ? 'active' : ($membership['status'] === 'pending' ? 'pending' : 'inactive') ?>">
                                        <?= ucfirst($membership['status']) ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-users"></i> Organization Memberships</h4>
                        <div style="text-align: center; padding: 1rem; color: var(--gray-500); font-size: 0.875rem;">
                            No organization memberships
                        </div>
                    </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>

