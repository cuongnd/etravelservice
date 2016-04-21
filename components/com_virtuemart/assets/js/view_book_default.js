//huong dan su dung
/*
 $('.view_book_default').view_book_default();

 view_book_default=$('.view_book_default').data('view_book_default');
 console.log(view_book_default);
 */

// jQuery Plugin for SprFlat admin view_book_default
// Control options and basic function of view_book_default
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function($) {

    // here we go!
    $.view_book_default = function(element, options) {

        // plugin's default options
        var defaults = {
            //main color scheme for view_book_default
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
            $element.find("#tabbed-nav").zozoTabs({
                theme: "silver",
                orientation: "horizontal",
                position: "top-left",
                size: "medium",
                animation: {
                    easing: "easeInOutExpo",
                    duration: 400,
                    effects: "slideH"
                },
                defaultTab: "tab1"
            });

            $element.find('.table-trip .body .item  .body-item').on('hidden', function () {
                // do something…
                var $item=$(this).closest('.item');
                $item.removeClass('in');
                $item.find('.header-item > .service-class-price').addClass('hide');
                $item.find('.header-item > .service-class,.header-item > .price').removeClass('hide');
                console.log($item);
            });
            $element.find('.table-trip .body .item  .body-item').on('show', function () {
                // do something…
                var $item=$(this).closest('.item');
                $item.addClass('in');
                $item.find('.header-item > .service-class-price').removeClass('hide');
                $item.find('.header-item > .service-class,.header-item > .price').addClass('hide');
                console.log($item);
            });
            $element.find('span.price').autoNumeric('init');
            $element.find('button.book-now').click(function(){
                var $item=$(this).closest('.item');
                var virtuemart_price_id=$item.data('virtuemart_price_id');
                var $form=$element.find('form#tour_price');
                $form.find('input[name="virtuemart_price_id"]').val(virtuemart_price_id);
                $form.find('input[name="task"]').val('book_now');
                $form.submit();
                console.log($form);
            });
        };

        plugin.example_function = function() {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.view_book_default = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('view_book_default')) {
                var plugin = new $.view_book_default(this, options);

                $(this).data('view_book_default', plugin);

            }

        });

    }

})(jQuery);
