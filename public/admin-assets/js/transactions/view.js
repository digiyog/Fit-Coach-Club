var Transaction = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            Transaction.getTransactions();
            Transaction.initializeComponents();
            Transaction.dataTableCustomFilter();
            Transaction.customValidationMethods();
            Transaction.addTransaction();
            Transaction.validateAddTransactionForm();
            Transaction.updateTransaction();
            Transaction.validateUpdateTransactionForm();
            Transaction.viewRemark();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Initialize Components
            var $filter_form = $(".custom-datatable-filter-form");

            // Bootstrap Select on filter form dropdowns
            Components.bootstrapSelect($filter_form);
            //------------

            $('input[name="date_range"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="date_range"]').on(
                "apply.daterangepicker",
                function (ev, picker) {
                    $(this).val(
                        picker.startDate.format("YYYY-MM-DD") +
                        "/" +
                        picker.endDate.format("YYYY-MM-DD")
                    );
                    $(':input[type="submit"]').prop("disabled", false);
                    $(':input[name="Clear"]').prop("disabled", false);
                }
            );

            $('input[name="date_range"]').on(
                "cancel.daterangepicker",
                function (ev, picker) {
                    $(this).val("");
                }
            );
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
         * Get Transactions list.
         */
        getTransactions: function() {
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
                headerCallback: function(e, a, t, n, s) {
                    // e.getElementsByTagName("th")[0].innerHTML =
                    //         '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                ],
                buttons: {
                    buttons: [
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
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        d.name = $("#name").val();
                        d.date_range = $("#date_range").val();
                    }
                },
                columns: [
                    { data: "user_name", name: "user_name" },
                    { data: "order_number", name: "order_number" },
                    { data: "title", name: "title" },
                    { data: "total_amount", name: "total_amount" },
                    { data: "due_amount", name: "due_amount" },
                    { data: "received_amount", name: "received_amount" },
                    { data: "payment_type", name: "payment_type" },
                    { data: "remark", name: "remark" },
                    { data: "date", name: "date" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        width:50,
                        className: "text-right"
                    }
                ],
                footerCallback: function (row, data, start, end, display) {

                    let totalAmount     = 0;
                    let dueAmount       = 0;
                    let receivedAmount  = 0;

                    data.forEach(function (row) {
                        totalAmount     += parseFloat(row.total_amount) || 0;
                        dueAmount       += parseFloat(row.due_amount) || 0;
                        receivedAmount  += parseFloat(row.received_amount) || 0;
                    });

                    $('#total_amount_footer').html(totalAmount.toFixed(2));
                    $('#due_amount_footer').html(dueAmount.toFixed(2));
                    $('#received_amount_footer').html(receivedAmount.toFixed(2));
                },
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

            // Apply filter
            $(".apply-filter").on("click", function(e) {
                data_table.ajax.reload();
                e.preventDefault();
            });
            //-------------

            // Clear filter
            $(".clear-filter").on("click", function(e) {
                $(".custom-datatable-filter-form")[0].reset();
                $source = $(".custom-datatable-filter-form");
                $select = $source.find(".select-picker");
                $select.selectpicker("refresh");
                data_table.ajax.reload();
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

                    // Update state of "Select all" control
                    // Transaction.updateDataTableSelectAllCtrl(table);

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
                    } else {
                        $dataTable
                            .find('tbody input[type="checkbox"]:checked')
                            .trigger("click");
                        $(".change-status").prop("disabled", true);
                        $(".dt-delete").prop("disabled", true);
                    }

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });

            // Handle table draw event
            table.on("draw", function() {
                // Update state of "Select all" control
                // Transaction.updateDataTableSelectAllCtrl(table);

                // Additional form validation methods
                Components.additionalValidationMethods();
               //----------
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
         * Add Transaction
         */
        addTransaction: function () {
            var $source = $(".data-table-container");
            $('.create-transaction').on("click", function () {

                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {
                        Transaction.initializeComponents();
                        Transaction.validateAddTransactionForm();

                        var $filter_form = $(".add-transaction-form");

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
         * Validate Add Transaction Form
         */
        validateAddTransactionForm: function() {
            var $form = $(".add-transaction-form");

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
                    var $form = $(".add-transaction-form");

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
         * Update Transaction Form
         */
        updateTransaction: function () {
            var $source = $(".data-table-container");
            $source.on("click", ".update-transaction", function () {

                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {
                        Transaction.initializeComponents();
                        Transaction.validateUpdateTransactionForm();
                    });
                $configuration_modal.on("hidden.bs.modal", function () {
                    App.resetModal($configuration_modal);
                });
            });
        },

        /**
         * Validate Update Transaction Form
         */
        validateUpdateTransactionForm: function() {
            var $form = $(".update-transaction-form");

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
                    var $form = $(".update-transaction-form");

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

Transaction.init();