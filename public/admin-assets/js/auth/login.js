var Login = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            Login.validateForm();
        },

        /**
         * Validate form.
         */
        validateForm: function() {
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

            $(".login-form").validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    email: {
                        required: true,
                        email: true,
                        emailChecker: true
                    },
                    password: {
                        required: true
                    }
                },
                //------------------

                // @validation error messages
                messages: {
                    email: {
                        required: "Please enter email."
                    },
                    password: {
                        required: "Please enter password."
                    }
                },
                //---------------------------

                // @validation highlighting + error placement
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
                }
                //-------------------------------------------
            });
        }
    };
})();

Login.init();
