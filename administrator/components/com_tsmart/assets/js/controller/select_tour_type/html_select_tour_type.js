(function ($) {

    // here we go!
    $.html_select_tour_type = function (element, options) {

        // plugin's default options
        var defaults = {
            list_tour_type:[],
            select_name:"",
            tsmart_tour_type_id:0
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
            var list_tour_type=plugin.settings.list_tour_type;
            var select_name=plugin.settings.select_name;
            var tsmart_tour_type_id=plugin.settings.tsmart_tour_type_id;
            plugin.select2=$element.find('select[name="'+select_name+'"]').select2({
                templateResult:plugin.set_select2_template_result,
                templateSelection:plugin.set_lect2_template_selection
            });
            plugin.select2.val(tsmart_tour_type_id).trigger("change")
        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_tour_type = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_tour_type')) {
                var plugin = new $.html_select_tour_type(this, options);
                $(this).data('html_select_tour_type', plugin);

            }

        });

    }

})(jQuery);


