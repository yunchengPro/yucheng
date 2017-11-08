<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>商品详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
    <link rel="stylesheet" href="/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="/mobile/css/talon_3g.css" />
    <link rel="stylesheet" href="/mobile/css/my-app.css">
    <link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/swiper.min.css"/>
    {include file="Pub/assetjs" /}
        

<style>

        
    .pro-detail-bar{
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 200;
        height: 40px;
        /*line-height: 35px;*/
        background: transparent;
        display: -webkit-box;
        padding: 0 10px;
        -webkit-box-pack: justify;
        -webkit-box-align: center;
    }
    .pro-detail-bar .back{
        background: url(/mobile/img/icon/back_bg@2x.png) no-repeat;
        background-size:100% ;
        width: 29px;
        height: 29px;
        display: block;
    }
    .pro-detail-bar .buycard{
        background: url(/mobile/img/icon/buycar_bg@2x.png) no-repeat;
        background-size:100% ;
        width: 29px;
        height: 29px;
        display: block;
    }
    
    
    .reward-bar{
        position: absolute;
        bottom: 0;
        background: url(/mobile/img/icon/rewrad-2.png) no-repeat;
        background-size:100% ;
        height: 40px;
        width: 100%;
        z-index: 20;
        display: -webkit-box;
        -webkit-box-align: center;
        padding-left: 10px;
        color: #FFFFFF;
        
    }
    .reward-bar .sp-val{font-size: 16px;margin-left: 10px;}
    .detail-footer{
            position: fixed;
            width: 100%;
            bottom: 0;
            z-index: 100;
            height: 44px;
           
            display: -webkit-box;
            -webkit-box-pack: center;
            -webkit-box-align: center;
             text-align: center;
            background: #FFFFFF;
            border-top: 0.5px solid #DDDDDD;
    }
    .detail-footer img{width: 20px;margin-top: 5px;}
    .detail-footer p{font-size: 10px;}
    .detail-footer .join-btn{
        background: #F09533;color: #FFFFFF;height: 44px;line-height: 44px;
    }
    .detail-footer .buy-btn{
        background: #F13437;color: #FFFFFF;height: 44px;line-height: 44px;
    }
    .detail-footer .collect{
        width: 20px;
        height: 20px;
        display: inline-block;
        background: url(/mobile/img/icon/star@2x.png) no-repeat;
        background-size:100% ;
    }
    
    .detail-footer .left{height: 44px;}
    .detail-footer .left .tl-flex-1{
        border-right: 0.5px solid #DDDDDD;
    }
    .am-slider-default{margin: 0;}
    .flex-column {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-box-pack: center;
    -webkit-box-align: center;
    text-align: center;
}


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
    <!--<header data-am-widget="header" class="am-header am-header-default am-header-fixed">
        <div class="am-header-left am-header-nav">
            <a href="index.html" class="">
                <i class="am-icon-angle-left am-icon-md gray"></i>
            </a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link">商品详情</a>
        </h1>
    </header>-->
    <div id="app">
    <div class="pro-detail-container">
         <div class="pro-detail-bar">
            <a href="/index/index/index" class="back"></a>
            <a href="/user/shopcart/index" class="buycard"></a>
        </div>
        <div class="pro-detail-item">
               <div class="swiper-container">
                    <div class="swiper-wrapper" >

                        <div class="swiper-slide" v-for="banner in banners">
                            <img :src="banner.productpic"/>
                        </div>
                       
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
          
              
                <div class="am-g pro-txt">
                    <div class="am-u-sm-12" v-html="goodsDetail.productname" ></div>
                   
                </div>
                <div class="am-g">
                    <div class="am-u-sm-12">
                        <span class="red price" v-html="goodsDetail.prouctprice"></span>元
                    </div>
                </div>
                <div class="am-g gray express">
                  <!--   <div class="am-u-sm-4">快递：0元</div>
                    <div class="am-u-sm-4 text-center">月销：29笔</div>
                    <div class="am-u-sm-4 text-right">广东深圳</div> -->
                </div>
        </div>
        <div class="pro-detail-item" id="chooseSec">
            <div class="choose-nav am-g">
                <nav class="am-u-sm-6">选择规格</nav>
                <nav class="am-u-sm-6 text-right"><i class="icon-right" style="position: relative;top: .4rem;"></i></nav>
            </div>
        </div>
        <!--<div class="pro-detail-item pro-detail-judge">
            <div>宝贝评价（109）</div>
            <div class="cust-line">张三疯0091</div>
            <div>
                样子很漂亮，材质很好，感觉很耐用。性价比也很高，虽然包装不够结实但还好为损坏，给个好评！
            </div>
            <div class="gray">颜色分类：黑色</div>
            <div class="text-center">
                <a href="login.html" class="getmore">查看全部评论</a>
            </div>
        </div>-->
        <div class="pro-detail-item">
        <!--     <div class="pro-store-img">
                <img src="/mobile/img/icon/LOGO2.png" />
                <div class="store-title">
                    &nbsp;&nbsp;格兰仕官方旗舰店
                </div>
            </div>
            <div class="am-g flex-center store-star">
                <div class="am-u-sm-6 flex-column">
                    <span class="font-16">22</span>
                    <div>宝贝数量</div>
                </div>
                <div class="am-u-sm-6 flex-column">
                    <span class="font-16">48</span>
                    <div>评价星级</div>
                </div>
            </div>
            <div class="am-g flex-center text-center store-btns">
                <!--<div class="am-u-sm-2"></div>-->
              <!--   <div class="am-u-sm-6">
                    <a href="login.html" class="getmore">查看分类</a>
                </div>
                <div class="am-u-sm-6">
                    <a href="index.html" class="getmore">进店逛逛</a>
                </div>
                
            </div> --> 
        </div>
    </div>
    
    <div data-am-widget="tabs" class="am-tabs am-tabs-d2 am-no-layout">
        <ul class="am-tabs-nav am-cf">
            <li class="am-active">
                <a href="[data-tab-panel-0]">
                    图文详情
                </a>
            </li>
            <li>
                <a href="[data-tab-panel-1]">
                    产品参数
                </a>
            </li>
        </ul>
        <div class="am-tabs-bd" >
            <div data-tab-panel-0="" class="am-tab-panel am-active">
               <div id="productView">
                    <div v-html="goodsDetail.description">
                              
                        </div>
                </div>
           
            </div>
            <div data-tab-panel-1="" class="am-tab-panel ">
                <div style="margin:0 auto;font-size:14px;background-color:#FFF;">
                    <div class="wrap">
                        <div class="topnav" id="link10" style="">
                            <strong style="">品牌：</strong>Midea/美的                       
                        </div>
                        <div class="topnav" id="link10" style="">
                            <strong style="">上市时间：</strong>2017-07-24 15:49:00                        
                        </div>

                        <div class="topnav" id="link10" style="">
                            <strong style="">规格：</strong>    1.5L容量                            
                        </div>
                    </div>
                </div>
        </div>
    </div>
    </div>
    
    <div class="am-modal-actions" id="my-actions">
        <div class="am-modal-actions-group">
            <ul class="am-list">
                    <?php foreach($sku_data['sku'] as $key => $value){ ?>

                        <li <?php if($key == 0 ){ ?> style="display: block;" <?php }else{ ?> style="display: none;" <?php }  ?>  class="am-modal-actions-header " _data="<?=$value['f_productspec']?>" _skuid="<?=$value['id']?>">
                            <div class="sec-choose">
                                <div id="showBox">
                                    <img src="<?=$value['productimage']?>" />
                                </div>
                                <div>
                                    <div><span class="red"><?=$value['prouctprice']?></span><small>元</small></div>
                                    <div>库存<?=$value['productstorage']?>件</div>
                                 <!--    <div>请选择颜色</div> -->
                                </div>
                            </div>
                        </li>
                      <?php } ?>

                    <?php foreach($sku_data['spec'] as $sku){ ?>
                            <li class="sec-items">
                                <div><?=$sku['spec_name']?></div>
                                <div>
                                    <?php foreach($sku['value'] as $spk => $spec){ ?>
                                        <span <?php if($spk == 0) { ?> class="active" <?php } ?> _data="<?=$spec['id']?>" ><?=$spec['spec_value']?></span>
                                    <?php }?>
                                   
                                </div>
                            </li>
                    <?php }?>
                   
                    <li class="sec-items-amount">
                    <div class="sec-amount">
                        <div>购买数量<label class="gray"></label></div>
                        <div class="sec-add-plus"><span class="plus">-</span><span id="amount">1</span><span class="add">+</span></div>
                    </div>
                    </li>
                    <li class="sec-items-blank"></li>
            </ul>
        </div>
        <div class="am-modal-actions-group">
            <a href="#" class="am-btn am-btn-secondary am-btn-block" v-on:click="addshopcart()">确 定</a>
        </div>
    </div>
    
    
    <footer class="detail-footer tl-flex">
        <div class="tl-flex-5 tl-flex left">
            <div href="#" class="tl-flex-1">
                <a href="#"><img src="/mobile/img/icon/mgs_on@2x.png" /><p>客服</p></a>
            </div>
            <div href="#" class="tl-flex-1">
                <a href="index.html"><img src="/mobile/img/icon/shop@2x.png" /><p>店铺</p></a>
            </div>
            <div href="#" class="tl-flex-1">
                <a id="collect-no" href="javascript:collect(1)" style="display: block;">
                    <img src="/mobile/img/icon/star@2x.png" />
                
                    <p>收藏</p>
                </a>
                <a id="collect-yes" href="javascript:collect(0)" style="display: none;">
                    <img src="/mobile/img/icon/star_on@2x.png" />
                
                    <p>收藏</p>
                </a>
            </div>
        </div>
        
        <div class="tl-flex-3 tl-flex">
            <div class="tl-flex-1 join-btn" >加入购物车</div>
            <div class="tl-flex-1 buy-btn">立即购买</div>
        </div>
    </footer>
    </div>
   <!--<div class="download-bar">
        <div class="close-box">
            <a href="javascript:void(0)">
                <img src="/mobile/img/icon/download_close@2x.png" />
            </a>
        </div>
        <div class="bar-content">
            <div class="logo-box"><img src="/mobile/img/icon/LOGO2.png" /></div>
            <div class="bar-desc">
                <div>
                    <div>你买单，我送钱</div>
                    <div>赶快下载<label class="red">牛牛汇</label>手机客户端</div>
                </div>
                <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt">点击下载</a>
            </div>
            
        </div>
    </div>-->
    <script>
        function collect(type){
            var t=type;
            if(t==0){
                alert("取消收藏成功");
                $("#collect-no").show();
                $("#collect-yes").hide();
            }else{
                alert("收藏成功");
                $("#collect-no").hide();
                $("#collect-yes").show();
            }
        }
        $(function(){
//          $(".download-bar .close-box a").click(function(){
//              $(this).parent().parent().fadeOut();
//          });


            $(window).scroll(function() {
                
                if( $(window).scrollTop()>40){
                    $(".pro-detail-bar").css({"background":"#fff","border-bottom":"1px solid #ddd"});
                }else{
                    $(".pro-detail-bar").css({"background":"transparent","border-bottom":"0"});
                }
            });
            
            
            
        });
    </script>
    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/swiper.min.js"></script>
    <script>
           new Vue({
            el:'#app',
            
            data:{
                goods_id:"<?=$goods_id?>",
                goodsDetail:[],
                banners:[],
                buygoods:0
            },
            created:function(){
                
                var _this=this;
                
                _this.getGoodsDetail();
            },
            methods:{
               
                getGoodsDetail:function(){
                    var _this = this;

                    _this.$http.post('/Product/Index/goodsDetailData',{
                        
                        goods_id:_this.goods_id
                    }).then(
                        function (res) {
                            // 处理成功的结果
                            //console.log(res);
                            //console.log("=============");
                            data = eval("("+res.body+")");
                            
                            if(data.code=='200'){
                               
                                _this.goodsDetail = data.data;
                                _this.banners = data.data.bannerimg;

                            }else{
                                 
                                layer.open({
                                    content: data.msg,
                                    skin: 'msg',
                                    time: 2 
                                });
                                return false;
                            }
                            //$("#city").val('');
                        },function (res) {
                            // 处理失败的结果
                             
                            layer.open({
                                content: '加载数据错误！请重新请求',
                                skin: 'msg',
                                time: 2 
                            });
                        }
                    );
                },
                addshopcart:function(){
                  
                    var obj = $(".sec-items").find('span.active');
                   
                    var sku_arr =  $(".am-modal-actions-group .am-list").find('li.am-modal-actions-header ');
                  
                    var skuid = sku_arr.attr('_skuid');
                    var productid = "<?=$goods_id?>";
                    var productnum = parseInt($("#amount").text());

                    var _this = this;
                   
                    if(_this.buygoods !=1 ){
                        _this.$http.post('/user/shopcart/addgoods',{
                            
                            skuid:skuid,
                            productid:productid,
                            productnum:productnum
                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                                //console.log("=============");
                                data = eval("("+res.body+")");
                                
                                if(data.code=='200'){
                                   
                                   window.location.href = "/user/shopcart/index";

                                }else{
                                     
                                    layer.open({
                                        content: data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                }
                                //$("#city").val('');
                            },function (res) {
                                // 处理失败的结果
                                 
                                layer.open({
                                    content: '加载数据错误！请重新请求',
                                    skin: 'msg',
                                    time: 2 
                                });
                            }
                        );
                    }else{
                      
                        window.location.href='/order/index/showorder?skuid='+skuid+'&productnum='+productnum;
                        return false;
                    }
                }
               
            },
            mounted:function(){
                var specs = '';
                var spec = '';
                var _this = this;
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

                $("#chooseSec,.join-btn").on("click",function(e){
                    _this.buygoods = 0;
                    $("#my-actions").modal('toggle');
                });
                $(".buy-btn").on("click",function(e){
                    _this.buygoods = 1;
                    $("#my-actions").modal('toggle');
                });
              
            },
            watch:{
                banners:function(){
                        this.$nextTick(function () {
                             var swiper = new Swiper('.swiper-container', {
                                pagination: '.swiper-pagination',
                                autoplay : 3000,
                                paginationClickable: true
                             });

                        });
                    }
                    
            },
            directives: { // 自定义指令
              
            }
          });
    </script>
</body>

   


</html>
