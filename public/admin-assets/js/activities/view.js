var Activity = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    var table;
    var searchTimer = null;

    return {
        /**
         * Initialization.
         */
        init: function() {
            Activity.getActivities();
            Activity.changeStatus();
            Activity.destroyRecord();
            Activity.updateOrder();
            Activity.initFilterControls();
            Activity.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Notification modal trigger feedback
            $(document).on('click', '.btn-save-notif-settings', function() {
                var $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
                setTimeout(function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Saved');
                    if (typeof App !== 'undefined' && App.showNotification) {
                        App.showNotification({ status: true, message: 'Notification settings updated successfully.' });
                    } else if (typeof iziToast !== 'undefined') {
                        iziToast.success({ title: 'Success', message: 'Notification settings updated successfully.' });
                    }
                    setTimeout(function() {
                        $('#notificationSettingsModal').modal('hide');
                        $btn.html('Save settings');
                    }, 600);
                }, 500);
            });
        },

        /**
         * Initialize custom filter & search controls.
         */
        initFilterControls: function() {
            // Live Search with Debounce
            $('#act-search-input').on('keyup', function() {
                clearTimeout(searchTimer);
                var query = $(this).val();
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.search(query).draw();
                    }
                }, 300);
            });

            // Filter dropdowns
            $('#act-filter-type, #act-filter-date, #act-filter-status').on('change', function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Per page dropdown
            $('#act-per-page').on('change', function() {
                var len = parseInt($(this).val(), 10) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Reset filters
            $(document).on('click', '#act-clear-filters-btn, .act-btn-reset-filters', function(e) {
                e.preventDefault();
                $('#act-search-input').val('');
                $('#act-filter-type').val('all');
                $('#act-filter-date').val('all');
                $('#act-filter-status').val('all');
                if (data_table) {
                    data_table.search('').ajax.reload();
                }
            });

            // Toggle More Filters
            $('#act-more-filters-btn').on('click', function() {
                $('#act-more-filters-panel').slideToggle(200);
                $(this).toggleClass('active');
            });
        },

        /**
         * Updates "Select all" control in a data table.
         */
        updateDataTableSelectAllCtrl: function(tbl) {
            var $table = tbl.table().node();
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
         * Get Activities list.
         */
        getActivities: function() {
            var $dataTable = $("#dataTable");
            if (!$dataTable.length) return;

            var createUrl = $dataTable.data("create-url") || "#";

            data_table = table = $dataTable.DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom: 'rt<"act-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.activity_type = $("#act-filter-type").val();
                        d.date_filter = $("#act-filter-date").val();
                        d.status = $("#act-filter-status").val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "",
                        searchable: false,
                        sortable: false,
                        width: "36px"
                    },
                    { data: "name", name: "name" },
                    { data: "activity_type", name: "activity_type" },
                    { data: "date", name: "date" },
                    { data: "app_notification", name: "app_notification", searchable: false, sortable: false },
                    { data: "order", name: "order", width: 90 },
                    { data: "status", name: "status", width: 90 },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width: 80
                    }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        width: "36px",
                        className: "text-center",
                        orderable: false,
                        render: function(e, a, t, n) {
                            return '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
                    },
                    {
                        targets: [4, 7],
                        orderable: false,
                        searchable: false
                    }
                ],
                headerCallback: function(e, a, t, n, s) {
                    var thFirst = e.getElementsByTagName("th")[0];
                    if (thFirst) {
                        thFirst.innerHTML =
                            '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                    }
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left me-1"></i> Previous',
                        sNext: 'Next <i class="fa fa-angle-right ms-1"></i>'
                    },
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ activities",
                    sInfoEmpty: "Showing 0 of 0 activities",
                    sInfoFiltered: "(filtered from _MAX_ total activities)",
                    sEmptyTable: `
                        <div class="act-empty-state">
                            <div class="act-empty-icon-wrap">
                                <svg class="act-empty-illustration" width="110" height="110" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="60" cy="60" r="54" fill="#EEF2FF" />
                                    <rect x="36" y="34" width="48" height="46" rx="10" fill="#FFFFFF" stroke="#3B82F6" stroke-width="3"/>
                                    <path d="M36 48H84" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round"/>
                                    <circle cx="48" cy="40" r="3" fill="#2563EB"/>
                                    <circle cx="72" cy="40" r="3" fill="#2563EB"/>
                                    <rect x="46" y="28" width="4" height="8" rx="2" fill="#2563EB"/>
                                    <rect x="70" y="28" width="4" height="8" rx="2" fill="#2563EB"/>
                                    <circle cx="78" cy="74" r="18" fill="#DBEAFE" stroke="#FFFFFF" stroke-width="2.5"/>
                                    <path d="M78 65C74.6863 65 72 67.6863 72 71V75L70 77H86L84 75V71C84 67.6863 81.3137 65 78 65Z" fill="#2563EB"/>
                                    <path d="M76 78C76 79.1046 76.8954 80 78 80C79.1046 80 80 79.1046 80 78" stroke="#1D4ED8" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M88 36L89.5 40L93.5 41.5L89.5 43L88 47L86.5 43L82.5 41.5L86.5 40L88 36Z" fill="#60A5FA"/>
                                    <path d="M30 68L31 71L34 72L31 73L30 76L29 73L26 72L29 71L30 68Z" fill="#93C5FD"/>
                                </svg>
                            </div>
                            <h4 class="act-empty-title">No activities created yet</h4>
                            <p class="act-empty-desc">Create an activity, choose its date and publish it to notify members in the app.</p>
                            <a href="` + createUrl + `" class="btn btn-primary act-btn-create-empty">
                                <i class="fa fa-plus me-1"></i> Create activity
                            </a>
                        </div>
                    `,
                    sZeroRecords: `
                        <div class="act-empty-state">
                            <div class="act-empty-icon-wrap" style="background:#f1f5f9; width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
                                <i class="fa fa-search fa-2x" style="color:#94a3b8;"></i>
                            </div>
                            <h4 class="act-empty-title">No matching activities found</h4>
                            <p class="act-empty-desc">Try adjusting your search or filter options to find what you're looking for.</p>
                            <button type="button" class="btn btn-outline-primary act-btn-reset-filters">
                                <i class="fa fa-refresh me-1"></i> Reset Filters
                            </button>
                        </div>
                    `
                },
                rowCallback: function(row, data, dataIndex) {
                    var rowId = data;
                    if ($.inArray(rowId, rows_selected) !== -1) {
                        $(row).find('input[type="checkbox"]').prop("checked", true);
                        $(row).addClass("selected");
                    }
                }
            });

            // Handle click on row checkbox
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

                if ($dataTable.find('tbody input[type="checkbox"]:checked').length > 0) {
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

                Activity.updateDataTableSelectAllCtrl(table);
                e.stopPropagation();
            });

            // Handle click on "Select all" control
            $dataTable.find("thead").on("click", 'input[name="select_all"]', function(e) {
                if (this.checked) {
                    $dataTable.find('tbody input[type="checkbox"]:not(:checked)').trigger("click");
                    $(".change-status").prop("disabled", false);
                    $(".dt-delete").prop("disabled", false);
                } else {
                    $dataTable.find('tbody input[type="checkbox"]:checked').trigger("click");
                    $(".change-status").prop("disabled", true);
                    $(".dt-delete").prop("disabled", true);
                }
                e.stopPropagation();
            });

            // Handle table draw event
            table.on("draw", function() {
                Activity.updateDataTableSelectAllCtrl(table);

                if (typeof Components !== 'undefined' && Components.additionalValidationMethods) {
                    Components.additionalValidationMethods();
                }

                var info = table.page.info();
                var count = info.recordsDisplay;
                $("#act-records-count").text(count + (count === 1 ? " activity" : " activities"));

                // Reset selection array and toolbar buttons on fresh draw
                rows_selected = [];
                $(".change-status").prop("disabled", true);
                $(".dt-delete").prop("disabled", true);
            });
        },

        /**
         * Change status.
         */
        changeStatus: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            $data_table_container.on("click", ".change-status", function() {
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    if (rowId && rowId.id) {
                        ids.push(rowId.id);
                    }
                });

                if (ids.length === 0) {
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: $dataTable.data("change-status-url"),
                    data: { ids: ids },
                    beforeSend: function() {
                        $(".change-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    },
                    success: function(response) {
                        if (typeof App !== 'undefined' && App.showNotification) {
                            App.showNotification(response);
                        } else if (typeof iziToast !== 'undefined') {
                            iziToast.success({ title: 'Success', message: response.message || 'Status changed successfully' });
                        }
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

            $data_table_container.on("click", ".dt-delete", function() {
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    if (rowId && rowId.id) {
                        ids.push(rowId.id);
                    }
                });

                if (ids.length === 0) {
                    return;
                }

                if (typeof iziToast !== 'undefined') {
                    iziToast.question({
                        timeout: 20000,
                        close: false,
                        overlay: true,
                        displayMode: "once",
                        color: "yellow",
                        id: "question",
                        zindex: 99999,
                        title: "Hey!",
                        message: "Are you sure you want to delete selected activities?",
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
                                            if (typeof App !== 'undefined' && App.showNotification) {
                                                App.showNotification(response);
                                            }
                                            data_table.ajax.reload(null, false);
                                            rows_selected = [];
                                        },
                                        error: function() {},
                                        complete: function() {
                                            $(".dt-delete").prop("disabled", true);
                                            $(".change-status").prop("disabled", true);
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
                    if (confirm("Are you sure you want to delete selected activities?")) {
                        $.ajax({
                            type: "DELETE",
                            url: $dataTable.data("destroy-url"),
                            data: { ids: ids },
                            success: function(response) {
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
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            $data_table_container.on("click", ".update-order", function() {
                var ids = [];
                $(".dataTable tbody tr").each(function(index, elem) {
                    var innerArray = [];
                    var $row = $(this).closest("tr");
                    var data = table.row($row).data();
                    if (data && data.id) {
                        var activityOrder = $('#activity_order_' + data.id).val();
                        innerArray.push(data.id, activityOrder);
                        ids.push(innerArray);
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
                        if (typeof App !== 'undefined' && App.showNotification) {
                            App.showNotification(response);
                        } else if (typeof iziToast !== 'undefined') {
                            iziToast.success({ title: 'Success', message: response.message || 'Order updated successfully' });
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
