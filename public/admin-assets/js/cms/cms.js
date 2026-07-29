var Languages = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            Languages.validateLanguageForm();
            Languages.initializeComponents();
            Languages.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $(".add-cms-form");

            // Image preview
            Components.imagePreview($form);
            //--------------

            //--------------
            // Description Editor
            Components.descriptionEditor($form);
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
                        /^[0-9]\d{0,1}(\.\d{1,5})?%?$/i.test(value)
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
        },

        /**
         * Validate language form.
         */
        validateLanguageForm: function() {
            var $form = $(".add-cms-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    // image: {
                    //     required: true,
                    //     accept: "image/jpg,image/jpeg,image/png,image/gif",
                    //     imageSizeCheck: true,
                    // },
                    // title: {
                    //     required: true,
                    // },
                    // sub_title: {
                    //     required: true,
                    // },
                    // page_type: {
                    //     required: true,
                    // },
                    // description: {
                    //     required: true,
                    // }
                    order:{
                       numericOnly: true,
                    }
                },
                //------------------

                // @validation error messages
                messages: {
                    // image:{
                    //     required: "This field is required.",
                    //     accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed."
                    // },
                    // title: {
                    //     required: "This field is required.",
                    // },
                    // sub_title: {
                    //     required: "This field is required.",
                    // },
                    // page_type: {
                    //     required: "This field is required.",
                    // },
                    // description: {
                    //     required: "This field is required.",
                    // }
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

Languages.init();

$("#code").on('keyup', function(){
    $(this).val($(this).val().toUpperCase());
});
