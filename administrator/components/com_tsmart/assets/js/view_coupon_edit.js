(function ($) {

    // here we go!
    $.view_coupon_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            task:'',
            hotel:[]
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
            $element.find("#adminForm").validate();
            $element.find('.toolbar .cancel').click(function(){
                $('input[name="task"]').val('cancel');
                $('#adminForm').submit();

            });
            $element.find('#select_from_date_to_date_vail_from_vail_to').on('apply.daterangepicker', function(ev, picker) {
                $element.find('input[name="list_tour_id[]"]:not(:checked)').prop('disabled', true).hide().closest('.checkbox').hide();
                $element.find('input[name="hotel_addon_type"]').prop('checked', false);
            });


            $element.find('input[name="list_tour_id[]"]:not(:checked)').prop('disabled', true).hide().closest('.checkbox').hide();
            $element.find('select[name="virtuemart_hotel_id"]').change(function(){
                $element.find('input[name="hotel_addon_type"]').prop('checked', false);
                $element.find('input[name="list_tour_id[]"]').prop('disabled', true).hide().closest('.checkbox').hide();
                var virtuemart_hotel_id=$(this).val();
                if(virtuemart_hotel_id==0||virtuemart_hotel_id==""||typeof virtuemart_hotel_id=="undefined")
                {
                    $element.find('input[name="location"]').val();
                    return;
                }

                $.ajax({
                    type: "GET",
                    url: 'index.php?option=com_tsmart&controller=coupon&task=get_detail_hotel&virtuemart_hotel_id='+virtuemart_hotel_id,
                    dataType: "json",
                    data: (function () {

                        dataPost = {
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
                        plugin.settings.hotel=response;
                        $element.find('input[name="location"]').val(response.city_area_name);
                    }

                });




            });
            $element.find('input[name="list_tour_id[]"]').click(function(){
                var self=$(this);
                var virtuemart_product_id=$(this).val();
                var vail_from=$element.find('input[name="vail_from"]').val();
                var vail_to=$element.find('input[name="vail_to"]').val();
                var virtuemart_hotel_addon_id=$element.find('input[name="virtuemart_hotel_addon_id"]').val();
                var hotel_addon_type=$element.find('input[name="hotel_addon_type"]').val();
                $.ajax({
                    type: "GET",
                    url: 'index.php?option=com_tsmart&controller=coupon&task=check_tour&virtuemart_product_id='+virtuemart_product_id,
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            vail_from:vail_from,
                            vail_to:vail_to,
                            virtuemart_hotel_addon_id:virtuemart_hotel_addon_id,
                            hotel_addon_type:hotel_addon_type
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
                        if(response.error==1)
                        {
                            alert(response.msg);
                            self.prop('checked', false);
                        }

                    }

                });

            });
            $element.find('input[name="hotel_addon_type"]').change(function(){
                var virtuemart_hotel_id=$element.find('select[name="virtuemart_hotel_id"]').val();
                var vail_from=$element.find('input[name="vail_from"]').val();
                var vail_to=$element.find('input[name="vail_to"]').val();
                if(virtuemart_hotel_id==0||virtuemart_hotel_id==""||typeof virtuemart_hotel_id=="undefined")
                {
                    alert('please select hotel');
                    $(this).prop('checked', false);
                    return;
                }else if(vail_from==0||vail_from==""||typeof vail_from=="undefined"){

                    alert('please select vail from date');
                    $(this).prop('checked', false);
                    return;
                }else if(vail_to==0||vail_to==""||typeof vail_to=="undefined"){
                    alert('please select vail to date');
                    $(this).prop('checked', false);
                    return;
                }
                var hotel_addon_type=$(this).val();
                $.ajax({
                    type: "GET",
                    url: 'index.php?option=com_tsmart&controller=coupon&task=get_tour_avail_by_hotel_id_first_itinerary&virtuemart_hotel_id='+virtuemart_hotel_id,
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            hotel_addon_type:hotel_addon_type
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        $('.div-loading').css({
                            display: "block"
                        });
                    },
                    success: function (response) {
                        $element.find('input[name="list_tour_id[]"]').prop('disabled', true).hide().closest('.checkbox').hide();
                        $element.find('input[name="list_tour_id[]"]').prop('checked', false);

                        $.each(response,function(index,virtuemart_product_id){
                            $element.find('input[name="list_tour_id[]"][value="'+virtuemart_product_id+'"]').prop('disabled', false).show().closest('.checkbox').show();
                        });
                        $('textarea[name="hotel_overview"]').html( plugin.settings.hotel.description);


                        $('.div-loading').css({
                            display: "none"


                        });

                    }

                });

            });


        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_coupon_edit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_coupon_edit')) {
                var plugin = new $.view_coupon_edit(this, options);
                $(this).data('view_coupon_edit', plugin);

            }

        });

    }

})(jQuery);


