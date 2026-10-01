var Transaction = (function() {
    var data_table;
    var searchTimer = null;

    return {
        /**
         * Initialization.
         */
        init: function() {
            Transaction.getTransactions();
            Transaction.initializeFilters();
            Transaction.customValidationMethods();
            Transaction.addTransaction();
            Transaction.validateAddTransactionForm();
            Transaction.updateTransaction();
            Transaction.validateUpdateTransactionForm();
            Transaction.viewRemark();
        },

        /**
         * Initialize Filter Controls.
         */
        initializeFilters: function() {
            // Date Range Picker initialization
            var $dateRange = $("#tx_date_range");
            if ($dateRange.length && typeof $.fn.daterangepicker !== "undefined") {
                $dateRange.daterangepicker({
                    autoUpdateInput: false,
                    opens: "left",
                    locale: {
                        cancelLabel: "Clear",
                        format: "DD-MM-YYYY"
                    }
                });

                $dateRange.on("apply.daterangepicker", function(ev, picker) {
                    $(this).val(picker.startDate.format("DD-MM-YYYY") + " - " + picker.endDate.format("DD-MM-YYYY"));
                    $(this).data("range-value", picker.startDate.format("YYYY-MM-DD") + "/" + picker.endDate.format("YYYY-MM-DD"));
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                });

                $dateRange.on("cancel.daterangepicker", function(ev, picker) {
                    $(this).val("");
                    $(this).data("range-value", "");
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                });
            }

            // Real-time search with debounce
            $("#tx_search").on("keyup input change", function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                }, 350);
            });

            // Filter dropdown change
            $("#tx_payment_type, #tx_collection_state").on("change", function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Page length dropdown change
            $("#tx_page_length").on("change", function() {
                var len = parseInt($(this).val(), 10) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Clear all filters
            $("#btn_clear_filters").on("click", function(e) {
                e.preventDefault();
                $("#tx_search").val("");
                if ($dateRange.length) {
                    $dateRange.val("").data("range-value", "");
                }
                $("#tx_payment_type").val("");
                $("#tx_collection_state").val("");
                $("#tx_page_length").val("20");

                if (data_table) {
                    data_table.page.len(20);
                    data_table.ajax.reload();
                }
            });

            // More filters button toggle
            $("#btn_toggle_more_filters").on("click", function(e) {
                e.preventDefault();
                $(this).toggleClass("active");
            });
        },

        /**
         * Get Transactions list with modern DataTables configuration.
         */
        getTransactions: function() {
            var $dataTable = $("#dataTable");

            data_table = $dataTable.DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                order: [[8, "desc"]], // Sort by Date DESC by default
                pageLength: 20,
                dom: 'rt<"dt-bottom-row"ip>',
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left"></i> Previous',
                        sNext: 'Next <i class="fa fa-angle-right"></i>'
                    },
                    sInfo: "Showing _TOTAL_ transactions",
                    sEmptyTable: "No transactions found",
                    sZeroRecords: "No matching transactions found"
                },
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        var searchVal = $("#tx_search").val();
                        d.name = searchVal;
                        d.search_query = searchVal;
                        d.date_range = $("#tx_date_range").data("range-value") || "";
                        d.payment_type = $("#tx_payment_type").val();
                        d.collection_state = $("#tx_collection_state").val();
                    }
                },
                columns: [
                    { data: "user_name", name: "users.name" },
                    { data: "order_number", name: "order_number", orderable: false },
                    { data: "title", name: "transactions.title" },
                    { data: "total_amount", name: "transactions.total_amount" },
                    { data: "due_amount", name: "transactions.due_amount" },
                    { data: "received_amount", name: "transactions.received_amount" },
                    { data: "payment_type", name: "transactions.payment_type" },
                    { data: "remark", name: "remark", orderable: false },
                    { data: "date", name: "transactions.created_at" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        orderable: false,
                        className: "text-end"
                    }
                ],
                drawCallback: function(settings) {
                    var json = settings.json;
                    var info = this.api().page.info();

                    // Update visible transactions counter
                    var visibleCount = (json && typeof json.iTotalDisplayRecords !== "undefined") ? json.iTotalDisplayRecords : info.recordsDisplay;
                    $("#visible_tx_count").text(visibleCount);

                    // Update KPI cards dynamically if summary was returned
                    if (json && json.summary) {
                        var s = json.summary;
                        if (typeof s.total_amount_formatted !== "undefined") {
                            $("#kpi-total-amount").text(s.total_amount_formatted);
                        }
                        if (typeof s.received_amount_formatted !== "undefined") {
                            $("#kpi-received-amount").text(s.received_amount_formatted);
                        }
                        if (typeof s.due_amount_formatted !== "undefined") {
                            $("#kpi-due-amount").text(s.due_amount_formatted);
                        }
                        if (typeof s.collection_rate_formatted !== "undefined") {
                            $("#kpi-collection-rate").text(s.collection_rate_formatted);
                        }

                        // Update SVG Donut stroke-dasharray
                        var rate = Math.min(100, Math.max(0, s.collection_rate || 0));
                        $("#kpi-donut-stroke").attr("stroke-dasharray", rate + ", 100");

                        // Update Progress bar fill
                        $("#kpi-progress-fill").css("width", rate + "%");
                    }
                }
            });
        },

        /**
         * Custom validation methods.
         */
        customValidationMethods: function() {
            if (typeof jQuery.validator === "undefined") {
                return;
            }

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
         * Add Transaction Modal
         */
        addTransaction: function () {
            $(document).on("click", ".create-transaction", function () {
                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .html('<div class="modal-body p-4 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>')
                    .load($this.data("url"), "", function (responseText, textStatus, jqXHR) {
                        if (textStatus === "error") {
                            $(this).html('<div class="modal-header"><h5 class="modal-title text-danger">Error</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-danger">Failed to load transaction form. Please try again.</div>');
                            return;
                        }

                        if (typeof Components !== "undefined" && Components.bootstrapSelect) {
                            var $filter_form = $(".add-transaction-form");
                            Components.bootstrapSelect($filter_form);
                        }
                        Transaction.validateAddTransactionForm();
                    });

                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        },

        /**
         * Validate Add Transaction Form
         */
        validateAddTransactionForm: function() {
            var $form = $(".add-transaction-form");
            if (!$form.length || typeof $form.validate === "undefined") {
                return;
            }

            $form.find(".select-picker").on("change", function () {
                $(this).valid();
            });

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                errorClass: "invalid-feedback",
                errorElement: "span",
                rules: {
                    user: {
                        required: true,
                    },
                    amount: {
                        required: true,
                        number: true,
                        min: 0,
                    },
                    received_amount: {
                        required: true,
                        number: true,
                        min: 0,
                    },
                    type: {
                        required: true,
                    },
                },
                highlight: function(element) {
                    $(element)
                        .closest(".form-group, .col-md-12")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element)
                        .closest(".form-group, .col-md-12")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    if ($(element).hasClass('select-picker')) {
                        error.insertAfter($(element).parent());
                    } else if($(element).hasClass('custom-file-input')) {
                        error.appendTo($(element).parents('.input-group').parent());
                    } else if($(element).hasClass('image-preview')) {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    var $form = $(form);
                    var $submitBtn = $form.find('.btn-submit');
                    var originalBtnHtml = $submitBtn.html();

                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: $form.serialize(),
                        beforeSend: function() {
                            if (typeof App !== "undefined" && App.formLoading) {
                                App.formLoading($form);
                            }
                            $submitBtn.prop('disabled', true);
                        },
                        success: function(response) {
                            if (typeof App !== "undefined" && App.formLoaded) {
                                App.formLoaded($form);
                            }
                            $submitBtn.prop('disabled', false).html(originalBtnHtml);
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }

                            if (response && (response._status === true || response.status === true)) {
                                if (typeof data_table !== "undefined" && data_table) {
                                    data_table.ajax.reload(null, false);
                                }
                                var $configuration_modal = $("#pageModalMedium");
                                $configuration_modal.modal("hide");
                            }
                        },
                        error: function(xhr) {
                            if (typeof App !== "undefined" && App.formLoaded) {
                                App.formLoaded($form);
                            }
                            $submitBtn.prop('disabled', false).html(originalBtnHtml);
                            var msg = "An error occurred while saving transaction.";
                            if (xhr.responseJSON && xhr.responseJSON._message) {
                                msg = xhr.responseJSON._message;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification({
                                    _status: false,
                                    _type: 'error',
                                    _message: msg
                                });
                            }
                        },
                        complete: function() {
                            if (typeof App !== "undefined" && App.formLoaded) {
                                App.formLoaded($form);
                            }
                            $submitBtn.prop('disabled', false);
                        }
                    });
                }
            });
        },

        /**
         * Update Transaction Form Modal
         */
        updateTransaction: function () {
            $(document).on("click", ".update-transaction", function () {
                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .html('<div class="modal-body p-4 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>')
                    .load($this.data("url"), "", function (responseText, textStatus, jqXHR) {
                        if (textStatus === "error") {
                            $(this).html('<div class="modal-header"><h5 class="modal-title text-danger">Error</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-danger">Failed to load transaction details. Please try again.</div>');
                            return;
                        }

                        Transaction.validateUpdateTransactionForm();
                    });

                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        },

        /**
         * Validate Update Transaction Form
         */
        validateUpdateTransactionForm: function() {
            var $form = $(".update-transaction-form");
            if (!$form.length || typeof $form.validate === "undefined") {
                return;
            }

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                errorClass: "invalid-feedback",
                errorElement: "span",
                rules: {
                    amount: {
                        required: true,
                        number: true,
                        min: 0,
                    },
                    received_amount: {
                        required: true,
                        number: true,
                        min: 0,
                    },
                },
                highlight: function(element) {
                    $(element)
                        .closest(".form-group, .col-md-12")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element)
                        .closest(".form-group, .col-md-12")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    if ($(element).hasClass('select-picker')) {
                        error.insertAfter($(element).parent());
                    } else if($(element).hasClass('custom-file-input')) {
                        error.appendTo($(element).parents('.input-group').parent());
                    } else if($(element).hasClass('image-preview')) {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    var $form = $(form);
                    var $submitBtn = $form.find('.btn-submit');
                    var originalBtnHtml = $submitBtn.html();

                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: $form.serialize(),
                        beforeSend: function() {
                            if (typeof App !== "undefined" && App.formLoading) {
                                App.formLoading($form);
                            }
                            $submitBtn.prop('disabled', true);
                        },
                        success: function(response) {
                            if (typeof App !== "undefined" && App.formLoaded) {
                                App.formLoaded($form);
                            }
                            $submitBtn.prop('disabled', false).html(originalBtnHtml);
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }

                            if (response && (response._status === true || response.status === true)) {
                                if (typeof data_table !== "undefined" && data_table) {
                                    data_table.ajax.reload(null, false);
                                }
                                var $configuration_modal = $("#pageModalMedium");
                                $configuration_modal.modal("hide");
                            }
                        },
                        error: function(xhr) {
                            if (typeof App !== "undefined" && App.formLoaded) {
                                App.formLoaded($form);
                            }
                            $submitBtn.prop('disabled', false).html(originalBtnHtml);
                            var msg = "An error occurred while updating transaction.";
                            if (xhr.responseJSON && xhr.responseJSON._message) {
                                msg = xhr.responseJSON._message;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification({
                                    _status: false,
                                    _type: 'error',
                                    _message: msg
                                });
                            }
                        },
                        complete: function() {
                            if (typeof App !== "undefined" && App.formLoaded) {
                                App.formLoaded($form);
                            }
                            $submitBtn.prop('disabled', false);
                        }
                    });
                }
            });
        },

        /**
         * View Remark Modal
         */
        viewRemark: function () {
            $(document).on("click", ".view-remark", function () {
                var $this = $(this);
                var $configuration_modal = $("#pageModal");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .html('<div class="modal-body p-4 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>')
                    .load($this.data("url"), "", function (responseText, textStatus, jqXHR) {
                        if (textStatus === "error") {
                            $(this).html('<div class="modal-header"><h5 class="modal-title text-danger">Error</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-danger">Failed to load remark. Please try again.</div>');
                        }
                    });

                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    Transaction.init();
});