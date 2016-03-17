(function ($) {

    // here we go!
    $.view_hoteladdon_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            task:''
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
            $element.find('input[name="list_tour_id[]"]').prop('disabled', true).hide().closest('.checkbox').hide();
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
                    url: 'index.php?option=com_virtuemart&controller=hoteladdon&task=get_detail_hotel&virtuemart_hotel_id='+virtuemart_hotel_id,
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
                        $element.find('input[name="location"]').val(response.city_area_name);
                    }

                });




            });
            $element.find('input[name="hotel_addon_type"]').change(function(){
                var virtuemart_hotel_id=$element.find('select[name="virtuemart_hotel_id"]').val();
                if(virtuemart_hotel_id==0||virtuemart_hotel_id==""||typeof virtuemart_hotel_id=="undefined")
                {
                    alert('please select hotel');
                    $(this).prop('checked', false);
                    return;
                }
                var hotel_addon_type=$(this).val();
                $.ajax({
                    type: "GET",
                    url: 'index.php?option=com_virtuemart&controller=hoteladdon&task=get_tour_avail_by_hotel_id_first_itinerary&virtuemart_hotel_id='+virtuemart_hotel_id,
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
                        console.log(response);
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
    $.fn.view_hoteladdon_edit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_hoteladdon_edit')) {
                var plugin = new $.view_hoteladdon_edit(this, options);
                $(this).data('view_hoteladdon_edit', plugin);

            }

        });

    }

})(jQuery);


