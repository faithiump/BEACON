<?php
/**
 * Shared navigation panel.
 *
 * Usage:
 *   echo view('components/nav', ['variant' => 'home', 'active' => 'home']);
 *
 * Active options: home, login, register, launch
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
    [
        'id' => 'register',
        'label' => 'Register',
        'url' => base_url('auth/register'),
    ],
    [
        'id' => 'launch',
        'label' => 'Launch Organization',
        'url' => base_url('organization/launch'),
    ],
];
?>

<nav class="<?= esc($navClass) ?>">
    <div class="<?= esc($containerClass) ?>">
        <a href="<?= esc($brandUrl) ?>" class="nav-brand">
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
        </div>
    </div>
</nav>

