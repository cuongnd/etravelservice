(function ($) {

    // here we go!
    $.view_discount_edit = function (element, options) {

        // plugin's default options
        var defaults = {
            model_price:'',
            tsmart_product_id:0
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
            var tsmart_product_id=plugin.settings.tsmart_product_id;
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
            var $select_table_tour_list_tour=$('#select_table_tour_tsmart_product_id').data('select_table_tour');
            $select_table_tour_list_tour.trigger('change',function(tsmart_product_id){
                var model_price=$element.find('select#model_price').val();
                if(model_price=='flat_price')
                {
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'departure',
                                task: 'ajax_get_list_departure_date_by_tour_id',
                                tsmart_product_id:tsmart_product_id
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
                            var select_table_departure_date=$('#select_table_departure_date_list_departure_date').data('select_table_departure_date');

                            select_table_departure_date.filter_by_list_departure_id(response);
                            $('#select_table_departure_date_list_departure_date').find('.tr-row:odd').css({
                                'background-color':''
                            });
                            $('#select_table_departure_date_list_departure_date').find('.tr-row:visible:odd').css({
                                'background-color':'#f9f9f9'
                            });


                        }
                    });
                }else{
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'serviceclass',
                                task: 'get_service_class_id_by_product_id',
                                tsmart_product_id:tsmart_product_id
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
                            var select_table_service_class=$('#select_table_service_class_list_service_class').data('select_table_service_class');

                            select_table_service_class.filter_by_list_service_class_id(response);
                            $('#select_table_service_class_list_service_class').find('.tr-row:odd').css({
                                'background-color':''
                            });
                            $('#select_table_service_class_list_service_class').find('.tr-row:visible:odd').css({
                                'background-color':'#f9f9f9'
                            });
                            select_table_service_class.un_check_all();

                        }
                    });

                }

            });
            $element.find('select#model_price').change(function(){
                var model_price=$(this).val();
                var select_table_departure_date=$('#select_table_departure_date_list_departure_date').data('select_table_departure_date');
                console.log(model_price);
                if(model_price=='flat_price')
                {
                    $('.form-select-departure-date').show();
                    $('.form-select-service-class').hide();
                }else{
                    $('.form-select-departure-date').hide();
                    $('.form-select-service-class').show();
                }
                var select_table_tour_list_tour=$('#select_table_tour_tsmart_product_id').data('select_table_tour');

                select_table_tour_list_tour.filter({
                    price_type:model_price
                });
                $('#select_table_tour_tsmart_product_id').find('.tr-row:odd').css({
                    'background-color':''
                });
                $('#select_table_tour_tsmart_product_id').find('.tr-row:visible:odd').css({
                    'background-color':'#f9f9f9'
                });


            });
            var model_price=plugin.settings.model_price;
            $element.find('select#model_price').val(model_price).trigger("change");
            $('#select_table_tour_tsmart_product_id').find('.body .tr-row .input-application[value="'+tsmart_product_id+'"]').prop('checked',true);

        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_discount_edit = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_discount_edit')) {
                var plugin = new $.view_discount_edit(this, options);
                $(this).data('view_discount_edit', plugin);

            }

        });

    }

})(jQuery);


