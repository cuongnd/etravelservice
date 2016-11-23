//huong dan su dung
/*
 $('.view_order_default').view_order_default();

 view_order_default=$('.view_order_default').data('view_order_default');
 console.log(view_order_default);
 */

// jQuery Plugin for SprFlat admin view_order_default
// Control options and basic function of view_order_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.view_order_default = function (element, options) {

        // plugin's default options
        var defaults = {
            passenger_config: {
                senior_passenger_age_to: 99,
                senior_passenger_age_from: 60,
                adult_passenger_age_to: 59,
                adult_passenger_age_from: 18,
                teen_passenger_age_to: 17,
                teen_passenger_age_from: 12,
                children_1_passenger_age_to: 11,
                children_1_passenger_age_from: 9,
                children_2_passenger_age_to: 8,
                children_2_passenger_age_from: 6,
                infant_passenger_age_to: 5,
                infant_passenger_age_from: 0,

            },
            departure: {
                full_charge_children1: 0,
                full_charge_children2: 0
            },
            build_room:{},
            build_pre_transfer:{},
            build_post_transfer:{},
            extra_pre_night_hotel:{},
            extra_post_night_hotel:{},

            tour_min_age: 0,
            tour_max_age: 99,

            list_passenger: [
                {
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    date_of_birth: '',
                    template_html_room_item: ""
                }
            ]

            //main color scheme for view_order_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        plugin.show_passenger = function () {


        };
        plugin.notify = function (content, type) {
            if (typeof  type == "undefined") {
                type = "error";
            }
            var notify = $.notify(content, {
                allow_dismiss: true,
                type: type,
                placement: {
                    align: "right"
                }
            });
        };

        plugin.validate = function () {
            var $html_build_payment_cardit_card=$('.html_build_payment_cardit_card').data('html_build_payment_cardit_card');
            if($html_build_payment_cardit_card.validate()){
                return false;
            }else{
                return true;
            }

        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
            return passenger_full_name;
        };

        // the "constructor" method that gets called when the object is created
        plugin.get_item_tour_cost_and_room_price_by_passenger_index = function (tour_cost_and_room_price,passenger_index) {

            if(tour_cost_and_room_price.length>0){
                for (var i=0;i<tour_cost_and_room_price.length;i++){
                    var item_tour_cost_and_room_price=tour_cost_and_room_price[i];
                    if(item_tour_cost_and_room_price.passenger_index==passenger_index){
                        return item_tour_cost_and_room_price;
                    }
                }
            }
            return null;
        };

        plugin.get_coupon = function () {
            var coupon_code=$element.find('input[name="coupon_number"]').val();
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    var dataPost = {
                        option: 'com_virtuemart',
                        controller: 'bookprivategroupsumary',
                        task: 'ajax_get_coupon',
                        coupon_code: coupon_code
                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    $('.div-loading').css({
                        display: "block"
                    });
                },
                success: function (coupon) {

                    $('.div-loading').css({
                        display: "none"


                    });
                    plugin.update_price(coupon);

                }
            });

        };
        plugin.update_price= function (coupon) {
            if(typeof coupon!='undefined') {
                var total_price = plugin.settings.total_price;
                total_price = total_price - coupon.coupon_value;
                plugin.settings.total_price = total_price;
                plugin.settings.coupon=coupon;
                $element.find('input[name="coupon_number"]').prop('disabled', true);
                $element.find('.total_fee.price').html('$US ' + total_price);
            }
        }
        plugin.get_data=function(){
            return plugin.settings;
        }
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('.get_coupon').click(function(){
                plugin.get_coupon();
            });
            $element.find("#tabbed-nav").zozoTabs({
                theme: "silver",
                orientation: "horizontal",
                position: "top-left",
                size: "medium",
                animation: {
                    easing: "easeInOutExpo",
                    duration: 400,
                    effects: "slideH"
                },
                defaultTab: "tab1"
            });

            var $form=$element.find('form#bookprivategroupsumary');
            $form.submit(function()
            {
                if (plugin.validate()) {
                    return false;
                }
                var data=plugin.get_data();
                data=JSON.stringify(data);
                $form.find('input[name="booking_summary"]').val(data);
                $form.find('input[name="task"]').val('go_to_pay_now');

                return true;
            });
            $element.find('#order-tabbed-nav').zozoTabs({
                theme: "silver",
                orientation: "horizontal",
                position: "top-left",
                size: "medium",
                animation: {
                    easing: "easeInOutExpo",
                    duration: 400,
                    effects: "slideH"
                },
                defaultTab: "tab1"
            });





        };

        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_order_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_order_default')) {
                var plugin = new $.view_order_default(this, options);

                $(this).data('view_order_default', plugin);

            }

        });

    }

})(jQuery);
