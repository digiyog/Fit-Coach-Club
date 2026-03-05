var ManualAttendence = (function() {
    // Array holding selected row IDs
    var rows_selected = [];
    var data_table;
    return {
        /**
         * Initialization.
         */
        init: function() {
            ManualAttendence.getManualAttendence();
            ManualAttendence.destroyRecord();
            ManualAttendence.initializeComponents();
            ManualAttendence.validateAddManualAttendenceForm();
            ManualAttendence.validateAddTodayWeightForm();
            ManualAttendence.customValidationMethods();
            ManualAttendence.deleteAttendence();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            // Initialize Components
            var $form = $('.add-manual-attendance-form');

            // Date Picker
            Components.datePicker($form);
            //-------------------

            // Bootstrap Select
            Components.bootstrapSelect($form);
            //-------------------

            // Initialize Components
            var $form = $('.add-today-weight-form');

            // Date Picker
            Components.datePicker($form);
            //-------------------

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
         * Validate Manual Attendance form.
         */
        validateAddManualAttendenceForm: function() {
            var $form = $(".add-manual-attendance-form");

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    date: {
                        required: true,
                    },
                    days: {
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
                    App.formLoading($form);
                    form.submit();
                }
            });
        },

        /**
         * Validate Today Weight form.
         */
        validateAddTodayWeightForm: function() {
            var $form = $(".add-today-weight-form");

            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    date: {
                        required: true,
                    },
                    weight: {
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
                    App.formLoading($form);
                    form.submit();
                }
            });
        },

        /**
         * Get Manual Attendance list.
         */
        getManualAttendence: function() {
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

                    $('.btn-toolbar').append(`
                        <a href="${$dataTable.data('track-shake-url')}"
                           title="View Attendance History"
                           class="btn btn-icon btn-rounded btn-primary btn-outline">
                            View Attendance History
                        </a>
                    `);
                },
                headerCallback: function(e, a, t, n, s) {
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
                    // sSearch: '<i data-feather="search"></i>',
                    // sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_"
                },
                processing: true,
                serverSide: true,
                searching: false,
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
                        d.user_id = $("#user_id").val();
                    }
                },
                columns: [
                    { data: "id", name: "id", width:150, },
                    { data: "attendence_date", name: "attendence_date" },
                    { data: "weight", name: "weight" },
                    { data: "attendence_count", name: "attendence_count" },
                    {
                        data: "action",
                        name: "action",
                        searchable: false,
                        sortable: false,
                        className: "text-right",
                        width:180,
                    }
                ],
                rowCallback: function(row, data, dataIndex) {
                }
            });

            // Handle table draw event
            table.on("draw", function() {
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

        /**
         * Delete Attendance.
         */
        deleteAttendence: function () {
            var $source = $(".data-table-container");
            $source.on("click", ".delete-attendence", function () {
                var $this = $(this);

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
                                    url: $this.data("url"),
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

ManualAttendence.init();
