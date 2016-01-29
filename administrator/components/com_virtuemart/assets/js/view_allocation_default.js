(function ($) {

    // here we go!
    $.view_allocation_default = function (element, options) {
        // plugin's default options
        var defaults = {

            list_date: [],


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
            var list_date=plugin.settings.list_date;

            var multi_calendar_allocation=$('#multi-calendar-allocation').DatePicker({
                mode: 'multiple',
                inline: true,
                calendars: 2,
                date: list_date
            });
            $element.find( ".allocation-edit-form" ).dialog({
                autoOpen: false,
                modal:true,
                width:800,
                appendTo:'body',
                dialogClass:"dialog-allocation-edit-form"
                //closeOnEscape: false,
                //open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

            });

            $element.find('.edit-allocation').click(function(){
                var self=$(this);
                var $row=self.closest('tr[role="row"]');
                var allocation_id=$row.data('virtuemart_allocation_id');
                var tour_id=$row.data('tour_id');
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'allocation',
                            task: 'ajax_get_allocation_item',
                            allocation_id:allocation_id,
                            tour_id:tour_id
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
                        $( ".allocation-edit-form" ).dialog( "open" );
                        $('.'+plugin.settings.dialog_class).find('input.number').val(0);
                        $('#virtuemart_allocation_id').val(allocation_id);
                        //plugin.fill_data(response);
                        //plugin.updata_price();
                    }
                });


            });
            $('.dialog-allocation-edit-form').find('.calculator-price').click(function(e){
                var self=$(this);
                var min_space=$('#min_space').val();
                var vail_period_from=$('#vail_period_from').val();
                var vail_period_to=$('#vail_period_to').val();
                var tour_id=$('#tour_id').val();
                var allocation_id=$('#virtuemart_allocation_id').val();
                var $row=self.closest('tr[role="row"]');
                var tour_class_ids=[];
                var tour_service_class_id=$('#tour_service_class_id').val();
                var weeklies=[];
                $('input[name="weekly[]"]:checked').each(function(){
                    weeklies.push($(this).val());
                });



                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'allocation',
                            task: 'ajax_get_allocation_item',
                            allocation_id:allocation_id,
                            min_space:min_space,
                            vail_period_from:vail_period_from,
                            vail_period_to:vail_period_to,
                            tour_id:tour_id,
                            tour_service_class_id:tour_service_class_id,
                            weeklies:weeklies

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
                        plugin.fill_price(response);


                    }
                });


            });
            $('.dialog-allocation-edit-form').find('.save-close-price').click(function(e){
                var self=$(this);
                var min_space=$('#min_space').val();
                var vail_period_from=$('#vail_period_from').val();
                var vail_period_to=$('#vail_period_to').val();
                var tour_id=$('#tour_id').val();
                var allocation_id=$('#virtuemart_allocation_id').val();
                var $row=self.closest('tr[role="row"]');
                var tour_class_ids=[];
                var tour_service_class_id=$('#tour_service_class_id').val();
                var weeklies=[];
                $('input[name="weekly[]"]:checked').each(function(){
                    weeklies.push($(this).val());
                });



                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'allocation',
                            task: 'ajax_save_allocation_item',
                            allocation_id:allocation_id,
                            min_space:min_space,
                            vail_period_from:vail_period_from,
                            vail_period_to:vail_period_to,
                            tour_id:tour_id,
                            tour_service_class_id:tour_service_class_id,
                            weeklies:weeklies

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
                        plugin.fill_data(response);
                        $( ".allocation-edit-form" ).dialog( "close" );


                    }
                });


            });



            $('#vail_period_from').datepicker();
            $('#vail_period_to').datepicker();
            $('#sale_period_open').datepicker();
            $('#sale_period_close').datepicker();
            Joomla.submitbutton = function(task)
            {

                if (task == "cancel")
                {
                    Joomla.submitform(task);
                }else if (task == "save" || task == "apply"){
                    var list_date=[];
                    var dates=multi_calendar_allocation.DatePickerGetDate();
                    dates=dates[0];
                    $.each(dates,function(index,date){
                        list_date.push($.format.date(date, "yyyy/MM/dd"));
                    });
                    $('#days_seleted').val(cassandraMAP.stringify(list_date));
                    Joomla.submitform(task);
                }
            };


        }
        plugin.fill_data=function(result)
        {
            return false;
            var date_format=plugin.settings.date_format;
            var list_tour_promotion_price_by_tour_promotion_price_id=result.list_tour_promotion_price_by_tour_promotion_price_id;
            if(typeof list_tour_promotion_price_by_tour_promotion_price_id=="undefined")
            {
                list_tour_promotion_price_by_tour_promotion_price_id=result.tour_private_price_by_tour_price_id;

            }
            $('input[name="tax"]').val(result.price.tax);
            $('input[name="tour_methor"]').val(result.tour.tour_methor);
            $('input[name="virtuemart_promotion_price_id"]').val(result.price.virtuemart_promotion_price_id);
            $('textarea[name="price_note"]').val(result.price.price_note.trim());
            $('#sale_period_from').val(result.price.sale_period_from);
            var sale_period_from=$.datepicker.formatDate(date_format, new Date(result.price.sale_period_from))
            $('#sale_period_from_text').val(sale_period_from);

            $('#sale_period_to').val(result.price.sale_period_to);
            var sale_period_to=$.datepicker.formatDate(date_format, new Date(result.price.sale_period_to))
            $('#sale_period_to_text').val(sale_period_to);


            $('select[name="service_class_id"]').val(result.price.service_class_id);
            $('select[name="service_class_id"]').trigger("liszt:updated.chosen");
            tour_methor=result.tour.tour_methor;
            if(tour_methor=='tour_group')
            {
                $('#tour_group').css({
                    display:"block"
                });
                $('#tour_basic').css({
                    display:"none"
                });
                $.each(list_tour_promotion_price_by_tour_promotion_price_id,function(index,item){
                    var $row=$('table.base-price tr[data-group_size_id="'+item.virtuemart_group_size_id+'"]');
                    $row.find('td input[column-type="senior"]').val(item.price_senior);
                    $row.find('td input[column-type="adult"]').val(item.price_adult);
                    $row.find('td input[column-type="teen"]').val(item.price_teen);
                    $row.find('td input[column-type="children1"]').val(item.price_children1);
                    $row.find('td input[column-type="children2"]').val(item.price_children2);
                    $row.find('td input[column-type="infant"]').val(item.price_infant);
                    $row.find('td input[column-type="private_room"]').val(item.price_private_room);

                });

            }else if(typeof list_tour_promotion_price_by_tour_promotion_price_id!="undefined"){
                $('#tour_group').css({
                    display:"none"
                });
                $('#tour_basic').css({
                    display:"block"
                });
                console.log(list_tour_promotion_price_by_tour_promotion_price_id);
                var $row = $('table.base-price  tbody tr');
                $row.find('td input[column-type="senior"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_senior);
                $row.find('td input[column-type="adult"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_adult);
                $row.find('td input[column-type="teen"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_teen);
                $row.find('td input[column-type="children1"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_children1);
                $row.find('td input[column-type="children2"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_children2);
                $row.find('td input[column-type="infant"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_infant);
                $row.find('td input[column-type="private_room"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_private_room);
            }


            var list_promotion_mark_up=result.list_promotion_mark_up;
            $.each(list_promotion_mark_up,function(type,item){
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


        plugin.fill_price=function(response){
            $('table.sale-price tr.promotion-price span[column-type="senior"]').html(response.price_senior);
            $('table.sale-price tr.promotion-price span[column-type="adult"]').html(response.price_adult);
            $('table.sale-price tr.promotion-price span[column-type="teen"]').html(response.price_teen);
            $('table.sale-price tr.promotion-price span[column-type="children1"]').html(response.price_children1);
            $('table.sale-price tr.promotion-price span[column-type="children2"]').html(response.price_children2);
            $('table.sale-price tr.promotion-price span[column-type="infant"]').html(response.price_infant);
            $('table.sale-price tr.promotion-price span[column-type="private_room"]').html(response.price_private_room);



            $('table.sale-price tr.base-price span[column-type="senior"]').html(response.base_price.price_senior);
            $('table.sale-price tr.base-price span[column-type="adult"]').html(response.base_price.price_adult);
            $('table.sale-price tr.base-price span[column-type="teen"]').html(response.base_price.price_teen);
            $('table.sale-price tr.base-price span[column-type="children1"]').html(response.base_price.price_children1);
            $('table.sale-price tr.base-price span[column-type="children2"]').html(response.base_price.price_children2);
            $('table.sale-price tr.base-price span[column-type="infant"]').html(response.base_price.price_infant);
            $('table.sale-price tr.base-price span[column-type="private_room"]').html(response.base_price.price_private_room);
        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_allocation_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_allocation_default')) {
                var plugin = new $.view_allocation_default(this, options);
                $(this).data('view_allocation_default', plugin);

            }

        });

    }

})(jQuery);


