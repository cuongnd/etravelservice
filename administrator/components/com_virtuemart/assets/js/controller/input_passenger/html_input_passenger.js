(function ($) {

    // here we go!
    $.html_input_passenger = function (element, options) {

        // plugin's default options
        var defaults = {
            input_name: '',
            to_name: '',
            min_date: new Date(),
            max_date: new Date(),
            from_date: new Date(),
            enable_cookie_passenger: true,
            display_format:'YYYY-MM-DD',
            format:'YYYY-MM-DD',
            list_passenger: {},
            output_data:[],
            event_after_change:false

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
            var list_passenger=plugin.settings.list_passenger;

            var total_passenger=list_passenger.first_name.length;
            console.log(total_passenger);
            for(var i=0;i<total_passenger-1;i++){
                $element.find('.add').trigger('click');
            }
            for(var i=0;i<total_passenger;i++){
                var $item=$element.find('.item-passenger:eq('+i+')');
                $item.find(':input[name]').each(function(index,input){
                    var $input=$(input);
                    var attr_name=$input.attr('name');
                    if(typeof attr_name =="undefined"){
                        throw "attr_name undefined";
                    }
                    attr_name=attr_name.replace("[]", "");

                    $item.find(':input[name="'+attr_name+'[]"]').val(list_passenger[attr_name][i]);
                });
            }
            //$html_build_room.update_passengers(data);
        };
        plugin.config_layout = function () {
            $element.find('.item-passenger').each(function(index,item){
                var $item=$(item);
                $item.find('input').each(function(index1,input){

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
            var enable_cookie_passenger=plugin.settings.enable_cookie_passenger;
            if(enable_cookie_passenger)
            {
                $.cookie('list_passenger',JSON.stringify(plugin.settings.output_data));
                console.log($.cookie('list_passenger'));
            }

        };
        plugin.add_passenger = function ($self) {
            $element.find(".item-passenger:last").clone(true).insertAfter(".item-passenger:last");
            var $item_passenger=$element.find('.item-passenger:last');
            $item_passenger.find('input').val('');
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
        plugin.remove_passenger = function ($self) {
            var total_passenger=$element.find('.item-passenger').length;
            if(total_passenger==1)
            {
                return;
            }
            var $item_passenger=$self.closest('.item-passenger');
            $item_passenger.remove();
            plugin.config_layout();
            plugin.update_data();
            plugin.update_event();

        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);

            $element.find('.add').click(function(){
                plugin.add_passenger($(this));
            });
            $element.find('.remove').click(function(){
                plugin.remove_passenger($(this));
            });
            plugin.update_event();
            plugin.update_data();
            var enable_cookie_passenger=plugin.settings.enable_cookie_passenger;
            var list_passenger=plugin.settings.list_passenger;
            if(enable_cookie_passenger)
            {
                list_passenger=$.cookie('list_passenger');
                plugin.settings.list_passenger= $.parseJSON(list_passenger);

            }
            plugin.render_input_person();

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


