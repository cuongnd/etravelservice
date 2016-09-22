//huong dan su dung
/*
 $('.html_build_payment_cardit_card').html_build_payment_cardit_card();

 html_build_payment_cardit_card=$('.html_build_payment_cardit_card').data('html_build_payment_cardit_card');
 console.log(html_build_payment_cardit_card);
 */

// jQuery Plugin for SprFlat admin html_build_payment_cardit_card
// Control options and basic function of html_build_payment_cardit_card
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.html_build_payment_cardit_card = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for html_build_payment_cardit_card
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
        }
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

        plugin.validate=function(){
            var $payment_type=$element.find('input[name="payment_type"]:checked');
            var $card_type=$element.find('input[name="card_type"]:checked');
            var $card_number=$element.find('input[name="card_number"]');

            var $month=$element.find('select[name="month"]');
            var $year=$element.find('select[name="year"]');
            var $last_three_number=$element.find('input[name="last_three_number"]');
            var $card_holder_name=$element.find('input[name="card_holder_name"]');
            if($payment_type.val()==''){
                plugin.notify('please select payment type');
                $payment_type.focus();
                return false;
            }else if($card_type.val()==''){
                plugin.notify('please select card type');
                $card_type.focus();
                return false;
            }else if($card_number.val()==''){
                plugin.notify('please select card number');
                $card_number.focus();
                return false;
            }else if($month.val()==''){
                plugin.notify('please select month');
                $month.focus();
                return false;
            }else if($year.val()==''){
                plugin.notify('please select year');
                $year.focus();
                return false;
            }else if($last_three_number.val()==''){
                plugin.notify('please select last three number');
                $last_three_number.focus();
                return false;
            }else if($card_holder_name.val()==''){
                plugin.notify('please select card holder name');
                $card_holder_name.focus();
                return false;
            }
            return true;
        }
        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_build_payment_cardit_card = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_build_payment_cardit_card')) {
                var plugin = new $.html_build_payment_cardit_card(this, options);

                $(this).data('html_build_payment_cardit_card', plugin);

            }

        });

    }

})(jQuery);
