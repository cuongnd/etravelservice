//huong dan su dung
/*
 $('.html_edit_passenger_in_order').html_edit_passenger_in_order();

 html_edit_passenger_in_order=$('.html_edit_passenger_in_order').data('html_edit_passenger_in_order');
 console.log(html_edit_passenger_in_order);
 */

// jQuery Plugin for SprFlat admin html_edit_passenger_in_order
// Control options and basic function of html_edit_passenger_in_order
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.html_edit_passenger_in_order = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for html_edit_passenger_in_order
            //be sure to be same as colors on main.css or custom-variables.less
            list_passenger:[],
            order:{}

        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.update_modal = function (passenger) {
            var $edit_passenger=plugin.settings.edit_passenger;
            $edit_passenger.find('span.full-name').html(passenger.first_name+' '+passenger.last_name);
            $edit_passenger.find('input[name="title"]').val(typeof passenger.title!="undefined"?passenger.title:'');
            $edit_passenger.find('input[name="first_name"]').val(typeof passenger.first_name!="undefined"?passenger.first_name:'');
            $edit_passenger.find('input[name="last_name"]').val(typeof passenger.last_name!="undefined"?passenger.last_name:'');
            $edit_passenger.find('input[name="gender"]').val(typeof passenger.gender!="undefined"?passenger.gender:'');
            $edit_passenger.find('input[name="date_of_birth"]').val(typeof passenger.date_of_birth!="undefined"?passenger.date_of_birth:'');
            $edit_passenger.find('input[name="nationality"]').val(typeof passenger.nationality!="undefined"?passenger.nationality:'');
            $edit_passenger.find('input[name="passport_no"]').val(typeof passenger.passport_no!="undefined"?passenger.passport_no:'');
            $edit_passenger.find('input[name="p_issue_date"]').val(typeof passenger.p_issue_date!="undefined"?passenger.p_issue_date:'');
            $edit_passenger.find('input[name="p_expiry_date"]').val(typeof passenger.p_expiry_date!="undefined"?passenger.p_expiry_date:'');
            $edit_passenger.find('input[name="phone_no"]').val(typeof passenger.p_expiry_date!="undefined"?passenger.phone_no:'');
            $edit_passenger.find('input[name="res_country"]').val(typeof passenger.res_country!="undefined"?passenger.res_country:'');
            $edit_passenger.find('input[name="confirm_email"]').val(typeof passenger.confirm_email!="undefined"?passenger.confirm_email:'');
            $edit_passenger.find('input[name="street_address"]').val(typeof passenger.street_address!="undefined"?passenger.street_address:'');
            $edit_passenger.find('input[name="suburb_town"]').val(typeof passenger.suburb_town!="undefined"?passenger.suburb_town:'');
            $edit_passenger.find('input[name="state_province"]').val(typeof passenger.state_province!="undefined"?passenger.state_province:'');
            $edit_passenger.find('input[name="postcode_zip"]').val(typeof passenger.postcode_zip!="undefined"?passenger.postcode_zip:'');
            $edit_passenger.find('input[name="res_country"]').val(typeof passenger.res_country!="undefined"?passenger.res_country:'');
            $edit_passenger.find('input[name="emergency_contact_name"]').val(typeof passenger.emergency_contact_name!="undefined"?passenger.emergency_contact_name:'');
            $edit_passenger.find('input[name="emergency_contact_email_address"]').val(typeof passenger.emergency_contact_email_address!="undefined"?passenger.emergency_contact_email_address:'');
            $edit_passenger.find('input[name="emergency_contact_phone_no"]').val(typeof passenger.emergency_contact_phone_no!="undefined"?passenger.emergency_contact_phone_no:'');
            $edit_passenger.find('input[name="requirement"]').val(typeof passenger.requirement!="undefined"?passenger.requirement:'');
        };
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            plugin.settings.edit_passenger=$element.find('#edit_passenger');
            var $edit_passenger=plugin.settings.edit_passenger;
            $edit_passenger.appendTo('body');
            plugin.settings.editing_passenger_id=0;
            $element.find('.row-item button.edit').click(function(){
                var $row_item=$(this).closest('.row-item');
                plugin.settings.editing_passenger_id=$row_item.data('passenger_id');
                $edit_passenger.modal("show");
            });
            $edit_passenger.on('shown', function(){
                var list_passenger=plugin.settings.list_passenger;
                var passenger=list_passenger[plugin.settings.editing_passenger_id];
                plugin.update_modal(passenger);
            });
            $edit_passenger.find('.save-passenger').click(function(){
                var order=plugin.settings.order;
                var tsmart_order_id=order.tsmart_order_id;
                var data=$edit_passenger.find(':input').serializeObject();
                $.ajax({
                    type: "GET",
                    url: 'index.php',
                    dataType: "json",
                    data: (function () {

                        dataPost = {
                            option: 'com_tsmart',
                            controller: 'order',
                            task: 'ajax_save_passenger',
                            tsmart_order_id: tsmart_order_id,
                            passenger_id: plugin.settings.editing_passenger_id,
                            data:data
                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {

                        $('.div-loading').css({
                            display: "block"
                        });
                    },
                    success: function (response) {

                        $('.div-loading').css({
                            display: "none"


                        });
                        if(response==1){
                            alert('save ok');
                            var data1= plugin.settings.list_passenger[plugin.settings.editing_passenger_id];
                            data= $.extend(data1,data);
                            plugin.settings.list_passenger[plugin.settings.editing_passenger_id]=data;
                            $edit_passenger.modal('hide');

                        }

                    }
                });

            });
        }

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_edit_passenger_in_order = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_edit_passenger_in_order')) {
                var plugin = new $.html_edit_passenger_in_order(this, options);

                $(this).data('html_edit_passenger_in_order', plugin);

            }

        });

    }

})(jQuery);
