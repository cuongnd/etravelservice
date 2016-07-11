(function ($) {

    // here we go!
    $.html_build_room = function (element, options) {

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
            list_room: [
                {
                    passengers: [],
                    room_type: 'single',
                    room_note: ''

                }
            ],
            item_room_template: {},
            event_after_change: false,
            update_passenger: false,
            limit_total: false,
            trigger_after_change: null,
            single: {
                max_adult: 1,
                max_children: 1,
                max_infant: 0,
                max_total: 1,
                validate_room: function (room_item, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
                    var passengers = room_item.passengers;
                    var max_total = plugin.settings[room_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_room_index(room_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (room_item, range_year_old_children_2, passenger_index_selected, room_index) {
                    var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                    var room_type = room_item.room_type;
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
                    var room_type = room_item.room_type;
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

                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (room_item, range_year_old_senior_adult_teen, passenger_index_selected, room_index) {
                    var room_type = room_item.room_type;
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
            element_key: 'html_build_room',
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
            return plugin.settings.list_room;
        }
        plugin.get_list_passenger = function () {
            plugin.update_data();
            return plugin.settings.list_passenger;
        }
        plugin.get_total_senior_adult_teen = function (room_index) {
            var total_adult = 0;
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
            var trigger_after_change = plugin.settings.trigger_after_change;
            if (trigger_after_change instanceof Function) {
                trigger_after_change(list_room);
            }
        },
            plugin.all_passenger_in_room_is_adult_or_children = function (room_index) {
                var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            full_charge_children1=full_charge_children1==1?true:false;
            var full_charge_children2 = departure.full_charge_children2;
            full_charge_children2=full_charge_children2==1?true:false;
            var virtuemart_promotion_price_id = departure.virtuemart_promotion_price_id;
            var virtuemart_promotion_price_id = virtuemart_promotion_price_id != null && virtuemart_promotion_price_id != 0;
            var price_senior = virtuemart_promotion_price_id ? sale_promotion_price_senior : sale_price_senior;
            var price_adult = virtuemart_promotion_price_id ? sale_promotion_price_adult : sale_price_adult;
            var price_teen = virtuemart_promotion_price_id ? sale_promotion_price_teen : sale_price_teen;
            var price_children1 = virtuemart_promotion_price_id ? sale_promotion_price_children1 : sale_price_children1;
            var price_children2 = virtuemart_promotion_price_id ? sale_promotion_price_children2 : sale_price_children2;
            var price_infant = virtuemart_promotion_price_id ? sale_promotion_price_infant : sale_price_infant;
            var price_private_room = virtuemart_promotion_price_id ? sale_promotion_price_private_room : sale_price_private_room;
            var price_extra_bed = virtuemart_promotion_price_id ? sale_promotion_price_extra_bed : sale_price_extra_bed;
            for (var i = 0; i < list_room.length; i++) {
                var room_item = list_room[i];
                var room_index = i;
                var passengers = room_item.passengers;
                if (passengers.length == 0) {
                    continue;
                }
                var room_type = room_item.room_type;
                room_item.tour_cost_and_room_price = [];
                var func_set_tour_cost_and_room_price = function (room_item, passenger_index, tour_cost, room_price, extra_bed_price,msg) {
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
                    room_item.tour_cost_and_room_price.push(item_passenger);
                    return room_item;
                };
                if (room_type == "single") {
                    if (passengers.length == 1 && plugin.get_total_senior_adult_teen(room_index) == 1) {
                        //case 0
                        console.log("case 0");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                    } else if (plugin.get_total_children_1(room_index) == 1) {
                        //case 1a
                        console.log("case 1a");
                        if(full_charge_children1){
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room * 2;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        }else{
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room ;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        }
                    }
                } else if (room_type == "double" || room_type == "twin") {
                    //Double OR Twin
                    if (passengers.length == 2 && plugin.get_total_senior_adult_teen(room_index) == 2) {
                        //case case 2 adult (case 0)
                        //add adult first
                        console.log("case 0");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1) {
                        //case case 2a 1 Adult ,1 Child ( 6 - 11 years)
                        console.log("case 2a");
                        //add adult first

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;

                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0);
                        //end add adult first
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                        }
                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //case 2b Adult,1 Child ( 0 - 5 years)
                        //add adult first
                        console.log("case 2b");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
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
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, p_index);
                                var tour_cost = price_children1;
                                var room_price = price_private_room;
                                var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                            }
                        }
                    } else if (passengers.length == 2 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_children_2(room_index) == 1) {
                        //case 2d Child ( 6 - 11 years),1 Child ( 2 - 5 years)
                        console.log("case 2d");
                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                        }
                        var passenger_index = plugin.get_infant_or_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = price_private_room;
                        var msg="include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);


                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //2 adult 1 children (0-5)
                        //addd 2 adult first
                        console.log("case 3e");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //children (0-5)
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
                            }

                        }


                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {

                        //1 adult 1 children (6-11), 1 children (0-5)
                        console.log("case 3f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                        }
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
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
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, p_index);
                                var tour_cost = price_children1;
                                var room_price = price_private_room;
                                var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a twin/double sharing room, he or she is required to pay half room fee.";
                                room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                            }
                        }
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                    }
                } else if (room_type == "triple") {
                    if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 3) {
                        //3 adult (case 0)
                        console.log("case 0");
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                             var tour_cost = plugin.get_price_tour_cost_by_passenger_index(a_passenger_index, price_senior, price_adult, price_teen);
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 2) {
                        //1adult,2 child 6-11 (case 3a)
                        console.log("case 3a");
                        //add adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg="not include any room fee. Therefore if you assign this shild to a triple sharing room, he or she is required to pay half room fee.";
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                        }


                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a triple  sharing room, he or she is required to pay extra bed fee.";
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price,0,msg);

                        }


                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //1 adult,1 child 6-11, 1 children 0-5 case 3b
                        console.log("case 3b");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var msg="not include any room fee. Therefore if you assign this shild to a triple sharing room, he or she is required to pay half room fee.";
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,0,msg);

                        }
                        var msg="The tour cost for this child type does not include any room fee. Therefore if you assign this shild to a triple  sharing room, he or she is required to pay extra bed fee.";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price,msg);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price,msg);
                            }

                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        //1 adult, 2 children 0-5 (case 3c)
                        console.log("case 3c");

                        //add adult frist
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                        //2 children 0-5
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;

                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
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
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_children_1(room_index) == 1) {
                        //2 adult, 1 children 6-11 (case 3d)
                        console.log("3d");
                        //adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //adult second
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        //children (6-11)
                        var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);
                        } else {
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //2 adult, 1 children 0-5 (case 3e)
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        //children 0-5


                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = price_extra_bed;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
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
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            }
                        } else {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var extra_bed_price=0;
                                if (p_index == 0 || p_index == 1) {
                                    room_price = price_private_room;
                                    extra_bed_price = 0;
                                } else {
                                    room_price = 0;
                                    extra_bed_price = price_extra_bed;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);

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
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        }
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        extra_bed_price = price_extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                    }else if(passengers.length == 3 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_children_2(room_index) == 2){
                        //1adult, 3 child 6-11 (case 3h)
                        console.log("case 3h");
                        if (full_charge_children1) {

                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);



                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        }
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = price_private_room;
                        var extra_bed_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);


                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 1);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 3) {
                        //1adult, 3 child 6-11 (case 4a)
                        console.log("case 4a");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //3 child 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var a_passenger_index = passengers[p_index];
                                    var tour_cost = price_children1;
                                    var room_price = 0;
                                    var extra_bed_price = 0;
                                    room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                                }
                            }
                        } else {
                            var a_children_1_index = 0;
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var a_passenger_index = passengers[p_index];
                                    var tour_cost = price_children1;
                                    if (a_children_1_index == 0 || a_children_1_index == 1) {
                                        var room_price = price_private_room;
                                        var extra_bed_price = 0;
                                    } else {
                                        var room_price = 0;
                                        var extra_bed_price = price_extra_bed;
                                    }
                                    room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                                    a_children_1_index++;
                                }
                            }
                        }

                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        //1adult, 2 child 6-11, 1 child 0-5 (case 4b)
                        console.log("case 4b");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //2 children (6-11)
                        if (full_charge_children1) {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                             var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                            //children 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                             var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        } else {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,extra_bed_price);
                            //children 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price,price_extra_bed);
                        }
                        // 1 children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            }

                        }


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        // 1adult, 1 child 6-11, 2 child 0-5 (case 4c)
                        console.log("case 4c");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        //1 children 6-11
                        if (full_charge_children1) {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                             var tour_cost = price_children1;
                            var room_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        } else {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                             var tour_cost = price_children1;
                            var room_price = price_private_room;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        }

                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;

                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
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
                        var extra_bed_price = price_extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);


                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 3) {
                        // 1adult,  3 child 0-5 (case 4d)
                        //adult 1
                        console.log("case 4d");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = price_private_room;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            var b_index = 0;
                            if (!plugin.is_senior_adult_teen(a_passenger_index)) {
                                var a_passenger_index = passengers[p_index];
                                var room_price = 0;
                                if (b_index == 0 || b_index == 1) {

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
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                                b_index++;
                            }
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_children_1(room_index) == 2) {
                        // 2adult,  2 child 6-12 (case 4e)
                        //adult 1
                        console.log("case 4e");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);
                        if (full_charge_children1) {
                            //children (6-11) 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                            //children (6-11) 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);
                        } else {
                            //children (6-11) 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = (price_private_room+price_extra_bed)/2;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                            //children (6-11) 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 1);
                            var tour_cost = price_children1;
                            var room_price = (price_private_room+price_extra_bed)/2;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                        }

                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 2 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        // 2adult,  1 child 6-12 ,1 child 0-5 (case 4f)
                        //adult
                        console.log("case 4f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        if (full_charge_children1) {
                            //children (6-11)
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);
                        } else {
                            //children (6-11)
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = price_extra_bed;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                        }
                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            }

                        }


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 2  && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        // 2 adult,  ,2 child 0-5 (case 4g)

                        console.log("case 4g");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;

                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;

                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
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
                        var extra_bed_price = price_extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);


                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(room_index) == 3  && plugin.get_total_infant_and_children_2(room_index) == 1) {
                        // 3 adult,  ,1 child 0-5 (case 4i)
                        console.log("case 4i");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;

                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);


                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        //adult 3
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_room(room_index, 2);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var room_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price);

                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
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
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);

                            }
                        } else {
                            var b_index = 0;
                            for (var p_index = 0; p_index < passengers.length; p_index++) {

                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                if (b_index == 0 || b_index == 1) {
                                    var room_price = price_private_room;
                                    var extra_bed_price = 0;
                                } else {
                                    var room_price = (price_private_room+price_extra_bed)/2;
                                    var extra_bed_price = 0;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                                b_index++;

                            }

                        }

                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 3 && plugin.get_total_children_2(room_index) == 1) {
                        // 3 child 6-11, 1 children 2-5 (case 4m)

                        console.log("case 4m");
                        //3 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);

                            }
                        } else {
                            var b_index = 0;
                            for (var p_index = 0; p_index < passengers.length; p_index++) {

                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                if (b_index == 0 || b_index == 1) {
                                    var room_price = price_private_room;
                                    var extra_bed_price = 0;
                                } else {
                                    var room_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                                b_index++;

                            }

                        }
                        //1 children 2-5
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);


                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 2 && plugin.get_total_infant_and_children_2(room_index) == 2) {
                        // 2 child 6-11, 2 children 2-5 (case 4p)
                        console.log("case 4p");
                        //2 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var room_price = 0;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);

                            }
                        } else {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var room_price = price_private_room;
                                var extra_bed_price = 0;
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                            }

                        }
                        // children (2-5) 1
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = price_extra_bed;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                        // children (2-5) 2
                        var passenger_index = plugin.get_children_2_passenger_order_in_room(room_index, 0);
                        var tour_cost = price_children2;
                        var room_price = 0;
                        var extra_bed_price = 0;
                        room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);
                    } else if (passengers.length == 4 && plugin.get_total_children_1(room_index) == 1 && plugin.get_total_children_2(room_index) == 3) {
                        // 1 child 6-11, 3 children 2-5 (case 4q)
                        console.log("case 4q");
                        //1 children 6-11
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = 0;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_room(room_index, 0);
                            var tour_cost = price_children1;
                            var room_price = price_private_room;
                            var extra_bed_price = 0;
                            room_item = func_set_tour_cost_and_room_price(room_item, passenger_index, tour_cost, room_price, extra_bed_price);

                        }

                        //3 children 2-5
                        var b_index = 0;
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            var tour_cost = price_children2;
                            if (!plugin.is_children_1()) {
                                var room_price = 0;
                                var extra_bed_price = 0;
                                if (b_index == 0) {
                                    var room_price = price_private_room;
                                } else if (b_index == 1) {
                                    //?
                                    var extra_bed_price = price_extra_bed;
                                }
                                room_item = func_set_tour_cost_and_room_price(room_item, a_passenger_index, tour_cost, room_price, extra_bed_price);
                                b_index++;
                            }


                        }

                    }
                }
                list_room[i] = room_item;
            }
            plugin.settings.list_room = list_room;

        };
        plugin.update_data = function () {
            return;
            var input_name = plugin.settings.input_name;
            var $input_name = $element.find('input[name="' + input_name + '"]');
            var data = $element.find(':input[name]').serializeObject();
            if (typeof data.list_room == "undefined") {
                return false;
            }
            console.log(data.list_room);
            for (var i = 0; i < data.list_room.length; i++) {
                if (typeof data.list_room[i].passengers == "undefined") {
                    data.list_room[i].passengers = [];
                }
            }

            plugin.settings.list_room = data.list_room;
            data = JSON.stringify(data);
            $input_name.val(data);
            var event_after_change = plugin.settings.event_after_change;
            if (event_after_change instanceof Function) {
                event_after_change(plugin.settings.list_room);
            }
        };
        plugin.get_list_passenger_selected = function () {
            var list_room = plugin.settings.list_room;
            var list_passenger_selected = [];
            $.each(list_room, function (index, room_item) {
                var passengers = room_item.passengers;
                list_passenger_selected = list_passenger_selected.concat(passengers);
            });
            return list_passenger_selected;
        }
        plugin.get_room_index_by_passenger_index_selected = function (passenger_index) {
            var list_room = plugin.settings.list_room;
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

            var list_room = plugin.settings.list_room;
            var limit_total = plugin.settings.limit_total;
            var room_item = list_room[room_index];
            if(list_passenger_selected.length>0)
            {
                for (var i = 0; i < list_passenger_selected.length; i++) {
                    var passenger_index = list_passenger_selected[i];
                    var a_room_index = plugin.get_room_index_by_passenger_index_selected(passenger_index);
                    if (a_room_index != room_index) {
                        $room_item.find('input.passenger-item:eq(' + passenger_index + ')').prop("disabled", true);
                    }
                }
            }else{
                $room_item.find('input.passenger-item').prop("disabled", false);
            }
        };
        plugin.get_list_passenger_checked = function () {
            var list_passenger_checked = [];
            var list_room = plugin.settings.list_room;
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
                var full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
                var key_full_name = passenger.first_name + passenger.middle_name + passenger.last_name;
                key_full_name = $.base64Encode(key_full_name);
                var $li = $('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="' + key_full_name + '" value="' + i + '" data-index="' + i + '" name="list_room[' + room_index + '][passengers][]" type="checkbox"> ' + full_name + '</label></li>');
                $li.appendTo($list_passenger);
            }
        };
        plugin.update_list_rooming = function () {
            var list_room = plugin.settings.list_room;
            var list_passenger = plugin.settings.list_passenger;
            var $table_rooming_list = $element.find('.table-rooming-list');
            var $tbody = $table_rooming_list.find('.tbody');
            $tbody.empty();
            var html_tr_item_room = plugin.settings.html_tr_item_room;
            $.each(list_room, function (index, room) {

                $(html_tr_item_room).appendTo($tbody);
                var $tr_item_room = $tbody.find('div.div-item-room:last');
                $tr_item_room.find('span.order').html(index + 1);
                $tr_item_room.find('div.room_type').html(room.room_type);
                $tr_item_room.find('div.room_note').html(room.room_note);

                if (typeof room.passengers != "undefined") {
                    var passengers = room.passengers;
                    var sub_list_passenger = [];

                    $.each(passengers, function (index_passenger, order_passenger) {
                        var item_passenger = list_passenger[order_passenger];
                        var full_name = item_passenger.first_name + ' ' + item_passenger.middle_name + ' ' + item_passenger.last_name + ' (' + item_passenger.year_old + ')';
                        sub_list_passenger.push(full_name);
                    });
                    sub_list_passenger = sub_list_passenger.join(',');
                    $tr_item_room.find('div.table_list_passenger').html(sub_list_passenger);


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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
            item_room_template.passengers = [];
            item_room_template.room_type = 'single';
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
            var list_room = plugin.settings.list_room;
            for (var i = 0; i < $list_room.length; i++) {
                var room_index = i;
                var $room_item = $element.find('.item-room:eq(' + room_index + ')');
                var room_item = list_room[room_index];
                var passengers = room_item.passengers;
                var room_type = room_item.room_type;
                var $list_passenger = $room_item.find('ul.list-passenger');
                if (room_item.room_type == '') {
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
                    plugin.jumper_room(room_index);
                    return false;
                    $list_passenger.tipso('destroy');
                } else if (!plugin.settings[room_type].validate_room(room_item, room_index)) {
                    plugin.jumper_room(room_index);
                    return false;
                }
                $list_passenger.removeClass('error');

            }
            plugin.calculator_tour_cost_and_room_price();
            return true;
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
            var list_room = plugin.settings.list_room;
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
            plugin.settings.list_room = list_room;
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
            $element.find('textarea[data-name="note"]').change(function change_note(event) {
                plugin.update_data();
                plugin.update_list_rooming();

            });


        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger = list_passenger;
            var total_passenger = list_passenger.length;
            var $list_room = $element.find('.item-room');
            $list_room.each(function (room_index, room) {
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
            if (!$html_input_passenger.validate()) {
                return false;
            } else if (!plugin.validate()) {
                return false;
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
            var list_room = plugin.settings.list_room;
            var passengers = list_room[room_index].passengers;
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
            return passenger_full_name;
        };
        plugin.add_passenger_to_room_index = function (room_index) {

            var list_passenger = plugin.settings.list_passenger.slice();
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            $room_item.find('ul.list-passenger').empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
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
            var list_room = plugin.settings.list_room;
            var passengers = list_room[room_index].passengers;
            $.each(passengers, function (index, passenger_id) {
                $item_room.find('.passenger-item:eq(' + passenger_id + ')').prop('checked', false);
            });
            var room_type = $item_room.find('input[type="radio"][data-name="room_type"]:checked').val();
            list_room[room_index].passengers = [];
            list_room[room_index].room_type = room_type;
            list_room[room_index].full = false;
            plugin.settings.list_room = list_room;
            plugin.lock_passenger_inside_rooms();
            plugin.set_label_passenger_in_rooms();
            plugin.update_list_rooming();
            plugin.trigger_after_change();

        };
        plugin.exists_senior_adult_teen_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
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
            var list_room = plugin.settings.list_room;
            var room_item = list_room[room_index];
            var room_type = room_item.room_type;
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
            var list_room = plugin.settings.list_room;
            list_room[room_index].passengers = passengers;
            list_room[room_index].full = plugin.check_is_full_passenger_inside_room(room_index);
            plugin.settings.list_room = list_room;
            plugin.lock_passenger_inside_rooms();
            plugin.set_label_passenger_in_rooms();
            plugin.update_list_rooming();
            plugin.trigger_after_change();
        };
        plugin.remove_passenger_in_room_index = function (room_index) {
            var list_room = plugin.settings.list_room;
            list_room[room_index].passengers = [];
            plugin.settings.list_room = list_room;
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
            var list_room = plugin.settings.list_room;
            var room_item = list_room[room_index];
            var room_type = room_item.room_type;
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
                    align: "right"
                }
            });
        };
        plugin.add_event_room_index = function (room_index) {
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');
            var $room_type_item = $room_item.find('input[data-name="room_type"]');
            var event_class = 'change_room_type';
            if (!$room_type_item.hasClass(event_class)) {
                $room_type_item.click(function () {
                    if (plugin.enable_change_room_type_to_room_index(room_index)) {
                        plugin.chang_room_type_to_room_index(room_index);
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
                        var list_room = plugin.settings.list_room;
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
                        plugin.add_room(a_room_index);
                        plugin.add_event_room_index(a_room_index + 1);
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
            var list_room = plugin.settings.list_room;
            list_room.splice(room_index, 1);
            plugin.settings.list_room = list_room;
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
        plugin.setup_template_element = function () {
            var list_room = plugin.settings.list_room.slice();
            plugin.settings.item_room_template = list_room[0];
            var html_item_room_template = $element.find('.item-room').getOuterHTML();
            plugin.settings.html_item_room_template = html_item_room_template;


            var html_tr_item_room = $element.find('.rooming-list .div-item-room').getOuterHTML();
            plugin.settings.html_tr_item_room = html_tr_item_room;

            var html_template_passenger = $element.find('ul.list-passenger li:first').getOuterHTML();
            plugin.settings.html_template_passenger = html_template_passenger;
        };
        plugin.format_name_for_room_index = function (room_index) {
            var list_room = plugin.settings.list_room;
            var room_item = list_room[room_index];
            var $room_item = $element.find('.item-room:eq(' + room_index + ')');

            $room_item.find('textarea[data-name="note"]').attr('name', 'list_room[' + room_index + '][note]');

            $room_item.find('.room-order').html(room_index + 1);
            $room_item.find('input[data-name="room_type"]').each(function (index1, input) {
                var $input = $(input);
                var data_name = $input.data('name');
                $input.attr('name', 'list_room[' + room_index + '][' + data_name + ']');
            });
            $room_item.find('input[data-name="room_type"][value="' + room_item.room_type + '"]').prop('checked', true);

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
            var list_room = plugin.settings.list_room;
            var temp_room = list_room[old_index];
            list_room[old_index] = list_room[new_index];
            list_room[new_index] = temp_room;
            plugin.settings.list_room = list_room;

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
                        console.log(plugin.settings.list_room);
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
            plugin.settings.range_year_old_infant = [passenger_config.infant_passenger_age_from, passenger_config.infant_passenger_age_from];
            plugin.settings.range_year_old_children_2 = [passenger_config.children_2_passenger_age_from, passenger_config.children_2_passenger_age_to];
            plugin.settings.range_year_old_children_1 = [passenger_config.children_1_passenger_age_from, passenger_config.children_1_passenger_age_to];
            plugin.settings.range_year_old_teen = [passenger_config.teen_passenger_age_from, passenger_config.teen_passenger_age_to];
            plugin.settings.range_year_old_adult = [passenger_config.adult_passenger_age_from, passenger_config.adult_passenger_age_to];
            plugin.settings.range_year_old_senior = [passenger_config.senior_passenger_age_from, passenger_config.senior_passenger_age_to];

            plugin.range_year_old_infant_and_children_2= [plugin.settings.range_year_old_infant[0], plugin.settings.range_year_old_children_2[1]];
            plugin.range_year_old_senior_adult_teen= [plugin.settings.range_year_old_teen[0], plugin.settings.range_year_old_senior[1]];
            plugin.range_adult_and_children= [plugin.settings.range_year_old_children_2[0], plugin.settings.range_year_old_senior[1]];
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
             $item_room.find('textarea[data-name="note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
             });
             }
             */

        };
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_build_room = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_build_room')) {
                var plugin = new $.html_build_room(this, options);
                $(this).data('html_build_room', plugin);

            }

        });

    }

})(jQuery);


