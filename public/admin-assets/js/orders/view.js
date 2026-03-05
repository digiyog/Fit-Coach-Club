var Order = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Order.getOrders();
            Order.initializeComponents();
            Order.dataTableCustomFilter();
            Order.changeStatus();
            Order.paymentStatusChange();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $filter_form = $(".custom-datatable-filter-form");

            // Bootstrap Select on filter form dropdowns
            Components.bootstrapSelect($filter_form);
            //------------

            // Enable Button on change filter form elements
            Components.enableButton($filter_form);
            //------------


            // Date Range picker
            var $date_range_picker = $filter_form.find(".filter-date-range-picker");

            if ($date_range_picker.length) {
                $date_range_picker.flatpickr({
                    dateFormat: "d-m-Y",
                    mode: "range",
                    maxDate: "today",
                });
            }
            //----------------
        },

        /**
         * Datatable custom filter.
         */
        dataTableCustomFilter: function() {
            $(".filter-button").click(function() {
                $(".custom-datatable-filters").toggleClass("hide");
            });
        },

        /**
         * Updates "Select all" control in a data table
         */
        updateDataTableSelectAllCtrl: function(table) {
            if(viewPermission == 1 && deletePermission == 1){
                var $table = table.table().node();
                var $chkbox_all = $('tbody input[type="checkbox"]', $table);
                var $chkbox_checked = $(
                    'tbody input[type="checkbox"]:checked',
                    $table
                );
                var chkbox_select_all = $(
                    'thead input[name="select_all"]',
                    $table
                ).get(0);

                // If none of the checkboxes are checked
                if ($chkbox_checked.length === 0) {
                    chkbox_select_all.checked = false;

                    if ("indeterminate" in chkbox_select_all) {
                        chkbox_select_all.indeterminate = false;
                    }

                    // If all of the checkboxes are checked
                } else if ($chkbox_checked.length === $chkbox_all.length) {
                    chkbox_select_all.checked = true;

                    if ("indeterminate" in chkbox_select_all) {
                        chkbox_select_all.indeterminate = false;
                    }

                    // If some of the checkboxes are checked
                } else {
                    chkbox_select_all.checked = true;
                    if ("indeterminate" in chkbox_select_all) {
                        chkbox_select_all.indeterminate = true;
                    }
                }
            }
        },

        /**
         * Get Orders list.
         */
        getOrders: function() {
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
                    $(".dt-buttons").addClass("btn-toolbar");
                    $(".current-page-button").addClass(
                        "btn btn-icon btn-rounded btn-primary btn-outline"
                    );

                    $(".all-page-button").addClass(
                        "btn btn-icon btn-rounded btn-primary btn-outline"
                    );
                    $(".all-page-button").attr("title", "Export All");
                    $(".all-page-button").html(
                        '<i title="Export Excel" class="fa fa-file-text"/> &nbsp; Export All'
                    );

                    // $('.btn-toolbar').append(
                    //     '<a href="'+$dataTable.data('export-csv-url')+'" title="Export CSV" class="btn btn-icon btn-rounded btn-primary btn-outline export-csv-all"> <i class="fa fa-file-text" aria-hidden="true" download></i> &nbsp; Export All </button> '
                    // );
                },
                buttons: {
                    buttons: [
                        // {
                        //     extend: "excel",
                        //     className: "all-page-button",
                        //     exportOptions: {
                        //         modifier: {
                        //             page: "all",
                        //             search: "none"
                        //         },
                        //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        //     }
                        // }
                    ]
                },
                searching: false,
                oLanguage: {
                    oPaginate: {
                        sPrevious:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sSearch: '<i data-feather="search"></i>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_"
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 20, 50, 75, 100],
                    [10, 20, 50, 75, 100]
                ],
                'aaSorting': [],
                pageLength: 10,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.filter = $("#filter").val();
                        d.filter_date_range = $("#filter_date_range").val();
                        d.user_id = $("#user_id").val();
                        d.status_filter = $("select[name=status_filter]").val();
                        d.payment_status_filter = $("select[name=payment_status_filter]").val();
                    }
                },
                columns: [
                    { data: "transaction_info", name: "transaction_info", searchable: false, orderable: false },
                    { data: "user_name", name: "user_name", searchable: false, orderable: false },
                    { data: "mobile_number", name: "mobile_number", searchable: false, orderable: false },
                    { data: "total_amount", name: "total_amount", searchable: false, orderable: false },
                    { data: "discount", name: "discount", searchable: false, orderable: false },
                    { data: "net_amount", name: "net_amount", searchable: false, orderable: false },
                    { data: "payment_status", name: "payment_status", searchable: false, orderable: false },
                    { data: "order_status", searchable: false, orderable: false },
                    { data: "action", name: "action", searchable: false, orderable: false, align: 'right' }
                ],

                footerCallback: function (row, data, start, end, display) {

                    let totalAmount = 0;
                    let discount    = 0;
                    let netAmount   = 0;

                    data.forEach(function (row) {
                        // ❌ Cancelled order skip
                        if (row.order_status === '<label class="badge badge-danger">Cancelled</label>') {
                            return;
                        }

                        totalAmount += parseFloat(row.total_amount) || 0;
                        discount    += parseFloat(row.discount) || 0;
                        netAmount   += parseFloat(row.net_amount) || 0;
                    });

                    $('#total_amount_footer').html(totalAmount.toFixed(2));
                    $('#discount_footer').html(discount.toFixed(2));
                    $('#net_amount_footer').html(netAmount.toFixed(2));
                },

                drawCallback: function(settings){
                    $source = $(".custom-datatable-filter-form");
                    if(settings.iDraw > 1)
                    {
                        App.stopFilterFormLoading($source);
                    }
                },
            });

            // Apply filter
            $(".apply-filter").on("click", function(e) {
                $source = $(".custom-datatable-filter-form");
                data_table.ajax.reload();
                App.filterFormLoading($source);
                e.preventDefault();
            });
            //-------------

            // Clear filter
            $(".clear-filter").on("click", function(e) {
                $(".custom-datatable-filter-form")[0].reset();
                $source = $(".custom-datatable-filter-form");
                $select = $source.find(".select-picker");
                $select.selectpicker("refresh");

                // Making draw count to 0 to make filters at init state.
                data_table.settings()[0].iDraw = 0;
                data_table.ajax.reload();

                // Disable button click on clear filters
                Components.enableButton($source);
                
                e.preventDefault();
            });
            //-------------

        },

        /**
         * Change Single status.
         */
        changeStatus: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $dataTable.on("click", ".change-status-single", function(element) {
                
                var statusUrl = $(this).attr('data-change-status-url');
                var dataStatus = $(this).data("status");
                var ids = $(this).data("id");

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to change status?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );

                                $.ajax({
                                    type: "POST",
                                    url: statusUrl,
                                    data: { ids: ids, status: dataStatus},
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                });
                            },
                            false
                        ],
                        [
                            "<button>NO</button>",
                            function(instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );
                            }
                        ]
                    ],
                    onClosing: function(instance, toast, closedBy) {
                        console.info("Closing | closedBy: " + closedBy);
                    },
                    onClosed: function(instance, toast, closedBy) {
                        console.info("Closed | closedBy: " + closedBy);
                    }
                });
            });
        },

        /**
         * Payment Status Change.
         */
        paymentStatusChange: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $dataTable.on("click", ".payment-status-change", function(element) {
                 
                var statusUrl = $(this).attr('data-payment-status-url');
                var dataStatus = $(this).data("status");
                var ids = $(this).data("id");

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to change payment status?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );

                                $.ajax({
                                    type: "POST",
                                    url: statusUrl,
                                    data: { ids: ids, status: dataStatus},
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                });
                            },
                            false
                        ],
                        [
                            "<button>NO</button>",
                            function(instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );
                            }
                        ]
                    ],
                    onClosing: function(instance, toast, closedBy) {
                        console.info("Closing | closedBy: " + closedBy);
                    },
                    onClosed: function(instance, toast, closedBy) {
                        console.info("Closed | closedBy: " + closedBy);
                    }
                });
            });
        }

    };
})();

Order.init();
