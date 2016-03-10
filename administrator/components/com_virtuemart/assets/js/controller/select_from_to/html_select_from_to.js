(function ($) {

    // here we go!
    $.html_select_from_to = function (element, options) {

        // plugin's default options
        var defaults = {
            from_name:'',
            to_name:'',
            min:0,
            max:100,
            from:12,
            to:70
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
            var min=plugin.settings.min;
            var max=plugin.settings.max;
            var from=plugin.settings.from;
            var to=plugin.settings.to;
            var from_name=plugin.settings.from_name;
            var to_name=plugin.settings.to_name;
            $element.find('.integer-range').ionRangeSlider({
                type: "double",
                min: min,
                max: max,
                from: from,
                to: to,
                keyboard: true,
                onFinish: function (data) {
                    var input_from=$element.find('input[name="'+from_name+'"]');
                    var input_to=$element.find('input[name="'+to_name+'"]');
                    input_from.val(data.from);
                    input_to.val(data.to);
                }
            });


        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_from_to = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_from_to')) {
                var plugin = new $.html_select_from_to(this, options);
                $(this).data('html_select_from_to', plugin);

            }

        });

    }

})(jQuery);


