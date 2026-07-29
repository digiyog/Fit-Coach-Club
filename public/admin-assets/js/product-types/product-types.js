var ProductType = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            ProductType.validateProductTypeForm();
            ProductType.initializeComponents();
            ProductType.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $('.product-type-form');
            // Image preview
            Components.imagePreview($form);
            //--------------

            // Date Picker
            Components.datePicker($form);
            //-------------------

            // Bootstrap Select
            Components.bootstrapSelect($form);
            //-------------------

            // Description Editor
            Components.descriptionEditor($form);
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
         * Validate Product Type form.
         */
        validateProductTypeForm: function() {
            var $form = $(".product-type-form");

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
                submitHandler: function(form) {
                    App.formLoading($form);
                    form.submit();
                }
            });
        },
    };
})();

ProductType.init();