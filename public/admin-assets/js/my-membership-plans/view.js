var MemberShipPlan = (function() {
    var data_table;
    var searchTimer = null;

    return {
        /**
         * Initialization.
         */
        init: function() {
            MemberShipPlan.getMemberShipPlans();
            MemberShipPlan.initializeFilters();
        },

        /**
         * Initialize Filter Controls.
         */
        initializeFilters: function() {
            // Date Range Picker initialization
            var $dateRange = $("#plan_date_range");
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
            $("#plan_search").on("keyup input change", function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    if (data_table) {
                        data_table.ajax.reload();
                    }
                }, 350);
            });

            // Filter dropdown changes
            $("#plan_id_filter, #plan_payment_status, #plan_amount_type").on("change", function () {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Page length dropdown change
            $("#plan_page_length").on("change", function () {
                var len = parseInt($(this).val(), 10) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // Clear all filters
            $("#btn_clear_filters").on("click", function (e) {
                e.preventDefault();
                $("#plan_search").val("");
                if ($dateRange.length) {
                    $dateRange.val("").data("range-value", "");
                }
                $("#plan_id_filter").val("");
                $("#plan_payment_status").val("");
                $("#plan_amount_type").val("");
                $("#plan_page_length").val("20");

                if (data_table) {
                    data_table.page.len(20);
                    data_table.ajax.reload();
                }
            });

            // Toggle filters bar on button click
            $("#btn_toggle_header_filters, #btn_toggle_more_filters").on("click", function (e) {
                e.preventDefault();
                $(".ledger-filters-grid").slideToggle(200);
            });
        },

        /**
         * Get MemberShip Plans list with modern DataTables configuration.
         */
        getMemberShipPlans: function() {
            var $dataTable = $("#dataTable");

            data_table = $dataTable.DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                order: [[3, "desc"]], // Sort by Start Date DESC by default
                pageLength: 20,
                dom: 'rt<"dt-bottom-row"ip>',
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fa fa-angle-left"></i> Previous',
                        sNext: 'Next <i class="fa fa-angle-right"></i>'
                    },
                    sInfo: "Showing records _START_ to _END_ of _TOTAL_",
                    sEmptyTable: "No membership plans found",
                    sZeroRecords: "No matching membership plans found"
                },
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        var searchVal = $("#plan_search").val();
                        d.search_query = searchVal;
                        d.membership_plan_id = $("#plan_id_filter").val();
                        d.payment_status = $("#plan_payment_status").val();
                        d.amount_type = $("#plan_amount_type").val();
                        d.filter_date_range = $("#plan_date_range").data("range-value") || "";
                    }
                },
                columns: [
                    { data: "membership_plan_name", name: "membership_plans.name" },
                    { data: "total_amount", name: "franchise_memberships.total_amount" },
                    { data: "payment_status", name: "franchise_memberships.payment_status" },
                    { data: "start_date", name: "franchise_memberships.start_date" },
                    { data: "end_date", name: "franchise_memberships.end_date" },
                    { data: "remark", name: "franchise_memberships.remark" }
                ],
                drawCallback: function(settings) {
                    var json = settings.json;
                    var info = this.api().page.info();

                    // Update visible plan count
                    var visibleCount = (json && typeof json.iTotalDisplayRecords !== "undefined") ? json.iTotalDisplayRecords : info.recordsDisplay;
                    $("#visible_plan_count").text(visibleCount);

                    // Update KPI cards dynamically if summary was returned
                    if (json && json.summary) {
                        var s = json.summary;
                        if (typeof s.total_records !== "undefined") {
                            $("#kpi_total_records").text(s.total_records);
                        }
                        if (typeof s.recorded_amount_formatted !== "undefined") {
                            $("#kpi_recorded_amount").text(s.recorded_amount_formatted);
                        }
                        if (typeof s.zero_amount_plans !== "undefined") {
                            $("#kpi_zero_amount_plans").text(s.zero_amount_plans);
                        }
                        if (typeof s.completion_text !== "undefined") {
                            $("#kpi_completion_text").text(s.completion_text);
                        }

                        // Breakdown bar and legend
                        if (s.breakdown && Array.isArray(s.breakdown)) {
                            var barHtml = "";
                            var legendHtml = "";

                            s.breakdown.forEach(function (b) {
                                barHtml += '<div class="bar-segment" style="width: ' + b.percent + '%; background-color: ' + b.color + ';" title="' + b.name + ': ' + b.count + '"></div>';
                                legendHtml += '<span class="legend-item"><span class="legend-dot" style="background-color: ' + b.color + ';"></span> ' + b.name + ' ' + b.count + '</span>';
                            });

                            $("#kpi_segmented_bar").html(barHtml);
                            $("#kpi_segmented_legend").html(legendHtml);
                        }
                    }
                }
            });
        }
    };
})();

$(document).ready(function() {
    MemberShipPlan.init();
});
