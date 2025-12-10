<?php
helper('url');
$uri = service('uri');
$segment = $uri->getSegment(2) ?? 'overview';
// Normalize segment to handle ".php" routes or trailing slashes
$segment = strtolower(preg_replace('/\.php$/', '', $segment));
?>
<aside class="admin-sidebar" id="orgSidebar">
    <div class="sidebar-header">
        <a href="<?= base_url('organization/overview') ?>" class="sidebar-logo" id="orgSidebarToggle">
            <img src="<?= base_url('assets/images/beacon-logo-v4.png') ?>" alt="BEACON" class="logo-icon">
        </a>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= base_url('organization/overview') ?>" class="nav-link <?= ($segment === 'overview' || $segment === 'dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>News Feed</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/events') ?>" class="nav-link <?= $segment === 'events' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/announcements') ?>" class="nav-link <?= $segment === 'announcements' ? 'active' : '' ?>">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/members') ?>" class="nav-link <?= $segment === 'members' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Members</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/products') ?>" class="nav-link <?= $segment === 'products' ? 'active' : '' ?>">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/reservations') ?>" class="nav-link <?= $segment === 'reservations' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/forum') ?>" class="nav-link <?= $segment === 'forum' ? 'active' : '' ?>">
                    <i class="fas fa-comments"></i>
                    <span>Forum</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('organization/logout') ?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" referrerpolicy="no-referrer">
<link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/organization/sidebar.css') ?>" type="text/css">
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('orgSidebarToggle');
    const sidebar = document.getElementById('orgSidebar') || document.querySelector('.admin-sidebar');
    const dashboardContainer = document.querySelector('.dashboard-container');

    const sidebarState = localStorage.getItem('orgSidebarCollapsed');
    // Default to expanded for better usability; only collapse if user chose it
    const isCollapsed = sidebarState === 'true';
    sidebar.classList.toggle('collapsed', isCollapsed);
    if (dashboardContainer) dashboardContainer.classList.toggle('sidebar-collapsed', isCollapsed);

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isNowCollapsed = sidebar.classList.toggle('collapsed');
            localStorage.setItem('orgSidebarCollapsed', isNowCollapsed.toString());
            if (dashboardContainer) {
                dashboardContainer.classList.toggle('sidebar-collapsed', isNowCollapsed);
            }
        });
    }
});
</script>

