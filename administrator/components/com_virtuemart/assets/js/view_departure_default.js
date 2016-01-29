(function ($) {

    // here we go!
    $.view_departure_default = function (element, options) {
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
        plugin.add_new_allocation = function () {
            var self = $(this);
            var allocation_id = 0;
            var tour_id = 0;
            $(".allocation-edit-form").dialog("open");
            $('.' + plugin.settings.dialog_class).find('input.number').val(0);
            $('#virtuemart_allocation_id').val(allocation_id);
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
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
                } else {
                    $("#days_seleted").rules("add", {
                        required: false
                    });
                    $('input[name="weekly[]"]').prop("checked");
                    $('input[name="weekly[]"]').prop("disabled", false);
                    $('input[name="weekly[]"]').rules("add", {
                        required: true
                    });
                }
                admin_form_edit_validate.element('input[name="weekly[]"]');
                admin_form_edit_validate.element('#days_seleted');

            });

            var multi_calendar_allocation = $('#multi-calendar-allocation').multi_calendar_date_picker({
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
                }
            });


            $element.find(".allocation-edit-form").dialog({
                autoOpen: false,
                modal: true,
                width: 800,
                appendTo: 'body',
                dialogClass: "dialog-allocation-edit-form"
                //closeOnEscape: false,
                //open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }

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
            $("#sale_period_open_before").ionRangeSlider({
                min: 1,
                max: 40,
                from: 1,
                to: 40,
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
                var virtuemart_departure_id = $row.data('virtuemart_departure_id');
                var tour_id = $row.data('tour_id');
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'departure',
                            task: 'ajax_get_allocation_item',
                            virtuemart_departure_id: virtuemart_departure_id,
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
                        $(".allocation-edit-form").dialog("open");
                        $('.' + plugin.settings.dialog_class).find('input.number').val(0);
                        $('#virtuemart_allocation_id').val(allocation_id);
                        //plugin.fill_data(response);
                        //plugin.updata_price();
                    }
                });


            });
            $('.dialog-allocation-edit-form').find('#tour_id').change(function () {
                var tour_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_virtuemart',
                            controller: 'departure',
                            task: 'ajax_get_list_service_class_by_tour_id',
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
                        $('.dialog-allocation-edit-form').find('#tour_service_class_id option').css({
                            display: "none"
                        });
                        response.push(0);
                        $.each(response, function (index, value) {
                            $('.dialog-allocation-edit-form').find('#tour_service_class_id option[value="' + value + '"]').css({
                                display: "block"
                            });
                        });
                        $("#tour_service_class_id").val(0);
                        $("#tour_service_class_id").trigger("liszt:updated");


                    }
                });

            });
            $('.dialog-allocation-edit-form').find('.calculator-price').click(function (e) {

                if (!$('#adminFormEdit').valid()) {
                    return false;
                }
                var self = $(this);
                var min_max_space = $('#min_max_space').val();
                var sale_period_open_before = $('#sale_period_open_before').val();
                var daterange_vail_period_from_to = $('#daterange_vail_period_from_to').val();
                var tour_id = $('#tour_id').val();
                var allocation_id = $('#virtuemart_allocation_id').val();
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
                            option: 'com_virtuemart',
                            controller: 'departure',
                            task: 'ajax_get_allocation_item',
                            allocation_id: allocation_id,
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
            $('.dialog-allocation-edit-form').find('.save-close-price').click(function (e) {
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
                            plugin.fill_data(response);
                            $(".allocation-edit-form").dialog("close");
                        }


                    }
                });


            });

            Joomla.submitbutton = function (task) {
                switch (task) {
                    case "add":
                        //code block
                        plugin.add_new_allocation();
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
                    var virtuemart_departure_id = $row.data('virtuemart_departure_id');
                    var tour_id = $row.data('tour_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'departure',
                                task: 'ajax_remove_item',
                                virtuemart_departure_id: virtuemart_departure_id,
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
                    var virtuemart_departure_id = $row.data('virtuemart_departure_id');
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        data: (function () {

                            dataPost = {
                                option: 'com_virtuemart',
                                controller: 'departure',
                                task: 'ajax_publish_item',
                                virtuemart_departure_id: virtuemart_departure_id
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
        plugin.fill_data = function (result) {
            return false;
            var date_format = plugin.settings.date_format;
            var list_tour_promotion_price_by_tour_promotion_price_id = result.list_tour_promotion_price_by_tour_promotion_price_id;
            if (typeof list_tour_promotion_price_by_tour_promotion_price_id == "undefined") {
                list_tour_promotion_price_by_tour_promotion_price_id = result.tour_private_price_by_tour_price_id;

            }
            $('input[name="tax"]').val(result.price.tax);
            $('input[name="tour_methor"]').val(result.tour.tour_methor);
            $('input[name="virtuemart_promotion_price_id"]').val(result.price.virtuemart_promotion_price_id);
            $('textarea[name="price_note"]').val(result.price.price_note.trim());
            $('#sale_period_from').val(result.price.sale_period_from);
            var sale_period_from = $.datepicker.formatDate(date_format, new Date(result.price.sale_period_from))
            $('#sale_period_from_text').val(sale_period_from);

            $('#sale_period_to').val(result.price.sale_period_to);
            var sale_period_to = $.datepicker.formatDate(date_format, new Date(result.price.sale_period_to))
            $('#sale_period_to_text').val(sale_period_to);


            $('select[name="service_class_id"]').val(result.price.service_class_id);
            $('select[name="service_class_id"]').trigger("liszt:updated.chosen");
            tour_methor = result.tour.tour_methor;
            if (tour_methor == 'tour_group') {
                $('#tour_group').css({
                    display: "block"
                });
                $('#tour_basic').css({
                    display: "none"
                });
                $.each(list_tour_promotion_price_by_tour_promotion_price_id, function (index, item) {
                    var $row = $('table.base-price tr[data-group_size_id="' + item.virtuemart_group_size_id + '"]');
                    $row.find('td input[column-type="senior"]').val(item.price_senior);
                    $row.find('td input[column-type="adult"]').val(item.price_adult);
                    $row.find('td input[column-type="teen"]').val(item.price_teen);
                    $row.find('td input[column-type="children1"]').val(item.price_children1);
                    $row.find('td input[column-type="children2"]').val(item.price_children2);
                    $row.find('td input[column-type="infant"]').val(item.price_infant);
                    $row.find('td input[column-type="private_room"]').val(item.price_private_room);

                });

            } else if (typeof list_tour_promotion_price_by_tour_promotion_price_id != "undefined") {
                $('#tour_group').css({
                    display: "none"
                });
                $('#tour_basic').css({
                    display: "block"
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


            var list_promotion_mark_up = result.list_promotion_mark_up;
            $.each(list_promotion_mark_up, function (type, item) {
                var $row = $('table.mark-up-price tr.' + type);
                $row.find('td input[column-type="senior"]').val(item.senior);
                $row.find('td input[column-type="adult"]').val(item.adult);
                $row.find('td input[column-type="teen"]').val(item.teen);
                $row.find('td input[column-type="children1"]').val(item.children1);
                $row.find('td input[column-type="children2"]').val(item.children2);
                $row.find('td input[column-type="infant"]').val(item.infant);
                $row.find('td input[column-type="private_room"]').val(item.private_room);

            });


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


