<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Details - BEACON Admin</title>
    <?php helper('url'); ?>
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
        
        .org-name-nav {
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
        }
        
        .details-section.basic-info {
            padding: 0.75rem;
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
            margin-bottom: 0.625rem;
            padding-bottom: 0.375rem;
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
            gap: 0.5rem;
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
        
        .text-content {
            white-space: pre-wrap;
            line-height: 1.6;
            color: var(--gray-700);
            font-size: 0.8125rem;
            padding: 0.75rem;
            background: var(--gray-50);
            border-radius: 8px;
            border-left: 4px solid var(--primary-500);
        }
        
        .officer-item {
            padding: 0.875rem;
            background: var(--gray-50);
            border-radius: 10px;
            margin-bottom: 0.625rem;
            transition: all 0.2s;
        }
        
        .file-item {
            padding: 0.625rem;
            background: var(--gray-50);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        
        .officer-item:hover, .file-item:hover {
            background: var(--gray-100);
            transform: translateX(4px);
        }
        
        .officer-item:last-child, .file-item:last-child {
            margin-bottom: 0;
        }
        
        .officer-name {
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .officer-position {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--primary-50);
            color: var(--primary-600);
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .officer-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .officer-contact span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .officer-contact i {
            color: var(--primary-500);
        }
        
        .file-header {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-bottom: 0.375rem;
            font-weight: 600;
            color: var(--gray-900);
            font-size: 0.8125rem;
        }
        
        .file-header i {
            color: var(--primary-500);
            font-size: 0.75rem;
        }
        
        .file-name {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin-bottom: 0.125rem;
            line-height: 1.4;
        }
        
        .file-size {
            font-size: 0.6875rem;
            color: var(--gray-500);
        }
        
        .file-download {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: var(--primary-50);
            color: var(--primary-600);
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.2s;
            margin-top: 0.375rem;
        }
        
        .file-download:hover {
            background: var(--primary-100);
            transform: translateY(-2px);
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
                    <span class="org-name-nav"><?= esc($organization['organization_name']) ?></span>
                    <span class="status-badge <?= $organization['is_active'] ? 'approved' : 'inactive' ?>">
                        <?= $organization['is_active'] ? 'Active' : 'Inactive' ?>
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
                                    <?= strtoupper(substr($organization['organization_acronym'] ?? 'ORG', 0, 2)) ?>
                                </div>
                            </div>
                            <h2 class="profile-name"><?= esc($organization['organization_name']) ?></h2>
                            <span class="profile-acronym"><?= esc($organization['organization_acronym']) ?></span>
                            <p class="profile-type">
                                <i class="fas fa-tag"></i> 
                                <?= ucfirst(str_replace('_', ' ', $organization['organization_type'])) ?>
                            </p>
                        </div>
                        <div class="profile-stats-row">
                            <div class="profile-stat">
                                <span class="stat-num"><?= $stats['members'] ?></span>
                                <span class="stat-text">MEMBERS</span>
                            </div>
                            <div class="profile-stat">
                                <span class="stat-num"><?= $stats['events'] ?></span>
                                <span class="stat-text">EVENTS</span>
                            </div>
                            <div class="profile-stat">
                                <span class="stat-num"><?= $stats['products'] ?></span>
                                <span class="stat-text">PRODUCTS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-info-circle"></i> Quick Info</h4>
                        <div class="quick-stat-item">
                            <div class="qs-icon primary"><i class="fas fa-building"></i></div>
                            <div class="qs-info">
                                <span class="qs-value"><?= ucfirst(str_replace('_', ' ', $organization['organization_category'])) ?></span>
                                <span class="qs-label">Category</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="qs-icon emerald"><i class="fas fa-calendar"></i></div>
                            <div class="qs-info">
                                <span class="qs-value"><?= $organization['founding_date'] ? date('Y', strtotime($organization['founding_date'])) : 'N/A' ?></span>
                                <span class="qs-label">Founded</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="qs-icon purple"><i class="fas fa-users"></i></div>
                            <div class="qs-info">
                                <span class="qs-value"><?= esc($organization['current_members']) ?></span>
                                <span class="qs-label">Current Members</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-address-book"></i> Contact</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-envelope" style="color: var(--primary-500);"></i>
                                <span style="color: var(--gray-700);"><?= esc($organization['contact_email']) ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-phone" style="color: var(--primary-500);"></i>
                                <span style="color: var(--gray-700);"><?= esc($organization['contact_phone']) ?></span>
                            </div>
                            <?php if ($application && $application['reviewed_at']): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-check-circle" style="color: var(--emerald-500);"></i>
                                <span style="color: var(--gray-700);">Approved: <?= date('M d, Y', strtotime($application['reviewed_at'])) ?></span>
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
                            <div class="detail-label">Organization Name</div>
                            <div class="detail-value"><?= esc($organization['organization_name']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Acronym</div>
                            <div class="detail-value"><?= esc($organization['organization_acronym']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Type</div>
                            <div class="detail-value"><?= ucfirst(str_replace('_', ' ', $organization['organization_type'])) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Category</div>
                            <div class="detail-value"><?= ucfirst(str_replace('_', ' ', $organization['organization_category'])) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Founding Date</div>
                            <div class="detail-value"><?= $organization['founding_date'] ? date('F d, Y', strtotime($organization['founding_date'])) : 'N/A' ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created At</div>
                            <div class="detail-value"><?= $organization['created_at'] ? date('F d, Y H:i', strtotime($organization['created_at'])) : 'N/A' ?></div>
                        </div>
                    </div>

                    <!-- Mission -->
                    <div class="details-section">
                        <h3><i class="fas fa-bullseye"></i> Mission</h3>
                        <div class="text-content"><?= esc($organization['mission']) ?></div>
                    </div>

                    <!-- Vision -->
                    <div class="details-section">
                        <h3><i class="fas fa-eye"></i> Vision</h3>
                        <div class="text-content"><?= esc($organization['vision']) ?></div>
                    </div>

                    <!-- Objectives -->
                    <div class="details-section">
                        <h3><i class="fas fa-list-check"></i> Objectives</h3>
                        <div class="text-content"><?= esc($organization['objectives']) ?></div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <aside class="feed-sidebar-right">
                    <!-- Advisor Information -->
                    <?php if ($advisor): ?>
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-user-tie"></i> Faculty Advisor</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div>
                                <div style="font-weight: 700; color: var(--gray-900); margin-bottom: 0.25rem;"><?= esc($advisor['name']) ?></div>
                                <div style="font-size: 0.875rem; color: var(--gray-600);"><?= strtoupper(esc($advisor['department'])) ?></div>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.875rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--gray-700);">
                                    <i class="fas fa-envelope" style="color: var(--primary-500);"></i>
                                    <?= esc($advisor['email']) ?>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--gray-700);">
                                    <i class="fas fa-phone" style="color: var(--primary-500);"></i>
                                    <?= esc($advisor['phone']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Officers -->
                    <?php if (!empty($officers)): ?>
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-users"></i> Officers</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <?php foreach ($officers as $officer): ?>
                            <div class="officer-item">
                                <div class="officer-name"><?= esc($officer['name']) ?></div>
                                <div class="officer-position">
                                    <i class="fas fa-user-tie"></i> <?= esc($officer['position']) ?>
                                </div>
                                <div class="officer-contact">
                                    <span><i class="fas fa-envelope"></i> <?= esc($officer['email']) ?></span>
                                    <span><i class="fas fa-phone"></i> <?= esc($officer['phone']) ?></span>
                                    <span><i class="fas fa-id-card"></i> <?= esc($officer['student_id']) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Documents -->
                    <?php if (!empty($files)): ?>
                    <div class="sidebar-card">
                        <h4 class="sidebar-title"><i class="fas fa-file-alt"></i> Documents</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <?php foreach ($files as $file): ?>
                            <div class="file-item">
                                <div class="file-header">
                                    <i class="fas fa-file-<?= $file['file_type'] === 'constitution' ? 'contract' : 'certificate' ?>"></i>
                                    <?= ucfirst(str_replace('_', ' ', $file['file_type'])) ?>
                                </div>
                                <div class="file-name">
                                    <i class="fas fa-file"></i> <?= esc($file['file_name']) ?>
                                </div>
                                <?php if ($file['file_size']): ?>
                                <div class="file-size"><?= number_format($file['file_size'] / 1024, 2) ?> KB</div>
                                <?php endif; ?>
                                <a href="<?= base_url($file['file_path']) ?>" target="_blank" class="file-download">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </aside>
            </div>
        </div>
    </main>
</body>
</html>
