(function ($) {

    // here we go!
    $.html_select_select_activity = function (element, options) {

        // plugin's default options
        var defaults = {
            activities:[],
            list_tsmart_activity_id:[]
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
            var activities=plugin.settings.activities;
            var list_tsmart_activity_id=plugin.settings.list_tsmart_activity_id;
            plugin.select2=$element.select2({
                data:activities,
                templateResult:plugin.set_select2_template_result,
                templateSelection:plugin.set_lect2_template_selection
            });
            //plugin.select2.val(list_tsmart_activity_id).trigger("change")
        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_select_activity = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_select_activity')) {
                var plugin = new $.html_select_select_activity(this, options);
                $(this).data('html_select_select_activity', plugin);

            }

        });

    }

})(jQuery);


