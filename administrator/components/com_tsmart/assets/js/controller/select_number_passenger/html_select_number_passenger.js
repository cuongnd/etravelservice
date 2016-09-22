(function ($) {

    // here we go!
    $.html_select_number_passenger = function (element, options) {

        // plugin's default options
        var defaults = {
            list_number:[]
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_select2_template_result = function (result) {
            return result.text;
        };
        plugin.set_lect2_template_selection = function (selection) {
            return selection.text;
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var list_number=plugin.settings.list_number;
            var number_selected=plugin.settings.number_selected;
            plugin.select2=$element.select2({
                data:list_number,
                templateResult:plugin.set_select2_template_result,
                templateSelection:plugin.set_lect2_template_selection
            });
            plugin.select2.val(number_selected).trigger("change")
        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_number_passenger = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_number_passenger')) {
                var plugin = new $.html_select_number_passenger(this, options);
                $(this).data('html_select_number_passenger', plugin);

            }

        });

    }

})(jQuery);


