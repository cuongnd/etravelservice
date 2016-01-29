(function ($) {

    // here we go!
    $.view_product_edit = function (element, options) {

        // plugin's default options
        var defaults = {
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
            var $tour_methor=$element.find('select[name="price_type"]');
            $tour_methor.change(function(){
                var value=$(this).val();
                if(value==0)
                {
                    $('input[name="list_group_size_id[]"]').attr("disabled", false);
                }else{
                    $('input[name="list_group_size_id[]"]').attr("disabled", true);
                }
            });

            if($tour_methor.val()==0)
            {
                $('input[name="list_group_size_id[]"]').attr("disabled", false);
            }else{
                $('input[name="list_group_size_id[]"]').attr("disabled", true);
            }

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_product_edit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_product_edit')) {
                var plugin = new $.view_product_edit(this, options);
                $(this).data('view_product_edit', plugin);

            }

        });

    }

})(jQuery);


