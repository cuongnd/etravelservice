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
                $(".order_edit_main_tour").dialog('open');
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


