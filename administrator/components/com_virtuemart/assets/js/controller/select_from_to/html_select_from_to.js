(function ($) {

    // here we go!
    $.html_select_from_to = function (element, options) {

        // plugin's default options
        var defaults = {
            min:0,
            max:100,
            from:0,
            to:100
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
            min=plugin.settings.min;
            max=plugin.settings.max;
            from=plugin.settings.from;
            to=plugin.settings.to;
            $element.ionRangeSlider({
                type: "double",
                min: min,
                max: max,
                from: from,
                to: to,
                keyboard: true,
                onFinish: function (data) {
                    self=data.input;
/*
                    input_from=self.find('input.block-item-rangeofintegers-from');
                    input_to=self.find('input.block-item-rangeofintegers-to');
                    input_from.val(data.fromNumber);
                    input_to.val(data.toNumber);
*/
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


