var Weight = (function() {
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Weight.getWeights();
            Weight.initializeEvents();
            Weight.viewImage();
        },

        /**
         * Initialize Events and Filters.
         */
        initializeEvents: function() {
            // Search Input with Debounce
            var searchTimeout;
            $("#fccHistorySearchInput").on("keyup search input", function() {
                var searchVal = $(this).val();
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if (data_table) {
                        data_table.search(searchVal).draw();
                    }
                }, 300);
            });

            // Page Size Selector
            $("#fccPageSizeSelect").on("change", function() {
                var size = parseInt($(this).val());
                if (data_table) {
                    data_table.page.len(size).draw();
                }
            });

            // Export Button
            $("#fccExportBtn").on("click", function(e) {
                e.preventDefault();
                if (data_table) {
                    data_table.button('.buttons-excel').trigger();
                }
            });
        },

        /**
         * Get Weights DataTable.
         */
        getWeights: function() {
            var $dataTable = $("#dataTable");
            if (!$dataTable.length) return;

            window.data_table = data_table = $dataTable.DataTable({
                order: [],
                columnDefs: [
                    {
                        targets: 0,
                        width: "70px",
                        orderable: false,
                        searchable: false,
                        className: "no-sort text-start"
                    },
                    {
                        targets: 1,
                        width: "25%",
                        className: "text-start"
                    },
                    {
                        targets: 2,
                        width: "25%",
                        className: "text-start"
                    },
                    {
                        targets: 3,
                        width: "30%",
                        className: "text-start"
                    },
                    {
                        targets: 4,
                        width: "90px",
                        orderable: false,
                        searchable: false,
                        className: "no-sort no-content text-end"
                    }
                ],
                drawCallback: function(settings) {
                    if (typeof feather !== "undefined") {
                        feather.replace();
                    }
                },
                buttons: {
                    buttons: [
                        {
                            extend: "excel",
                            className: "buttons-excel",
                            exportOptions: {
                                modifier: {
                                    page: "all",
                                    search: "none"
                                },
                                columns: [0, 1, 2, 3]
                            }
                        }
                    ]
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: -1px;"><polyline points="15 18 9 12 15 6"></polyline></svg> Previous',
                        sNext:
                            'Next <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: -1px;"><polyline points="9 18 15 12 9 6"></polyline></svg>'
                    },
                    sInfo: "Showing _START_–_END_ of _TOTAL_ records",
                    sInfoEmpty: "Showing 0 to 0 of 0 records",
                    sInfoFiltered: "(filtered from _MAX_ total entries)",
                    sEmptyTable: "No weight measurements found",
                    sSearch: '<i data-feather="search"></i>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_"
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                pageLength: 20,
                dom: '<"fcc-modern-table-wrap"rt><"fcc-dt-footer"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.user_id = $("#user_id").val();
                        d.year = $("#year").val();
                        d.date_range = $("#date_range").val();
                    }
                },
                columns: [
                    {
                        data: "entry",
                        name: "serial_no",
                        searchable: false,
                        sortable: false,
                        width: "70px",
                        render: function (data, type, row, meta) {
                            return row.entry !== undefined ? row.entry : (meta.row + meta.settings._iDisplayStart + 1);
                        }
                    },
                    { data: "date", name: "date", width: "25%" },
                    { data: "weight", name: "weight", width: "25%" },
                    { data: "evidence", name: "evidence", width: "30%" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        className: "no-sort no-content text-end",
                        width: "90px"
                    }
                ]
            });
        },

        /**
         * View Image Modal.
         */
        viewImage: function () {
            $(document).off("click", ".view-image").on("click", ".view-image", function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $this = $(this);
                var url = $this.data("url") || $this.attr("data-url");
                if (!url) return;

                var $modal = $("#pageModal");
                if (!$modal.length) {
                    $modal = $("#pageModalMedium");
                }

                // Show spinner while loading
                $modal.find(".modal-content").html(
                    '<div class="p-5 text-center" style="font-family: \'Outfit\', sans-serif;">' +
                    '<div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;"></div>' +
                    '<p class="mt-3 text-muted fw-semibold" style="font-size: 13.5px;">Loading image preview...</p>' +
                    '</div>'
                );

                $modal.modal("show");

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {
                        $modal.find(".modal-content").html(response);
                        if (typeof feather !== "undefined") {
                            feather.replace();
                        }
                    },
                    error: function () {
                        $modal.find(".modal-content").html(
                            '<div class="p-4 text-center" style="font-family: \'Outfit\', sans-serif;">' +
                            '<div class="text-danger mb-2"><i class="fa fa-exclamation-triangle fa-2x"></i></div>' +
                            '<h6 class="fw-bold text-dark">Error Loading Image</h6>' +
                            '<p class="text-muted small">Could not retrieve the photo evidence.</p>' +
                            '<button type="button" class="btn btn-light btn-sm mt-2" data-bs-dismiss="modal" data-dismiss="modal">Close</button>' +
                            '</div>'
                        );
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    Weight.init();
});
