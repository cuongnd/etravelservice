(function ($) {

    // here we go!
    $.html_select_list_range_of_date = function (element, options) {

        // plugin's default options
        var defaults = {
            list_range_of_date:[],
            select_name:"",
            id_selected:0
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
            var list_range_of_date=plugin.settings.list_range_of_date;
            var select_name=plugin.settings.select_name;
            var id_selected=plugin.settings.id_selected;
            plugin.select2=$element.find('select[name="'+select_name+'"]').select2({

            });
            plugin.select2.val(id_selected).trigger("change")
        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_list_range_of_date = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_list_range_of_date')) {
                var plugin = new $.html_select_list_range_of_date(this, options);
                $(this).data('html_select_list_range_of_date', plugin);

            }

        });

    }

})(jQuery);


