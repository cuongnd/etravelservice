(function ($) {

    // here we go!
    $.html_select_city = function (element, options) {

        // plugin's default options
        var defaults = {
            state_element:''
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_list_state_province = function (virtuemart_state_id) {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_virtuemart',
                        controller: 'cityarea',
                        task: 'ajax_get_list_city_by_state_id',
                        virtuemart_state_id:virtuemart_state_id
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
                    var item={};
                    item.virtuemart_cityarea_id=0;
                    item.city_area_name='Select city';
                    response.unshift(item);
                    $.set_date_selected(response,'virtuemart_cityarea_id','city_area_name',$element);
                    $element.trigger("liszt:updated");
                }
            });

        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var state_element=plugin.settings.state_element;
            $(state_element).change(function(){
                var virtuemart_state_id=$(this).val();
                plugin.set_list_state_province(virtuemart_state_id);
            });
            var virtuemart_country_id=$(state_element).val();
            plugin.set_list_state_province(virtuemart_country_id);

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_city = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_city')) {
                var plugin = new $.html_select_city(this, options);
                $(this).data('html_select_city', plugin);

            }

        });

    }

})(jQuery);


