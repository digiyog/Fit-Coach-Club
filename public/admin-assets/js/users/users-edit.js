var Users = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            Users.validateEditUserForm();
            Users.initializeComponents();
            Users.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $('.edit-user-form');
            // Image preview
            Components.imagePreview($form);
            //--------------

            // Description Editor
            // Components.descriptionEditor($form);
            //-------------------

            // Additional form validation methods
            Components.additionalValidationMethods();
            //----------
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
                function (value, element) {
                    if (value) {
                        return (
                            this.optional(element) ||
                            /^[\+[0-9]{0}[0-9]{1,5}]*$/i.test(value)
                        );
                    } else {
                        return true;
                    }
                },
                "Please enter valid order number."
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
         * Validate user form.
         */
        validateEditUserForm: function() {
            var $form = $(".edit-user-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    // profile_image: {
                    //     required: {
                    //         depends: function () {
                    //             return ($('input[name=image_name]').val() == '');
                    //         }
                    //     },
                    //     // accept: "image/jpg,image/jpeg,image/png,image/gif,image/webp"
                    // },
                    name: {
                        required: false,
                    },
                    email: {
                        required: true,
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
                    confirm_pass: {
                        password: true
                    }
                },
                //------------------

                // @validation error messages
                messages: {
                    // profile_image:{
                    //     required: "This field is required.",
                    //     // accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    // },
                    name: {
                        required: "This field is required.",
                    },
                    email: {
                        required: "This field is required.",
                        remote:   "The email address you have entered is already registered.",
                    },
                    mobile_number: {
                        required: "This field is required.",
                        remote:   "The mobile number you have entered is already registered.",
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
    };
})();

Users.init();