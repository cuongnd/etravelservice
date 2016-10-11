(function ($) {

    // here we go!
    $.bootstrap_activelist = function (element, options) {

        // plugin's default options
        var defaults = {
            element_name:'',
            state_element:'',
            default:0
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('input').iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal',
                increaseArea: '20%'
            });

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.bootstrap_activelist = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('bootstrap_activelist')) {
                var plugin = new $.bootstrap_activelist(this, options);
                $(this).data('bootstrap_activelist', plugin);

            }

        });

    }

})(jQuery);


