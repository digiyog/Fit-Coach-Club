var Framework = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            Framework.validateFrameworkForm();
            Framework.initializeComponents();
            Framework.customValidationMethods();
            Framework.getJobs();
            Framework.dataTableCustomFilter();
            Framework.getFavouriteNurses();
            Framework.removeNurseFromFavourite();
            Framework.getBlockedNurses();
            Framework.removeNurseFromBlocklist();
            Framework.addNurseToFavouritePopup();
            Framework.addNurseToBlocklistPopup();
            Framework.getRegions();
            Framework.getCounty();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $(".add-framework-form");

            // Bootstrap select
            Components.bootstrapSelect($form);
            //-----------------

            // Filter
            var $source = $(".custom-datatable-filter");

            Components.bootstrapSelect($source);

            var $date_picker = $source.find(".date-picker");
            if ($date_picker.length) {
                $date_picker.flatpickr({
                    format: "Y-m-d"
                });
            }

            var $date_range_picker = $source.find(".date-range-picker");
            if ($date_range_picker.length) {
                $date_range_picker.flatpickr({
                    mode: "range",
                    dateFormat: "d-m-Y"
                });
            }
            //-------
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
                        /^[a-zA-Z][a-zA-Z ]+$/i.test(value)
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
                "Please enter valid number."
            );

            jQuery.validator.addMethod(
                "emailChecker",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i.test(
                            value
                        )
                    );
                },
                "Please enter a valid email address."
            );

            jQuery.validator.addMethod(
                "mobileNumber",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^(?:0|\+?44)(?:\d\s?){10,12}$/i.test(value)
                    );
                },
                "Please enter valid number."
            );
        },

        /**
         * Validate Framework form.
         */
        validateFrameworkForm: function() {
            var $form = $(".add-framework-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    name: {
                        required: true
                    },
                    pricecard: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    contact_name: {
                        required: true
                    },
                    contact_email: {
                        required: true,
                        emailChecker: true
                    },
                    contact_no: {
                        required: true,
                        mobileNumber: true,
                        maxlength: 14
                    },
                    client_reference_no: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    region: {
                        required: true
                    }
                },
                //------------------

                // @validation error messages
                messages: {
                    name: {
                        required: "Please enter name."
                    },
                    pricecard: {
                        required: "Please select pricecard."
                    },
                    address: {
                        required: "Please enter address."
                    },
                    contact_name: {
                        required: "Please enter contact name"
                    },
                    contact_email: {
                        required: "Please enter email address.",
                        emailChecker: "Invalid email address."
                    },
                    contact_no: {
                        required: "Please enter contact number.",
                        mobileNumber: "Input format eg.(01111 ******)",
                        maxlength: "Exceed limit"
                    },
                    client_reference_no: {
                        required: "Please enter Client Reference No"
                    },
                    country: {
                        required: "Please select country."
                    },
                    region: {
                        required: "Please select region"
                    }
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
                submitHandler: function(form) {
                    App.formLoading($form);
                    form.submit();
                }
            });
        },

        /**
         * Get regions according to country.
         */
        getRegions: function($source) {
            var $source = $(".add-framework-form");

            $source.on("change", ".country-region", function() {
                var $this = $(this);
                var country = $this.val();

                if (country != "") {
                    var country_sortname = $(this)
                        .find(":selected")
                        .attr("data-sortname");

                    initAutocomplete(country_sortname);

                    $(".region-div").append(
                        '<div class="input-loading"> <i class="fa fa-refresh fa-spin"></i> </div>'
                    );
                    $(".county-div").append(
                        '<div class="input-loading"> <i class="fa fa-refresh fa-spin"></i> </div>'
                    );

                    $.ajax({
                        type: "GET",
                        url: $this.data("url"),
                        data: {
                            country_id: country
                        },
                        success: function(response) {
                            var data = response._data;
                            var options =
                                '<option selected="selected" value=""> Region Name </option>';
                            var county_option =
                                '<option selected="selected" value=""> County Name </option>';

                            $.each(data, function(index, data) {
                                options +=
                                    '<option value="' +
                                    data.id +
                                    '">' +
                                    data.name +
                                    "</option>";
                            });
                            $("#region").html(options);
                            $("#region").selectpicker("refresh");

                            // County
                            $("#county").html(county_option);
                            $("#county").selectpicker("refresh");
                            $(".county-div .input-loading").hide();
                            //----------
                            $(".region-div .input-loading").hide();
                        }
                    });
                }
            });
        },

        /**
         * Get counties according to region.
         */
        getCounty: function($source) {
            var $source = $(".add-framework-form");

            $source.on("change", ".region-county", function() {
                var $this = $(this);
                var region = $this.val();

                if (region != "") {
                    $(".county-div").append(
                        '<div class="input-loading"> <i class="fa fa-refresh fa-spin"></i> </div>'
                    );
                    $.ajax({
                        type: "GET",
                        url: $this.data("url"),
                        data: {
                            region_id: region
                        },
                        success: function(response) {
                            var data = response._data;
                            var options =
                                '<option selected="selected" value=""> County Name </option>';

                            $.each(data, function(index, data) {
                                options +=
                                    '<option value="' +
                                    data.id +
                                    '">' +
                                    data.name +
                                    "</option>";
                            });
                            $("#county").html(options);
                            $("#county").selectpicker("refresh");
                            $(".county-div .input-loading").hide();
                        }
                    });
                }
            });
        },

        /**
         * Get jobs list.
         */
        getJobs: function() {
            var $dataTable = $("#dataTable");

            if ($dataTable.length) {
                data_table = table = $dataTable.DataTable({
                    oLanguage: {
                        oPaginate: {
                            sPrevious:
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                            sNext:
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                        },
                        sInfo: "Showing page _PAGE_ of _PAGES_",
                        sSearch: '<i data-feather="search"></i>',
                        sSearchPlaceholder: "Search...",
                        sLengthMenu: "Results :  _MENU_"
                    },
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 10,
                    order: [],
                    dom:
                        '<"row"<"col-md-12"<"row"<"col-md-6 pl-0"lf> <"col-md-6"> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                    ajax: {
                        url: $dataTable.data("url"),
                        data: function(d) {
                            d.job_filter_date = $(
                                "input[name=job_filter_date]"
                            ).val();
                            d.job_filter_status = $(
                                "select[name=job_filter_status]"
                            ).val();
                        }
                    },
                    columns: [
                        { data: "job", name: "job" },
                        {
                            data: "framework",
                            name: "framework"
                        },
                        {
                            data: "start_time",
                            name: "start_time"
                        },
                        {
                            data: "band",
                            name: "band"
                        },
                        {
                            data: "assignment_code",
                            name: "assignment_code"
                        },
                        {
                            data: "nurse",
                            name: "nurse"
                        },
                        {
                            data: "hours",
                            name: "hours"
                        },
                        {
                            data: "amount",
                            name: "amount"
                        },
                        {
                            data: "penalty",
                            name: "penalty"
                        },
                        {
                            data: "status",
                            name: "status"
                        }
                    ]
                });
            }

            // Apply filter
            $(".apply-filter").on("click", function(e) {
                data_table.ajax.reload();
                e.preventDefault();
            });
            //-------------

            // Clear filter
            $(".clear-filter").on("click", function(e) {
                $(".custom-datatable-filter-form").trigger("reset");
                data_table.ajax.reload();
                e.preventDefault();
            });
            //-------------
        },

        /**
         * Datatable custom filter.
         */
        dataTableCustomFilter: function() {
            $(".filter-button").click(function() {
                $(".custom-datatable-filter").toggleClass("hide");
            });
        },

        /**
         * Get nurses.
         */
        getNurses: function() {
            var $dataTable = $("#dataTable-nurses");

            nurses_data_table = table = $dataTable.DataTable({
                oLanguage: {
                    oPaginate: {
                        sPrevious:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext:
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sInfo: "Showing page _PAGE_ of _PAGES_",
                    sSearch: '<i data-feather="search"></i>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_"
                },
                processing: true,
                serverSide: true,
                lengthMenu: [5, 10, 20, 50],
                pageLength: 10,
                order: [],
                dom:
                    '<"row"<"col-md-12"<"row"<"col-md-12 pl-0"lf> <"col-md-6"> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                ajax: {
                    url: $dataTable.data("url"),
                    data: function(d) {
                        //
                    }
                },
                columns: [
                    { data: "name", name: "name" },
                    { data: "gender", name: "gender" },
                    { data: "age", name: "age" },
                    { data: "grade", name: "grade" },
                    { data: "band", name: "band" },
                    { data: "action", name: "action" }
                ]
            });
        },

        /**
         * Add nurse to favourite popup.
         */
        addNurseToFavouritePopup: function() {
            $(".add-favourite-button").click(function() {
                var $this = $(this);
                var $page_modal = $("#pageModal");

                $page_modal.modal("show");
                $page_modal.find(".modal-content").load(
                    $this.data("url"),
                    {
                        type: "favourite"
                    },
                    function() {
                        Framework.getNurses();
                        Framework.addToFavourite();
                    }
                );

                $page_modal.on("hidden.bs.modal", function() {
                    App.resetModal($page_modal);
                });
            });
        },

        /**
         * Remove nurse from favourite.
         */
        removeNurseFromFavourite: function() {
            var $source = $("#dataTable-favourite-nurses");
            $source.on("click", ".remove-favourite-button", function() {
                var $this = $(this);
                var id = $this.data("id");

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to remove?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                $.ajax({
                                    type: "DELETE",
                                    url: $this.data("destroy-url"),
                                    data: { id: id },
                                    beforeSend: function() {
                                        // $(".dt-delete").prop("disabled", true);
                                    },
                                    success: function(response) {
                                        App.showNotification(response);
                                        favourite_data_table.ajax.reload();
                                    },
                                    error: function() {},
                                    complete: function() {
                                        // $(".dt-delete").prop("disabled", true);
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
                        //
                    },
                    onClosed: function(instance, toast, closedBy) {
                        //
                    }
                });
            });
        },

        /**
         * Add to favourite.
         */
        addToFavourite: function() {
            var $source = $("#dataTable-nurses");
            $source.on("click", ".add-to-favourite", function() {
                var $this = $(this);
                var id = $this.data("hospital-id");
                var nurse_id = $this.data("nurse-id");

                $.ajax({
                    type: "POST",
                    url: $this.data("url"),
                    data: { id: id, nurse_id: nurse_id },
                    beforeSend: function() {
                        //
                    },
                    success: function(response) {
                        App.showNotification(response);
                        nurses_data_table.ajax.reload();
                        favourite_data_table.ajax.reload();
                    },
                    error: function() {},
                    complete: function() {
                        //
                    }
                });
            });
        },

        /**
         * Get favourites nurses.
         */
        getFavouriteNurses: function() {
            var $dataTable = $("#dataTable-favourite-nurses");

            if ($dataTable.length) {
                favourite_data_table = table = $dataTable.DataTable({
                    oLanguage: {
                        oPaginate: {
                            sPrevious:
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                            sNext:
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                        },
                        sInfo: "Showing page _PAGE_ of _PAGES_",
                        sSearch: '<i data-feather="search"></i>',
                        sSearchPlaceholder: "Search...",
                        sLengthMenu: "Results :  _MENU_"
                    },
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 10,
                    order: [],
                    dom:
                        '<"row"<"col-md-12"<"row"<"col-md-6 pl-0"lf> <"col-md-6"> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                    ajax: {
                        url: $dataTable.data("url"),
                        data: function(d) {
                            //
                        }
                    },
                    columns: [
                        { data: "name", name: "name" },
                        { data: "gender", name: "gender" },
                        { data: "age", name: "age" },
                        { data: "grade", name: "grade" },
                        { data: "band", name: "band" },
                        { data: "action", name: "action" }
                    ]
                });
            }
        },

        /**
         * Add nurse to blocklist popup.
         */
        addNurseToBlocklistPopup: function() {
            $(".add-blocklist-button").click(function() {
                var $this = $(this);
                var $page_modal = $("#pageModal");

                $page_modal.modal("show");
                $page_modal.find(".modal-content").load(
                    $this.data("url"),
                    {
                        type: "blocklist"
                    },
                    function() {
                        Framework.getNurses();
                        Framework.addToBlocklist();
                    }
                );

                $page_modal.on("hidden.bs.modal", function() {
                    App.resetModal($page_modal);
                });
            });
        },

        /**
         * Remove nurse from blocklist.
         */
        removeNurseFromBlocklist: function() {
            var $source = $("#dataTable-blocked-nurses");
            $source.on("click", ".remove-blocked-button", function() {
                var $this = $(this);
                var id = $this.data("id");

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: "once",
                    color: "yellow",
                    id: "question",
                    zindex: 99999,
                    title: "Hey!",
                    message: "Are you sure to want to remove?",
                    position: "center",
                    progressBar: false,
                    buttons: [
                        [
                            "<button><b>YES</b></button>",
                            function(instance, toast) {
                                $.ajax({
                                    type: "DELETE",
                                    url: $this.data("destroy-url"),
                                    data: { id: id },
                                    beforeSend: function() {
                                        // $(".dt-delete").prop("disabled", true);
                                    },
                                    success: function(response) {
                                        App.showNotification(response);
                                        blocklist_data_table.ajax.reload();
                                    },
                                    error: function() {},
                                    complete: function() {
                                        // $(".dt-delete").prop("disabled", true);
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
                        //
                    },
                    onClosed: function(instance, toast, closedBy) {
                        //
                    }
                });
            });
        },

        /**
         * Get blocked nurses.
         */
        getBlockedNurses: function() {
            var $dataTable = $("#dataTable-blocked-nurses");

            if ($dataTable.length) {
                blocklist_data_table = table = $dataTable.DataTable({
                    oLanguage: {
                        oPaginate: {
                            sPrevious:
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                            sNext:
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                        },
                        sInfo: "Showing page _PAGE_ of _PAGES_",
                        sSearch: '<i data-feather="search"></i>',
                        sSearchPlaceholder: "Search...",
                        sLengthMenu: "Results :  _MENU_"
                    },
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 10,
                    order: [],
                    dom:
                        '<"row"<"col-md-12"<"row"<"col-md-6 pl-0"lf> <"col-md-6"> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
                    ajax: {
                        url: $dataTable.data("url"),
                        data: function(d) {
                            //
                        }
                    },
                    columns: [
                        { data: "name", name: "name" },
                        { data: "gender", name: "gender" },
                        { data: "age", name: "age" },
                        { data: "grade", name: "grade" },
                        { data: "band", name: "band" },
                        { data: "status", name: "status" },
                        { data: "action", name: "action" }
                    ]
                });
            }
        },

        /**
         * Add to blocklist.
         */
        addToBlocklist: function() {
            var $source = $("#dataTable-nurses");
            $source.on("click", ".add-to-blocklist", function() {
                var $this = $(this);
                var id = $this.data("hospital-id");
                var nurse_id = $this.data("nurse-id");

                $.ajax({
                    type: "POST",
                    url: $this.data("url"),
                    data: { id: id, nurse_id: nurse_id },
                    beforeSend: function() {
                        //
                    },
                    success: function(response) {
                        App.showNotification(response);
                        nurses_data_table.ajax.reload();
                        blocklist_data_table.ajax.reload();
                    },
                    error: function() {},
                    complete: function() {
                        //
                    }
                });
            });
        }
    };
})();

Framework.init();
