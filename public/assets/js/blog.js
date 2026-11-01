/**
 * Advaita School of Excellence - Blog Page Interactivity
 * Real-time Filtering, Instant Search, Sorting & Pagination
 */
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.adv-blog-cat-btn');
    const searchInput = document.getElementById('advBlogSearch');
    const searchClearBtn = document.getElementById('advBlogSearchClear');
    const sortSelect = document.getElementById('advBlogSort');
    const postsGrid = document.getElementById('advBlogPostsGrid');
    const cards = Array.from(document.querySelectorAll('.adv-blog-card'));
    const emptyState = document.getElementById('advBlogEmpty');
    const pageButtons = document.querySelectorAll('.adv-blog-page-btn');
    const sidebarCatLinks = document.querySelectorAll('.adv-blog-cat-item a');
    const subscribeForm = document.getElementById('advBlogSubscribeForm');

    let currentFilter = 'all';
    let currentSearch = '';
    let currentSort = 'latest';

    // Apply Filter & Search
    function applyFilters() {
        let visibleCount = 0;
        const query = currentSearch.toLowerCase().trim();

        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category') || '';
            const cardTitle = (card.getAttribute('data-title') || '').toLowerCase();
            const cardExcerpt = (card.querySelector('.adv-blog-card-excerpt')?.textContent || '').toLowerCase();

            const matchesCategory = (currentFilter === 'all' || cardCat === currentFilter);
            const matchesSearch = query === '' || cardTitle.includes(query) || cardExcerpt.includes(query) || cardCat.includes(query);

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

    // Sidebar Category Links
    sidebarCatLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const cat = this.getAttribute('data-category');
            if (cat) {
                currentFilter = cat;
                filterButtons.forEach(b => {
                    if (b.getAttribute('data-category') === cat) {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });
                applyFilters();
                // Scroll to top of posts
                const mainSection = document.getElementById('advBlogMainSection');
                if (mainSection) {
                    mainSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
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
            const indexA = parseInt(a.getAttribute('data-index') || '0', 10);
            const indexB = parseInt(b.getAttribute('data-index') || '0', 10);

            if (currentSort === 'latest') return indexA - indexB;
            if (currentSort === 'oldest') return indexB - indexA;
            if (currentSort === 'title-asc') return titleA.localeCompare(titleB);
            return 0;
        });

        sorted.forEach(card => postsGrid.appendChild(card));
    }

    // Pagination
    pageButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const isPrev = this.classList.contains('prev-btn');
            const isNext = this.classList.contains('next-btn');

            let activePageBtn = document.querySelector('.adv-blog-page-btn.active');
            let currentPageNum = activePageBtn ? parseInt(activePageBtn.textContent.trim(), 10) : 1;

            if (isPrev) {
                if (currentPageNum > 1) setPage(currentPageNum - 1);
            } else if (isNext) {
                if (currentPageNum < 5) setPage(currentPageNum + 1);
            } else {
                const targetPage = parseInt(this.textContent.trim(), 10);
                if (!isNaN(targetPage)) setPage(targetPage);
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

        const mainSection = document.getElementById('advBlogMainSection');
        if (mainSection) {
            mainSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Newsletter subscription form
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const input = this.querySelector('.adv-blog-email-input');
            const btn = this.querySelector('.adv-blog-subscribe-btn');
            if (input && input.value) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Subscribed!';
                btn.style.background = '#16a34a';
                setTimeout(() => {
                    input.value = '';
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                }, 3000);
            }
        });
    }

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
