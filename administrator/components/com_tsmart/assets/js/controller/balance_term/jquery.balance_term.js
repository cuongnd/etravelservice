(function ($) {

    // here we go!
    $.balance_term = function (element, options) {

        // plugin's default options
        var defaults = {
            name_balance_of_day:'',
            name_percent_balance_of_day:''
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
            var name_balance_of_day=plugin.settings.name_balance_of_day;
            var name_percent_balance_of_day=plugin.settings.name_percent_balance_of_day;

            $element.find('input.inputbox_number').autoNumeric('init').change(function () {
                var value_of_this = $(this).autoNumeric('get');
                $('input[name="'+name_balance_of_day+'"]').val(value_of_this).trigger("change");


            });
            $element.find('input.inputbox_percent').autoNumeric('init').change(function () {
                var value_of_this = $(this).autoNumeric('get');
                $('input[name="'+name_percent_balance_of_day+'"]').val(value_of_this).trigger("change");
            });




        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.balance_term = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('balance_term')) {
                var plugin = new $.balance_term(this, options);
                $(this).data('balance_term', plugin);

            }

        });

    }

})(jQuery);


