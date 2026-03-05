var User = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            User.validateForm();
            User.validateChangePasswordForm();
            User.customValidationMethods();
            User.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $('.update-profile-form');

            // Image preview
            Components.imagePreview($form);
            //--------------

            // Bootstrap Select
            Components.bootstrapSelect($form);
            //--------------

            // Date picker
            Components.datePicker($form);
            //------------------
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
        },

        /**
         * Validate language form.
         */
        validateForm: function() {
            var $form = $(".update-profile-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    profile_image: {
                        // accept: 'image/jpg,image/jpeg,image/png,image/gif'
                    },
                    name: {
                        required: true,
                    },
                    mobile_number: {
                        required: true,
                        mobileNumber: true,
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
                    timezone:{
                        required: true
                    }
                },
                //------------------

                // @validation error messages
                messages: {
                    profile_image: {
                        // accept: 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.',
                    },
                    name: {
                        required: "This field is required.",
                    },
                    mobile_number: {
                        required: "This field is required.",
                        remote: "Mobile number already regitered.",
                    },
                    email: {
                        required: "This field is required.",
                        remote: "Email already regitered.",
                    },
                    timezone:{
                        required: "This field is required.",
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
                errorPlacement: function(error, element) {
                    if($(element).hasClass('select-picker'))
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else if($(element).hasClass('custom-file-input'))
                    {
                        error.appendTo($(element).parent().parent().parent());
                    }
                    else if($(element).hasClass('image-preview'))
                    {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else if($(element).attr('id') == 'mobile_number')
                    {
                        error.appendTo($(element).parent().parent());
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    App.formLoading($form);
                    form.submit();
                }
            });
        },

        /**
         * Validate profile form.
         */
        validateChangePasswordForm: function () {
            var $form = $('.change-password-form');

            $form.validate({

            // @validation states + elements
            errorClass: 'invalid-feedback',
            errorElement: 'span',
            //------------------------------

            // @validation rules 
            rules: {
                current_password: {
                    required: true
                },
                new_password: {
                    required: true,
                    passwordChecker: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            //------------------

            // @validation error messages 
            messages: {
                current_password: {
                    required: 'Please enter current password.',
                },
                new_password: {
                    required: 'Please enter new password.',
                },
                confirm_password: {
                    required: 'Please enter confirm password.',
                    equalTo: 'Your password and confirmation password do not match.',
                }
            },
            //---------------------------

            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass('has-danger').removeClass('has-success');
                $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass('has-success').removeClass('has-danger');
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            submitHandler: function (form) {
                App.formLoading($form);
                form.submit();
            }
            });
        },
    };
})();

User.init();
