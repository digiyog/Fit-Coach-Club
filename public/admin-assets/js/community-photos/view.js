var CommunityPhoto = (function() {
    var data_table;
    var searchTimer;
    var activeViewMode = 'gallery';

    return {
        /**
         * Initialization.
         */
        init: function() {
            CommunityPhoto.getCommunityPhotos();
            CommunityPhoto.bindCustomEvents();
            CommunityPhoto.viewPhotos();
        },

        /**
         * Bind custom filter, search, view toggle and refresh events
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

            // View toggle (Gallery / List)
            $('#btnViewGallery').on('click', function() {
                activeViewMode = 'gallery';
                $('.btn-view-toggle').removeClass('active');
                $(this).addClass('active');
                if (data_table && data_table.rows().count() > 0) {
                    $('.data-table-container').hide();
                    $('#gallery-view-container').show();
                }
            });

            $('#btnViewList').on('click', function() {
                activeViewMode = 'list';
                $('.btn-view-toggle').removeClass('active');
                $(this).addClass('active');
                if (data_table && data_table.rows().count() > 0) {
                    $('#gallery-view-container').hide();
                    $('.data-table-container').show();
                }
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
                    { data: "message", name: "message" },
                    { data: "view_photos", name: "view_photos", width: 120 },
                    { data: "date_time", name: "date_time", width: 140 }
                ],
                rowCallback: function(row, data, dataIndex) {
                    $(row).addClass('cursor-pointer');
                    $(row).on('click', function() {
                        CommunityPhoto.updatePreviewPane(data);
                    });
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    var info = api.page.info();

                    // Update count text
                    $('#comm-record-count-text').text(info.recordsDisplay + ' photos');

                    // Toggle empty state vs data state
                    if (info.recordsTotal === 0) {
                        $('#empty-state-view').show();
                        $('.data-table-container').hide();
                    } else {
                        $('#empty-state-view').hide();
                        if (activeViewMode === 'list') {
                            $('.data-table-container').show();
                        } else {
                            $('.data-table-container').show();
                        }
                    }
                }
            });
        },

        /**
         * Update the Right Preview Pane
         */
        updatePreviewPane: function(data) {
            if (!data) return;

            $('#meta-member-val').text(data.name || '—');
            $('#meta-uploaded-val').html(data.date_time || '—');
            $('#meta-message-val').text(data.message || '—');
        },

        /**
         * View Photos Modal.
         */
        viewPhotos: function () {
            var $source = $(".data-table-container");
            $source.on("click", ".view-photos", function (e) {
                e.stopPropagation();
                var $this = $(this);
                var $configuration_modal = $("#pageModal");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {});
                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && typeof App.resetModal === "function") {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    CommunityPhoto.init();
});
