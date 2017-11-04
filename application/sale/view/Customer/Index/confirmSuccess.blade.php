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
   
</head>

<body class="tip-bg">
    <section class="tip-wrap">
        <div class="item-1">您已成功申请<span>{$returnData['roleName']}</span>了</div>
        <div class="item-2">恭喜您，获得一笔奖励金！</div>
        <div class="reward-box r-2">
            <div class="one-reward">
                <div class="num"><?php echo SubstrPrice(DePrice($returnData['bullBonus']))?></div>
                <div class="txt">牛粮奖励金</div>
            </div>
            <div class="one-reward">
                <div class="num"><?php echo SubstrPrice($returnData['bullMoney'])?></div>
                <div class="txt">牛豆</div>
            </div>
            
            
        </div>
        
        <!--<div class="point">
            <span>温馨提示：</span>下载APP可以立即使用
        </div>
        
        <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt" class="go_download">去下载</a>-->
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
  <!--   <div class="share-success-wrap">
        <img src="<?=$publicDomain?>/mobile/img/icon/success.png" />
        <div class="share-success-tip">恭喜您成功申请<span class="red"> {$returnData['roleName']}</span></div>
      
        <div class="warmth-box">
        	<div class="warmth-p1">温馨提示:</div>
        	<div class="warmth-p2">恭喜获得 <span class="red">{$returnData['bullMoney']}</span> 牛豆</div>
        </div> -->
        <!--<div class="pay-btn">
            <a href="index.html" type="button" class="am-btn am-btn-danger am-btn-block">完 成</a>
        </div>-->
    <!-- </div> -->
    
  
</body>
<script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>

</html>
