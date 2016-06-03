(function($){
	$.tree_object= function(item,object_list,key_path){
		if(typeof object_list=='undefined'){
			var object_list={};
		}

		if(!$.isEmptyObject(item))
		{

			$.each(item,function(key,value){
				if(typeof key_path!=='undefined'){
					var key_path1=key_path+'.'+key;
				}else{
					var key_path1=key;
				}
				if(typeof value!=='object')
				{
					object_list[key_path1]=value
				}else if(!$.isEmptyObject(value))
				{
					$.tree_object(value,object_list,key_path1);
				}
			});
		}
		return object_list;
	};
	$.set_date_selected=function(data,key,value,select_element,selected){
		$(select_element).empty();
		$.each(data,function(index,item){
			$('<option '+(item[key]==selected?'selected':'')+' value="'+item[key]+'">'+item[value]+'</option>').appendTo($(select_element));
		});
	};
	$.set_html_for_tag=function(response)
	{
		var styleSheets=response._styleSheets;
		$.each(styleSheets,function(href,item){
			var $link=$( 'link[href~="'+href+'"]' );
			if($link.length==0)
			{
				$link=$('<link rel="'+item.mime+'" href="'+href+'">');
				$link.appendTo('head');
			}
		});
		var scripts=response._scripts;
		$.each(scripts,function(src,item){
			var $script=$( 'script[src~="'+src+'"]' );
			if($script.length==0)
			{
				$script=$('<script type="'+item.mime+'" src="'+src+'"></script>');
				$script.appendTo('head');
			}
		});
		$.each(response._script,function(type,content){
			var $script=$('<script type="'+type+'">'+content+'</script>');
			$script.appendTo('head');
		});
	}
    $.fn.getOuterHTML = function() {
        var wrapper = $('<div class="getOuterHTML"></div>');
        $(this).wrap(wrapper);
        var html=$(this).parent().html();
        $(this).unwrap();
        return html;
    };
    function _calculateAge(birthday) { // birthday is a date

    }
    function getAge(dateString) {

    }

    $.get_year_old_by_date = function(dateString) {
        if(dateString=='')
        {
            return '';
        }
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    };
    $.randomDate=function(start, end) {
        return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
    }
})(jQuery);