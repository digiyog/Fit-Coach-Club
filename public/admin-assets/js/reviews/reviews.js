var Review = (function () {
    return {
        /**
         * Initialization.
         */
        init: function () {
            Review.validateReviewForm();
            Review.initializeComponents();
            Review.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function () {
            var $form = $('.review-form');
            // Image preview
            Components.imagePreview($form);
            //--------------

            // Description Editor
            Components.descriptionEditor($form);
            //-------------------

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
        customValidationMethods: function () {
            jQuery.validator.addMethod(
                "lettersOnly",
                function (value, element) {
                    return (
                        this.optional(element) ||
                        /^[a-zA-Z&][a-zA-Z& ]+$/i.test(value)
                    );
                },
                "Please enter only alphabets."
            );

            jQuery.validator.addMethod(
                "numericOnly",
                function (value, element) {
                    return (
                        this.optional(element) ||
                        /^[0-9]\d{0,1}(\.\d{1,2})?%?$/i.test(value)
                    );
                },
                "Please enter valid order number."
            );

            jQuery.validator.addMethod(
                "uppercaseOnly",
                function (value, element) {
                    return (
                        this.optional(element) ||
                        /^[A-Z]+$/g.test(value)
                    );
                },
                "Please enter only capital letters."
            );
        },

        /**
         * Validate Review form.
         */
        validateReviewForm: function () {
            var $form = $(".review-form");
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
                    arabic_image: {
                        required: {
                            depends: function () {
                                return ($('input[name=arabic_image_name]').val() == '');
                            }
                        },
                        // accept: "image/jpg,image/jpeg,image/png,image/gif,image/webp"
                    },
                    title: {
                        required: true,
                        lettersOnly: true,
                    },
                    language_id: {
                        required: true,

                    },
                    date: {
                        required: true,
                    },
                    short_description: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    arabic_title: {
                        required: true,
                    },
                    arabic_short_description: {
                        required: true,
                    },
                    arabic_description: {
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
                    image: {
                        required: "This field is required.",
                        // accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    },
                    arabic_image: {
                        required: "This field is required.",
                        // accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    },
                    title: {
                        required: "Please enter title.",
                    },
                    language_id: {
                        required: "Please select language.",
                    },
                    date: {
                        required: "Please select date.",
                    },
                    short_description: {
                        required: "Please enter short description.",
                    },
                    description: {
                        required: "Please enter description.",
                    },
                    arabic_title: {
                        required: "Please enter arabic title.",
                    },
                    arabic_short_description: {
                        required: "Please enter arabic short description.",
                    },
                    arabic_description: {
                        required: "Please enter arabic description.",
                    },

                },
                //---------------------------

                highlight: function (element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                errorPlacement: function (error, element) {
                    if ($(element).hasClass('custom-file-input')) {
                        error.appendTo($(element).parents('.input-group').parent());
                    }
                    else if ($(element).hasClass('image-preview')) {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    }
                    else if ($(element).hasClass('select-picker')) {
                        error.appendTo($(element).parent().parent());
                    }
                    else if ($(element).attr('id') == 'mobile_number') {
                        error.appendTo($(element).parent().parent());
                    }
                    else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form) {
                    App.formLoading($form);
                    form.submit();
                }
            });
        },
    };
})();

Review.init();