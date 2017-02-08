(function ($) {

    // here we go!
    $.input_number = function (element, options) {

        // plugin's default options
        var defaults = {
            element_name:''
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_value=function(number){
            var element_name=plugin.settings.element_name;
            $element.find('.input_number_'+element_name).autoNumeric('set',number);
            $element.find('input[name="'+element_name+'"]').val(number).trigger("change");
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var element_name=plugin.settings.element_name;
            $element.find('.input_number_'+element_name).autoNumeric('init',{}).change(function () {
                var value_of_this = $(this).autoNumeric('get');
                $element.find('input[name="'+element_name+'"]').val(value_of_this).trigger("change");
            });




        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.input_number = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('input_number')) {
                var plugin = new $.input_number(this, options);
                $(this).data('input_number', plugin);

            }

        });

    }

})(jQuery);


