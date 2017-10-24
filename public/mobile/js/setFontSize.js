/*
 * 手机端动态设置html font-size 
 * time 2016-9-6 16:23:05
 * author  talon
 * desc 设计图宽度按750
 * */

/*===============================================================================================*/
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var html = document.documentElement;
        var windowWidth = html.clientWidth;
        if(windowWidth > 1024) windowWidth = 1024;	//ipad 768x1024
        html.style.fontSize = windowWidth / 7.5 + 'px';
        // 等价于html.style.fontSize = windowWidth / 640 * 100 + 'px';
		
		
		// 这个6.4就是根据设计稿的横向宽度来确定的，假如你的设计稿是750

		// 那么 html.style.fontSize = windowWidth / 7.5 + 'px'
    }, false);

})();
//窗口改变执行
 window.onresize=function(){
 	 var html = document.documentElement;
        var windowWidth = html.clientWidth;
         if(windowWidth > 1024) windowWidth = 1024;	//ipad 768x1024
        html.style.fontSize = windowWidth / 7.5 + 'px';
 };
 /*$(window).resize(function(){
 	 var html = document.documentElement;
        var windowWidth = html.clientWidth;
        html.style.fontSize = windowWidth / 7.5 + 'px';
 });
*/

/*===============================================================================================*/
 /*function setFontSize(){
   	   var deviceWidth = document.documentElement.clientWidth;//
		//if(deviceWidth > 768) deviceWidth = 768;//ipad 768px
		if(deviceWidth > 1024) deviceWidth = 1024;//ipad 768px
		document.documentElement.style.fontSize = deviceWidth / 7.5 + 'px';		//7.5=设计图宽度/100
   }

 
 //*第一种调用 jquery
 $(function(){setFontSize();});
 $(window).resize(setFontSize);
 */
/*===============================================================================================*/
//*第二种调用，纯js
	//模拟jquery $(document).ready()
    /*
    document.ready = function (callback) {
	    ///兼容FF,Google
	    if (document.addEventListener) {
	        document.addEventListener('DOMContentLoaded', function () {
	            document.removeEventListener('DOMContentLoaded', arguments.callee, false);
	            callback();
	        }, false)
	    }
	     //兼容IE
	    else if (document.attachEvent) {
	        document.attachEvent('onreadytstatechange', function () {
	              if (document.readyState == "complete") {
	                        document.detachEvent("onreadystatechange", arguments.callee);
	                        callback();
	               }
	        })
	    }
	    else if (document.lastChild == document.body) {
	        callback();
	    }
	};

 	//DOM结构绘制完毕后就执行
 	document.ready(setFontSize);	//等价于 document.ready(function(){ setFontSize(); });
  
  	//窗口改变执行
 	window.onresize=function(){ setFontSize();};
 	*/
