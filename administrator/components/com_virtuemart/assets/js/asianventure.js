(function ($) {

    // here we go!
    $.asianventure = function (element, options) {

        // plugin's default options
        var defaults = {
            totalPages: 0,
            totalItem: 10,
            tour_id: 0,
            view: '',
            visiblePages: 5,
            add_new_popup: false,
            url: '',
            cid: [],
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
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            var url = plugin.settings.url;
            var link_create_new_tour = 'index.php?option=com_virtuemart&view=product&task=edit';
            console.log(url);
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
            $(document).on('shown.bs.tab', '.header-main-menu a[data-toggle="tab"]', function (e) {
                var $target = $(e.target); // activated tab
                var href = $target.attr('href');
                console.log(href);
                if (href == '#tour_build') {
                    window.location.href = link_create_new_tour;

                }
            });
            //$('.pagination.pagination-toolbar').appendTo('.toolbar-pagination');

            var add_new_popup = plugin.settings.add_new_popup;
            var cid = plugin.settings.cid;
            var view = plugin.settings.view;
            if (add_new_popup == 1) {
                $element.find("#vm-edit-form").dialog({
                    autoOpen: true,
                    modal: true,
                    width: 900,
                    title: view,
                    height: 1600,
                    open: function (ev, ui) {
                        console.log(cid);
                        $('#vm-iframe').attr('src', 'index.php?option=com_virtuemart&view=' + view + '&task=edit&cid[0]=' + cid[0] + '&show_in_parent_window=1').css({
                            width: '900px',
                            height: '1500px',

                        });
                    },
                    close: function (ev, ui) {
                        window.location.href = "index.php?option=com_virtuemart&view=" + view;
                    },
                    show: {effect: "blind", duration: 800},
                    appendTo: 'body',
                    //closeOnEscape: false,
                    //open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

                });
                dialog_close = function () {
                    $("#vm-edit-form").dialog("close");
                    window.location.href = "index.php?option=com_virtuemart&view=" + view;
                }
                change_height_dialog = function (height) {

                    $("#vm-edit-form").dialog("option", "height", height + 200);
                    $('#vm-iframe').height(height);

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


