(function ($) {

    // here we go!
    $.html_build_pickup_transfer = function (element, options) {

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
            transfer_item_config:{

            },
            list_excursion_addon:{

            },
            passenger_config: {},
            output_data: [],
            list_transfer: [
                {
                    passengers: [],
                    transfer_type: 'basic',
                    transfer_note: ''

                }
            ],
            infant_title:"Infant",
            teen_title:"Teen",
            children_title:"Children",
            adult_title:"Adult",
            senior_title:"Adult",
            item_transfer_template: {},
            event_after_change: false,
            update_passenger: false,
            limit_total: false,
            trigger_after_change: null,
            private_bed:"private bed",
            share_bed:"Share bed with others",
            extra_bed:"Extra  bed",
            basic: {
                max_adult: 20,
                max_children: 20,
                max_infant: 20,
                max_total: 20,
                validate_transfer: function (transfer_item, transfer_index) {
                    var transfer_item_config=plugin.settings.transfer_item_config;
                    var data_price=transfer_item_config.data_price;
                    var item_flat=data_price.item_flat;
                    var list_transfer_items_price=data_price.items;

                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    if(item_flat.net_price!=0)
                    {
                        var max_total = plugin.settings[transfer_type].max_total;
                    }else{
                        var max_total=list_transfer_items_price.length;
                    }
                    if (passengers.length > max_total) {
                        var content_notify = 'our policy does not allow '+passengers.length+' max passenger alow is '+max_total+' persons to stay in a this transfer. please try select again';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if ((plugin.exists_infant_in_transfer_index(transfer_index) || plugin.exists_children_2_in_transfer_index(transfer_index)) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'our policy does not allow a child under 5 years to stay alone in basic transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (transfer_item, range_year_old_infant, passenger_index_selected, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var content_notify = 'you cannot add infant(2-5) to sigle transfer, you can add adult or children (>6) inside transfer';
                    plugin.notify(content_notify);
                    $transfer_item.find('.list-passenger').removeClass('error');
                    $transfer_item.find('.list-passenger').tipso('destroy');
                    $transfer_item.find('.list-passenger').tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    }).addClass('error');
                    $transfer_item.find('.list-passenger').tipso('show');
                    $transfer_item.find('.list-passenger').addClass('error');
                    return false;
                },
                enable_select_infant2: function (transfer_item, range_year_old_children_2, passenger_index_selected, transfer_index) {

                    return false;
                },
                enable_select_children: function (transfer_item, range_year_old_children_1, passenger_index_selected, transfer_index) {

                    return true;
                },
                enable_select_senior_adult_teen: function (transfer_item, range_year_old_senior_adult_teen, passenger_index_selected, transfer_index) {

                    return true;
                }
            },
            double: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 2,
                max_total: 3,
                validate_transfer: function (transfer_item, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    var min_total = plugin.settings[transfer_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in transfer is ' + min_total;
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in transfer is ' + max_total;
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_children_2_in_transfer_index(transfer_index) && (!plugin.exists_senior_adult_teen_in_transfer_index(transfer_index) && !plugin.exists_children_1_in_transfer_index(transfer_index) )) {
                        var content_notify = 'transfer exists infant (2-5) you need add adult or children in this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_transfer_index(transfer_index) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'our policy does not allow infant to stay in   transfer other children. You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_transfer_is_adult_or_children(transfer_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin transfer . You are suggested to select a triple transfer instead.';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (transfer_item, range_year_old_infant, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (transfer_item, range_year_old_children_2, passenger_index_selected, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_transfer_index(transfer_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in transfer you need add one adult(>=12) or children (6-11) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_transfer_index(transfer_index) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in transfer  you need add one adult(>=12) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (transfer_item, range_year_old_children_1, passenger_index_selected, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
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
                    var exists_infant_in_transfer = function (passengers) {
                        var exists_infant_in_transfer = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_transfer = true;
                                break;
                            }
                        }
                        return exists_infant_in_transfer;
                    };
                    var total_adult_and_children = 0;
                    for (var i = 0; i < passengers.length; i++) {
                        var passenger_index = passengers[i];
                        if (plugin.is_senior_adult_teen(passenger_index) || plugin.is_children_1(passenger_index)) {
                            total_adult_and_children++;
                        }
                    }

                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_transfer_index(transfer_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'exists baby infant(0-1) please select one adult(>=12)';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        return false;
                    } else if (passengers.length == max_total - 1 && total_adult_and_children == max_total - 1) {
                        var content_notify = 'double transfer max total adult and children is two person, if you want this  you should select twin transfer, or triple transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        return false;

                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (transfer_item, range_year_old_senior_adult_teen, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var max_total = plugin.settings[transfer_type].max_total;
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
                        var content_notify = 'double transfer max total adult and children is two person, if you want this  you should select twin transfer, or triple transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');


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
                validate_transfer: function (transfer_item, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    var min_total = plugin.settings[transfer_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in transfer is ' + min_total;
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in transfer is ' + max_total;
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_transfer_index(transfer_index) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   transfer . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length == max_total && plugin.all_passenger_in_transfer_is_adult_or_children(transfer_index)) {
                        var content_notify = 'our policy does not allow 3 persons  from 6 years old up to share a double /twin transfer . You are suggested to select a triple transfer instead';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_infant1: function (transfer_item, range_year_old_infant, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (transfer_item, range_year_old_children_2, passenger_index_selected, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_transfer_index(transfer_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in transfer you need add one adult(>=12) or children (6-11) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_transfer_index(transfer_index) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in transfer  you need add one adult(>=12) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (transfer_item, range_year_old_children_1, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var max_total = plugin.settings[transfer_type].max_total;
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
                    var exists_infant_in_transfer = function (passengers) {
                        var exists_infant_in_transfer = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_transfer = true;
                                break;
                            }
                        }
                        return exists_infant_in_transfer;
                    };

                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_transfer_index(transfer_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children transfer  exists baby infant (0-1) in transfer  you need add one adult(>=12) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;

                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (transfer_item, range_year_old_senior_adult_teen, passenger_index_selected, transfer_index) {
                    return true;
                }
            },
            triple: {
                max_adult: 2,
                max_children: 2,
                max_infant: 1,
                min_total: 3,
                max_total: 4,
                validate_transfer: function (transfer_item, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    var min_total = plugin.settings[transfer_type].min_total;
                    if (passengers.length < min_total) {
                        var content_notify = 'min person in transfer is ' + min_total;
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (passengers.length > max_total) {
                        var content_notify = 'max person in transfer is ' + max_total + ' please eject some person';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.exists_infant_in_transfer_index(transfer_index) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'our policy does not allow infant  to stay with other children  in   transfer . You are suggested to assign a Teen/Adult/Senior to look after the infant.';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    } else if (plugin.get_total_senior_adult_teen(transfer_index) == 4) {
                        var content_notify = 'Our policy does not allow 4 teeners/adults/seniors to share the same transfer.You are suggested to select 2 transfers .You are suggested to select 2 transfers';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;

                    } else if (plugin.get_total_senior_adult_teen(transfer_index) == 3 && plugin.exists_children_1_in_transfer_index(transfer_index)) {
                        var content_notify = 'Our policy does not allow a child over 6 years old to share the same transfer with 3 teeners/adults/seniors .You are suggested to select 2 transfers';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;

                    }
                    return true;
                },
                enable_select_infant1: function (transfer_item, range_year_old_infant, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        return false;
                    }
                    return true;
                },
                enable_select_infant2: function (transfer_item, range_year_old_children_2, passenger_index_selected, transfer_index) {
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var max_total = plugin.settings[transfer_type].max_total;
                    if (passengers.length == max_total - 1 && !plugin.exists_children_or_adult_in_transfer_index(transfer_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (2-5) in transfer you need add one adult(>=12) or children (6-11) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_transfer_index(transfer_index) && !plugin.exists_senior_adult_teen_in_transfer_index(transfer_index)) {
                        var content_notify = 'you cannot add more infant (2-5) because  exists baby infant (0-1) in transfer  you need add one adult(>=12) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;
                    }
                    return true;
                },
                enable_select_children: function (transfer_item, range_year_old_children_1, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var max_total = plugin.settings[transfer_type].max_total;
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
                    var exists_infant_in_transfer = function (passengers) {
                        var exists_infant_in_transfer = false;
                        for (var i = 0; i < passengers.length; i++) {
                            var passenger_index = passengers[i];
                            if (plugin.is_infant(passenger_index)) {
                                exists_infant_in_transfer = true;
                                break;
                            }
                        }
                        return exists_infant_in_transfer;
                    };

                    if (passengers.length == max_total - 1 && plugin.exists_infant_in_transfer_index(transfer_index) && all_passenger_is_infant_and_children(passengers)) {
                        var content_notify = 'you cannot add more children transfer  exists baby infant (0-1) in transfer  you need add one adult(>=12) inside this transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');
                        $transfer_item.find('.list-passenger').addClass('error');
                        return false;

                        return false;
                    }
                    return true;
                },
                enable_select_senior_adult_teen: function (transfer_item, range_year_old_senior_adult_teen, passenger_index_selected, transfer_index) {
                    var transfer_type = transfer_item.transfer_type;
                    var passengers = transfer_item.passengers;
                    var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                    var max_total = plugin.settings[transfer_type].max_total;
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
                        var content_notify = 'triple transfer max total adult is three person, you should add more transfer';
                        plugin.notify(content_notify);
                        $transfer_item.find('.list-passenger').removeClass('error');
                        $transfer_item.find('.list-passenger').tipso('destroy');
                        $transfer_item.find('.list-passenger').tipso({
                            size: 'tiny',
                            useTitle: false,
                            content: content_notify,
                            animationIn: 'bounceInDown'
                        }).addClass('error');
                        $transfer_item.find('.list-passenger').tipso('show');


                        return false;
                    }
                    return true;
                }
            },
            element_key: 'html_build_pickup_transfer',
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
        plugin.get_list_transfer = function () {
            plugin.update_data();
            return plugin.settings.list_transfer;
        }
        plugin.get_list_passenger = function () {
            plugin.update_data();
            return plugin.settings.list_passenger;
        }
        plugin.get_total_senior_adult_teen = function (transfer_index) {
            var total_adult = 0;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var exists_infant2_in_transfer = false;
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
        plugin.all_passenger_in_transfer_is_adult = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var all_passenger_in_transfer_is_adult = true;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index) || plugin.is_children_1(passenger_index) || plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    all_passenger_in_transfer_is_adult = false;
                    break;
                }
            }
            return all_passenger_in_transfer_is_adult;
        };
        plugin.trigger_after_change = function () {
            var list_transfer = plugin.settings.list_transfer;
            var trigger_after_change = plugin.settings.trigger_after_change;
            if (trigger_after_change instanceof Function) {
                trigger_after_change(list_transfer);
            }
        },
            plugin.all_passenger_in_transfer_is_adult_or_children = function (transfer_index) {
                var list_transfer = plugin.settings.list_transfer;
                var all_passenger_in_transfer_is_adult_or_children = true;
                var passengers = list_transfer[transfer_index].passengers;
                for (var i = 0; i < passengers.length; i++) {
                    var passenger_index = passengers[i];
                    if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                        all_passenger_in_transfer_is_adult_or_children = false;
                        break;
                    }
                }
                return all_passenger_in_transfer_is_adult_or_children;
            };
        plugin.get_senior_adult_teen_passenger_order_in_transfer = function (transfer_index, order) {
            var adult_passenger_index_of_order = -1;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
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
        plugin.get_infant_or_children_2_passenger_order_in_transfer = function (transfer_index, order) {
            var infant_or_children_2_passenger_index_of_order = -1;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
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
        plugin.get_children_2_passenger_order_in_transfer = function (transfer_index, order) {
            var children_2_passenger_index_of_order = -1;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
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
        plugin.get_children_1_passenger_order_in_transfer = function (transfer_index, order) {
            var children_1_passenger_index_of_order = -1;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
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
        plugin.get_all_children_2_in_transfer = function (transfer_index) {
            var list_all_children_2_in_transfer=[];
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    list_all_children_2_in_transfer.push(passenger_index);
                }
            }
            return list_all_children_2_in_transfer;
        };
        plugin.get_all_children_1_in_transfer = function (transfer_index) {
            var list_all_children_1_in_transfer=[];
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    list_all_children_1_in_transfer.push(passenger_index);
                }
            }
            return list_all_children_1_in_transfer;
        };
        plugin.get_all_infant_children_2_in_transfer = function (transfer_index) {
            var list_all_infant_children_2_in_transfer=[];
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var list_passenger = plugin.settings.list_passenger;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    list_all_infant_children_2_in_transfer.push(passenger_index);
                }
            }
            return list_all_infant_children_2_in_transfer;
        };
        plugin.calculator_tour_cost_and_transfer_price = function () {
            var list_transfer = plugin.get_list_transfer();
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
                sale_price_private_transfer = plugin.settings.departure.sale_price_private_transfer,
                sale_price_extra_bed = plugin.settings.departure.sale_price_extra_bed,
                sale_promotion_price_senior = plugin.settings.departure.sale_promotion_price_senior,
                sale_promotion_price_adult = plugin.settings.departure.sale_promotion_price_adult,
                sale_promotion_price_teen = plugin.settings.departure.sale_promotion_price_teen,
                sale_promotion_price_children1 = plugin.settings.departure.sale_promotion_price_children1,
                sale_promotion_price_children2 = plugin.settings.departure.sale_promotion_price_children2,
                sale_promotion_price_infant = plugin.settings.departure.sale_promotion_price_infant,
                sale_promotion_price_private_transfer = plugin.settings.departure.sale_promotion_price_private_transfer,
                sale_promotion_price_extra_bed = plugin.settings.departure.sale_promotion_price_extra_bed;

            var departure = plugin.settings.departure;
            var full_charge_children1 = departure.full_charge_children1;
            full_charge_children1=full_charge_children1==1?true:false;
            var full_charge_children2 = departure.full_charge_children2;
            full_charge_children2=full_charge_children2==1?true:false;
            var tsmart_promotion_price_id = departure.tsmart_promotion_price_id;
            var tsmart_promotion_price_id = tsmart_promotion_price_id != null && tsmart_promotion_price_id != 0;
            var price_senior = tsmart_promotion_price_id ? sale_promotion_price_senior : sale_price_senior;
            var price_adult = tsmart_promotion_price_id ? sale_promotion_price_adult : sale_price_adult;
            var price_teen = tsmart_promotion_price_id ? sale_promotion_price_teen : sale_price_teen;
            var price_children1 = tsmart_promotion_price_id ? sale_promotion_price_children1 : sale_price_children1;
            var price_children2 = tsmart_promotion_price_id ? sale_promotion_price_children2 : sale_price_children2;
            var price_infant = tsmart_promotion_price_id ? sale_promotion_price_infant : sale_price_infant;
            var price_private_transfer = tsmart_promotion_price_id ? sale_promotion_price_private_transfer : sale_price_private_transfer;
            var price_extra_bed = tsmart_promotion_price_id ? sale_promotion_price_extra_bed : sale_price_extra_bed;
            for (var i = 0; i < list_transfer.length; i++) {
                var transfer_item = list_transfer[i];
                var transfer_index = i;
                var passengers = transfer_item.passengers;
                if (passengers.length == 0) {
                    continue;
                }
                var transfer_type = transfer_item.transfer_type;
                transfer_item.tour_cost_and_transfer_price = [];
                var func_set_tour_cost_and_transfer_price = function (transfer_item, passenger_index, transfer_cost,msg,transfer_note) {
                    var item_passenger = {};
                    item_passenger.passenger_index = passenger_index;
                    item_passenger.transfer_cost = transfer_cost;
                    if (typeof msg == "undefined") {
                        msg = "";
                    }
                    item_passenger.msg = msg;


                    if (typeof transfer_note == "undefined") {
                        transfer_note = "";
                    }
                    item_passenger.transfer_note = transfer_note;



                    transfer_item.tour_cost_and_transfer_price.push(item_passenger);
                    return transfer_item;
                };
                var transfer_item_config=plugin.settings.transfer_item_config;
                var data_price=transfer_item_config.data_price;
                var item_flat=data_price.item_flat;
                var list_transfer_items_price=data_price.items;

                console.log(transfer_item_config);
                if (transfer_type == "basic") {
                    for (var p_index = 0; p_index < passengers.length; p_index++) {
                        if(item_flat.net_price!=0)
                        {
                            var transfer_cost=item_flat.net_price+item_flat.mark_up_amount+(item_flat.net_price*item_flat.mark_up_percent)/100;
                        }else{
                            var current_price=list_transfer_items_price[passengers.length-1];
                            var transfer_cost=current_price.net_price+current_price.mark_up_amount+(current_price.net_price*current_price.mark_up_percent)/100;

                        }
                        var a_passenger_index = passengers[p_index];
                        var transfer_note="";
                        transfer_item = func_set_tour_cost_and_transfer_price(transfer_item, a_passenger_index, transfer_cost,"",transfer_note);
                    }


                }
                list_transfer[i] = transfer_item;
            }
            plugin.settings.list_transfer = list_transfer;

        };
        plugin.update_data = function () {
            return;
            var input_name = plugin.settings.input_name;
            var $input_name = $element.find('input[name="' + input_name + '"]');
            var data = $element.find(':input[name]').serializeObject();
            if (typeof data.list_transfer == "undefined") {
                return false;
            }
            console.log(data.list_transfer);
            for (var i = 0; i < data.list_transfer.length; i++) {
                if (typeof data.list_transfer[i].passengers == "undefined") {
                    data.list_transfer[i].passengers = [];
                }
            }
            var list_transfer=plugin.get_list_transfer();

            plugin.settings.list_transfer = data.list_transfer;
            data = JSON.stringify(data);
            $input_name.val(data);
            var event_after_change = plugin.settings.event_after_change;
            if (event_after_change instanceof Function) {
                event_after_change(plugin.settings.list_transfer);
            }
        };
        plugin.get_list_passenger_selected = function () {
            var list_transfer = plugin.settings.list_transfer;
            var list_passenger_selected = [];
            $.each(list_transfer, function (index, transfer_item) {
                var passengers = transfer_item.passengers;
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
        plugin.get_transfer_index_by_passenger_index_selected = function (passenger_index) {
            var list_transfer = plugin.settings.list_transfer;
            var return_transfer_index = false;
            for (var i = 0; i < list_transfer.length; i++) {
                var transfer_item = list_transfer[i];
                var passengers = transfer_item.passengers;
                if ($.inArray(passenger_index, passengers) != -1) {
                    return_transfer_index = i;
                    break;
                } else {

                }

            }
            return return_transfer_index;
        };
        plugin.lock_passenger_inside_transfer_index = function (transfer_index) {
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var list_passenger_selected = plugin.get_list_passenger_selected();

            var list_transfer = plugin.get_list_transfer();
            var limit_total = plugin.settings.limit_total;
            var transfer_item = list_transfer[transfer_index];
            $transfer_item.find('input.passenger-item').prop("disabled", false);
            if(list_passenger_selected.length>0)
            {
                for (var i = 0; i < list_passenger_selected.length; i++) {
                    var passenger_index = list_passenger_selected[i];
                    var a_transfer_index = plugin.get_transfer_index_by_passenger_index_selected(passenger_index);
                    if (a_transfer_index != transfer_index) {
                        $transfer_item.find('input.passenger-item:eq(' + passenger_index + ')').prop("disabled", true);
                    }
                }
            }
        };
        plugin.get_list_passenger_checked = function () {
            var list_passenger_checked = [];
            var list_transfer = plugin.settings.list_transfer;
            var list_passenger = plugin.settings.list_passenger;
            $.each(list_transfer, function (index, transfer) {

                if (typeof transfer.passengers != "undefined") {
                    var passengers = transfer.passengers;
                    $.each(passengers, function (index_passenger, order_passenger) {
                        list_passenger_checked.push(order_passenger);
                    });


                }
            });
            return list_passenger_checked;
        };
        plugin.add_list_passenger_to_transfer = function ($item_transfer) {
            var transfer_index = $item_transfer.index();
            var list_passenger = plugin.settings.list_passenger;
            var total_passenger = list_passenger.length;
            var $list_passenger = $item_transfer.find('.list-passenger');
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
                var $li = $('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="' + key_full_name + '" value="' + i + '" data-index="' + i + '" name="list_transfer[' + transfer_index + '][passengers][]" type="checkbox"> ' + full_name + '</label></li>');
                $li.appendTo($list_passenger);
            }
        };
        plugin.get_item_tour_cost_and_transfer_price_by_passenger_index = function (tour_cost_and_transfer_price,passenger_index) {

            if(tour_cost_and_transfer_price.length>0){
                for (var i=0;i<tour_cost_and_transfer_price.length;i++){
                    var item_tour_cost_and_transfer_price=tour_cost_and_transfer_price[i];
                    if(item_tour_cost_and_transfer_price.passenger_index==passenger_index){
                        return item_tour_cost_and_transfer_price;
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
        plugin.update_list_transfering = function () {
            var list_transfer = plugin.get_list_transfer();
            var list_passenger = plugin.settings.list_passenger;
            var $table_transfering_list = $element.find('.table-transfering-list');
            var $tbody = $table_transfering_list.find('.tbody');
            $tbody.empty();
            var html_tr_item_transfer = plugin.settings.html_tr_item_transfer;
            $.each(list_transfer, function (index, transfer) {

                $(html_tr_item_transfer).appendTo($tbody);
                var $tr_item_transfer = $tbody.find('div.div-item-transfer:last');
                $tr_item_transfer.find('span.order').html(index + 1);
                $tr_item_transfer.find('div.transfer_type').html(transfer.transfer_type);
                $tr_item_transfer.find('div.transfer_note').html(transfer.transfer_note);
                var tour_cost_and_transfer_price = transfer.tour_cost_and_transfer_price;

                if (typeof transfer.passengers != "undefined" && transfer.passengers.length>0) {
                    var passengers = transfer.passengers;
                    passengers=plugin.sorting_passengers_by_year_old(passengers);
                    var sub_list_passenger = [];
                    var sub_list_passenger_private_transfer = [];
                    for(var i=0;i<passengers.length;i++){
                        var item_passenger = passengers[i];
                        var order_passenger=item_passenger.index_of;
                        var transfer_price_per_passenger=0;
                        var bed_note="";
                        if(typeof tour_cost_and_transfer_price!="undefined")
                        {
                            var item_tour_cost_and_transfer_price=plugin.get_item_tour_cost_and_transfer_price_by_passenger_index(tour_cost_and_transfer_price,order_passenger);

                            if(item_tour_cost_and_transfer_price!=null)
                            {
                                var passenger_note=item_tour_cost_and_transfer_price.msg;
                                var bed_note=item_tour_cost_and_transfer_price.bed_note;

                                bed_note='<div class="bed_note">'+bed_note+'&nbsp;</div>';
                            }
                        }
                        sub_list_passenger_private_transfer.push(bed_note);
                        var full_name = item_passenger.first_name + ' ' + item_passenger.middle_name + ' ' + item_passenger.last_name + ' (' + item_passenger.year_old + ')';
                        sub_list_passenger.push('<div class="passenger-item">'+full_name+'</div>');
                    }
                    $.each(passengers, function (index_passenger, order_passenger) {


                    });
                    sub_list_passenger = sub_list_passenger.join('');
                    $tr_item_transfer.find('div.table_list_passenger').html(sub_list_passenger);

                    sub_list_passenger_private_transfer = sub_list_passenger_private_transfer.join('');
                    $tr_item_transfer.find('div.private-transfer').html(sub_list_passenger_private_transfer);
                    $.set_height_element($tr_item_transfer.find('.row-item-column'));


                }
            });
            $element.find('.div-item-transfer .delete-transfer').click(function delete_transfer(event) {
                var $self = $(this);
                var $tr_transfer_item = $self.closest('.div-item-transfer');
                var index_of_transfer = $tr_transfer_item.index();
                $element.find('.item-transfer:eq(' + index_of_transfer + ') .remove-transfer').trigger('click');

            });

            $element.find('.table-transfering-list .tbody').sortable("refresh");

        };

        plugin.get_total_children_2 = function (transfer_index) {
            var total_children_2 = 0;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    total_children_2++;
                }
            }
            return total_children_2;
        };
        plugin.get_total_children_1 = function (transfer_index) {
            var total_children_1 = 0;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    total_children_1++;
                }
            }
            return total_children_1;
        };
        plugin.get_total_children_1_and_children_2 = function (transfer_index) {
            var total_children_1_and_children_2 = 0;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index) || plugin.is_children_1(passenger_index)) {
                    total_children_1_and_children_2++;
                }
            }
            return total_children_1_and_children_2;
        };
        plugin.get_total_infant_and_children_2 = function (transfer_index) {
            var total_infant_and_children_2 = 0;
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index) || plugin.is_children_2(passenger_index)) {
                    total_infant_and_children_2++;
                }
            }
            return total_infant_and_children_2;
        };
        plugin.add_transfer = function ($self) {


            var html_item_transfer_template = plugin.settings.html_item_transfer_template;
            var $last_item_transfer = $element.find(".item-transfer:last");
            $(html_item_transfer_template).insertAfter($last_item_transfer);
            var $last_item_transfer = $element.find(".item-transfer:last");

            var item_transfer_template = {};
            var list_transfer = plugin.settings.list_transfer;
            item_transfer_template.passengers = [];
            item_transfer_template.transfer_type = 'basic';
            item_transfer_template.transfer_note = '';
            item_transfer_template.tour_cost_and_transfer_price = [];
            item_transfer_template.full = false;
            list_transfer.push(item_transfer_template);
            $last_item_transfer.find('input[value="basic"][data-name="transfer_type"]').prop("checked", true);
            var last_transfer_index = $last_item_transfer.index();
            if (plugin.enable_add_passenger_to_transfer_index(last_transfer_index)) {
                plugin.add_passenger_to_transfer_index(last_transfer_index);
            }
            plugin.format_name_for_transfer_index(last_transfer_index);
            plugin.lock_passenger_inside_transfer_index(last_transfer_index);
            plugin.set_label_passenger_in_transfers();
            plugin.update_list_transfering();
            plugin.trigger_after_change();

        };
        plugin.jumper_transfer = function (transfer_index) {
            $.scrollTo($element.find('.item-transfer:eq(' + transfer_index + ')'), 800);
        };
        plugin.validate = function () {
            var $list_transfer = $element.find('.item-transfer');
            var list_transfer = plugin.settings.list_transfer;
            for (var i = 0; i < $list_transfer.length; i++) {
                var transfer_index = i;
                var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
                var transfer_item = list_transfer[transfer_index];
                var passengers = transfer_item.passengers;
                var transfer_type = transfer_item.transfer_type;
                var $list_passenger = $transfer_item.find('ul.list-passenger');
                if (transfer_item.transfer_type == '') {
                    var content_notify = 'please select transfer type';
                    plugin.notify(content_notify);
                    $list_transfer.tipso('destroy');
                    $list_transfer.addClass('error');
                    $list_transfer.tipso({
                        size: 'tiny',
                        useTitle: false,
                        content: content_notify,
                        animationIn: 'bounceInDown'
                    });
                    $list_transfer.tipso('show');
                    plugin.jumper_transfer(transfer_index);
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
                    plugin.jumper_transfer(transfer_index);
                    return false;
                    $list_passenger.tipso('destroy');
                } else if (!plugin.settings[transfer_type].validate_transfer(transfer_item, transfer_index)) {
                    plugin.jumper_transfer(transfer_index);
                    return false;
                }
                $list_passenger.removeClass('error');

            }
            plugin.calculator_tour_cost_and_transfer_price();
            return true;
        };
        plugin.find_passenger_not_inside_transfer = function () {
            var passenger_not_inside_transfer = [];
            var list_passenger = plugin.settings.list_passenger.slice();
            var list_passenger_checked = plugin.get_list_passenger_checked();
            for (var i = 0; i < list_passenger.length; i++) {
                if (list_passenger_checked.indexOf(i) > -1) {

                } else {
                    passenger_not_inside_transfer.push(list_passenger[i]);
                }
            }
            return passenger_not_inside_transfer;

        }
        plugin.get_data = function () {
            return plugin.settings.output_data;
        };
        plugin.validate_data_transfer_index = function (transfer_index) {
            var $item_transfer = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var list_transfer = plugin.settings.list_transfer;
            var transfer_item = list_transfer[transfer_index];

            var $type = $item_transfer.find('input[type="radio"][data-name="transfer_type"]:checked');
            if ($type.length == 0) {
                var content_notify = 'please select transfer type';
                plugin.notify(content_notify);
                $item_transfer.find('.list-transfer').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $item_transfer.find('.list-transfer').tipso('show');
                return false;
            }
            var type = $type.val();
            type = plugin.settings[type];
            var total_passenger_selected = $item_transfer.find('.list-passenger input.passenger-item[exists_inside_other_transfer!="true"]:checked').length;
            if (total_passenger_selected >= type.max_total) {
                transfer_item.full = true;
                $item_transfer.find('.list-passenger input.passenger-item[exists_inside_other_transfer!="true"]:not(:checked)').prop("disabled", true);

            } else {
                transfer_item.full = false;
                $item_transfer.find('.list-passenger input.passenger-item[exists_inside_other_transfer!="true"]').prop("disabled", false);
            }
            list_transfer[transfer_index] = transfer_item;
            plugin.settings.list_transfer = list_transfer;
            return true;
        };
        plugin.reset_passenger_selected = function ($self) {
            var $item_transfer = $self.closest('.item-transfer');
            //$item_transfer.find('.list-passenger input.passenger-item[exists_inside_other_transfer]').prop("disabled", false).prop("checked", false).trigger('change');
            plugin.lock_passenger_inside_transfer($self);


        };
        plugin.update_event = function () {


            $element.find('input.passenger-item').unbind('change');
            $element.find('input.passenger-item').change(function selected_passenger(event) {
                var $html_input_passenger = $('#html_input_passenger').data('html_input_passenger');
                var $self = $(this);
                var $transfer = $self.closest('.item-transfer');

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
                    //$self.removeAttr('exists_inside_other_transfer');
                    //var passenger_index=$self.data('index');
                    //$element.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]').prop("disabled", false);

                }
                plugin.lock_passenger_inside_transfer($self);
                plugin.update_data();
                plugin.update_list_transfering();

            });

            $element.find('input[type="radio"][data-name="transfer_type"]').unbind('change');
            $element.find('input[type="radio"][data-name="transfer_type"]').change(function selected_type(event) {
                /* var $self=$(this);
                 plugin.reset_passenger_selected($self);
                 plugin.update_data();
                 plugin.update_list_transfering();*/

            });
            $element.find('textarea[data-name="transfer_note"]').change(function change_note(event) {
                plugin.update_data();
                plugin.update_list_transfering();

            });


        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger = list_passenger;
            var total_passenger = list_passenger.length;
            var $list_transfer = $element.find('.item-transfer');
            $list_transfer.each(function (transfer_index, transfer) {
                if (plugin.enable_add_passenger_to_transfer_index(transfer_index)) {
                    plugin.add_passenger_to_transfer_index(transfer_index);
                    plugin.format_name_for_transfer_index(transfer_index);
                    plugin.lock_passenger_inside_transfer_index(transfer_index);
                    plugin.set_label_passenger_in_transfers();
                }
                plugin.add_event_transfer_index(transfer_index);
                /*
                 var $transfer=$(transfer);
                 var $list_passenger=$transfer.find('.list-passenger');
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
                 var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" data-index="'+i+'" value="'+i+'" name="list_transfer['+transfer_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');
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
             plugin.update_list_transfering();


             */
            plugin.trigger_after_change();
        };
        plugin.exists_infant_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var exists_infant_in_transfer = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_transfer = true;
                    break;
                }
            }
            return exists_infant_in_transfer;
        };
        plugin.exists_infant_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var exists_infant_in_transfer = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_infant(passenger_index)) {
                    exists_infant_in_transfer = true;
                    break;
                }
            }
            return exists_infant_in_transfer;
        };
        plugin.exists_children_2_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var exists_children_2_in_transfer = false;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_2(passenger_index)) {
                    exists_children_2_in_transfer = true;
                    break;
                }
            }
            return exists_children_2_in_transfer;
        };
        plugin.check_all_passenger_is_infant_and_children_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
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
        plugin.enable_add_transfer = function (transfer_index) {

            if (!plugin.validate()) {
                return false;
            }


            var list_passenger = plugin.settings.list_passenger;
            var list_passenger_checked = plugin.get_list_passenger_checked();
            if (list_passenger_checked.length >= list_passenger.length) {
                plugin.notify('you cannot add more transfer');
                return false;
            }


            return true;
        };
        plugin.enable_remove_transfer = function (transfer_index) {
            return true;
        };
        plugin.enable_remove_transfer_index = function (transfer_index) {
            return true;
        };
        plugin.enable_add_passenger_to_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name = passenger.first_name + ' ' + passenger.middle_name + ' ' + passenger.last_name + '(' + passenger.year_old + ')';
            return passenger_full_name;
        };
        plugin.add_passenger_to_transfer_index = function (transfer_index) {

            var list_passenger = plugin.settings.list_passenger.slice();
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
            $transfer_item.find('ul.list-passenger').empty();
            for (var i = 0; i < list_passenger.length; i++) {
                var passenger = list_passenger[i];
                var passenger_full_name = plugin.get_passenger_full_name(passenger);
                var html_template_passenger = plugin.settings.html_template_passenger;
                var $template_passenger = $(html_template_passenger);
                $template_passenger.find('.full-name').html(passenger_full_name);
                $transfer_item.find('ul.list-passenger').append($template_passenger);

            }

        };
        plugin.enable_change_transfer_type_to_transfer_index = function (transfer_index) {

            return true;
        };
        plugin.add_event_last_transfer_index = function () {
            var $last_transfer_item = $element.find('.item-transfer:last');
            var last_transfer_index = $last_transfer_item.index();
            plugin.add_event_transfer_index(last_transfer_index);
        };
        plugin.lock_passenger_inside_transfers = function () {
            var $list_transfer = $element.find('.item-transfer');
            $list_transfer.each(function (transfer_index) {
                plugin.lock_passenger_inside_transfer_index(transfer_index);
            });
        };
        plugin.set_label_passenger_in_transfer_index = function (transfer_index) {
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
            $transfer_item.find('.list-passenger li .in_transfer').html('');
            $transfer_item.find('.list-passenger li').each(function (passenger_index) {
                var a_transfer_index = plugin.get_transfer_index_by_passenger_index_selected(passenger_index);
                if ($.isNumeric(a_transfer_index)) {
                    $(this).find('.in_transfer').html(' (transfer ' + (a_transfer_index + 1) + ')');
                }
            });
        };
        plugin.set_label_passenger_in_transfers = function () {
            var $list_transfer = $element.find('.item-transfer');
            $list_transfer.each(function (transfer_index) {
                plugin.set_label_passenger_in_transfer_index(transfer_index);
            });
        };
        plugin.chang_transfer_type_to_transfer_index = function (transfer_index) {
            var $item_transfer = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            $.each(passengers, function (index, passenger_id) {
                $item_transfer.find('.passenger-item:eq(' + passenger_id + ')').prop('checked', false);
            });
            var transfer_type = $item_transfer.find('input[type="radio"][data-name="transfer_type"]:checked').val();
            list_transfer[transfer_index].passengers = [];
            list_transfer[transfer_index].transfer_type = transfer_type;
            list_transfer[transfer_index].full = false;
            plugin.settings.list_transfer = list_transfer;
            plugin.lock_passenger_inside_transfers();
            plugin.set_label_passenger_in_transfers();
            plugin.update_list_transfering();
            plugin.trigger_after_change();

        };
        plugin.exists_senior_adult_teen_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_senior_adult_teen(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_1_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_children_1(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.exists_children_or_adult_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                if (plugin.is_adult_or_children_by_passenger_index(passenger_index)) {
                    return true;
                }
            }
            return false;
        };
        plugin.enable_change_passenger_inside_transfer_index = function (transfer_index) {
            return true;
        };
        plugin.check_is_full_passenger_inside_transfer = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var transfer_item = list_transfer[transfer_index];
            var transfer_type = transfer_item.transfer_type;
            var setting_transfer_type = plugin.settings[transfer_type];
            var passengers = transfer_item.passengers;
            return passengers.length == setting_transfer_type.max_total;
        };
        plugin.change_passenger_inside_transfer_index = function (transfer_index) {

            var passengers = [];
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
            $transfer_item.find('.passenger-item').each(function (passenger_index) {
                var $self = $(this);
                if ($self.is(':checked')) {
                    passengers.push(passenger_index);
                }
            });
            var list_transfer = plugin.settings.list_transfer;
            list_transfer[transfer_index].passengers = passengers;
            list_transfer[transfer_index].full = plugin.check_is_full_passenger_inside_transfer(transfer_index);
            plugin.settings.list_transfer = list_transfer;
            plugin.lock_passenger_inside_transfers();
            plugin.set_label_passenger_in_transfers();
            plugin.trigger_after_change();
        };
        plugin.remove_passenger_in_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            list_transfer[transfer_index].passengers = [];
            plugin.settings.list_transfer = list_transfer;
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
        plugin.check_transfer_before_add_passenger = function (transfer_index, current_passenger_index_selected) {
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var $passenger = $transfer_item.find('input.passenger-item:eq(' + current_passenger_index_selected + ')');
            var range_year_old_infant = plugin.settings.range_year_old_infant;
            var range_year_old_children_2 = plugin.settings.range_year_old_children_2;
            var range_year_old_infant_and_children_2 = plugin.settings.range_year_old_infant_and_children_2;
            var list_transfer = plugin.settings.list_transfer;
            var transfer_item = list_transfer[transfer_index];
            var transfer_type = transfer_item.transfer_type;
            if (plugin.is_infant(current_passenger_index_selected) && !plugin.settings[transfer_type].enable_select_infant1(transfer_item, range_year_old_infant, current_passenger_index_selected, transfer_index)) {
                var content_notify = 'you cannot add more infant 0-1 because there are no adult in transfer';
                plugin.notify(content_notify);
                $transfer_item.find('.list-passenger').removeClass('error');
                $transfer_item.find('.list-passenger').tipso('destroy');
                $transfer_item.find('.list-passenger').tipso({
                    size: 'tiny',
                    useTitle: false,
                    content: content_notify,
                    animationIn: 'bounceInDown'
                }).addClass('error');
                $transfer_item.find('.list-passenger').tipso('show');
                return false;
            } else {
                $transfer_item.find('.list-passenger').removeClass('error');
                $transfer_item.find('.list-passenger').tipso('destroy');
            }
            if (plugin.is_children_2(current_passenger_index_selected) && !plugin.settings[transfer_type].enable_select_infant2(transfer_item, range_year_old_children_2, current_passenger_index_selected, transfer_index)) {
                return false;
            } else {
                $transfer_item.find('.list-passenger').removeClass('error');
                $transfer_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_children_1 = plugin.settings.range_year_old_children_1;
            if (plugin.is_children_1(current_passenger_index_selected) && !plugin.settings[transfer_type].enable_select_children(transfer_item, range_year_old_children_1, current_passenger_index_selected, transfer_index)) {
                return false;
            } else {
                $transfer_item.find('.list-passenger').removeClass('error');
                $transfer_item.find('.list-passenger').tipso('destroy');
            }
            var range_year_old_senior_adult_teen = plugin.settings.range_year_old_senior_adult_teen;
            if (plugin.is_senior_adult_teen(current_passenger_index_selected) && !plugin.settings[transfer_type].enable_select_senior_adult_teen(transfer_item, range_year_old_senior_adult_teen, current_passenger_index_selected, transfer_index)) {
                return false;
            } else {
                $transfer_item.find('.list-passenger').removeClass('error');
                $transfer_item.find('.list-passenger').tipso('destroy');
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
        plugin.update_transfer_note_transfer_index = function (transfer_index) {
            var $item_transfer = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var list_transfer = plugin.settings.list_transfer;
            var passengers = list_transfer[transfer_index].passengers;
            var transfer_note=$item_transfer.find('textarea[data-name="transfer_note"]').val();
            list_transfer[transfer_index].transfer_note = transfer_note;
            plugin.settings.list_transfer = list_transfer;

        };
        plugin.add_event_transfer_index = function (transfer_index) {
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var $transfer_type_item = $transfer_item.find('input[data-name="transfer_type"]');
            var event_class = 'change_transfer_type';
            if (!$transfer_type_item.hasClass(event_class)) {
                $transfer_type_item.click(function () {
                    var $self = $(this);
                    var a_transfer_index = $self.closest('.item-transfer').index();
                    if (plugin.enable_change_transfer_type_to_transfer_index(a_transfer_index)) {
                        plugin.chang_transfer_type_to_transfer_index(a_transfer_index);
                    }
                }).addClass(event_class);
            }

            var $list_passenger = $transfer_item.find('ul.list-passenger');
            $list_passenger.find('input.passenger-item').each(function (passenger_index) {
                var $passenger = $(this);
                var event_class = 'change_passenger';
                if (!$passenger.hasClass(event_class)) {
                    $passenger.change(function (event) {

                        var $self = $(this);
                        var a_transfer_index = $self.closest('.item-transfer').index();
                        $transfer_item = $self.closest('.item-transfer');
                        var passenger_index = $self.closest('li').index();
                        var list_transfer = plugin.settings.list_transfer;
                        var passengers = list_transfer[a_transfer_index].passengers;
                        var passenger_index_selected = $self.closest('li').index();

                        if (plugin.enable_change_passenger_inside_transfer_index(a_transfer_index)) {
                           plugin.change_passenger_inside_transfer_index(a_transfer_index);

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

             var content_notify='transfer is full you can not add more passenger';
             plugin.notify(content_notify);
             $transfer_item.find('.list-passenger').removeClass('error');
             $transfer_item.find('.list-passenger').tipso('destroy');
             $transfer_item.find('.list-passenger').tipso({
             size: 'tiny',
             useTitle:false,
             content:content_notify,
             animationIn:'bounceInDown'
             }).addClass('error');




             }).addClass(event_class);

             }
             });
             */
            var event_class = 'add_transfer';
            if (!$transfer_item.find('.add-more-transfer').hasClass(event_class)) {
                $transfer_item.find('.add-more-transfer').click(function () {
                    var a_transfer_index = $(this).closest('.item-transfer').index();
                    var $a_transfer_item = $(this).closest('.item-transfer');
                    if (plugin.enable_add_transfer(a_transfer_index)) {
                        //alert('ok');
                        plugin.add_transfer(a_transfer_index);
                        plugin.add_event_transfer_index(a_transfer_index + 1);
                    }
                }).addClass(event_class);


            }
            var event_class = 'remove_transfer';
            if (!$transfer_item.find('.remove-transfer').hasClass(event_class)) {
                $transfer_item.find('.remove-transfer').click(function () {
                    var a_transfer_index = $(this).closest('.item-transfer').index();
                    if (plugin.enable_remove_transfer_index(a_transfer_index)) {
                        plugin.remove_transfer_index(a_transfer_index);
                    }
                }).addClass(event_class);
            }
            var debug=plugin.settings.debug;
            if(debug)
            {
                var event_class = 'change_transfer_note';
                if (!$transfer_item.find('textarea[data-name="transfer_note"]').hasClass(event_class)) {

                    $transfer_item.find('textarea[data-name="transfer_note"]').change(function () {
                        plugin.update_transfer_note_transfer_index(transfer_index);
                    }).addClass(event_class);
                }
            }

            if(debug)
            {
                var event_class = 'add_transfer_note';
                if (!$transfer_item.find('.random-text').hasClass(event_class)) {

                    $transfer_item.find('.random-text').click(function () {
                        $transfer_item.find('textarea[data-name="transfer_note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
                    }).addClass(event_class);
                }
            }


        };
        plugin.format_name_for_transfers = function () {
            var $list_transfer = $element.find('.item-transfer');
            $list_transfer.each(function (transfer_index) {
                plugin.format_name_for_transfer_index(transfer_index);
            });
        };
        plugin.remove_transfer_index = function (transfer_index) {

            var total_transfer = $element.find('.item-transfer').length;
            if (total_transfer == 1) {
                return;
            }
            var $item_transfer = $element.find('.item-transfer:eq(' + transfer_index + ')');
            var list_transfer = plugin.settings.list_transfer;
            list_transfer.splice(transfer_index, 1);
            plugin.settings.list_transfer = list_transfer;
            $item_transfer.remove();
            plugin.lock_passenger_inside_transfers();
            plugin.format_name_for_transfers();
            plugin.set_label_passenger_in_transfers();
            plugin.update_list_transfering();
            plugin.trigger_after_change();

        };
        plugin.add_passenger_to_last_transfer_index = function () {

            var $last_transfer_item = $element.find('.item-transfer:last');
            var last_transfer_index = $last_transfer_item.index();
            if (plugin.enable_add_passenger_to_transfer_index(last_transfer_index)) {
                plugin.add_passenger_to_transfer_index(last_transfer_index);
            }

        };
        plugin.setup_template_element = function () {
            var list_transfer = plugin.settings.list_transfer.slice();
            plugin.settings.item_transfer_template = list_transfer[0];
            var html_item_transfer_template = $element.find('.item-transfer').getOuterHTML();
            plugin.settings.html_item_transfer_template = html_item_transfer_template;


            var html_tr_item_transfer = $element.find('.transfering-list .div-item-transfer').getOuterHTML();
            plugin.settings.html_tr_item_transfer = html_tr_item_transfer;

            var html_template_passenger = $element.find('ul.list-passenger li:first').getOuterHTML();
            plugin.settings.html_template_passenger = html_template_passenger;
        };
        plugin.format_name_for_transfer_index = function (transfer_index) {
            var list_transfer = plugin.settings.list_transfer;
            var transfer_item = list_transfer[transfer_index];
            var $transfer_item = $element.find('.item-transfer:eq(' + transfer_index + ')');

            $transfer_item.find('textarea[data-name="transfer_note"]').attr('name', 'list_transfer[' + transfer_index + '][transfer_note]');

            $transfer_item.find('.transfer-order').html(transfer_index + 1);
            $transfer_item.find('input[data-name="transfer_type"]').each(function (index1, input) {
                var $input = $(input);
                var data_name = $input.data('name');
                $input.attr('name', 'list_transfer[' + transfer_index + '][' + data_name + ']');
            });
            $transfer_item.find('input[data-name="transfer_type"][value="' + transfer_item.transfer_type + '"]').prop('checked', true);

            $transfer_item.find('ul.list-passenger input.passenger-item').each(function (passenger_index) {
                $(this).attr('name', 'list_transfer[' + transfer_index + '][passengers][]');
                $(this).val(passenger_index);
            });
            var passengers = transfer_item.passengers;
            for (var i = 0; i < passengers.length; i++) {
                var passenger_index = passengers[i];
                $transfer_item.find('ul.list-passenger input.passenger-item:eq(' + passenger_index + ')').prop('checked', true);
            }
            //var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" value="'+i+'" data-index="'+i+'" name="list_transfer['+transfer_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');

        };
        plugin.format_name_for_last_transfer = function () {
            var $last_transfer_item = $element.find('.item-transfer:last');
            var last_transfer_index = $last_transfer_item.index();
            plugin.format_name_for_transfer_index(last_transfer_index);
        };
        plugin.exchange_index_for_list_transfer = function (old_index, new_index) {
            var list_transfer = plugin.settings.list_transfer;
            var temp_transfer = list_transfer[old_index];
            list_transfer[old_index] = list_transfer[new_index];
            list_transfer[new_index] = temp_transfer;
            plugin.settings.list_transfer = list_transfer;

        };
        plugin.setup_sortable = function () {
            $element.find('.table-transfering-list .tbody').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                items: ".div-item-transfer",
                stop: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_transfering();*/
                }

            });
            var element_key = plugin.settings.element_key;
            $element.find('.' + element_key + '_list_transfer').sortable({
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
                        plugin.exchange_index_for_list_transfer(old_index, new_index);
                        console.log(plugin.settings.list_transfer);
                        if (old_index < new_index) {
                            plugin.format_name_for_transfer_index(old_index);
                            plugin.format_name_for_transfer_index(new_index);
                        } else {
                            plugin.format_name_for_transfer_index(new_index);
                            plugin.format_name_for_transfer_index(old_index);
                        }
                        plugin.set_label_passenger_in_transfers();
                        console.log("Start position: " + ui.item.startPos);
                        console.log("New position: " + ui.item.index());
                        plugin.update_list_transfering();
                        plugin.trigger_after_change();

                    }
                },
                update: function (event, ui) {
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_transfering();*/
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
            plugin.add_passenger_to_last_transfer_index();
            plugin.format_name_for_last_transfer();
            plugin.add_event_last_transfer_index();
            plugin.setup_sortable();


            /*
             plugin.update_event();
             plugin.update_data();
             var debug=plugin.settings.debug;
             if(debug)
             {
             $element.find('.item-transfer .random-text').click(function(){
             var $item_transfer=$(this).closest('.item-transfer');
             $item_transfer.find('textarea[data-name="transfer_note"]').delorean({ type: 'words', amount: 5, character: 'Doc', tag:  '' }).trigger('change');
             });
             }
             */


        };
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.html_build_pickup_transfer = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_build_pickup_transfer')) {
                var plugin = new $.html_build_pickup_transfer(this, options);
                $(this).data('html_build_pickup_transfer', plugin);

            }

        });

    }

})(jQuery);


