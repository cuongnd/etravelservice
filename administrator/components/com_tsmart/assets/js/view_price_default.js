(function ($) {

    // here we go!
    $.view_price_default = function (element, options) {

        // plugin's default options
        var defaults = {
            list_price:[],
            totalPages:0,
            totalItem:10,
            tour_id:0,
            visiblePages:5,
            price_type:'',
            dialog_class:'dialog-form-price',
            date_format:'m/d/y',
            tsmart_price_id:0


        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var $price_form=$('#price-form');
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);

            var totalItem=plugin.settings.totalItem;
            var tour_id=plugin.settings.tour_id;
            var price_type=plugin.settings.price_type;
            $('.vm_toolbar').insertAfter($('.buid-information'));
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

            $price_form.find('.random-price').click(function(){
                $('.base-price .inputbox.number').each(function(){
                    $(this).val(Math.floor(Math.random() * 600) + 1).trigger('change');
                });
            });
            $price_form.find('.random-markup').click(function(){
                var type=['amount','percent'];
                var random_type=Math.floor(Math.random() * (1 - 0 + 1)) + 0;
                $('.mark-up-price  .inputbox.number').val('');
                $('.mark-up-price .'+type[random_type]+' .inputbox.number').each(function(){
                    if(random_type==0)
                    {
                        $(this).val(Math.floor(Math.random() * 200) + 1).trigger('change');
                    }else {
                        $(this).val(Math.floor(Math.random() * 99) + 1).trigger('change');
                    }

                });
            });
            $element.find('.edit-price').click(function(){
                var self=$(this);
                var $row=self.closest('tr[role="row"]');
                $row.toggleClass('focus');
                var tsmart_price_id=$row.data('tsmart_price_id');
                plugin.settings.tsmart_price_id=tsmart_price_id;
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'price',
                            task: 'get_list_price',
                            price_id:tsmart_price_id,
                            tour_id:tour_id,
                            price_type:price_type,
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

                        $('input[name="tsmart_price_id"]').val(tsmart_price_id);
                        $( "#price-form" ).dialog( "open" );
                        plugin.updata_price();
                    }
                });


            });
            var range_of_date= $('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
            var now = new Date();
            range_of_date.instant_daterangepicker.minDate=moment(now);
            now.setYear(now.getFullYear() + 2);
            range_of_date.instant_daterangepicker.maxDate=moment(now);

            var $html_select_service_class=$('#html_select_service_class_tsmart_service_class_id').data('html_select_service_class');
            $('select[name="tsmart_service_class_id"]').on("change", function(e) {
                var tsmart_service_class_id=$(this).val();
                var tsmart_price_id=plugin.settings.tsmart_price_id;
                // mostly used event, fired to the original element when the value changes
                var list_price=plugin.settings.list_price;
                var range_of_date= $('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
                var disable_dates=[];
                $.each(list_price,function(index,item){
                    if(item.tsmart_price_id!=tsmart_price_id && item.tsmart_service_class_id==tsmart_service_class_id)
                    {
                        var sale_period_from=new Date(item.sale_period_from);
                        var sale_period_to=new Date(item.sale_period_to);

                        while(sale_period_from < sale_period_to){
                            var item_date=moment(sale_period_from);
                            item_date=item_date.format('YYYY-MM-DD');
                            if(disable_dates.indexOf(item_date) == -1)
                            {
                                disable_dates.push(item_date);
                            }

                            var newDate = sale_period_from.setDate(sale_period_from.getDate() + 1);
                            sale_period_from = new Date(newDate);
                        }
                    }
                });
                range_of_date.instant_daterangepicker.disableDates=disable_dates;
            });
            var range_of_date= $('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
            range_of_date.selected_start_date=function(ev, startDate){
                return;
                var item_start_date=new Date(startDate);
                var tsmart_service_class_id=$('select[name="tsmart_service_class_id"]').val();
                var tsmart_price_id=plugin.settings.tsmart_price_id;
                var list_price=plugin.settings.list_price;
                if(list_price.length==0)
                {
                    return;
                }
                console.log(list_price);
                var min_sale_period_from=new Date();
                $.each(list_price,function(index,item){
                    if(item.tsmart_price_id!=tsmart_price_id && item.tsmart_service_class_id==tsmart_service_class_id)
                    {
                        var sale_period_from=new Date(item.sale_period_from);
                        var sale_period_to=new Date(item.sale_period_to);
                        if(min_sale_period_from<sale_period_from && item_start_date<=sale_period_from)
                        {
                            sale_period_from=moment(sale_period_from);
                            min_sale_period_from=new Date(sale_period_from.format('YYYY-MM-DD'));
                        }
                    }
                });
                var max_date=range_of_date.instant_daterangepicker.maxDate;
                console.log(max_date.format('YYYY-MM-DD'));
                max_date=new Date(max_date.format('YYYY-MM-DD'));
                var disable_dates=range_of_date.instant_daterangepicker.disableDates;

                while(min_sale_period_from<max_date  ){
                    var item_date=moment(min_sale_period_from);
                    item_date=item_date.format('YYYY-MM-DD');
                    if(disable_dates.indexOf(item_date) == -1)
                    {
                        disable_dates.push(item_date);
                    }

                    var newDate = min_sale_period_from.setDate(min_sale_period_from.getDate() + 1);
                    min_sale_period_from = new Date(newDate);
                }

                range_of_date.instant_daterangepicker.disableDates=disable_dates;
                range_of_date.instant_daterangepicker.updateView();
                range_of_date.instant_daterangepicker.updateCalendars();


            };
            range_of_date.daterangepicker.on('reset.daterangepicker', function() {
                var tsmart_service_class_id=$('select[name="tsmart_service_class_id"]').val();
                var tsmart_price_id=plugin.settings.tsmart_price_id;
                // mostly used event, fired to the original element when the value changes
                var list_price=plugin.settings.list_price;
                var range_of_date= $('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
                var disable_dates=[];
                $.each(list_price,function(index,item){
                    if(item.tsmart_price_id!=tsmart_price_id && item.tsmart_service_class_id==tsmart_service_class_id)
                    {
                        var sale_period_from=new Date(item.sale_period_from);
                        var sale_period_to=new Date(item.sale_period_to);

                        while(sale_period_from < sale_period_to){
                            var item_date=moment(sale_period_from);
                            item_date=item_date.format('YYYY-MM-DD');
                            if(disable_dates.indexOf(item_date) == -1)
                            {
                                disable_dates.push(item_date);
                            }

                            var newDate = sale_period_from.setDate(sale_period_from.getDate() + 1);
                            sale_period_from = new Date(newDate);
                        }
                    }
                });
                range_of_date.instant_daterangepicker.disableDates=disable_dates;
                range_of_date.instant_daterangepicker.updateView();
                range_of_date.instant_daterangepicker.updateCalendars();
            });

            range_of_date.selected_end_date=function(ev, endDate){
            };

            $('.dialog-form-price table.base-price').find('input[column-type="private_room"]').change(function(){
                var private_room=$(this).val();
                $('.dialog-form-price table.base-price').find('input[column-type="private_room"]').val(private_room);
            });

            $element.find('.delete-price').click(function(){

                if (confirm('Are you sure you want delete this item ?')) {
                    var self=$(this);
                    var $row=self.closest('tr[role="row"]');
                    var price_id=$row.data('tsmart_price_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'price',
                                task: 'ajax_remove',
                                price_id:price_id,
                                tour_id:tour_id,
                                price_type:price_type,
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
                    var price_id=$row.data('tsmart_price_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'price',
                                task: 'ajax_publish',
                                price_id:price_id,
                                tour_id:tour_id,
                                price_type:price_type,
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
                if(!plugin.validate())
                {
                    return false;
                }

                var dataPost=$('.dialog-form-price').find(':input').serializeObject();

                console.log(dataPost.tour_price_by_tour_price_id);
                var price_id=dataPost.tsmart_price_id;
                var $row=$('.list-prices tr[data-price_id="'+price_id+'"]');
                if (confirm('Are you sure you want save this item ?')) {
                   //save and close
                    $.ajax({
                        type: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        url: 'index.php?option=com_tsmart&controller=price&task=ajax_save_price',
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
                                $row.attr('data-price_id',result.tsmart_price_id);
                                $row.data('price_id',result.tsmart_price_id);
                                $row.find('span.item-id').html(result.tsmart_price_id);
                                $row.find('input[name="tsmart_price_id[]"]').val(result.tsmart_price_id);
                                if( result.service_class !=null && typeof result.service_class !="undefined")
                                {
                                    $row.find('td.service_class_name').html(result.service_class.service_class_name);
                                }
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
                    var price_id=$('input[name="tsmart_price_id"]').val();
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
            plugin.validate=function(){
                var list_price=plugin.settings.list_price;
                var tsmart_price_id=$('input[name="tsmart_price_id"]').val();
                var tsmart_service_class_id=$('select[name="tsmart_service_class_id"]').val();
                if(tsmart_service_class_id==0)
                {
                    alert('please select service class');
                    return false;
                }
                var sale_period_from=$('input[name="sale_period_from"]').val();
                sale_period_from=new Date(sale_period_from);
                var sale_period_to=$('input[name="sale_period_to"]').val();
                sale_period_to=new Date(sale_period_to);
                if(list_price.length>0)
                {
                    for(var i=0;i<list_price.length;i++){
                        var item=list_price[i];
                        if(item.tsmart_price_id!=tsmart_price_id && tsmart_service_class_id==item.tsmart_service_class_id)
                        {
                            var item_sale_period_from=new Date(item.sale_period_from);
                            var item_sale_period_to=new Date(item.sale_period_to);
                            if (sale_period_from > item_sale_period_from && sale_period_from < item_sale_period_to){
                                alert('sale_period_from > item_sale_period_from && sale_period_from < item_sale_period_to');
                                return false;
                            }


                        }
                    }
                }
                return true;

            };
            $('.dialog-form-price').find('.apply-price').click(function(){
                if(!plugin.validate())
                {
                    return false;
                }
                var dataPost=$('.dialog-form-price').find(':input').serializeObject();
                if (confirm('Are you sure you want save this item ?')) {
                    var price_id=dataPost.tsmart_price_id;
                    var $row=$('.list-prices tr[data-price_id="'+price_id+'"]');
                    //save and close
                    $.ajax({
                        type: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        url: 'index.php?option=com_tsmart&controller=price&task=ajax_save_price',
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
                                $row.attr('data-price_id',result.tsmart_price_id);
                                $row.data('price_id',result.tsmart_price_id);
                                $row.find('span.item-id').html(result.tsmart_price_id);
                                $row.find('input[name="tsmart_price_id[]"]').val(result.tsmart_price_id);
                                if( result.service_class !=null && typeof result.service_class !="undefined")
                                {
                                    $row.find('td.service_class_name').html(result.service_class.service_class.service_class_name);
                                }
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
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'price',
                                task: 'get_list_price'
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
                            $('.'+plugin.settings.dialog_class).find('input.number').val(0);
                            plugin.fill_data(response);

                            $('input[name="tsmart_price_id"]').val(0);
                            plugin.updata_price();
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
            var price_type=plugin.settings.price_type;
            var flat_price=plugin.settings.flat_price;
            var list_tour_price_by_tour_price_id=result.list_tour_price_by_tour_price_id;
            if(typeof list_tour_price_by_tour_price_id=="undefined")
            {
                list_tour_price_by_tour_price_id=result.tour_private_price_by_tour_price_id;

            }
            $('input[name="tax"]').val(result.price.tax);
            $('textarea[name="price_note"]').val(String(result.price.price_note).trim());
            if(result.price.sale_period_from!=null)
            {
                $('input[name="sale_period_from"]').val(result.price.sale_period_from);
            }
            if(result.price.sale_period_to!=null) {
                $('input[name="sale_period_to"]').val(result.price.sale_period_to);
            }
            var range_of_date= $('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
            if(typeof range_of_date!="undefined" && result.price.sale_period_to!=null && result.price.sale_period_to!=null) {
                range_of_date.set_date(result.price.sale_period_from,result.price.sale_period_to);
            }
            $('select[name="tsmart_service_class_id"]').val(result.price.tsmart_service_class_id);
            console.log(result.price.tsmart_service_class_id);
            console.log($('select[name="tsmart_service_class_id"]'));
            $('select[name="tsmart_service_class_id"]').trigger("chosen:updated");
            if(price_type!=flat_price&& list_tour_price_by_tour_price_id!=null && (typeof list_tour_price_by_tour_price_id!="undefined") && list_tour_price_by_tour_price_id.length)
            {
                $.each(list_tour_price_by_tour_price_id,function(index,item){
                    var $row=$('table.base-price tr[data-group_size_id="'+item.tsmart_group_size_id+'"]');
                    $row.find('td input[column-type="senior"]').val(item.price_senior);
                    $row.find('td input[column-type="adult"]').val(item.price_adult);
                    $row.find('td input[column-type="teen"]').val(item.price_teen);
                    $row.find('td input[column-type="children1"]').val(item.price_children1);
                    $row.find('td input[column-type="children2"]').val(item.price_children2);
                    $row.find('td input[column-type="infant"]').val(item.price_infant);
                    $row.find('td input[column-type="private_room"]').val(item.price_private_room);
                    $row.find('td input[column-type="price_extra_bed"]').val(item.price_extra_bed);

                });

            }else if(list_tour_price_by_tour_price_id !=null){
                console.log(list_tour_price_by_tour_price_id);
                var $row=$('table.base-price  tbody tr');
                $row.find('td input[column-type="senior"]').val(list_tour_price_by_tour_price_id.price_senior);
                $row.find('td input[column-type="adult"]').val(list_tour_price_by_tour_price_id.price_adult);
                $row.find('td input[column-type="teen"]').val(list_tour_price_by_tour_price_id.price_teen);
                $row.find('td input[column-type="children1"]').val(list_tour_price_by_tour_price_id.price_children1);
                $row.find('td input[column-type="children2"]').val(list_tour_price_by_tour_price_id.price_children2);
                $row.find('td input[column-type="infant"]').val(list_tour_price_by_tour_price_id.price_infant);
                $row.find('td input[column-type="private_room"]').val(list_tour_price_by_tour_price_id.price_private_room);
                $row.find('td input[column-type="extra_bed"]').val(list_tour_price_by_tour_price_id.price_extra_bed);

            }


            var list_mark_up=result.list_mark_up;

            if(typeof list_mark_up=="object")
            {
                $.each(list_mark_up,function(type,item){
                    var $row=$('table.mark-up-price tr.'+type);
                    $row.find('td input[column-type="senior"]').val(item.senior);
                    $row.find('td input[column-type="adult"]').val(item.adult);
                    $row.find('td input[column-type="teen"]').val(item.teen);
                    $row.find('td input[column-type="children1"]').val(item.children1);
                    $row.find('td input[column-type="children2"]').val(item.children2);
                    $row.find('td input[column-type="infant"]').val(item.infant);
                    $row.find('td input[column-type="private_room"]').val(item.private_room);
                    $row.find('td input[column-type="extra_bed"]').val(item.extra_bed);

                });
            }
            var price=result.price;
            var full_charge_children1=price.full_charge_children1;
            if(full_charge_children1==1)
            {
                $price_form.find('input[name="full_charge_children1"]').prop('checked',true);
            }

            var full_charge_children2=price.full_charge_children2;
            if(full_charge_children2==1)
            {
                $price_form.find('input[name="full_charge_children2"]').prop('checked',true);
            }


        }
        plugin.updata_price=function update_price(){
            var price_type=plugin.settings.price_type;
            var flat_price=plugin.settings.flat_price;
            var $base_price_tr_first= $('.'+plugin.settings.dialog_class).find('table.base-price tbody tr');
            var tax=$('input[name="tax"]').val();
            tax=parseFloat(tax);
            $base_price_tr_first.find('input').each(function(){
                var $self=$(this);
                var group_id=$self.attr('group-id');
                var column_type=$self.attr('column-type');
                var price=$self.val();
                if(column_type=="extra_bed"){
                    console.log('test123');
                }
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
                if(price_type!=flat_price)
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
                //profit_price=Math.round(profit_price);
                //profit_price=Math.round(profit_price, 2);
                $span_profit.html(Math.round(profit_price));
                if(price_type!=flat_price)
                {
                    var $span_sale=$('table.sale-price span[group-id="'+group_id+'"][column-type="'+column_type+'"]');
                }else{
                    var $span_sale=$('table.sale-price span[column-type="'+column_type+'"]');
                }

                var sale_price=profit_price+price+((profit_price+price)*tax)/100;
                //sale_price=Math.round(sale_price);
                //sale_price=Math.round(profit_price, 2);
                $span_sale.html(Math.round(sale_price));


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


