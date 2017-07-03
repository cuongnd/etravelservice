(function ($) {

    // here we go!
    $.build_room_hotel_add_on = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            to_name: '',
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
            passenger_config: {},
            output_data: [],
            list_night_hotel: [
                {
                    passengers: [],
                    list_room_type: 'single',
                    room_note: ''
                }
            ],
            infant_title: "Infant",
            debug: false,
            teen_title: "Teen",
            children_title: "Children",
            adult_title: "Adult",
            senior_title: "Adult",
            item_room_template: {},
            event_after_change: false,
            update_passenger: false,
            limit_total: false,
            disable: false,
            trigger_after_change: null,
            private_bed: "private bed",
            share_bed: "Share bed with others",
            extra_bed: "Extra  bed",
            single: {
                max_adult: 1,
                max_children: 1,
                max_infant: 0,
                max_total: 1,
                validate_room: function (room_item, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length > max_total) {
                        var content_notify = 'our policy does not allow two persons to stay in a single room. You are suggested to select a twin/double room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_room_index(room_index) || plugin.exists_children_2_in_room_index(room_index)) {
                        var content_notify = 'our policy does not allow a child under 5 years to stay alone in single room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (room_item, range_year_old_infant, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var content_notify = 'you cannot add infant(2-5) to sigle room, you can add adult or children (>6) inside room';
                    plugin.notify(content_notify);
                    $room_item.find('.list-passenger').removeClass('error');
                    $room_item.find('.list-passenger').tipso('destroy');
                    $room_item.find('.list-passenger').tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    }).addClass('error');
                    $room_item.find('.list-passenger').tipso('show');
                    $room_item.find('.list-passenger').addClass('error');
                    return false;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    return false;
                },
                enable_select_children: function (room_item, range_year_old_children_1, passenger_index_selected, room_index) {
                    return true;
                },
                enable_select_senior_adult_teen: function (room_item, range_year_old_senior_adult_teen, passenger_index_selected, room_index) {
                    return true;
                }
            },
            double: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 2,
                max_total: 3,
                validate_room: function (room_item, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    var min_total = plugin.settings[room_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in room is ' + min_total;
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in room is ' + max_total;
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_children_2_in_room_index(room_index) && (!plugin.exists_senior_adult_teen_in_room_index(room_index) && !plugin.exists_children_1_in_room_index(room_index) )) {
                        var content_notify = 'room exists infant (2-5) you need add adult or children in this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_room_index(room_index) && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        var content_notify = 'our policy does not allow infant to stay in   room other children. You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_room_is_adult_or_children(room_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin room . You are suggested to select a triple room instead.';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (room_item, range_year_old_infant, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_room_index(room_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in room you need add one adult(>=12) or children (6-11) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_room_index(room_index) && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in room  you need add one adult(>=12) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (room_item, range_year_old_children_1, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
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
                    var exists_infant_in_room = function (passengers) {
                        var exists_infant_in_room = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_room = true;
                                break;
                            }
                        }
                        return exists_infant_in_room;
                    };
                    var total_adult_and_children = 0;
                    for (var i = 0; i < passengers.length; i++) {
                        var passenger_index = passengers[i];
                        if (plugin.is_senior_adult_teen(passenger_index) || plugin.is_children_1(passenger_index)) {
                            total_adult_and_children++;
                        }
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_room_index(room_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'exists baby infant(0-1) please select one adult(>=12)';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        return false;
                    } else if (passengers.length == max_total - 1 && total_adult_and_children == max_total - 1) {
                        var content_notify = 'double room max total adult and children is two person, if you want this  you should select twin room, or triple room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (room_item, range_year_old_senior_adult_teen, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var max_total = plugin.settings[room_type].max_total;
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
                        var content_notify = 'double room max total adult and children is two person, if you want this  you should select twin room, or triple room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
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
                validate_room: function (room_item, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    var min_total = plugin.settings[room_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in room is ' + min_total;
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in room is ' + max_total;
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_room_index(room_index) && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   room . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_room_is_adult_or_children(room_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin room . You are suggested to select a triple room instead';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (room_item, range_year_old_infant, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_room_index(room_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in room you need add one adult(>=12) or children (6-11) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_room_index(room_index) && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in room  you need add one adult(>=12) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (room_item, range_year_old_children_1, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var max_total = plugin.settings[room_type].max_total;
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
                    var exists_infant_in_room = function (passengers) {
                        var exists_infant_in_room = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_room = true;
                                break;
                            }
                        }
                        return exists_infant_in_room;
                    };
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_room_index(room_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children room  exists baby infant (0-1) in room  you need add one adult(>=12) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (room_item, range_year_old_senior_adult_teen, passenger_index_selected, room_index) {
                    return true;
                }
            },
            triple: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 3,
                max_total: 4,
                validate_room: function (room_item, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    var min_total = plugin.settings[room_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in room is ' + min_total;
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in room is ' + max_total + ' please eject some person';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_room_index(room_index) && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   room . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.get_total_senior_adult_teen(room_index) == 4) {
                        var content_notify = 'Our policy does not allow 4 teeners/adults/seniors to share the same room.You are suggested to select 2 rooms .You are suggested to select 2 rooms';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.get_total_senior_adult_teen(room_index) == 3 && plugin.exists_children_1_in_room_index(room_index)) {
                        var content_notify = 'Our policy does not allow a child over 6 years old to share the same room with 3 teeners/adults/seniors .You are suggested to select 2 rooms';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (room_item, range_year_old_infant, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_room_index(room_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in room you need add one adult(>=12) or children (6-11) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_room_index(room_index) && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in room  you need add one adult(>=12) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (room_item, range_year_old_children_1, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var max_total = plugin.settings[room_type].max_total;
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
                    var exists_infant_in_room = function (passengers) {
                        var exists_infant_in_room = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_room = true;
                                break;
                            }
                        }
                        return exists_infant_in_room;
                    };
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_room_index(room_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children room  exists baby infant (0-1) in room  you need add one adult(>=12) inside this room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        $room_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (room_item, range_year_old_senior_adult_teen, passenger_index_selected, room_index) {
                    var room_type = room_item.list_room_type;
                    var passengers = room_item.passengers;
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var max_total = plugin.settings[room_type].max_total;
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
                        var content_notify = 'triple room max total adult is three person, you should add more room';
                        plugin.notify(content_notify);
                        $room_item.find('.list-passenger').removeClass('error');
                        $room_item.find('.list-passenger').tipso('destroy');
                        $room_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $room_item.find('.list-passenger').tipso('show');
                        return false;
                    }
                    return true;
                }
            },
            element_key: 'build_room_hotel_add_on',
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
        plugin.get_list_room = function () {
            plugin.update_data();
            return plugin.settings.list_night_hotel;
        }
        plugin.get_list_passenger = function () {
            plugin.update_data();
            return plugin.settings.list_passenger;
        }
        plugin.get_total_senior_adult_teen = function (room_index) {
            var total_adult = 0;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var exists_infant2_in_room = false;
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
        plugin.all_passenger_in_room_is_adult = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var all_passenger_in_room_is_adult = true;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index) || plugin.is_children_1(passenger_index) || plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    all_passenger_in_room_is_adult = false;
                    break;
                }
            }
            return all_passenger_in_room_is_adult;
        };
        plugin.trigger_after_change = function () {
            var list_room = plugin.settings.list_night_hotel;
            var trigger_after_change = plugin.settings.trigger_after_change;
            if (trigger_after_change instanceof Function) {
                trigger_after_change(list_room);
            }
            plugin.update_list_rooming();
        };
        plugin.all_passenger_in_room_is_adult_or_children = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var all_passenger_in_room_is_adult_or_children = true;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    all_passenger_in_room_is_adult_or_children = false;
                    break;
                }
            }
            return all_passenger_in_room_is_adult_or_children;
        };
        plugin.get_senior_adult_teen_passenger_order_in_room = function (room_index, order) {
            var adult_passenger_index_of_order = -1;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
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
        plugin.get_infant_or_children_2_passenger_order_in_room = function (room_index, order) {
            var infant_or_children_2_passenger_index_of_order = -1;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
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
        plugin.get_children_2_passenger_order_in_room = function (room_index, order) {
            var children_2_passenger_index_of_order = -1;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
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
        plugin.get_children_1_passenger_order_in_room = function (room_index, order) {
            var children_1_passenger_index_of_order = -1;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
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
        plugin.get_all_children_2_in_room = function (room_index) {
            var list_all_children_2_in_room = [];
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    list_all_children_2_in_room.push(passenger_index);
                }
            }
            return list_all_children_2_in_room;
        };
        plugin.get_all_children_1_in_room = function (room_index) {
            var list_all_children_1_in_room = [];
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    list_all_children_1_in_room.push(passenger_index);
                }
            }
            return list_all_children_1_in_room;
        };
        plugin.get_all_infant_children_2_in_room = function (room_index) {
            var list_all_infant_children_2_in_room = [];
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    list_all_infant_children_2_in_room.push(passenger_index);
                }
            }
            return list_all_infant_children_2_in_room;
        };
        plugin.calculator_tour_cost_and_room_price = function () {
            var list_room = plugin.get_list_room();
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
                sale_price_private_room = plugin.settings.departure.sale_price_private_room,
                sale_price_extra_bed = plugin.settings.departure.sale_price_extra_bed,
                sale_promotion_price_senior = plugin.settings.departure.sale_promotion_price_senior,
                sale_promotion_price_adult = plugin.settings.departure.sale_promotion_price_adult,
                sale_promotion_price_teen = plugin.settings.departure.sale_promotion_price_teen,
                sale_promotion_price_children1 = plugin.settings.departure.sale_promotion_price_children1,
                sale_promotion_price_children2 = plugin.settings.departure.sale_promotion_price_children2,
                sale_promotion_price_infant = plugin.settings.departure.sale_promotion_price_infant,
                sale_promotion_price_private_room = plugin.settings.departure.sale_promotion_price_private_room,
                sale_promotion_price_extra_bed = plugin.settings.departure.sale_promotion_price_extra_bed;
            var departure = plugin.settings.departure;
            var full_charge_children1 = departure.full_charge_children1;
            full_charge_children1 = parseInt(full_charge_children1) == 1 ? true : false;
            var full_charge_children2 = departure.full_charge_children2;
            full_charge_children2 = parseInt(full_charge_children2) == 1 ? true : false;
            var tsmart_promotion_price_id = departure.tsmart_promotion_price_id;
            var tsmart_promotion_price_id = tsmart_promotion_price_id != null && tsmart_promotion_price_id != 0;
            var price_senior = departure.tsmart_discount_id > 0 ? departure.sale_discount_price_senior : sale_price_senior;
            var price_adult = departure.tsmart_discount_id > 0 ? departure.sale_discount_price_adult : sale_price_adult;
            var price_teen = departure.tsmart_discount_id > 0 ? departure.sale_discount_price_teen : sale_price_teen;
            var price_children1 = departure.tsmart_discount_id > 0 ? departure.sale_discount_price_children1 : sale_price_children1;
            var price_children2 = departure.tsmart_discount_id > 0 ? departure.sale_discount_price_children2 : sale_price_children2;
            var price_infant = departure.tsmart_discount_id > 0 ? departure.sale_discount_price_adult : sale_price_infant;
            var price_private_room =  sale_price_private_room;
            var price_extra_bed =  sale_price_extra_bed;
            for (var i = 0; i < list_room.length; i++) {
                var room_item = list_room[i];
                var room_index = i;
                var passengers = room_item.passengers;
                if (passengers.length == 0) {
                    continue;
                }
                var room_type = room_item.list_room_type;
                room_item.tour_cost_and_room_price = [];
                var func_set_tour_cost_and_room_price = function (room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note) {
                    var item_passenger = {};
                    item_passenger.passenger_index = passenger_index;
                    item_passenger.tour_cost = tour_cost;
                    item_passenger.room_price = room_price;
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
                    room_item.tour_cost_and_room_price.push(item_passenger);
                    return room_item;
                };
                if (room_type == "single") {
                    if (passengers.length == 1 && plugin.get_total_senior_adult_teen(room_index) == 1) {
                        //case 0
                        console.log("case 0");
                        var msg = "The tour cost for this passenger is based on room for 2 persons.  Therefore he or she is required to pay the room supplement fee while taking a private room.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                    } else if (plugin.get_total_children_1(room_index) == 1) {
                        //case 1a
                        console.log("case 1a");
                        if (full_charge_children1) {
                            var msg = "The tour cost for this passenger is based on room for 2 persons.  Therefore he or she is required to pay the single room supplement fee while taking a private room.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while taking a private room.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room * 2;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                    }
                } else if (room_type == "double" || room_type == "twin") {
                    //Double OR Twin
                    console.log('case room_type == "double" || room_type == "twin" ');
                    if (passengers.length == 2 && plugin.get_total_senior_adult_teen(room_index) == 2) {
                        //case case 2 adult (case 0)
                        //add adult first
                        console.log("case 0");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1) {
                        //case case 2a 1 Adult ,1 Child ( 6 - 11 years)
                        console.log("case 2a");
                        //add adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //end add adult first
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //case 2b Adult,1 Child ( 0 - 5 years)
                        //add adult first
                        console.log("case 2b");
                        var msg = "This passenger is required to pay the room supplement fee to grant the free stay to kid sharing this room.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 2 && plugin.get_total_children_1(room_index) == 2) {
                        //2 Child ( 6 - 11 years) (case 2c)
                        console.log("case 2c");
                        for (var p_index = 0; p_index < 2; p_index++) {
                            if (full_charge_children1) {
                                var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, p_index);
                                var tour_cost = price_children1;
                                var room_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, p_index);
                                var tour_cost = price_children1;
                                var room_price = price_private_room;
                                var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                                var bed_note = plugin.settings.private_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                            }
                        }
                    } else if (passengers.length == 2 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_children_2(room_index) == 1) {
                        //case 2d Child ( 6 - 11 years),1 Child ( 2 - 5 years)
                        console.log("case 2d");
                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        var passenger_index = plugin.get_infant_or_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = price_private_room;
                        var msg = "include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //2 adult 1 children (0-5)
                        //addd 2 adult first
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //children (0-5)
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {

                        //1 adult 1 children (6-11), 1 children (0-5)
                        console.log("case 3f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(room_index) == 2 && plugin.get_total_children_2(room_index) == 1) {
                        //2 children (6-11), 1 children (2-5) case 3g
                        console.log("case 3g");
                        for (var p_index = 0; p_index < 2; p_index++) {
                            if (full_charge_children1) {
                                var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, p_index);
                                var tour_cost = price_children1;
                                var room_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, p_index);
                                var tour_cost = price_children1;
                                var room_price = price_private_room;
                                var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                                var bed_note = plugin.settings.private_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                            }
                        }
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 2) {

                        //1adult 2 children 0-5 (case 3i)
                        console.log("case 3i");
                        //adult
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //2 children (0-5)
                        var msg = "";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            }
                        }
                    }
                } else if (room_type == "triple") {
                    if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 3) {
                        //3 adult (case 0)
                        console.log("case 0");
                        var list_bed_note = [];
                        list_bed_note[0] = plugin.settings.private_bed;
                        list_bed_note[1] = plugin.settings.private_bed;
                        list_bed_note[2] = plugin.settings.extra_bed;
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            var tour_cost = plugin.get_price_tour_cost_by_passenger_index(a_passenger_index, price_senior, price_adult, price_teen);
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, 0, "", list_bed_note[p_index]);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 2) {
                        //1adult,2 child 6-11 (case 3a)
                        console.log("case 3a");
                        //add adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                        var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                        var passenger_index = list_object_children_1_passenger[0].index_of;
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg = "not include any room fee. Therefore if you assign this shild to a triple sharing room, he or she is required to pay half room fee.";
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        var passenger_index = list_object_children_1_passenger[1].index_of;
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, 0, "", bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a triple  sharing room, he or she is required to pay extra bed fee.";
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //1 adult,1 child 6-11, 1 children 0-5 case 3b
                        console.log("case 3b");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg = "not include any room fee. Therefore if you assign this shild to a triple sharing room, he or she is required to pay half room fee.";
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a triple  sharing room, he or she is required to pay extra bed fee.";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.extra_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.extra_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        //1 adult, 2 children 0-5 (case 3c)
                        console.log("case 3c");
                        var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a triple  sharing room, he or she is required to pay extra bed fee.";
                        //add adult frist
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //2 children 0-5
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_room = plugin.get_all_infant_children_2_in_room(room_index);
                        var list_object_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_room);
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
                        var room_price = 0;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //group_2_infant_children_2
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.share_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_children_1(room_index) == 1) {
                        //2 adult, 1 children 6-11 (case 3d)
                        console.log("3d");
                        //adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult second
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //children (6-11)
                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //2 adult, 1 children 0-5 (case 3e)
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //children 0-5
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(room_index) == 3) {
                        //3 children 6-11 (case 3f)
                        console.log("case 3f");
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[1] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[2] = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
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
                                    room_price = price_private_room;
                                    extra_bed_price = 0;
                                } else {
                                    room_price = 0;
                                    extra_bed_price = price_extra_bed;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msgs[i], list_bed_note[p_index]);
                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(room_index) == 2 && plugin.get_total_children_2(room_index) == 1) {
                        //2 child 6-11 ,1 children 2-5 (case 3g)
                        //2children 6-11
                        console.log("case 3g");
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                    } else if (passengers.length == 3 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_children_2(room_index) == 2) {
                        //1 children 6-11, 2 child 2-5 (case 3h)
                        console.log("case 3h");
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other."
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        var list_children_2_in_room = plugin.get_all_children_2_in_room(room_index);
                        var list_object_children_2_passenger = plugin.sorting_passengers_by_year_old(list_children_2_in_room);
                        var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                        var passenger_index = list_object_children_2_passenger[0].index_of;
                        var tour_cost = price_children2;
                        var room_price = price_private_room;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        var passenger_index = list_object_children_2_passenger[1].index_of;
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 3) {
                        //1adult, 3 child 6-11 (case 4a)
                        console.log("case 4a");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //3 child 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var a_passenger_index = passengers[p_index];
                                    var tour_cost = price_children1;
                                    var room_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note = plugin.settings.private_bed;
                                    room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                                }
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[1] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[2] = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            var list_bed_note = [];
                            list_bed_note[0] = plugin.settings.private_bed;
                            list_bed_note[1] = plugin.settings.extra_bed;
                            list_bed_note[2] = plugin.settings.extra_bed;
                            var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var room_price = price_private_room;
                                    var extra_bed_price = 0;
                                } else {
                                    var room_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msgs[p_index], list_bed_note[p_index]);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //1adult, 2 child 6-11, 1 child 0-5 (case 4b)
                        console.log("case 4b");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //2 children (6-11)
                        if (full_charge_children1) {
                            var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                            //children 1
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                            //children 2
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var room_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                            //children 1
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            //children 2
                            var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, price_extra_bed, msg, bed_note);
                        }
                        // 1 children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        // 1adult, 1 child 6-11, 2 child 0-5 (case 4c)
                        console.log("case 4c");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //1 children 6-11
                        if (full_charge_children1) {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        } else {
                            //children 1
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, msg, bed_note);
                        }
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_room = plugin.get_all_infant_children_2_in_room(room_index);
                        var list_object_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_room);
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
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 3) {
                        // 1adult,  3 child 0-5 (case 4d)
                        //adult 1
                        console.log("case 4d");
                        var msg = "This passenger is required to pay the room supplement fee to grant the free stay to kid sharing this room.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        //3 children (0-5)
                        var list_bed_note = [];
                        list_bed_note[0] = plugin.settings.private_bed;
                        list_bed_note[1] = plugin.settings.extra_bed;
                        list_bed_note[2] = plugin.settings.share_bed;
                        var list_all_infant_children_2_in_room = plugin.get_all_infant_children_2_in_room(room_index);
                        var list_object_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_room);
                        for (var p_index = 0; p_index < list_object_infant_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_infant_children_2_passenger[p_index].index_of;
                            var room_price = 0;
                            if (p_index == 0 || p_index == 1) {
                                var extra_bed_price = price_extra_bed;
                            } else {
                                var extra_bed_price = price_private_room;
                            }
                            if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                            }
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                            }
                            if (p_index == 0) {
                                var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            } else {
                                var msg = "";
                            }
                            room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msg, list_bed_note[p_index]);
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_children_1(room_index) == 2) {
                        // 2adult,  2 child 6-12 (case 4e)
                        //adult 1
                        console.log("case 4e");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);

                        if (full_charge_children1) {
                            //children (6-11) 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            //children (6-11) 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                        } else {
                            var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                            var list_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                            //children (6-11) 1
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = list_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var room_price = (price_private_room + price_extra_bed) / 2;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            //children (6-11) 2
                            var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            var passenger_index = list_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var room_price = (price_private_room + price_extra_bed) / 2;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        // 2adult,  1 child 6-12 ,1 child 0-5 (case 4f)
                        //adult
                        console.log("case 4f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        if (full_charge_children1) {
                            //children (6-11)
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                        } else {
                            //children (6-11)
                            var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note = plugin.settings.extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        }
                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        // 2 adult,  ,2 child 0-5 (case 4g)
                        console.log("case 4g");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_room = plugin.get_all_infant_children_2_in_room(room_index);
                        var list_infant_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_room);
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
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 3 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        // 3 adult,  ,1 child 0-5 (case 4i)
                        console.log("case 4i");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.private_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //adult 3
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 2);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, 0, "", bed_note);
                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.share_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 4) {
                        // 4 child 6-11 (case 4l)
                        console.log("case 4l");
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                var bed_note = plugin.settings.private_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[1] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[2] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[3] = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
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
                                    var room_price = price_private_room;
                                    var extra_bed_price = 0;
                                } else {
                                    var room_price = (price_private_room + price_extra_bed) / 2;
                                    var extra_bed_price = 0;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msgs[p_index], list_bed_note[p_index]);
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 3 && plugin.get_total_children_2(room_index) == 1) {
                        // 3 child 6-11, 1 children 2-5 (case 4m)
                        console.log("case 4m");
                        //3 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var tour_cost = price_children1;
                                    var room_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note = plugin.settings.private_bed;
                                    room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                                }
                            }
                        } else {
                            var msgs = [];
                            msgs[0] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[1] = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            msgs[2] = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                            var list_bed_note = [];
                            list_bed_note[0] = plugin.settings.private_bed;
                            list_bed_note[1] = plugin.settings.private_bed;
                            list_bed_note[2] = plugin.settings.extra_bed;
                            var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var room_price = price_private_room;
                                    var extra_bed_price = 0;
                                } else {
                                    var room_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                console.log(a_passenger_index);
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, msgs[p_index], list_bed_note[p_index]);
                            }
                        }
                        //1 children 2-5
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        // 2 child 6-11, 2 children 2-5 (case 4p)
                        console.log("case 4p");
                        //2 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var tour_cost = price_children1;
                                    var room_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note = plugin.settings.private_bed;
                                    room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                                }
                            }
                        } else {
                            var list_all_children_1_in_room = plugin.get_all_children_1_in_room(room_index);
                            var list_object_children_1_passenger = plugin.sorting_passengers_by_year_old(list_all_children_1_in_room);
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                            var msg = "The tour cost for this child type does not include the room fee.  Therefore he or she is required to pay the  room supplement fee while sharing room with other.";
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        }
                        var list_all_children_2_in_room = plugin.get_all_children_2_in_room(room_index);
                        var list_object_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_children_2_in_room);
                        // children (2-5) 1
                        var msg = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        var passenger_index = list_object_children_2_passenger[0].index_of;
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note = plugin.settings.extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        // children (2-5) 2
                        var passenger_index = list_object_children_2_passenger[1].index_of;
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        var bed_note = plugin.settings.share_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_children_2(room_index) == 3) {
                        // 1 child 6-11, 3 children 2-5 (case 4q)
                        console.log("case 4q");
                        //1 children 6-11
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, "", bed_note);
                        } else {
                            var msg = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to this triple room, he or she is required to pay the room supplement fee.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var extra_bed_price = 0;
                            var bed_note = plugin.settings.private_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price, msg, bed_note);
                        }
                        //3 children 2-5
                        var list_msg = [];
                        list_msg[0] = "The tour cost for this child type does not include any room fee. Therefore if you assign this shild to this triple room, he or she is required to pay the room supplement fee.";
                        list_msg[1] = "The tour cost for this child type does not include any room fee. Therefore  he or she is required to pay extra bed fee while  staying in triple room.";
                        list_msg[2] = "";
                        var list_bed_note = [];
                        list_bed_note[0] = plugin.settings.private_bed;
                        list_bed_note[1] = plugin.settings.extra_bed;
                        list_bed_note[2] = plugin.settings.share_bed;
                        var list_all_children_2_in_room = plugin.get_all_children_2_in_room(room_index);
                        var list_object_children_2_passenger = plugin.sorting_passengers_by_year_old(list_all_children_2_in_room);
                        for (var p_index = 0; p_index < list_object_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_children_2_passenger[p_index].index_of;
                            var tour_cost = price_children2;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            if (p_index == 0) {
                                var room_price = price_private_room;
                            } else if (p_index == 1) {
                                //?
                                var extra_bed_price = price_extra_bed;
                            }
                            room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price, list_msg[p_index], list_bed_note[p_index]);
                        }
                    }
                }
                list_room[i] = room_item;
            }
            plugin.settings.list_night_hotel = list_room;
        };
        plugin.update_data = function () {
            var input_name = plugin.settings.input_name;
            var $input_name = $element.find('input[name="' + input_name + '"]');
            var data = $element.find(':input[name]').serializeObject();
            if (typeof data.list_night_hotel == "undefined") {
                return false;
            }
            for (var i = 0; i < data.list_night_hotel.length; i++) {
                if (typeof data.list_night_hotel[i].passengers == "undefined") {
                    data.list_night_hotel[i].passengers = [];
                }
            }
            var list_room = plugin.get_list_room();
            plugin.settings.list_night_hotel = data.list_night_hotel;
            data = JSON.stringify(data);
            $input_name.val(data);
            var event_after_change = plugin.settings.event_after_change;
            if (event_after_change instanceof Function) {
                event_after_change(plugin.settings.list_night_hotel);
            }
        };
        plugin.get_list_passenger_selected = function () {
            var list_room = plugin.settings.list_night_hotel;
            var list_passenger_selected = [];
            $.each(list_room, function (index, room_item) {
                var passengers = room_item.passengers;
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
        plugin.get_room_index_by_passenger_index_selected = function (passenger_index) {
            var list_room = plugin.settings.list_night_hotel;
            var return_room_index = false;
            for (var i = 0; i < list_room.length; i++) {
                var room_item = list_room[i];
                var passengers = room_item.passengers;
                if ($.inArray(passenger_index, passengers) != -1) {
                    return_room_index = i;
                    break;
                } else {
                }
            }
            return return_room_index;
        };
        plugin.lock_passenger_inside_room_index = function (room_index) {
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            var list_passenger_selected = plugin.get_list_passenger_selected();
            var list_room = plugin.get_list_room();
            var limit_total = plugin.settings.limit_total;
            var room_item = list_room[room_index];
            $room_item.find('input.passenger-item').prop("disabled", false);
            if (list_passenger_selected.length > 0) {
                for (var i = 0; i < list_passenger_selected.length; i++) {
                    var passenger_index = list_passenger_selected[i];
                    var a_room_index = plugin.get_room_index_by_passenger_index_selected(passenger_index);
                    if (a_room_index != room_index) {
                        $room_item.find('input.passenger-item:eq(' + passenger_index + ')').prop("disabled", true);
                    }
                }
            }
        };
        plugin.get_list_passenger_checked = function () {
            var list_passenger_checked = [];
            var list_room = plugin.settings.list_night_hotel;
            var list_passenger = plugin.settings.list_passenger;
            $.each(list_room, function (index, room) {
                if (typeof room.passengers != "undefined") {
                    var passengers = room.passengers;
                    $.each(passengers, function (index_passenger, order_passenger) {
                        list_passenger_checked.push(order_passenger);
                    });
                }
            });
            return list_passenger_checked;
        };
        plugin.add_list_passenger_to_room = function ($item_room) {
            var room_index = $item_room.index();
            var list_passenger = plugin.settings.list_passenger;
            var debug = plugin.settings.debug;
            var total_passenger = list_passenger.length;
            var $list_passenger = $item_room.find('.list-passenger');
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
                var full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '<span style="' + (!debug ? 'display: none;' : '') + '">(' + passenger.year_old + ')</span>';
                var key_full_name = passenger.first_name + passenger.middle_name + passenger.last_name;
                key_full_name = $.base64Encode(key_full_name);
                var $li = $('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="' + key_full_name + '" value="' + i + '" data-index="' + i + '" name="list_room[' + room_index + '][passengers][]" type="checkbox"> ' + full_name + '</label></li>');
                $li.appendTo($list_passenger);
            }
        };
        plugin.get_item_tour_cost_and_room_price_by_passenger_index = function (tour_cost_and_room_price, passenger_index) {
            if (tour_cost_and_room_price.length > 0) {
                for (var i = 0; i < tour_cost_and_room_price.length; i++) {
                    var item_tour_cost_and_room_price = tour_cost_and_room_price[i];
                    if (item_tour_cost_and_room_price.passenger_index == passenger_index) {
                        return item_tour_cost_and_room_price;
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
        plugin.update_list_rooming = function () {
            var list_room = plugin.get_list_room();
            var list_passenger = plugin.settings.list_passenger;
            var $table_rooming_list = $element.find('.table-rooming-list');
            var $tbody = $table_rooming_list.find('.tbody');
            $tbody.empty();
            var debug = plugin.settings.debug;
            var html_tr_item_room = plugin.settings.html_tr_item_room;
            $.each(list_room, function (index, room) {
                $(html_tr_item_room).appendTo($tbody);
                var $tr_item_room = $tbody.find('div.div-item-room:last');
                $tr_item_room.find('span.order').html(index + 1);
                $tr_item_room.find('div.room_type').html(room.list_room_type);
                $tr_item_room.find('div.room_note').html(room.room_note);
                var tour_cost_and_room_price = room.tour_cost_and_room_price;
                if (typeof room.passengers != "undefined" && room.passengers.length > 0) {
                    var passengers = room.passengers;
                    passengers = plugin.sorting_passengers_by_year_old(passengers);
                    var sub_list_passenger = [];
                    var sub_list_passenger_private_room = [];
                    for (var i = 0; i < passengers.length; i++) {
                        var item_passenger = passengers[i];
                        var order_passenger = item_passenger.index_of;
                        var room_price_per_passenger = 0;
                        var bed_note = "";
                        if (typeof tour_cost_and_room_price != "undefined") {
                            var item_tour_cost_and_room_price = plugin.get_item_tour_cost_and_room_price_by_passenger_index(tour_cost_and_room_price, order_passenger);
                            if (item_tour_cost_and_room_price != null) {
                                var passenger_note = item_tour_cost_and_room_price.msg;
                                var bed_note = item_tour_cost_and_room_price.bed_note;
                                bed_note = '<div class="bed_note">' + bed_note + '&nbsp;</div>';
                            }
                        }
                        sub_list_passenger_private_room.push(bed_note);
                        var full_name = item_passenger.first_name + ' ' + item_passenger.middle_name + ' ' + item_passenger.last_name + ' <span style="' + (!debug ? 'display: none;' : '') + '">(' + item_passenger.year_old + ')</span>';
                        sub_list_passenger.push('<div class="passenger-item">' + full_name + '</div>');
                    }
                    $.each(passengers, function (index_passenger, order_passenger) {
                    });
                    sub_list_passenger = sub_list_passenger.join('');
                    $tr_item_room.find('div.table_list_passenger').html(sub_list_passenger);
                    sub_list_passenger_private_room = sub_list_passenger_private_room.join('');
                    $tr_item_room.find('div.private-room').html(sub_list_passenger_private_room);
                    $.set_height_element($tr_item_room.find('.row-item-column'));
                }
            });
            $element.find('.div-item-room .delete-room').click(function delete_room(event) {
                var $self = $(this);
                var $tr_room_item = $self.closest('.div-item-room');
                var index_of_room = $tr_room_item.index();
                $element.find('.item-room:eq(' + index_of_room + ') .remove-room').trigger('click');
            });
            $element.find('.table-rooming-list .tbody').sortable("refresh");
        };
        plugin.get_total_children_2 = function (room_index) {
            var total_children_2 = 0;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    total_children_2++;
                }
            }
            return total_children_2;
        };
        plugin.get_total_children_1 = function (room_index) {
            var total_children_1 = 0;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    total_children_1++;
                }
            }
            return total_children_1;
        };
        plugin.get_total_children_1_and_children_2 = function (room_index) {
            var total_children_1_and_children_2 = 0;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index) || plugin.is_children_1(passenger_index)) {
                    total_children_1_and_children_2++;
                }
            }
            return total_children_1_and_children_2;
        };
        plugin.get_total_infant_and_children_2 = function (room_index) {
            var total_infant_and_children_2 = 0;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    total_infant_and_children_2++;
                }
            }
            return total_infant_and_children_2;
        };
        plugin.add_room = function ($self) {
            var html_item_room_template = plugin.settings.html_item_room_template;
            var $last_item_room = $element.find(".item-room:last");
            $(html_item_room_template).insertAfter($last_item_room);
            var $last_item_room = $element.find(".item-room:last");
            var item_room_template = {};
            var list_room = plugin.settings.list_night_hotel;
            item_room_template.passengers = [];
            item_room_template.list_room_type = 'single';
            item_room_template.room_note = '';
            item_room_template.tour_cost_and_room_price = [];
            item_room_template.full = false;
            list_room.push(item_room_template);
            $last_item_room.find('input[value="single"][data-name="room_type"]').prop("checked", true);
            var last_room_index = $last_item_room.index();
            if (plugin.enable_add_passenger_to_room_index(last_room_index)) {
                plugin.add_passenger_to_room_index(last_room_index);
            }
            plugin.format_name_for_room_index(last_room_index);
            plugin.lock_passenger_inside_room_index(last_room_index);
            plugin.set_label_passenger_in_rooms();
            plugin.update_list_rooming();
            plugin.trigger_after_change();
        };
        plugin.jumper_room = function (room_index) {
            $.scrollTo($element.find('.item-room:eq(' + room_index + ')'), 800);
        };
        plugin.validate = function () {
            var $list_room = $element.find('.item-room');
            var list_room = plugin.settings.list_night_hotel;
            var else_list_passenger = [];
            for (var i = 0; i < $list_room.length; i++) {
                var room_index = i;
                var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                var room_item = list_room[room_index];
                var passengers = room_item.passengers;
                var room_type = room_item.list_room_type;
                var $list_passenger = $room_item.find('ul.list-passenger');
                if (room_item.list_room_type == '') {
                    var content_notify = 'please select room type';
                    plugin.notify(content_notify);
                    $list_room.tipso('destroy');
                    $list_room.addClass('error');
                    $list_room.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_room.tipso('show');
                    plugin.jumper_room(room_index);
                    return false;
                } else if (passengers.length == 0) {
                    $list_passenger.removeClass('error');
                    $list_passenger.tipso('destroy');
                    var content_notify = 'please select passenger or remove empty room';
                    plugin.notify(content_notify);
                    $list_passenger.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_passenger.addClass('error');
                    $list_passenger.tipso('show');
                    plugin.jumper_room(room_index);
                    $list_passenger.tipso('destroy');
                    return false;
                } else if (!plugin.settings[room_type].validate_room(room_item, room_index)) {
                    plugin.jumper_room(room_index);
                    return false;
                }
                $list_passenger.removeClass('error');
            }
            var else_list_passenger = plugin.find_passenger_not_inside_room();
            if (else_list_passenger.length > 0) {
                var else_list_passenger_full_name = [];
                for (var i = 0; i < else_list_passenger.length; i++) {
                    var passenger = else_list_passenger[i];
                    var full_name = plugin.get_passenger_full_name(passenger);
                    else_list_passenger_full_name.push(full_name);
                }
                else_list_passenger_full_name = else_list_passenger_full_name.join(',');
                var content_notify = 'some passenger (' + else_list_passenger_full_name + ') not sign room, please insert passenger in to room';
                plugin.notify(content_notify);
                return false;
            }
            plugin.calculator_tour_cost_and_room_price();
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '<span style="display: none">(' + passenger.year_old + ')</span>';
            return passenger_full_name;
        };
        plugin.find_passenger_not_inside_room = function () {
            var passenger_not_inside_room = [];
            var list_passenger = plugin.settings.list_passenger.slice();
            var list_passenger_checked = plugin.get_list_passenger_checked();
            for (var i = 0; i < list_passenger.length; i++) {
                if (list_passenger_checked.indexOf(i) > -1) {
                } else {
                    passenger_not_inside_room.push(list_passenger[i]);
                }
            }
            return passenger_not_inside_room;
        }
        plugin.get_data = function () {
            return plugin.settings.output_data;
        };
        plugin.validate_data_room_index = function (room_index) {
            var $item_room = $element.find('.item-room:eq(' + room_index + ')');
            var list_room = plugin.settings.list_night_hotel;
            var room_item = list_room[room_index];
            var $type = $item_room.find('input[type="radio"][data-name="room_type"]:checked');
            if ($type.length == 0) {
                var content_notify = 'please select room type';
                plugin.notify(content_notify);
                $item_room.find('.list-room').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $item_room.find('.list-room').tipso('show');
                return false;
            }
            var type = $type.val();
            type = plugin.settings[type];
            var total_passenger_selected = $item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]:checked').length;
            if (total_passenger_selected >= type.max_total) {
                room_item.full = true;
                $item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]:not(:checked)').prop("disabled", true);
            } else {
                room_item.full = false;
                $item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]').prop("disabled", false);
            }
            list_room[room_index] = room_item;
            plugin.settings.list_night_hotel = list_room;
            return true;
        };
        plugin.reset_passenger_selected = function ($self) {
            var $item_room = $self.closest('.item-room');
            //$item_room.find('.list-passenger input.passenger-item[exists_inside_other_room]').prop("disabled", false).prop("checked", false).trigger('change');
            plugin.lock_passenger_inside_room($self);
        };
        plugin.update_event = function () {
            $element.find('input.passenger-item').unbind('change');
            $element.find('input.passenger-item').change(function selected_passenger(event) {
                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
                var $self = $(this);
                var $room = $self.closest('.item-room');
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
                    //$self.removeAttr('exists_inside_other_room');
                    //var passenger_index=$self.data('index');
                    //$element.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]').prop("disabled", false);
                }
                plugin.lock_passenger_inside_room($self);
                plugin.update_data();
                plugin.update_list_rooming();
            });
            $element.find('input[type="radio"][data-name="room_type"]').unbind('change');
            $element.find('input[type="radio"][data-name="room_type"]').change(function selected_type(event) {
                /* var $self=$(this);
                 plugin.reset_passenger_selected($self);
                 plugin.update_data();
                 plugin.update_list_rooming();*/
            });
            $element.find('textarea[data-name="room_note"]').change(function change_note(event) {
                plugin.update_data();
                plugin.update_list_rooming();
            });
        };
        plugin.update_passengers = function (list_passenger,index_action,event_name) {
            var list_night_hotel = plugin.settings.list_night_hotel;
            for(var i=0;i<list_night_hotel.length;i++){
                var room_item=list_night_hotel[i];
                var passengers=room_item.passengers;
                console.log(passengers);
                for (var j=0;j<passengers.length;j++){
                    var passenger_index=passengers[j];
                    if(typeof event_name!="undefined" && typeof index_action!="undefined"  && event_name=="remove" && passenger_index>index_action){
                        list_night_hotel[i].passengers[j]=passenger_index-1;
                    }else if(typeof event_name!="undefined" && typeof index_action!="undefined"  && event_name=="add" && passenger_index>index_action){
                        list_night_hotel[i].passengers[j]=passenger_index+1;
                    }
                }
            }
            plugin.settings.list_night_hotel=list_night_hotel;
            plugin.settings.list_passenger = list_passenger;
            var total_passenger = list_passenger.length;
            var $list_room = $element.find('.item-room');
            $list_room.each(function (room_index, room) {
                plugin.remove_passenger_not_inside_list_passenger_in_room_index(room_index);
                if (plugin.enable_add_passenger_to_room_index(room_index)) {
                    plugin.add_passenger_to_room_index(room_index);
                    plugin.format_name_for_room_index(room_index);
                    plugin.lock_passenger_inside_room_index(room_index);
                    plugin.set_label_passenger_in_rooms();
                }
                plugin.add_event_room_index(room_index);
                /*
                 var $room=$(room);
                 var $list_passenger=$room.find('.list-passenger');
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
                 var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" data-index="'+i+'" value="'+i+'" name="list_room['+room_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');
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
             plugin.update_list_rooming();


             */
            plugin.trigger_after_change();
        };
        plugin.exists_infant_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var exists_infant_in_room = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_room = true;
                    break;
                }
            }
            return exists_infant_in_room;
        };
        plugin.exists_infant_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var exists_infant_in_room = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_room = true;
                    break;
                }
            }
            return exists_infant_in_room;
        };
        plugin.exists_children_2_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var exists_children_2_in_room = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    exists_children_2_in_room = true;
                    break;
                }
            }
            return exists_children_2_in_room;
        };
        plugin.check_all_passenger_is_infant_and_children_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
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
        plugin.enable_add_room = function (room_index) {
            var $list_room = $element.find('.item-room');
            var list_room = plugin.settings.list_night_hotel;
            for (var i = 0; i < $list_room.length; i++) {
                var room_index = i;
                var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                var room_item = list_room[room_index];
                var passengers = room_item.passengers;
                var room_type = room_item.list_room_type;
                var $list_passenger = $room_item.find('ul.list-passenger');
                if (room_item.list_room_type == '') {
                    var content_notify = 'please select room type';
                    plugin.notify(content_notify);
                    $list_room.tipso('destroy');
                    $list_room.addClass('error');
                    $list_room.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_room.tipso('show');
                    plugin.jumper_room(room_index);
                    return false;
                } else if (passengers.length == 0) {
                    $list_passenger.removeClass('error');
                    $list_passenger.tipso('destroy');
                    var content_notify = 'please select passenger or remove empty room';
                    plugin.notify(content_notify);
                    $list_passenger.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_passenger.addClass('error');
                    $list_passenger.tipso('show');
                    plugin.jumper_room(room_index);
                    $list_passenger.tipso('destroy');
                    return false;
                } else if (!plugin.settings[room_type].validate_room(room_item, room_index)) {
                    plugin.jumper_room(room_index);
                    return false;
                }
                $list_passenger.removeClass('error');
            }
            var list_passenger = plugin.settings.list_passenger;
            var list_passenger_checked = plugin.get_list_passenger_checked();
            if (list_passenger_checked.length >= list_passenger.length) {
                plugin.notify('you cannot add more room');
                return false;
            }
            return true;
        };
        plugin.enable_remove_room = function (room_index) {
            return true;
        };
        plugin.enable_remove_room_index = function (room_index) {
            return true;
        };
        plugin.enable_add_passenger_to_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            var debug = plugin.settings.debug;
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '<span style="' + (!debug ? 'display: none;' : '') + '">(' + passenger.year_old + ')</span>';
            return passenger_full_name;
        };
        plugin.add_passenger_to_room_index = function (room_index) {
            var list_passenger = plugin.settings.list_passenger.slice();
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            $room_item.find('ul.list-passenger').empty();
            console.log(list_passenger);
            for (var key in list_passenger) {
                var passenger = list_passenger[key];
                var passenger_full_name = plugin.get_passenger_full_name(passenger);
                var html_template_passenger = plugin.settings.html_template_passenger;
                var $template_passenger = $(html_template_passenger);
                $template_passenger.find('.full-name').html(passenger_full_name);
                $room_item.find('ul.list-passenger').append($template_passenger);
            }
        };
        plugin.enable_change_room_type_to_room_index = function (room_index) {
            return true;
        };
        plugin.add_event_last_room_index = function () {
            var $last_room_item = $element.find('.item-room:last');
            var last_room_index = $last_room_item.index();
            plugin.add_event_room_index(last_room_index);
        };
        plugin.lock_passenger_inside_rooms = function () {
            var $list_room = $element.find('.item-room');
            $list_room.each(function (room_index) {
                plugin.lock_passenger_inside_room_index(room_index);
            });
        };
        plugin.set_label_passenger_in_room_index = function (room_index) {
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            $room_item.find('.list-passenger li .in_room').html('');
            $room_item.find('.list-passenger li').each(function (passenger_index) {
                var a_room_index = plugin.get_room_index_by_passenger_index_selected(passenger_index);
                if ($.isNumeric(a_room_index)) {
                    $(this).find('.in_room').html(' (room ' + (a_room_index + 1) + ')');
                }
            });
        };
        plugin.set_label_passenger_in_rooms = function () {
            var $list_room = $element.find('.item-room');
            $list_room.each(function (room_index) {
                plugin.set_label_passenger_in_room_index(room_index);
            });
        };
        plugin.chang_room_type_to_room_index = function (room_index) {
            var $item_room = $element.find('.item-room:eq(' + room_index + ')');
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            $.each(passengers, function (index, passenger_id) {
                $item_room.find('.passenger-item:eq(' + passenger_id + ')').prop('checked', false);
            });
            var room_type = $item_room.find('input[type="radio"][data-name="room_type"]:checked').val();
            var room_type_note = $item_room.find('input[type="radio"][data-name="room_type"]:checked').data('note');
            $item_room.find('.mobile-note').html(room_type_note);
            console.log(room_type_note);
            list_room[room_index].passengers = [];
            list_room[room_index].list_room_type = room_type;
            list_room[room_index].full = false;
            plugin.settings.list_night_hotel = list_room;
            plugin.lock_passenger_inside_rooms();
            plugin.set_label_passenger_in_rooms();
            plugin.update_list_rooming();
            plugin.trigger_after_change();
        };
        plugin.exists_senior_adult_teen_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_1_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_or_adult_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_adult_or_children_by_passenger_index(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.enable_change_passenger_inside_room_index = function (room_index) {
            return true;
        };
        plugin.check_is_full_passenger_inside_room = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var room_item = list_room[room_index];
            var room_type = room_item.list_room_type;
            var setting_room_type = plugin.settings[room_type];
            var passengers = room_item.passengers;
            return passengers.length == setting_room_type.max_total;
        };
        plugin.change_passenger_inside_room_index = function (room_index) {
            var passengers = [];
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            $room_item.find('.passenger-item').each(function (passenger_index) {
                var $self = $(this);
                if ($self.is(':checked')) {
                    passengers.push(passenger_index);
                }
            });
            var list_room = plugin.settings.list_night_hotel;
            list_room[room_index].passengers = passengers;
            list_room[room_index].full = plugin.check_is_full_passenger_inside_room(room_index);
            plugin.settings.list_night_hotel = list_room;
            plugin.lock_passenger_inside_rooms();
            plugin.set_label_passenger_in_rooms();
            plugin.calculator_tour_cost_and_room_price();
            plugin.trigger_after_change();
        };
        plugin.remove_passenger_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            list_room[room_index].passengers = [];
            plugin.settings.list_night_hotel = list_room;
        };
        plugin.remove_passenger_not_inside_list_passenger_in_room_index = function (room_index) {
            var list_passenger = plugin.settings.list_passenger;
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var passengers1 = [];
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (typeof  list_passenger[passenger_index] != "undefined") {
                    passengers1.push(passenger_index);
                }
            }
            plugin.settings.list_night_hotel[room_index].passengers = passengers1;
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
        plugin.check_room_before_add_passenger = function (room_index, current_passenger_index_selected) {
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            var $passenger = $room_item.find('input.passenger-item:eq(' + current_passenger_index_selected + ')');
            var range_year_old_infant = plugin.settings.range_year_old_infant;
            var range_year_old_children_2 = plugin.settings.range_year_old_children_2;
            var range_year_old_infant_and_children_2 = plugin.settings.range_year_old_infant_and_children_2;
            var list_room = plugin.settings.list_night_hotel;
            var room_item = list_room[room_index];
            var room_type = room_item.list_room_type;
            if (plugin.is_infant(current_passenger_index_selected) && !plugin.settings[room_type].enable_select_infant1(room_item, range_year_old_infant, current_passenger_index_selected, room_index)) {
                var content_notify = 'you cannot add more infant 0-1 because there are no adult in room';
                plugin.notify(content_notify);
                $room_item.find('.list-passenger').removeClass('error');
                $room_item.find('.list-passenger').tipso('destroy');
                $room_item.find('.list-passenger').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $room_item.find('.list-passenger').tipso('show');
                return false;
            } else {
                $room_item.find('.list-passenger').removeClass('error');
                $room_item.find('.list-passenger').tipso('destroy');
            }
            if (plugin.is_children_2(current_passenger_index_selected) && !plugin.settings[room_type].enable_select_infant2(room_item, range_year_old_children_2, current_passenger_index_selected, room_index)) {
                return false;
            } else {
                $room_item.find('.list-passenger').removeClass('error');
                $room_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_children_1 = plugin.settings.range_year_old_children_1;
            if (plugin.is_children_1(current_passenger_index_selected) && !plugin.settings[room_type].enable_select_children(room_item, range_year_old_children_1, current_passenger_index_selected, room_index)) {
                return false;
            } else {
                $room_item.find('.list-passenger').removeClass('error');
                $room_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_senior_adult_teen = plugin.settings.range_year_old_senior_adult_teen;
            if (plugin.is_senior_adult_teen(current_passenger_index_selected) && !plugin.settings[room_type].enable_select_senior_adult_teen(room_item, range_year_old_senior_adult_teen, current_passenger_index_selected, room_index)) {
                return false;
            } else {
                $room_item.find('.list-passenger').removeClass('error');
                $room_item.find('.list-passenger').tipso('destroy');
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
        plugin.update_room_note_room_index = function (room_index) {
            var $item_room = $element.find('.item-room:eq(' + room_index + ')');
            var list_room = plugin.settings.list_night_hotel;
            var passengers = list_room[room_index].passengers;
            var room_note = $item_room.find('textarea[data-name="room_note"]').val();
            list_room[room_index].room_note = room_note;
            plugin.settings.list_night_hotel = list_room;
        };
        plugin.add_event_room_index = function (room_index) {
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            var $room_type_item = $room_item.find('input[data-name="room_type"]');
            var event_class = 'change_room_type';
            if (!$room_type_item.hasClass(event_class)) {
                $room_type_item.click(function () {
                    var $self = $(this);
                    var a_room_index = $self.closest('.item-room').index();
                    if (plugin.enable_change_room_type_to_room_index(a_room_index)) {
                        plugin.chang_room_type_to_room_index(a_room_index);
                    }
                }).addClass(event_class);
            }
            var $list_passenger = $room_item.find('ul.list-passenger');
            $list_passenger.find('input.passenger-item').each(function (passenger_index) {
                var $passenger = $(this);
                var event_class = 'change_passenger';
                if (!$passenger.hasClass(event_class)) {
                    $passenger.change(function (event) {
                        var $self = $(this);
                        var a_room_index = $self.closest('.item-room').index();
                        $room_item = $self.closest('.item-room');
                        var passenger_index = $self.closest('li').index();
                        var list_room = plugin.settings.list_night_hotel;
                        var passengers = list_room[a_room_index].passengers;
                        var passenger_index_selected = $self.closest('li').index();
                        if (plugin.enable_change_passenger_inside_room_index(a_room_index)) {

                            plugin.change_passenger_inside_room_index(a_room_index);
                        } else {
                            $(this).prop('checked', !$passenger.is(':checked'));
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

             var content_notify='room is full you can not add more passenger';
             plugin.notify(content_notify);
             $room_item.find('.list-passenger').removeClass('error');
             $room_item.find('.list-passenger').tipso('destroy');
             $room_item.find('.list-passenger').tipso({
             size: 'tiny',
             useTitle:false,
             content:content_notify,
             animationIn:'bounceInDown'
             }).addClass('error');




             }).addClass(event_class);

             }
             });
             */
            var event_class = 'add_room';
            if (!$room_item.find('.add-more-room').hasClass(event_class)) {
                $room_item.find('.add-more-room').click(function () {
                    var a_room_index = $(this).closest('.item-room').index();
                    var $a_room_item = $(this).closest('.item-room');
                    if (plugin.enable_add_room(a_room_index)) {
                        //alert('ok');
                        var a_last_room_index = $element.find('.item-room').last().index();
                        var $a_last_room_item = $element.find('.item-room').last();
                        plugin.add_room(a_last_room_index);
                        plugin.add_event_room_index(a_last_room_index + 1);
                    }
                }).addClass(event_class);
            }
            var event_class = 'remove_room';
            if (!$room_item.find('.remove-room').hasClass(event_class)) {
                $room_item.find('.remove-room').click(function () {
                    var a_room_index = $(this).closest('.item-room').index();
                    if (plugin.enable_remove_room_index(a_room_index)) {
                        plugin.remove_room_index(a_room_index);
                    }
                }).addClass(event_class);
            }
            var debug = plugin.settings.debug;
            if (debug) {
                var event_class = 'change_room_note';
                if (!$room_item.find('textarea[data-name="room_note"]').hasClass(event_class)) {
                    $room_item.find('textarea[data-name="room_note"]').change(function () {
                        plugin.update_room_note_room_index(room_index);
                    }).addClass(event_class);
                }
            }
            if (debug) {
                var event_class = 'add_room_note';
                if (!$room_item.find('.random-text').hasClass(event_class)) {
                    $room_item.find('.random-text').click(function () {
                        $room_item.find('textarea[data-name="room_note"]').delorean({
                            type: 'words',
                            amount: 5,
                            character: 'Doc',
                            tag: ''
                        }).trigger('change');
                    }).addClass(event_class);
                }
            }
        };
        plugin.format_name_for_rooms = function () {
            var $list_room = $element.find('.item-room');
            $list_room.each(function (room_index) {
                plugin.format_name_for_room_index(room_index);
            });
        };
        plugin.remove_room_index = function (room_index) {
            var total_room = $element.find('.item-room').length;
            if (total_room == 1) {
                return;
            }
            var $item_room = $element.find('.item-room:eq(' + room_index + ')');
            var list_room = plugin.settings.list_night_hotel;
            list_room.splice(room_index, 1);
            plugin.settings.list_night_hotel = list_room;
            $item_room.remove();
            plugin.lock_passenger_inside_rooms();
            plugin.format_name_for_rooms();
            plugin.set_label_passenger_in_rooms();
            plugin.update_list_rooming();
            plugin.trigger_after_change();
        };
        plugin.add_passenger_to_last_room_index = function () {
            var $last_room_item = $element.find('.item-room:last');
            var last_room_index = $last_room_item.index();
            if (plugin.enable_add_passenger_to_room_index(last_room_index)) {
                plugin.add_passenger_to_room_index(last_room_index);
            }
        };
        plugin.reset_build_hotel_add_on = function () {
            $element.empty();
            var $wrapper_build_hotel_add_on=$(plugin.settings.wrapper_build_hotel_add_on);
            $wrapper_build_hotel_add_on.appendTo($element);
            $wrapper_build_hotel_add_on.find('.html_build_room_list_room').unwrap();

            plugin.add_passenger_to_last_room_index();
            plugin.format_name_for_last_room();
            plugin.add_event_last_room_index();
            plugin.setup_sortable();

        }
        plugin.setup_template_element = function () {
            var list_room = plugin.settings.list_night_hotel.slice();
            plugin.settings.item_room_template = list_room[0];


            var html_item_room_template = $element.find('.item-room').getOuterHTML();
            plugin.settings.html_item_room_template = html_item_room_template;
            var html_tr_item_room = $element.find('.rooming-list .div-item-room').getOuterHTML();
            plugin.settings.html_tr_item_room = html_tr_item_room;
            var html_template_passenger = $element.find('ul.list-passenger li:first').getOuterHTML();
            plugin.settings.html_template_passenger = html_template_passenger;

            var wrapper_build_hotel_add_on = $element.getOuterHTML();
            plugin.settings.wrapper_build_hotel_add_on = wrapper_build_hotel_add_on;

        };
        plugin.format_name_for_room_index = function (room_index) {
            var list_room = plugin.settings.list_night_hotel;
            var room_item = list_room[room_index];
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            $room_item.find('textarea[data-name="room_note"]').attr('name', 'list_room[' + room_index + '][room_note]');
            $room_item.find('.room-order').html(room_index + 1);
            $room_item.find('input[data-name="room_type"]').each(function (index1, input) {
                var $input = $(input);
                var data_name = $input.data('name');
                $input.attr('name', 'list_room[' + room_index + '][' + data_name + ']');
            });
            $room_item.find('input[data-name="room_type"][value="' + room_item.list_room_type + '"]').prop('checked', true);
            $room_item.find('ul.list-passenger input.passenger-item').each(function (passenger_index) {
                $(this).attr('name', 'list_room[' + room_index + '][passengers][]');
                $(this).val(passenger_index);
            });
            var passengers = room_item.passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                $room_item.find('ul.list-passenger input.passenger-item:eq(' + passenger_index + ')').prop('checked', true);
            }
            //var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" value="'+i+'" data-index="'+i+'" name="list_room['+room_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');
        };
        plugin.format_name_for_last_room = function () {
            var $last_room_item = $element.find('.item-room:last');
            var last_room_index = $last_room_item.index();
            plugin.format_name_for_room_index(last_room_index);
        };
        plugin.exchange_index_for_list_room = function (old_index, new_index) {
            var list_room = plugin.settings.list_night_hotel;
            var temp_room = list_room[old_index];
            list_room[old_index] = list_room[new_index];
            list_room[new_index] = temp_room;
            plugin.settings.list_night_hotel = list_room;
        };
        plugin.setup_sortable = function () {
            $element.find('.table-rooming-list .tbody').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                items: ".div-item-room",
                stop: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_rooming();*/
                }
            });
            var element_key = plugin.settings.element_key;
            $element.find('.' + element_key + '_list_room').sortable({
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
                        plugin.exchange_index_for_list_room(old_index, new_index);
                        console.log(plugin.settings.list_night_hotel);
                        if (old_index < new_index) {
                            plugin.format_name_for_room_index(old_index);
                            plugin.format_name_for_room_index(new_index);
                        } else {
                            plugin.format_name_for_room_index(new_index);
                            plugin.format_name_for_room_index(old_index);
                        }
                        plugin.set_label_passenger_in_rooms();
                        console.log("Start position: " + ui.item.startPos);
                        console.log("New position: " + ui.item.index());
                        plugin.update_list_rooming();
                        plugin.trigger_after_change();
                    }
                },
                update: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_rooming();*/
                }
            });
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var passenger_config = plugin.settings.passenger_config;
            plugin.settings.range_year_old_infant = [passenger_config.infant_passenger_age_from, passenger_config.infant_passenger_age_to];
            plugin.settings.range_year_old_children_2 = [passenger_config.children_2_passenger_age_from, passenger_config.children_2_passenger_age_to];
            plugin.settings.range_year_old_children_1 = [passenger_config.children_1_passenger_age_from, passenger_config.children_1_passenger_age_to];
            plugin.settings.range_year_old_teen = [passenger_config.teen_passenger_age_from, passenger_config.teen_passenger_age_to];
            plugin.settings.range_year_old_adult = [passenger_config.adult_passenger_age_from, passenger_config.adult_passenger_age_to];
            plugin.settings.range_year_old_senior = [passenger_config.senior_passenger_age_from, passenger_config.senior_passenger_age_to];
            plugin.range_year_old_infant_and_children_2 = [plugin.settings.range_year_old_infant[0], plugin.settings.range_year_old_children_2[1]];
            plugin.range_year_old_senior_adult_teen = [plugin.settings.range_year_old_teen[0], plugin.settings.range_year_old_senior[1]];
            plugin.range_adult_and_children = [plugin.settings.range_year_old_children_2[0], plugin.settings.range_year_old_senior[1]];
            plugin.setup_template_element();
            //plugin.render_input_person();
            plugin.add_passenger_to_last_room_index();
            plugin.format_name_for_last_room();
            plugin.add_event_last_room_index();
            plugin.setup_sortable();
            /*
             plugin.update_event();
             plugin.update_data();
             var debug=plugin.settings.debug;
             if(debug)
             {
             $element.find('.item-room .random-text').click(function(){
             var $item_room=$(this).closest('.item-room');
             $item_room.find('textarea[data-name="room_note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
             });
             }
             */
        };
        plugin.init();
    };
    // add the plugin to the jQuery.fn object
    $.fn.build_room_hotel_add_on = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('build_room_hotel_add_on')) {
                var plugin = new $.build_room_hotel_add_on(this, options);
                $(this).data('build_room_hotel_add_on', plugin);
            }
        });
    }
})(jQuery);


