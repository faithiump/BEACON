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
})();

