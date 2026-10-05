var PreviousMonthCounsellings = (function() {
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            PreviousMonthCounsellings.getCounsellings();
            PreviousMonthCounsellings.initEvents();
        },

        /**
         * Get Previous Month Counsellings list.
         */
        getCounsellings: function() {
            var $dataTable = $("#previousMonthDataTable");
            if (!$dataTable.length) return;

            $.fn.dataTable.ext.errMode = 'none';

            window.previous_month_table = data_table = $dataTable.DataTable({
                order: [],
                columnDefs: [
                    {
                        targets: "_all",
                        defaultContent: ""
                    },
                    {
                        targets: 0,
                        width: "36px",
                        className: "no-sort no-content text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 10,
                        width: "50px",
                        orderable: false,
                        searchable: false,
                        className: "no-sort no-content text-end"
                    }
                ],
                drawCallback: function(settings) {
                    if (typeof feather !== "undefined") {
                        feather.replace();
                    }
                    if (settings.json) {
                        var totalRecords = settings.json.recordsFiltered !== undefined 
                            ? settings.json.recordsFiltered 
                            : (settings.json.iTotalDisplayRecords !== undefined ? settings.json.iTotalDisplayRecords : (settings.json.iTotalRecords || 0));

                        var activeTab = $('#active_tab').val() || 'all_sessions';
                        var tabText = 'sessions';
                        if (activeTab === 'member_summary') {
                            tabText = "members summary";
                        } else if (activeTab === 'weight_loss') {
                            tabText = "weight loss achievers";
                        } else if (activeTab === 'pending_dues') {
                            tabText = "members with dues";
                        } else {
                            tabText = "recorded sessions";
                        }

                        var coachFilter = $('#coach_name').val();
                        var displayText = totalRecords + ' ' + tabText;
                        if (coachFilter) {
                            displayText += ' (' + coachFilter + ')';
                        }

                        $('#fccTableCountDisplay').text(displayText);
                    }
                },
                buttons: {
                    buttons: [
                        {
                            extend: "excel",
                            className: "buttons-excel",
                            title: "Previous_Month_Counselling_" + $('#selected_month').val() + "_" + $('#selected_year').val(),
                            exportOptions: {
                                modifier: {
                                    page: "all",
                                    search: "none"
                                },
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        }
                    ]
                },
                oLanguage: {
                    oPaginate: {
                        sPrevious:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: -1px;"><polyline points="15 18 9 12 15 6"></polyline></svg> Previous',
                        sNext:
                            'Next <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: -1px;"><polyline points="9 18 15 12 9 6"></polyline></svg>'
                    },
                    sInfo: "Showing _START_–_END_ of _TOTAL_ sessions",
                    sInfoEmpty: "Showing 0 to 0 of 0 sessions",
                    sInfoFiltered: "(filtered from _MAX_ total entries)",
                    sEmptyTable: "No counselling records found for the selected month",
                    sSearch: '<i data-feather="search"></i>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_"
                },
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pageLength: 25,
                dom: '<"fcc-modern-table-wrap"rt><"fcc-dt-footer"ip>',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.name = $("#fccSearchInput").val();
                        d.coach_name = $("#coach_name").val();
                        d.plan_id = $("#plan_id").val();
                        d.month = $("#selected_month").val();
                        d.year = $("#selected_year").val();
                        d.tab = $("#active_tab").val();
                    }
                },
                columns: [
                    {
                        data: "checkbox",
                        name: "checkbox",
                        searchable: false,
                        sortable: false,
                        width: "36px",
                        className: "no-sort no-content text-center"
                    },
                    { data: "name", name: "name" },
                    { data: "att", name: "att", width: "70px" },
                    { data: "coach_name", name: "coach_name" },
                    { data: "plan", name: "plan", className: "col-plan", width: "135px" },
                    { data: "days", name: "days", width: "65px" },
                    { data: "progress", name: "progress", className: "text-nowrap" },
                    { data: "dues", name: "dues", width: "75px", className: "text-nowrap" },
                    { data: "meal", name: "meal", width: "95px", className: "text-nowrap" },
                    { data: "completed_at", name: "completed_at", className: "text-nowrap" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        className: "no-sort no-content text-end",
                        width: "50px"
                    }
                ]
            });
        },

        /**
         * Initialize event listeners.
         */
        initEvents: function() {
            function reloadDataTable() {
                if ($.fn.DataTable.isDataTable('#previousMonthDataTable')) {
                    $('#previousMonthDataTable').DataTable().ajax.reload();
                }
            }

            // Tabs navigation
            $('.fcc-tab-item-link').on('click', function(e) {
                e.preventDefault();
                $('.fcc-tab-item-link').removeClass('active');
                $(this).addClass('active');
                var tab = $(this).data('tab');
                $('#active_tab').val(tab);
                reloadDataTable();
            });

            // Live search with debounce
            var searchTimer;
            $('#fccSearchInput').on('keyup input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    reloadDataTable();
                }, 300);
            });

            // Coach filter selection
            $(document).on('click', '[data-filter-type="coach"]', function(e) {
                e.preventDefault();
                var val = $(this).data('value');
                var label = val ? val : 'All coaches';
                $('#coach_name').val(val);
                $('#coachFilterLabel').text(label);
                $('[data-filter-type="coach"]').removeClass('active');
                $(this).addClass('active');
                if (val) {
                    $('#coachFilterDropdown').addClass('active-filter');
                } else {
                    $('#coachFilterDropdown').removeClass('active-filter');
                }
                reloadDataTable();
            });

            // Plan filter selection
            $(document).on('click', '[data-filter-type="plan"]', function(e) {
                e.preventDefault();
                var val = $(this).data('value');
                var label = $(this).text();
                $('#plan_id').val(val);
                $('#planFilterLabel').text(label);
                $('[data-filter-type="plan"]').removeClass('active');
                $(this).addClass('active');
                if (val) {
                    $('#planFilterDropdown').addClass('active-filter');
                } else {
                    $('#planFilterDropdown').removeClass('active-filter');
                }
                reloadDataTable();
            });

            // Reset all filters
            $('#fccResetAllFiltersBtn').on('click', function(e) {
                e.preventDefault();
                $('#fccSearchInput').val('');
                $('#coach_name').val('');
                $('#coachFilterLabel').text('All coaches');
                $('#coachFilterDropdown').removeClass('active-filter');
                $('[data-filter-type="coach"]').removeClass('active');
                $('[data-filter-type="coach"][data-value=""]').addClass('active');

                $('#plan_id').val('');
                $('#planFilterLabel').text('All meal plans');
                $('#planFilterDropdown').removeClass('active-filter');
                $('[data-filter-type="plan"]').removeClass('active');
                $('[data-filter-type="plan"][data-value=""]').addClass('active');

                reloadDataTable();
            });

            // Page size dropdown selection
            $(document).on('click', '[data-page-size]', function(e) {
                e.preventDefault();
                var size = parseInt($(this).data('page-size'));
                $('#pageSizeLabel').text(size + ' per page');
                $('[data-page-size]').removeClass('active');
                $(this).addClass('active');
                if ($.fn.DataTable.isDataTable('#previousMonthDataTable')) {
                    $('#previousMonthDataTable').DataTable().page.len(size).draw();
                }
            });

            // Export to Excel Button
            $('#fccExportBtn').on('click', function(e) {
                e.preventDefault();
                if (window.previous_month_table) {
                    window.previous_month_table.button('.buttons-excel').trigger();
                }
            });

            // Select all checkbox
            $('#select-all-counsellings').on('change', function() {
                var checked = $(this).is(':checked');
                $('.child-chk').prop('checked', checked);
            });

            $(document).on('change', '.child-chk', function() {
                var total = $('.child-chk').length;
                var checked = $('.child-chk:checked').length;
                $('#select-all-counsellings').prop('checked', total > 0 && total === checked);
            });
        }
    };
})();

$(document).ready(function() {
    PreviousMonthCounsellings.init();
});
