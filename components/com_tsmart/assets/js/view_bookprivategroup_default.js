//huong dan su dung
/*
 $('.view_bookprivategroup_default').view_bookprivategroup_default();

 view_bookprivategroup_default=$('.view_bookprivategroup_default').data('view_bookprivategroup_default');
 console.log(view_bookprivategroup_default);
 */

// jQuery Plugin for SprFlat admin view_bookprivategroup_default
// Control options and basic function of view_bookprivategroup_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.view_bookprivategroup_default = function (element, options) {

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

            //main color scheme for view_bookprivategroup_default
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        plugin.update_price = function () {
            var departure = plugin.settings.departure;
            var $html_build_room = $('#html_build_room').data('html_build_room');
            var list_room = $html_build_room.get_list_room();// $html_build_room.settings.list_room;
            var total_tour_cost=0;
            var room_total_price=0;
            for(var i=0;i<list_room.length;i++)
            {
                var room_item=list_room[i];
                var tour_cost_and_room_price=room_item.tour_cost_and_room_price;
                if(typeof tour_cost_and_room_price!="undefined")
                {

                    tour_cost_and_room_price.forEach(function(item_tour_cost_and_room_price) {
                        total_tour_cost+=item_tour_cost_and_room_price.tour_cost;

                        room_total_price+=item_tour_cost_and_room_price.room_price;
                        room_total_price+=item_tour_cost_and_room_price.extra_bed_price;
                    });
                }


            }
            $element.find('.booking-summary-content .passenger-service-fee-total').autoNumeric('set', total_tour_cost);

            var list_passenger = $html_build_room.get_list_passenger(); //$html_build_room.settings.list_passenger;

            $element.find('.room-service-fee-total').autoNumeric('set', room_total_price);


        };
        plugin.show_passenger = function () {
            var $html_build_room = $('#html_build_room').data('html_build_room');
            var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
            $html_input_passenger.settings.event_after_change = function (list_passenger) {
                var $list_passenger = $element.find('.list_passenger');
                var total_passenger = list_passenger.length;
                $element.find('.booking-summary-content .total-passenger').html(total_passenger);
                $list_passenger.empty();
                for (var i = 0; i < total_passenger; i++) {
                    var passenger = list_passenger[i];
                    var full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
                    var $li = $('<li>' + full_name + '</li>');
                    $li.appendTo($list_passenger);
                }
                $html_build_room.update_passengers(list_passenger);
                plugin.update_price();

            }
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

            var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
            var $html_build_form_contact=$('.html_build_form_contact').data('html_build_form_contact');
            var $html_build_room = $('#html_build_room').data('html_build_room');
            if (!$html_input_passenger.validate()) {
                return false;
            } else if (!$html_build_form_contact.validate()) {
                return false;
            } else if (!$html_build_room.validate()) {
                return false;
            }
            var $html_build_room = $('#html_build_room').data('html_build_room');
            var list_room = $html_build_room.get_list_room();// $html_build_room.settings.list_room;
            plugin.update_room(list_room);
            plugin.update_price();
            var passenger_not_inside_room = $html_build_room.find_passenger_not_inside_room();
            if (passenger_not_inside_room.length > 0) {
                plugin.notify('there are some person not set room');
                return false;
            }

            return true;
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

        plugin.update_room = function (list_room) {
            var $html_build_room = $('#html_build_room').data('html_build_room');
            var list_passenger = $html_build_room.settings.list_passenger;
            $element.find('.booking-summary-content .list-room').empty();


            var $template_html_room_item = $(plugin.settings.template_html_room_item);
            $template_html_room_item.appendTo($element.find('.booking-summary-content .list-room'));
            var $list_passenger_room=$element.find('.booking-summary-content .list-room .list_passenger_room');
            var room_total_price=0;
            for (var i = 0; i < list_room.length; i++) {
                var room_item = list_room[i];

                var passengers = room_item.passengers;
                var room_type = room_item.list_room_type;
                var tour_cost_and_room_price = room_item.tour_cost_and_room_price;
                //$template_html_room_item.find('.room-type').html(room_type);
                var total_price_per_room=0;
                if(passengers.length>0)
                {
                    for (j = 0; j < passengers.length; j++) {
                        var passenger_index = passengers[j];
                        var full_name = plugin.get_passenger_full_name(list_passenger[passenger_index]);
                        var total_price_per_passenger=0;
                        var passenger_note="";
                        if(typeof tour_cost_and_room_price!="undefined")
                        {
                            var item_tour_cost_and_room_price=plugin.get_item_tour_cost_and_room_price_by_passenger_index(tour_cost_and_room_price,passenger_index);

                            if(item_tour_cost_and_room_price!=null)
                            {
                                total_price_per_passenger=item_tour_cost_and_room_price.room_price+item_tour_cost_and_room_price.extra_bed_price;
                                var passenger_note=item_tour_cost_and_room_price.msg;
                            }
                        }
                        total_price_per_room+=total_price_per_passenger;
                        var $li = $('<li>' + full_name + ' <span class=""><b>'+total_price_per_passenger+'$</b></span><span data-tipso-content="'+passenger_note+'" class="pull-right icon-question "></span></li>');
                        if(total_price_per_passenger>0)
                        {
                            $li.appendTo($list_passenger_room);
                        }
                        room_total_price+=total_price_per_passenger;

                    }
                }
            }
            $element.find('.booking-summary-content .list-room .icon-question').tipso({
                size: 'tiny',
                useTitle: false,
                animationIn: 'bounceInDown'
            }).addClass('error');
            $element.find('.room-service-fee-total').autoNumeric('set', room_total_price);


        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
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
            plugin.settings.template_html_room_item = $element.find('.booking-summary-content .list-room .list_passenger_room').getOuterHTML();
            $element.find('.booking-summary-content .list-room').empty();
            $element.find('.passenger-service-fee-total').autoNumeric('init');
            $element.find('.room-service-fee-total').autoNumeric('init');
            $element.find('.table-trip .body .item  .body-item').on('hidden', function () {
                // do something…
                var $item = $(this).closest('.item');
                $item.removeClass('in');
                $item.find('.header-item > .service-class-price').addClass('hide');
                $item.find('.header-item > .service-class,.header-item > .price').removeClass('hide');
                console.log($item);
            });
            $element.find('.table-trip .body .item  .body-item').on('show', function () {
                // do something…
                var $item = $(this).closest('.item');
                $item.addClass('in');
                $item.find('.header-item > .service-class-price').removeClass('hide');
                $item.find('.header-item > .service-class,.header-item > .price').addClass('hide');
                console.log($item);
            });
            $element.find('span.price').autoNumeric('init');
            $element.find('button.book-now').click(function () {
                var $item = $(this).closest('.item');
                var virtuemart_price_id = $item.data('virtuemart_price_id');
                var $form = $element.find('form#tour_price');
                $form.find('input[name="virtuemart_price_id"]').val(virtuemart_price_id);
                $form.find('input[name="task"]').val('book_now');

                console.log($form);
            });
            plugin.show_passenger();
            var $form=$element.find('form#bookprivategroup');
            $form.submit(function()
            {
                if (!plugin.validate()) {
                    return false;
                }
                var $form=$element.find('form#bookprivategroup');
                $form.find('input[name="task"]').val('go_to_booking_add_on_from');
                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
                var list_passenger=$html_input_passenger.settings.list_passenger;
                var a_list_passenger=[];
                var list_children_infant=list_passenger.children_infant;
                for(var i=0;i<list_children_infant.length;i++){
                    a_list_passenger.push(list_children_infant[i]);
                }

                var list_senior_adult_teen=list_passenger.senior_adult_teen;
                for(var i=0;i<list_senior_adult_teen.length;i++){
                    a_list_passenger.push(list_senior_adult_teen[i]);
                }
                a_list_passenger=JSON.stringify(a_list_passenger);
                $.cookie('cookie_list_passenger', a_list_passenger);


                var list_room = $html_build_room.get_list_room();
                var a_list_room=JSON.stringify(list_room);
                $.cookie('cookie_list_room', a_list_room);

                $form.find('input[name="build_room"]').val(a_list_room);

                var $html_build_form_contact=$('.html_build_form_contact').data('html_build_form_contact');
                var contact_data=$html_build_form_contact.get_data();
                contact_data=JSON.stringify(contact_data);
                $.cookie('contact_data', contact_data);
                $form.find('input[name="contact_data"]').val(contact_data);

                return true;
            });

            var $html_build_room = $('#html_build_room').data('html_build_room');
            $html_build_room.settings.trigger_after_change = function (list_room) {
                plugin.update_room(list_room);
                plugin.update_price();
            };

        };

        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_bookprivategroup_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_bookprivategroup_default')) {
                var plugin = new $.view_bookprivategroup_default(this, options);

                $(this).data('view_bookprivategroup_default', plugin);

            }

        });

    }

})(jQuery);
