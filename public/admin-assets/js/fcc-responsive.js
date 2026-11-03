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
    });
})(jQuery);

