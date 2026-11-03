/**
 * FitCoachClub Responsive Shell & Mobile Navigation Controller
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Close / Collapse Button on Sidebar Brand
        $(document).on('click', '#fccSidebarCloseBtn, #fccMobileCloseSidebar', function(e) {
            e.preventDefault();
            if (window.innerWidth < 992) {
                $('#container').removeClass('sbar-open').addClass('sidebar-closed');
                $('.overlay').removeClass('show');
                $('html, body').removeClass('sidebar-noneoverflow');
            } else {
                if ($('.sidebarCollapse').length) {
                    $('.sidebarCollapse').first().trigger('click');
                } else {
                    $('#container').toggleClass('sidebar-closed');
                }
            }
        });

        // Overlay Click Closes Drawer
        $(document).on('click', '.overlay', function() {
            $('#container').removeClass('sbar-open').addClass('sidebar-closed');
            $('.overlay').removeClass('show');
            $('html, body').removeClass('sidebar-noneoverflow');
        });

        // Close sidebar on link click when on mobile viewport
        $(document).on('click', '#sidebar a', function() {
            if (window.innerWidth < 992) {
                var href = $(this).attr('href');
                if (href && href !== 'javascript:void(0);' && !href.startsWith('#')) {
                    $('#container').removeClass('sbar-open').addClass('sidebar-closed');
                    $('.overlay').removeClass('show');
                    $('html, body').removeClass('sidebar-noneoverflow');
                }
            }
        });

        // Re-run feather icons replace if available
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Global Dynamic Dropdown & Select Stacking Handler
        // Ensures that any card or container containing an open dropdown is brought to the absolute front
        $(document).on('show.bs.select shown.bs.select show.bs.dropdown shown.bs.dropdown', function(e) {
            var $target = $(e.target);
            var $card = $target.closest('.fcc-card, .fcc-create-card, .widget, .widget-content, .card');
            if ($card.length) {
                $card.addClass('has-dropdown-open').css('z-index', 1200);
            }
            var $field = $target.closest('.fcc-form-field, .fcc-input-wrap, .fcc-select-wrap, tr');
            if ($field.length) {
                $field.addClass('has-dropdown-open').css('z-index', 1200);
            }
        });

        $(document).on('hide.bs.select hidden.bs.select hide.bs.dropdown hidden.bs.dropdown', function(e) {
            var $target = $(e.target);
            var $card = $target.closest('.fcc-card, .fcc-create-card, .widget, .widget-content, .card');
            if ($card.length) {
                $card.removeClass('has-dropdown-open').css('z-index', '');
            }
            var $field = $target.closest('.fcc-form-field, .fcc-input-wrap, .fcc-select-wrap, tr');
            if ($field.length) {
                $field.removeClass('has-dropdown-open').css('z-index', '');
            }
        });
    });
})(jQuery);

