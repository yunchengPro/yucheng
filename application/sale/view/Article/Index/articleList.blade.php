{include file="Pub/header" /}


	<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/information.css" />




	<header class="information-type page-header">
        
         <div class="page-bar">
        
            <a href="/index/index/index">
                <img src="/mobile/img/icon/back@2x.png" class="back-ico">
            </a>
            
            <div class="bar-title">新闻资讯</div>
            
        </div>
        
   		<div class="type-list">
   			<div class="one-type" v-for="cate in category">
   				<a href="javascript:;" v-on:click="getCateArticle(cate.id)" v-if="cate.id == categoryid" class="active" v-html="cate.categoryname"></a>
                <a href="javascript:;" v-on:click="getCateArticle(cate.id)" v-else  v-html="cate.categoryname" ></a>

   			</div>
          
   		</div>
    </header>
	<section class="information-list" id="info-list" v-scroll="getMore">
   		<a  v-bind:href="'/article/index/articleDetail?aid='+article.id" v-for="article in articleList">
	   		<div class="one-info">
	   			<div class="info-title">
	   				<div class="tl-ellipsis-2 main-title" v-html="article.title"> </div>
	   				<div class="tl-ellipsis sub-title" v-html="article.shorttitle">
	   					
	   				</div>
	   			</div>
	   			<div class="thumbnail">
	   				<img :src="article.thumb" />
	   			</div>
	   			
	   		</div>
   		</a>
   	
   		<p class="no-data">亲！没有数据了哦~</p>
		
	 </section>
             <!--导航-->
        <nav class="nav-wrap">
            <div class="nav-item ">
                <a href="/">
                    <span class="mall"></span>
                    <p>商城</p>
                </a>
            </div>
            <div class="nav-item active">
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
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=dcc7a8bcf5dd15a02336fa3fa263d724"></script>
	<script>
            
 		var Vm = new Vue({
                el: '#app',
                data: {
                    // 页面约定的参数
                    apiUrl:"/article/index/getArticleList",
                    page:1,   
                    city_id:'<?=$city_id?>',
                    maxPage:'',
                    articleList:[],
                    category:[],
                    categoryid:'',
                    newstype:"<?=$newstype?>"
                },
                created:function(){
                   
                    

                },
                mounted: function() {
                    var _this=this;
                    // // 页面初始化时执行的方法
                    // if(_this.city_id==''){
                    //     _this.maps();
                    // }else{
                       _this.getArticleList(_this.page); 
                    // }
                   
                   
                },
                methods: {
                    
                    maps:function(){
                        var _this = this;
                       
                        //加载地图，调用浏览器定位服务
                        _this.map = new AMap.Map('container', {
                            resizeEnable: true
                        });
                        _this.map.plugin('AMap.Geolocation', function() {
                            _this.geolocation = new AMap.Geolocation({
                                enableHighAccuracy: true,//是否使用高精度定位，默认:true
                                timeout: 10000,          //超过10秒后停止定位，默认：无穷大
                                buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
                                zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
                                buttonPosition:'RB'
                            });
                            _this.map.addControl(_this.geolocation);
                            _this.geolocation.getCurrentPosition();

                            AMap.event.addListener(_this.geolocation, 'complete', _this.onComplete);//返回定位信息
                            AMap.event.addListener(_this.geolocation, 'error', _this.onError);      //返回定位出错信息
                        });
                    },
                    onComplete:function(data){
                       
                        var _this = this;
                        var str='定位成功';
                        
                        _this.lngx =  data.position.getLng();
                        _this.laty =  data.position.getLat();
                       
                        _this.getCityInfo();
                        _this.getArticleList(_this.page);
                    },
                    onError:function(data){
                        var str='定位失败';
                        var _this = this;
                        _this.lngx =  '113.94303';//data.position.getLng();
                        _this.laty =  '22.54023';//data.position.getLat();
                        _this.city_id ='440305';
                        _this.getCityInfo();
                        _this.getArticleList(_this.page);
                    },getCityInfo:function(){
                        var _this=this;
                       
                        _this.$http.post('/index/index/getLngLatAddress',{
                            
                            lngx:_this.lngx,
                            laty:_this.laty
                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                               
                                data = eval("("+res.body+")");
                                //console.log(data);
                                if(data.code=='200'){
                                    _this.city = data.data.city;
                                    _this.city_id = data.data.citycode;
                                }else{
                                    layer.open({
                                        content: data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                }   
                               
                            },function (res) {
                                // 处理失败的结果
                                console.log(res);
                            }
                        );

                    },
                    // 自定义方法
                    getMore: function() {
                        var _this = this;
                      
                        _this.switchShow = !_this.switchShow;
                        _this.page++;
                        
                        if(_this.page<=_this.maxPage){
                            _this.getArticleList();
                        }else{
                            _this.switchShow = false;
                            $(".loading").hide();
                            return ;
                        }
                        // _this.getStoList(_this.page);
                       
                    },
                    getCateArticle:function(categoryid){
                        var _this=this;
                        _this.page = 1;
                        _this.categoryid = categoryid;
                        _this.getArticleList();
                       
                        
                    },
                    getArticleList:function(){
                        var _this=this;


                        _this.$http.post(_this.apiUrl,{

                            categoryid:_this.categoryid,
                            page:_this.page,
                            newstype:_this.newstype,
                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                                //console.log("=============");
                                data = eval("("+res.body+")");
                                console.log(data);

                                if(data.code=='200'){
                               
                                    _this.category  = data.data.category;
                                    if(_this.page > 1){
                                        _this.articleList = _this.articleList.concat(data.data.article.list);
                                    }else{
                                        _this.articleList = data.data.article.list;
                                    }
                                    _this.categoryid = data.data.categoryid;
                                    //alert(res.body);
                                    //_this.personInfo=res.body.personInfo;
                                    _this.maxPage = data.data.article.maxPage;
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
                                console.log(res);
                            }
                        );
                    }
                    
                },directives: { // 自定义指令
                    scroll: {
                        bind: function(el, binding) {
                            window.addEventListener('scroll', function() {
                                if(document.body.scrollTop + window.innerHeight+20 >= el.clientHeight) {
                                    var fnc = binding.value;
                                    fnc();
                                }
                            })
                        }
                    }
                }
            });
        
        </script>
{include file="Pub/tail" /}