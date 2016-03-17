(function ($) {

    // here we go!
    $.html_edit_price_hotel_add_on = function (element, options) {

        // plugin's default options
        var defaults = {
            debug:true,
            data:{
                item_mark_up_type:'',
                items:{
                    single_room:{
                        mark_up_type:'',
                        net_price:0,
                        mark_up_percent:0,
                        mark_up_amount:0,
                        tax:0,
                        sale_price:0
                    },
                    double_twin_room:{
                        mark_up_type:'',
                        net_price:0,
                        mark_up_percent:0,
                        mark_up_amount:0,
                        tax:0,
                        sale_price:0
                    },
                    triple_room:{
                        mark_up_type:'',
                        net_price:0,
                        mark_up_percent:0,
                        mark_up_amount:0,
                        tax:0,
                        sale_price:0
                    }
                }
            },
            output_name:''
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.change_price = function (self) {
            var data=plugin.settings.data;
            var $item=self.closest('.item');
            var net_price=$item.find('.net-price').autoNumeric('get');
            net_price=parseFloat(net_price.length>0?net_price:0);

            var mark_up_percent=$item.find('.mark-up-percent').autoNumeric('get');
            mark_up_percent=parseFloat(mark_up_percent.length>0?mark_up_percent:0);

            var mark_up_amount=$item.find('.mark-up-amount').autoNumeric('get');
            mark_up_amount=parseFloat(mark_up_amount.length>0?mark_up_amount:0);

            var tax=$item.find('.tax').autoNumeric('get');
            var tax=parseFloat(tax.length>0?tax:0);
            if(debug) {
                console.log('net_price');
                console.log(net_price);
                console.log('mark_up_percent');
                console.log(mark_up_percent);
                console.log('mark_up_amount');
                console.log(mark_up_amount);
                console.log('tax');
                console.log(tax);
            }
            var sale_price=net_price+net_price*(mark_up_percent/100)+mark_up_amount+net_price*(tax/100);
            var $sale_price=$item.find('.sale-price');
            $sale_price.autoNumeric('set', sale_price);
            if(self.hasClass('mark-up-percent'))
            {
                if(mark_up_percent>0)
                {
                    data.item_mark_up_type="percent";
                    $element.find('.item .mark-up-amount').prop('disabled', true);
                }else{
                    data.item_mark_up_type="";
                    $element.find('.item .mark-up-amount').prop('disabled', false);
                }
            }
            if(self.hasClass('mark-up-amount'))
            {
                if(mark_up_amount>0)
                {
                    data.item_mark_up_type="amount";
                    $element.find('.item .mark-up-percent').prop('disabled', true);
                }else{
                    data.item_mark_up_type="";
                    $element.find('.item .mark-up-percent').prop('disabled', false);
                }
            }
            plugin.update_data();
        };
        plugin.update_data = function () {
            var output_name=plugin.settings.output_name;
            var data=plugin.settings.data;
            var $output=$('input[name="'+output_name+'"]');
            var output_data={};
            output_data.item_mark_up_type=data.item_mark_up_type;
            output_data.items={};
            $element.find('.item').each(function(){
                var $item=$(this);
                var key=$item.attr('data-key-room');
                output_data.items[key]={};
                output_data.items[key].net_price=$item.find('.net-price').autoNumeric('get');
                output_data.items[key].mark_up_percent=$item.find('.mark-up-percent').autoNumeric('get');
                output_data.items[key].mark_up_amount=$item.find('.mark-up-amount').autoNumeric('get');
                output_data.items[key].tax=$item.find('.tax').autoNumeric('get');
                output_data.items[key].sale_price=$item.find('.sale-price').autoNumeric('get');
            });
            output_data = base64.encode(cassandraMAP.stringify(output_data));
            $output.val(output_data);
        };
        plugin.reset_all = function () {
            var $items=$element.find('.item');
            plugin.update_data();
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            debug=plugin.settings.debug;
            $element.find('.item input').change(function(){
                plugin.change_price($(this));
            });
            $element.find('.auto').autoNumeric('init');
            $element.find('.reset-all').click(function(){
                plugin.reset_all();
            });
            $element.find('.item input').change(function(){
                plugin.change_price($(this));
                plugin.update_data();
            });
            plugin.fill_data();
        };
        plugin.fill_data=function(){
            var data=plugin.settings.data;
            var items=data.items;
            var total_item=items.length;
            $.each(items,function(key,item){
                var $item= $element.find('.item[data-key-room="'+key+'"]');
                $item.find('.net-price').autoNumeric('set',item.net_price);
                $item.find('.mark-up-percent').autoNumeric('set',item.mark_up_percent);
                $item.find('.mark-up-amount').autoNumeric('set',item.mark_up_amount);
                $item.find('.tax').autoNumeric('set',item.tax);
                $item.find('.sale-price').autoNumeric('set',item.sale_price);

            });

            var item_mark_up_type=data.item_mark_up_type;
            if(item_mark_up_type=='percent')
            {
                $element.find('.item mark-up-amount').prop('disabled', true);
            }else if(item_mark_up_type=='amount')
            {
                $element.find('.item mark-up-percent').prop('disabled', true);
            }

        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_edit_price_hotel_add_on = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_edit_price_hotel_add_on')) {
                var plugin = new $.html_edit_price_hotel_add_on(this, options);
                $(this).data('html_edit_price_hotel_add_on', plugin);

            }

        });

    }

})(jQuery);


