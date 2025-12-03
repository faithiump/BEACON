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
            <img src="<?= base_url('assets/images/beacon-logo-v3.png') ?>" alt="BEACON Logo" class="nav-logo-img">
            <img src="<?= base_url('assets/images/beacon-logo-text-v2.png') ?>" alt="BEACON" class="nav-logo">
        </a>
        <button class="nav-menu-toggle" id="navMenuToggle" type="button" aria-label="Toggle navigation menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
        <div class="nav-links" id="navLinks">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('navMenuToggle');
    const navLinks = document.getElementById('navLinks');
    
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('nav-links-open');
            menuToggle.classList.toggle('nav-menu-toggle-active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navLinks.contains(event.target) && !menuToggle.contains(event.target)) {
                navLinks.classList.remove('nav-links-open');
                menuToggle.classList.remove('nav-menu-toggle-active');
            }
        });
    }
    
    // Register dropdown functionality
    function initRegisterDropdown() {
        const registerDropdownBtn = document.getElementById('registerDropdownBtn');
        const registerDropdown = document.querySelector('.register-dropdown');
        const registerDropdownMenu = document.getElementById('registerDropdownMenu');
        
        if (!registerDropdownBtn || !registerDropdown || !registerDropdownMenu) {
            console.error('Register dropdown elements missing');
            return;
        }
        
        // Remove any existing event listeners
        const newBtn = registerDropdownBtn.cloneNode(true);
        registerDropdownBtn.parentNode.replaceChild(newBtn, registerDropdownBtn);
        const btn = document.getElementById('registerDropdownBtn');
        
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = document.querySelector('.register-dropdown');
            const menu = document.getElementById('registerDropdownMenu');
            
            if (!dropdown || !menu) return;
            
            const isActive = dropdown.classList.contains('active');
            
            console.log('Button clicked, isActive:', isActive);
            
            if (isActive) {
                dropdown.classList.remove('active');
                menu.style.display = 'none';
            } else {
                dropdown.classList.add('active');
                menu.style.display = 'block';
                menu.style.opacity = '1';
                menu.style.visibility = 'visible';
            }
        });
        
        // Close dropdown when clicking on a link
        const dropdownItems = registerDropdown.querySelectorAll('.register-dropdown-item');
        dropdownItems.forEach(function(item) {
            item.addEventListener('click', function() {
                registerDropdown.classList.remove('active');
                registerDropdownMenu.style.display = 'none';
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (registerDropdown && !registerDropdown.contains(event.target)) {
                registerDropdown.classList.remove('active');
                if (registerDropdownMenu) {
                    registerDropdownMenu.style.display = 'none';
                }
            }
        });
    }
    
    // Initialize immediately and also after a short delay to ensure DOM is ready
    initRegisterDropdown();
    setTimeout(initRegisterDropdown, 100);
});
</script>

