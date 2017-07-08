(function ($) {

    // here we go!
    $.view_orders_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            task:'',
            config_show_price: {
                mDec: 1,
                aSep: ' ',
                vMin: -9999,
                aSign: 'US$'
            },
            passenger_cost_config: {
                mDec: 1,
                aSep: ' ',
                vMin: '-999.00',
                aSign: ''
            },
            numeric_tax_config: {
                mDec: 1,
                aSep: ' ',
                vMin: '-999.00',
                aSign: ''
            },
            list_passenger_not_in_room:[]
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.calculator_price = function ($tr) {
            var tour_fee=$tr.find('input.tour_fee').autoNumeric('get');
            var single_room_fee=$tr.find('input.single_room_fee').autoNumeric('get');
            var extra_fee=$tr.find('input.extra_fee').autoNumeric('get');
            var discount_fee=$tr.find('input.discount_fee').autoNumeric('get');
            var payment=$tr.find('input.payment').autoNumeric('get');
            var cancel_fee=$tr.find('input.cancel_fee').autoNumeric('get');
            var $total_cost=$tr.find('input.total_cost');
            var total_cost=parseFloat(tour_fee)+parseFloat(single_room_fee)+parseFloat(extra_fee)-parseFloat(discount_fee);
            $total_cost.autoNumeric('set',total_cost);
            var $balance=$tr.find('input.balance');
            var balance=parseFloat(total_cost)-parseFloat(payment);
            $balance.autoNumeric('set',balance);
            var $refund=$tr.find('input.refund');
            var refund=parseFloat(payment)-parseFloat(cancel_fee);
            $refund.autoNumeric('set',refund);
        };
        plugin.hotel_add_on_calculator_price = function ($tr) {
            var night_hotel_fee=$tr.find('input.night_hotel_fee').autoNumeric('get');
            var discount=$tr.find('input.night_discount').autoNumeric('get');
            var total_night=$tr.find('input.total_night').val();
            var payment=$tr.find('input.night_payment').autoNumeric('get');
            var cancel_fee=$tr.find('input.night_cancel_fee').autoNumeric('get');
            var $total_cost=$tr.find('input.total_cost');
            var total_cost=parseFloat(night_hotel_fee)*total_night-parseFloat(discount);
            $total_cost.autoNumeric('set',total_cost);
            var $balance=$tr.find('input.night_balance');
            var balance=parseFloat(total_cost)-parseFloat(payment);
            $balance.autoNumeric('set',balance);
            var $refund=$tr.find('input.night_refund');
            var refund=parseFloat(payment)-parseFloat(cancel_fee);
            $refund.autoNumeric('set',refund);
        };
        plugin.fill_data_passenger_cost = function (list_row) {
            for(var i=0;i<list_row.length;i++){
                var row=list_row[i];
                var $tr= $('table.edit_passenger_cost').find('tbody tr.passenger:eq('+i+')');
                $tr.find('input.tsmart_passenger_id').val(row.tsmart_passenger_id);
                $tr.find('input.tour_fee').autoNumeric('set',parseFloat(row.tour_fee));
                $tr.find('input.single_room_fee').autoNumeric('set',parseFloat(row.single_room_fee));
                $tr.find('input.extra_fee').autoNumeric('set',parseFloat(row.extra_fee));
                $tr.find('input.discount_fee').autoNumeric('set',parseFloat(row.discount_fee));
                $tr.find('input.payment').autoNumeric('set',parseFloat(row.payment));
                $tr.find('input.cancel_fee').autoNumeric('set',parseFloat(row.cancel_fee));
                plugin.calculator_price($tr);
            }

        };
        plugin.fill_data_passenger_cost_edit_hotel_add = function (list_passenger,type,hotel_addon_detail) {
            var $tbody=$(".order_edit_passenger_cost_edit_hotel_add_on").find('table.edit_passenger_cost_hotel_addon_edit_passenger tbody');
            $tbody.empty();
            for(var i=0;i<list_passenger.length;i++){
                var passenger=list_passenger[i];
                var $tr= $( plugin.settings.row_passenger_cost_edit_hotel_addon);
                $tr.appendTo($tbody);
                $tr.find('.order').html(i+1);
                $tr.find('.passenger_cost').autoNumeric('init', plugin.settings.passenger_cost_config);
                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
                $tr.find('input.tsmart_passenger_id').val(passenger.tsmart_passenger_id);
                $tr.find('input.total_night').val(hotel_addon_detail.total_night);
                $tr.find('input.night_hotel_fee').autoNumeric('set',parseFloat(passenger.unit_price_per_night));
                $tr.find('input.night_discount').autoNumeric('set',parseFloat(passenger[type+'_night_discount']));
                $tr.find('input.night_payment').autoNumeric('set',parseFloat(passenger[type+'_night_payment']));
                $tr.find('input.night_cancel_fee').autoNumeric('set',parseFloat(passenger[type+'_night_cancel_fee']));
                plugin.hotel_add_on_calculator_price($tr);
            }
            $tbody.find('.passenger_cost').autoNumeric('init',plugin.settings.config_show_price).change(function(){
                var $tr=$(this).closest('tr.passenger');
                plugin.hotel_add_on_calculator_price($tr);
            });;

        };
        plugin.fill_data_service_cost_edit_hotel_add = function (data_price) {
            var $tbody=$(".view_orders_edit_form_edit_service_cost_edit_hotel").find('table.service_cost_edit_hotel tbody');
            $tbody.empty();
            for(var key in data_price) {
                var room=data_price[key];
                var $tr= $( plugin.settings.row_service_cost_edit_hotel_addon);
                $tr.appendTo($tbody);
                $tr.find('.room_type').html(room.room_type);
                $tr.find('.net_price').html(room.net_price);
                $tr.find('.mark_up').html(room.mark_up_price);
                $tr.find('.tax').html(room.tax);
                $tr.find('.sale_price').html(room.sale_price);
            }

            $tbody.find('.service_cost').autoNumeric('init', plugin.settings.config_show_price);
            $tbody.find('.tax').autoNumeric('init', plugin.settings.numeric_tax_config);

        };
        plugin.fill_data_rooming_hotel_add_on = function (list_rooming) {
            var $tbody=$(".view_orders_edit_order_bookinginfomation_hotel_addon").find('table.rooming-list tbody');
            $tbody.empty();
            var i=1;
            for(var key in list_rooming) {
                var passengers=list_rooming[key];
                var $tr= $( plugin.settings.tr_rooming_edit_hotel);
                $tr.appendTo($tbody);
                $tr.find('.order').html(i);
                var list_full_name=[];
                var list_title=[];
               var room_type='';
               var room_note='';
               var room_created_on='';
                for(var passenger_index in passengers) {
                    var passenger=passengers[passenger_index];
                    room_type=passenger.room_type;
                    room_note=passenger.room_note;
                    room_created_on=passenger.room_created_on;
                    var full_name=plugin.get_passenger_full_name(passenger);
                    list_full_name.push(full_name);
                    list_title.push(passenger.title);
                }

                $tr.find('.full-name').html(list_full_name.join("<br/>"));
                $tr.find('.title').html(list_title.join("<br/>"));
                $tr.find('.room-type').html(room_type);
                $tr.find('.room-note').html(room_note);
                $tr.find('.room-created-on').html(room_created_on);
                i++;
            }

            $tbody.find('.service_cost').autoNumeric('init', plugin.settings.config_show_price);
            $tbody.find('.tax').autoNumeric('init', plugin.settings.numeric_tax_config);

        };
        plugin.fill_data_rooming_hotel_add_on2 = function (list_rooming) {
            var $tbody=$(".view_orders_edit_form_edit_room_in_hotel_add_on").find('table.rooming_hotel_add_on tbody');
            $tbody.empty();
            var i=1;
            for(var key in list_rooming) {
                var passengers=list_rooming[key];
                var $tr= $( plugin.settings.tr_rooming_edit_hotel_add_on);
                $tr.appendTo($tbody);
                $tr.find('.order').html(i);
                var list_title=[];
               var room_type='';
               var room_note='';
               var room_created_on='';
                for(var passenger_index in passengers) {
                    var passenger=passengers[passenger_index];
                    room_type=passenger.room_type;
                    room_note=passenger.room_note;
                    room_created_on=passenger.room_created_on;
                    var full_name=plugin.get_passenger_full_name(passenger);
                    var $full_name=$('<div class="passenger-item">'+full_name+'</div>');
                    $full_name.data('passenger',passenger);
                    $full_name.appendTo( $tr.find('.full-name'));
                    list_title.push(passenger.title);
                }
                $tr.data('tsmart_order_hotel_addon_id',key);
                $tr.find('.title').html(list_title.join("<br/>"));
                $tr.find('.room-type').html(room_type);
                $tr.find('.room-note').html(room_note);
                $tr.find('.room-created-on').html(room_created_on);
                i++;
            }
            $tbody.find('a.remove-passenger-from-rooming-list').click(function(){
                var $tr=$(this).closest('.item-rooming');
                plugin.ajax_remove_passenger_from_rooming_list($tr);
            });


        };
        plugin.ajax_remove_passenger_from_rooming_list = function ($tr) {
            var $passengers=$tr.find('td .passenger-item');
            var  $html_build_rooming_hotel_add_on_rooming_list=$('#html_build_rooming_hotel_rooming_list_hotel_add_on').data('html_build_rooming_list');
            var list_passenger=$html_build_rooming_hotel_add_on_rooming_list.settings.list_passenger;
            for(var i=0;i<$passengers.length;i++){
                var passenger=$($passengers.get(i)).data('passenger');
                list_passenger.push(passenger);
            }

            var $tbody=$(".view_orders_edit_form_edit_room_in_hotel_add_on").find('table.rooming_hotel_add_on tbody');
            var i=1;
            $tbody.find('tr').each(function(){
                $(this).find('.order').html(i);
                i++;
            });
            $html_build_rooming_hotel_add_on_rooming_list.reset_build_rooming();
            var $ul=$(".view_orders_edit_form_edit_room_in_hotel_add_on").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            for(var i=0;i<$passengers.length;i++){
                var passenger=$($passengers.get(i)).data('passenger');
                var full_name=plugin.get_passenger_full_name(passenger);
                var $li= $('<li class="tags"><a href="javascript:void(0)">'+full_name+'</a></li>');
                $li.appendTo($ul);
            }
            $tr.remove();
        }
        plugin.fill_data_passenger_not_in_room_hotel_add_on = function (list_passenger_not_in_room) {
            var $ul=$(".view_orders_edit_form_edit_room_in_hotel_add_on").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            $ul.empty();
            for(var key in list_passenger_not_in_room) {
                var passenger=list_passenger_not_in_room[key];
                var full_name=plugin.get_passenger_full_name(passenger);
                var $li= $('<li class="tags"><a href="javascript:void(0)">'+full_name+'</a></li>');
                $li.appendTo($ul);
            }


        };
        plugin.fill_data_passenger_not_in_room_hotel_add_on2 = function (list_passenger_not_in_room) {
            var $ul=$(".view_orders_edit_order_bookinginfomation_hotel_addon").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            $ul.empty();
            for(var key in list_passenger_not_in_room) {
                var passenger=list_passenger_not_in_room[key];
                var full_name=plugin.get_passenger_full_name(passenger);
                var $li= $('<li class="tags"><a href="javascript:void(0)">'+full_name+'</a></li>');
                $li.appendTo($ul);
            }


        };
        plugin.calculator_price_show = function ($tr) {
            var total_cost=$tr.find('span.total_cost').autoNumeric('get');
            var payment=$tr.find('span.payment').autoNumeric('get');
            var cancel_fee=$tr.find('span.cancel_fee').autoNumeric('get');
            var $balance=$tr.find('span.balance');
            var balance=parseFloat(total_cost)-parseFloat(payment);
            $balance.autoNumeric('set',balance);
            var $refund=$tr.find('span.refund');
            var refund=parseFloat(payment)-parseFloat(cancel_fee);
            $refund.autoNumeric('set',refund);
        };
        plugin.fill_data_passenger_show = function (list_row) {
            for(var i=0;i<list_row.length;i++){
                var row=list_row[i];
                var $tr= $('table.orders_show_form_passenger').find('tbody tr.passenger:eq('+i+')');
                $tr.find('span.total_cost').autoNumeric('set',parseFloat(row.total_cost));
                $tr.find('span.payment').autoNumeric('set',parseFloat(row.payment));
                $tr.find('span.cancel_fee').autoNumeric('set',parseFloat(row.cancel_fee));
                plugin.calculator_price_show($tr);
            }
        };
        plugin.update_data_order = function () {
            var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_order_detail_by_order_id',
                        tsmart_order_id:tsmart_order_id
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
                    var order_data=response.r.order_data;
                    var list_row=response.list_row;
                    plugin.fill_data_passenger_show(list_row);

                }
            });
        };
        plugin.update_booking_infomation = function (response) {
            var $tr_main_tour= $('table.listallservicebooking').find('tr.main-tour');
            $tr_main_tour.find('td.total-passenger').html(response.total_passenger);
            $tr_main_tour.find('td.service_date').html(response.service_date);
            $element.find('.booking-detail .service_date').html(response.departure_date);
            $element.find('.booking-detail .assign-name').html(response.assign_name);

        };
        plugin.update_orders_show_form_passenger = function (list_row) {
            for(var i=0;i<list_row.length;i++){
                var row=list_row[i];
                var $tr= $('table.orders_show_form_passenger').find('tbody tr.passenger:eq('+i+')');
                $tr.find('select.passenger_status').val(row.passenger_status).trigger('change');
                $tr.find('input[name="tsmart_passenger_id[]"]').val(row.tsmart_passenger_id);
            }
        };
        plugin.change_status_passenger_in_hotel_add_on = function (passenger,status) {
            var type=$('.order_edit_night_hotel').find('input[name="type"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_passenger_in_hotel_add_on',
                        status:status,
                        type:type,
                        tsmart_passenger_id:passenger.tsmart_passenger_id
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
                    if(response.e==0){
                        alert('change status passenger successful');
                    }
                }
            });

        };
        plugin.update_orders_show_form_hotel_addon_passenger = function (list_passenger,type) {
            var $tbody=$(".order_edit_night_hotel").find('table.orders_show_form_passenger_edit_hotel_addon tbody');
            $tbody.empty();
            for(var i=0;i<list_passenger.length;i++){
                var passenger=list_passenger[i];
                var $tr=$(plugin.setting_row_passenger_edit_hotel_addon);
                $tr.data('passenger',passenger);
                $tr.find('.tsmart_passenger_id').html(passenger.tsmart_passenger_id);
                $tr.find('select.passenger_status').val(passenger[type+'_night_status']);
                $tr.find('.book_date').html(passenger.created_on);
                $tr.find('.total_cost').html(passenger[type+'_night_total_cost']);
                $tr.find('.payment').html(passenger[type+'_night_payment']);
                $tr.find('.balance').html(passenger[type+'_night_balance']);
                $tr.find('.cancel_fee').html(passenger[type+'_night_cancel_fee']);
                $tr.find('.refund').html(passenger[type+'_night_refund']);
                $tr.find('.cost').autoNumeric('init', plugin.settings.config_show_price);
                $tr.appendTo($tbody);

                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
            }
            $tbody.find('select.passenger_status').change(function(){
                var passenger=$(this).closest('tr.passenger').data('passenger');
                var status=$(this).val();
                plugin.change_status_passenger_in_hotel_add_on(passenger,status);
            });
        };
        plugin.update_orders_show_form_general = function (response) {
            var order=response.r;
            var terms_condition=order.terms_condition;
            var reservation_notes=order.reservation_notes;
            var itinerary=order.itinerary;
            $('.edit-form-general').find('#terms_condition').val(terms_condition);
            $('.edit-form-general').find('#reservation_notes').val(reservation_notes);
            $('.edit_form_booking_summary').find('select#tsmart_orderstate_id').val(order.tsmart_orderstate_id).trigger('change');
            tinymce.get("itinerary").setContent(itinerary);
            //$('.edit-form-general').find('#terms_condition').val(terms_condition);
        };
        plugin.update_orders_show_form_general_edit_hotel_addon = function (response,type) {
            var order_detail=response.order_detail;
            var hotel_addon_detail=response.hotel_addon_detail;
            var terms_condition=hotel_addon_detail.terms_condition;
            var reservation_notes=hotel_addon_detail.reservation_notes;
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.hotel_name').val(hotel_addon_detail.hotel_name);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.check_in_date').val(hotel_addon_detail.show_checkin_date);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.check_out_date').val(hotel_addon_detail.show_checkout_date);
            $(".order_edit_night_hotel").find('input[name="tsmart_group_hotel_addon_order_id"]').val(hotel_addon_detail.tsmart_group_hotel_addon_order_id);
            $(".order_edit_night_hotel").find('input[name="tsmart_hotel_addon_id"]').val(hotel_addon_detail.tsmart_hotel_addon_id);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.total_cost').html(hotel_addon_detail[type+"_total_cost"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.net_price').html(hotel_addon_detail[type+"_total_cost"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.discount').html(hotel_addon_detail[type+"_total_cost"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.commission').html(hotel_addon_detail[type+"_total_cost"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.payment').html(hotel_addon_detail[type+"_total_payment"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.balance').html(hotel_addon_detail[type+"_total_balance"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.cancel').html(hotel_addon_detail[type+"_total_cancel"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.balance').html(hotel_addon_detail[type+"_total_cost"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.refund').html(hotel_addon_detail[type+"_total_refund"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary span.profit').html(hotel_addon_detail[type+"_total_cost"]);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary select[name="hotel_add_on_status"]').val(hotel_addon_detail.group_status).trigger('change.select2');;
            $(".order_edit_night_hotel").find('input[name="type"]').val(type);
            //$('.edit_form_booking_summary').find('select#tsmart_orderstate_id').val(order.tsmart_orderstate_id).trigger('change');
            //$('.edit-form-general').find('#terms_condition').val(terms_condition);
            var data_price=hotel_addon_detail.data_price;
            plugin.fill_data_service_cost_edit_hotel_add(data_price);
            plugin.fill_data_rooming_hotel_add_on(response.list_rooming);

        };
        plugin.update_orders_show_form_general_edit_hotel_addon2 = function (response,type) {
            var order_detail=response.order_detail;
            var hotel_addon_detail=response.hotel_addon_detail;
            var terms_condition=hotel_addon_detail.terms_condition;
            var reservation_notes=hotel_addon_detail.reservation_notes;
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('input.hotel_name').val(hotel_addon_detail.hotel_name);

            var $select_date_night_hotel_checkin_date=$('#select_date_night_hotel_checkin_date').data('html_select_date');
            $select_date_night_hotel_checkin_date.set_date(hotel_addon_detail.checkin_date);

            var $select_date_night_hotel_checkout_date=$('#select_date_night_hotel_checkout_date').data('html_select_date');
            $select_date_night_hotel_checkout_date.set_date(hotel_addon_detail.checkout_date);
        };
        plugin.fill_data_passenger_detail = function (passenger_data) {
            var $view_orders_edit_form_add_and_remove_passenger=$(".view_orders_edit_form_add_and_remove_passenger");
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="title"]').val(passenger_data.title);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="first_name"]').val(passenger_data.first_name);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="middle_name"]').val(passenger_data.middle_name);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="last_name"]').val(passenger_data.last_name);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="date_of_birth"]').val(passenger_data.date_of_birth);
            $view_orders_edit_form_add_and_remove_passenger.find('input#select_date_picker_date_of_birth').val(passenger_data.date_of_birth);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="nationality"]').val(passenger_data.nationality);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="passport_no"]').val(passenger_data.passport_no);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="issue_date"]').val(passenger_data.issue_date);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="expiry_date"]').val(passenger_data.expiry_date);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="phone_no"]').val(passenger_data.phone_no);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="email_address"]').val(passenger_data.email_address);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="confirm_email"]').val(passenger_data.email_address);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="street_address"]').val(passenger_data.street_address);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="suburb_town"]').val(passenger_data.suburb_town);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="postcode_zip"]').val(passenger_data.postcode_zip);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="state_province"]').val(passenger_data.state_province);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="res_country"]').val(passenger_data.res_country);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="emergency_contact_name"]').val(passenger_data.emergency_contact_name);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="emergency_contact_email"]').val(passenger_data.emergency_contact_email);
            $view_orders_edit_form_add_and_remove_passenger.find('input[name="emergency_contact_phone_no"]').val(passenger_data.emergency_contact_phone_no);
            $view_orders_edit_form_add_and_remove_passenger.find('input[type="hidden"][name="tsmart_passenger_id"]').val(passenger_data.tsmart_passenger_id);
        };
        plugin.validate_input_passenger = function () {
            var $form_passenger=$(".view_orders_edit_form_add_and_remove_passenger");
            var $email_address=$form_passenger.find('input[name="email_address"]');
            var email_address=$email_address.val();
            var $confirm_email=$form_passenger.find('input[name="confirm_email"]');
            var confirm_email=$confirm_email.val();
            for(var i=0;i<$form_passenger.find('input[required="required"]').length;i++){
                var $input=$form_passenger.find('input[required="required"]:eq('+i+')');
                if($input.val().trim()==""){
                    alert("please input required filed");
                    $input.focus();
                    return false;
                }
            }
            if(!$.is_email(email_address)){
                alert("email address invaild");
                $email_address.focus();
                return false;
            }else if(!$.is_email(confirm_email)) {
                alert("email address confirm invaild");
                $confirm_email.focus();
                return false;
            }else if(email_address!=confirm_email) {
                alert("confirm email not same email address");
                $confirm_email.focus();
                return false;
            }
            return true;
        };
        plugin.validate_add_passenger_to_room = function () {
            var $form_add_passenger_to_room=$(".view_orders_edit_form_edit_passenger");
            for(var i=0;i<$form_add_passenger_to_room.find('.passenger-item').length;i++) {
                var $passenger_item = $form_add_passenger_to_room.find('.passenger-item:eq(' + i + ')');
                var $select_passenger=$passenger_item.find('select.list-passenger');
                var select_passenger=$select_passenger.val();
                if(select_passenger==0){
                    alert("please select passenger");
                    $select_passenger.focus();
                    return false;
                }
            }
            return true;
        };
        plugin.fill_data_row_passenger_detail = function (passenger_data) {
            var $tr_passenger=$(".view_orders_edit_passenger_manager").find('tr.item-passenger[data-tsmart_passenger_id="'+passenger_data.tsmart_passenger_id+'"]');
            if($tr_passenger.length==0){
                var tr_passenger=$(".view_orders_edit_passenger_manager").find('tr.item-passenger:first-child').getOuterHTML();
                $tr_passenger=$(tr_passenger);
                $tr_passenger.insertAfter($(".view_orders_edit_passenger_manager").find("tr.item-passenger:last-child"));
            }
            $tr_passenger.find('span.title').html(passenger_data.title);
            $tr_passenger.find('div.tsmart_passenger_id span.cid').html(passenger_data.tsmart_passenger_id);
            $tr_passenger.find('div.tsmart_passenger_id input[name="cid[]"]').val(passenger_data.tsmart_passenger_id);
            $tr_passenger.attr("data-tsmart_passenger_id",passenger_data.tsmart_passenger_id);
            $tr_passenger.data("tsmart_passenger_id",passenger_data.tsmart_passenger_id);
            $tr_passenger.find('span.first_name').html(passenger_data.first_name);
            $tr_passenger.find('span.middle_name').html(passenger_data.middle_name);
            $tr_passenger.find('span.last_name').html(passenger_data.last_name);
            $tr_passenger.find('span.date_of_birth').html(passenger_data.date_of_birth);
            $tr_passenger.find('input#select_date_picker_date_of_birth').html(passenger_data.date_of_birth);
            $tr_passenger.find('span.nationality').html(passenger_data.nationality);
            $tr_passenger.find('span.passport_no').html(passenger_data.passport_no);
            $tr_passenger.find('span.issue_date').html(passenger_data.issue_date);
            $tr_passenger.find('span.expiry_date').html(passenger_data.expiry_date);
        };
        plugin.lock_passenger = function () {
            var list_passenger_selected=plugin.settings.list_passenger_selected;
            console.log('action log passeger');
            $('.view_orders_edit_form_edit_passenger').find('select.list-passenger').each(function(){
                $(this).find('option[value]').attr('disabled',false);
                var current_tsmart_passenger_id=$(this).val();
                for(var i=0;i<list_passenger_selected.length;i++){
                    var passenger_item=list_passenger_selected[i];
                    if(passenger_item.tsmart_passenger_id!=0)
                    {
                        $(this).find('option[value="'+passenger_item.tsmart_passenger_id+'"]').attr('disabled',true);
                    }
                }
                $(this).find('option[value="'+current_tsmart_passenger_id+'"]').attr('disabled',false);

            });
            console.log(plugin.settings.list_passenger_selected);
        };
        plugin.get_passenger_not_in_room_by_tsmart_passenger_id = function (tsmart_passenger_id) {
            var list_passenger_not_in_room=plugin.settings.list_passenger_not_in_room;
            for(var i=0;i<list_passenger_not_in_room.length;i++){
                var passenger=list_passenger_not_in_room[i];
                if(passenger.tsmart_passenger_id==tsmart_passenger_id){
                    return passenger;
                }
            }
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name;
            return passenger_full_name;
        };

        plugin.update_total_cost_form_add_passenger_to_room = function () {
            var full_total=0;
            var update_full_total=true;
            var list_passenger_selected=plugin.settings.list_passenger_selected;
            for(var i=0;i<list_passenger_selected.length;i++){
                var item_passenger=list_passenger_selected[i];
                var tsmart_passenger_id=item_passenger.tsmart_passenger_id;
                if(tsmart_passenger_id>0){
                    var tour_cost=item_passenger.tour_cost;
                    var discount=item_passenger.passenger_discount;
                    var extra_fee=item_passenger.passenger_extra_fee;
                    var total=parseFloat(tour_cost)+parseFloat(extra_fee)-parseFloat(discount);
                    full_total+=total;
                }else{
                    update_full_total=false;
                    break;
                }
            }
            if(update_full_total==true){
                $( ".view_orders_edit_form_edit_passenger .wrapper-calculator").find('span.cost.full-total').autoNumeric('set',full_total);
            }
        };
        plugin.update_build_rooming_by_passenger = function (list_passenger_not_in_temporary_and_not_in_room) {
            var  $html_build_rooming_hotel_rooming_list=$('#html_build_rooming_hotel_rooming_list').data('html_build_rooming_list');
            $html_build_rooming_hotel_rooming_list.update_build_rooming_by_passenger(list_passenger_not_in_temporary_and_not_in_room);
            var $ul_list_passenger_not_in_temporary_and_not_in_room=$( ".view_orders_edit_form_edit_room").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            $ul_list_passenger_not_in_temporary_and_not_in_room.empty();
            for (var key in list_passenger_not_in_temporary_and_not_in_room) {
                var item_passenger=list_passenger_not_in_temporary_and_not_in_room[key];
                var full_name=plugin.get_passenger_full_name(item_passenger);
                $(' <li class="tag passenger">'+full_name+'('+item_passenger.year_old+' year)'+'</li>').appendTo($ul_list_passenger_not_in_temporary_and_not_in_room);

            }
        };
        plugin.fill_data_form_show_first_history_rooming = function (list_passenger) {
            var $order_edit_form_show_first_history_rooming=$(".order_edit_form_show_first_history_rooming");
            $order_edit_form_show_first_history_rooming.find('table.first_history_rooming tbody').empty();
            for (var tsmart_room_order_id in list_passenger) {
                var items=list_passenger[tsmart_room_order_id];
                var room_type="";
                var creation="";
                var names=[];
                var titles=[];
                for(var i=0;i<items.length;i++) {
                    var passenger=items[i];
                    var full_name=plugin.get_passenger_full_name(passenger);
                    names.push(full_name);
                    titles.push(passenger.title);
                    room_type=passenger.room_type;
                    creation=passenger.created_on;
                }
                var $template_show_first_history_rooming = $(plugin.settings.template_show_first_history_rooming);
                $template_show_first_history_rooming.find('span.tsmart_room_order_id').html(tsmart_room_order_id);
                $template_show_first_history_rooming.find('span.names').html(names.join("<br/>"));
                $template_show_first_history_rooming.find('span.titles').html(titles.join("<br/>"));
                $template_show_first_history_rooming.find('span.room_type').html(room_type);
                $template_show_first_history_rooming.find('span.creation').html(creation);
                $template_show_first_history_rooming.appendTo($order_edit_form_show_first_history_rooming.find('table.first_history_rooming tbody'));
            }


        };
        plugin.fill_data_form_show_near_last_history_rooming = function (list_passenger) {
            var $order_edit_form_show_near_last_history_rooming=$(".order_edit_form_show_near_last_history_rooming");
            $order_edit_form_show_near_last_history_rooming.find('table.near_last_history_rooming tbody').empty();
            for (var tsmart_room_order_id in list_passenger) {
                var items=list_passenger[tsmart_room_order_id];
                var room_type="";
                var creation="";
                var names=[];
                var titles=[];
                for(var i=0;i<items.length;i++) {
                    var passenger=items[i];
                    var full_name=plugin.get_passenger_full_name(passenger);
                    names.push(full_name);
                    titles.push(passenger.title);
                    room_type=passenger.room_type;
                    creation=passenger.created_on;
                }
                var $template_form_show_near_last_history_rooming= $(plugin.settings.template_show_near_last_history_rooming);
                $template_form_show_near_last_history_rooming.find('span.tsmart_room_order_id').html(tsmart_room_order_id);
                $template_form_show_near_last_history_rooming.find('span.names').html(names.join("<br/>"));
                $template_form_show_near_last_history_rooming.find('span.titles').html(titles.join("<br/>"));
                $template_form_show_near_last_history_rooming.find('span.room_type').html(room_type);
                $template_form_show_near_last_history_rooming.find('span.creation').html(creation);
                $template_form_show_near_last_history_rooming.appendTo($order_edit_form_show_near_last_history_rooming.find('table.near_last_history_rooming tbody'));
            }


        };
        plugin.fill_data_form_show_last_history_rooming = function (list_passenger) {
            var $order_edit_form_show_last_history_rooming=$(".order_edit_form_show_last_history_rooming");
            $order_edit_form_show_last_history_rooming.find('table.last_history_rooming tbody').empty();
            for (var tsmart_room_order_id in list_passenger) {
                var items=list_passenger[tsmart_room_order_id];
                var room_type="";
                var creation="";
                var names=[];
                var titles=[];
                for(var i=0;i<items.length;i++) {
                    var passenger=items[i];
                    var full_name=plugin.get_passenger_full_name(passenger);
                    names.push(full_name);
                    titles.push(passenger.title);
                    room_type=passenger.room_type;
                    creation=passenger.created_on;
                }
                var $template_form_show_last_history_rooming= $(plugin.settings.template_show_last_history_rooming);
                $template_form_show_last_history_rooming.find('span.tsmart_room_order_id').html(tsmart_room_order_id);
                $template_form_show_last_history_rooming.find('span.names').html(names.join("<br/>"));
                $template_form_show_last_history_rooming.find('span.titles').html(titles.join("<br/>"));
                $template_form_show_last_history_rooming.find('span.room_type').html(room_type);
                $template_form_show_last_history_rooming.find('span.creation').html(creation);
                $template_form_show_last_history_rooming.appendTo($order_edit_form_show_last_history_rooming.find('table.last_history_rooming tbody'));
            }


        };
        plugin.reset_build_rooming_hotel_add_on_form = function (response,type) {

            var $build_room_hotel_add_on = $('#html_build_room').data('build_room_hotel_add_on');
            $build_room_hotel_add_on.settings.list_passenger=response.list_passenger_not_in_room;
            $build_room_hotel_add_on.settings.list_night_hotel=[];
            var item={
                list_room_type:"single",
                passengers:[],
                room_note:""
            };
            var departure=$build_room_hotel_add_on.settings.departure;
            departure.hotel_addon_detail=response.hotel_addon_detail;
            departure.hotel_addon_detail.type=type;
            $build_room_hotel_add_on.settings.departure=departure;
            $build_room_hotel_add_on.settings.list_night_hotel.push(item);
            $build_room_hotel_add_on.reset_build_hotel_add_on();

            //$build_room_hotel_add_on.update_passengers(list_passenger,index_action,event_name);

        };
        plugin.reset_build_rooming_hotel_add_on_form2 = function (response,type) {
            var  $html_build_rooming_hotel_add_on_rooming_list=$('#html_build_rooming_hotel_rooming_list_hotel_add_on').data('html_build_rooming_list');
            $html_build_rooming_hotel_add_on_rooming_list.reset_build_rooming();
            $html_build_rooming_hotel_add_on_rooming_list.settings.list_passenger=response.list_passenger_not_in_room;
            console.log($html_build_rooming_hotel_add_on_rooming_list);
           /* $build_room_hotel_add_on.settings.list_passenger=response.list_passenger;
            $build_room_hotel_add_on.settings.list_night_hotel=[];
            var item={
                list_room_type:"single",
                passengers:[],
                room_note:""
            };
            var departure=$build_room_hotel_add_on.settings.departure;
            departure.hotel_addon_detail=response.hotel_addon_detail;
            departure.hotel_addon_detail.type=type;
            $build_room_hotel_add_on.settings.departure=departure;
            $build_room_hotel_add_on.settings.list_night_hotel.push(item);
            $build_room_hotel_add_on.reset_build_hotel_add_on();
*/
            //$build_room_hotel_add_on.update_passengers(list_passenger,index_action,event_name);

        };
        plugin.load_hotel_add_on_by_group_hotel_addon_order_id = function (tsmart_group_hotel_addon_order_id, type) {
            var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id',
                        type:type,
                        tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                        tsmart_order_id:tsmart_order_id
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
                    var list_passenger=response.list_passenger;
                    plugin.update_orders_show_form_hotel_addon_passenger(list_passenger,type);
                    plugin.update_orders_show_form_general_edit_hotel_addon(response,type);
                    plugin.fill_data_passenger_not_in_room_hotel_add_on2(response.list_passenger_not_in_room);
                    $(".order_edit_night_hotel").dialog('open');

                }
            });

        };
        plugin.change_status_group_hotel_add_on = function (status) {
            var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_group_hotel_add_on',
                        tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                        status:status,
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
                    if(response.e==0){
                        alert('change status successful');
                    }

                }
            });
        };
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            var task=plugin.settings.task;
            if(task=='add_new_item')
            {

                $element.dialog({
                    dialogClass:'asian-dialog-form',
                    modal: true,
                    width: 900,
                    title: 'Transfer add on',
                    show: {effect: "blind", duration: 800},
                    appendTo: 'body'
                });
            }
            $element.find('.cost').autoNumeric('init', plugin.settings.config_show_price);
            $element.find('.passenger_cost').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '-999.00',
                aSign: ''
            }).change(function(){
                var $tr=$(this).closest('tr.passenger');
                plugin.calculator_price($tr);
            });
            $('table.edit_passenger_cost').find('tbody tr.passenger').each(function(){
                var $tr=$(this);
                plugin.calculator_price($tr);
            });
            $('.view_orders_edit_form_edit_passenger_cost').find('button.save').click(function(){
                var $list_tr= $('table.edit_passenger_cost').find('tbody tr.passenger');
                var list_row=[];
                for(var i=0;i<$list_tr.length;i++){
                    var $tr= $('table.edit_passenger_cost').find('tbody tr.passenger:eq('+i+')');
                    var item={};
                    item.tsmart_passenger_id=$tr.find('input.tsmart_passenger_id').val();
                    item.tour_cost=$tr.find('input.tour_fee').autoNumeric('get');
                    item.room_fee=$tr.find('input.single_room_fee').autoNumeric('get');
                    item.extra_fee=$tr.find('input.extra_fee').autoNumeric('get');
                    item.discount=$tr.find('input.discount_fee').autoNumeric('get');
                    item.cancel_fee=$tr.find('input.cancel_fee').autoNumeric('get');
                    item.payment=$tr.find('input.payment').autoNumeric('get');
                    list_row.push(item);
                }
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost',
                            list_row:list_row,
                            tsmart_order_id:tsmart_order_id
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
                        if(response.e==0){
                            alert('save successful');
                        }
                        $(".order_edit_passenger_cost").dialog('close');
                        plugin.update_data_order();

                    }
                });

            });
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary select[name="hotel_add_on_status"]').change(function(){
                var status=$(this).val();
                plugin.change_status_group_hotel_add_on(status);
            });
            $('.view_orders_edit_order_edit_main_tour').find('button.save').click(function(){
                var $list_tr= $('table.orders_show_form_passenger').find('tbody tr.passenger');
                var list_row=[];
                for(var i=0;i<$list_tr.length;i++){
                    var $tr= $('table.orders_show_form_passenger').find('tbody tr.passenger:eq('+i+')');
                    var item={};
                    item.tsmart_passenger_id=$tr.find('input[name="tsmart_passenger_id[]"]').val();
                    item.passenger_status=$tr.find('select.passenger_status').val();
                    list_row.push(item);
                }
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                var departure_date=$(".order_edit_main_tour").find('input[name="departure_date"]').val();
                var departure_date_end=$(".order_edit_main_tour").find('input[name="departure_date_end"]').val();
                var assign_user_id=$(".order_edit_main_tour").find('#assign_user_id').val();
                var terms_condition=$(".order_edit_main_tour").find('#terms_condition').val();
                var reservation_notes=$(".order_edit_main_tour").find('#reservation_notes').val();
                var tsmart_orderstate_id=$(".order_edit_main_tour").find('#tsmart_orderstate_id').val();
                var itinerary_content=tinymce.get("jform_articletext").getContent();

                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_order_info',
                            list_row:list_row,
                            tsmart_order_id:tsmart_order_id,
                            departure_date:departure_date,
                            departure_date_end:departure_date_end,
                            terms_condition:terms_condition,
                            reservation_notes:reservation_notes,
                            itinerary:itinerary_content,
                            tsmart_orderstate_id:tsmart_orderstate_id,
                            assign_user_id:assign_user_id
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
                        if(response.e==0){
                            alert('save successful');
                        }
                        $(".order_edit_main_tour").dialog('close');
                        var list_row=response.list_row;
                        plugin.update_booking_infomation(response);

                    }
                });

            });
            $('.edit_night_hotel').find('button.save').click(function(){
                var $list_tr= $('table.orders_show_form_passenger').find('tbody tr.passenger');
                var list_row=[];
                for(var i=0;i<$list_tr.length;i++){
                    var $tr= $('table.orders_show_form_passenger').find('tbody tr.passenger:eq('+i+')');
                    var item={};
                    item.tsmart_passenger_id=$tr.find('input[name="tsmart_passenger_id[]"]').val();
                    item.passenger_status=$tr.find('select.passenger_status').val();
                    list_row.push(item);
                }
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                var departure_date=$(".order_edit_main_tour").find('input[name="departure_date"]').val();
                var departure_date_end=$(".order_edit_main_tour").find('input[name="departure_date_end"]').val();
                var assign_user_id=$(".order_edit_main_tour").find('#assign_user_id').val();
                var terms_condition=$(".order_edit_main_tour").find('#terms_condition').val();
                var reservation_notes=$(".order_edit_main_tour").find('#reservation_notes').val();
                var tsmart_orderstate_id=$(".order_edit_main_tour").find('#tsmart_orderstate_id').val();
                var itinerary_content=tinymce.get("jform_articletext").getContent();

                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_order_info',
                            list_row:list_row,
                            tsmart_order_id:tsmart_order_id,
                            departure_date:departure_date,
                            departure_date_end:departure_date_end,
                            terms_condition:terms_condition,
                            reservation_notes:reservation_notes,
                            itinerary:itinerary_content,
                            tsmart_orderstate_id:tsmart_orderstate_id,
                            assign_user_id:assign_user_id
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
                        if(response.e==0){
                            alert('save successful');
                        }
                        $(".order_edit_main_tour").dialog('close');
                        var list_row=response.list_row;
                        plugin.update_booking_infomation(response);

                    }
                });

            });
            $('.view_orders_edit_form_edit_passenger_cost').find('button.cancel').click(function(){
                $(".order_edit_passenger_cost").dialog('close');
            });
            $('.view_orders_edit_order_edit_main_tour').find('button.cancel').click(function(){
                $(".order_edit_main_tour").dialog('close');
            });
            $('.view_orders_edit_form_edit_passenger').find('button.cancel').click(function(){
                $(".order_edit_passenger").dialog('close');
            });
            $('.order_edit_night_hotel').find('button.cancel').click(function(){
                $(".order_edit_night_hotel").dialog('close');
            });
            $('.order_edit_night_hotel').find('.edit-night-hotel-add-on').click(function(){
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type=$(".order_edit_night_hotel").find('input[name="type"]').val();
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            tsmart_order_id:tsmart_order_id,
                            type:type
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
                        plugin.update_orders_show_form_general_edit_hotel_addon2(response,type);
                        $(".order_form_edit_night_hotel").dialog('open');

                    }
                });


            });
            $('.view_orders_edit_form_edit_room').find('button.cancel').click(function(){
                $(".order_edit_room").dialog('close');
            });
            $('.view_orders_edit_form_edit_room').find('button.save').click(function(){
               var  $html_build_rooming_hotel_rooming_list=$('#html_build_rooming_hotel_rooming_list').data('html_build_rooming_list');
                if(!$html_build_rooming_hotel_rooming_list.validate()){
                    return;
                }
                $html_build_rooming_hotel_rooming_list.get_data();
                var list_night_hotel=$html_build_rooming_hotel_rooming_list.settings.list_night_hotel;
                var list_passenger=$html_build_rooming_hotel_rooming_list.settings.list_passenger;
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_rooming',
                            tsmart_order_id:tsmart_order_id,
                            list_night_hotel:list_night_hotel,
                            list_passenger:list_passenger
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
                        if(response.e==0){
                            alert('save successful');
                        }
                        //plugin.update_data_order();
                        $(".order_edit_room").dialog('close');

                    }
                });




            });
            $('.order_hotel_add_on_edit_rooming').find('button.cancel').click(function(){
                $('.order_hotel_add_on_edit_rooming').dialog('close');
            });
            $('.order_hotel_add_on_edit_rooming').find('button.save').click(function(){
               var  $html_build_rooming_hotel_add_on_rooming_list=$('#html_build_rooming_hotel_rooming_list_hotel_add_on').data('html_build_rooming_list');
                if(!$html_build_rooming_hotel_add_on_rooming_list.validate()){
                    return;
                }
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var tsmart_hotel_addon_id=$('.order_edit_night_hotel').find('input[name="tsmart_hotel_addon_id"]').val();
                var type=$('.order_edit_night_hotel').find('input[name="type"]').val();
                $html_build_rooming_hotel_add_on_rooming_list.get_data();
                var list_night_hotel=$html_build_rooming_hotel_add_on_rooming_list.settings.list_night_hotel;
                var list_passenger=$html_build_rooming_hotel_add_on_rooming_list.settings.list_passenger;
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_rooming_hotel_add_on',
                            tsmart_order_id:tsmart_order_id,
                            tsmart_hotel_addon_id:tsmart_hotel_addon_id,
                            type:type,
                            list_night_hotel:list_night_hotel,
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            list_passenger:list_passenger
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
                        if(response.e==0){
                            alert('save successful');
                        }
                        //plugin.update_data_order();
                        $(".order_hotel_add_on_edit_rooming").dialog('close');
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id,type);

                    }
                });




            });
            $('.order_form_add_and_remove_room_edit_hotel_addon').find('button.save-room').click(function(){
                var $build_room_hotel_add_on = $('#html_build_room').data('build_room_hotel_add_on');
                var group_passengers=[];
                var list_room=$build_room_hotel_add_on.get_list_room();
                var list_passenger=$build_room_hotel_add_on.settings.list_passenger;

                var departure=$build_room_hotel_add_on.settings.departure;
                var hotel_addon_detail=departure.hotel_addon_detail;
                console.log(hotel_addon_detail);
                var tsmart_hotel_addon_id=hotel_addon_detail.tsmart_hotel_addon_id;
                var type=hotel_addon_detail.type;
                list_room.forEach(function(item_room) {
                    var room_type=item_room.list_room_type;
                    var passenger_room={};
                    passenger_room.room_type=room_type;

                    passenger_room.passengers=[];
                    var tour_cost_and_room_price=item_room.tour_cost_and_room_price;
                    tour_cost_and_room_price.forEach(function(item_tour_cost_and_room_price) {
                        var passenger={};
                        passenger.room_price=item_tour_cost_and_room_price.room_price;
                        var passenger_index=item_tour_cost_and_room_price.passenger_index;
                        passenger.tsmart_passenger_id=list_passenger[passenger_index].tsmart_passenger_id;
                        passenger_room.passengers.push(passenger);
                    });
                    group_passengers.push(passenger_room);
                });
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'save_passenger_room_in_hotel_add_on',
                            tsmart_order_id:tsmart_order_id,
                            tsmart_hotel_addon_id:tsmart_hotel_addon_id,
                            type:type,
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            group_passengers:group_passengers
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
                        $(".order_form_add_and_remove_room_edit_hotel_addon").dialog('close');
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id,type);

                    }
                });

            });
            $('.view_orders_edit_form_edit_room').find('button.show-first-history').click(function(){
              var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'get_first_history_rooming',
                            tsmart_order_id:tsmart_order_id
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
                        var list_passenger=response.list_passenger;
                        plugin.fill_data_form_show_first_history_rooming(list_passenger);
                        $(".order_edit_form_show_first_history_rooming").dialog('open');

                    }
                });




            });

            $('.view_orders_edit_form_edit_room').find('button.show-near-last-one-history').click(function(){
              var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'get_near_last_history_rooming',
                            tsmart_order_id:tsmart_order_id
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
                        var list_passenger=response.list_passenger;
                        plugin.fill_data_form_show_near_last_history_rooming(list_passenger);
                        $(".order_edit_form_show_near_last_history_rooming").dialog('open');

                    }
                });




            });
            $('.view_orders_edit_form_edit_room').find('button.show-last-history').click(function(){
              var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'get_last_history_rooming',
                            tsmart_order_id:tsmart_order_id
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
                        var list_passenger=response.list_passenger;
                        plugin.fill_data_form_show_last_history_rooming(list_passenger);
                        $(".order_edit_form_show_last_history_rooming").dialog('open');

                    }
                });




            });

            $('.order_edit_form_show_first_history_rooming').find('button.cancel').click(function(){
                $(".order_edit_form_show_first_history_rooming").dialog('close');
            });
            $('.order_edit_form_show_near_last_history_rooming').find('button.cancel').click(function(){
                $(".order_edit_form_show_near_last_history_rooming").dialog('close');
            });
            $('.order_edit_form_show_last_history_rooming').find('button.cancel').click(function(){
                $(".order_edit_form_show_last_history_rooming").dialog('close');
            });
            $('.view_orders_edit_form_edit_room').find('a.delete').click(function(){
                if (!confirm('are you sure delete this room ?')) {
                    return;
                }
                var $tr=$(this).closest('tr.room_order_item');
                var tsmart_room_order_id=$tr.data('tsmart_room_order_id');
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_delete_rooming',
                            tsmart_order_id:tsmart_order_id,
                            tsmart_room_order_id:tsmart_room_order_id
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
                        if(response.error==0){
                            alert('delete successful');
                        }
                        $( ".view_orders_edit_form_edit_room").find('tr.room_order_item[data-tsmart_room_order_id="'+tsmart_room_order_id+'"]').remove();
                        var list_passenger_not_in_temporary_and_not_in_room=response.list_passenger_not_in_temporary_and_not_in_room;
                        plugin.update_build_rooming_by_passenger(list_passenger_not_in_temporary_and_not_in_room);
                        //plugin.update_data_order();
                        //$(".order_edit_room").dialog('close');

                    }
                });




            });
            $element.find("#adminForm").validate();
            $element.find('.toolbar .cancel').click(function(){
                Joomla.submitform('cancel');
            });
            $element.find('.edit_form.main-tour').click(function(){
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_by_order_id',
                            tsmart_order_id:tsmart_order_id
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
                        var list_row=response.list_row;
                        plugin.update_orders_show_form_passenger(list_row);
                        plugin.update_orders_show_form_general(response);
                        $(".order_edit_main_tour").dialog('open');

                    }
                });



            });
            var $row_passenger=$(".order_edit_night_hotel").find('table.orders_show_form_passenger_edit_hotel_addon tbody tr.passenger');
            plugin.setting_row_passenger_edit_hotel_addon=$row_passenger.getOuterHTML();
            $row_passenger.remove();
            $element.find('.edit_form.night_hotel').click(function(){
                var tsmart_group_hotel_addon_order_id=$(this).data('object_id');
                var type=$(this).data('object_type');
                plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id,type);



            });
            $element.find('.edit_form.transfer').click(function(){
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_by_order_id',
                            tsmart_order_id:tsmart_order_id
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
                        var list_row=response.list_row;
                        plugin.update_orders_show_form_passenger(list_row);
                        plugin.update_orders_show_form_general(response);
                        $(".order_edit_transfer").dialog('open');

                    }
                });



            });
            $element.find(".tabbed-nav.book-add-on").zozoTabs({
                theme: "orange",
                orientation: "horizontal",
                position: "top-left",
                size: "medium",
                animation: {
                    easing: "easeInOutExpo",
                    duration: 200,
                    effects: "slideLeft"
                },
                defaultTab: "tab1"
            });
            $element.find(".order_book_add_on").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'book add on',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-voucher-center').click(function(){
                $(".form_order_voucher_center").dialog('open');
            });
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('button.save').click(function(){
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type=$(".order_edit_night_hotel").find('input[name="type"]').val();
                var checkin_date=$(".view_orders_edit_from_general_edit_hotel_addon_child").find('input[name="night_hotel_checkin_date"]').val();
                var checkout_date=$(".view_orders_edit_from_general_edit_hotel_addon_child").find('input[name="night_hotel_checkout_date"]').val();
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_group_hotel_add_on',
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            checkin_date:checkin_date,
                            checkout_date:checkout_date,
                            tsmart_order_id:tsmart_order_id,
                            type:type
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
                        alert('save information success');
                        $(".order_form_edit_night_hotel").dialog('close');
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id,type);

                    }
                });

            });
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('button.cancel').click(function(){
                $(".order_form_edit_night_hotel").dialog('close');

            });
            $element.find(".form_order_add_flight").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Add flight',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-add-flight').click(function(){
                $(".form_order_add_flight").dialog('open');
            });

            $element.find(".form_order_add_hoc_item").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Add flight',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_form_edit_night_hotel").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'edit genarate night hotel',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-add-hoc-item').click(function(){
                $(".form_order_add_hoc_item").dialog('open');
            });

            $element.find(".form_order_voucher_center").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Voucher center',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-book-add-on').click(function(){
                $(".order_book_add_on").dialog('open');
            });
            $element.find('.edit_form.excursion').click(function(){
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_by_order_id',
                            tsmart_order_id:tsmart_order_id
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
                        var list_row=response.list_row;
                        plugin.update_orders_show_form_passenger(list_row);
                        plugin.update_orders_show_form_general(response);
                        $(".order_edit_excursion").dialog('open');

                    }
                });



            });
            $element.find(".order_edit_excursion").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit excursion',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_transfer").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit transfer',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_night_hotel").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit night hotel',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_main_tour").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit details',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_passenger").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Add more passenger',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });


            $('.order_edit_main_tour').find('.passenger-control .add-passenger').click(function(){
                alert('tinh toan');
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_by_order_id',
                            tsmart_order_id:tsmart_order_id
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
                        var order_data=response.r.order_data;
                        var list_row=response.list_row;
                        plugin.fill_data_passenger_cost(list_row);

                        $(".order_edit_passenger").dialog('open');

                    }
                });


            });
            $element.find(".order_edit_passenger_cost").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Edit passenger cost',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $row_passenger_cost=$(".order_edit_passenger_cost_edit_hotel_add_on").find('table.edit_passenger_cost_hotel_addon_edit_passenger tbody tr.passenger');
            plugin.settings.row_passenger_cost_edit_hotel_addon=$row_passenger_cost.getOuterHTML();

            var $tr_service_cost_edit_hotel=$(".view_orders_edit_form_edit_service_cost_edit_hotel").find('table.service_cost_edit_hotel tbody tr.item-room');
            plugin.settings.row_service_cost_edit_hotel_addon=$tr_service_cost_edit_hotel.getOuterHTML();

            var $tr_rooming_edit_hotel=$(".view_orders_edit_order_bookinginfomation_hotel_addon").find('table.rooming-list tbody tr.item-rooming');
            plugin.settings.tr_rooming_edit_hotel=$tr_rooming_edit_hotel.getOuterHTML();

            var $tr_rooming_edit_hotel_add_on=$(".view_orders_edit_form_edit_room_in_hotel_add_on").find('table.rooming_hotel_add_on tbody tr.item-rooming');
            plugin.settings.tr_rooming_edit_hotel_add_on=$tr_rooming_edit_hotel_add_on.getOuterHTML();

            $tr_rooming_edit_hotel_add_on.remove();
            $element.find(".order_edit_passenger_cost_edit_hotel_add_on").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Edit passenger cost hotel addon',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });

            $element.find(".order_edit_form_show_first_history_rooming").dialog({
                dialogClass:'asian-dialog-form',
                modal: false,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Show first history rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $first_history_rooming=$('.view_orders_edit_form_show_first_history_rooming').find('table.first_history_rooming tbody tr:first-child');
            plugin.settings.template_show_first_history_rooming=$first_history_rooming.getOuterHTML();
            $first_history_rooming.remove();
            $element.find(".order_edit_form_show_near_last_history_rooming").dialog({
                dialogClass:'asian-dialog-form',
                modal: false,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Show near last history rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $near_last_history_rooming=$('.view_orders_edit_form_show_near_last_history_rooming').find('table.near_last_history_rooming tbody tr:first-child');
            plugin.settings.template_show_near_last_history_rooming=$near_last_history_rooming.getOuterHTML();
            $near_last_history_rooming.remove();


            $element.find(".order_edit_form_show_last_history_rooming").dialog({
                dialogClass:'asian-dialog-form',
                modal: false,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Show last history rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $last_history_rooming=$('.view_orders_edit_form_show_last_history_rooming').find('table.last_history_rooming tbody tr:first-child');
            plugin.settings.template_show_last_history_rooming=$last_history_rooming.getOuterHTML();
            $last_history_rooming.remove();

            $('.order_edit_main_tour').find('.passenger-control .edit-booking-cost').click(function(){
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_by_order_id',
                            tsmart_order_id:tsmart_order_id
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
                        var order_data=response.r.order_data;
                        var list_row=response.list_row;
                        plugin.fill_data_passenger_cost(list_row);

                        $(".order_edit_passenger_cost").dialog('open');

                    }
                });




            });

            $('.order_edit_night_hotel').find('.passenger-control .edit-hotel-add-cost').click(function(){
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type=$(".order_edit_night_hotel").find('input[name="type"]').val();
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            tsmart_order_id:tsmart_order_id,
                            type:type
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
                        var list_passenger=response.list_passenger;
                        var hotel_addon_detail=response.hotel_addon_detail;
                        plugin.fill_data_passenger_cost_edit_hotel_add(list_passenger,type,hotel_addon_detail);

                        $(".order_edit_passenger_cost_edit_hotel_add_on").dialog('open');

                    }
                });




            });
            $('.order_edit_night_hotel').find('.passenger-control .edit-hotel-add-room').click(function(){
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type=$('.order_edit_night_hotel').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_and_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            tsmart_order_id:tsmart_order_id,
                            type:type
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

                        plugin.reset_build_rooming_hotel_add_on_form(response,type);
                        $(".order_form_add_and_remove_room_edit_hotel_addon").dialog('open');

                    }
                });




            });

            $element.find(".order_form_add_and_remove_room_edit_hotel_addon").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'add rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".order_edit_room").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Edit rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });

            $('.order_edit_main_tour').find('.room-control .edit-room').click(function(){
                $(".order_edit_room").dialog('open');

            });
            $('.view_orders_edit_order_bookinginfomation_hotel_addon').find('.room-control .edit-room').click(function(){
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type=$('.order_edit_night_hotel').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_and_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id:tsmart_group_hotel_addon_order_id,
                            tsmart_order_id:tsmart_order_id,
                            type:type
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

                        plugin.reset_build_rooming_hotel_add_on_form2(response,type);
                        plugin.fill_data_rooming_hotel_add_on2(response.list_rooming,type);
                        plugin.fill_data_passenger_not_in_room_hotel_add_on(response.list_passenger_not_in_room,type);
                        $(".order_hotel_add_on_edit_rooming").dialog('open');

                    }
                });




            });


            $element.find(".order_form_add_and_remove_passenger").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'add/edit passenger',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".order_hotel_add_on_edit_rooming").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'hotel add on edit rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $( ".view_orders_edit_passenger_manager" ).on( "click", "tr.item-passenger a.edit", function() {
                var $tr=$(this).closest("tr.item-passenger");
                var tsmart_passenger_id=$tr.data("tsmart_passenger_id");
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_passenger_detail_by_passenger_id',
                            tsmart_passenger_id:tsmart_passenger_id
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
                        var passenger_data=response.passenger_data;
                        plugin.fill_data_passenger_detail(passenger_data);

                        $(".order_form_add_and_remove_passenger").dialog('open');

                    }
                });
            });
            $( ".view_orders_edit_passenger_manager" ).on( "click", "tr.item-passenger a.delete", function() {
                if (!confirm('are you sure delete this passenger')) {
                    return;
                }
                var $tr=$(this).closest("tr.item-passenger");
                var tsmart_passenger_id=$tr.data("tsmart_passenger_id");
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_delete_passenger_by_passenger_id',
                            tsmart_passenger_id:tsmart_passenger_id
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
                        if(response.error==0){
                            alert('delete passenger success');
                        }
                        $( ".view_orders_edit_passenger_manager").find('tr.item-passenger[data-tsmart_passenger_id="'+tsmart_passenger_id+'"]').remove();

                    }
                });
            });
            $element.find('.passenger-manager-control .add_passenger').click(function(){
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_passenger_detail_by_passenger_id',
                            tsmart_passenger_id:0
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
                        var passenger_data=response.passenger_data;
                        plugin.fill_data_passenger_detail(passenger_data);

                        $(".order_form_add_and_remove_passenger").dialog('open');

                    }
                });



            });
            $('.order_form_add_and_remove_room_edit_hotel_addon').find('button.cancel').click(function(){
                $(".order_form_add_and_remove_room_edit_hotel_addon").dialog('close');
            });
            $('.form_order_voucher_center').find('button.cancel').click(function(){
                $(".form_order_voucher_center").dialog('close');
            });
            $('.order_edit_passenger_cost_edit_hotel_add_on').find('button.cancel').click(function(){
                $(".order_edit_passenger_cost_edit_hotel_add_on").dialog('close');
            });
            $('.order_edit_passenger_cost_edit_hotel_add_on').find('button.save').click(function(){
                //save hotel add on cost passenger
                var $list_tr= $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon table.edit_passenger_cost_hotel_addon_edit_passenger').find('tbody tr.passenger');
                var list_row=[];
                for(var i=0;i<$list_tr.length;i++){
                    var $tr= $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon table.edit_passenger_cost_hotel_addon_edit_passenger').find('tbody tr.passenger:eq('+i+')');
                    var item={};
                    item.tsmart_passenger_id=$tr.find('input.tsmart_passenger_id').val();
                    item.discount=$tr.find('input.night_discount').autoNumeric('get');
                    item.cancel_fee=$tr.find('input.night_cancel_fee').autoNumeric('get');
                    item.payment=$tr.find('input.night_payment').autoNumeric('get');
                    list_row.push(item);
                }
                var tsmart_order_id=$element.find('input[name="tsmart_order_id"]').val();
                var type=$('.order_edit_night_hotel').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost_hotel_add_on',
                            type:type,
                            list_row:list_row,
                            tsmart_order_id:tsmart_order_id
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
                        if(response.e==0){
                            alert('save successful');
                        }
                        $(".order_edit_passenger_cost_edit_hotel_add_on").dialog('close');
                        var tsmart_group_hotel_addon_order_id=$('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id,type);


                    }
                });


            });
            $('.form_order_add_hoc_item').find('button.cancel').click(function(){
                $(".form_order_add_hoc_item").dialog('close');
            });
            $('.form_order_add_flight').find('button.cancel').click(function(){
                $(".form_order_add_flight").dialog('close');
            });
            $('.order_form_add_and_remove_passenger').find('button.cancel').click(function(){
                $(".order_form_add_and_remove_passenger").dialog('close');
            });
            $('.order_book_add_on').find('button.cancel').click(function(){
                $(".order_book_add_on").dialog('close');
            });
            $('.order_form_add_and_remove_passenger').find('button.save').click(function(){
                var $view_orders_edit_form_add_and_remove_passenger=$(".view_orders_edit_form_add_and_remove_passenger");
                var tsmart_passenger_id=$view_orders_edit_form_add_and_remove_passenger.find('input[type="hidden"][name="tsmart_passenger_id"]').val();
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                if(!plugin.validate_input_passenger()){
                    return;
                }
                var json_post=$view_orders_edit_form_add_and_remove_passenger.find(":input").serializeObject();
                json_post.tsmart_order_id=tsmart_order_id;
                console.log(json_post);
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_detail_by_passenger_id',
                            tsmart_passenger_id:tsmart_passenger_id,
                            json_post:json_post
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
                        var passenger_data=response.passenger_data;
                        plugin.fill_data_row_passenger_detail(passenger_data);
                        if(response.error==0){
                            alert('save data success');
                        }
                        $(".order_form_add_and_remove_passenger").dialog('close');

                    }
                });


            });
            var item_passenger={
                tsmart_passenger_id:0,
                passenger_type:"",
                tour_cost:0,
                passenger_discount:0,
                passenger_bed_type:"private_bed",
                passenger_extra_fee:0
            };
            var item_passenger_clone= JSON.parse(JSON.stringify(item_passenger));
            plugin.settings.passenger_template=$('.view_orders_edit_form_edit_passenger').find('.passenger-item').getOuterHTML();
            plugin.settings.passenger_calculator_template=$('.view_orders_edit_form_edit_passenger .wrapper-calculator').find('.passenger-item-calculator').getOuterHTML();
            plugin.settings.list_passenger_selected=[];
            plugin.settings.list_passenger_selected.push(item_passenger_clone);
            $('.view_orders_edit_form_edit_passenger').find('select.number-passenger').change(function(){
                var total_passenger=$(this).val();
                var current_total_passenger=$('.view_orders_edit_form_edit_passenger').find('.passenger-item').length;
                if(total_passenger>current_total_passenger){
                    //add new
                    var total_add_new=total_passenger-current_total_passenger;

                    for(var i=1;i<=total_add_new;i++) {
                        var item_passenger_clone= JSON.parse(JSON.stringify(item_passenger));
                        plugin.settings.list_passenger_selected.push(item_passenger_clone);
                        var $passenger=$(plugin.settings.passenger_template);
                        $passenger.insertAfter($('.view_orders_edit_form_edit_passenger').find('.passenger-item:last-child'));
                        $passenger.find('input.cost').autoNumeric('init', plugin.settings.config_show_price);
                    }
                }else if(total_passenger<current_total_passenger){
                    //remove
                    var total_remove=current_total_passenger-total_passenger;
                    for(var i=1;i<=total_remove;i++) {
                        var $passenger_item= $('.view_orders_edit_form_edit_passenger').find('.passenger-item:last-child');
                        var tsmart_passenger_id=$passenger_item.find('select.list-passenger').val();
                        var total_item_in_array=plugin.settings.list_passenger_selected;
                        plugin.settings.list_passenger_selected.pop();
                        $passenger_item.remove();
                    }
                }
                plugin.lock_passenger();
                var current_total_calculator_passenger=$('.view_orders_edit_form_edit_passenger .wrapper-calculator').find('.passenger-item-calculator').length;
                if(total_passenger>current_total_calculator_passenger){
                    //add new
                    var total_add_calculator_new=total_passenger-current_total_calculator_passenger;

                    for(var i=1;i<=total_add_calculator_new;i++) {
                        var $passenger_calculator=$(plugin.settings.passenger_calculator_template);
                        $passenger_calculator.insertAfter($('.view_orders_edit_form_edit_passenger .wrapper-calculator').find('.passenger-item-calculator:last-child'));
                        $passenger_calculator.find('span.cost').autoNumeric('init', plugin.settings.config_show_price);
                    }
                }else if(total_passenger<current_total_calculator_passenger){
                    //remove
                    var total_remove=current_total_calculator_passenger-total_passenger;
                    for(var i=1;i<=total_remove;i++) {
                        var $passenger_calculator_item= $('.view_orders_edit_form_edit_passenger .wrapper-calculator').find('.passenger-item-calculator:last-child');

                        $passenger_calculator_item.remove();
                    }
                }
                console.log("length:"+plugin.settings.list_passenger_selected.length);
                plugin.update_total_cost_form_add_passenger_to_room();


            });
            $( ".view_orders_edit_form_edit_passenger .wrapper-passenger" ).find('input.cost').autoNumeric('init', plugin.settings.config_show_price);
            $( ".view_orders_edit_form_edit_passenger .wrapper-passenger" ).on( "change", "select.list-passenger", function() {
                var $passenger_item= $(this).closest('.passenger-item');
                var tsmart_passenger_id=$(this).val();
                var index_passenger=$passenger_item.index();
                var current_item_passenger=plugin.settings.list_passenger_selected[index_passenger];
                var passenger=plugin.get_passenger_not_in_room_by_tsmart_passenger_id(tsmart_passenger_id);
                current_item_passenger.tsmart_passenger_id=tsmart_passenger_id;
                current_item_passenger.passenger_type=tsmart_passenger_id!=0?$.get_title_passenger(passenger.date_of_birth):"N/A";
                plugin.settings.list_passenger_selected[index_passenger]=current_item_passenger;
                plugin.lock_passenger();
                $passenger_item.find('input.passenger-type').val(current_item_passenger.passenger_type);

                var discount=$passenger_item.find('input.discount').autoNumeric('get');
                var extra_fee=$passenger_item.find('input.extra-fee').autoNumeric('get');
                var departure=plugin.settings.departure;

                var $passenger_item_calculator =$( ".view_orders_edit_form_edit_passenger .wrapper-calculator").find('.passenger-item-calculator:eq('+index_passenger+')');
                var $tour_cost= $passenger_item_calculator.find('span.tour-cost');
                if(tsmart_passenger_id!=0) {
                    var passenger=plugin.get_passenger_not_in_room_by_tsmart_passenger_id(tsmart_passenger_id);
                    $passenger_item_calculator.find('span.person-name').html(plugin.get_passenger_full_name(passenger));
                    var person_type=$.get_title_passenger(passenger.date_of_birth);
                    var tour_cost=0;
                    switch(person_type) {
                        case "children1":
                            tour_cost=departure.sale_price_children1;
                            break;
                        case "children2":
                            tour_cost=departure.sale_price_children2;
                            break;
                        case "teen":
                            tour_cost=departure.sale_price_teen;
                            break;
                        case "adult":
                            tour_cost=departure.sale_price_adult;
                            break;
                        case "senior":
                            tour_cost=departure.sale_price_senior;
                            break;
                    }
                    current_item_passenger.tour_cost=tour_cost;
                    $tour_cost.autoNumeric('set',tour_cost);
                    if(extra_fee!="")
                    {
                        $passenger_item_calculator.find('span.extra-fee').autoNumeric('set',extra_fee);
                        current_item_passenger.passenger_extra_fee=extra_fee;
                    }else{
                        $passenger_item_calculator.find('span.extra-fee').html("N/A");
                        current_item_passenger.passenger_extra_fee=0;
                    }
                    if(discount!=""){
                        $passenger_item_calculator.find('span.discount').autoNumeric('set',discount);
                        current_item_passenger.passenger_discount=discount;
                    }else {
                        $passenger_item_calculator.find('span.discount').html("N/A");
                        current_item_passenger.passenger_discount=0;
                    }
                    if(extra_fee!=''&&discount!=""){
                        var total=parseFloat(tour_cost)+parseFloat(extra_fee)-parseFloat(discount);
                        $passenger_item_calculator.find('span.cost.passenger-total-cost').autoNumeric('set',total);
                    }else{
                        $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                    }

                }else{
                    $passenger_item_calculator.find('span.person-name').html("N/A");
                    $tour_cost.html("N/A");
                    $passenger_item_calculator.find('span.extra-fee').html("N/A");
                    $passenger_item_calculator.find('span.discount').html("N/A");
                    $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                }
                plugin.settings.list_passenger_selected[index_passenger]=current_item_passenger;
                plugin.update_total_cost_form_add_passenger_to_room();

            });
            $( ".view_orders_edit_form_edit_passenger .wrapper-passenger" ).on( "change", ".discount.cost,.extra-fee.cost", function() {
                var $passenger_item= $(this).closest('.passenger-item');
                var tsmart_passenger_id=$passenger_item.find('select.list-passenger').val();
                var index_passenger=$passenger_item.index();
                var discount=$passenger_item.find('input.discount').autoNumeric('get');
                var extra_fee=$passenger_item.find('input.extra-fee').autoNumeric('get');

                var departure=plugin.settings.departure;
                var current_item_passenger=plugin.settings.list_passenger_selected[index_passenger];
                var $passenger_item_calculator =$( ".view_orders_edit_form_edit_passenger .wrapper-calculator").find('.passenger-item-calculator:eq('+index_passenger+')');
                var $tour_cost= $passenger_item_calculator.find('span.tour-cost');
                if(tsmart_passenger_id!=0) {
                    var passenger=plugin.get_passenger_not_in_room_by_tsmart_passenger_id(tsmart_passenger_id);
                    var person_type=$.get_title_passenger(passenger.date_of_birth);
                    var tour_cost=0;
                    switch(person_type) {
                        case "children1":
                            tour_cost=departure.sale_price_children1;
                            break;
                        case "children2":
                            tour_cost=departure.sale_price_children2;
                            break;
                        case "teen":
                            tour_cost=departure.sale_price_teen;
                            break;
                        case "adult":
                            tour_cost=departure.sale_price_adult;
                            break;
                        case "senior":
                            tour_cost=departure.sale_price_senior;
                            break;
                    }
                    $tour_cost.autoNumeric('set',tour_cost);
                    current_item_passenger.tour_cost=tour_cost;
                    if(extra_fee!="")
                    {
                        $passenger_item_calculator.find('span.extra-fee').autoNumeric('set',extra_fee);
                        current_item_passenger.passenger_extra_fee=extra_fee;
                    }else{
                        $passenger_item_calculator.find('span.extra-fee').html("N/A");
                        current_item_passenger.passenger_extra_fee=0;
                    }
                    if(discount!=""){
                        $passenger_item_calculator.find('span.discount').autoNumeric('set',discount);
                        current_item_passenger.passenger_discount=discount;
                    }else {
                        $passenger_item_calculator.find('span.discount').html("N/A");
                        current_item_passenger.passenger_discount=0;
                    }
                    if(extra_fee!=''&&discount!=""){
                        var total=parseFloat(tour_cost)+parseFloat(extra_fee)-parseFloat(discount);
                        $passenger_item_calculator.find('span.cost.passenger-total-cost').autoNumeric('set',total);
                    }else{
                        $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                    }




                }else{
                    $passenger_item_calculator.find('span.person-name').html("N/A");
                    $tour_cost.html("N/A");
                    $passenger_item_calculator.find('span.extra-fee').html("N/A");
                    $passenger_item_calculator.find('span.discount').html("N/A");
                    $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                }
                plugin.settings.list_passenger_selected[index_passenger]=current_item_passenger;
                plugin.update_total_cost_form_add_passenger_to_room();


            });
            $('.view_orders_edit_form_edit_passenger').find('button.save').click(function(){
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                if(!plugin.validate_add_passenger_to_room()){
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_add_passenger_to_room',
                            tsmart_order_id: 'tsmart_order_id',
                            list_row:plugin.settings.list_passenger_selected
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
                        var order_data=response.r;
                        if(response.error==0){
                            alert('save data success');
                        }
                        $(".order_edit_passenger").dialog('close');

                    }
                });
            });
            $('.order_edit_night_hotel').find('button.save').click(function(){
                var tsmart_order_id=$element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                if(!plugin.validate_add_passenger_to_room()){
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_add_passenger_to_room',
                            tsmart_order_id: 'tsmart_order_id',
                            list_row:plugin.settings.list_passenger_selected
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
                        var order_data=response.r;
                        if(response.error==0){
                            alert('save data success');
                        }
                        $(".order_edit_passenger").dialog('close');

                    }
                });
            });
            var $view_orders_edit_form_add_and_remove_passenger=$('.view_orders_edit_form_add_and_remove_passenger');
            $view_orders_edit_form_add_and_remove_passenger.find('button.auto-fill').click(function(){
                var $list_input=$view_orders_edit_form_add_and_remove_passenger.find('input[required="required"]');
                for(var i=0;i<$list_input.length;i++){
                    var $current_input=$($list_input.get(i));
                    $current_input.delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' });
                }
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="email_address"],input[name="confirm_email"]').val("test@gmail.com");
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="first_name"]').delorean({ type: 'words', amount: 1, character: 'Doc', tag:  '' });
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="middle_name"]').delorean({ type: 'words', amount: 1, character: 'Doc', tag:  '' });
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="last_name"]').delorean({ type: 'words', amount: 1, character: 'Doc', tag:  '' });
            });


        };

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_orders_edit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_orders_edit')) {
                var plugin = new $.view_orders_edit(this, options);
                $(this).data('view_orders_edit', plugin);

            }

        });

    }

})(jQuery);


