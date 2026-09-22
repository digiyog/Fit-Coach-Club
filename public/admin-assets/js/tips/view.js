var Tip = (function() {
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
            Tip.getTips();
            Tip.changeStatus();
            Tip.destroyRecord();
            Tip.updateOrder();
            Tip.viewVideo();
            Tip.initFilterControls();
            Tip.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Content settings modal save feedback
            $(document).on('click', '.btn-save-content-settings', function() {
                var $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
                setTimeout(function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Saved');
                    if (typeof App !== 'undefined' && App.showNotification) {
                        App.showNotification({ status: true, message: 'Content settings updated successfully.' });
                    } else if (typeof iziToast !== 'undefined') {
                        iziToast.success({ title: 'Success', message: 'Content settings updated successfully.' });
                    }
                    setTimeout(function() {
                        $('#contentSettingsModal').modal('hide');
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
            $('#tip-search-input').on('keyup', function() {
                clearTimeout(searchTimer);
                var query = $(this).val();
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.search(query).draw();
                    }
                }, 300);
            });

            // Filter dropdowns
            $('#tip-filter-coach, #tip-filter-status').on('change', function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Per page dropdown
            $('#tip-per-page').on('change', function() {
                var len = parseInt($(this).val(), 10) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Reset filters
            $(document).on('click', '#tip-clear-filters-btn, .tip-btn-reset-filters', function(e) {
                e.preventDefault();
                $('#tip-search-input').val('');
                $('#tip-filter-coach').val('all');
                $('#tip-filter-status').val('all');
                if (data_table) {
                    data_table.search('').ajax.reload();
                }
            });

            // Toggle More Filters
            $('#tip-more-filters-btn').on('click', function() {
                $('#tip-more-filters-panel').slideToggle(200);
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
         * Get Tips list.
         */
        getTips: function() {
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
                dom: 'rt<"tip-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.coach = $("#tip-filter-coach").val();
                        d.status = $("#tip-filter-status").val();
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
                    { data: "coach_name", name: "coach_name", width: 140 },
                    { data: "link", name: "link", width: 170 },
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
                        targets: [3, 6],
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
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ video tips",
                    sInfoEmpty: "Showing 0 of 0 video tips",
                    sInfoFiltered: "(filtered from _MAX_ total video tips)",
                    sEmptyTable: `
                        <div class="tip-empty-state">
                            <div class="tip-empty-icon-wrap">
                                <svg class="tip-empty-illustration" width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="60" cy="60" r="54" fill="#EEF2FF" />
                                    <!-- Play Button Circle -->
                                    <circle cx="60" cy="58" r="22" fill="#2563EB"/>
                                    <polygon points="56,48 70,58 56,68" fill="#FFFFFF"/>
                                    <!-- Lightbulb badge left -->
                                    <circle cx="38" cy="74" r="14" fill="#FFFFFF" stroke="#3B82F6" stroke-width="2.5"/>
                                    <path d="M38 67C35.5 67 33.5 69 33.5 71.5C33.5 73.1 34.4 74.4 35.6 75.2V77H40.4V75.2C41.6 74.4 42.5 73.1 42.5 71.5C42.5 69 40.5 67 38 67Z" fill="#F59E0B"/>
                                    <rect x="36" y="78" width="4" height="2" rx="1" fill="#D97706"/>
                                    <!-- Link badge right -->
                                    <circle cx="82" cy="74" r="14" fill="#FFFFFF" stroke="#3B82F6" stroke-width="2.5"/>
                                    <path d="M78 78L86 70M76 74L79 71C80.5 69.5 83 69.5 84.5 71C86 72.5 86 75 84.5 76.5L82 79" stroke="#2563EB" stroke-width="2" stroke-linecap="round"/>
                                    <!-- Sparkles -->
                                    <path d="M88 34L89.5 38L93.5 39.5L89.5 41L88 45L86.5 41L82.5 39.5L86.5 38L88 34Z" fill="#60A5FA"/>
                                    <path d="M28 48L29 51L32 52L29 53L28 56L27 53L24 52L27 51L28 48Z" fill="#93C5FD"/>
                                </svg>
                            </div>
                            <h4 class="tip-empty-title">No video tips added yet</h4>
                            <p class="tip-empty-desc">Add a title, select a coach and paste a YouTube link to share helpful content with members.</p>
                            <a href="` + createUrl + `" class="btn btn-primary tip-btn-create-empty">
                                <i class="fa fa-plus me-1"></i> Add video tip
                            </a>
                        </div>
                    `,
                    sZeroRecords: `
                        <div class="tip-empty-state">
                            <div class="tip-empty-icon-wrap" style="background:#f1f5f9; width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
                                <i class="fa fa-search fa-2x" style="color:#94a3b8;"></i>
                            </div>
                            <h4 class="tip-empty-title">No matching video tips found</h4>
                            <p class="tip-empty-desc">Try adjusting your search or coach filters to find what you're looking for.</p>
                            <button type="button" class="btn btn-outline-primary tip-btn-reset-filters">
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

                Tip.updateDataTableSelectAllCtrl(table);
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
                Tip.updateDataTableSelectAllCtrl(table);

                if (typeof Components !== 'undefined' && Components.additionalValidationMethods) {
                    Components.additionalValidationMethods();
                }

                var info = table.page.info();
                var count = info.recordsDisplay;
                $("#tip-records-count").text(count + (count === 1 ? " video tip" : " video tips"));

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
                        message: "Are you sure you want to delete selected video tips?",
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
                    if (confirm("Are you sure you want to delete selected video tips?")) {
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
                        var tipOrder = $('#tip_order_' + data.id).val();
                        innerArray.push(data.id, tipOrder);
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
        },

        /**
         * View Video Modal.
         */
        viewVideo: function() {
            var $source = $(".data-table-container");
            $source.on("click", ".view-video", function() {
                var $this = $(this);
                var $configuration_modal = $("#pageModal");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function() {});

                $configuration_modal.on("hidden.bs.modal", function() {
                    if (typeof App !== 'undefined' && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    Tip.init();
});
