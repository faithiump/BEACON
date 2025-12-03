<?php
/**
 * Admin Navigation Panel
 * 
 * Minimalistic navigation component for admin dashboard
 * 
 * Usage:
 *   echo view('admin/navigation', ['pending_organizations' => $pending_organizations ?? []]);
 */
$pending_organizations = $pending_organizations ?? [];
?>
<nav class="admin-nav">
    <div class="admin-nav-container">
        <a href="<?= base_url('admin/dashboard') ?>" class="admin-nav-logo" onclick="event.preventDefault(); window.location.reload();" title="Refresh Dashboard">
            <img src="<?= base_url('assets/images/beacon-logo-v4.png') ?>" alt="BEACON Admin" class="admin-logo-img">
        </a>
        
        <div class="admin-nav-right">
            <div class="notification-wrapper">
                <button class="notification-btn" id="notificationBtn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge"><?= count($pending_organizations) ?></span>
                </button>
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h3>Pending Organization Approvals</h3>
                        <span class="notification-count"><?= count($pending_organizations) ?> new</span>
                    </div>
                    <div class="notification-list">
                        <?php if (!empty($pending_organizations)): ?>
                            <?php foreach (array_slice($pending_organizations, 0, 3) as $org): ?>
                                <div class="notification-item" onclick="window.location.href='#organizations'">
                                    <div class="notification-icon organization">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title">Organization Pending Approval</p>
                                        <p class="notification-text"><?= esc($org['name']) ?></p>
                                        <span class="notification-time"><?= esc($org['submitted_at']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="padding: 1rem; text-align: center; color: #64748b;">
                                No pending organizations
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="notification-footer">
                        <a href="#organizations" class="view-all-link">View All Pending Organizations</a>
                    </div>
                </div>
            </div>
            
            <div class="admin-profile">
                <div class="profile-info">
                    <span class="profile-name"><?= esc(session()->get('admin_user')) ?></span>
                    <span class="profile-role">Administrator</span>
                </div>
                <div class="profile-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
            
            <a href="<?= base_url('admin/logout') ?>" class="logout-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/navigation.css') ?>" type="text/css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Notification dropdown toggle
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.remove('active');
            }
        });
    }
});
</script>

