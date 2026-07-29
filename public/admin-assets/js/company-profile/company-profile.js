var CompanyProfile = (function () {
    return {
        /**
         * Initialization.
         */
        init: function () {
            CompanyProfile.validateCompanyProfileForm();
            CompanyProfile.initializeComponents();
            CompanyProfile.customValidationMethods();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function () {
            var $form = $(".company-profile-form");

            // Image preview
            Components.imagePreview($form);
            //--------------
           
            // Description Editor
            Components.descriptionEditor($form);
            //-------------------
            // Remove image
            Components.removeImage($form);
            //-------------
 
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
                        /^[a-zA-Z][a-zA-Z ]+$/i.test(value)
                    );
                },
                "Please enter only alphabets."
            );

            jQuery.validator.addMethod(
                "emailChecker",
                function (value, element) {
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
                "mobileChecker",
                function (value, element) {
                    if (value) {
                        return (
                            this.optional(element) ||
                            /^[\+[0-9 ]{1}[0-9 ]{2,130}]*$/i.test(value)
                        );
                    } else {
                        return true;
                    }
                },
                "Please enter a valid mobile number."
            );

           
        },

        /**
         * Validate user form.
         */
        validateCompanyProfileForm: function () {
            var $form = $(".company-profile-form");

            $form.validate({
                 ignore: "input[type='text']:hidden, .note-editor *",
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
                    email: {
                        required: true,
                        emailChecker: true,
                    },
                    phone_no: {
                        required: true,
                        mobileChecker: true,
                    },
                    whatsapp_no: {
                        mobileChecker: true,
                    },
                    address: {
                        required: true,
                    },
                    facebook_link: {
                        required: false,  
                    }, 
                    twitter_link: {
                        required: false,  
                    },
                    instagram_link: {
                        required: false,  
                    },
                    linkdin_link: {
                        required: false,  
                    },
                    header_logo_image: {
                        required: {
                            depends: function () {
                                return ($('input[name=header_image]').val() == '');
                            }
                        },
                        // accept: "image/jpg,image/jpeg,image/png,image/gif,image/webp"
                    },
                    fab_icon_image: {
                        required: {
                            depends: function () {
                                return ($('input[name=footer_image]').val() == '');
                            }
                        },
                        // accept: "image/jpg,image/jpeg,image/png,image/gif,image/webp"
                    },
                    footer_logo_image: {
                        required: {
                            depends: function () {
                                return ($('input[name=fab_name]').val() == '');
                            }
                        },
                        // accept: "image/jpg,image/jpeg,image/png,image/gif,image/webp"
                    },
                },
                //------------------

                // @validation error messages
                messages: {
                    name: {
                        required: "Please enter name.",
                    },
                    email: {
                        required: "Please enter email.",
                    },
                    phone_no: {
                        required: "Please enter mobile number.",
                    },
                    address: {
                        required: "Please enter address.",
                    },
                    facebook_link: {
                        required: "Please enter facebook Link.",
                    },
                    twitter_link: {
                        required: "Please enter Twitter Link.",
                    },
                    instagram_link: {
                        required: "Please enter Instagram Link.",
                    },
                    linkdin_link: {
                        required: "Please enter Linkdin Link.",
                    },
                    header_logo_image: {
                        required: "Please choose logo.",
                        accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.",
                    },
                    fab_icon_image: {
                        required: "Please choose icon.",
                        accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.",
                    },
                    footer_logo_image: {
                        required: "Please choose footer logo.",
                        accept: "Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.",
                    },
                },
                //---------------------------

                highlight: function (element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-danger")
                        .removeClass("has-success");
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element)
                        .closest(".form-group")
                        .addClass("has-success")
                        .removeClass("has-danger");
                    $(element).addClass("is-valid").removeClass("is-invalid");
                },
                submitHandler: function (form) {
                    App.formLoading($form);
                    form.submit();
                },
            });
        },
    };
})();

CompanyProfile.init();
