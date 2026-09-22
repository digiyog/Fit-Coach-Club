window.CommunityPhoto = (function() {
    var data_table;
    var table;
    var allRecords = [];
    var currentIndex = -1;
    var searchTimer = null;

    return {
        init: function() {
            CommunityPhoto.getCommunityPhotos();
            CommunityPhoto.bindEventHandlers();
            CommunityPhoto.viewPhotos();
        },

        getCommunityPhotos: function() {
            var $dataTable = $("#dataTable");
            if ($dataTable.length === 0) return;

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {
                    var ths = e.getElementsByTagName("th");
                    if (ths.length > 0) ths[0].innerHTML = '#';
                },
                oLanguage: {
                    sEmptyTable: 'No photos uploaded yet',
                    sZeroRecords: 'No matching photos found'
                },
                processing: true,
                serverSide: true,
                pageLength: 20,
                dom: 'rt',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.date = $("#cp-filter-date").val() || "all";
                        d.sort_order = $("#cp-filter-sort").val() || "newest";
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "serial_no",
                        searchable: false,
                        sortable: false,
                        width: 50,
                        className: "text-center fw-bold text-muted",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "name",
                        name: "name",
                        width: 200,
                        render: function(data, type, row) {
                            var initial = (data && data.trim().length > 0) ? data.trim().charAt(0).toUpperCase() : 'U';
                            var colors = [
                                { bg: '#eff6ff', color: '#2563eb' },
                                { bg: '#f5f3ff', color: '#7c3aed' },
                                { bg: '#ecfdf5', color: '#059669' },
                                { bg: '#fff7ed', color: '#ea580c' },
                                { bg: '#fdf2f8', color: '#db2777' },
                                { bg: '#fefce8', color: '#ca8a04' }
                            ];
                            var charCode = (data && data.length > 0) ? data.charCodeAt(0) + data.length : 0;
                            var col = colors[charCode % colors.length];

                            var fallbackHtml = '<div class="cp-table-avatar-fallback" style="background: ' + col.bg + '; color: ' + col.color + '; font-weight: 700; width: 32px; height: 32px; min-width: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">' + initial + '</div>';

                            var avatarHtml;
                            if (row && row.avatar && row.avatar.trim() !== '') {
                                avatarHtml = '<div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px; flex-shrink: 0;">' +
                                    fallbackHtml +
                                    '<img src="' + row.avatar + '" class="cp-table-avatar" alt="" style="position: absolute; top: 0; left: 0; width: 32px; height: 32px; border-radius: 50%; object-fit: cover; z-index: 2;" onerror="this.remove();" />' +
                                '</div>';
                            } else {
                                avatarHtml = fallbackHtml;
                            }

                            return '<div class="d-flex align-items-center gap-2">' +
                                   avatarHtml +
                                   '<div><div class="fw-bold text-dark" style="font-size: 13px;">' + (data || 'N/A') + '</div></div>' +
                                   '</div>';
                        }
                    },
                    {
                        data: "message",
                        name: "message",
                        render: function(data) {
                            if (!data || data === 'N/A' || data.trim() === '') {
                                return '<span class="text-muted fst-italic" style="font-size: 12px;">No caption provided</span>';
                            }
                            return '<span class="text-secondary" style="font-size: 12.5px; line-height: 1.4;">' + data + '</span>';
                        }
                    },
                    {
                        data: "view_photos",
                        name: "view_photos",
                        width: 140,
                        render: function(data, type, row) {
                            var url = (row && row.view_photos_url) ? row.view_photos_url : '';
                            var count = (row && row.images_count) ? row.images_count : 1;
                            var thumb = (row && row.first_image) ?
                                '<img src="' + row.first_image + '" style="width: 34px; height: 34px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0;" alt="Photo">' :
                                '';

                            if (url) {
                                return '<div class="d-flex align-items-center gap-2">' +
                                       thumb +
                                       '<a href="javascript:void(0);" data-url="' + url + '" class="view-photos btn btn-sm btn-outline-primary py-1 px-2" style="border-radius: 8px; font-weight: 600; font-size: 11.5px; display: inline-flex; align-items: center; gap: 4px;"><i class="fa fa-eye"></i> View (' + count + ')</a>' +
                                       '</div>';
                            }
                            return data || '<span class="text-muted">No photos</span>';
                        }
                    },
                    {
                        data: "date_time",
                        name: "date_time",
                        width: 160,
                        render: function(data, type, row) {
                            var formatted = (row && row.date_formatted) ? row.date_formatted : data;
                            var rel = (row && row.relative_time) ? '<div class="text-muted" style="font-size: 11px;">' + row.relative_time + '</div>' : '';
                            return '<div><div style="font-size: 12px; font-weight: 600; color: #334155;">' + (formatted || 'N/A') + '</div>' + rel + '</div>';
                        }
                    }
                ]
            });

            table.on("draw", function() {
                allRecords = table.rows().data().toArray();
                var pageInfo = table.page.info();
                var total = pageInfo.recordsTotal || 0;
                var current = pageInfo.page + 1;
                var totalPages = pageInfo.pages || 1;

                // Sync counts
                $("#cp-records-count").text(total + " photos");
                $("#cp-stat-total").text(total);

                // Sync custom footer info & pagination
                if (total === 0) {
                    $("#cp-footer-info").text("Showing 0 of 0 photos");
                    $("#cp-curr-page").text("1");
                    $("#cp-prev-page").prop("disabled", true);
                    $("#cp-next-page").prop("disabled", true);
                } else {
                    var start = pageInfo.start + 1;
                    var end = pageInfo.end;
                    $("#cp-footer-info").text("Showing " + start + " to " + end + " of " + total + " photos");
                    $("#cp-curr-page").text(current);
                    $("#cp-prev-page").prop("disabled", current <= 1);
                    $("#cp-next-page").prop("disabled", current >= totalPages);
                }

                // Render Gallery
                CommunityPhoto.renderGallery();

                // Select first photo if available and none selected
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
                var initial = (item && item.name && item.name.trim().length > 0) ? item.name.trim().charAt(0).toUpperCase() : 'U';
                var colors = [
                    { bg: '#eff6ff', color: '#2563eb' },
                    { bg: '#f5f3ff', color: '#7c3aed' },
                    { bg: '#ecfdf5', color: '#059669' },
                    { bg: '#fff7ed', color: '#ea580c' },
                    { bg: '#fdf2f8', color: '#db2777' },
                    { bg: '#fefce8', color: '#ca8a04' }
                ];
                var charCode = (item && item.name && item.name.length > 0) ? item.name.charCodeAt(0) + item.name.length : 0;
                var col = colors[charCode % colors.length];

                var cardFallbackHtml = '<div class="cp-card-avatar-fallback" style="background: ' + col.bg + '; color: ' + col.color + '; font-weight: 700; width: 32px; height: 32px; min-width: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">' + initial + '</div>';

                var avatarHtml;
                if (item && item.avatar && item.avatar.trim() !== '') {
                    avatarHtml = '<div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px; flex-shrink: 0;">' +
                        cardFallbackHtml +
                        '<img src="' + item.avatar + '" class="cp-card-avatar" alt="" style="position: absolute; top: 0; left: 0; width: 32px; height: 32px; border-radius: 50%; object-fit: cover; z-index: 2;" onerror="this.remove();" />' +
                    '</div>';
                } else {
                    avatarHtml = cardFallbackHtml;
                }

                var countBadge = (item.images_count > 1) ?
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
                                `<div class="cp-card-placeholder d-flex align-items-center justify-content-center h-100 text-muted" style="min-height: 140px; background: #f1f5f9;"><i class="fa fa-picture-o fa-2x"></i></div>`
                            }
                            ${countBadge}
                        </div>
                        ${item.message ? `<div class="cp-card-message" title="${item.message}">${item.message}</div>` : `<div class="cp-card-message empty-msg">No caption</div>`}
                    </div>
                `;
                $galleryWrap.append(cardHtml);
            });
        },

        selectPhoto: function(idx) {
            if (!allRecords || idx < 0 || idx >= allRecords.length) {
                CommunityPhoto.resetPreview();
                return;
            }

            currentIndex = idx;
            var item = allRecords[idx];

            // Highlight gallery card
            $(".cp-gallery-card").removeClass("active");
            $(".cp-gallery-card[data-index='" + idx + "']").addClass("active");

            // Highlight table row
            $("#dataTable tbody tr").removeClass("selected");
            $($("#dataTable tbody tr").get(idx)).addClass("selected");

            // Update Right Preview Box
            var imgSrc = item.first_image || (item.images && item.images.length > 0 ? item.images[0] : '');
            var $box = $("#cp-preview-visual");

            if (imgSrc) {
                $box.html(`
                    <div class="cp-preview-img-container">
                        <img src="${imgSrc}" id="cp-main-preview-img" class="cp-preview-img" alt="Preview">
                        <button type="button" class="cp-btn-zoom view-photos" data-url="${item.view_photos_url}" title="View Fullscreen">
                            <i class="fa fa-arrows-alt"></i>
                        </button>
                    </div>
                `);
            } else {
                $box.html(`
                    <div class="cp-preview-empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                        <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size: 13px;">No image available</p>
                    </div>
                `);
            }

            // Update Metadata
            $("#cp-meta-member").text(item.name || '—');
            $("#cp-meta-uploaded").text(item.date_formatted || item.date_time || '—');
            $("#cp-meta-message").text(item.message || 'No message provided');

            // Status bar
            $("#cp-meta-status").html('<span class="cp-status-dot-active">●</span> Mobile upload synced');
        },

        resetPreview: function() {
            currentIndex = -1;
            $("#cp-preview-visual").html(`
                <div class="cp-preview-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                    </svg>
                    <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size: 13px;">Select a photo to preview</p>
                </div>
            `);
            $("#cp-meta-member").text('—');
            $("#cp-meta-uploaded").text('—');
            $("#cp-meta-message").text('—');
            $("#cp-meta-status").html('<i class="fa fa-clock-o text-muted"></i> Waiting for the first mobile upload');
        },

        bindEventHandlers: function() {
            // View Mode Switching (Gallery vs List)
            $(document).on("click", "#cp-view-gallery", function(e) {
                e.preventDefault();
                $("#cp-view-gallery").addClass("active");
                $("#cp-view-list").removeClass("active");
                $("#cp-list-section").hide();
                $("#cp-gallery-section").show();
                CommunityPhoto.renderGallery();
            });

            $(document).on("click", "#cp-view-list", function(e) {
                e.preventDefault();
                $("#cp-view-list").addClass("active");
                $("#cp-view-gallery").removeClass("active");
                $("#cp-gallery-section").hide();
                $("#cp-list-section").show();
                if (data_table) {
                    data_table.columns.adjust().draw(false);
                }
            });

            // Click Gallery Card to select photo
            $(document).on("click", ".cp-gallery-card", function() {
                var idx = parseInt($(this).data("index"), 10);
                CommunityPhoto.selectPhoto(idx);
            });

            // Click Table Row to select photo
            $(document).on("click", "#dataTable tbody tr", function() {
                var idx = $(this).index();
                CommunityPhoto.selectPhoto(idx);
            });

            // Preview Nav Arrows (< and >)
            $(document).on("click", "#cp-preview-prev", function() {
                if (allRecords.length === 0) return;
                var newIdx = (currentIndex <= 0) ? allRecords.length - 1 : currentIndex - 1;
                CommunityPhoto.selectPhoto(newIdx);
            });

            $(document).on("click", "#cp-preview-next", function() {
                if (allRecords.length === 0) return;
                var newIdx = (currentIndex >= allRecords.length - 1) ? 0 : currentIndex + 1;
                CommunityPhoto.selectPhoto(newIdx);
            });

            // Pagination Controls (< Previous, Next >)
            $(document).on("click", "#cp-prev-page", function(e) {
                e.preventDefault();
                if (data_table) {
                    data_table.page("previous").draw(false);
                }
            });

            $(document).on("click", "#cp-next-page", function(e) {
                e.preventDefault();
                if (data_table) {
                    data_table.page("next").draw(false);
                }
            });

            // Live Search with Debounce
            $("#cp-search-input").on("keyup input", function() {
                clearTimeout(searchTimer);
                var q = $(this).val();
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.search(q).draw();
                    }
                }, 300);
            });

            // Filter dropdowns
            $("#cp-filter-date, #cp-filter-sort").on("change", function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Per page dropdown
            $("#cp-per-page").on("change", function() {
                if (data_table) {
                    data_table.page.len(parseInt($(this).val(), 10)).draw();
                }
            });

            // Refresh buttons
            $(document).on("click", "#cp-btn-refresh, .cp-check-photos-btn", function(e) {
                e.preventDefault();
                var $icon = $(this).find(".fa-refresh");
                $icon.addClass("fa-spin");
                if (data_table) {
                    data_table.ajax.reload(function() {
                        setTimeout(function() {
                            $icon.removeClass("fa-spin");
                        }, 400);
                    }, false);
                }
            });
        },

        viewPhotos: function() {
            $(document).on("click", ".view-photos", function(e) {
                e.preventDefault();
                var url = $(this).data("url") || $(this).attr("href");
                if (!url || url === "#" || url.indexOf("javascript") === 0) return;

                var $modal = $("#pageModal");
                $modal.find(".modal-dialog").html(
                    '<div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;"><div class="text-center py-5"><div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;"><span class="visually-hidden">Loading...</span></div><div class="mt-2 text-muted small">Loading photos...</div></div></div>'
                );

                $modal.modal("show");
                $modal.find(".modal-dialog").load(url, function(response, status, xhr) {
                    if (status === "error") {
                        $(this).html('<div class="modal-content" style="border-radius: 16px; border: none;"><div class="p-4 text-center text-danger"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><p>Failed to load photos. Please try again.</p><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button></div></div>');
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    if (!window.__cp_initialized) {
        window.__cp_initialized = true;
        CommunityPhoto.init();
    }
});
