<?php
/**
 * Admin Top Bar
 */
$pending_organizations = $pending_organizations ?? [];
?>
<div class="admin-topbar">
    <div class="topbar-left">
        <h2 class="topbar-title">Admin Dashboard</h2>
    </div>
    
    <div class="topbar-right">
        <div class="notification-wrapper">
            <button class="topbar-icon-btn" id="notificationBtn">
                <i class="fas fa-bell"></i>
                <?php if (count($pending_organizations) > 0): ?>
                    <span class="icon-badge"><?= count($pending_organizations) ?></span>
                <?php endif; ?>
            </button>
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <span class="notification-count"><?= count($pending_organizations) ?> new</span>
                </div>
                <div class="notification-list">
                    <?php if (!empty($pending_organizations)): ?>
                        <?php foreach (array_slice($pending_organizations, 0, 5) as $org): ?>
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
                        <div class="notification-empty">No notifications</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Notification dropdown
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('active');
        }
    });
});
</script>

