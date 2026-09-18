var AttendenceRegister = (function() {
    var data_table;
    var searchTimer;

    return {
        /**
         * Initialization.
         */
        init: function() {
            AttendenceRegister.initDataTable();
            AttendenceRegister.initFilters();
            AttendenceRegister.initActions();
            AttendenceRegister.viewAttendence();
        },

        /**
         * Get selected month and year from dropdown.
         */
        getSelectedDate: function() {
            var val = $("#filterMonthYear").val() || "";
            var parts = val.split("-");
            var month = parts[0] || "";
            var year = parts[1] || "";
            return { month: month, year: year };
        },

        /**
         * Initialize DataTable.
         */
        initDataTable: function() {
            var $dataTable = $("#attendanceDataTable");
            if (!$dataTable.length) return;

            data_table = $dataTable.DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                ordering: true,
                order: [[0, "asc"]],
                dom: '<"table-responsive"t><"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [
                    {
                        extend: "excelHtml5",
                        title: "Attendance_Register_" + ($("#filterMonthYear").val() || ""),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    // Strip HTML tags for clean excel export
                                    var text = $(node).text().trim();
                                    return text.replace(/\s+/g, " ");
                                }
                            }
                        }
                    },
                    {
                        extend: "csvHtml5",
                        title: "Attendance_Register_" + ($("#filterMonthYear").val() || ""),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    var text = $(node).text().trim();
                                    return text.replace(/\s+/g, " ");
                                }
                            }
                        }
                    }
                ],
                language: {
                    paginate: {
                        previous: '<i class="fa fa-chevron-left" style="font-size:11px;"></i> Previous',
                        next: 'Next <i class="fa fa-chevron-right" style="font-size:11px;"></i>'
                    },
                    info: "Showing _START_–_END_ of _TOTAL_ members",
                    infoEmpty: "Showing 0–0 of 0 members",
                    emptyTable: "No member attendance records found for this period",
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                },
                ajax: {
                    url: $dataTable.data("url"),
                    type: "GET",
                    data: function(d) {
                        var dateObj = AttendenceRegister.getSelectedDate();
                        d.month = dateObj.month;
                        d.year = dateObj.year;
                        d.coach_name = $("#filterCoach").val() || "";
                        d.attendance_status = $("#filterAttendanceStatus").val() || "";
                    },
                    dataSrc: function(json) {
                        if (json && json.stats) {
                            AttendenceRegister.updatePulseStats(json.stats);
                        }
                        return json.aaData || [];
                    }
                },
                columns: [
                    { data: "member", name: "name", orderable: true },
                    { data: "attendance", name: "total_present", orderable: true },
                    { data: "total_present", name: "total_present", orderable: true },
                    { data: "total_absent", name: "total_absent", orderable: true },
                    { data: "rate", name: "total_present", orderable: true },
                    { data: "follow_up", name: "total_present", orderable: true },
                    { data: "action", name: "action", orderable: false, searchable: false, className: "text-end" }
                ],
                drawCallback: function() {
                    // Update month text in titles if needed
                    var selectedText = $("#filterMonthYear option:selected").text().replace("📅", "").trim();
                    var monthOnly = selectedText.split(" ")[0];
                    if (monthOnly) {
                        $(".pulse-dynamic-month").text(monthOnly);
                    }
                }
            });
        },

        /**
         * Update the top Pulse and Attention widgets in real-time.
         */
        updatePulseStats: function(stats) {
            if (!stats) return;

            // Month text
            if (stats.month_name) {
                $(".pulse-dynamic-month").text(stats.month_name);
            }

            // Member count
            $("#pulse-member-count").text(stats.total_members || 0);

            // Present & Absent counts
            $("#pulse-total-present").text(stats.total_present || 0);
            $("#pulse-total-absent").text(stats.total_absent || 0);

            // Donut Gauge
            var avgRate = Math.min(100, Math.max(0, parseInt(stats.avg_rate) || 0));
            $("#pulse-avg-rate").text(avgRate + "%");
            var circumference = 2 * Math.PI * 40; // 251.32
            var dashoffset = circumference - (avgRate / 100) * circumference;
            $("#pulse-donut-gauge").css({
                "stroke-dasharray": circumference,
                "stroke-dashoffset": dashoffset
            });

            // Segmented Progress Bar
            var presentPct = Math.min(100, Math.max(0, parseInt(stats.present_pct) || 0));
            var absentPct = Math.min(100, Math.max(0, parseInt(stats.absent_pct) || 0));
            $("#pulse-bar-present").css("width", presentPct + "%");
            $("#pulse-bar-absent").css("width", absentPct + "%");
            $("#pulse-present-pct").text(presentPct + "%");
            $("#pulse-absent-pct").text(absentPct + "%");

            // Top Consistency
            $("#pulse-top-days").text((stats.top_consistency_days || 0) + " days");
            var topNames = stats.top_consistency_names || "None";
            $("#pulse-top-names").text(topNames).attr("title", topNames);

            // Needs Attention Section
            $("#needs-attention-count-btn").text(stats.needs_attention_count || 0);
            var $list = $("#needs-attention-list");
            $list.empty();

            if (stats.needs_attention_list && stats.needs_attention_list.length > 0) {
                $.each(stats.needs_attention_list, function(idx, item) {
                    var html = '<div class="fcc-attention-item">' +
                        '<div class="fcc-attention-user-info">' +
                        '<div class="fcc-attention-avatar" style="background-color: ' + (item.bg_color || '#fee2e2') + '; color: ' + (item.text_color || '#dc2626') + ';">' +
                        (item.initial || 'U') +
                        '</div>' +
                        '<span class="fcc-attention-name">' + (item.name || 'User') + '</span>' +
                        '</div>' +
                        '<div class="fcc-attention-stat">' +
                        '<span>0 / ' + (item.total_days || 30) + ' days</span>' +
                        '<i class="fa fa-chevron-right ms-1"></i>' +
                        '</div>' +
                        '</div>';
                    $list.append(html);
                });
            } else {
                $list.html('<div class="text-muted text-center py-3" style="font-size: 13px;"><i class="fa fa-check-circle text-success me-1"></i> All members have active check-ins!</div>');
            }
        },

        /**
         * Initialize Filter event listeners.
         */
        initFilters: function() {
            // Real-time search with debounce
            $("#filterSearch").on("keyup search", function() {
                var query = this.value;
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    if (data_table) {
                        data_table.search(query).draw();
                    }
                }, 300);
            });

            // Month & Year Filter Change
            $("#filterMonthYear").on("change", function() {
                var selectedText = $(this).find("option:selected").text().replace("📅", "").trim();
                var monthOnly = selectedText.split(" ")[0];
                if (monthOnly) {
                    $(".pulse-dynamic-month").text(monthOnly);
                }
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Coach Filter Change
            $("#filterCoach").on("change", function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Attendance Status Filter Change
            $("#filterAttendanceStatus").on("change", function() {
                if (data_table) {
                    data_table.ajax.reload();
                }
            });

            // Page length change
            $("#filterPageLength").on("change", function() {
                var len = parseInt($(this).val()) || 20;
                if (data_table) {
                    data_table.page.len(len).draw();
                }
            });

            // More Filters Dropdown Change
            $("#filterMoreActions").on("change", function() {
                var action = $(this).val();
                if (action === "clear") {
                    $("#filterSearch").val("");
                    $("#filterCoach").val("");
                    $("#filterAttendanceStatus").val("");
                    $("#filterMonthYear").prop("selectedIndex", 0);
                    $(this).val("");

                    var selectedText = $("#filterMonthYear option:selected").text().replace("📅", "").trim();
                    var monthOnly = selectedText.split(" ")[0];
                    if (monthOnly) {
                        $(".pulse-dynamic-month").text(monthOnly);
                    }

                    if (data_table) {
                        data_table.search("").ajax.reload();
                    }
                }
            });

            // Clear Filters Button (if present)
            $("#btnClearFilters").on("click", function(e) {
                e.preventDefault();
                $("#filterSearch").val("");
                $("#filterCoach").val("");
                $("#filterAttendanceStatus").val("");
                
                // Reset to first option (current month/year)
                $("#filterMonthYear").prop("selectedIndex", 0);

                var selectedText = $("#filterMonthYear option:selected").text().replace("📅", "").trim();
                var monthOnly = selectedText.split(" ")[0];
                if (monthOnly) {
                    $(".pulse-dynamic-month").text(monthOnly);
                }

                if (data_table) {
                    data_table.search("").ajax.reload();
                }
            });
        },

        /**
         * Initialize Actions (Review Needs Attention & Export).
         */
        initActions: function() {
            // Review Needs Attention quick filter
            $("#btnReviewNeedsAttention").on("click", function(e) {
                e.preventDefault();
                $("#filterAttendanceStatus").val("no_checkins");
                if (data_table) {
                    data_table.ajax.reload();
                }
                // Smooth scroll to table card
                $("html, body").animate({
                    scrollTop: $(".fcc-table-card").offset().top - 80
                }, 400);
            });

            // Export Report button
            $("#btnExportReport").on("click", function(e) {
                e.preventDefault();
                if (data_table) {
                    data_table.button(".buttons-excel").trigger();
                }
            });
        },

        /**
         * View Attendance Calendar Modal.
         */
        viewAttendence: function() {
            var $source = $(".data-table-container");
            $source.on("click", ".view-attendence", function(e) {
                e.preventDefault();
                var $this = $(this);
                var url = $this.data("url");
                if (!url) return;

                var $modal = $("#pageModal");
                if (!$modal.length) {
                    // Create modal container if not already in layout
                    $("body").append(
                        '<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-hidden="true">' +
                        '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">' +
                        '<div class="modal-content border-0 shadow-lg" style="border-radius: 16px;"></div>' +
                        '</div>' +
                        '</div>'
                    );
                    $modal = $("#pageModal");
                }

                $modal.find(".modal-content").html(
                    '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );
                
                $modal.modal("show");
                $modal.find(".modal-content").load(url, function(response, status, xhr) {
                    if (status === "error") {
                        $(this).html('<div class="p-4 text-center text-danger">Failed to load attendance details. Please try again.</div>');
                    }
                });
            });
        }
    };
})();

$(document).ready(function() {
    AttendenceRegister.init();
});
