<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>牛店关系建立</title>
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
    </style>
</head>

<body class="tip-bg">
   <section class="tip-wrap" style="bottom: 30%;">
        <div class="item-1">您与牛店关系  <span>建立成功</span>！</div>
        
        <?php if($share_recoed_bonus_return > 0){ ?>
        <div class="reward-box">
            <div class="one-reward">
                <div class="num"><?=$share_recoed_bonus_return?></div>
                <div class="txt">牛粮奖励金</div>
            </div>
            <div class="one-reward">
                <div class="num"><?=$share_recoed_bull_return?></div>
                <div class="txt">牛豆</div>
            </div>
            
        </div>
        
        <div class="point">
            <span>温馨提示：</span>下载APP可以立即使用
        </div>
        
        <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt" class="go_download">去下载</a>
        <?php } ?>
    </section>
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
  <!--   <div class="build-success-wrap">
        <img src="/mobile/img/icon/success.png" />
        <div class="bulid-success-tip">您与牛店关系建立成功！</div> -->
        
        <!--<div class="pay-btn">
            <a href="index.html" type="button" class="am-btn am-btn-danger am-btn-block">完 成</a>
        </div>-->
  <!--   </div> -->
    
  <!--   <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt">
    	<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer flex-center am-g login-note am-no-layout">
	        <img src="/mobile/img/icon/LOGO2.png" class="am-u-sm-2">
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
