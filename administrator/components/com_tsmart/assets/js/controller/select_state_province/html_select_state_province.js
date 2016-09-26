(function ($) {

    // here we go!
    $.html_select_state_province = function (element, options) {

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
        plugin.set_list_state_province = function (tsmart_country_id) {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'state',
                        task: 'ajax_get_list_state_by_country_id',
                        tsmart_country_id:tsmart_country_id
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
                    item.tsmart_state_id=0;
                    item.state_name='Select state';
                    response.unshift(item);
                    var tsmart_state_id=plugin.settings.tsmart_state_id;
                    $.set_date_selected(response,'tsmart_state_id','state_name',$element,tsmart_state_id);

                    $element.trigger("liszt:updated");
                }
            });

        };
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            plugin.settings.tsmart_state_id=$element.val();
            var country_element=plugin.settings.country_element;
            $(country_element).change(function(){
                var tsmart_country_id=$(this).val();
                plugin.set_list_state_province(tsmart_country_id);
            });
            var tsmart_country_id=$(country_element).val();
            plugin.set_list_state_province(tsmart_country_id);

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_state_province = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_state_province')) {
                var plugin = new $.html_select_state_province(this, options);
                $(this).data('html_select_state_province', plugin);

            }

        });

    }

})(jQuery);


