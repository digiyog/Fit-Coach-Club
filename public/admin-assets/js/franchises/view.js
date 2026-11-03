var Franchise = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Franchise.getFranchises();
            Franchise.changeStatus();
            Franchise.destroyRecord();
            Franchise.singleDeleteRecord();
            Franchise.updateOrder();
            Franchise.initializeComponents();
            Franchise.dataTableCustomFilter();
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
        },

        /**
         * Get Franchises list.
         */
        getFranchises: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                initComplete: function() {
                    $(".dt-buttons").addClass("btn-toolbar");
                    $(".btn-toolbar").html(
                        '<button type="button" title="Change Status" class="fcc-dt-mass-btn change-status" disabled> <i class="fa fa-exchange" aria-hidden="true"></i> Change Status </button>' +
                        '<button type="button" title="Delete" class="fcc-dt-mass-btn dt-delete" disabled> <i class="fa fa-trash-o" aria-hidden="true"></i> Delete </button>'
                    );
                },
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML =
                            '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                    {
                        targets: 0,
                        width: "36px",
                        className: "checkbox-column text-center",
                        orderable: false,
                        searchable: false,
                        render: function(e, a, t, n) {
                            return '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
                    },
                    {
                        targets: 1,
                        width: "40px",
                        className: "fcc-idx-cell text-muted fw-bold",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
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
                        sPrevious: '<i class="fa fa-arrow-left"></i>',
                        sNext: '<i class="fa fa-arrow-right"></i>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sSearch: "",
                    sSearchPlaceholder: "Search by name, email or mobile number...",
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
                    '<"fcc-dt-toolbar"<"fcc-dt-length"l><"fcc-dt-search"f><"fcc-dt-actions"B>>rt<"fcc-dt-footer"<"fcc-dt-info"i><"fcc-dt-paginate"p>>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.name = $("#name").val();
                        d.email = $("#email").val();
                        d.mobile_number = $("#mobile_number").val();
                        d.date_range = $("#date_range").val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "",
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: null,
                        name: "",
                        searchable: false,
                        sortable: false
                    },
                    { data: "name", name: "name" },
                    { data: "email", name: "email" },
                    { data: "mobile_number", name: "mobile_number" },
                    { data: "pending_amount", name: "pending_amount" },
                    { data: "pending_days", name: "pending_days" },
                    { data: "view_membership", name: "view_membership" },
                    { data: "status", name: "status" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false
                    }
                ],
                rowCallback: function(row, data, dataIndex) {
                    // Get row ID
                    var rowId = data[0];

                    // If row ID is in the list of selected row IDs
                    if ($.inArray(rowId, rows_selected) !== -1) {
                        $(row)
                            .find('input[type="checkbox"]')
                            .prop("checked", true);
                        $(row).addClass("selected");
                    }
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
                    Franchise.updateDataTableSelectAllCtrl(table);

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
                Franchise.updateDataTableSelectAllCtrl(table);

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
         * Single delete record.
         */
        singleDeleteRecord: function() {
            var $dataTable = $("#dataTable");
            $dataTable.on("click", ".single-delete-record", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                if (!id) return;

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure you want to delete this franchise?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                $.ajax({
                                    type: "DELETE",
                                    url: $dataTable.data("destroy-url"),
                                    data: { ids: [id] },
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                    }
                                });
                                instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                            },
                            true
                        ],
                        [
                            "<button>NO</button>",
                            function(instance, toast) {
                                instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                            }
                        ]
                    ]
                });
            });
        },

        /**
         * Single delete record from action column.
         */
        singleDeleteRecord: function() {
            var $dataTable = $("#dataTable");
            $dataTable.on("click", ".single-delete-record", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                if (!id) return;

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure you want to delete this franchise?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                $.ajax({
                                    type: "DELETE",
                                    url: $dataTable.data("destroy-url"),
                                    data: { ids: [id] },
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                    }
                                });
                                instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                            },
                            true
                        ],
                        [
                            "<button>NO</button>",
                            function(instance, toast) {
                                instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                            }
                        ]
                    ]
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
                    var franchiseOrder = $('#franchise_order_'+rowId.id).val();
                    innerArray.push(rowId.id, franchiseOrder);
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

Franchise.init();
