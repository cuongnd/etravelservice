(function ($) {

    // here we go!
    $.html_select_question = function (element, options) {

        // plugin's default options
        var defaults = {
            categoryfaq:[],
            list_tsmart_faq_id:[],
            category_question_element:""
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        plugin.set_select2_template_result = function (result) {
            return result.text;
        };
        plugin.set_lect2_template_selection = function (selection) {
            return selection.text;
        };
        plugin.set_list_question = function (tsmart_categoryfaq_id) {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: "json",
                data: (function () {

                    dataPost = {
                        option: 'com_tsmart',
                        controller: 'faq',
                        task: 'ajax_get_list_question_by_categoryfaq_id',
                        tsmart_categoryfaq_id:tsmart_categoryfaq_id
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
                    var item={};
                    item.tsmart_faq_id=0;
                    item.faq_name ='Select question';
                    response.unshift(item);
                    var tsmart_faq_id=$element.val();
                    $.set_data_selected(response,'tsmart_faq_id','faq_name',$element,tsmart_faq_id);
                    $element.trigger("liszt:updated");
                }
            });

        };

        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            var question=plugin.settings.categoryfaq;
            var list_tsmart_categoryfaq_id=plugin.settings.list_tsmart_faq_id;
            plugin.select2=$element.select2({
                data:question,
                templateResult:plugin.set_select2_template_result,
                templateSelection:plugin.set_lect2_template_selection
            });
            //plugin.select2.val(list_tsmart_activity_id).trigger("change")

            var category_question_element=plugin.settings.category_question_element;

            $(category_question_element).change(function(){
                var tsmart_categoryfaq_id=$(this).val();
                plugin.set_list_question(tsmart_categoryfaq_id);
            });
            var tsmart_categoryfaq_id=$(category_question_element).val();

            plugin.set_list_question(tsmart_categoryfaq_id);


        }

        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.html_select_question = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('html_select_question')) {
                var plugin = new $.html_select_question(this, options);
                $(this).data('html_select_question', plugin);

            }

        });

    }

})(jQuery);


