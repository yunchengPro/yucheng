/*
  author talon
 * */


/*
 01.toast 闪框
 * */
function toast(msg,ms){
	var ms=ms||2000;	//不传默认2s 后删除
	var toastDiv='<div class="tl-toast-box">'+msg+'</div>';
	var timer;
	var delDost=function(){
		$(".tl-toast-box").remove();
		clearTimeout(timer);
	};
	
	$("body").append(toastDiv);
	 timer= setTimeout(function(){
	 	delDost();
	 },ms);
}

/*
 02.LinkTo 
 * */
function LinkTo(url){
	window.location.href=url;
}
