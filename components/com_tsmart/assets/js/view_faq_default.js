(function ($) {

    // here we go!
    $.view_faq_default = function (element, options) {

        // plugin's default options
        var defaults = {
            totalPages:0,
            totalItem:10,
            tour_id:0,
            visiblePages:5,
            tour_methor:'',
            dialog_class:'dialog-form-price',
            date_format:'m/d/y'


        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            $('.buid-information').insertBefore($('.vm_toolbar'));

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_faq_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_faq_default')) {
                var plugin = new $.view_faq_default(this, options);
                $(this).data('view_faq_default', plugin);

            }

        });

    }

})(jQuery);


