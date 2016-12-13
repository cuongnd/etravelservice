(function ($) {

    // here we go!
    $.number_state = function (element, options) {

        // plugin's default options
        var defaults = {
            min:0,
            max:100,
            current:50,
            left_color:'#990100',
            right_color:'#ff9900'
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
    $.fn.number_state = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('number_state')) {
                var plugin = new $.number_state(this, options);
                $(this).data('number_state', plugin);

            }

        });

    }

})(jQuery);


