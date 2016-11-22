(function ($) {

    // here we go!
    $.html_list_checkbox_group_size = function (element, options) {

        // plugin's default options
        var defaults = {
            list_selected:[]
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.format_selected = function () {
            var list_selected=plugin.settings.list_selected;
            console.log(list_selected);
            $.each(list_selected,function(index,tsmart_group_size_id){
                $('input[name="list_group_size_id[]"][value="'+tsmart_group_size_id+'"]').trigger('change');
            })
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);

            $element.find('input[name="list_group_size_id[]"]').change(function(){
                var self=$(this);
                var from=$(this).data('from');
                if(self.is(':checked'))
                {
                    $('input[name="list_group_size_id[]"][data-from="'+from+'"]').prop( "disabled", true );
                    self.prop( "disabled", false );

                }else{
                    $('input[name="list_group_size_id[]"][data-from="'+from+'"]').prop( "disabled", false );
                }
            });
            plugin.format_selected();


        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_list_checkbox_group_size = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_list_checkbox_group_size')) {
                var plugin = new $.html_list_checkbox_group_size(this, options);
                $(this).data('html_list_checkbox_group_size', plugin);

            }

        });

    }

})(jQuery);


