<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>牛牛汇首页</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="renderer" content="webkit">
        <meta name="author" content="talon">
        <meta name="application-name" content="niuniuhui-wap">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="format-detection" content="telephone=no" />
        
        <link rel="stylesheet" href="/mobile/css/amazeui.min.css" />
        <link rel="stylesheet" type="text/css" href="/mobile/css/talon_3g.css"/>
        <link rel="stylesheet" type="text/css" href="/mobile/css/swiper.min.css"/>
       <link rel="stylesheet" type="text/css" href="/mobile/css/mall_wap.css"/>
       <link rel="stylesheet" type="text/css" href="/mobile/css/navigation.css"/>
        <script type="text/javascript" src="/mobile/js/jquery.min.js" ></script>
        <script type="text/javascript" src="/mobile/js/vue.min.js" ></script>
        <style>
            
       </style>
    </head>
    <body>
        <div id="app">
            
            <!--mall-banner-->
            <section class="mall-banner">
                <!-- Swiper -->
                <div class="swiper-container banner-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-6.png"/></a></div>
                       <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-5.png"/></a></div>
                        <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-6.png"/></a></div>
                          <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-5.png"/></a></div>
                       
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                 </div>
            </section>
            <!--/end mall-banner-->
            
            <section class="mall-sort">
                <div class="mall-bar">推荐分类</div>
                <div class="tl-grid sort-list">
                    <div class="tl-grid-1-3">
                        <a href="#">
                            <img src="/mobile/img/food1.png" class="sort-img"/>
                            <p class="sort-name">服装</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-3">
                        <a href="#">
                            <img src="/mobile/img/food2.png" class="sort-img"/>
                            
                            <p class="sort-name">美肤</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-3">
                        <a href="#">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                </div>
            </section>
            
            <div class="mall-recom">
                
                <a class="recom-good">
                    <img src="/mobile/img/mall/banner-5.png" />
                    <div class="tl-ellipsis-2 good-name">DW 手表 百达翡丽iPone X Ipnone8 iPhone7 iPhone6S iPhone6 iPhone5  买不起啊</div>
                    <div class="tl-flex base-info">
                        <div class="tl-flex-3 price">
                            ￥100.00
                        </div>
                        <div class="tl-flex-3 buy-num">
                            2000人购买
                        </div>
                        <div class="tl-flex-3 stock">
                            库存200
                        </div>
                        
                    </div>
                </a>
                
                <a class="recom-good">
                    <img src="/mobile/img/mall/banner-6.png" />
                    <div class="tl-ellipsis-2 good-name">DW 手表 百达翡丽iPone X Ipnone8 iPhone7 iPhone6S iPhone6 iPhone5  买不起啊</div>
                    <div class="tl-flex base-info">
                        <div class="tl-flex-3 price">
                            ￥100.00
                        </div>
                        <div class="tl-flex-3 buy-num">
                            2000人购买
                        </div>
                        <div class="tl-flex-3 stock">
                            库存200
                        </div>
                        
                    </div>
                </a>
                
            </div>
            
            
            <!--全部商品-->
            <section class="recom-wrap mall-goods-wrap">
                <!--<div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">全部商品</div>
                </div>-->
                
                <div class="mall-goods-list">
                    <a href="#">
                        <div class="one-good">
                            <div class="good-img">
                                <img src="/mobile/img/food1.png" />
                                
                            </div>
                            <div class="good-desc">
                                <div class="tl-ellipsis-2 good-name">iPhone x那么贵，卖肾都买不起了，还是买个200块的诺基亚玩玩算了</div>
                                <div class="good-price">
                                    <span class="orange">￥66.00</span>
                                </div>
                            </div>
                        
                        </div>
                    </a>
                    
                    <a href="#">
                        <div class="one-good">
                            <div class="good-img">
                                <img src="/mobile/img/food2.png" />
                                <div></div>
                            </div>
                            <div class="good-desc">
                                <div class="tl-ellipsis-2  good-name">iPhone x</div>
                                <div class="good-price">
                                    <span class="orange">￥66.00</span>
                                </div>
                            </div>
                        
                        </div>
                    </a>
                    
                    <a href="#">
                        <div class="one-good">
                            <div class="good-img">
                                <img src="/mobile/img/food3.png" />
                                
                            </div>
                            <div class="good-desc">
                                <div class="tl-ellipsis-2  good-name">法林三氟拉</div>
                                <div class="good-price">
                                    <span class="orange">￥66.00</span>
                                </div>
                            </div>
                        
                        </div>
                    </a>
                    
                    <a href="#">
                        <div class="one-good">
                            <div class="good-img">
                                <img src="/mobile/img/food3.png" />
                                    
                            </div>
                            <div class="good-desc">
                                <div class="tl-ellipsis-2  good-name">iPhone x</div>
                                <div class="good-price">
                                    <span class="orange">￥66.00</span>
                                </div>
                            </div>
                        
                        </div>
                    </a>
                </div>
            </section>
            <!--/end 全部商品-->
            
            
            
            
            
            
            
            
            
            
            
        
        
        
        
        
        

        
        <!--导航-->
        <nav class="nav-wrap">
            <div class="nav-item active">
                <a href="../mallIndex.html">
                    <span class="mall"></span>
                    <p>商城</p>
                </a>
            </div>
            <div class="nav-item">
                <a href="../usercenter/shoppingCart.html">
                    <span class="cart"></span>
                    <p>购物车</p>
                </a>
            </div>
                
            <div class="nav-item ">
                <a href="../usercenter/index.html">
                    <span class="niu"></span>
                    <p>我的</p>
                </a>
            </div>  
        </nav>
        
        
        <!--/end 底部导航-->
        
        <script type="text/javascript" src="/mobile/js/swiper.min.js" ></script>
          <!-- Initialize Swiper -->
        <script>
       
        
           new Vue({
            el:'#app',
            data:{
            
            },
            mounted:function(){
                
                //banner 轮播
                var bannerSwiper = new Swiper('.banner-swiper', {
                    pagination: '.swiper-pagination',
                   // autoplay : 3000,
                    paginationClickable: true
                    
                });

            }
          });
    </script>
    </body>
</html>
