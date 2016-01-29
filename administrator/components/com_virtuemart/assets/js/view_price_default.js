(function ($) {

    // here we go!
    $.view_price_default = function (element, options) {

        // plugin's default options
        var defaults = {
            totalPages:0,
            totalItem:10,
            tour_id:0,
            visiblePages:5,
            tour_methor:'',
            dialog_class:'dialog-form-price',
            date_format:'m/d/y'


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
            var totalItem=plugin.settings.totalItem;
            var tour_id=plugin.settings.tour_id;
            var tour_methor=plugin.settings.tour_methor;
/*
            $element.find('#ul_pagination').twbsPagination({
                totalPages: plugin.settings.totalPages,
                visiblePages: plugin.settings.visiblePages,
                onPageClick: function (event, page) {
                    $('#page-content').text('Page ' + page);
                }
            });*/


            $element.find( "#price-form" ).dialog({
                autoOpen: false,
                modal:true,
                width:700,
                appendTo:'body',
                dialogClass:plugin.settings.dialog_class,
                //closeOnEscape: false,
                //open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

            });


            $element.find('.edit-price').click(function(){
                var self=$(this);
                var $row=self.closest('tr[role="row"]');
                $row.toggleClass('focus');
                var price_id=$row.data('price_id');
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'price',
                            task: 'get_list_price',
                            price_id:price_id,
                            tour_id:tour_id,
                            tour_methor:tour_methor,
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
                        $('.'+plugin.settings.dialog_class).find('input.number').val(0);
                        plugin.fill_data(response);
                        $('input[name="virtuemart_price_id"]').val(price_id);
                        $( "#price-form" ).dialog( "open" );
                        plugin.updata_price();
                    }
                });


            });
            $element.find('.delete-price').click(function(){

                if (confirm('Are you sure you want delete this item ?')) {
                    var self=$(this);
                    var $row=self.closest('tr[role="row"]');
                    var price_id=$row.data('price_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'price',
                                task: 'ajax_remove',
                                price_id:price_id,
                                tour_id:tour_id,
                                tour_methor:tour_methor,
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
                            if(response==1)
                            {
                                alert('delete item succesfull');
                                $row.remove();
                            }
                            else{
                                alert(response);
                            }
                        }
                    });

                } else {
                    return;
                }






            });

            $element.find('.publish-price').click(function(){

                if (confirm('Are you sure you want publish this item ?')) {
                    var self=$(this);
                    var $row=self.closest('tr[role="row"]');
                    var price_id=$row.data('price_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'price',
                                task: 'ajax_publish',
                                price_id:price_id,
                                tour_id:tour_id,
                                tour_methor:tour_methor,
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
                            if(response==1)
                            {
                                alert('publish item succesfull');
                                $row.find('a.publish-price span.icon-white').toggleClass( "icon-publish icon-unpublish");
                            }
                            else{
                                alert(response);
                            }
                        }
                    });

                } else {
                    return;
                }






            });
            $('.'+plugin.settings.dialog_class).find('input.number').keypress(function (e) {
                if(e.which==13) {

                    plugin.updata_price();
                }
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message

                    return false;
                }
                else if(e.which==13){
                    plugin.updata_price();

                }

            });
            plugin.updata_price();
            $('.'+plugin.settings.dialog_class).find('input.number').keyup(function (e) {
                if(e.which==13) {

                    plugin.updata_price();
                }
                //if the letter is not digit then display error and don't type anything
                /*if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message

                    return false;
                }
                else{
                    var total=0;
                    $('table.mark-up-price tr.percent input').each(function(){

                    });
                    console.log('state update');



                }*/



            });
            $('.'+plugin.settings.dialog_class).find('input.number').change(function (e) {
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message

                    return false;
                }
                else{
                    plugin.updata_price();

                }

            });

            $('.dialog-form-price').find('.save-close-price').click(function(){
                var dataPost=$('.dialog-form-price').find(':input').serializeObject();
                var price_id=dataPost.virtuemart_price_id;
                var $row=$('.list-prices tr[data-price_id="'+price_id+'"]');
                if (confirm('Are you sure you want save this item ?')) {
                   //save and close
                    $.ajax({
                        type: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        url: 'index.php?option=com_virtuemart&controller=price&task=ajax_save_price',
                        data: JSON.stringify(dataPost),
                        beforeSend: function () {

                            $('.div-loading').css({
                                display: "block"
                            });
                        },
                        success: function (response) {

                            $('.div-loading').css({
                                display: "none"


                            });
                            if(response.e==0)
                            {
                                var result=response.r;


                                alert('save item succesfull');
                                $( "#price-form").dialog( "close" );
                                if($row.length==0)
                                {
                                    $row=$('.list-prices tbody tr:first').clone(true).prependTo( ".list-prices tbody").fadeIn('slow');
                                }
                                $row.attr('data-price_id',result.virtuemart_price_id);
                                $row.data('price_id',result.virtuemart_price_id);
                                $row.find('span.item-id').html(result.virtuemart_price_id);
                                $row.find('input[name="row_price_id[]"]').val(result.virtuemart_price_id);
                                $row.find('td.service_class_name').html(result.service_class.service_class_name);
                                $row.find('td.sale_period').html(result.sale_period);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.price_note').html(result.price_note.trim());
                                $row.find('a.publish-price .icon-white').removeClass('icon-unpublish').addClass('icon-publish');

                                $row.delay(500).queue(function(){
                                    $(this).toggleClass('focus');
                                    $(this).dequeue();
                                });

                            }
                            else{
                                alert(response.m);
                            }


                        }
                    });


                } else {
                    return;
                }
            });
            $('.dialog-form-price').find('.cancel-price').click(function(){
                if (confirm('Are you sure you want cancel this item ?')) {
                   //save and close
                    var price_id=$('input[name="virtuemart_price_id"]').val();
                    $( "#price-form").dialog( "close" );
                    var $row=$('.list-prices tr[data-price_id="'+price_id+'"]');
                    $row.delay(500).queue(function(){
                        $(this).toggleClass('focus');
                        $(this).dequeue();
                    });
                } else {
                    return;
                }
            });
            $('.dialog-form-price').find('.apply-price').click(function(){
                var dataPost=$('.dialog-form-price').find(':input').serializeObject();
                if (confirm('Are you sure you want save this item ?')) {
                    var price_id=dataPost.virtuemart_price_id;
                    var $row=$('.list-prices tr[data-price_id="'+price_id+'"]');
                    //save and close
                    $.ajax({
                        type: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        url: 'index.php?option=com_virtuemart&controller=price&task=ajax_save_price',
                        data: JSON.stringify(dataPost),
                        beforeSend: function () {

                            $('.div-loading').css({
                                display: "block"
                            });
                        },
                        success: function (response) {

                            $('.div-loading').css({
                                display: "none"


                            });
                            if(response.e==0)
                            {
                                var result=response.r;
                                alert('save item succesfull');
                                if($row.length==0)
                                {
                                    $row=$('.list-prices tbody tr:first').clone(true).prependTo( ".list-prices tbody").fadeIn('slow');
                                }
                                $row.attr('data-price_id',result.virtuemart_price_id);
                                $row.data('price_id',result.virtuemart_price_id);
                                $row.find('span.item-id').html(result.virtuemart_price_id);
                                $row.find('input[name="row_price_id[]"]').val(result.virtuemart_price_id);
                                $row.find('td.service_class_name').html(result.service_class.service_class_name);
                                $row.find('td.sale_period').html(result.sale_period);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.price_note').html(result.price_note.trim());
                                $row.find('a.publish-price .icon-white').removeClass('icon-unpublish').addClass('icon-publish');

                                $row.delay(500).queue(function(){
                                    $(this).toggleClass('focus');
                                    $(this).dequeue();
                                });

                            }
                            else{
                                alert(response.m);
                            }


                        }
                    });


                } else {
                    return;
                }            });
            Joomla.submitbutton = function(task)
            {
                if (task == "add")
                {
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                task: 'price.get_list_price'
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
                            $( "#price-form" ).dialog( "open" );
                        }
                    });


                }
            };
            Joomla.delete_item = function(task,item_id)
            {
                if (confirm('Are you sure you want delete this item ?')) {
                    form = document.getElementById('adminForm');
                    $('input[name="cid[]"]').val(item_id);
                    Joomla.submitform(task);
                } else {
                    return;
                }


            };


            $('.'+plugin.settings.dialog_class).find('input.number').change(function (e) {
                var value=$(this).val();
                if(typeof value=='undefined'||value=='')
                {
                    $(this).val(0);
                    console.log(value);
                    plugin.updata_price();
                }

            });
            $('table.mark-up-price tr.percent input').focusin(function(e){
                total=0;
                $('table.mark-up-price tr.amount input').each(function(){
                    total+=$(this).val();
                });
                if(total>0)
                {
                    alert('you cannot input percent when amount is <> 0');
                }
                return false;
            });
            $('table.mark-up-price tr.amount input').focusin(function(e){
                total=0;
                $('table.mark-up-price tr.percent input').each(function(){
                    total+=$(this).val();
                });
                if(total>0)
                {
                    alert('you cannot input amount when percent is <> 0');
                }
                return false;
            });


        }
        plugin.fill_data=function(result)
        {
            var date_format=plugin.settings.date_format;
            var tour_methor=plugin.settings.tour_methor;
            var list_tour_price_by_tour_price_id=result.list_tour_price_by_tour_price_id;
            if(typeof list_tour_price_by_tour_price_id=="undefined")
            {
                list_tour_price_by_tour_price_id=result.tour_private_price_by_tour_price_id;

            }
            $('input[name="tax"]').val(result.price.tax);
            $('textarea[name="price_note"]').val(result.price.price_note.trim());
            $('#sale_period_from').val(result.price.sale_period_from);
            var sale_period_from=$.datepicker.formatDate(date_format, new Date(result.price.sale_period_from))
            $('#sale_period_from_text').val(sale_period_from);

            $('#sale_period_to').val(result.price.sale_period_to);
            var sale_period_to=$.datepicker.formatDate(date_format, new Date(result.price.sale_period_to))
            $('#sale_period_to_text').val(sale_period_to);


            $('select[name="service_class_id"]').val(result.price.service_class_id);
            $('select[name="service_class_id"]').trigger("liszt:updated.chosen");
            if(tour_methor=='tour_group')
            {
                $.each(list_tour_price_by_tour_price_id,function(index,item){
                    var $row=$('table.base-price tr[data-group_size_id="'+item.virtuemart_group_size_id+'"]');
                    $row.find('td input[column-type="senior"]').val(item.price_senior);
                    $row.find('td input[column-type="adult"]').val(item.price_adult);
                    $row.find('td input[column-type="teen"]').val(item.price_teen);
                    $row.find('td input[column-type="children1"]').val(item.price_children1);
                    $row.find('td input[column-type="children2"]').val(item.price_children2);
                    $row.find('td input[column-type="infant"]').val(item.price_infant);
                    $row.find('td input[column-type="private_room"]').val(item.price_private_room);

                });

            }else{
                console.log(list_tour_price_by_tour_price_id);
                var $row=$('table.base-price  tbody tr');
                $row.find('td input[column-type="senior"]').val(list_tour_price_by_tour_price_id.price_senior);
                $row.find('td input[column-type="adult"]').val(list_tour_price_by_tour_price_id.price_adult);
                $row.find('td input[column-type="teen"]').val(list_tour_price_by_tour_price_id.price_teen);
                $row.find('td input[column-type="children1"]').val(list_tour_price_by_tour_price_id.price_children1);
                $row.find('td input[column-type="children2"]').val(list_tour_price_by_tour_price_id.price_children2);
                $row.find('td input[column-type="infant"]').val(list_tour_price_by_tour_price_id.price_infant);
                $row.find('td input[column-type="private_room"]').val(list_tour_price_by_tour_price_id.price_private_room);

            }


            var list_mark_up=result.list_mark_up;
            $.each(list_mark_up,function(type,item){
                var $row=$('table.mark-up-price tr.'+type);
                $row.find('td input[column-type="senior"]').val(item.senior);
                $row.find('td input[column-type="adult"]').val(item.adult);
                $row.find('td input[column-type="teen"]').val(item.teen);
                $row.find('td input[column-type="children1"]').val(item.children1);
                $row.find('td input[column-type="children2"]').val(item.children2);
                $row.find('td input[column-type="infant"]').val(item.infant);
                $row.find('td input[column-type="private_room"]').val(item.private_room);

            });


        }
        plugin.updata_price=function updata_price(){
            var tour_methor=plugin.settings.tour_methor;
            var $base_price_tr_first= $('.'+plugin.settings.dialog_class).find('table.base-price tbody tr');
            var tax=$('input[name="tax"]').val();
            tax=parseFloat(tax);
            $base_price_tr_first.find('input').each(function(){
                var $self=$(this);
                var group_id=$self.attr('group-id');
                var column_type=$self.attr('column-type');
                var price=$self.val();

                price=parseFloat(price);
                var amount=$('table.mark-up-price tr.amount input[column-type="'+column_type+'"]').val();
                if(typeof amount=="undefined")
                {

                    amount=0;
                }
                amount=parseFloat(amount);
                var percent=$('table.mark-up-price tr.percent input[column-type="'+column_type+'"]').val();
                if(typeof percent=="undefined")
                {
                    percent=0;
                }
                percent=parseFloat(percent);
                if(tour_methor=='tour_group')
                {
                    var $span_profit=$('table.profit-price span[group-id="'+group_id+'"][column-type="'+column_type+'"]');
                }else{
                    var $span_profit=$('table.profit-price span[column-type="'+column_type+'"]');
                }

                var profit_price=0;
                if(percent>0)
                {
                    profit_price=(price*percent)/100;
                }else{
                    profit_price=amount;
                }
                profit_price=Math.round(profit_price);
                //profit_price=Math.round(profit_price, 2);
                $span_profit.html(profit_price);
                if(tour_methor=='tour_group')
                {
                    var $span_sale=$('table.sale-price span[group-id="'+group_id+'"][column-type="'+column_type+'"]');
                }else{
                    var $span_sale=$('table.sale-price span[column-type="'+column_type+'"]');
                }

                var sale_price=profit_price+price+((profit_price+price)*tax)/100;
                sale_price=Math.round(sale_price);
                //sale_price=Math.round(profit_price, 2);
                $span_sale.html(sale_price);


            });
        }
        plugin.set_disable_amount=function()
        {
            var total=0;
            $('table.mark-up-price tr.percent input').each(function(){
                total+=$(this).val();
            });
            if(total>0)
            {
                alert('you cannot ')
                $('table.mark-up-price tr.amount input').val(0).prop('disabled', true);
            }else{
                $('table.mark-up-price tr.amount input').prop('disabled', false);
            }
        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_price_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_price_default')) {
                var plugin = new $.view_price_default(this, options);
                $(this).data('view_price_default', plugin);

            }

        });

    }

})(jQuery);


