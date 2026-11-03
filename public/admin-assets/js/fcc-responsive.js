/**
 * FitCoachClub Responsive Shell & Mobile Navigation Controller
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Mobile Close Button on Sidebar Brand
        $(document).on('click', '#fccMobileCloseSidebar', function(e) {
            e.preventDefault();
            $('#container').removeClass('sbar-open').addClass('sidebar-closed');
            $('.overlay').removeClass('show');
            $('html, body').removeClass('sidebar-noneoverflow');
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
    });
})(jQuery);

