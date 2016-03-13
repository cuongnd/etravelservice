(function ($) {

    // here we go!
    $.view_hoteladdon_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            task:''
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
            var task=plugin.settings.task;
            if(task=='add_new_item')
            {

                $element.dialog({
                    dialogClass:'asian-dialog-form',
                    modal: true,
                    width: 900,
                    title: 'Transfer add on',
                    show: {effect: "blind", duration: 800},
                    appendTo: 'body'
                });
            }
            $element.find("#adminForm").validate();
            $element.find('.toolbar .cancel').click(function(){
                $('input[name="task"]').val('cancel');
                $('#adminForm').submit();

            });


        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_hoteladdon_edit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_hoteladdon_edit')) {
                var plugin = new $.view_hoteladdon_edit(this, options);
                $(this).data('view_hoteladdon_edit', plugin);

            }

        });

    }

})(jQuery);


