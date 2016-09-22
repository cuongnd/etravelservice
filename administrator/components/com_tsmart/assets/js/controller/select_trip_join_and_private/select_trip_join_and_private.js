(function ($) {

    // here we go!
    $.select_trip_join_and_private = function (element, options) {

        // plugin's default options
        var defaults = {
            list_tour: [],
            select_name: "",
            virtuemart_product_id: 0
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.update_select_tour = function (list_tour) {
            $element.find('.list_tour label').hide();
            for (var i = 0; i < list_tour.length; i++) {
                var tour = list_tour[i];
                $element.find('.list_tour label[data-virtuemart_product_id="' + tour.virtuemart_product_id + '"]').show();
            }
        };
        plugin.update_select_service_class = function (list_service_class) {
            $element.find('.list_service_class label').hide();
            for (var i = 0; i < list_service_class.length; i++) {
                var service_class = list_service_class[i];
                $element.find('.list_service_class label[data-virtuemart_service_class_id="' + service_class.virtuemart_service_class_id + '"]').show();
            }
        };
        plugin.update_select_departure = function (list_departure) {
            $element.find('.list_departure .departure-item').hide();
            for (var i = 0; i < list_departure.length; i++) {
                var departure = list_departure[i];
                $element.find('.list_departure .departure-item[data-virtuemart_departure_id="' + departure.virtuemart_departure_id + '"]').show();
            }
        };
        plugin.add_event_change_tour_type = function () {

            $element.find('select.tour_type').change(function () {


                var virtuemart_tour_type_id = $(this).val();
                if (virtuemart_tour_type_id == 2) {
                    $element.find('.air-service-class').show();
                    $element.find('.air-departure').hide();
                } else if (virtuemart_tour_type_id == 5) {
                    $element.find('.air-service-class').hide();
                    $element.find('.air-departure').show();
                }
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'product',
                            task: 'ajax_get_list_tour_id_by_tour_type_id',
                            virtuemart_tour_type_id: virtuemart_tour_type_id
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        $('.div-loading').css({
                            display: "block"
                        });
                    },
                    success: function (list_tour) {

                        $('.div-loading').css({
                            display: "none"


                        });
                        plugin.update_select_tour(list_tour);
                    }
                });

            });
        };
        plugin.add_event_change_tour = function () {
            $element.find('.list_tour .checkbox.virtuemart_product_id').change(function () {
                var virtuemart_tour_type_id = $element.find('select.tour_type').val();
                if (virtuemart_tour_type_id == 2) {
                    var virtuemart_product_id = $(this).val();
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'product',
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
                        success: function (list_service_class) {

                            $('.div-loading').css({
                                display: "none"


                            });
                            plugin.update_select_service_class(list_service_class);
                        }
                    });

                } else if (virtuemart_tour_type_id == 5) {
                    var virtuemart_product_id = $(this).val();
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: 'product',
                                task: 'ajax_get_list_departure_by_tour_id',
                                virtuemart_product_id: virtuemart_product_id
                            };
                            return dataPost;
                        })(),
                        beforeSend: function () {

                            $('.div-loading').css({
                                display: "block"
                            });
                        },
                        success: function (list_departure) {

                            $('.div-loading').css({
                                display: "none"


                            });
                            plugin.update_select_departure(list_departure);
                        }
                    });
                }

            });


        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var list_tour = plugin.settings.list_tour;
            var select_name = plugin.settings.select_name;
            var virtuemart_product_id = plugin.settings.virtuemart_product_id;
            plugin.add_event_change_tour_type();
            plugin.add_event_change_tour();
        };
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.select_trip_join_and_private = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('select_trip_join_and_private')) {
                var plugin = new $.select_trip_join_and_private(this, options);
                $(this).data('select_trip_join_and_private', plugin);

            }

        });

    }

})(jQuery);


