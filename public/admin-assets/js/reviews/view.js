var Review = (function () {
    var data_table;
    var searchTimer = null;

    return {
        /**
         * Initialization.
         */
        init: function () {
            Review.getReviews();
            Review.initializeFilters();
            Review.destroyRecord();
            Review.customValidationMethods();
        },

        /**
         * Initialize Filter Controls.
         */
        initializeFilters: function () {
            // Date Range Picker initialization
            var $dateRange = $("#review_date_range");
            if ($dateRange.length && typeof $.fn.daterangepicker !== "undefined") {
                $dateRange.daterangepicker({
                    autoUpdateInput: false,
                    opens: "left",
                    locale: {
                        cancelLabel: "Clear",
                        format: "DD-MM-YYYY"
                    }
                });

                $dateRange.on("apply.daterangepicker", function (ev, picker) {
                    $(this).val(picker.startDate.format("DD-MM-YYYY") + " - " + picker.endDate.format("DD-MM-YYYY"));
                    $(this).data("range-value", picker.startDate.format("YYYY-MM-DD") + "/" + picker.endDate.format("YYYY-MM-DD"));
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                });

                $dateRange.on("cancel.daterangepicker", function (ev, picker) {
                    $(this).val("");
                    $(this).data("range-value", "");
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                });
            }

            // Real-time search with debounce
            $("#review_search").on("keyup input change", function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                }, 350);
            });

            // Filter dropdown change
            $("#review_rating_filter, #review_message_filter").on("change", function () {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Page length dropdown change
            $("#review_page_length").on("change", function () {
                var len = parseInt($(this).val(), 10) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Toggle filters bar on button click
            $("#btn_toggle_header_filters, #btn_toggle_more_filters").on("click", function (e) {
                e.preventDefault();
                $(".ledger-filters-grid").slideToggle(200);
            });

            // Handle Select All Checkbox
            $(document).on("change", "#select_all_reviews", function () {
                var isChecked = $(this).is(":checked");
                $("#dataTable tbody .review-chk-native").prop("checked", isChecked);
                if (isChecked) {
                    $("#dataTable tbody tr").addClass("selected");
                } else {
                    $("#dataTable tbody tr").removeClass("selected");
                }
                Review.updateDeleteButton();
            });

            // Handle Row Checkbox Click
            $(document).on("change", "#dataTable tbody .review-chk-native", function () {
                var $row = $(this).closest("tr");
                if ($(this).is(":checked")) {
                    $row.addClass("selected");
                } else {
                    $row.removeClass("selected");
                }
                Review.syncSelectAllHeader();
                Review.updateDeleteButton();
            });
        },

        /**
         * Update state of Delete Selected button based on selection
         */
        updateDeleteButton: function () {
            var checkedCount = $("#dataTable tbody .review-chk-native:checked").length;
            var $btn = $("#btn_delete_selected");
            if (checkedCount > 0) {
                $btn.prop("disabled", false);
                $btn.html('<i class="fa fa-trash-o"></i> Delete selected (' + checkedCount + ')');
            } else {
                $btn.prop("disabled", true);
                $btn.html('<i class="fa fa-trash-o"></i> Delete selected');
            }
        },

        /**
         * Sync header Select All checkbox state
         */
        syncSelectAllHeader: function () {
            var totalCheckboxes = $("#dataTable tbody .review-chk-native").length;
            var checkedCount = $("#dataTable tbody .review-chk-native:checked").length;
            var $selectAll = $("#select_all_reviews");

            if (totalCheckboxes > 0 && checkedCount === totalCheckboxes) {
                $selectAll.prop("checked", true).prop("indeterminate", false);
            } else if (checkedCount > 0) {
                $selectAll.prop("checked", false).prop("indeterminate", true);
            } else {
                $selectAll.prop("checked", false).prop("indeterminate", false);
            }
        },

        /**
         * Reset checkboxes when table redraws
         */
        syncCheckboxState: function () {
            $("#select_all_reviews").prop("checked", false).prop("indeterminate", false);
            Review.updateDeleteButton();
        },

        /**
         * Get Reviews list with modern DataTables configuration.
         */
        getReviews: function () {
            var $dataTable = $("#dataTable");

            data_table = $dataTable.DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                order: [[4, "desc"]], // Sort by Created At DESC by default
                pageLength: 20,
                dom: 'rt<"dt-bottom-row"ip>',
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left"></i> Previous',
                        sNext: 'Next <i class="fa fa-angle-right"></i>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sEmptyTable: "No reviews found",
                    sZeroRecords: "No matching reviews found"
                },
                ajax: {
                    url: $dataTable.data("url"),
                    data: function (d) {
                        var searchVal = $("#review_search").val();
                        d.search_query = searchVal;
                        d.rating_filter = $("#review_rating_filter").val();
                        d.message_filter = $("#review_message_filter").val();
                        d.filter_date_range = $("#review_date_range").data("range-value") || "";
                        d.user_id = $("#user_id").val();
                    }
                },
                columns: [
                    {
                        data: "checkbox",
                        name: "checkbox",
                        orderable: false,
                        searchable: false,
                        className: "th-chk text-center"
                    },
                    { data: "name", name: "users.name" },
                    { data: "rating", name: "ratings.rating" },
                    { data: "message", name: "ratings.message" },
                    { data: "created_at", name: "ratings.created_at" }
                ],
                drawCallback: function (settings) {
                    var json = settings.json;
                    var info = this.api().page.info();

                    // Update visible reviews counter
                    var visibleCount = (json && typeof json.iTotalDisplayRecords !== "undefined") ? json.iTotalDisplayRecords : info.recordsDisplay;
                    $("#visible_review_count").text(visibleCount);

                    // Update KPI cards dynamically if summary was returned
                    if (json && json.summary) {
                        var s = json.summary;
                        if (typeof s.total_reviews !== "undefined") {
                            $("#kpi_total_reviews").text(s.total_reviews);
                        }
                        if (typeof s.average_rating !== "undefined") {
                            $("#kpi_average_rating").text(s.average_rating);
                        }
                        if (typeof s.five_star_reviews !== "undefined") {
                            $("#kpi_five_star_reviews").text(s.five_star_reviews);
                            $("#kpi_breakdown_5_count").text(s.five_star_reviews);
                        }
                        if (typeof s.four_star_reviews !== "undefined") {
                            $("#kpi_breakdown_4_count").text(s.four_star_reviews);
                        }
                        if (typeof s.unrated_reviews !== "undefined") {
                            $("#kpi_breakdown_unrated_count").text(s.unrated_reviews);
                        }
                        if (typeof s.written_messages !== "undefined") {
                            $("#kpi_written_messages").text(s.written_messages);
                        }

                        // Breakdown bar widths
                        if (typeof s.five_star_percent !== "undefined") {
                            $("#kpi_breakdown_5_bar").css("width", s.five_star_percent + "%");
                        }
                        if (typeof s.four_star_percent !== "undefined") {
                            $("#kpi_breakdown_4_bar").css("width", s.four_star_percent + "%");
                        }
                        if (typeof s.unrated_percent !== "undefined") {
                            $("#kpi_breakdown_unrated_bar").css("width", s.unrated_percent + "%");
                        }
                    }

                    // Reset selection state on page change/draw
                    Review.syncCheckboxState();
                }
            });
        },

        /**
         * Destroy selected records.
         */
        destroyRecord: function () {
            var $dataTable = $("#dataTable");

            $("#btn_delete_selected").on("click", function (e) {
                e.preventDefault();

                var ids = [];
                $("#dataTable tbody .review-chk-native:checked").each(function () {
                    ids.push($(this).val());
                });

                if (ids.length === 0) {
                    return;
                }

                var count = ids.length;
                var confirmMsg = "Are you sure you want to delete the selected " + (count > 1 ? count + " reviews" : "review") + "?";

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
                        message: confirmMsg,
                        position: "center",
                        progressBar: false,
                        buttons: [
                            [
                                "<button><b>YES</b></button>",
                                function (instance, toast) {
                                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");

                                    $.ajax({
                                        type: "DELETE",
                                        url: $dataTable.data("destroy-url"),
                                        data: { ids: ids },
                                        beforeSend: function () {
                                            $("#btn_delete_selected").prop("disabled", true);
                                        },
                                        success: function (response) {
                                            if (typeof App !== "undefined" && App.showNotification) {
                                                App.showNotification(response);
                                            }
                                            if (data_table) {
                                                data_table.ajax.reload(null, false);
                                            }
                                        },
                                        error: function () {
                                            $("#btn_delete_selected").prop("disabled", false);
                                        }
                                    });
                                },
                                true
                            ],
                            [
                                "<button>NO</button>",
                                function (instance, toast) {
                                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                                }
                            ]
                        ]
                    });
                } else if (confirm(confirmMsg)) {
                    $.ajax({
                        type: "DELETE",
                        url: $dataTable.data("destroy-url"),
                        data: { ids: ids },
                        beforeSend: function () {
                            $("#btn_delete_selected").prop("disabled", true);
                        },
                        success: function (response) {
                            if (typeof App !== "undefined" && App.showNotification) {
                                App.showNotification(response);
                            }
                            if (data_table) {
                                data_table.ajax.reload(null, false);
                            }
                        },
                        error: function () {
                            $("#btn_delete_selected").prop("disabled", false);
                        }
                    });
                }
            });
        },

        /**
         * Custom validation methods if needed.
         */
        customValidationMethods: function () {
            if (typeof jQuery.validator === "undefined") {
                return;
            }
        }
    };
})();

$(document).ready(function () {
    Review.init();
});
