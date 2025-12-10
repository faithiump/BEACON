<?php helper('url'); ?>
<header class="admin-topbar">
            <div class="topbar-left">
                <a href="<?= base_url('organization/overview') ?>" class="topbar-title-link">
                    <img src="<?= base_url('assets/images/beacon-logo-text-v1.png') ?>" alt="BEACON" class="topbar-title-image">
                </a>
    </div>
    <div class="topbar-right">
                <?php
                    $orgLogo = session()->get('org_logo') ?? session()->get('organization_logo') ?? null;
                    $orgAcronym = strtoupper(session()->get('org_acronym') ?? session()->get('organization_acronym') ?? 'ORG');
                    $orgInitial = strtoupper(substr($orgAcronym, 0, 1) ?: 'O');
                ?>
        <a class="topbar-profile" href="<?= base_url('organization/profile') ?>" title="View profile">
            <div class="profile-avatar">
                <?php if ($orgLogo): ?>
                    <img src="<?= base_url($orgLogo) ?>" alt="Organization Logo">
                <?php else: ?>
                            <?= esc($orgInitial) ?>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                        <span class="profile-name"><?= esc($orgAcronym) ?></span>
                <span class="profile-role">Organization</span>
            </div>
            <i class="fas fa-chevron-right profile-caret" aria-hidden="true"></i>
        </a>
        <a href="<?= base_url('organization/logout') ?>" class="topbar-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">

