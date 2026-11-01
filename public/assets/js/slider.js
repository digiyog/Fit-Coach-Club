/**
 * Advaita School of Excellence - Hero Slider Script
 * Handles slide transitions, autoplay, pause-on-hover, dots and arrow navigation,
 * and touch swipe gestures.
 */

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.adv-hero-slider-section');
    if (!slider) return;

    const slides = slider.querySelectorAll('.adv-slide');
    const dots = slider.querySelectorAll('.adv-slider-dot');
    const prevBtn = slider.querySelector('.adv-slider-arrow.prev-arrow');
    const nextBtn = slider.querySelector('.adv-slider-arrow.next-arrow');

    if (slides.length <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        const dotsWrap = slider.querySelector('.adv-slider-dots');
        if (dotsWrap) dotsWrap.style.display = 'none';
        return;
    }

    let currentIndex = 0;
    let autoplayTimer = null;
    const intervalTime = 6500; // 6.5s per slide

    function goToSlide(index) {
        if (index < 0) {
            index = slides.length - 1;
        } else if (index >= slides.length) {
            index = 0;
        }

        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('active');
                dot.setAttribute('aria-selected', 'true');
            } else {
                dot.classList.remove('active');
                dot.setAttribute('aria-selected', 'false');
            }
        });

        currentIndex = index;
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startAutoplay() {
        stopAutoplay();
        autoplayTimer = setInterval(nextSlide, intervalTime);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    // Arrow event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            nextSlide();
            startAutoplay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            prevSlide();
            startAutoplay();
        });
    }

    // Dot event listeners
    dots.forEach((dot, idx) => {
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            goToSlide(idx);
            startAutoplay();
        });
    });

    // Pause autoplay on mouse hover
    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    // Touch swipe support
    let startX = 0;
    let endX = 0;

    slider.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        stopAutoplay();
    }, { passive: true });

    slider.addEventListener('touchend', (e) => {
        endX = e.changedTouches[0].clientX;
        const diffX = startX - endX;
        if (Math.abs(diffX) > 45) {
            if (diffX > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
        startAutoplay();
    }, { passive: true });

    // Keyboard navigation when slider is in view
    document.addEventListener('keydown', (e) => {
        const rect = slider.getBoundingClientRect();
        const isInView = rect.top < window.innerHeight && rect.bottom > 0;
        if (isInView) {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                startAutoplay();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                startAutoplay();
            }
        }
    });

    // Start auto slide
    startAutoplay();
});
