(function ($) {

    // here we go!
    $.supplier_html_select_supplier = function (element, options) {

        // plugin's default options
        var defaults = {
            list_supplier:[],
            name:"",
            supplier_id:0
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.options = function (options) {

        }
        plugin.set_value = function (values) {
            var name=plugin.settings.name;
            $element.find('select[name="'+name+'"]').val(values).trigger('change');
        }
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var name=plugin.settings.name;
            $element.find('select[name="'+name+'"]').select2({
                placeholder: 'Select an option',
                containerCssClass: "supplier_html_select_supplier"
            });
        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.supplier_html_select_supplier = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('supplier_html_select_supplier')) {
                var plugin = new $.supplier_html_select_supplier(this, options);
                $(this).data('supplier_html_select_supplier', plugin);

            }

        });

    }

})(jQuery);


