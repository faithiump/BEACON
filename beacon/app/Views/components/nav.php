<?php
/**
 * Shared navigation panel.
 *
 * Usage:
 *   echo view('components/nav', ['variant' => 'home', 'active' => 'home']);
 *
 * Active options: home, login
 */

$active = $active ?? '';

$navClass = 'auth-nav';
$containerClass = 'nav-container';
$brandUrl = base_url();

$links = [
    [
        'id' => 'home',
        'label' => 'Home',
        'url' => base_url(),
    ],
    [
        'id' => 'login',
        'label' => 'Login',
        'url' => base_url('auth/login'),
    ],
];
?>

<nav class="<?= esc($navClass) ?>">
    <div class="<?= esc($containerClass) ?>">
        <a href="<?= esc($brandUrl) ?>" class="nav-brand">
            <img src="<?= base_url('assets/images/beacon-logo-v1.png') ?>" alt="BEACON Logo" class="nav-logo-img">
            <span class="nav-logo">BEACON</span>
            <span class="nav-subtitle">CSPC</span>
        </a>
        <div class="nav-links">
            <?php foreach ($links as $link): ?>
                <?php $isActive = $active === $link['id']; ?>
                <a href="<?= esc($link['url']) ?>" class="nav-link<?= $isActive ? ' active' : '' ?>">
                    <?= esc($link['label']) ?>
                </a>
            <?php endforeach; ?>
            <!-- Register Dropdown -->
            <div class="register-dropdown">
                <button class="register-btn" id="registerDropdownBtn" type="button">
                    <span>Register</span>
                    <svg class="dropdown-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="register-dropdown-menu" id="registerDropdownMenu">
                    <a href="<?= base_url('auth/register') ?>" class="register-dropdown-item">
                        <span>Student</span>
                    </a>
                    <a href="<?= base_url('organization/launch') ?>" class="register-dropdown-item">
                        <span>Organization</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

