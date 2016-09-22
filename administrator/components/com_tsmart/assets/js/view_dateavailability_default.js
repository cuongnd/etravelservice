(function ($) {

    // here we go!
    $.view_dateavailability_default = function (element, options) {
        // plugin's default options
        var defaults = {

            list_date: [],
            date_availability_item:{
                weekly:"",
                allow_passenger:"",
                days_seleted:''
            },
            is_load_ajax_get_dateavailability:0


        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var $dialog_dateavailability_edit_form=$element.find(".dateavailability-edit-form");
        plugin.add_new_dateavailability = function () {
            var self = $(this);
            var dateavailability_id = 0;
            var tour_id = 0;
            $(".dateavailability-edit-form").dialog("open");
            $('.' + plugin.settings.dialog_class).find('input.number').val(0);
            $('#virtuemart_dateavailability_id').val(dateavailability_id);
        };
        plugin.update_select_service_class = function () {
            var date_availability_item=plugin.settings.date_availability_item;
            var list_service_class=plugin.settings.list_tour;
            $dialog_dateavailability_edit_form.find('#virtuemart_service_class_id').empty();
            var $option = '<option value="0">Please select service class</option>';
            $dialog_dateavailability_edit_form.find('#virtuemart_service_class_id').append($option);
            for(var i=0;i<list_service_class.length;i++){
                var item_service_class=list_service_class[i];
                var $option = '<option  '+(item_service_class.virtuemart_product_id==date_availability_item.virtuemart_product_id?' selected ':'') +' value="' + item_service_class.virtuemart_product_id + '">' + item_service_class.service_class_name + '</option>';
                $dialog_dateavailability_edit_form.find('#virtuemart_service_class_id').append($option);

            }
            $dialog_dateavailability_edit_form.find('#virtuemart_service_class_id').trigger('change');



        };
        plugin.update_layout_date_availability = function (date_availability_item) {

            $('#multi-calendar-dateavailability').DatePickerSetDate(date_availability_item, false);
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            $element.find('span.price').autoNumeric('init',{
                mDec:0,
                aSep:' ',
                aSign:'US$'
            });

            var list_date = plugin.settings.list_date;
            $element.find('.show-list-date').tipso();

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
                    dateavailability_name: "please input dateavailability name",
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
                    $dialog_dateavailability_edit_form.find('#multi-calendar-dateavailability').show();
                    $dialog_dateavailability_edit_form.find('input[name="weekly[]"]').prop('checked', false);
                } else {
                    $("#days_seleted").rules("add", {
                        required: false
                    });
                    $('input[name="weekly[]"]').prop("checked");
                    $('input[name="weekly[]"]').prop("disabled", false);
                    $('input[name="weekly[]"]').rules("add", {
                        required: true
                    });
                    $dialog_dateavailability_edit_form.find('#multi-calendar-dateavailability').DatePickerClear();
                    $dialog_dateavailability_edit_form.find('#multi-calendar-dateavailability').hide();
                }
                admin_form_edit_validate.element('input[name="weekly[]"]');
                admin_form_edit_validate.element('#days_seleted');

            });
            var max_date=new Date();
            max_date.setYear((new Date()).getYear() + 1);
            var multi_calendar_dateavailability = $('#multi-calendar-dateavailability').multi_calendar_date_picker({
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

            $element.find('#virtuemart_service_class_id').change(function(){
                var virtuemart_service_class_id=$(this).val();
                var virtuemart_product_id=$('#virtuemart_product_id').val();
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        var dataPost = {
                            option: 'com_tsmart',
                            controller: 'dateavailability',
                            task: 'ajax_get_dateavailability_item',
                            virtuemart_product_id: virtuemart_product_id,
                            virtuemart_product_id: virtuemart_service_class_id
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        $('.div-loading').css({
                            display: "block"
                        });
                    },
                    success: function (date_availability_item) {

                        $('.div-loading').css({
                            display: "none"


                        });
                        plugin.update_layout_date_availability(date_availability_item);
                        //plugin.update_price();
                    }
                });

            });
            $element.find(".dateavailability-edit-form").dialog({
                autoOpen: false,
                modal: true,
                width: 800,
                appendTo: 'body',
                dialogClass: "dialog-dateavailability-edit-form",
                //closeOnEscape: false,
                open: function(event, ui) {


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

            $element.find('.edit-dateavailability').click(function () {
                var self = $(this);
                var $row = self.closest('tr[role="row"]');
                var virtuemart_date_availability_id = $row.data('virtuemart_date_availability_id');
                var virtuemart_service_class_id = $row.data('virtuemart_service_class_id');
                var virtuemart_product_id = $row.data('virtuemart_product_id');
                $(".dateavailability-edit-form").dialog("open");
                $('#virtuemart_product_id').val(virtuemart_product_id).trigger('change');
                plugin.settings.date_availability_item.virtuemart_product_id=virtuemart_service_class_id;



            });

            $dialog_dateavailability_edit_form.find('#virtuemart_product_id').change(function () {
                var virtuemart_product_id = $(this).val();
                if(virtuemart_product_id==0){
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'dateavailability',
                            task: 'ajax_get_list_service_class_by_tour_id',
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
                        plugin.settings.list_tour=response;
                        plugin.update_select_service_class();


                    }
                });

            });
            $('.dialog-dateavailability-edit-form').find('.calculator-price').click(function (e) {

                if (!$('#adminFormEdit').valid()) {
                    return false;
                }
                var self = $(this);
                var min_max_space = $('#min_max_space').val();
                var sale_period_open_before = $('#sale_period_open_before').val();
                var daterange_vail_period_from_to = $('#daterange_vail_period_from_to').val();
                var tour_id = $('#tour_id').val();
                var virtuemart_dateavailability_id = $('#virtuemart_dateavailability_id').val();
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
                            controller: 'dateavailability',
                            task: 'ajax_get_dateavailability_item',
                            dateavailability_id: virtuemart_dateavailability_id,
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
            $('.dialog-dateavailability-edit-form').find('.save-close-price').click(function (e) {
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
                            $(".dateavailability-edit-form").dialog("close");
                        }


                    }
                });


            });

            Joomla.submitbutton = function (task) {
                switch (task) {
                    case "add":
                        //code block
                        plugin.add_new_dateavailability();
                        break;
                    case n:
                        //code block
                        break;
                    default:
                    //default code block
                }

            };
            //delete dateavailability
            $element.find('.delete-dateavailability').click(function () {
                if (confirm('Are you sure you want delete this item ?')) {
                    var self = $(this);
                    var $row = self.closest('tr[role="row"]');
                    var virtuemart_dateavailability_id = $row.data('virtuemart_dateavailability_id');
                    var tour_id = $row.data('tour_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'dateavailability',
                                task: 'ajax_remove_item',
                                virtuemart_dateavailability_id: virtuemart_dateavailability_id,
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
            $element.find('.publish-dateavailability').click(function () {

                if (confirm('Are you sure you want publish this item ?')) {
                    var self = $(this);
                    var $row = self.closest('tr[role="row"]');
                    var virtuemart_dateavailability_id = $row.data('virtuemart_dateavailability_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'dateavailability',
                                task: 'ajax_publish_item',
                                virtuemart_dateavailability_id: virtuemart_dateavailability_id
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
                                $row.find('a.publish-dateavailability span.icon-white').toggleClass("icon-publish icon-unpublish");
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


        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_dateavailability_default = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_dateavailability_default')) {
                var plugin = new $.view_dateavailability_default(this, options);
                $(this).data('view_dateavailability_default', plugin);

            }

        });

    }

})(jQuery);


