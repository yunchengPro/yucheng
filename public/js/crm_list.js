$(function(){
	/*
    if (typeof(parent.set_tab_title) == "function"){
        parent.set_tab_title(TAB_TITLE);
    }
	*/
	
    /*
	$("tbody tr").hover(
	   function(){$(this).addClass("hover");},
	   function(){$(this).removeClass("hover");}
	);
	*/
 //    $(document).delegate("#cbx-sel-all","click",function(){
	//     var checked = $(this).attr("checked")?$(this).attr("checked"):false;
	//     $(".table_body input[type=checkbox]").attr("checked",checked);
	// });

	$("#btn-delete").click(function(){

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
			var showmsg = "确定要删除？";
		}
		if (funlang=='en_US'){
			var showmsg = "Are you sure you want to delete?";
		}
		if(!confirm(showmsg)){
			return false;
		}


        do_ajax_request("/message/delete");
	});
	
	$("#btn-delete_aliexpress").click(function(){

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
			var showmsg = "确定要删除？";
		}
		if (funlang=='en_US'){
			var showmsg = "Are you sure you want to delete?";
		}
		if(!confirm(showmsg)){
			return false;
		}


        do_ajax_request("/aliexpress/message/delete");
	});

	$("#btn-getresponsible").click(function(){
        do_ajax_request("/message/getresponsible");
	});
	$("#btn-getemailresponsible").click(function(){
        do_ajax_request("/email/email/getresponsible");
	});


	$("#btn-search").click(function(e){
	    /*
        if ($("#keyword").val().trim().length == 0){
            new top.YSL.Tip("请输入搜索关键词",2);//2为出错提示
            e.preventDefault();
	        return false;
        }
        */
        return true;
	});
    $("a.selection_action").click(function(e){
        do_ajax_request($(this).attr("href"));
        if ($(this).attr("_update_count")){
            if (typeof(parent.update_count) == "function"){
                parent.update_count();
    	    }
        }
        e.preventDefault();
        return false;
    });


    $("#btn_add").unbind("click");
	$("#btn_add").click(function(e){	
		var target  = e.target || e.srcElement;
		var btn_add = $(target).closest('#btn_add');
		var _url = btn_add.attr("_url");
		layer.open({
			type: 2,
			title: '新增记录',
			maxmin: true,
			shadeClose: false, //点击遮罩关闭层
			area : ['600px' , '420px'],
			content: _url+"?popurl="+encodeURI(_url),
		});

	});

	$(".row_edit").unbind("click");
	$(".row_edit").click(function(e){	
		$(this).unbind("click");
		var target  = e.target || e.srcElement;
		var row_edit = $(target).closest('.row_edit');
		var _url = row_edit.attr("_url");
		layer.open({
			type: 2,
			title: '修改记录',
			maxmin: true,
			shadeClose: false, //点击遮罩关闭层
			area : ['600px' , '420px'],
			content: _url+"?popurl="+encodeURI(_url),
		});

	});


	$(".row_del").click(function(e){	
		$(this).unbind("click");
		var target  = e.target || e.srcElement;
		var row_del = $(target).closest('.row_del');
		var _url = row_del.attr("_url");
		var _confirm = row_del.attr("_confirm");
		layer.open({
			type: 2,
			title: '删除确认',
			maxmin: false,
			shadeClose: false, //点击遮罩关闭层
			area : ['350px' , '150px'],
			content: '/confirm?url='+encodeURI(_url)+'&confirm='+encodeURI(_confirm),
		});
	});




	//排序
	$('.data_sort').bind('keypress',function(event){
        if(event.keyCode == "13"){
            if ($(this).attr("_url")== undefined){
				new top.YSL.Tip('找不到_url',2);
				return false;
			}else{
				var url = $(this).attr("_url");
				url+="/id/"+$(this).attr("_id")+"/"+$(this).attr("name")+"/"+$(this).val();
			}
			if ($(this).attr("_title")== undefined){
				var showmsg = "是否确认排序?";
			}else{
				var showmsg = $(this).attr("_title");
			}

			layer.confirm(showmsg,{icon: 3, title:'操作确认'}, function(index){
				$.ajax({
					type: "GET",
					async: false,
					dataType: "json",
					url: url,
					success: function(data){
						new top.YSL.Tip(data.msg,(data.result == 'success')?1:2);//2为出错提示				
						if (data.result != 'success'){
							return ;
						}

						if (data.extra_js_confirm){
							eval(data.extra_js_confirm);
						}	else	{
							if (window.listTable && window.listTable.is_searched()){					
								listTable.search();
							}	else	{						
								window.location.reload();
							}
						}

					}
				});
				layer.close(index);
			});
			return false;
        }
    });
	
	//批量排序
	$("*[_rel=data_sort]").on("click",function(){

			if ($(this).attr("_url")== undefined){
				new top.YSL.Tip('找不到_url',2);
				return false;
			}else{
				var url = $(this).attr("_url");
			}
			if ($(this).attr("_title")== undefined){
				var showmsg = "是否确认排序?";
			}else{
				var showmsg = $(this).attr("_title");
			}
			var id = '';
			$(".data_sort").each(function(){
				id+=$(this).attr("_id")+",";
			});
			if(id.substr(-1)==',') id = id.substr(0,id.length-1);

			var value = '';
			$(".data_sort").each(function(){
				value+=$(this).val()+",";
			});
			if(value.substr(-1)==',') value = value.substr(0,value.length-1);
			
			data="id="+id+"&"+$(this).attr("name")+"="+value;
			//批量勾选操作
			layer.confirm(showmsg,{icon: 3, title:'操作确认'}, function(index){
				$.ajax({
					type: "POST",
					async: false,
					dataType: "json",
					url: url,
					data: data,
					success: function(data){
						new top.YSL.Tip(data.msg,(data.result == 'success')?1:2);//2为出错提示				
						if (data.result != 'success'){
							return ;
						}									
						if (data.extra_js_confirm){
							eval(data.extra_js_confirm);
						}	else	{
							if (window.listTable && window.listTable.is_searched()){					
								listTable.search();
							}	else	{						
								window.location.reload();
							}
						}

					}
				});
				
			layer.close(index);
		});
		return false;
	});

	$("*[_rel=edit_data]").unbind("click");
	//批量修改
	$("*[_rel=edit_data]").on("click",function(){
			//$(this).unbind("click");
			if ($(this).attr("_url")== undefined){
				new top.YSL.Tip('找不到_url',2);
				return false;
			}else{
				var url = $(this).attr("_url");
			}
			if ($(this).attr("_title")== undefined){
				var showmsg = "是否确认要修改数据?";
			}else{
				var showmsg = $(this).attr("_title");
			}
			var id = '';
			$(".edit_data").each(function(){
				id+=$(this).attr("_id")+",";
			});
			if(id.substr(-1)==',') id = id.substr(0,id.length-1);

			var value = '';
			$(".edit_data").each(function(){
				value+=$(this).val()+",";
			});
			if(value.substr(-1)==',') value = value.substr(0,value.length-1);
			
			data="id="+id+"&"+$(this).attr("name")+"="+value;
			//批量勾选操作
			layer.confirm(showmsg,{icon: 3, title:'操作确认'}, function(index){
				$.ajax({
					type: "POST",
					async: false,
					dataType: "json",
					url: url,
					data: data,
					success: function(data){
						new top.YSL.Tip(data.msg,(data.result == 'success')?1:2);//2为出错提示				
						if (data.result != 'success'){
							return ;
						}									
						if (data.extra_js_confirm){
							eval(data.extra_js_confirm);
						}	else	{
							if (window.listTable && window.listTable.is_searched()){					
								listTable.search();
							}	else	{						
								window.location.reload();
							}
						}

					}
				});
				
			layer.close(index);
		});
		return false;
	});




	var get_selected_items = function(){
        var selected = $("#mt-20 .table_body tbody input[type=checkbox]:checked");
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
	var do_ajax_request = function(url){
        var ids = get_selected_items();
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
					if (window.listTable && window.listTable.is_searched()){
						listTable.search();
					}	else	{
						window.location.reload();
					}
				}
			}
		});
	}
});


var do_ajax_request_selected = function(url){
	var ids = get_selected_items();
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

			if (data.result != 'success'){
				return ;
			}									
			if (data.extra_js){
				eval(data.extra_js);
			}	else	{
				if (window.listTable && window.listTable.is_searched()){					
					listTable.search();
				}	else	{						
					window.location.reload();
				}
			}
		}
	});
	
}


var get_selected_items = function(){
    var selected = $("#mt-20 .table tbody input[type=checkbox]:checked");
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
    }else{
		//new top.YSL.Tip("发送成功",1);
		var result = [];
		selected.each(function(i,n){
			result.push($(n).val());
		});
//    alert(result); false;
		return result;
    }
}

$("*[_rel=popup]").unbind("click");
$("*[_rel=popup]").click(function(){
		//$(this).unbind("click");
		if ($(this).attr("_url")== undefined){
			new top.YSL.Tip('找不到_url',2);
			return false;
		}else{
			var url = $(this).attr("_url");
		}

		if ($(this).attr("_title")== undefined){
			var title = "操作信息";
		}else{
			var title = $(this).attr("_title");
		}
		if ($(this).attr("_width")== undefined){
			var width = "600px";
		}else{
			var width = $(this).attr("_width")+"px";
		}
		if ($(this).attr("_height")== undefined){
			var height = "420px";
		}else{
			var height = $(this).attr("_height")+"px";
		}
		//alert(2);
		layer.open({
				type: 2,
				title: title,
				maxmin: true,
				shadeClose: false, 
				area : [width, height],
				content: url,
		});
		
		return false;
	});

	$("*[_rel=confirm]").unbind();
	$("*[_rel=confirm]").on("click",function(){
			//$(this).unbind();

			if ($(this).attr("_url")== undefined){
				new top.YSL.Tip('找不到_url',2);
				return false;
			}else{
				var url = $(this).attr("_url");
			}
			if ($(this).attr("_title")== undefined){
				var showmsg = "确定执行该操作?";
				return false;
			}else{
				var showmsg = $(this).attr("_title");
			}

			//批量勾选操作
			var batch = $(this).attr("_batch"); //_batch='true'

			layer.confirm(showmsg,{icon: 3, title:'操作确认'}, function(index){
				if(typeof batch!='undefined' && batch!=''){
					do_ajax_request_selected(url);
				}else{
					
					$.ajax({
						type: "GET",
						async: false,
						dataType: "json",
						url: url,
						success: function(data){
							new top.YSL.Tip(data.msg,(data.result == 'success')?1:2);//2为出错提示				
							if (data.result != 'success'){
								return ;
							}				
							if (data.extra_js_confirm){
								eval(data.extra_js_confirm);
							}	else	{
								if (window.listTable && window.listTable.is_searched()){					
									listTable.search();
								}	else	{						
									window.location.reload();
								}
							}

						}
					});
				}
			layer.close(index);
		});
		return false;
	});

	$("*[_rel=confirm_all]").unbind();
	$("*[_rel=confirm_all]").on("click",function(){
			//$(this).unbind();
			var ids = get_selected_items();
			if(ids == false){
				return false;
			}
			if ($(this).attr("_url")== undefined){
				new top.YSL.Tip('找不到_url',2);
				return false;
			}else{
				var url = $(this).attr("_url") + "&ids=" + ids;
			}
			if ($(this).attr("_title")== undefined){
				var showmsg = "确定执行该操作?";
				return false;
			}else{
				var showmsg = $(this).attr("_title");
			}

			//批量勾选操作
			var batch = $(this).attr("_batch"); //_batch='true'

			layer.confirm(showmsg,{icon: 3, title:'操作确认'}, function(index){
				if(typeof batch!='undefined' && batch!=''){
					do_ajax_request_selected(url);
				}else{
					
					$.ajax({
						type: "GET",
						async: false,
						dataType: "json",
						url: url,
						success: function(data){
							new top.YSL.Tip(data.msg,(data.result == 'success')?1:2);//2为出错提示				
							if (data.result != 'success'){
								return ;
							}				
							if (data.extra_js_confirm){
								eval(data.extra_js_confirm);
							}	else	{
								if (window.listTable && window.listTable.is_searched()){					
									listTable.search();
								}	else	{						
									window.location.reload();
								}
							}

						}
					});
				}
			layer.close(index);
		});
		return false;
	});

	$("*[_rel=popup_listpage]").unbind("click");
	$("*[_rel=popup_listpage]").on("click",function(){
		//alert(1);
		//$(this).unbind("click");
		if ($(this).attr("_url")== undefined){
			new top.YSL.Tip('找不到_url',2);
			return false;
		}else{
			var url = $(this).attr("_url");
		}

		if ($(this).attr("_title")== undefined){
			var title = "操作信息";
		}else{
			var title = $(this).attr("_title");
		}
		if ($(this).attr("_width")== undefined){
			var width = "600px";
		}else{
			var width = $(this).attr("_width")+"px";
		}
		if ($(this).attr("_height")== undefined){
			var height = "420px";
		}else{
			var height = $(this).attr("_height")+"px";
		}

		layer.open({
				type: 2,
				title: title,
				maxmin: true,
				shadeClose: false, 
				area : [width, height],
				content: url,
		});
		
		return false;
	});


	$("*[_rel=newpage]").on("click",function(){
		//$(this).unbind("click");
		if($(this).attr('_url')){
			var bStop=false;
			var bStopIndex=0;
			var _href=$(this).attr('_url');
			var _titleName=$(this).attr("_title");
			var topWindow=$(window.parent.document);
			var show_navLi=topWindow.find("#min_title_list li");
			show_navLi.each(function() {
				if($(this).find('span').attr("data-href")==_href){
					bStop=true;
					bStopIndex=show_navLi.index($(this));
					return false;
				}
			});
			if(!bStop){
				creatIframe(_href,_titleName);
				min_titleList();
			}
			else{
				show_navLi.removeClass("active").eq(bStopIndex).addClass("active");
				var iframe_box=topWindow.find("#iframe_box");
				iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src",_href);
			}
		}
	});

	$('.dropdown-toggle').click(function(e) {
	   e.stopPropagation();
	   $('.button-group,.drop').removeClass("open");
	   $(this).closest('.button-group, .drop').addClass("open");
	   $('.drop-menu').mouseout(function(){
	   		$('.button-group,.drop').removeClass("open");
	   });
	   $('.drop-menu').mouseover(function(){
	   		$(this).parent().addClass("open");
	   });
	   /*
	   $(document).click(function(){
			$('.button-group,.drop').removeClass("open");
       });
		*/
	});