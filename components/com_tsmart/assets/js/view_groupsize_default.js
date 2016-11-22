(function ($) {

    // here we go!
    $.view_groupsize_default = function (element, options) {

        // plugin's default options
        var defaults = {
            list_group_size:[]
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
            var list_group_size=plugin.settings.list_group_size;
            var tsmart_group_size_id=parseInt($('input[name="tsmart_group_size_id"]').val());
            $element.find('input[name="from"]').change(function(){

                $('.input_number_to').autoNumeric('set', '');
                $('input[name="to"]').val('');


            });
            $element.find('input[name="to"]').change(function(){
                var to=parseInt($(this).val());
                var tsmart_group_size_id=$('input[name="tsmart_group_size_id"]').val();
                var from=parseInt($('input[name="from"]').val());
                if(to<from)
                {
                    $('.input_number_to').autoNumeric('set', '');
                    $(this).val('');
                    alert('to must less then from, please select other');
                    return false;
                }

                var list_number_disable=[];
                for(var i=0;i<list_group_size.length;i++)
                {
                    var group_size=list_group_size[i];
                    if(group_size.type!=='flat_price' && group_size.tsmart_group_size_id!=tsmart_group_size_id)
                    {
                        if(from==parseInt(group_size.from)&&to==parseInt(group_size.to)){
                            alert('group size exists, please select other');
                            $('.input_number_to').autoNumeric('set', '');
                            $(this).val('');
                            break;
                        }

                    }
                }

            });
        };

        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.view_groupsize_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_groupsize_default')) {
                var plugin = new $.view_groupsize_default(this, options);
                $(this).data('view_groupsize_default', plugin);

            }

        });

    }

})(jQuery);


