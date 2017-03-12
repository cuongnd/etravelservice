(function ($) {

    // here we go!
    $.html_select_from = function (element, options) {

        // plugin's default options
        var defaults = {
            name:'',
            min:0,
            from:0,
            max:100
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
            var disable=plugin.settings.disable;
            var from=plugin.settings.from;
            var name=plugin.settings.name;
            $element.find('.integer').ionRangeSlider({
                type: "single",
                min: min,
                max: max,
                from: from,
                disable:disable,
                keyboard: true,
                onFinish: function (data) {
                    console.log(data);
                    var input_from=$element.find('input[name="'+name+'"]');
                    input_from.val(data.from);
                }
            });


        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_from = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_from')) {
                var plugin = new $.html_select_from(this, options);
                $(this).data('html_select_from', plugin);

            }

        });

    }

})(jQuery);


