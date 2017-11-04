<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/<?=$publicDomain?>/mobile/css/amazeui.min.css"> -->
   {include file="Pub/assetcss" /}
     {include file="Pub/assetjs" /}
</head>

<body>
 
    <style type="text/css">
        .text-overflow-2{
            height: 40px;
            word-break: normal;
            overflow: hidden;
        }
        .am-slider .am-slides img {
            display: block;
            width: 100vw;
            height: 50vw;
        }
        .am-slider-default {margin: 0 0 6px;}
    </style>
    <div class="store-header am-g">
        <a href="#" class="am-u-sm-2" >
        <?php if(!empty($business['businesslogo'])){ ?>
            <img src="<?=$business['businesslogo']?>" width="40px" />
        <?php }else{ ?>
            <img src="<?=$publicDomain?>/mobile/img/default.png" width="40px" />
        <?php } ?>

        </a>
        <span class="am-u-sm-8 store-title"><?=$business['businessname']?></span>
      <!--   <a href="login.html" class="am-u-sm-2 share">
            <div class="flex-column gray share-f">
                <i class="icon icon-share"></i>分享
            </div>
        </a> -->
    </div>
    <div data-am-widget="tabs" class="am-tabs am-tabs-d2">
        <ul class="am-tabs-nav am-cf">
            <li class="am-active">
                <a href="[data-tab-panel-0]">
                    店铺首页
                </a>
            </li>
            <li>
                <a href="[data-tab-panel-1]">
                    全部宝贝
                </a>
            </li>
        </ul>
        <div class="am-tabs-bd">
            <div data-tab-panel-0 class="am-tab-panel am-active">

                <div class="store-banner">
                    <div data-am-widget="slider" class="am-slider am-slider-default" data-am-slider='{&quot;directionNav&quot;:false}'>
                        <ul class="am-slides" >
                        <?php foreach($business['banner'] as $v){ ?>
                            <li>
                                <img src="<?=$v['thumb']?>">
                              
                            </li>
                        <?php } ?> 
                        </ul>
                    </div>
                </div>
                <?php 
                    $count = count($productlist['list']);
                    foreach($productlist['list'] as $k=>$v){?>
                    <?php 
                        if($k%2==0){
                    ?>
                    <div class="pro-container am-g">
                    <?php }?>
                        <div class="pro-box am-u-sm-6">
                            <a href="/index/index/goodsdetail?goodsid=<?=$v['productid']?>">
                               <div class="pro-dt">
                                    <img src="<?=$v['thumb']?>" />
                                    <div class="pro-box-txt">
                                        <div class="text-overflow-2"><?=$v['productname']?></div>
                                        <div><span class="red"><?=$v['prouctprice']?></span><small>元</small></div>
                                     <!--    <div><span class="red"><?=$v['marketprice']?></span><small>元</small>+<span class="red"><?=$v['bullamount']?></span><small>牛豆</small></div> -->
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php 
                        if($k%2==1 || $k==$count-1){
                    ?>
                    </div>
                    <?php }?>
                <?php } ?>
               <!--  <div class="pro-cate">商品分类</div> -->
            </div>
            <div data-tab-panel-1 class="am-tab-panel ">
                <?php 
                    $count = count($productlist['list']);
                    foreach($productlist['list'] as $k=>$v){?>
                    <?php 
                        if($k%2==0){
                    ?>
                    <div class="pro-container am-g">
                    <?php }?>
                        <div class="pro-box am-u-sm-6">
                            <a href="/index/index/goodsdetail?goodsid=<?=$v['productid']?>">
                                <div class="pro-dt">
                                    <img src="<?=$v['thumb']?>" />
                                    <div class="pro-box-txt">
                                        <div class="text-overflow-2"><?=$v['productname']?></div>
                                        <div><span class="red"><?=$v['prouctprice']?></span><small>元</small></div>
                                        <div><span class="red"><?=$v['marketprice']?></span><small>元</small>+<span class="red"><?=$v['bullamount']?></span><small>牛豆</small></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php 
                        if($k%2==1 || $k==$count-1){
                    ?>
                    </div>
                    <?php }?>
                <?php } ?>
               
            </div>
        </div>
    </div>
    <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt"><div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer flex-center am-g login-note">
        <img src="<?=$publicDomain?>/mobile/img/icon/LOGO2.png" class="am-u-sm-2" />
        <div class="footer-des flex-column am-u-sm-6">
            <div>你买单，我送钱</div>
            <div>赶快下载<label class="red"></label>手机客户端</div>
        </div>
        <div class="am-u-sm-4 text-right">
            <span class="download">点击下载</span>
        </div>
    </div></a>

<script>
    $(function(){
        
        setProductImgSize();
        $(window).resize(function(){
            setProductImgSize();
        });
    });
    
    //动态设置商品图片宽高
    function setProductImgSize(){
        pro_with=$(".pro-dt").width();
        //这里设置宽高一样
        $(".pro-dt img").css({
            "with":pro_with,
            'height':pro_with
        });
    }
</script>
</body>
</html>