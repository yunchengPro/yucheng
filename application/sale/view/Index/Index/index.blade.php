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
            
            <div class="mall-recom">
                
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
              <!--   
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
                </a> -->
                
            </div>
            
            
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


