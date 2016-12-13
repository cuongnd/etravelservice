(function ($) {

    // here we go!
    $.range_of_integer = function (element, options) {

        // plugin's default options
        var defaults = {
            name_input_from:'',
            name_input_to:''
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
            var name_input_from=plugin.settings.name_input_from;
            var name_input_to=plugin.settings.name_input_to;

            $element.find('input.inputbox_number').autoNumeric('init').change(function () {
                var value_of_this = $(this).autoNumeric('get');
                $('input[name="'+name_input_from+'"]').val(value_of_this).trigger("change");


            });
            $element.find('input.inputbox_percent').autoNumeric('init').change(function () {
                var value_of_this = $(this).autoNumeric('get');
                $('input[name="'+name_input_to+'"]').val(value_of_this).trigger("change");
            });




        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.range_of_integer = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('range_of_integer')) {
                var plugin = new $.range_of_integer(this, options);
                $(this).data('range_of_integer', plugin);

            }

        });

    }

})(jQuery);


