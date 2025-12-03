<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - BEACON Admin</title>
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

                <!-- Comprehensive Student Details Card -->
                <div class="content-card">
                    <div class="card-header">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <a href="<?= base_url('admin/' . ($returnTo ?? 'dashboard')) ?>" class="btn-action view" style="text-decoration: none;">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <h2 style="margin: 0;">
                                <i class="fas fa-user-graduate"></i>
                                Student Details
                            </h2>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span class="status-badge <?= $student['is_active'] ? 'approved' : 'inactive' ?>">
                                <?= $student['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <!-- Student Header -->
                        <div style="margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 2px solid #e2e8f0;">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 700; color: #0f172a;">
                                <?= esc($student['name']) ?>
                            </h3>
                            <p style="margin: 0; font-size: 1.125rem; color: #64748b; font-weight: 500;">
                                <?= esc($student['student_id']) ?>
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
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Student ID</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['student_id']) ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Full Name</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['name']) ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">First Name</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['firstname']) ?></div>
                                </div>
                                <?php if ($student['middlename']): ?>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Middle Name</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['middlename']) ?></div>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Last Name</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['lastname']) ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Course</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['course']) ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Department</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= ucfirst(str_replace('_', ' ', $student['department'])) ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Year Level</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">Year <?= esc($student['year_level']) ?></div>
                                </div>
                                <?php if ($student['birthday']): ?>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Birthday</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= date('F d, Y', strtotime($student['birthday'])) ?></div>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Gender</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= ucfirst(str_replace('_', ' ', $student['gender'])) ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Email</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                        <a href="mailto:<?= esc($student['email']) ?>" style="color: #35283f; text-decoration: none;">
                                            <i class="fas fa-envelope"></i> <?= esc($student['email']) ?>
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Phone</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                        <a href="tel:<?= esc($student['phone']) ?>" style="color: #35283f; text-decoration: none;">
                                            <i class="fas fa-phone"></i> <?= esc($student['phone']) ?>
                                        </a>
                                    </div>
                                </div>
                                <?php if ($student['address']): ?>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Address</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= esc($student['address']) ?></div>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Email Verified</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;">
                                        <span class="status-badge <?= $student['email_verified'] ? 'approved' : 'pending' ?>">
                                            <?= $student['email_verified'] ? 'Verified' : 'Not Verified' ?>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Account Created</div>
                                    <div style="font-size: 0.9375rem; color: #0f172a; font-weight: 500;"><?= $student['created_at'] ? date('F d, Y H:i', strtotime($student['created_at'])) : 'N/A' ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Organization Memberships Section -->
                        <?php if (!empty($memberships)): ?>
                        <div style="margin-bottom: 2.5rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-users" style="color: #35283f;"></i>
                                Organization Memberships
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                                <?php foreach ($memberships as $membership): ?>
                                <div style="padding: 1.25rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #35283f;">
                                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; font-size: 1rem;"><?= esc($membership['organization_name']) ?></div>
                                    <div style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(53, 40, 63, 0.1); color: #35283f; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.75rem;">
                                        <i class="fas fa-building"></i> <?= esc($membership['organization_acronym']) ?>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-tag" style="color: #35283f;"></i>
                                            <span><?= ucfirst(str_replace('_', ' ', $membership['organization_type'])) ?></span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-calendar" style="color: #35283f;"></i>
                                            <span>Joined: <?= date('M d, Y', strtotime($membership['joined_at'])) ?></span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span class="status-badge <?= $membership['status'] === 'active' ? 'active' : ($membership['status'] === 'pending' ? 'pending' : 'inactive') ?>">
                                                <?= ucfirst($membership['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div style="margin-bottom: 2.5rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-users" style="color: #35283f;"></i>
                                Organization Memberships
                            </h4>
                            <div style="text-align: center; padding: 2rem; color: #64748b; background: #f8fafc; border-radius: 8px;">
                                No organization memberships
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Statistics Section -->
                        <div style="padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                            <h4 style="display: flex; align-items: center; gap: 0.5rem; margin: 0 0 1.5rem 0; font-size: 1.125rem; font-weight: 600; color: #0f172a;">
                                <i class="fas fa-chart-bar" style="color: #35283f;"></i>
                                Student Statistics
                            </h4>
                            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                                <div class="stat-card stat-card-blue">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon-large">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-value"><?= number_format($stats['organizations']) ?></h3>
                                        <p class="stat-label">Organizations</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
