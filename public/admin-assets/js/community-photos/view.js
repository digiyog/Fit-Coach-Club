var CommunityPhoto = (function() {
    var data_table;
    var searchTimer;
    var activeViewMode = 'gallery';
    var allLoadedRows = [];
    var currentPreviewIndex = -1;

    return {
        /**
         * Initialization.
         */
        init: function() {
            CommunityPhoto.getCommunityPhotos();
            CommunityPhoto.bindCustomEvents();
        },

        /**
         * Bind custom filter, search, view toggle, refresh, and preview navigation events
         */
        bindCustomEvents: function() {
            // Search Input with debounce
            $('#community-search-input').on('keyup input', function() {
                var searchVal = $(this).val();
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    data_table.search(searchVal).draw();
                }, 300);
            });

            // Date & Sort filter
            $('#community-filter-date, #community-filter-sort').on('change', function() {
                data_table.ajax.reload();
            });

            // Per page dropdown
            $('#comm-page-length').on('change', function() {
                var newLen = parseInt($(this).val(), 10) || 20;
                data_table.page.len(newLen).draw();
            });

            // Refresh buttons
            $('#btnRefreshCommunity, #btnCheckNewPhotos').on('click', function() {
                var $btn = $(this);
                $btn.prop('disabled', true).find('i').addClass('fa-spin');
                data_table.ajax.reload(function() {
                    setTimeout(function() {
                        $btn.prop('disabled', false).find('i').removeClass('fa-spin');
                    }, 400);
                });
            });

            // Arrow button navigation in preview header
            $('#btnPrevPhoto').on('click', function() {
                if (currentPreviewIndex > 0) {
                    CommunityPhoto.selectPreviewByIndex(currentPreviewIndex - 1);
                }
            });

            $('#btnNextPhoto').on('click', function() {
                if (currentPreviewIndex >= 0 && currentPreviewIndex < allLoadedRows.length - 1) {
                    CommunityPhoto.selectPreviewByIndex(currentPreviewIndex + 1);
                }
            });

            // Handle clicking on "View Photos" button without opening modal
            $(document).on('click', '.view-photos', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $row = $(this).closest('tr');
                var rowIndex = $row.index();
                CommunityPhoto.selectPreviewByIndex(rowIndex);
            });

            // Handle clicking on any row in the table
            $('#dataTable tbody').on('click', 'tr', function(e) {
                var rowIndex = $(this).index();
                CommunityPhoto.selectPreviewByIndex(rowIndex);
            });
        },

        /**
         * Get Community Photos list.
         */
        getCommunityPhotos: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {},
                columnDefs: [
                    {
                        targets: 0,
                        width: "40px",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                ],
                buttons: [],
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left"></i> Previous',
                        sNext: 'Next <i class="fa fa-angle-right"></i>'
                    },
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ photos",
                    sInfoEmpty: "Showing 0 of 0 photos",
                    sSearch: "",
                    sSearchPlaceholder: "Search member or message...",
                    sLengthMenu: "Results : _MENU_",
                    sEmptyTable: "No community photos found"
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom: 'rt<"d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-2"<"datatable-info-wrap"i><"datatable-paginate-wrap"p>>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.date_filter = $('#community-filter-date').val();
                        d.sort_order = $('#community-filter-sort').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "serial_no",
                        searchable: false,
                        sortable: false,
                        width: 40
                    },
                    { data: "name", name: "name", width: 140 },
                    { 
                        data: "message", 
                        name: "message",
                        render: function(data, type, row) {
                            if (!data || data === 'N/A') return '<span class="text-muted">N/A</span>';
                            var rawText = row.full_message || data;
                            var escapedText = rawText.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                            return '<span class="comm-table-msg" title="' + escapedText + '">' + data + '</span>';
                        }
                    },
                    { data: "date_time", name: "date_time", width: 140 }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var info = api.page.info();

                    allLoadedRows = [];
                    api.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        allLoadedRows.push(this.data());
                    });

                    // Update count text
                    $('#comm-record-count-text').text(info.recordsDisplay + ' photos');

                    // Toggle empty state vs table view
                    if (info.recordsTotal === 0) {
                        $('#empty-state-view').show();
                        $('.data-table-container').hide();
                        CommunityPhoto.resetPreviewPane();
                    } else {
                        $('#empty-state-view').hide();
                        $('.data-table-container').show();

                        // Auto-select first row on initial draw if none selected
                        if (allLoadedRows.length > 0 && currentPreviewIndex < 0) {
                            CommunityPhoto.selectPreviewByIndex(0);
                        } else if (currentPreviewIndex >= 0 && currentPreviewIndex < allLoadedRows.length) {
                            CommunityPhoto.selectPreviewByIndex(currentPreviewIndex);
                        } else if (allLoadedRows.length > 0) {
                            CommunityPhoto.selectPreviewByIndex(0);
                        }
                    }
                }
            });
        },

        /**
         * Select a row and update preview by row index
         */
        selectPreviewByIndex: function(index) {
            if (index < 0 || index >= allLoadedRows.length) return;

            currentPreviewIndex = index;
            var data = allLoadedRows[index];

            // Highlight table row
            $('#dataTable tbody tr').removeClass('active-preview-row');
            $('#dataTable tbody tr').eq(index).addClass('active-preview-row');

            // Update prev/next button states
            $('#btnPrevPhoto').prop('disabled', index === 0);
            $('#btnNextPhoto').prop('disabled', index >= allLoadedRows.length - 1);

            // Update photo & metadata
            CommunityPhoto.renderPhotoPreview(data);
        },

        /**
         * Render Photo and Details in Right Preview Pane
         */
        renderPhotoPreview: function(data) {
            if (!data) return;

            var $container = $('#preview-image-container');
            var images = data.images || [];

            if (images.length > 0) {
                var firstImage = images[0];
                var html = '<div class="w-100 text-center position-relative">';
                html += '<img src="' + firstImage + '" id="main-preview-img" class="comm-preview-img-main" alt="' + (data.name || 'Member Photo') + '">';

                // Thumbnails if multiple images
                if (images.length > 1) {
                    html += '<div class="comm-preview-thumbnails mt-2">';
                    $.each(images, function(i, url) {
                        var activeClass = i === 0 ? 'active' : '';
                        html += '<div class="comm-thumb-item ' + activeClass + '" data-img-src="' + url + '">';
                        html += '<img src="' + url + '" alt="thumb">';
                        html += '</div>';
                    });
                    html += '</div>';
                }
                html += '</div>';

                $container.html(html).addClass('has-photo');

                // Thumbnail click handler
                $container.find('.comm-thumb-item').on('click', function(e) {
                    e.stopPropagation();
                    $container.find('.comm-thumb-item').removeClass('active');
                    $(this).addClass('active');
                    $('#main-preview-img').attr('src', $(this).data('img-src'));
                });
            } else {
                $container.html(
                    '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2">' +
                    '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>' +
                    '<circle cx="8.5" cy="8.5" r="1.5"></circle>' +
                    '<polyline points="21 15 16 10 5 21"></polyline>' +
                    '</svg>' +
                    '<span style="font-size: 13.5px; font-weight: 500;">No photo available for this upload</span>'
                ).removeClass('has-photo');
            }

            // Update Metadata Details
            $('#meta-member-val').text(data.name || '—');
            $('#meta-uploaded-val').html(data.date_time || '—');
            $('#meta-message-val').text(data.full_message || data.message || '—');

            // Footer
            $('#preview-footer-status').html('<i class="fa fa-check-circle text-success me-1"></i> <span>Mobile upload synced</span>');
        },

        /**
         * Reset preview pane to default state
         */
        resetPreviewPane: function() {
            currentPreviewIndex = -1;
            $('#preview-image-container').html(
                '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2">' +
                '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>' +
                '<circle cx="8.5" cy="8.5" r="1.5"></circle>' +
                '<polyline points="21 15 16 10 5 21"></polyline>' +
                '</svg>' +
                '<span style="font-size: 13.5px; font-weight: 500;">Select a photo to preview</span>'
            ).removeClass('has-photo');

            $('#meta-member-val').text('—');
            $('#meta-uploaded-val').text('—');
            $('#meta-message-val').text('—');
            $('#preview-footer-status').html('<i class="fa fa-clock-o text-muted me-1"></i> <span>Waiting for mobile uploads</span>');
            $('#btnPrevPhoto, #btnNextPhoto').prop('disabled', true);
        }
    };
})();

$(document).ready(function() {
    CommunityPhoto.init();
});
