//huong dan su dung/* $('.list_radio_rooming').list_radio_rooming(); list_radio_rooming=$('.list_radio_rooming').data('list_radio_rooming'); console.log(list_radio_rooming); */// jQuery Plugin for SprFlat admin list_radio_rooming// Control options and basic function of list_radio_rooming// version 1.0, 28.02.2013// by SuggeElson www.suggeelson.com(function ($) {    // here we go!    $.list_radio_rooming = function (element, options) {        // plugin's default options        var defaults = {            on_change:null            //main color scheme for list_radio_rooming            //be sure to be same as colors on main.css or custom-variables.less        }        // current instance of the object        var plugin = this;        // this will hold the merged default, and user-provided options        plugin.settings = {}        var $element = $(element), // reference to the jQuery version of DOM element            element = element;    // reference to the actual DOM element        // the "constructor" method that gets called when the object is created        plugin.get_value=function(){            var element_name= plugin.settings.element_name;            return $element.find('input[name="'+element_name+'"]:checked').val();        };        plugin.init = function () {            plugin.settings = $.extend({}, defaults, options);            var element_name= plugin.settings.element_name;            $element.find('input[name="'+element_name+'"]').checkator({}).change(function(){                var on_change= plugin.settings.on_change;                var selected=$(this).val();                if(on_change instanceof Function && $(this).is(':checked') ){                    on_change(selected);                }            });        }        plugin.example_function = function () {        }        plugin.init();    }    // add the plugin to the jQuery.fn object    $.fn.list_radio_rooming = function (options) {        // iterate through the DOM elements we are attaching the plugin to        return this.each(function () {            // if plugin has not already been attached to the element            if (undefined == $(this).data('list_radio_rooming')) {                var plugin = new $.list_radio_rooming(this, options);                $(this).data('list_radio_rooming', plugin);            }        });    }})(jQuery);