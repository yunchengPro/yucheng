{include file="Pub/header" /}
            
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
                
                <div class="tl-grid sort-list">
                    <div class="tl-grid-1-4 am-text-center" >
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food1.png" class="sort-img"/>
                            <p class="sort-name">服装</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food2.png" class="sort-img"/>
                            
                            <p class="sort-name">美肤</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                    <div class="tl-grid-1-4 am-text-center">
                        <a href="/product/index/categorylist">
                            <img src="/mobile/img/food3.png" class="sort-img"/>
                            <p class="sort-name">数码</p>
                        </a>
                    </div>
                </div>
            </section>
            
            <div class="mall-recom">
                
                <a class="recom-good" href="/product/index/goodsdetail">
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
                
                <a class="recom-good" href="/product/index/goodsdetail">
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
                    <a href="#" v-for="goods in goodslist">
                        <div class="one-good">
                            <div class="good-img">
                                <img :src="goods.thumb" />
                                  
                            </div>
                            <div class="good-desc">
                                <div class="tl-ellipsis-2 good-name" v-html="goods.productname"></div>
                                <div class="good-price">
                                <span class="orange">￥<span v-html="goods.prouctprice"></span></span>
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
{include file="Pub/tail" /}
        
        <!--/end 底部导航-->
        
<script type="text/javascript" src="/mobile/js/swiper.min.js" ></script>
  <!-- Initialize Swiper -->
<script>


   new Vue({
    el:'#app',
    data:{
        goodslist:[],
        page:1,
        switchShow:false, 
    },

    methods:{
        getgoodslist:function(){
            
            var _this=this;

            _this.$http.post('/product/index/goodslist',{
                page:_this.page,
            }).then(
                function (res) {
                    var _this=this;
                    data = eval("("+res.body+")");
                    //data = cl(res);
                    if(data.code=='200'){
                            
                        if(_this.page > 1){
                            _this.goodslist = _this.buyconlist.concat(data.data.list);
                        }else{
                            _this.goodslist = data.data.list;
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
                    //console.log(res);
                    layer.open({
                        content: '加载数据错误！请重新请求',
                        skin: 'msg',
                        time: 2 
                    });
                }
            );
        },
    },
    mounted:function(){
        
        //banner 轮播
        var bannerSwiper = new Swiper('.banner-swiper', {
            pagination: '.swiper-pagination',
           // autoplay : 3000,
            paginationClickable: true
            
        });
        var _this = this;
        _this.getgoodslist();
    }
  });
</script>
