var Counsellings = (function() {
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Counsellings.getCounsellings();
            Counsellings.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $filter_form = $(".custom-datatable-filter-form");
            if ($filter_form.length && typeof Components !== "undefined") {
                Components.bootstrapSelect($filter_form);
                Components.datePicker($filter_form);
            }
        },

        /**
         * Get Counsellings list.
         */
        getCounsellings: function() {
            var $dataTable = $("#dataTable");

            window.data_table = data_table = $dataTable.DataTable({
                order: [],
                columnDefs: [
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
                        var totalRecords = settings.json.recordsFiltered !== undefined ? settings.json.recordsFiltered : (settings.json.iTotalDisplayRecords !== undefined ? settings.json.iTotalDisplayRecords : (settings.json.iTotalRecords || 0));
                        
                        var activeTab = $('#active_tab').val() || 'completed';
                        var tabText = 'completed sessions';
                        if (activeTab === 'today') {
                            tabText = "today's sessions";
                        } else if (activeTab === 'pending') {
                            tabText = "pending follow-ups";
                        } else {
                            tabText = "completed sessions";
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
                    sInfo: "Showing _START_–_END_ of _TOTAL_ completed sessions",
                    sInfoEmpty: "Showing 0 to 0 of 0 sessions",
                    sInfoFiltered: "(filtered from _MAX_ total entries)",
                    sEmptyTable: "No counselling sessions found matching the criteria",
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
                        d.date = $("#date").val();
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
                    { data: "att", name: "att", width: "50px" },
                    { data: "coach_name", name: "coach_name" },
                    { data: "plan", name: "plan" },
                    { data: "days", name: "days", width: "65px" },
                    { data: "progress", name: "progress" },
                    { data: "dues", name: "dues", width: "75px" },
                    { data: "meal", name: "meal", width: "90px" },
                    { data: "completed_at", name: "completed_at" },
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
        }
    };
})();

$(document).ready(function() {
    Counsellings.init();
});
