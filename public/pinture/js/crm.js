(function($){
    var Y = {};
    Y.get_selected_items = function(){
        var selected = $("#listDiv .table_body tbody input[type=checkbox]:checked");
	    if (selected.length == 0){

			var funlang='';
			var strCookie=document.cookie;
			var arrCookie=strCookie.split("; ");
			for(var langi=0;langi<arrCookie.length;langi++){
				var langarr=arrCookie[langi].split("=");
				if("edcrmlang"==langarr[0]){
					funlang=langarr[1];
					break;
				} 
			}
			if (funlang=='' || funlang=='zh_CN'){
			   new top.YSL.Tip("还没选中任何项目",2);//2为出错提示
			}
			if (funlang=='en_US'){
			   new top.YSL.Tip("No rows selected",2);//2为出错提示
			}	

	        return false;
	    }
	    var result = [];
        selected.each(function(i,n){
            result.push($(n).val());
        });
        return result;
	}
	Y.do_ajax_request = function(url,callback){
		callback = callback || function(){
			if (window.listTable && window.listTable.is_searched()){
				listTable.search();
			}	else	{
				window.location.reload();
			}
		};		
        var ids = Y.get_selected_items();
	    if (!ids){
	        return false;
	    }
        $.ajax({
			type: "GET",
			async: false,
			dataType: "json",
			url: url,
			data: "ids="+ids.join(","),
			success: function(data){
				new top.YSL.Tip(data.msg,(data.result == 'success')?1:2);//2为出错提示
				if (data.result == 'success'){
					callback();
				}
			}
		});
	}
	Y.window_resize = function(){
        //$("#tab_ctn").height(Y.get_tabiframe_height());
        /*$(".ctn-iframe").height(Y.get_tabiframe_height());*/
	}
	Y.get_tabiframe_height = function(){
	   // return $(window).height()-60;
	}


	Y.window_resize_sub = function(){
        //$("#tab_ctn").height(Y.get_tabiframe_height_sub());
	}

	Y.get_tabiframe_height_sub = function(){
	   // return $(window).height()-40;
	}




    window.CRM = Y;
})(jQuery);