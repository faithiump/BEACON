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
            <a class="topbar-profile" href="<?= base_url('organization/profile') ?>" title="View Profile">
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
            </a>
        </div>
        <button type="button" id="orgLogoutTrigger" class="topbar-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
</header>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>" type="text/css">
<style>
    .org-logout-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 200000;
    }
    .org-logout-backdrop.show { display: flex; }
    .org-logout-modal {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        width: 360px;
        max-width: 90vw;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.22);
        border: 1px solid #e2e8f0;
    }
    .org-logout-modal h3 {
        margin: 0 0 0.35rem 0;
        font-size: 1.05rem;
        color: #0f172a;
        font-weight: 800;
    }
    .org-logout-modal p {
        margin: 0 0 1rem 0;
        color: #475569;
        font-size: 0.95rem;
    }
    .org-logout-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .org-btn {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
        font-weight: 700;
        cursor: pointer;
        background: #fff;
        color: #0f172a;
        transition: all 0.15s ease;
    }
    .org-btn:hover { border-color: #cbd5e1; }
    .org-btn.primary {
        background: #64116e;
        color: #fff;
        border-color: #64116e;
    }
    .org-btn.primary:hover {
        background: #530e5b;
        border-color: #530e5b;
    }
</style>
<div class="org-logout-backdrop" id="orgLogoutBackdrop" role="dialog" aria-modal="true" aria-labelledby="orgLogoutTitle">
    <div class="org-logout-modal">
        <h3 id="orgLogoutTitle">Log out of organization?</h3>
        <p>You’ll need to sign in again to access your organization dashboard.</p>
        <div class="org-logout-actions">
            <button type="button" class="org-btn" id="orgLogoutCancel">Cancel</button>
            <button type="button" class="org-btn primary" id="orgLogoutConfirm">Log Out</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.getElementById('orgLogoutTrigger');
    const backdrop = document.getElementById('orgLogoutBackdrop');
    const btnCancel = document.getElementById('orgLogoutCancel');
    const btnConfirm = document.getElementById('orgLogoutConfirm');
    const logoutUrl = "<?= base_url('organization/logout') ?>";

    const closeModal = () => backdrop?.classList.remove('show');
    const openModal = () => backdrop?.classList.add('show');

    trigger?.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
    });
    btnCancel?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', (e) => {
        if (e.target === backdrop) closeModal();
    });
    btnConfirm?.addEventListener('click', () => {
        window.location.href = logoutUrl;
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
});
</script>
