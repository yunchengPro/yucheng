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
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    <style>
        .build-success-wrap {
            text-align: center;
            padding-top: 75px;
            padding-bottom: 45px;
            
        }
        .build-success-wrap img {
            width: 125px;
            margin-bottom: 15px;
        }
        .build-success-wrap .bulid-success-tip{
            font-size: 14px;
            color: #333333;
        }
    </style>
</head>
<?php if($loginType == 'login'){ ?>
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
        <img src="<?=$publicDomain?>/mobile/img/icon/ic_relationship@2x.png" />
        
            <div class="bulid-success-tip">您已建立过牛粉关系，不可再次建立</div>
       
           <!--   <div class="bulid-success-tip">注册成功</div> -->
       
        
        <!--<div class="pay-btn">
            <a href="index.html" type="button" class="am-btn am-btn-danger am-btn-block">完 成</a>
        </div>-->
    </div>

    <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt">
        <div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer flex-center am-g login-note am-no-layout">
            <img src="<?=$publicDomain?>/mobile/img/icon/LOGO2.png" class="am-u-sm-2">
            <div class="footer-des flex-column am-u-sm-6">
                <div>你买单，我送钱 </div>
                <div>赶快下载<label class="red"></label>手机客户端</div>
            </div>
            <div class="am-u-sm-4 text-right">
                <span class="download">点击下载</span>
            </div>
        </div>
    </a>
</body>
<?php }else{ ?>
<body class="tip-bg">
    <section class="tip-wrap">
        <div class="item-1">注册成功，您已是<span>牛粉</span>了</div>
        <div class="item-2">恭喜您，获得一笔奖励金！</div>
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
    </section>
    
   <script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
    <script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
</body>
<?php } ?> 


</html>
