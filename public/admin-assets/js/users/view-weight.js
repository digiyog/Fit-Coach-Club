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
                        width: "60px",
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 4,
                        width: "160px",
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
                                columns: [0, 1, 2]
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
                        width: "60px",
                        render: function (data, type, row, meta) {
                            return row.entry !== undefined ? row.entry : (meta.row + meta.settings._iDisplayStart + 1);
                        }
                    },
                    { data: "date", name: "date" },
                    { data: "weight", name: "weight" },
                    { data: "evidence", name: "evidence" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        className: "no-sort no-content text-end",
                        width: "160px"
                    }
                ]
            });
        },

        /**
         * View Image Modal.
         */
        viewImage: function () {
            $(document).on("click", ".view-image", function (e) {
                e.preventDefault();
                var $this = $(this);
                var url = $this.data("url");
                if (!url) return;

                var $pageModal = $("#pageModal");
                $pageModal.modal("show");
                $pageModal
                    .find(".modal-content")
                    .load(url, function () {
                        if (typeof feather !== "undefined") {
                            feather.replace();
                        }
                    });
            });
        }
    };
})();

$(document).ready(function() {
    Weight.init();
});
