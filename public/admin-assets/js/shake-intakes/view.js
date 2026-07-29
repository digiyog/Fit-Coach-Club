var ShakeIntakes = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            ShakeIntakes.getShakeIntakes();
            ShakeIntakes.initializeComponents();
            ShakeIntakes.dataTableCustomFilter();
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
         * Get Shake Intakes list.
         */
        getShakeIntakes: function() {
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
                },
                // headerCallback: function(e, a, t, n, s) {
                //     e.getElementsByTagName("th")[0].innerHTML =
                //             '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                // },
                columnDefs: [
                    {
                //         targets: 0,
                //         width: "30px",
                //         className: "",
                //         orderable: !1,
                //         visible: true,
                //         render: function(e, a, t, n) {
                //             return '<label class="new-control new-checkbox checkbox-outline-primary  m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                //         }
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
                        d.month = $("#month").val();
                        d.year = $("#year").val();
                        d.date_range = $("#date_range").val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "serial_no",
                        searchable: false,
                        sortable: false,
                        width:80,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: "name", name: "name" },
                    { data: "coach_name", name: "coach_name" },
                    { data: "attendance", name: "attendance", width:150 },
                    { data: "days", name: "days", width:150 },
                    { data: "date", name: "date", width:150 },
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
                    ShakeIntakes.updateDataTableSelectAllCtrl(table);

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
                ShakeIntakes.updateDataTableSelectAllCtrl(table);

                // Additional form validation methods
                Components.additionalValidationMethods();
               //----------
            });
        },
    };
})();

ShakeIntakes.init();
