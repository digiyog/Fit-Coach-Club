/**
 * Advaita School of Excellence - Global SweetAlert2 Integration Helper
 * Replaces default browser confirm/alert dialogs with branded SweetAlert2 modals & toasts.
 */
(function() {
    'use strict';

    var BrandColors = {
        navy: '#083B7A',
        orange: '#F37021',
        danger: '#DC2626',
        success: '#10B981',
        slate: '#64748B',
        dark: '#0F172A'
    };

    var AppSwal = {
        /**
         * Generic Confirmation Modal
         */
        confirm: function(title, text, confirmBtnText, onConfirm, icon, confirmBtnColor, cancelBtnText) {
            if (typeof Swal === 'undefined') {
                if (window.confirm(title + '\n\n' + (text || ''))) {
                    if (typeof onConfirm === 'function') onConfirm();
                }
                return;
            }

            return Swal.fire({
                title: title || 'Are you sure?',
                html: text || 'Please confirm to continue.',
                icon: icon || 'question',
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor || BrandColors.navy,
                cancelButtonColor: BrandColors.slate,
                confirmButtonText: confirmBtnText || 'Yes, Proceed',
                cancelButtonText: cancelBtnText || 'Cancel',
                reverseButtons: true,
                focusCancel: false,
                customClass: {
                    popup: 'adv-swal-popup',
                    title: 'adv-swal-title',
                    htmlContainer: 'adv-swal-text',
                    confirmButton: 'adv-swal-confirm-btn',
                    cancelButton: 'adv-swal-cancel-btn'
                }
            }).then(function(result) {
                if (result.isConfirmed && typeof onConfirm === 'function') {
                    onConfirm();
                }
                return result;
            });
        },

        /**
         * Delete Confirmation Modal
         */
        confirmDelete: function(itemTitle, onConfirm) {
            var msg = itemTitle ? 'Are you sure you want to delete "' + itemTitle + '"?' : 'Are you sure you want to delete this item?';
            return this.confirm(
                'Confirm Deletion',
                msg + '<br><small class="adv-swal-danger-note">This action cannot be undone.</small>',
                '<i class="fa-solid fa-trash-can"></i> &nbsp;Yes, Delete',
                onConfirm,
                'warning',
                BrandColors.danger,
                'Keep It'
            );
        },

        /**
         * Toast Notification (Top Right)
         */
        toast: function(type, message, timerMs) {
            if (typeof Swal === 'undefined') {
                return;
            }

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: timerMs || 4000,
                timerProgressBar: true,
                didOpen: function(toast) {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
                customClass: {
                    popup: 'adv-swal-toast-popup'
                }
            });

            return Toast.fire({
                icon: type || 'info',
                title: message || ''
            });
        },

        /**
         * Standard Alert Dialog
         */
        alert: function(title, text, icon) {
            if (typeof Swal === 'undefined') {
                window.alert(title + (text ? '\n\n' + text : ''));
                return;
            }

            return Swal.fire({
                title: title,
                html: text,
                icon: icon || 'info',
                confirmButtonColor: BrandColors.navy,
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'adv-swal-popup',
                    title: 'adv-swal-title',
                    confirmButton: 'adv-swal-confirm-btn'
                }
            });
        }
    };

    // Attach to global window
    window.AppSwal = AppSwal;

    /**
     * Intercept and manage form submissions with deletion or data-confirm
     */
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Delegated listener for forms with delete or data-confirm
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            // If already confirmed through SweetAlert, allow submit
            if (form.getAttribute('data-swal-confirmed') === 'true') {
                return;
            }

            var confirmMsg = form.getAttribute('data-confirm');
            var confirmTitle = form.getAttribute('data-confirm-title');
            var isDelete = form.querySelector('input[name="_method"][value="DELETE"]') ||
                           (form.getAttribute('action') && form.getAttribute('action').indexOf('destroy') !== -1) ||
                           form.classList.contains('delete-form');

            if (confirmMsg || isDelete) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var title = confirmTitle || (isDelete ? 'Confirm Deletion' : 'Are you sure?');
                var text = confirmMsg || (isDelete ? 'This record will be permanently deleted. This action cannot be undone.' : 'Do you want to proceed?');
                var btnColor = isDelete ? BrandColors.danger : BrandColors.navy;
                var btnText = isDelete ? '<i class="fa-solid fa-trash-can"></i> &nbsp;Yes, Delete' : 'Yes, Proceed';
                var iconType = isDelete ? 'warning' : 'question';

                AppSwal.confirm(title, text, btnText, function() {
                    form.setAttribute('data-swal-confirmed', 'true');
                    form.submit();
                }, iconType, btnColor);
            }
        }, true); // Use capture phase so inline onsubmit can be intercepted

        // 2. Flash Message Triggers (Auto display session toast or alert)
        var flashSuccess = document.querySelector('[data-swal-success]');
        if (flashSuccess) {
            var successMsg = flashSuccess.getAttribute('data-swal-success');
            if (successMsg) {
                AppSwal.toast('success', successMsg);
            }
        }

        var flashError = document.querySelector('[data-swal-error]');
        if (flashError) {
            var errorMsg = flashError.getAttribute('data-swal-error');
            if (errorMsg) {
                AppSwal.toast('error', errorMsg);
            }
        }
    });

})();
