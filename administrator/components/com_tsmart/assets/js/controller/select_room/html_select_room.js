(function ($) {

    // here we go!
    $.html_select_room = function (element, options) {

        // plugin's default options
        var defaults = {
            element_name:'',
            state_element:'',
            default:0
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_list_room = function (tsmart_hotel_id) {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'room',
                        task: 'ajax_get_list_room_by_hotel_id',
                        tsmart_hotel_id:tsmart_hotel_id
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
                    item.tsmart_room_id=0;
                    item.room_area_name='Select room';
                    response.unshift(item);
                    var element_name=plugin.settings.element_name;
                    var tsmart_room_id=$element.val();
                    $.set_data_selected(response,'tsmart_room_id','room_name',$element,tsmart_room_id);
                    $element.trigger("liszt:updated");
                }
            });

        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var state_element=plugin.settings.state_element;

            $(state_element).change(function(){
                var tsmart_hotel_id=$(this).val();
                plugin.set_list_room(tsmart_hotel_id);
            });
            var tsmart_hotel_id=$(state_element).val();

            plugin.set_list_room(tsmart_hotel_id);

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_room = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_room')) {
                var plugin = new $.html_select_room(this, options);
                $(this).data('html_select_room', plugin);

            }

        });

    }

})(jQuery);


