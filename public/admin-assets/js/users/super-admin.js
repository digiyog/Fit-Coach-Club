var isPlaceSelected = true;

var User = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            User.validateForm();
            User.validateEditForm();
            User.checkPermissionsByModule();
            User.checkPermissionsByType();
            User.customValidationMethods();
            User.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $addForm = $('.add-super-admin-form');
            var $editForm = $('.edit-super-admin-form');

            // Additional form validation methods
            Components.additionalValidationMethods();
            //----------
            
            // Image preview
            Components.imagePreview($addForm);
            Components.imagePreview($editForm);
            //--------------

            // Bootstrap Select
            Components.bootstrapSelect($addForm);
            Components.bootstrapSelect($editForm);
            //--------------

            // Date picker
            Components.datePicker($addForm);
            Components.datePicker($editForm);
            //------------------

            // Date Range picker
            var $date_range_picker_add = $addForm.find(".date-picker-input");
            var $date_range_picker_edit = $editForm.find(".date-picker-input");

            if ($date_range_picker_add.length) {
                $date_range_picker_add.flatpickr({
                    dateFormat: "d-m-Y",
                    mode: "single",
                    maxDate: "today",
                });
            }

            if ($date_range_picker_edit.length) {
                $date_range_picker_edit.flatpickr({
                    dateFormat: "d-m-Y",
                    mode: "single",
                    maxDate: "today",
                });
            }
            //----------------
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
                "mobileNumber",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        // /^\+[1-9]{1}[0-9]{8,14}$/i.test(value)
                        /^\d{8,14}$/i.test(value)
                    );
                },
                // "Please enter valid number e.g. +61474567894"
                "Please enter valid number e.g. 4745678940"
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
                "passwordChecker",
                function (value, element) {
                    return (
                        this.optional(element) ||
                        // /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{6,}$/i.test(
                        //     value
                        // )
                        /^(?!.*\s)(?=.*).{6,12}$/.test(
                            value
                        )
                    );
                },
                // "Password must contain minimum 6 characters including one uppercase, one lowercase, one special character and numeric value."
                "Password must be 6 to 12 characters."
            );

            jQuery.validator.addMethod(
                "usernameCheck",
                function(value, element) {
                    if (value) {
                        return (
                            this.optional(element) ||
                            // /^[a-zA-Z0-9!*&$#@%_]*$/g.test(value)
                            /^((?=.*[\d])[a-zA-Z0-9]{12,})*$/g.test(value)
                        );
                    } else {
                        return true;
                    }
                },
                "Please enter valid alphanumeric username"
            );

            jQuery.validator.addMethod(
                "intlMobileNumber",
                function(value, element) {
                    return $(element).intlTelInput("isValidNumber");
                },
                "Please enter valid number for selected country"
            );
        },

        /**
         * Validate Add Super Admin form.
         */
        validateForm: function() {
            var $form = $(".add-super-admin-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    profile_image: {
                        required: false,
                        accept: "image/jpg,image/jpeg,image/png,image/gif",
                        imageSizeCheck: true,
                    },
                    name: {
                        required: true,
                    },
                    gender: {
                        required: true,
                    },
                    email: {
                        required: true,
                        emailChecker: true,
                        remote: {
                            url: $("#email").data("url"),
                            type: "post",
                            data: {
                                email: function () {
                                    return $("#email").val();
                                },
                            },
                        },
                    },
                    mobile_number: {
                        required: true,
                        mobileNumber: false,
                        digits: true,
                        intlMobileNumber: true,
                        remote: {
                            url: $("#mobile_number").data("url"),
                            type: "post",
                            data: {
                                mobile_number: function () {
                                    return $("#mobile_number").val();
                                },
                            },
                        },
                    },
                    password: {
                        required: true,
                        passwordChecker: true,
                    },
                    'permissions[]': {
                        required: {
                            depends: function(){
                                if($(".check-by-module:checked").length > 0 || $(".single-permission:checked").length > 0 || $(".check-all-view").prop("checked") == true || $(".check-all-create").prop("checked") == true || $(".check-all-edit").prop("checked") == true || $(".check-all-delete").prop("checked") == true){
                                    return false;
                                }
                                else{
                                    return true;
                                }
                            }
                        },
                    },
                    country_id: {
                        required: true,
                    },
                },
                //------------------

                // @validation error messages
                messages: {
                    profile_image:{
                        required: "This field is required.",
                        accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    },
                    name: {
                        required: "This field is required.",
                    },
                    gender: {
                        required: "This field is required.",
                    },
                    password: {
                        required: "This field is required.",
                    },
                    email: {
                        required: "This field is required.",
                        remote: "Email already registered.",
                    },
                    mobile_number: {
                        required: "This field is required.",
                        remote: "Mobile number already registered.",
                        digits: "Please enter numbers only.",
                    },
                    'permissions[]': {
                        required: "Please select at least one of the permissions.",
                    },
                    country_id: {
                        required: "This field is required.",
                    },
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
                    else if($(element).hasClass('select-picker'))
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else if($(element).attr('id') == 'mobile_number')
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else if($(element).hasClass('image-preview'))
                    {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else if($(element).hasClass('single-permission'))
                    {
                        error.appendTo($(element).closest('.form-group'));

                        $(".new-control-input").on('change', function(){
                            $(".add-super-admin-form").valid();
                        });
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, e) {
                    e.preventDefault();

                    App.formLoading($form);
                    form.submit();
                }
            });
        },

        /**
         * Validate edit language form.
         */
        validateEditForm: function() {
            var $form = $(".edit-super-admin-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    profile_image: {
                        required: false,
                        accept: "image/jpg,image/jpeg,image/png,image/gif",
                        imageSizeCheck: true,
                    },
                    name: {
                        required: true,
                    },
                    gender: {
                        required: true,
                    },
                    email: {
                        required: true,
                        emailChecker: true,
                        remote: {
                            url: $("#email").data("url"),
                            type: "post",
                            data: {
                                email: function () {
                                    return $("#email").val();
                                },
                                user_id: function () {
                                    return $("#user_id").val();
                                },
                            },
                        },
                    },
                    mobile_number: {
                        required: true,
                        mobileNumber: false,
                        digits: true,
                        intlMobileNumber: true,
                        remote: {
                            url: $("#mobile_number").data("url"),
                            type: "post",
                            data: {
                                mobile_number: function () {
                                    return $("#mobile_number").val();
                                },
                                user_id: function () {
                                    return $("#user_id").val();
                                },
                            },
                        },
                    },
                    password: {
                        required: false,
                        passwordChecker: true,
                    },
                    'permissions[]': {
                        required: {
                            depends: function(){
                                if($(".check-by-module:checked").length > 0 || $(".single-permission:checked").length > 0 || $(".check-all-view").prop("checked") == true || $(".check-all-create").prop("checked") == true || $(".check-all-edit").prop("checked") == true || $(".check-all-delete").prop("checked") == true){
                                    return false;
                                }
                                else{
                                    return true;
                                }
                            }
                        },
                    },
                    country_id: {
                        required: true,
                    },
                },
                //------------------

                // @validation error messages
                messages: {
                    profile_image:{
                        required: "This field is required.",
                        accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    },
                    name: {
                        required: "This field is required.",
                    },
                    gender: {
                        required: "This field is required.",
                    },
                    password: {
                        required: "This field is required.",
                    },
                    email: {
                        required: "This field is required.",
                        remote: "Email already registered.",
                    },
                    mobile_number: {
                        required: "This field is required.",
                        remote: "Mobile number already registered.",
                        digits: "Please enter numbers only.",
                    },
                    'permissions[]': {
                        required: "Please select at least one of the permissions.",
                    },
                    country_id: {
                        required: "This field is required.",
                    },
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
                    else if($(element).hasClass('select-picker'))
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else if($(element).attr('id') == 'mobile_number')
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else if($(element).hasClass('image-preview'))
                    {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else if($(element).hasClass('single-permission'))
                    {
                        error.appendTo($(element).closest('.form-group'));

                        $(".new-control-input").on('change', function(){
                            $(".add-super-admin-form").valid();
                        });
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, e) {
                    e.preventDefault();

                    App.formLoading($form);
                    form.submit();
                }
            });
        },

        /**
         * Check permissions by module name
         */
        checkPermissionsByModule: function() {
            var addForm = $(".add-super-admin-form, .edit-super-admin-form");

            // Handle module click
            addForm.on("change", ".check-by-module", function() {
                var permissionIndex = $(this).val();

                // Module
                if($(this).is(":checked")){
                    $(".permissions-"+permissionIndex).prop('checked', true);
                }
                else{
                    $(".permissions-"+permissionIndex).prop('checked', false);
                }

                // Single View Permission
                if($(".view-permission:checked").length < $(".view-permission").length){
                    $(".check-all-view").prop('checked', false);
                }
                else{
                    $(".check-all-view").prop('checked', true);
                }

                // Single Create Permission
                if($(".create-permission:checked").length < $(".create-permission").length){
                    $(".check-all-create").prop('checked', false);
                }
                else{
                    $(".check-all-create").prop('checked', true);
                }

                // Single Edit Permission
                if($(".edit-permission:checked").length < $(".edit-permission").length){
                    $(".check-all-edit").prop('checked', false);
                }
                else{
                    $(".check-all-edit").prop('checked', true);
                }

                // Single Delete Permission
                if($(".delete-permission:checked").length < $(".delete-permission").length){
                    $(".check-all-delete").prop('checked', false);
                }
                else{
                    $(".check-all-delete").prop('checked', true);
                }
            });

            // Handle module single permission click
            addForm.on("change", ".single-permission", function() {
                var permissionModuleIndex = $(this).data('module-index');

                if($(this).hasClass('create-permission') || $(this).hasClass('edit-permission') || $(this).hasClass('delete-permission')){
                    $(this).parents('tr').find('.view-permission').prop('checked', true);
                }

                if($(".permissions-"+permissionModuleIndex+":checked").length < 4){
                    $("#check_"+permissionModuleIndex).prop('checked', false);
                }
                else{
                    $("#check_"+permissionModuleIndex).prop('checked', true);
                }
            });
        },

        /**
         * Check permissions by type (View, Create, Edit, Delete)
         */
        checkPermissionsByType: function() {
            var addForm = $(".add-super-admin-form, .edit-super-admin-form");

            // Handle type check all click
            addForm.on("change", ".check-all", function() {
                if($(this).is(":checked")){
                    $(".view-permission").prop('checked', true);
                    $(".create-permission").prop('checked', true);
                    $(".edit-permission").prop('checked', true);
                    $(".delete-permission").prop('checked', true);
                }
                else{
                    $(".view-permission").prop('checked', false);

                    // Uncheck create boxes also
                    $('.check-all-create').prop('checked', false);
                    $('.create-permission').prop('checked', false);

                    // Uncheck edit boxes also
                    $('.check-all-edit').prop('checked', false);
                    $('.edit-permission').prop('checked', false);

                    // Uncheck delete boxes also
                    $('.check-all-delete').prop('checked', false);
                    $('.delete-permission').prop('checked', false);
                }
            });

            // Handle type check all click
            addForm.on("change", ".single-permission", function() {
                // Check module by type
                if($(".single-permission:checked").length == $(".single-permission").length){
                    $(".check-all").prop("checked", true);
                }
                else{
                    $(".check-all").prop("checked", false);
                }
            });

            // Handle type view click
            addForm.on("change", ".check-all-view", function() {
                if($(this).is(":checked")){
                    $(".view-permission").prop('checked', true);
                }
                else{
                    $(".view-permission").prop('checked', false);

                    // Uncheck create boxes also
                    $('.check-all-create').prop('checked', false);
                    $('.create-permission').prop('checked', false);

                    // Uncheck edit boxes also
                    $('.check-all-edit').prop('checked', false);
                    $('.edit-permission').prop('checked', false);

                    // Uncheck delete boxes also
                    $('.check-all-delete').prop('checked', false);
                    $('.delete-permission').prop('checked', false);
                }

                // Check module by type
                $(".single-permission").each(function(index, element){
                    var permissionModuleIndex = $(this).data('module-index');
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle module single view permission click
            addForm.on("change", ".view-permission", function() {
                var permissionModuleIndex = $(this).data('module-index');

                if($(".view-permission:checked").length < $(".view-permission").length){
                    $(".check-all-view").prop('checked', false);
                    
                    $(".check-all-create").prop('checked', false);
                    $(this).parents('tr').find('.create-permission').prop('checked', false);

                    $(".check-all-edit").prop('checked', false);
                    $(this).parents('tr').find('.edit-permission').prop('checked', false);

                    $(".check-all-delete").prop('checked', false);
                    $(this).parents('tr').find('.delete-permission').prop('checked', false);
                }
                else{
                    $(".check-all-view").prop('checked', true);
                }

                // Check module by type
                $(".permissions-"+permissionModuleIndex).each(function(index, element){
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle type create click
            addForm.on("change", ".check-all-create", function() {
                if($(this).is(":checked")){
                    $(".create-permission").prop('checked', true);
                    
                    // Check view boxes also
                    $('.check-all-view').prop('checked', true);
                    $('.view-permission').prop('checked', true);
                }
                else{
                    $(".create-permission").prop('checked', false);
                }

                // Check module by type
                $(".single-permission").each(function(index, element){
                    var permissionModuleIndex = $(this).data('module-index');
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle module single create permission click
            addForm.on("change", ".create-permission", function() {
                var permissionModuleIndex = $(this).data('module-index');

                if($(".create-permission:checked").length < $(".create-permission").length){
                    $(".check-all-create").prop('checked', false);
                }
                else{
                    $(".check-all-create").prop('checked', true);

                    // If Only create checkbox checked in single row
                    if($(".view-permission:checked").length == $(".view-permission").length){
                        $(".check-all-view").prop("checked", true);
                    }
                }

                // Check module by type
                $(".permissions-"+permissionModuleIndex).each(function(index, element){
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle type edit click
            addForm.on("change", ".check-all-edit", function() {
                if($(this).is(":checked")){
                    $(".edit-permission").prop('checked', true);

                    // Check view boxes also
                    $('.check-all-view').prop('checked', true);
                    $('.view-permission').prop('checked', true);
                }
                else{
                    $(".edit-permission").prop('checked', false);
                }

                // Check module by type
                $(".single-permission").each(function(index, element){
                    var permissionModuleIndex = $(this).data('module-index');
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle module single edit permission click
            addForm.on("change", ".edit-permission", function() {
                var permissionModuleIndex = $(this).data('module-index');

                if($(".edit-permission:checked").length < $(".edit-permission").length){
                    $(".check-all-edit").prop('checked', false);
                }
                else{
                    $(".check-all-edit").prop('checked', true);

                    // If Only edit checkbox checked in single row
                    if($(".view-permission:checked").length == $(".view-permission").length){
                        $(".check-all-view").prop("checked", true);
                    }
                }

                // Check module by type
                $(".permissions-"+permissionModuleIndex).each(function(index, element){
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle type delete click
            addForm.on("change", ".check-all-delete", function() {
                if($(this).is(":checked")){
                    $(".delete-permission").prop('checked', true);

                    // Check view boxes also
                    $('.check-all-view').prop('checked', true);
                    $('.view-permission').prop('checked', true);
                }
                else{
                    $(".delete-permission").prop('checked', false);
                }

                // Check module by type
                $(".single-permission").each(function(index, element){
                    var permissionModuleIndex = $(this).data('module-index');
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });

            // Handle module single delete permission click
            addForm.on("change", ".delete-permission", function() {
                var permissionModuleIndex = $(this).data('module-index');

                if($(".delete-permission:checked").length < $(".delete-permission").length){
                    $(".check-all-delete").prop('checked', false);
                }
                else{
                    $(".check-all-delete").prop('checked', true);

                    // If Only edit checkbox checked in single row
                    if($(".view-permission:checked").length == $(".view-permission").length){
                        $(".check-all-view").prop("checked", true);
                    }
                }

                // Check module by type
                $(".permissions-"+permissionModuleIndex).each(function(index, element){
                    
                    if($(".permissions-"+permissionModuleIndex+":checked").length < $(".permissions-"+permissionModuleIndex).length){
                        $("#check_"+permissionModuleIndex).prop('checked', false);
                    }
                    else{
                        $("#check_"+permissionModuleIndex).prop('checked', true);
                    }
                });
            });
        },
    };
})();

User.init();
