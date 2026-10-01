var Order = (function() {
    var data_table;
    var searchTimer = null;

    return {
        /**
         * Initialization.
         */
        init: function() {
            Order.getOrders();
            Order.initializeFilters();
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
         * Initialize Filter Controls.
         */
        initializeFilters: function() {
            // Date Range Picker initialization
            var $dateRange = $("#order_date_range");
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
            $("#order_search").on("keyup input change", function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                }, 350);
            });

            // Filter dropdown change
            $("#order_payment_status, #order_status_filter").on("change", function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Page length dropdown change
            $("#order_page_length").on("change", function() {
                var len = parseInt($(this).val(), 10) || 10;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Clear all filters
            $("#btn_clear_filters").on("click", function(e) {
                e.preventDefault();
                $("#order_search").val("");
                if ($dateRange.length) {
                    $dateRange.val("").data("range-value", "");
                }
                $("#order_payment_status").val("");
                $("#order_status_filter").val("");
                $("#order_page_length").val("10");

                if (data_table) {
                    data_table.page.len(10);
                    data_table.ajax.reload();
                }
            });

            // Toggle filters bar on button click
            $("#btn_toggle_header_filters, #btn_toggle_more_filters").on("click", function(e) {
                e.preventDefault();
                $(".ledger-filters-grid").slideToggle(200);
            });
        },

        /**
         * Get Orders list with modern DataTables configuration.
         */
        getOrders: function() {
            var $dataTable = $("#dataTable");

            data_table = $dataTable.DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                order: [[0, "desc"]], // Sort by Order Date DESC
                pageLength: 10,
                dom: 'rt<"dt-bottom-row"ip>',
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left"></i> Previous',
                        sNext: 'Next <i class="fa fa-angle-right"></i>'
                    },
                    sInfo: "Showing _TOTAL_ orders",
                    sEmptyTable: "No orders found",
                    sZeroRecords: "No matching orders found"
                },
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        var searchVal = $("#order_search").val();
                        d.filter = searchVal;
                        d.search_query = searchVal;
                        d.filter_date_range = $("#order_date_range").data("range-value") || "";
                        d.user_id = $("#user_id").val();
                        d.status_filter = $("#order_status_filter").val();
                        d.payment_status_filter = $("#order_payment_status").val();
                    }
                },
                columns: [
                    { data: "transaction_info", name: "created_at" },
                    { data: "user_name", name: "user_name" },
                    { data: "mobile_number", name: "mobile_number" },
                    { data: "total_amount", name: "total_amount" },
                    { data: "discount", name: "discount" },
                    { data: "net_amount", name: "net_amount" },
                    { data: "payment_status", name: "payment_status" },
                    { data: "order_status", name: "order_status" },
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

                    // Update visible orders counter
                    var visibleCount = (json && typeof json.iTotalDisplayRecords !== "undefined") ? json.iTotalDisplayRecords : info.recordsDisplay;
                    $("#visible_order_count").text(visibleCount);

                    // Update KPI cards dynamically if summary was returned
                    if (json && json.summary) {
                        var s = json.summary;
                        if (typeof s.total_orders_formatted !== "undefined") {
                            $("#kpi_total_orders").text(s.total_orders_formatted);
                        }
                        if (typeof s.net_amount_formatted !== "undefined") {
                            $("#kpi_net_amount").text(s.net_amount_formatted);
                        }
                        if (typeof s.successful_payments_formatted !== "undefined") {
                            $("#kpi_successful_payments").text(s.successful_payments_formatted);
                        }
                        if (typeof s.pending_payments_formatted !== "undefined") {
                            $("#kpi_pending_payments").text(s.pending_payments_formatted);
                        }

                        // Progress Bar Segments
                        if (typeof s.delivered_percent !== "undefined") {
                            $("#kpi_seg_delivered").css("width", s.delivered_percent + "%");
                        }
                        if (typeof s.cancelled_percent !== "undefined") {
                            $("#kpi_seg_cancelled").css("width", s.cancelled_percent + "%");
                        }
                        if (typeof s.order_placed_percent !== "undefined") {
                            $("#kpi_seg_placed").css("width", s.order_placed_percent + "%");
                        }

                        // Legend Counts
                        if (typeof s.delivered_orders !== "undefined") {
                            $("#kpi_legend_delivered").text(s.delivered_orders);
                        }
                        if (typeof s.cancelled_orders !== "undefined") {
                            $("#kpi_legend_cancelled").text(s.cancelled_orders);
                        }
                        if (typeof s.order_placed !== "undefined") {
                            $("#kpi_legend_placed").text(s.order_placed);
                        }
                    }
                }
            });
        },

        /**
         * Change Single order status.
         */
        changeStatus: function() {
            var $dataTable = $("#dataTable");

            $dataTable.on("click", ".change-status-single", function(e) {
                e.preventDefault();
                var statusUrl = $(this).attr('data-change-status-url');
                var dataStatus = $(this).data("status");
                var ids = $(this).data("id");

                if (typeof iziToast !== "undefined") {
                    iziToast.question({
                        timeout: 20000,
                        close: false,
                        overlay: true,
                        displayMode: "once",
                        color: "yellow",
                        id: "question",
                        zindex: 99999,
                        title: "Hey!",
                        message: "Are you sure you want to change order status?",
                        position: "center",
                        progressBar: false,
                        buttons: [
                            [
                                "<button><b>YES</b></button>",
                                function(instance, toast) {
                                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");

                                    $.ajax({
                                        type: "POST",
                                        url: statusUrl,
                                        data: { ids: ids, status: dataStatus },
                                        success: function(response) {
                                            if (typeof App !== "undefined" && App.showNotification) {
                                                App.showNotification(response);
                                            }
                                            if (data_table) {
                                                data_table.ajax.reload(null, false);
                                            }
                                        },
                                        error: function() {}
                                    });
                                },
                                false
                            ],
                            [
                                "<button>NO</button>",
                                function(instance, toast) {
                                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                                }
                            ]
                        ]
                    });
                } else if (confirm("Are you sure you want to change order status?")) {
                    $.ajax({
                        type: "POST",
                        url: statusUrl,
                        data: { ids: ids, status: dataStatus },
                        success: function(response) {
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }
                            if (data_table) {
                                data_table.ajax.reload(null, false);
                            }
                        }
                    });
                }
            });
        },

        /**
         * Payment Status Change.
         */
        paymentStatusChange: function() {
            var $dataTable = $("#dataTable");

            $dataTable.on("click", ".payment-status-change", function(e) {
                e.preventDefault();
                var statusUrl = $(this).attr('data-payment-status-url');
                var dataStatus = $(this).data("status");
                var ids = $(this).data("id");

                if (typeof iziToast !== "undefined") {
                    iziToast.question({
                        timeout: 20000,
                        close: false,
                        overlay: true,
                        displayMode: "once",
                        color: "yellow",
                        id: "question",
                        zindex: 99999,
                        title: "Hey!",
                        message: "Are you sure you want to change payment status?",
                        position: "center",
                        progressBar: false,
                        buttons: [
                            [
                                "<button><b>YES</b></button>",
                                function(instance, toast) {
                                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");

                                    $.ajax({
                                        type: "POST",
                                        url: statusUrl,
                                        data: { ids: ids, status: dataStatus },
                                        success: function(response) {
                                            if (typeof App !== "undefined" && App.showNotification) {
                                                App.showNotification(response);
                                            }
                                            if (data_table) {
                                                data_table.ajax.reload(null, false);
                                            }
                                        },
                                        error: function() {}
                                    });
                                },
                                false
                            ],
                            [
                                "<button>NO</button>",
                                function(instance, toast) {
                                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                                }
                            ]
                        ]
                    });
                } else if (confirm("Are you sure you want to change payment status?")) {
                    $.ajax({
                        type: "POST",
                        url: statusUrl,
                        data: { ids: ids, status: dataStatus },
                        success: function(response) {
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }
                            if (data_table) {
                                data_table.ajax.reload(null, false);
                            }
                        }
                    });
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
         * Add Order
         */
        addOrder: function () {
            $(document).on("click", ".create-order", function () {
                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {
                        if (typeof Components !== "undefined" && Components.bootstrapSelect) {
                            var $filter_form = $(".add-order-form");
                            Components.bootstrapSelect($filter_form);
                        }
                        Order.validateAddOrderForm();
                    });

                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        },

        /**
         * Validate Add Order Form
         */
        validateAddOrderForm: function() {
            var $form = $(".add-order-form");
            if (!$form.length || typeof $form.validate === "undefined") {
                return;
            }

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
                    },
                    received_amount: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                },
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "check_section") {
                        error.appendTo(".check-section-error");
                    } else if($(element).hasClass('custom-file-input')) {
                        error.appendTo($(element).parents('.input-group').parent());
                    } else if($(element).hasClass('image-preview')) {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    var $form = $(form);

                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: $form.serialize(),
                        beforeSend: function() {
                            if (typeof App !== "undefined" && App.formLoading) {
                                App.formLoading($form);
                            }
                        },
                        success: function(response) {
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }
                            if (data_table) {
                                data_table.ajax.reload(null, false);
                            }
                            var $configuration_modal = $("#pageModalMedium");
                            $configuration_modal.modal("hide");
                        },
                        error: function() {},
                        complete: function() {}
                    });
                }
            });
        },

        /**
         * Update Order Form
         */
        updateOrder: function () {
            $(document).on("click", ".update-order", function () {
                var $this = $(this);
                var $configuration_modal = $("#pageModalMedium");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {
                        Order.validateUpdateOrderForm();
                    });
                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        },

        /**
         * Validate Update Order Form
         */
        validateUpdateOrderForm: function() {
            var $form = $(".update-order-form");
            if (!$form.length || typeof $form.validate === "undefined") {
                return;
            }

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                errorClass: "invalid-feedback",
                errorElement: "span",
                rules: {
                    received_amount: {
                        required: true,
                    },
                },
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "check_section") {
                        error.appendTo(".check-section-error");
                    } else if($(element).hasClass('custom-file-input')) {
                        error.appendTo($(element).parents('.input-group').parent());
                    } else if($(element).hasClass('image-preview')) {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    var $form = $(form);

                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: $form.serialize(),
                        beforeSend: function() {
                            if (typeof App !== "undefined" && App.formLoading) {
                                App.formLoading($form);
                            }
                        },
                        success: function(response) {
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }
                            if (data_table) {
                                data_table.ajax.reload(null, false);
                            }
                            var $configuration_modal = $("#pageModalMedium");
                            $configuration_modal.modal("hide");
                        },
                        error: function() {},
                        complete: function() {}
                    });
                }
            });
        },

        /**
         * View Remark.
         */
        viewRemark: function () {
            $(document).on("click", ".view-remark", function () {
                var $this = $(this);
                var $configuration_modal = $("#pageModal");

                $configuration_modal.modal("show");
                $configuration_modal
                    .find(".modal-content")
                    .load($this.data("url"), "", function () {});

                $configuration_modal.on("hidden.bs.modal", function () {
                    if (typeof App !== "undefined" && App.resetModal) {
                        App.resetModal($configuration_modal);
                    }
                });
            });
        },
    };
})();

$(document).ready(function() {
    Order.init();
});
