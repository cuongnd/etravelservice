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
            display_format:'YYYY-MM-DD',
            format:'YYYY-MM-DD',
            list_passenger: [],
            output_data:[],
            list_room:[
                {
                    passengers:[],
                    room_type:[],
                    room_note:''

                }
            ],
            item_room_template:{},
            event_after_change:false,
            update_passenger:false,
            single:{
                max_adult:1,
                max_children:1,
                max_infant:0,
                max_total:1
            },
            double:{
                max_adult:2,
                max_children:2,
                max_infant:1,
                max_total:2
            },
            twin:{
                max_adult:2,
                max_children:2,
                max_infant:1,
                max_total:3
            },
            triple:{
                max_adult:2,
                max_children:2,
                max_infant:1,
                max_total:4
            },
            element_key:'html_build_room',
            debug:false,
            range_adult:[12,50]

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
        plugin.render_input_person = function () {

        };

        plugin.update_data = function () {
            var input_name=plugin.settings.input_name;
            var $input_name=$element.find('input[name="'+input_name+'"]');
            var data=$element.find(':input[name]').serializeObject();
            if(typeof data.list_room=="undefined")
            {
                return false;
            }
            console.log(data.list_room);
            plugin.settings.list_room=data.list_room;
            data= JSON.stringify(data);
            $input_name.val(data);
            var event_after_change=plugin.settings.event_after_change;
            if(event_after_change instanceof Function)
            {
                event_after_change(plugin.settings.list_room);
            }
        };
        plugin.get_list_passenger_selected=function(){
            var $list_room=$element.find('.item-room');
            var list_passenger_selected=[];
            $list_room.each(function(index,room){
                var $room=$(room);
                var $list_passenger_selected_inside_room=$room.find('.list-passenger input.passenger-item:checked');
                $list_passenger_selected_inside_room.each(function(index1,passenger){
                    var $passenger=$(passenger);
                    var data_index=$passenger.data('index');
                    list_passenger_selected.push(data_index);
                });


            });
            return list_passenger_selected;
        }
        plugin.lock_passenger_inside_room = function ($self) {
            var $current_room=$self.closest('.item-room');
            var current_room_index=$current_room.index();
            var $list_room=$element.find('.item-room');
            var list_passenger_selected=plugin.get_list_passenger_selected();
            if(list_passenger_selected.length>0) {
                $list_room.each(function (index, room) {
                    if (index != current_room_index) {
                        var $room = $(room);
                        $room.find('.list-passenger input.passenger-item').each(function (passenger_index) {
                            if (!$.inArray(passenger_index, list_passenger_selected)) {
                                $(this).prop("disabled", true);
                            }
                        });
                    }
                });
            }
            console.log('hello lock_passenger_inside_room');

        };
        plugin.get_list_passenger_checked=function(){
            var list_passenger_checked=[];
            var list_room=plugin.settings.list_room;
            var list_passenger=plugin.settings.list_passenger;
            $.each(list_room,function(index,room){

                if(typeof room.passengers!="undefined") {
                    var passengers = room.passengers;
                    $.each(passengers, function (index_passenger, order_passenger) {
                        list_passenger_checked.push(order_passenger);
                    });


                }
            });
            return list_passenger_checked;
        };
        plugin.add_list_passenger_to_room = function ($item_room) {
            var room_index=$item_room.index();
            var list_passenger =plugin.settings.list_passenger;
            var total_passenger=list_passenger.length;
            var $list_passenger=$item_room.find('.list-passenger');
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
                var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" value="'+i+'" data-index="'+i+'" name="list_room['+room_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');
                $li.appendTo($list_passenger);
            }
        };
        plugin.update_list_rooming = function () {
            var list_room=plugin.settings.list_room;
            var list_passenger=plugin.settings.list_passenger;
            var $table_rooming_list=$element.find('.table-rooming-list');
            var $tbody=$table_rooming_list.find('.tbody');
            $tbody.empty();
            var html_tr_item_room=plugin.settings.html_tr_item_room;
            $.each(list_room,function(index,room){

                $(html_tr_item_room).appendTo($tbody);
                var $tr_item_room=$tbody.find('div.div-item-room:last');
                $tr_item_room.find('span.order').html(index+1);
                $tr_item_room.find('div.room_type').html(room.type);
                $tr_item_room.find('div.room_note').html(room.note);

                if(typeof room.passengers!="undefined") {
                    var passengers = room.passengers;
                    var sub_list_passenger = [];

                    $.each(passengers, function (index_passenger, order_passenger) {
                        var item_passenger = list_passenger[order_passenger];
                        var full_name=item_passenger.first_name+' '+item_passenger.middle_name+' '+item_passenger.last_name+' '+item_passenger.year_old;
                        sub_list_passenger.push(full_name);
                    });
                    sub_list_passenger=sub_list_passenger.join(',');
                    $tr_item_room.find('div.table_list_passenger').html(sub_list_passenger);


                }
            });
            $element.find('.div-item-room .delete-room').click(function delete_room(event){
                var $self=$(this);
                var $tr_room_item=$self.closest('.div-item-room');
                var index_of_room=$tr_room_item.index();
                $element.find('.item-room:eq('+index_of_room+') .remove-room').trigger('click');

            });

            $element.find('.table-rooming-list .tbody').sortable("refresh");

        };
        plugin.add_room = function ($self) {


            var html_item_room_template=plugin.settings.html_item_room_template;
            var $last_item_room=$element.find(".item-room:last");
            $(html_item_room_template).insertAfter($last_item_room);
            var $last_item_room=$element.find(".item-room:last");

            var item_room_template={};
            var list_room=plugin.settings.list_room;
            item_room_template.passengers=[];
            item_room_template.room_type=[];
            item_room_template.note=[];
            list_room.push(item_room_template);
            console.log(list_room);

            var last_room_index=$last_item_room.index();
            if(plugin.enable_add_passenger_to_room_index(last_room_index))
            {
                plugin.add_passenger_to_room_index(last_room_index);
            }
            plugin.format_name_for_room(last_room_index);


        };
        plugin.validate=function(){
            var error=false;
            var $list_room=$element.find('.item-room');
            $list_room.each(function(index,room){
                var $room=$(room);
                var $type=$room.find('input[data-name="type"]:checked');
                if($type.length==0)
                {
                    $room.find('.list-room').addClass('error');
                    error=true;
                    $room.find('.list-room').tipso({
                        size: 'tiny',
                        useTitle:false,
                        content:'please select room type',
                        animationIn:'bounceInDown'
                    });
                    $room.find('.list-room').tipso('show');
                }else{
                    $room.find('.list-room').removeClass('error');
                    $room.find('.list-room').tipso('destroy');
                }

                var $passenger=$room.find('input.passenger-item:checked');
                var $list_passenger=$room.find('.list-passenger');
                if($passenger.length==0)
                {
                    $list_passenger.addClass('error');
                    error=true;
                    $list_passenger.tipso({
                        size: 'tiny',
                        useTitle:false,
                        content:'please select passenger',
                        animationIn:'bounceInDown'
                    });
                    $list_passenger.tipso('show');
                }else{
                    $list_passenger.removeClass('error');
                    $list_passenger.tipso('destroy');

                }





            });
            var list_passenger=plugin.settings.list_passenger;

            var list_passenger_checked=plugin.get_list_passenger_checked();
            console.log(list_passenger.length);
            console.log(list_passenger_checked.length);
            if(list_passenger_checked.length>=list_passenger.length)
            {
                error= true;
                alert('you cannot add more room');
            }
            if(error)
            {
                return false;
            }else{
                return true;
            }
        };
        plugin.get_data=function(){
            return plugin.settings.output_data;
        };
        plugin.validate_data = function ($self) {
            var $item_room=$self.closest('.item-room');
            var $type=$item_room.find('input[type="radio"][data-name="type"]:checked');
            if($type.length==0)
            {
                $item_room.find('.list-room').tipso({
                    size: 'tiny',
                    useTitle:false,
                    content:'please select room type',
                    animationIn:'bounceInDown'
                }).addClass('error');
                $item_room.find('.list-room').tipso('show');
                return false;
            }
            var type=$type.val();
            type=plugin.settings[type];
            var total_passenger_selected=$item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]:checked').length;
            if(total_passenger_selected>=type.max_total)
            {
                $item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]:not(:checked)').prop("disabled", true);

            }else{
                $item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]').prop("disabled", false);
            }

            return true;
        };
        plugin.reset_passenger_selected = function ($self) {
            var $item_room=$self.closest('.item-room');
            //$item_room.find('.list-passenger input.passenger-item[exists_inside_other_room]').prop("disabled", false).prop("checked", false).trigger('change');
            plugin.lock_passenger_inside_room($self);


        };
        plugin.update_event = function () {


            $element.find('input.passenger-item').unbind('change');
            $element.find('input.passenger-item').change(function selected_passenger(event){
                var $html_input_passenger=$('#html_input_passenger').data('html_input_passenger');
                var $self=$(this);
                var $room=$self.closest('.item-room');

                if(!$html_input_passenger.validate())
                {
                    $self.prop('checked', false);
                    return false;
                }


                if(!plugin.validate_data($self))
                {
                    $self.prop('checked', false);
                    return false;
                }
                if(!$self.is(":checked"))
                {
                    var passenger_index=$self.data('index');
                    console.log(passenger_index);
                    //$self.removeAttr('exists_inside_other_room');
                    //var passenger_index=$self.data('index');
                    //$element.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]').prop("disabled", false);

                }
                plugin.lock_passenger_inside_room($self);
                plugin.update_data();
                plugin.update_list_rooming();

            });

            $element.find('input[type="radio"][data-name="type"]').unbind('change');
            $element.find('input[type="radio"][data-name="type"]').change(function selected_type(event){
               /* var $self=$(this);
                plugin.reset_passenger_selected($self);
                plugin.update_data();
                plugin.update_list_rooming();*/

            });
            $element.find('textarea[data-name="note"]').change(function change_note(event){
                plugin.update_data();
                plugin.update_list_rooming();

            });


        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger=list_passenger;
            var total_passenger=list_passenger.length;
            var $list_room=$element.find('.item-room');
            $list_room.each(function(room_index,room){
                if(plugin.enable_add_passenger_to_room_index(room_index))
                {
                    plugin.add_passenger_to_room_index(room_index);
                    plugin.format_name_for_room(room_index);
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
        };
        plugin.enable_add_room = function (room_index) {
            if(!plugin.validate())
            {
                return false;
            }
            return true;
            var list_room=plugin.settings.list_room;
            var list_passenger=plugin.settings.list_passenger;
            var range_adult=plugin.settings;

            return true;

        };
        plugin.enable_remove_room = function (room_index) {
            return true;
        };
        plugin.enable_remove_room_index = function (room_index) {
            return true;
        };
        plugin.enable_add_passenger_to_room_index = function (room_index) {
            return true;
        };
        plugin.get_passenger_full_name = function (passenger) {
            var passenger_full_name=passenger.first_name+' '+passenger.middle_name+' '+passenger.last_name+'('+passenger.year_old+')';
            return passenger_full_name;
        };
        plugin.add_passenger_to_room_index = function (room_index) {

            var function_add_passenger=function(passenger){
                var passenger_full_name=plugin.get_passenger_full_name(passenger);
                var html_template_passenger=plugin.settings.html_template_passenger;
                var $template_passenger=$(html_template_passenger);
                $template_passenger.find('.full-name').html(passenger_full_name);
                $room_item.find('ul.list-passenger').append($template_passenger);

            };
            var function_change_passenger=function(passenger,$passenger){
                var passenger_full_name=plugin.get_passenger_full_name(passenger);

                $passenger.find('.full-name').html(passenger_full_name);
            };
            var list_passenger=plugin.settings.list_passenger.slice();
            var $room_item=$element.find('.item-room:eq('+room_index+')');
            var $list_passenger=$room_item.find('ul.list-passenger li');
            for(var i=0;i<$list_passenger.length;i++){
                var passenger=list_passenger.pop();
                var $passenger=$($list_passenger.get( i ));
                if(typeof passenger=="undefined")
                {
                    $passenger.remove();
                }else{
                    function_change_passenger(passenger,$passenger);
                }
            }
            for(var i=0;i<list_passenger.length;i++){
                var passenger=list_passenger[i];
                function_add_passenger(passenger);
            }

        };
        plugin.enable_change_room_type_to_room_index = function (room_index) {
            return true;
        };
        plugin.add_event_last_room_index = function () {
            var $last_room_item=$element.find('.item-room:last');
            var last_room_index=$last_room_item.index();
            plugin.add_event_room_index(last_room_index);
        };
        plugin.chang_room_type_to_room_index = function (room_index) {
            
        };
        plugin.enable_change_passenger_inside_room_index = function (room_index) {
            return true;
        };
        plugin.change_passenger_inside_room_index = function (room_index) {

            var passengers=[];
            var $room_item=$element.find('.item-room:eq('+room_index+')');
            $room_item.find('.passenger-item').each(function(passenger_index){
                var $self=$(this);
                if($self.is(':checked'))
                {
                    passengers.push(passenger_index);
                }
            });
            var list_room=plugin.settings.list_room;
            list_room[room_index].passengers=passengers;
            console.log(plugin.settings.list_room);
        };
        plugin.add_event_room_index = function (room_index) {
            var $room_item=$element.find('.item-room:eq('+room_index+')');
            var $room_type_item=$room_item.find('input[data-name="type"]');
            var event_class='change_room_type';
            if(!$room_type_item.hasClass(event_class))
            {
                $room_type_item.click(function(){
                    if(plugin.enable_change_room_type_to_room_index(room_index))
                    {
                        plugin.chang_room_type_to_room_index(room_index);
                    }
                }).addClass(event_class);
            }

            var $list_passenger=$room_item.find('ul.list-passenger');
            $list_passenger.find('input.passenger-item').each(function(passenger_index){
                var $passenger=$(this);
                var event_class='change_passenger';
                if(!$passenger.hasClass(event_class))
                {
                    $passenger.change(function(){
                        if(plugin.enable_change_passenger_inside_room_index(room_index))
                        {
                            plugin.change_passenger_inside_room_index(room_index);
                        }
                    }).addClass(event_class);
                }
            });
            var event_class='add_room';
            if(!$room_item.find('.add-more-room').hasClass(event_class))
            {
                $room_item.find('.add-more-room').click(function(){
                    if(plugin.enable_add_room(room_index))
                    {
                        plugin.add_room(room_index);
                        plugin.add_event_room_index(room_index+1);
                    }
                }).addClass(event_class);


            }
            var event_class='remove_room';
            if(!$room_item.hasClass(event_class))
            {
                $room_item.find('.remove-room').click(function(){
                    var $room=$(this).closest('.item-room');
                    var room_index=$room.index();
                    if(plugin.enable_remove_room_index(room_index))
                    {
                        plugin.remove_room_index(room_index);
                    }
                }).addClass(event_class);
            }




        };
        plugin.remove_room_index = function (room_index) {

            var total_room=$element.find('.item-room').length;
            if(total_room==1)
            {
                return;
            }
            var $item_room=$element.find('.item-room:eq('+room_index+')');
            var list_room=plugin.settings.list_room;
            list_room.splice(room_index, 1);
            plugin.settings.list_room=list_room;
            $item_room.remove();
        };
        plugin.add_passenger_to_last_room_index = function () {

            var $last_room_item=$element.find('.item-room:last');
            var last_room_index=$last_room_item.index();
            if(plugin.enable_add_passenger_to_room_index(last_room_index))
            {
                plugin.add_passenger_to_room_index(last_room_index);
            }

        };
        plugin.setup_template_element = function () {
            var list_room=plugin.settings.list_room.slice();
            plugin.settings.item_room_template=list_room[0];
            var html_item_room_template=$element.find('.item-room').getOuterHTML();
            plugin.settings.html_item_room_template= html_item_room_template;


            var html_tr_item_room=$element.find('.rooming-list .div-item-room').getOuterHTML();
            plugin.settings.html_tr_item_room= html_tr_item_room;

            var html_template_passenger=$element.find('ul.list-passenger li:first').getOuterHTML();
            plugin.settings.html_template_passenger= html_template_passenger;
        };
        plugin.format_name_for_room = function (room_index) {
            var $room_item=$element.find('.item-room:eq('+room_index+')');

            $room_item.find('textarea[data-name="note"]').attr('name','list_room['+room_index+'][note]');

            $room_item.find('.room-order').html(room_index+1);
            $room_item.find('input[data-name="type"]').each(function(index1,input){
                var $input=$(input);
                var data_name=$input.data('name');
                $input.attr('name','list_room['+room_index+']['+data_name+']');
            });
            $room_item.find('ul.list-passenger input.passenger-item').each(function(passenger_index){
                $(this).attr('name','list_room['+room_index+'][passengers][]');
                $(this).val(passenger_index);
            });
            //var $li=$('<li><label class="checkbox-inline"> <input class="passenger-item" data-key_full_name="'+key_full_name+'" value="'+i+'" data-index="'+i+'" name="list_room['+room_index+'][passengers][]" type="checkbox"> '+full_name+'</label></li>');

        };
        plugin.format_name_for_last_room = function () {
            var $last_room_item=$element.find('.item-room:last');
            var last_room_index=$last_room_item.index();
            plugin.format_name_for_room(last_room_index);
        };
        plugin.exchange_index_for_list_room = function (old_index, new_index) {
            var list_room=plugin.settings.list_room;
            var temp_room=list_room[old_index];
            list_room[old_index]=list_room[new_index];
            list_room[new_index]=temp_room;
            plugin.settings.list_room=list_room;
        };
        plugin.setup_sortable = function () {
            $element.find('.table-rooming-list .tbody').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                items: ".div-item-room",
                stop:function(event, ui ){
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_rooming();*/
                }

            });
            var element_key=plugin.settings.element_key;
            $element.find('.'+element_key+'_list_room').sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                handle: ".handle",
                start: function(event, ui) {
                    ui.item.startPos = ui.item.index();
                },
                stop: function(event, ui) {
                    var old_index=ui.item.startPos;
                    var new_index=ui.item.index();
                    if( ui.item.startPos!=ui.item.index())
                    {
                        plugin.format_name_for_room(old_index);
                        plugin.format_name_for_room(new_index);
                        plugin.exchange_index_for_list_room(old_index,new_index);
                        console.log("Start position: " + ui.item.startPos);
                        console.log("New position: " + ui.item.index());

                    }
                },
                update:function(event, ui ){
                    console.log(ui);
                    /* plugin.config_layout();
                     plugin.update_data();
                     plugin.update_list_rooming();*/
                }

            });
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
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


