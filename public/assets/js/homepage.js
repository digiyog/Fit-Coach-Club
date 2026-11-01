/**
 * Advaita School of Excellence - Homepage Interactivity Script
 * Handles animated counters, gallery lightbox, smooth scroll & interactive cards.
 */

document.addEventListener('DOMContentLoaded', () => {
    // --------------------------------------------------------------------------
    // 1. Number Counter Animation on Scroll
    // --------------------------------------------------------------------------
    const counters = document.querySelectorAll('.parshv-stat-num');
    let hasAnimated = false;

    function animateCounters() {
        counters.forEach(counter => {
            const targetText = counter.innerText.trim();
            const numericMatch = targetText.match(/\d+/);
            if (!numericMatch) return;

            const target = parseInt(numericMatch[0], 10);
            const suffix = targetText.replace(numericMatch[0], '');
            let current = 0;
            const duration = 1600;
            const stepTime = Math.max(Math.floor(duration / target), 20);

            const timer = setInterval(() => {
                current += Math.ceil(target / (duration / stepTime));
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.innerText = current + suffix;
            }, stepTime);
        });
    }

    if (counters.length > 0) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    hasAnimated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.3 });

        const statsSection = document.querySelector('.parshv-stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    }

    // --------------------------------------------------------------------------
    // 2. Simple Image Lightbox Modal for Gallery & Campus Cards
    // --------------------------------------------------------------------------
    const galleryItems = document.querySelectorAll('.parshv-gallery-item, .parshv-fac-card');
    
    // Create modal container dynamically
    const modal = document.createElement('div');
    modal.className = 'parshv-lightbox-modal';
    modal.innerHTML = `
        <div class="parshv-lightbox-overlay"></div>
        <div class="parshv-lightbox-content">
            <button type="button" class="parshv-lightbox-close" aria-label="Close lightbox">&times;</button>
            <img src="" alt="Enlarged Campus View" class="parshv-lightbox-img">
            <div class="parshv-lightbox-caption"></div>
        </div>
    `;
    document.body.appendChild(modal);

    const lightboxImg = modal.querySelector('.parshv-lightbox-img');
    const lightboxCaption = modal.querySelector('.parshv-lightbox-caption');
    const lightboxClose = modal.querySelector('.parshv-lightbox-close');
    const lightboxOverlay = modal.querySelector('.parshv-lightbox-overlay');

    function openLightbox(src, title) {
        lightboxImg.src = src;
        lightboxCaption.innerText = title || 'Advaita School of Excellence';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            lightboxImg.src = '';
        }, 300);
    }

    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            const img = item.querySelector('img');
            const titleEl = item.querySelector('.parshv-gallery-caption-title, .parshv-fac-title');
            if (img) {
                const title = titleEl ? titleEl.innerText : img.alt;
                openLightbox(img.src, title);
            }
        });
    });

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightboxOverlay) lightboxOverlay.addEventListener('click', closeLightbox);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeLightbox();
        }
    });

    // --------------------------------------------------------------------------
    // 3. Advaita FAQ Accordion Interactive Toggle
    // --------------------------------------------------------------------------
    const faqItems = document.querySelectorAll('.adv-faq-item');
    if (faqItems.length > 0) {
        faqItems.forEach(item => {
            const btn = item.querySelector('.adv-faq-btn');
            const answer = item.querySelector('.adv-faq-answer');
            const icon = item.querySelector('.adv-faq-icon i');

            if (btn && answer) {
                btn.addEventListener('click', () => {
                    const isOpen = item.classList.contains('active');

                    // Close all accordion items
                    faqItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                        const otherBtn = otherItem.querySelector('.adv-faq-btn');
                        const otherAns = otherItem.querySelector('.adv-faq-answer');
                        const otherIcon = otherItem.querySelector('.adv-faq-icon i');

                        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                        if (otherAns) otherAns.style.display = 'none';
                        if (otherIcon) {
                            otherIcon.className = 'fa-solid fa-plus';
                        }
                    });

                    // If current item was not open, open it
                    if (!isOpen) {
                        item.classList.add('active');
                        btn.setAttribute('aria-expanded', 'true');
                        answer.style.display = 'block';
                        if (icon) {
                            icon.className = 'fa-solid fa-minus';
                        }
                    }
                });
            }
        });
    }

    // --------------------------------------------------------------------------
    // 4. Advaita On-Load Admission Popup Modal
    // --------------------------------------------------------------------------
    const onloadModal = document.getElementById('advOnloadModal');
    const onloadClose = document.getElementById('advOnloadClose');
    const onloadBackdrop = document.getElementById('advOnloadBackdrop');

    if (onloadModal) {
        // Automatically display popup on page load after a smooth 900ms delay
        setTimeout(() => {
            onloadModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }, 900);

        function closeOnloadModal() {
            onloadModal.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (onloadClose) {
            onloadClose.addEventListener('click', closeOnloadModal);
        }

        if (onloadBackdrop) {
            onloadBackdrop.addEventListener('click', closeOnloadModal);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && onloadModal.classList.contains('show')) {
                closeOnloadModal();
            }
        });
    }

    // --------------------------------------------------------------------------
    // 5. Why Choose Advaita - Card Slider
    // --------------------------------------------------------------------------
    const whySliderWrap = document.querySelector('.adv-why-slider-wrap');
    const whySliderGrid = document.querySelector('.adv-why-slider-wrap .adv-why-grid');
    const whyCards = document.querySelectorAll('.adv-why-slider-wrap .adv-why-card');
    const whyPrevBtn = document.querySelector('.adv-why-arrow.prev');
    const whyNextBtn = document.querySelector('.adv-why-arrow.next');
    const whyDots = document.querySelectorAll('.adv-why-dot');

    if (whySliderGrid && whyCards.length > 0) {
        let whyCurrentPage = 0;
        const whyTotalCards = whyCards.length;

        function getVisibleCards() {
            if (window.innerWidth <= 576) return 1;
            if (window.innerWidth <= 768) return 2;
            return 3;
        }

        function getTotalPages() {
            return Math.ceil(whyTotalCards / getVisibleCards());
        }

        function updateWhySlider() {
            const visible = getVisibleCards();
            const totalPages = getTotalPages();
            if (whyCurrentPage >= totalPages) {
                whyCurrentPage = 0;
            }

            // Calculate card step distance accurately
            let cardStep = 0;
            if (whyCards.length > 1) {
                cardStep = whyCards[1].offsetLeft - whyCards[0].offsetLeft;
            }
            if (cardStep <= 0) {
                const gap = window.innerWidth <= 768 ? 16 : (window.innerWidth <= 991 ? 20 : 24);
                cardStep = whyCards[0].offsetWidth + gap;
            }

            const maxOffset = Math.max(0, (whyTotalCards - visible) * cardStep);
            const targetOffset = whyCurrentPage * visible * cardStep;
            const offset = Math.min(targetOffset, maxOffset);

            whySliderGrid.style.transform = `translateX(-${offset}px)`;

            // Update dots
            whyDots.forEach((dot, i) => {
                dot.classList.toggle('active', i === whyCurrentPage);
                dot.setAttribute('aria-selected', i === whyCurrentPage ? 'true' : 'false');
            });
        }

        function whySlideNext() {
            const totalPages = getTotalPages();
            whyCurrentPage = (whyCurrentPage + 1) % totalPages;
            updateWhySlider();
        }

        function whySlidePrev() {
            const totalPages = getTotalPages();
            whyCurrentPage = (whyCurrentPage - 1 + totalPages) % totalPages;
            updateWhySlider();
        }

        if (whyNextBtn) whyNextBtn.addEventListener('click', () => { whySlideNext(); resetWhyAutoplay(); });
        if (whyPrevBtn) whyPrevBtn.addEventListener('click', () => { whySlidePrev(); resetWhyAutoplay(); });

        whyDots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                whyCurrentPage = i;
                updateWhySlider();
                resetWhyAutoplay();
            });
        });

        // Pause autoplay on mouse hover
        if (whySliderWrap) {
            whySliderWrap.addEventListener('mouseenter', () => clearInterval(whyAutoplayTimer));
            whySliderWrap.addEventListener('mouseleave', () => resetWhyAutoplay());
        }

        // Mobile touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        whySliderGrid.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        whySliderGrid.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 45) {
                whySlideNext();
                resetWhyAutoplay();
            } else if (touchEndX - touchStartX > 45) {
                whySlidePrev();
                resetWhyAutoplay();
            }
        }, { passive: true });

        // Update on window resize
        window.addEventListener('resize', () => {
            updateWhySlider();
        });

        // Auto-play every 5 seconds
        let whyAutoplayTimer = setInterval(whySlideNext, 5000);

        function resetWhyAutoplay() {
            clearInterval(whyAutoplayTimer);
            whyAutoplayTimer = setInterval(whySlideNext, 5000);
        }

        // Initial state
        updateWhySlider();
    }

    // --------------------------------------------------------------------------
    // 7. Campus Gallery - Card Slider
    // --------------------------------------------------------------------------
    const gallerySliderWrap = document.querySelector('.adv-gallery-slider-wrap');
    const galleryGrid = document.querySelector('.adv-gallery-grid');
    const galleryCards = document.querySelectorAll('.adv-gallery-card');
    const galleryPrevBtn = document.querySelector('.adv-gallery-arrow.prev');
    const galleryNextBtn = document.querySelector('.adv-gallery-arrow.next');
    const galleryDots = document.querySelectorAll('.adv-gallery-dot');

    if (galleryGrid && galleryCards.length > 0) {
        let galleryCurrentIndex = 0;
        const galleryTotalCards = galleryCards.length;

        function getGalleryVisibleCards() {
            if (window.innerWidth <= 576) return 1;
            if (window.innerWidth <= 991) return 2;
            return 3;
        }

        function getGalleryMaxIndex() {
            const visible = getGalleryVisibleCards();
            return Math.max(0, galleryTotalCards - visible);
        }

        function updateGallerySlider() {
            const maxIndex = getGalleryMaxIndex();
            if (galleryCurrentIndex > maxIndex) {
                galleryCurrentIndex = 0;
            }

            // Calculate card step distance accurately
            let cardStep = 0;
            if (galleryCards.length > 1) {
                cardStep = galleryCards[1].offsetLeft - galleryCards[0].offsetLeft;
            }
            if (cardStep <= 0) {
                const gap = window.innerWidth <= 768 ? 16 : (window.innerWidth <= 991 ? 20 : 24);
                cardStep = galleryCards[0].offsetWidth + gap;
            }

            const offset = galleryCurrentIndex * cardStep;
            galleryGrid.style.transform = `translateX(-${offset}px)`;

            // Update dots
            galleryDots.forEach((dot, i) => {
                dot.classList.toggle('active', i === galleryCurrentIndex);
                dot.setAttribute('aria-selected', i === galleryCurrentIndex ? 'true' : 'false');
            });
        }

        function gallerySlideNext() {
            const maxIndex = getGalleryMaxIndex();
            galleryCurrentIndex = galleryCurrentIndex >= maxIndex ? 0 : galleryCurrentIndex + 1;
            updateGallerySlider();
        }

        function gallerySlidePrev() {
            const maxIndex = getGalleryMaxIndex();
            galleryCurrentIndex = galleryCurrentIndex <= 0 ? maxIndex : galleryCurrentIndex - 1;
            updateGallerySlider();
        }

        if (galleryNextBtn) galleryNextBtn.addEventListener('click', () => { gallerySlideNext(); resetGalleryAutoplay(); });
        if (galleryPrevBtn) galleryPrevBtn.addEventListener('click', () => { gallerySlidePrev(); resetGalleryAutoplay(); });

        galleryDots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                const maxIndex = getGalleryMaxIndex();
                galleryCurrentIndex = Math.min(i, maxIndex);
                updateGallerySlider();
                resetGalleryAutoplay();
            });
        });

        // Pause autoplay on mouse hover
        if (gallerySliderWrap) {
            gallerySliderWrap.addEventListener('mouseenter', () => clearInterval(galleryAutoplayTimer));
            gallerySliderWrap.addEventListener('mouseleave', () => resetGalleryAutoplay());
        }

        // Mobile touch swipe support
        let gTouchStartX = 0;
        let gTouchEndX = 0;
        galleryGrid.addEventListener('touchstart', (e) => {
            gTouchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        galleryGrid.addEventListener('touchend', (e) => {
            gTouchEndX = e.changedTouches[0].screenX;
            if (gTouchStartX - gTouchEndX > 45) {
                gallerySlideNext();
                resetGalleryAutoplay();
            } else if (gTouchEndX - gTouchStartX > 45) {
                gallerySlidePrev();
                resetGalleryAutoplay();
            }
        }, { passive: true });

        // Update on window resize
        window.addEventListener('resize', () => {
            updateGallerySlider();
        });

        // Auto-play every 5 seconds
        let galleryAutoplayTimer = setInterval(gallerySlideNext, 5000);

        function resetGalleryAutoplay() {
            clearInterval(galleryAutoplayTimer);
            galleryAutoplayTimer = setInterval(gallerySlideNext, 5000);
        }

        // Initial state
        updateGallerySlider();
    }

    // --------------------------------------------------------------------------
    // 8. Testimonials - Single Line Card Slider
    // --------------------------------------------------------------------------
    const testiSliderWrap = document.querySelector('.adv-testimonials-slider-wrap');
    const testiGrid = document.querySelector('.adv-testimonials-grid');
    const testiCards = document.querySelectorAll('.adv-testi-card');
    const testiPrevBtn = document.querySelector('.adv-testi-arrow.prev');
    const testiNextBtn = document.querySelector('.adv-testi-arrow.next');
    const testiDots = document.querySelectorAll('.adv-testi-dot');

    if (testiGrid && testiCards.length > 0) {
        let testiCurrentIndex = 0;
        const testiTotalCards = testiCards.length;

        function getTestiVisibleCards() {
            if (window.innerWidth <= 576) return 1;
            if (window.innerWidth <= 991) return 2;
            return 3;
        }

        function getTestiMaxIndex() {
            const visible = getTestiVisibleCards();
            return Math.max(0, testiTotalCards - visible);
        }

        function updateTestiSlider() {
            const maxIndex = getTestiMaxIndex();
            if (testiCurrentIndex > maxIndex) {
                testiCurrentIndex = maxIndex;
            }

            // Calculate card step distance accurately
            let cardStep = 0;
            if (testiCards.length > 1) {
                cardStep = testiCards[1].offsetLeft - testiCards[0].offsetLeft;
            }
            if (cardStep <= 0) {
                const gap = window.innerWidth <= 576 ? 16 : (window.innerWidth <= 991 ? 20 : 28);
                cardStep = testiCards[0].offsetWidth + gap;
            }

            const offset = testiCurrentIndex * cardStep;
            testiGrid.style.transform = `translateX(-${offset}px)`;

            // Update dots
            testiDots.forEach((dot, i) => {
                const isActive = i === testiCurrentIndex || (i >= maxIndex && testiCurrentIndex === maxIndex && i === maxIndex);
                dot.classList.toggle('active', i === testiCurrentIndex);
                dot.setAttribute('aria-selected', i === testiCurrentIndex ? 'true' : 'false');
            });

            // Toggle arrows visibility if all cards fit on screen
            if (testiPrevBtn && testiNextBtn) {
                const hasOverflow = maxIndex > 0;
                testiPrevBtn.style.opacity = hasOverflow ? '1' : '0.4';
                testiPrevBtn.style.pointerEvents = hasOverflow ? 'auto' : 'none';
                testiNextBtn.style.opacity = hasOverflow ? '1' : '0.4';
                testiNextBtn.style.pointerEvents = hasOverflow ? 'auto' : 'none';
            }
        }

        function testiSlideNext() {
            const maxIndex = getTestiMaxIndex();
            testiCurrentIndex = testiCurrentIndex >= maxIndex ? 0 : testiCurrentIndex + 1;
            updateTestiSlider();
        }

        function testiSlidePrev() {
            const maxIndex = getTestiMaxIndex();
            testiCurrentIndex = testiCurrentIndex <= 0 ? maxIndex : testiCurrentIndex - 1;
            updateTestiSlider();
        }

        if (testiNextBtn) testiNextBtn.addEventListener('click', () => { testiSlideNext(); resetTestiAutoplay(); });
        if (testiPrevBtn) testiPrevBtn.addEventListener('click', () => { testiSlidePrev(); resetTestiAutoplay(); });

        testiDots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                const maxIndex = getTestiMaxIndex();
                testiCurrentIndex = Math.min(i, maxIndex);
                updateTestiSlider();
                resetTestiAutoplay();
            });
        });

        // Pause autoplay on mouse hover
        if (testiSliderWrap) {
            testiSliderWrap.addEventListener('mouseenter', () => clearInterval(testiAutoplayTimer));
            testiSliderWrap.addEventListener('mouseleave', () => resetTestiAutoplay());
        }

        // Mobile touch swipe support
        let tTouchStartX = 0;
        let tTouchEndX = 0;
        testiGrid.addEventListener('touchstart', (e) => {
            tTouchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        testiGrid.addEventListener('touchend', (e) => {
            tTouchEndX = e.changedTouches[0].screenX;
            if (tTouchStartX - tTouchEndX > 45) {
                testiSlideNext();
                resetTestiAutoplay();
            } else if (tTouchEndX - tTouchStartX > 45) {
                testiSlidePrev();
                resetTestiAutoplay();
            }
        }, { passive: true });

        // Update on window resize
        window.addEventListener('resize', () => {
            updateTestiSlider();
        });

        // Auto-play every 6 seconds
        let testiAutoplayTimer = setInterval(testiSlideNext, 6000);

        function resetTestiAutoplay() {
            clearInterval(testiAutoplayTimer);
            testiAutoplayTimer = setInterval(testiSlideNext, 6000);
        }

        // Initial state
        updateTestiSlider();
    }
});
