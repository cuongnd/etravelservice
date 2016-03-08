(function ($) {

    // here we go!
    $.html_select_amount_percent = function (element, options) {

        // plugin's default options
        var defaults = {
            amount_name:'amount',
            percent_name:'percent'
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
            var amount_name=plugin.settings.amount_name;
            var percent_name=plugin.settings.percent_name;
            $('.auto').autoNumeric('init');
            $element.find('.percent_input').change(function(event){
                var value_of_this=$(this).autoNumeric('get');
                $('input[name="'+percent_name+'"]').val(value_of_this);

            });
            $element.find('.amount_input').change(function(event){
                var value_of_this=$(this).autoNumeric('get');
                $('input[name="'+amount_name+'"]').val(value_of_this);

            });

        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_amount_percent = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_amount_percent')) {
                var plugin = new $.html_select_amount_percent(this, options);
                $(this).data('html_select_amount_percent', plugin);

            }

        });

    }

})(jQuery);


