(function ($) {

    // here we go!
    $.html_select_percent_amount = function (element, options) {

        // plugin's default options
        var defaults = {
            type_name:'type',
            amount_name:'amount'
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
            var type_name=plugin.settings.type_name;
            var amount_name=plugin.settings.amount_name;
            $element.find('.auto').autoNumeric('init');
            $element.find('.percent_input').change(function(event){
                var value_of_this=$(this).autoNumeric('get');
                if(value_of_this!=0)
                {
                    $element.find('.amount_input').prop('disabled', true);
                }else{
                    $element.find('.amount_input').prop('disabled', false);
                }
                $element.find('input[name="'+amount_name+'"]').val(value_of_this);
                $element.find('input[name="'+type_name+'"]').val('percent').trigger('change');
            });
            $element.find('.amount_input').change(function(event){
                var value_of_this=$(this).autoNumeric('get');
                if(value_of_this!=0)
                {
                    $element.find('.percent_input').prop('disabled', true);
                }else{
                    $element.find('.percent_input').prop('disabled', false);
                }
                $element.find('input[name="'+amount_name+'"]').val(value_of_this);
                $element.find('input[name="'+type_name+'"]').val('amount').trigger('change');
            });

        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_percent_amount = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_percent_amount')) {
                var plugin = new $.html_select_percent_amount(this, options);
                $(this).data('html_select_percent_amount', plugin);

            }

        });

    }

})(jQuery);


