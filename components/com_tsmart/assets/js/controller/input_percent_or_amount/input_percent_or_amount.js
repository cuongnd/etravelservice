(function ($) {

    // here we go!
    $.input_percent_or_amount = function (element, options) {

        // plugin's default options
        var defaults = {
            list_tour:[],
            name_of_value_percent_or_amount:"",
            name_of_type_percent_or_amount:"",
            value_of_percent_or_amount:0,
            min_amount:0,
            max_amount:0,
            min_percent:0,
            max_percent:0
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
            var name_of_value_percent_or_amount=plugin.settings.name_of_value_percent_or_amount;
            $element.find('input.input_number_or_percent').autoNumeric('init').change(function () {
                var value_of_this = $(this).autoNumeric('get');
                $('input[name="'+name_of_value_percent_or_amount+'"]').val(value_of_this).trigger("change");


            });
            var value_of_type=plugin.settings.value_of_type;
            var name_of_type_percent_or_amount=plugin.settings.name_of_type_percent_or_amount;
            var min_percent=plugin.settings.min_percent;
            var max_percent=plugin.settings.max_percent;
            var min_amount=plugin.settings.min_amount;
            var max_amount=plugin.settings.max_amount;
            $element.find('select.type_percent_or_amount').change(function(){
                var type=$(this).val();
                if(type=="percent"){
                    var value_number_or_percent = $element.find('input.input_number_or_percent').autoNumeric('get');
                    if(value_number_or_percent< min_percent || value_number_or_percent>max_percent)
                    {
                        $element.find('input.input_number_or_percent').autoNumeric('set',0);
                    }
                    $element.find('input.input_number_or_percent').autoNumeric("update", {
                        aSign: '%',
                        vMin: min_percent,
                        vMax: max_percent,
                    });
                }else{
                    if(value_number_or_percent< min_amount || value_number_or_percent>max_amount)
                    {
                        $element.find('input.input_number_or_percent').autoNumeric('set',0);
                    }

                    $element.find('input.input_number_or_percent').autoNumeric("update", {
                        vMin: min_amount,
                        vMax: max_amount,
                        aSign: ''
                    });
                }
            });
            plugin.select2=$element.find('select.type_percent_or_amount').select2({

            });
            plugin.select2.val(value_of_type).trigger("change");

        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.input_percent_or_amount = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('input_percent_or_amount')) {
                var plugin = new $.input_percent_or_amount(this, options);
                $(this).data('input_percent_or_amount', plugin);

            }

        });

    }

})(jQuery);


