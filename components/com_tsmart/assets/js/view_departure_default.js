(function ($) {

    // here we go!
    $.view_departure_default = function (element, options) {
        // plugin's default options
        var defaults = {

            list_date: [],
            departure_item:{
                weekly:"",
                allow_passenger:"",
                days_seleted:''
            },
            is_load_ajax_get_departure:0


        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var $dialog_departure_edit_form=$element.find(".departure-edit-form");
        plugin.add_new_departure = function () {
            var self = $(this);
            var departure_id = 0;
            var tour_id = 0;
            $(".departure-edit-form").dialog("open");
            $('.' + plugin.settings.dialog_class).find('input.number').val(0);
            $('#tsmart_departure_id').val(departure_id);
        };
        plugin.update_select_service_class = function () {
            var departure_item=plugin.settings.departure_item;
            var list_service_class=plugin.settings.list_tour;
            var promotion_price=plugin.settings.promotion_price;
            $dialog_departure_edit_form.find('#tsmart_service_class_id').empty();
            var $option = '<option value="0">Please select service class</option>';
            $dialog_departure_edit_form.find('#tsmart_service_class_id').append($option);
            $.each(list_service_class, function (index, item_service_class) {
                var $option = '<option  '+(item_service_class.tsmart_language_id==departure_item.tsmart_language_id?' selected ':'') +' value="' + item_service_class.tsmart_language_id + '">' + item_service_class.service_class_name + '</option>';
                $dialog_departure_edit_form.find('#tsmart_service_class_id').append($option);
            });
            $dialog_departure_edit_form.find('#tsmart_service_class_id').trigger('change');



        };
        plugin.update_layout_departure = function () {
            departure_item=plugin.settings.departure_item;
            if(departure_item.tsmart_departure_parent_id>0)
            {
                $('.range-of-date').hide();
                $('.area-select-date').hide();

            }else{
                $('.range-of-date').show();
                $('.area-select-date').show();
            }
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('span.price').autoNumeric('init',{
                mDec:0,
                aSep:' ',
                aSign:'US$'
            });

            var list_date = plugin.settings.list_date;
            $.validator.addMethod('selectcheck', function (value) {
                return (value != '0');
            }, "This field is required");

            $.validator.addMethod("greaterThan",

                function (value, element, param) {
                    var $min = $(param);
                    if (this.settings.onfocusout) {
                        $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
                            $(element).valid();
                        });
                    }
                    return parseInt(value) > parseInt($min.val());
                }, "input must be greater than {0}");

            $.validator.addMethod("less_than",

                function (value, element, param) {
                    var $max = $(param);
                    if (this.settings.onfocusout) {
                        $max.off(".validate-lessThan").on("blur.validate-lessThan", function () {
                            $(element).valid();
                        });
                    }
                    return parseInt(value) < parseInt($max.val());
                }, "input must be less than {0}");


            var admin_form_edit_validate = $('#adminFormEdit').validate({
                ignore: [], // <-- validate hidden elements
                // other options, rules, and callbacks,

                onkeyup: false,
                errorClass: "myErrorClass",

                //put error message behind each form element
                errorPlacement: function (error, element) {
                    var elem = $(element);
                    error.insertBefore(element);
                    $.notify({
                        // options
                        message: error.text()
                    }, {
                        // settings
                        type: 'error'
                    });

                },

                //When there is an error normally you just add the class to the element.
                // But in the case of select2s you must add it to a UL to make it visible.
                // The select element, which would otherwise get the class, is hidden from
                // view.
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-offscreen")) {
                        $("#s2id_" + elem.attr("id") + " ul").addClass(errorClass);
                    } else {
                        elem.addClass(errorClass);
                    }
                },

                //When removing make the same adjustments as when adding
                unhighlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-offscreen")) {
                        $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
                    } else {
                        elem.removeClass(errorClass);
                    }
                },
                rules: {
                    'weekly[]': {
                        required: true
                    },
                    'sale_period_from': {
                        required: true
                    },
                    'sale_period_to': {
                        required: true
                    }

                },
                messages: {
                    tour_id: "please select tour",
                    tour_service_class_id: "please select tour class",
                    departure_name: "please input departure name",
                    daterange_vail_period_from_to: "please select date period range",
                    'weekly[]':"please select weekly"
                }
            });
            $('#date_type').toggleize();
            $('#date_type').change(function () {
                var data_type = $(this).val();
                if (data_type == 'day_select') {
                    $("#days_seleted").rules("add", {
                        required: true
                    });
                    $('input[name="weekly[]"]').prop("checked");
                    $('input[name="weekly[]"]').prop("disabled", true);
                    $('input[name="weekly[]"]').rules("add", {
                        required: false
                    });
                    $dialog_departure_edit_form.find('#multi-calendar-departure').show();
                    $dialog_departure_edit_form.find('input[name="weekly[]"]').prop('checked', false);
                } else {
                    $("#days_seleted").rules("add", {
                        required: false
                    });
                    $('input[name="weekly[]"]').prop("checked");
                    $('input[name="weekly[]"]').prop("disabled", false);
                    $('input[name="weekly[]"]').rules("add", {
                        required: true
                    });
                    $dialog_departure_edit_form.find('#multi-calendar-departure').DatePickerClear();
                    $dialog_departure_edit_form.find('#multi-calendar-departure').hide();
                }
                admin_form_edit_validate.element('input[name="weekly[]"]');
                admin_form_edit_validate.element('#days_seleted');

            });
            var max_date=new Date();
            max_date.setYear((new Date()).getYear() + 1);
            var multi_calendar_departure = $('#multi-calendar-departure').multi_calendar_date_picker({
                mode: 'multiple',
                inline: true,
                calendars: 2,
                date: list_date,
                onChange: function (dates, el) {
                    var list_date = [];
                    $.each(dates, function (index, date) {
                        list_date.push($.format.date(date, "yyyy-MM-dd"));
                    });
                    if (list_date.length > 0) {
                        $('#days_seleted').val(cassandraMAP.stringify(list_date));
                    } else {
                        $('#days_seleted').val('');
                    }
                    admin_form_edit_validate.element('#days_seleted');
                },
                min_date:new Date(),
                max_date:max_date
            });


            $element.find(".departure-edit-form").dialog({
                autoOpen: false,
                modal: true,
                width: 800,
                appendTo: 'body',
                dialogClass: "dialog-departure-edit-form",
                //closeOnEscape: false,
                open: function(event, ui) {
                    var is_load_ajax_get_departure=plugin.settings.is_load_ajax_get_departure;
                    if(is_load_ajax_get_departure==0)
                    {
                        plugin.settings.departure_item=defaults.departure_item;
                        plugin.fill_data();
                        plugin.update_layout_departure();
                    }
                    var range_of_date = $dialog_departure_edit_form.find('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
                    range_of_date.on_change=function(start, end) {
                        plugin.update_calendar_price(start, end);
                    };
                }

            });
            $("#min_max_space").ionRangeSlider({
                min: 1,
                max: 40,
                from: 1,
                to: 40,
                type: 'double',
                grid: true,
                grid_num: 10,
                keyboard:true,
                keyboard_step:1
            });
            var max_date=365;
            $("#sale_period_open_before").ionRangeSlider({
                min: 1,
                max: max_date,
                from: 1,
                to: max_date,
                type: 'single',
                grid: true,
                grid_num: 10,
                keyboard:true,
                keyboard_step:1
            });
            $("#sale_period_close_before").ionRangeSlider({
                min: 1,
                max: max_date,
                from: 1,
                to: max_date,
                type: 'single',
                grid: true,
                grid_num: 10,
                keyboard:true,
                keyboard_step:1
            });
            $("#g_guarantee").ionRangeSlider({
                min: 1,
                max: 10,
                from: 1,
                to: 10,
                type: 'single',
                grid: true,
                grid_num: 10,
                keyboard:true,
                keyboard_step:1
            });
            $("#limited_space").ionRangeSlider({
                min: 1,
                max: 40,
                from: 1,
                to: 40,
                type: 'single',
                grid: true,
                grid_num: 10,
                keyboard:true,
                keyboard_step:1,
                onFinish: function (data) {

                }
            });
            $('#daterange_vail_period_from_to').daterangepicker({});

            $element.find('.edit-departure').click(function () {
                var self = $(this);
                var $row = self.closest('tr[role="row"]');
                var tsmart_departure_id = $row.data('tsmart_departure_id');
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'departure',
                            task: 'ajax_get_departure_item',
                            tsmart_departure_id: tsmart_departure_id
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        $('.div-loading').css({
                            display: "block"
                        });
                    },
                    success: function (departure_item) {

                        $('.div-loading').css({
                            display: "none"


                        });
                        plugin.settings.is_load_ajax_get_departure=1;
                        $(".departure-edit-form").dialog("open");
                        plugin.settings.is_load_ajax_get_departure=0;
                        $('.' + plugin.settings.dialog_class).find('input.number').val(0);
                        $('#tsmart_departure_id').val(tsmart_departure_id);
                        plugin.settings.departure_item=departure_item;
                        plugin.fill_data();
                        plugin.update_layout_departure();
                        //plugin.update_price();
                    }
                });


            });

            $dialog_departure_edit_form.find('#tsmart_product_id').change(function () {
                var tsmart_product_id = $(this).val();
                if(tsmart_product_id==0){
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'departure',
                            task: 'ajax_get_list_service_class_by_tour_id',
                            tsmart_language_id: tsmart_product_id

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
                        plugin.settings.list_tour=response;
                        plugin.update_select_service_class();


                    }
                });

            });
            $('.dialog-departure-edit-form').find('.calculator-price').click(function (e) {

                if (!$('#adminFormEdit').valid()) {
                    return false;
                }
                var self = $(this);
                var min_max_space = $('#min_max_space').val();
                var sale_period_open_before = $('#sale_period_open_before').val();
                var daterange_vail_period_from_to = $('#daterange_vail_period_from_to').val();
                var tour_id = $('#tour_id').val();
                var tsmart_departure_id = $('#tsmart_departure_id').val();
                var $row = self.closest('tr[role="row"]');
                var tour_class_ids = [];
                var tour_service_class_id = $('#tour_service_class_id').val();
                var weeklies = [];
                $('input[name="weekly[]"]:checked').each(function () {
                    weeklies.push($(this).val());
                });


                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'departure',
                            task: 'ajax_get_departure_item',
                            departure_id: tsmart_departure_id,
                            min_max_space: min_max_space,
                            sale_period_open_before: sale_period_open_before,
                            daterange_vail_period_from_to: daterange_vail_period_from_to,
                            tour_id: tour_id,
                            tour_service_class_id: tour_service_class_id,
                            weeklies: weeklies

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
            $('.dialog-departure-edit-form').find('.save-close-price').click(function (e) {
                if (!$('#adminFormEdit').valid()) {
                    return false;
                }

                var dataPost=$('#adminFormEdit').find(':input').serializeObject();
                $.ajax({
                    type: "POST",
                    url: 'index.php',
                    dataType: "json",
                    data: dataPost,
                    beforeSend: function () {

                        $('.div-loading').css({
                            display: "block"
                        });
                    },
                    success: function (response) {

                        $('.div-loading').css({
                            display: "none"


                        });
                        if (response.e == 1) {
                            var list_error = [];
                            $.each(response.m, function (index, error) {
                                $.notify({
                                    // options
                                    message: error.message
                                }, {
                                    // settings
                                    type: error.type
                                });
                            });

                        } else {
                            plugin.fill_data();
                            $(".departure-edit-form").dialog("close");
                        }


                    }
                });


            });

            Joomla.submitbutton = function (task) {
                switch (task) {
                    case "add":
                        //code block
                        plugin.add_new_departure();
                        break;
                    case n:
                        //code block
                        break;
                    default:
                    //default code block
                }

            };
            //delete departure
            $element.find('.delete-departure').click(function () {
                if (confirm('Are you sure you want delete this item ?')) {
                    var self = $(this);
                    var $row = self.closest('tr[role="row"]');
                    var tsmart_departure_id = $row.data('tsmart_departure_id');
                    var tour_id = $row.data('tour_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'departure',
                                task: 'ajax_remove_item',
                                tsmart_departure_id: tsmart_departure_id,
                                tour_id: tour_id
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
            $element.find('.publish-departure').click(function () {

                if (confirm('Are you sure you want publish this item ?')) {
                    var self = $(this);
                    var $row = self.closest('tr[role="row"]');
                    var tsmart_departure_id = $row.data('tsmart_departure_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'departure',
                                task: 'ajax_publish_item',
                                tsmart_departure_id: tsmart_departure_id
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
                                $row.find('a.publish-departure span.icon-white').toggleClass("icon-publish icon-unpublish");
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


        }
        plugin.update_calendar_price = function (start, end) {
            console.log(start);
            console.log(end);
        };
        plugin.fill_data = function () {
            var departure_item=plugin.settings.departure_item;
            $dialog_departure_edit_form.find('select[name="tsmart_product_id"]').val(departure_item.tsmart_language_id);
            $dialog_departure_edit_form.find('select[name="tsmart_product_id"]').trigger('change');
            $dialog_departure_edit_form.find('input[name="departure_name"]').val(departure_item.departure_name);
            $dialog_departure_edit_form.find('textarea[name="note"]').val(departure_item.note);
            var min_max_space_slider = $dialog_departure_edit_form.find('input[name="min_max_space"]').data("ionRangeSlider");
            min_max_space_slider.update({
                from: departure_item.min_space,
                to: departure_item.max_space
            });
            var sale_period_open_before_slider = $dialog_departure_edit_form.find('input[name="sale_period_open_before"]').data("ionRangeSlider");
            sale_period_open_before_slider.update({
                from: departure_item.sale_period_open_before
            });

            var sale_period_close_before_slider = $dialog_departure_edit_form.find('input[name="sale_period_close_before"]').data("ionRangeSlider");
            sale_period_close_before_slider.update({
                from: departure_item.sale_period_close_before
            });

            var g_guarantee_slider = $dialog_departure_edit_form.find('input[name="g_guarantee"]').data("ionRangeSlider");
            g_guarantee_slider.update({
                from: departure_item.g_guarantee
            });

            var limited_space_slider = $dialog_departure_edit_form.find('input[name="limited_space"]').data("ionRangeSlider");
            limited_space_slider.update({
                from: departure_item.limited_space
            });
            var allow_passenger=departure_item.allow_passenger;
            allow_passenger=allow_passenger.split(',');
            $dialog_departure_edit_form.find('input[name="allow_passenger[]"]').prop('checked', false);
            $.each(allow_passenger,function(index,item){
                $dialog_departure_edit_form.find('input[name="allow_passenger[]"][value="'+item+'"]').prop('checked', true);
            });
            var date_type=departure_item.date_type;
            $dialog_departure_edit_form.find('select[name="date_type"]').val(date_type);
            $dialog_departure_edit_form.find('select[name="date_type"]').trigger('change');
            var weekly=departure_item.weekly;
            weekly=weekly.split(',');
            $dialog_departure_edit_form.find('input[name="weekly[]"]').prop('checked', false);
            $.each(weekly,function(index,item){
                $dialog_departure_edit_form.find('input[name="weekly[]"][value="'+item+'"]').prop('checked', true);
            });
            var days_seleted=departure_item.days_seleted;
            days_seleted=days_seleted.split(',');
            $dialog_departure_edit_form.find('#multi-calendar-departure').DatePickerSetDate(days_seleted, true);

            var range_of_date = $dialog_departure_edit_form.find('#select_from_date_to_date_sale_period_from_sale_period_to').data('html_select_range_of_date');
            range_of_date.set_date(departure_item.sale_period_from,departure_item.sale_period_to);
            range_of_date.instant_daterangepicker.updateView();
            range_of_date.instant_daterangepicker.updateCalendars();



        }


        plugin.fill_price = function (response) {
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
    $.fn.view_departure_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_departure_default')) {
                var plugin = new $.view_departure_default(this, options);
                $(this).data('view_departure_default', plugin);

            }

        });

    }

})(jQuery);


