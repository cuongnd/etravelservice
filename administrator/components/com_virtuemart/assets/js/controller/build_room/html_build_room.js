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
                max_total:1
            },
            twin:{
                max_adult:2,
                max_children:2,
                max_infant:1,
                max_total:1
            },
            triple:{
                max_adult:2,
                max_children:2,
                max_infant:1,
                max_total:1
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
                $item.find('input').each(function(index1,input){
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
        plugin.add_room = function ($self) {
            $element.find(".item-room:last").clone(true).insertAfter(".item-room:last");
            var $item_passenger=$element.find('.item-room:last');
            plugin.config_layout();
            plugin.update_data();
            plugin.update_event();
        };
        plugin.get_data=function(){
            return plugin.settings.output_data;
        };
        plugin.update_event = function () {
            $element.find('input').change(function(){
                plugin.update_data();
            });
        };
        plugin.update_passengers = function (data) {
            plugin.settings.list_passenger=data;
            var $list_passenger=$element.find('.list-passenger');
            var total_passenger=data.first_name.length;
            $list_passenger.empty();
            for(var i=0;i<total_passenger;i++){
                var full_name=data.first_name[i]+' '+data.middle_name[i]+' '+data.last_name[i];
                var $li=$('<li><label><input type="checkbox">'+full_name+'</label></li>');
                $li.appendTo($list_passenger);
            }
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


