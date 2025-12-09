<?php helper('url'); ?>
<header class="org-topbar">
    <div class="org-brand">
        <a href="<?= base_url('organization/overview') ?>" class="org-logo">
            <i class="fas fa-broadcast-tower"></i>
            <span>BEACON</span>
        </a>
    </div>
    <nav class="org-nav">
        <a href="<?= base_url('organization/overview') ?>" class="org-nav-link">Overview</a>
        <a href="<?= base_url('organization/events') ?>" class="org-nav-link">Events</a>
        <a href="<?= base_url('organization/announcements') ?>" class="org-nav-link">Announcements</a>
        <a href="<?= base_url('organization/members') ?>" class="org-nav-link">Members</a>
        <a href="<?= base_url('organization/products') ?>" class="org-nav-link">Products</a>
        <a href="<?= base_url('organization/reservations') ?>" class="org-nav-link">Reservations</a>
        <a href="<?= base_url('organization/forum') ?>" class="org-nav-link">Forum</a>
    </nav>
</header>

<style>
.org-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background: #1f1530;
    color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.org-brand .org-logo {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
}
.org-brand .org-logo i {
    font-size: 1.2rem;
}
.org-nav {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.org-nav-link {
    color: #e2e8f0;
    font-weight: 600;
    text-decoration: none;
    padding: 0.4rem 0.6rem;
    border-radius: 6px;
    transition: background 0.2s ease, color 0.2s ease;
}
.org-nav-link:hover {
    background: rgba(255,255,255,0.12);
    color: #fff;
}
</style>

