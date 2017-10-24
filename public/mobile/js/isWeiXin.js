$(function(){
	if(isWeiXin()){
		$(".web-header").remove();
		$("body").removeClass("am-with-fixed-header");
	}
});

 function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}