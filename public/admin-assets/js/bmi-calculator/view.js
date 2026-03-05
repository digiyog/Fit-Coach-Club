var BmiCalculator = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            BmiCalculator.getBmiCalculator();
            BmiCalculator.destroyRecord();
            BmiCalculator.initializeComponents();
            BmiCalculator.validateBmiCalculatorForm();
            BmiCalculator.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Initialize Components
            var $form = $('.calculate-bmi-form');

            // Bootstrap Select
            Components.bootstrapSelect($form);
            //-------------------
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
        },

        /**
         * Validate Bmi Calculator form.
         */
        validateBmiCalculatorForm: function() {
            var $form = $(".calculate-bmi-form");

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    name: {
                        required: true,
                    },
                    mobile_number: {
                        required: true,
                    },
                    age: {
                        required: true,
                    },
                    weight: {
                        required: true,
                    },
                    height: {
                        required: true,
                    },
                    gender: {
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
                    else if($(element).hasClass('select-picker'))
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else if (element.hasClass("editor-textarea")) {
                        error.insertAfter(element.siblings(".note-editor")); // editor ke baad
                    } 
                    else if (element.parent(".input-group").length) {
                        error.insertAfter(element.parent()); // input-group ke baad
                    } 
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    // App.formLoading($form);
                    // form.submit();
                    event.preventDefault();

                    const gender = document.getElementById('gender').value;
                    const age = parseFloat(document.getElementById('age').value);
                    const weight = parseFloat(document.getElementById('weight').value);
                    const height = parseFloat(document.getElementById('height').value) / 100; // cm → m

                    // ✅ BMI
                    const bmi = (weight / (height * height)).toFixed(2);
                    document.getElementById('view_bmi').textContent = bmi;

                    // ✅ Body Fat % (Deurenberg formula)
                    let bodyFat;
                    if (gender == 1) {
                      bodyFat = (1.20 * bmi + 0.23 * age - 16.2).toFixed(2);
                    } else {
                      bodyFat = (1.20 * bmi + 0.23 * age - 5.4).toFixed(2);
                    }
                    document.getElementById('view_body_fat').textContent = bodyFat + ' %';

                    // ✅ Muscle Mass (rough estimate)
                    if (gender == 1) {
                      muscleMass = (100 - bodyFat - 10).toFixed(1);
                    } else {
                      muscleMass = (100 - bodyFat - 46.0).toFixed(1); // females naturally have higher fat
                    }
                    document.getElementById('view_muscle_mass').textContent = muscleMass + ' %';

                    // ✅ Visceral Fat (approximation)
                    const visceralFat = (bmi * 0.45 + age * 0.15 + (gender == 1 ? 1.5 : 0)).toFixed(1);
                    document.getElementById('view_visceral_fat').textContent = visceralFat;

                    // ✅ Basal Metabolic Rate (Mifflin-St Jeor Formula)
                    let bmr;
                    if (gender == 1) {
                        bmr = 88.36 + (13.4 * weight) + (4.8 * height * 100) - (5.7 * age);
                    } else {
                        bmr = 447.6 + (9.2 * weight) + (3.1 * height * 100) - (4.3 * age);
                    }
                    document.getElementById('view_metabolic_rate').textContent = Math.round(bmr) + ' kcal/day';

                    // ✅ Body Age (fun estimate)
                    let bodyAge = age + ((bmi - 22) * 0.6) + ((bodyFat - 20) * 0.4);
                    document.getElementById('view_body_age').textContent = Math.round(bodyAge) + ' years';

                    // Basic Biologic Age estimation
                    let bio_age = age + ((bmi - 22) * 0.5) + ((bodyFat - 18) * 0.3) - ((muscleMass - 30) * 0.3);
                    document.getElementById('view_biologic_age').textContent = Math.round(bio_age) + ' years';


                    $('#responseHide').hide();
                    $('#responseShow').removeClass('d-none');

                    var $dataTable = $("#dataTable");

                    $.ajax({
                        type: "POST",
                        url: $dataTable.data("save-url"),
                        data: $('.calculate-bmi-form').serialize(),
                        beforeSend: function() {
                        },
                        success: function(response) {
                            App.showNotification(response);
                            data_table.ajax.reload(null, false);
                            rows_selected = [];
                        },
                        error: function() {},
                        complete: function() {
                        }
                    });
                }
            });
        },

        /**
         * Updates "Select all" control in a data table
         */
        updateDataTableSelectAllCtrl: function(table) {
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
        },

        /**
         * Get Bmi Calculator list.
         */
        getBmiCalculator: function() {
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

                    // $('.btn-toolbar').append(
                    //     '<button type="button" title="Order Update" class="btn btn-icon btn-rounded btn-primary btn-outline update-order"> &nbsp; Order Update </button> '
                    // );

                    // $('.btn-toolbar').append(
                    //     '<button type="button" title="Change Status" class="btn btn-icon btn-rounded btn-primary btn-outline change-status" disabled> <i class="fa fa-exchange" aria-hidden="true"></i> &nbsp; Change Status </button> '
                    // );

                    $('.btn-toolbar').append(
                        '<button type="button" title="Delete" class="btn btn-icon btn-rounded btn-primary btn-outline dt-delete" disabled> <i class="fa fa-trash" aria-hidden="true"></i> &nbsp; Delete </button> '
                    );
                },
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML =
                            '<label class="new-control new-checkbox checkbox-outline-primary m-auto">\n<input type="checkbox" name="select_all" class="new-control-input chk-parent select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                },
                columnDefs: [
                    {
                        targets: 0,
                        width: "30px",
                        className: "",
                        orderable: !1,
                        visible: true,
                        render: function(e, a, t, n) {
                            return '<label class="new-control new-checkbox checkbox-outline-primary  m-auto">\n<input type="checkbox" class="new-control-input child-chk select-customers-primary" id="customer-all-info">\n<span class="new-control-indicator"></span><span style="visibility:hidden">c</span>\n</label>';
                        }
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
                        //         columns: [1, 2]
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
                        //         columns: [1, 2, 3, 4]
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
                    [20, 50, 75, 100],
                    [20, 50, 75, 100]
                ],
                pageLength: 20,
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-6"lf> <"col-md-6"B> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                    }
                },
                columns: [
                    {
                        data: null,
                        name: "",
                        searchable: false,
                        sortable: false
                    },
                    { data: "name", name: "name" },
                    { data: "mobile_number", name: "mobile_number" },
                    { data: "age", name: "age" },
                    { data: "weight", name: "weight" },
                    { data: "height", name: "height" },
                    { data: "gender", name: "gender" },
                    { data: "bmi", name: "bmi" },
                    { data: "body_fat", name: "body_fat" },
                    { data: "visceral_fat", name: "visceral_fat" },
                    { data: "muscle_mass", name: "muscle_mass" },
                    { data: "metabolic_rate", name: "metabolic_rate" },
                    { data: "biologic_age", name: "biologic_age" },
                    { data: "body_age", name: "body_age" },
                    // {
                    //     data: "action",
                    //     name: "action",
                    //     searchable: false,
                    //     sortable: false,
                    //     width:50,
                    // }
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
                }
            });

            // Apply filter
            // $(".apply-filter").on("click", function(e) {
            //     data_table.ajax.reload();
            //     e.preventDefault();
            // });
            //-------------

            // Clear filter
            // $(".clear-filter").on("click", function(e) {
            //     $(".agency-filter-form")[0].reset();
            //     $source = $(".agency-filter-form");
            //     $select = $source.find(".select-picker");
            //     $select.selectpicker("refresh");
            //     data_table.ajax.reload();
            //     e.preventDefault();
            // });
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
                    BmiCalculator.updateDataTableSelectAllCtrl(table);

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
                BmiCalculator.updateDataTableSelectAllCtrl(table);

                // Additional form validation methods
                Components.additionalValidationMethods();
               //----------
            });
        },

        /**
         * Destroy record.
         */
        destroyRecord: function() {
            var $data_table_container = $(".data-table-container");
            var $dataTable = $(".dataTable");

            // Handle form submission event
            $data_table_container.on("click", ".dt-delete", function() {
                // Iterate over all selected checkboxes
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    ids.push(rowId.id);
                });

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to delete?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {

                                $.ajax({
                                    type: "DELETE",
                                    url: $dataTable.data("destroy-url"),
                                    data: { ids: ids },
                                    beforeSend: function() {
                                        $(".dt-delete").prop("disabled", true);
                                        $(".change-status").prop("disabled", true);
                                    },
                                    success: function(response) {
                                        App.showNotification(response);
                                        data_table.ajax.reload(null, false);
                                        rows_selected = [];
                                    },
                                    error: function() {},
                                    complete: function() {
                                        $(".dt-delete").prop("disabled", true);
                                        $(".change-status").prop("disabled", true);
                                    }
                                });
                                instance.hide(
                                    { transitionOut: "fadeOut" },
                                    toast,
                                    "button"
                                );
                            },
                            true
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
    };
})();

BmiCalculator.init();
