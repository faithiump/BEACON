<?php helper('url'); ?>
<header class="admin-topbar">
    <div class="topbar-left">
        <img src="<?= base_url('assets/images/beacon-logo-text-v1.png') ?>" alt="BEACON" class="topbar-title-image">
        <span class="topbar-title-text">Organization Dashboard</span>
        <div class="topbar-search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="topbar-search" placeholder="Search" aria-label="Search">
        </div>
    </div>
    <div class="topbar-right">
        <a href="<?= base_url('organization/overview') ?>" class="topbar-icon-btn" title="Overview">
            <i class="fas fa-th-large"></i>
        </a>
        <button class="topbar-btn primary" type="button" onclick="window.location.href='<?= base_url('organization/announcements') ?>'">
            <i class="fas fa-plus"></i>
            <span>Create</span>
        </button>
        <div class="topbar-profile">
            <div class="profile-avatar">
                <?= strtoupper(substr(session()->get('org_acronym') ?? 'ORG', 0, 2)) ?>
            </div>
            <div class="profile-info">
                <span class="profile-name"><?= esc(session()->get('org_name') ?? 'Organization') ?></span>
                <span class="profile-role">Organization</span>
            </div>
            <a href="<?= base_url('organization/logout') ?>" class="topbar-icon-btn" title="Logout" style="margin-left:0.5rem;">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</header>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">

