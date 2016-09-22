(function ($) {

    // here we go!
    $.html_select_month = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            format: 'MM/YYYY',
            view_format: 'MM/YYYY',
            min_month: new Date(),
            max_month: new Date(),
            from_month: new Date(),
            to_month: new Date(),
            StartYear: (new Date()).getFullYear()
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
            var min_month = plugin.settings.min_month;
            var max_month = plugin.settings.max_month;
            var from_month = plugin.settings.from_month;
            var to_month = plugin.settings.to_month;
            var format = plugin.settings.format;
            var StartYear = plugin.settings.StartYear;
            var view_format = plugin.settings.view_format;
            var input_name = plugin.settings.input_name;
            plugin.datepicker = $element.find('.select_month').MonthPicker(
                {
                    StartYear: StartYear,
                    ShowIcon: false,
                    OnAfterChooseMonth: function( selectedDate ){
                        selectedDate=moment(selectedDate);
                        $element.find('input[name="' + input_name + '"]').val(selectedDate.format(view_format));
                    }
                }
            );

        };
        plugin.set_month = function (startDate, endDate) {
            daterangepicker = $element.find('.range_of_month').data('daterangepicker');
            daterangepicker.setStartDate(startDate);
            daterangepicker.setEndDate(endDate);
        }
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_select_month = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_month')) {
                var plugin = new $.html_select_month(this, options);
                $(this).data('html_select_month', plugin);

            }

        });

    }

})(jQuery);


