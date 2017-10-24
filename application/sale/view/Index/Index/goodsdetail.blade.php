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
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
     <style type="text/css"> 
      
     .topnav {
            position: relative;
            width: 100%;
            padding-top: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e5e5;
            text-indent: 20px;
        }   
        .topnav strong{font-weight: normal;line-height: 20px;}
        
        .topnav span{
            background: url('<?php echo $config["webview_url"];?>public/images/ic_arrow_right_agreement.svg') no-repeat;
            height: 22px;
            width: 22px;
            display: block;
            float:right;
            position: absolute;
            right: 0px;
            top: 5px;
            text-indent: -999px;
        }   
       
        .am-tabs-bd{border: 0;} 
        .pro-img{ width: 100vw;height: 100vw;}
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
            <span class="bar-title"></span>
        </div>
    </header>
    <div class="pro-detail-container">
        <div class="pro-detail-item">
            
            <div data-am-widget="slider" class="am-slider am-slider-default" data-am-slider='{&quot;directionNav&quot;:false}'>
                <ul class="am-slides">
                    <?php foreach($data['bannerimg'] as $v){ ?>
                        <li>
                            <img src="<?=$v['productpic']?>">
                          
                        </li>
                    <?php } ?> 
                </ul>
            </div>
             <div class="am-g pro-txt">
                <div class="am-u-sm-8 text-overflow"><?=$data['productname']?></div>
            </div>
            <div class="am-g">
                <div class="am-u-sm-12">
                    <?php if($data['prouctprice'] > 0){ ?><span class="red "><?=$data['prouctprice']?></span>元 <?php } ?> 
                    <?php if($data['prouctprice'] > 0 && $data['bullamount'] > 0 ){ ?> + <?php } ?>
                    <?php if($data['bullamount'] > 0){ ?><span class="red "><?=$data['bullamount']?></span>牛豆 <?php } ?>
                     &nbsp;
                    <span style="color: #666;">市场价：<s><?=$data['marketprice']?></s></span>
                </div>
            </div>
            <div class="am-g gray express">
                <div class="am-u-sm-4">快递：<?=$data['transport']?>元</div>
                <div class="am-u-sm-4 text-center">月销：<?=$data['salecount']?>笔</div>
                <div class="am-u-sm-4 text-right"><?=$data['area']?></div>
            </div>
        </div>
        <div class="pro-detail-item" id="chooseSec"><!--id="chooseSec" 当前div的id 屏蔽选择商品规格效果-->
            <a href="#"><div class="choose-nav am-g">
                <nav class="am-u-sm-6" style="color: #333;">选择 规格</nav>
                <nav class="am-u-sm-6 text-right"><i class="icon-right" style="position: relative;top: .4rem;"></i></nav>
            </div></a>
        </div>
       <!--  <div class="pro-detail-item pro-detail-judge">
            <div>宝贝评价（<?=$data['evaluatecount']?>）</div>
            <div class="cust-line"><?=$data['commnentData']['frommembername']?></div>
            <div>
                <?=$data['commnentData']['content']?>
            </div>
            
        </div> -->
        <div class="pro-detail-item">
            <div class="pro-store-img">
                <?php if(!empty($data['businesslogo'])){ ?>
                <img src="<?=$data['businesslogo']?>" />
                <?php }else{ ?>
                 <img src="<?=$publicDomain?>/mobile/img/default.png" />
                <?php } ?>
                <div class="store-title">
                    &nbsp;&nbsp;<?=$data['businessname']?>
                </div>
            </div>
            <div class="am-g flex-center store-star">
                <div class="am-u-sm-6 text-center">
                    <span class="font-16"><?=$data['goodscount']?></span>
                    <div>宝贝数量</div>
                </div>
                <div class="am-u-sm-6 text-center">
                    <span class="font-16"><?=$data['scores']?></span>
                    <div>评价星级</div>
                </div>
            </div>
            <div class="am-g flex-center text-center store-btns">
                <!-- <div class="am-u-sm-2"></div> -->
                <div class="am-u-sm-6">
                    <a href="/index/index/login" class="getmore">查看分类</a>
                </div>
                <div class="am-u-sm-6">
                    <a href="/index/index/index?shopid=<?=$data['businessid']?>" class="getmore">进店逛逛</a>
                </div>
                <!-- <div class="am-u-sm-2"></div> -->
            </div>
        </div>
    </div>

    <div class="am-modal-actions" id="my-actions">
        <div class="am-modal-actions-group">
            <ul class="am-list">
            <?php foreach($data['sku'] as $key => $value){ ?>

                <li <?php if($key == 0 ){ ?> style="display: block;" <?php }else{ ?> style="display: none;" <?php }  ?>  class="am-modal-actions-header " _data="<?=$value['f_productspec']?>">
                    <div class="sec-choose">
                        <div id="showBox">
                            <img src="<?=$value['productimage']?>" />
                        </div>
                        <div>
                            <div><?php if($value['prouctprice'] > 0){ ?><span class="red"><?=$value['prouctprice']?></span><small>元</small><?php } ?><?php if($value['bullamount'] > 0){ ?>+<span class="red"><?=$value['bullamount']?></span><small>牛豆</small><?php } ?></div>
                            <div>库存<?=$value['productstorage']?>件</div>
                            <div>请选择颜色</div>
                        </div>
                    </div>
                </li>
              <?php } ?>
              <?php foreach($data['spec'] as $key => $value){ ?>
                    <li class="sec-items">
                        <div><?=$value['spec_name']?></div>
                        <div>
                            <?php foreach($value['value'] as $kv => $vv){ ?>
                                <span <?php if($kv == 0) { ?> class="active" <?php } ?> _data="<?=$vv['id']?>" data-img=""><?=$vv['spec_value']?></span>
                            <?php } ?>
                           
                        </div>
                    </li>
              <?php } ?>
             <!--  <li class="sec-items">
                <div>颜色分类</div>
                <div>
                    <span class="active"  data-img="<?=$publicDomain?>/mobile/img/food1.png">黑色</span>
                    <span data-img="<?=$publicDomain?>/mobile/img/food2.png">红色</span>
                    <span data-img="<?=$publicDomain?>/mobile/img/food3.png">白色</span>
                    <span data-img="<?=$publicDomain?>/mobile/img/food4.png">灰色</span>
                </div>
              </li>
              <li class="sec-items">
                <div>其他分类</div>
                <div>
                    <span>黑色</span>
                    <span class="active">红色</span>
                    <span>白色</span>
                    <span>灰色</span>
                </div>
              </li> -->
             <!--  <li class="sec-items-amount">
                <div class="sec-amount">
                    <div>购买数量<label class="gray">（每人限购24件）</label></div>
                    <div class="sec-add-plus"><span class="plus">-</span><span id="amount">2</span><span class="add">+</span></div>
                </div>
              </li> -->
              <li class="sec-items-blank"></li>
            </ul>
        </div>
       <!--  <div class="am-modal-actions-group">
            <a href="login.html" class="am-btn am-btn-secondary am-btn-block">确 定</a>
        </div> -->
    </div>
    <?php if($show != 1) { ?>
    {include file="Pub/down" /}
    <?php } ?>
    <div data-am-widget="tabs" class="am-tabs am-tabs-d2 am-no-layout">
        <ul class="am-tabs-nav am-cf">
            <li class="am-active">
                <a href="[data-tab-panel-0]">
                    图文详情
                </a>
            </li>
            <li>
                <a href="[data-tab-panel-1]">
                    详细参数
                </a>
            </li>
        </ul>
        <div class="am-tabs-bd" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            <div data-tab-panel-0="" class="am-tab-panel am-active">
                <div id='productView'>
                    <div style='margin:0 auto;font-size:14px;padding-right: 10px; padding-left: 10px;overflow: hidden;'>
                        <?php //print_r($data["data"]['f_appdescription']); ?>  
                        <?php echo $data['description']; ?>
                    </div>
                </div>
           
            </div>
            <div data-tab-panel-1="" class="am-tab-panel ">
                <div style='margin:0 auto;font-size:14px;background-color:#FFF;'>
                    <div class="wrap" >
                        <div class="topnav" id="link10" style="" >
                            <strong style="">品牌：</strong><?=$brand;?>
                        </div>
                        <div class="topnav" id="link10" style="" >
                            <strong style="">上市时间：</strong><?=$addtime;?>
                        </div>

                        <?php foreach($spec as $key=>$value){   ?>
                            <div class="topnav" id="link10" style="" >
                                <strong style=""><?=$value['spec_name'];?>：</strong> <?php foreach($value['value'] as $ka=>$va){ ?>  <?=$va['spec_value'];?> <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(function(){
        $("img").lazyload({
            threshold : 200,
            effect: "fadeIn"
        });
    });
</script>
<script>

$(function(){
     $("#chooseSec").on("click",function(e){
        $("#my-actions").modal('toggle');
    });

    var i = 0;
    var specs = '';
    var spec = '';
    //选择规格
    $(".sec-items").on("click","span",function(){
       
        $(this).addClass('active').siblings().removeClass('active');
        $(this).parent().parent().parent().find("li.sec-items").each(function(){
            spec = $(this).find('span.active').attr('_data');
          
            specs += spec + ',';
           
          
        });
      
          
        $('ul.am-list').find("li.am-modal-actions-header ").each(function(){
            
            var chosspec = $(this).attr('_data') + ',';
           
            if(chosspec == specs){
         
                $('.am-modal-actions-header').hide(); 
               
                $(this).show();
            }

        });
        spec = '';
        specs = '';
    });

    //添加数量
    $(".sec-amount span").on("click",function(){
        var num = parseInt($("#amount").text());
        if($(this).hasClass('plus')){
            if(num>1){
                num--;
            }
        }
        if($(this).hasClass('add')){
            num++;
        }
        $("#amount").html(num);
    });
});
   
</script>
</body>
</html>
