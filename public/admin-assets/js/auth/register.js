var Register = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            Register.validateForm();
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

            $(".register-form").validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    name: {
                        required: true,
                        lettersOnly: true,
                    },
                    city: {
                        required: true,
                        lettersOnly: true,
                    },
                    email: {
                        required: true,
                        email: true,
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
                        required: true
                    }
                },
                //------------------

                // @validation error messages
                messages: {
                    name: {
                        required: "Please enter name."
                    },
                    city: {
                        required: "Please enter city."
                    },
                    mobile_number: {
                        required: "Please enter mobile number.",
                        remote:   "The mobile number you have entered is already registered.",
                    },
                    email: {
                        required: "Please enter email.",
                        remote:   "The email address you have entered is already registered.",
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

Register.init();
