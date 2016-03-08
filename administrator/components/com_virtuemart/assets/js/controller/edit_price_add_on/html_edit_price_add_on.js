(function ($) {

    // here we go!
    $.html_edit_price_add_on = function (element, options) {

        // plugin's default options
        var defaults = {
            debug:true,
            min:0,
            max:100,
            from:0,
            to:100,
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.change_price = function (self) {
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
                    $element.find('.item .mark-up-amount').prop('disabled', true);
                }else{
                    $element.find('.item .mark-up-amount').prop('disabled', false);
                }
            }
            if(self.hasClass('mark-up-amount'))
            {
                if(mark_up_amount>0)
                {
                    $element.find('.item .mark-up-percent').prop('disabled', true);
                }else{
                    $element.find('.item .mark-up-percent').prop('disabled', false);
                }
            }
            var current_value=self.autoNumeric('get');
            current_value=parseFloat(current_value.length>0?current_value:0);
            if(current_value>0)
            {
                $element.find('.item-flat input').prop('disabled', true);
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
                }else{
                    $element.find('.item-flat input').prop('disabled', false);
                }
            }
        };
        plugin.change_flat_price = function (self) {
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
                    $element.find('.item-flat .mark-up-amount').prop('disabled', true);
                }else{
                    $element.find('.item-flat .mark-up-amount').prop('disabled', false);
                }
            }
            if(self.hasClass('mark-up-amount'))
            {
                if(mark_up_amount>0)
                {
                    $element.find('.item-flat .mark-up-percent').prop('disabled', true);
                }else{
                    $element.find('.item-flat .mark-up-percent').prop('disabled', false);
                }
            }
            var current_value=self.autoNumeric('get');
            current_value=parseFloat(current_value.length>0?current_value:0);
            if(current_value>0)
            {

                $element.find('.item input').prop('disabled', true);
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
                }else{
                    $element.find('.item input').prop('disabled', false);
                }
            }
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
            $element.find('.auto').autoNumeric('init');
            $element.find('.more-group').click(function(){
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
            });
            $element.find('.item input').change(function(){
                plugin.change_price($(this));
            });

            $element.find('.children-discount-amount').change(function(){
                var value=$(this).autoNumeric('get');
                value=parseFloat(value.length>0?value:0);
                if(value>0)
                {
                    $element.find('.children-discount-percent').prop('disabled', true);
                }else{
                    $element.find('.children-discount-percent').prop('disabled', false);

                }

             });
            $element.find('.children-discount-percent').change(function(){
                var value=$(this).autoNumeric('get');
                value=parseFloat(value.length>0?value:0);
                if(value>0)
                {
                    $element.find('.children-discount-amount').prop('disabled', true);
                }else{
                    $element.find('.children-discount-amount').prop('disabled', false);

                }

             });



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


