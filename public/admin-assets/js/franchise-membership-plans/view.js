var FranchiseMemberShipPlan = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            FranchiseMemberShipPlan.getFranchiseMemberShipPlans();
            FranchiseMemberShipPlan.changeStatus();
            FranchiseMemberShipPlan.destroyRecord();
            FranchiseMemberShipPlan.updateOrder();
            FranchiseMemberShipPlan.initializeComponents();
            FranchiseMemberShipPlan.dataTableCustomFilter();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Initialize Components
            var $filter_form = $(".custom-datatable-filter-form");

            // Bootstrap Select on filter form dropdowns
            Components.bootstrapSelect($filter_form);
            //------------

            // Enable Button on change filter form elements
            // Components.enableButton($filter_form);
            //------------

            $('input[name="date_range"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="date_range"]').on(
                "apply.daterangepicker",
                function (ev, picker) {
                    $(this).val(
                        picker.startDate.format("YYYY-MM-DD") +
                        "/" +
                        picker.endDate.format("YYYY-MM-DD")
                    );
                    $(':input[type="submit"]').prop("disabled", false);
                    $(':input[name="Clear"]').prop("disabled", false);
                }
            );

            $('input[name="date_range"]').on(
                "cancel.daterangepicker",
                function (ev, picker) {
                    $(this).val("");
                }
            );
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
            // var $table = table.table().node();
            // var $chkbox_all = $('tbody input[type="checkbox"]', $table);
            // var $chkbox_checked = $(
            //     'tbody input[type="checkbox"]:checked',
            //     $table
            // );
            // var chkbox_select_all = $(
            //     'thead input[name="select_all"]',
            //     $table
            // ).get(0);

            // // If none of the checkboxes are checked
            // if ($chkbox_checked.length === 0) {
            //     chkbox_select_all.checked = false;

            //     if ("indeterminate" in chkbox_select_all) {
            //         chkbox_select_all.indeterminate = false;
            //     }

            //     // If all of the checkboxes are checked
            // } else if ($chkbox_checked.length === $chkbox_all.length) {
            //     chkbox_select_all.checked = true;

            //     if ("indeterminate" in chkbox_select_all) {
            //         chkbox_select_all.indeterminate = false;
            //     }

            //     // If some of the checkboxes are checked
            // } else {
            //     chkbox_select_all.checked = true;
            //     if ("indeterminate" in chkbox_select_all) {
            //         chkbox_select_all.indeterminate = true;
            //     }
            // }
        },

        /**
         * Get Franchise MemberShip Plans list.
         */
        getFranchiseMemberShipPlans: function() {
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
                    $(".current-page-button").attr(
                        "title",
                        "Export Current Page"
                    );
                    $(".current-page-button").html(
                        '<i title="Export Excel" class="fa fa-file-text"/> &nbsp; Export Current Page'
                    );

                    $(".all-page-button").addClass(
                        "btn btn-icon btn-rounded btn-primary btn-outline"
                    );
                    $(".all-page-button").attr("title", "Export All");
                    $(".all-page-button").html(
                        '<i title="Export Excel" class="fa fa-file-text"/> &nbsp; Export All'
                    );

                    // $('.btn-toolbar').append(
                    //     '<button type="button" title="Order Update" class="btn btn-icon btn-rounded btn-primary btn-outline update-order"> &nbsp; Order Update </button> '
                    // );

                    // $('.btn-toolbar').append(
                    //     '<button type="button" title="Change Status" class="btn btn-icon btn-rounded btn-primary btn-outline change-status" disabled> <i class="fa fa-exchange" aria-hidden="true"></i> &nbsp; Change Status </button> '
                    // );

                    // $('.btn-toolbar').append(
                    //     '<button type="button" title="Delete" class="btn btn-icon btn-rounded btn-primary btn-outline dt-delete" disabled> <i class="fa fa-trash" aria-hidden="true"></i> &nbsp; Delete </button> '
                    // );
                },
                headerCallback: function(e, a, t, n, s) {
                    // e.getElementsByTagName("th")[0].innerHTML =
                    //         '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                    // {
                    //     targets: 0,
                    //     width: "30px",
                    //     className: "",
                    //     orderable: !1,
                    //     visible: true,
                    //     // render: function(e, a, t, n) {
                    //     //     return '<label class="new-control new-checkbox checkbox-outline-primary  m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                    //     // }
                    // }
                ],
                buttons: {
                    buttons: [
                        // {
                        //     extend: "excel",
                        //     className: "current-page-button",
                        //     exportOptions: {
                        //         modifier: {
                        //             page: "current",
                        //             search: "none"
                        //         },
                        //         columns: [1, 2]
                        //     }
                        // },
                        // {
                        //     extend: "excel",
                        //     className: "all-page-button",
                        //     exportOptions: {
                        //         modifier: {
                        //             page: "all",
                        //             search: "none"
                        //         },
                        //         columns: [1, 2, 3, 4]
                        //     }
                        // }
                    ]
                },
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
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.franchise_id = $("#franchise_id").val();
                        d.membership_plan_id = $("#membership_plan_id").val();
                        d.payment_status = $("#payment_status").val();
                    }
                },
                columns: [
                    // {
                    //     data: null,
                    //     name: "",
                    //     searchable: false,
                    //     sortable: false,
                    //     visible: false
                    // },
                    { data: "franchise_name", name: "franchise_name" },
                    { data: "membership_plan_name", name: "membership_plan_name" },
                    { data: "total_amount", name: "total_amount" },
                    { data: "payment_status", name: "payment_status" },
                    { data: "start_date", name: "start_date" },
                    { data: "end_date", name: "end_date" },
                    { data: "remark", name: "remark" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width:80
                    }
                ],

                footerCallback: function (row, data, start, end, display) {

                    let totalAmount = 0;

                    data.forEach(function (row) {
                        totalAmount += parseFloat(row.total_amount) || 0;
                    });

                    $('#total_amount_footer').html(totalAmount.toFixed(2));
                },

                rowCallback: function(row, data, dataIndex) {
                    // Get row ID
                    var rowId = data[0];

                    // If row ID is in the list of selected row IDs
                    // if ($.inArray(rowId, rows_selected) !== -1) {
                    //     $(row)
                    //         .find('input[type="checkbox"]')
                    //         .prop("checked", true);
                    //     $(row).addClass("selected");
                    // }
                }
            });

            // Apply filter
            $(".apply-filter").on("click", function(e) {
                data_table.ajax.reload();
                e.preventDefault();
            });
            //-------------

            // Clear filter
            $(".clear-filter").on("click", function(e) {
                $(".custom-datatable-filter-form")[0].reset();
                $source = $(".custom-datatable-filter-form");
                $select = $source.find(".select-picker");
                $select.selectpicker("refresh");
                data_table.ajax.reload();
                e.preventDefault();
            });
            //-------------

            // Handle click on checkbox
            $dataTable
                .find("tbody")
                .on("click", 'input[type="checkbox"]', function(e) {
                    var $row = $(this).closest("tr");
                    // Get row data
                    var data = table.row($row).data();

                    // Get row ID
                    var rowId = data;

                    // Determine whether row ID is in the list of selected row IDs
                    var index = $.inArray(rowId, rows_selected);

                    // If checkbox is checked and row ID is not in list of selected row IDs
                    if (this.checked && index === -1) {
                        rows_selected.push(rowId);

                        // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                    } else if (!this.checked && index !== -1) {
                        rows_selected.splice(index, 1);
                    }

                    if (
                        $dataTable.find('tbody input[type="checkbox"]:checked')
                            .length > 0
                    ) {
                        $(".change-status").prop("disabled", false);
                        $(".dt-delete").prop("disabled", false);
                    } else {
                        $(".change-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    }

                    if (this.checked) {
                        $row.addClass("selected");
                    } else {
                        $row.removeClass("selected");
                    }

                    // Update state of "Select all" control
                    FranchiseMemberShipPlan.updateDataTableSelectAllCtrl(table);

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });

            // Handle click on "Select all" control
            $dataTable
                .find("thead")
                .on("click", 'input[name="select_all"]', function(e) {
                    if (this.checked) {
                        $dataTable
                            .find('tbody input[type="checkbox"]:not(:checked)')
                            .trigger("click");
                        $(".change-status").prop("disabled", false);
                        $(".dt-delete").prop("disabled", false);
                    } else {
                        $dataTable
                            .find('tbody input[type="checkbox"]:checked')
                            .trigger("click");
                        $(".change-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    }

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });

            // Handle table draw event
            table.on("draw", function() {
                // Update state of "Select all" control
                FranchiseMemberShipPlan.updateDataTableSelectAllCtrl(table);

                // Additional form validation methods
                Components.additionalValidationMethods();
               //----------
            });
        },

        /**
         * Change status.
         */
        changeStatus: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $data_table_container.on("click", ".change-status", function() {
                // Iterate over all selected checkboxes
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    ids.push(rowId.id);
                });

                $.ajax({
                    type: "POST",
                    url: $dataTable.data("change-status-url"),
                    data: { ids: ids },
                    beforeSend: function() {
                        $(".change-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    },
                    success: function(response) {
                        App.showNotification(response);
                        data_table.ajax.reload(null, false);
                        rows_selected = [];
                    },
                    error: function() {},
                    complete: function() {
                        $(".change-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    }
                });
            });
        },

        /**
         * Destroy record.
         */
        destroyRecord: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $data_table_container.on("click", ".dt-delete", function() {
                // Iterate over all selected checkboxes
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    ids.push(rowId.id);
                });

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to delete?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {

                                $.ajax({
                                    type: "DELETE",
                                    url: $dataTable.data("destroy-url"),
                                    data: { ids: ids },
                                    beforeSend: function() {
                                        $(".dt-delete").prop("disabled", true);
                                        $(".change-status").prop("disabled", true);
                                    },
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                    complete: function() {
                                        $(".dt-delete").prop("disabled", true);
                                        $(".change-status").prop("disabled", true);
                                    }
                                });
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );
                            },
                            true
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
         * Update Order.
         */
        updateOrder: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $data_table_container.on("click", ".update-order", function() {
                // $('.new-checkbox').click();
                // Iterate over all selected checkboxes
                // var ids = [];
                // $.each(rows_selected, function(index, rowId) {
                //     console.log(rowId);
                //     ids.push(rowId.id);
                // });
                var ids = [];
                $(".dataTable tbody tr").each(function(index, rowId) {
                    var innerArray = [];
                    var $row = $(this).closest("tr");
                    var data = table.row($row).data();
                    var rowId = data;
                    rows_selected.push(rowId);
                    var franchiseMembershipPlanOrder = $('#franchise_membership_plan_order_'+rowId.id).val();
                    innerArray.push(rowId.id, franchiseMembershipPlanOrder);
                    ids.push(innerArray);
                });
                $.ajax({
                    type: "POST",
                    url: $dataTable.data("update-order-url"),
                    data: { ids: ids },
                    beforeSend: function() {
                        $(".update-order").prop("disabled", true);
                    },
                    success: function(response) {
                        App.showNotification(response);
                        data_table.ajax.reload(null, false);
                        rows_selected = [];
                    },
                    error: function() {},
                    complete: function() {
                        $(".update-order").prop("disabled", false);
                    }
                });
            });
        },
    };
})();

FranchiseMemberShipPlan.init();
