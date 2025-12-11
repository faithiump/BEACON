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
                    $logoSrc = null;
                    if ($orgLogo) {
                        $logoSrc = (strpos($orgLogo, 'http') === 0) ? $orgLogo : base_url($orgLogo);
                    }
                ?>
        <div class="topbar-profile-wrapper">
            <button class="topbar-profile" type="button" id="orgProfileToggle" title="Profile menu">
                <div class="profile-avatar">
                    <?php if ($logoSrc): ?>
                        <img src="<?= $logoSrc ?>" alt="Organization Logo">
                    <?php else: ?>
                                <?= esc($orgInitial) ?>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                            <span class="profile-name"><?= esc($orgAcronym) ?></span>
                    <span class="profile-role">Organization</span>
                </div>
                <i class="fas fa-chevron-down profile-caret" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu profile-menu" id="orgProfileMenu">
                <a href="<?= base_url('organization/profile/edit') ?>" style="display:flex; align-items:center; gap:0.5rem; padding:0.65rem 0.9rem; color:#0f172a; text-decoration:none;">
                    <i class="fas fa-user-edit" style="color:#64116e;"></i> Edit Profile
                </a>
            </div>
        </div>
        <a href="<?= base_url('organization/logout') ?>" class="topbar-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">
<script>
document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('orgProfileToggle');
        const menu = document.getElementById('orgProfileMenu');
        if (btn && menu) {
            const hideMenu = () => {
                menu.classList.remove('open');
                menu.style.display = 'none';
            };

            const showMenu = () => {
                menu.style.display = 'block';
                menu.classList.add('open');
                // clear any previous inline positioning
                menu.style.left = '';
                menu.style.top = '';
                menu.style.position = 'absolute';
            };

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = menu.classList.contains('open');
                if (isOpen) {
                    hideMenu();
                } else {
                    showMenu();
                }
            });
            menu.addEventListener('click', (e) => e.stopPropagation());
            document.addEventListener('click', hideMenu);
            window.addEventListener('resize', hideMenu);
            window.addEventListener('scroll', hideMenu, true);
        }
});
</script>

