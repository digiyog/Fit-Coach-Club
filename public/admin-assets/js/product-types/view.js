var ProductType = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            ProductType.getProductTypes();
            ProductType.changeStatus();
            ProductType.destroyRecord();
            ProductType.singleDeleteRecord();
            ProductType.filterToggle();
        },

        /**
         * Toggle filter drawer
         */
        filterToggle: function() {
            $(document).on("click", "#btnToggleFilter, .filter-button", function(e) {
                e.preventDefault();
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

            if (!chkbox_select_all) return;

            // If none of the checkboxes are checked
            if ($chkbox_checked.length === 0) {
                chkbox_select_all.checked = false;
                if ("indeterminate" in chkbox_select_all) {
                    chkbox_select_all.indeterminate = false;
                }
            } else if ($chkbox_checked.length === $chkbox_all.length) {
                chkbox_select_all.checked = true;
                if ("indeterminate" in chkbox_select_all) {
                    chkbox_select_all.indeterminate = false;
                }
            } else {
                chkbox_select_all.checked = true;
                if ("indeterminate" in chkbox_select_all) {
                    chkbox_select_all.indeterminate = true;
                }
            }
        },

        /**
         * Get Product Types list.
         */
        getProductTypes: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                initComplete: function() {
                    $(".dt-buttons").addClass("btn-toolbar");
                    $(".btn-toolbar").html(
                        '<button type="button" class="fcc-dt-filter-btn" id="btnToggleFilter" title="Filter"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg> Filter</button>' +
                        '<button type="button" title="Delete" class="fcc-dt-mass-btn dt-delete" disabled><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Delete</button>'
                    );
                    $(".fcc-dt-search svg, .dataTables_filter svg, .fcc-dt-search i, .dataTables_filter i").remove();
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
                            return '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
                    },
                    {
                        targets: 1,
                        width: "44px",
                        className: "text-center fcc-idx-col",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return '<span class="fcc-row-idx">' + (meta.row + meta.settings._iDisplayStart + 1) + '</span>';
                        }
                    }
                ],
                buttons: {
                    buttons: []
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sSearch: "",
                    sSearchPlaceholder: "Search product type by name...",
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
                    data: function(d) {}
                },
                columns: [
                    {
                        data: null,
                        name: "",
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: "row_number",
                        name: "id",
                        searchable: false,
                        sortable: false
                    },
                    { data: "name", name: "name", className: "fcc-col-name" },
                    { data: "status", name: "status", width: "130px", className: "text-center" },
                    { data: "created_at", name: "created_at", width: "220px" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width: "90px",
                        className: "text-center"
                    }
                ],
                rowCallback: function(row, data, dataIndex) {
                    var rowId = data.id;
                    if ($.inArray(rowId, rows_selected) !== -1) {
                        $(row).find('input[type="checkbox"]').prop("checked", true);
                        $(row).addClass("selected");
                    }
                }
            });

            // Handle click on checkbox
            $dataTable
                .find("tbody")
                .on("click", 'input[type="checkbox"]', function(e) {
                    var $row = $(this).closest("tr");
                    var data = table.row($row).data();
                    if (!data) return;

                    var rowId = data.id;
                    var index = $.inArray(rowId, rows_selected);

                    if (this.checked && index === -1) {
                        rows_selected.push(rowId);
                    } else if (!this.checked && index !== -1) {
                        rows_selected.splice(index, 1);
                    }

                    if (rows_selected.length > 0) {
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

                    ProductType.updateDataTableSelectAllCtrl(table);
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
                    e.stopPropagation();
                });

            // Handle table draw event
            table.on("draw", function() {
                ProductType.updateDataTableSelectAllCtrl(table);
                if (typeof feather !== "undefined") {
                    feather.replace();
                }
                $(".fcc-dt-search svg, .dataTables_filter svg, .fcc-dt-search i, .dataTables_filter i").remove();
            });
        },

        /**
         * Change status.
         */
        changeStatus: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $("#dataTable");

            $data_table_container.on("click", ".change-status", function() {
                if (rows_selected.length === 0) return;

                $.ajax({
                    type: "POST",
                    url: $dataTable.data("change-status-url"),
                    data: { ids: rows_selected },
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
         * Single record delete
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
                    id: "single_delete_question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure you want to delete this product type?",
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
                                    },
                                    error: function() {}
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
         * Batch destroy records.
         */
        destroyRecord: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $("#dataTable");

            $data_table_container.on("click", ".dt-delete", function() {
                if (rows_selected.length === 0) return;

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "batch_delete_question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure you want to delete the selected product types?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                $.ajax({
                                    type: "DELETE",
                                    url: $dataTable.data("destroy-url"),
                                    data: { ids: rows_selected },
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
                    ]
                });
            });
        }
    };
})();

ProductType.init();
