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
            <div class="footer-logo-container">
                <img src="<?= base_url('assets/images/beacon-logo-v3.png') ?>" alt="BEACON Logo" class="footer-brand-logo">
                <span class="footer-logo">BEACON</span>
            </div>
            <span class="footer-tagline">Unified Campus Hub of CSPC</span>
            <p class="footer-description">
                Centralized platform for campus announcements, events, and organization management.
                Stay informed and connected with the entire CSPC community.
            </p>
        </div>

        <div class="footer-logos">
            <img src="<?= base_url('assets/images/cspc-logo.png') ?>" alt="CSPC Logo" class="footer-logo-img">
            <img src="<?= base_url('assets/images/beacon-logo-v1.png') ?>" alt="BEACON Logo" class="footer-logo-img">
            <img src="<?= base_url('assets/images/cspc-ccs-logo.png') ?>" alt="CSPC CCS Logo" class="footer-logo-img">
        </div>

        <div class="footer-contact">
            <h4>Contact</h4>
            <ul>
                <li>CSPC Campus, Nabua, Camarines Sur</li>
                <li><a href="mailto:beacon@cspc.edu.ph">beacon@cspc.edu.ph</a></li>
                <li>+63 912 345 6789</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© <?= esc($year) ?> BEACON • Camarines Sur Polytechnic Colleges. All rights reserved.</p>
    </div>
</footer>

