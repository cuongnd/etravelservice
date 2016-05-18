(function ($) {

    // here we go!
    $.view_promotion_default = function (element, options) {

        // plugin's default options
        var defaults = {
            totalPages: 0,
            totalItem: 10,
            tour_id: 0,
            visiblePages: 5,
            dialog_class: 'dialog-form-price',
            price_type: '',
            list_service_class: [],
            date_format: 'm/d/y',
            list_base_price_by_service_class_id_and_tour_id: [],
            display_format: 'DD-MM-YYYY',
            promotion_price:{
                virtuemart_service_class_id:0,
                virtuemart_price_id:'',
                sale_period_from:new Date(),
                sale_period_to:new Date()
            }

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}
        var $promotion_price_form = $('#price-form');
        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.validate = function () {
            var virtuemart_product_id = $('select[name="virtuemart_product_id"]').val();
            if (virtuemart_product_id <= 0) {
                alert('please select tour');
                return false;
            }

            var virtuemart_service_class_id = $('select[name="virtuemart_service_class_id"]').val();
            if (virtuemart_service_class_id <= 0) {
                alert('please select tour class');
                return false;
            }
            var virtuemart_price_id = $('select[name="virtuemart_price_id"]').val();
            if (virtuemart_price_id <= 0) {
                alert('please select range of date');
                return false;
            }

            var sale_period_from = $('input[name="sale_period_from"]').val();
            if (sale_period_from.trim() == '') {
                alert('please select tour sale period from');
                return false;
            }

            var sale_period_to = $('input[name="sale_period_to"]').val();
            if (sale_period_to.trim() == '') {
                alert('please select tour sale period to');
                return false;
            }
            return true;
        }
        plugin.setup_random_price = function () {
            //random price
            $promotion_price_form.find('.random-price').click(function () {
                $promotion_price_form.find('.base-price .inputbox.number').each(function () {
                    $(this).val(Math.floor(Math.random() * 600) + 1).trigger('change');
                });
            });

            //end random price
            //random promotion price
            $promotion_price_form.find('.random-promotion').click(function () {
                var type = ['amount', 'percent'];
                var random_type = Math.floor(Math.random() * (1 - 0 + 1)) + 0;
                $promotion_price_form.find('.mark-up-promotion-price  .inputbox.number').val('');
                $promotion_price_form.find('.mark-up-promotion-price .' + type[random_type] + ' .inputbox.number').each(function () {

                    if (random_type == 0) {
                        $(this).val(Math.floor(Math.random() * 200) + 1).trigger('change');
                    } else {
                        $(this).val(Math.floor(Math.random() * 99) + 1).trigger('change');
                    }

                });
            });
            //end random promotion price
            //random markup
            $promotion_price_form.find('.random-markup').click(function () {

                var type = ['amount', 'percent'];
                var random_type = Math.floor(Math.random() * (1 - 0 + 1)) + 0;
                $('.mark-up-price  .inputbox.number').val('');
                $('.mark-up-price .' + type[random_type] + ' .inputbox.number').each(function () {
                    if (random_type == 0) {
                        $(this).val(Math.floor(Math.random() * 200) + 1).trigger('change');
                    } else {
                        $(this).val(Math.floor(Math.random() * 99) + 1).trigger('change');
                    }

                });
            });
            //end ramdom markup
        };
        plugin.update_select_range_of_date = function (list_range_of_date) {
            var virtuemart_price_id=plugin.settings.promotion_price.virtuemart_price_id;
            var display_format = plugin.settings.display_format;
            $promotion_price_form.find('#virtuemart_price_id').empty();
            var $option = '<option value="0">Please select range of date</option>';
            $promotion_price_form.find('#virtuemart_price_id').append($option);
            $.each(list_range_of_date, function (index, item) {
                var sale_period_from = new Date(item.sale_period_from);
                sale_period_from = moment(sale_period_from).format(display_format);
                var sale_period_to = new Date(item.sale_period_to);
                sale_period_to = moment(sale_period_to).format(display_format);

                var $option = '<option  '+(item.virtuemart_price_id==virtuemart_price_id?' selected ':'')+' value="' + item.virtuemart_price_id + '">' + sale_period_from + ' -> ' + sale_period_to + '</option>';
                $promotion_price_form.find('#virtuemart_price_id').append($option);
            });
            $promotion_price_form.find('#virtuemart_price_id').trigger('change');
        };
        plugin.update_service_class = function (list_service_class) {
            $promotion_price_form.find('#virtuemart_service_class_id').empty();
            var promotion_price=plugin.settings.promotion_price;
            var $option = '<option value="0">Please select service class</option>';
            $promotion_price_form.find('#virtuemart_service_class_id').append($option);
            $.each(list_service_class, function (index, item_service_class) {
                var $option = '<option  '+(item_service_class.virtuemart_service_class_id==promotion_price.virtuemart_service_class_id?' selected ':'') +' value="' + item_service_class.virtuemart_service_class_id + '">' + item_service_class.service_class_name + '</option>';
                $promotion_price_form.find('#virtuemart_service_class_id').append($option);
            });
            $promotion_price_form.find('#virtuemart_service_class_id').trigger('change');
        };
        plugin.update_value_calendar = function () {
            var promotion_price=plugin.settings.promotion_price;
            var display_format = plugin.settings.display_format;
            var range_of_date = $promotion_price_form.find('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
            range_of_date.set_date(promotion_price.sale_period_from,promotion_price.sale_period_to);
            range_of_date.instant_daterangepicker.updateView();
            range_of_date.instant_daterangepicker.updateCalendars();


        };
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            var totalItem = plugin.settings.totalItem;
            var tour_id = plugin.settings.tour_id;
            var price_type = plugin.settings.price_type;
            /*
             $element.find('#ul_pagination').twbsPagination({
             totalPages: plugin.settings.totalPages,
             visiblePages: plugin.settings.visiblePages,
             onPageClick: function (event, page) {
             $('#page-content').text('Page ' + page);
             }
             });*/


            $("#price-form").dialog({
                autoOpen: false,
                modal: true,
                width: 700,
                appendTo: 'body',
                dialogClass: plugin.settings.dialog_class,
                //closeOnEscape: false,
                //open: function(event, ui) { $element.find(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

            });


            $element.find('.edit-price').click(function () {
                var self = $(this);
                var $row = self.closest('tr[role="row"]');
                $row.toggleClass('focus');
                var virtuemart_promotion_price_id = $row.data('virtuemart_promotion_price_id');
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'promotion',
                            task: 'get_list_promotion_price',
                            virtuemart_promotion_price_id: virtuemart_promotion_price_id
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
                        $element.find('.' + plugin.settings.dialog_class).find('input.number').val(0);
                        plugin.fill_data(response);
                        $element.find('input[name="virtuemart_promotion_price_id"]').val(virtuemart_promotion_price_id);
                        $element.find('#price_type').val(response.tour.price_type);
                        $("#price-form").dialog("open");



                        plugin.settings.list_service_class=response.list_service_class;
                        plugin.settings.tour= response.tour;
                        plugin.settings.promotion_price= response.promotion_price;
                        var virtuemart_product_id = response.tour.virtuemart_product_id;
                        plugin.update_service_class(response.list_service_class);
                        plugin.update_value_calendar();
                        $promotion_price_form.find("#virtuemart_product_id").val(virtuemart_product_id);
                        $promotion_price_form.find("#virtuemart_product_id").trigger("liszt:updated");
                        $promotion_price_form.find('#template_price').html(response.price_content);


                        $promotion_price_form.find('input.number').change(function (e) {
                            var value = $(this).val();
                            if (typeof value == 'undefined' || value == '') {
                                $(this).val(0);
                                console.log(value);
                                plugin.updata_price();
                            }

                        });
                        plugin.updata_price(response);
                        plugin.setup_calculator_promotion_price();
                        plugin.setup_random_price();
                    }
                });


            });
            $element.find('.delete-price').click(function () {

                if (confirm('Are you sure you want delete this item ?')) {
                    var self = $(this);
                    var $row = self.closest('tr[role="row"]');
                    var price_id = $row.data('price_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'promotion',
                                task: 'ajax_remove',
                                price_id: price_id,
                                tour_id: tour_id,
                                price_type: price_type,
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
                            if (response == 1) {
                                alert('delete item succesfull');
                                $row.remove();
                            }
                            else {
                                alert(response);
                            }
                        }
                    });

                } else {
                    return;
                }


            });

            $element.find('.publish-price').click(function () {

                if (confirm('Are you sure you want publish this item ?')) {
                    var self = $(this);
                    var $row = self.closest('tr[role="row"]');
                    var virtuemart_promotion_price_id = $row.data('virtuemart_promotion_price_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'promotion',
                                task: 'ajax_publish',
                                price_id: virtuemart_promotion_price_id
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
                            if (response == 1) {
                                alert('publish item succesfull');
                                $row.find('a.publish-price span.icon-white').toggleClass("icon-publish icon-unpublish");
                            }
                            else {
                                alert(response);
                            }
                        }
                    });

                } else {
                    return;
                }


            });
            plugin.setup_random_price();

            $('.dialog-form-price').find('.save-close-price').click(function () {
                if (!plugin.validate()) {
                    return false;
                }

                var dataPost = $('.dialog-form-price').find(':input').serializeObject();
                var price_id = dataPost.virtuemart_promotion_price_id;
                var $row = $element.find('.list-prices tr[data-price_id="' + price_id + '"]');
                if (confirm('Are you sure you want save this item ?')) {
                    //save and close
                    $.ajax({
                        type: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        url: 'index.php?option=com_virtuemart&controller=promotion&task=ajax_save_promotion_price',
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
                            if (response.e == 0) {
                                var result = response.r;


                                alert('save item succesfull');
                                $element.find("#price-form").dialog("close");
                                if ($row.length == 0) {
                                    $row = $element.find('.list-prices tbody tr:first').clone(true).prependTo(".list-prices tbody").fadeIn('slow');
                                }
                                $row.attr('data-price_id', result.virtuemart_price_id);
                                $row.data('price_id', result.virtuemart_price_id);
                                $row.find('span.item-id').html(result.virtuemart_price_id);
                                $row.find('input[name="row_price_id[]"]').val(result.virtuemart_price_id);
                                $row.find('td.service_class_name').html(result.service_class.service_class_name);
                                $row.find('td.sale_period').html(result.sale_period);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.price_note').html(result.price_note.trim());
                                $row.find('a.publish-price .icon-white').removeClass('icon-unpublish').addClass('icon-publish');

                                $row.delay(500).queue(function () {
                                    $(this).toggleClass('focus');
                                    $(this).dequeue();
                                });

                            }
                            else {
                                alert(response.m);
                            }


                        }
                    });


                } else {
                    return;
                }
            });
            $element.find('.dialog-form-price').find('.cancel-price').click(function () {
                if (confirm('Are you sure you want cancel this item ?')) {
                    //save and close
                    var price_id = $element.find('input[name="virtuemart_price_id"]').val();
                    $element.find("#price-form").dialog("close");
                    var $row = $element.find('.list-prices tr[data-price_id="' + price_id + '"]');
                    $row.delay(500).queue(function () {
                        $(this).toggleClass('focus');
                        $(this).dequeue();
                    });
                } else {
                    return;
                }
            });
            $element.find('.dialog-form-price').find('.apply-price').click(function () {
                var dataPost = $element.find('.dialog-form-price').find(':input').serializeObject();
                if (confirm('Are you sure you want save this item ?')) {
                    var promotion_price_id = dataPost.virtuemart_promotion_price_id;
                    var $row = $element.find('.list-prices tr[data-price_id="' + promotion_price_id + '"]');
                    //save and close
                    $.ajax({
                        type: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        url: 'index.php?option=com_virtuemart&controller=promotion&task=ajax_save_promotion_price',
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
                            if (response.e == 0) {
                                var result = response.r;
                                alert('save item succesfull');
                                if ($row.length == 0) {
                                    $row = $element.find('.list-prices tbody tr:first').clone(true).prependTo(".list-prices tbody").fadeIn('slow');
                                }
                                $row.attr('data-price_id', result.virtuemart_price_id);
                                $row.data('price_id', result.virtuemart_price_id);
                                $row.find('span.item-id').html(result.virtuemart_price_id);
                                $row.find('input[name="row_price_id[]"]').val(result.virtuemart_price_id);
                                $row.find('td.service_class_name').html(result.service_class.service_class_name);
                                $row.find('td.sale_period').html(result.sale_period);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.modified_on').html(result.modified_on);
                                $row.find('td.price_note').html(result.promotion_price_note.trim());
                                $row.find('a.publish-price .icon-white').removeClass('icon-unpublish').addClass('icon-publish');

                                $row.delay(500).queue(function () {
                                    $(this).toggleClass('focus');
                                    $(this).dequeue();
                                });

                            }
                            else {
                                alert(response.m);
                            }


                        }
                    });


                } else {
                    return;
                }
            });
            Joomla.submitbutton = function (task) {
                if (task == "add") {
                    var virtuemart_product_id = $element.find('input[name="virtuemart_product_id"]').val();
                    if(virtuemart_product_id==0){
                        $("#price-form").dialog("open");
                        return false;
                    }
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'promotion',
                                task: 'get_list_promotion_price',
                                tour_id: virtuemart_product_id
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
                            $("#price-form").dialog("open");
                            plugin.update_service_class()
                            $("#price-form").find('#virtuemart_service_class_id option').css({
                                display: "none"
                            });
                            response.virtuemart_service_class_ids.push(0);
                            $.each(response.virtuemart_service_class_ids, function (index, value) {
                                $("#price-form").find('#virtuemart_service_class_id option[value="' + value + '"]').css({
                                    display: "block"
                                });
                            });
                            $element.find("#virtuemart_product_id").val(virtuemart_product_id);
                            $element.find("#virtuemart_product_id").trigger("change");
                            $element.find("#virtuemart_service_class_id").trigger("change");
                            $element.find('#template_price').html(response.price_content);

                            $("#price-form").find('input.number').change(function (e) {
                                var value = $(this).val();
                                if (typeof value == 'undefined' || value == '') {
                                    $(this).val(0);
                                    console.log(value);
                                    plugin.updata_price();
                                }

                            });
                            plugin.updata_price(response);
                        }
                    });


                }
            };
            Joomla.delete_item = function (task, item_id) {
                if (confirm('Are you sure you want delete this item ?')) {
                    form = document.getElementById('adminForm');
                    $element.find('input[name="cid[]"]').val(item_id);
                    Joomla.submitform(task);
                } else {
                    return;
                }


            };
            $promotion_price_form.find('#virtuemart_service_class_id').change(function () {
                var virtuemart_service_class_id = $(this).val();
                if (virtuemart_service_class_id == 0) {
                    return;
                }
                var virtuemart_product_id = $promotion_price_form.find('select[name="virtuemart_product_id"]').val();
                if (virtuemart_product_id == 0) {
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'promotion',
                            task: 'ajax_get_list_base_price_by_service_class_id_and_tour_id',
                            virtuemart_product_id: virtuemart_product_id,
                            virtuemart_service_class_id: virtuemart_service_class_id

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
                        plugin.settings.list_base_price_by_service_class_id_and_tour_id = response;
                        var list_price = plugin.settings.list_base_price_by_service_class_id_and_tour_id;

                        console.log(list_price);
                        plugin.update_select_range_of_date(list_price);
                    }
                });


            });
            $promotion_price_form.find('#virtuemart_price_id').change(function () {
                var virtuemart_price_id = $(this).val();
                if (virtuemart_price_id > 0) {
                    var list_price = plugin.settings.list_base_price_by_service_class_id_and_tour_id;
                    var range_of_date = $promotion_price_form.find('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
                    range_of_date.clear();
                    for (var i = 0; i < list_price.length; i++) {
                        var item = list_price[i];
                        if (item.virtuemart_price_id == virtuemart_price_id) {

                            var sale_period_from = new Date(item.sale_period_from);
                            var sale_period_to = new Date(item.sale_period_to);
                            range_of_date.instant_daterangepicker.minDate = moment(sale_period_from);
                            range_of_date.instant_daterangepicker.maxDate = moment(sale_period_to);

                            range_of_date.instant_daterangepicker.startDate = moment(sale_period_from);
                            range_of_date.instant_daterangepicker.endDate = moment(sale_period_to);

                            range_of_date.instant_daterangepicker.updateView();
                            range_of_date.instant_daterangepicker.updateCalendars();
                            display_format = plugin.settings.display_format;
                            console.log(range_of_date.instant_daterangepicker.minDate.format(display_format));
                            break;
                        }
                    }
                    range_of_date.focus();
                }

            });
            $("#price-form").find('#virtuemart_product_id').change(function () {
                var virtuemart_product_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'promotion',
                            task: 'ajax_get_change_tour',
                            virtuemart_product_id: virtuemart_product_id

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
                        $promotion_price_form.find('#virtuemart_service_class_id option').remove();
                        var list_service_class = plugin.settings.list_service_class;

                        $promotion_price_form.find('#price_type').val(response.tour.price_type);
                        response.virtuemart_service_class_ids.push(0);
                        var virtuemart_service_class_ids = response.virtuemart_service_class_ids;
                        var $option = '<option value="0">Please select tour class</option>';
                        $promotion_price_form.find('#virtuemart_service_class_id').append($option);
                        $.each(response.virtuemart_service_class_ids, function (index, virtuemart_service_class_id) {
                            $.each(list_service_class, function (index, item_service_class) {
                                if (item_service_class.virtuemart_service_class_id == virtuemart_service_class_id) {
                                    var $option = '<option value="' + virtuemart_service_class_id + '">' + item_service_class.service_class_name + '</option>';
                                    $promotion_price_form.find('#virtuemart_service_class_id').append($option);
                                }
                            });
                        });

                        $promotion_price_form.find("#virtuemart_service_class_id").val(0);
                        $promotion_price_form.find("#virtuemart_service_class_id").trigger("change");
                        $promotion_price_form.find('#template_price').html(response.price_content);
                        plugin.updata_price(response);
                        plugin.setup_calculator_promotion_price();
                        plugin.setup_random_price();


                    }
                });

            });


            $element.find('.' + plugin.settings.dialog_class).find('input.number').change(function (e) {
                var value = $(this).val();
                if (typeof value == 'undefined' || value == '') {
                    $(this).val(0);
                    console.log(value);
                    plugin.updata_price();
                }

            });
            plugin.setup_calculator_promotion_price();


        }
        plugin.setup_calculator_promotion_price = function () {
            console.log('setup_calculator_promotion_price');
            $promotion_price_form.find('input.number').keypress(function (e) {
                if (e.which == 13) {

                    plugin.updata_price();
                }
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message

                    return false;
                }
                else if (e.which == 13) {
                    plugin.updata_price();

                }

            });
            $promotion_price_form.find('input.number').keyup(function (e) {
                if (e.which == 13) {

                    plugin.updata_price();
                }
                //if the letter is not digit then display error and don't type anything
                /*if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                 //display error message

                 return false;
                 }
                 else{
                 var total=0;
                 $element.find('table.mark-up-price tr.percent input').each(function(){

                 });
                 console.log('state update');



                 }*/


            });
            $promotion_price_form.find('input.number').change(function (e) {
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    //display error message

                    return false;
                }
                else {
                    plugin.updata_price();

                }

            });

            $promotion_price_form.find('table.mark-up-price tr.percent input').focusin(function (e) {
                total = 0;
                $element.find('table.mark-up-price tr.amount input').each(function () {
                    total += $(this).val();
                });
                if (total > 0) {
                    alert('you cannot input percent when amount is <> 0');
                }
                return false;
            });
            $promotion_price_form.find('table.mark-up-price tr.amount input').focusin(function (e) {
                total = 0;
                $element.find('table.mark-up-price tr.percent input').each(function () {
                    total += $(this).val();
                });
                if (total > 0) {
                    alert('you cannot input amount when percent is <> 0');
                }
                return false;
            });

            //validate promotion
            $promotion_price_form.find('table.promotion-price tr.percent input').focusin(function (e) {
                total = 0;
                $element.find('table.promotion-price tr.amount input').each(function () {
                    total += $(this).val();
                });
                if (total > 0) {
                    alert('you cannot input percent when amount is <> 0');
                }
                return false;
            });
            $promotion_price_form.find('table.promotion-price tr.amount input').focusin(function (e) {
                total = 0;
                $element.find('table.promotion-price tr.percent input').each(function () {
                    total += $(this).val();
                });
                if (total > 0) {
                    alert('you cannot input amount when percent is <> 0');
                }
                return false;
            });
        }
        plugin.fill_data = function (result) {
            var date_format = plugin.settings.date_format;
            var list_tour_promotion_price_by_tour_promotion_price_id = result.list_tour_promotion_price_by_tour_promotion_price_id;
            if (typeof list_tour_promotion_price_by_tour_promotion_price_id == "undefined") {
                list_tour_promotion_price_by_tour_promotion_price_id = result.tour_private_price_by_tour_promotion_price_id;

            }
            $promotion_price_form.find('input[name="tax"]').val(result.promotion_price.tax);
            plugin.settings.price_type=result.tour.price_type
            $promotion_price_form.find('input[name="virtuemart_promotion_price_id"]').val(result.promotion_price.virtuemart_promotion_price_id);
            $promotion_price_form.find('textarea[name="price_note"]').val(result.promotion_price.price_note.trim());


            var range_of_date= $('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');

            if(typeof range_of_date!="undefined") {

                range_of_date.set_date(result.promotion_price.sale_period_from,result.promotion_price.sale_period_to);
            }





            $promotion_price_form.find('select[name="service_class_id"]').val(result.promotion_price.virtuemart_service_class_id);
            $promotion_price_form.find('select[name="service_class_id"]').trigger("liszt:updated.chosen");
            var price_type = result.tour.price_type;
            if (price_type == 'tour_group') {
                $element.find('#tour_group').css({
                    display: "block"
                });
                $element.find('#tour_basic').css({
                    display: "none"
                });
                $.each(list_tour_promotion_price_by_tour_promotion_price_id, function (index, item) {
                    var $row = $promotion_price_form.find('table.base-price tr[data-group_size_id="' + item.virtuemart_group_size_id + '"]');
                    $row.find('td input[column-type="senior"]').val(item.price_senior);
                    $row.find('td input[column-type="adult"]').val(item.price_adult);
                    $row.find('td input[column-type="teen"]').val(item.price_teen);
                    $row.find('td input[column-type="children1"]').val(item.price_children1);
                    $row.find('td input[column-type="children2"]').val(item.price_children2);
                    $row.find('td input[column-type="infant"]').val(item.price_infant);
                    $row.find('td input[column-type="private_room"]').val(item.price_private_room);
                    $row.find('td input[column-type="extra_bed"]').val(item.price_extra_bed);

                });

            } else if (typeof list_tour_promotion_price_by_tour_promotion_price_id != "undefined") {
                $promotion_price_form.find('#tour_group').css({
                    display: "none"
                });
                $promotion_price_form.find('#tour_basic').css({
                    display: "block"
                });
                console.log(list_tour_promotion_price_by_tour_promotion_price_id);
                var $row = $element.find('table.base-price  tbody tr');
                $row.find('td input[column-type="senior"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_senior);
                $row.find('td input[column-type="adult"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_adult);
                $row.find('td input[column-type="teen"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_teen);
                $row.find('td input[column-type="children1"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_children1);
                $row.find('td input[column-type="children2"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_children2);
                $row.find('td input[column-type="infant"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_infant);
                $row.find('td input[column-type="private_room"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_private_room);
                $row.find('td input[column-type="extra_bed"]').val(list_tour_promotion_price_by_tour_promotion_price_id.price_extra_bed);
            }


            var list_promotion_mark_up = result.list_promotion_mark_up;
            $.each(list_promotion_mark_up, function (type, item) {
                var $row = $element.find('table.mark-up-price tr.' + type);
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
        plugin.updata_price = function updata_price(respone) {
            var price_type = $promotion_price_form.find('#price_type').val();
            var virtuemart_product_id = $promotion_price_form.find('input[name="virtuemart_product_id"]').val();
            var $promotion_price_tr_first = $promotion_price_form.find('table.base-price tbody tr');
            var tax = $promotion_price_form.find('input[name="tax"]').val();
            tax = parseFloat(tax);
            $promotion_price_tr_first.find('input').each(function () {
                var $self = $(this);
                var group_id = $self.attr('group-id');
                var column_type = $self.attr('column-type');
                var price = $self.val();

                price = parseFloat(price);
                //tính giá sau khi promotion
                var promotion_amount = $promotion_price_form.find('table.promotion-price tr.amount input[column-type="' + column_type + '"]').val();
                if (typeof promotion_amount == "undefined") {

                    promotion_amount = 0;
                }

                promotion_amount = parseFloat(promotion_amount);

                var promotion_percent = $promotion_price_form.find('table.promotion-price tr.percent input[column-type="' + column_type + '"]').val();
                if (typeof promotion_percent == "undefined") {
                    promotion_percent = 0;
                }
                var profit_promotion_price = 0;
                if (promotion_percent > 0) {
                    profit_promotion_price = price - (price * promotion_percent) / 100;
                } else {
                    profit_promotion_price = price - promotion_amount;
                }


                var amount = $promotion_price_form.find('table.mark-up-price tr.amount input[column-type="' + column_type + '"]').val();
                if (typeof amount == "undefined") {

                    amount = 0;
                }
                amount = parseFloat(amount);
                var percent = $promotion_price_form.find('table.mark-up-price tr.percent input[column-type="' + column_type + '"]').val();
                if (typeof percent == "undefined") {
                    percent = 0;
                }
                percent = parseFloat(percent);
                if (price_type == plugin.settings.price_type) {
                    var $span_profit = $promotion_price_form.find('table.profit-price span[group-id="' + group_id + '"][column-type="' + column_type + '"]');
                } else {
                    var $span_profit = $promotion_price_form.find('table.profit-price span[column-type="' + column_type + '"]');
                }

                var profit_price = 0;
                if (percent > 0) {
                    profit_price = (profit_promotion_price * percent) / 100;
                } else {
                    profit_price = amount;
                }
                profit_price = Math.round(profit_price);
                //profit_price=Math.round(profit_price, 2);
                $span_profit.html(profit_price);
                if (price_type == 'tour_group') {
                    var $span_sale = $promotion_price_form.find('table.sale-price span[group-id="' + group_id + '"][column-type="' + column_type + '"]');
                } else {
                    var $span_sale = $promotion_price_form.find('table.sale-price span[column-type="' + column_type + '"]');
                }
                //tính giá bán sau thuế
                //=s? ti?n c� l�i+s? ti?n g?c(?� bao g?m promotion)+(s? ti?n c� l�i+s? ti?n g?c(?� bao g?m promotion))*ph?n tr?m thu?
                var sale_price = profit_price + profit_promotion_price + ((profit_price + profit_promotion_price) * tax) / 100;
                sale_price = Math.round(sale_price);
                //sale_price=Math.round(profit_price, 2);
                $span_sale.html(sale_price);


            });
        }
        plugin.set_disable_amount = function () {
            var total = 0;
            $element.find('table.mark-up-price tr.percent input').each(function () {
                total += $(this).val();
            });
            if (total > 0) {
                alert('you cannot ')
                $element.find('table.mark-up-price tr.amount input').val(0).prop('disabled', true);
            } else {
                $element.find('table.mark-up-price tr.amount input').prop('disabled', false);
            }
        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_promotion_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_promotion_default')) {
                var plugin = new $.view_promotion_default(this, options);
                $(this).data('view_promotion_default', plugin);

            }

        });

    }

})(jQuery);


