(function ($) {

    // here we go!
    $.view_price = function (element, options) {

        // plugin's default options
        var defaults = {



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


            $element.find('input.number').keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message

                    return false;
                }
                else{

                }
            });
            Joomla.submitbutton = function(task)
            {

                if (task == "cancel")
                {
                    Joomla.submitform(task);
                }else if (task == "save" || task == "apply"){
                    Joomla.submitform(task);
                }
            };


        }


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_price = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_price')) {
                var plugin = new $.view_price(this, options);
                $(this).data('view_price', plugin);

            }

        });

    }

})(jQuery);


