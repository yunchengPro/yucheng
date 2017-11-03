<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$title}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	    <meta name="renderer" content="webkit">
	    <meta name="author" content="talon">
	    <meta name="application-name" content="niuniuhui-wap">
	    <meta http-equiv="Cache-Control" content="no-siteapp" />
	    <meta name="format-detection" content="telephone=no" />
	    
	    <link rel="stylesheet" href="/mobile/css/amazeui.min.css" />
	    <link rel="stylesheet" type="text/css" href="/mobile/css/talon_3g.css"/>
	   
	  
	   
	     <script type="text/javascript" src="/mobile/js/jquery.min.js" ></script>
	  
	    <script type="text/javascript" src="/mobile/js/vue.min.js" ></script>
	      <script type="text/javascript" src="/mobile/js/vue-resource.min.js" ></script>
	      
	    <style>
	    	body{background: url(/mobile/img/icon/download_bg.png) no-repeat;background-size:cover ;position: static;}
	    	.down-load{margin-top: 350px;padding: 0 20px;}
	    	@media only screen and (min-width: 320px) and (max-width:359px) {
	    		.down-load{margin-top: 300px;padding: 0 20px;}
	    	}
	    	@media only screen and (min-width: 360px) and (max-width:374px) {
	    		.down-load{margin-top: 320px;padding: 0 20px;}
	    	}
	    	@media only screen and (min-width: 375px) and (max-width:400px)   {
	    		.down-load{margin-top: 340px;padding: 0 20px;}
	    	}
	    	@media only screen and (min-width: 401px)    {
	    		.down-load{margin-top: 370px;padding: 0 30px;}
	    	}
	    	
	    	@media only screen and (min-width: 640px)    {
	    		.down-load{margin-top: 580px;padding: 0 30px;}
	    	}
	    	.dl-h3{font-size: 18px;text-align: center;margin-bottom: 10px;}
	    	.dl-h4{font-size:14px;text-align: center;}
	    	.btn-box{padding: 15px 15px 0;border-top: 0.5px solid #DDDDDD;margin-top: 20px;}
	    	.download-btn {
		    background: #F13437;
		    display: block;
		    width: 100%;
		    height: 40px;
		    line-height: 40px;
		    text-align: center;
		    border-radius: 4px;
		    font-size: 15px;
		    color: #FFFFFF;
		    margin-bottom: 12px;
		    margin-top: 15px;
		    text-decoration: none;
		}
		.download-btn img {
		    width: 18px;
		    margin-right: 15px;
		    vertical-align: middle;
		    margin-top: -5px;
		}
		.page-header{
	background: #F9F9F9;
	border-bottom: 1px solid #CCCCCC;
}
.page-header .page-bar{
	    text-align: center;
    height: 44px;
    font-size: 14px;
    color: #333;
    position: relative;
    line-height: 44px;
}
.page-header .page-bar a{
	
}

.page-header .page-bar .back-ico{
	    width: 10px;
    height: 17px;
    background: url(../img/icon/back@2x.png) no-repeat;
    background-size: 100%;
    display: block;
    position: absolute;
    top: 50%;
    margin-top: -8.5px;
    left: 10px;
}
.page-header .page-bar .bar-title{
	font-size: 16px;

	width: 100%;
	text-align: center;
}
	   </style>
	</head>
	<body>
	<header class="page-header">
			
			<div class="page-bar">
			
			<a href="/user/index/index">
				<img src="/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			
			<div class="bar-title">牛牛汇App下载</div>
			
		</div>
	</header>
	
		<div id="app">
			<div class="down-load">
				<div class="dl-h3">牛牛汇APP</div>
				<div class="dl-h4">享受一分钱花3次</div>
				<div class="btn-box">
					<a href="javascript:download()" class="download-btn"><img src="/mobile//img/icon/IOS.png">Iphone版下载</a>
					<a href="http://sj.qq.com/myapp/detail.htm?apkName=com.ynh.nnh.tt" class="download-btn"><img src="/mobile//img/icon/Android.png">Android版下载</a>
				</div>	
			</div>
		</div>
		
	
	
	
			<script type="text/javascript">
				
		        function download(){

		             var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
		             if (userAgent.indexOf("Safari") > -1) {
		                window.location.href="itms-services://?action=download-manifest&url=http://nnhcoupon.oss-cn-shenzhen.aliyuncs.com/IOS/download/2.1.1_adhoc.plist?"+(Math.random()*100000);
		             }else{
		             	window.location.href="ttp://sj.qq.com/myapp/detail.htm?apkName=com.ynh.nnh.tt";
		                //alert("请在Safari浏览器打开 本网页");
		             }
		        }
    
			</script>
			<script type="text/javascript">
				
				
			
			var vm=	new Vue({
				  el: '#app',
				  data: {
				   		
				  },
				 
				  methods:{
				  	
				  },
				   mounted:function(){
			  		
			  },
				  watch:{
				  	
				  }
				});
		 	</script>
		</script>
	</body>
</html>
