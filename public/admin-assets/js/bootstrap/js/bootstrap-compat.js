/*!
 * Bootstrap 4 -> 5 jQuery compatibility layer
 *
 * Re-implements the Bootstrap 4 jQuery plugin interface (jQuery.fn.modal,
 * jQuery.fn.tooltip, jQuery.fn.popover, etc.) on top of the Bootstrap 5
 * vanilla JavaScript API so existing code such as $("#modal").modal("show")
 * keeps working after the upgrade to Bootstrap 5.
 *
 * Bootstrap 5.3.8
 */
(function (factory) {
    if (typeof define === "function" && define.amd) {
        define(["jquery", "bootstrap"], factory);
    } else {
        factory(window.jQuery, window.bootstrap);
    }
})(function ($, bootstrap) {
    "use strict";

    if (!$ || !bootstrap || !$.fn) {
        return;
    }

    var COMPONENTS = {
        Alert: ["close", "dispose"],
        Button: ["toggle", "dispose"],
        Carousel: ["cycle", "pause", "prev", "next", "nextWhenVisible", "slideTo", "dispose"],
        Collapse: ["toggle", "show", "hide", "dispose"],
        Dropdown: ["toggle", "show", "hide", "update", "dispose"],
        Modal: ["toggle", "show", "hide", "handleUpdate", "dispose"],
        Offcanvas: ["toggle", "show", "hide", "dispose"],
        Popover: ["enable", "disable", "toggleEnabled", "toggle", "show", "hide", "update", "dispose"],
        ScrollSpy: ["refresh", "dispose"],
        Tab: ["show", "dispose"],
        Toast: ["show", "hide", "dispose"],
        Tooltip: ["enable", "disable", "toggleEnabled", "toggle", "show", "hide", "update", "dispose"]
    };

    $.each(COMPONENTS, function (name, callableMethods) {
        var Component = bootstrap[name];

        if (!Component) {
            return;
        }

        var plugin = function (arg1 /* , ...args */) {
            var method = typeof arg1 === "string" ? arg1 : null;
            var config = method ? null : arg1;
            var args = Array.prototype.slice.call(arguments, method ? 1 : 0);

            return this.each(function () {
                var element = this;
                var instance;

                if (name === "Dropdown" && (element.classList.contains("selectpicker") || closestByClass(element, "bootstrap-select"))) {
                    return;
                }

                if (method) {
                    instance = Component.getInstance ? Component.getInstance(element) : null;

                    if (instance) {
                        instance[method].apply(instance, args);
                    } else if ($.inArray(method, callableMethods) !== -1) {
                        instance = new Component(element, config);
                        instance[method].apply(instance, args);
                    }

                    return;
                }

                new Component(element, config);
            });
        };

        plugin.Constructor = Component;
        $.fn[name] = plugin;
        $.fn[name.toLowerCase()] = plugin;
    });

    /* ------------------------------------------------------------------
     * Bootstrap 4 data-attribute auto-init layer.
     *
     * Third-party plugins (bootstrap-select 1.12.x, summernote bs4, ...)
     * emit Bootstrap 4 markup at runtime: data-toggle="dropdown",
     * data-toggle="button", data-dismiss="modal", data-toggle="tooltip",
     * etc. Bootstrap 5 ignores these attributes, so we replicate the
     * Bootstrap 4 data API by routing them through the Bootstrap 5
     * vanilla API. Elements that already use data-bs-* are left alone.
     * ------------------------------------------------------------------ */

    function hasBsToggle(element) {
        var attrs = element.attributes;
        for (var i = 0; i < attrs.length; i++) {
            if (/^data-bs-toggle$/.test(attrs[i].name)) {
                return true;
            }
        }
        return false;
    }

    function getTarget(element) {
        var target = element.getAttribute("data-target");
        if (target) {
            return target;
        }
        var href = element.getAttribute("href");
        if (href && href.charAt(0) === "#") {
            return href;
        }
        return null;
    }

    function closestByClass(element, className) {
        while (element && element !== document && element.nodeType === 1) {
            if (element.classList && element.classList.contains(className)) {
                return element;
            }
            element = element.parentNode;
        }
        return null;
    }

    $(document).on("click.bs4compat", "[data-toggle='dropdown']", function (e) {
        if (hasBsToggle(this)) {
            return;
        }
        // Exclude bootstrap-select dropdown toggle buttons to prevent Bootstrap 5 Popper conflict
        if (this.classList.contains("selectpicker") || closestByClass(this, "bootstrap-select")) {
            return;
        }
        e.preventDefault();
        bootstrap.Dropdown.getOrCreateInstance(this).toggle();
    });

    /* ------------------------------------------------------------------
     * Dedicated Bootstrap-Select Toggle & Dismiss for Bootstrap 5
     * ------------------------------------------------------------------ */
    $(document).on("click", ".bootstrap-select > .dropdown-toggle", function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var $select = $btn.closest(".bootstrap-select");
        var $menu = $select.find("> .dropdown-menu");
        var isOpen = $select.hasClass("show") || $select.hasClass("open");

        // Close any other open bootstrap-select instances
        $(".bootstrap-select.show, .bootstrap-select.open").not($select).each(function () {
            var $other = $(this);
            $other.removeClass("show open");
            $other.find("> .dropdown-menu").removeClass("show open");
            $other.trigger("hide.bs.dropdown").trigger("hidden.bs.dropdown");
            $other.find("select").trigger("hidden.bs.select");
        });

        if (isOpen) {
            $select.removeClass("show open");
            $menu.removeClass("show open");
            $select.trigger("hide.bs.dropdown").trigger("hidden.bs.dropdown");
            $select.find("select").trigger("hidden.bs.select");
        } else {
            $select.addClass("show open");
            $menu.addClass("show open");
            $select.trigger("show.bs.dropdown").trigger("shown.bs.dropdown");
            $select.find("select").trigger("shown.bs.select");

            var $searchInput = $menu.find(".bs-searchbox input");
            if ($searchInput.length) {
                setTimeout(function () {
                    $searchInput.focus();
                }, 50);
            }
        }
    });

    // Close bootstrap-select on click outside or after selecting a single option
    $(document).on("click", function (e) {
        var $target = $(e.target);

        // Keep open if interacting with searchbox, actionsbox, or donebutton
        if ($target.closest(".bootstrap-select .bs-searchbox, .bootstrap-select .bs-actionsbox").length) {
            return;
        }

        // For multiple selects, keep open unless clicking outside the component
        var $bs = $target.closest(".bootstrap-select");
        if ($bs.length) {
            var isMultiple = $bs.find("select").prop("multiple");
            if (isMultiple && !$target.closest(".bs-donebutton").length) {
                return;
            }
        }

        // Close all open bootstrap-select dropdowns
        $(".bootstrap-select.show, .bootstrap-select.open").each(function () {
            var $s = $(this);
            $s.removeClass("show open");
            $s.find("> .dropdown-menu").removeClass("show open");
            $s.trigger("hide.bs.dropdown").trigger("hidden.bs.dropdown");
            $s.find("select").trigger("hidden.bs.select");
        });
    });

    // Close bootstrap-select on Escape key
    $(document).on("keydown", function (e) {
        if (e.which === 27) { // Escape
            $(".bootstrap-select.show, .bootstrap-select.open").each(function () {
                var $s = $(this);
                $s.removeClass("show open");
                $s.find("> .dropdown-menu").removeClass("show open");
                $s.trigger("hide.bs.dropdown").trigger("hidden.bs.dropdown");
                $s.find("select").trigger("hidden.bs.select");
            });
        }
    });

    $(document).on("click.bs4compat", "[data-toggle='button']", function () {
        if (hasBsToggle(this)) {
            return;
        }
        bootstrap.Button.getOrCreateInstance(this).toggle();
    });

    $(document).on("click.bs4compat", "[data-dismiss='modal']", function () {
        if (hasBsToggle(this)) {
            return;
        }
        var modal = closestByClass(this, "modal");
        if (modal) {
            bootstrap.Modal.getOrCreateInstance(modal).hide();
        }
    });

    $(document).on("click.bs4compat", "[data-dismiss='alert']", function () {
        if (hasBsToggle(this)) {
            return;
        }
        var alertEl = closestByClass(this, "alert");
        if (alertEl) {
            bootstrap.Alert.getOrCreateInstance(alertEl).close();
        }
    });

    $(document).on("click.bs4compat", "[data-toggle='collapse']", function (e) {
        if (hasBsToggle(this)) {
            return;
        }
        var target = getTarget(this);
        if (!target) {
            return;
        }
        e.preventDefault();
        var collapseEl = document.querySelector(target);
        if (collapseEl) {
            bootstrap.Collapse.getOrCreateInstance(collapseEl, {
                parent: this.getAttribute("data-parent") || null
            }).toggle();
        }
    });

    $(document).on("click.bs4compat", "[data-toggle='tab'], [data-toggle='pill']", function (e) {
        if (hasBsToggle(this)) {
            return;
        }
        var target = getTarget(this);
        if (!target) {
            return;
        }
        e.preventDefault();
        var tabPane = document.querySelector(target);
        if (tabPane) {
            bootstrap.Tab.getOrCreateInstance(this).show();
        }
    });

    function initDelegatedTooltips(type) {
        var dataApi = type === "popover" ? "[data-toggle='popover']" : "[data-toggle='tooltip']";
        var Component = type === "popover" ? bootstrap.Popover : bootstrap.Tooltip;

        if (!Component) {
            return;
        }

        $(document).on("mouseenter.bs4compat focus.bs4compat", dataApi, function () {
            if (!hasBsToggle(this) && !Component.getInstance(this)) {
                new Component(this);
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", function () {
            initDelegatedTooltips("tooltip");
            initDelegatedTooltips("popover");
        });
    } else {
        initDelegatedTooltips("tooltip");
        initDelegatedTooltips("popover");
    }

    /* ------------------------------------------------------------------
     * Global Dropdown Popper Strategy: Fixed & Viewport-Bounded
     *
     * In tables (DataTables, .table-responsive, cards, etc.), dropdowns
     * often get clipped or go under the table if positioned absolutely
     * inside containers with overflow: hidden / auto / scroll.
     *
     * By setting strategy to 'fixed' and boundary to 'viewport' globally
     * on Bootstrap 5's Dropdown defaults, Popper calculates the menu
     * coordinates relative to the viewport. The menu floats on top of
     * all table containers, never clips, flips upwards if near the screen
     * bottom, and avoids any DOM detaching.
     * ------------------------------------------------------------------ */
    if (bootstrap && bootstrap.Dropdown && bootstrap.Dropdown.Default) {
        bootstrap.Dropdown.Default.boundary = 'viewport';
        bootstrap.Dropdown.Default.popperConfig = { strategy: 'fixed' };
    }

    // Close any open table dropdown when scrolling table containers
    $(document).on('scroll', '.table-responsive, .fcc-modern-table-wrap, .dataTables_wrapper', function () {
        $('.dropdown-menu.show').each(function () {
            var $menu = $(this);
            var $wrapper = $menu.closest('.dropdown, .custom-dropdown');
            var toggle = $wrapper.find('[data-bs-toggle="dropdown"], [data-toggle="dropdown"]')[0];
            if (toggle && bootstrap && bootstrap.Dropdown) {
                var inst = bootstrap.Dropdown.getInstance(toggle);
                if (inst) inst.hide();
            }
        });
    });
});
