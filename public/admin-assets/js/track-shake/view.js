var TrackShake = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            TrackShake.getTrackShake();
            TrackShake.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
        },

        /**
         * Get Track Shake list.
         */
        getTrackShake: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                initComplete: function() {
                    if (data_table.row().count() == 0) {
                        data_table
                            .buttons(".buttons-excel")
                            .nodes()
                            .css("display", "none");
                    } else {
                        data_table
                            .buttons(".buttons-excel")
                            .nodes()
                            .css("display", "block");
                    }
                },
                headerCallback: function(e, a, t, n, s) {
                },
                columnDefs: [
                ],
                buttons: {
                    buttons: [
                    ]
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sInfo: "Showing _START_–_END_ of _TOTAL_ records",
                    sLengthMenu: "Results : _MENU_"
                },
                processing: true,
                serverSide: true,
                searching: true,
                lengthMenu: [
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12 fcc-dt-footer"<"row align-items-center w-100"<"col-md-5"i><"col-md-7 d-flex justify-content-end"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.user_id = $("#user_id").val();
                        d.activity = $("#fccActivityFilter").val();
                        d.source = $("#fccSourceFilter").val();
                    }
                },
                columns: [
                    { data: "id", name: "id", width: 65 },
                    { data: "date", name: "date", width: 130 },
                    { data: "total_days", name: "total_days", width: 90 },
                    { data: "change", name: "change", width: 90 },
                    { data: "remark", name: "remark" },
                    { data: "type", name: "type", width: 110 },
                    { data: "message", name: "message" },
                ],
                rowCallback: function(row, data, dataIndex) {
                }
            });

            window.data_table = data_table;

            // Search input with debounce
            var searchTimer;
            $('#fccActivitySearchInput').on('keyup input', function() {
                clearTimeout(searchTimer);
                var val = this.value;
                searchTimer = setTimeout(function() {
                    data_table.search(val).draw();
                }, 300);
            });

            // Activity Filter
            $('#fccActivityFilter').on('change', function() {
                data_table.ajax.reload();
            });

            // Source Filter
            $('#fccSourceFilter').on('change', function() {
                data_table.ajax.reload();
            });

            // Page Size Selector
            $('#fccPageSizeSelect').on('change', function() {
                data_table.page.len(parseInt($(this).val(), 10)).draw();
            });

            // Handle table draw event
            table.on("draw", function() {
                Components.additionalValidationMethods();
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        },
    };
})();

TrackShake.init();
