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
            link_reload:'',
            close_ui_dialog_id:'',
            dialog_class:'dialog-form-price',
            date_format:'m/d/y',
            parent_ui_dialog_id:'vm-ui-dialog',
            parent_iframe_id:'vm-iframe',
            reload_iframe_id:'vm-iframe-reload',
            remove_ui_dialog:false,
            small_form:0


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
            var link_reload=plugin.settings.link_reload;
            link_reload= base64.decode(link_reload);
            var close_ui_dialog_id=plugin.settings.close_ui_dialog_id;
            var parent_ui_dialog_id=plugin.settings.parent_ui_dialog_id;
            var parent_iframe_id=plugin.settings.parent_iframe_id;
            var reload_iframe_id=plugin.settings.reload_iframe_id;
            var remove_ui_dialog=plugin.settings.remove_ui_dialog;
            var small_form = plugin.settings.small_form;
            if(small_form==1)
            {
                $('body.admin.com_virtuemart').addClass('small_form');
            }
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
            var jq_parent=window.parent.jQuery;
            console.log(close_ui_dialog_id);
            console.log(reload_iframe_id);
            console.log(link_reload);
            if(close_window_children==1 && typeof   window.parent.dialog_close=='function')
            {
                window.parent.dialog_close(close_ui_dialog_id,reload_iframe_id,link_reload,remove_ui_dialog);
            }
            $('.vm_toolbar').insertAfter('#admin-ui-tabs');
            if(view_height==0)
            {
                var view_height=$('.admin.com_virtuemart>.container-main').height();
            }
            $('.admin.com_virtuemart').height(view_height+20);
            if (typeof window.parent.change_height_dialog == "function") {
                window.parent.change_height_dialog(parent_ui_dialog_id,parent_iframe_id,view_height+20);
            }


            /*if(view_height!=0) {
                if (typeof window.parent.change_height_dialog == "function") {

                }
                $('.admin.com_virtuemart,html').height(view_height);
            }*/

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


