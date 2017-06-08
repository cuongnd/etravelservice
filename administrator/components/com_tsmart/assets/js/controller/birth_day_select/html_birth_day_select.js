(function ($) {

    // here we go!
    $.html_birth_day_select = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            format: 'mm/dd/yy',
            view_format: 'mm/dd/yy',
            min_date: new Date(),
            max_date: new Date(),
            from_date: new Date(),
            to_date: new Date(),
            value_selected: ""
        };

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var value_selected = plugin.settings.value_selected;
            var min_date = plugin.settings.min_date;
            var max_date = plugin.settings.max_date;
            var from_date = plugin.settings.from_date;
            var to_date = plugin.settings.to_date;
            var format = plugin.settings.format;
            var view_format = plugin.settings.view_format;
            var input_name = plugin.settings.input_name;
            plugin.datepicker= $element.find('.select_date').datepicker({
                showButtonPanel: true,
                showWeek: true,
                yearRange: "-100:+0", // this is the option you're looking for
                dateFormat: view_format,
                changeMonth: true,
                changeYear: true,
                onSelect:function(dateText, inst ){
                    dateText=$.format.date(dateText, format);
                    $element.find('input[name="'+input_name+'"]').val(dateText);
                }
            });
            $element.find('input.select_date').change(function(){
                var date=$(this).val();
                var date=$.format.date(date, format);
                $element.find('input[name="'+input_name+'"]').val(date);
            });
            $element.find('.select_date').datepicker('setDate', value_selected);
            value_selected

        };
        plugin.set_date=function(startDate,endDate){
            daterangepicker=$element.find('.range_of_date').data('daterangepicker');
            daterangepicker.setStartDate(startDate);
            daterangepicker.setEndDate(endDate);
        }
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_birth_day_select = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_birth_day_select')) {
                var plugin = new $.html_birth_day_select(this, options);
                $(this).data('html_birth_day_select', plugin);

            }

        });

    }

})(jQuery);


