var open_tab_url = function(url,cfg){
	 
    var config = $.extend({},{
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
        var tab = $("<li><a href=\"javascript:void(0)\" class=\"tab_link\">"+config.title+"</a><a class='close_tab' href='javascript:void(0)' onclick='close_tab(\""+tid+"\")'></a></li>").attr("id",tid);
        var iframe = $("<iframe frameborder='0' frameborder='no' width='100%' scrolling='auto'  vsspace='0' hspace='0' marginwidth='0' marginheight='0' class='ctn-iframe hide' class='ctn-iframe hide' name='"+iname+"'></iframe>");
        iframe.attr("src",url).attr("id",cid);
        $("#tab_ctrl").append(tab);
        $("#tab_ctn").append(iframe);
        init_tab();
    }
    if (config.do_switch){
        $("#"+tid+" a:first").click();
		//iframe.show();
    }
	iframe.focus();
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

	if ($("#tab_ctn iframe").length==0){
		$('#tab_menu_ctn').show();
		$('#tab_ctn').hide();
	}

}
var close_current_tab = function(){
    $("#tab_ctrl li.current a.close_tab").trigger("click");
}
var set_tab_title = function(title){
    $("#tab_ctrl li:first a").html(title);
}
var init_tab = function(){
    $.each($("#tab_ctrl li"),function(idx){
        $(this).children("a:first").click(function(){

            $.each($("#tab_ctrl li"),function(_idx){
                _idx == idx ? $(this).addClass("current") : $(this).removeClass("current");
            })
            $.each($("#tab_ctn").children(),function(_idx){
				$('#tab_menu_ctn').hide();
				$('#tab_ctn').show();
                _idx == idx-3 ? $(this).show() : $(this).hide();
            })
        });
    });
}
