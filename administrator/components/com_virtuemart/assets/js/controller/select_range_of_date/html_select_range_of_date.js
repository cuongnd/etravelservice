(function ($) {

    // here we go!
    $.html_select_range_of_date = function (element, options) {

        // plugin's default options
        var defaults = {
            from_name: '',
            to_name: '',
            min_date: new Date(),
            max_date: new Date(),
            from_date: new Date(),
            to_date: new Date(),
            display_format:'YYYY-MM-DD',
            format:'YYYY-MM-DD',
            ranges: {}

        };

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_date=function(start, end){
            start=moment(start);
            end=moment(end);
            var format=plugin.settings.format;
            var input_from = $element.find('input[name="' + start + '"]');
            var input_to = $element.find('input[name="' + end + '"]');
            input_from.val(start.format(format));
            input_to.val(end.format(format));
            var display_format=plugin.settings.display_format;
            $element.find('.range_of_date').val(start.format(display_format)+'-'+end.format(display_format));
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var min_date = plugin.settings.min_date;
            var max_date = plugin.settings.max_date;
            var from_date = plugin.settings.from_date;
            var to_date = plugin.settings.to_date;
            var from_name = plugin.settings.from_name;
            var to_name = plugin.settings.to_name;
            plugin.daterangepicker=$element.find('.range_of_date').daterangepicker({
                    format: plugin.settings.format,
                    "showDropdowns": true,
                    startDate: new Date(),
                    endDate: new Date(),
                    ranges:plugin.settings.ranges
                },
                function (start, end, label) {
                    var input_from = $element.find('input[name="' + from_name + '"]');
                    var input_to = $element.find('input[name="' + to_name + '"]');
                    input_from.val(start.format(plugin.settings.format));
                    input_to.val(end.format(plugin.settings.format));
                }
            );

            plugin.instant_daterangepicker=$element.find('.range_of_date').data('daterangepicker');
            plugin.daterangepicker.on('daterangepicker.selected_start_date', function(ev, startDate) {
                //do something, like clearing an input
                plugin.selected_start_date(ev, startDate);
            });
            plugin.daterangepicker.on('daterangepicker.selected_end_date', function(ev, endDate) {
                //do something, like clearing an input
                plugin.selected_end_date(ev, endDate);
            });


        };
        plugin.selected_start_date=function(ev, picker){

        };
        plugin.selected_end_date=function(ev, picker){

        };
        plugin.focus=function(){
            $element.find('.range_of_date').focus();
        };
        plugin.clear=function()
        {
            var from_name = plugin.settings.from_name;
            var to_name = plugin.settings.to_name;
            $element.find('.range_of_date').val('');
            var input_from = $element.find('input[name="' + from_name + '"]');
            var input_to = $element.find('input[name="' + to_name + '"]');
            input_from.val('');
            input_to.val('');
        };
        plugin.set_date=function(startDate,endDate){
            daterangepicker=$element.find('.range_of_date').data('daterangepicker');
            daterangepicker.setStartDate(startDate);
            daterangepicker.setEndDate(endDate);
        }
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_select_range_of_date = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_range_of_date')) {
                var plugin = new $.html_select_range_of_date(this, options);
                $(this).data('html_select_range_of_date', plugin);

            }

        });

    }

})(jQuery);


