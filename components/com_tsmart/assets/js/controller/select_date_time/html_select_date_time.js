(function ($) {

    // here we go!
    $.html_select_date_time = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            format: 'mm/dd/yy',
            view_format: 'mm/dd/yy',
            min_date: new Date(),
            max_date: new Date(),
            from_date: new Date(),
            to_date: new Date()
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
            var min_date = plugin.settings.min_date;
            var max_date = plugin.settings.max_date;
            var from_date = plugin.settings.from_date;
            var to_date = plugin.settings.to_date;
            var format = plugin.settings.format;
            var input_name = plugin.settings.input_name;
            var view_format = plugin.settings.view_format;
            console.log(view_format);
            var input_name = plugin.settings.input_name;
            var slickDTP = new SlickDTP();


            $element.find('.select_date_time').click(function() {
                slickDTP.pickDate($element.find('.select_date_time'),'');
            });



           /* plugin.datepicker= $element.find('.select_date_time').datepicker({
                showButtonPanel: true,
                showWeek: true,
                minDate: "+0",
                dateFormat: format,
                changeMonth: true,
                changeYear: true,
                onSelect:function(dateText, inst ){
                    dateText=$.format.date(dateText, view_format);
                    $element.find('input[name="'+input_name+'"]').val(dateText);
                }
            });
*/
        };
        plugin.set_date=function(startDate,endDate){
            var daterangepicker=$element.find('.range_of_date_time').data('daterangepicker');
            daterangepicker.setStartDate(startDate);
            daterangepicker.setEndDate(endDate);
        };
        plugin.get_value=function(){
            return $element.find('input.select_date_time').val();
        };
        plugin.focus=function(){
            return $element.find('input.select_date_time').focus();
        };
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_select_date_time = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_date_time')) {
                var plugin = new $.html_select_date_time(this, options);
                $(this).data('html_select_date_time', plugin);

            }

        });

    }

})(jQuery);


