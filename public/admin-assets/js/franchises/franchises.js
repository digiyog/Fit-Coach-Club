var Franchise = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            Franchise.validateFranchiseForm();
            Franchise.initializeComponents();
            Franchise.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $('.franchise-form');
            // Image preview
            Components.imagePreview($form);
            //--------------

            // // Description Editor
            // Components.descriptionEditor($form);
            // //-------------------

            // Bootstrap Select
            Components.bootstrapSelect($form);
            //-------------------

            // Additional form validation methods
            Components.additionalValidationMethods($form);
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
         * Validate Franchise form.
         */
        validateFranchiseForm: function() {
            var $form = $(".franchise-form");
            $form.validate({
                ignore: "input[type='text']:hidden, .note-editor *",
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    image: {
                        required: {
                            depends: function () {
                                return ($('input[name=image_name]').val() == '');
                            }
                        },
                        // accept: "image/jpg,image/jpeg,image/png,image/gif,image/webp"
                    },
                    name: {
                        required: true,
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
                    },
                    order: {
                        required: true,
                        numericOnly: true,
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
                    image:{
                        required: "This field is required.",
                        // accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    },
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
                    },
                    order: {
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
                    else if($(element).hasClass('image-preview'))
                    {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else if($(element).hasClass('select-picker'))
                    {
                        error.appendTo($(element).parent().parent());
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
    };
})();

Franchise.init();