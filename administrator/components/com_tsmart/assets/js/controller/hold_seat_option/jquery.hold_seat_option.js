(function ($) {

    // here we go!
    $.hold_seat_option = function (element, options) {

        // plugin's default options
        var defaults = {
            name_hold_seat:'',
            name_hold_seat_hours:''
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
            var name_hold_seat=plugin.settings.name_hold_seat;
            var name_hold_seat_hours=plugin.settings.name_hold_seat_hours;
            $element.find('select[name="'+name_hold_seat+'"]').change(function(){
                var hold_seat=$(this).val();
                if(hold_seat==0 || hold_seat=='')
                {
                    $element.find('select[name="'+name_hold_seat_hours+'"]').prop('disabled',true).trigger('chosen:updated');
                }else{
                    $element.find('select[name="'+name_hold_seat_hours+'"]').prop('disabled',false).trigger('chosen:updated');

                }
            });



        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.hold_seat_option = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('hold_seat_option')) {
                var plugin = new $.hold_seat_option(this, options);
                $(this).data('hold_seat_option', plugin);

            }

        });

    }

})(jQuery);


