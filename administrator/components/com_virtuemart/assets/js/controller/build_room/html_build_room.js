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
            event_after_change:false,
            update_passenger:false,
            single:{
                max_adult:1,
                max_children:1,
                max_infant:1,
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
            }

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
        plugin.config_layout = function () {
            $element.find('.item-room').each(function(index,item){
                var $item=$(item);
                $item.find('input[data-name="type"]').each(function(index1,input){
                    var $input=$(input);
                    var data_name=$input.data('name');
                    $input.attr('name',data_name+'['+index+']');
                });


            });
        };

        plugin.update_data = function () {
            var input_name=plugin.settings.input_name;
            var $input_name=$element.find('input[name="'+input_name+'"]');
            var data=$element.find(':input[name]').serializeObject();
            plugin.settings.output_data=data;
            data= JSON.stringify(data);
            $input_name.val(data);
            var event_after_change=plugin.settings.event_after_change;
            if(event_after_change instanceof Function)
            {
                event_after_change(plugin.settings.output_data);
            }
        };
        plugin.lock_passenger_inside_room = function () {
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
            $list_room.each(function(index,room){
                var $room=$(room);
                if(list_passenger_selected.length>0)
                {
                    for(var i=0;i<list_passenger_selected.length;i++){
                        var passenger_index=list_passenger_selected[i];
                        $room.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]:not(:checked)').prop("disabled", true).attr('exists_inside_other_room',true);
                    }
                }
            });

        };
        plugin.add_room = function ($self) {
            var $item_room=$element.find(".item-room:last").clone(true);
            $item_room.find('input[type="radio"][data-name="type"]').prop('checked',false);
            $item_room.insertAfter(".item-room:last");
            var $item_passenger=$element.find('.item-room:last');
            $item_passenger.find('.list-passenger input.passenger-item').prop('checked',false);
            $item_passenger.find('.list-passenger input.passenger-item').prop("disabled", false);
            plugin.lock_passenger_inside_room();
            plugin.config_layout();
            plugin.update_data();
            plugin.update_event();
        };
        plugin.get_data=function(){
            return plugin.settings.output_data;
        };
        plugin.validate_data = function ($self) {
            var $item_room=$self.closest('.item-room');
            console.log($item_room);
            var $type=$item_room.find('input[type="radio"][data-name="type"]:checked');
            if($type.length==0)
            {
                alert('please select room type');
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
            $item_room.find('.list-passenger input.passenger-item[exists_inside_other_room!="true"]').prop("disabled", false).prop("checked", false).trigger('change');
            plugin.lock_passenger_inside_room();


        };
        plugin.update_event = function () {

            $element.find('input.passenger-item').unbind('change');
            $element.find('input.passenger-item').change(function selected_passenger(event){
                var $self=$(this);
                if(!plugin.validate_data($self))
                {
                    $self.prop('checked', false);
                    return false;
                }
                if(!$self.is(":checked"))
                {
                    $self.removeAttr('exists_inside_other_room');
                    var passenger_index=$self.data('index');
                    $element.find('.list-passenger input.passenger-item[data-index="'+passenger_index+'"]').prop("disabled", false);
                }
                plugin.lock_passenger_inside_room();

            });

            $element.find('input[type="radio"][data-name="type"]').unbind('change');
            $element.find('input[type="radio"][data-name="type"]').change(function selected_type(event){
                var $self=$(this);
                plugin.reset_passenger_selected($self);

            });

        };
        plugin.update_passengers = function (list_passenger) {
            plugin.settings.list_passenger=list_passenger;
            var total_passenger=list_passenger.length;
            var $list_room=$element.find('.item-room');
            $list_room.each(function(index,room){
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
                    key_full_name= base64.encode(key_full_name);
                    var $li=$('<li><label><input class="passenger-item" data-key_full_name="'+key_full_name+'" data-index="'+i+'" name="passenger['+i+']" type="checkbox">'+full_name+'</label></li>');
                    $li.appendTo($list_passenger);
                }

                $list_passenger.find('li input.passenger-item').each(function(index){
                    var $self=$(this);
                    var key_full_name = $(this).data('key_full_name');
                    if(list_old_passenger.length>0 && $.inArray(key_full_name,list_old_passenger)){
                        $self.prop('checked',true).trigger('change');
                    }
                });
            });


            plugin.update_event();
        };
        plugin.remove_room = function ($self) {
            var total_room=$element.find('.item-room').length;
            if(total_room==1)
            {
                return;
            }
            var $item_room=$self.closest('.item-room');
            $item_room.remove();
            plugin.config_layout();
            plugin.update_data();
            plugin.update_event();

        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            plugin.render_input_person();
            $element.find('.add-more-room').click(function(){
                plugin.add_room($(this));
            });
            $element.find('.remove-room').click(function(){
                plugin.remove_room($(this));
            });
            plugin.update_event();
            plugin.update_data();

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


