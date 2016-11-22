(function ($) {

    // here we go!
    $.view_paymentsetting_default = function (element, options) {

        // plugin's default options
        var defaults = {


        };

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            $element.find('#deposit_term,#credit_card_fee').autoNumeric('init');
            $element.find('input[name="deposit_type"]').change(function(){
                var type=$(this).val();
                var amount=$element.find('input[name="deposit_amount"]').val();
                var $balance_day=$('input[name="balance_day_1"],input[name="balance_day_2"],input[name="balance_day_3"]');
                var $balance_percent=$('input[name="balance_percent_2"],input[name="balance_percent_1"],input[name="balance_percent_3"]');
                if(type=='percent'&&amount==100)
                {
                    $balance_day.prop('disabled', true);
                    $balance_percent.prop('disabled', true);
                }else{
                    $balance_day.prop('disabled', false);
                    $balance_percent.prop('disabled', false);
                }
            });

        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_paymentsetting_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_paymentsetting_default')) {
                var plugin = new $.view_paymentsetting_default(this, options);
                $(this).data('view_paymentsetting_default', plugin);

            }

        });

    }

})(jQuery);


