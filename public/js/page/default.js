YSL.tab("#tab_ctrl","#tab_ctn",{event:"click",url:"url"});
$("#tab_ctrl").children()[0].click();
function setTabLayout(){
	var totalHeight = $(window).height() - $('#col-main').outerHeight() + $('#col-main').height();
	var tab = $('#tab_ctn');
	var h = totalHeight - $('#admin-nav').outerHeight()-8;
	//var h = totalHeight - tab.offset().top;
	tab.height(h);
}
setTabLayout();
$(window).bind("resize",setTabLayout);



function close_current_mail(){
    if (typeof(frames["iframe_message"].close_current_mail) == 'function'){
        frames["iframe_message"].close_current_mail();
    }
}



$("#tab_ctrl li").click(function(e){
    var target  = e.target || e.srcElement;
	var li = $(target).closest("li");
	var topmenuname = li.attr("iname");
	$("#topiframe").val(topmenuname);
});



/**
 * 处理公共事件
 */
function handle_event(e,context){
    e=e||window.event;
    if((e.which||e.keyCode)==116 || (e.ctrlKey && (e.which||e.keyCode)==82)){
        var w = context;
		if (w.frameElement){
			w.document.location.reload(true);
			if(e.preventDefault){
				e.preventDefault();
			}
			else{
				e.keyCode = 0;
				e.returnValue=false;
			}
			return false;
		}
	}
	if (HOTKEY_CONFIG['close_view']){
	    var hotkey = HOTKEY_CONFIG['close_view'].split("+");
	    var keyCode = in_array(hotkey.pop(),YSL.KEYS);
        var ctrlKey = in_array("CTRL",hotkey) !== false;
        var altKey = in_array("ALT",hotkey) !== false;
        var shiftKey = in_array("SHIFT",hotkey) !== false;

        if ((altKey == e.altKey) && (shiftKey == e.shiftKey) && (ctrlKey == e.ctrlKey) && (e.which||e.keyCode)==keyCode){//关闭当前详细页
            var iname = $("#tab_ctrl li.current").attr("iname");
            if (typeof(frames[iname].close_current_tab) == 'function'){
                frames[iname].close_current_tab();
            }
    	}
	}

}


function in_array(val,array){
    var result = false;
    $.each(array,function(i,n){
        if (val.toLowerCase() == n.toLowerCase()){
            result = i;
        }
    })
    return result;
}


$("#progress").ajaxStart(function(){
   $(this).show();
 });
 $("#progress").ajaxStop(function(){

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
	  $(this).find("span").html("信息发送成功");
	}
	if (funlang=='en_US'){
	   $(this).find("span").html("sent success");
	}	

     var that = this;
     setTimeout(function(){$(that).hide();},2000);
 });
 $(window).on("keydown",function(e){
     handle_event(e,window);
})