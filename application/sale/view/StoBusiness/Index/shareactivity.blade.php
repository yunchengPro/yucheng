<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<title>活动介绍</title>
	<meta content="width=device-width; initial-scale=1; maximum-scale=1" name="viewport">
	<meta name="renderer" content="webkit">
	<meta name="format-detection" content="telephone=no" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	{include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
	

<style>
	.activity-desc-bg{
		background: url(<?=$publicDomain?>/mobile/img/icon/activity_bg.png) center no-repeat;
		background-size: cover;
	}
	.activity-desc-wrap{padding: 50px 15px;color: #FFFFFF;}
	.activity-desc-wrap h4{font-size: 15px;font-weight: bold;}
	.activity-desc-wrap .activity-item{margin-bottom: 5px;font-size: 12px;}
	.activity-oper{margin: 30px 0; }
	.activity-oper .register-btn{background: #f4b327;height: 44px;width: 100%;border-radius: 4px;font-size: 16px;}
</style>
</head>
<body class="activity-desc-bg">
	    <!--<header data-am-widget="header" class="am-header am-header-default am-header-fixed">
        <div class="am-header-left am-header-nav">
            <a href="store-intro.html">
                <i class="am-icon-angle-left am-icon-md gray"></i>
            </a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link">评价</a>
        </h1>
    </header>-->
   <section class="activity-desc-wrap">
   		<div><img src="<?=$publicDomain?>/mobile/img/icon/activity_title.png"/></div>
   			<h4>成功注册即享优惠</h4>

		<div class="activity-item">1、牛票专区购物，买多少送多少；</div>
		
		<div class="activity-item">2、成功邀请一个好友注册可获得300牛豆；</div>
		
		<div class="activity-item">3、邀请好友每消费一笔，可获得消费提成；</div>
		
		
		<h4>如何成为用户</h4>
		
		<div class="activity-item">1、下载APP点击底部菜单“我的”输入手机号和验证码即可成为；</div>
		
		<div class="activity-item">2、或点击该页面的注册按钮进行注册；</div>
		
		<div class="activity-oper">
			<button class="register-btn" onclick="window.location.href='/Index/Index/login'">立即注册</button>
		</div>
   </section>
    	
		
	

       
   

</body>
</html>
 