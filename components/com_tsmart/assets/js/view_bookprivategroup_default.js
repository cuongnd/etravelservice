//huong dan su dung/* $('.view_bookprivategroup_default').view_bookprivategroup_default(); view_bookprivategroup_default=$('.view_bookprivategroup_default').data('view_bookprivategroup_default'); console.log(view_bookprivategroup_default); */// jQuery Plugin for SprFlat admin view_bookprivategroup_default// Control options and basic function of view_bookprivategroup_default// version 1.0, 28.02.2013// by SuggeElson www.suggeelson.com(function ($) {    // here we go!    $.view_bookprivategroup_default = function (element, options) {        // plugin's default options        var defaults = {            total_senior_adult_teen: 1,            total_children_infant: 1,            debug: false,            price_type:'',            product: {},            passenger_config: {                senior_passenger_age_to: 99,                senior_passenger_age_from: 60,                adult_passenger_age_to: 59,                adult_passenger_age_from: 18,                teen_passenger_age_to: 17,                teen_passenger_age_from: 12,                children_1_passenger_age_to: 11,                children_1_passenger_age_from: 9,                children_2_passenger_age_to: 8,                children_2_passenger_age_from: 6,                infant_passenger_age_to: 5,                infant_passenger_age_from: 0,            },            item: {                full_charge_children1: 0,                full_charge_children2: 0            },            tour_min_age: 0,            tour_max_age: 99,            list_passenger: [                {                    first_name: '',                    middle_name: '',                    last_name: '',                    date_of_birth: '',                    template_html_room_item: ""                }            ],            config_show_price: {                mDec: 1,                aSep: ' ',                aSign: 'US$'            },            range_year_old_infant: [0, 1],            range_year_old_children_2: [2, 5],            range_year_old_children_1: [6, 11],            range_year_old_teen: [12, 17],            range_year_old_adult: [18, 65],            range_year_old_senior: [66, 99],            range_year_old_senior_adult_teen: [12, 99],            range_adult_and_children: [6, 99],            option_dreymodal : {                minWidth: 250,                maxWidth: 250,                overlay: true,                overlayColor: "#222222",                overlayOpacity: 0.9,                closeButton: true,                inAnimationTime: 600,                inAnimationType: "slideInFromLeft",                outAnimationTime: 600,                outAnimationType: "slideOutToRight",                allowEscapeKey: true,                title: "Alert",                titleBackColor: "#128a4b",                overlayBlur: false,                append: false            }            //main color scheme for view_bookprivategroup_default            //be sure to be same as colors on main.css or custom-variables.less        }        // current instance of the object        var plugin = this;        // this will hold the merged default, and user-provided options        plugin.settings = {}        var $element = $(element), // reference to the jQuery version of DOM element            element = element;    // reference to the actual DOM element        plugin.update_price = function () {            var product=plugin.settings.product;            var departure = plugin.settings.item;            if(parseInt(product.tour_length)>1) {                var $list_radio_rooming = $('#list-radio-box-rooming').data('list_radio_rooming');                var build_room = $list_radio_rooming.get_value() == 'build_room';                if (build_room) {                    var total_tour_cost = 0;                    var $html_build_room = $('#html_build_room').data('html_build_room');                    var list_room = $html_build_room.get_list_room();// $html_build_room.settings.list_room;                    plugin.update_room(list_room);                    var room_total_price = 0;                    for (var i = 0; i < list_room.length; i++) {                        var room_item = list_room[i];                        var tour_cost_and_room_price = room_item.tour_cost_and_room_price;                        if (typeof tour_cost_and_room_price != "undefined") {                            tour_cost_and_room_price.forEach(function (item_tour_cost_and_room_price) {                                total_tour_cost += item_tour_cost_and_room_price.tour_cost;                                room_total_price += item_tour_cost_and_room_price.room_price;                            });                        }                    }                    var total_tour_cost=plugin.get_total_tour_cost();                    var total_cost_service=room_total_price+total_tour_cost;                    $element.find('.booking-summary-content .passenger-service-fee-total').autoNumeric('set', total_tour_cost);                    $element.find('.booking-summary-content .total-cost-service').autoNumeric('set', total_cost_service);                }else {                    var total_tour_cost=plugin.get_total_tour_cost();                    $element.find('.booking-summary-content .passenger-service-fee-total').autoNumeric('set', total_tour_cost);                    $element.find('.booking-summary-content .total-cost-service').autoNumeric('set', total_tour_cost);                }            }else{                var total_tour_cost=plugin.get_total_tour_cost();                $element.find('.booking-summary-content .passenger-service-fee-total').autoNumeric('set', total_tour_cost);                $element.find('.booking-summary-content .total-cost-service').autoNumeric('set', total_tour_cost);            }        };        plugin.get_total_tour_cost=function(){            var product=plugin.settings.product;            var departure = plugin.settings.item;            var total_tour_cost = 0;            var sale_price_senior = departure.sale_price_senior,                sale_price_adult = departure.sale_price_adult,                sale_price_teen = departure.sale_price_teen,                sale_price_children1 = departure.sale_price_children1,                sale_price_children2 = departure.sale_price_children2,                sale_price_infant = departure.sale_price_infant,                sale_price_private_room = departure.sale_price_private_room,                sale_price_extra_bed = departure.sale_price_extra_bed;            var price_senior = departure.tsmart_discount_id>0 ? departure.sale_discount_price_senior : sale_price_senior;            var price_adult = departure.tsmart_discount_id>0 ? departure.sale_discount_price_adult : sale_price_adult;            var price_teen = departure.tsmart_discount_id>0 ? departure.sale_discount_price_teen :sale_price_teen;            var price_children1 = departure.tsmart_discount_id>0 ? departure.sale_discount_price_children1 : sale_price_children1;            var price_children2 = departure.tsmart_discount_id>0 ? departure.sale_discount_price_children2 : sale_price_children2;            var price_infant = departure.tsmart_discount_id>0 ? departure.sale_discount_price_infant : sale_price_infant;            var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');            var list_passenger = $html_input_passenger.settings.list_passenger;            var a_list_passenger = [];            var list_children_infant = list_passenger.children_infant;            if(typeof list_children_infant!="undefined")            {                for (var i = 0; i < list_children_infant.length; i++) {                    var passenger=list_children_infant[i];                    if(passenger.date_of_birth.trim()=='') continue;                    a_list_passenger.push(passenger);                }            }            var list_senior_adult_teen = list_passenger.senior_adult_teen;            for (var i = 0; i < list_senior_adult_teen.length; i++) {                var passenger=list_senior_adult_teen[i];                if(passenger.year_old==0) continue;                a_list_passenger.push(passenger);            }            for(var i=0;i<a_list_passenger.length;i++){                var passenger=a_list_passenger[i];                if(plugin.is_senior(passenger)){                    total_tour_cost+=price_senior;                }else if(plugin.is_adult(passenger)){                    total_tour_cost+=price_adult;                }else if(plugin.is_teen(passenger)){                    total_tour_cost+=price_teen;                }else if(plugin.is_children_2(passenger)){                    total_tour_cost+=price_children2;                }else if(plugin.is_children_1(passenger)){                    total_tour_cost+=price_children1;                }else if(plugin.is_infant(passenger)){                    total_tour_cost+=price_infant;                }            }            return total_tour_cost;        };        plugin.is_adult = function (passenger) {            var range_year_old_adult = plugin.settings.range_year_old_adult;            return passenger.year_old >= range_year_old_adult[0] && passenger.year_old <= range_year_old_adult[1];        };        plugin.is_senior = function (passenger) {            var range_year_old_senior = plugin.settings.range_year_old_senior;            return passenger.year_old >= range_year_old_senior[0] && passenger.year_old <= range_year_old_senior[1];        };        plugin.is_teen = function (passenger) {            var range_year_old_teen = plugin.settings.range_year_old_teen;            return passenger.year_old >= range_year_old_teen[0] && passenger.year_old <= range_year_old_teen[1];        };        plugin.is_infant = function (passenger) {            var range_year_old_infant = plugin.settings.range_year_old_infant;            return passenger.year_old <= range_year_old_infant[1];        };        plugin.is_children_1 = function (passenger) {            var range_year_old_children_1 = plugin.settings.range_year_old_children_1;            return passenger.year_old >= range_year_old_children_1[0] && passenger.year_old <= range_year_old_children_1[1];        };        plugin.is_children_2 = function (passenger) {            var range_year_old_children_2 = plugin.settings.range_year_old_children_2;            return parseInt(passenger.year_old) >= parseInt(range_year_old_children_2[0]) && parseInt(passenger.year_old) <= parseInt(range_year_old_children_2[1]);        };        plugin.show_passenger = function () {            var debug=plugin.settings.debug;            var product=plugin.settings.product;            var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');            $html_input_passenger.settings.event_after_change = function (list_passenger,index_action,event_name) {                if(parseInt(product.tour_length)>1) {                    var $html_build_room = $('#html_build_room').data('html_build_room');                    $html_build_room.update_passengers(list_passenger,index_action,event_name);                }                plugin.update_price();                var $list_passenger = $element.find('.list_passenger');                var total_passenger = list_passenger.length;                $element.find('.booking-summary-content .total-passenger').html(total_passenger);                $list_passenger.empty();                for (var i = 0; i < total_passenger; i++) {                    var passenger = list_passenger[i];                    var full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + ' <span class="'+(!debug?' hide ':'')+'" >(' + passenger.year_old + ')</span> <span class="price '+(!debug?' hide ':'')+'">'+(typeof passenger.tour_cost!="undefined"?passenger.tour_cost:0)+'</span>';                    var $li = $('<li>' + full_name + '</li>');                    $li.appendTo($list_passenger);                }                if(debug)                {                    $list_passenger.find('span.price').autoNumeric('init', plugin.settings.config_show_price);                }            }        };        plugin.notify = function (content, type) {            if (typeof  type == "undefined") {                type = "error";            }            var notify = $.notify(content, {                allow_dismiss: true,                type: type,                placement: {                    align: "center"                }            });        };        plugin.validate = function () {            var product=plugin.settings.product;            if(parseInt(product.tour_length)>1) {                var $list_radio_rooming = $('#list-radio-box-rooming').data('list_radio_rooming');                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');                var $html_build_room = $('#html_build_room').data('html_build_room');                if (!$html_input_passenger.validate()) {                    return false;                }else if($list_radio_rooming.get_value() == null){                    plugin.notify('please select rooming');                    $element.find('.tour-border.rooming').addClass('error');                    $.scrollTo($element.find('.tour-border.rooming'), 800);                    return false;                }else if ($list_radio_rooming.get_value() == 'build_room' && !$html_build_room.validate()) {                    return false;                }                var list_room = $html_build_room.get_list_room();// $html_build_room.settings.list_room;                plugin.update_room(list_room);                plugin.update_price();                var $html_build_form_contact = $('.html_build_form_contact').data('html_build_form_contact');                if (!$html_build_form_contact.validate()) {                    return false;                }                var passenger_not_inside_room = $html_build_room.find_passenger_not_inside_room();                if ($list_radio_rooming.get_value() == 'build_room' && passenger_not_inside_room.length > 0) {                    plugin.notify('there are some person not set room');                    return false;                }                return true;            }else {                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');                if (!$html_input_passenger.validate()) {                    return false;                }                plugin.update_price();                var $html_build_form_contact = $('.html_build_form_contact').data('html_build_form_contact');                if (!$html_build_form_contact.validate()) {                    return false;                }                return true;            }        };        plugin.get_passenger_full_name = function (passenger,debug) {            if(typeof debug=="undefined") debug=false;            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name;            if(debug){                passenger_full_name+=' (' + passenger.year_old + ')'            }            return passenger_full_name;        };        // the "constructor" method that gets called when the object is created        plugin.get_item_tour_cost_and_room_price_by_passenger_index = function (tour_cost_and_room_price, passenger_index) {            if (tour_cost_and_room_price.length > 0) {                for (var i = 0; i < tour_cost_and_room_price.length; i++) {                    var item_tour_cost_and_room_price = tour_cost_and_room_price[i];                    if (item_tour_cost_and_room_price.passenger_index == passenger_index) {                        return item_tour_cost_and_room_price;                    }                }            }            return null;        };        plugin.update_room = function (list_room) {            var $html_build_room = $('#html_build_room').data('html_build_room');            var list_passenger = $html_build_room.settings.list_passenger;            var debug = plugin.settings.debug;            $element.find('.booking-summary-content .list-room').empty();            var $template_html_room_item = $(plugin.settings.template_html_room_item);            $template_html_room_item.appendTo($element.find('.booking-summary-content .list-room'));            var $list_passenger_room = $element.find('.booking-summary-content .list-room .list_passenger_room');            var room_total_price = 0;            console.log(list_room);            for (var i = 0; i < list_room.length; i++) {                var room_item = list_room[i];                var passengers = room_item.passengers;                var room_type = room_item.list_room_type;                var tour_cost_and_room_price = room_item.tour_cost_and_room_price;                //$template_html_room_item.find('.room-type').html(room_type);                var total_price_per_room = 0;                if (passengers.length > 0) {                    for (j = 0; j < passengers.length; j++) {                        var passenger_index = passengers[j];                        if (typeof list_passenger[passenger_index] == "undefined") continue;                        var full_name = plugin.get_passenger_full_name(list_passenger[passenger_index],debug);                        var total_price_per_passenger = 0;                        var passenger_note = "";                        if (typeof tour_cost_and_room_price != "undefined") {                            var item_tour_cost_and_room_price = plugin.get_item_tour_cost_and_room_price_by_passenger_index(tour_cost_and_room_price, passenger_index);                            if (item_tour_cost_and_room_price != null) {                                total_price_per_passenger = item_tour_cost_and_room_price.room_price + item_tour_cost_and_room_price.extra_bed_price;                                var passenger_note = item_tour_cost_and_room_price.msg;                            }                        }                        total_price_per_room += total_price_per_passenger;                        var $li = $('<li>' + full_name + ' <b><span class="price '+(!debug?' hide ':'')+'">' + total_price_per_passenger + '</span></b><span data-tipso-content="' + passenger_note + '" class="pull-right icon-question "></span></li>');                        if (total_price_per_passenger > 0) {                            $li.appendTo($list_passenger_room);                        }                        room_total_price += total_price_per_passenger;                    }                }            }            if(debug)            {                $list_passenger_room.find('span.price').autoNumeric('init', plugin.settings.config_show_price);            }            $element.find('.booking-summary-content .list-room .icon-question').tipso({                size: 'tiny',                useTitle: false,                animationIn: 'bounceInDown'            }).addClass('error');            $element.find('.room-service-fee-total').autoNumeric('set', room_total_price);        };        plugin.update_price_when_change_passenger = function ($html_input_passenger,index_action,event_name) {            var list_passenger = $html_input_passenger.settings.list_passenger;            var tsmart_price_id = $element.find('input[name="tsmart_price_id"]').val();            var booking_date = $element.find('input[name="booking_date"]').val();            $.ajax({                type: "GET",                url: 'index.php',                dataType: "json",                data: (function () {                    dataPost = {                        option: 'com_tsmart',                        controller: 'bookprivategroup',                        task: 'ajax_get_price_book_private_group',                        tsmart_price_id: tsmart_price_id,                        booking_date: booking_date,                        ajax: 1,                        total_senior_adult_teen: list_passenger.senior_adult_teen.length,                    };                    return dataPost;                })(),                beforeSend: function () {                    $('.div-loading').css({                        display: "block"                    });                },                success: function (response) {                    var option=plugin.settings.option_dreymodal;                    $('.div-loading').css({                        display: "none"                    });                    if (response.e == 1) {                        option.title = "Error";                        option.titleBackColor = "#bc056d";                        var alert_drey_modal = new Dreymodal('<div>' + response.r + '</div>', option);                        alert_drey_modal.open();                    } else {                        var response_item = response.item;                        var item = plugin.settings.item;                        if (response_item.price_adult != item.price_adult || response_item.price_children1 != item.price_children1 || response_item.price_children2 != item.price_children2 || response_item.price_infant != item.price_infant) {                            option.title = "Messenger";                            option.titleBackColor = "#128a4b";                            var alert_drey_modal = new Dreymodal('<div>your price change</div>', option);                            alert_drey_modal.open();                        }                        plugin.settings.item = response_item;                        var product=plugin.settings.product;                        if(parseInt(product.tour_length)>1) {                            var $html_build_room = $('#html_build_room').data('html_build_room');                            $html_build_room.settings.departure = response_item;                        }                        var $table_list_price = $element.find('.wrapper-price').empty().html(response.r_html);                        $element.find('.wrapper-price .price').autoNumeric('init', plugin.settings.config_show_price);                        $element.find('select[name="filter_total_passenger_from_12_years_old"]').val(list_passenger.senior_adult_teen.length).trigger("change");                        $element.find('select[name="filter_total_passenger_under_12_years_old"]').val(typeof list_passenger.children_infant != "undefined" ? list_passenger.children_infant.length : 0).trigger("change");                        plugin.update_price();                    }                }            });        };        plugin.check_group_size = function (total_passenger) {            var list_group_size = plugin.settings.list_group_size;            var in_group = false;            for (var i = 0; i < list_group_size.length; i++) {                var group_size = list_group_size[i];                if (total_passenger >= parseInt(group_size.from) && total_passenger <= parseInt(group_size.to)) {                    in_group = true;                    break;                }            }            if (!in_group) {                var option = {                    minWidth: 250,                    maxWidth: 250,                    overlay: true,                    overlayColor: "#222222",                    overlayOpacity: 0.9,                    closeButton: true,                    inAnimationTime: 600,                    inAnimationType: "slideInFromLeft",                    outAnimationTime: 600,                    outAnimationType: "slideOutToRight",                    allowEscapeKey: true,                    title: "Error",                    titleBackColor: "#bc056d",                    overlayBlur: false,                    append: false                };                var alert_drey_modal = new Dreymodal('<div><h3>your selected not suport,this tour only suport group:</h3>' + plugin.settings.list_string_group_size.join(',') + '<br/>please select an other</div>', option);                alert_drey_modal.open();                return in_group;            }            return in_group;        };        plugin.init = function () {            plugin.settings = $.extend({}, defaults, options);            var product=plugin.settings.product;            plugin.settings.price_type=product.price_type;            $element.find("#tabbed-nav").zozoTabs({                theme: "silver",                orientation: "horizontal",                position: "top-left",                size: "medium",                animation: {                    easing: "easeInOutExpo",                    duration: 400,                    effects: "slideH"                },                defaultTab: "tab1"            });            plugin.settings.template_html_room_item = $element.find('.booking-summary-content .list-room .list_passenger_room').getOuterHTML();            $element.find('.booking-summary-content .list-room').empty();            $element.find('.passenger-service-fee-total').autoNumeric('init', plugin.settings.config_show_price);            $element.find('.room-service-fee-total').autoNumeric('init', plugin.settings.config_show_price);            $element.find('.table-trip .body .item  .body-item').on('hidden', function () {                // do something…                var $item = $(this).closest('.item');                $item.removeClass('in');                $item.find('.header-item > .service-class-price').addClass('hide');                $item.find('.header-item > .service-class,.header-item > .price').removeClass('hide');                console.log($item);            });            $element.find('.table-trip .body .item  .body-item').on('show', function () {                // do something…                var $item = $(this).closest('.item');                $item.addClass('in');                $item.find('.header-item > .service-class-price').removeClass('hide');                $item.find('.header-item > .service-class,.header-item > .price').addClass('hide');                console.log($item);            });            $element.find('span.price').autoNumeric('init', plugin.settings.config_show_price);            $element.find('button.book-now').click(function () {                var $item = $(this).closest('.item');                var tsmart_price_id = $item.data('tsmart_price_id');                var $form = $element.find('form#tour_price');                $form.find('input[name="tsmart_price_id"]').val(tsmart_price_id);                $form.find('input[name="task"]').val('book_now');                console.log($form);            });            plugin.show_passenger();            var $form = $element.find('form#bookprivategroup');            $form.submit(function () {                if (!plugin.validate()) {                    return false;                }                var $form = $element.find('form#bookprivategroup');                $form.find('input[name="task"]').val('go_to_booking_add_on_from');                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');                var list_passenger = $html_input_passenger.settings.list_passenger;                var a_list_passenger = [];                var list_children_infant = list_passenger.children_infant;                if(typeof list_children_infant!="undefined") {                    for (var i = 0; i < list_children_infant.length; i++) {                        a_list_passenger.push(list_children_infant[i]);                    }                }                var list_senior_adult_teen = list_passenger.senior_adult_teen;                for (var i = 0; i < list_senior_adult_teen.length; i++) {                    a_list_passenger.push(list_senior_adult_teen[i]);                }                a_list_passenger = JSON.stringify(a_list_passenger);                $.cookie('cookie_list_passenger', a_list_passenger);                var list_room = $html_build_room.get_list_room();                var a_list_room = JSON.stringify(list_room);                $.cookie('cookie_list_room', a_list_room);                $form.find('input[name="build_room"]').val(a_list_room);                var $html_build_form_contact = $('.html_build_form_contact').data('html_build_form_contact');                var contact_data = $html_build_form_contact.get_data();                contact_data = JSON.stringify(contact_data);                $.cookie('contact_data', contact_data);                $form.find('input[name="contact_data"]').val(contact_data);                return true;            });            var product=plugin.settings.product;            if(parseInt(product.tour_length)>1) {                var $html_build_room = $('#html_build_room').data('html_build_room');                $html_build_room.settings.trigger_after_change = function (list_room) {                    plugin.update_price();                };            }            $element.find('.show-hidden-fee').sidr({                name: 'sidr_booking_summary',                side: 'left', // By default                displace: false            });            var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');            var total_senior_adult_teen = plugin.settings.total_senior_adult_teen;            var total_children_infant = plugin.settings.total_children_infant;            if (total_senior_adult_teen > 1) {                $html_input_passenger.setup_total_senior_adult_teen(total_senior_adult_teen - 1);            }            $html_input_passenger.setup_total_children_infant(total_children_infant);            $html_input_passenger.settings.function_trigger_check_allow_remove_passenger = function ($html_input_passenger,index_remove) {                var price_type=plugin.settings.price_type;                if(price_type=='flat_price')                {                    return true;                }else{                    var list_passenger = $html_input_passenger.settings.list_passenger;                    return plugin.check_group_size(list_passenger.senior_adult_teen.length - 1);                }            }            $html_input_passenger.settings.function_trigger_remove_passenger = function ($html_input_passenger,index_remove) {                plugin.show_passenger();                plugin.update_price_when_change_passenger($html_input_passenger,index_remove,'remove');            }            $html_input_passenger.settings.function_trigger_check_allow_add_passenger = function ($html_input_passenger,index_add) {                var price_type=plugin.settings.price_type;                if(price_type=='flat_price')                {                    return true;                }else{                    var list_passenger = $html_input_passenger.settings.list_passenger;                    return plugin.check_group_size(list_passenger.senior_adult_teen.length + 1);                }            }            $html_input_passenger.settings.function_trigger_add_passenger = function ($html_input_passenger,index_add) {                plugin.show_passenger();                plugin.update_price_when_change_passenger($html_input_passenger,index_add,'add');            }            var $filter_total_passenger_from_12_years_old = $element.find('#select_number_passenger_filter_total_passenger_from_12_years_old').data('html_select_number_passenger');            var list_group_size = plugin.settings.list_group_size;            // sort by value            list_group_size.sort(function (a, b) {                return a.from - b.from;            });            plugin.settings.list_group_size = list_group_size;            var list_string_group_size = [];            for (var i = 0; i < list_group_size.length; i++) {                var group_size = list_group_size[i];                list_string_group_size.push("(" + group_size.from + " to " + group_size.to + ")");            }            plugin.settings.list_string_group_size = list_string_group_size;            $filter_total_passenger_from_12_years_old.settings.onchange = function (total_passenger) {                var price_type=plugin.settings.price_type;                if(price_type=='flat_price')                {                }else{                    if (!plugin.check_group_size(total_passenger) ) {                        var first_group_size = plugin.settings.list_group_size[0];                        $filter_total_passenger_from_12_years_old.set_value(first_group_size.from);                        return false;                    }                }            };            if(parseInt(product.tour_length)>1) {                var $list_radio_rooming = $('#list-radio-box-rooming').data('list_radio_rooming');                if ($list_radio_rooming.get_value() != 'build_room') {                    $element.find('.form-build-room').hide();                    $element.find('.room-supplement-fee').hide();                }                $list_radio_rooming.settings.on_change = function (selected) {                    if (selected == 'build_room') {                        $element.find('.form-build-room').show();                        $element.find('.room-supplement-fee').show();                    } else {                        $element.find('.form-build-room').hide();                        $element.find('.room-supplement-fee').hide();                    }                    var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');                    if ($html_input_passenger.validate()) {                        plugin.update_price();                    }                };            }            var $form_go = $element.find('form.departure-date-select');            $form_go.submit(function () {                var $filter_total_passenger_from_12_years_old = $element.find('#select_number_passenger_filter_total_passenger_from_12_years_old').data('html_select_number_passenger');                var total_passenger_from_12_years_old= $filter_total_passenger_from_12_years_old.get_value();                var $filter_total_passenger_under_12_years_old = $element.find('#select_number_passenger_filter_total_passenger_under_12_years_old').data('html_select_number_passenger');                var total_passenger_under_12_years_old= $filter_total_passenger_under_12_years_old.get_value();                if(total_passenger_under_12_years_old>total_passenger_from_12_years_old*2)                {                    var option_dreymodal= plugin.settings.option_dreymodal;                    option_dreymodal.title = "Error";                    option_dreymodal.titleBackColor = "#bc056d";                    var alert_drey_modal = new Dreymodal('<div>Total passenger under 12 years old must lest double total passenger from 12 years old</div>',option_dreymodal);                    alert_drey_modal.open();                    return false;                }               return true;            });            $element.find('.price').autoNumeric('init', plugin.settings.config_show_price);        };        plugin.example_function = function () {        }        plugin.init();    }    // add the plugin to the jQuery.fn object    $.fn.view_bookprivategroup_default = function (options) {        // iterate through the DOM elements we are attaching the plugin to        return this.each(function () {            // if plugin has not already been attached to the element            if (undefined == $(this).data('view_bookprivategroup_default')) {                var plugin = new $.view_bookprivategroup_default(this, options);                $(this).data('view_bookprivategroup_default', plugin);            }        });    }})(jQuery);