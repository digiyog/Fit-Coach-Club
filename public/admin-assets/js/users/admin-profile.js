var User = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            User.initializeComponents();
            User.customValidationMethods();
            User.validateForm();
            User.validateChangePasswordForm();

            // Handle Top Save Changes button click
            $(document).on("click", "#btn_save_top", function(e) {
                e.preventDefault();
                $(".update-profile-form").submit();
            });

            // Handle password show / hide toggle
            $(document).on("click", ".toggle-password", function(e) {
                e.preventDefault();
                var targetSelector = $(this).data("target");
                var $input = $(targetSelector);
                var $icon = $(this).find("i");

                if ($input.length) {
                    if ($input.attr("type") === "password") {
                        $input.attr("type", "text");
                        $icon.removeClass("fa-eye").addClass("fa-eye-slash");
                    } else {
                        $input.attr("type", "password");
                        $icon.removeClass("fa-eye-slash").addClass("fa-eye");
                    }
                }
            });
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $('.update-profile-form');

            // Image preview with dropify
            if (typeof Components !== "undefined" && Components.imagePreview) {
                Components.imagePreview($form);
            } else if ($.fn.dropify) {
                $form.find('.image-preview').dropify({
                    allowedFileExtensions: "jpg jpeg png gif svg webp",
                    showRemove: false
                });
            }
        },

        /**
         * Custom validation methods.
         */
        customValidationMethods: function() {
            if (typeof jQuery.validator === "undefined") {
                return;
            }

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
                        /^\d{8,14}$/i.test(value)
                    );
                },
                "Please enter a valid mobile number."
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
                        /^(?!.*\s)(?=.*).{6,12}$/.test(
                            value
                        )
                    );
                },
                "Password must be 6 to 12 characters."
            );
        },

        /**
         * Validate profile form.
         */
        validateForm: function() {
            var $form = $(".update-profile-form");
            if (!$form.length || typeof $form.validate === "undefined") {
                return;
            }

            $form.validate({
                errorClass: "invalid-feedback",
                errorElement: "span",
                rules: {
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
                    }
                },
                messages: {
                    name: {
                        required: "Name is required.",
                    },
                    mobile_number: {
                        required: "Mobile number is required.",
                        remote: "Mobile number already registered.",
                    },
                    email: {
                        required: "Email address is required.",
                        remote: "Email address already registered.",
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group-custom, .form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element)
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group-custom, .form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element)
                        .addClass("is-valid")
                        .removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    if ($(element).hasClass('image-preview')) {
                        error.appendTo($(element).parents('.dropify-wrapper').parent());
                    } else if ($(element).closest('.input-with-icon').length) {
                        error.insertAfter($(element).closest('.input-with-icon'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    if (typeof App !== "undefined" && App.formLoading) {
                        App.formLoading($form);
                    }
                    form.submit();
                }
            });
        },

        /**
         * Validate change password form (if used on separate page).
         */
        validateChangePasswordForm: function () {
            var $form = $('.change-password-form');
            if (!$form.length || typeof $form.validate === "undefined") {
                return;
            }

            $form.validate({
                errorClass: 'invalid-feedback',
                errorElement: 'span',
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
                highlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group, .form-group-custom').addClass('has-danger').removeClass('has-success');
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group, .form-group-custom').addClass('has-success').removeClass('has-danger');
                    $(element).addClass('is-valid').removeClass('is-invalid');
                },
                errorPlacement: function (error, element) {
                    var $parentGroup = $(element).closest('.form-group-custom, .form-group');
                    var $hint = $parentGroup.find('.field-hint');
                    if ($hint.length) {
                        error.insertAfter($hint);
                    } else if ($(element).closest('.input-with-icon').length) {
                        error.insertAfter($(element).closest('.input-with-icon'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form) {
                    if (typeof App !== "undefined" && App.formLoading) {
                        App.formLoading($form);
                    }
                    form.submit();
                }
            });
        },
    };
})();

$(document).ready(function() {
    User.init();
});
