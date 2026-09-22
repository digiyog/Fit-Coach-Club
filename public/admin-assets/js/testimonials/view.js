var Testimonial = (function() {
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
            Testimonial.getTestimonials();
            Testimonial.changeStatus();
            Testimonial.destroyRecord();
            Testimonial.updateOrder();
            Testimonial.initFilterControls();
            Testimonial.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Display settings modal trigger feedback
            $(document).on('click', '.btn-save-display-settings', function() {
                var $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
                setTimeout(function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i> Saved');
                    if (typeof App !== 'undefined' && App.showNotification) {
                        App.showNotification({ status: true, message: 'Display settings updated successfully.' });
                    } else if (typeof iziToast !== 'undefined') {
                        iziToast.success({ title: 'Success', message: 'Display settings updated successfully.' });
                    }
                    setTimeout(function() {
                        $('#displaySettingsModal').modal('hide');
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
            $('#tst-search-input').on('keyup', function() {
                clearTimeout(searchTimer);
                var query = $(this).val();
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.search(query).draw();
                    }
                }, 300);
            });

            // Filter dropdowns
            $('#tst-filter-format, #tst-filter-channel, #tst-filter-status').on('change', function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Per page dropdown
            $('#tst-per-page').on('change', function() {
                var len = parseInt($(this).val(), 10) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Reset filters
            $(document).on('click', '#tst-clear-filters-btn, .tst-btn-reset-filters', function(e) {
                e.preventDefault();
                $('#tst-search-input').val('');
                $('#tst-filter-format').val('all');
                $('#tst-filter-channel').val('all');
                $('#tst-filter-status').val('all');
                if (data_table) {
                    data_table.search('').ajax.reload();
                }
            });

            // Toggle More Filters
            $('#tst-more-filters-btn').on('click', function() {
                $('#tst-more-filters-panel').slideToggle(200);
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
         * Get Testimonials list.
         */
        getTestimonials: function() {
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
                dom: 'rt<"tst-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.format = $("#tst-filter-format").val();
                        d.channel = $("#tst-filter-channel").val();
                        d.status = $("#tst-filter-status").val();
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
                    { data: "story_type", name: "link", width: 120 },
                    { data: "media", name: "image", width: 110, searchable: false },
                    { data: "display_channels", name: "", searchable: false, sortable: false, width: 140 },
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
                    sInfo: "Showing _START_ to _END_ of _TOTAL_ testimonials",
                    sInfoEmpty: "Showing 0 of 0 testimonials",
                    sInfoFiltered: "(filtered from _MAX_ total testimonials)",
                    sEmptyTable: `
                        <div class="tst-empty-state">
                            <div class="tst-empty-icon-wrap">
                                <svg class="tst-empty-illustration" width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="60" cy="60" r="54" fill="#EEF2FF" />
                                    <!-- Quote Mark -->
                                    <path d="M50 42C43.3726 42 38 47.3726 38 54C38 60.6274 43.3726 66 50 66C50.5 66 51 65.95 51.5 65.86C50.8 70.5 47 74 42 75L43 78C51 77 57 70 57 58V45C57 43.3431 55.6569 42 54 42H50Z" fill="#3B82F6"/>
                                    <path d="M72 42C65.3726 42 60 47.3726 60 54C60 60.6274 65.3726 66 72 66C72.5 66 73 65.95 73.5 65.86C72.8 70.5 69 74 64 75L65 78C73 77 79 70 79 58V45C79 43.3431 77.6569 42 76 42H72Z" fill="#2563EB"/>
                                    <!-- Floating Video Play Badge -->
                                    <circle cx="44" cy="74" r="14" fill="#FFFFFF" stroke="#3B82F6" stroke-width="2.5"/>
                                    <path d="M42 69L48 74L42 79V69Z" fill="#2563EB"/>
                                    <!-- Floating Photo Badge -->
                                    <rect x="68" y="62" width="26" height="22" rx="5" fill="#FFFFFF" stroke="#3B82F6" stroke-width="2.5"/>
                                    <circle cx="74" cy="68" r="2" fill="#2563EB"/>
                                    <path d="M70 79L76 72L81 77L84 74L90 80" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <!-- Sparkles -->
                                    <path d="M92 36L93.5 40L97.5 41.5L93.5 43L92 47L90.5 43L86.5 41.5L90.5 40L92 36Z" fill="#60A5FA"/>
                                    <path d="M28 50L29 53L32 54L29 55L28 58L27 55L24 54L27 53L28 50Z" fill="#93C5FD"/>
                                </svg>
                            </div>
                            <h4 class="tst-empty-title">No testimonials added yet</h4>
                            <p class="tst-empty-desc">Add a member story with a video or image, then publish it to the app or website.</p>
                            <a href="` + createUrl + `" class="btn btn-primary tst-btn-create-empty">
                                <i class="fa fa-plus me-1"></i> Add testimonial
                            </a>
                        </div>
                    `,
                    sZeroRecords: `
                        <div class="tst-empty-state">
                            <div class="tst-empty-icon-wrap" style="background:#f1f5f9; width:80px; height:80px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
                                <i class="fa fa-search fa-2x" style="color:#94a3b8;"></i>
                            </div>
                            <h4 class="tst-empty-title">No matching testimonials found</h4>
                            <p class="tst-empty-desc">Try adjusting your search or filter options to find what you're looking for.</p>
                            <button type="button" class="btn btn-outline-primary tst-btn-reset-filters">
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

                Testimonial.updateDataTableSelectAllCtrl(table);
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
                Testimonial.updateDataTableSelectAllCtrl(table);

                if (typeof Components !== 'undefined' && Components.additionalValidationMethods) {
                    Components.additionalValidationMethods();
                }

                var info = table.page.info();
                var count = info.recordsDisplay;
                $("#tst-records-count").text(count + (count === 1 ? " testimonial" : " testimonials"));

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
                        message: "Are you sure you want to delete selected testimonials?",
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
                    if (confirm("Are you sure you want to delete selected testimonials?")) {
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
                        var testimonialOrder = $('#testimonial_order_' + data.id).val();
                        innerArray.push(data.id, testimonialOrder);
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
    Testimonial.init();
});
