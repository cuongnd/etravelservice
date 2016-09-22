//huong dan su dung
/*
 $('.view_order').view_order();

 view_order=$('.view_order').data('view_order');
 console.log(view_order);
 */

// jQuery Plugin for SprFlat admin view_order
// Control options and basic function of view_order
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_order = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_order
            //be sure to be same as colors on main.css or custom-variables.less

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            var $form=$element.find('form#reset_password');
            $form.submit(function()
            {
                if (plugin.validate()) {

                    return true;
                }
                return false;
            });
        }

        plugin.example_function = function() {

        }
        plugin.validate = function() {
            //lon 6 ky tu
            //co chu va co so
            //
            var LOWER = /[a-z]/,
                UPPER = /[A-Z]/,
                DIGIT = /[0-9]/,
                DIGITS = /[0-9].*[0-9]/,
                SPECIAL = /[^a-zA-Z0-9]/,
                SAME = /^(.)\1+$/;



            var $password=$element.find('input[name="password"]');
            var $password1=$element.find('input[name="password1"]');
            var str_password=$password.val();
            var lower = LOWER.test(str_password),
                upper = UPPER.test(plugin.uncapitalize(str_password)),
                digit = DIGIT.test(str_password),
                digits = DIGITS.test(str_password),
                special = SPECIAL.test(str_password);


            if($password.val()==''){
                $.notify('you need input password');
                $password.focus();
                return false;
            }else if($password1.val()==''){
                $.notify('you need retype input password');
                $password1.focus();
                return false;
            }else if($password.val()!=$password1.val()){
                $.notify('you need check retype input password');
                $password1.focus();
                return false;
            }else if($password1.val().length<6){
                $.notify('The password must have 6 letters or more  and contain  number');
                $password.focus();
                return false;
            }else if(!((lower||upper)&&digit)){
                $.notify('The password must contain  number');
                $password.focus();
                return false;
            }
            return true;

        };
        plugin.uncapitalize=function(str) {
            return str.substring(0, 1).toLowerCase() + str.substring(1);
        };
        plugin.init();

    };

    // add the plugin to the jQuery.fn object
    $.fn.view_order = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_order')) {
                var plugin = new $.view_order(this, options);

                 $(this).data('view_order', plugin);

            }

        });

    }

})(jQuery);
