(function ($) {

    // here we go!
    $.html_build_passenger_summary_confirm = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            to_name: '',
            display_date_format:'YYYY-MM-DD',
            date_format:'YYYY-MM-DD',
            date_ranges: {},
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
            list_aobject: [
                {
                    passengers: [],
                    aobject_type: 'single',
                    aobject_note: ''

                }
            ],
            infant_title:"Infant",
            teen_title:"Teen",
            children_title:"Children",
            adult_title:"Adult",
            senior_title:"Adult",
            item_aobject_template: {},
            event_after_change: false,
            update_passenger: false,
            limit_total: false,
            trigger_after_change: null,
            private_bed:"private bed",
            share_bed:"Share bed with others",
            extra_bed:"Extra  bed",
            single: {
                max_adult: 1,
                max_children: 1,
                max_infant: 0,
                max_total: 1,
                validate_aobject: function (aobject_item, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length > max_total) {
                        var content_notify = 'our policy does not allow two persons to stay in a single aobject. You are suggested to select a twin/double aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_aobject_index(aobject_index) || plugin.exists_children_2_in_aobject_index(aobject_index)) {
                        var content_notify = 'our policy does not allow a child under 5 years to stay alone in single aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (aobject_item, range_year_old_infant, passenger_index_selected, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var content_notify = 'you cannot add infant(2-5) to sigle aobject, you can add adult or children (>6) inside aobject';
                    plugin.notify(content_notify);
                    $aobject_item.find('.list-passenger').removeClass('error');
                    $aobject_item.find('.list-passenger').tipso('destroy');
                    $aobject_item.find('.list-passenger').tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    }).addClass('error');
                    $aobject_item.find('.list-passenger').tipso('show');
                    $aobject_item.find('.list-passenger').addClass('error');
                    return false;
                },
                enable_select_infant2: function (aobject_item, range_year_old_children_2, passenger_index_selected, aobject_index) {

                    return false;
                },
                enable_select_children: function (aobject_item, range_year_old_children_1, passenger_index_selected, aobject_index) {

                    return true;
                },
                enable_select_senior_adult_teen: function (aobject_item, range_year_old_senior_adult_teen, passenger_index_selected, aobject_index) {

                    return true;
                }
            },
            double: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 2,
                max_total: 3,
                validate_aobject: function (aobject_item, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    var min_total = plugin.settings[aobject_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in aobject is ' + min_total;
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in aobject is ' + max_total;
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_children_2_in_aobject_index(aobject_index) && (!plugin.exists_senior_adult_teen_in_aobject_index(aobject_index) && !plugin.exists_children_1_in_aobject_index(aobject_index) )) {
                        var content_notify = 'aobject exists infant (2-5) you need add adult or children in this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_aobject_index(aobject_index) && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        var content_notify = 'our policy does not allow infant to stay in   aobject other children. You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_aobject_is_adult_or_children(aobject_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin aobject . You are suggested to select a triple aobject instead.';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (aobject_item, range_year_old_infant, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (aobject_item, range_year_old_children_2, passenger_index_selected, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_aobject_index(aobject_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in aobject you need add one adult(>=12) or children (6-11) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_aobject_index(aobject_index) && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in aobject  you need add one adult(>=12) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (aobject_item, range_year_old_children_1, passenger_index_selected, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
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
                    var exists_infant_in_aobject = function (passengers) {
                        var exists_infant_in_aobject = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_aobject = true;
                                break;
                            }
                        }
                        return exists_infant_in_aobject;
                    };
                    var total_adult_and_children = 0;
                    for (var i = 0; i < passengers.length; i++) {
                        var passenger_index = passengers[i];
                        if (plugin.is_senior_adult_teen(passenger_index) || plugin.is_children_1(passenger_index)) {
                            total_adult_and_children++;
                        }
                    }

                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_aobject_index(aobject_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'exists baby infant(0-1) please select one adult(>=12)';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        return false;
                    } else if (passengers.length == max_total - 1 && total_adult_and_children == max_total - 1) {
                        var content_notify = 'double aobject max total adult and children is two person, if you want this  you should select twin aobject, or triple aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        return false;

                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (aobject_item, range_year_old_senior_adult_teen, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var max_total = plugin.settings[aobject_type].max_total;
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
                        var content_notify = 'double aobject max total adult and children is two person, if you want this  you should select twin aobject, or triple aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');


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
                validate_aobject: function (aobject_item, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    var min_total = plugin.settings[aobject_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in aobject is ' + min_total;
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in aobject is ' + max_total;
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_aobject_index(aobject_index) && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   aobject . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_aobject_is_adult_or_children(aobject_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin aobject . You are suggested to select a triple aobject instead';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (aobject_item, range_year_old_infant, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (aobject_item, range_year_old_children_2, passenger_index_selected, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_aobject_index(aobject_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in aobject you need add one adult(>=12) or children (6-11) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_aobject_index(aobject_index) && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in aobject  you need add one adult(>=12) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (aobject_item, range_year_old_children_1, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var max_total = plugin.settings[aobject_type].max_total;
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
                    var exists_infant_in_aobject = function (passengers) {
                        var exists_infant_in_aobject = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_aobject = true;
                                break;
                            }
                        }
                        return exists_infant_in_aobject;
                    };

                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_aobject_index(aobject_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children aobject  exists baby infant (0-1) in aobject  you need add one adult(>=12) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;

                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (aobject_item, range_year_old_senior_adult_teen, passenger_index_selected, aobject_index) {
                    return true;
                }
            },
            triple: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 3,
                max_total: 4,
                validate_aobject: function (aobject_item, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    var min_total = plugin.settings[aobject_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in aobject is ' + min_total;
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in aobject is ' + max_total + ' please eject some person';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_aobject_index(aobject_index) && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   aobject . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.get_total_senior_adult_teen(aobject_index) == 4) {
                        var content_notify = 'Our policy does not allow 4 teeners/adults/seniors to share the same aobject.You are suggested to select 2 aobjects .You are suggested to select 2 aobjects';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;

                    } else if (plugin.get_total_senior_adult_teen(aobject_index) == 3 && plugin.exists_children_1_in_aobject_index(aobject_index)) {
                        var content_notify = 'Our policy does not allow a child over 6 years old to share the same aobject with 3 teeners/adults/seniors .You are suggested to select 2 aobjects';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;

                    }
                    return true;
                },
                enable_select_infant1: function (aobject_item, range_year_old_infant, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (aobject_item, range_year_old_children_2, passenger_index_selected, aobject_index) {
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var max_total = plugin.settings[aobject_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_aobject_index(aobject_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in aobject you need add one adult(>=12) or children (6-11) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_aobject_index(aobject_index) && !plugin.exists_senior_adult_teen_in_aobject_index(aobject_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in aobject  you need add one adult(>=12) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (aobject_item, range_year_old_children_1, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var max_total = plugin.settings[aobject_type].max_total;
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
                    var exists_infant_in_aobject = function (passengers) {
                        var exists_infant_in_aobject = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_aobject = true;
                                break;
                            }
                        }
                        return exists_infant_in_aobject;
                    };

                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_aobject_index(aobject_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children aobject  exists baby infant (0-1) in aobject  you need add one adult(>=12) inside this aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');
                        $aobject_item.find('.list-passenger').addClass('error');
                        return false;

                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (aobject_item, range_year_old_senior_adult_teen, passenger_index_selected, aobject_index) {
                    var aobject_type = aobject_item.aobject_type;
                    var passengers = aobject_item.passengers;
                    var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                    var max_total = plugin.settings[aobject_type].max_total;
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
                        var content_notify = 'triple aobject max total adult is three person, you should add more aobject';
                        plugin.notify(content_notify);
                        $aobject_item.find('.list-passenger').removeClass('error');
                        $aobject_item.find('.list-passenger').tipso('destroy');
                        $aobject_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $aobject_item.find('.list-passenger').tipso('show');


                        return false;
                    }
                    return true;
                }
            },
            element_key: 'html_build_passenger_summary_confirm',
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
        plugin.get_list_aobject = function () {
            plugin.update_data();
            return plugin.settings.list_aobject;
        }
        plugin.get_list_passenger = function () {
            plugin.update_data();
            return plugin.settings.list_passenger;
        }
        plugin.get_total_senior_adult_teen = function (aobject_index) {
            var total_adult = 0;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var exists_infant2_in_aobject = false;
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
        plugin.all_passenger_in_aobject_is_adult = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var all_passenger_in_aobject_is_adult = true;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index) || plugin.is_children_1(passenger_index) || plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    all_passenger_in_aobject_is_adult = false;
                    break;
                }
            }
            return all_passenger_in_aobject_is_adult;
        };
        plugin.trigger_after_change = function () {
            var list_aobject = plugin.settings.list_aobject;
            var trigger_after_change = plugin.settings.trigger_after_change;
            if (trigger_after_change instanceof Function) {
                trigger_after_change(list_aobject);
            }
        },
            plugin.all_passenger_in_aobject_is_adult_or_children = function (aobject_index) {
                var list_aobject = plugin.settings.list_aobject;
                var all_passenger_in_aobject_is_adult_or_children = true;
                var passengers = list_aobject[aobject_index].passengers;
                for (var i = 0; i < passengers.length; i++) {
                    var passenger_index = passengers[i];
                    if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                        all_passenger_in_aobject_is_adult_or_children = false;
                        break;
                    }
                }
                return all_passenger_in_aobject_is_adult_or_children;
            };
        plugin.get_senior_adult_teen_passenger_order_in_aobject = function (aobject_index, order) {
            var adult_passenger_index_of_order = -1;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
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
        plugin.get_infant_or_children_2_passenger_order_in_aobject = function (aobject_index, order) {
            var infant_or_children_2_passenger_index_of_order = -1;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
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
        plugin.get_children_2_passenger_order_in_aobject = function (aobject_index, order) {
            var children_2_passenger_index_of_order = -1;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
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
        plugin.get_children_1_passenger_order_in_aobject = function (aobject_index, order) {
            var children_1_passenger_index_of_order = -1;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
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
        plugin.swith_index = function (passenger_index_1, passenger_index_2,get_min) {
            var list_passenger = plugin.settings.list_passenger;
            var passenger_1 = list_passenger[passenger_index_1];
            var passenger_2 = list_passenger[passenger_index_2];
            if(typeof get_min=="undefined")
            {
                get_min=true;
            }
            if(get_min)
            {
                if(passenger_1.year_old<=passenger_2.year_old)
                {
                    return passenger_index_1;
                }else {
                    return passenger_index_2;
                }
            }else{
                if(passenger_1.year_old<=passenger_2.year_old)
                {
                    return passenger_index_2;
                }else {
                    return passenger_index_1;
                }
            }
        };
        plugin.get_all_children_2_in_aobject = function (aobject_index) {
            var list_all_children_2_in_aobject=[];
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    list_all_children_2_in_aobject.push(passenger_index);
                }
            }
            return list_all_children_2_in_aobject;
        };
        plugin.get_all_children_1_in_aobject = function (aobject_index) {
            var list_all_children_1_in_aobject=[];
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    list_all_children_1_in_aobject.push(passenger_index);
                }
            }
            return list_all_children_1_in_aobject;
        };
        plugin.get_all_infant_children_2_in_aobject = function (aobject_index) {
            var list_all_infant_children_2_in_aobject=[];
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    list_all_infant_children_2_in_aobject.push(passenger_index);
                }
            }
            return list_all_infant_children_2_in_aobject;
        };
        plugin.calculator_tour_cost_and_aobject_price = function () {
            var list_aobject = plugin.get_list_aobject();
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
                sale_price_private_aobject = plugin.settings.departure.sale_price_private_aobject,
                sale_price_extra_bed = plugin.settings.departure.sale_price_extra_bed,
                sale_promotion_price_senior = plugin.settings.departure.sale_promotion_price_senior,
                sale_promotion_price_adult = plugin.settings.departure.sale_promotion_price_adult,
                sale_promotion_price_teen = plugin.settings.departure.sale_promotion_price_teen,
                sale_promotion_price_children1 = plugin.settings.departure.sale_promotion_price_children1,
                sale_promotion_price_children2 = plugin.settings.departure.sale_promotion_price_children2,
                sale_promotion_price_infant = plugin.settings.departure.sale_promotion_price_infant,
                sale_promotion_price_private_aobject = plugin.settings.departure.sale_promotion_price_private_aobject,
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
            var price_private_aobject = virtuemart_promotion_price_id ? sale_promotion_price_private_aobject : sale_price_private_aobject;
            var price_extra_bed = virtuemart_promotion_price_id ? sale_promotion_price_extra_bed : sale_price_extra_bed;
            for (var i = 0; i < list_aobject.length; i++) {
                var aobject_item = list_aobject[i];
                var aobject_index = i;
                var passengers = aobject_item.passengers;
                if (passengers.length == 0) {
                    continue;
                }
                var aobject_type = aobject_item.aobject_type;
                aobject_item.tour_cost_and_aobject_price = [];
                var func_set_tour_cost_and_aobject_price = function (aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note) {
                    var item_passenger = {};
                    item_passenger.passenger_index = passenger_index;
                    item_passenger.tour_cost = tour_cost;
                    item_passenger.aobject_price = aobject_price;
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



                    aobject_item.tour_cost_and_aobject_price.push(item_passenger);
                    return aobject_item;
                };
                if (aobject_type == "single") {
                    if (passengers.length == 1 && plugin.get_total_senior_adult_teen(aobject_index) == 1) {
                        //case 0
                        console.log("case 0");
                        var msg="The tour cost for this passenger is based on aobject for 2 persons.  Therefore he or she is required to pay the aobject supplement fee while taking a private aobject.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = price_private_aobject;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                    } else if (plugin.get_total_children_1(aobject_index) == 1) {
                        //case 1a
                        console.log("case 1a");
                        if(full_charge_children1){
                            var msg="The tour cost for this passenger is based on aobject for 2 persons.  Therefore he or she is required to pay the single aobject supplement fee while taking a private aobject.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject ;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);
                        }else{
                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while taking a private aobject.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject * 2;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }
                    }
                } else if (aobject_type == "double" || aobject_type == "twin") {
                    //Double OR Twin
                    console.log('case aobject_type == "double" || aobject_type == "twin" ');
                    if (passengers.length == 2 && plugin.get_total_senior_adult_teen(aobject_index) == 2) {
                        //case case 2 adult (case 0)
                        //add adult first
                        console.log("case 0");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 1) {
                        //case case 2a 1 Adult ,1 Child ( 6 - 11 years)
                        console.log("case 2a");
                        //add adult first

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //end add adult first
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a twin/double sharing aobject, he or she is required to pay half aobject fee.";
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }

                    } else if (passengers.length == 2 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        //case 2b Adult,1 Child ( 0 - 5 years)
                        //add adult first
                        console.log("case 2b");
                        var msg="This passenger is required to pay the aobject supplement fee to grant the free stay to kid sharing this aobject.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = price_private_aobject;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);


                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            }

                        }

                    } else if (passengers.length == 2 && plugin.get_total_children_1(aobject_index) == 2) {
                        //2 Child ( 6 - 11 years) (case 2c)
                        console.log("case 2c");
                        for (var p_index = 0; p_index < 2; p_index++) {
                            if (full_charge_children1) {
                                var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, p_index);
                                var tour_cost = price_children1;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.private_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, p_index);
                                var tour_cost = price_children1;
                                var aobject_price = price_private_aobject;
                                var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a twin/double sharing aobject, he or she is required to pay half aobject fee.";
                                var bed_note=plugin.settings.private_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                            }
                        }
                    } else if (passengers.length == 2 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_children_2(aobject_index) == 1) {
                        //case 2d Child ( 6 - 11 years),1 Child ( 2 - 5 years)
                        console.log("case 2d");
                        var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                        if (full_charge_children1) {
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a twin/double sharing aobject, he or she is required to pay half aobject fee.";
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }
                        var passenger_index = plugin.get_infant_or_children_2_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = price_children2;
                        var aobject_price = price_private_aobject;
                        var msg="include any aobject fee. Therefore if you assign this shild to a twin/double sharing aobject, he or she is required to pay half aobject fee.";
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);


                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 2 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        //2 adult 1 children (0-5)
                        //addd 2 adult first
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //children (0-5)
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            }

                        }


                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {

                        //1 adult 1 children (6-11), 1 children (0-5)
                        console.log("case 3f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        } else {
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a twin/double sharing aobject, he or she is required to pay half aobject fee.";
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            }

                        }

                    } else if (passengers.length == 3 && plugin.get_total_children_1(aobject_index) == 2 && plugin.get_total_children_2(aobject_index) == 1) {
                        //2 children (6-11), 1 children (2-5) case 3g

                        console.log("case 3g");
                        for (var p_index = 0; p_index < 2; p_index++) {
                            if (full_charge_children1) {
                                var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, p_index);
                                var tour_cost = price_children1;
                                var aobject_price = 0;
                                var bed_note=plugin.settings.private_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            } else {
                                var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, p_index);
                                var tour_cost = price_children1;
                                var aobject_price = price_private_aobject;
                                var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a twin/double sharing aobject, he or she is required to pay half aobject fee.";
                                var bed_note=plugin.settings.private_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                            }
                        }
                        var passenger_index = plugin.get_children_2_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = price_children2;
                        var aobject_price = 0;
                        var bed_note=plugin.settings.share_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                    }else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 2) {

                        //1adult 2 children 0-5 (case 3i)
                        console.log("case 3i");
                        //adult
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        //2 children (0-5)
                        var msg="";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);
                            }

                        }




                    }
                } else if (aobject_type == "triple") {
                    if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 3) {
                        //3 adult (case 0)
                        console.log("case 0");
                        var list_bed_note=[];
                        list_bed_note[0]=plugin.settings.private_bed;
                        list_bed_note[1]=plugin.settings.private_bed;
                        list_bed_note[2]=plugin.settings.extra_bed;


                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                             var tour_cost = plugin.get_price_tour_cost_by_passenger_index(a_passenger_index, price_senior, price_adult, price_teen);
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price,0,"",list_bed_note[p_index]);
                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 2) {
                        //1adult,2 child 6-11 (case 3a)
                        console.log("case 3a");
                        //add adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        var  list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                        var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);



                        var passenger_index = list_object_children_1_passenger[0].index_of;

                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var msg="not include any aobject fee. Therefore if you assign this shild to a triple sharing aobject, he or she is required to pay half aobject fee.";
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }

                        var passenger_index = list_object_children_1_passenger[1].index_of;
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,0,"",bed_note);
                        } else {
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a triple  sharing aobject, he or she is required to pay extra bed fee.";
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        }


                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        //1 adult,1 child 6-11, 1 children 0-5 case 3b
                        console.log("case 3b");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price);
                        } else {
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var msg="not include any aobject fee. Therefore if you assign this shild to a triple sharing aobject, he or she is required to pay half aobject fee.";
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a triple  sharing aobject, he or she is required to pay extra bed fee.";
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note=plugin.settings.extra_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note=plugin.settings.extra_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);
                            }

                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 2) {
                        //1 adult, 2 children 0-5 (case 3c)
                        console.log("case 3c");
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to a triple  sharing aobject, he or she is required to pay extra bed fee.";
                        //add adult frist
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = price_private_aobject;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        //2 children 0-5
                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;
                        var list_all_infant_children_2_in_aobject=plugin.get_all_infant_children_2_in_aobject(aobject_index);
                        var list_object_infant_children_2_passenger=plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_aobject);

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
                        var aobject_price = 0;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //group_2_infant_children_2
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var aobject_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note=plugin.settings.share_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 2 && plugin.get_total_children_1(aobject_index) == 1) {
                        //2 adult, 1 children 6-11 (case 3d)
                        console.log("3d");
                        //adult first
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //adult second
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        //children (6-11)
                        var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                        if (full_charge_children1) {

                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                        } else {
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }
                    } else if (passengers.length == 3 && plugin.get_total_senior_adult_teen(aobject_index) == 2 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        //2 adult, 1 children 0-5 (case 3e)
                        console.log("case 3e");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        //children 0-5
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";

                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var extra_bed_price = price_extra_bed;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);
                            }

                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(aobject_index) == 3) {
                        //3 children 6-11 (case 3f)
                        console.log("case 3f");

                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.private_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            }
                        } else {
                            var msgs=[];
                            msgs[0]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[1]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[2]="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";

                            var list_bed_note=[];
                            list_bed_note[0]=plugin.settings.private_bed;
                            list_bed_note[1]=plugin.settings.private_bed;
                            list_bed_note[2]=plugin.settings.extra_bed;



                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(passengers);
                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger = list_object_children_1_passenger[p_index];
                                var a_passenger_index=a_passenger.index_of;
                                var tour_cost = price_children1;
                                var extra_bed_price=0;
                                if (p_index == 0 || p_index == 1) {
                                    aobject_price = price_private_aobject;
                                    extra_bed_price = 0;
                                } else {
                                    aobject_price = 0;
                                    extra_bed_price = price_extra_bed;
                                }
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msgs[i],list_bed_note[p_index]);

                            }
                        }
                    } else if (passengers.length == 3 && plugin.get_total_children_1(aobject_index) == 2 && plugin.get_total_children_2(aobject_index) == 1) {
                        //2 child 6-11 ,1 children 2-5 (case 3g)
                        //2children 6-11
                        console.log("case 3g");
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 1);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        } else {
                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";

                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";

                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 1);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);
                        }
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        var passenger_index = plugin.get_children_2_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = price_children2;
                        var aobject_price = 0;
                        extra_bed_price = price_extra_bed;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                    }else if(passengers.length == 3 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_children_2(aobject_index) == 2){
                        //1 children 6-11, 2 child 2-5 (case 3h)
                        console.log("case 3h");
                        if (full_charge_children1) {

                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        } else {
                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other."
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);

                        }
                        var list_children_2_in_aobject=plugin.get_all_children_2_in_aobject(aobject_index);
                        var list_object_children_2_passenger=plugin.sorting_passengers_by_year_old(list_children_2_in_aobject);
                        var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                        var passenger_index = list_object_children_2_passenger[0].index_of;
                        var tour_cost = price_children2;
                        var aobject_price = price_private_aobject;
                        var extra_bed_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        var passenger_index = list_object_children_2_passenger[1].index_of;
                        var tour_cost = price_children2;
                        var aobject_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 3) {
                        //1adult, 3 child 6-11 (case 4a)
                        console.log("case 4a");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //3 child 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if (plugin.is_children_1(a_passenger_index)) {
                                    var a_passenger_index = passengers[p_index];
                                    var tour_cost = price_children1;
                                    var aobject_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note=plugin.settings.private_bed;
                                    aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                                }
                            }
                        } else {
                            var msgs=[];
                            msgs[0]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[1]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[2]="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";


                            var list_bed_note=[];
                            list_bed_note[0]=plugin.settings.private_bed;
                            list_bed_note[1]=plugin.settings.extra_bed;
                            list_bed_note[2]=plugin.settings.extra_bed;

                            var list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);


                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var aobject_price = price_private_aobject;
                                    var extra_bed_price = 0;
                                } else {
                                    var aobject_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msgs[p_index],list_bed_note[p_index]);
                            }
                        }

                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 2 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        //1adult, 2 child 6-11, 1 child 0-5 (case 4b)
                        console.log("case 4b");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //2 children (6-11)
                        if (full_charge_children1) {
                            var list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);
                            //children 1
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                             var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                            //children 2
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                             var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                            var aobject_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        } else {

                            var list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);

                            //children 1
                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,extra_bed_price,msg,bed_note);
                            //children 2
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,price_extra_bed,msg,bed_note);
                        }
                        // 1 children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            }

                        }


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 2) {
                        // 1adult, 1 child 6-11, 2 child 0-5 (case 4c)
                        console.log("case 4c");
                        //adult 1

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        //1 children 6-11
                        if (full_charge_children1) {
                            //children 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                             var tour_cost = price_children1;
                            var aobject_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        } else {
                            //children 1
                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                             var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,msg,bed_note);
                        }

                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;

                        var list_all_infant_children_2_in_aobject=plugin.get_all_infant_children_2_in_aobject(aobject_index);
                        var list_object_infant_children_2_passenger=plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_aobject);

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
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var aobject_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);


                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var aobject_price = 0;
                        var extra_bed_price = 0;
                        var bed_note=plugin.settings.share_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 3) {
                        // 1adult,  3 child 0-5 (case 4d)
                        //adult 1
                        console.log("case 4d");
                        var msg="This passenger is required to pay the aobject supplement fee to grant the free stay to kid sharing this aobject.";
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = price_private_aobject;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);


                        //3 children (0-5)

                        var list_bed_note=[];
                        list_bed_note[0]=plugin.settings.private_bed;
                        list_bed_note[1]=plugin.settings.extra_bed;
                        list_bed_note[2]=plugin.settings.share_bed;


                        var list_all_infant_children_2_in_aobject=plugin.get_all_infant_children_2_in_aobject(aobject_index);
                        var list_object_infant_children_2_passenger=plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_aobject);

                        for (var p_index = 0; p_index < list_object_infant_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_infant_children_2_passenger[p_index].index_of;
                            var aobject_price = 0;
                            if (p_index == 0 || p_index == 1) {

                                var extra_bed_price = price_extra_bed;
                            } else {

                                var extra_bed_price = price_private_aobject;
                            }
                            if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                            }
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                            }
                            if(p_index==0)
                            {
                                var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                            }else {
                                var msg="";
                            }

                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msg,list_bed_note[p_index]);
                        }
                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 2 && plugin.get_total_children_1(aobject_index) == 2) {
                        // 2adult,  2 child 6-12 (case 4e)
                        //adult 1
                        console.log("case 4e");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);
                        if (full_charge_children1) {
                            //children (6-11) 1
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);

                            //children (6-11) 2
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 1);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                        } else {

                            var list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                            var list_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);


                            //children (6-11) 1
                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            var passenger_index = list_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var aobject_price = (price_private_aobject+price_extra_bed)/2;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                            //children (6-11) 2
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                            var passenger_index = list_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var aobject_price = (price_private_aobject+price_extra_bed)/2;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        }

                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 2 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        // 2adult,  1 child 6-12 ,1 child 0-5 (case 4f)
                        //adult
                        console.log("case 4f");
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        if (full_charge_children1) {
                            //children (6-11)
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                        } else {
                            //children (6-11)
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = price_extra_bed;
                            var bed_note=plugin.settings.extra_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        }
                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            }

                        }


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 2  && plugin.get_total_infant_and_children_2(aobject_index) == 2) {
                        // 2 adult,  ,2 child 0-5 (case 4g)

                        console.log("case 4g");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        var group_1_infant_children_2 = null;
                        var group_2_infant_children_2 = null;
                        var group_infant_children_2_price = 0;

                        var list_all_infant_children_2_in_aobject=plugin.get_all_infant_children_2_in_aobject(aobject_index);
                        var list_infant_children_2_passenger=plugin.sorting_passengers_by_year_old(list_all_infant_children_2_in_aobject);

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
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        var passenger_index = group_1_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var aobject_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);


                        //group_2_infant_children_2
                        var passenger_index = group_2_infant_children_2;
                        var tour_cost = group_infant_children_2_price;
                        var aobject_price = 0;
                        var extra_bed_price = 0;
                        var bed_note=plugin.settings.share_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);


                    } else if (passengers.length == 4 && plugin.get_total_senior_adult_teen(aobject_index) == 3  && plugin.get_total_infant_and_children_2(aobject_index) == 1) {
                        // 3 adult,  ,1 child 0-5 (case 4i)
                        console.log("case 4i");
                        //adult 1
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 0);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);


                        //adult 2
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 1);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.private_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        //adult 3
                        var passenger_index = plugin.get_senior_adult_teen_passenger_order_in_aobject(aobject_index, 2);
                         var tour_cost = plugin.get_price_tour_cost_by_passenger_index(passenger_index, price_senior, price_adult, price_teen);
                        var aobject_price = 0;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price,0,"",bed_note);

                        //children 0-5
                        for (var p_index = 0; p_index < passengers.length; p_index++) {
                            var a_passenger_index = passengers[p_index];
                            if (plugin.is_infant(a_passenger_index)) {
                                var tour_cost = price_infant;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            } else if (plugin.is_children_2(a_passenger_index)) {
                                var tour_cost = price_children2;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.share_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                            }

                        }


                    } else if (passengers.length == 4 && plugin.get_total_children_1(aobject_index) == 4) {
                        // 4 child 6-11 (case 4l)
                        console.log("case 4l");
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                var tour_cost = price_children1;
                                var aobject_price = 0;
                                var extra_bed_price = 0;
                                var bed_note=plugin.settings.private_bed;
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);

                            }
                        } else {
                            var msgs=[];
                            msgs[0]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[1]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[2]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[3]="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";


                            var list_bed_note=[];
                            list_bed_note[0]=plugin.settings.private_bed;
                            list_bed_note[1]=plugin.settings.private_bed;
                            list_bed_note[2]=plugin.settings.extra_bed;
                            list_bed_note[3]=plugin.settings.extra_bed;


                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(passengers);

                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {

                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var aobject_price = price_private_aobject;
                                    var extra_bed_price = 0;
                                } else {
                                    var aobject_price = (price_private_aobject+price_extra_bed)/2;
                                    var extra_bed_price = 0;
                                }
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msgs[p_index],list_bed_note[p_index]);

                            }

                        }

                    } else if (passengers.length == 4 && plugin.get_total_children_1(aobject_index) == 3 && plugin.get_total_children_2(aobject_index) == 1) {
                        // 3 child 6-11, 1 children 2-5 (case 4m)

                        console.log("case 4m");
                        //3 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if(plugin.is_children_1(a_passenger_index)){
                                    var tour_cost = price_children1;
                                    var aobject_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note=plugin.settings.private_bed;
                                    aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                                }

                            }
                        } else {
                            var msgs=[];
                            msgs[0]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[1]="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            msgs[2]="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";

                            var list_bed_note=[];
                            list_bed_note[0]=plugin.settings.private_bed;
                            list_bed_note[1]=plugin.settings.private_bed;
                            list_bed_note[2]=plugin.settings.extra_bed;

                            var list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);


                            for (var p_index = 0; p_index < list_object_children_1_passenger.length; p_index++) {
                                var a_passenger_index = list_object_children_1_passenger[p_index].index_of;
                                var tour_cost = price_children1;
                                if (p_index == 0 || p_index == 1) {
                                    var aobject_price = price_private_aobject;
                                    var extra_bed_price = 0;
                                } else {
                                    var aobject_price = 0;
                                    var extra_bed_price = price_extra_bed;
                                }
                                console.log(a_passenger_index);
                                aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,msgs[p_index],list_bed_note[p_index]);

                            }

                        }
                        //1 children 2-5
                        var passenger_index = plugin.get_children_2_passenger_order_in_aobject(aobject_index, 0);
                        var tour_cost = price_children2;
                        var aobject_price = 0;
                        var extra_bed_price = 0;
                        var bed_note=plugin.settings.share_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);


                    } else if (passengers.length == 4 && plugin.get_total_children_1(aobject_index) == 2 && plugin.get_total_infant_and_children_2(aobject_index) == 2) {
                        // 2 child 6-11, 2 children 2-5 (case 4p)
                        console.log("case 4p");
                        //2 children 6-11
                        if (full_charge_children1) {
                            for (var p_index = 0; p_index < passengers.length; p_index++) {
                                var a_passenger_index = passengers[p_index];
                                if(plugin.is_children_1(a_passenger_index)){
                                    var tour_cost = price_children1;
                                    var aobject_price = 0;
                                    var extra_bed_price = 0;
                                    var bed_note=plugin.settings.private_bed;
                                    aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                                }
                            }
                        } else {
                            var list_all_children_1_in_aobject=plugin.get_all_children_1_in_aobject(aobject_index);
                            var list_object_children_1_passenger=plugin.sorting_passengers_by_year_old(list_all_children_1_in_aobject);

                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            var passenger_index = list_object_children_1_passenger[0].index_of;
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);


                            var msg="The tour cost for this child type does not include the aobject fee.  Therefore he or she is required to pay the  aobject supplement fee while sharing aobject with other.";
                            var passenger_index = list_object_children_1_passenger[1].index_of;
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.private_bed;

                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        }
                        var list_all_children_2_in_aobject=plugin.get_all_children_2_in_aobject(aobject_index);
                        var list_object_children_2_passenger=plugin.sorting_passengers_by_year_old(list_all_children_2_in_aobject);

                        // children (2-5) 1
                        var msg="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        var passenger_index = list_object_children_2_passenger[0].index_of;
                        var tour_cost = price_children2;
                        var aobject_price = 0;
                        var extra_bed_price = price_extra_bed;
                        var bed_note=plugin.settings.extra_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        // children (2-5) 2
                        var passenger_index = list_object_children_2_passenger[1].index_of;
                        var tour_cost = price_children2;
                        var aobject_price = 0;
                        var extra_bed_price = 0;
                        var bed_note=plugin.settings.share_bed;
                        aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                    } else if (passengers.length == 4 && plugin.get_total_children_1(aobject_index) == 1 && plugin.get_total_children_2(aobject_index) == 3) {
                        // 1 child 6-11, 3 children 2-5 (case 4q)
                        console.log("case 4q");
                        //1 children 6-11
                        if (full_charge_children1) {
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,"",bed_note);
                        } else {
                            var msg="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to this triple aobject, he or she is required to pay the aobject supplement fee.";
                            var passenger_index = plugin.get_children_1_passenger_order_in_aobject(aobject_index, 0);
                            var tour_cost = price_children1;
                            var aobject_price = price_private_aobject;
                            var extra_bed_price = 0;
                            var bed_note=plugin.settings.private_bed;
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, passenger_index, tour_cost, aobject_price, extra_bed_price,msg,bed_note);

                        }

                        //3 children 2-5
                        var list_msg=[];
                        list_msg[0]="The tour cost for this child type does not include any aobject fee. Therefore if you assign this shild to this triple aobject, he or she is required to pay the aobject supplement fee.";
                        list_msg[1]="The tour cost for this child type does not include any aobject fee. Therefore  he or she is required to pay extra bed fee while  staying in triple aobject.";
                        list_msg[2]="";


                        var list_bed_note=[];
                        list_bed_note[0]=plugin.settings.private_bed;
                        list_bed_note[1]=plugin.settings.extra_bed;
                        list_bed_note[2]=plugin.settings.share_bed;


                        var list_all_children_2_in_aobject=plugin.get_all_children_2_in_aobject(aobject_index);
                        var list_object_children_2_passenger=plugin.sorting_passengers_by_year_old(list_all_children_2_in_aobject);


                        for (var p_index = 0; p_index < list_object_children_2_passenger.length; p_index++) {
                            var a_passenger_index = list_object_children_2_passenger[p_index].index_of;
                            var tour_cost = price_children2;
                            var aobject_price = 0;
                            var extra_bed_price = 0;
                            if (p_index == 0) {
                                var aobject_price = price_private_aobject;
                            } else if (p_index == 1) {
                                //?
                                var extra_bed_price = price_extra_bed;
                            }
                            aobject_item = func_set_tour_cost_and_aobject_price(aobject_item, a_passenger_index, tour_cost, aobject_price, extra_bed_price,list_msg[p_index],list_bed_note[p_index]);


                        }

                    }
                }
                list_aobject[i] = aobject_item;
            }
            plugin.settings.list_aobject = list_aobject;

        };
        plugin.update_data = function () {
            return;
            var input_name = plugin.settings.input_name;
            var $input_name = $element.find('input[name="' + input_name + '"]');
            var data = $element.find(':input[name]').serializeObject();
            if (typeof data.list_aobject == "undefined") {
                return false;
            }
            console.log(data.list_aobject);
            for (var i = 0; i < data.list_aobject.length; i++) {
                if (typeof data.list_aobject[i].passengers == "undefined") {
                    data.list_aobject[i].passengers = [];
                }
            }
            var list_aobject=plugin.get_list_aobject();

            plugin.settings.list_aobject = data.list_aobject;
            data = JSON.stringify(data);
            $input_name.val(data);
            var event_after_change = plugin.settings.event_after_change;
            if (event_after_change instanceof Function) {
                event_after_change(plugin.settings.list_aobject);
            }
        };
        plugin.get_list_passenger_selected = function () {
            var list_aobject = plugin.settings.list_aobject;
            var list_passenger_selected = [];
            $.each(list_aobject, function (index, aobject_item) {
                var passengers = aobject_item.passengers;
                list_passenger_selected = list_passenger_selected.concat(passengers);
            });
            return list_passenger_selected;
        }
        plugin.sorting_passengers_by_year_old=function(passengers){
            var list_passenger= plugin.settings.list_passenger;
            var list_object_passenger=[];
            for (var p_index = 0; p_index < passengers.length; p_index++) {
                var a_passenger_index=passengers[p_index];
                var passenger=list_passenger[a_passenger_index];
                passenger.index_of=a_passenger_index;
                list_object_passenger.push(passenger);
            }
            list_object_passenger.sort(function(a, b) {
                return parseFloat(a.year_old) - parseFloat(b.year_old);
            });
            list_object_passenger.reverse();
            return list_object_passenger;

        };
        plugin.get_aobject_index_by_passenger_index_selected = function (passenger_index) {
            var list_aobject = plugin.settings.list_aobject;
            var return_aobject_index = false;
            for (var i = 0; i < list_aobject.length; i++) {
                var aobject_item = list_aobject[i];
                var passengers = aobject_item.passengers;
                if ($.inArray(passenger_index, passengers) != -1) {
                    return_aobject_index = i;
                    break;
                } else {

                }

            }
            return return_aobject_index;
        };
        plugin.lock_passenger_inside_aobject_index = function (aobject_index) {
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var list_passenger_selected = plugin.get_list_passenger_selected();

            var list_aobject = plugin.get_list_aobject();
            var limit_total = plugin.settings.limit_total;
            var aobject_item = list_aobject[aobject_index];
            $aobject_item.find('input.passenger-item').prop("disabled", false);
            if(list_passenger_selected.length>0)
            {
                for (var i = 0; i < list_passenger_selected.length; i++) {
                    var passenger_index = list_passenger_selected[i];
                    var a_aobject_index = plugin.get_aobject_index_by_passenger_index_selected(passenger_index);
                    console.log("a_aobject_index:"+a_aobject_index);
                    if (a_aobject_index != aobject_index) {
                        $aobject_item.find('input.passenger-item:eq(' + passenger_index + ')').prop("disabled", true);
                    }
                }
            }
        };
        plugin.get_list_passenger_checked = function () {
            var list_passenger_checked = [];
            var list_aobject = plugin.settings.list_aobject;
            var list_passenger = plugin.settings.list_passenger;
            $.each(list_aobject, function (index, aobject) {

                if (typeof aobject.passengers != "undefined") {
                    var passengers = aobject.passengers;
                    $.each(passengers, function (index_passenger, order_passenger) {
                        list_passenger_checked.push(order_passenger);
                    });


                }
            });
            return list_passenger_checked;
        };
        plugin.add_list_passenger_to_aobject = function ($item_aobject) {
            var aobject_index = $item_aobject.index();
            var list_passenger = plugin.settings.list_passenger;
            var total_passenger = list_passenger.length;
            var $list_passenger = $item_aobject.find('.list-passenger');
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
                var $li = $('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="' + key_full_name + '" value="' + i + '" data-index="' + i + '" name="list_aobject[' + aobject_index + '][passengers][]" type="checkbox"> ' + full_name + '</label></li>');
                $li.appendTo($list_passenger);
            }
        };
        plugin.get_item_tour_cost_and_aobject_price_by_passenger_index = function (tour_cost_and_aobject_price,passenger_index) {

            if(tour_cost_and_aobject_price.length>0){
                for (var i=0;i<tour_cost_and_aobject_price.length;i++){
                    var item_tour_cost_and_aobject_price=tour_cost_and_aobject_price[i];
                    if(item_tour_cost_and_aobject_price.passenger_index==passenger_index){
                        return item_tour_cost_and_aobject_price;
                    }
                }
            }
            return null;
        };

        plugin.get_passenger_title_by_passenger_index = function (index_passenger) {
            if(plugin.is_infant(index_passenger))
            {
                return plugin.settings.infant_title;
            }else if(plugin.is_children_1(index_passenger) ||  plugin.is_children_2(index_passenger) ){
                return plugin.settings.children_title;
            }else if(plugin.is_teen(index_passenger)  ){
                return plugin.settings.teen_title;
            }else if(plugin.is_adult(index_passenger)  ){
                return plugin.settings.adult_title;
            }else if(plugin.is_senior(index_passenger)){
                return plugin.settings.senior_title;
            }
        };
        plugin.update_list_aobjecting = function () {
            var list_aobject = plugin.get_list_aobject();
            var list_passenger = plugin.settings.list_passenger;
            var $table_aobjecting_list = $element.find('.table-aobjecting-list');
            var $tbody = $table_aobjecting_list.find('.tbody');
            $tbody.empty();
            var html_tr_item_aobject = plugin.settings.html_tr_item_aobject;
            $.each(list_aobject, function (index, aobject) {

                $(html_tr_item_aobject).appendTo($tbody);
                var $tr_item_aobject = $tbody.find('div.div-item-aobject:last');
                $tr_item_aobject.find('span.order').html(index + 1);
                $tr_item_aobject.find('div.aobject_type').html(aobject.aobject_type);
                $tr_item_aobject.find('div.aobject_note').html(aobject.aobject_note);
                var tour_cost_and_aobject_price = aobject.tour_cost_and_aobject_price;

                if (typeof aobject.passengers != "undefined" && aobject.passengers.length>0) {
                    var passengers = aobject.passengers;
                    passengers=plugin.sorting_passengers_by_year_old(passengers);
                    var sub_list_passenger = [];
                    var sub_list_passenger_private_aobject = [];
                    for(var i=0;i<passengers.length;i++){
                        var item_passenger = passengers[i];
                        var order_passenger=item_passenger.index_of;
                        var aobject_price_per_passenger=0;
                        var bed_note="";
                        if(typeof tour_cost_and_aobject_price!="undefined")
                        {
                            var item_tour_cost_and_aobject_price=plugin.get_item_tour_cost_and_aobject_price_by_passenger_index(tour_cost_and_aobject_price,order_passenger);

                            if(item_tour_cost_and_aobject_price!=null)
                            {
                                var passenger_note=item_tour_cost_and_aobject_price.msg;
                                var bed_note=item_tour_cost_and_aobject_price.bed_note;

                                bed_note='<div class="bed_note">'+bed_note+'&nbsp;</div>';
                            }
                        }
                        sub_list_passenger_private_aobject.push(bed_note);
                        var full_name = item_passenger.first_name + ' ' + item_passenger.middle_name + ' ' + item_passenger.last_name + ' (' + item_passenger.year_old + ')';
                        sub_list_passenger.push('<div class="passenger-item">'+full_name+'</div>');
                    }
                    $.each(passengers, function (index_passenger, order_passenger) {


                    });
                    sub_list_passenger = sub_list_passenger.join('');
                    $tr_item_aobject.find('div.table_list_passenger').html(sub_list_passenger);

                    sub_list_passenger_private_aobject = sub_list_passenger_private_aobject.join('');
                    $tr_item_aobject.find('div.private-aobject').html(sub_list_passenger_private_aobject);
                    $.set_height_element($tr_item_aobject.find('.row-item-column'));


                }
            });
            $element.find('.div-item-aobject .delete-aobject').click(function delete_aobject(event) {
                var $self = $(this);
                var $tr_aobject_item = $self.closest('.div-item-aobject');
                var index_of_aobject = $tr_aobject_item.index();
                $element.find('.item-aobject:eq(' + index_of_aobject + ') .remove-aobject').trigger('click');

            });

            $element.find('.table-aobjecting-list .tbody').sortable("refresh");

        };

        plugin.get_total_children_2 = function (aobject_index) {
            var total_children_2 = 0;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    total_children_2++;
                }
            }
            return total_children_2;
        };
        plugin.get_total_children_1 = function (aobject_index) {
            var total_children_1 = 0;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    total_children_1++;
                }
            }
            return total_children_1;
        };
        plugin.get_total_children_1_and_children_2 = function (aobject_index) {
            var total_children_1_and_children_2 = 0;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index) || plugin.is_children_1(passenger_index)) {
                    total_children_1_and_children_2++;
                }
            }
            return total_children_1_and_children_2;
        };
        plugin.get_total_infant_and_children_2 = function (aobject_index) {
            var total_infant_and_children_2 = 0;
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    total_infant_and_children_2++;
                }
            }
            return total_infant_and_children_2;
        };
        plugin.add_aobject = function ($self) {


            var html_item_aobject_template = plugin.settings.html_item_aobject_template;
            var $last_item_aobject = $element.find(".item-aobject:last");
            $(html_item_aobject_template).insertAfter($last_item_aobject);
            var $last_item_aobject = $element.find(".item-aobject:last");

            var item_aobject_template = {};
            var list_aobject = plugin.settings.list_aobject;
            item_aobject_template.passengers = [];
            item_aobject_template.aobject_type = 'single';
            item_aobject_template.aobject_note = '';
            item_aobject_template.tour_cost_and_aobject_price = [];
            item_aobject_template.full = false;
            list_aobject.push(item_aobject_template);
            $last_item_aobject.find('input[value="single"][data-name="aobject_type"]').prop("checked", true);
            var last_aobject_index = $last_item_aobject.index();
            if (plugin.enable_add_passenger_to_aobject_index(last_aobject_index)) {
                plugin.add_passenger_to_aobject_index(last_aobject_index);
            }
            plugin.format_name_for_aobject_index(last_aobject_index);
            plugin.lock_passenger_inside_aobject_index(last_aobject_index);
            plugin.set_label_passenger_in_aobjects();
            plugin.update_list_aobjecting();
            plugin.trigger_after_change();

        };
        plugin.jumper_aobject = function (aobject_index) {
            $.scrollTo($element.find('.item-aobject:eq(' + aobject_index + ')'), 800);
        };
        plugin.validate = function () {
            var $list_aobject = $element.find('.item-aobject');
            var list_aobject = plugin.settings.list_aobject;
            for (var i = 0; i < $list_aobject.length; i++) {
                var aobject_index = i;
                var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
                var aobject_item = list_aobject[aobject_index];
                var passengers = aobject_item.passengers;
                var aobject_type = aobject_item.aobject_type;
                var $list_passenger = $aobject_item.find('ul.list-passenger');
                if (aobject_item.aobject_type == '') {
                    var content_notify = 'please select aobject type';
                    plugin.notify(content_notify);
                    $list_aobject.tipso('destroy');
                    $list_aobject.addClass('error');
                    $list_aobject.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_aobject.tipso('show');
                    plugin.jumper_aobject(aobject_index);
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
                    plugin.jumper_aobject(aobject_index);
                    return false;
                    $list_passenger.tipso('destroy');
                } else if (!plugin.settings[aobject_type].validate_aobject(aobject_item, aobject_index)) {
                    plugin.jumper_aobject(aobject_index);
                    return false;
                }
                $list_passenger.removeClass('error');

            }
            plugin.calculator_tour_cost_and_aobject_price();
            return true;
        };
        plugin.find_passenger_not_inside_aobject = function () {
            var passenger_not_inside_aobject = [];
            var list_passenger = plugin.settings.list_passenger.slice();
            var list_passenger_checked = plugin.get_list_passenger_checked();
            for (var i = 0; i < list_passenger.length; i++) {
                if (list_passenger_checked.indexOf(i) > -1) {

                } else {
                    passenger_not_inside_aobject.push(list_passenger[i]);
                }
            }
            return passenger_not_inside_aobject;

        }
        plugin.get_data = function () {
            return plugin.settings.output_data;
        };
        plugin.validate_data_aobject_index = function (aobject_index) {
            var $item_aobject = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var list_aobject = plugin.settings.list_aobject;
            var aobject_item = list_aobject[aobject_index];

            var $type = $item_aobject.find('input[type="radio"][data-name="aobject_type"]:checked');
            if ($type.length == 0) {
                var content_notify = 'please select aobject type';
                plugin.notify(content_notify);
                $item_aobject.find('.list-aobject').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $item_aobject.find('.list-aobject').tipso('show');
                return false;
            }
            var type = $type.val();
            type = plugin.settings[type];
            var total_passenger_selected = $item_aobject.find('.list-passenger input.passenger-item[exists_inside_other_aobject!="true"]:checked').length;
            if (total_passenger_selected >= type.max_total) {
                aobject_item.full = true;
                $item_aobject.find('.list-passenger input.passenger-item[exists_inside_other_aobject!="true"]:not(:checked)').prop("disabled", true);

            } else {
                aobject_item.full = false;
                $item_aobject.find('.list-passenger input.passenger-item[exists_inside_other_aobject!="true"]').prop("disabled", false);
            }
            list_aobject[aobject_index] = aobject_item;
            plugin.settings.list_aobject = list_aobject;
            return true;
        };
        plugin.reset_passenger_selected = function ($self) {
            var $item_aobject = $self.closest('.item-aobject');
            //$item_aobject.find('.list-passenger input.passenger-item[exists_inside_other_aobject]').prop("disabled", false).prop("checked", false).trigger('change');
            plugin.lock_passenger_inside_aobject($self);


        };
        plugin.update_event = function () {


            $element.find('input.passenger-item').unbind('change');
            $element.find('input.passenger-item').change(function selected_passenger(event) {
                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
                var $self = $(this);
                var $aobject = $self.closest('.item-aobject');

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
                    //$self.removeAttr('exists_inside_other_aobject');
                    //var passenger_index=$self.data('index');
                    //$element.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]').prop("disabled", false);

                }
                plugin.lock_passenger_inside_aobject($self);
                plugin.update_data();
                plugin.update_list_aobjecting();

            });

            $element.find('input[type="radio"][data-name="aobject_type"]').unbind('change');
            $element.find('input[type="radio"][data-name="aobject_type"]').change(function selected_type(event) {
                /* var $self=$(this);
                 plugin.reset_passenger_selected($self);
                 plugin.update_data();
                 plugin.update_list_aobjecting();*/

            });
            $element.find('textarea[data-name="aobject_note"]').change(function change_note(event) {
                plugin.update_data();
                plugin.update_list_aobjecting();

            });


        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger = list_passenger;
            var total_passenger = list_passenger.length;
            var $list_aobject = $element.find('.item-aobject');
            $list_aobject.each(function (aobject_index, aobject) {
                if (plugin.enable_add_passenger_to_aobject_index(aobject_index)) {
                    plugin.add_passenger_to_aobject_index(aobject_index);
                    plugin.format_name_for_aobject_index(aobject_index);
                    plugin.lock_passenger_inside_aobject_index(aobject_index);
                    plugin.set_label_passenger_in_aobjects();
                }
                plugin.add_event_aobject_index(aobject_index);
                /*
                 var $aobject=$(aobject);
                 var $list_passenger=$aobject.find('.list-passenger');
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
                 var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" data-index="'+i+'" value="'+i+'" name="list_aobject['+aobject_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');
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
             plugin.update_list_aobjecting();


             */
            plugin.trigger_after_change();
        };
        plugin.exists_infant_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var exists_infant_in_aobject = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_aobject = true;
                    break;
                }
            }
            return exists_infant_in_aobject;
        };
        plugin.exists_infant_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var exists_infant_in_aobject = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_aobject = true;
                    break;
                }
            }
            return exists_infant_in_aobject;
        };
        plugin.exists_children_2_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var exists_children_2_in_aobject = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    exists_children_2_in_aobject = true;
                    break;
                }
            }
            return exists_children_2_in_aobject;
        };
        plugin.check_all_passenger_is_infant_and_children_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
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
        plugin.enable_add_aobject = function (aobject_index) {

            var list_passenger = plugin.settings.list_passenger;
            var list_passenger_checked = plugin.get_list_passenger_checked();
            if (list_passenger_checked.length >= list_passenger.length) {
                plugin.notify('you cannot add more aobject');
                return false;
            }


            return true;
        };
        plugin.enable_remove_aobject = function (aobject_index) {
            return true;
        };
        plugin.enable_remove_aobject_index = function (aobject_index) {
            return true;
        };
        plugin.enable_add_passenger_to_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            passenger_full_name='';
            if (typeof passenger=='object'){
                var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
            }

            return passenger_full_name;
        };
        plugin.add_passenger_to_aobject_index = function (aobject_index) {

            var list_passenger = plugin.settings.list_passenger.slice();
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            $aobject_item.find('ul.list-passenger').empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var passenger_full_name = plugin.get_passenger_full_name(passenger);
                var html_template_passenger = plugin.settings.html_template_passenger;
                var $template_passenger = $(html_template_passenger);
                $template_passenger.find('.full-name').html(passenger_full_name);
                $aobject_item.find('ul.list-passenger').append($template_passenger);

            }

        };
        plugin.enable_change_aobject_type_to_aobject_index = function (aobject_index) {

            return true;
        };
        plugin.add_event_last_aobject_index = function () {
            var $last_aobject_item = $element.find('.item-aobject:last');
            var last_aobject_index = $last_aobject_item.index();
            plugin.add_event_aobject_index(last_aobject_index);
        };
        plugin.lock_passenger_inside_aobjects = function () {
            var $list_aobject = $element.find('.item-aobject');
            $list_aobject.each(function (aobject_index) {
                plugin.lock_passenger_inside_aobject_index(aobject_index);
            });
        };
        plugin.set_label_passenger_in_aobject_index = function (aobject_index) {
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            $aobject_item.find('.list-passenger li .in_aobject').html('');
            $aobject_item.find('.list-passenger li').each(function (passenger_index) {
                var a_aobject_index = plugin.get_aobject_index_by_passenger_index_selected(passenger_index);
                if ($.isNumeric(a_aobject_index)) {
                    $(this).find('.in_aobject').html(' (aobject ' + (a_aobject_index + 1) + ')');
                }
            });
        };
        plugin.set_label_passenger_in_aobjects = function () {
            var $list_aobject = $element.find('.item-aobject');
            $list_aobject.each(function (aobject_index) {
                plugin.set_label_passenger_in_aobject_index(aobject_index);
            });
        };
        plugin.chang_aobject_type_to_aobject_index = function (aobject_index) {
            var $item_aobject = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            $.each(passengers, function (index, passenger_id) {
                $item_aobject.find('.passenger-item:eq(' + passenger_id + ')').prop('checked', false);
            });
            var aobject_type = $item_aobject.find('input[type="radio"][data-name="aobject_type"]:checked').val();
            list_aobject[aobject_index].passengers = [];
            list_aobject[aobject_index].aobject_type = aobject_type;
            list_aobject[aobject_index].full = false;
            plugin.settings.list_aobject = list_aobject;
            plugin.lock_passenger_inside_aobjects();
            plugin.set_label_passenger_in_aobjects();
            plugin.update_list_aobjecting();
            plugin.trigger_after_change();

        };
        plugin.exists_senior_adult_teen_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_1_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_or_adult_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_adult_or_children_by_passenger_index(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.enable_change_passenger_inside_aobject_index = function (aobject_index) {
            return true;
        };
        plugin.check_is_full_passenger_inside_aobject = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var aobject_item = list_aobject[aobject_index];
            var aobject_type = aobject_item.aobject_type;
            var setting_aobject_type = plugin.settings[aobject_type];
            var passengers = aobject_item.passengers;
            return passengers.length == setting_aobject_type.max_total;
        };
        plugin.change_passenger_inside_aobject_index = function (aobject_index) {

            var passengers = [];
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            $aobject_item.find('.passenger-item').each(function (passenger_index) {
                var $self = $(this);
                if ($self.is(':checked')) {
                    passengers.push(passenger_index);
                }
            });
            var list_aobject = plugin.settings.list_aobject;
            list_aobject[aobject_index].passengers = passengers;
            list_aobject[aobject_index].full = plugin.check_is_full_passenger_inside_aobject(aobject_index);
            plugin.settings.list_aobject = list_aobject;
            plugin.lock_passenger_inside_aobjects();
            plugin.set_label_passenger_in_aobjects();
            plugin.trigger_after_change();
        };
        plugin.remove_passenger_in_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            list_aobject[aobject_index].passengers = [];
            plugin.settings.list_aobject = list_aobject;
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
        plugin.check_aobject_before_add_passenger = function (aobject_index, current_passenger_index_selected) {
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var $passenger = $aobject_item.find('input.passenger-item:eq(' + current_passenger_index_selected + ')');
            var range_year_old_infant = plugin.settings.range_year_old_infant;
            var range_year_old_children_2 = plugin.settings.range_year_old_children_2;
            var range_year_old_infant_and_children_2 = plugin.settings.range_year_old_infant_and_children_2;
            var list_aobject = plugin.settings.list_aobject;
            var aobject_item = list_aobject[aobject_index];
            var aobject_type = aobject_item.aobject_type;
            if (plugin.is_infant(current_passenger_index_selected) && !plugin.settings[aobject_type].enable_select_infant1(aobject_item, range_year_old_infant, current_passenger_index_selected, aobject_index)) {
                var content_notify = 'you cannot add more infant 0-1 because there are no adult in aobject';
                plugin.notify(content_notify);
                $aobject_item.find('.list-passenger').removeClass('error');
                $aobject_item.find('.list-passenger').tipso('destroy');
                $aobject_item.find('.list-passenger').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $aobject_item.find('.list-passenger').tipso('show');
                return false;
            } else {
                $aobject_item.find('.list-passenger').removeClass('error');
                $aobject_item.find('.list-passenger').tipso('destroy');
            }
            if (plugin.is_children_2(current_passenger_index_selected) && !plugin.settings[aobject_type].enable_select_infant2(aobject_item, range_year_old_children_2, current_passenger_index_selected, aobject_index)) {
                return false;
            } else {
                $aobject_item.find('.list-passenger').removeClass('error');
                $aobject_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_children_1 = plugin.settings.range_year_old_children_1;
            if (plugin.is_children_1(current_passenger_index_selected) && !plugin.settings[aobject_type].enable_select_children(aobject_item, range_year_old_children_1, current_passenger_index_selected, aobject_index)) {
                return false;
            } else {
                $aobject_item.find('.list-passenger').removeClass('error');
                $aobject_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_senior_adult_teen = plugin.settings.range_year_old_senior_adult_teen;
            if (plugin.is_senior_adult_teen(current_passenger_index_selected) && !plugin.settings[aobject_type].enable_select_senior_adult_teen(aobject_item, range_year_old_senior_adult_teen, current_passenger_index_selected, aobject_index)) {
                return false;
            } else {
                $aobject_item.find('.list-passenger').removeClass('error');
                $aobject_item.find('.list-passenger').tipso('destroy');
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
        plugin.update_aobject_note_aobject_index = function (aobject_index) {
            var $item_aobject = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var list_aobject = plugin.settings.list_aobject;
            var passengers = list_aobject[aobject_index].passengers;
            var aobject_note=$item_aobject.find('textarea[data-name="aobject_note"]').val();
            list_aobject[aobject_index].aobject_note = aobject_note;
            plugin.settings.list_aobject = list_aobject;

        };
        plugin.add_event_aobject_index = function (aobject_index) {
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var $aobject_type_item = $aobject_item.find('input[data-name="aobject_type"]');
            var event_class = 'change_aobject_type';
            if (!$aobject_type_item.hasClass(event_class)) {
                $aobject_type_item.click(function () {
                    var $self = $(this);
                    var a_aobject_index = $self.closest('.item-aobject').index();
                    if (plugin.enable_change_aobject_type_to_aobject_index(a_aobject_index)) {
                        plugin.chang_aobject_type_to_aobject_index(a_aobject_index);
                    }
                }).addClass(event_class);
            }

            var $list_passenger = $aobject_item.find('ul.list-passenger');
            $list_passenger.find('input.passenger-item').each(function (passenger_index) {
                var $passenger = $(this);
                var event_class = 'change_passenger';
                if (!$passenger.hasClass(event_class)) {
                    $passenger.change(function (event) {

                        var $self = $(this);
                        var a_aobject_index = $self.closest('.item-aobject').index();
                        $aobject_item = $self.closest('.item-aobject');
                        var passenger_index = $self.closest('li').index();
                        var list_aobject = plugin.settings.list_aobject;
                        var passengers = list_aobject[a_aobject_index].passengers;
                        var passenger_index_selected = $self.closest('li').index();

                        if (plugin.enable_change_passenger_inside_aobject_index(a_aobject_index)) {
                           plugin.change_passenger_inside_aobject_index(a_aobject_index);

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

             var content_notify='aobject is full you can not add more passenger';
             plugin.notify(content_notify);
             $aobject_item.find('.list-passenger').removeClass('error');
             $aobject_item.find('.list-passenger').tipso('destroy');
             $aobject_item.find('.list-passenger').tipso({
             size: 'tiny',
             useTitle:false,
             content:content_notify,
             animationIn:'bounceInDown'
             }).addClass('error');




             }).addClass(event_class);

             }
             });
             */
            var event_class = 'add_aobject';
            if (!$aobject_item.find('.add-more-aobject').hasClass(event_class)) {
                $aobject_item.find('.add-more-aobject').click(function () {
                    var a_aobject_index = $(this).closest('.item-aobject').index();
                    var $a_aobject_item = $(this).closest('.item-aobject');
                    if (plugin.enable_add_aobject(a_aobject_index)) {
                        //alert('ok');
                        plugin.add_aobject(a_aobject_index);
                        plugin.add_event_aobject_index(a_aobject_index + 1);
                    }
                }).addClass(event_class);


            }
            var event_class = 'add_calendar';
            if (!$aobject_item.find('.date').hasClass(event_class)) {
                var type=plugin.settings.type;
                var date_format= plugin.settings.date_format;
                var departure_date=plugin.settings.departure.departure_date;
                var minDate=moment().format(date_format);
                var maxDate=moment().format(date_format);
                departure_date = moment(departure_date);
                if(type=='post'){
                    var total_day=plugin.settings.departure.total_day;
                    departure_date=departure_date.add(total_day, 'days');
                    minDate=departure_date.format(date_format);
                    maxDate=departure_date.add(30, 'days').format(date_format);
                }else{
                    maxDate=departure_date.format(date_format);
                }

                $aobject_item.find('.date').daterangepicker({
                        format: plugin.settings.date_format,
                        "showDropdowns": true,
                        minDate: minDate,
                        maxDate: maxDate,
                        ranges:plugin.settings.date_ranges
                    },
                    function (start, end, label) {
                        /*var input_from = $element.find('input[name="' + from_name + '"]');
                        var input_to = $element.find('input[name="' + to_name + '"]');
                        input_from.val(start.format(plugin.settings.format));
                        input_to.val(end.format(plugin.settings.format));
                        plugin.on_change(start, end);*/

                    }
                );
            }
            var event_class = 'remove_aobject';
            if (!$aobject_item.find('.remove-aobject').hasClass(event_class)) {
                $aobject_item.find('.remove-aobject').click(function () {
                    var a_aobject_index = $(this).closest('.item-aobject').index();
                    if (plugin.enable_remove_aobject_index(a_aobject_index)) {
                        plugin.remove_aobject_index(a_aobject_index);
                    }
                }).addClass(event_class);
            }
            var debug=plugin.settings.debug;
            if(debug)
            {
                var event_class = 'change_aobject_note';
                if (!$aobject_item.find('textarea[data-name="aobject_note"]').hasClass(event_class)) {

                    $aobject_item.find('textarea[data-name="aobject_note"]').change(function () {
                        plugin.update_aobject_note_aobject_index(aobject_index);
                    }).addClass(event_class);
                }
            }

            if(debug)
            {
                var event_class = 'add_aobject_note';
                if (!$aobject_item.find('.random-text').hasClass(event_class)) {

                    $aobject_item.find('.random-text').click(function () {
                        $aobject_item.find('textarea[data-name="aobject_note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
                    }).addClass(event_class);
                }
            }


        };
        plugin.format_name_for_aobjects = function () {
            var $list_aobject = $element.find('.item-aobject');
            $list_aobject.each(function (aobject_index) {
                plugin.format_name_for_aobject_index(aobject_index);
            });
        };
        plugin.remove_aobject_index = function (aobject_index) {

            var total_aobject = $element.find('.item-aobject').length;
            if (total_aobject == 1) {
                return;
            }
            var $item_aobject = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var list_aobject = plugin.settings.list_aobject;
            list_aobject.splice(aobject_index, 1);
            plugin.settings.list_aobject = list_aobject;
            $item_aobject.remove();
            plugin.lock_passenger_inside_aobjects();
            plugin.format_name_for_aobjects();
            plugin.set_label_passenger_in_aobjects();
            plugin.update_list_aobjecting();
            plugin.trigger_after_change();

        };
        plugin.add_passenger_to_last_aobject_index = function () {

            var $last_aobject_item = $element.find('.item-aobject:last');
            var last_aobject_index = $last_aobject_item.index();
            if (plugin.enable_add_passenger_to_aobject_index(last_aobject_index)) {
                plugin.add_passenger_to_aobject_index(last_aobject_index);
            }

        };
        plugin.setup_template_element = function () {
            var list_aobject = plugin.settings.list_aobject.slice();
            plugin.settings.item_aobject_template = list_aobject[0];
            var html_item_aobject_template = $element.find('.item-aobject').getOuterHTML();
            plugin.settings.html_item_aobject_template = html_item_aobject_template;


            var html_tr_item_aobject = $element.find('.aobjecting-list .div-item-aobject').getOuterHTML();
            plugin.settings.html_tr_item_aobject = html_tr_item_aobject;

            var html_template_passenger = $element.find('ul.list-passenger li:first').getOuterHTML();
            plugin.settings.html_template_passenger = html_template_passenger;
        };
        plugin.get_tour_detail = function () {
            var tour=plugin.settings.tour;
            console.log(tour);
        };
        plugin.format_name_for_aobject_index = function (aobject_index) {
            var list_aobject = plugin.settings.list_aobject;
            var list_passenger=plugin.settings.list_passenger;
            var aobject_item = list_aobject[aobject_index];
            var $aobject_item = $element.find('.item-aobject:eq(' + aobject_index + ')');
            var passenger=list_passenger[aobject_index];
            var tour_detail=plugin.get_tour_detail();
            $aobject_item.find('textarea[data-name="aobject_note"]').attr('name', 'list_aobject[' + aobject_index + '][aobject_note]');
            $aobject_item.find('.aobject-order').html(aobject_index + 1+':'+plugin.get_passenger_full_name(passenger));
            $aobject_item.find('.tour-detail').html(tour_detail);
            $aobject_item.find('input[data-name="aobject_type"]').each(function (index1, input) {
                var $input = $(input);
                var data_name = $input.data('name');
                $input.attr('name', 'list_aobject[' + aobject_index + '][' + data_name + ']');
            });
            $aobject_item.find('input[data-name="aobject_type"][value="' + aobject_item.aobject_type + '"]').prop('checked', true);

            $aobject_item.find('ul.list-passenger input.passenger-item').each(function (passenger_index) {
                $(this).attr('name', 'list_aobject[' + aobject_index + '][passengers][]');
                $(this).val(passenger_index);
            });
            var passengers = aobject_item.passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                $aobject_item.find('ul.list-passenger input.passenger-item:eq(' + passenger_index + ')').prop('checked', true);
            }
            //var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" value="'+i+'" data-index="'+i+'" name="list_aobject['+aobject_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');

        };
        plugin.format_name_for_last_aobject = function () {
            var $last_aobject_item = $element.find('.item-aobject:last');
            var last_aobject_index = $last_aobject_item.index();
            plugin.format_name_for_aobject_index(last_aobject_index);
        };
        plugin.exchange_index_for_list_aobject = function (old_index, new_index) {
            var list_aobject = plugin.settings.list_aobject;
            var temp_aobject = list_aobject[old_index];
            list_aobject[old_index] = list_aobject[new_index];
            list_aobject[new_index] = temp_aobject;
            plugin.settings.list_aobject = list_aobject;

        };
        plugin.setup_sortable = function () {
            $element.find('.table-aobjecting-list .tbody').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                items: ".div-item-aobject",
                stop: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_aobjecting();*/
                }

            });
            var element_key = plugin.settings.element_key;
            $element.find('.' + element_key + '_list_aobject').sortable({
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
                        plugin.exchange_index_for_list_aobject(old_index, new_index);
                        console.log(plugin.settings.list_aobject);
                        if (old_index < new_index) {
                            plugin.format_name_for_aobject_index(old_index);
                            plugin.format_name_for_aobject_index(new_index);
                        } else {
                            plugin.format_name_for_aobject_index(new_index);
                            plugin.format_name_for_aobject_index(old_index);
                        }
                        plugin.set_label_passenger_in_aobjects();
                        console.log("Start position: " + ui.item.startPos);
                        console.log("New position: " + ui.item.index());
                        plugin.update_list_aobjecting();
                        plugin.trigger_after_change();

                    }
                },
                update: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_aobjecting();*/
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

            plugin.range_year_old_infant_and_children_2= [plugin.settings.range_year_old_infant[0], plugin.settings.range_year_old_children_2[1]];
            plugin.range_year_old_senior_adult_teen= [plugin.settings.range_year_old_teen[0], plugin.settings.range_year_old_senior[1]];
            plugin.range_adult_and_children= [plugin.settings.range_year_old_children_2[0], plugin.settings.range_year_old_senior[1]];
            plugin.setup_template_element();
            //plugin.render_input_person();
            var list_passenger=plugin.settings.list_passenger;
            console.log(list_passenger);


            /*
             plugin.update_event();
             plugin.update_data();
             var debug=plugin.settings.debug;
             if(debug)
             {
             $element.find('.item-aobject .random-text').click(function(){
             var $item_aobject=$(this).closest('.item-aobject');
             $item_aobject.find('textarea[data-name="aobject_note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
             });
             }
             */


        };
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_build_passenger_summary_confirm = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_build_passenger_summary_confirm')) {
                var plugin = new $.html_build_passenger_summary_confirm(this, options);
                $(this).data('html_build_passenger_summary_confirm', plugin);

            }

        });

    }

})(jQuery);


