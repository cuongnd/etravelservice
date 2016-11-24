(function ($) {

    // here we go!
    $.asianventure = function (element, options) {

        // plugin's default options
        var defaults = {
            option:'com_tsmart',
            show_iframe: false,
            totalPages: 0,
            totalItem: 10,
            tour_id: 0,
            view: '',
            visiblePages: 5,
            add_new_popup: false,
            url: '',
            cid: [],
            key_string: '',
            dialog_class: 'dialog-form-price',
            date_format: 'm/d/y'


        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_event_task = function (self) {
            var option= plugin.settings.option;
            var $form = self.closest('form');
            var task = self.data('jtask');
            task=task.split('.');
            var controller=task[0];
            var task=task[1];
            $('<input type="hidden" name="option" value="'+option+'">').appendTo($form);
            $('<input type="hidden" name="controller" value="'+controller+'">').appendTo($form);
            $('<input type="hidden" name="task" value="'+task+'">').appendTo($form);
            $form.submit();

        };

        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            var url = plugin.settings.url;

            var link_create_new_tour = 'index.php?option=com_tsmart&view=product&task=edit';
            if (url.indexOf(link_create_new_tour) > -1 ) {
                $('.nav-tabs a:eq(3)').tab('show');
            } else {
                var list_link = '.header-main-menu .tab-content a';
                for (var i = 0; i < $(list_link).length; i++) {
                    var $link = $(list_link + ':eq(' + i + ')');
                    var href = $link.attr('href');
                    if (href.toLowerCase() == url || url.indexOf(href) > -1) {
                        var tab_pane = $link.closest('.tab-pane');
                        var index = tab_pane.index();
                        $('.nav-tabs a:eq(' + index + ')').tab('show');
                        break;
                    }
                }
            }
            $element.find('[data-jtask]').click(function () {
                plugin.set_event_task($(this));
            });
            $(document).on('shown.bs.tab', '.header-main-menu a[data-toggle="tab"]', function (e) {
                var $target = $(e.target); // activated tab
                var href = $target.attr('href');
                if (href == '#tour_build') {
                    window.location.href = link_create_new_tour;

                }
            });
            //$('.pagination.pagination-toolbar').appendTo('.toolbar-pagination');

            view=plugin.settings.view;
            if(view!='tsmart') {
                var tab_active_name=$('.header-main-menu li.active a').attr('href');
                tab_active_name=tab_active_name.replace("#", "");
                $('#admin-content').addClass(tab_active_name);
                if(tab_active_name=='logistic'||tab_active_name=='setup_system')
                {
                    //$('.title_page').closest('div').removeClass('offset8').addClass('offset1');
                }else if(tab_active_name=='tour_build')
                {
                    //$('.vm_toolbar').insertAfter($('.buid-information'));
                }

            }

            var add_new_popup = plugin.settings.add_new_popup;
            var cid = plugin.settings.cid;
            var key_string = plugin.settings.key_string;
            var view = plugin.settings.view;
            var ui_dialog_id='vm-edit-form-'+view;
            var iframe_id='vm-iframe-'+view;
            var ui_dialog_setting={
                autoOpen: true,
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                title: view,
                height: 1600,
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
                //closeOnEscape: false,
                //open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

            };
            var link_load_data= 'index.php?option=com_tsmart&view=' + view + '&task=edit&cid[0]=' + cid[0] + '&ui_dialog_id='+ui_dialog_id+'&show_in_parent_window=1'+key_string+'&iframe_id='+iframe_id;
            var ui_dialog=$element.find("#"+ui_dialog_id);
            $element.find('.show-edit-popup').click(function(event){
                var href=$(this).attr('href');
                var dialog_id='dialog-'+view+"-edit";
                var $dialog=$('#'+dialog_id);
                if($dialog.length==0)
                {
                    var $dialog=$('<div id="'+dialog_id+'"></div>');
                    $dialog.appendTo('body');
                }
                ui_dialog_setting.open=function (ev, ui) {
                    $.ajax({
                        type: "GET",
                        url: href+'&tmpl=ajax_json',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                            };
                            return dataPost;
                        })(),
                        beforeSend: function () {

                            $('.div-loading').css({
                                display: "block"
                            });
                        },
                        success: function (response) {

                            $('.div-loading').css({
                                display: "none"


                            });
                            $.set_html_for_tag(response);
                            var html=response.html;
                            $dialog.empty();
                            $(html).appendTo($dialog);
                        }

                    });


/*
                    dialog.load(href+'&tmpl=component', function() {

                    });
*/

                };
                $dialog.dialog(ui_dialog_setting);
                event.preventDefault();
            });

            if (add_new_popup == 1) {

                var show_iframe = plugin.settings.show_iframe;
                if(show_iframe)
                {
                    ui_dialog_setting.open=function (ev, ui) {
                        $('#'+iframe_id).attr('src',link_load_data).css({
                            width: '900px',
                            height: '1500px',

                        });
                    };
                    ui_dialog_setting.close=function (ev, ui) {
                        window.location.href = "index.php?option=com_tsmart&view=" + view+key_string;
                    };

                }else{
                    link_load_data=link_load_data+'&tmpl=component';
                    ui_dialog_setting.open=function (ev, ui) {
                        $("#"+ui_dialog_id).load(link_load_data, function() {

                        });

                    };
                }
                ui_dialog.dialog(ui_dialog_setting);





                dialog_close = function (close_ui_dialog_id,reload_iframe_id,link_reload,remove_ui_dialog) {

                    var $close_ui_dialog=$("#vm-edit-form");
                    if( typeof close_ui_dialog_id!='undefined' && close_ui_dialog_id!='')
                    {
                        $close_ui_dialog=$('#'+close_ui_dialog_id);
                    }
                    $close_ui_dialog.dialog("close");

                    if( typeof remove_ui_dialog!='undefined' && remove_ui_dialog=='true')
                    {
                        $close_ui_dialog.remove();
                    }
                    if(typeof  link_reload=='undefined')
                    {
                        link_reload="index.php?option=com_tsmart&view=" + view;
                    }
                    if( typeof reload_iframe_id!='undefined' && reload_iframe_id!='')
                    {
                        $('#'+reload_iframe_id).attr('src',$('#'+reload_iframe_id).attr('src') );
                    }else
                    {
                        window.location.href = link_reload;
                    }
                };
                change_height_dialog = function (ui_dialog_id,iframe_id,height) {

                    $("#"+ui_dialog_id).dialog("option", "height", height+200);
                    $('#'+iframe_id).height(height+100);

                }

            }
            $('.adminlist.table.table-striped').addClass('table-bordered');

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.asianventure = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('asianventure')) {
                var plugin = new $.asianventure(this, options);
                $(this).data('asianventure', plugin);

            }

        });

    }

})(jQuery);


