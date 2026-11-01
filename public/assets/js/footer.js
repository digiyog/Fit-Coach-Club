/**
 * Advaita School Footer Interactions
 * Scroll to top & floating widgets
 */
document.addEventListener('DOMContentLoaded', () => {
    const scrollTopBtn = document.getElementById('advScrollTopBtn');
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('is-visible');
            } else {
                scrollTopBtn.classList.remove('is-visible');
            }
        }, { passive: true });

        scrollTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
