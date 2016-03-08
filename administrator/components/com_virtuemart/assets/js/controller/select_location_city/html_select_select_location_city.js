(function ($) {

    // here we go!
    $.html_select_location_city = function (element, options) {

        // plugin's default options
        var defaults = {
            country_element:'',
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_list_state_province = function (virtuemart_country_id) {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_virtuemart',
                        controller: 'state',
                        task: 'ajax_get_list_state_by_country_id',
                        virtuemart_country_id:virtuemart_country_id
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
                    item.virtuemart_state_id=0;
                    item.state_name='Select state';
                    response.unshift(item);
                    $.set_date_selected(response,'virtuemart_state_id','state_name',$element);
                    $element.trigger("liszt:updated");
                }
            });

        };
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            var country_element=plugin.settings.country_element;
            $(country_element).change(function(){
                var virtuemart_country_id=$(this).val();
                plugin.set_list_state_province(virtuemart_country_id);
            });
            var virtuemart_country_id=$(country_element).val();
            plugin.set_list_state_province(virtuemart_country_id);

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_location_city = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_location_city')) {
                var plugin = new $.html_select_location_city(this, options);
                $(this).data('html_select_location_city', plugin);

            }

        });

    }

})(jQuery);


