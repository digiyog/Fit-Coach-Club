var Order = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Order.getOrders();
            Order.initializeComponents();
            Order.dataTableCustomFilter();
            Order.changeStatus();
            Order.paymentStatusChange();
            Order.customValidationMethods();
            Order.addOrder();
            Order.validateAddOrderForm();
            Order.updateOrder();
            Order.validateUpdateOrderForm();
            Order.viewRemark();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $filter_form = $(".custom-datatable-filter-form");

            // Bootstrap Select on filter form dropdowns
            Components.bootstrapSelect($filter_form);
            //------------

            // Enable Button on change filter form elements
            Components.enableButton($filter_form);
            //------------


            // Date Range picker
            var $date_range_picker = $filter_form.find(".filter-date-range-picker");

            if ($date_range_picker.length) {
                $date_range_picker.flatpickr({
                    dateFormat: "d-m-Y",
                    mode: "range",
                    maxDate: "today",
                });
            }
            //----------------
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
         * Updates "Select all" control in a data table
         */
        updateDataTableSelectAllCtrl: function(table) {
            if(viewPermission == 1 && deletePermission == 1){
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
            }
        },

        /**
         * Get Orders list.
         */
        getOrders: function() {
            var $dataTable = $("#dataTable");

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

                    $(".all-page-button").addClass(
                        "btn btn-icon btn-rounded btn-primary btn-outline"
                    );
                    $(".all-page-button").attr("title", "Export All");
                    $(".all-page-button").html(
                        '<i title="Export Excel" class="fa fa-file-text"/> &nbsp; Export All'
                    );

                    // $('.btn-toolbar').append(
                    //     '<a href="'+$dataTable.data('export-csv-url')+'" title="Export CSV" class="btn btn-icon btn-rounded btn-primary btn-outline export-csv-all"> <i class="fa fa-file-text" aria-hidden="true" download></i> &nbsp; Export All </button> '
                    // );
                },
                buttons: {
                    buttons: [
                        // {
                        //     extend: "excel",
                        //     className: "all-page-button",
                        //     exportOptions: {
                        //         modifier: {
                        //             page: "all",
                        //             search: "none"
                        //         },
                        //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        //     }
                        // }
                    ]
                },
                searching: false,
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
                'aaSorting': [],
                pageLength: 10,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.filter = $("#filter").val();
                        d.filter_date_range = $("#filter_date_range").val();
                        d.user_id = $("#user_id").val();
                        d.status_filter = $("select[name=status_filter]").val();
                        d.payment_status_filter = $("select[name=payment_status_filter]").val();
                    }
                },
                columns: [
                    { data: "transaction_info", name: "transaction_info", searchable: false, orderable: false },
                    { data: "user_name", name: "user_name", searchable: false, orderable: false },
                    { data: "mobile_number", name: "mobile_number", searchable: false, orderable: false },
                    { data: "total_amount", name: "total_amount", searchable: false, orderable: false },
                    { data: "discount", name: "discount", searchable: false, orderable: false },
                    { data: "net_amount", name: "net_amount", searchable: false, orderable: false },
                    { data: "payment_status", name: "payment_status", searchable: false, orderable: false },
                    { data: "order_status", searchable: false, orderable: false },
                    { data: "action", name: "action", searchable: false, orderable: false, align: 'right' }
                ],

                footerCallback: function (row, data, start, end, display) {

                    let totalAmount = 0;
                    let discount    = 0;
                    let netAmount   = 0;

                    data.forEach(function (row) {
                        // ❌ Cancelled order skip
                        if (row.order_status === '<label class="badge badge-danger">Cancelled</label>') {
                            return;
                        }

                        totalAmount += parseFloat(row.total_amount) || 0;
                        discount    += parseFloat(row.discount) || 0;
                        netAmount   += parseFloat(row.net_amount) || 0;
                    });

                    $('#total_amount_footer').html(totalAmount.toFixed(2));
                    $('#discount_footer').html(discount.toFixed(2));
                    $('#net_amount_footer').html(netAmount.toFixed(2));
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
                e.preventDefault();
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

        },

        /**
         * Change Single status.
         */
        changeStatus: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $dataTable.on("click", ".change-status-single", function(element) {
                
                var statusUrl = $(this).attr('data-change-status-url');
                var dataStatus = $(this).data("status");
                var ids = $(this).data("id");

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to change status?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );

                                $.ajax({
                                    type: "POST",
                                    url: statusUrl,
                                    data: { ids: ids, status: dataStatus},
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                });
                            },
                            false
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
         * Payment Status Change.
         */
        paymentStatusChange: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $dataTable.on("click", ".payment-status-change", function(element) {
                 
                var statusUrl = $(this).attr('data-payment-status-url');
                var dataStatus = $(this).data("status");
                var ids = $(this).data("id");

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to change payment status?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );

                                $.ajax({
                                    type: "POST",
                                    url: statusUrl,
                                    data: { ids: ids, status: dataStatus},
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                });
                            },
                            false
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
         * Custom validation methods.
         */
        customValidationMethods: function() {
            jQuery.validator.addMethod(
                "lettersOnly",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^[a-zA-Z&][a-zA-Z& ]+$/i.test(value)
                    );
                },
                "Please enter only alphabets."
            );

            jQuery.validator.addMethod(
                "numericOnly",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^[0-9]\d{0,1}(\.\d{1,2})?%?$/i.test(value)
                    );
                },
                "Please enter valid order number."
            );

            jQuery.validator.addMethod(
                "numeric",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^[0-9]\d{0,9}(\.\d{1,9})?%?$/i.test(value)
                    );
                },
                "Please enter only numeric value."
            );

            jQuery.validator.addMethod(
                "uppercaseOnly",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^[A-Z]+$/g.test(value)
                    );
                },
                "Please enter only capital letters."
            );

            jQuery.validator.addMethod(
                "password",
                function(value, element) {
                    return (
                        $('#new_pass').val() === $('#confirm_pass').val()
                    );
                },
                "New password and confirm password must be same"
            );
        },

        /**
         * Add Order
         */
        addOrder: function () {
            var $source = $(".data-table-container");
            $('.create-order').on("click", function () {

                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {
                        Order.initializeComponents();
                        Order.validateAddOrderForm();

                        var $filter_form = $(".add-order-form");

                        // Bootstrap Select on filter form dropdowns
                        Components.bootstrapSelect($filter_form);
                        //------------
                    });

                $configuration_modal.on("hidden.bs.modal", function () {
                    App.resetModal($configuration_modal);
                });
            });
        },

        /**
         * Validate Add Order Form
         */
        validateAddOrderForm: function() {
            var $form = $(".add-order-form");

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    user: {
                        required: true,
                    },
                    amount: {
                        required: true,
                    },
                    received_amount: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                },
                //------------------
                //------------------
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "check_section") {
                        error.appendTo(".check-section-error");
                    } else {
                        error.insertAfter(element);
                    }
                },
                //------------------
                // @validation error messages
                messages: {
                    
                },
                //---------------------------

                highlight: function(element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    if($(element).hasClass('custom-file-input'))
                    {
                        error.appendTo($(element).parents('.input-group').parent());
                    }
                    else if($(element).hasClass('image-preview'))
                    {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    var $dataTable = $("#dataTable");
                    var $form = $(".add-order-form");

                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: $form.serialize(),
                        beforeSend: function() {
                            App.formLoading($form);
                        },
                        success: function(response) {
                            App.showNotification(response);
                            data_table.ajax.reload(null, false);
                            rows_selected = [];

                            var $configuration_modal = $("#pageModalMedium");
                            $configuration_modal.modal("hide");
                        },
                        error: function() {},
                        complete: function() {
                        }
                    });
                }
            });
        },

        /**
         * Update Order Form
         */
        updateOrder: function () {
            var $source = $(".data-table-container");
            $source.on("click", ".update-order", function () {

                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {
                        Order.initializeComponents();
                        Order.validateUpdateOrderForm();
                    });
                $configuration_modal.on("hidden.bs.modal", function () {
                    App.resetModal($configuration_modal);
                });
            });
        },

        /**
         * Validate Update Order Form
         */
        validateUpdateOrderForm: function() {
            var $form = $(".update-order-form");

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    received_amount: {
                        required: true,
                    },
                },
                //------------------
                //------------------
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "check_section") {
                        error.appendTo(".check-section-error");
                    } else {
                        error.insertAfter(element);
                    }
                },
                //------------------
                // @validation error messages
                messages: {},
                //---------------------------

                highlight: function(element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    if($(element).hasClass('custom-file-input'))
                    {
                        error.appendTo($(element).parents('.input-group').parent());
                    }
                    else if($(element).hasClass('image-preview'))
                    {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    var $dataTable = $("#dataTable");
                    var $form = $(".update-order-form");

                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: $form.serialize(),
                        beforeSend: function() {
                            App.formLoading($form);
                        },
                        success: function(response) {
                            App.showNotification(response);
                            data_table.ajax.reload(null, false);
                            rows_selected = [];

                            var $configuration_modal = $("#pageModalMedium");
                            $configuration_modal.modal("hide");
                        },
                        error: function() {},
                        complete: function() {
                        }
                    });
                }
            });
        },

        /**
         * View Remark.
         */
        viewRemark: function () {
            var $source = $(".data-table-container");
            $source.on("click", ".view-remark", function () {

                var $this = $(this);
                var $configuration_modal = $("#pageModal");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {


                    });
                $configuration_modal.on("hidden.bs.modal", function () {
                    App.resetModal($configuration_modal);
                });
            });
        },

    };
})();

Order.init();
