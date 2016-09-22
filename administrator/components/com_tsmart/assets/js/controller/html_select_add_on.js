(function ($) {

    // here we go!
    $.html_select_add_on = function (element, options) {

        // plugin's default options
        var defaults = {
            id_field_edit_content_wrapper:'',
            iframe_link:'',
            tour_id:0,
            key_string:'',
            id_vm_iframe:'',
            dialog_class:'dialog-form-price',
            link_redirect:'',
            ui_dialog_id:'dialog_content',
            iframe_id:'vm-iframe',
            reload_ui_dialog_id:'vm-ui-dialog',
            reload_iframe_id:'vm-iframe',
            small_form:0,
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
            var id_field_edit_content_wrapper=plugin.settings.id_field_edit_content_wrapper;
            var view=plugin.settings.view;
            var key_string = plugin.settings.key_string;
            var iframe_id = plugin.settings.iframe_id;
            var link_reload = plugin.settings.link_reload;
            var ui_dialog_id = plugin.settings.ui_dialog_id;
            var reload_ui_dialog_id = plugin.settings.reload_ui_dialog_id;
            var reload_iframe_id = plugin.settings.reload_iframe_id;
            var iframe_link = plugin.settings.iframe_link;

            $element.find('.edit_content').click(function(){
                var jq_parent=window.parent.jQuery;
                var $dialog=jq_parent('<div  id="'+ui_dialog_id+'" ><iframe id="'+iframe_id+'"  scrolling="no" src=""></iframe></div>');
                jq_parent('body').append($dialog);

                jq_parent('#'+ui_dialog_id).dialog({
                    modal: true,
                    dialogClass:'asian-dialog-form',
                    width: 600,
                    title: view,
                    height: 900,
                    open: function (ev, ui) {

                        jq_parent('#'+ui_dialog_id).find('#'+iframe_id).attr('src', iframe_link+'&show_in_parent_window=1&window_name=test'+key_string+'&link_reload='+link_reload+'&ui_dialog_id='+ui_dialog_id+'&iframe_id='+iframe_id+'&reload_ui_dialog_id='+reload_ui_dialog_id+'&reload_iframe_id='+reload_iframe_id+'&remove_ui_dialog=1&small_form=1').css({
                            width: '600px',
                            height: '800px',

                        });
                    },
                    close: function (ev, ui) {
                        jq_parent('#'+ui_dialog_id).dialog("destroy").remove();
                        $('#'+reload_iframe_id).attr('src',$('#'+reload_iframe_id).attr('src') );
                    },
                    show: {effect: "blind", duration: 800},
                    appendTo: 'body'
                    //closeOnEscape: false,
                    //open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

                });

            });

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_add_on = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_add_on')) {
                var plugin = new $.html_select_add_on(this, options);
                $(this).data('html_select_add_on', plugin);

            }

        });

    }

})(jQuery);


