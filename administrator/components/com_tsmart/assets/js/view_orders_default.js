(function ($) {

    // here we go!
    $.view_orders_default = function (element, options) {
        // plugin's default options
        var defaults = {

            list_date: [],
            departure_item:{
                weekly:"",
                allow_passenger:"",
                days_seleted:'',
                sale_period_from:new Date(),
                sale_period_to:new Date()
            },
            is_load_ajax_get_departure:0


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
            $element.find('span.price').autoNumeric('init',{
                mDec:0,
                aSep:' ',
                aSign:'US$'
            });
            $element.find('.assign_user_id select.user').data('is_first',true);
            $element.find('.assign_user_id select.user').change(function () {
                var is_first=$(this).data('is_first');
                console.log(is_first);
                if(is_first==true)
                {
                    $(this).data('is_first',false);
                    return;
                }
                var assign_user_id=$(this).val();
                var $tr=$(this).closest('tr');
                var tsmart_order_id=$tr.data('tsmart_order_id');
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_order_assign_user',
                            tsmart_order_id: tsmart_order_id,
                            assign_user_id: assign_user_id

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
                        if (response.e == 1) {
                            $.notify({
                                // options
                                message: response.m
                            }, {
                                // settings
                                type: 'error'
                            });

                        } else {
                            $.notify({
                                // options
                                message: "update successful !"
                            }, {
                                // settings
                            });

                        }


                    }
                });

            });





        }
        plugin.update_calendar_price = function (start, end) {
            console.log(start);
            console.log(end);
        };


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_orders_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_orders_default')) {
                var plugin = new $.view_orders_default(this, options);
                $(this).data('view_orders_default', plugin);

            }

        });

    }

})(jQuery);


