var Achievement = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
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
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Initialize Components
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
         * Get Achievements list.
         */
        getAchievements: function() {
            var $dataTable = $("#dataTable");
            var createUrl = $dataTable.data("create-url") || "/achievements/create";

            data_table = table = $dataTable.DataTable({
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML =
                            '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                    {
                        targets: 0,
                        width: "36px",
                        className: "text-center",
                        orderable: false,
                        visible: true,
                        render: function(e, a, t, n) {
                            return '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
                    }
                ],
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<span class="ach-nav-arrow"><i class="fa fa-angle-left"></i> Previous</span>',
                        sNext: '<span class="ach-nav-arrow">Next <i class="fa fa-angle-right"></i></span>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sInfoEmpty: "Showing 0 of 0 achievements",
                    sInfoFiltered: "(filtered from _MAX_ total achievements)",
                    sSearch: '<i data-feather="search"></i>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_",
                    sEmptyTable: `
                        <div class="ach-empty-illustration-wrap">
                            <div class="ach-empty-icon-circle">
                                <div class="ach-sparkle-dot s1">✦</div>
                                <div class="ach-sparkle-dot s2">✦</div>
                                <div class="ach-sparkle-dot s3">✦</div>
                                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="6"></circle>
                                    <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                </svg>
                            </div>
                            <h4 class="ach-empty-heading">No achievements yet</h4>
                            <p class="ach-empty-text">Create your first achievement to start celebrating member progress.</p>
                            <a href="${createUrl}" class="ach-btn ach-btn-create ach-empty-create-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Create achievement
                            </a>
                        </div>
                    `,
                    sZeroRecords: `
                        <div class="ach-empty-illustration-wrap">
                            <div class="ach-empty-icon-circle" style="background: #f1f5f9; border-color: #e2e8f0;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                            <h4 class="ach-empty-heading">No matching achievements found</h4>
                            <p class="ach-empty-text">Try adjusting your search criteria or clearing filters.</p>
                        </div>
                    `
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom: 'rt<"ach-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.type = $('#ach-filter-type').val();
                        d.status = $('#ach-filter-status').val();
                        d.visibility = $('#ach-filter-visibility').val();
                        d.in_app_show = $('#ach-filter-inapp').val();
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
                    { data: "order", name: "order" , width: 90 },
                    { data: "status", name: "status" , width: 90 },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width: 70,
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

            // Live Search Integration
            $('#ach-search-input').on('keyup input', function() {
                table.search($(this).val()).draw();
            });

            // Page Length Integration
            $('#ach-per-page').on('change', function() {
                var len = parseInt($(this).val(), 10);
                table.page.len(len).draw();
            });

            // Filter Dropdowns Integration
            $('.ach-filter-select').on('change', function() {
                table.ajax.reload();
            });

            // More Filters Toggle
            $('#ach-more-filters-btn').on('click', function(e) {
                e.preventDefault();
                $('#ach-more-filters-collapse').slideToggle(200);
                $(this).toggleClass('open');
            });

            // Clear All Filters
            $('#ach-clear-filters-btn').on('click', function(e) {
                e.preventDefault();
                $('#ach-search-input').val('');
                $('#ach-filter-type').val('all');
                $('#ach-filter-status').val('all');
                $('#ach-filter-visibility').val('all');
                $('#ach-filter-inapp').val('all');
                table.search('').ajax.reload();
            });

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
                        $(".change-status").prop("disabled", false).addClass('active-btn');
                        $(".dt-delete").prop("disabled", false).addClass('active-btn');
                    } else {
                        $(".change-status").prop("disabled", true).removeClass('active-btn');
                        $(".dt-delete").prop("disabled", true).removeClass('active-btn');
                    }

                    if (this.checked) {
                        $row.addClass("selected");
                    } else {
                        $row.removeClass("selected");
                    }

                    // Update state of "Select all" control
                    Achievement.updateDataTableSelectAllCtrl(table);

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
                        $(".change-status").prop("disabled", false).addClass('active-btn');
                        $(".dt-delete").prop("disabled", false).addClass('active-btn');
                    } else {
                        $dataTable
                            .find('tbody input[type="checkbox"]:checked')
                            .trigger("click");
                        $(".change-status").prop("disabled", true).removeClass('active-btn');
                        $(".dt-delete").prop("disabled", true).removeClass('active-btn');
                    }

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });

            // Handle table draw event
            table.on("draw", function() {
                // Update state of "Select all" control
                Achievement.updateDataTableSelectAllCtrl(table);

                // Additional form validation methods
                Components.additionalValidationMethods();

                // Dynamic Count Syncing
                var pageInfo = table.page.info();
                var count = pageInfo.recordsTotal || 0;
                $('#ach-records-count').text(count + ' achievements');
                $('.ach-stat-count').text(count);

                // Dynamic Publishing Checklist Syncing
                if (count > 0) {
                    $('.ach-checklist-status').text('3 of 3 complete');
                    $('.ach-progress-fill').css('width', '100%');
                    $('.ach-step-num').addClass('step-done');
                } else {
                    $('.ach-checklist-status').text('0 of 3 complete');
                    $('.ach-progress-fill').css('width', '0%');
                    $('.ach-step-num').removeClass('step-done');
                }
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
                    var achievementOrder = $('#achievement_order_'+rowId.id).val();
                    innerArray.push(rowId.id, achievementOrder);
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

Achievement.init();
