(function ($) {

    // here we go!
    $.html_input_passenger = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            to_name: '',
            min_age: 0,
            max_age: 99,
            min_date: new Date(),
            max_date: new Date(),
            from_date: new Date(),
            departure: {
                departure_date: "",
                allow_passenger: "",
                total_day: 0
            },
            passenger_config: {},
            format: 'mm/dd/yy',
            view_format: 'mm/dd/yy',
            list_passenger: {
                senior_adult_teen: [
                    {
                        first_name: '',
                        middle_name: '',
                        last_name: '',
                        nationality: '',
                        date_of_birth: ''
                    }
                ],
                children_infant: []
            },
            output_data: [],
            event_after_change: false,
            html_item_passenger_template: '',
            range_senior_adult_teen_years: [12, 99],
            range_children_infant: [0, 11],
            debug: false,
            list_country: [],
            senior_adult_teen_title: [
                {
                    'id': 'Mr',
                    'text': 'Mr'
                },
                {
                    'id': 'Miss',
                    'text': 'Miss'
                }
            ],
            children_infant_title: [
                {
                    'id': 'title1',
                    'text': 'title1'
                },
                {
                    'id': 'title2',
                    'text': 'title2'
                }
            ],
            function_trigger_remove_passenger: null,
            function_trigger_add_passenger: null,
            function_trigger_check_allow_add_passenger: null,
            function_trigger_check_allow_remove_passenger: null
        };
        // current instance of the object
        var plugin = this;
        const SENIOR_ADULT_TEEN = "senior-adult-teen";
        const CHILDREN_INFANT = "children-infant";
        // this will hold the merged default, and user-provided options
        plugin.settings = {};
        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.update_data = function () {

            var $list_list_passenger = $element.find('.input-passenger-list-passenger');
            $list_list_passenger.each(function (index_list_passenger, list_passenger) {
                var $list_passenger = $(this);
                var group_passenger = plugin.get_type_passenger($list_passenger);
                if (group_passenger == SENIOR_ADULT_TEEN) {
                    var type = 'senior_adult_teen';
                } else {
                    var type = 'children_infant';
                }
                $list_passenger.find('.item-passenger').each(function (index_passenger) {
                    var $item_passenger = $(this);
                    $item_passenger.find('input[data-name],select[data-name]').each(function (index1, input) {
                        var $input = $(input);
                        var data_name = $input.data('name');
                        $input.attr('name', 'list_passenger[' + type + '][' + index_passenger + '][' + data_name + ']');
                    });
                    $item_passenger.find('.passenger-index').html(index_passenger + 1);
                });
            });
            var input_name = plugin.settings.input_name;
            var $input_name = $element.find('input[name="' + input_name + '"]');
            var post = $element.find('.item-passenger :input[name*="list_passenger"]').serializeObject();
            var list_passenger = post.list_passenger;
            plugin.settings.list_passenger = list_passenger;
            var list_passenger_stringify = JSON.stringify(list_passenger);
            $input_name.val(list_passenger_stringify);
            var event_after_change = plugin.settings.event_after_change;
            var current_date = plugin.settings.departure.departure_date;
            var total_day = plugin.settings.departure.total_day - 1;
            if (event_after_change instanceof Function) {
                var list_passenger_not_group = [];
                $.each(plugin.settings.list_passenger.senior_adult_teen, function (index, passenger) {
                    passenger.year_old = '' + $.get_year_old_by_date_and_current_date_and_tour_length(passenger.date_of_birth, current_date, total_day);
                    list_passenger_not_group.push(passenger);
                });
                if(typeof plugin.settings.list_passenger.children_infant!="undefined")
                {
                    $.each(plugin.settings.list_passenger.children_infant, function (index, passenger) {
                        passenger.year_old = $.get_year_old_by_date_and_current_date_and_tour_length(passenger.date_of_birth, current_date, total_day);
                        list_passenger_not_group.push(passenger);
                    });
                }
                event_after_change(list_passenger_not_group);
            }
        };
        plugin.add_passenger = function ($self) {

            var item_passenger_template = plugin.settings.item_passenger_template;
            var $list_passenger = $self.closest('.input-passenger-list-passenger');
            $list_passenger.empty();

            var group_passenger = plugin.get_type_passenger($list_passenger);

            if (group_passenger == SENIOR_ADULT_TEEN) {
                plugin.settings.list_passenger.senior_adult_teen.push(item_passenger_template);
                var list_passenger = plugin.settings.list_passenger.senior_adult_teen;
                var type = 'senior_adult_teen';
            } else {
                plugin.settings.list_passenger.children_infant.push(item_passenger_template);
                var list_passenger = plugin.settings.list_passenger.children_infant;
                var type = 'children_infant';
            }

            $.each(list_passenger, function (index, passenger) {
                if(typeof passenger!="undefined") {
                    var $item_passenger_template = $(plugin.settings.html_item_passenger_template);
                    $item_passenger_template.find('input.date').addClass(type);
                    $item_passenger_template.appendTo($list_passenger);

                    $item_passenger_template.find(':input[data-name],select[data-name]').each(function (index1, input) {
                        var $input = $(input);
                        var data_name = $input.attr('data-name');
                        $input.attr('name', 'list_passenger[' + type + '][' + index + '][' + data_name + ']');
                        $input.val(passenger[data_name]);
                    });
                    $item_passenger_template.find('.passenger-index').html(index + 1);
                    $item_passenger_template.find(':input[data-name="nationality"]').select2({
                        data: plugin.settings.list_country
                    });
                    $item_passenger_template.find(':input[data-name="gender"]').select2({});
                    plugin.update_event($item_passenger_template);
                }
            });
            $element.find('.input-passenger-list-passenger').sortable("refresh");
            plugin.update_data();
            if (typeof plugin.settings.function_trigger_add_passenger == "function") {
                plugin.settings.function_trigger_add_passenger(plugin);
            }
        };
        plugin.get_data = function () {
            return plugin.settings.list_passenger;
        };
        plugin.notify = function (content, type) {
            if (typeof  type == "undefined") {
                type = "error";
            }
            var notify = $.notify(content, {
                allow_dismiss: true,
                type: type,
                placement: {
                    align: "center"
                }
            });
        };
        plugin.check_allow_passenger = function (year_old) {
            var allow_passenger = plugin.settings.departure.allow_passenger;
            var passenger_config = plugin.settings.passenger_config;
            allow_passenger = allow_passenger.split(",");
            var all_passenger = "senior,adult,teen,child_1,child_2,infant";
            all_passenger = all_passenger.split(",");
            var else_not_allow_passenger = [];
            for (var i = 0; i < all_passenger.length; i++) {
                var item_passenger = all_passenger[i];
                if (allow_passenger.indexOf(item_passenger) == -1) {
                    else_not_allow_passenger.push(item_passenger);
                }
            }
            for (var i = 0; i < else_not_allow_passenger.length; i++) {
                var item_allow_passenger = else_not_allow_passenger[i];
                if (item_allow_passenger == "senior" && year_old >= passenger_config.senior_passenger_age_from && year_old <= passenger_config.senior_passenger_age_to) {
                    var content_notify = "not allow senior(" + passenger_config.senior_passenger_age_from + "-" + passenger_config.senior_passenger_age_to + ")";
                    plugin.notify(content_notify);
                    return false;
                } else if (item_allow_passenger == "adult" && year_old >= passenger_config.adult_passenger_age_from && year_old <= passenger_config.adult_passenger_age_to) {
                    var content_notify = "not allow adult(" + passenger_config.adult_passenger_age_from + "-" + passenger_config.adult_passenger_age_to + ")";
                    plugin.notify(content_notify);
                    return false;
                } else if (item_allow_passenger == "teen" && year_old >= passenger_config.teen_passenger_age_from && year_old <= passenger_config.teen_passenger_age_to) {
                    var content_notify = "not allow teen(" + passenger_config.teen_passenger_age_from + "-" + passenger_config.teen_passenger_age_to + ")";
                    plugin.notify(content_notify);
                    return false;
                } else if (item_allow_passenger == "child_1" && year_old >= passenger_config.children_1_passenger_age_from && year_old <= passenger_config.children_1_passenger_age_to) {
                    var content_notify = "not allow child_1(" + passenger_config.children_1_passenger_age_from + "-" + passenger_config.children_1_passenger_age_to + ")";
                    plugin.notify(content_notify);
                    return false;
                } else if (item_allow_passenger == "child_2" && year_old >= passenger_config.children_2_passenger_age_from && year_old <= passenger_config.children_2_passenger_age_to) {
                    var content_notify = "not allow child_2(" + passenger_config.children_2_passenger_age_from + "-" + passenger_config.children_2_passenger_age_to + ")";
                    plugin.notify(content_notify);
                    return false;
                } else if (item_allow_passenger == "infant" && year_old >= passenger_config.infant_passenger_age_from && year_old <= passenger_config.infant_passenger_age_to) {
                    var content_notify = "not allow infant(" + passenger_config.infant_passenger_age_from + "-" + passenger_config.infant_passenger_age_to + ")";
                    plugin.notify(content_notify);
                    return false;
                }
            }
            return true;
        };
        plugin.check_date = function (dateText, inst) {
            var $input = $(inst.input);
            var range_children_infant = plugin.settings.range_children_infant;
            var range_senior_adult_teen_years = plugin.settings.range_senior_adult_teen_years;
            var current_date = plugin.settings.departure.departure_date;
            var total_day = plugin.settings.departure.total_day - 1;
            var year_old = $.get_year_old_by_date_and_current_date_and_tour_length(dateText, current_date, total_day);
            var group_passenger = $input.closest('.input-passenger-list-passenger').hasClass('senior-adult-teen') ? SENIOR_ADULT_TEEN : CHILDREN_INFANT;
            if (group_passenger == SENIOR_ADULT_TEEN) {
                if (year_old >= range_children_infant[0] && year_old <= range_children_infant[1]) {
                    var content_notify = "our of range senior adult teen year old";
                    plugin.notify(content_notify);
                    return false;
                }
            } else if (year_old >= range_senior_adult_teen_years[0] && year_old <= range_senior_adult_teen_years[1]) {
                var content_notify = "our of range children infant year old";
                plugin.notify(content_notify);
                return false;
            }
            if (year_old < range_children_infant[0] || year_old > range_senior_adult_teen_years[1]) {
                var content_notify = "year old not avaibel";
                plugin.notify(content_notify);
                return false;
            }
            if (!plugin.check_allow_passenger(year_old)) {
                return false;
            }
            return true;
        };
        plugin.update_event = function ($wrapper) {
           /* $wrapper.find('input[name],select[name]').change(function update_data() {
                plugin.update_data();
            });*/
            $wrapper.find('.add').click(function add_passenger() {

                if (typeof plugin.settings.function_trigger_check_allow_add_passenger == "function") {
                    if(!plugin.settings.function_trigger_check_allow_add_passenger(plugin)){
                        return false;
                    }
                }
                plugin.add_passenger($(this));
            });
            $wrapper.find('.remove').click(function remove_passenger() {
                if (typeof plugin.settings.function_trigger_check_allow_remove_passenger == "function") {
                    if(!plugin.settings.function_trigger_check_allow_remove_passenger(plugin)){
                        return false;
                    }
                }
                plugin.remove_passenger($(this));
            });
            var view_format = plugin.settings.view_format;
            $wrapper.find('input[data-name="date_of_birth"]').each(function (index_date_of_birth) {
                var $self = $(this);
                var option_date_picker = {
                    showButtonPanel: true,
                    showWeek: true,
                    minDate: "-99Y",
                    maxDate: '0',
                    dateFormat: view_format,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-64:+0",
                    onSelect:function(dateText,inst){
                        if (!plugin.check_date(dateText, inst)) {
                            //$(this).datepicker('setDate', inst.lastVal);
                            $(this).val(inst.lastVal);
                        }
                        plugin.update_data();
                    }
                };
                var group_passenger = plugin.get_type_passenger($self);
                if (group_passenger == SENIOR_ADULT_TEEN) {

                    /*
                     option_date_picker.minDate='-'+ plugin.settings.range_senior_adult_teen_years[1]+'Y';
                     option_date_picker.maxDate='-'+plugin.settings.range_senior_adult_teen_years[0]+'Y';
                     option_date_picker.yearRange='-'+plugin.settings.range_senior_adult_teen_years[1]+':-'+plugin.settings.range_senior_adult_teen_years[0];
                     */
                } else {
                    /*
                     option_date_picker.minDate='-'+plugin.settings.range_children_infant[1]+'Y';
                     option_date_picker.yearRange='-'+plugin.settings.range_children_infant[1]+':-'+plugin.settings.range_children_infant[0];
                     */
                }
                $self.datepicker(option_date_picker);
            });
        };
        plugin.get_type_passenger = function ($self) {
            var $list_passenger = $self.closest('.input-passenger-list-passenger');
            if ($list_passenger.hasClass('senior-adult-teen')) {
                return SENIOR_ADULT_TEEN;
            } else {
                return CHILDREN_INFANT;
            }
        };
        plugin.remove_last_passenger=function(type_passenger){
            if($.inArray(type_passenger,[0,1])==-1){
                return false;
            }
            if(type_passenger==0){
                type_passenger=SENIOR_ADULT_TEEN;
            }else {
                type_passenger=CHILDREN_INFANT;
            }
            var $list_list_passenger = $element.find('.input-passenger-list-passenger.'+type_passenger+' .item-passenger:last-child .remove').trigger('click');

        };
        plugin.remove_passenger = function ($self) {
            var $list_passenger = $self.closest('.input-passenger-list-passenger');
            var $item_passenger = $self.closest('.item-passenger');
            var index_passenger = $item_passenger.index();
            $item_passenger.remove();
            var group_passenger = plugin.get_type_passenger($list_passenger);
            if (group_passenger == SENIOR_ADULT_TEEN) {
                var list_passenger = plugin.settings.list_passenger.senior_adult_teen;
                list_passenger.splice(index_passenger, 1);
                plugin.settings.list_passenger.senior_adult_teen = list_passenger;
            } else {
                var list_passenger = plugin.settings.list_passenger.children_infant;
                list_passenger.splice(index_passenger, 1);
                plugin.settings.list_passenger.children_infant = list_passenger;
            }
            if (typeof plugin.settings.function_trigger_remove_passenger == "function") {
                plugin.settings.function_trigger_remove_passenger(plugin);
            }
            plugin.update_data();

        };
        plugin.validate = function () {
            for(var i=0;i<$element.find('.row.item-passenger').length;i++){
                var $passenger = $element.find('.row.item-passenger:eq('+i+')');
                var $first_name=$passenger.find('input[data-name="first_name"]');
                var $last_name=$passenger.find('input[data-name="last_name"]');
                var $date_of_birth=$passenger.find('input[data-name="date_of_birth"]');
                if($first_name.val().trim()==""){
                    plugin.notify('please input first name');
                    $first_name.focus();
                    return false;
                }else if($last_name.val().trim()==""){
                    plugin.notify('please input last name');
                    $last_name.focus();
                    return false;
                }else if($date_of_birth.val().trim()==""){
                    plugin.notify('please input date of birth');
                    $date_of_birth.focus();
                    return false;
                }
            }
            return true;

        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger = list_passenger;
            plugin.render_input_person();
        };
        plugin.setup_total_senior_adult_teen = function (total_senior_adult_teen) {
            for (var i = 0; i < total_senior_adult_teen; i++) {

                $element.find('.input-passenger-list-passenger.senior-adult-teen .btn.add:first').trigger('click');
            }
        }
        plugin.setup_total_children_infant = function (total_children_infant) {
            if(total_children_infant==0){
                $element.find('.input-passenger-list-passenger.children-infant .btn.remove:first').trigger('click');
                $element.find('.row.person-type:last').hide();
            }else {
                var current_total_passenger = plugin.settings.list_passenger.children_infant.length;
                console.log(total_children_infant);
                var start = Math.abs(total_children_infant - current_total_passenger);
                for (var i = 0; i < start; i++) {
                    plugin.add_passenger($element.find('.input-passenger-list-passenger.children-infant'));
                }
                $element.find('.row.person-type:last').show();
            }
        }
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var min_age = plugin.settings.min_age;
            var max_age = plugin.settings.max_age;
            var range_senior_adult_teen_years = plugin.settings.range_senior_adult_teen_years;

            if (range_senior_adult_teen_years[0] < min_age) {
                plugin.settings.range_senior_adult_teen_years[0] = min_age;
            }
            if (range_senior_adult_teen_years[1] > max_age) {
                plugin.settings.range_senior_adult_teen_years[1] = max_age;
            }
            var range_children_infant = plugin.settings.range_children_infant;

            if (range_children_infant[0] < min_age) {
                //plugin.settings.range_children_infant[0] = min_age;
            }
            if (range_children_infant[1] > max_age) {
                //plugin.settings.range_children_infant[1] = max_age;
            }
            var html_item_passenger_template = $element.find('.item-passenger').getOuterHTML();
            plugin.settings.html_item_passenger_template = html_item_passenger_template;
            var item_passenger_template = plugin.settings.list_passenger.senior_adult_teen[0];
            plugin.settings.item_passenger_template = plugin.settings.list_passenger.senior_adult_teen;
            if (range_children_infant[1] >= min_age) {
                plugin.settings.list_passenger.children_infant.push(item_passenger_template);
                $(html_item_passenger_template).appendTo($element.find('.input-passenger-list-passenger.children-infant'));
            }else{
                $element.find('.row.person-type:last').hide();

            }
            var $list_list_passenger = $element.find('.input-passenger-list-passenger');
            $list_list_passenger.each(function (index_list_passenger, list_passenger) {
                var $list_passenger = $(this);
                var group_passenger = plugin.get_type_passenger($list_passenger);
                if (group_passenger == SENIOR_ADULT_TEEN) {
                    var type = 'senior_adult_teen';
                } else {
                    var type = 'children_infant';
                }
                $list_passenger.find('.item-passenger').each(function (index_passenger) {
                    var $item_passenger = $(this);
                    $item_passenger.find('input[data-name],select[data-name]').each(function (index1, input) {
                        var $input = $(input);
                        var data_name = $input.data('name');
                        $input.attr('name', 'list_passenger[' + type + '][' + index_passenger + '][' + data_name + ']');
                    });
                    $item_passenger.find('.passenger-index').html(index_passenger + 1);
                    $item_passenger.find(':input[data-name="nationality"]').select2({
                        data: plugin.settings.list_country
                    });
                    $item_passenger.find(':input[data-name="gender"]').select2({});
                });
            });
            plugin.update_event($element);
            $element.find('.input-passenger-list-passenger').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                items: ">.item-passenger",
                stop: function (event, ui) {
                    var $list_list_passenger = $element.find('.input-passenger-list-passenger');
                    $list_list_passenger.each(function (index_list_passenger) {
                        $(this).find('.passenger-index').each(function (index_passenger) {
                            $(this).html(index_passenger + 1);
                        });
                    });
                    plugin.update_data();
                }
            });
            //$element.find('.input-passenger-list-passenger').disableSelection();
            plugin.update_data();
            //plugin.render_input_person();
            var debug = plugin.settings.debug;
            if (debug) {
                $element.find('.auto-fill-date').click(function (event) {
                    $element.find('input:not(.date)').delorean({
                        type: 'words',
                        amount: 1,
                        character: 'Name',
                        tag: ''
                    }).trigger('change');
                    $element.find('input.date.senior_adult_teen').each(function () {
                        var date = $.randomDate(new Date(1917, 0, 1), new Date(2004, 0, 1));
                        date = moment(date);
                        $(this).val(date.format('MM/DD/YYYY')).trigger('change');
                    });
                    $element.find('input.date.children_infant').each(function () {
                        var date = $.randomDate(new Date(2005, 0, 1), new Date(2016, 0, 1));
                        date = moment(date);
                        $(this).val(date.format('MM/DD/YYYY')).trigger('change');
                    });
                    var items = [''];
                    var item = items[Math.floor(Math.random() * items.length)];
                    $element.find('input.date.children_infant').each(function () {
                        var date = $.randomDate(new Date(2005, 0, 1), new Date(2016, 0, 1));
                        date = moment(date);
                        $(this).val(date.format('MM/DD/YYYY')).trigger('change');
                    });
                });
/*
                for (var i = 0; i < 5; i++) {
                    $element.find('.input-passenger-list-passenger.senior-adult-teen .btn.add:first').trigger('click');
                    $element.find('.input-passenger-list-passenger.children-infant .btn.add:first').trigger('click');
                    $element.find('.auto-fill-date').trigger('click');
                }
*/
            }
        };
        plugin.init();
    };
    // add the plugin to the jQuery.fn object
    $.fn.html_input_passenger = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_input_passenger')) {
                var plugin = new $.html_input_passenger(this, options);
                $(this).data('html_input_passenger', plugin);
            }
        });
    }
})(jQuery);


