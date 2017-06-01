(function ($) {

    // here we go!
    $.view_orders_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            task:'',
            config_show_price: {
                mDec: 1,
                aSep: ' ',
                aSign: 'US$'
            },
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
            if(total_cost<0){
                total_cost=0;
            }
            $total_cost.autoNumeric('set',total_cost);
            var $balance=$tr.find('input.balance');
            var balance=parseFloat(total_cost)-parseFloat(payment);
            if(balance<0){
                balance=0;
            }
            $balance.autoNumeric('set',balance);
            var $refund=$tr.find('input.refund');
            var refund=parseFloat(payment)-parseFloat(cancel_fee);
            if(refund<0){
                refund=0;
            }
            $refund.autoNumeric('set',refund);
        };
        plugin.fill_data_passenger_cost = function (list_row) {
            for(var i=0;i<list_row.length;i++){
                var row=list_row[i];
                var $tr= $('table.edit_passenger_cost').find('tbody tr.passenger:eq('+i+')');
                $tr.find('input.tour_fee').autoNumeric('set',parseFloat(row.tour_fee));
                $tr.find('input.single_room_fee').autoNumeric('set',parseFloat(row.single_room_fee));
                $tr.find('input.extra_fee').autoNumeric('set',parseFloat(row.extra_fee));
                $tr.find('input.discount_fee').autoNumeric('set',parseFloat(row.discount_fee));
                $tr.find('input.payment').autoNumeric('set',parseFloat(row.payment));
                $tr.find('input.cancel_fee').autoNumeric('set',parseFloat(row.cancel_fee));
                plugin.calculator_price($tr);
            }

        };
        plugin.calculator_price_show = function ($tr) {
            var total_cost=$tr.find('span.total_cost').autoNumeric('get');
            var payment=$tr.find('span.payment').autoNumeric('get');
            var cancel_fee=$tr.find('span.cancel_fee').autoNumeric('get');
            var $balance=$tr.find('span.balance');
            var balance=parseFloat(total_cost)-parseFloat(payment);
            if(balance<0){
                balance=0;
            }
            $balance.autoNumeric('set',balance);
            var $refund=$tr.find('span.refund');
            var refund=parseFloat(payment)-parseFloat(cancel_fee);
            if(refund<0){
                refund=0;
            }
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
            }
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
        plugin.fill_data_passenger_detail = function (passenger_data) {

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
                    item.tour_fee=$tr.find('input.tour_fee').autoNumeric('get');
                    item.single_room_fee=$tr.find('input.single_room_fee').autoNumeric('get');
                    item.extra_fee=$tr.find('input.extra_fee').autoNumeric('get');
                    item.discount_fee=$tr.find('input.discount_fee').autoNumeric('get');
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

            $('.view_orders_edit_order_edit_main_tour').find('button.save').click(function(){
                var $list_tr= $('table.orders_show_form_passenger').find('tbody tr.passenger');
                var list_row=[];
                for(var i=0;i<$list_tr.length;i++){
                    var $tr= $('table.orders_show_form_passenger').find('tbody tr.passenger:eq('+i+')');
                    var item={};
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
                var itinerary_content=tinymce.get("itinerary").getContent();

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
            $('.view_orders_edit_form_edit_room').find('button.cancel').click(function(){
                $(".order_edit_room").dialog('close');
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
                $(".order_edit_passenger").dialog('open');
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


            $element.find(".order_form_add_and_remove_passenger").dialog({
                dialogClass:'asian-dialog-form',
                modal: true,
                width: 900,
                autoOpen: false,
                title: 'add/edit passenger',
                show: {effect: "blind", duration: 800},
                appendTo: 'body'
            });
            $('.view_orders_edit_passenger_manager').find("tr.item-passenger a.edit").click(function(){
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
                            tsmart_order_id:tsmart_passenger_id
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
            $element.find('.passenger-manager-control .add_passenger').click(function(){
                $(".order_form_add_and_remove_passenger").dialog('open');

            });
            $('.order_form_add_and_remove_passenger').find('button.cancel').click(function(){
                $(".order_form_add_and_remove_passenger").dialog('close');
            });


        }

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


