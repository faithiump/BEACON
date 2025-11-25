<?php
$year = date('Y');
$navLinks = [
    ['label' => 'Home', 'url' => base_url()],
    ['label' => 'Login', 'url' => base_url('auth/login')],
    ['label' => 'Register', 'url' => base_url('auth/register')],
    ['label' => 'Launch Organization', 'url' => base_url('organization/launch')],
];
?>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-brand">
            <span class="footer-logo">BEACON</span>
            <span class="footer-tagline">Unified Campus Hub of CSPC</span>
            <p class="footer-description">
                Centralized platform for campus announcements, events, and organization management.
                Stay informed and connected with the entire CSPC community.
            </p>
        </div>

        <div class="footer-links">
            <h4>Explore</h4>
            <ul>
                <?php foreach ($navLinks as $link): ?>
                    <li><a href="<?= esc($link['url']) ?>"><?= esc($link['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-contact">
            <h4>Contact</h4>
            <ul>
                <li>📍 CSPC Campus, Nabua, Camarines Sur</li>
                <li>✉️ <a href="mailto:beacon@cspc.edu.ph">beacon@cspc.edu.ph</a></li>
                <li>☎️ +63 912 345 6789</li>
            </ul>
            <div class="footer-cta">
                <a href="<?= base_url('organization/launch') ?>">Launch Your Organization</a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© <?= esc($year) ?> BEACON • Camarines Sur Polytechnic Colleges. All rights reserved.</p>
    </div>
</footer>

<script src="<?= base_url('assets/js/nav.js') ?>"></script>

