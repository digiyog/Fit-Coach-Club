/**
 * Contact Us Page Interactive JavaScript
 * Form validation, feedback alerts, and smooth micro-interactions
 */
document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('advaitaContactForm');
    const submitBtn = document.getElementById('contactSubmitButton');
    const successBanner = document.getElementById('contactFormSuccess');

    if (!contactForm) return;

    const fields = {
        name: {
            input: document.getElementById('formFullName'),
            error: document.getElementById('nameError'),
            validate: function (val) {
                if (!val.trim()) return 'Please enter your full name.';
                if (val.trim().length < 3) return 'Name must be at least 3 characters.';
                return '';
            }
        },
        phone: {
            input: document.getElementById('formPhoneNumber'),
            error: document.getElementById('phoneError'),
            validate: function (val) {
                const cleaned = val.replace(/\D/g, '');
                if (!cleaned) return 'Please enter your phone number.';
                if (cleaned.length < 10) return 'Please enter a valid 10-digit mobile number.';
                return '';
            }
        },
        email: {
            input: document.getElementById('formEmailAddr'),
            error: document.getElementById('emailError'),
            validate: function (val) {
                if (!val.trim()) return 'Please enter your email address.';
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(val.trim())) return 'Please enter a valid email address.';
                return '';
            }
        },
        message: {
            input: document.getElementById('formUserMessage'),
            error: document.getElementById('messageError'),
            validate: function (val) {
                if (!val.trim()) return 'Please enter your message.';
                if (val.trim().length < 10) return 'Message must be at least 10 characters long.';
                return '';
            }
        }
    };

    // Live validation on blur & input
    Object.keys(fields).forEach(key => {
        const item = fields[key];
        if (!item.input) return;

        item.input.addEventListener('blur', function () {
            const err = item.validate(item.input.value);
            showFieldError(item, err);
        });

        item.input.addEventListener('input', function () {
            if (item.input.classList.contains('has-error')) {
                const err = item.validate(item.input.value);
                showFieldError(item, err);
            }
        });
    });

    function showFieldError(item, err) {
        if (err) {
            item.input.classList.add('has-error');
            item.input.classList.remove('is-valid');
            if (item.error) {
                item.error.textContent = err;
                item.error.style.display = 'block';
            }
        } else {
            item.input.classList.remove('has-error');
            item.input.classList.add('is-valid');
            if (item.error) {
                item.error.textContent = '';
                item.error.style.display = 'none';
            }
        }
    }

    // Form submission
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        let isValid = true;
        Object.keys(fields).forEach(key => {
            const item = fields[key];
            if (!item.input) return;
            const err = item.validate(item.input.value);
            showFieldError(item, err);
            if (err) isValid = false;
        });

        if (!isValid) {
            // Focus first error field
            const firstError = contactForm.querySelector('.has-error');
            if (firstError) firstError.focus();
            return;
        }

        // Simulate Submission with Button Loading State
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Sending...</span>';

            // Real AJAX Submission to Laravel
            const formData = new FormData(contactForm);
            fetch(contactForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = originalText;

                if (data.success) {
                    if (successBanner) {
                        const successText = successBanner.querySelector('.success-info p');
                        if (successText && data.message) {
                            successText.textContent = data.message;
                        }
                        successBanner.style.display = 'flex';
                        successBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                    contactForm.reset();
                    Object.keys(fields).forEach(key => {
                        const item = fields[key];
                        if (item.input) {
                            item.input.classList.remove('is-valid', 'has-error');
                        }
                    });
                    setTimeout(function () {
                        if (successBanner) successBanner.style.display = 'none';
                    }, 8000);
                } else {
                    alert(data.message || 'Something went wrong. Please try again.');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = originalText;
                // Fallback to normal form submit if fetch fails
                contactForm.submit();
            });
        }
    });
});
