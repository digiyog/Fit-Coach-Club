var Achievement = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    var searchTimer;

    return {
        /**
         * Initialization.
         */
        init: function() {
            Achievement.getAchievements();
            Achievement.changeStatus();
            Achievement.destroyRecord();
            Achievement.updateOrder();
            Achievement.initializeComponents();
            Achievement.bindCustomFilters();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Initialize Components
        },

        /**
         * Bind custom search, filters and page length controls
         */
        bindCustomFilters: function() {
            // Custom Search Input with debounce
            $('#custom-search-input').on('keyup input', function() {
                var searchVal = $(this).val();
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    data_table.search(searchVal).draw();
                }, 300);
            });

            // Filter Dropdowns
            $('#filter-type, #filter-status, #filter-visibility').on('change', function() {
                data_table.ajax.reload();
            });

            // Per page dropdown
            $('#custom-page-length').on('change', function() {
                var newLen = parseInt($(this).val(), 10) || 20;
                data_table.page.len(newLen).draw();
            });

            // More Filters button
            $('#btnMoreFilters').on('click', function(e) {
                e.preventDefault();
                // Focus on first filter
                $('#filter-type').focus();
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

                // If all of the checkboxes are checked
            } else if ($chkbox_checked.length === $chkbox_all.length && $chkbox_all.length > 0) {
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
         * Get Achievements list.
         */
        getAchievements: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML =
                        '<label class="new-control new-checkbox checkbox-outline-primary m-auto mb-0 d-flex align-items-center justify-content-center">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                    {
                        targets: 0,
                        width: "36px",
                        className: "text-center",
                        orderable: false,
                        visible: true,
                        render: function(e, a, t, n) {
                            return '<label class="new-control new-checkbox checkbox-outline-primary m-auto mb-0 d-flex align-items-center justify-content-center">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-row-' + t.id + '">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
                    }
                ],
                buttons: [],
                oLanguage: {
                    oPaginate: {
                        sPrevious:
                            '<i class="fa fa-angle-left"></i> Previous',
                        sNext:
                            'Next <i class="fa fa-angle-right"></i>'
                    },
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ achievements",
                    sInfoEmpty: "Showing 0 of 0 achievements",
                    sSearch: "",
                    sSearchPlaceholder: "Search achievements...",
                    sLengthMenu: "Results : _MENU_",
                    sEmptyTable: "No achievements found"
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
                        d.filter_type = $('#filter-type').val();
                        d.filter_status = $('#filter-status').val();
                        d.filter_visibility = $('#filter-visibility').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "",
                        searchable: false,
                        sortable: false
                    },
                    { data: "title", name: "title" },
                    { data: "type", name: "type" },
                    { data: "in_app_show", name: "in_app_show" },
                    { data: "show_achievement", name: "show_achievement" },
                    { data: "order", name: "order", width: 90 },
                    { data: "status", name: "status", width: 90 },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width: 80,
                        className: "text-end"
                    }
                ],
                rowCallback: function(row, data, dataIndex) {
                    var rowId = data;

                    if ($.inArray(rowId, rows_selected) !== -1) {
                        $(row)
                            .find('input[type="checkbox"]')
                            .prop("checked", true);
                        $(row).addClass("selected");
                    }
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    var info = api.page.info();

                    // Update summary counts
                    $('#table-record-count-text').text(info.recordsDisplay + ' achievements');

                    // Empty state toggle
                    if (info.recordsTotal === 0) {
                        $('#empty-state-view').show();
                        $('.data-table-container').hide();
                    } else {
                        $('#empty-state-view').hide();
                        $('.data-table-container').show();
                    }

                    // Reset selection buttons if no rows checked
                    if ($dataTable.find('tbody input[type="checkbox"]:checked').length === 0) {
                        $(".change-status").prop("disabled", true).removeClass("enabled");
                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                    }
                }
            });

            // Handle click on checkbox
            $dataTable
                .find("tbody")
                .on("click", 'input[type="checkbox"]', function(e) {
                    var $row = $(this).closest("tr");
                    var data = table.row($row).data();
                    var rowId = data;
                    var index = $.inArray(rowId, rows_selected);

                    if (this.checked && index === -1) {
                        rows_selected.push(rowId);
                    } else if (!this.checked && index !== -1) {
                        rows_selected.splice(index, 1);
                    }

                    if ($dataTable.find('tbody input[type="checkbox"]:checked').length > 0) {
                        $(".change-status").prop("disabled", false).addClass("enabled");
                        $(".dt-delete").prop("disabled", false).addClass("danger-enabled");
                    } else {
                        $(".change-status").prop("disabled", true).removeClass("enabled");
                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                    }

                    if (this.checked) {
                        $row.addClass("selected");
                    } else {
                        $row.removeClass("selected");
                    }

                    Achievement.updateDataTableSelectAllCtrl(table);
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
                        $(".change-status").prop("disabled", false).addClass("enabled");
                        $(".dt-delete").prop("disabled", false).addClass("danger-enabled");
                    } else {
                        $dataTable
                            .find('tbody input[type="checkbox"]:checked')
                            .trigger("click");
                        $(".change-status").prop("disabled", true).removeClass("enabled");
                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                    }

                    e.stopPropagation();
                });

            // Handle table draw event
            table.on("draw", function() {
                Achievement.updateDataTableSelectAllCtrl(table);
                if (typeof Components !== "undefined" && typeof Components.additionalValidationMethods === "function") {
                    Components.additionalValidationMethods();
                }
            });
        },

        /**
         * Change status.
         */
        changeStatus: function() {
            var $dataTable = $("#dataTable");

            $(document).on("click", ".change-status.enabled", function() {
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    if (rowId && rowId.id) {
                        ids.push(rowId.id);
                    }
                });

                if (ids.length === 0) return;

                $.ajax({
                    type: "POST",
                    url: $dataTable.data("change-status-url"),
                    data: { ids: ids },
                    beforeSend: function() {
                        $(".change-status").prop("disabled", true).removeClass("enabled");
                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                    },
                    success: function(response) {
                        if (typeof App !== "undefined" && typeof App.showNotification === "function") {
                            App.showNotification(response);
                        } else if (typeof iziToast !== "undefined") {
                            iziToast.success({ title: 'Success', message: response._message || 'Status changed successfully.' });
                        }
                        data_table.ajax.reload(null, false);
                        rows_selected = [];
                    },
                    error: function() {},
                    complete: function() {
                        $(".change-status").prop("disabled", true).removeClass("enabled");
                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                    }
                });
            });
        },

        /**
         * Destroy record.
         */
        destroyRecord: function() {
            var $dataTable = $("#dataTable");

            $(document).on("click", ".dt-delete.danger-enabled", function() {
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    if (rowId && rowId.id) {
                        ids.push(rowId.id);
                    }
                });

                if (ids.length === 0) return;

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure you want to delete selected achievements?",
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
                                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                                        $(".change-status").prop("disabled", true).removeClass("enabled");
                                    },
                                    success: function(response) {
                                        if (typeof App !== "undefined" && typeof App.showNotification === "function") {
                                            App.showNotification(response);
                                        } else if (typeof iziToast !== "undefined") {
                                            iziToast.success({ title: 'Success', message: response._message || 'Record deleted successfully.' });
                                        }
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                    complete: function() {
                                        $(".dt-delete").prop("disabled", true).removeClass("danger-enabled");
                                        $(".change-status").prop("disabled", true).removeClass("enabled");
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
                    onClosing: function(instance, toast, closedBy) {},
                    onClosed: function(instance, toast, closedBy) {}
                });
            });
        },

        /**
         * Update Order.
         */
        updateOrder: function() {
            var $dataTable = $("#dataTable");

            $(document).on("click", ".update-order", function() {
                var ids = [];
                $("#dataTable tbody tr").each(function(index, elem) {
                    var $row = $(this);
                    var data = table.row($row).data();
                    if (data && data.id) {
                        var achievementOrder = $('#achievement_order_' + data.id).val();
                        if (achievementOrder !== undefined) {
                            ids.push([data.id, achievementOrder]);
                        }
                    }
                });

                if (ids.length === 0) {
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: $dataTable.data("update-order-url"),
                    data: { ids: ids },
                    beforeSend: function() {
                        $(".update-order").prop("disabled", true);
                    },
                    success: function(response) {
                        if (typeof App !== "undefined" && typeof App.showNotification === "function") {
                            App.showNotification(response);
                        } else if (typeof iziToast !== "undefined") {
                            iziToast.success({ title: 'Success', message: response._message || 'Order updated successfully.' });
                        }
                        data_table.ajax.reload(null, false);
                        rows_selected = [];
                    },
                    error: function() {},
                    complete: function() {
                        $(".update-order").prop("disabled", false);
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    Achievement.init();
});
