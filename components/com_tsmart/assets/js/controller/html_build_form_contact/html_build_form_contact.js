//huong dan su dung
/*
 $('.html_build_form_contact').html_build_form_contact();

 html_build_form_contact=$('.html_build_form_contact').data('html_build_form_contact');
 console.log(html_build_form_contact);
 */

// jQuery Plugin for SprFlat admin html_build_form_contact
// Control options and basic function of html_build_form_contact
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com
(function ($) {

    // here we go!
    $.html_build_form_contact = function (element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for html_build_form_contact
            //be sure to be same as colors on main.css or custom-variables.less
        }
        // current instance of the object
        var plugin = this;
        // this will hold the merged default, and user-provided options
        plugin.settings = {}
        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
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
        plugin.get_data = function () {
            return $element.find('input').serializeObject();
        }
        plugin.validate = function () {
            var $contact_name = $element.find('input[name="contact_name"]');
            var $phone_number = $element.find('input[name="phone_number"]');
            var $email_address = $element.find('input[name="email_address"]');
            var $confirm_email = $element.find('input[name="confirm_email"]');
            var $street_address = $element.find('input[name="street_address"]');
            var $suburb_town = $element.find('input[name="suburb_town"]');
            var $state_province = $element.find('input[name="state_province"]');
            var $post_code_zip = $element.find('input[name="post_code_zip"]');
            var $res_country = $element.find('input[name="res_country"]');
            var $emergency_contact_name = $element.find('input[name="emergency_contact_name"]');
            var $emergency_email_address = $element.find('input[name="emergency_email_address"]');
            var $emergency_phone_number = $element.find('input[name="emergency_phone_number"]');
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if ($contact_name.val() == '') {
                plugin.notify('please input contact name');
                $contact_name.focus();
                return false;
            } else if ($phone_number.val() == '') {
                plugin.notify('please input phone number');
                $phone_number.focus();
                return false;
            } else if ($email_address.val() == '') {
                plugin.notify('please input email address');
                $email_address.focus();
                return false;
            }
            else if(!regex.test($email_address.val())){
                plugin.notify('email address incorrect');
                $email_address.focus();
                return false;
            } else if ($confirm_email.val().trim() == '') {
                plugin.notify('please input confirm email');
                $confirm_email.focus();
                return false;
            }else if(!regex.test($confirm_email.val())) {
                plugin.notify('confirm email address incorrect');
                $confirm_email.focus();
                return false;
            }else if($email_address.val().trim()!=$confirm_email.val().trim()){
                plugin.notify('confirm email address dont same email address');
                $confirm_email.focus();
                return false;
            } else if ($street_address.val() == '') {
                plugin.notify('please input street address');
                $street_address.focus();
                return false;
            } else if ($suburb_town.val() == '') {
                plugin.notify('please input suburb town');
                $suburb_town.focus();
                return false;
            } else if ($state_province.val() == '') {
                plugin.notify('please input state province');
                $state_province.focus();
                return false;
            } else if ($post_code_zip.val() == '') {
                plugin.notify('please select input code zip');
                $post_code_zip.focus();
                return false;
            } else if ($res_country.val() == '') {
                plugin.notify('please select input country');
                $res_country.focus();
                return false;
            } else if ($emergency_contact_name.val() == '') {
                plugin.notify('please input emergency contact name');
                $emergency_contact_name.focus();
                return false;
            } else if ($emergency_email_address.val() == '') {
                plugin.notify('please input  emergency email address');
                $emergency_email_address.focus();
                return false;
            } else if ($emergency_phone_number.val() == '') {
                plugin.notify('please input emergency phone number');
                $emergency_phone_number.focus();
                return false;
            }
            return true;
        }
        plugin.example_function = function () {
        }
        plugin.init();
    }
    // add the plugin to the jQuery.fn object
    $.fn.html_build_form_contact = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_build_form_contact')) {
                var plugin = new $.html_build_form_contact(this, options);
                $(this).data('html_build_form_contact', plugin);
            }
        });
    }
})(jQuery);
