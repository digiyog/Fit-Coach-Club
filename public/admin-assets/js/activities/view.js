var Activity = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    var searchTimer = null;

    return {
        /**
         * Initialization.
         */
        init: function() {
            Activity.getActivities();
            Activity.initFilters();
            Activity.changeStatus();
            Activity.destroyRecord();
            Activity.updateOrder();
        },

        /**
         * Updates "Select all" control in a data table
         */
        updateDataTableSelectAllCtrl: function(table) {
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[type="checkbox"]', $table);
            var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

            if (!chkbox_select_all) return;

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
         * Updates action toolbar buttons status based on selection
         */
        updateActionButtons: function() {
            var selectedCount = $('#dataTable tbody input[type="checkbox"]:checked').length;
            var $btnStatus = $(".change-status");
            var $btnDelete = $(".dt-delete");

            if (selectedCount > 0) {
                $btnStatus.prop("disabled", false).addClass("enabled");
                $btnDelete.prop("disabled", false).addClass("danger-enabled");
            } else {
                $btnStatus.prop("disabled", true).removeClass("enabled");
                $btnDelete.prop("disabled", true).removeClass("danger-enabled");
            }
        },

        /**
         * Get Activities list.
         */
        getActivities: function() {
            var $dataTable = $("#dataTable");

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {
                    var th = e.getElementsByTagName("th")[0];
                    if (th) {
                        th.innerHTML =
                            '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent" id="act-select-all">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        width: "35px",
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        render: function(e, a, t, n) {
                            return '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" class="new-control-input child-chk" id="act_chk_' + t.id + '">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
                    }
                ],
                buttons: {
                    buttons: []
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ activities",
                    sInfoEmpty: "Showing 0 of 0 activities",
                    sZeroRecords: "No matching activities found"
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 100],
                    [20, 50, 100]
                ],
                pageLength: 20,
                dom: '<"row"<"col-md-12"rt><"col-md-12"<"d-flex align-items-center justify-content-between flex-wrap gap-2 pt-3 px-2"<"act-info-wrap"i><"act-paginate-wrap"p>>>>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.activity_type = $("#filter-activity-type").val();
                        d.date = $("#filter-date").val();
                        d.status = $("#filter-status").val();
                    }
                },
                columns: [
                    { data: null, name: "id", searchable: false, sortable: false },
                    { data: "name", name: "name" },
                    { data: "activity_type", name: "activity_type" },
                    { data: "date", name: "date" },
                    { data: "app_notification", name: "status" },
                    { data: "order", name: "order", width: "90px" },
                    { data: "status", name: "status", width: "90px" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width: "80px"
                    }
                ],
                rowCallback: function(row, data, dataIndex) {
                    var rowId = data;
                    if ($.inArray(rowId, rows_selected) !== -1) {
                        $(row).find('input[type="checkbox"]').prop("checked", true);
                        $(row).addClass("selected");
                    }
                }
            });

            // Handle click on checkbox in tbody
            $dataTable.find("tbody").on("click", 'input[type="checkbox"]', function(e) {
                var $row = $(this).closest("tr");
                var data = table.row($row).data();
                var rowId = data;
                var index = $.inArray(rowId, rows_selected);

                if (this.checked && index === -1) {
                    rows_selected.push(rowId);
                } else if (!this.checked && index !== -1) {
                    rows_selected.splice(index, 1);
                }

                if (this.checked) {
                    $row.addClass("selected");
                } else {
                    $row.removeClass("selected");
                }

                Activity.updateDataTableSelectAllCtrl(table);
                Activity.updateActionButtons();
                e.stopPropagation();
            });

            // Handle click on "Select all" control
            $dataTable.find("thead").on("click", 'input[name="select_all"]', function(e) {
                if (this.checked) {
                    $dataTable.find('tbody input[type="checkbox"]:not(:checked)').trigger("click");
                } else {
                    $dataTable.find('tbody input[type="checkbox"]:checked').trigger("click");
                }
                Activity.updateActionButtons();
                e.stopPropagation();
            });

            // Handle table draw event
            table.on("draw", function() {
                var info = table.page.info();
                $("#act-count-text").text(info.recordsTotal + " activities");

                // Toggle Empty State when no records exist and no search filter applied
                var hasSearch = $("#act-search-input").val().trim().length > 0;
                var hasFilter = $("#filter-activity-type").val() !== "" || $("#filter-date").val() !== "" || $("#filter-status").val() !== "";

                if (info.recordsTotal === 0 && !hasSearch && !hasFilter) {
                    $(".data-table-container").hide();
                    $("#act-empty-state").show();
                } else {
                    $(".data-table-container").show();
                    $("#act-empty-state").hide();
                }

                Activity.updateDataTableSelectAllCtrl(table);
                Activity.updateActionButtons();

                if (typeof Components !== "undefined" && typeof Components.additionalValidationMethods === "function") {
                    Components.additionalValidationMethods();
                }
            });
        },

        /**
         * Initialize search and filter inputs
         */
        initFilters: function() {
            // Live Search with Debounce
            $("#act-search-input").on("input keyup", function() {
                var val = $(this).val();
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    data_table.search(val).draw();
                }, 300);
            });

            // Filter dropdowns
            $("#filter-activity-type, #filter-date, #filter-status").on("change", function() {
                data_table.ajax.reload();
            });

            // Per page dropdown
            $("#act-page-length").on("change", function() {
                var len = parseInt($(this).val(), 10);
                data_table.page.len(len).draw();
            });

            // Reset filters
            $("#btnResetFilters").on("click", function(e) {
                e.preventDefault();
                $("#act-search-input").val("");
                $("#filter-activity-type").val("");
                $("#filter-date").val("");
                $("#filter-status").val("");
                data_table.search("").ajax.reload();
            });
        },

        /**
         * Change status.
         */
        changeStatus: function() {
            var $data_table_container = $(".act-main-card");
            var $dataTable = $("#dataTable");

            $data_table_container.on("click", ".change-status", function() {
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
                        }
                        data_table.ajax.reload(null, false);
                        rows_selected = [];
                    },
                    error: function() {},
                    complete: function() {
                        Activity.updateActionButtons();
                    }
                });
            });
        },

        /**
         * Destroy record.
         */
        destroyRecord: function() {
            var $data_table_container = $(".act-main-card");
            var $dataTable = $("#dataTable");

            $data_table_container.on("click", ".dt-delete", function() {
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    if (rowId && rowId.id) {
                        ids.push(rowId.id);
                    }
                });

                if (ids.length === 0) return;

                if (typeof iziToast !== "undefined") {
                    iziToast.question({
                        timeout: 20000,
                        close: false,
                        overlay: true,
                        displayMode: "once",
                        color: "yellow",
                        id: "question",
                        zindex: 99999,
                        title: "Hey!",
                        message: "Are you sure you want to delete the selected activities?",
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
                                            }
                                            data_table.ajax.reload(null, false);
                                            rows_selected = [];
                                        },
                                        error: function() {},
                                        complete: function() {
                                            Activity.updateActionButtons();
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
                } else {
                    if (confirm("Are you sure you want to delete the selected activities?")) {
                        $.ajax({
                            type: "DELETE",
                            url: $dataTable.data("destroy-url"),
                            data: { ids: ids },
                            success: function(response) {
                                if (typeof App !== "undefined" && typeof App.showNotification === "function") {
                                    App.showNotification(response);
                                }
                                data_table.ajax.reload(null, false);
                                rows_selected = [];
                            }
                        });
                    }
                }
            });
        },

        /**
         * Update Order.
         */
        updateOrder: function() {
            var $data_table_container = $(".act-main-card");
            var $dataTable = $("#dataTable");

            $data_table_container.on("click", ".update-order", function() {
                var ids = [];
                $("#dataTable tbody tr").each(function(index, elem) {
                    var $row = $(this);
                    var data = table.row($row).data();
                    if (data && data.id) {
                        var activityOrder = $("#activity_order_" + data.id).val();
                        ids.push([data.id, activityOrder]);
                    }
                });

                if (ids.length === 0) return;

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
    Activity.init();
});
