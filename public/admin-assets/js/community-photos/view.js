window.CommunityPhoto = (function() {
    var data_table;
    var table;
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
                    oPaginate: {
                        sPrevious: '<span class="cp-nav-arrow"><i class="fa fa-angle-left"></i> Previous</span>',
                        sNext: '<span class="cp-nav-arrow">Next <i class="fa fa-angle-right"></i></span>'
                    },
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ photos",
                    sInfoEmpty: "Showing 0 to 0 of 0 photos",
                    sInfoFiltered: "(filtered from _MAX_ total photos)",
                    sEmptyTable: '<div class="text-center py-5 text-muted"><i class="fa fa-picture-o fa-3x mb-3" style="color: #cbd5e1;"></i><h5 class="fw-bold text-dark mb-1">No community photos yet</h5><p class="text-muted small">Photos uploaded by members from the mobile app will appear here.</p></div>',
                    sZeroRecords: '<div class="text-center py-5 text-muted"><i class="fa fa-search fa-3x mb-3" style="color: #cbd5e1;"></i><h5 class="fw-bold text-dark mb-1">No matching photos found</h5><p class="text-muted small">Try adjusting your search terms or filters.</p></div>'
                },
                processing: true,
                serverSide: true,
                pageLength: 20,
                dom: 'rt<"cp-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        var memberVal = $("#cp-filter-member").val();
                        d.name = (memberVal && memberVal !== "all") ? memberVal : "";
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
                        width: 220,
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

                            var fallbackHtml = '<div class="cp-table-avatar-fallback" style="background: ' + col.bg + '; color: ' + col.color + '; font-weight: 700; width: 34px; height: 34px; min-width: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px;">' + initial + '</div>';

                            var avatarHtml;
                            if (row && row.avatar && row.avatar.trim() !== '') {
                                avatarHtml = '<div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; min-width: 34px; flex-shrink: 0;">' +
                                    fallbackHtml +
                                    '<img src="' + row.avatar + '" class="cp-table-avatar" alt="" style="position: absolute; top: 0; left: 0; width: 34px; height: 34px; border-radius: 50%; object-fit: cover; z-index: 2;" onerror="this.remove();" />' +
                                '</div>';
                            } else {
                                avatarHtml = fallbackHtml;
                            }

                            return '<div class="d-flex align-items-center gap-2">' +
                                   avatarHtml +
                                   '<div><div class="fw-bold text-dark" style="font-size: 13.5px;">' + (data || 'N/A') + '</div></div>' +
                                   '</div>';
                        }
                    },
                    {
                        data: "message",
                        name: "message",
                        render: function(data) {
                            if (!data || data === 'N/A' || data.trim() === '') {
                                return '<span class="text-muted fst-italic" style="font-size: 12.5px;">No message provided</span>';
                            }
                            return '<span class="text-secondary" style="font-size: 13px; line-height: 1.45;">' + data + '</span>';
                        }
                    },
                    {
                        data: "view_photos",
                        name: "view_photos",
                        width: 180,
                        render: function(data, type, row) {
                            var url = (row && row.view_photos_url) ? row.view_photos_url : '';
                            var count = (row && row.images_count) ? row.images_count : 1;
                            var thumb = (row && row.first_image) ?
                                '<img src="' + row.first_image + '" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0;" alt="Photo">' :
                                '';

                            if (url) {
                                return '<div class="d-flex align-items-center gap-2">' +
                                       thumb +
                                       '<a href="javascript:void(0);" data-url="' + url + '" class="view-photos btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;"><i class="fa fa-eye"></i> View (' + count + ')</a>' +
                                       '</div>';
                            }
                            return data || '<span class="text-muted">No photos</span>';
                        }
                    },
                    {
                        data: "date_time",
                        name: "date_time",
                        width: 180,
                        render: function(data, type, row) {
                            var formatted = (row && row.date_formatted) ? row.date_formatted : data;
                            var rel = (row && row.relative_time) ? '<div class="text-muted" style="font-size: 11px;">' + row.relative_time + '</div>' : '';
                            return '<div><div style="font-size: 12.5px; font-weight: 600; color: #334155;">' + (formatted || 'N/A') + '</div>' + rel + '</div>';
                        }
                    }
                ]
            });

            table.on("draw", function() {
                var records = table.rows().data().toArray();
                var pageInfo = table.page.info();
                var total = pageInfo.recordsTotal || 0;

                $("#cp-records-count").text(total + (total === 1 ? " photo" : " photos"));
                $("#cp-stat-total").text(total);

                if ($("#cp-view-gallery").hasClass("active")) {
                    CommunityPhoto.renderGallery(records);
                }
            });
        },

        renderGallery: function(records) {
            var $galleryWrap = $("#cp-gallery-grid");
            var $emptyWrap = $("#cp-gallery-empty");

            if (!records) {
                records = (data_table && data_table.rows) ? data_table.rows().data().toArray() : [];
            }

            if (!records || records.length === 0) {
                $galleryWrap.hide();
                $emptyWrap.show();
                return;
            }

            $emptyWrap.hide();
            $galleryWrap.show().empty();

            $.each(records, function(idx, item) {
                var imgSrc = item.first_image || (item.images && item.images.length > 0 ? item.images[0] : '');
                var photoUrl = item.view_photos_url || '';
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

                var cardFallbackHtml = '<div class="cp-card-avatar-fallback" style="background: ' + col.bg + '; color: ' + col.color + '; font-weight: 700; width: 34px; height: 34px; min-width: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px;">' + initial + '</div>';

                var avatarHtml;
                if (item && item.avatar && item.avatar.trim() !== '') {
                    avatarHtml = '<div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px; min-width: 34px; flex-shrink: 0;">' +
                        cardFallbackHtml +
                        '<img src="' + item.avatar + '" class="cp-card-avatar" alt="" style="position: absolute; top: 0; left: 0; width: 34px; height: 34px; border-radius: 50%; object-fit: cover; z-index: 2;" onerror="this.remove();" />' +
                    '</div>';
                } else {
                    avatarHtml = cardFallbackHtml;
                }

                var countBadge = (item.images_count > 1) ?
                    '<span class="cp-card-photo-count"><i class="fa fa-camera"></i> ' + item.images_count + ' photos</span>' : '';

                var cardHtml = `
                    <div class="cp-gallery-card view-photos cursor-pointer" data-url="${photoUrl}" title="Click to view photos">
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
                                `<div class="cp-card-placeholder d-flex align-items-center justify-content-center h-100 text-muted" style="min-height: 160px; background: #f1f5f9;"><i class="fa fa-picture-o fa-2x"></i></div>`
                            }
                            ${countBadge}
                        </div>
                        ${item.message ? `<div class="cp-card-message" title="${item.message}">${item.message}</div>` : `<div class="cp-card-message empty-msg">No caption</div>`}
                    </div>
                `;
                $galleryWrap.append(cardHtml);
            });
        },

        bindEventHandlers: function() {
            // Switch to List View
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

            // Switch to Gallery View
            $(document).on("click", "#cp-view-gallery", function(e) {
                e.preventDefault();
                $("#cp-view-gallery").addClass("active");
                $("#cp-view-list").removeClass("active");
                $("#cp-list-section").hide();
                $("#cp-gallery-section").show();
                CommunityPhoto.renderGallery();
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
            $("#cp-filter-member, #cp-filter-date, #cp-filter-sort").on("change", function() {
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
