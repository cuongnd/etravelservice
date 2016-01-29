(function ($) {

    // here we go!
    $.view_allocation = function (element, options) {

        // plugin's default options
        var defaults = {

            list_date: [],


        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            list_date=plugin.settings.list_date;
            $.each(list_date,function(index,date){
                list_date[index]=new Date(date);
            });
            var multi_calendar_allocation=$('#multi-calendar-allocation').DatePicker({
                mode: 'multiple',
                inline: true,
                calendars: 2,
                date: list_date
            });
            $('#vail_period_from').datepicker();
            $('#vail_period_to').datepicker();
            $('#sale_period_open').datepicker();
            $('#sale_period_close').datepicker();
            Joomla.submitbutton = function(task)
            {

                if (task == "cancel")
                {
                    Joomla.submitform(task);
                }else if (task == "save" || task == "apply"){
                    var list_date=[];
                    var dates=multi_calendar_allocation.DatePickerGetDate();
                    dates=dates[0];
                    $.each(dates,function(index,date){
                        list_date.push($.format.date(date, "yyyy/MM/dd"));
                    });
                    $('#days_seleted').val(cassandraMAP.stringify(list_date));
                    Joomla.submitform(task);
                }
            };


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_allocation = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_allocation')) {
                var plugin = new $.view_allocation(this, options);
                $(this).data('view_allocation', plugin);

            }

        });

    }

})(jQuery);


