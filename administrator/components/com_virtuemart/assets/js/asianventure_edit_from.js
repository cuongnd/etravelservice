(function ($) {

    // here we go!
    $.asianventure_edit_from = function (element, options) {

        // plugin's default options
        var defaults = {
            totalPages:0,
            totalItem:10,
            tour_id:0,
            view:'',
            view_height:0,
            visiblePages:5,
            show_in_parent_window:false,
            url:'',
            cid:[],
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
            var show_in_parent_window=plugin.settings.show_in_parent_window;
            var view_height=plugin.settings.view_height;
            var close_window_children=plugin.settings.close_window_children;
            if(show_in_parent_window==1)
            {
/*
                Joomla.submitbutton=function(task)
                {
                    Joomla.submitform( task );
                    window.parent.dialog_close();
                }
*/
            }
            if(close_window_children==1)
            {
                window.parent.dialog_close();
            }
            $('.vm_toolbar').insertAfter('#admin-ui-tabs');
            if(view_height!=0) {
                if (typeof window.parent.change_height_dialog == "function") {
                    window.parent.change_height_dialog(view_height);
                }
                $('.admin.com_virtuemart,html').height(view_height);
            }

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.asianventure_edit_from = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('asianventure_edit_from')) {
                var plugin = new $.asianventure_edit_from(this, options);
                $(this).data('asianventure_edit_from', plugin);

            }

        });

    }

})(jQuery);


