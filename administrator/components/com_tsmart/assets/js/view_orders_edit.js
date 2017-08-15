(function ($) {

    // here we go!
    $.view_orders_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            task: '',
            debug: false,
            config_show_price: {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999.00',
                aSign: 'US$ '
            },
            config_show_price_positive: {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: '',
                wEmpty: 'zero'
            },
            config_show_price_payment: {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: 'US$ ',
                wEmpty: 'zero'
            },
            config_show_price_discount: {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: 'US$'
            },
            config_show_price_surcharge: {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: 'US$ '
            },
            passenger_cost_config: {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999999.00',
                aSign: ''
            },
            numeric_tax_config: {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999999.00',
                aSign: ''
            },
            list_passenger_not_in_room: []
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.calculator_passenger_cost_main = function ($tr, $self) {

            var tour_fee = $tr.find('input.tour_fee').autoNumeric('get');
            var discount = $tr.find('input.discount_fee').autoNumeric('get');
            var surcharge = $tr.find('input.surcharge').autoNumeric('get');
            var cancel_fee = $tr.find('input.cancel_fee').autoNumeric('get');
            var total_night = $tr.find('input.total_night').val();
            total_night = 1;
            var single_room_fee = $tr.find('input.single_room_fee').autoNumeric('get');
            var extra_fee = $tr.find('input.extra_fee').autoNumeric('get');
            var $cancel_fee = $tr.find('input.cancel_fee');
            var $discount = $tr.find('input.discount_fee');

            var $payment = $tr.find('input.payment');
            var payment = $payment.autoNumeric('get');
            var $total_cost = $tr.find('input.total_cost');
            var $refund = $tr.find('input.refund');
            var $balance = $tr.find('input.balance');

            var total_cost = parseFloat(tour_fee) * total_night + parseFloat(single_room_fee) + parseFloat(extra_fee) - parseFloat(discount);
            $total_cost.autoNumeric('set', total_cost);
            var refund = parseFloat(payment) - parseFloat(cancel_fee);
            $refund.autoNumeric('set', refund);

            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var $view_edit_form_edit_passenger_cost_main_tour = $('.view_orders_edit_form_edit_passenger_cost');

            var $list_tr = $view_edit_form_edit_passenger_cost_main_tour.find('table.edit_passenger_cost tbody tr');
            for (var i = 0; i < $list_tr.length; i++) {
                var $current_tr = $($list_tr.get(i));
                var tour_fee = $current_tr.find('input.tour_fee').autoNumeric('get');
                var discount = $current_tr.find('input.discount_fee').autoNumeric('get');
                var extra_fee = $current_tr.find('input.extra_fee').autoNumeric('get');
                var surcharge = $current_tr.find('input.surcharge').autoNumeric('get');
                var total_cost = parseFloat(tour_fee) + parseFloat(extra_fee) + parseFloat(surcharge) - parseFloat(discount);
                total_cost_for_all += parseFloat(total_cost);
                var cancel_fee = $current_tr.find('input.cancel_fee').autoNumeric('get');
                var payment = $current_tr.find('input.payment').autoNumeric('get');
                total_cancel += parseFloat(cancel_fee);
                total_refund += (parseFloat(payment) - parseFloat(cancel_fee));

            }
            $view_edit_form_edit_passenger_cost_main_tour.find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $view_edit_form_edit_passenger_cost_main_tour.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_edit_form_edit_passenger_cost_main_tour.find('.total-refund').autoNumeric('set', total_refund);


        };
        plugin.calculator_passenger_cost_hotel_add_on = function ($tr, $self) {

            var night_hotel_fee = $tr.find('input.night_hotel_fee').autoNumeric('get');
            var discount = $tr.find('input.night_discount').autoNumeric('get');
            var night_surcharge = $tr.find('input.night_surcharge').autoNumeric('get');
            var surcharge = $tr.find('input.night_surcharge').autoNumeric('get');
            var cancel_fee = $tr.find('input.night_cancel_fee').autoNumeric('get');
            var total_night = $tr.find('input.total_night').val();
            cancel_fee = cancel_fee ? cancel_fee : 0;
            var $payment = $tr.find('input.night_payment');
            var payment = $payment.autoNumeric('get');
            var $total_cost = $tr.find('input.total_cost');
            var $refund = $tr.find('input.night_refund');
            var total_cost = parseFloat(night_hotel_fee) * total_night + parseFloat(surcharge) - parseFloat(discount);
            $total_cost.autoNumeric('set', total_cost);
            if (payment) {
                var refund = parseFloat(payment) - parseFloat(cancel_fee);
                $refund.autoNumeric('set', refund);
            } else {
                $refund.autoNumeric('set', 0);
            }
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var $list_tr = $(".view_orders_edit_form_edit_passenger_cost_edit_hotel_addon").find('table.edit_passenger_cost_hotel_addon_edit_passenger tbody tr');
            for (var i = 0; i < $list_tr.length; i++) {
                var $tr = $($list_tr.get(i));
                var hotel_fee = $tr.find('input.night_hotel_fee').autoNumeric('get');
                var total_night = $tr.find('input.total_night').val();
                var discount = $tr.find('input.night_discount').autoNumeric('get');
                var surcharge = $tr.find('input.night_surcharge').autoNumeric('get');
                var total_cost = parseFloat(hotel_fee) * total_night + parseFloat(surcharge) - parseFloat(discount);
                total_cost_for_all += parseFloat(total_cost);
                var cancel_fee = $tr.find('input.night_cancel_fee').autoNumeric('get');
                total_cancel += parseFloat(cancel_fee);
                refund = $tr.find('input.night_refund').autoNumeric('get');
                total_refund += parseFloat(refund);

            }
            $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon').find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon').find('.total-cancel').autoNumeric('set', total_cancel);
            $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon').find('.total-refund').autoNumeric('set', total_refund);

        };
        plugin.calculator_passenger_cost_transfer_add_on = function ($tr, $self) {

            var transfer_fee = $tr.find('input.transfer_fee').autoNumeric('get');
            var discount = $tr.find('input.transfer_discount').autoNumeric('get');
            var surcharge = $tr.find('input.transfer_surcharge').autoNumeric('get');
            var cancel_fee = $tr.find('input.transfer_cancel_fee').autoNumeric('get');
            cancel_fee = cancel_fee ? cancel_fee : 0;
            var $payment = $tr.find('input.transfer_payment');
            var payment = $payment.autoNumeric('get');

            var $cancel_fee = $tr.find('input.transfer_cancel_fee');
            var $discount = $tr.find('input.transfer_discount');
            cancel_fee = cancel_fee ? cancel_fee : 0;

            var $total_cost = $tr.find('input.total_cost');
            var $refund = $tr.find('input.transfer_refund');
            var $balance = $tr.find('input.transfer_balance');
            var total_cost = parseFloat(transfer_fee) + parseFloat(surcharge) - parseFloat(discount);
            $total_cost.autoNumeric('set', total_cost);
            if (payment) {
                var refund = parseFloat(payment) - parseFloat(cancel_fee);
                $refund.autoNumeric('set', refund);
            } else {
                $refund.autoNumeric('set', 0);
            }
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var $view_edit_form_edit_passenger_cost_transfer_add_on = $('.view_edit_form_edit_passenger_cost_transfer_add_on');

            var $list_tr = $view_edit_form_edit_passenger_cost_transfer_add_on.find('table.edit_passenger_cost_transfer_addon_edit_passenger tbody tr');
            for (var i = 0; i < $list_tr.length; i++) {
                var $tr = $($list_tr.get(i));
                var transfer_fee = $tr.find('input.transfer_fee').autoNumeric('get');
                var discount = $tr.find('input.transfer_discount').autoNumeric('get');
                var surcharge = $tr.find('input.transfer_surcharge').autoNumeric('get');
                var total_cost = parseFloat(transfer_fee) + parseFloat(surcharge) - parseFloat(discount);
                total_cost_for_all += parseFloat(total_cost);
                var cancel_fee = $tr.find('input.transfer_cancel_fee').autoNumeric('get');
                total_cancel += parseFloat(cancel_fee);
                refund = $tr.find('input.transfer_refund').autoNumeric('get');
                total_refund += parseFloat(refund);

            }
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-refund').autoNumeric('set', total_refund);


        };
        plugin.calculator_passenger_cost_excursion_add_on = function ($tr, $self) {

            var excursion_fee = $tr.find('input.excursion_fee').autoNumeric('get');
            var discount = $tr.find('input.excursion_discount').autoNumeric('get');
            var surcharge = $tr.find('input.excursion_surcharge').autoNumeric('get');
            var cancel_fee = $tr.find('input.excursion_cancel_fee').autoNumeric('get');
            cancel_fee = cancel_fee ? cancel_fee : 0;
            var $payment = $tr.find('input.excursion_payment');
            var payment = $payment.autoNumeric('get');
            var $total_cost = $tr.find('input.total_cost');
            var $refund = $tr.find('input.excursion_refund');
            var total_cost = parseFloat(excursion_fee) + parseFloat(surcharge) - parseFloat(discount);
            $total_cost.autoNumeric('set', total_cost);
            if (payment) {
                var refund = parseFloat(payment) - parseFloat(cancel_fee);
                $refund.autoNumeric('set', refund);
            } else {
                $refund.autoNumeric('set', 0);
            }
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var $list_tr = $(".view_orders_edit_form_edit_passenger_cost_edit_excursion_addon").find('table.edit_passenger_cost_excursion_addon_edit_passenger tbody tr');
            for (var i = 0; i < $list_tr.length; i++) {
                var $tr = $($list_tr.get(i));
                var excursion_fee = $tr.find('input.excursion_fee').autoNumeric('get');
                var discount = $tr.find('input.excursion_discount').autoNumeric('get');
                var surcharge = $tr.find('input.excursion_surcharge').autoNumeric('get');
                var total_cost = parseFloat(excursion_fee) + parseFloat(surcharge) - parseFloat(discount);
                total_cost_for_all += parseFloat(total_cost);
                var cancel_fee = $tr.find('input.excursion_cancel_fee').autoNumeric('get');
                total_cancel += parseFloat(cancel_fee);
                refund = $tr.find('input.excursion_refund').autoNumeric('get');
                total_refund += parseFloat(refund);

            }
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-cancel').autoNumeric('set', total_cancel);
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-refund').autoNumeric('set', total_refund);

        };
        plugin.fill_data_passenger_cost = function (list_row) {
            for (var i = 0; i < list_row.length; i++) {
                var row = list_row[i];
                var $tr = $('table.edit_passenger_cost').find('tbody tr.passenger:eq(' + i + ')');
                $tr.find('input.tsmart_passenger_id').val(row.tsmart_passenger_id);
                $tr.find('input.tour_fee').autoNumeric('set', parseFloat(row.tour_fee));
                var surcharge = row.surcharge;
                surcharge = surcharge != null ? surcharge : 0;
                $tr.find('input.surcharge').autoNumeric('set', parseFloat(surcharge));
                $tr.find('input.single_room_fee').autoNumeric('set', parseFloat(row.single_room_fee));
                $tr.find('input.extra_fee').autoNumeric('set', parseFloat(row.extra_fee));
                var discount_fee = row.discount_fee;
                if (discount_fee != null) {
                    $tr.find('input.discount_fee').autoNumeric('set', parseFloat(row.discount_fee));
                } else {
                    $tr.find('input.discount_fee').autoNumeric('set', parseFloat(0));
                }
                var payment = row.payment;
                if (payment != null) {
                    $tr.find('input.payment').autoNumeric('set', parseFloat(row.payment));
                } else {
                    $tr.find('input.payment').autoNumeric('set', parseFloat(0));
                }
                var cancel_fee = row.cancel_fee;
                if (cancel_fee != null) {
                    $tr.find('input.cancel_fee').autoNumeric('set', parseFloat(row.cancel_fee));
                } else {
                    $tr.find('input.cancel_fee').autoNumeric('set', parseFloat(0));
                }


                //plugin.calculator_passenger_cost_main($tr);
            }

        };
        plugin.fill_data_passenger_cost_edit_hotel_add_on = function (list_passenger, type, hotel_addon_detail) {
            var $view_orders_edit_form_edit_passenger_cost_edit_hotel_addon= $(".view_orders_edit_form_edit_passenger_cost_edit_hotel_addon");
            var $tbody =$view_orders_edit_form_edit_passenger_cost_edit_hotel_addon.find('table.edit_passenger_cost_hotel_addon_edit_passenger tbody');
            $tbody.empty();
            var total_night = hotel_addon_detail.total_night;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var $tr = $(plugin.settings.row_passenger_cost_edit_hotel_addon);
                $tr.appendTo($tbody);
                $tr.find('.order').html(i + 1);
                $tr.find('.night_hotel_fee,.night_surcharge,.night_discount,.night_cancel_fee').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '0',
                    aSign: '',
                    wEmpty: 'zero'
                });
                $tr.find('.night_payment,.total_cost,.night_refund').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '0',
                    aSign: '',
                });

                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
                $tr.find('input.tsmart_passenger_id').val(passenger.tsmart_passenger_id);
                $tr.find('input.total_night').val(hotel_addon_detail.total_night);

                var night_surcharge = passenger[type + '_night_surcharge'];

                $tr.find('input.night_surcharge').autoNumeric('set', parseFloat(night_surcharge));

                $tr.find('input.night_hotel_fee').autoNumeric('set', parseFloat(passenger.unit_price_per_night));
                var night_cancel_fee = passenger[type + '_night_cancel_fee'];
                if (night_cancel_fee == null) {
                    $tr.find('input.night_cancel_fee').autoNumeric('set', 0);
                } else {
                    $tr.find('input.night_cancel_fee').autoNumeric('set', parseFloat(night_cancel_fee));
                }

                var night_discount = passenger[type + '_night_discount'];
                if (night_discount == null) {
                    $tr.find('input.night_discount').autoNumeric('set', parseFloat(0));
                } else {
                    $tr.find('input.night_discount').autoNumeric('set', parseFloat(night_discount));
                }
                var night_payment = passenger[type + '_night_payment'];
                if (night_payment != null) {
                    $tr.find('input.night_payment').autoNumeric('set', parseFloat(night_payment));
                }
                var night_hotel_fee=$tr.find('input.night_hotel_fee').autoNumeric('get');
                var total_cost=parseFloat(night_hotel_fee* total_night) ;
                $tr.find('input.total_cost').autoNumeric('set', total_cost);
                $tr.find('input.night_cancel_fee').autoNumeric('set', parseFloat(passenger[type + '_night_cancel_fee']));
                var night_refund = (night_payment != null ? parseFloat(night_payment) : 0) - (night_cancel_fee != null ? parseFloat(night_cancel_fee) : 0);
                $tr.find('input.night_refund').autoNumeric('set', parseFloat(night_refund));
            }
            $tbody.find('.passenger_cost:not(:disabled):not([readonly])').change(function () {
                var $tr = $(this).closest('tr.passenger');
                var $self = $(this);
                plugin.calculator_passenger_cost_hotel_add_on($tr, $self);
            });

            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var hotel_fee = parseFloat(passenger.unit_price_per_night).toFixed(1);
                var night_discount = passenger[type + '_night_discount'];
                night_discount=night_discount!=null?night_discount:0;

                var surcharge = passenger[type + '_night_surcharge'];
                surcharge=surcharge!=null?surcharge:0;

                var discount = passenger[type + '_night_discount'];
                discount=discount!=null?discount:0;
                var total_cost = parseFloat(hotel_fee) * total_night + parseFloat(surcharge) - parseFloat(discount);

                total_cost_for_all += parseFloat(total_cost);

                var cancel_fee = passenger[type + '_night_cancel_fee'];
                cancel_fee=cancel_fee!=null?cancel_fee:0;
                total_cancel += parseFloat(cancel_fee);

                var payment = passenger[type + '_night_payment'];
                payment=payment!=null?payment:0;

                var refund = payment-cancel_fee;

                total_refund += parseFloat(refund);

            }
            $view_orders_edit_form_edit_passenger_cost_edit_hotel_addon.find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $view_orders_edit_form_edit_passenger_cost_edit_hotel_addon.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_orders_edit_form_edit_passenger_cost_edit_hotel_addon.find('.total-refund').autoNumeric('set', total_refund);

        }

        plugin.fill_data_passenger_cost_edit_excursion_add_on = function (list_passenger, hotel_addon_detail) {
            var $tbody = $(".view_orders_edit_form_edit_passenger_cost_edit_excursion_addon").find('table.edit_passenger_cost_excursion_addon_edit_passenger tbody');
            $tbody.empty();
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var $tr = $(plugin.settings.row_passenger_cost_edit_excursion_addon);
                $tr.appendTo($tbody);
                $tr.find('.tsmart_passenger_id').html(passenger.tsmart_passenger_id);
                $tr.find('.excursion_fee,.excursion_surcharge,.excursion_discount,.excursion_cancel_fee').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '0',
                    aSign: '',
                    wEmpty: 'zero'
                });
                $tr.find('.excursion_payment').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '0',
                    aSign: '',
                });
                $tr.find('.total_cost,.excursion_refund').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '-99999',
                    aSign: '',
                });
                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
                $tr.find('input.tsmart_passenger_id').val(passenger.tsmart_passenger_id);
                $tr.find('input.excursion_fee').autoNumeric('set', parseFloat(passenger.excursion_fee));
                $tr.find('input.excursion_surcharge').autoNumeric('set', parseFloat(passenger.excursion_surcharge));
                $tr.find('input.excursion_payment').autoNumeric('set', parseFloat(passenger.excursion_payment));
                $tr.find('input.excursion_cancel_fee').autoNumeric('set', parseFloat(passenger.excursion_cancel_fee));
                total_cancel += parseFloat(passenger.excursion_cancel_fee);
                var excursion_fee = passenger.excursion_fee;
                var excursion_surcharge = passenger.excursion_surcharge;
                var excursion_discount = passenger.excursion_discount;
                $tr.find('input.excursion_discount').autoNumeric('set', parseFloat(excursion_discount));
                var total_cost = parseFloat(excursion_fee) + parseFloat(excursion_surcharge) - parseFloat(excursion_discount);
                $tr.find('input.total_cost').autoNumeric('set', parseFloat(total_cost));
                var excursion_payment = passenger.excursion_payment;
                var excursion_cancel_fee = passenger.excursion_cancel_fee;
                excursion_cancel_fee = excursion_cancel_fee ? excursion_cancel_fee : 0;
                refund = 0;
                if (excursion_payment != null) {
                    var refund = parseFloat(excursion_payment) - parseFloat(excursion_cancel_fee);
                    $tr.find('input.excursion_refund').autoNumeric('set', refund);
                } else {
                    $tr.find('input.excursion_refund').autoNumeric('set', 0);
                }
                total_cost_for_all += total_cost;
                total_refund += refund;
            }
            $tbody.find('.passenger_cost:not([disabled])').change(function () {
                var $tr = $(this).closest('tr.passenger');
                plugin.calculator_passenger_cost_excursion_add_on($tr, $(this));
            });
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-cancel').autoNumeric('set', total_cancel);
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-refund').autoNumeric('set', total_refund);

        };
        plugin.fill_data_service_cost_edit_hotel_add = function (data_price) {
            var $tbody = $(".view_orders_edit_form_edit_service_cost_edit_hotel").find('table.service_cost_edit_hotel tbody');
            $tbody.empty();
            for (var key in data_price) {
                var room = data_price[key];
                var $tr = $(plugin.settings.row_service_cost_edit_hotel_addon);
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
        plugin.fill_data_service_cost_edit_transfer_add_on = function (data_price) {
            console.log('update fill_data_service_cost_edit_transfer_add_on');
            return;
            var $tbody = $(".view_orders_edit_form_edit_service_cost_edit_transfer").find('table.service_cost_edit_hotel tbody');
            $tbody.empty();
            for (var key in data_price) {
                var room = data_price[key];
                var $tr = $(plugin.settings.row_service_cost_edit_hotel_addon);
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
            var $tbody = $(".view_orders_edit_order_bookinginfomation_hotel_addon").find('table.rooming-list tbody');
            $tbody.empty();
            var i = 1;
            for (var key_room_type in list_rooming) {
                var list_room = list_rooming[key_room_type];
                for (var tsmart_order_hotel_addon_id in list_room) {
                    var list_passenger = list_room[tsmart_order_hotel_addon_id];
                    var $tr = $(plugin.settings.tr_rooming_edit_hotel);
                    $tr.appendTo($tbody);
                    $tr.find('.order').html(i);
                    var list_full_name = [];
                    var list_title = [];
                    var room_type = key_room_type;
                    var room_note = '';
                    var room_created_on = '';
                    for (var passenger_index in list_passenger) {
                        var passenger = list_passenger[passenger_index];
                        room_note = passenger.room_note;
                        room_created_on = passenger.room_created_on;
                        var full_name = plugin.get_passenger_full_name(passenger);
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

            }

            $tbody.find('.service_cost').autoNumeric('init', plugin.settings.config_show_price);
            $tbody.find('.tax').autoNumeric('init', plugin.settings.numeric_tax_config);

        };
        plugin.fill_data_rooming_hotel_add_on2 = function (list_rooming) {
            var $tbody = $(".view_orders_edit_form_edit_room_in_hotel_add_on").find('table.rooming_hotel_add_on tbody');
            $tbody.empty();
            var i = 1;
            for (var key_room_type in list_rooming) {
                var list_room = list_rooming[key_room_type];


                for (var tsmart_order_hotel_addon_id in list_room) {
                    var list_passenger = list_room[tsmart_order_hotel_addon_id];
                    var $tr = $(plugin.settings.tr_rooming_edit_hotel_add_on);
                    $tr.appendTo($tbody);
                    $tr.find('.order').html(i);
                    var list_title = [];
                    var room_type = '';
                    var room_note = '';
                    var room_created_on = '';
                    for (var passenger_index in list_passenger) {
                        var passenger = list_passenger[passenger_index];
                        room_type = passenger.room_type;
                        room_note = passenger.room_note;
                        room_created_on = passenger.room_created_on;
                        var full_name = plugin.get_passenger_full_name(passenger);
                        var $full_name = $('<div class="passenger-item">' + full_name + '</div>');
                        $full_name.data('passenger', passenger);
                        $full_name.appendTo($tr.find('.full-name'));
                        list_title.push(passenger.title);
                    }
                    $tr.data('tsmart_order_hotel_addon_id', key_room_type);
                    $tr.find('.title').html(list_title.join("<br/>"));
                    $tr.find('.room-type').html(room_type);
                    $tr.find('.room-note').html(room_note);
                    $tr.find('.room-created-on').html(room_created_on);
                    i++;
                }

            }
            $tbody.find('a.remove-passenger-from-rooming-list').click(function () {
                var $tr = $(this).closest('.item-rooming');
                plugin.ajax_remove_passenger_from_rooming_list($tr);
            });


        };
        plugin.ajax_remove_passenger_from_rooming_list = function ($tr) {
            var $passengers = $tr.find('td .passenger-item');
            var $html_build_rooming_hotel_add_on_rooming_list = $('#html_build_rooming_hotel_rooming_list_hotel_add_on').data('html_build_rooming_list');
            var list_passenger = $html_build_rooming_hotel_add_on_rooming_list.settings.list_passenger;
            for (var i = 0; i < $passengers.length; i++) {
                var passenger = $($passengers.get(i)).data('passenger');
                list_passenger.push(passenger);
            }

            var $tbody = $(".view_orders_edit_form_edit_room_in_hotel_add_on").find('table.rooming_hotel_add_on tbody');

            $html_build_rooming_hotel_add_on_rooming_list.reset_build_rooming();
            var $ul = $(".view_orders_edit_form_edit_room_in_hotel_add_on").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            for (var i = 0; i < $passengers.length; i++) {
                var passenger = $($passengers.get(i)).data('passenger');
                var full_name = plugin.get_passenger_full_name(passenger);
                var $li = $('<li class="tags"><a href="javascript:void(0)">' + full_name + '</a></li>');
                $li.appendTo($ul);
            }
            $tr.remove();
            var i = 1;
            $tbody.find('tr').each(function () {
                $(this).find('.order').html(i);
                i++;
            });
        }
        plugin.fill_data_passenger_not_in_room_hotel_add_on = function (list_passenger_not_in_room) {
            var $ul = $(".view_orders_edit_form_edit_room_in_hotel_add_on").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            $ul.empty();
            for (var key in list_passenger_not_in_room) {
                var passenger = list_passenger_not_in_room[key];
                var full_name = plugin.get_passenger_full_name(passenger);
                var $li = $('<li class="tags"><a href="javascript:void(0)">' + full_name + '</a></li>');
                $li.appendTo($ul);
            }


        };
        plugin.fill_data_passenger_not_in_room_hotel_add_on2 = function (list_passenger_not_in_room) {
            var $ul = $(".view_orders_edit_order_bookinginfomation_hotel_addon").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            $ul.empty();
            for (var key in list_passenger_not_in_room) {
                var passenger = list_passenger_not_in_room[key];
                var full_name = plugin.get_passenger_full_name(passenger);
                var $li = $('<li class="tags"><a href="javascript:void(0)">' + full_name + '</a></li>');
                $li.appendTo($ul);
            }


        };
        plugin.fill_data_passenger_not_in_transfer_add_on = function (list_passenger_not_in_transfer) {
            var $ul = $(".view_edit_form_list_passenger_not_in_transfer").find('ul.list_passenger_not_in_temporary_and_not_in_transfer');
            $ul.empty();
            for (var key in list_passenger_not_in_transfer) {
                var passenger = list_passenger_not_in_transfer[key];
                var full_name = plugin.get_passenger_full_name(passenger);
                var $li = $('<li class="tags"><a href="javascript:void(0)">' + full_name + '</a></li>');
                $li.appendTo($ul);
            }


        };
        plugin.fill_data_passenger_not_in_excursion_add_on = function (list_passenger_not_in_excursion) {
            var $ul = $(".view_edit_form_list_passenger_not_in_excursion").find('ul.list_passenger_not_in_temporary_and_not_in_excursion');
            $ul.empty();
            for (var key in list_passenger_not_in_excursion) {
                var passenger = list_passenger_not_in_excursion[key];
                var full_name = plugin.get_passenger_full_name(passenger);
                var $li = $('<li class="tags"><a href="javascript:void(0)">' + full_name + '</a></li>');
                $li.appendTo($ul);
            }


        };
        plugin.calculator_passenger_cost_main_show = function ($tr) {
            var total_cost = $tr.find('span.total_cost').autoNumeric('get');
            var payment = $tr.find('span.payment').autoNumeric('get');
            var cancel_fee = $tr.find('span.cancel_fee').autoNumeric('get');
            var $balance = $tr.find('span.balance');
            var balance = parseFloat(total_cost) - parseFloat(payment);
            $balance.autoNumeric('set', balance);
            var $refund = $tr.find('span.refund');
            var refund = parseFloat(payment) - parseFloat(cancel_fee);
            $refund.autoNumeric('set', refund);
        };
        plugin.fill_data_passenger_show = function (list_row) {
            for (var i = 0; i < list_row.length; i++) {
                var row = list_row[i];
                var $tr = $('table.orders_show_form_passenger').find('tbody tr.passenger:eq(' + i + ')');
                $tr.find('span.total_cost').autoNumeric('set', parseFloat(row.total_cost));
                $tr.find('span.payment').autoNumeric('set', parseFloat(row.payment));
                $tr.find('span.cancel_fee').autoNumeric('set', parseFloat(row.cancel_fee));
                plugin.calculator_passenger_cost_main_show($tr);
            }
        };
        plugin.update_summary_main_tour = function (list_row) {
            var $edit_form_booking_summary = $('.view_orders_edit_order_edit_main_tour .edit_form_booking_summary');
            var total_cost_for_all = 0,
                total_cancel = 0,
                total_refund = 0;

            for (var i = 0; i < list_row.length; i++) {
                var passenger = list_row[i],
                    tour_cost = passenger.tour_cost,
                    room_fee = passenger.room_fee,
                    surcharge = passenger.surcharge,
                    extra_fee = passenger.extra_fee,
                    discount = passenger.discount,
                    cancel_fee = passenger.cancel_fee,
                    payment = passenger.payment
                    ;
                tour_cost = tour_cost != null ? tour_cost : 0;
                room_fee = room_fee != null ? room_fee : 0;
                surcharge = surcharge != null ? surcharge : 0;
                extra_fee = extra_fee != null ? extra_fee : 0;
                discount = discount != null ? discount : 0;
                total_cost_for_all += (parseFloat(tour_cost) + parseFloat(room_fee) + parseFloat(extra_fee) + parseFloat(surcharge) - parseFloat(discount));
                cancel_fee=cancel_fee=!null?cancel_fee:0;
                total_cancel+= parseFloat(cancel_fee);
                payment=payment!=null?payment:0;
                total_refund += (parseFloat(payment) - parseFloat(cancel_fee));
            }

            $edit_form_booking_summary.find('span.gross_total').autoNumeric('set', parseFloat(total_cost_for_all));
            $edit_form_booking_summary.find('span.cancel').autoNumeric('set', parseFloat(total_cancel));
            $edit_form_booking_summary.find('span.refund').autoNumeric('set', parseFloat(total_refund));
            var main_tour_current_payment=$edit_form_booking_summary.find('input.main_tour_current_payment').autoNumeric('get');
            var main_tour_current_discount=$edit_form_booking_summary.find('span.main_tour_current_discount').autoNumeric('get');
            var main_tour_current_commission=$edit_form_booking_summary.find('span.main_tour_current_commission').autoNumeric('get');
            var main_tour_net_total=total_cost_for_all-(main_tour_current_discount+main_tour_current_commission+total_cancel);
            $edit_form_booking_summary.find('span.refund').autoNumeric('set', parseFloat(total_refund));
            var balance=main_tour_net_total-main_tour_current_payment;
            $edit_form_booking_summary.find('span.balance').autoNumeric('set', parseFloat(balance));

        };
        plugin.update_passenger_cost_main_tour = function (list_row) {
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var $view_edit_form_edit_passenger_cost_main_tour = $('.view_orders_edit_form_edit_passenger_cost');

            var $list_tr = $view_edit_form_edit_passenger_cost_main_tour.find('table.edit_passenger_cost tbody tr');
            for (var i = 0; i < list_row.length; i++) {
                var $current_tr = $($list_tr.get(i));
                var passenger=list_row[i];
                var passenger = list_row[i],
                    total_cost= 0,
                    refund= 0,
                    tour_cost = passenger.tour_cost,
                    room_fee = passenger.room_fee,
                    surcharge = passenger.surcharge,
                    extra_fee = passenger.extra_fee,
                    discount = passenger.discount,
                    cancel_fee = passenger.cancel_fee,
                    payment = passenger.payment
                    ;
                tour_cost = tour_cost != null ? tour_cost : 0;
                room_fee = room_fee != null ? room_fee : 0;
                surcharge = surcharge != null ? surcharge : 0;
                extra_fee = extra_fee != null ? extra_fee : 0;
                discount = discount != null ? discount : 0;
                total_cost= parseFloat(tour_cost) + parseFloat(room_fee) + parseFloat(surcharge) + parseFloat() - parseFloat(discount);
                total_cost_for_all +=total_cost;
                cancel_fee=cancel_fee=!null?cancel_fee:0;
                total_cancel += parseFloat(cancel_fee);
                payment=payment!=null?payment:0;
                refund= parseFloat(payment) - parseFloat(cancel_fee);
                total_refund += refund;

                $current_tr.find('input.tour_fee').autoNumeric('set',tour_cost);
                $current_tr.find('input.single_room_fee').autoNumeric('set',room_fee);
                $current_tr.find('input.extra_fee').autoNumeric('set',extra_fee);
                $current_tr.find('input.surcharge').autoNumeric('set',surcharge);
                $current_tr.find('input.discount_fee').autoNumeric('set',discount);
                $current_tr.find('input.total_cost').autoNumeric('set',total_cost);
                $current_tr.find('input.payment').autoNumeric('set',payment);
                $current_tr.find('input.cancel_fee').autoNumeric('set',cancel_fee);
                $current_tr.find('input.refund').autoNumeric('set',refund);
            }
            $view_edit_form_edit_passenger_cost_main_tour.find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $view_edit_form_edit_passenger_cost_main_tour.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_edit_form_edit_passenger_cost_main_tour.find('.total-refund').autoNumeric('set', total_refund);

        };
        plugin.update_data_order = function () {
            var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_main_tour_order_detail_by_order_id',
                        tsmart_order_id: tsmart_order_id
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
                    var order_data = response.r.order_data;
                    var list_row = response.list_row;
                    plugin.update_summary_main_tour(list_row);
                    plugin.update_passenger_cost_main_tour(list_row);
                    plugin.fill_data_passenger_show(list_row);

                }
            });
        };
        plugin.update_booking_infomation = function (response) {
            var $tr_main_tour = $('table.listallservicebooking').find('tr.main-tour');
            $tr_main_tour.find('td.total-passenger').html(response.total_passenger);
            $tr_main_tour.find('td.service_date').html(response.service_date);
            $element.find('.booking-detail .service_date').html(response.departure_date);
            $element.find('.booking-detail .assign-name').html(response.assign_name);

        };
        plugin.update_orders_show_form_passenger = function (list_row) {
            for (var i = 0; i < list_row.length; i++) {
                var row = list_row[i];
                var $tr = $('table.orders_show_form_passenger').find('tbody tr.passenger:eq(' + i + ')');
                $tr.find('select.passenger_status').val(row.passenger_status);
                $tr.find('input[name="tsmart_passenger_id[]"]').val(row.tsmart_passenger_id);
            }
        };
        plugin.change_status_passenger_in_hotel_add_on = function (passenger, status) {
            var type = $('.order_edit_night_hotel').find('input[name="type"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_passenger_in_hotel_add_on',
                        status: status,
                        type: type,
                        tsmart_passenger_id: passenger.tsmart_passenger_id
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
                    if (response.e == 0) {
                        alert('change status passenger successful');
                    }
                }
            });

        };
        plugin.change_status_passenger_in_main_tour = function (tsmart_passenger_id, status) {
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_passenger_in_main_tour',
                        status: status,
                        tsmart_passenger_id: tsmart_passenger_id
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
                    if (response.e == 0) {
                        alert('change status passenger successful');
                    }
                }
            });

        };
        plugin.change_status_passenger_in_transfer_add_on = function (passenger, status) {
            var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
            var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
            var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();

            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_passenger_in_transfer_add_on',
                        status: status,
                        type: type,
                        tsmart_passenger_id: passenger.tsmart_passenger_id
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
                    if (response.e == 0) {
                        alert('change status passenger successful');
                    }
                }
            });

        };
        plugin.change_status_passenger_in_excursion_add_on = function (passenger, status) {
            var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
            var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
            var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();

            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_passenger_in_excursion_add_on',
                        tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                        status: status,
                        type: type,
                        tsmart_passenger_id: passenger.tsmart_passenger_id
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
                    if (response.e == 0) {
                        alert('change status passenger successful');
                    }
                }
            });

        };
        plugin.update_orders_show_form_hotel_addon_passenger = function (list_passenger, type) {
            var $tbody = $(".view_orders_edit_form_passenger_edit_hotel_add_on").find('table.orders_show_form_passenger_edit_hotel_addon tbody');
            $tbody.empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var $tr = $(plugin.settings.row_passenger_edit_hotel_addon);
                $tr.find('.cost').autoNumeric('init', plugin.settings.config_show_price);
                $tr.data('passenger', passenger);
                $tr.find('.tsmart_passenger_id').html(passenger.tsmart_passenger_id);
                $tr.find('select.passenger_status').val(passenger[type + '_night_status']);
                $tr.find('.book_date').html(passenger.created_on);
                $tr.find('.total_cost').autoNumeric('set', passenger[type + '_night_total_cost']);
                var night_payment = passenger[type + '_night_payment'];

                $tr.find('.balance').html(passenger[type + '_night_balance']);
                var night_cancel_fee = passenger[type + '_night_cancel_fee'];
                night_cancel_fee = night_cancel_fee != null ? night_cancel_fee : 0;
                $tr.find('.cancel_fee').autoNumeric('set', night_cancel_fee);

                var night_refund = passenger[type + '_night_refund'];
                night_refund = night_refund != null ? night_refund : 0;
                $tr.find('.refund').autoNumeric('set', night_refund);
                if (night_payment == null) {
                    $tr.find('.payment').html("N/A");
                } else {
                    $tr.find('.payment').autoNumeric('set', night_payment);
                }

                $tr.appendTo($tbody);

                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
            }
            $tbody.find('select.passenger_status').change(function () {
                var passenger = $(this).closest('tr.passenger').data('passenger');
                var status = $(this).val();
                plugin.change_status_passenger_in_hotel_add_on(passenger, status);
            });
        };
        plugin.update_orders_show_form_transfer_addon_passenger = function (list_passenger, type) {
            var $tbody = $(".view_edit_form_passenger_edit_transfer_addon").find('table.orders_show_form_passenger_edit_transfer_addon tbody');
            $tbody.empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var $tr = $(plugin.settings.row_passenger_edit_transfer_addon);
                $tr.find('.cost').autoNumeric('init', plugin.settings.config_show_price);
                $tr.data('passenger', passenger);
                $tr.find('.tsmart_passenger_id').html(passenger.tsmart_passenger_id);
                $tr.find('select.passenger_status').val(passenger[type + '_transfer_status']);
                $tr.find('.book_date').html(passenger.created_on);
                $tr.find('.total_cost').autoNumeric('set', passenger[type + '_transfer_total_cost']);
                var night_payment = passenger[type + '_transfer_payment'];
                var transfer_cancel_fee = passenger[type + 'transfer_cancel_fee'];

                $tr.find('.cancel_fee').autoNumeric('set', transfer_cancel_fee == null ? 0 : transfer_cancel_fee);
                $tr.find('.refund').autoNumeric('set', passenger[type + '_transfer_refund']);
                if (night_payment == null) {
                    $tr.find('.payment').autoNumeric('set', 0);
                } else {
                    $tr.find('.payment').autoNumeric('set', night_payment);
                }


                $tr.appendTo($tbody);

                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
            }
            $tbody.find('select.passenger_status').change(function () {
                var passenger = $(this).closest('tr.passenger').data('passenger');
                var status = $(this).val();
                plugin.change_status_passenger_in_transfer_add_on(passenger, status);
            });
        };
        plugin.update_orders_show_form_passenger_edit_excursion_add_on = function (response) {
            var list_passenger = response.list_passenger;
            var $tbody = $(".view_orders_edit_form_passenger_edit_excursion").find('table.orders_show_form_passenger_edit_excursion_addon tbody');
            $tbody.empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];

                var $tr = $(plugin.settings.row_passenger_edit_excursion_addon);
                $tr.find('.cost').autoNumeric('init', plugin.settings.config_show_price);
                $tr.data('passenger', passenger);
                $tr.find('.tsmart_passenger_id').html(passenger.tsmart_passenger_id);
                $tr.find('.book_date').html(passenger.created_on);
                $tr.find('.total_cost').autoNumeric('set', passenger.excursion_total_cost);
                var excursion_payment = passenger.excursion_payment;
                excursion_payment = excursion_payment != null ? excursion_payment : 0;
                $tr.find('.payment').autoNumeric('set', excursion_payment);
                var excursion_cancel_fee = passenger.excursion_cancel_fee;
                excursion_cancel_fee = excursion_cancel_fee != null ? excursion_cancel_fee : 0;
                $tr.find('.cancel_fee').autoNumeric('set', excursion_cancel_fee);
                var excursion_refund = passenger.excursion_refund;
                excursion_refund = excursion_refund != null ? excursion_refund : 0;
                $tr.find('.refund').autoNumeric('set', excursion_refund);

                if (passenger.excursion_payment === null) {
                    $tr.find('.payment').html('N/A');
                    $tr.find('.refund').autoNumeric('set', 0);
                }
                $tr.appendTo($tbody);

                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
                $tr.find('select.passenger_status').val(passenger.excursion_group_state);
            }
            $tbody.find('select.passenger_status').change(function () {
                var passenger = $(this).closest('tr.passenger').data('passenger');
                var status = $(this).val();
                plugin.change_status_passenger_in_excursion_add_on(passenger, status);
            });
        };
        plugin.update_orders_show_form_general_main_tour = function (response) {
            var order_detail = response.r;
            var main_tour_order=response.main_tour_order;
            var terms_condition = main_tour_order.terms_condition;
            var reservation_notes = main_tour_order.reservation_notes;
            var itinerary = main_tour_order.itinerary;
            $('.edit-form-general').find('#terms_condition_readonly').val(terms_condition);
            $('.edit-form-general').find('#reservation_notes_readonly').val(reservation_notes);
            $('.edit_form_booking_summary').find('select#tsmart_orderstate_id').val(order_detail.tsmart_orderstate_id);
            tinymce.get("itinerary_readonly").setContent(itinerary);
            //$('.edit-form-general').find('#terms_condition').val(terms_condition);
        };
        plugin.update_orders_show_form_general_edit_hotel_addon = function (response, type) {
            var order_detail = response.order_detail;
            var hotel_addon_detail = response.hotel_addon_detail;
            var terms_condition = hotel_addon_detail.terms_condition;
            var reservation_notes = hotel_addon_detail.reservation_notes;
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.hotel_name').val(hotel_addon_detail.hotel_name);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.hotel_location').val(hotel_addon_detail.hotel_location.full_city);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.check_in_date').val(hotel_addon_detail.show_checkin_date);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('span.group_hotel_addon_order').html(hotel_addon_detail.tsmart_group_hotel_addon_order_id);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.check_out_date').val(hotel_addon_detail.show_checkout_date);
            $(".order_edit_night_hotel").find('input[name="tsmart_group_hotel_addon_order_id"]').val(hotel_addon_detail.tsmart_group_hotel_addon_order_id);
            $(".order_edit_night_hotel").find('input[name="tsmart_hotel_addon_id"]').val(hotel_addon_detail.tsmart_hotel_addon_id);


            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary select[name="hotel_add_on_status"]').val(hotel_addon_detail.group_status).trigger('change.select2');
            ;
            $(".order_edit_night_hotel").find('input[name="type"]').val(type);
            var $user_html_select_user = $('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').data('user_html_select_user');
            $user_html_select_user.set_value(response.list_assign_user_id_manager_hotel_add_on);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').addClass('hide_more');


            var hotel_location = hotel_addon_detail.hotel_location;
            if (typeof hotel_location != "undefined" && hotel_location != null) {
                $('.view_orders_edit_from_general_edit_hotel_addon').find('input.hotel_location').val(hotel_addon_detail.hotel_location.full_city);
            }
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.check_in_date').val(hotel_addon_detail.show_checkin_date);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('span.group_hotel_addon_order').html(hotel_addon_detail.tsmart_order_hotel_addon_id);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('input.check_out_date').val(hotel_addon_detail.show_checkout_date);
            $(".view_orders_edit_order_edit_hotel_add_on").find('input[name="tsmart_order_hotel_addon_id"]').val(hotel_addon_detail.tsmart_order_hotel_addon_id);
            var total_night=hotel_addon_detail.total_night;
            var group_hotel_current_discount=hotel_addon_detail.group_hotel_current_discount;
            var group_hotel_current_commission=hotel_addon_detail.group_hotel_current_commission;
            var group_hotel_payment=hotel_addon_detail.group_hotel_payment;
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var list_passenger=response.list_passenger;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var hotel_fee = parseFloat(passenger.unit_price_per_night).toFixed(1);
                var night_discount = passenger[type + '_night_discount'];
                night_discount=night_discount!=null?night_discount:0;

                var surcharge = passenger[type + '_night_surcharge'];
                surcharge=surcharge!=null?surcharge:0;

                var discount = passenger[type + '_night_discount'];
                discount=discount!=null?discount:0;
                var total_cost = parseFloat(hotel_fee) * total_night + parseFloat(surcharge) - parseFloat(discount);

                total_cost_for_all += parseFloat(total_cost);

                var cancel_fee = passenger[type + '_night_cancel_fee'];
                cancel_fee=cancel_fee!=null?cancel_fee:0;
                total_cancel += parseFloat(cancel_fee);

                var payment = passenger[type + '_night_payment'];
                payment=payment!=null?payment:0;

                var refund = payment-cancel_fee;

                total_refund += parseFloat(refund);

            }
            var net_total=total_cost_for_all-(parseFloat(group_hotel_current_discount)+parseFloat(group_hotel_current_commission)+total_cancel);
            var balance=group_hotel_payment-total_cancel;
            var $view_orders_edit_form_hotel_add_on_summary = $(".view_orders_edit_order_edit_hotel_addon").find('.view_orders_edit_form_hotel_add_on_summary');
            $view_orders_edit_form_hotel_add_on_summary.find('.gross_total').autoNumeric('set', total_cost_for_all);
            $view_orders_edit_form_hotel_add_on_summary.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_orders_edit_form_hotel_add_on_summary.find('.total-refund').autoNumeric('set', total_refund);
            $view_orders_edit_form_hotel_add_on_summary.find('span.net_total').autoNumeric('set', net_total);
            $view_orders_edit_form_hotel_add_on_summary.find('span.group_hotel_current_discount').autoNumeric('set', group_hotel_current_discount);
            $view_orders_edit_form_hotel_add_on_summary.find('span.group_hotel_current_commission').autoNumeric('set', group_hotel_current_commission);
            $view_orders_edit_form_hotel_add_on_summary.find('span.balance').autoNumeric('set', balance);
            $view_orders_edit_form_hotel_add_on_summary.find('span.cancel').autoNumeric('set', total_cancel);
            $view_orders_edit_form_hotel_add_on_summary.find('span.refund').autoNumeric('set', total_refund);
            $view_orders_edit_form_hotel_add_on_summary.find('select[name="hotel_add_on_status"]').val(hotel_addon_detail.group_status);
            $view_orders_edit_form_hotel_add_on_summary.find('input.group_hotel_current_payment').autoNumeric('set', group_hotel_payment);

            var $user_html_select_user = $('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').data('user_html_select_user');
            $user_html_select_user.set_value(response.list_assign_user_id_manager_hotel_add_on);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').addClass('hide_more');


            //$('.edit_form_booking_summary').find('select#tsmart_orderstate_id').val(order.tsmart_orderstate_id).trigger('change');
            //$('.edit-form-general').find('#terms_condition').val(terms_condition);
            var data_price = hotel_addon_detail.data_price;
            plugin.fill_data_service_cost_edit_hotel_add(data_price);
            plugin.fill_data_rooming_hotel_add_on(response.list_rooming);





        };

        plugin.update_orders_show_form_general_edit_excursion_addon = function (response, type) {
            var order_detail = response.order_detail;
            var excursion_addon_detail = response.excursion_addon;
            var terms_condition = excursion_addon_detail.terms_condition;
            var reservation_notes = excursion_addon_detail.reservation_notes;
            $('.view_orders_edit_from_general_edit_excursion_addon').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_excursion_addon').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_excursion_addon').find('input.excursion_addon_name').val(excursion_addon_detail.excursion_addon_name);
            var excursion_location = excursion_addon_detail.excursion_location;
            if (typeof excursion_location != "undefined" && excursion_location != null) {
                $('.view_orders_edit_from_general_edit_excursion_addon').find('input.excursion_location').val(excursion_addon_detail.excursion_location.full_city);
            }
            $('.view_orders_edit_from_general_edit_excursion_addon').find('input.check_in_date').val(excursion_addon_detail.show_checkin_date);
            $('.view_orders_edit_from_general_edit_excursion_addon').find('span.group_excursion_addon_order').html(excursion_addon_detail.tsmart_order_excursion_addon_id);
            $('.view_orders_edit_from_general_edit_excursion_addon').find('input.check_out_date').val(excursion_addon_detail.show_checkout_date);
            $(".view_orders_edit_order_edit_excursion_add_on").find('input[name="tsmart_order_excursion_addon_id"]').val(excursion_addon_detail.tsmart_order_excursion_addon_id);

            var gross_total = 0;
            var total_discount = 0;
            var total_cancel = 0;
            var gross_cost = 0;
            var group_excursion_current_commission = 0;
            var group_excursion_current_discount = 0;
            var payment = excursion_addon_detail.group_excursion_payment;
            payment = payment != null ? payment : 0;
            var total_refund = 0;
            var list_passenger = response.list_passenger;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var excursion_cancel_fee = passenger.excursion_cancel_fee;
                excursion_cancel_fee = excursion_cancel_fee != null ? excursion_cancel_fee : 0;
                gross_total += parseFloat(passenger.excursion_total_cost);
                total_cancel += parseFloat(excursion_cancel_fee);
                if (passenger.excursion_payment === null) {
                    total_refund += 0;
                } else {
                    total_refund += passenger.excursion_payment - passenger.excursion_cancel_fee;
                }

            }
            var net_total = parseFloat(gross_total) - (parseFloat(group_excursion_current_discount) + parseFloat(group_excursion_current_commission) + parseFloat(total_cancel));
            var balance = net_total - parseFloat(payment);
            var $view_orders_edit_form_excursion_add_on_summary = $(".view_orders_edit_order_edit_excursion_add_on").find('.view_orders_edit_form_excursion_add_on_summary');
            $view_orders_edit_form_excursion_add_on_summary.find('span.gross_total').autoNumeric('set', gross_total);
            $view_orders_edit_form_excursion_add_on_summary.find('span.net_total').autoNumeric('set', net_total);
            $view_orders_edit_form_excursion_add_on_summary.find('span.group_excursion_current_discount').autoNumeric('set', group_excursion_current_discount);
            $view_orders_edit_form_excursion_add_on_summary.find('span.group_excursion_current_commission').autoNumeric('set', group_excursion_current_commission);
            $view_orders_edit_form_excursion_add_on_summary.find('span.balance').autoNumeric('set', balance);
            $view_orders_edit_form_excursion_add_on_summary.find('span.cancel').autoNumeric('set', total_cancel);
            $view_orders_edit_form_excursion_add_on_summary.find('span.refund').autoNumeric('set', total_refund);
            $view_orders_edit_form_excursion_add_on_summary.find('select[name="excursion_add_on_status"]').val(excursion_addon_detail.group_status);
            $view_orders_edit_form_excursion_add_on_summary.find('input.group_excursion_current_payment').autoNumeric('set', payment);
            var $user_html_select_user = $('#user_html_select_user_read_only_list_assign_user_id_manager_excursion_add_on').data('user_html_select_user');
            $user_html_select_user.set_value(response.list_assign_user_id_manager_excursion_add_on);
            $('.view_orders_edit_from_general_edit_excursion_addon').find('#user_html_select_user_read_only_list_assign_user_id_manager_excursion_add_on').addClass('hide_more');

            //$('.edit_form_booking_summary').find('select#tsmart_orderstate_id').val(order.tsmart_orderstate_id).trigger('change');
            //$('.edit-form-general').find('#terms_condition').val(terms_condition);
            var data_price = excursion_addon_detail.data_price;
            //plugin.fill_data_service_cost_edit_excursion_add(data_price);
            //plugin.fill_data_rooming_excursion_add_on(response.list_rooming);


        };
        plugin.update_orders_show_form_general_edit_transfer_addon = function (response, type) {
            var order_detail = response.order_detail;
            var transfer_addon = response.transfer_addon;
            var terms_condition = transfer_addon.terms_condition;
            var reservation_notes = transfer_addon.reservation_notes;
            $('.view_orders_edit_from_general_edit_transfer').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_transfer').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_transfer').find('input.transfer_addon_name').val(transfer_addon.transfer_addon_name);
            var transfer_location = transfer_addon.transfer_location;
            if (typeof transfer_location != "undefined" && transfer_location != null) {
                $('.view_orders_edit_from_general_edit_transfer').find('input.transfer_location').val(transfer_location.full_city);
            }
            $('.view_orders_edit_from_general_edit_transfer').find('input.check_in_date').val(transfer_addon.show_checkin_date);
            $('.view_orders_edit_from_general_edit_transfer').find('span.group_hotel_addon_order').html(transfer_addon.tsmart_group_hotel_addon_order_id);
            $('.view_orders_edit_from_general_edit_transfer').find('input.check_out_date').val(transfer_addon.show_checkout_date);
            $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="tsmart_order_transfer_addon_id"]').val(transfer_addon.tsmart_order_transfer_addon_id);
            $(".view_orders_edit_from_general_edit_transfer").find('span.tsmart_order_transfer_addon_id').html(transfer_addon.tsmart_order_transfer_addon_id);
            var $view_orders_edit_form_transfer_add_on_summary = $(".view_orders_edit_order_edit_transfer_add_on").find('.view_orders_edit_form_transfer_add_on_summary');


            var gross_total = 0;
            var total_discount = 0;
            var total_cancel = 0;
            var gross_cost = 0;
            var group_transfer_current_commission = 0;
            var group_transfer_current_discount = 0;
            var payment = transfer_addon.group_transfer_payment;
            payment = payment != null ? payment : 0;
            var total_refund = 0;
            var list_passenger = response.list_passenger;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var transfer_fee= passenger[type + '_transfer_fee'];
                transfer_fee = transfer_fee != null ? transfer_fee : 0;
                console.log('transfer_fee:'+transfer_fee);
                var transfer_surchage= passenger[type + '_transfer_surcharge'];
                transfer_surchage = transfer_surchage != null ? transfer_surchage : 0;
                console.log('transfer_surchage:'+transfer_surchage);
                var transfer_discount= passenger[type + '_transfer_discount'];
                transfer_discount = transfer_discount != null ? transfer_discount : 0;
                console.log('transfer_discount:'+transfer_discount);
                gross_total+= (parseFloat(transfer_fee)+parseFloat(transfer_surchage)-parseFloat(transfer_discount));
                var transfer_cancel_fee = passenger[type + '_transfer_cancel_fee'];
                transfer_cancel_fee = transfer_cancel_fee != null ? transfer_cancel_fee : 0;
                total_cancel += parseFloat(transfer_cancel_fee);
                var transfer_payment = passenger[type + '_transfer_payment'];
                transfer_payment = transfer_payment != null ? transfer_payment : 0;
                total_refund += transfer_payment - transfer_cancel_fee;
            }
            var net_total = parseFloat(gross_total) - (parseFloat(group_transfer_current_discount) + parseFloat(group_transfer_current_commission) + parseFloat(total_cancel));

            var balance = net_total - parseFloat(payment);
            $view_orders_edit_form_transfer_add_on_summary.find('span.gross_total').autoNumeric('set', gross_total);
            $view_orders_edit_form_transfer_add_on_summary.find('span.net_total').autoNumeric('set', net_total);
            $view_orders_edit_form_transfer_add_on_summary.find('span.group_transfer_current_discount').autoNumeric('set', group_transfer_current_discount);
            $view_orders_edit_form_transfer_add_on_summary.find('span.group_transfer_current_commission').autoNumeric('set', group_transfer_current_commission);
            $view_orders_edit_form_transfer_add_on_summary.find('span.balance').autoNumeric('set', balance);
            $view_orders_edit_form_transfer_add_on_summary.find('span.cancel').autoNumeric('set', total_cancel);
            $view_orders_edit_form_transfer_add_on_summary.find('span.refund').autoNumeric('set', total_refund);
            $view_orders_edit_form_transfer_add_on_summary.find('select[name="transfer_add_on_status"]').val(transfer_addon.group_status);
            $view_orders_edit_form_transfer_add_on_summary.find('input.group_transfer_current_payment').autoNumeric('set', payment);

            $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val(type);
            var $user_html_select_user = $('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').data('user_html_select_user');
            $user_html_select_user.set_value(response.list_assign_user_id_manager_hotel_add_on);
            $('.view_orders_edit_from_general_edit_hotel_addon').find('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').addClass('hide_more');
            var data_price = transfer_addon.data_price;
            plugin.fill_data_service_cost_edit_transfer_add_on(data_price);

        };
        plugin.init_main_tour = function () {
            //change main tour status
            $(".view_orders_edit_order_edit_main_tour").find('.edit_form_booking_summary select[name="tsmart_main_tour_order_state_id"]').change(function () {
                var tsmart_main_tour_order_state_id = $(this).val();
                plugin.change_status_main_tour(tsmart_main_tour_order_state_id);
            });
            //end change main tour payment
            //change main tour status
            $(".view_orders_edit_order_edit_main_tour").find('.edit_form_booking_summary input.main_tour_current_payment').change(function () {
                var main_tour_current_payment = $(this).autoNumeric('get');
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_order_main_tour_id = $('.view_orders_edit_order_edit_main_tour').find('input[name="tsmart_order_main_tour_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_main_tour_current_payment',
                            main_tour_current_payment: main_tour_current_payment,
                            tsmart_order_main_tour_id: tsmart_order_main_tour_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                            var net_total = $('.view_orders_edit_order_edit_main_tour .edit_form_booking_summary').find('span.main_tour_net_total').autoNumeric('get');
                            var balance = net_total - main_tour_current_payment;
                            $('.view_orders_edit_order_edit_main_tour .edit_form_booking_summary').find('span.balance').autoNumeric('set', balance);
                        }


                    }
                });
            });
            //end change main tour payment
            $('.view_orders_edit_form_edit_passenger_cost').find('.total-cost-for-all,.total-cancel,.total-refund').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999.00',
                aSign: 'US$ '
            });
            var $view_orders_edit_order_edit_main_tour = $('.view_orders_edit_order_edit_main_tour');
            $view_orders_edit_order_edit_main_tour.find('.view_orders_edit_form_passenger select.passenger_status').change(function () {
                var tsmart_passenger_id = $(this).closest('tr.passenger').data('tsmart_passenger_id');
                var status = $(this).val();
                plugin.change_status_passenger_in_main_tour(tsmart_passenger_id, status);
            });
            //tinyMCE.get('itinerary_readonly').getBody().setAttribute('contenteditable', 'false');
            //console.log(tinymce.get("itinerary_readonly").getContent());
            //tinymce.get("jform_articletext").getContent();
            //tinymce.get('itinerary_readonly').getBody().setAttribute('contenteditable', 'false');
        };
        plugin.init_hotel_add_on = function () {
            var $view_orders_edit_form_hotel_add_on_summary = $(".view_orders_edit_order_edit_hotel_addon").find('.view_orders_edit_form_hotel_add_on_summary');


            $view_orders_edit_form_hotel_add_on_summary.find('.gross_total,.group_hotel_current_discount,.group_hotel_current_commission,.net_total,.balance,.cancel,.refund').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999.00',
                aSign: 'US$ '
            });
            $view_orders_edit_form_hotel_add_on_summary.find('input.group_hotel_current_payment').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: 'US$ ',
                wEmpty: 'zero'
            });

            $view_orders_edit_form_hotel_add_on_summary.find('input.group_hotel_current_payment').change(function () {
                var group_hotel_current_payment = $(this).autoNumeric('get');
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_group_hotel_current_payment',
                            group_hotel_payment: group_hotel_current_payment,
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                            var net_total = $('.view_orders_edit_form_hotel_add_on_summary').find('span.net_total').autoNumeric('get');
                            var balance = net_total - group_hotel_current_payment;
                            $('.view_orders_edit_form_hotel_add_on_summary').find('span.balance').autoNumeric('set', balance);
                        }


                    }
                });

            });
            $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon').find('.total-cost-for-all,.total-cancel,.total-refund').autoNumeric('init', plugin.settings.config_show_price);


        };
        plugin.init_transfer_add_on = function () {
            var $view_orders_edit_form_transfer_add_on_summary = $(".view_orders_edit_order_edit_transfer_add_on").find('.view_orders_edit_form_transfer_add_on_summary');
            $view_orders_edit_form_transfer_add_on_summary.find('.gross_total,.group_transfer_current_discount,.group_transfer_current_commission,.net_total,.balance,.cancel,.refund').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999.00',
                aSign: 'US$ '
            });
            $view_orders_edit_form_transfer_add_on_summary.find('input.group_transfer_current_payment').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: 'US$ ',
                wEmpty: 'zero'
            });

            $view_orders_edit_form_transfer_add_on_summary.find('input.group_transfer_current_payment').change(function () {
                var group_transfer_current_payment = $(this).autoNumeric('get');
                var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_group_transfer_current_payment',
                            group_transfer_payment: group_transfer_current_payment,
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            type: type,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                            var net_total = $('.view_orders_edit_form_transfer_add_on_summary').find('span.net_total').autoNumeric('get');
                            var balance = net_total - group_transfer_current_payment;
                            $('.view_orders_edit_form_transfer_add_on_summary').find('span.balance').autoNumeric('set', balance);
                        }


                    }
                });

            });
            $view_orders_edit_form_transfer_add_on_summary.find('select[name="transfer_add_on_status"]').change(function () {
                var transfer_add_on_status = $(this).val();
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'change_status_group_transfer',
                            transfer_add_on_status: transfer_add_on_status,
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                        }


                    }
                });

            });


        };
        plugin.init_excursion_add_on = function () {
            var $view_orders_edit_form_excursion_add_on_summary = $(".view_orders_edit_order_edit_excursion_add_on").find('.view_orders_edit_form_excursion_add_on_summary');

            $view_orders_edit_form_excursion_add_on_summary.find('.gross_total,.group_excursion_current_discount,.group_excursion_current_commission,.net_total,.balance,.cancel,.refund').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '-9999999',
                aSign: 'US$ ',
                wEmpty: 'zero'
            });
            $view_orders_edit_form_excursion_add_on_summary.find('input.group_excursion_current_payment').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '0',
                aSign: 'US$ ',
                wEmpty: 'zero'
            });

            $view_orders_edit_form_excursion_add_on_summary.find('input.group_excursion_current_payment').change(function () {
                var group_excursion_current_payment = $(this).autoNumeric('get');
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_group_excursion_current_payment',
                            group_excursion_payment: group_excursion_current_payment,
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                            var net_total = $('.view_orders_edit_form_excursion_add_on_summary').find('span.net_total').autoNumeric('get');
                            var balance = net_total - group_excursion_current_payment;
                            $('.view_orders_edit_form_excursion_add_on_summary').find('span.balance').autoNumeric('set', balance);
                        }


                    }
                });

            });
            $view_orders_edit_form_excursion_add_on_summary.find('select[name="excursion_add_on_status"]').change(function () {
                var excursion_add_on_status = $(this).val();
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'change_status_group_excursion',
                            excursion_add_on_status: excursion_add_on_status,
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                        }


                    }
                });

            });

        };
        plugin.update_orders_show_form_general_edit_hotel_addon2 = function (response, type) {
            var order_detail = response.order_detail;
            var hotel_addon_detail = response.hotel_addon_detail;
            var terms_condition = hotel_addon_detail.terms_condition;
            var reservation_notes = hotel_addon_detail.reservation_notes;

            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('input.hotel_name').val(hotel_addon_detail.hotel_name);
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('input.hotel_location').val(hotel_addon_detail.hotel_location.full_city);


            var $user_html_select_user_list_assign_user_id_manager_hotel_add_on = $('#user_html_select_user_list_assign_user_id_manager_hotel_add_on').data('user_html_select_user');
            $user_html_select_user_list_assign_user_id_manager_hotel_add_on.set_value(response.list_assign_user_id_manager_hotel_add_on);

            var $select_date_night_hotel_checkin_date = $('#select_date_night_hotel_checkin_date').data('html_select_date');
            $select_date_night_hotel_checkin_date.set_date(hotel_addon_detail.checkin_date);

            var $select_date_night_hotel_checkout_date = $('#select_date_night_hotel_checkout_date').data('html_select_date');
            $select_date_night_hotel_checkout_date.set_min_date(hotel_addon_detail.checkin_date);
            $select_date_night_hotel_checkout_date.set_date(hotel_addon_detail.checkout_date);

            $select_date_night_hotel_checkin_date.on_select(function () {
                var date = $select_date_night_hotel_checkin_date.get_date();
                $select_date_night_hotel_checkout_date.set_min_date(date);
            });
            //$select_date_night_hotel_checkout_date.set_min_date($select_date_night_hotel_checkin_date);
        };

        plugin.update_orders_show_form_general_edit_transfer_addon_popup = function (response, type) {
            var order_detail = response.order_detail;
            var transfer_addon = response.transfer_addon;
            var terms_condition = transfer_addon.terms_condition;
            var reservation_notes = transfer_addon.reservation_notes;
            $('.view_orders_edit_from_general_edit_transfer_popup').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_transfer_popup').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_transfer_popup').find('input.transfer_addon_name').val(transfer_addon.transfer_addon_name);
            var transfer_location = transfer_addon.transfer_location;
            if (typeof transfer_location != "undefined" && transfer_location != null) {
                $('.view_orders_edit_from_general_edit_transfer_popup').find('input.hotel_location').val(transfer_addon.hotel_location.full_city);
            }
            var $user_html_select_user_list_assign_user_id_manager_hotel_add_on = $('#user_html_select_user_list_assign_user_id_manager_transfer_add_on').data('user_html_select_user');
            $user_html_select_user_list_assign_user_id_manager_hotel_add_on.set_value(response.list_assign_user_id_manager_transfer_add_on);

            var $select_date_transfer_check_in_date = $('#select_date_transfer_check_in_date').data('html_select_date');
            $select_date_transfer_check_in_date.set_date(transfer_addon.checkin_date);
        };
        plugin.fill_data_passenger_detail = function (passenger_data) {
            var $view_orders_edit_form_add_and_remove_passenger = $(".view_orders_edit_form_add_and_remove_passenger");
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
            var $form_passenger = $(".view_orders_edit_form_add_and_remove_passenger");
            var $email_address = $form_passenger.find('input[name="email_address"]');
            var email_address = $email_address.val();
            var $confirm_email = $form_passenger.find('input[name="confirm_email"]');
            var confirm_email = $confirm_email.val();
            for (var i = 0; i < $form_passenger.find('input[required="required"]').length; i++) {
                var $input = $form_passenger.find('input[required="required"]:eq(' + i + ')');
                if ($input.val().trim() == "") {
                    alert("please input required filed");
                    $input.focus();
                    return false;
                }
            }
            if (!$.is_email(email_address)) {
                alert("email address invaild");
                $email_address.focus();
                return false;
            } else if (!$.is_email(confirm_email)) {
                alert("email address confirm invaild");
                $confirm_email.focus();
                return false;
            } else if (email_address != confirm_email) {
                alert("confirm email not same email address");
                $confirm_email.focus();
                return false;
            }
            return true;
        };
        plugin.validate_add_passenger_to_room = function () {
            var $form_add_passenger_to_room = $(".view_orders_edit_main_tour_form_add_more_passenger");
            for (var i = 0; i < $form_add_passenger_to_room.find('.passenger-item').length; i++) {
                var $passenger_item = $form_add_passenger_to_room.find('.passenger-item:eq(' + i + ')');
                var $select_passenger = $passenger_item.find('select.list-passenger');
                var select_passenger = $select_passenger.val();
                if (select_passenger == 0) {
                    alert("please select passenger");
                    $select_passenger.focus();
                    return false;
                }
            }
            return true;
        };
        plugin.fill_data_row_passenger_detail = function (passenger_data) {
            var $tr_passenger = $(".view_orders_edit_passenger_manager").find('tr.item-passenger[data-tsmart_passenger_id="' + passenger_data.tsmart_passenger_id + '"]');
            if ($tr_passenger.length == 0) {
                var tr_passenger = $(".view_orders_edit_passenger_manager").find('tr.item-passenger:first-child').getOuterHTML();
                $tr_passenger = $(tr_passenger);
                $tr_passenger.insertAfter($(".view_orders_edit_passenger_manager").find("tr.item-passenger:last-child"));
            }
            $tr_passenger.find('span.title').html(passenger_data.title);
            $tr_passenger.find('div.tsmart_passenger_id span.cid').html(passenger_data.tsmart_passenger_id);
            $tr_passenger.find('div.tsmart_passenger_id input[name="cid[]"]').val(passenger_data.tsmart_passenger_id);
            $tr_passenger.attr("data-tsmart_passenger_id", passenger_data.tsmart_passenger_id);
            $tr_passenger.data("tsmart_passenger_id", passenger_data.tsmart_passenger_id);
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
        plugin.get_passenger_not_in_room_by_tsmart_passenger_id = function (tsmart_passenger_id) {
            var list_passenger_not_in_room = plugin.settings.list_passenger_not_in_room;
            for (var i = 0; i < list_passenger_not_in_room.length; i++) {
                var passenger = list_passenger_not_in_room[i];
                if (passenger.tsmart_passenger_id == tsmart_passenger_id) {
                    return passenger;
                }
            }
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name;
            return passenger_full_name;
        };

        plugin.update_total_cost_form_add_passenger_to_room = function () {
            var full_total = 0;
            var update_full_total = true;
            var list_passenger_selected = plugin.settings.list_passenger_selected;
            for (var i = 0; i < list_passenger_selected.length; i++) {
                var item_passenger = list_passenger_selected[i];
                var tsmart_passenger_id = item_passenger.tsmart_passenger_id;
                if (tsmart_passenger_id > 0) {
                    var tour_cost = item_passenger.tour_cost;
                    var discount = item_passenger.passenger_discount;
                    var extra_fee = item_passenger.passenger_extra_fee;
                    var surcharge = item_passenger.passenger_surcharge;
                    var total = parseFloat(tour_cost) + parseFloat(extra_fee) + parseFloat(surcharge) - parseFloat(discount);
                    full_total += total;
                } else {
                    update_full_total = false;
                    break;
                }
            }
            if (update_full_total == true) {
                $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-calculator").find('span.cost.full-total').autoNumeric('set', full_total);
            }
        };
        plugin.update_build_rooming_by_passenger = function (list_passenger_not_in_temporary_and_not_in_room) {
            var $html_build_rooming_hotel_rooming_list = $('#html_build_rooming_hotel_rooming_list').data('html_build_rooming_list');
            $html_build_rooming_hotel_rooming_list.update_build_rooming_by_passenger(list_passenger_not_in_temporary_and_not_in_room);
            var $ul_list_passenger_not_in_temporary_and_not_in_room = $(".view_orders_edit_form_edit_room").find('ul.list_passenger_not_in_temporary_and_not_in_room');
            $ul_list_passenger_not_in_temporary_and_not_in_room.empty();
            for (var key in list_passenger_not_in_temporary_and_not_in_room) {
                var item_passenger = list_passenger_not_in_temporary_and_not_in_room[key];
                var full_name = plugin.get_passenger_full_name(item_passenger);
                $(' <li class="tag passenger">' + full_name + '(' + item_passenger.year_old + ' year)' + '</li>').appendTo($ul_list_passenger_not_in_temporary_and_not_in_room);

            }
        };
        plugin.fill_data_form_show_first_history_rooming = function (list_passenger) {
            var $order_edit_form_show_first_history_rooming = $(".order_edit_form_show_first_history_rooming");
            $order_edit_form_show_first_history_rooming.find('table.first_history_rooming tbody').empty();
            for (var tsmart_room_order_id in list_passenger) {
                var items = list_passenger[tsmart_room_order_id];
                var room_type = "";
                var creation = "";
                var names = [];
                var titles = [];
                for (var i = 0; i < items.length; i++) {
                    var passenger = items[i];
                    var full_name = plugin.get_passenger_full_name(passenger);
                    names.push(full_name);
                    titles.push(passenger.title);
                    room_type = passenger.room_type;
                    creation = passenger.created_on;
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
            var $order_edit_form_show_near_last_history_rooming = $(".order_edit_form_show_near_last_history_rooming");
            $order_edit_form_show_near_last_history_rooming.find('table.near_last_history_rooming tbody').empty();
            for (var tsmart_room_order_id in list_passenger) {
                var items = list_passenger[tsmart_room_order_id];
                var room_type = "";
                var creation = "";
                var names = [];
                var titles = [];
                for (var i = 0; i < items.length; i++) {
                    var passenger = items[i];
                    var full_name = plugin.get_passenger_full_name(passenger);
                    names.push(full_name);
                    titles.push(passenger.title);
                    room_type = passenger.room_type;
                    creation = passenger.created_on;
                }
                var $template_form_show_near_last_history_rooming = $(plugin.settings.template_show_near_last_history_rooming);
                $template_form_show_near_last_history_rooming.find('span.tsmart_room_order_id').html(tsmart_room_order_id);
                $template_form_show_near_last_history_rooming.find('span.names').html(names.join("<br/>"));
                $template_form_show_near_last_history_rooming.find('span.titles').html(titles.join("<br/>"));
                $template_form_show_near_last_history_rooming.find('span.room_type').html(room_type);
                $template_form_show_near_last_history_rooming.find('span.creation').html(creation);
                $template_form_show_near_last_history_rooming.appendTo($order_edit_form_show_near_last_history_rooming.find('table.near_last_history_rooming tbody'));
            }


        };
        plugin.fill_data_form_show_last_history_rooming = function (list_passenger) {
            var $order_edit_form_show_last_history_rooming = $(".order_edit_form_show_last_history_rooming");
            $order_edit_form_show_last_history_rooming.find('table.last_history_rooming tbody').empty();
            for (var tsmart_room_order_id in list_passenger) {
                var items = list_passenger[tsmart_room_order_id];
                var room_type = "";
                var creation = "";
                var names = [];
                var titles = [];
                for (var i = 0; i < items.length; i++) {
                    var passenger = items[i];
                    var full_name = plugin.get_passenger_full_name(passenger);
                    names.push(full_name);
                    titles.push(passenger.title);
                    room_type = passenger.room_type;
                    creation = passenger.created_on;
                }
                var $template_form_show_last_history_rooming = $(plugin.settings.template_show_last_history_rooming);
                $template_form_show_last_history_rooming.find('span.tsmart_room_order_id').html(tsmart_room_order_id);
                $template_form_show_last_history_rooming.find('span.names').html(names.join("<br/>"));
                $template_form_show_last_history_rooming.find('span.titles').html(titles.join("<br/>"));
                $template_form_show_last_history_rooming.find('span.room_type').html(room_type);
                $template_form_show_last_history_rooming.find('span.creation').html(creation);
                $template_form_show_last_history_rooming.appendTo($order_edit_form_show_last_history_rooming.find('table.last_history_rooming tbody'));
            }


        };
        plugin.reset_build_rooming_hotel_add_on_form = function (response, type) {

            var $build_room_hotel_add_on = $('#html_build_room').data('build_room_hotel_add_on');
            $build_room_hotel_add_on.settings.list_passenger = response.list_passenger_not_in_room;
            $build_room_hotel_add_on.settings.list_night_hotel = [];
            var item = {
                list_room_type: "single",
                passengers: [],
                room_note: ""
            };
            var departure = $build_room_hotel_add_on.settings.departure;
            departure.hotel_addon_detail = response.hotel_addon_detail;
            departure.hotel_addon_detail.type = type;
            $build_room_hotel_add_on.settings.departure = departure;
            $build_room_hotel_add_on.settings.list_night_hotel.push(item);
            $build_room_hotel_add_on.reset_build_hotel_add_on();

            //$build_room_hotel_add_on.update_passengers(list_passenger,index_action,event_name);

        };
        plugin.reset_build_rooming_main_tour_form = function (response) {

            var $html_build_rooming_list = $('#html_build_rooming_hotel_rooming_list').data('html_build_rooming_list');
            $html_build_rooming_list.reset_build_rooming();
            //$html_build_rooming_list.settings.list_passenger=response.list_passenger_not_in_room;
        };
        plugin.reset_build_rooming_hotel_add_on_form2 = function (response, type) {
            var $html_build_rooming_hotel_add_on_rooming_list = $('#html_build_rooming_hotel_rooming_list_hotel_add_on').data('html_build_rooming_list');
            $html_build_rooming_hotel_add_on_rooming_list.reset_build_rooming();
            $html_build_rooming_hotel_add_on_rooming_list.settings.list_passenger = response.list_passenger_not_in_room;
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
            var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id',
                        type: type,
                        tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                        tsmart_order_id: tsmart_order_id
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
                    var list_passenger = response.list_passenger;
                    plugin.update_orders_show_form_hotel_addon_passenger(list_passenger, type);
                    plugin.update_orders_show_form_general_edit_hotel_addon(response, type);
                    plugin.fill_data_passenger_not_in_room_hotel_add_on2(response.list_passenger_not_in_room);
                    $(".order_edit_night_hotel").dialog('open');

                }
            });

        };
        plugin.load_transfer_add_on_by_tsmart_transfer_addon_id = function (tsmart_order_transfer_addon_id, type) {
            var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_order_detail_and_transfer_add_on_detail_by_tsmart_order_transfer_addon_id',
                        tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                        type: type,
                        tsmart_order_id: tsmart_order_id
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
                    var list_passenger = response.list_passenger;
                    plugin.update_orders_show_form_general_edit_transfer_addon(response, type);
                    plugin.update_orders_show_form_service_cost_transfer_addon(response, type);
                    plugin.update_orders_show_form_transfer_addon_passenger(list_passenger, type);
                    plugin.fill_data_passenger_not_in_transfer_add_on(response.list_passenger_not_in_transfer);
                    $(".order_edit_transfer").dialog('open');

                }
            });


        };
        plugin.change_status_group_hotel_add_on = function (status) {
            var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_group_hotel_add_on',
                        tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                        status: status,
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
                    if (response.e == 0) {
                        alert('change status successful');
                    }

                }
            });
        };
        plugin.change_status_main_tour = function (tsmart_main_tour_order_state_id) {
            var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
            var tsmart_order_main_tour_id = $('.view_orders_edit_order_edit_main_tour').find('input[name="tsmart_order_main_tour_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'change_status_main_tour',
                        tsmart_order_id: tsmart_order_id,
                        tsmart_order_main_tour_id: tsmart_order_main_tour_id,
                        tsmart_main_tour_order_state_id: tsmart_main_tour_order_state_id,
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
                    if (response.e == 0) {
                        alert('change status successful');
                    }

                }
            });
        };
        plugin.re_load_main_booking_info = function (tsmart_order_id) {
            var tsmart_order_main_tour_id = $('.view_orders_edit_order_edit_main_tour').find('input[name="tsmart_order_main_tour_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_main_tour_order_detail_by_order_id',
                        tsmart_order_main_tour_id: tsmart_order_main_tour_id,
                        tsmart_order_id: tsmart_order_id
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
                    var list_row = response.list_row;
                    plugin.update_orders_show_form_passenger(list_row);
                    plugin.update_orders_show_form_general_main_tour(response);
                    $(".order_edit_main_tour").dialog('open');

                }
            });

        };
        plugin.build_form_add_passenger_to_transfer = function (response, type) {
            plugin.settings.list_passenger_not_in_transfer = response.list_passenger_not_in_transfer;
            plugin.settings.current_transfer_addon = response.transfer_addon;
            var $select_total_passenger = $('.view_edit_form_add_passenger_to_transfer_add_on').find('select.total_passenger_transfer');
            $select_total_passenger.empty();
            var $option = $('<option value="0">select number passenger</option>');
            $option.appendTo($select_total_passenger);
            for (var i = 1; i <= plugin.settings.list_passenger_not_in_transfer.length; i++) {
                var $option = $('<option value="' + i + '">' + i + '</option>');
                $option.appendTo($select_total_passenger);
            }
            $('.view_edit_form_and_passenger_to_transfer_add_on').find('select.total_passenger_transfer').val(0).trigger('change');
            var $body_table_add_passenger_to_transfer = $('.view_edit_form_add_passenger_to_transfer_add_on').find('.body-table-add-passenger-to-transfer');
            $body_table_add_passenger_to_transfer.empty();


        };
        plugin.get_cost_transfer_by_tsmart_passenger_id = function (tsmart_passenger_id) {
            var list_passenger_not_in_transfer = plugin.settings.list_passenger_not_in_transfer;
            var transfer_addon = plugin.settings.current_transfer_addon;
            var children_under_year = transfer_addon;
            for (var i = 0; i < list_passenger_not_in_transfer.length; i++) {
                var passenger = list_passenger_not_in_transfer[i];
                if (passenger.tsmart_passenger_id == tsmart_passenger_id) {
                    if (passenger.year_old <= children_under_year) {
                        return transfer_addon.children_cost;
                    } else {
                        return transfer_addon.adult_cost;
                    }
                }
            }
            return 0;
        };
        plugin.get_cost_excursion_by_tsmart_passenger_id = function (tsmart_passenger_id) {
            var list_passenger_not_in_excursion = plugin.settings.list_passenger_not_in_excursion;
            var excursion_addon = plugin.settings.current_excursion_addon;
            var children_under_year = excursion_addon;
            for (var i = 0; i < list_passenger_not_in_excursion.length; i++) {
                var passenger = list_passenger_not_in_excursion[i];
                if (passenger.tsmart_passenger_id == tsmart_passenger_id) {
                    if (passenger.year_old <= children_under_year) {
                        return excursion_addon.children_cost;
                    } else {
                        return excursion_addon.adult_cost;
                    }
                }
            }
            return 0;
        };
        plugin.calculator_transfer_cost = function () {
            var $list_transfer_passenger_item = $('.view_edit_form_add_passenger_to_transfer_add_on').find('.body-table-add-passenger-to-transfer .passenger-item');
            var total = 0;
            var total_cost_invail = true;
            for (var i = 0; i < $list_transfer_passenger_item.length; i++) {
                var $item_passenger_cost = $($list_transfer_passenger_item.get(i));
                var $select_passenger_item = $item_passenger_cost.find('select.select-passenger-item');
                var tsmart_passenger_id = $select_passenger_item.val();
                var discount = $item_passenger_cost.find('input.discount').autoNumeric('get');

                if (tsmart_passenger_id == 0) {
                    $item_passenger_cost.find('span.cost').autoNumeric('set', 'N/A');
                    $item_passenger_cost.find('span.total_cost_per_persion').autoNumeric('set', 'N/A');
                    total_cost_invail = false;
                } else {
                    var cost = plugin.get_cost_transfer_by_tsmart_passenger_id(tsmart_passenger_id);
                    $item_passenger_cost.find('span.cost').autoNumeric('set', cost);
                    var total_cost_per_persion = cost - discount;
                    $item_passenger_cost.find('span.total_cost_per_persion').autoNumeric('set', total_cost_per_persion);
                    total += total_cost_per_persion;
                }
            }
            $('.view_edit_form_add_passenger_to_transfer_add_on').find('.total-for-all-passenger').autoNumeric('set', total_cost_invail ? total : 'N/A');
        };
        plugin.calculator_excursion_cost = function () {
            var $list_excursion_passenger_item = $('.view_edit_form_add_passenger_to_excursion_add_on').find('.body-table-add-passenger-to-excursion .passenger-item');
            var total = 0;
            var total_cost_invail = true;
            for (var i = 0; i < $list_excursion_passenger_item.length; i++) {
                var $item_passenger_cost = $($list_excursion_passenger_item.get(i));
                var $select_passenger_item = $item_passenger_cost.find('select.select-passenger-item');
                var tsmart_passenger_id = $select_passenger_item.val();
                var discount = $item_passenger_cost.find('input.discount').autoNumeric('get');
                var surcharge = $item_passenger_cost.find('input.surcharge').autoNumeric('get');
                if (tsmart_passenger_id == 0) {
                    $item_passenger_cost.find('span.cost').html('N/A');
                    $item_passenger_cost.find('span.total_cost_per_persion').html('N/A');
                    total_cost_invail = false;
                } else {
                    var cost = plugin.get_cost_excursion_by_tsmart_passenger_id(tsmart_passenger_id);
                    $item_passenger_cost.find('span.cost').autoNumeric('set', cost);
                    total_cost_per_persion = parseFloat(cost);
                    if (surcharge != "" && discount != "") {
                        var total_cost_per_persion = parseFloat(cost) + parseFloat(surcharge) - parseFloat(discount);
                    } else if (surcharge != "" && discount === "") {
                        var total_cost_per_persion = parseFloat(cost) + parseFloat(surcharge);
                    } else if (surcharge === "" && discount != "") {
                        var total_cost_per_persion = parseFloat(cost) - parseFloat(discount);
                    }
                    $item_passenger_cost.find('span.cost').autoNumeric('set', total_cost_per_persion);
                    $item_passenger_cost.find('span.total_cost_per_persion').autoNumeric('set', total_cost_per_persion);
                    total += total_cost_per_persion;
                }
            }
            if (total_cost_invail) {
                $('.view_edit_form_add_passenger_to_excursion_add_on').find('.total-for-all-passenger').autoNumeric('set', total);
            } else {
                $('.view_edit_form_add_passenger_to_excursion_add_on').find('.total-for-all-passenger').html('N/A');
            }

        };
        plugin.fill_data_passenger_cost_edit_transfer_add_on = function (response, type) {
            var list_passenger = response.list_passenger;
            var $view_edit_form_edit_passenger_cost_transfer_add_on = $('.view_edit_form_edit_passenger_cost_transfer_add_on');
            var $tbody = $view_edit_form_edit_passenger_cost_transfer_add_on.find('table.edit_passenger_cost_transfer_addon_edit_passenger tbody');
            $tbody.empty();
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var $tr = $(plugin.settings.row_transfer_passenger_cost);
                $tr.appendTo($tbody);
                $tr.find('.tsmart_passenger_id').html(passenger.tsmart_passenger_id);
                $tr.find('.transfer_fee,.transfer_surcharge,.transfer_discount,.transfer_cancel_fee').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '0',
                    aSign: '',
                    wEmpty: 'zero'
                });
                $tr.find('.transfer_payment').autoNumeric('init', {
                    mDec: 1,
                    aSep: ' ',
                    vMin: '0',
                    aSign: '',
                });
                $tr.find('.total_cost,.transfer_refund').autoNumeric('init', plugin.settings.passenger_cost_config);


                $tr.find('.passenger_cost').autoNumeric('init', plugin.settings.passenger_cost_config);
                $tr.find('span.full-name').html(plugin.get_passenger_full_name(passenger));
                $tr.find('input.tsmart_passenger_id').val(passenger.tsmart_passenger_id);
                $tr.find('input.transfer_fee').autoNumeric('set', parseFloat(passenger[type + '_transfer_fee']));
                $tr.find('input.transfer_surcharge').autoNumeric('set', parseFloat(passenger[type + '_transfer_surcharge']));
                $tr.find('input.transfer_payment').autoNumeric('set', parseFloat(passenger[type + '_transfer_payment']));
                $tr.find('input.transfer_cancel_fee').autoNumeric('set', parseFloat(passenger[type + '_transfer_cancel_fee']));
                total_cancel += parseFloat(passenger[type + '_transfer_cancel_fee']);
                var transfer_fee = passenger[type + '_transfer_fee'];
                var transfer_surcharge = passenger[type + '_transfer_surcharge'];
                var transfer_discount = passenger[type + '_transfer_discount'];
                $tr.find('input.transfer_discount').autoNumeric('set', parseFloat(transfer_discount));
                var total_cost = parseFloat(transfer_fee) + parseFloat(transfer_surcharge) - (transfer_discount != null ? parseFloat(transfer_discount) : 0);
                $tr.find('input.total_cost').autoNumeric('set', parseFloat(total_cost));
                var transfer_payment = passenger[type + '_transfer_payment'];
                var transfer_cancel_fee = passenger[type + '_transfer_cancel_fee'];
                transfer_cancel_fee = transfer_cancel_fee ? transfer_cancel_fee : 0;
                refund = 0;
                if (transfer_payment != null) {
                    var refund = parseFloat(transfer_payment) - parseFloat(transfer_cancel_fee);
                    $tr.find('input.transfer_refund').autoNumeric('set', refund);
                } else {
                    $tr.find('input.transfer_refund').autoNumeric('set', 0);
                }
                total_cost_for_all += total_cost;
                total_refund += refund;


                var transfer_fee = passenger[type + '_transfer_fee'];
                var transfer_discount = passenger[type + '_transfer_discount'];
                var total_cost = 0;


            }
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-refund').autoNumeric('set', total_refund);


            $tbody.find('.passenger_cost').change(function () {
                var $tr = $(this).closest('tr.passenger');
                var $self = $(this);
                plugin.calculator_passenger_cost_transfer_add_on($tr, $self);
            });
            var total_cost_for_all = 0;
            var total_cancel = 0;
            var total_refund = 0;
            var $view_edit_form_edit_passenger_cost_transfer_add_on = $('.view_edit_form_edit_passenger_cost_transfer_add_on');

            var $list_tr = $view_edit_form_edit_passenger_cost_transfer_add_on.find('table.edit_passenger_cost_transfer_addon_edit_passenger tbody tr');
            for (var i = 0; i < $list_tr.length; i++) {
                var $tr = $($list_tr.get(i));
                var transfer_fee = $tr.find('input.transfer_fee').autoNumeric('get');
                var discount = $tr.find('input.transfer_discount').autoNumeric('get');
                var surcharge = $tr.find('input.transfer_surcharge').autoNumeric('get');
                var total_cost = parseFloat(transfer_fee) + parseFloat(surcharge) - parseFloat(discount);
                total_cost_for_all += parseFloat(total_cost);
                var cancel_fee = $tr.find('input.transfer_cancel_fee').autoNumeric('get');
                total_cancel += parseFloat(cancel_fee);
                refund = $tr.find('input.transfer_refund').autoNumeric('get');
                total_refund += parseFloat(refund);

            }
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-cost-for-all').autoNumeric('set', total_cost_for_all);
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-cancel').autoNumeric('set', total_cancel);
            $view_edit_form_edit_passenger_cost_transfer_add_on.find('.total-refund').autoNumeric('set', total_refund);

        };
        plugin.update_orders_show_form_service_cost_excursion_addon = function (response) {
            //plugin.settings.row_service_cost_excursion_add_on
            var $tbody = $(".view_orders_edit_form_edit_service_cost_edit_excursion_add_on").find('table.service_cost_edit_excursion_add_on tbody');
            $tbody.empty();
            var excursion_addon = response.excursion_addon;

            var $row_service_cost_excursion_add_on = $(plugin.settings.row_service_cost_excursion_add_on);
            $row_service_cost_excursion_add_on.find('span.room_type').html('adult');
            $row_service_cost_excursion_add_on.find('span.net_price').html(excursion_addon.net_price);
            $row_service_cost_excursion_add_on.find('span.mark_up').html(excursion_addon.mark_up);
            $row_service_cost_excursion_add_on.find('span.tax').html(excursion_addon.tax);
            $row_service_cost_excursion_add_on.find('span.sale_price').html(excursion_addon.adult_cost);
            $row_service_cost_excursion_add_on.appendTo($tbody);

            var $row_service_cost_excursion_add_on = $(plugin.settings.row_service_cost_excursion_add_on);
            $row_service_cost_excursion_add_on.find('span.room_type').html('child');
            $row_service_cost_excursion_add_on.find('span.net_price').html(excursion_addon.net_price);
            $row_service_cost_excursion_add_on.find('span.mark_up').html(excursion_addon.mark_up);
            $row_service_cost_excursion_add_on.find('span.tax').html(excursion_addon.tax);
            $row_service_cost_excursion_add_on.find('span.sale_price').html(excursion_addon.children_cost);
            $row_service_cost_excursion_add_on.appendTo($tbody);
            $tbody.find('.service_cost').autoNumeric('init', plugin.settings.config_show_price);

        };
        plugin.update_orders_show_form_service_cost_transfer_addon = function (response) {
            //plugin.settings.row_service_cost_transfer_add_on
            var $tbody = $(".view_orders_edit_form_edit_service_cost_edit_transfer_add_on").find('table.service_cost_edit_transfer_add_on tbody');
            $tbody.empty();
            var transfer_addon = response.transfer_addon;

            var $row_service_cost_transfer_add_on = $(plugin.settings.row_service_cost_transfer_add_on);
            $row_service_cost_transfer_add_on.find('span.room_type').html('adult');
            $row_service_cost_transfer_add_on.find('span.net_price').html(transfer_addon.net_price);
            $row_service_cost_transfer_add_on.find('span.mark_up').html(transfer_addon.mark_up);
            $row_service_cost_transfer_add_on.find('span.tax').html(transfer_addon.tax);
            $row_service_cost_transfer_add_on.find('span.sale_price').html(transfer_addon.adult_cost);
            $row_service_cost_transfer_add_on.appendTo($tbody);

            var $row_service_cost_transfer_add_on = $(plugin.settings.row_service_cost_transfer_add_on);
            $row_service_cost_transfer_add_on.find('span.room_type').html('child');
            $row_service_cost_transfer_add_on.find('span.net_price').html(transfer_addon.net_price);
            $row_service_cost_transfer_add_on.find('span.mark_up').html(transfer_addon.mark_up);
            $row_service_cost_transfer_add_on.find('span.tax').html(transfer_addon.tax);
            $row_service_cost_transfer_add_on.find('span.sale_price').html(transfer_addon.children_cost);
            $row_service_cost_transfer_add_on.appendTo($tbody);
            $tbody.find('.service_cost').autoNumeric('init', plugin.settings.config_show_price);

        };
        plugin.load_excursion_add_on_by_tsmart_excusion_addon_id = function (tsmart_order_excursion_addon_id) {
            var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
            $.ajax({
                type: "POST",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'orders',
                        task: 'ajax_get_order_detail_and_excurstion_add_on_detail_by_tsmart_order_excurstion_addon_id',
                        tsmart_order_id: tsmart_order_id,
                        tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,

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
                    var list_row = response.list_row;
                    plugin.update_orders_show_form_general_edit_excursion_addon(response);
                    plugin.update_orders_show_form_service_cost_excursion_addon(response);
                    plugin.update_orders_show_form_passenger_edit_excursion_add_on(response);
                    plugin.fill_data_passenger_not_in_excursion_add_on(response.list_passenger_not_in_excursion);
                    $(".order_edit_excursion").dialog('open');

                }
            });
        };
        plugin.update_orders_show_form_general_edit_excursion_addon_popup = function (response) {
            var order_detail = response.order_detail;
            var excursion_addon = response.excursion_addon;
            var terms_condition = excursion_addon.terms_condition;
            var reservation_notes = excursion_addon.reservation_notes;
            $('.view_orders_edit_from_general_edit_excursion_addon_popup').find('#terms_condition').val(terms_condition);
            $('.view_orders_edit_from_general_edit_excursion_addon_popup').find('#reservation_notes').val(reservation_notes);
            $('.view_orders_edit_from_general_edit_excursion_addon_popup').find('input.excursion_addon_name').val(excursion_addon.excursion_addon_name);
            var excursion_location = excursion_addon.excursion_location;

            if (typeof excursion_location != "undefined" && excursion_location != null) {
                $('.view_orders_edit_from_general_edit_excursion_addon_popup').find('input.excursion_location').val(excursion_addon.hotel_location.full_city);
            }
            var $user_html_select_user_list_assign_user_id_manager_hotel_add_on = $('#user_html_select_user_list_assign_user_id_manager_excursion_add_on').data('user_html_select_user');
            $user_html_select_user_list_assign_user_id_manager_hotel_add_on.set_value(response.list_assign_user_id_manager_excursion_add_on);

            var $select_date_excursion_check_in_date = $('#select_date_excursion_check_in_date').data('html_select_date');
            $select_date_excursion_check_in_date.set_date(excursion_addon.checkin_date);
        };


        plugin.build_form_add_passenger_to_excursion = function (response) {
            plugin.settings.list_passenger_not_in_excursion = response.list_passenger_not_in_excursion;
            plugin.settings.current_excursion_addon = response.excursion_addon;
            var $select_total_passenger = $('.view_edit_form_add_passenger_to_excursion_add_on').find('select.total_passenger_excursion');
            $select_total_passenger.empty();

            var $option = $('<option value="0">select number passenger</option>');
            $option.appendTo($select_total_passenger);
            for (var i = 1; i <= plugin.settings.list_passenger_not_in_excursion.length; i++) {
                var $option = $('<option value="' + i + '">' + i + '</option>');
                $option.appendTo($select_total_passenger);
            }
            $('.view_edit_form_add_passenger_to_excursion_add_on').find('select.total_passenger_excursion').val(0).trigger('change');
            var $body_table_add_passenger_to_excurrsion = $('.view_edit_form_add_passenger_to_excursion_add_on').find('.body-table-add-passenger-to-excursion');
            $body_table_add_passenger_to_excurrsion.empty();


        };
        plugin.config_view_add_more_passenger_main_tour = function () {
            var list_passenger_in_temporary_by_order_id = plugin.settings.list_passenger_in_temporary_by_order_id;
            var $select_number_passenger = $('.view_orders_edit_main_tour_form_add_more_passenger').find('.number-passenger');
            $select_number_passenger.empty();
            var $option = $('<option value="">please passenger</option>');
            $option.appendTo($select_number_passenger);
            if (list_passenger_in_temporary_by_order_id.length > 0) {
                for (var i = 0; i < list_passenger_in_temporary_by_order_id.length; i++) {
                    var $option = $('<option value="' + (i + 1) + '">' + (i + 1) + ' per(s)</option>');
                    $option.appendTo($select_number_passenger);
                }
            }
            var $wrapper_passenger = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-passenger");
            $wrapper_passenger.empty();
            var $wrapper_calculator = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-calculator");
            $wrapper_calculator.empty();

        };
        plugin.update_form_general_main_tour_popup = function (response) {
            var order_detail = response.r;
            var main_tour_order=response.main_tour_order;
            var terms_condition = main_tour_order.terms_condition;
            var reservation_notes = main_tour_order.reservation_notes;
            var itinerary = main_tour_order.itinerary;
            var $edit_form_general_popup_main_tour=$('.edit-form-general-popup-main-tour');
            console.log($edit_form_general_popup_main_tour);
            $edit_form_general_popup_main_tour.find('#main_tour_terms_condition').val(terms_condition);
            $edit_form_general_popup_main_tour.find('#main_tour_reservation_notes').val(reservation_notes);
            tinymce.get("itinerary").setContent(itinerary);
            $edit_form_general_popup_main_tour.find('#list_assign_user_id_manager_main_tour').val(main_tour_order.list_assign_user_id_manager_main_tour).trigger("change");
        };
        plugin.init = function () {


            plugin.init_main_tour();
            plugin.init_hotel_add_on();
            plugin.init_transfer_add_on();
            plugin.init_excursion_add_on();


            $('.edit_form_general_main_tour_popup').find('#main_product_code').keypress(function (e) {
                if (e.which == 13) {

                    // Do something here if the popup is open

                    $('.edit_form_general_main_tour_popup').find('#main_tour_terms_condition').focus();

                }
            });


            plugin.settings = $.extend({}, defaults, options);
            var task = plugin.settings.task;
            if (task == 'add_new_item') {

                $element.dialog({
                    dialogClass: 'asian-dialog-form',
                    modal: true,
                    width: 900,
                    title: 'Transfer add on',
                    show: {effect: "blind", duration: 800},
                    appendTo: 'body'
                });
            }
            $element.find('.cost').autoNumeric('init', plugin.settings.config_show_price);
            $('.view_orders_edit_form_edit_passenger_cost').find('.passenger_cost').autoNumeric('init', {
                mDec: 1,
                aSep: ' ',
                vMin: '-999999',
                aSign: '',
                wEmpty: 'zero'
            }).change(function () {
                var $tr = $(this).closest('tr.passenger');
                plugin.calculator_passenger_cost_main($tr, $(this));
            });
            /* $('table.edit_passenger_cost').find('tbody tr.passenger').each(function(){
             var $tr=$(this);
             plugin.calculator_passenger_cost_main($tr);
             });*/
            $('.view_orders_edit_form_edit_passenger_cost').find('button.save').click(function () {
                var $list_tr = $('table.edit_passenger_cost').find('tbody tr.passenger');
                var list_row = [];
                for (var i = 0; i < $list_tr.length; i++) {
                    var $tr = $('table.edit_passenger_cost').find('tbody tr.passenger:eq(' + i + ')');
                    var item = {};
                    item.tsmart_passenger_id = $tr.find('input.tsmart_passenger_id').val();
                    //item.tour_cost=$tr.find('input.tour_fee').autoNumeric('get');
                    item.room_fee = $tr.find('input.single_room_fee').autoNumeric('get');
                    item.extra_fee = $tr.find('input.extra_fee').autoNumeric('get');
                    item.surcharge = $tr.find('input.surcharge').autoNumeric('get');
                    var $discount = $tr.find('input.discount_fee');
                    if ($discount.val().toLowerCase().trim() == 'n/a' || $discount.val().trim() == '') {
                        item.discount = null;
                    } else {
                        item.discount = $tr.find('input.discount_fee').autoNumeric('get');
                    }

                    var $cancel_fee = $tr.find('input.cancel_fee');
                    if ($cancel_fee.val().toLowerCase().trim() == 'n/a' || $cancel_fee.val().trim() == '') {
                        item.cancel_fee = null;
                    } else {
                        item.cancel_fee = $cancel_fee.autoNumeric('get');
                    }

                    var $payment = $tr.find('input.payment');
                    if ($payment.val().toLowerCase().trim() == 'n/a' || $payment.val().trim() == '') {
                        item.payment = null;
                    } else {
                        item.payment = $payment.autoNumeric('get');
                    }
                    list_row.push(item);
                }
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost',
                            list_row: list_row,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        $(".order_edit_passenger_cost").dialog('close');
                        plugin.update_data_order();

                    }
                });

            });
            $('.view_orders_edit_form_transfer_add_on_summary').find('span.cost').autoNumeric('init', plugin.settings.passenger_cost_config);
            $('.view_edit_form_add_passenger_to_transfer_add_on').find('.total-for-all-passenger').autoNumeric('init', plugin.settings.config_show_price);


            $('.view_edit_form_add_passenger_to_transfer_add_on').find('select.total_passenger_transfer').change(function () {
                var total_passenger = $(this).val();
                var $body_table_add_passenger_to_transfer = $('.view_edit_form_add_passenger_to_transfer_add_on').find('.body-table-add-passenger-to-transfer');
                $body_table_add_passenger_to_transfer.empty();
                var list_passenger_not_in_transfer = plugin.settings.list_passenger_not_in_transfer;
                for (var i = 0; i < total_passenger; i++) {
                    var $item_row_passenger_cost_in_transfer_add_on = $(plugin.settings.item_row_passenger_cost_in_transfer_add_on);
                    var list_passenger_not_in_transfer = plugin.settings.list_passenger_not_in_transfer;
                    var $select_passenger = $item_row_passenger_cost_in_transfer_add_on.find('select.select-passenger-item');
                    var $option = $('<option value="0">select passenger</option>');
                    $option.appendTo($select_passenger);
                    for (var index in list_passenger_not_in_transfer) {
                        var passenger = list_passenger_not_in_transfer[index];
                        var full_name = plugin.get_passenger_full_name(passenger);
                        var $option = $('<option value="' + passenger.tsmart_passenger_id + '">' + full_name + '</option>');
                        $option.appendTo($select_passenger);
                    }
                    $item_row_passenger_cost_in_transfer_add_on.find('input.discount').autoNumeric('init', plugin.settings.config_show_price_discount);
                    $item_row_passenger_cost_in_transfer_add_on.find('input.surcharge').autoNumeric('init', plugin.settings.config_show_price_surcharge);
                    $select_passenger.change(function () {
                        plugin.calculator_transfer_cost();
                    });
                    $item_row_passenger_cost_in_transfer_add_on.find('input.discount').change(function () {
                        plugin.calculator_transfer_cost();
                    });
                    $item_row_passenger_cost_in_transfer_add_on.find('input.surcharge').change(function () {
                        plugin.calculator_transfer_cost();
                    });
                    $item_row_passenger_cost_in_transfer_add_on.find('span.cost').autoNumeric('init', plugin.settings.config_show_price);
                    $item_row_passenger_cost_in_transfer_add_on.find('span.total_cost_per_persion').autoNumeric('init', plugin.settings.config_show_price);


                    $item_row_passenger_cost_in_transfer_add_on.appendTo($body_table_add_passenger_to_transfer);
                }
            });

            $('.view_edit_form_add_passenger_to_excursion_add_on').find('select.total_passenger_excursion').change(function () {
                var total_passenger = $(this).val();
                var $body_table_add_passenger_to_excursion = $('.view_edit_form_add_passenger_to_excursion_add_on').find('.body-table-add-passenger-to-excursion');
                $body_table_add_passenger_to_excursion.empty();
                var list_passenger_not_in_excursion = plugin.settings.list_passenger_not_in_excursion;
                for (var i = 0; i < total_passenger; i++) {
                    var $item_row_passenger_cost_in_excursion_add_on = $(plugin.settings.item_row_passenger_cost_in_excursion_add_on);
                    var list_passenger_not_in_excursion = plugin.settings.list_passenger_not_in_excursion;
                    var $select_passenger = $item_row_passenger_cost_in_excursion_add_on.find('select.select-passenger-item');
                    var $option = $('<option value="0">select passenger</option>');
                    $option.appendTo($select_passenger);
                    for (var index in list_passenger_not_in_excursion) {
                        var passenger = list_passenger_not_in_excursion[index];
                        var full_name = plugin.get_passenger_full_name(passenger);
                        var $option = $('<option value="' + passenger.tsmart_passenger_id + '">' + full_name + '</option>');
                        $option.appendTo($select_passenger);
                    }
                    $item_row_passenger_cost_in_excursion_add_on.find('input.discount').autoNumeric('init', plugin.settings.config_show_price_discount);
                    $item_row_passenger_cost_in_excursion_add_on.find('input.surcharge').autoNumeric('init', plugin.settings.config_show_price_surcharge);
                    $select_passenger.change(function () {
                        plugin.calculator_excursion_cost();
                    });
                    $item_row_passenger_cost_in_excursion_add_on.find('input.discount').change(function () {
                        plugin.calculator_excursion_cost();
                    });
                    $item_row_passenger_cost_in_excursion_add_on.find('input.surcharge').change(function () {
                        plugin.calculator_excursion_cost();
                    });
                    $item_row_passenger_cost_in_excursion_add_on.find('span.cost').autoNumeric('init', plugin.settings.config_show_price);
                    $item_row_passenger_cost_in_excursion_add_on.find('span.total_cost_per_persion').autoNumeric('init', plugin.settings.config_show_price);


                    $item_row_passenger_cost_in_excursion_add_on.appendTo($body_table_add_passenger_to_excursion);
                }
            });
            $('.view_edit_form_add_passenger_to_excursion_add_on').find('.total-for-all-passenger').autoNumeric('init', plugin.settings.config_show_price);
            $(".order_edit_night_hotel").find('.view_orders_edit_form_hotel_add_on_summary select[name="hotel_add_on_status"]').change(function () {
                var status = $(this).val();
                plugin.change_status_group_hotel_add_on(status);
            });

            $('.view_orders_edit_from_general_edit_hotel_addon').find('#user_html_select_user_read_only_list_assign_user_id_manager_hotel_add_on').hover(function () {
                $(this).removeClass('hide_more');

            }, function () {
                $(this).addClass('hide_more');
            });


            $('.edit_form_general_main_tour_popup').find('button.cancel').click(function () {
                $(".edit_form_general_main_tour_popup").dialog('close');

            });
            $('.edit_form_general_main_tour_popup').find('button.save').click(function () {

                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var $edit_form_general_main_tour_popup=$(".edit_form_general_main_tour_popup");
                var departure_date = $edit_form_general_main_tour_popup.find('input[name="departure_date"]').val();
                var departure_date_end = $edit_form_general_main_tour_popup.find('input[name="departure_date_end"]').val();
                var terms_condition = $edit_form_general_main_tour_popup.find('#main_tour_terms_condition').val();
                var reservation_notes = $edit_form_general_main_tour_popup.find('#main_tour_reservation_notes').val();
                var tsmart_orderstate_id = $edit_form_general_main_tour_popup.find('#tsmart_orderstate_id').val();
                var tsmart_order_main_tour_id = $('.view_orders_edit_order_edit_main_tour').find('input[name="tsmart_order_main_tour_id"]').val();
                var itinerary_content = tinymce.get("itinerary").getContent();
                var list_assign_user_id_manager_main_tour = $edit_form_general_main_tour_popup.find('select[name="list_assign_user_id_manager_main_tour"]').val();

                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_main_tour_order_info',
                            tsmart_order_id: tsmart_order_id,
                            tsmart_order_main_tour_id: tsmart_order_main_tour_id,
                            departure_date: departure_date,
                            departure_date_end: departure_date_end,
                            terms_condition: terms_condition,
                            reservation_notes: reservation_notes,
                            itinerary: itinerary_content,
                            tsmart_orderstate_id: tsmart_orderstate_id,
                            list_assign_user_id_manager_main_tour: list_assign_user_id_manager_main_tour
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
                        if (response.e == 0) {
                            alert('save successful');
                            $(".edit_form_general_main_tour_popup").dialog('close');
                            plugin.re_load_main_booking_info(tsmart_order_id);
                        }

                    }
                });

            });
            $('.edit_night_hotel').find('button.save').click(function () {
                var $list_tr = $('table.orders_show_form_passenger').find('tbody tr.passenger');
                var list_row = [];
                for (var i = 0; i < $list_tr.length; i++) {
                    var $tr = $('table.orders_show_form_passenger').find('tbody tr.passenger:eq(' + i + ')');
                    var item = {};
                    item.tsmart_passenger_id = $tr.find('input[name="tsmart_passenger_id[]"]').val();
                    item.passenger_status = $tr.find('select.passenger_status').val();
                    list_row.push(item);
                }
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var departure_date = $(".order_edit_main_tour").find('input[name="departure_date"]').val();
                var departure_date_end = $(".order_edit_main_tour").find('input[name="departure_date_end"]').val();
                var assign_user_id = $(".order_edit_main_tour").find('#assign_user_id').val();
                var terms_condition = $(".order_edit_main_tour").find('#terms_condition').val();
                var reservation_notes = $(".order_edit_main_tour").find('#reservation_notes').val();
                var tsmart_orderstate_id = $(".order_edit_main_tour").find('#tsmart_orderstate_id').val();
                var itinerary_content = tinymce.get("jform_articletext").getContent();

                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_main_tour_order_info',
                            list_row: list_row,
                            tsmart_order_id: tsmart_order_id,
                            departure_date: departure_date,
                            departure_date_end: departure_date_end,
                            terms_condition: terms_condition,
                            reservation_notes: reservation_notes,
                            itinerary: itinerary_content,
                            tsmart_orderstate_id: tsmart_orderstate_id,
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        $(".order_edit_main_tour").dialog('close');
                        var list_row = response.list_row;
                        plugin.update_booking_infomation(response);

                    }
                });

            });
            $('.view_orders_edit_form_edit_passenger_cost').find('button.cancel').click(function () {
                $(".order_edit_passenger_cost").dialog('close');
            });
            $('.view_orders_edit_order_edit_main_tour').find('button.cancel').click(function () {
                $(".order_edit_main_tour").dialog('close');
            });
            $('.view_orders_edit_main_tour_form_add_more_passenger').find('button.cancel').click(function () {
                $(".order_add_more_passenger").dialog('close');
            });
            $('.order_edit_night_hotel').find('button.cancel').click(function () {
                $(".order_edit_night_hotel").dialog('close');
            });
            $('.order_edit_night_hotel').find('.edit-night-hotel-add-on').click(function () {
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type = $(".order_edit_night_hotel").find('input[name="type"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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
                        plugin.update_orders_show_form_general_edit_hotel_addon2(response, type);
                        $(".order_form_edit_night_hotel").dialog('open');

                    }
                });


            });

            $('.view_orders_edit_from_general_edit_transfer').find('.edit-transfer-add-on').click(function () {
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_transfer_add_on_detail_by_tsmart_order_transfer_addon_id',
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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
                        plugin.update_orders_show_form_general_edit_transfer_addon_popup(response, type);
                        $(".form_edit_general_transfer_popup").dialog('open');

                    }
                });


            });
            $('.view_orders_edit_order_edit_excursion_add_on').find('.edit-excursion-add-on,.edit-general-excursion-add-on').click(function () {
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_excurstion_add_on_detail_by_tsmart_order_excurstion_addon_id',
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id,
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
                        plugin.update_orders_show_form_general_edit_excursion_addon_popup(response);
                        $(".form_edit_general_excursion_popup").dialog('open');

                    }
                });


            });
            $('.view_orders_edit_form_edit_room').find('button.cancel').click(function () {
                $(".order_edit_room").dialog('close');
            });
            $('.form_edit_general_excursion_popup').find('button.cancel').click(function () {
                $(".form_edit_general_excursion_popup").dialog('close');
                $('#terms_condition').remove();
            });
            $('.view_orders_edit_form_edit_room').find('button.save').click(function () {
                var $html_build_rooming_hotel_rooming_list = $('#html_build_rooming_hotel_rooming_list').data('html_build_rooming_list');
                if (!$html_build_rooming_hotel_rooming_list.validate()) {
                    return;
                }
                $html_build_rooming_hotel_rooming_list.get_data();
                var list_night_hotel = $html_build_rooming_hotel_rooming_list.settings.list_night_hotel;
                var list_passenger = $html_build_rooming_hotel_rooming_list.settings.list_passenger;
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_rooming',
                            tsmart_order_id: tsmart_order_id,
                            list_night_hotel: list_night_hotel,
                            list_passenger: list_passenger
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        //plugin.update_data_order();
                        $(".order_edit_room").dialog('close');
                        plugin.re_load_main_booking_info(tsmart_order_id);

                    }
                });


            });
            $('.view_edit_form_add_passenger_to_transfer_add_on').find('button.save').click(function () {
                var $list_transfer_passenger_item = $('.view_edit_form_add_passenger_to_transfer_add_on').find('.body-table-add-passenger-to-transfer .passenger-item');
                var list_transfer_cost_per_passenger = [];
                var total_cost_invail = true;
                var list_passenger = [];
                var duplicate_passenger = false;
                for (var i = 0; i < $list_transfer_passenger_item.length; i++) {
                    var $item_passenger_cost = $($list_transfer_passenger_item.get(i));
                    var $select_passenger_item = $item_passenger_cost.find('select.select-passenger-item');
                    var tsmart_passenger_id = $select_passenger_item.val();
                    var discount = $item_passenger_cost.find('input.discount').autoNumeric('get');

                    if (tsmart_passenger_id == 0) {
                        total_cost_invail = false;
                        alert('please select passenger');
                        $select_passenger_item.focus();
                        break;
                    } else {
                        var item = {};
                        item.tsmart_passenger_id = tsmart_passenger_id;
                        item.discount = discount;
                        var cost = plugin.get_cost_transfer_by_tsmart_passenger_id(tsmart_passenger_id);
                        var total_cost_per_persion = cost - discount;
                        item.cost = total_cost_per_persion;
                        list_transfer_cost_per_passenger.push(item);
                        if ($.inArray(tsmart_passenger_id, list_passenger) == -1) {
                            list_passenger.push(tsmart_passenger_id);
                        } else {
                            duplicate_passenger = true;
                            alert('duplicate passenger');
                            $select_passenger_item.focus();
                            break;
                        }
                    }
                }
                if (!total_cost_invail) {
                    return false;
                } else if (duplicate_passenger) {
                    return false;
                }
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost_to_transfer',
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            type: type,
                            tsmart_order_id: tsmart_order_id,
                            list_transfer_cost_per_passenger: list_transfer_cost_per_passenger,
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        //plugin.update_data_order();
                        $(".form_and_passenger_to_transfer_add_on").dialog('close');
                        plugin.load_transfer_add_on_by_tsmart_transfer_addon_id(tsmart_order_transfer_addon_id, type);

                    }
                });


            });
            $('.view_edit_form_add_passenger_to_excursion_add_on').find('button.cancel').click(function () {
                $(".form_add_and_passenger_to_excurson_add_on").dialog('close');
            });
            $('.view_edit_form_add_passenger_to_excursion_add_on').find('button.save').click(function () {
                var $list_transfer_passenger_item = $('.view_edit_form_add_passenger_to_excursion_add_on').find('.body-table-add-passenger-to-excursion .passenger-item');
                var list_excursion_cost_per_passenger = [];
                var total_cost_invail = true;
                var list_passenger = [];
                var duplicate_passenger = false;
                for (var i = 0; i < $list_transfer_passenger_item.length; i++) {
                    var $item_passenger_cost = $($list_transfer_passenger_item.get(i));
                    var $select_passenger_item = $item_passenger_cost.find('select.select-passenger-item');
                    var tsmart_passenger_id = $select_passenger_item.val();
                    var discount = $item_passenger_cost.find('input.discount').autoNumeric('get');
                    var surcharge = $item_passenger_cost.find('input.surcharge').autoNumeric('get');

                    if (tsmart_passenger_id == 0) {
                        total_cost_invail = false;
                        alert('please select passenger');
                        $select_passenger_item.focus();
                        break;
                    } else {
                        var item = {};
                        item.tsmart_passenger_id = tsmart_passenger_id;
                        item.discount = discount;
                        item.surcharge = surcharge;
                        var cost = plugin.get_cost_excursion_by_tsmart_passenger_id(tsmart_passenger_id);
                        var total_cost_per_persion = cost - discount;
                        item.cost = total_cost_per_persion;
                        list_excursion_cost_per_passenger.push(item);

                        if ($.inArray(tsmart_passenger_id, list_passenger) == -1) {
                            list_passenger.push(tsmart_passenger_id);
                        } else {
                            duplicate_passenger = true;
                            alert('duplicate passenger');
                            $select_passenger_item.focus();
                            break;
                        }
                    }
                }
                if (!total_cost_invail) {
                    return false;
                } else if (duplicate_passenger) {
                    return false;
                }
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost_to_excursion',
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id,
                            list_excursion_cost_per_passenger: list_excursion_cost_per_passenger,
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        //plugin.update_data_order();
                        $(".form_add_and_passenger_to_excurson_add_on").dialog('close');
                        plugin.load_excursion_add_on_by_tsmart_excusion_addon_id(tsmart_order_excursion_addon_id);

                    }
                });


            });
            $('.order_hotel_add_on_edit_rooming').find('button.cancel').click(function () {
                $('.order_hotel_add_on_edit_rooming').dialog('close');
            });
            $('.order_hotel_add_on_edit_rooming').find('button.save').click(function () {
                var $html_build_rooming_hotel_add_on_rooming_list = $('#html_build_rooming_hotel_rooming_list_hotel_add_on').data('html_build_rooming_list');
                if (!$html_build_rooming_hotel_add_on_rooming_list.validate()) {
                    return;
                }
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var tsmart_hotel_addon_id = $('.order_edit_night_hotel').find('input[name="tsmart_hotel_addon_id"]').val();
                var type = $('.order_edit_night_hotel').find('input[name="type"]').val();
                $html_build_rooming_hotel_add_on_rooming_list.get_data();
                var list_night_hotel = $html_build_rooming_hotel_add_on_rooming_list.settings.list_night_hotel;
                var list_passenger = $html_build_rooming_hotel_add_on_rooming_list.settings.list_passenger;
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_rooming_hotel_add_on',
                            tsmart_order_id: tsmart_order_id,
                            tsmart_hotel_addon_id: tsmart_hotel_addon_id,
                            type: type,
                            list_night_hotel: list_night_hotel,
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            list_passenger: list_passenger
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        //plugin.update_data_order();
                        $(".order_hotel_add_on_edit_rooming").dialog('close');
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id, type);

                    }
                });


            });
            $('.order_form_add_and_remove_room_edit_hotel_addon').find('button.save-room').click(function () {
                var $build_room_hotel_add_on = $('#html_build_room').data('build_room_hotel_add_on');
                var group_passengers = [];
                var list_room = $build_room_hotel_add_on.get_list_room();
                var list_passenger = $build_room_hotel_add_on.settings.list_passenger;

                var departure = $build_room_hotel_add_on.settings.departure;
                var hotel_addon_detail = departure.hotel_addon_detail;
                console.log(hotel_addon_detail);
                var tsmart_hotel_addon_id = hotel_addon_detail.tsmart_hotel_addon_id;
                var type = hotel_addon_detail.type;
                list_room.forEach(function (item_room) {
                    var room_type = item_room.list_room_type;
                    var passenger_room = {};
                    passenger_room.room_type = room_type;

                    passenger_room.passengers = [];
                    var tour_cost_and_room_price = item_room.tour_cost_and_room_price;
                    tour_cost_and_room_price.forEach(function (item_tour_cost_and_room_price) {
                        var passenger = {};
                        passenger.room_price = item_tour_cost_and_room_price.room_price;
                        var passenger_index = item_tour_cost_and_room_price.passenger_index;
                        passenger.tsmart_passenger_id = list_passenger[passenger_index].tsmart_passenger_id;
                        passenger_room.passengers.push(passenger);
                    });
                    group_passengers.push(passenger_room);
                });
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'save_passenger_room_in_hotel_add_on',
                            tsmart_order_id: tsmart_order_id,
                            tsmart_hotel_addon_id: tsmart_hotel_addon_id,
                            type: type,
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            group_passengers: group_passengers
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
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id, type);

                    }
                });

            });
            $('.view_orders_edit_form_edit_room').find('button.show-first-history').click(function () {
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'get_first_history_rooming',
                            tsmart_order_id: tsmart_order_id
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
                        var list_passenger = response.list_passenger;
                        plugin.fill_data_form_show_first_history_rooming(list_passenger);
                        $(".order_edit_form_show_first_history_rooming").dialog('open');

                    }
                });


            });

            $('.view_orders_edit_form_edit_room').find('button.show-near-last-one-history').click(function () {
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'get_near_last_history_rooming',
                            tsmart_order_id: tsmart_order_id
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
                        var list_passenger = response.list_passenger;
                        plugin.fill_data_form_show_near_last_history_rooming(list_passenger);
                        $(".order_edit_form_show_near_last_history_rooming").dialog('open');

                    }
                });


            });
            $('.view_orders_edit_form_edit_room').find('button.show-last-history').click(function () {
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'get_last_history_rooming',
                            tsmart_order_id: tsmart_order_id
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
                        var list_passenger = response.list_passenger;
                        plugin.fill_data_form_show_last_history_rooming(list_passenger);
                        $(".order_edit_form_show_last_history_rooming").dialog('open');

                    }
                });


            });

            $('.order_edit_form_show_first_history_rooming').find('button.cancel').click(function () {
                $(".order_edit_form_show_first_history_rooming").dialog('close');
            });
            $('.order_edit_form_show_near_last_history_rooming').find('button.cancel').click(function () {
                $(".order_edit_form_show_near_last_history_rooming").dialog('close');
            });
            $('.order_edit_form_show_last_history_rooming').find('button.cancel').click(function () {
                $(".order_edit_form_show_last_history_rooming").dialog('close');
            });
            $('.view_orders_edit_form_edit_room').find('a.delete').click(function () {
                if (!confirm('are you sure delete this room ?')) {
                    return;
                }
                var $tr = $(this).closest('tr.room_order_item');
                var tsmart_room_order_id = $tr.data('tsmart_room_order_id');
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_delete_rooming',
                            tsmart_order_id: tsmart_order_id,
                            tsmart_room_order_id: tsmart_room_order_id
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
                        if (response.error == 0) {
                            alert('delete successful');
                        }
                        $(".view_orders_edit_form_edit_room").find('tr.room_order_item[data-tsmart_room_order_id="' + tsmart_room_order_id + '"]').remove();
                        var list_passenger_not_in_temporary_and_not_in_room = response.list_passenger_not_in_temporary_and_not_in_room;
                        plugin.update_build_rooming_by_passenger(list_passenger_not_in_temporary_and_not_in_room);
                        //plugin.update_data_order();
                        //$(".order_edit_room").dialog('close');

                    }
                });


            });
            $element.find("#adminForm").validate();
            $element.find('.toolbar .cancel').click(function () {
                Joomla.submitform('cancel');
            });
            $element.find('.edit_form.main-tour').click(function () {
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                plugin.re_load_main_booking_info(tsmart_order_id);


            });
            var $row_passenger = $(".view_orders_edit_form_passenger_edit_hotel_add_on").find('table.orders_show_form_passenger_edit_hotel_addon tbody tr.passenger');
            plugin.settings.row_passenger_edit_hotel_addon = $row_passenger.getOuterHTML();
            $row_passenger.remove();

            var $row_passenger_excursion = $(".view_orders_edit_form_passenger_edit_excursion").find('table.orders_show_form_passenger_edit_excursion_addon tbody tr.passenger');
            plugin.settings.row_passenger_edit_excursion_addon = $row_passenger_excursion.getOuterHTML();
            $row_passenger_excursion.remove();
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('.total-cost-for-all,.total-cancel,.total-refund').autoNumeric('init', plugin.settings.config_show_price);

            var $row_passenger_edit_transfer_addon = $(".view_edit_form_passenger_edit_transfer_addon").find('table.orders_show_form_passenger_edit_transfer_addon tbody tr.passenger');
            plugin.settings.row_passenger_edit_transfer_addon = $row_passenger_edit_transfer_addon.getOuterHTML();
            $row_passenger_edit_transfer_addon.remove();
            $('.view_edit_form_edit_passenger_cost_transfer_add_on').find('.total-cost-for-all,.total-cancel,.total-refund').autoNumeric('init', plugin.settings.config_show_price);

            $element.find('.edit_form.night_hotel').click(function () {
                var tsmart_group_hotel_addon_order_id = $(this).data('object_id');
                var type = $(this).data('object_type');
                plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id, type);


            });
            $element.find('.edit_form.transfer').click(function () {
                var tsmart_order_transfer_addon_id = $(this).data('object_id');
                var type = $(this).data('object_type');
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                plugin.load_transfer_add_on_by_tsmart_transfer_addon_id(tsmart_order_transfer_addon_id, type);


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
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'book add on',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".form_add_and_passenger_to_excurson_add_on").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'add passenger to excursion',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".form_edit_general_excursion_popup").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'excursion edit genare info',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".form_edit_general_transfer_popup").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'edit general transfer info',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-voucher-center').click(function () {
                $(".form_order_voucher_center").dialog('open');
            });
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('button.save').click(function () {
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type = $(".order_edit_night_hotel").find('input[name="type"]').val();
                var terms_condition = $(".view_orders_edit_from_general_edit_hotel_addon_child").find('textarea[name="terms_condition"]').val();
                var reservation_notes = $(".view_orders_edit_from_general_edit_hotel_addon_child").find('textarea[name="reservation_notes"]').val();
                var checkin_date = $(".view_orders_edit_from_general_edit_hotel_addon_child").find('input[name="night_hotel_checkin_date"]').val();
                var checkout_date = $(".view_orders_edit_from_general_edit_hotel_addon_child").find('input[name="night_hotel_checkout_date"]').val();
                var list_assign_user_id_manager_hotel_add_on = $(".view_orders_edit_from_general_edit_hotel_addon_child").find('select[name="list_assign_user_id_manager_hotel_add_on"]').val();

                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();


                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_group_hotel_add_on',
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            checkin_date: checkin_date,
                            checkout_date: checkout_date,
                            terms_condition: terms_condition,
                            reservation_notes: reservation_notes,
                            list_assign_user_id_manager_hotel_add_on: list_assign_user_id_manager_hotel_add_on,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id, type);

                    }
                });

            });
            $('.form_edit_general_excursion_popup').find('button.save').click(function () {
                var $view_orders_edit_from_general_edit_excursion_addon_popup=$(".view_orders_edit_from_general_edit_excursion_addon_popup");
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                var terms_condition = $view_orders_edit_from_general_edit_excursion_addon_popup.find('textarea[name="excursion_terms_condition"]').val();
                var reservation_notes = $view_orders_edit_from_general_edit_excursion_addon_popup.find('textarea[name="excursion_reservation_notes"]').val();
                var checkin_date = $view_orders_edit_from_general_edit_excursion_addon_popup.find('input[name="excursion_checkin_date"]').val();
                var checkout_date = $view_orders_edit_from_general_edit_excursion_addon_popup.find('input[name="excursion_checkout_date"]').val();
                var list_assign_user_id_manager_excursion_add_on = $view_orders_edit_from_general_edit_excursion_addon_popup.find('select[name="list_assign_user_id_manager_excursion_add_on"]').val();

                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();


                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_group_excursion_in_for',
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            checkin_date: checkin_date,
                            checkout_date: checkout_date,
                            terms_condition: terms_condition,
                            reservation_notes: reservation_notes,
                            list_assign_user_id_manager_hotel_add_on: list_assign_user_id_manager_excursion_add_on,
                            tsmart_order_id: tsmart_order_id,
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
                        $(".form_edit_general_excursion_popup").dialog('close');
                        plugin.load_excursion_add_on_by_tsmart_excusion_addon_id(tsmart_order_excursion_addon_id);

                    }
                });

            });
            $('.view_orders_edit_from_general_edit_transfer_popup').find('button.cancel').click(function () {
                $(".form_edit_general_transfer_popup").dialog('close');
            });
            $('.order_edit_excursion').find('button.cancel').click(function () {
                $(".order_edit_excursion").dialog('close');
            });
            $('.view_orders_edit_from_general_edit_transfer_popup').find('button.save').click(function () {
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
                var terms_condition = $(".view_orders_edit_from_general_edit_transfer_popup").find('textarea[name="terms_condition"]').val();
                var reservation_notes = $(".view_orders_edit_from_general_edit_transfer_popup").find('textarea[name="reservation_notes"]').val();
                var checkin_date = $(".view_orders_edit_from_general_edit_transfer_popup").find('input[name="night_hotel_checkin_date"]').val();
                var list_assign_user_id_manager_hotel_add_on = $(".view_orders_edit_from_general_edit_transfer_popup").find('select[name="list_assign_user_id_manager_transfer_add_on"]').val();

                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();


                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_transfer_add_on',
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            checkin_date: checkin_date,
                            terms_condition: terms_condition,
                            reservation_notes: reservation_notes,
                            list_assign_user_id_manager_hotel_add_on: list_assign_user_id_manager_hotel_add_on,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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
                        $(".form_edit_general_transfer_popup").dialog('close');
                        plugin.load_transfer_add_on_by_tsmart_transfer_addon_id(tsmart_order_transfer_addon_id, type);

                    }
                });

            });
            $('.view_orders_edit_from_general_edit_hotel_addon_child').find('button.cancel').click(function () {
                $(".order_form_edit_night_hotel").dialog('close');

            });
            $element.find(".form_order_add_flight").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Add flight',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-add-flight').click(function () {
                $(".form_order_add_flight").dialog('open');
            });

            $element.find(".form_order_add_hoc_item").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Add flight',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_form_edit_night_hotel").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'edit genarate night hotel',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-add-hoc-item').click(function () {
                $(".form_order_add_hoc_item").dialog('open');
            });

            $element.find(".form_order_voucher_center").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Voucher center',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find('.list-control-activity .btn-book-add-on').click(function () {
                $(".order_book_add_on").dialog('open');
            });
            $element.find('.edit_form.excursion').click(function () {
                var tsmart_order_excursion_addon_id = $(this).data('object_id');
                plugin.load_excursion_add_on_by_tsmart_excusion_addon_id(tsmart_order_excursion_addon_id);


            });
            $element.find(".order_edit_excursion").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit excursion',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_transfer").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit transfer',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_night_hotel").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit night hotel',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_edit_main_tour").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 1000,
                autoOpen: false,
                title: 'Edit details',
                show: {effect: "blind", duration: 800},
                appendTo: 'body',
            });
            $element.find(".order_add_more_passenger").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Add more passenger',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });


            $('.order_edit_main_tour').find('.passenger-control .add-passenger').click(function () {
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_list_passenger_in_temporary_by_order_id',
                            tsmart_order_id: tsmart_order_id
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
                        var order_data = response.r.order_data;
                        plugin.settings.list_passenger_in_temporary_by_order_id = response.list_passenger_in_temporary_by_order_id;
                        plugin.config_view_add_more_passenger_main_tour();
                        $(".order_add_more_passenger").dialog('open');

                    }
                });


            });
            $element.find(".order_edit_passenger_cost").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Edit passenger cost',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $row_passenger_cost = $(".order_edit_passenger_cost_edit_hotel_add_on").find('table.edit_passenger_cost_hotel_addon_edit_passenger tbody tr.passenger');
            plugin.settings.row_passenger_cost_edit_hotel_addon = $row_passenger_cost.getOuterHTML();
            $row_passenger_cost.remove();

            var $row_passenger_cost_edit_excursion_addon = $(".view_orders_edit_form_edit_passenger_cost_edit_excursion_addon").find('table.edit_passenger_cost_excursion_addon_edit_passenger tbody tr.passenger');
            plugin.settings.row_passenger_cost_edit_excursion_addon = $row_passenger_cost_edit_excursion_addon.getOuterHTML();
            $row_passenger_cost_edit_excursion_addon.remove();


            var $row_transfer_passenger_cost = $(".view_edit_form_edit_passenger_cost_transfer_add_on").find('table.edit_passenger_cost_transfer_addon_edit_passenger tbody tr.passenger');
            plugin.settings.row_transfer_passenger_cost = $row_transfer_passenger_cost.getOuterHTML();
            $row_transfer_passenger_cost.remove();


            var $row_service_cost_excursion_add_on = $(".view_orders_edit_form_edit_service_cost_edit_excursion_add_on").find('table.service_cost_edit_excursion_add_on tbody tr.item-service-cost');
            plugin.settings.row_service_cost_excursion_add_on = $row_service_cost_excursion_add_on.getOuterHTML();
            $row_service_cost_excursion_add_on.remove();

            var $row_service_cost_transfer_add_on = $(".view_orders_edit_form_edit_service_cost_edit_transfer_add_on").find('table.service_cost_edit_transfer_add_on tbody tr.item-service-cost');
            plugin.settings.row_service_cost_transfer_add_on = $row_service_cost_transfer_add_on.getOuterHTML();
            $row_service_cost_transfer_add_on.remove();


            var $tr_service_cost_edit_hotel = $(".view_orders_edit_form_edit_service_cost_edit_hotel").find('table.service_cost_edit_hotel tbody tr.item-room');
            plugin.settings.row_service_cost_edit_hotel_addon = $tr_service_cost_edit_hotel.getOuterHTML();

            var $tr_rooming_edit_hotel = $(".view_orders_edit_order_bookinginfomation_hotel_addon").find('table.rooming-list tbody tr.item-rooming');
            plugin.settings.tr_rooming_edit_hotel = $tr_rooming_edit_hotel.getOuterHTML();

            var $tr_rooming_edit_hotel_add_on = $(".view_orders_edit_form_edit_room_in_hotel_add_on").find('table.rooming_hotel_add_on tbody tr.item-rooming');
            plugin.settings.tr_rooming_edit_hotel_add_on = $tr_rooming_edit_hotel_add_on.getOuterHTML();
            $tr_rooming_edit_hotel_add_on.remove();

            var $item_row_passenger_cost_in_transfer_add_on = $(".view_edit_form_add_passenger_to_transfer_add_on").find('.body-table-add-passenger-to-transfer .passenger-item');
            plugin.settings.item_row_passenger_cost_in_transfer_add_on = $item_row_passenger_cost_in_transfer_add_on.getOuterHTML();
            $item_row_passenger_cost_in_transfer_add_on.remove();

            var $item_row_passenger_cost_in_excursion_add_on = $(".view_edit_form_add_passenger_to_excursion_add_on").find('.body-table-add-passenger-to-excursion .passenger-item');
            plugin.settings.item_row_passenger_cost_in_excursion_add_on = $item_row_passenger_cost_in_excursion_add_on.getOuterHTML();
            $item_row_passenger_cost_in_excursion_add_on.remove();


            $element.find(".order_edit_passenger_cost_edit_hotel_add_on").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Edit passenger cost hotel addon',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });

            $element.find(".order_edit_form_show_first_history_rooming").dialog({
                dialogClass: 'asian-dialog-form',
                modal: false,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Show first history rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $first_history_rooming = $('.view_orders_edit_form_show_first_history_rooming').find('table.first_history_rooming tbody tr:first-child');
            plugin.settings.template_show_first_history_rooming = $first_history_rooming.getOuterHTML();
            $first_history_rooming.remove();
            $element.find(".order_edit_form_show_near_last_history_rooming").dialog({
                dialogClass: 'asian-dialog-form',
                modal: false,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Show near last history rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $near_last_history_rooming = $('.view_orders_edit_form_show_near_last_history_rooming').find('table.near_last_history_rooming tbody tr:first-child');
            plugin.settings.template_show_near_last_history_rooming = $near_last_history_rooming.getOuterHTML();
            $near_last_history_rooming.remove();


            $element.find(".order_edit_form_show_last_history_rooming").dialog({
                dialogClass: 'asian-dialog-form',
                modal: false,
                hide: 'explode',
                width: 900,
                autoOpen: false,
                title: 'Show last history rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            var $last_history_rooming = $('.view_orders_edit_form_show_last_history_rooming').find('table.last_history_rooming tbody tr:first-child');
            plugin.settings.template_show_last_history_rooming = $last_history_rooming.getOuterHTML();
            $last_history_rooming.remove();

            $('.order_edit_main_tour').find('.passenger-control .edit-booking-cost').click(function () {
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_main_tour_order_detail_by_order_id',
                            tsmart_order_id: tsmart_order_id
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
                        var order_data = response.r.order_data;
                        var list_row = response.list_row;
                        plugin.fill_data_passenger_cost(list_row);

                        $(".order_edit_passenger_cost").dialog('open');

                    }
                });


            });

            $('.order_edit_night_hotel').find('.passenger-control .edit-hotel-add-cost').click(function () {
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type = $(".order_edit_night_hotel").find('input[name="type"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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
                        var list_passenger = response.list_passenger;
                        var hotel_addon_detail = response.hotel_addon_detail;
                        plugin.fill_data_passenger_cost_edit_hotel_add_on(list_passenger, type, hotel_addon_detail);

                        $(".order_edit_passenger_cost_edit_hotel_add_on").dialog('open');

                    }
                });


            });

            $('.view_orders_edit_form_passenger_edit_excursion').find('.passenger-control .edit-passenger-cost').click(function () {
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_excurstion_add_on_detail_by_tsmart_order_excurstion_addon_id',
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id,
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
                        var list_passenger = response.list_passenger;
                        var excursion_addon = response.excursion_addon;
                        plugin.fill_data_passenger_cost_edit_excursion_add_on(list_passenger, excursion_addon);

                        $(".order_edit_passenger_cost_edit_excursion_add_on").dialog('open');

                    }
                });


            });
            $('.order_edit_night_hotel').find('.passenger-control .edit-hotel-add-room').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type = $('.order_edit_night_hotel').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_and_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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

                        plugin.reset_build_rooming_hotel_add_on_form(response, type);
                        $(".order_form_add_and_remove_room_edit_hotel_addon").dialog('open');

                    }
                });


            });

            $('.view_edit_form_passenger_edit_transfer_addon').find('.passenger-control .add-passenger-to-transfer').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                var type = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_transfer_add_on_detail_by_tsmart_order_transfer_addon_id',
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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

                        plugin.build_form_add_passenger_to_transfer(response, type);
                        $(".form_and_passenger_to_transfer_add_on").dialog('open');

                    }
                });


            });
            $('.view_orders_edit_form_passenger_edit_excursion').find('.passenger-control .edit-excursion-add-passenger').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_excurstion_add_on_detail_by_tsmart_order_excurstion_addon_id',
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id,
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

                        plugin.build_form_add_passenger_to_excursion(response);
                        $(".form_add_and_passenger_to_excurson_add_on").dialog('open');

                    }
                });


            });
            $('.view_edit_form_passenger_edit_transfer_addon').find('.passenger-control .edit-transfer-cost').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                var type = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_transfer_add_on_detail_by_tsmart_order_transfer_addon_id',
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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

                        plugin.fill_data_passenger_cost_edit_transfer_add_on(response, type);
                        $(".form_edit_passenger_cost_transfer_add_on").dialog('open');

                    }
                });


            });

            $element.find(".order_form_add_and_remove_room_edit_hotel_addon").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'add rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".form_and_passenger_to_transfer_add_on").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'add passenger to transfer',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".order_edit_room").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Edit rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".form_edit_passenger_cost_transfer_add_on").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Edit passenger cost transfer',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".order_edit_passenger_cost_edit_excursion_add_on").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Edit passenger cost excursion',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });

            $('.order_edit_main_tour').find('.room-control .edit-room').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_main_tour_order_detail_by_order_id',
                            tsmart_order_id: tsmart_order_id
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

                        $(".order_edit_room").dialog('open');
                        plugin.reset_build_rooming_main_tour_form(response);

                    }
                });


            });
            $('.view_orders_edit_order_bookinginfomation_hotel_addon').find('.room-control .edit-room').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                var type = $('.order_edit_night_hotel').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_order_detail_and_night_hotel_detail_and_list_passenger_in_temporary_and_passenger_not_joint_hotel_addon_by_hotel_addon_id',
                            tsmart_group_hotel_addon_order_id: tsmart_group_hotel_addon_order_id,
                            tsmart_order_id: tsmart_order_id,
                            type: type
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

                        plugin.reset_build_rooming_hotel_add_on_form2(response, type);
                        plugin.fill_data_rooming_hotel_add_on2(response.list_rooming, type);
                        plugin.fill_data_passenger_not_in_room_hotel_add_on(response.list_passenger_not_in_room, type);
                        $(".order_hotel_add_on_edit_rooming").dialog('open');

                    }
                });


            });
            $('.view_orders_edit_from_general_main_tour,.view_orders_edit_order_booking_information_itinerary').find('.edit-general-main-tour').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                var tsmart_order_main_tour_id = $('.view_orders_edit_order_edit_main_tour').find('input[name="tsmart_order_main_tour_id"]').val();

                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_main_tour_order_detail_by_order_id',
                            tsmart_order_id: tsmart_order_id,
                            tsmart_order_main_tour_id: tsmart_order_main_tour_id
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
                        plugin.update_form_general_main_tour_popup(response);
                        $(".edit_form_general_main_tour_popup").dialog('open');

                    }
                });


            });


            $element.find(".order_form_add_and_remove_passenger").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'add/edit passenger',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".edit_form_general_main_tour_popup").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'Edit main tour infomation',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $element.find(".order_hotel_add_on_edit_rooming").dialog({
                dialogClass: 'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'hotel add on edit rooming',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $(".view_orders_edit_passenger_manager").on("click", "tr.item-passenger a.edit", function () {
                var $tr = $(this).closest("tr.item-passenger");
                var tsmart_passenger_id = $tr.data("tsmart_passenger_id");
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_passenger_detail_by_passenger_id',
                            tsmart_passenger_id: tsmart_passenger_id
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

                        var passenger_data = response.passenger_data;
                        plugin.fill_data_passenger_detail(passenger_data);

                        $(".order_form_add_and_remove_passenger").dialog('open');

                    }
                });
            });
            $(".view_orders_edit_passenger_manager").on("click", "tr.item-passenger a.delete", function () {
                if (!confirm('are you sure delete this passenger')) {
                    return;
                }
                var $tr = $(this).closest("tr.item-passenger");
                var tsmart_passenger_id = $tr.data("tsmart_passenger_id");
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_delete_passenger_by_passenger_id',
                            tsmart_passenger_id: tsmart_passenger_id
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
                        if (response.error == 0) {
                            alert('delete passenger success');
                        }
                        $(".view_orders_edit_passenger_manager").find('tr.item-passenger[data-tsmart_passenger_id="' + tsmart_passenger_id + '"]').remove();

                    }
                });
            });
            $element.find('.passenger-manager-control .add_passenger').click(function () {
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_get_passenger_detail_by_passenger_id',
                            tsmart_passenger_id: 0
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
                        var passenger_data = response.passenger_data;
                        plugin.fill_data_passenger_detail(passenger_data);

                        $(".order_form_add_and_remove_passenger").dialog('open');

                    }
                });


            });
            $('.order_form_add_and_remove_room_edit_hotel_addon').find('button.cancel').click(function () {
                $(".order_form_add_and_remove_room_edit_hotel_addon").dialog('close');
            });
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('button.cancel').click(function () {
                $(".order_edit_passenger_cost_edit_excursion_add_on").dialog('close');
            });
            $('.view_orders_edit_order_edit_transfer_add_on').find('button.cancel').click(function () {
                $(".order_edit_transfer").dialog('close');
            });
            $('.view_edit_form_add_passenger_to_transfer_add_on').find('button.cancel').click(function () {
                $(".form_and_passenger_to_transfer_add_on").dialog('close');
            });
            $('.form_order_voucher_center').find('button.cancel').click(function () {
                $(".form_order_voucher_center").dialog('close');
            });
            $('.order_edit_passenger_cost_edit_hotel_add_on').find('button.cancel').click(function () {
                $(".order_edit_passenger_cost_edit_hotel_add_on").dialog('close');
            });
            $('.order_edit_passenger_cost_edit_hotel_add_on').find('button.save').click(function () {
                //save hotel add on cost passenger
                var $list_tr = $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon table.edit_passenger_cost_hotel_addon_edit_passenger').find('tbody tr.passenger');
                var list_row = [];
                for (var i = 0; i < $list_tr.length; i++) {
                    var $tr = $('.view_orders_edit_form_edit_passenger_cost_edit_hotel_addon table.edit_passenger_cost_hotel_addon_edit_passenger').find('tbody tr.passenger:eq(' + i + ')');
                    var item = {};
                    item.tsmart_passenger_id = $tr.find('input.tsmart_passenger_id').val();
                    var $discount = $tr.find('input.night_discount');

                    if ($discount.val().toLowerCase().trim() == 'n/a' || $discount.val().trim() == '') {
                        item.discount = null;
                    } else {
                        item.discount = $tr.find('input.night_discount').autoNumeric('get');
                    }
                    item.surcharge = $tr.find('input.night_surcharge').autoNumeric('get');
                    ;
                    var $cancel_fee = $tr.find('input.night_cancel_fee');
                    if ($cancel_fee.val().toLowerCase().trim() == 'n/a' || $cancel_fee.val().trim() == '') {
                        item.cancel_fee = null;
                    } else {
                        item.cancel_fee = $cancel_fee.autoNumeric('get');
                    }

                    var $payment = $tr.find('input.night_payment');
                    if ($payment.val().toLowerCase().trim() == 'n/a' || $payment.val().trim() == '') {
                        item.payment = null;
                    } else {
                        item.payment = $payment.autoNumeric('get');
                    }
                    list_row.push(item);
                }
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                var type = $('.order_edit_night_hotel').find('input[name="type"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost_hotel_add_on',
                            type: type,
                            list_row: list_row,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        $(".order_edit_passenger_cost_edit_hotel_add_on").dialog('close');
                        var tsmart_group_hotel_addon_order_id = $('.order_edit_night_hotel').find('input[name="tsmart_group_hotel_addon_order_id"]').val();
                        plugin.load_hotel_add_on_by_group_hotel_addon_order_id(tsmart_group_hotel_addon_order_id, type);


                    }
                });


            });

            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('button.cancel').click(function () {
                $(".order_edit_passenger_cost_edit_excursion_add_on").dialog('close');
            });
            $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon').find('button.save').click(function () {
                //save hotel add on cost passenger
                var $list_tr = $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon table.edit_passenger_cost_excursion_addon_edit_passenger').find('tbody tr.passenger');
                var list_row = [];
                for (var i = 0; i < $list_tr.length; i++) {
                    var $tr = $('.view_orders_edit_form_edit_passenger_cost_edit_excursion_addon table.edit_passenger_cost_excursion_addon_edit_passenger').find('tbody tr.passenger:eq(' + i + ')');
                    var item = {};

                    item.tsmart_passenger_id = $tr.find('input.tsmart_passenger_id').val();
                    var excursion_discount = $tr.find('input.excursion_discount').val().toLowerCase();
                    var excursion_surcharge = $tr.find('input.excursion_surcharge').val().toLowerCase();
                    if (excursion_discount == 'n/a' || excursion_discount == '') {
                        item.excursion_discount = null;
                    } else {
                        item.excursion_discount = $tr.find('input.excursion_discount').autoNumeric('get');
                    }
                    if (excursion_surcharge == 'n/a' || excursion_surcharge == '') {
                        item.excursion_surcharge = null;
                    } else {
                        item.excursion_surcharge = $tr.find('input.excursion_surcharge').autoNumeric('get');
                    }
                    var excursion_cancel_fee = $tr.find('input.excursion_cancel_fee').val().toLowerCase();
                    if (excursion_cancel_fee == 'n/a' || excursion_cancel_fee == '') {
                        item.excursion_cancel_fee = null;
                    } else {
                        item.excursion_cancel_fee = $tr.find('input.excursion_cancel_fee').autoNumeric('get');
                    }

                    var excursion_payment = $tr.find('input.excursion_payment').val().toLowerCase();
                    if (excursion_payment == 'n/a' || excursion_payment == '') {
                        item.excursion_payment = null;
                    } else {
                        item.excursion_payment = $tr.find('input.excursion_payment').autoNumeric('get');
                    }
                    list_row.push(item);
                }
                var tsmart_order_excursion_addon_id = $('.view_orders_edit_order_edit_excursion_add_on').find('input[name="tsmart_order_excursion_addon_id"]').val();

                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost_excursion_add_on',
                            list_row: list_row,
                            tsmart_order_excursion_addon_id: tsmart_order_excursion_addon_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        $(".order_edit_passenger_cost_edit_excursion_add_on").dialog('close');
                        plugin.load_excursion_add_on_by_tsmart_excusion_addon_id(tsmart_order_excursion_addon_id);


                    }
                });


            });
            $('.view_edit_form_edit_passenger_cost_transfer_add_on').find('button.cancel').click(function () {
                $(".form_edit_passenger_cost_transfer_add_on").dialog('close');
            });
            $('.view_edit_form_edit_passenger_cost_transfer_add_on').find('button.save').click(function () {
                //save hotel add on cost passenger
                var tsmart_order_transfer_addon_id = $('.view_orders_edit_order_edit_transfer_add_on').find('input[name="tsmart_order_transfer_addon_id"]').val();
                var type = $(".view_orders_edit_order_edit_transfer_add_on").find('input[name="type"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();


                var $list_tr = $('.view_edit_form_edit_passenger_cost_transfer_add_on table.edit_passenger_cost_transfer_addon_edit_passenger').find('tbody tr.passenger');
                var list_row = [];
                for (var i = 0; i < $list_tr.length; i++) {
                    var $tr = $('.view_edit_form_edit_passenger_cost_transfer_add_on table.edit_passenger_cost_transfer_addon_edit_passenger').find('tbody tr.passenger:eq(' + i + ')');
                    var item = {};
                    item.tsmart_passenger_id = $tr.find('input.tsmart_passenger_id').val();
                    var transfer_discount = $tr.find('input.transfer_discount').val().toLowerCase();
                    var transfer_surcharge = $tr.find('input.transfer_surcharge').val().toLowerCase();
                    if (transfer_discount == 'n/a' || transfer_discount == '') {
                        item.discount = null;
                    } else {
                        item.discount = $tr.find('input.transfer_discount').autoNumeric('get');
                    }
                    if (transfer_surcharge == 'n/a' || transfer_surcharge == '') {
                        item.discount = null;
                    } else {
                        item.surcharge = $tr.find('input.transfer_surcharge').autoNumeric('get');
                    }
                    var transfer_cancel_fee = $tr.find('input.transfer_cancel_fee').val().toLowerCase();
                    if (transfer_cancel_fee == 'n/a' || transfer_cancel_fee == '') {
                        item.cancel_fee = null;
                    } else {
                        item.cancel_fee = $tr.find('input.transfer_cancel_fee').autoNumeric('get');
                    }

                    var transfer_payment = $tr.find('input.transfer_payment').val().toLowerCase();
                    if (transfer_payment == 'n/a' || transfer_payment == '') {
                        item.payment = null;
                    } else {
                        item.payment = $tr.find('input.transfer_payment').autoNumeric('get');
                    }
                    list_row.push(item);
                }
                var tsmart_order_id = $element.find('input[name="tsmart_order_id"]').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'orders',
                            task: 'ajax_save_passenger_cost_transfer_add_on',
                            type: type,
                            list_row: list_row,
                            tsmart_order_transfer_addon_id: tsmart_order_transfer_addon_id,
                            tsmart_order_id: tsmart_order_id
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
                        if (response.e == 0) {
                            alert('save successful');
                        }
                        $(".form_edit_passenger_cost_transfer_add_on").dialog('close');
                        plugin.load_transfer_add_on_by_tsmart_transfer_addon_id(tsmart_order_transfer_addon_id, type);


                    }
                });


            });
            $('.form_order_add_hoc_item').find('button.cancel').click(function () {
                $(".form_order_add_hoc_item").dialog('close');
            });
            $('.form_order_add_flight').find('button.cancel').click(function () {
                $(".form_order_add_flight").dialog('close');
            });
            $('.order_form_add_and_remove_passenger').find('button.cancel').click(function () {
                $(".order_form_add_and_remove_passenger").dialog('close');
            });
            $('.order_book_add_on').find('button.cancel').click(function () {
                $(".order_book_add_on").dialog('close');
            });
            $('.order_form_add_and_remove_passenger').find('button.save').click(function () {
                var $view_orders_edit_form_add_and_remove_passenger = $(".view_orders_edit_form_add_and_remove_passenger");
                var tsmart_passenger_id = $view_orders_edit_form_add_and_remove_passenger.find('input[type="hidden"][name="tsmart_passenger_id"]').val();
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                if (!plugin.validate_input_passenger()) {
                    return;
                }
                var json_post = $view_orders_edit_form_add_and_remove_passenger.find(":input").serializeObject();
                json_post.tsmart_order_id = tsmart_order_id;
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
                            tsmart_passenger_id: tsmart_passenger_id,
                            json_post: json_post
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
                        var passenger_data = response.passenger_data;
                        plugin.fill_data_row_passenger_detail(passenger_data);
                        if (response.error == 0) {
                            alert('save data success');
                        }
                        $(".order_form_add_and_remove_passenger").dialog('close');

                    }
                });


            });
            var $wrapper_passenger = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-passenger");
            plugin.settings.passenger_template = $wrapper_passenger.find('.passenger-item').getOuterHTML();
            $wrapper_passenger.empty();
            var $wrapper_calculator = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-calculator");
            plugin.settings.passenger_calculator_template = $wrapper_calculator.find('.passenger-item-calculator').getOuterHTML();
            $wrapper_calculator.empty();
            plugin.settings.list_passenger_selected = [];
            $('.view_orders_edit_main_tour_form_add_more_passenger').find('select.number-passenger').change(function () {
                var total_passenger = $(this).val();
                var $wrapper_passenger = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-passenger");
                $wrapper_passenger.empty();

                for (var i = 0; i < total_passenger; i++) {
                    var item_passenger = {
                        tsmart_passenger_id: 0,
                        passenger_type: "",
                        tour_cost: 0,
                        passenger_discount: 0,
                        passenger_surcharge: 0,
                        passenger_bed_type: "private_bed",
                        passenger_extra_fee: 0
                    };
                    plugin.settings.list_passenger_selected.push(item_passenger);
                    var $passenger = $(plugin.settings.passenger_template);
                    var list_passenger_in_temporary_by_order_id = plugin.settings.list_passenger_in_temporary_by_order_id;
                    var $select_list_passenger = $passenger.find('.list-passenger');
                    $select_list_passenger.empty();
                    var $option = $('<option value="">please select passenger</option>');
                    $option.appendTo($select_list_passenger);
                    for (var j = 0; j < list_passenger_in_temporary_by_order_id.length; j++) {
                        var passenger = list_passenger_in_temporary_by_order_id[j];
                        var full_name = plugin.get_passenger_full_name(passenger);
                        var $option = $('<option value="' + passenger.tsmart_passenger_id + '">' + full_name + '</option>');
                        $option.appendTo($select_list_passenger);
                    }
                    $passenger.appendTo($wrapper_passenger);
                    $passenger.find('input.cost').autoNumeric('init', plugin.settings.config_show_price);
                    $passenger.find('select.list-passenger,discount.cost,.surcharge.cost,.extra-fee.cost').change(function () {
                        var $passenger_item = $(this).closest('.passenger-item');
                        var tsmart_passenger_id = $passenger_item.find('select.list-passenger').val();
                        var index_passenger = $passenger_item.index();
                        var discount = $passenger_item.find('input.discount').autoNumeric('get');
                        var surcharge = $passenger_item.find('input.surcharge').autoNumeric('get');
                        var extra_fee = $passenger_item.find('input.extra-fee').autoNumeric('get');

                        var departure = plugin.settings.departure;
                        var current_item_passenger = plugin.settings.list_passenger_selected[index_passenger];
                        var $passenger_item_calculator = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-calculator").find('.passenger-item-calculator:eq(' + index_passenger + ')');
                        var $tour_cost = $passenger_item_calculator.find('span.tour-cost');
                        if (tsmart_passenger_id != 0) {
                            var passenger = plugin.get_passenger_not_in_room_by_tsmart_passenger_id(tsmart_passenger_id);
                            var person_type = $.get_title_passenger(passenger.date_of_birth);
                            var tour_cost = 0;
                            switch (person_type) {
                                case "children1":
                                    tour_cost = departure.sale_price_children1;
                                    break;
                                case "children2":
                                    tour_cost = departure.sale_price_children2;
                                    break;
                                case "teen":
                                    tour_cost = departure.sale_price_teen;
                                    break;
                                case "adult":
                                    tour_cost = departure.sale_price_adult;
                                    break;
                                case "senior":
                                    tour_cost = departure.sale_price_senior;
                                    break;
                            }
                            $tour_cost.autoNumeric('set', tour_cost);
                            current_item_passenger.tour_cost = tour_cost;
                            if (extra_fee != "") {
                                $passenger_item_calculator.find('span.extra-fee').autoNumeric('set', extra_fee);
                                current_item_passenger.passenger_extra_fee = extra_fee;
                            } else {
                                $passenger_item_calculator.find('span.extra-fee').html("N/A");
                                current_item_passenger.passenger_extra_fee = 0;
                            }
                            if (discount != "") {
                                $passenger_item_calculator.find('span.discount').autoNumeric('set', discount);
                                current_item_passenger.passenger_discount = discount;
                            } else {
                                $passenger_item_calculator.find('span.discount').html("N/A");
                                current_item_passenger.passenger_discount = 0;
                            }
                            if (surcharge != "") {
                                $passenger_item_calculator.find('span.surcharge').autoNumeric('set', surcharge);
                                current_item_passenger.passenger_surcharge = surcharge;
                            } else {
                                $passenger_item_calculator.find('span.surcharge').html("N/A");
                                current_item_passenger.passenger_surcharge = 0;
                            }
                            if (extra_fee != '' && discount != "") {
                                var total = parseFloat(tour_cost) + parseFloat(extra_fee) - parseFloat(discount);
                                $passenger_item_calculator.find('span.cost.passenger-total-cost').autoNumeric('set', total);
                            } else {
                                $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                            }


                        } else {
                            $passenger_item_calculator.find('span.person-name').html("N/A");
                            $tour_cost.html("N/A");
                            $passenger_item_calculator.find('span.extra-fee').html("N/A");
                            $passenger_item_calculator.find('span.surcharge').html("N/A");
                            $passenger_item_calculator.find('span.discount').html("N/A");
                            $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                        }
                        plugin.settings.list_passenger_selected[index_passenger] = current_item_passenger;
                        plugin.update_total_cost_form_add_passenger_to_room();
                    });

                }

                var $wrapper_calculator = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-calculator");
                $wrapper_calculator.empty();
                for (var i = 0; i < total_passenger; i++) {
                    var $passenger_calculator = $(plugin.settings.passenger_calculator_template);
                    $passenger_calculator.appendTo($wrapper_calculator);
                    $passenger_calculator.find('span.cost').autoNumeric('init', plugin.settings.config_show_price);
                }

                plugin.update_total_cost_form_add_passenger_to_room();


            });
            $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-passenger").find('input.cost').autoNumeric('init', plugin.settings.config_show_price);
            $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-passenger").on("change", "select.list-passenger", function () {
                var $passenger_item = $(this).closest('.passenger-item');
                var tsmart_passenger_id = $(this).val();
                var index_passenger = $passenger_item.index();
                var current_item_passenger = plugin.settings.list_passenger_selected[index_passenger];
                var passenger = plugin.get_passenger_not_in_room_by_tsmart_passenger_id(tsmart_passenger_id);
                current_item_passenger.tsmart_passenger_id = tsmart_passenger_id;
                current_item_passenger.passenger_type = tsmart_passenger_id != 0 ? $.get_title_passenger(passenger.date_of_birth) : "N/A";
                plugin.settings.list_passenger_selected[index_passenger] = current_item_passenger;
                $passenger_item.find('input.passenger-type').val(current_item_passenger.passenger_type);

                var discount = $passenger_item.find('input.discount').autoNumeric('get');
                var extra_fee = $passenger_item.find('input.extra-fee').autoNumeric('get');
                var departure = plugin.settings.departure;

                var $passenger_item_calculator = $(".view_orders_edit_main_tour_form_add_more_passenger .wrapper-calculator").find('.passenger-item-calculator:eq(' + index_passenger + ')');
                var $tour_cost = $passenger_item_calculator.find('span.tour-cost');
                if (tsmart_passenger_id != 0) {
                    var passenger = plugin.get_passenger_not_in_room_by_tsmart_passenger_id(tsmart_passenger_id);
                    $passenger_item_calculator.find('span.person-name').html(plugin.get_passenger_full_name(passenger));
                    var person_type = $.get_title_passenger(passenger.date_of_birth);
                    var tour_cost = 0;
                    switch (person_type) {
                        case "children1":
                            tour_cost = departure.sale_price_children1;
                            break;
                        case "children2":
                            tour_cost = departure.sale_price_children2;
                            break;
                        case "teen":
                            tour_cost = departure.sale_price_teen;
                            break;
                        case "adult":
                            tour_cost = departure.sale_price_adult;
                            break;
                        case "senior":
                            tour_cost = departure.sale_price_senior;
                            break;
                    }
                    current_item_passenger.tour_cost = tour_cost;
                    $tour_cost.autoNumeric('set', tour_cost);
                    if (extra_fee != "") {
                        $passenger_item_calculator.find('span.extra-fee').autoNumeric('set', extra_fee);
                        current_item_passenger.passenger_extra_fee = extra_fee;
                    } else {
                        $passenger_item_calculator.find('span.extra-fee').html("N/A");
                        current_item_passenger.passenger_extra_fee = 0;
                    }
                    if (discount != "") {
                        $passenger_item_calculator.find('span.discount').autoNumeric('set', discount);
                        current_item_passenger.passenger_discount = discount;
                    } else {
                        $passenger_item_calculator.find('span.discount').html("N/A");
                        current_item_passenger.passenger_discount = 0;
                    }
                    if (extra_fee != '' && discount != "") {
                        var total = parseFloat(tour_cost) + parseFloat(extra_fee) - parseFloat(discount);
                        $passenger_item_calculator.find('span.cost.passenger-total-cost').autoNumeric('set', total);
                    } else {
                        $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                    }

                } else {
                    $passenger_item_calculator.find('span.person-name').html("N/A");
                    $tour_cost.html("N/A");
                    $passenger_item_calculator.find('span.extra-fee').html("N/A");
                    $passenger_item_calculator.find('span.discount').html("N/A");
                    $passenger_item_calculator.find('span.cost.passenger-total-cost').html("N/A");
                }
                plugin.settings.list_passenger_selected[index_passenger] = current_item_passenger;
                plugin.update_total_cost_form_add_passenger_to_room();

            });
            $('.view_orders_edit_main_tour_form_add_more_passenger').find('button.save').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                if (!plugin.validate_add_passenger_to_room()) {
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
                            list_row: plugin.settings.list_passenger_selected
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
                        var order_data = response.r;
                        if (response.error == 0) {
                            alert('save data success');
                        }
                        $(".order_add_more_passenger").dialog('close');

                    }
                });
            });
            $('.order_edit_night_hotel').find('button.save').click(function () {
                var tsmart_order_id = $element.find('input[type="hidden"][name="tsmart_order_id"]').val();
                if (!plugin.validate_add_passenger_to_room()) {
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
                            list_row: plugin.settings.list_passenger_selected
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
                        var order_data = response.r;
                        if (response.error == 0) {
                            alert('save data success');
                        }
                        $(".order_add_more_passenger").dialog('close');

                    }
                });
            });
            var $view_orders_edit_form_add_and_remove_passenger = $('.view_orders_edit_form_add_and_remove_passenger');
            $view_orders_edit_form_add_and_remove_passenger.find('button.auto-fill').click(function () {
                var $list_input = $view_orders_edit_form_add_and_remove_passenger.find('input[required="required"]');
                for (var i = 0; i < $list_input.length; i++) {
                    var $current_input = $($list_input.get(i));
                    $current_input.delorean({type: 'words', amount: 5, character: 'Doc', tag: ''});
                }
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="email_address"],input[name="confirm_email"]').val("test@gmail.com");
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="first_name"]').delorean({
                    type: 'words',
                    amount: 1,
                    character: 'Doc',
                    tag: ''
                });
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="middle_name"]').delorean({
                    type: 'words',
                    amount: 1,
                    character: 'Doc',
                    tag: ''
                });
                $view_orders_edit_form_add_and_remove_passenger.find('input[name="last_name"]').delorean({
                    type: 'words',
                    amount: 1,
                    character: 'Doc',
                    tag: ''
                });
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


