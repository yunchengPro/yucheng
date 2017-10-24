
var open_tab_url = function(url,title,cfg){
	
    var config = $.extend({},{
		'title': title,
		'do_switch': true,
		'callback': function(){}
	},cfg);

    //获取id 用来定义iframe的名字
    var tmp = new Array();
     tmp=url.split("/");
    var iname = tmp[tmp.length-1];
    var tid = "_t_" + YSL.md5(url);
    var cid = "_c_" + YSL.md5(url);
  
    if ($("#"+tid).length == 0){
  //       var tab = $("<li><a href=\"javascript:void(0)\" onclick='click_tab(\""+cid+"\")' title=\""+title+"\" class=\"tab_link\">"+title+"</a><a class='close_tab' href='javascript:void(0)' onclick='close_tab(\""+tid+"\")'></a></li>").attr("id",tid);
  //       var iframe = $("<iframe frameborder='0' frameborder='no' width='100%' scrolling='auto' vsspace='0' hspace='0' marginwidth='0' marginheight='0' class='ctn-iframe-top hide' name='"+cid+"'></iframe>");
		// iframe.attr("src",url).attr("id",cid);
		// $("#tab_ctrl").append(tab);
		// showPageLoading();
  //       $("#tab_ctn").append(iframe);
  //       init_tab();
		// document.getElementById(cid).onload = function() {
		// 	hiddenPageLoading();
		// };
		// $("#curiframe").val(cid);
		// $("#"+cid).removeClass("hide").siblings().addClass("hide");
    }

	document.getElementById(cid).onload = function() {
		hiddenPageLoading();
	};

    if (config.do_switch){
        $("#"+tid+" a:first").click();
    }

	iframe.focus();
}


var click_tab = function(cid){
	//alert('aaaaaaaa');
	//$(".box li").removeClass("current");
	$("#curiframe").val(cid);
}



var close_tab = function(tid){
    var switch_next = $("#"+tid).hasClass("current");
    var next = $("#"+tid).next().length ? $("#"+tid).next() : $("#"+tid).prev();
    $("#"+tid).remove();
    $("#"+tid.replace(/_t_/,"_c_")).remove();
    init_tab();
    if (switch_next == true){
        next.children("a:first").click();
    }
}

var close_current_tab = function(){
    $("#tab_ctrl li.current a.close_tab").trigger("click");
}


$(".box a").click(function(e){
    var target  = e.target || e.srcElement;
	var a = $(target).closest("a");
	var menuurl = a.attr("_url");
	var menuname = a.text();
	open_tab_url(menuurl,menuname,{'do_switch':(e.ctrlKey==false) });
	
});


var showPageLoading = function(){
	$("#pageloading").html("<img src='/pinture/images/pageloading.gif' />");
}
var hiddenPageLoading = function(){
	$("#pageloading").html("");
}



var init_tab = function(){
    $.each($("#tab_ctrl li"),function(idx){
        $(this).children("a:first").click(function(){
            $.each($("#tab_ctrl li"),function(_idx){
                _idx == idx ? $(this).addClass("current") : $(this).removeClass("current");
            })
            $.each($("#tab_ctn").children(),function(_idx){
                _idx == idx ? $(this).show() : $(this).hide();
            })
        });
    });
	//var toploading = layer.load(0, { shade: false }); //loadin...

}


function setTabLayout(){
	/*
    var tabsub = $('#col_sub');
	var totalHeight = $(window).height() - tabsub.outerHeight() + tabsub.height()+2;
	var h = totalHeight - tabsub.offset().top;
	tabsub.height(h);
	*/
}

setTabLayout();


$(function(){

	 CRM.window_resize();
	$(".box h4").click(function(){
		$(this).parent().toggleClass("box_fold");
	}).hover(function(){
		$(this).toggleClass("hover");
	});
     $(window).resize(CRM.window_resize());
			 //layer.close(toploading);
	//update_count();
})


$(window).bind("resize",setTabLayout);

if(typeof gourl!='undefined' && gourl!='' && typeof gourlname!='undefined' && gourlname!='')
	open_tab_url(gourl,gourlname,{'do_switch':false });
else
	$("#col_sub a:first").click();


$("#navigation_collapser").click(function(e){
	var col_sub_div = document.getElementById("col_sub");
	if (col_sub_div.style.display == "" || col_sub_div.style.display == "block" ){
		col_sub_div.style.display = "none";
		$('#navigation_collapser').attr("title","显示左边菜单");
		$('#navigation_collapser').html("→");
	}else{
		col_sub_div.style.display = "block";
		$('#navigation_collapser').attr("title","隐藏左边菜单");
		$('#navigation_collapser').html("←");
	}

});







