/**
 * Advaita School of Excellence - Gallery Page Interactivity
 * Filtering, Instant Search, Sorting, Pagination & Lightbox Viewer
 */
document.addEventListener('DOMContentLoaded', function () {
    // Gallery Elements
    const filterButtons = document.querySelectorAll('.adv-gal-cat-btn');
    const searchInput = document.getElementById('advGallerySearch');
    const searchClearBtn = document.getElementById('advSearchClear');
    const sortSelect = document.getElementById('advGallerySort');
    const cardsGrid = document.getElementById('advGalleryGrid');
    const cards = Array.from(document.querySelectorAll('.adv-gal-card'));
    const emptyState = document.getElementById('advGalleryEmpty');
    const pageButtons = document.querySelectorAll('.adv-gal-page-btn');

    // Lightbox Elements
    const lightbox = document.getElementById('advGalleryLightbox');
    const lightboxImg = document.getElementById('advLightboxImg');
    const lightboxTitle = document.getElementById('advLightboxTitle');
    const lightboxCounter = document.getElementById('advLightboxCounter');
    const lightboxClose = document.getElementById('advLightboxClose');
    const lightboxPrev = document.getElementById('advLightboxPrev');
    const lightboxNext = document.getElementById('advLightboxNext');
    const lightboxThumbs = document.getElementById('advLightboxThumbs');

    let currentFilter = 'all';
    let currentSearch = '';
    let currentSort = 'latest';
    let currentAlbumCards = [];
    let currentLightboxIndex = 0;

    // Per-card photo collections for a rich, specific lightbox experience
    const cardGalleries = {
        '1': [
            { src: 'assets/images/gallery/gallery-math-science-lab.jpg', caption: 'Interactive Mathematics & Practical Lab Experiments' },
            { src: 'assets/images/gallery/gallery-classroom-learning.jpg', caption: 'Interactive Classroom Learning & Smart Classrooms' },
            { src: 'assets/images/gallery/gallery-foundation-achievers.jpg', caption: 'Focused Academic Guidance & Student Mentorship' }
        ],
        '2': [
            { src: 'assets/images/gallery/gallery-school-band.jpg', caption: 'School Marching Band & Assembly Celebrations' },
            { src: 'assets/images/gallery/cultural.jpg', caption: 'Vibrant Cultural & Annual Day Celebrations' },
            { src: 'assets/images/gallery/dance.jpg', caption: 'Classical Folk Dance Performances' }
        ],
        '3': [
            { src: 'assets/images/gallery/gallery-football-match-sprint.jpg', caption: 'Inter-House Football Championship & Turf Sprint' },
            { src: 'assets/images/gallery/gallery-football-juggling-boy.jpg', caption: 'Football Freestyle & Precision Ball Control' },
            { src: 'assets/images/gallery/gallery-sports-field-agility.jpg', caption: 'Expansive Campus Sports Field & Obstacle Courses' }
        ],
        '4': [
            { src: 'assets/images/gallery/gallery-art-studio-origami-planes.jpg', caption: 'Art Studio: Creative Origami Paper Planes with Mentors' },
            { src: 'assets/images/gallery/gallery-painted-hands-girl.jpg', caption: 'Joyful Hand Painting & Artistic Self Expression' },
            { src: 'assets/images/gallery/gallery-art-circle-kids.jpg', caption: 'Collaborative Creative Arts & Color Day' }
        ],
        '5': [
            { src: 'assets/images/gallery/gallery-parachute-lawn-building.jpg', caption: 'Teamwork & Parachute Fun on Lush Campus Greens' },
            { src: 'assets/images/gallery/gallery-parachute-activity.jpg', caption: 'Outdoor Cooperative Play & High-Energy Activities' },
            { src: 'assets/images/gallery/gallery-crawl-tunnel-kids.jpg', caption: 'Active Kindergarten Team Exercises' }
        ],
        '6': [
            { src: 'assets/images/gallery/gallery-splash-pool-kids-play.jpg', caption: 'Early Years Aquatic Splash Pool & Water Play Arena' },
            { src: 'assets/images/gallery/gallery-splash-pool-fun.jpg', caption: 'Supervised Splash Pool Fun & Activities' },
            { src: 'assets/images/gallery/campus-building.png', caption: 'Modern Architecture & Green Campus Infrastructure' }
        ],
        '7': [
            { src: 'assets/images/gallery/gallery-hula-hoop-kid.jpg', caption: 'Agility Obstacle Course & Ring Stepping Activity' },
            { src: 'assets/images/gallery/gallery-crawl-tunnel-kids.jpg', caption: 'Caterpillar Tunnel Crawl & Motor Agility' },
            { src: 'assets/images/gallery/gallery-sports-field-agility.jpg', caption: 'Outdoor Physical Agility Training' }
        ],
        '8': [
            { src: 'assets/images/gallery/gallery-crawl-tunnel-kids.jpg', caption: 'Caterpillar Tunnel Crawl & Motor Agility' },
            { src: 'assets/images/gallery/gallery-hula-hoop-kid.jpg', caption: 'Obstacle Coordination & Balance' },
            { src: 'assets/images/gallery/gallery-parachute-lawn-building.jpg', caption: 'Fun-Filled Outdoor Games & Exploration' }
        ],
        '9': [
            { src: 'assets/images/gallery/gallery-gymnastics-cartwheel.jpg', caption: 'Lawn Gymnastics, Cartwheels & Body Conditioning' },
            { src: 'assets/images/gallery/gallery-sports-field-agility.jpg', caption: 'Athletics & Multi-Sport Arena' },
            { src: 'assets/images/gallery/gallery-football-match-sprint.jpg', caption: 'Speed Drills & Agility Training' }
        ],
        '10': [
            { src: 'assets/images/gallery/gallery-football-juggling-boy.jpg', caption: 'Freestyle Ball Control & Soccer Drills on Field' },
            { src: 'assets/images/gallery/gallery-football-match-sprint.jpg', caption: 'Competitive Football Matches & Athletics' },
            { src: 'assets/images/gallery/gallery-sports-field-agility.jpg', caption: 'Campus Sports Arena & Conditioning' }
        ],
        '11': [
            { src: 'assets/images/gallery/gallery-painted-hands-girl.jpg', caption: 'Joyful Hand Painting & Artistic Self Expression' },
            { src: 'assets/images/gallery/gallery-art-circle-kids.jpg', caption: 'Collaborative Creative Arts & Color Day' },
            { src: 'assets/images/gallery/gallery-art-studio-origami-planes.jpg', caption: 'Art Studio & Origami Creations' }
        ],
        '12': [
            { src: 'assets/images/gallery/gallery-art-circle-kids.jpg', caption: 'Celebration of Colors: Circular Painted Hands Activity' },
            { src: 'assets/images/gallery/gallery-painted-hands-girl.jpg', caption: 'Creative Smiles & Expressive Art' },
            { src: 'assets/images/gallery/gallery-art-studio-origami-planes.jpg', caption: 'Art Studio Workshop with Students' }
        ],
        '13': [
            { src: 'assets/images/gallery/gallery-classroom-learning.jpg', caption: 'Interactive Classroom Learning & Smart Classrooms' },
            { src: 'assets/images/gallery/gallery-math-science-lab.jpg', caption: 'Modern Experiential Learning Environment' },
            { src: 'assets/images/gallery/academic-learning.png', caption: 'Focused Academic Guidance & Study Environment' }
        ],
        '14': [
            { src: 'assets/images/gallery/gallery-foundation-achievers.jpg', caption: 'IIT-JEE & NEET Integrated Foundation Achievers' },
            { src: 'assets/images/gallery/gallery-math-science-lab.jpg', caption: 'Hands-on Science & Practical Lab Workshops' },
            { src: 'assets/images/gallery/gallery-classroom-learning.jpg', caption: 'Rigorous Academic Coaching & Mentoring' }
        ],
        '15': [
            { src: 'assets/images/gallery/gallery-parachute-activity.jpg', caption: 'Multi-Color Parachute Coordination & Team Synergy' },
            { src: 'assets/images/gallery/gallery-parachute-lawn-building.jpg', caption: 'Campus Lawn Games & Outdoor Merriment' },
            { src: 'assets/images/gallery/gallery-crawl-tunnel-kids.jpg', caption: 'Early Years Active Group Play' }
        ],
        '16': [
            { src: 'assets/images/gallery/gallery-sports-field-agility.jpg', caption: 'Expansive Campus Sports Field & Athletics' },
            { src: 'assets/images/gallery/gallery-football-match-sprint.jpg', caption: 'Football Matches on Green Turf' },
            { src: 'assets/images/gallery/gallery-gymnastics-cartwheel.jpg', caption: 'Lawn Gymnastics & Physical Education' }
        ]
    };

    // Fallback category photo collections
    const albumGalleries = {
        'academic': [
            { src: 'assets/images/gallery/gallery-math-science-lab.jpg', caption: 'Mathematics & Practical Science Labs' },
            { src: 'assets/images/gallery/gallery-classroom-learning.jpg', caption: 'Interactive Classroom Learning & Smart Classrooms' },
            { src: 'assets/images/gallery/gallery-foundation-achievers.jpg', caption: 'IIT-JEE & NEET Foundation Scholars' }
        ],
        'events': [
            { src: 'assets/images/gallery/gallery-school-band.jpg', caption: 'School Marching Band & Assembly Celebrations' },
            { src: 'assets/images/gallery/cultural.jpg', caption: 'Vibrant Cultural Celebrations' },
            { src: 'assets/images/gallery/dance.jpg', caption: 'Classical Folk Dance at Annual Fest' }
        ],
        'sports': [
            { src: 'assets/images/gallery/gallery-football-match-sprint.jpg', caption: 'Inter-House Football Tournament & Turf Sprint' },
            { src: 'assets/images/gallery/gallery-football-juggling-boy.jpg', caption: 'Football Freestyle Skills & Agility' },
            { src: 'assets/images/gallery/gallery-gymnastics-cartwheel.jpg', caption: 'Gymnastics & Tumbling on Lawn' },
            { src: 'assets/images/gallery/gallery-sports-field-agility.jpg', caption: 'Sports Arena & Athletic Field' },
            { src: 'assets/images/gallery/gallery-hula-hoop-kid.jpg', caption: 'Obstacle Course & Agility Drills' }
        ],
        'arts': [
            { src: 'assets/images/gallery/gallery-art-studio-origami-planes.jpg', caption: 'Art Studio Origami & Craft Workshops' },
            { src: 'assets/images/gallery/gallery-painted-hands-girl.jpg', caption: 'Creative Hand Painting & Self Expression' },
            { src: 'assets/images/gallery/gallery-art-circle-kids.jpg', caption: 'Joyful Colors & Art Collaboration Circle' },
            { src: 'assets/images/gallery/arts.jpg', caption: 'Fine Arts Studio & Canvas Work' }
        ],
        'student-life': [
            { src: 'assets/images/gallery/gallery-parachute-lawn-building.jpg', caption: 'Lawn Parachute Activities & Team Building' },
            { src: 'assets/images/gallery/gallery-crawl-tunnel-kids.jpg', caption: 'Kindergarten Caterpillar Tunnel Fun' },
            { src: 'assets/images/gallery/gallery-parachute-activity.jpg', caption: 'Rainbow Parachute Active Play' }
        ],
        'infrastructure': [
            { src: 'assets/images/gallery/gallery-splash-pool-kids-play.jpg', caption: 'Splash Pool & Kids Aquatic Play Park' },
            { src: 'assets/images/gallery/gallery-splash-pool-fun.jpg', caption: 'Early Childhood Splash Pool Facility' },
            { src: 'assets/images/gallery/campus-building.png', caption: 'Advaita School Modern Academic Wings' },
            { src: 'assets/images/fac-library.jpg', caption: 'Resource-rich Central Library' }
        ],
        'workshops': [
            { src: 'assets/images/gallery/gallery-math-science-lab.jpg', caption: 'Math & Geometry Hands-On Workshop' },
            { src: 'assets/images/gallery/gallery-art-studio-origami-planes.jpg', caption: 'Art Studio Origami Workshop' },
            { src: 'assets/images/gallery/robotics-lab.png', caption: 'Robotics & AI Innovation Lab' },
            { src: 'assets/images/gallery/science-lab.png', caption: 'Modern Practical Science Labs' }
        ],
        'outreach': [
            { src: 'assets/images/gallery/outreach.jpg', caption: 'Community Outreach & Social Service Drives' },
            { src: 'assets/images/gallery/educational-tour.jpg', caption: 'Field Trips & Community Learning' }
        ]
    };

    // Filter & Search handler
    function applyFilters() {
        let visibleCount = 0;
        const query = currentSearch.toLowerCase().trim();

        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            const cardTitle = (card.getAttribute('data-title') || '').toLowerCase();
            const cardCount = (card.getAttribute('data-count') || '').toLowerCase();

            const matchesCategory = (currentFilter === 'all' || cardCat === currentFilter);
            const matchesSearch = query === '' || cardTitle.includes(query) || cardCat.includes(query) || cardCount.includes(query);

            if (matchesCategory && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.classList.add('visible');
            } else {
                emptyState.classList.remove('visible');
            }
        }
    }

    // Category button clicks
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-category') || 'all';
            applyFilters();
        });
    });

    // Search input
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            currentSearch = this.value;
            if (searchClearBtn) {
                if (currentSearch.length > 0) {
                    searchClearBtn.classList.add('visible');
                } else {
                    searchClearBtn.classList.remove('visible');
                }
            }
            applyFilters();
        });
    }

    if (searchClearBtn) {
        searchClearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                currentSearch = '';
                searchClearBtn.classList.remove('visible');
                applyFilters();
                searchInput.focus();
            }
        });
    }

    // Sort Handler
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            currentSort = this.value;
            sortCards();
        });
    }

    function sortCards() {
        const sorted = cards.slice().sort((a, b) => {
            const titleA = a.getAttribute('data-title') || '';
            const titleB = b.getAttribute('data-title') || '';
            const countA = parseInt(a.getAttribute('data-count') || '0', 10);
            const countB = parseInt(b.getAttribute('data-count') || '0', 10);
            const indexA = parseInt(a.getAttribute('data-index') || '0', 10);
            const indexB = parseInt(b.getAttribute('data-index') || '0', 10);

            if (currentSort === 'latest') return indexA - indexB;
            if (currentSort === 'oldest') return indexB - indexA;
            if (currentSort === 'photos-desc') return countB - countA;
            if (currentSort === 'title-asc') return titleA.localeCompare(titleB);
            return 0;
        });

        sorted.forEach(card => cardsGrid.appendChild(card));
    }

    // Pagination Click handler
    pageButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;

            const isPrev = this.classList.contains('prev-btn');
            const isNext = this.classList.contains('next-btn');

            let activePageBtn = document.querySelector('.adv-gal-page-btn.active');
            let currentPageNum = activePageBtn ? parseInt(activePageBtn.textContent.trim(), 10) : 1;

            if (isPrev) {
                if (currentPageNum > 1) {
                    setPage(currentPageNum - 1);
                }
            } else if (isNext) {
                if (currentPageNum < 5) {
                    setPage(currentPageNum + 1);
                }
            } else {
                const targetPage = parseInt(this.textContent.trim(), 10);
                if (!isNaN(targetPage)) {
                    setPage(targetPage);
                }
            }
        });
    });

    function setPage(pageNum) {
        pageButtons.forEach(b => {
            if (!b.classList.contains('prev-btn') && !b.classList.contains('next-btn')) {
                if (parseInt(b.textContent.trim(), 10) === pageNum) {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            }
        });

        // Smooth scroll to top of gallery section
        const gridSection = document.getElementById('advGalleryGridSection');
        if (gridSection) {
            gridSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // =========================================================================
    // Lightbox Implementation
    // =========================================================================
    function openLightbox(card) {
        const category = card.getAttribute('data-category') || 'academic';
        const title = card.getAttribute('data-title') || 'Album';
        const cardIndex = card.getAttribute('data-index');
        const mainImg = card.querySelector('.adv-gal-card-thumb img');
        const mainSrc = mainImg ? mainImg.getAttribute('src') : '';

        // Build list of photos: card-specific first, then category fallback
        let photos = (cardIndex && cardGalleries[cardIndex]) ? cardGalleries[cardIndex] : (albumGalleries[category] || []);
        if (!photos.length || photos[0].src !== mainSrc) {
            photos = [{ src: mainSrc, caption: title }, ...photos.filter(p => p.src !== mainSrc)];
        }

        currentAlbumCards = photos;
        currentLightboxIndex = 0;

        lightboxTitle.textContent = title;
        buildLightboxThumbs();
        showLightboxPhoto(0);

        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function showLightboxPhoto(index) {
        if (!currentAlbumCards.length) return;
        currentLightboxIndex = (index + currentAlbumCards.length) % currentAlbumCards.length;
        const photo = currentAlbumCards[currentLightboxIndex];

        lightboxImg.src = photo.src;
        lightboxImg.alt = photo.caption || 'Advaita Gallery Photo';
        lightboxCounter.textContent = `${currentLightboxIndex + 1} / ${currentAlbumCards.length}`;

        // Update active thumb
        const thumbs = lightboxThumbs.querySelectorAll('.adv-gal-lightbox-thumb-item');
        thumbs.forEach((t, i) => {
            if (i === currentLightboxIndex) {
                t.classList.add('active');
            } else {
                t.classList.remove('active');
            }
        });
    }

    function buildLightboxThumbs() {
        lightboxThumbs.innerHTML = '';
        currentAlbumCards.forEach((photo, idx) => {
            const thumb = document.createElement('div');
            thumb.className = `adv-gal-lightbox-thumb-item ${idx === 0 ? 'active' : ''}`;
            thumb.innerHTML = `<img src="${photo.src}" alt="Thumb ${idx + 1}">`;
            thumb.addEventListener('click', () => showLightboxPhoto(idx));
            lightboxThumbs.appendChild(thumb);
        });
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Attach card click handlers
    cards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Open on card or card button click
            openLightbox(this);
        });
    });

    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            showLightboxPhoto(currentLightboxIndex - 1);
        });
    }

    if (lightboxNext) {
        lightboxNext.addEventListener('click', (e) => {
            e.stopPropagation();
            showLightboxPhoto(currentLightboxIndex + 1);
        });
    }

    // Close on backdrop click (outside image)
    if (lightbox) {
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox || e.target.classList.contains('adv-gal-lightbox-stage')) {
                closeLightbox();
            }
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') showLightboxPhoto(currentLightboxIndex - 1);
        if (e.key === 'ArrowRight') showLightboxPhoto(currentLightboxIndex + 1);
    });

    // Mobile Navbar Drawer Handler
    const mobileToggle = document.getElementById('advNavMobileToggle');
    const drawerClose = document.getElementById('advDrawerClose');
    const drawer = document.getElementById('advNavbarDrawer');
    const backdrop = document.getElementById('advDrawerBackdrop');

    if (mobileToggle && drawer && backdrop) {
        mobileToggle.addEventListener('click', () => {
            drawer.classList.add('is-open');
            backdrop.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        });
    }

    function closeMobileNav() {
        if (drawer) drawer.classList.remove('is-open');
        if (backdrop) backdrop.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (drawerClose) drawerClose.addEventListener('click', closeMobileNav);
    if (backdrop) backdrop.addEventListener('click', closeMobileNav);
});
