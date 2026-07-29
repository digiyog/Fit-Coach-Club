var App = (function() {
    return {
        /**
         * Initialization.
         */
        init: function() {
            App.setupAjax();
        },

        /**
         * Setup AJAX.
         */
        setupAjax: function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });
        },

        /**
         * Show notification.
         *
         * @param {*} notification
         */
        showNotification: function(notification) {
            iziToast[notification._type]({
                title: notification._type.toUpperCase(),
                message: notification._message,
                position: "topRight",
                timeout: 6000,
                transitionIn: "fadeInLeft"
            });
        },

        /**
         * Form loading.
         */
        formLoading: function($form) {
            var $btn_submit = $form.find(".btn-submit");

            $btn_submit
                .find(".fa")
                .removeClass("fa-save")
                .addClass("fa-refresh fa-spin");
            $btn_submit.prop("disabled", true);
        },

        /**
         * Stop form loading.
         */
        stopFormLoading: function($form) {
            var $btn_submit = $form.find(".btn-submit");

            $btn_submit
                .find(".fa")
                .removeClass("fa-refresh fa-spin")
                .addClass("fa-save");
            $btn_submit.prop("disabled", false);
        },

        /**
         * Reset modal.
         */
        resetModal: function($modal) {
            var html = '<div class="loading">Loading...</div>';
            $modal.find(".modal-content").html(html);
        },

        /**
         * Array Column.
         */
        arrayColumn: function(array, columnName) {
            return array.map(function(value, index) {
                return value[columnName];
            });
        }
    };
})();

App.init();
