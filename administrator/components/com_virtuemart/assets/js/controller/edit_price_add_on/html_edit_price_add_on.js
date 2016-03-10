(function ($) {

    // here we go!
    $.html_edit_price_add_on = function (element, options) {

        // plugin's default options
        var defaults = {
            debug:true,
            data:{
                children_discount_type:'',
                price_type:'',
                item_mark_up_type:'',
                item_flat_mark_up_type:'',
                children_discount_amount:0,
                children_discount_percent:0,
                children_under_year:0,
                items:[
                    {
                        mark_up_type:'',
                        net_price:0,
                        mark_up_percent:0,
                        mark_up_amount:0,
                        tax:0,
                        sale_price:0
                    }
                ],
                item_flat:{
                    mark_up_type:'',
                    net_price:0,
                    mark_up_percent:0,
                    mark_up_amount:0,
                    tax:0,
                    sale_price:0
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
            var current_value=self.autoNumeric('get');
            current_value=parseFloat(current_value.length>0?current_value:0);
            if(current_value>0)
            {
                $element.find('.item-flat input').prop('disabled', true);
                data.price_type="item";
            }else{
                var disable_flat_price=false;
                $element.find('.item input').each(function(){
                    var check_value=$(this).autoNumeric('get');
                    check_value=parseFloat(check_value.length>0?check_value:0);
                    if(check_value>0)
                    {
                        disable_flat_price=true;
                    }
                });
                if(disable_flat_price)
                {
                    $element.find('.item-flat input').prop('disabled', true);
                    data.price_type="item";
                }else{
                    $element.find('.item-flat input').prop('disabled', false);
                    data.price_type="";
                }
            }
            plugin.update_data();
        };
        plugin.change_flat_price = function (self) {
            var data=plugin.settings.data;
            var $item=self.closest('.item-flat');
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
                    data.item_flat_mark_up_type="percent";
                    $element.find('.item-flat .mark-up-amount').prop('disabled', true);
                }else{
                    data.item_flat_mark_up_type="";
                    $element.find('.item-flat .mark-up-amount').prop('disabled', false);
                }
            }
            if(self.hasClass('mark-up-amount'))
            {
                if(mark_up_amount>0)
                {
                    data.item_flat_mark_up_type="amount";
                    $element.find('.item-flat .mark-up-percent').prop('disabled', true);
                }else{
                    data.item_flat_mark_up_type="";
                    $element.find('.item-flat .mark-up-percent').prop('disabled', false);
                }
            }
            var current_value=self.autoNumeric('get');
            current_value=parseFloat(current_value.length>0?current_value:0);
            if(current_value>0)
            {

                $element.find('.item input').prop('disabled', true);
                data.price_type="item_flat";
            }else{
                var disable_price=false;
                $element.find('.item-flat input').each(function(){
                    var check_value=$(this).autoNumeric('get');
                    check_value=parseFloat(check_value.length>0?check_value:0);
                    if(check_value>0)
                    {
                        disable_price=true;
                    }
                });

                if(disable_price)
                {
                    $element.find('.item input').prop('disabled', true);
                    data.price_type="item_flat";
                }else{
                    $element.find('.item input').prop('disabled', false);
                    data.price_type="";
                }
            }
            plugin.update_data();
        };
        plugin.add_more_item = function () {
            var $last_item=$element.find('.item:last');
            $last_item.clone().insertAfter($last_item);
            $last_item=$element.find('.item:last');
            $last_item.find('.auto').autoNumeric('init');
            $element.find('.item').each(function(index){
                $(this).find('.index').html(index+1);
            });
            $last_item.find('input').change(function(){
                plugin.change_price($(this));
            });
        };
        plugin.update_data = function () {
            var output_name=plugin.settings.output_name;
            var data=plugin.settings.data;
            var $output=$('input[name="'+output_name+'"]');
            var output_data={};
            output_data.children_discount_type=data.children_discount_type;
            output_data.price_type=data.price_type;
            output_data.item_mark_up_type=data.item_mark_up_type;
            output_data.item_flat_mark_up_type=data.item_flat_mark_up_type;
            output_data.children_discount_amount=$element.find('.children-discount-amount').autoNumeric('get');
            output_data.children_discount_percent=$element.find('.children-discount-percent').autoNumeric('get');
            output_data.children_under_year=$element.find('.children-under-year').autoNumeric('get');
            output_data.items=[];
            $element.find('.item').each(function(){
                var $item=$(this);
                var item={};
                item.net_price=$item.find('.net-price').autoNumeric('get');
                item.mark_up_percent=$item.find('.mark-up-percent').autoNumeric('get');
                item.mark_up_amount=$item.find('.mark-up-amount').autoNumeric('get');
                item.tax=$item.find('.tax').autoNumeric('get');
                item.sale_price=$item.find('.sale-price').autoNumeric('get');
                output_data.items.push(item);
            });
            output_data.item_flat={};
            output_data.item_flat.net_price=$element.find('.item-flat .net-price').autoNumeric('get');
            output_data.item_flat.mark_up_percent=$element.find('.item-flat .mark-up-percent').autoNumeric('get');
            output_data.item_flat.mark_up_amount=$element.find('.item-flat .mark-up-amount').autoNumeric('get');
            output_data.item_flat.tax=$element.find('.item-flat .tax').autoNumeric('get');
            output_data.item_flat.sale_price=$element.find('.item-flat .sale-price').autoNumeric('get');

            output_data = base64.encode(cassandraMAP.stringify(output_data));

            $output.val(output_data);
        };
        plugin.remove_item = function () {
            var $items=$element.find('.item');
            if($items.length==1)
            {
                return false;
            }
            var $last_item=$element.find('.item:last');
            $last_item.remove();
            plugin.update_data();
        };
        plugin.reset_all = function () {
            var $items=$element.find('.item');
            if($items.length==1)
            {
                return false;
            }
            for(var i=0;i<$items.length;i++)
            {
                if(i>0)
                {
                    $element.find('.item:last').remove();
                }
            }
            plugin.update_data();
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            debug=plugin.settings.debug;


            $element.find('.item').each(function(){

            });
            $element.find('.item input').change(function(){
                plugin.change_price($(this));
            });
            $element.find('.item-flat input').change(function(){
                plugin.change_flat_price($(this));
            });
            $element.find('.children-under-year').change(function(){
                plugin.update_data();
            });
            $element.find('.auto').autoNumeric('init');
            $element.find('.more-group').click(function(){
                plugin.add_more_item();
                plugin.update_data();
            });
            $element.find('.delete-group').click(function(){
                plugin.remove_item();
            });
            $element.find('.reset-all').click(function(){
                plugin.reset_all();
            });
            $element.find('.item input').change(function(){
                plugin.change_price($(this));
                plugin.update_data();
            });

            $element.find('.children-discount-amount').change(function(){
                var value=$(this).autoNumeric('get');
                value=parseFloat(value.length>0?value:0);
                if(value>0)
                {
                    plugin.settings.data.children_discount_type='amount';

                    $element.find('.children-discount-percent').prop('disabled', true);
                }else{
                    plugin.settings.data.children_discount_amount=value;
                    $element.find('.children-discount-percent').prop('disabled', false);

                }
                plugin.settings.data.children_discount_amount=value;
                plugin.update_data();
             });
            $element.find('.children-discount-percent').change(function(){
                var value=$(this).autoNumeric('get');
                value=parseFloat(value.length>0?value:0);
                if(value>0)
                {
                    plugin.settings.data.children_discount_type='percnet';
                    $element.find('.children-discount-amount').prop('disabled', true);
                }else{
                    plugin.settings.data.children_discount_type='';
                    $element.find('.children-discount-amount').prop('disabled', false);

                }
                plugin.settings.data.children_discount_percent=value;
                plugin.update_data();
             });
            plugin.fill_data();
        };
        plugin.fill_data=function(){
            var data=plugin.settings.data;
            var items=data.items;
            var item_flat=data.item_flat;
            var children_discount_amount=data.children_discount_amount;
            var children_discount_percent=data.children_discount_percent;
            var total_item=items.length;
            total_item=total_item>20?20:total_item;
            var children_under_year=data.children_under_year;
            if(typeof children_under_year!=="undefined")
            {
                children_under_year=parseInt(children_under_year.length>0?children_under_year:0);
            }else
            {
                children_under_year=0;
            }
            $element.find('.children-under-year').autoNumeric('set', children_under_year);
            for(var i=0;i<total_item;i++)
            {
                var item=items[i];
                if(i>0)
                {
                    plugin.add_more_item();
                }
                var $item= $element.find('.item:eq('+i+')');
                $item.find('.net-price').autoNumeric('set',item.net_price);
                $item.find('.mark-up-percent').autoNumeric('set',item.mark_up_percent);
                $item.find('.mark-up-amount').autoNumeric('set',item.mark_up_amount);
                $item.find('.tax').autoNumeric('set',item.tax);
                $item.find('.sale-price').autoNumeric('set',item.sale_price);
            }

            $element.find('.item-flat .net-price').autoNumeric('set',item_flat.net_price);
            $element.find('.item-flat .mark-up-percent').autoNumeric('set',item_flat.mark_up_percent);
            $element.find('.item-flat .mark-up-amount').autoNumeric('set',item_flat.mark_up_amount);
            $element.find('.item-flat .tax').autoNumeric('set',item_flat.tax);
            $element.find('.item-flat .sale-price').autoNumeric('set',item_flat.sale_price);
            var children_discount_type=data.children_discount_type;
            if(children_discount_type=='amount')
            {
                $element.find('.children-discount-amount').autoNumeric('set', children_discount_amount);
                $element.find('.children-discount-percent').prop('disabled', true);
            }else if(children_discount_type=='percent')
            {
                $element.find('.children-discount-percent').autoNumeric('set', children_discount_percent);
                $element.find('.children-discount-amount').prop('disabled', true);
            }

            var price_type=data.price_type;
            if(price_type=='item')
            {
                $element.find('.item-flat input').prop('disabled', true);

                var item_mark_up_type=data.item_mark_up_type;
                if(item_mark_up_type=='percent')
                {
                    $element.find('.item mark-up-amount').prop('disabled', true);
                }else if(price_type=='amount')
                {
                    $element.find('.item mark-up-percent').prop('disabled', true);
                }



            }else if(price_type=='item_flat')
            {
                $element.find('.item input').prop('disabled', true);
                var item_flat_mark_up_type=data.item_flat_mark_up_type;
                if(item_flat_mark_up_type=='percent')
                {
                    $element.find('.item-flat mark-up-amount').prop('disabled', true);
                }else if(item_flat_mark_up_type=='amount')
                {
                    $element.find('.item-flat mark-up-percent').prop('disabled', true);
                }

            }


        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_edit_price_add_on = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_edit_price_add_on')) {
                var plugin = new $.html_edit_price_add_on(this, options);
                $(this).data('html_edit_price_add_on', plugin);

            }

        });

    }

})(jQuery);


