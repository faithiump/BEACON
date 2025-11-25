(() => {
    const nav = document.querySelector('.auth-nav');
    if (!nav) {
        return;
    }

    let lastScrollY = window.scrollY;
    let ticking = false;

    const updateNavState = () => {
        const currentScroll = window.scrollY;

        if (currentScroll > lastScrollY + 10 && currentScroll > 80) {
            nav.classList.add('nav-hidden');
        } else if (currentScroll < lastScrollY - 10 || currentScroll <= 80) {
            nav.classList.remove('nav-hidden');
        }

        lastScrollY = currentScroll;
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updateNavState);
            ticking = true;
        }
    });

    // Register Dropdown Toggle
    const registerDropdownBtn = document.getElementById('registerDropdownBtn');
    const registerDropdownMenu = document.getElementById('registerDropdownMenu');
    const registerDropdown = document.querySelector('.register-dropdown');

    if (registerDropdownBtn && registerDropdownMenu && registerDropdown) {
        registerDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            registerDropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!registerDropdown.contains(e.target)) {
                registerDropdown.classList.remove('active');
            }
        });

        // Close dropdown when clicking on a dropdown item
        const dropdownItems = registerDropdownMenu.querySelectorAll('.register-dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', () => {
                registerDropdown.classList.remove('active');
            });
        });
    }
})();

