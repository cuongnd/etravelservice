(function ($) {

    // here we go!
    $.html_select_tour = function (element, options) {

        // plugin's default options
        var defaults = {
            list_tour:[],
            select_name:"",
            tsmart_language_id:0
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
            var list_tour=plugin.settings.list_tour;
            var select_name=plugin.settings.select_name;
            var tsmart_product_id=plugin.settings.tsmart_language_id;
            plugin.select2=$element.select2({

            });
            //plugin.select2.val(tsmart_product_id).trigger("change")
        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_tour = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_tour')) {
                var plugin = new $.html_select_tour(this, options);
                $(this).data('html_select_tour', plugin);

            }

        });

    }

})(jQuery);


