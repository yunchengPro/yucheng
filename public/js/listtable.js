var listTable = new Object;
//排序初始值
listTable.sort_by = '';
listTable.sort_order = 'ASC';
listTable.pageSize = listTable.pageSize?listTable.pageSize:(PAGE_SIZE)?PAGE_SIZE:20;
listTable.need_transform = 0;
/**
 * ajax加载成功显示内容
 */
listTable.bingo = function(response){

    //$("#footer_wrap").remove();
    table_content = response;
   
    var divs = response['showdiv'];
    //alert(table_content);
    if (divs) {
    	$("#"+divs).html(table_content);//这里替换了列表内容
    } else {
    	$("#mt-20").html(table_content);//这里替换了列表内容
    }
    listTable.hideLoading();
    //$("#load-div").hide();
    if (listTable.need_transform){
        listTable.transformGrid();
    }
    listTable.syncColWidth();
    listTable.resizeGridHeight();
};

listTable.edit = function(obj, posturl, id) {
  var tag = obj.firstChild.tagName;
  if (typeof(tag) != "undefined" && tag.toLowerCase() == "input") {
    return;
  }
  var isIE = window.ActiveXObject ? true : false;
  /* 保存原始的内容 */
  var org = obj.innerHTML;
  var val = isIE ? obj.innerText : obj.textContent;


  /* 创建一个输入框 */
  var txt = document.createElement("INPUT");
  txt.value = (val == 'N/A') ? '' : val;
  txt.style.width = (obj.offsetWidth + 12) + "px" ;

  /* 隐藏对象中的内容，并将输入框加入到对象中 */
  obj.innerHTML = "";
  obj.appendChild(txt);
  txt.focus();

  /* 编辑区输入事件处理函数 */
  txt.onkeypress = function(e) {
    var evt = Utils.fixEvent(e);
    var obj = Utils.srcElement(e);
    if (evt.keyCode == 13) {
      obj.blur();
      return false;
    }
    if (evt.keyCode == 27){
      obj.parentNode.innerHTML = org;
    }
  }

  /* 编辑区失去焦点的处理函数 */
  txt.onblur = function(e) {
    if (Utils.trim(txt.value).length > 0) {
    	$.ajax({
			type: "POST",
			url: posturl,
			data: "val=" + encodeURIComponent(Utils.trim(txt.value)) + "&id=" + id,
			success: function(data){
				if (data == "success") {
					obj.innerHTML = Utils.trim(txt.value);
				}  else {
					alert(data);
					obj.innerHTML = org;
				}
			}
		});
    } else {
    	obj.innerHTML = org;
    }
  }
}

/**
 * 过滤条件
 */
listTable.filterData = function(fromId){
    var data = '';
    if (fromId) {
    	searchForm = fromId;
    } else {
    	searchForm = 'searcherForm';
    }
    $("#"+ searchForm +" :text").each(function() {
        key = $(this).attr('name');
		value = $(this).val();
		if(key!=''&&key!=null && value!=''){
	         data += key+'='+value+'&';
		}
    });
    $("#"+ searchForm +" select").each(function() {
        key = $(this).attr('name');
		value = $(this).val();
		if(key!=''&&key!=null && value!=''){
            data +=key+'='+value+'&';
		}
    });
    $("#"+ searchForm +" :hidden").each(function() {
        key = $(this).attr('name');
        value = $(this).val();
        if(key!=''&&key!=null && value!=''){
            data +=key+'='+value+'&';
        }
    });
    var ids = '';
    $("input:checked[name='ids[]']").each(function() {
        key = $(this).attr('name');
        value = $(this).val();
        if(key!=''&&key!=null && value!=''){
            ids +=value+',';
        }
    });
    if(ids!=''){
        data+='ids='+ids.substr(0,ids.length-1)+'&';
    }


    if(this.sort_by!=''){
    	data += 'sort_by='+this.sort_by+'&sort_order='+this.sort_order+'&';
    }
    var searchtypename = $("#searchtypename").val();
    if (searchtypename!=''&& searchtypename!= undefined){
    	data +='searchtypename='+searchtypename+'&';
    }
    data +='pageSize='+this.pageSize+'&';
    return data;
};


/**
 * ajax请求
 */
listTable.ajax_page = function(ajax_url,data,callback){
   
	listTable.showLoading();
    $.ajax({
      type:"POST",//以post的方式
      url:ajax_url,//请求的路径
      dataType:"html",
	  data:data,
      success: function(response){//成功的话执行bingo函数
          listTable.bingo(response);
      },
      complete: callback
   });
};
listTable.showLoading = function(){
	if ($("#pageloading")){
		$("#btn_search").after("<span id='pageloading' class='prepageloading'><img src='/pinture/images/pageloading.gif'  /></span>");
	}
	if ($("#pageloadingadvand")){
		$("#btn_search_advanced").after("<span id='pageloadingadvand' class='prepageloading'><img src='/pinture/images/pageloading.gif'  /></span>");
	}
	$("#pageloading").show();
	$("#pageloadingadvand").show();

}
listTable.hideLoading = function(){
	$("#pageloading").hide();
	$("#pageloadingadvand").hide();
}
/**
 * 分页点击事件
 */
listTable.pageClick = function(page_url){
   
    //$("#footer_wrap").remove();
	data = this.filterData();
    ajax_url = page_url;//获取当前页面url
    this.ajax_page(ajax_url,data);   //ajax事件
    var scroll_offset = $("#mt-20").offset(); //得到pos这个div层的offset，包含两个值，top和left
    $("body,html").animate({
            scrollTop:scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动
    },0); 
    return false;
};
/**
 * 分页选择事件
 */
listTable.pageChange = function(page_url){
	data = this.filterData();
    ajax_url = page_url;//获取当前页面url
    this.ajax_page(ajax_url,data);   //ajax事件
    return false;
};
/**
 * 检索
 */
listTable.search = function(fromId,callback){
	data = this.filterData(fromId);
    ajax_url = document.URL.split("?")[0];
    this.ajax_page(ajax_url,data,callback);
};



/**
 * 导出
 */

listTable.exportData = function(fromId,callback){
	data = this.filterData(fromId);
	var exporturl = $("#btn-export").attr("_url");
    window.open(exporturl+"?"+data);
};

/**
 * 判断是否有搜索过,使用参数个数来判断
 */
listTable.is_searched = function(fromId){
	data = this.filterData(fromId);
	param = data.split("&");
	return param.length>2;
}
/**
 * 排序
 */
listTable.sort = function(sort_by,sort_order){
	this.sort_by = sort_by;
    /*
	if (this.sort_by == sort_by){
		this.sort_order = this.sort_order == "DESC" ? "ASC" : "DESC";
    } else {
    	this.sort_order = "DESC";
    }
    */
    this.sort_order = sort_order == "DESC" ? "ASC" : "DESC";
    //alert(this.sort_order);
    data = this.filterData();
    ajax_url = document.URL.split("?")[0];
    this.ajax_page(ajax_url,data);
};

/**
 * 改变每页显示的记录条数
 */
listTable.changePageSize = function(event){
	var evt = (typeof event == "undefined") ? window.event : event;
    if(evt.keyCode == 13){
        pagesize = $("#pageSize").val()?$("#pageSize").val():listTable.pageSize;
    	this.pageSize = pagesize;
    	data = this.filterData();
    	ajax_url = document.URL.split("?")[0];
    	this.ajax_page(ajax_url,data);
    	return false;
    }
};

/**
 * 跳到第几页
 */
listTable.changePage = function(event,url){

	var evt = (typeof event == "undefined") ? window.event : event;
    if(evt.keyCode == 13){
        page = $("#page").val()?$("#page").val():1;
        var re = /^[1-9]+[0-9]*]*$/;   //判断正整数  
        if (!re.test(page)){
            alert("请输入数字");
            $("#page").val("");
            $("#page").focus();
            return false;
        }
		listTable.pageClick(url+page);
    	return true;
    }
};





listTable.transformGrid = function(){
    var grid = $('#mt-20');
    var header_wrap = $('<div />').addClass("header_wrap");
    var table_header = $('<table cellspacing="0" cellpadding="0" class="table table-bordered" id="list-table">');
    var thead = $("#mt-20 table:first thead");
    var table_body = $("#mt-20 table:first");
  
    //thead.appendTo(table_header);
    // table_header.appendTo(header_wrap);
    grid.prepend(header_wrap);
    table_body.addClass("table_body");
    table_body.wrap("<div class='body_wrap'></div>");
    $(".body_wrap").appendTo(table_header);
    //alert(1);
    //$(".footer_wrap").remove();
    //alert(2);
}

listTable.initGrid = function(need_transform){
    if (need_transform){
        listTable.need_transform = 1;
        listTable.transformGrid();
    }
    listTable.syncColWidth();
    listTable.resizeGridHeight();
    $(window).bind("resize",listTable.resizeGridHeight);
}

listTable.resizeGridHeight = function(){
    var grid = $('#mt-20');
	
	var totalHeight = $(window).height() - grid.outerHeight() + grid.height()-8;

	var height = totalHeight - grid.offset().top;
    var fix_height = height - $(".header_wrap").height() - $(".footer_wrap").height();
    var body_height = $(".table_body:first").height();

    $(".body_wrap").height(fix_height);

    if (body_height > fix_height){
        $(".header_wrap").css("margin-right","17px");
    }   else    {
        $(".header_wrap").css("margin-right","0");
    }
	
}
listTable.syncColWidth = function(){
    var headers = $("#mt-20 .table_header:first th");
    var columns = $("#mt-20 .table_body:first tr:first td");
    if (!headers || !columns){
        return ;
    }
    headers.each(function(i,n){
        $(headers[i]).addClass("col_"+(i+1));
        $(columns[i]).addClass("col_"+(i+1));
    });
    return ;
    /*
    if (headers.length != columns.length){
        return ;
    }
    headers.each(function(i,n){
        var m = $(columns[i]);
        if ($(n).css("min-width") != "0px" && $(n).css("min-width") != "auto"){
            m.css("min-width",$(n).css("min-width"));
        }   else    {
            m.css("width",$(n).css("width"));
        }
    })
    return ;
    */
}

