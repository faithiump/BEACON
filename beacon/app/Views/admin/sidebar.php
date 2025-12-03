<?php
/**
 * Admin Sidebar Navigation
 */
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-logo" id="sidebarToggle">
            <img src="<?= base_url('assets/images/beacon-logo-v4.png') ?>" alt="BEACON" class="logo-icon">
            <img src="<?= base_url('assets/images/beacon-logo-text-v1.png') ?>" alt="BEACON" class="logo-text">
        </a>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item active" data-tooltip="Dashboard">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item nav-group" data-tooltip="Organizations">
                <a href="#" class="nav-link nav-group-toggle">
                    <i class="fas fa-building"></i>
                    <span>Organizations</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="<?= base_url('admin/organizations') ?>">All Organizations</a></li>
                    <li><a href="<?= base_url('admin/organizations/pending') ?>">Pending Approvals</a></li>
                </ul>
            </li>
            
            <li class="nav-item nav-group" data-tooltip="Students">
                <a href="#" class="nav-link nav-group-toggle">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="<?= base_url('admin/students') ?>">All Students</a></li>
                    <li><a href="<?= base_url('admin/students/activity') ?>">Student Activity</a></li>
                </ul>
            </li>
            
            <li class="nav-item nav-group" data-tooltip="Users">
                <a href="#" class="nav-link nav-group-toggle">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="<?= base_url('admin/users') ?>">All Users</a></li>
                </ul>
            </li>
            
            <li class="nav-item nav-group" data-tooltip="Transactions">
                <a href="#" class="nav-link nav-group-toggle">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Transactions</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="<?= base_url('admin/transactions') ?>">All Transactions</a></li>
                    <li><a href="<?= base_url('admin/transactions/payments') ?>">Payments</a></li>
                </ul>
            </li>
            
            <li class="nav-item" data-tooltip="Logout">
                <a href="<?= base_url('admin/logout') ?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-sidebar.css') ?>" type="text/css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const dashboardContainer = document.querySelector('.dashboard-container');
    const dashboardWrapper = document.querySelector('.dashboard-wrapper');
    const topbar = document.querySelector('.admin-topbar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle sidebar collapsed state
            sidebar.classList.toggle('collapsed');
            
            // Toggle dashboard container class
            if (dashboardContainer) {
                dashboardContainer.classList.toggle('sidebar-collapsed');
            }
        });
    }
    
    // Handle nav group toggles
    const navGroupToggles = document.querySelectorAll('.nav-group-toggle');
    navGroupToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const navItem = this.closest('.nav-item');
            navItem.classList.toggle('open');
        });
    });
    
    // Open parent nav-group when submenu item is clicked
    const submenuLinks = document.querySelectorAll('.nav-submenu a');
    submenuLinks.forEach(link => {
        link.addEventListener('click', function() {
            const navItem = this.closest('.nav-item');
            if (navItem && navItem.classList.contains('nav-group')) {
                navItem.classList.add('open');
            }
        });
    });
    
    // Set active state based on current URL
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;
    const urlParams = new URLSearchParams(window.location.search);
    const currentTab = urlParams.get('tab');
    const currentSection = urlParams.get('section');
    
    const navLinks = document.querySelectorAll('.nav-link, .nav-submenu a');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (!href || href === '#') return;
        
        // Parse the link's URL
        const linkUrl = new URL(href, window.location.origin);
        const linkPath = linkUrl.pathname;
        const linkParams = new URLSearchParams(linkUrl.search);
        const linkTab = linkParams.get('tab');
        const linkSection = linkParams.get('section');
        
        // Check if paths match
        const pathMatch = currentPath === linkPath;
        
        // Check if tab and section match (for dashboard links)
        let tabMatch = true;
        let sectionMatch = true;
        
        if (linkTab) {
            tabMatch = currentTab === linkTab;
        }
        
        if (linkSection) {
            sectionMatch = currentSection === linkSection;
        } else if (currentSection && linkTab) {
            // If link doesn't have section but current URL does, don't match
            sectionMatch = false;
        }
        
        // Mark as active if path matches and tab/section conditions are met
        if (pathMatch && tabMatch && sectionMatch) {
            link.classList.add('active');
            const navItem = link.closest('.nav-item');
            if (navItem) {
                navItem.classList.add('active');
                // If it's in a submenu, open the parent group
                const parentGroup = navItem.closest('.nav-group');
                if (parentGroup) {
                    parentGroup.classList.add('open');
                }
            }
        }
    });
});
</script>

