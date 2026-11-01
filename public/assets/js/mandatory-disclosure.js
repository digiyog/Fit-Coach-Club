/**
 * Mandatory Public Disclosure Interaction Script
 * Advaita School of Excellence
 * Smooth Accordion Toggles & Accessibility
 */
document.addEventListener('DOMContentLoaded', function () {
    const accordionHeaders = document.querySelectorAll('.disclosure-accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const card = this.closest('.disclosure-accordion-card');
            if (!card) return;

            const isCollapsed = card.classList.contains('collapsed');
            
            if (isCollapsed) {
                card.classList.remove('collapsed');
                this.setAttribute('aria-expanded', 'true');
            } else {
                card.classList.add('collapsed');
                this.setAttribute('aria-expanded', 'false');
            }
        });

        // Keyboard navigation (Enter / Space)
        header.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
});
