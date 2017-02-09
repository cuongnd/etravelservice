(function ($) {

    // here we go!
    $.html_select_number_passenger = function (element, options) {

        // plugin's default options
        var defaults = {
            element_name:"",
            template_selection:"%s",
            template_result:"%s",
            list_number:[],
            placeholder:"",
            disable_select:false,
            onchange:null
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_select2_template_result = function (result) {
            var template_result=plugin.settings.template_result;
            return $.sprintf( template_result,result.text);
        };
        plugin.set_lect2_template_selection = function (selection) {
            if(selection.text!="") {
                var template_selection = plugin.settings.template_selection;
                return $.sprintf(template_selection, selection.text);
            }else{
                selection.text;
            }
        };
        plugin.set_value=function(number_selected){
            var element_name=plugin.settings.element_name;
            $element.find('select[name="'+element_name+'"]').val(number_selected).trigger('change');
        };
        plugin.get_value=function(){
            var element_name=plugin.settings.element_name;
            return $element.find('select[name="'+element_name+'"]').val();
        };
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);

            var list_number=plugin.settings.list_number;
            var placeholder=plugin.settings.placeholder;
            var number_selected=plugin.settings.number_selected;
            var disable_select=plugin.settings.disable_select;
            var element_name=plugin.settings.element_name;
            plugin.select2=$element.find('select[name="'+element_name+'"]').select2({
                data:list_number,
                placeholder: placeholder,
                templateResult:plugin.set_select2_template_result,
                templateSelection:plugin.set_lect2_template_selection,
            });
            $element.find('select[name="'+element_name+'"]').on('select2:select', function (evt) {
                var onchange=plugin.settings.onchange;
                var selected=$(this).val();
                if( onchange instanceof Function){

                    onchange(selected);
                }
            });
            plugin.select2.val(number_selected).trigger("change");
        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_number_passenger = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_number_passenger')) {
                var plugin = new $.html_select_number_passenger(this, options);
                $(this).data('html_select_number_passenger', plugin);

            }

        });

    }

})(jQuery);


