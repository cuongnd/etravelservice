(function ($) {

    // here we go!
    $.user_html_select_user = function (element, options) {

        // plugin's default options
        var defaults = {
            list_user:[],
            name:"",
            user_id:0
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
                containerCssClass: "user_html_select_user"
            });
        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.user_html_select_user = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('user_html_select_user')) {
                var plugin = new $.user_html_select_user(this, options);
                $(this).data('user_html_select_user', plugin);

            }

        });

    }

})(jQuery);


