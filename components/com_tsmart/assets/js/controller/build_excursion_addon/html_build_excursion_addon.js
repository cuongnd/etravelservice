(function ($) {

    // here we go!
    $.html_build_excursion_addon = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            to_name: '',
            list_cost_for_passenger:[],
            min_date: new Date(),
            max_date: new Date(),
            from_date: new Date(),
            to_date: new Date(),
            display_format: 'YYYY-MM-DD',
            format: 'YYYY-MM-DD',
            list_passenger: [],
            departure: {
                departure_date: "",
                allow_passenger: "",
                total_day: 0
            },
            config_show_price: {
                mDec: 1,
                aSep: ' ',
                aSign: 'US$'
            },
            list_data_excursion_addon: [],
            passenger_config: {},
            output_data: [],

            list_excursion: [

            ],
            item_excursion_template: {
                    passengers: [],
                    excursion_type: 'basic',
                    excursion_note: ''
                }
            ,
            infant_title: "Infant",
            teen_title: "Teen",
            children_title: "Children",
            adult_title: "Adult",
            senior_title: "Adult",
            event_after_change: false,
            update_passenger: false,
            limit_total: false,
            trigger_after_change: null,
            private_bed: "private bed",
            share_bed: "Share bed with others",
            extra_bed: "Extra  bed",
            basic: {
                max_adult: 1,
                max_children: 1,
                max_infant: 0,
                max_total: 1,
                validate_excursion: function (excursion_item, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length > max_total) {
                        var content_notify = 'our policy does not allow two persons to stay in a basic excursion. You are suggested to select a twin/double excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_excursion_index(excursion_index) || plugin.exists_children_2_in_excursion_index(excursion_index)) {
                        var content_notify = 'our policy does not allow a child under 5 years to stay alone in basic excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (excursion_item, range_year_old_infant, passenger_index_selected, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var content_notify = 'you cannot add infant(2-5) to sigle excursion, you can add adult or children (>6) inside excursion';
                    plugin.notify(content_notify);
                    $excursion_item.find('.list-passenger').removeClass('error');
                    $excursion_item.find('.list-passenger').tipso('destroy');
                    $excursion_item.find('.list-passenger').tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    }).addClass('error');
                    $excursion_item.find('.list-passenger').tipso('show');
                    $excursion_item.find('.list-passenger').addClass('error');
                    return false;
                },
                enable_select_infant2: function (excursion_item, range_year_old_children_2, passenger_index_selected, excursion_index) {
                    return false;
                },
                enable_select_children: function (excursion_item, range_year_old_children_1, passenger_index_selected, excursion_index) {
                    return true;
                },
                enable_select_senior_adult_teen: function (excursion_item, range_year_old_senior_adult_teen, passenger_index_selected, excursion_index) {
                    return true;
                }
            },
            double: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 2,
                max_total: 3,
                validate_excursion: function (excursion_item, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    var min_total = plugin.settings[excursion_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in excursion is ' + min_total;
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in excursion is ' + max_total;
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_children_2_in_excursion_index(excursion_index) && (!plugin.exists_senior_adult_teen_in_excursion_index(excursion_index) && !plugin.exists_children_1_in_excursion_index(excursion_index) )) {
                        var content_notify = 'excursion exists infant (2-5) you need add adult or children in this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_excursion_index(excursion_index) && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        var content_notify = 'our policy does not allow infant to stay in   excursion other children. You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_excursion_is_adult_or_children(excursion_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin excursion . You are suggested to select a triple excursion instead.';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (excursion_item, range_year_old_infant, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (excursion_item, range_year_old_children_2, passenger_index_selected, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_excursion_index(excursion_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in excursion you need add one adult(>=12) or children (6-11) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_excursion_index(excursion_index) && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in excursion  you need add one adult(>=12) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (excursion_item, range_year_old_children_1, passenger_index_selected, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    console.log('max_total:' + max_total);
                    console.log('passengers_length:' + passengers.length);
                    var all_passenger_is_infant_and_children = function (passengers) {
                        var all_passenger_is_infant_or_children = true;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_senior_adult_teen(passenger_index)) {
                                all_passenger_is_infant_or_children = false;
                                break;
                            }
                        }
                        return all_passenger_is_infant_or_children;
                    };
                    var exists_infant_in_excursion = function (passengers) {
                        var exists_infant_in_excursion = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_excursion = true;
                                break;
                            }
                        }
                        return exists_infant_in_excursion;
                    };
                    var total_adult_and_children = 0;
                    for (var i = 0; i < passengers.length; i++) {
                        var passenger_index = passengers[i];
                        if (plugin.is_senior_adult_teen(passenger_index) || plugin.is_children_1(passenger_index)) {
                            total_adult_and_children++;
                        }
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_excursion_index(excursion_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'exists baby infant(0-1) please select one adult(>=12)';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        return false;
                    } else if (passengers.length == max_total - 1 && total_adult_and_children == max_total - 1) {
                        var content_notify = 'double excursion max total adult and children is two person, if you want this  you should select twin excursion, or triple excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (excursion_item, range_year_old_senior_adult_teen, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var max_total = plugin.settings[excursion_type].max_total;
                    var all_passenger_is_adult = function (passengers) {
                        var all_passenger_is_adult = true;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (!plugin.is_senior_adult_teen(passenger_index)) {
                                all_passenger_is_adult = false;
                                break;
                            }
                        }
                        return all_passenger_is_adult;
                    };
                    if (passengers.length == max_total - 1 && all_passenger_is_adult(passengers)) {
                        var content_notify = 'double excursion max total adult and children is two person, if you want this  you should select twin excursion, or triple excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        return false;
                    }
                    return true;
                }
            },
            twin: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 2,
                max_total: 3,
                validate_excursion: function (excursion_item, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    var min_total = plugin.settings[excursion_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in excursion is ' + min_total;
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in excursion is ' + max_total;
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_excursion_index(excursion_index) && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   excursion . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_excursion_is_adult_or_children(excursion_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin excursion . You are suggested to select a triple excursion instead';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (excursion_item, range_year_old_infant, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (excursion_item, range_year_old_children_2, passenger_index_selected, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_excursion_index(excursion_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in excursion you need add one adult(>=12) or children (6-11) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_excursion_index(excursion_index) && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in excursion  you need add one adult(>=12) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (excursion_item, range_year_old_children_1, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var max_total = plugin.settings[excursion_type].max_total;
                    var all_passenger_is_infant_and_children = function (passengers) {
                        var all_passenger_is_infant_or_children = true;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_senior_adult_teen(passenger_index)) {
                                all_passenger_is_infant_or_children = false;
                                break;
                            }
                        }
                        return all_passenger_is_infant_or_children;
                    };
                    var exists_infant_in_excursion = function (passengers) {
                        var exists_infant_in_excursion = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_excursion = true;
                                break;
                            }
                        }
                        return exists_infant_in_excursion;
                    };
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_excursion_index(excursion_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children excursion  exists baby infant (0-1) in excursion  you need add one adult(>=12) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (excursion_item, range_year_old_senior_adult_teen, passenger_index_selected, excursion_index) {
                    return true;
                }
            },
            triple: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 3,
                max_total: 4,
                validate_excursion: function (excursion_item, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    var min_total = plugin.settings[excursion_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in excursion is ' + min_total;
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in excursion is ' + max_total + ' please eject some person';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_excursion_index(excursion_index) && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   excursion . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.get_total_senior_adult_teen(excursion_index) == 4) {
                        var content_notify = 'Our policy does not allow 4 teeners/adults/seniors to share the same excursion.You are suggested to select 2 excursions .You are suggested to select 2 excursions';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.get_total_senior_adult_teen(excursion_index) == 3 && plugin.exists_children_1_in_excursion_index(excursion_index)) {
                        var content_notify = 'Our policy does not allow a child over 6 years old to share the same excursion with 3 teeners/adults/seniors .You are suggested to select 2 excursions';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (excursion_item, range_year_old_infant, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (excursion_item, range_year_old_children_2, passenger_index_selected, excursion_index) {
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var max_total = plugin.settings[excursion_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_excursion_index(excursion_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in excursion you need add one adult(>=12) or children (6-11) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_excursion_index(excursion_index) && !plugin.exists_senior_adult_teen_in_excursion_index(excursion_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in excursion  you need add one adult(>=12) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (excursion_item, range_year_old_children_1, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var max_total = plugin.settings[excursion_type].max_total;
                    var all_passenger_is_infant_and_children = function (passengers) {
                        var all_passenger_is_infant_or_children = true;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_senior_adult_teen(passenger_index)) {
                                all_passenger_is_infant_or_children = false;
                                break;
                            }
                        }
                        return all_passenger_is_infant_or_children;
                    };
                    var exists_infant_in_excursion = function (passengers) {
                        var exists_infant_in_excursion = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_excursion = true;
                                break;
                            }
                        }
                        return exists_infant_in_excursion;
                    };
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_excursion_index(excursion_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children excursion  exists baby infant (0-1) in excursion  you need add one adult(>=12) inside this excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        $excursion_item.find('.list-passenger').addClass('error');
                        return false;
                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (excursion_item, range_year_old_senior_adult_teen, passenger_index_selected, excursion_index) {
                    var excursion_type = excursion_item.excursion_type;
                    var passengers = excursion_item.passengers;
                    var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                    var max_total = plugin.settings[excursion_type].max_total;
                    var all_passenger_is_adult = function (passengers) {
                        var all_passenger_is_adult = true;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (!plugin.is_senior_adult_teen(passenger_index)) {
                                all_passenger_is_adult = false;
                                break;
                            }
                        }
                        return all_passenger_is_adult;
                    };
                    if (passengers.length == max_total - 1 && all_passenger_is_adult(passengers)) {
                        var content_notify = 'triple excursion max total adult is three person, you should add more excursion';
                        plugin.notify(content_notify);
                        $excursion_item.find('.list-passenger').removeClass('error');
                        $excursion_item.find('.list-passenger').tipso('destroy');
                        $excursion_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $excursion_item.find('.list-passenger').tipso('show');
                        return false;
                    }
                    return true;
                }
            },
            element_key: 'html_build_excursion_addon',
            debug: false,
            range_year_old_infant: [0, 1],
            range_year_old_children_2: [2, 5],
            range_year_old_children_1: [6, 11],
            range_year_old_teen: [12, 17],
            range_year_old_adult: [18, 65],
            range_year_old_senior: [66, 99],
            range_year_old_infant_and_children_2: [0, 5],
            range_year_old_senior_adult_teen: [12, 99],
            range_adult_and_children: [6, 99],
        };
        // current instance of the object
        var plugin = this;
        // this will hold the merged default, and user-provided options
        plugin.settings = {};
        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.on_change = function (start, end) {
        };
        plugin.get_list_excursion = function () {
            plugin.update_data();
            return plugin.settings.list_excursion;
        }
        plugin.get_list_passenger = function () {
            plugin.update_data();
            return plugin.settings.list_passenger;
        }
        plugin.get_total_senior_adult_teen = function (excursion_index) {
            var total_adult = 0;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var exists_infant2_in_excursion = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    total_adult++;
                }
            }
            return total_adult;
        };
        plugin.render_input_person = function () {
        };
        plugin.all_passenger_in_excursion_is_adult = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var all_passenger_in_excursion_is_adult = true;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index) || plugin.is_children_1(passenger_index) || plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    all_passenger_in_excursion_is_adult = false;
                    break;
                }
            }
            return all_passenger_in_excursion_is_adult;
        };
        plugin.trigger_after_change = function () {
            var list_excursion = plugin.settings.list_excursion;
            var trigger_after_change = plugin.settings.trigger_after_change;
            if (trigger_after_change instanceof Function) {
                trigger_after_change(list_excursion);
            }
        };
        plugin.all_passenger_in_excursion_is_adult_or_children = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var all_passenger_in_excursion_is_adult_or_children = true;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    all_passenger_in_excursion_is_adult_or_children = false;
                    break;
                }
            }
            return all_passenger_in_excursion_is_adult_or_children;
        };
        plugin.get_senior_adult_teen_passenger_order_in_excursion = function (excursion_index, order) {
            var adult_passenger_index_of_order = -1;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var a_order = 0;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    if (a_order == order) {
                        adult_passenger_index_of_order = passenger_index;
                        break;
                    }
                    a_order++;
                }
            }
            return adult_passenger_index_of_order;
        };
        plugin.get_infant_or_children_2_passenger_order_in_excursion = function (excursion_index, order) {
            var infant_or_children_2_passenger_index_of_order = -1;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var a_order = 0;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    if (a_order == order) {
                        infant_or_children_2_passenger_index_of_order = passenger_index;
                        break;
                    }
                    a_order++;
                }
            }
            return infant_or_children_2_passenger_index_of_order;
        };
        plugin.get_children_2_passenger_order_in_excursion = function (excursion_index, order) {
            var children_2_passenger_index_of_order = -1;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var a_order = 0;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    if (a_order == order) {
                        children_2_passenger_index_of_order = passenger_index;
                        break;
                    }
                    a_order++;
                }
            }
            return children_2_passenger_index_of_order;
        };
        plugin.get_children_1_passenger_order_in_excursion = function (excursion_index, order) {
            var children_1_passenger_index_of_order = -1;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var a_order = 0;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    if (a_order == order) {
                        children_1_passenger_index_of_order = passenger_index;
                        break;
                    }
                    a_order++;
                }
            }
            return children_1_passenger_index_of_order;
        };
        plugin.is_adult = function (passenger_index) {
            var range_year_old_adult = plugin.settings.range_year_old_adult;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old >= range_year_old_adult[0] && passenger.year_old <= range_year_old_adult[1];
        };
        plugin.is_senior = function (passenger_index) {
            var range_year_old_senior = plugin.settings.range_year_old_senior;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old >= range_year_old_senior[0] && passenger.year_old <= range_year_old_senior[1];
        };
        plugin.get_price_tour_cost_by_passenger_index = function (passenger_index, price_senior, price_adult, price_teen) {
            if (plugin.is_senior(passenger_index)) {
                return price_senior;
            } else if (plugin.is_adult(passenger_index)) {
                return price_adult;
            } else if (plugin.is_teen(passenger_index)) {
                return price_teen;
            }
        };
        plugin.swith_index = function (passenger_index_1, passenger_index_2, get_min) {
            var list_passenger = plugin.settings.list_passenger;
            var passenger_1 = list_passenger[passenger_index_1];
            var passenger_2 = list_passenger[passenger_index_2];
            if (typeof get_min == "undefined") {
                get_min = true;
            }
            if (get_min) {
                if (passenger_1.year_old <= passenger_2.year_old) {
                    return passenger_index_1;
                } else {
                    return passenger_index_2;
                }
            } else {
                if (passenger_1.year_old <= passenger_2.year_old) {
                    return passenger_index_2;
                } else {
                    return passenger_index_1;
                }
            }
        };
        plugin.get_all_children_2_in_excursion = function (excursion_index) {
            var list_all_children_2_in_excursion = [];
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    list_all_children_2_in_excursion.push(passenger_index);
                }
            }
            return list_all_children_2_in_excursion;
        };
        plugin.get_all_children_1_in_excursion = function (excursion_index) {
            var list_all_children_1_in_excursion = [];
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    list_all_children_1_in_excursion.push(passenger_index);
                }
            }
            return list_all_children_1_in_excursion;
        };
        plugin.get_all_infant_children_2_in_excursion = function (excursion_index) {
            var list_all_infant_children_2_in_excursion = [];
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    list_all_infant_children_2_in_excursion.push(passenger_index);
                }
            }
            return list_all_infant_children_2_in_excursion;
        };
        plugin.calculator_tour_cost_and_excursion_price = function () {
            var list_excursion = plugin.get_list_excursion();
            var list_passenger = plugin.get_list_passenger();
            var senior_passenger_age_to = plugin.settings.passenger_config.senior_passenger_age_to,
                senior_passenger_age_from = plugin.settings.passenger_config.senior_passenger_age_from,
                adult_passenger_age_to = plugin.settings.passenger_config.adult_passenger_age_to,
                adult_passenger_age_from = plugin.settings.passenger_config.adult_passenger_age_from,
                teen_passenger_age_to = plugin.settings.passenger_config.teen_passenger_age_to,
                teen_passenger_age_from = plugin.settings.passenger_config.teen_passenger_age_from,
                children_1_passenger_age_to = plugin.settings.passenger_config.children_1_passenger_age_to,
                children_1_passenger_age_from = plugin.settings.passenger_config.children_1_passenger_age_from,
                children_2_passenger_age_to = plugin.settings.passenger_config.children_2_passenger_age_to,
                children_2_passenger_age_from = plugin.settings.passenger_config.children_2_passenger_age_from,
                infant_passenger_age_to = plugin.settings.passenger_config.infant_passenger_age_to,
                infant_passenger_age_from = plugin.settings.passenger_config.infant_passenger_age_from;
            var sale_price_senior = plugin.settings.departure.sale_price_senior,
                sale_price_adult = plugin.settings.departure.sale_price_adult,
                sale_price_teen = plugin.settings.departure.sale_price_teen,
                sale_price_children1 = plugin.settings.departure.sale_price_children1,
                sale_price_children2 = plugin.settings.departure.sale_price_children2,
                sale_price_infant = plugin.settings.departure.sale_price_infant,
                sale_price_private_excursion = plugin.settings.departure.sale_price_private_excursion,
                sale_price_extra_bed = plugin.settings.departure.sale_price_extra_bed,
                sale_promotion_price_senior = plugin.settings.departure.sale_promotion_price_senior,
                sale_promotion_price_adult = plugin.settings.departure.sale_promotion_price_adult,
                sale_promotion_price_teen = plugin.settings.departure.sale_promotion_price_teen,
                sale_promotion_price_children1 = plugin.settings.departure.sale_promotion_price_children1,
                sale_promotion_price_children2 = plugin.settings.departure.sale_promotion_price_children2,
                sale_promotion_price_infant = plugin.settings.departure.sale_promotion_price_infant,
                sale_promotion_price_private_excursion = plugin.settings.departure.sale_promotion_price_private_excursion,
                sale_promotion_price_extra_bed = plugin.settings.departure.sale_promotion_price_extra_bed;
            var departure = plugin.settings.departure;
            var full_charge_children1 = departure.full_charge_children1;
            full_charge_children1 = full_charge_children1 == 1 ? true : false;
            var full_charge_children2 = departure.full_charge_children2;
            full_charge_children2 = full_charge_children2 == 1 ? true : false;
            var tsmart_promotion_price_id = departure.tsmart_promotion_price_id;
            var tsmart_promotion_price_id = tsmart_promotion_price_id != null && tsmart_promotion_price_id != 0;
            var price_senior = tsmart_promotion_price_id ? sale_promotion_price_senior : sale_price_senior;
            var price_adult = tsmart_promotion_price_id ? sale_promotion_price_adult : sale_price_adult;
            var price_teen = tsmart_promotion_price_id ? sale_promotion_price_teen : sale_price_teen;
            var price_children1 = tsmart_promotion_price_id ? sale_promotion_price_children1 : sale_price_children1;
            var price_children2 = tsmart_promotion_price_id ? sale_promotion_price_children2 : sale_price_children2;
            var price_infant = tsmart_promotion_price_id ? sale_promotion_price_infant : sale_price_infant;
            var price_private_excursion = tsmart_promotion_price_id ? sale_promotion_price_private_excursion : sale_price_private_excursion;
            var price_extra_bed = tsmart_promotion_price_id ? sale_promotion_price_extra_bed : sale_price_extra_bed;
            for (var i = 0; i < list_excursion.length; i++) {
                var excursion_item = list_excursion[i];
                var excursion_index = i;
                var passengers = excursion_item.passengers;
                if (passengers.length == 0) {
                    continue;
                }
                var excursion_type = excursion_item.excursion_type;
                excursion_item.tour_cost_and_excursion_price = [];
                var func_set_tour_cost_and_excursion_price = function (excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note) {
                    var item_passenger = {};
                    item_passenger.passenger_index = passenger_index;
                    item_passenger.tour_cost = tour_cost;
                    item_passenger.excursion_price = excursion_price;
                    if (typeof extra_bed_price == "undefined") {
                        extra_bed_price = 0;
                    }
                    item_passenger.extra_bed_price = extra_bed_price;
                    if (typeof msg == "undefined") {
                        msg = "";
                    }
                    item_passenger.msg = msg;
                    if (typeof bed_note == "undefined") {
                        bed_note = "";
                    }
                    item_passenger.bed_note = bed_note;
                    excursion_item.tour_cost_and_excursion_price.push(item_passenger);
                    return excursion_item;
                };
                if (excursion_type == "basic") {
                    if (passengers.length == 1 && plugin.get_total_senior_adult_teen(excursion_index) == 1) {
                        //case 0
                        console.log("case 0");
                        var msg = "The tour cost for this passenger is based on excursion for 2 persons.  Therefore he or she is required to pay the excursion supplement fee while taking a private excursion.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = price_private_excursion;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                    } else if (plugin.get_total_children_1(excursion_index) == 1) {
                        //case 1a
                        console.log("case 1a");
                        if (full_charge_children1) {
                            var msg = "The tour cost for this passenger is based on excursion for 2 persons.  Therefore he or she is required to pay the basic excursion supplement fee while taking a private excursion.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while taking a private excursion.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion * 2;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                    }
                } else if (excursion_type == "double" || excursion_type == "twin") {
                    //Double OR Twin
                    console.log('case excursion_type == "double" || excursion_type == "twin" ');
                    if (passengers.length == 2 && plugin.get_total_senior_adult_teen(excursion_index) == 2) {
                        //case case 2 adult (case 0)
                        //add adult first
                        console.log("case 0");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 1) {
                        //case case 2a 1 Adult ,1 Child ( 6 - 11 years)
                        console.log("case 2a");
                        //add adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //end add adult first
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a twin/double sharing excursion, he or she is required to pay half excursion fee.";
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        //case 2b Adult,1 Child ( 0 - 5 years)
                        //add adult first
                        console.log("case 2b");
                        var msg = "This passenger is required to pay the excursion supplement fee to grant the free stay to kid sharing this excursion.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = price_private_excursion;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 2 && plugin.get_total_children_1(excursion_index) == 2) {
                        //2 Child ( 6 - 11 years) (case 2c)
                        console.log("case 2c");
                        for (var p_index = 0; p_index < 2; p_index++) {
                            if (full_charge_children1) {
                                var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, p_index);
                                var tour_cost = price_children1;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, p_index);
                                var tour_cost = price_children1;
                                var excursion_price = price_private_excursion;
                                var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a twin/double sharing excursion, he or she is required to pay half excursion fee.";
                                var bed_note = plugin.settings.private_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                            }
                        }
                    } else if (passengers.length == 2 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_children_2(excursion_index) == 1) {
                        //case 2d Child ( 6 - 11 years),1 Child ( 2 - 5 years)
                        console.log("case 2d");
                        var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a twin/double sharing excursion, he or she is required to pay half excursion fee.";
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        var passenger_index = plugin.get_infant_or_children_2_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = price_children2;
                        var excursion_price = price_private_excursion;
                        var msg = "include any excursion fee. Therefore if you assign this shild to a twin/double sharing excursion, he or she is required to pay half excursion fee.";
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 2 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        //2 adult 1 children (0-5)
                        //addd 2 adult first
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //children (0-5)
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {

                        //1 adult 1 children (6-11), 1 children (0-5)
                        console.log("case 3f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a twin/double sharing excursion, he or she is required to pay half excursion fee.";
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(excursion_index) == 2 && plugin.get_total_children_2(excursion_index) == 1) {
                        //2 children (6-11), 1 children (2-5) case 3g
                        console.log("case 3g");
                        for (var p_index = 0; p_index < 2; p_index++) {
                            if (full_charge_children1) {
                                var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, p_index);
                                var tour_cost = price_children1;
                                var excursion_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, p_index);
                                var tour_cost = price_children1;
                                var excursion_price = price_private_excursion;
                                var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a twin/double sharing excursion, he or she is required to pay half excursion fee.";
                                var bed_note = plugin.settings.private_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                            }
                        }
                        var passenger_index = plugin.get_children_2_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = price_children2;
                        var excursion_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 2) {

                        //1adult 2 children 0-5 (case 3i)
                        console.log("case 3i");
                        //adult
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //2 children (0-5)
                        var msg = "";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            }
                        }
                    }
                } else if (excursion_type == "triple") {
                    if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 3) {
                        //3 adult (case 0)
                        console.log("case 0");
                        var list_bed_note = [];
                        list_bed_note[0] = plugin.settings.private_bed;
                        list_bed_note[1] = plugin.settings.private_bed;
                        list_bed_note[2] = plugin.settings.extra_bed;
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            var tour_cost = plugin.get_price_tour_cost_by_passenger_index(a_passenger_index, price_senior, price_adult, price_teen);
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, 0, "", list_bed_note[p_index]);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 2) {
                        //1adult,2 child 6-11 (case 3a)
                        console.log("case 3a");
                        //add adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                        var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                        var passenger_index = list_object_children_1_passenger[0].index_of;
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var msg = "not include any excursion fee. Therefore if you assign this shild to a triple sharing excursion, he or she is required to pay half excursion fee.";
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        var passenger_index = list_object_children_1_passenger[1].index_of;
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, 0, "", bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a triple  sharing excursion, he or she is required to pay extra bed fee.";
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        //1 adult,1 child 6-11, 1 children 0-5 case 3b
                        console.log("case 3b");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price);
                        } else {
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var msg = "not include any excursion fee. Therefore if you assign this shild to a triple sharing excursion, he or she is required to pay half excursion fee.";
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a triple  sharing excursion, he or she is required to pay extra bed fee.";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.extra_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.extra_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 2) {
                        //1 adult, 2 children 0-5 (case 3c)
                        console.log("case 3c");
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to a triple  sharing excursion, he or she is required to pay extra bed fee.";
                        //add adult frist
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = price_private_excursion;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //2 children 0-5
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_excursion = plugin.get_all_infant_children_2_in_excursion(excursion_index);
                        var list_object_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_excursion);
                        for (var p_index = 0; p_index < list_object_infant_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_infant_children_2_passenger[p_index].index_of;
                            if (plugin.is_infant(a_passenger_index)) {
                                if (group_1_infant_children_2 != null) {
                                    group_2_infant_children_2 = a_passenger_index;
                                } else {
                                    group_1_infant_children_2 = a_passenger_index;
                                }
                                group_infant_children_2_price = price_infant;
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                if (group_1_infant_children_2 != null) {
                                    group_2_infant_children_2 = a_passenger_index;
                                } else {
                                    group_1_infant_children_2 = a_passenger_index;
                                }
                                group_infant_children_2_price = price_children2;
                            }
                        }
                        //group_1_infant_children_2
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var excursion_price = 0;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //group_2_infant_children_2
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var excursion_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.share_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 2 && plugin.get_total_children_1(excursion_index) == 1) {
                        //2 adult, 1 children 6-11 (case 3d)
                        console.log("3d");
                        //adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult second
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //children (6-11)
                        var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(excursion_index) == 2 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        //2 adult, 1 children 0-5 (case 3e)
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //children 0-5
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(excursion_index) == 3) {
                        //3 children 6-11 (case 3f)
                        console.log("case 3f");
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[1] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[2] = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var list_bed_note = [];
                            list_bed_note[0] = plugin.settings.private_bed;
                            list_bed_note[1] = plugin.settings.private_bed;
                            list_bed_note[2] = plugin.settings.extra_bed;
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(passengers);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger = list_object_children_1_passenger[p_index];
                                var a_passenger_index = a_passenger.index_of;
                                var tour_cost = price_children1;
                                var extra_bed_price = 0;
                                if (p_index == 0 || p_index == 1) {
                                    excursion_price = price_private_excursion;
                                    extra_bed_price = 0;
                                } else {
                                    excursion_price = 0;
                                    extra_bed_price = price_extra_bed;
                                }
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msgs[i], list_bed_note[p_index]);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(excursion_index) == 2 && plugin.get_total_children_2(excursion_index) == 1) {
                        //2 child 6-11 ,1 children 2-5 (case 3g)
                        //2children 6-11
                        console.log("case 3g");
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 1);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 1);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        var passenger_index = plugin.get_children_2_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = price_children2;
                        var excursion_price = 0;
                        extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_children_2(excursion_index) == 2) {
                        //1 children 6-11, 2 child 2-5 (case 3h)
                        console.log("case 3h");
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other."
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        var list_children_2_in_excursion = plugin.get_all_children_2_in_excursion(excursion_index);
                        var list_object_children_2_passenger = plugin.sorting_passengers_by_year_old(list_children_2_in_excursion);
                        var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                        var passenger_index = list_object_children_2_passenger[0].index_of;
                        var tour_cost = price_children2;
                        var excursion_price = price_private_excursion;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        var passenger_index = list_object_children_2_passenger[1].index_of;
                        var tour_cost = price_children2;
                        var excursion_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 3) {
                        //1adult, 3 child 6-11 (case 4a)
                        console.log("case 4a");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //3 child 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var a_passenger_index = passengers[p_index];
                                    var tour_cost = price_children1;
                                    var excursion_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note = plugin.settings.private_bed;
                                    excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                                }
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[1] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[2] = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var list_bed_note = [];
                            list_bed_note[0] = plugin.settings.private_bed;
                            list_bed_note[1] = plugin.settings.extra_bed;
                            list_bed_note[2] = plugin.settings.extra_bed;
                            var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var excursion_price = price_private_excursion;
                                    var extra_bed_price = 0;
                                } else {
                                    var excursion_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msgs[p_index], list_bed_note[p_index]);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 2 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        //1adult, 2 child 6-11, 1 child 0-5 (case 4b)
                        console.log("case 4b");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //2 children (6-11)
                        if (full_charge_children1) {
                            var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                            //children 1
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                            //children 2
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var excursion_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                            //children 1
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            //children 2
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, price_extra_bed, msg, bed_note);
                        }
                        // 1 children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 2) {
                        // 1adult, 1 child 6-11, 2 child 0-5 (case 4c)
                        console.log("case 4c");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //1 children 6-11
                        if (full_charge_children1) {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        } else {
                            //children 1
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, msg, bed_note);
                        }
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_excursion = plugin.get_all_infant_children_2_in_excursion(excursion_index);
                        var list_object_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_excursion);
                        for (var p_index = 0; p_index < list_object_infant_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_infant_children_2_passenger[p_index].index_of;
                            if (plugin.is_infant(a_passenger_index)) {
                                if (group_1_infant_children_2 != null) {
                                    group_2_infant_children_2 = a_passenger_index;
                                } else {
                                    group_1_infant_children_2 = a_passenger_index;
                                }
                                group_infant_children_2_price = price_infant;
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                if (group_1_infant_children_2 != null) {
                                    group_2_infant_children_2 = a_passenger_index;
                                } else {
                                    group_1_infant_children_2 = a_passenger_index;
                                }
                                group_infant_children_2_price = price_children2;
                            }
                        }
                        //group_1_infant_children_2
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var excursion_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var excursion_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 3) {
                        // 1adult,  3 child 0-5 (case 4d)
                        //adult 1
                        console.log("case 4d");
                        var msg = "This passenger is required to pay the excursion supplement fee to grant the free stay to kid sharing this excursion.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = price_private_excursion;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        //3 children (0-5)
                        var list_bed_note = [];
                        list_bed_note[0] = plugin.settings.private_bed;
                        list_bed_note[1] = plugin.settings.extra_bed;
                        list_bed_note[2] = plugin.settings.share_bed;
                        var list_all_infant_children_2_in_excursion = plugin.get_all_infant_children_2_in_excursion(excursion_index);
                        var list_object_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_excursion);
                        for (var p_index = 0; p_index < list_object_infant_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_infant_children_2_passenger[p_index].index_of;
                            var excursion_price = 0;
                            if (p_index == 0 || p_index == 1) {
                                var extra_bed_price = price_extra_bed;
                            } else {
                                var extra_bed_price = price_private_excursion;
                            }
                            if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                            }
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                            }
                            if (p_index == 0) {
                                var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            } else {
                                var msg = "";
                            }
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msg, list_bed_note[p_index]);
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 2 && plugin.get_total_children_1(excursion_index) == 2) {
                        // 2adult,  2 child 6-12 (case 4e)
                        //adult 1
                        console.log("case 4e");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        if (full_charge_children1) {
                            //children (6-11) 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            //children (6-11) 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 1);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                        } else {
                            var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                            var list_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                            //children (6-11) 1
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = list_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var excursion_price = (price_private_excursion + price_extra_bed) / 2;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            //children (6-11) 2
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var passenger_index = list_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var excursion_price = (price_private_excursion + price_extra_bed) / 2;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 2 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        // 2adult,  1 child 6-12 ,1 child 0-5 (case 4f)
                        //adult
                        console.log("case 4f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        if (full_charge_children1) {
                            //children (6-11)
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                        } else {
                            //children (6-11)
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note = plugin.settings.extra_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        }
                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 2 && plugin.get_total_infant_and_children_2(excursion_index) == 2) {
                        // 2 adult,  ,2 child 0-5 (case 4g)
                        console.log("case 4g");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_excursion = plugin.get_all_infant_children_2_in_excursion(excursion_index);
                        var list_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_excursion);
                        for (var p_index = 0; p_index < list_infant_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_infant_children_2_passenger[p_index].index_of;
                            if (plugin.is_infant(a_passenger_index)) {
                                if (group_1_infant_children_2 != null) {
                                    group_2_infant_children_2 = a_passenger_index;
                                } else {
                                    group_1_infant_children_2 = a_passenger_index;
                                }
                                group_infant_children_2_price = price_infant;
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                if (group_1_infant_children_2 != null) {
                                    group_2_infant_children_2 = a_passenger_index;
                                } else {
                                    group_1_infant_children_2 = a_passenger_index;
                                }
                                group_infant_children_2_price = price_children2;
                            }
                        }
                        //group_1_infant_children_2
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var excursion_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var excursion_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(excursion_index) == 3 && plugin.get_total_infant_and_children_2(excursion_index) == 1) {
                        // 3 adult,  ,1 child 0-5 (case 4i)
                        console.log("case 4i");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //adult 3
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_excursion(excursion_index, 2);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var excursion_price = 0;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, 0, "", bed_note);
                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_children_1(excursion_index) == 4) {
                        // 4 child 6-11 (case 4l)
                        console.log("case 4l");
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var excursion_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[1] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[2] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[3] = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var list_bed_note = [];
                            list_bed_note[0] = plugin.settings.private_bed;
                            list_bed_note[1] = plugin.settings.private_bed;
                            list_bed_note[2] = plugin.settings.extra_bed;
                            list_bed_note[3] = plugin.settings.extra_bed;
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(passengers);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var excursion_price = price_private_excursion;
                                    var extra_bed_price = 0;
                                } else {
                                    var excursion_price = (price_private_excursion + price_extra_bed) / 2;
                                    var extra_bed_price = 0;
                                }
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msgs[p_index], list_bed_note[p_index]);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_children_1(excursion_index) == 3 && plugin.get_total_children_2(excursion_index) == 1) {
                        // 3 child 6-11, 1 children 2-5 (case 4m)
                        console.log("case 4m");
                        //3 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var tour_cost = price_children1;
                                    var excursion_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note = plugin.settings.private_bed;
                                    excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                                }
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[1] = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            msgs[2] = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                            var list_bed_note = [];
                            list_bed_note[0] = plugin.settings.private_bed;
                            list_bed_note[1] = plugin.settings.private_bed;
                            list_bed_note[2] = plugin.settings.extra_bed;
                            var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var excursion_price = price_private_excursion;
                                    var extra_bed_price = 0;
                                } else {
                                    var excursion_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                console.log(a_passenger_index);
                                excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, msgs[p_index], list_bed_note[p_index]);
                            }
                        }
                        //1 children 2-5
                        var passenger_index = plugin.get_children_2_passenger_order_in_excursion(excursion_index, 0);
                        var tour_cost = price_children2;
                        var excursion_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_children_1(excursion_index) == 2 && plugin.get_total_infant_and_children_2(excursion_index) == 2) {
                        // 2 child 6-11, 2 children 2-5 (case 4p)
                        console.log("case 4p");
                        //2 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var tour_cost = price_children1;
                                    var excursion_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note = plugin.settings.private_bed;
                                    excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                                }
                            }
                        } else {
                            var list_all_children_1_in_excursion = plugin.get_all_children_1_in_excursion(excursion_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_excursion);
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                            var msg = "The tour cost for this child type does not include the excursion fee.  Therefore he or she is required to pay the  excursion supplement fee while sharing excursion with other.";
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        }
                        var list_all_children_2_in_excursion = plugin.get_all_children_2_in_excursion(excursion_index);
                        var list_object_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_children_2_in_excursion);
                        // children (2-5) 1
                        var msg = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        var passenger_index = list_object_children_2_passenger[0].index_of;
                        var tour_cost = price_children2;
                        var excursion_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        // children (2-5) 2
                        var passenger_index = list_object_children_2_passenger[1].index_of;
                        var tour_cost = price_children2;
                        var excursion_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_children_1(excursion_index) == 1 && plugin.get_total_children_2(excursion_index) == 3) {
                        // 1 child 6-11, 3 children 2-5 (case 4q)
                        console.log("case 4q");
                        //1 children 6-11
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to this triple excursion, he or she is required to pay the excursion supplement fee.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_excursion(excursion_index, 0);
                            var tour_cost = price_children1;
                            var excursion_price = price_private_excursion;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, passenger_index, tour_cost, excursion_price, extra_bed_price, msg, bed_note);
                        }
                        //3 children 2-5
                        var list_msg = [];
                        list_msg[0] = "The tour cost for this child type does not include any excursion fee. Therefore if you assign this shild to this triple excursion, he or she is required to pay the excursion supplement fee.";
                        list_msg[1] = "The tour cost for this child type does not include any excursion fee. Therefore  he or she is required to pay extra bed fee while  staying in triple excursion.";
                        list_msg[2] = "";
                        var list_bed_note = [];
                        list_bed_note[0] = plugin.settings.private_bed;
                        list_bed_note[1] = plugin.settings.extra_bed;
                        list_bed_note[2] = plugin.settings.share_bed;
                        var list_all_children_2_in_excursion = plugin.get_all_children_2_in_excursion(excursion_index);
                        var list_object_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_children_2_in_excursion);
                        for (var p_index = 0; p_index < list_object_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_children_2_passenger[p_index].index_of;
                            var tour_cost = price_children2;
                            var excursion_price = 0;
                            var extra_bed_price = 0;
                            if (p_index == 0) {
                                var excursion_price = price_private_excursion;
                            } else if (p_index == 1) {
                                //?
                                var extra_bed_price = price_extra_bed;
                            }
                            excursion_item = func_set_tour_cost_and_excursion_price(excursion_item, a_passenger_index, tour_cost, excursion_price, extra_bed_price, list_msg[p_index], list_bed_note[p_index]);
                        }
                    }
                }
                list_excursion[i] = excursion_item;
            }
            plugin.settings.list_excursion = list_excursion;
        };
        plugin.update_data = function () {
            return;
            var input_name = plugin.settings.input_name;
            var $input_name = $element.find('input[name="' + input_name + '"]');
            var data = $element.find(':input[name]').serializeObject();
            if (typeof data.list_excursion == "undefined") {
                return false;
            }
            console.log(data.list_excursion);
            for (var i = 0; i < data.list_excursion.length; i++) {
                if (typeof data.list_excursion[i].passengers == "undefined") {
                    data.list_excursion[i].passengers = [];
                }
            }
            var list_excursion = plugin.get_list_excursion();
            plugin.settings.list_excursion = data.list_excursion;
            data = JSON.stringify(data);
            $input_name.val(data);
            var event_after_change = plugin.settings.event_after_change;
            if (event_after_change instanceof Function) {
                event_after_change(plugin.settings.list_excursion);
            }
        };
        plugin.get_list_passenger_selected = function () {
            var list_excursion = plugin.settings.list_excursion;
            var list_passenger_selected = [];
            $.each(list_excursion, function (index, excursion_item) {
                var passengers = excursion_item.passengers;
                list_passenger_selected = list_passenger_selected.concat(passengers);
            });
            return list_passenger_selected;
        }
        plugin.sorting_passengers_by_year_old = function (passengers) {
            var list_passenger = plugin.settings.list_passenger;
            var list_object_passenger = [];
            for (var p_index = 0; p_index < passengers.length; p_index++) {
                var a_passenger_index = passengers[p_index];
                var passenger = list_passenger[a_passenger_index];
                passenger.index_of = a_passenger_index;
                list_object_passenger.push(passenger);
            }
            list_object_passenger.sort(function (a, b) {
                return parseFloat(a.year_old) - parseFloat(b.year_old);
            });
            list_object_passenger.reverse();
            return list_object_passenger;
        };
        plugin.get_excursion_index_by_passenger_index_selected = function (passenger_index) {
            var list_excursion = plugin.settings.list_excursion;
            var return_excursion_index = false;
            for (var i = 0; i < list_excursion.length; i++) {
                var excursion_item = list_excursion[i];
                var passengers = excursion_item.passengers;
                if ($.inArray(passenger_index, passengers) != -1) {
                    return_excursion_index = i;
                    break;
                } else {
                }
            }
            return return_excursion_index;
        };
        plugin.get_list_passenger_checked = function () {
            var list_passenger_checked = [];
            var list_excursion = plugin.settings.list_excursion;
            var list_passenger = plugin.settings.list_passenger;
            $.each(list_excursion, function (index, excursion) {
                if (typeof excursion.passengers != "undefined") {
                    var passengers = excursion.passengers;
                    $.each(passengers, function (index_passenger, order_passenger) {
                        list_passenger_checked.push(order_passenger);
                    });
                }
            });
            return list_passenger_checked;
        };
        plugin.add_list_passenger_to_excursion = function ($item_excursion) {
            var excursion_index = $item_excursion.index();
            var list_passenger = plugin.settings.list_passenger;
            var total_passenger = list_passenger.length;
            var $list_passenger = $item_excursion.find('.list-passenger');
            $list_passenger.empty();
            var list_old_passenger = [];
            $list_passenger.find('li input.passenger-item').each(function (index) {
                var $self = $(this);
                if ($self.is(':checked')) {
                    var key_full_name = $(this).data('key_full_name');
                    list_old_passenger.push(key_full_name);
                }
            });
            for (var i = 0; i < total_passenger; i++) {
                var passenger = list_passenger[i];
                var full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
                var key_full_name = passenger.first_name + passenger.middle_name + passenger.last_name;
                key_full_name = $.base64Encode(key_full_name);
                var $li = $('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="' + key_full_name + '" value="' + i + '" data-index="' + i + '" name="list_excursion[' + excursion_index + '][passengers][]" type="checkbox"> ' + full_name + '</label></li>');
                $li.appendTo($list_passenger);
            }
        };
        plugin.get_item_tour_cost_and_excursion_price_by_passenger_index = function (tour_cost_and_excursion_price, passenger_index) {
            if (tour_cost_and_excursion_price.length > 0) {
                for (var i = 0; i < tour_cost_and_excursion_price.length; i++) {
                    var item_tour_cost_and_excursion_price = tour_cost_and_excursion_price[i];
                    if (item_tour_cost_and_excursion_price.passenger_index == passenger_index) {
                        return item_tour_cost_and_excursion_price;
                    }
                }
            }
            return null;
        };
        plugin.get_passenger_title_by_passenger_index = function (index_passenger) {
            if (plugin.is_infant(index_passenger)) {
                return plugin.settings.infant_title;
            } else if (plugin.is_children_1(index_passenger) || plugin.is_children_2(index_passenger)) {
                return plugin.settings.children_title;
            } else if (plugin.is_teen(index_passenger)) {
                return plugin.settings.teen_title;
            } else if (plugin.is_adult(index_passenger)) {
                return plugin.settings.adult_title;
            } else if (plugin.is_senior(index_passenger)) {
                return plugin.settings.senior_title;
            }
        };
        plugin.update_list_excursioning = function () {
            var list_excursion = plugin.get_list_excursion();
            var list_passenger = plugin.settings.list_passenger;
            var $table_excursioning_list = $element.find('.table-excursioning-list');
            var $tbody = $table_excursioning_list.find('.tbody');
            $tbody.empty();
            var html_tr_item_excursion = plugin.settings.html_tr_item_excursion;
            $.each(list_excursion, function (index, excursion) {
                $(html_tr_item_excursion).appendTo($tbody);
                var $tr_item_excursion = $tbody.find('div.div-item-excursion:last');
                $tr_item_excursion.find('span.order').html(index + 1);
                $tr_item_excursion.find('div.excursion_type').html(excursion.excursion_type);
                $tr_item_excursion.find('div.excursion_note').html(excursion.excursion_note);
                var tour_cost_and_excursion_price = excursion.tour_cost_and_excursion_price;
                console.log(excursion);
                if (typeof excursion.passengers != "undefined" && excursion.passengers.length > 0) {
                    var passengers = excursion.passengers;
                    passengers = plugin.sorting_passengers_by_year_old(passengers);
                    var sub_list_passenger = [];
                    var sub_list_passenger_private_excursion = [];
                    for (var i = 0; i < passengers.length; i++) {
                        var item_passenger = passengers[i];
                        var order_passenger = item_passenger.index_of;
                        var excursion_price_per_passenger = 0;
                        var bed_note = "";
                        if (typeof tour_cost_and_excursion_price != "undefined") {
                            var item_tour_cost_and_excursion_price = plugin.get_item_tour_cost_and_excursion_price_by_passenger_index(tour_cost_and_excursion_price, order_passenger);
                            if (item_tour_cost_and_excursion_price != null) {
                                var passenger_note = item_tour_cost_and_excursion_price.msg;
                                var bed_note = item_tour_cost_and_excursion_price.bed_note;
                                bed_note = '<div class="bed_note">' + bed_note + '&nbsp;</div>';
                            }
                        }
                        sub_list_passenger_private_excursion.push(bed_note);
                        var full_name = item_passenger.first_name + ' ' + item_passenger.middle_name + ' ' + item_passenger.last_name + ' (' + item_passenger.year_old + ')';
                        sub_list_passenger.push('<div class="passenger-item">' + full_name + '</div>');
                    }
                    $.each(passengers, function (index_passenger, order_passenger) {
                    });
                    sub_list_passenger = sub_list_passenger.join('');
                    $tr_item_excursion.find('div.table_list_passenger').html(sub_list_passenger);
                    sub_list_passenger_private_excursion = sub_list_passenger_private_excursion.join('');
                    $tr_item_excursion.find('div.private-excursion').html(sub_list_passenger_private_excursion);
                    $.set_height_element($tr_item_excursion.find('.row-item-column'));
                }
            });
            $element.find('.div-item-excursion .delete-excursion').click(function delete_excursion(event) {
                var $self = $(this);
                var $tr_excursion_item = $self.closest('.div-item-excursion');
                var index_of_excursion = $tr_excursion_item.index();
                $element.find('.item-excursion:eq(' + index_of_excursion + ') .remove-excursion').trigger('click');
            });
            $element.find('.table-excursioning-list .tbody').sortable("refresh");
        };
        plugin.get_total_children_2 = function (excursion_index) {
            var total_children_2 = 0;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    total_children_2++;
                }
            }
            return total_children_2;
        };
        plugin.get_total_children_1 = function (excursion_index) {
            var total_children_1 = 0;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    total_children_1++;
                }
            }
            return total_children_1;
        };
        plugin.get_total_children_1_and_children_2 = function (excursion_index) {
            var total_children_1_and_children_2 = 0;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index) || plugin.is_children_1(passenger_index)) {
                    total_children_1_and_children_2++;
                }
            }
            return total_children_1_and_children_2;
        };
        plugin.get_total_infant_and_children_2 = function (excursion_index) {
            var total_infant_and_children_2 = 0;
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    total_infant_and_children_2++;
                }
            }
            return total_infant_and_children_2;
        };
        plugin.add_excursion = function ($self) {
            var html_item_excursion_template = plugin.settings.html_item_excursion_template;
            var $last_item_excursion = $element.find(".item-excursion:last");
            $(html_item_excursion_template).insertAfter($last_item_excursion);
            var $last_item_excursion = $element.find(".item-excursion:last");
            var item_excursion_template = {};
            var list_excursion = plugin.settings.list_excursion;
            item_excursion_template.passengers = [];
            item_excursion_template.excursion_type = 'basic';
            item_excursion_template.excursion_note = '';
            item_excursion_template.tour_cost_and_excursion_price = [];
            item_excursion_template.full = false;
            list_excursion.push(item_excursion_template);
            $last_item_excursion.find('input[value="basic"][data-name="excursion_type"]').prop("checked", true);
            var last_excursion_index = $last_item_excursion.index();
            if (plugin.enable_add_passenger_to_excursion_index(last_excursion_index)) {
                plugin.add_passenger_to_excursion_index(last_excursion_index);
            }
            plugin.format_name_for_excursion_index(last_excursion_index);
            plugin.lock_passenger_inside_excursion_index(last_excursion_index);
            plugin.set_label_passenger_in_excursions();
            plugin.update_list_excursioning();
            plugin.trigger_after_change();
        };
        plugin.jumper_excursion = function (excursion_index) {
            $.scrollTo($element.find('.item-excursion:eq(' + excursion_index + ')'), 800);
        };
        plugin.validate = function () {
            var $list_excursion = $element.find('.item-excursion');
            var list_excursion = plugin.settings.list_excursion;
            for (var i = 0; i < $list_excursion.length; i++) {
                var excursion_index = i;
                var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
                var excursion_item = list_excursion[excursion_index];
                var passengers = excursion_item.passengers;
                var excursion_type = excursion_item.excursion_type;
                var $list_passenger = $excursion_item.find('ul.list-passenger');
                if (excursion_item.excursion_type == '') {
                    var content_notify = 'please select excursion type';
                    plugin.notify(content_notify);
                    $list_excursion.tipso('destroy');
                    $list_excursion.addClass('error');
                    $list_excursion.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_excursion.tipso('show');
                    plugin.jumper_excursion(excursion_index);
                    return false;
                } else if (passengers.length == 0) {
                    $list_passenger.removeClass('error');
                    $list_passenger.tipso('destroy');
                    var content_notify = 'please select passenger';
                    plugin.notify(content_notify);
                    $list_passenger.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_passenger.addClass('error');
                    $list_passenger.tipso('show');
                    plugin.jumper_excursion(excursion_index);
                    return false;
                    $list_passenger.tipso('destroy');
                } else if (!plugin.settings[excursion_type].validate_excursion(excursion_item, excursion_index)) {
                    plugin.jumper_excursion(excursion_index);
                    return false;
                }
                $list_passenger.removeClass('error');
            }
            plugin.calculator_tour_cost_and_excursion_price();
            return true;
        };
        plugin.find_passenger_not_inside_excursion = function () {
            var passenger_not_inside_excursion = [];
            var list_passenger = plugin.settings.list_passenger.slice();
            var list_passenger_checked = plugin.get_list_passenger_checked();
            for (var i = 0; i < list_passenger.length; i++) {
                if (list_passenger_checked.indexOf(i) > -1) {
                } else {
                    passenger_not_inside_excursion.push(list_passenger[i]);
                }
            }
            return passenger_not_inside_excursion;
        }
        plugin.get_data = function () {
            return plugin.settings.output_data;
        };
        plugin.validate_data_excursion_index = function (excursion_index) {
            var $item_excursion = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var list_excursion = plugin.settings.list_excursion;
            var excursion_item = list_excursion[excursion_index];
            var $type = $item_excursion.find('input[type="radio"][data-name="excursion_type"]:checked');
            if ($type.length == 0) {
                var content_notify = 'please select excursion type';
                plugin.notify(content_notify);
                $item_excursion.find('.list-excursion').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $item_excursion.find('.list-excursion').tipso('show');
                return false;
            }
            var type = $type.val();
            type = plugin.settings[type];
            var total_passenger_selected = $item_excursion.find('.list-passenger input.passenger-item[exists_inside_other_excursion!="true"]:checked').length;
            if (total_passenger_selected >= type.max_total) {
                excursion_item.full = true;
                $item_excursion.find('.list-passenger input.passenger-item[exists_inside_other_excursion!="true"]:not(:checked)').prop("disabled", true);
            } else {
                excursion_item.full = false;
                $item_excursion.find('.list-passenger input.passenger-item[exists_inside_other_excursion!="true"]').prop("disabled", false);
            }
            list_excursion[excursion_index] = excursion_item;
            plugin.settings.list_excursion = list_excursion;
            return true;
        };
        plugin.reset_passenger_selected = function ($self) {
            var $item_excursion = $self.closest('.item-excursion');
            //$item_excursion.find('.list-passenger input.passenger-item[exists_inside_other_excursion]').prop("disabled", false).prop("checked", false).trigger('change');
            plugin.lock_passenger_inside_excursion($self);
        };
        plugin.update_event = function () {
            $element.find('input.passenger-item').unbind('change');
            $element.find('input.passenger-item').change(function selected_passenger(event) {
                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
                var $self = $(this);
                var $excursion = $self.closest('.item-excursion');
                if (!$html_input_passenger.validate()) {
                    $self.prop('checked', false);
                    return false;
                }
                if (!plugin.validate_data($self)) {
                    $self.prop('checked', false);
                    return false;
                }
                if (!$self.is(":checked")) {
                    var passenger_index = $self.data('index');
                    console.log(passenger_index);
                    //$self.removeAttr('exists_inside_other_excursion');
                    //var passenger_index=$self.data('index');
                    //$element.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]').prop("disabled", false);
                }
                plugin.lock_passenger_inside_excursion($self);
                plugin.update_data();
                plugin.update_list_excursioning();
            });
            $element.find('input[type="radio"][data-name="excursion_type"]').unbind('change');
            $element.find('input[type="radio"][data-name="excursion_type"]').change(function selected_type(event) {
                /* var $self=$(this);
                 plugin.reset_passenger_selected($self);
                 plugin.update_data();
                 plugin.update_list_excursioning();*/
            });
            $element.find('textarea[data-name="excursion_note"]').change(function change_note(event) {
                plugin.update_data();
                plugin.update_list_excursioning();
            });
        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger = list_passenger;
            var total_passenger = list_passenger.length;
            var $list_excursion = $element.find('.item-excursion');
            $list_excursion.each(function (excursion_index, excursion) {
                if (plugin.enable_add_passenger_to_excursion_index(excursion_index)) {
                    plugin.add_passenger_to_excursion_index(excursion_index);
                    plugin.format_name_for_excursion_index(excursion_index);
                }
                plugin.add_event_excursion_index(excursion_index);
                /*
                 var $excursion=$(excursion);
                 var $list_passenger=$excursion.find('.list-passenger');
                 $list_passenger.empty();
                 var list_old_passenger=[];
                 $list_passenger.find('li input.passenger-item').each(function(index){
                 var $self=$(this);
                 if($self.is(':checked')) {
                 var key_full_name = $(this).data('key_full_name');
                 list_old_passenger.push(key_full_name);
                 }
                 });
                 for(var i=0;i<total_passenger;i++){
                 var passenger=list_passenger[i];
                 var full_name=passenger.first_name+' '+passenger.middle_name+' '+passenger.last_name+'('+passenger.year_old+')';
                 var key_full_name=passenger.first_name+passenger.middle_name+passenger.last_name;
                 key_full_name= $.base64Encode(key_full_name);
                 var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" data-index="'+i+'" value="'+i+'" name="list_excursion['+excursion_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');
                 $li.appendTo($list_passenger);
                 }
                 */
                /*$list_passenger.find('li input.passenger-item').each(function(index){
                 var $self=$(this);
                 var key_full_name = $(this).data('key_full_name');
                 if(list_old_passenger.length>0 && $.inArray(key_full_name,list_old_passenger)){
                 $self.prop('checked',true).trigger('change');
                 }
                 });*/
            });
            /*

             plugin.config_layout();
             plugin.update_data();
             plugin.update_event();
             plugin.update_list_excursioning();


             */
            plugin.trigger_after_change();
        };
        plugin.exists_infant_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var exists_infant_in_excursion = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_excursion = true;
                    break;
                }
            }
            return exists_infant_in_excursion;
        };
        plugin.exists_infant_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var exists_infant_in_excursion = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_excursion = true;
                    break;
                }
            }
            return exists_infant_in_excursion;
        };
        plugin.exists_children_2_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var exists_children_2_in_excursion = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    exists_children_2_in_excursion = true;
                    break;
                }
            }
            return exists_children_2_in_excursion;
        };
        plugin.check_all_passenger_is_infant_and_children_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var all_passenger_is_infant_or_children = true;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    all_passenger_is_infant_or_children = false;
                    break;
                }
            }
            return all_passenger_is_infant_or_children;
        };
        plugin.enable_add_excursion = function (excursion_index) {
            var list_passenger = plugin.settings.list_passenger;
            var list_passenger_checked = plugin.get_list_passenger_checked();
            if (list_passenger_checked.length >= list_passenger.length) {
                plugin.notify('you cannot add more excursion');
                return false;
            }
            return true;
        };
        plugin.enable_remove_excursion = function (excursion_index) {
            return true;
        };
        plugin.enable_remove_excursion_index = function (excursion_index) {
            return true;
        };
        plugin.enable_add_passenger_to_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
            return passenger_full_name;
        };
        plugin.add_passenger_to_excursion_index = function (excursion_index) {
            var list_passenger = plugin.settings.list_passenger.slice();
            var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
            $excursion_item.find('ul.list-passenger').empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var passenger_full_name = plugin.get_passenger_full_name(passenger);
                var html_template_passenger = plugin.settings.html_template_passenger;
                var $template_passenger = $(html_template_passenger);
                $template_passenger.find('.full-name').html(passenger_full_name);
                $excursion_item.find('ul.list-passenger').append($template_passenger);
            }
        };
        plugin.enable_change_excursion_type_to_excursion_index = function (excursion_index) {
            return true;
        };
        plugin.add_event_all_excursion_index = function () {
            var $list_excursion_item = $element.find('.item-excursion');
            for (var i = 0; i < $list_excursion_item.length; i++) {
                plugin.add_event_excursion_index(i);
            }
        };
        plugin.lock_passenger_inside_excursions = function () {
            var $list_excursion = $element.find('.item-excursion');
            $list_excursion.each(function (excursion_index) {
            });
        };
        plugin.chang_excursion_type_to_excursion_index = function (excursion_index) {
            var $item_excursion = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            $.each(passengers, function (index, passenger_id) {
                $item_excursion.find('.passenger-item:eq(' + passenger_id + ')').prop('checked', false);
            });
            var excursion_type = $item_excursion.find('input[type="radio"][data-name="excursion_type"]:checked').val();
            list_excursion[excursion_index].passengers = [];
            list_excursion[excursion_index].excursion_type = excursion_type;
            list_excursion[excursion_index].full = false;
            plugin.settings.list_excursion = list_excursion;
            plugin.set_label_passenger_in_excursions();
            plugin.update_list_excursioning();
            plugin.trigger_after_change();
        };
        plugin.exists_senior_adult_teen_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_1_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_or_adult_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_adult_or_children_by_passenger_index(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.check_enable_joint_excursion = function (passenger_idex) {
            var transfer_config = plugin.settings.pas;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                var passenger = list_passenger[passenger_index];
                console.log(passenger);
                if (parseInt(passenger.year_old) >= parseInt(transfer_config.transfer_arrange_year_old_from) && parseInt(passenger.year_old) <= parseInt(transfer_config.transfer_arrange_year_old_to)) {
                    return true;
                }
            }
            return false;
        };
        plugin.enable_change_passenger_inside_excursion_index = function (excursion_index, passenger_index) {
            var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var tsmart_excursion_addon_id = $excursion_item.find('input[type="hidden"][name="tsmart_excursion_addon_id"]').val();
            var list_data_excursion_addon = plugin.settings.list_data_excursion_addon;
            var data_excursion_addon = list_data_excursion_addon[tsmart_excursion_addon_id];
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            if (parseInt(passenger.year_old) >= parseInt(data_excursion_addon.passenger_age_from) && parseInt(passenger.year_old) <= parseInt(data_excursion_addon.passenger_age_to)) {
                return true;
            }
            var content_notify = 'this passenger can not joint excursion, we allow year old from ' + data_excursion_addon.passenger_age_from + ' to ' + data_excursion_addon.passenger_age_to + ', please select an other';
            plugin.notify(content_notify);
            return false;
        };
        plugin.check_is_full_passenger_inside_excursion = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var excursion_item = list_excursion[excursion_index];
            var excursion_type = excursion_item.excursion_type;
            var setting_excursion_type = plugin.settings[excursion_type];
            var passengers = excursion_item.passengers;
            return passengers.length == setting_excursion_type.max_total;
        };
        plugin.change_passenger_inside_excursion_index = function (excursion_index) {

            var passengers = [];
            var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
            $excursion_item.find('.passenger-item').each(function (passenger_index) {
                var $self = $(this);
                if ($self.is(':checked')) {
                    passengers.push(passenger_index);
                }
            });
/*
            var tsmart_excursion_addon_id=$excursion_item.find('input[name="tsmart_excursion_addon_id"]').val();
            var excursion_addon_data=plugin.get_excursion_addon_data(tsmart_excursion_addon_id);
            var list_passenger=plugin.settings.list_passenger;
            for(var i=0;i<passengers.length;i++){
                var passenger_index=passengers[i];
                var passenger=list_passenger[passenger_index];
                var cost=plugin.get_price_by_passenger(excursion_addon_data,passenger,passengers.length);

            }

*/
            plugin.settings.list_excursion[excursion_index].passengers=passengers;
            plugin.set_price_for_per_passenger();
            plugin.trigger_after_change();
        };

        plugin.set_price_for_per_passenger = function () {
            var list_cost_for_passenger=[];
            var list_passenger = plugin.settings.list_passenger;
            var list_excursion=plugin.settings.list_excursion;

            $.each(list_excursion, function(index, excursion_addon) {
                var passengers = excursion_addon.passengers;
                var total_passenger=passengers.length;
                var tsmart_excursion_addon_id = excursion_addon.tsmart_excursion_addon_id;
                var list_data_excursion_addon=plugin.settings.list_data_excursion_addon;
                var excursion_addon_data=list_data_excursion_addon[tsmart_excursion_addon_id];
                $.each(passengers, function(index, passenger_index) {
                    var passenger=list_passenger[passenger_index];
                    var cost=0;
                    if(typeof excursion_addon_data!="undefined" )
                    {
                        cost=plugin.get_price_by_passenger(excursion_addon_data,passenger,total_passenger);
                    }
                    if(typeof list_cost_for_passenger[passenger_index]=="undefined"){
                        list_cost_for_passenger[passenger_index]={};
                        list_cost_for_passenger[passenger_index].cost=0;
                        list_cost_for_passenger[passenger_index].full_name=plugin.get_passenger_full_name(passenger);
                    }
                    list_cost_for_passenger[passenger_index].cost+=cost;
                });
            });
            plugin.settings.list_cost_for_passenger=list_cost_for_passenger;
        };



        plugin.get_excursion_addon_data = function (tsmart_excursion_addon_id) {
            var list_data_excursion_addon=plugin.settings.list_data_excursion_addon;
            console.log(list_data_excursion_addon);
            for (var i=0;i<list_data_excursion_addon.length;i++){
                var excursion_addon=list_data_excursion_addon[i];
                if(excursion_addon.tsmart_excursion_addon_id==tsmart_excursion_addon_id){
                    return excursion_addon;
                }
            }
            return null;
        };

        plugin.get_price_by_passenger = function (excursion_addon_data,passenger,total_passenger) {

            var year_old=passenger.year_old;
            var price=0;
            var data_price=excursion_addon_data.data_price;
            if(typeof data_price=="undefined"){
                return price;
            }
            var item_flat=data_price.item_flat;
            var net_price=parseFloat(item_flat.net_price);
            var item_flat_mark_up_type=data_price.item_flat_mark_up_type;
            if(parseInt(net_price)>0){
                var tax=item_flat.tax;
                var mark_up_amount=parseFloat(item_flat.mark_up_amount);
                var mark_up_percent=parseFloat(item_flat.mark_up_percent);
                if(item_flat_mark_up_type=='percent'){
                    var current_price=net_price+(net_price*mark_up_percent)/100;
                    price=current_price+(current_price*tax)/100;
                }else{
                    var current_price=current_price+mark_up_amount;
                    price=current_price+(current_price*tax)/100;
                }
            }else{
                var items=data_price.items;
                var item_mark_up_type=data_price.item_mark_up_type;
                if(total_passenger<items.length)
                {
                    var item=items[total_passenger];
                }else{
                    var item=items[items.length-1];
                }

                var mark_up_amount=parseFloat(item.mark_up_amount);
                var mark_up_percent=parseFloat(item.mark_up_percent);
                var net_price=parseFloat(item.net_price);
                var tax=parseFloat(item.tax);
                if(item_mark_up_type=='percent'){
                    var item_sale_price=net_price+(net_price*mark_up_percent)/100;
                    item_sale_price=item_sale_price+(item_sale_price*tax)/100;
                    price=item_sale_price;

                }else{
                    var item_sale_price=net_price+mark_up_amount;
                    item_sale_price=item_sale_price+(item_sale_price*tax)/100;
                    price=item_sale_price;
                }
            }
            var children_under_year=parseInt(data_price.children_under_year);
            var children_discount_amount=parseFloat(data_price.children_discount_amount);
            var children_discount_percent=parseFloat(data_price.children_discount_percent);
            var children_discount_type=data_price.children_discount_type;
            if(children_under_year>0 &&  year_old<=children_under_year && children_discount_type=="percent"){
                price=price-(price*children_discount_percent)/100;
            }else if(children_under_year>0 &&  year_old<=children_under_year && children_discount_type=="amount"){
                price=price-children_discount_amount;
            }
            if(price<0){
                price=0;
            }
            return price;
        };

        plugin.remove_passenger_in_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            list_excursion[excursion_index].passengers = [];
            plugin.settings.list_excursion = list_excursion;
        };
        plugin.is_adult_or_children_by_passenger_index = function (passenger_index) {
            var list_passenger = plugin.settings.list_passenger;
            var range_adult_and_children = plugin.settings.range_adult_and_children;
            console.log(list_passenger);
            console.log(passenger_index);
            var passenger = list_passenger[passenger_index];
            console.log(passenger);
            console.log(passenger_index);
            console.log(parseInt(passenger.year_old));
            console.log(parseInt(passenger.year_old) >= range_adult_and_children[0]);
            console.log(parseInt(passenger.year_old) <= range_adult_and_children[1]);
            if (typeof passenger != "undefined" && parseInt(passenger.year_old) >= range_adult_and_children[0] && parseInt(passenger.year_old) <= range_adult_and_children[1]) {
                return true;
            } else {
                return false;
            }
        };
        plugin.is_infant_or_children_2 = function (passenger_index) {
            var range_year_old_infant_and_children_2 = plugin.settings.range_year_old_infant_and_children_2;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old <= range_year_old_infant_and_children_2[1];
        };
        plugin.is_infant = function (passenger_index) {
            var range_year_old_infant = plugin.settings.range_year_old_infant;
            console.log(range_year_old_infant);
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old <= range_year_old_infant[1];
        };
        plugin.is_children_1 = function (passenger_index) {
            var range_year_old_children_1 = plugin.settings.range_year_old_children_1;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old >= range_year_old_children_1[0] && passenger.year_old <= range_year_old_children_1[1];
        };
        plugin.is_children_2 = function (passenger_index) {
            var range_year_old_children_2 = plugin.settings.range_year_old_children_2;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return parseInt(passenger.year_old) >= parseInt(range_year_old_children_2[0]) && parseInt(passenger.year_old) <= parseInt(range_year_old_children_2[1]);
        };
        plugin.is_senior_adult_teen = function (passenger_index) {
            var range_year_old_senior_adult_teen = plugin.settings.range_year_old_senior_adult_teen;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old >= range_year_old_senior_adult_teen[0] && passenger.year_old <= range_year_old_senior_adult_teen[1];
        };
        plugin.is_teen = function (passenger_index) {
            var range_year_old_teen = plugin.settings.range_year_old_teen;
            var list_passenger = plugin.settings.list_passenger;
            var passenger = list_passenger[passenger_index];
            return passenger.year_old >= range_year_old_teen[0] && passenger.year_old <= range_year_old_teen[1];
        };
        plugin.check_excursion_before_add_passenger = function (excursion_index, current_passenger_index_selected) {
            var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var $passenger = $excursion_item.find('input.passenger-item:eq(' + current_passenger_index_selected + ')');
            var range_year_old_infant = plugin.settings.range_year_old_infant;
            var range_year_old_children_2 = plugin.settings.range_year_old_children_2;
            var range_year_old_infant_and_children_2 = plugin.settings.range_year_old_infant_and_children_2;
            var list_excursion = plugin.settings.list_excursion;
            var excursion_item = list_excursion[excursion_index];
            var excursion_type = excursion_item.excursion_type;
            if (plugin.is_infant(current_passenger_index_selected) && !plugin.settings[excursion_type].enable_select_infant1(excursion_item, range_year_old_infant, current_passenger_index_selected, excursion_index)) {
                var content_notify = 'you cannot add more infant 0-1 because there are no adult in excursion';
                plugin.notify(content_notify);
                $excursion_item.find('.list-passenger').removeClass('error');
                $excursion_item.find('.list-passenger').tipso('destroy');
                $excursion_item.find('.list-passenger').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $excursion_item.find('.list-passenger').tipso('show');
                return false;
            } else {
                $excursion_item.find('.list-passenger').removeClass('error');
                $excursion_item.find('.list-passenger').tipso('destroy');
            }
            if (plugin.is_children_2(current_passenger_index_selected) && !plugin.settings[excursion_type].enable_select_infant2(excursion_item, range_year_old_children_2, current_passenger_index_selected, excursion_index)) {
                return false;
            } else {
                $excursion_item.find('.list-passenger').removeClass('error');
                $excursion_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_children_1 = plugin.settings.range_year_old_children_1;
            if (plugin.is_children_1(current_passenger_index_selected) && !plugin.settings[excursion_type].enable_select_children(excursion_item, range_year_old_children_1, current_passenger_index_selected, excursion_index)) {
                return false;
            } else {
                $excursion_item.find('.list-passenger').removeClass('error');
                $excursion_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_senior_adult_teen = plugin.settings.range_year_old_senior_adult_teen;
            if (plugin.is_senior_adult_teen(current_passenger_index_selected) && !plugin.settings[excursion_type].enable_select_senior_adult_teen(excursion_item, range_year_old_senior_adult_teen, current_passenger_index_selected, excursion_index)) {
                return false;
            } else {
                $excursion_item.find('.list-passenger').removeClass('error');
                $excursion_item.find('.list-passenger').tipso('destroy');
            }
            return true;
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
        plugin.update_excursion_note_excursion_index = function (excursion_index) {
            var $item_excursion = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var list_excursion = plugin.settings.list_excursion;
            var passengers = list_excursion[excursion_index].passengers;
            var excursion_note = $item_excursion.find('textarea[data-name="excursion_note"]').val();
            list_excursion[excursion_index].excursion_note = excursion_note;
            plugin.settings.list_excursion = list_excursion;
        };
        plugin.add_event_excursion_index = function (excursion_index) {
            var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var $list_passenger = $excursion_item.find('ul.list-passenger');
            $list_passenger.find('input.passenger-item').each(function (passenger_index) {
                var $passenger = $(this);
                var event_class = 'change_passenger';
                if (!$passenger.hasClass(event_class)) {
                    $passenger.change(function (event) {
                        var $self = $(this);
                        var a_excursion_index = $self.closest('.item-excursion').index();
                        $excursion_item = $self.closest('.item-excursion');
                        var list_excursion = plugin.settings.list_excursion;
                        var passenger_index = $self.closest('li').index();
                        if (plugin.enable_change_passenger_inside_excursion_index(a_excursion_index, passenger_index)) {
                            plugin.change_passenger_inside_excursion_index(a_excursion_index);
                        } else {
                            $self.prop('checked', false);
                        }
                    }).addClass(event_class);
                }
            });
            /*
             $list_passenger.find('input.passenger-item').each(function(passenger_index){
             var event_class='click_passenger';
             var $passenger=$(this);
             if(!$passenger.hasClass(event_class))
             {
             $passenger.click(function(event){

             var content_notify='excursion is full you can not add more passenger';
             plugin.notify(content_notify);
             $excursion_item.find('.list-passenger').removeClass('error');
             $excursion_item.find('.list-passenger').tipso('destroy');
             $excursion_item.find('.list-passenger').tipso({
             size: 'tiny',
             useTitle:false,
             content:content_notify,
             animationIn:'bounceInDown'
             }).addClass('error');




             }).addClass(event_class);

             }
             });
             */
            var event_class = 'add_excursion';
            if (!$excursion_item.find('.add-more-excursion').hasClass(event_class)) {
                $excursion_item.find('.add-more-excursion').click(function () {
                    var a_excursion_index = $(this).closest('.item-excursion').index();
                    var $a_excursion_item = $(this).closest('.item-excursion');
                    if (plugin.enable_add_excursion(a_excursion_index)) {
                        //alert('ok');
                        plugin.add_excursion(a_excursion_index);
                        plugin.add_event_excursion_index(a_excursion_index + 1);
                    }
                }).addClass(event_class);
            }
            var event_class = 'remove_excursion';
            if (!$excursion_item.find('.remove-excursion').hasClass(event_class)) {
                $excursion_item.find('.remove-excursion').click(function () {
                    var a_excursion_index = $(this).closest('.item-excursion').index();
                    if (plugin.enable_remove_excursion_index(a_excursion_index)) {
                        plugin.remove_excursion_index(a_excursion_index);
                    }
                }).addClass(event_class);
            }
            var debug = plugin.settings.debug;
            if (debug) {
                var event_class = 'change_excursion_note';
                if (!$excursion_item.find('textarea[data-name="excursion_note"]').hasClass(event_class)) {
                    $excursion_item.find('textarea[data-name="excursion_note"]').change(function () {
                        plugin.update_excursion_note_excursion_index(excursion_index);
                    }).addClass(event_class);
                }
            }
            if (debug) {
                var event_class = 'add_excursion_note';
                if (!$excursion_item.find('.random-text').hasClass(event_class)) {
                    $excursion_item.find('.random-text').click(function () {
                        $excursion_item.find('textarea[data-name="excursion_note"]').delorean({
                            type: 'words',
                            amount: 5,
                            character: 'Doc',
                            tag: ''
                        }).trigger('change');
                    }).addClass(event_class);
                }
            }
        };
        plugin.format_name_for_excursions = function () {
            var $list_excursion = $element.find('.item-excursion');
            $list_excursion.each(function (excursion_index) {
                plugin.format_name_for_excursion_index(excursion_index);
            });
        };
        plugin.remove_excursion_index = function (excursion_index) {
            var total_excursion = $element.find('.item-excursion').length;
            if (total_excursion == 1) {
                return;
            }
            var $item_excursion = $element.find('.item-excursion:eq(' + excursion_index + ')');
            var list_excursion = plugin.settings.list_excursion;
            list_excursion.splice(excursion_index, 1);
            plugin.settings.list_excursion = list_excursion;
            $item_excursion.remove();
            plugin.format_name_for_excursions();
            plugin.set_label_passenger_in_excursions();
            plugin.update_list_excursioning();
            plugin.trigger_after_change();
        };
        plugin.setup_template_element = function () {
            var item_excursion_template = plugin.settings.item_excursion_template;
            var $list_excursion_item = $element.find('.item-excursion');
            for (var i = 0; i < $list_excursion_item.length; i++) {
                var $excursion_item = $element.find('.item-excursion:eq(' + i + ')');
                var tsmart_excursion_addon_id=$excursion_item.find('input[name="tsmart_excursion_addon_id"]').val();
                var item={
                    passengers: [],
                    excursion_type: 'basic',
                    excursion_note: '',
                    tsmart_excursion_addon_id:tsmart_excursion_addon_id
                };

                plugin.settings.list_excursion[i]=item;
            }

            var html_item_excursion_template = $element.find('.item-excursion').getOuterHTML();
            plugin.settings.html_item_excursion_template = html_item_excursion_template;
            var html_tr_item_excursion = $element.find('.excursioning-list .div-item-excursion').getOuterHTML();
            plugin.settings.html_tr_item_excursion = html_tr_item_excursion;
            var html_template_passenger = $element.find('ul.list-passenger li:first').getOuterHTML();
            plugin.settings.html_template_passenger = html_template_passenger;
        };
        plugin.format_name_for_excursion_index = function (excursion_index) {
            var list_excursion = plugin.settings.list_excursion;
            var excursion_item = list_excursion[excursion_index];
            var $excursion_item = $element.find('.item-excursion:eq(' + excursion_index + ')');
            $excursion_item.find('textarea[data-name="excursion_note"]').attr('name', 'list_excursion[' + excursion_index + '][excursion_note]');
            $excursion_item.find('.excursion-order').html(excursion_index + 1);
            $excursion_item.find('input[data-name="excursion_type"]').each(function (index1, input) {
                var $input = $(input);
                var data_name = $input.data('name');
                $input.attr('name', 'list_excursion[' + excursion_index + '][' + data_name + ']');
            });
            $excursion_item.find('ul.list-passenger input.passenger-item').each(function (passenger_index) {
                $(this).attr('name', 'list_excursion[' + excursion_index + '][passengers][]');
                $(this).val(passenger_index);
            });
            var passengers = excursion_item.passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                $excursion_item.find('ul.list-passenger input.passenger-item:eq(' + passenger_index + ')').prop('checked', true);
            }
        };
        plugin.format_name_for_all_excursion = function () {
            var $list_excursion_item = $element.find('.item-excursion');
            for (var i = 0; i < $list_excursion_item.length; i++) {
                plugin.format_name_for_excursion_index(i);
            }
        };
        plugin.exchange_index_for_list_excursion = function (old_index, new_index) {
            var list_excursion = plugin.settings.list_excursion;
            var temp_excursion = list_excursion[old_index];
            list_excursion[old_index] = list_excursion[new_index];
            list_excursion[new_index] = temp_excursion;
            plugin.settings.list_excursion = list_excursion;
        };
        plugin.setup_sortable = function () {
            $element.find('.table-excursioning-list .tbody').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                items: ".div-item-excursion",
                stop: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_excursioning();*/
                }
            });
            var element_key = plugin.settings.element_key;
            $element.find('.' + element_key + '_list_excursion').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                start: function (event, ui) {
                    ui.item.startPos = ui.item.index();
                },
                stop: function (event, ui) {
                    var old_index = ui.item.startPos;
                    var new_index = ui.item.index();
                    if (ui.item.startPos != ui.item.index()) {
                        plugin.exchange_index_for_list_excursion(old_index, new_index);
                        console.log(plugin.settings.list_excursion);
                        if (old_index < new_index) {
                            plugin.format_name_for_excursion_index(old_index);
                            plugin.format_name_for_excursion_index(new_index);
                        } else {
                            plugin.format_name_for_excursion_index(new_index);
                            plugin.format_name_for_excursion_index(old_index);
                        }
                        plugin.set_label_passenger_in_excursions();
                        console.log("Start position: " + ui.item.startPos);
                        console.log("New position: " + ui.item.index());
                        plugin.update_list_excursioning();
                        plugin.trigger_after_change();
                    }
                },
                update: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_excursioning();*/
                }
            });
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var passenger_config = plugin.settings.passenger_config;
            plugin.setup_template_element();
            //plugin.render_input_person();
            plugin.format_name_for_all_excursion();
            plugin.add_event_all_excursion_index();
            plugin.setup_sortable();
            var config_show_price = plugin.settings.config_show_price;
            $element.find('.price').autoNumeric('init', config_show_price);
            /*
             plugin.update_event();
             plugin.update_data();
             var debug=plugin.settings.debug;
             if(debug)
             {
             $element.find('.item-excursion .random-text').click(function(){
             var $item_excursion=$(this).closest('.item-excursion');
             $item_excursion.find('textarea[data-name="excursion_note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
             });
             }
             */
        };
        plugin.init();
    };
    // add the plugin to the jQuery.fn object
    $.fn.html_build_excursion_addon = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_build_excursion_addon')) {
                var plugin = new $.html_build_excursion_addon(this, options);
                $(this).data('html_build_excursion_addon', plugin);
            }
        });
    }
})(jQuery);


