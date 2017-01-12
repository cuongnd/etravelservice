(function ($) {

    // here we go!
    $.html_generate_code = function (element, options) {

        // plugin's default options
        var defaults = {
            state_element:'',
            controller:'',
            task:'',
            read_only:false
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var controller= plugin.settings.controller;
            var task= plugin.settings.task;
            var read_only= plugin.settings.read_only;
            if(!read_only)
            {
                $element.find('.generate_code').click(function(){
                    $.ajax({
                        type: "GET",
                        url: 'index.php',
                        dataType: "json",
                        data: (function () {

                            dataPost = {
                                option: 'com_tsmart',
                                controller: controller,
                                task: task
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
                            $element.find('input.code').val(response.code);
                        }
                    });

                });
            }



        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_generate_code = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_generate_code')) {
                var plugin = new $.html_generate_code(this, options);
                $(this).data('html_generate_code', plugin);

            }

        });

    }

})(jQuery);


