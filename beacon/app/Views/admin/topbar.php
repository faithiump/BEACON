<?php
/**
 * Admin Top Bar
 */
$pending_organizations = $pending_organizations ?? [];
?>
<div class="admin-topbar">
    <div class="topbar-left">
        <button class="search-btn" id="searchBtn">
            <i class="fas fa-search"></i>
        </button>
        <input type="text" class="topbar-search" placeholder="Search..." id="topbarSearch">
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
        
        <div class="message-wrapper">
            <button class="topbar-icon-btn">
                <i class="fas fa-envelope"></i>
                <span class="icon-badge">4</span>
            </button>
        </div>
        
        <div class="user-menu">
            <button class="user-avatar-btn" id="userMenuBtn">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <div class="user-info">
                    <div class="user-name"><?= esc(session()->get('admin_user')) ?></div>
                    <div class="user-role">Administrator</div>
                </div>
                <div class="user-menu-items">
                    <a href="#" class="user-menu-item">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="#" class="user-menu-item">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="<?= base_url('admin/logout') ?>" class="user-menu-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-topbar.css') ?>" type="text/css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    const searchBtn = document.getElementById('searchBtn');
    const topbarSearch = document.getElementById('topbarSearch');
    
    // Notification dropdown
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
            if (userDropdown) userDropdown.classList.remove('active');
        });
    }
    
    // User menu dropdown
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            if (notificationDropdown) notificationDropdown.classList.remove('active');
        });
    }
    
    // Search toggle
    if (searchBtn && topbarSearch) {
        searchBtn.addEventListener('click', function() {
            topbarSearch.focus();
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('active');
        }
        if (userDropdown && !userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });
});
</script>

