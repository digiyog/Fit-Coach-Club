var Users = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Users.getNewMembers();
            Users.getLatestTransactions();
            Users.getTopEarners();
            Users.getStates();
            // Users.clearFilter();
            Users.getIncomeByPostion();
            Users.dataTableCustomFilter();
            Users.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Filter form
            var $filter_form = $(".dashboard-filter-form");

            // Bootstrap Select on filter form dropdowns
            Components.bootstrapSelect($filter_form);
            //------------

            // Enable Button on change filter form elements
            Components.enableButton($filter_form);
            //------------
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
         * Get Users list.
         */
        getNewMembers: function() {
            var $dataTable = $("#new-members");

            data_table = table = $dataTable.DataTable({
                // Enable / Disable features 
                paging: false,
                info: false,
                searching: false,
                ordering: false,
                //----------

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
                headerCallback: function(e, a, t, n, s) {},
                columnDefs: [],
                buttons: {
                    buttons: []
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
                    [10, 20, 50, 75, 100],
                    [10, 20, 50, 75, 100]
                ],
                pageLength: 10,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.filter_country = $("select[name=country_filter]").val();
                        d.filter_state = $("select[name='state_filter[]']").val();
                        d.filter_start_date = $("#start_date").val();
                        d.filter_end_date = $("#end_date").val();
                        d.filter_date_type = $("#date_filter_type").val();
                    }
                },
                columns: [
                    { data: "profile_image", name: "profile_image", sortable: false },
                    { data: "name", name: "name" },
                    { data: "contact_info", name: "contact_info" },
                    { data: "referral_code", name: "referral_code" }
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
                },

                drawCallback: function(settings){
                    $source = $(".custom-datatable-filter-form");
                    if(settings.iDraw > 1)
                    {
                        App.stopFilterFormLoading($source);
                    }
                },
            });
        },

        /**
         * Get Latest Transactions list.
         */
        getLatestTransactions: function() {
            var $dataTable = $("#latest-transactions");

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

                    $('.btn-toolbar').append(
                        '<a href="'+$dataTable.data('export-csv-url')+'" title="Export CSV" class="btn btn-icon btn-rounded btn-primary btn-outline export-csv-all"> <i class="fa fa-file-text" aria-hidden="true" download></i> &nbsp; Export All </button> '
                    );
                },
                headerCallback: function(e, a, t, n, s) {
                    // e.getElementsByTagName("th")[0].innerHTML =
                    //     '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                    {
                        // targets: 0,
                        // width: "30px",
                        // className: "",
                        // orderable: !1,
                        // render: function(e, a, t, n) {
                        //     return '<label class="new-control new-checkbox checkbox-outline-primary  m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        // }
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
                        //         columns: [1, 2, 3, 4, 5, 6, 7]
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
                        //         columns: [0, 1, 2, 3, 4, 5, 6, 7]
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
                    [10, 20, 50, 75, 100],
                    [10, 20, 50, 75, 100]
                ],
                "aaSorting":[],
                pageLength: 10,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.user_filter = $("select[name=user_filter]").val();
                        d.product_filter = $("select[name=product_filter]").val();
                        d.postcode_filter = $("#postcode_filter").val();
                        d.filter_role = $("#role_filter").val();
                        d.filter_amount_type = $("#amount_type_filter").val();
                        d.filter_transaction_type = $("#transaction_type_filter").val();
                        d.filter_transaction_status = $("#transaction_status_filter").val();
                        d.filter_date_range = $("#filter_date_range").val();
                    }
                },
                columns: [
                    { data: "transaction_info", name: "transaction_info" },
                    { data: "transaction_user_info", name: "transaction_user_info" },
                    { data: "amount_type", name: "amount_type" },
                    { data: "transaction_type", name: "transaction_type" },
                    { data: "amount", name: "amount" },
                    { data: "transaction_payment_gateway", name: "transaction_payment_gateway" },
                    { data: "status", name: "status" },
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
                },

                drawCallback: function(settings){
                    $source = $(".custom-datatable-filter-form");
                    if(settings.iDraw > 1)
                    {
                        App.stopFilterFormLoading($source);
                    }
                },
            });

            // Apply filter
            $(".apply-filter").on("click", function(e) {
                $source = $(".custom-datatable-filter-form");
                data_table.ajax.reload();
                App.filterFormLoading($source);
                // e.preventDefault();
            });
            //-------------

            // Clear filter
            $(".clear-filter").on("click", function(e) {
                $(".custom-datatable-filter-form")[0].reset();
                $source = $(".custom-datatable-filter-form");
                $select = $source.find(".select-picker");
                $select.selectpicker("refresh");

                // Making draw count to 0 to make filters at init state.
                data_table.settings()[0].iDraw = 0;
                data_table.ajax.reload();

                // Disable button click on clear filters
                Components.enableButton($source);
                
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
                        $(".change-job-status").prop("disabled", false);
                        $(".archive-status").prop("disabled", false);
                        $(".dt-delete").prop("disabled", false);
                    } else {
                        $(".change-status").prop("disabled", true);
                        $(".change-job-status").prop("disabled", true);
                        $(".archive-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    }

                    if (this.checked) {
                        $row.addClass("selected");
                    } else {
                        $row.removeClass("selected");
                    }

                    // Update state of "Select all" control
                    // Users.updateDataTableSelectAllCtrl(table);

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
                        $(".change-job-status").prop("disabled", false);
                    } else {
                        $dataTable
                            .find('tbody input[type="checkbox"]:checked')
                            .trigger("click");
                        $(".change-status").prop("disabled", true);
                        $(".change-job-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    }

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });

            // Handle table draw event
            table.on("draw", function() {
                // Update state of "Select all" control
                // Users.updateDataTableSelectAllCtrl(table);
            });
        },

        /**
         * Get Top Earners list.
         */
        getTopEarners: function() {
            var $dataTable = $("#top-bidders");

            data_table = table = $dataTable.DataTable({
                // Enable / Disable features 
                paging: false,
                info: false,
                searching: false,
                ordering: false,
                //----------

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
                headerCallback: function(e, a, t, n, s) {},
                columnDefs: [],
                buttons: {
                    buttons: []
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
                    [10, 20, 50, 75, 100],
                    [10, 20, 50, 75, 100]
                ],
                pageLength: 10,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.filter_country = $("select[name=country_filter]").val();
                        d.filter_state = $("select[name=state_filter]").val();
                        d.filter_start_date = $("#start_date").val();
                        d.filter_end_date = $("#end_date").val();
                        d.filter_date_type = $("#date_filter_type").val();
                    }
                },
                columns: [
                    { data: "user_name", name: "user_name" },
                    { data: "email", name: "email" },
                    { data: "mobile_number", name: "mobile_number" },
                    { data: "wallet_amount", name: "wallet_amount" }
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
                },

                drawCallback: function(settings){
                    $source = $(".dashboard-filter-form");
                    if(settings.iDraw > 1)
                    {
                        App.stopFilterFormLoading($source);
                    }
                },
            });
        },

        /**
         * Get States
         */
        getStates: function() {
            var addForm = $(".dashboard-filter-form");
            // Handle form submission event
            addForm.on("change", "#country_filter", function() {

                var countryId = $(this).val();
                var options = '';

                $.ajax({
                    type: "POST",
                    url: addForm.find('#country_filter').data("get-states-url"),
                    data: { country_id: countryId },
                    beforeSend: function(){
                        $('#state_filter').parent().append('<div class="input-loading"> <i class="fa fa-spinner fa-spin"></i> </div>');
                    },
                    success: function(response) {
                        // Preparing Dropdown
                        if(response._data)
                        {
                            // Main Data
                            $.each(response._data, function(index,data){
                                var stateId = data.id;
                                var stateName = data.name;
                                options += '<option value="'+ stateId +'">'+ stateName +'</option>';
                            });
                        }

                        $('#state_filter').html(options);
                        $('#state_filter').selectpicker('refresh');
                    },
                    complete: function () {
                        $('#state_filter').parent().find(".input-loading").remove();
                    }
                });
            });
        },

        clearFilter: function(){
            var dashboardFilerForm = $(".dashboard-filter-form");

            // Clear filter
            dashboardFilerForm.find(".clear-filter").on("click", function(e) {
                $(".dashboard-filter-form")[0].reset();
                $source = $(".dashboard-filter-form");
                $select = $source.find(".select-picker");
                $select.selectpicker("refresh");

                // Disable button click on clear filters
                // Components.enableButton($source);
                
                e.preventDefault();
            });
            //-------------
        },

        /**
         * Get Income By Position
         */
        getIncomeByPostion: function() {
            var $dataTable = $("#income-by-position");

            data_table = table = $dataTable.DataTable({
                // Enable / Disable features 
                paging: false,
                info: false,
                searching: false,
                ordering: false,
                //----------

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
                headerCallback: function(e, a, t, n, s) {},
                columnDefs: [],
                buttons: {
                    buttons: []
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
                    [10, 20, 50, 75, 100],
                    [10, 20, 50, 75, 100]
                ],
                pageLength: 10,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.filter_country = $("select[name=country_filter]").val();
                        d.filter_state = $("select[name=state_filter]").val();
                        d.filter_start_date = $("#start_date").val();
                        d.filter_end_date = $("#end_date").val();
                        d.filter_date_type = $("#date_filter_type").val();
                    }
                },
                columns: [
                    { data: "position_name", name: "position_name" },
                    { data: "persons_count", name: "persons_count" },
                    { data: "total_income", name: "total_income" },
                    { data: "total_company_income", name: "total_company_income" },
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
                },

                drawCallback: function(settings){
                    $source = $(".custom-datatable-filter-form");
                    if(settings.iDraw > 1)
                    {
                        App.stopFilterFormLoading($source);
                    }
                },
            });
        },

    };
})();

Users.init();
