(function ($) {

    // here we go!
    $.view_accommodation_default = function (element, options) {

        // plugin's default options
        var defaults = {
            task:'',
            totalPages:0,
            totalItem:10,
            tour_id:0,
            visiblePages:5,
            tour_methor:'',
            dialog_class:'dialog-form-price',
            date_format:'m/d/y'


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
            $('.buid-information').insertBefore($('.vm_toolbar'));

            var task=plugin.settings.task;
            console.log(task);
            if(task=='add_new_item'||task=='edit_item')
            {

                $element.find( ".view-accommodation-edit" ).dialog({
                    dialogClass:'asian-dialog-form',
                    modal: true,
                    width: 900,
                    title: 'accommodation',
                    show: {effect: "blind", duration: 800},
                    appendTo: 'body'
                });
            }



        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_accommodation_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_accommodation_default')) {
                var plugin = new $.view_accommodation_default(this, options);
                $(this).data('view_accommodation_default', plugin);

            }

        });

    }

})(jQuery);

