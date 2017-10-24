<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    <style>
    	.build-success-wrap {
		    text-align: center;
		    padding-top: 45px;
		    padding-bottom: 45px;
		    background: #FFFFFF;
		}
		.build-success-wrap img {
		    width: 80px;
		    margin-bottom: 25px;
		}
		.build-success-wrap .bulid-success-tip{
			font-size: 15px;
			color: #333333;
		}
		.build-oper{padding: 25px 15px;}
		.build-oper .download-btn{
			display: block;
			text-align: center;
			border-radius: 4px;
		    background: #f13437;
		    font-size: 16px;
		    height: 44px;
		    line-height: 44px;
		    width: 100%;
		    color: #FFFFFF;
		}
    </style>
</head>

<body style="background: #EEEEEE;">
    <!--<header data-am-widget="header" class="am-header am-header-default am-header-fixed">
        <div class="am-header-left am-header-nav">
            <a href="store-intro.html">
                <i class="am-icon-angle-left am-icon-md gray"></i>
            </a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link">支付成功</a>
        </h1>
    </header>-->
    <div class="build-success-wrap">
        <img src="<?=$publicDomain?>/mobile/img/icon/success.png" />
        <div class="bulid-success-tip">您已经是牛店的粉丝，请下载享优惠</div>
        <!--<div class="pay-btn">
            <a href="index.html" type="button" class="am-btn am-btn-danger am-btn-block">完 成</a>
        </div>-->
    </div>
    <div class="build-oper">
    	 <a  href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt" class="download-btn">去下载</a>
    </div>
    
   <!--  <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt">
    	<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer flex-center am-g login-note am-no-layout">
	        <img src="<?=$publicDomain?>/mobile/img/icon/LOGO2.png" class="am-u-sm-2">
	        <div class="footer-des flex-column am-u-sm-6">
	            <div>消费还可赚钱</div>
	            <div>赶快下载<label class="red"></label>手机客户端</div>
	        </div>
	        <div class="am-u-sm-4 text-right">
	            <span class="download">点击下载</span>
	        </div>
    	</div>
    </a> -->
</body>


</html>
