(function ($) {

    // here we go!
    $.html_select_date = function (element, options) {

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
                minDate: "+0",
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

        };
        plugin.get_date=function(){
            var input_name = plugin.settings.input_name;
            return $element.find('input[name="'+input_name+'"]').val();
        };
        plugin.set_max_date=function(date){
            console.log(date);
            $element.find('.select_date').datepicker('option','maxDate',new Date(date));
        };
        plugin.on_select=function(fuc){
            var format = plugin.settings.format;
            var input_name = plugin.settings.input_name;

            $element.find('.select_date').datepicker('option','onSelect',function(dateText, inst ){
                dateText=$.format.date(dateText, format);
                $element.find('input[name="'+input_name+'"]').val(dateText);
                fuc.call();

            });
        };
        plugin.set_min_date=function(date){
            $element.find('.select_date').datepicker('option','minDate',new Date(date));
        };
        plugin.set_date=function(queryDate){
            $element.find('.select_date').datepicker('setDate', queryDate);
        };
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_select_date = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_date')) {
                var plugin = new $.html_select_date(this, options);
                $(this).data('html_select_date', plugin);

            }

        });

    }

})(jQuery);


