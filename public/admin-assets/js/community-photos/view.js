var CommunityPhoto = (function() {
    var data_table;
    var allRecords = [];
    var currentIndex = -1;
    var currentView = 'gallery'; // 'gallery' or 'list'

    return {
        /**
         * Initialization.
         */
        init: function() {
            CommunityPhoto.getCommunityPhotos();
            CommunityPhoto.bindEventHandlers();
            CommunityPhoto.viewPhotos();
        },

        /**
         * Get Community Photos list and setup DataTables.
         */
        getCommunityPhotos: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML = '#';
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<span class="cp-nav-arrow"><i class="fa fa-angle-left"></i> Previous</span>',
                        sNext: '<span class="cp-nav-arrow">Next <i class="fa fa-angle-right"></i></span>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sInfoEmpty: "Showing 0 of 0 photos",
                    sInfoFiltered: "(filtered from _MAX_ total photos)",
                    sSearch: '<i data-feather="search"></i>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_",
                    sEmptyTable: CommunityPhoto.getEmptyStateHtml(),
                    sZeroRecords: CommunityPhoto.getZeroRecordsHtml()
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom: 'rt<"cp-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.name = $("#cp-filter-member").val();
                        d.date = $("#cp-filter-date").val();
                        d.sort_order = $("#cp-filter-sort").val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "serial_no",
                        searchable: false,
                        sortable: false,
                        width: 50,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { 
                        data: "name", 
                        name: "name", 
                        width: 160,
                        render: function(data, type, row) {
                            var avatarHtml = row.avatar ? 
                                '<img src="' + row.avatar + '" class="cp-table-avatar" alt="Avatar">' :
                                '<div class="cp-table-avatar-fallback">' + (data ? data.charAt(0).toUpperCase() : 'U') + '</div>';
                            return '<div class="d-flex align-items-center gap-2">' + avatarHtml + '<span class="fw-semibold text-dark">' + (data || 'N/A') + '</span></div>';
                        }
                    },
                    { 
                        data: "message", 
                        name: "message",
                        render: function(data) {
                            return '<span class="text-secondary">' + (data ? data : '<em>No message provided</em>') + '</span>';
                        }
                    },
                    { data: "view_photos", name: "view_photos", width: 140 },
                    { data: "date_time", name: "date_time", width: 160 }
                ]
            });

            // Handle table draw event
            table.on("draw", function() {
                allRecords = table.rows().data().toArray();
                var pageInfo = table.page.info();
                var total = pageInfo.recordsTotal || 0;

                // Sync counts
                $('#cp-records-count').text(total + ' photos');
                $('#cp-stat-total').text(total);

                // Render Gallery
                CommunityPhoto.renderGallery();

                // Select first item if available
                if (allRecords.length > 0) {
                    if (currentIndex < 0 || currentIndex >= allRecords.length) {
                        CommunityPhoto.selectPhoto(0);
                    } else {
                        CommunityPhoto.selectPhoto(currentIndex);
                    }
                } else {
                    CommunityPhoto.resetPreview();
                }
            });
        },

        /**
         * Render Gallery Grid from DataTables records
         */
        renderGallery: function() {
            var $galleryWrap = $("#cp-gallery-grid");
            var $emptyWrap = $("#cp-gallery-empty");

            if (!allRecords || allRecords.length === 0) {
                $galleryWrap.hide();
                $emptyWrap.show();
                return;
            }

            $emptyWrap.hide();
            $galleryWrap.show().empty();

            $.each(allRecords, function(idx, item) {
                var imgSrc = item.first_image || (item.images && item.images.length > 0 ? item.images[0] : '');
                var isSelected = (idx === currentIndex) ? 'active' : '';
                var avatarHtml = item.avatar ? 
                    '<img src="' + item.avatar + '" class="cp-card-avatar" alt="Avatar">' :
                    '<div class="cp-card-avatar-fallback">' + (item.name ? item.name.charAt(0).toUpperCase() : 'U') + '</div>';

                var badgeHtml = (item.images_count > 1) ? 
                    '<span class="cp-card-photo-count"><i class="fa fa-camera"></i> ' + item.images_count + ' photos</span>' : '';

                var cardHtml = `
                    <div class="cp-gallery-card ${isSelected}" data-index="${idx}">
                        <div class="cp-card-header">
                            ${avatarHtml}
                            <div class="cp-card-user-info">
                                <div class="cp-card-name" title="${item.name || ''}">${item.name || 'Member'}</div>
                                <div class="cp-card-time">${item.relative_time || item.date_formatted || ''}</div>
                            </div>
                        </div>
                        <div class="cp-card-image-wrap">
                            ${imgSrc ? 
                                `<img src="${imgSrc}" class="cp-card-thumb" alt="Upload" loading="lazy">` : 
                                `<div class="cp-card-placeholder"><i class="fa fa-picture-o"></i></div>`
                            }
                            ${badgeHtml}
                        </div>
                        ${item.message ? `<div class="cp-card-message" title="${item.message}">${item.message}</div>` : `<div class="cp-card-message empty-msg">No caption</div>`}
                    </div>
                `;
                $galleryWrap.append(cardHtml);
            });
        },

        /**
         * Select a photo by index and update the Right Preview Card
         */
        selectPhoto: function(idx) {
            if (!allRecords || idx < 0 || idx >= allRecords.length) {
                CommunityPhoto.resetPreview();
                return;
            }

            currentIndex = idx;
            var item = allRecords[idx];

            // Update Gallery card active state
            $(".cp-gallery-card").removeClass("active");
            $(".cp-gallery-card[data-index='" + idx + "']").addClass("active");

            // Update Table row active state
            $("#dataTable tbody tr").removeClass("selected");
            $($("#dataTable tbody tr").get(idx)).addClass("selected");

            // Update Right Preview Box
            var imgSrc = item.first_image || (item.images && item.images.length > 0 ? item.images[0] : '');
            var $box = $("#cp-preview-visual");

            if (imgSrc) {
                var carouselDots = '';
                if (item.images && item.images.length > 1) {
                    carouselDots = '<div class="cp-preview-dots mt-2 text-center">';
                    $.each(item.images, function(i, url) {
                        var dotActive = (i === 0) ? 'active' : '';
                        carouselDots += `<span class="cp-preview-dot ${dotActive}" data-img="${url}"></span>`;
                    });
                    carouselDots += '</div>';
                }

                $box.html(`
                    <div class="cp-preview-img-container">
                        <img src="${imgSrc}" id="cp-main-preview-img" class="cp-preview-img" alt="Preview">
                        <button type="button" class="cp-btn-zoom view-photos" data-url="${item.view_photos_url}" title="View Fullscreen">
                            <i class="fa fa-arrows-alt"></i>
                        </button>
                    </div>
                    ${carouselDots}
                `);
            } else {
                $box.html(`
                    <div class="cp-preview-empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                        <p class="mb-0 mt-2 text-muted fw-semibold">No image available</p>
                    </div>
                `);
            }

            // Update Metadata
            $("#cp-meta-member").text(item.name || '—');
            $("#cp-meta-uploaded").text(item.date_formatted || item.date_time || '—');
            $("#cp-meta-message").text(item.message || 'No message provided');

            // Update Status Banner
            $("#cp-meta-status").html('<span class="cp-status-dot-active">●</span> Mobile upload synced');
        },

        /**
         * Reset Right Preview Card to placeholder state
         */
        resetPreview: function() {
            currentIndex = -1;
            $("#cp-preview-visual").html(`
                <div class="cp-preview-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                    </svg>
                    <p class="mb-0 mt-2 text-muted fw-semibold">Select a photo to preview</p>
                </div>
            `);
            $("#cp-meta-member").text('—');
            $("#cp-meta-uploaded").text('—');
            $("#cp-meta-message").text('—');
            $("#cp-meta-status").html('<i class="fa fa-clock-o text-muted"></i> Waiting for the first mobile upload');
        },

        /**
         * Empty state markup for zero photos
         */
        getEmptyStateHtml: function() {
            return `
                <div class="cp-empty-illustration-wrap">
                    <div class="cp-empty-icon-circle">
                        <div class="cp-sparkle-dot s1">✦</div>
                        <div class="cp-sparkle-dot s2">✦</div>
                        <div class="cp-sparkle-dot s3">✦</div>
                        <div class="cp-phone-sync-icon">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect>
                                <path d="M12 18h.01"></path>
                            </svg>
                            <span class="cp-phone-badge-sync">
                                <i class="fa fa-refresh"></i>
                            </span>
                        </div>
                    </div>
                    <h4 class="cp-empty-heading">No community photos yet</h4>
                    <p class="cp-empty-text">Photos shared through the Fit Coach Club mobile app will appear here automatically. No admin upload is required.</p>
                    <button type="button" class="cp-btn-check cp-check-photos-btn">
                        <i class="fa fa-refresh"></i> Check for new photos
                    </button>
                </div>
            `;
        },

        /**
         * Zero records markup when search filters return no results
         */
        getZeroRecordsHtml: function() {
            return `
                <div class="cp-empty-illustration-wrap">
                    <div class="cp-empty-icon-circle" style="background: #f1f5f9; border-color: #e2e8f0;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h4 class="cp-empty-heading">No matching community photos</h4>
                    <p class="cp-empty-text">Try changing your search term or clearing date filters.</p>
                </div>
            `;
        },

        /**
         * Bind UI Event Handlers
         */
        bindEventHandlers: function() {
            // View Mode Switching (Gallery vs List)
            $("#cp-view-gallery").on("click", function() {
                currentView = 'gallery';
                $(this).addClass("active").removeClass("cp-btn-outline");
                $("#cp-view-list").removeClass("active").addClass("cp-btn-outline");
                $("#cp-gallery-section").show();
                $("#cp-list-section").hide();
                CommunityPhoto.renderGallery();
            });

            $("#cp-view-list").on("click", function() {
                currentView = 'list';
                $(this).addClass("active").removeClass("cp-btn-outline");
                $("#cp-view-gallery").removeClass("active").addClass("cp-btn-outline");
                $("#cp-gallery-section").hide();
                $("#cp-list-section").show();
            });

            // Live Search Integration
            $("#cp-search-input").on("keyup input", function() {
                data_table.search($(this).val()).draw();
            });

            // Date & Sort Filters
            $("#cp-filter-date, #cp-filter-sort, #cp-filter-member").on("change", function() {
                data_table.ajax.reload();
            });

            // Page length dropdown
            $("#cp-per-page").on("change", function() {
                data_table.page.len(parseInt($(this).val(), 10)).draw();
            });

            // Refresh Buttons
            $("#cp-btn-refresh, .cp-check-photos-btn").on("click", function(e) {
                e.preventDefault();
                var $icon = $(this).find(".fa-refresh, svg");
                $icon.addClass("fa-spin");
                data_table.ajax.reload(function() {
                    setTimeout(function() {
                        $icon.removeClass("fa-spin");
                    }, 500);
                }, false);
            });

            // Click on Gallery Card to select photo
            $(document).on("click", ".cp-gallery-card", function() {
                var idx = parseInt($(this).data("index"), 10);
                CommunityPhoto.selectPhoto(idx);
            });

            // Click on Table Row to select photo
            $(document).on("click", "#dataTable tbody tr", function() {
                var idx = $(this).index();
                CommunityPhoto.selectPhoto(idx);
            });

            // Previous and Next Nav in Preview Header
            $("#cp-preview-prev").on("click", function() {
                if (allRecords.length === 0) return;
                var newIdx = (currentIndex <= 0) ? allRecords.length - 1 : currentIndex - 1;
                CommunityPhoto.selectPhoto(newIdx);
            });

            $("#cp-preview-next").on("click", function() {
                if (allRecords.length === 0) return;
                var newIdx = (currentIndex >= allRecords.length - 1) ? 0 : currentIndex + 1;
                CommunityPhoto.selectPhoto(newIdx);
            });

            // Thumbnail dot switching in preview
            $(document).on("click", ".cp-preview-dot", function() {
                var imgUrl = $(this).data("img");
                $("#cp-main-preview-img").attr("src", imgUrl);
                $(".cp-preview-dot").removeClass("active");
                $(this).addClass("active");
            });
        },

        /**
         * View Photos in Modal.
         */
        viewPhotos: function () {
            $(document).on("click", ".view-photos", function () {
                var $this = $(this);
                var $modal = $("#pageModal");

                $modal.modal("show");
                $modal.find(".modal-content").load($this.data("url"), "", function () {});
                $modal.on("hidden.bs.modal", function () {
                    App.resetModal($modal);
                });
            });
        }
    };
})();

CommunityPhoto.init();
