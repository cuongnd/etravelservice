(function ($) {    // here we go!    $.select_table_departure_date = function (element, options) {        // plugin's default options        var defaults = {            default:0,            list_service_class:[]        }        // current instance of the object        var plugin = this;        // this will hold the merged default, and user-provided options        plugin.settings = {};        var $element = $(element), // reference to the jQuery version of DOM element            element = element;    // reference to the actual DOM element        // the "constructor" method that gets called when the object is created        plugin.init = function () {            plugin.settings = $.extend({}, defaults, options);            $element.find('.tr-row:odd').css({                'background-color':'#f9f9f9'            });        };        plugin.trigger=function(event,_function){            switch(event) {                case 'change':                    $element.find('.body .tr-row .input-application').click(function(){                        var tsmart_service_class_id=$(this).val();                        if(typeof _function=='function')                        {                            _function.call(this,tsmart_service_class_id);                        }                    });                    break;                case 'click':                    break;                default:                    //            }        };        plugin.filter_by_list_departure_id=function(list_departure_id){            $element.find('>.body>.tr-row').removeClass('show').hide();            for (var key in list_departure_id) {                // skip loop if the property is from prototype                if (!list_departure_id.hasOwnProperty(key)) continue;                var tr_row=$element.find('>.body>.tr-row[data-tsmart_departure_id="'+list_departure_id[key]+'"]');                tr_row.addClass('show').show();            }        }        plugin.un_check_all=function(){            $element.find('.body .tr-row .input-application').prop('checked',false);        }        plugin.init();    }    // add the plugin to the jQuery.fn object    $.fn.select_table_departure_date = function (options) {        // iterate through the DOM elements we are attaching the plugin to        return this.each(function () {            // if plugin has not already been attached to the element            if (undefined == $(this).data('select_table_departure_date')) {                var plugin = new $.select_table_departure_date(this, options);                $(this).data('select_table_departure_date', plugin);            }        });    }})(jQuery);