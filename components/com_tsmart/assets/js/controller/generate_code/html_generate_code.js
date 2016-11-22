(function ($) {

    // here we go!
    $.html_generate_code = function (element, options) {

        // plugin's default options
        var defaults = {
            state_element:''
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);


        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_generate_code = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_generate_code')) {
                var plugin = new $.html_generate_code(this, options);
                $(this).data('html_generate_code', plugin);

            }

        });

    }

})(jQuery);


