<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$title?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="renderer" content="webkit">
        <meta name="author" content="talon">
        <meta name="application-name" content="niuniuhui-wap">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="format-detection" content="telephone=no" />
        
        {include file="Pub/assetcss" /}
        {include file="Pub/assetjs" /}

    </head>
    <body>
        <div id="app" v-scroll="getMore">

            <!--mall-banner-->
            <section class="mall-banner">
                <!-- Swiper -->
                <div class="swiper-container banner-swiper">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide" v-for="banner in bannerList">
                            <a href="#"><img :src="banner.thumb"/></a>
                        </div>
                       <!--  <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-6.png"/></a></div>
                        <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-5.png"/></a></div>
                        <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-6.png"/></a></div>
                        <div class="swiper-slide"><a href="#"><img src="/mobile/img/mall/banner-5.png"/></a></div> -->
                       
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                 </div>
            </section>
            <!--/end mall-banner-->

            <!--mall-sort-->
            <section class="mall-sort">
                <div class="mall-bar">推荐分类</div>
                <div class="tl-grid sort-list">
                    <div class="tl-grid-1-3" v-for="category in categoryList">
                        <a href="/product/category/index">
                            <img :src="category.category_icon" class="sort-img"/>
                            <p class="sort-name"  v-html="category.name"></p>
                        </a>
                    </div>
                  
                </div>
            </section>
            <!--/end mall-sort-->

            <!--mall-menu-->
            <section class="mall-menu">
                <!-- <div class="tl-grid"> -->

                  <!--   <div class="tl-grid-1-4 one-menu" v-for="category in categoryList">
                        <a href="/product/category/index">
                            <img :src="category.category_icon" class="sort-img"/>
                            <p class="sort-name"  v-html="category.name"></p>
                        </a>
                    </div> -->

                 <!--    <div class="tl-grid-1-4 one-menu">
                        <a href="areaIndex.html">
                            <img src="/mobile/img/icon/niupiaozhuanqu.png"/>
                            <p>牛票专区</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="areaIndex-2.html">
                            <img src="/mobile/img/icon/niupiao_niudou.png"/>
                            <p>牛票+牛豆</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="../topic/topic.html">
                            <img src="/mobile/img/icon/haiwaiziyou.png"/>
                            <p>海外直邮</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="#">
                            <img src="/mobile/img/icon/sannongchanpin.png"/>
                            <p>三农产品</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="#">
                            <img src="/mobile/img/icon/gehumeizhuang.png"/>
                            <p>个护美妆</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="#">
                            <img src="/mobile/img/icon/jiayongdianqi.png"/>
                            <p>家用电器</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="#">
                            <img src="/mobile/img/icon/shoujishuma.png"/>
                            <p>手机数码</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 one-menu">
                        <a href="#">
                            <img src="/mobile/img/icon/niudouzhuanxiang.png"/>
                            <p>牛豆专享</p>
                        </a>
                    </div> -->
                    
               <!--  </div> -->
                <div class="niuren-niuyu">
                    <a href="/Article/Index/articleList?newstype=1"><img src="/mobile/img/icon/niurenniuyu@2x.png"/></a>
                    <div class="hot-news" >
                        
                        <div class="swiper-container news-swiper" >
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><a href="/Article/Index/articleList?newstype=1"><label>美搭</label><p class="tl-ellipsis">服装春季上新，服装春季上新，大牌低至7折服装春季上新，大牌低至7折服装春季上新，大牌低至7折大牌低至7折</p></a></div>
                                <div class="swiper-slide"><a href="/Article/Index/articleList?newstype=1"><label>美搭</label><p class="tl-ellipsis">美妆大促销，满199减100</p></a></div>
                               
                               
                            </div>
                   
                        </div>
                        
                    </div>
                </div>
            </section>
            <!--/end mall-mall-->

         
            
          <!--   <div class="mall-recom">
                
                <a :href="'/product/index/goodsDetail?goods_id='+recomGoods.id" class="recom-good" v-for="recomGoods in recomGoodsLis">
                    <img :src="recomGoods.thumb" />
                    <div class="tl-ellipsis-2 good-name" v-html="recomGoods.productname"></div>
                    <div class="tl-flex base-info">
                        <div class="tl-flex-3 price">
                            ￥<span v-html="recomGoods.prouctprice"></span>
                        </div>
                        <div class="tl-flex-3 buy-num">
                            <span v-html="recomGoods.salecount"></span>人购买
                        </div>
                        <div class="tl-flex-3 stock">
                            库存<span v-html="recomGoods.productstorage"></span>
                        </div>
                        
                    </div>
                </a>
            
            </div> -->
            
            <!--每周好货-->
           <!--  <section class="recom-wrap">
                <div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">每周好货</div>
                </div>
                <div class="weekly-list">
                    <a href="#"><div class="one-good">
                        
                            <img src="/mobile/img/towel-1.png">
                            <p class="g-name ellipsis">通用洗澡大号毛巾通用洗澡大号毛巾</p>
                            <p class="g-price ellipsis">
                                <span class="red">139.00</span>元+<span class="red">2222.00</span>牛豆</p>
                            <p class="mark-price">市场价：<del>100.00</del></p>
                        
                        
                    </div>
                    </a>
                    <div class=" one-good">
                        <a href="#">
                            <img src="/mobile/img/towel-2.png">
                            <p class="g-name ellipsis">新疆长毛绒洗脸方巾</p>
                            <p class="g-price">26.00元</p>
                            <p class="mark-price">市场价：<del>100.00</del></p>
                            
                        </a>
                        
                    </div>
                    <div class="one-good">
                        <a href="#">
                            <img src="/mobile/img/towel-2.png">
                            <p class="g-name ellipsis">纯棉方巾</p>
                            <p class="g-price">26.00元</p>
                            <p class="mark-price">市场价：<del>100.00</del></p>
                        </a>
                        
                    </div>
                    <div class="one-good">
                        <a href="#">
                            <img src="/mobile/img/towel-1.png">
                                <p class="g-name ellipsis">通用洗澡大号毛巾</p>
                            <p class="g-price">139.00元</p>
                        <p class="mark-price">市场价：<del>100.00</del></p>
                        </a>
                        
                    </div>
                    <div class=" one-good">
                        <a href="#">
                            <img src="/mobile/img/towel-2.png">
                            <p class="g-name ellipsis">新疆长毛绒洗脸方巾</p>
                            <p class="g-price">26.00元</p>
                            <p class="mark-price">市场价：<del>100.00</del></p>
                        </a>
                        
                    </div>
                    
                    
                </div>
            </section> -->
            <!--/end 每周好货-->
            
            <!--新品推荐-->
            <section class="recom-wrap">
                <div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">新品推荐</div>
                </div>
                <div class="tl-grid">
                    <div class="new-l">
                        <a href="#">
                            <img src="/mobile/img/mall/xinpin-1.png" />
                        </a>
                    </div>
                    <!--<div class="tl-grid-1-3">
                        <div class="tl-grid">
                            
                                <a href="#">
                                    <img src="/mobile/img/mall/xinpin-2.png" />
                                </a>
                            
                            
                                <a href="#">
                                    <img src="/mobile/img/mall/xinpin-4.png" />
                                </a>
                            
                        </div>
                    </div>
                    
                    <div class="tl-grid-1-3">
                        <div class="tl-grid">
                            
                                <a href="#">
                                    <img src="/mobile/img/mall/xinpin-3.png" />
                                </a>
                            
                            
                                <a href="#">
                                    <img src="/mobile/img/mall/xinpin-5.png" />
                                </a>
                            
                        </div>
                    </div>-->
                    
                    <div class="tl-grid-2-3 new-r">
                        
                            <div class="tl-grid-1-2">
                            <a href="#">
                                <img src="/mobile/img/mall/xinpin-2.png" />
                            </a>
                            </div>
                        <div class="tl-grid-1-2">
                            <a href="#">
                                <img src="/mobile/img/mall/xinpin-3.png" />
                            </a>
                        </div>
                        
                        
                        
                            <div class="tl-grid-1-2">
                                <a href="#">
                                    <img src="/mobile/img/mall/xinpin-4.png" />
                                </a>
                            </div>
                            <div class="tl-grid-1-2">
                                <a href="#">
                                    <img src="/mobile/img/mall/xinpin-5.png" />
                                </a>
                            </div>
                        
                        
                    </div>
                </div>
            </section>
            <!--/end 新品推荐-->
            
            <!--热搜名品-->
            <section class="recom-wrap">
                <div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">热搜名品</div>
                </div>
                <div class="tl-grid">
                    <div class="tl-grid-1-2">   
                        <a href="#">
                            <img src="/mobile/img/mall/koubei-1.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-2">   
                        <a href="#">
                            <img src="/mobile/img/mall/koubei-2.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-2">   
                        <a href="#">
                            <img src="/mobile/img/mall/koubei-3.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-2">   
                        <a href="#">
                            <img src="/mobile/img/mall/koubei-4.png" />
                        </a>    
                    </div>
                </div>
            </section>
            <!--/end 热搜名品-->
            
            <!---->
            <section class="recom-wrap">
                <a href="#">
                    <img src="/mobile/img/mall/banner-7.png" />
                </a>
            </section>
            <!--/end-->
            
            <!--精品优选-->
            <section class="recom-wrap">
                <div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">精品优选</div>
                </div>
                
                <div class="tl-grid preferred-top">
                    <div class="tl-grid-1-2">   
                        <a href="#">
                            <img src="/mobile/img/mall/youxuan-1.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-2">   
                        <a href="#">
                            <img src="/mobile/img/mall/youxuan-2.png" />
                        </a>    
                    </div>
                    
                </div>
                <div class="tl-grid preferred-bot">
                    <div class="tl-grid-1-4">   
                        <a href="#">
                            <img src="/mobile/img/mall/youxuan-3.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-4">   
                        <a href="#">
                            <img src="/mobile/img/mall/youxuan-4.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-4">   
                        <a href="#">
                            <img src="/mobile/img/mall/youxuan-5.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-4">   
                        <a href="#">
                            <img src="/mobile/img/mall/youxuan-6.png" />
                        </a>    
                    </div>
                </div>
            </section>
            <!--/end 精品优选-->
            
            <!--品牌聚惠-->
            <section class="recom-wrap">
                <div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">品牌聚惠</div>
                </div>
                <div class="tl-grid">
                    <div class="tl-grid-1-3">
                        <a href="#">
                            <img src="/mobile/img/mall/pinpai-1.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-3">
                        <a href="#">
                            <img src="/mobile/img/mall/pinpai-2.png" />
                        </a>    
                    </div>
                    <div class="tl-grid-1-3">
                        <a href="#">
                            <img src="/mobile/img/mall/pinpai-3.png" />
                        </a>    
                    </div>
                </div>
            </section>
            <!--/end 品牌聚惠-->

            <!--全部商品-->
            <section class="recom-wrap mall-goods-wrap">
                <!--<div class="recom-bar">
                    <div class="line"></div>
                    <div class="title">全部商品</div>
                </div>-->
                
                <div class="mall-goods-list"  >

                    <a :href="'/product/index/goodsDetail?goods_id='+goods.productid" v-for="goods in goodsList">
                        <div class="one-good">
                            <div class="good-img">
                                <img :src="goods.thumb"  />
                                
                            </div>
                            <div class="good-desc">
                                <div class="tl-ellipsis-2 good-name" v-html="goods.productname"></div>
                                <div class="good-price">
                                    <span class="orange">￥ <span v-html="goods.prouctprice"></span></span>
                                </div>
                            </div>
                        
                        </div>
                    </a>
                    
                  <!--   <a href="#">
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
                    </a> -->
                </div>
            </section>
            <!--/end 全部商品-->
        

        
        <!--导航-->
        <nav class="nav-wrap">
            <div class="nav-item active">
                <a href="/">
                    <span class="mall"></span>
                    <p>商城</p>
                </a>
            </div>
            <div class="nav-item ">
                <a href="/Article/Index/articleList?newstype=1">
                    <span class="news"></span>
                    <p>资讯</p>
                </a>
            </div>
            <div class="nav-item">
                <a href="/user/shopcart/index">
                    <span class="cart"></span>
                    <p>购物车</p>
                </a>
            </div>
                
            <div class="nav-item ">
                <a href="/user/index/index">
                    <span class="niu"></span>
                    <p>我的</p>
                </a>
            </div>  
        </nav>


        </div>


<!--/end 底部导航-->
<script type="text/javascript" src="/mobile/js/swiper.min.js" ></script>
  <!-- Initialize Swiper -->
<script>


  var vm= new Vue({
    el:'#app',
    data:{
        bannerList:[],
        categoryList:[],
        recomGoodsLis:[],
        page:1,
        goodsList:[],
        requestFlag:true,//
        switchShow:false
    },
    mounted:function(){
        var _this = this;
        
        _this.getbannerList();
        _this.getcategoryList();
        _this.getRecomGoods();
        _this.getGoodsList();

        //牛人牛语
        var newsSwiper = new Swiper('.news-swiper', {
            //pagination: '.swiper-pagination',
           autoplay : 3000,
            //loop : true,
            // autoHeight: true,
             height:20,
            slidesPerGroup : 2,
            direction : 'vertical'
            
            
        });
        

    },
    methods:{
        getbannerList:function(){
            var _this = this;
            
            _this.$http.post('/index/index/getbannerlist',{

            }).then(
                function (res) {
                    // 处理成功的结果
                    //console.log(res);
                    //console.log("=============");
                    data = eval("("+res.body+")");
                    
                    if(data.code=='200'){
                        _this.bannerList = data.data;

                     Vue.nextTick(function () {
                        // DOM 更新了
                        //  //banner 轮播
                        var bannerSwiper = new Swiper('.banner-swiper', {
                            pagination: '.swiper-pagination',
                            autoplay : 3000,
                            paginationClickable: true
                            
                        });   
                    })

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
        getcategoryList:function(){
            var _this = this;
            
            _this.$http.post('/index/index/getcategorylist',{

            }).then(
                function (res) {
                    // 处理成功的结果
                    //console.log(res);
                    //console.log("=============");
                    data = eval("("+res.body+")");
                    
                    if(data.code=='200'){
                        _this.categoryList = data.data;    
                       
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
        getRecomGoods:function(){
            var _this = this;
            
            _this.$http.post('/index/index/getonerecom',{

            }).then(
                function (res) {
                    // 处理成功的结果
                    //console.log(res);
                    //console.log("=============");
                    data = eval("("+res.body+")");
                    
                    if(data.code=='200'){
                        _this.recomGoodsLis = data.data;    
                       
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
        // 自定义方法
        getMore: function() {
            var _this = this;
          
            _this.switchShow = !_this.switchShow;
          
            if(_this.requestFlag){


                _this.page++;
                if(_this.page<=_this.maxPage){
                    _this.getGoodsList();

                }else{
                    _this.switchShow = false;
                    $(".loading").hide();
                    return ;
                }
            }
            // _this.getStoList(_this.page);
           
        },
        getGoodsList:function(){
                    var _this = this;

                    _this.requestFlag=false;
                    _this.$http.post('/index/index/goodslist',{
                        page:_this.page,
                        
                    }).then(
                        function (res) {
                            // 处理成功的结果
                            //console.log(res);
                            //console.log("=============");
                            data = eval("("+res.body+")");
                            
                            if(data.code=='200'){
                                _this.requestFlag=true;
                                if(_this.page > 1){
                                    _this.goodsList = _this.goodsList.concat(data.data.list);
                                }else{
                                    _this.goodsList = data.data.list;
                                }
                                _this.maxPage = data.data.maxPage;
                                _this.switchShow = !_this.switchShow; 

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
    },
    directives: { // 自定义指令
        scroll: {
            bind: function(el, binding) {
                window.addEventListener('scroll', function() {
                    if(document.body.scrollTop + window.innerHeight >= el.clientHeight) {
                        var fnc = binding.value;
                        fnc();
                    }
                })
            }
        }
    }
  });
</script>

    </body>
</html>


