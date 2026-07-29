var CalculateCalorie = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            CalculateCalorie.validateForm();
            CalculateCalorie.customValidationMethods();
            CalculateCalorie.initializeComponents();
        },

        /**
         * Initialize components.
         */
        initializeComponents: function() {
            var $form = $('.calculate-calories-form');

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
            var $form = $(".calculate-calories-form");

            $form.validate({
                // @validation states + elements
                errorClass: "invalid-feedback",
                errorElement: "span",
                //------------------------------

                // @validation rules
                rules: {
                    weight: {
                        required: true,
                    },
                    height:{
                        required: true
                    },
                    age:{
                        required: true
                    },
                    gender:{
                        required: true
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
                submitHandler: function(form, event) {
                    event.preventDefault();
                    // App.formLoading($form);
                    // form.submit();

                      const age = Number(document.getElementById('age').value) || 0;
                      const gender = document.getElementById('gender').value;
                      const weight = Number(document.getElementById('weight').value) || 0;
                      const height = Number(document.getElementById('height').value) || 0;

                      // 🔸 Validate inputs
                      if (age <= 0 || weight <= 0 || height <= 0) {
                        alert('Please enter valid Age, Weight, and Height.');
                        return;
                      }

                      // 🔹 BMI Calculation
                      const heightM = height / 100; // cm → m
                      const bmi = weight / (heightM * heightM);
                      let bmiCategory = '';
                      if (bmi < 18.5) bmiCategory = 'Underweight';
                      else if (bmi < 25) bmiCategory = 'Normal weight';
                      else if (bmi < 30) bmiCategory = 'Overweight';
                      else bmiCategory = 'Obese';

                      // 🔹 BMR Calculation (Mifflin–St Jeor)
                      // Men: 10*w + 6.25*h - 5*age + 5
                      // Women: 10*w + 6.25*h - 5*age -161
                      const bmr = gender === 2
                        ? (10 * weight + 6.25 * height - 5 * age - 161)
                        : (10 * weight + 6.25 * height - 5 * age + 5);

                    // 🔸 Show Results
                    document.getElementById('body_mass').textContent = bmi.toFixed(1);
                    document.getElementById('weight_calculate').textContent = bmiCategory;
                    document.getElementById('calaroie_take').textContent = Math.round(bmr) + ' kcal/day';

                    $('#responseHide').hide();
                    $('#responseShow').removeClass('d-none');
                }
            });
        }
    };
})();

CalculateCalorie.init();