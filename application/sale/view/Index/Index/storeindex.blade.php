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
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/baguettebox.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    {include file="Pub/assetcss" /}
   
    {include file="Pub/assetjs" /}
    <script src="<?=$publicDomain?>/mobile/js/baguettebox.min.js"></script>

<style type="text/css">
    .am-slider .am-slides img {
    display: block;
      width: 100vw;
    height: 66.67vw;
}
.a_ic_store{
        position: absolute;
    bottom: 10px;
    z-index: 100;
    right: 10px
}
.ic_store{
    background:url(<?=$publicDomain?>/mobile/img/icon/ic_store_top_picture@2x.png) no-repeat;
    background-size: 100%;
    width: 45px;
    height: 45px;
}
.ic_store .ic_count{
       font-size: 12px;
    position: absolute;
    bottom: 2px;
    right: 10px;
    color: #dedbdb;
}
.page-header{
    background: #F9F9F9;
    border-bottom: 1px solid #CCCCCC;
}
.page-header .page-bar{
    text-align: center;
    padding: 10px;
    font-size: 14px;
    color: #333;
    position: relative;
}
.page-header .page-bar a{
    
}

.page-header .page-bar .back-ico{
    width: 10px;
    height: 17px;
    background: url(<?=$publicDomain?>/mobile/img/icon/back@2x.png) no-repeat;
    background-size:100% ;
    display: block;
    position: absolute;
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
            <a href="javascript:history.go(-1)">
                <span class="back-ico"></span>
            </a>
            <span class="bar-title"><?=$title?></span>
        </div>
    </header>
    <div class="pro-detail-container">
        <div class="pro-detail-item">
            <div data-am-widget="slider" class="am-slider am-slider-a1" data-am-slider='{&quot;directionNav&quot;:false}'>
                <ul class="am-slides baguetteBox" >
                <?php foreach($shopDetails['img'] as $v){ ?>
                    <li>
                      
                      
                        <a href="<?=$v?>"><img src="<?=$v?>"></a>
                      
                    </li>
                <?php } ?> 
                </ul>
                <?php if($shopDetails['albumCount'] > 0){ ?>
                    <a href="/index/index/storealbum?storeid=<?=$storeid?>&from=singlemessage&isappinstalled=1" class="a_ic_store">
                        <div class="ic_store">
                            <span class="ic_count"><?=$shopDetails['albumCount']?>张</span>
                        </div>
                       
                    </a>
                <?php } ?>
            </div>
            <div class="am-g pro-txt">
                <div class="am-u-sm-8 text-overflow"><?=$shopDetails['businessname']?></div>
                <div class="am-u-sm-4 text-right gray-more"><?=$shopDetails['work_time']?></div>
            </div>

            
          
            <div class="store-intro-star am-g">
                <div class="am-u-sm-10">

                <?php $star = explode('.',$shopDetails['scores']); ?>
                <?php 
                    for($i =1; $i <= 5; $i++) {
                        if($star[0] >= $i) { ?>
                            <!--  quan -->
                             <i class="icon icon-jude active"></i>
                        <?php  } else {
                            if($star[1] > 0) {?>
                              <!-- half -->
                                <i class="icon icon-jude half"></i>
                           <?php  } else {?>
                                 <i class="icon icon-jude gray"></i>
                             <?php }
                        }
                    }?>
    
                    <?=$shopDetails['scores']?> 分<span class="gray">&nbsp;<?=$shopDetails['scorescount']?> 人评分</span>
                </div>
                <div class="am-u-sm-2"></div>
            </div>
          
            <div class="store-address-row">
                <div class="store-address gray">
                    <?php if($shopDetails['isdelivery'] > 0){ ?><i class="icon icon-song" style="position: relative;top: 4px;margin-right: 10px;"></i>
                    <?=$shopDetails['delivery']?>元起送&nbsp;&nbsp;|&nbsp;&nbsp;<?php } ?>
                    <?php if($shopDetails['actualfreight'] == 0){ ?>
                          免费配送
                        </span>
                    <?php }else{ ?> 
                          <span v-else>
                          配送费<?=$shopDetails['actualfreight']?>元
                        </span>
                    <?php } ?>    
                </div>
               
            </div>
            
            <div class="store-address-row">
               
               
                    <div class="gray">
                        <a href="http://m.amap.com/share/index/__q=<?=$shopDetails['laty']?>,<?=$shopDetails['lngx']?>,<?=$title?>&src=jsapi&callapp=0&lnglat=<?=$shopDetails['lngx']?>,<?=$shopDetails['laty']?>8&name=<?=$shopDetails['businessname']?>" class="gray-more"> <i class="icon icon-address"></i> </a>
                    </div>
                   <a href="http://m.amap.com/share/index/__q=<?=$shopDetails['laty']?>,<?=$shopDetails['lngx']?>,<?=$title?>&src=jsapi&callapp=0&lnglat=<?=$shopDetails['lngx']?>,<?=$shopDetails['laty']?>8&name=<?=$shopDetails['businessname']?>" class="gray-more"> <div class="store-address gray"><?=$shopDetails['address']?></div> </a>
               
                

               <!--  <div class="store-address-row">
                    <div class="gray">
                        <i class="icon icon-address"></i>
                    </div>
                    <div class="store-address gray">
                        广东省深圳市南山区软件园一期 1栋3楼广东省深圳市南山区软件园一期 1栋3楼
                    </div>
                    <div>
                        <a href="login.html"><i class="am-icon-phone am-icon-md red"></i></a>
                    </div>
                </div> -->

                <div class="am-fr">
                    <a href="tel:<?php if(!empty($shopDetails['servicetel'])){ echo $shopDetails['servicetel'][0];}?>"><i class="am-icon-phone am-icon-md red" style="border-left: 1px solid #ddd; padding-left: 10px;"></i></a>
                </div>
            </div>
           
        </div>
        <div class="pro-detail-item  am-g">
                <div class="pro-line"></div>
                <div class="am-u-sm-4"><i class="icon icon-stop <?php if($shopDetails['isparking'] != 1){ ?> icon-no-stop <?php } ?>"></i>&nbsp;免费停车</div>
                <div class="am-u-sm-4 text-center"><i class="icon icon-wifi <?php if($shopDetails['iswifi'] != 1){ ?> icon-no-wifi <?php } ?>"></i>&nbsp;免费wifi</div>
                <div class="am-u-sm-4 text-right"><i class="icon icon-truck <?php if($shopDetails['isdelivery'] != 1){ ?> icon-no-truck <?php } ?>"></i>&nbsp;免费送货</div>
        </div>
        <div class="pro-detail-item am-g">
            <div class="am-u-sm-6" style="margin-top: 5px;"><span class="red">送<?=$shopDetails['discount']?>牛豆</span></div>
            <div class="am-u-sm-6 text-right">
                <a href="/StoBusiness/Index/setpayamount?business_code=<?=$shopDetails['business_code']?>" class="topay">优惠付款</a>
            </div>
        </div>
        <div class="pro-detail-item">
            <div class="am-g  store-intro-title">
                <div class="am-u-sm-12">购买须知</div>
                <!-- <div class="am-u-sm-2 text-right"><a href="login.html" class="gray"><i class="icon-right"></i></a></div> -->
            </div>
            <div class="foot-slide" style="color: #999;">
                <?=$shopDetails['description']?>
            </div>
        </div>

          <?php if($shopDetails['goodscount']>0){ ?>
         <div class="pro-detail-item"> 
       
          
                <div class="am-g  store-intro-title">
                    <a href="/index/index/stogoodlist?storeid=<?=$storeid?>" > 
                    <div class="am-u-sm-10" style="color: #333;">全部商品(<?php echo $shopDetails['goodscount']; ?>)</div>
                    <div class="am-u-sm-2 text-right"><i class="icon-right"></i></div>
                    </a>
                </div>
            
        
           
            <!--<div class="foot-slide">
                <div class="slide-box">
                    <img src="img/food1.png" />
                    <div class="gray text-overflow">提拉米苏草莓蛋糕123123</div>
                </div>
                <div class="slide-box">
                    <img src="img/food2.png" />
                    <div class="gray text-overflow">魔方白果香双层123123</div>
                </div>
                <div class="slide-box">
                    <img src="img/food3.png" />
                    <div class="gray text-overflow">提拉米苏草莓蛋糕123123</div>
                </div>
                <div class="slide-box">
                    <img src="img/food1.png" />
                    <div class="gray text-overflow">魔方白果香双层3123123</div>
                </div>
            </div>-->
            
            <div class="recom-list">
                <?php foreach($shopDetails['recommendPro'] as $value){ ?>
                <div class="one-recom">
                    <a href="/StoBusiness/Index/setpayamount?storeid=<?=$shopDetails['id']?>&amount=<?=$value['prouctprice']?>&business_code=<?=$shopDetails['business_code']?>">
                        <div class="am-fl"><img src="<?=$value['thumb']?>" /></div>
                         <div class="am-fl">
                           <div class="recom-good-name tl-ellipsis"><?=$value['productname']?></div>
                            <?php if($value['prouctprice'] > 0){ ?><div class="recom-good-price"><span class="red"><?=$value['prouctprice']?></span>元</div><?php }　?>
                                
                        </div>
                        <div class="clear"></div>
                    </a>
                </div>
                <?php } ?>

              
            </div>
        </div>
          <?php } ?>


        <?php if($EvaluateList['total'] > 0 ){ ?>
        <div class="pro-detail-item">
         
                <div class="am-g  store-intro-title">
                    <a href="/index/index/stoEvaluateList?storeid=<?=$storeid?>">
                        <div class="am-u-sm-10" style="color: #333;">附近顾客点评<span class="gray">(<?=$EvaluateList['total']?>)</span></div>
                        <div class="am-u-sm-2 text-right gray"><i class="icon-right"></i></div>
                    </a>
                </div>
           
            <div class="store-comment-list">
                <?php foreach($EvaluateList['list'] as $v){ ?>
                    <div class="store-comment">
                        <div class="am-g flex-center" style="align-items: initial;">
                            <div class="am-u-sm-2">
                                <?php if(empty($v['headpic'])){ ?>
                                    <img src="<?=$publicDomain?>/mobile/img/default.png" class="avator">
                                <?php }else{ ?>
                                    <img src="<?=$v['headpic']?>" class="avator">
                                <?php } ?>
                            </div>
                            <div class="am-u-sm-4">
                                <span> <?=$v['frommembername']?></span>
                                <div class="red">
                                   
                                    <?php $star = explode('.',$v['scores']); ?>

                                     <?php 
                                        for($i =1; $i <= 5; $i++) {
                                            if($star[0] >= $i) { ?>
                                                <!--  quan -->
                                                 <i class="icon icon-jude active"></i>
                                            <?php  } else {
                                                if($star[1] > 0) {?>
                                                  <!-- half -->
                                                    <i class="icon icon-jude half"></i>
                                               <?php  } else {?>
                                                     <i class="icon icon-jude gray"></i>
                                                 <?php }
                                            }
                                        }?>
                               
                                    <span class="gray-more"><?=$v['scores']?></span>
                                </div>
                            </div>
                            <div class="am-u-sm-6 gray-more text-right">
                                <?=$v['addtime']?>
                            </div>
                        </div>
                        <div class="am-g">
                            <div class="am-u-sm-2"></div>
                            <div class="am-u-sm-10">
                                {$v['content']}
                                <div class="baguetteBox">
                                    <?php foreach($v['img_arr'] as $value){ ?>
                                        <a href="<?=$value?>"><img src="<?=$value?>" style="width: 50px;height: 50px;" /></a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>  
            </div>
        </div>
        <?php } ?>
    </div>
    
        <script>
            $(function(){
                 baguetteBox.run('.baguetteBox', {
                        //buttons: true,
                        captions:true,
                        animation: 'fadeIn'
                        
                    });
            });
        </script>
    {include file="Pub/down" /}
</script>
</body>
</html>
