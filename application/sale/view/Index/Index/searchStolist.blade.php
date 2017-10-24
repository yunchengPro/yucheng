{include file="Pub/header" /}
		
		<header class="store-search">
            <div class="search-bar">
    			<a v-bind:href="'/index/index/index?city_id='+city_id" class="go-back" v-if="city_id != ''" ></a>
                <a v-bind:href="'/index/index/index'" class="go-back" v-else ></a>
    			<div>
    				<input type="search" id="search" value="" class="search-inp" placeholder="店铺名称或分类、商圈" autofocus="autofocus"/>
    			</div>
    			<span class="search" v-on:click="searchAction()" >搜索</span>
            </div>
            <div class="tl-grid category-wrap">
                <div :class="['tl-grid-1-4',{'active':curtab==1}] " @click="tab(1)">
                    <span class="c-name">{{tabtxt[0]}}</span>
                    <i></i>
                    <div class="filter-wrap cate-wrap">
                        <div class="filter-left">
                          
                            
                            <div :class="['l-item',{'active':leftcur==index}]" v-for="(item,index) in cates" @click="tabCate(item.cateId)">{{item.cateName}}</div>
                        </div>
                        <div class="filter-right">
                          
                            
                            <div :class="['r-item',{'active':rightcur==index}]" @click="tabCateChild($event,index,cur.cid)" v-for="(cur,index) in curCates">{{cur.cname}}</div>
                        </div>
                    </div>
                </div>
                <div :class="['tl-grid-1-4',{'active':curtab==2}] "  @click="tab(2)">
                    <span class="c-name">{{tabtxt[1]}}</span>
                    <i></i>
                    <div class="filter-wrap cate-wrap">
                        <div class="tl-grid filter-bar">
                            <div :class="['tl-grid-1-2',{'active':curNearbytab==0}]" @click="nearByTab(0)">
                                <span>行政区域</span>
                            </div>
                            <div :class="['tl-grid-1-2',{'active':curNearbytab==1}]" @click="nearByTab(1)">
                                <span>地铁</span>
                            </div>
                        </div>
                        <div class="filter-left">
                           
                            <div :class="['l-item',{'active':localLeft==index}]" v-for="(item,index) in nearbys" @click="tabNearby(index)">{{item.local}}</div>
                        </div>
                        <div class="filter-right">
                          
                            
                            <div :class="['r-item',{'active':localRight==index}]" @click="queryNearby($event,index,item.localId,item.localtype)" v-for="(item,index) in curLocals">{{item.area}}</div>
                        </div>
                    </div>
                </div>
                <div :class="['tl-grid-1-4',{'active':curtab==3}] " @click="tab(3)">
                    <span class="c-name">{{tabtxt[2]}}</span>
                    <i></i>
                    <div class="filter-wrap">
                       
                        <div :class="['f-item',{'active':sortcur==index}]" v-for="(sortItem,index) in smartSorts" @click="sort($event,index)">{{sortItem.name}}</div>
                        
                    </div>
                </div>
                <div :class="['tl-grid-1-4',{'active':curtab==4}] " @click="tab(4)">
                    <span class="c-name">筛选</span>
                    <i></i>
                    <div class="filter-wrap trade-wrap">
                      
                        <div class="trade-item" v-for="(fitem, index) in filters" >
                                <label :class="{'i-checked':fitem.i_check}" @click="tabCheck($event,index)">
                                <div>{{fitem.type}}</div>
                                <!--<input type="checkbox" :checked="fitem.i_check" :value="fitem.val"/>-->
                                <i></i>
                            </label>
                    
                        </div>
                        <div class="trade-item trade-oper">
                            <button class="tl-fl btn-1" @click="uncheckAll">重置</button>
                            <button class="tl-fr btn-2" @click="queryStoreList(4)">确定</button>
                        </div>
                    </div>
                </div>
            </div>
		</header>
        <div class="tl-select-mask" v-show="showMask" @click="closeMask"></div>
        <?php if(empty($categoryid)){ ?>
    		<section class="hot-list-wrap" v-if="businessList == ''" >
    			<div class="hot-tip">热门搜索</div>
    			<div class="hot-list">
                    <?php foreach($keywords as $value){ ?>
                        <a v-on:click="searchAction('<?=$value?>')" class="one-hot"><?=$value?></a>
                    <?php } ?>
    			
    			</div>
    		</section>
    		<!-- <section class="histroy-wrap">
    			<div class="history-head">
    				<span class="his-tip">历史搜索</span>
    				<span class="remove">清空历史</span>
    			</div>
    			<div class="history-list">
    				<div class="histroy-item">
    					健身房
    				</div>
    				<div class="histroy-item">
    					婚纱店
    				</div>
    			</div>
    		</section> -->
		<?php } ?>
        <section class="banner">
            <!-- Swiper -->
            <div class="swiper-container">
                <div class="swiper-wrapper" >

                    <div class="swiper-slide" v-for="banner in banners">
                        <a :href="banner.url"><img :src="banner.thumb"/></a>
                    </div>
                   
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </section>
        <!--/end banner-->
        <!--menu-->
        <section class="menu-wrap" id="menu"  v-if="menus != ''">
            
            <div class="menu-list" >
                <div class="one-menu" v-for="menu in menus">
                    
                    <a :href="'/index/index/searchStolist?categoryid='+menu.categoryid+'&city_id='+city_id">
                        <img v-bind:src="menu.category_icon" />
                        <p v-html="menu.categoryname"></p>
                    </a>
                    
                </div>
            </div>
        </section>
		 <div class="list"  v-scroll="getMore">
                <!--附近商家-->
                <section class="nearby-shops">
                    <div class="tip-box">
                        <span class="title">附近商家</span>
                        <span class="line"></span>
                    </div>
                    <div class="shops-list">
                        
                        <div class="one-shop" v-for="business in businessList">  
                            <a v-bind:href="'/index/index/storeindex?storeid='+business.id" >
                                <div class="tl-grid-2-5">
                                    <img :src="business.businesslogo" />
                                </div>  
                                <div class="tl-grid-3-5">
                                    <div class="item">
                                        <span class="name" v-html="business.businessname">
                                        </span>
                                        <span class="tl-fr time" v-html="business.busstartime+'-'+business.busendtime"></span>
                                    </div>
                                    <div class="item">
                                        <span class="star" v-for="scores in business.scores_arr"></span>
                                       
                                        <span class="star-half" v-if="business.scores_half == 1"></span>
                                        <span v-html="business.scores"></span>分
                                        
                                        
                                        送<span class="tl-fr red" v-html="business.reutnproportion"></span>牛豆
                                    </div>
                                    <div class="item">
                                        <span class="addr"></span>
                                        <span v-html="business.area"></span>
                                        <span v-html="business.categoryname"></span>
                                        <span class="tl-fr" v-html="business.distance"></span>
                                    </div>
                                    <div class="item">
                                        <span v-if="business.isdelivery == 1" ><span class="song">送</span>起送<span v-html="business.delivery" ></span>元</span>
                                        <span v-if="business.actualfreight <= 0">
                                          免费配送
                                        </span>
                                        <span v-else>
                                          配送费<span v-html="business.actualfreight" ></span>元
                                        </span>
                                    </div>
                                </div> 
                            </a>    
                        </div>
                        <div>
                            <div>
                              
                                <p class="loading" v-show="!switchShow">没有更多了...</p>
                            </div>
                        </div>
                </section>
            </div>
             <!--底部导航-->
            {include file="Pub/navigation" /}
            <!--/end 底部导航-->
        {include file="Pub/tail" /}
        <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=dcc7a8bcf5dd15a02336fa3fa263d724"></script>
        <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/swiper.min.js" ></script>
		<script>
            
            var Vm = new Vue({
                el: '#app',
                data: {
                    // 页面约定的参数
                    apiUrl:"/index/index/getSearchStolist",
                    page:1,                 
                    banners:[],
                    city:'',
                    businessList:[],
                    menus: [
                              
                        ],
                    switchShow:false, 
                    lngx:'',
                    laty:'',
                    map:{},
                    geolocation:{},
                    city_id:'<?=$city_id?>',
                    metro_id:'',
                    maxPage:'',
                    keywords:$("#search").val(),
                    categoryid:"<?=$categoryid?>",
                    order:'',
                    curtab:0,
                    tabtxt:["分类","附近","智能排序"],
                    leftcur:-1,//分类左边
                    rightcur:-1,//分类右边
                    sortcur:-1,//智能排序
                    showMask:false,//蒙蔽显示
                    localLeft:-1,
                    localRight:-1,
                    curNearbytab:0,
                    smartSorts:[
                        {"name":'离我最近'},    
                        {"name":'销量最好'},
                        {"name":'评分最高'},
                        {"name":'折扣最优'}
                    ],
                    filters:[
                        {"type":"正在营业","i_check":true,"val":1},
                        {"type":"有免费WIFI","i_check":false,"val":2},
                        {"type":"可免费停车","i_check":false,"val":3},
                        {"type":"支持外送","i_check":false,"val":4},
                        {"type":"全部","i_check":false,"val":5}
                    ],
                    cates:[],
                    nearbys:[],
                    regions:[],
                    subway:[],
                    curCates:[],
                    curLocals:[],
                    isdelivery:'',
                    isparking:'',
                    iswifi:'',
                    isbusiness:''
                },
                created:function(){
                   
                    
                    this.curCates=this.cates[0].childs;
                    this.nearbys=this.regions;
                    this.curLocals=this.nearbys[0].childs;

                },
                mounted: function() {
                    var _this=this;
                    _this.page = 1;
                    // 页面初始化时执行的方法
                    _this.returnCategory();
                    _this.returnRegions();
                    _this.returnSubway();
                    if(_this.categoryid > 0){
                        // _this.maps();
                        // _this.getCategory();
                         // 页面初始化时执行的方法
                        if(_this.city_id =='' || _this.lngx=='' || _this.lngx=='' ){
                            
                            _this.maps();
                            _this.getCategory();
                        }else{

                            _this.getCityInfo();
                            _this.getStoList(_this.page);
                            _this.getCategory();
                        }
                    }
                   
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
                methods: {
                        // 过滤条件 tab 切换
                        tab:function(idx){
                            
                            this.curtab=idx;
                            this.showMask=true;
                        },
                        //分类左边
                        tabCate:function(idx){
                            this.leftcur=idx-1;
                            this.curCates=this.cates[idx-1].childs;
                        },
                        //分类-右边
                        tabCateChild:function(e,idx,cid){
                           
                            var _this = this;
                            _this.rightcur=idx-1;
                            _this.queryStoreList(1);
                            var dom=e.target;
                            _this.tabtxt[0]=dom.innerText;
                            _this.categoryid = cid;
                            _this.page = 1;
                            _this.getStoList(_this.page);
                        },
                        nearByTab:function(tabIdx){
                            this.curNearbytab=tabIdx;
                            if(tabIdx==0){
                                this.nearbys=this.regions;
                            }else{
                                this.nearbys=this.subway;
                            }
                            
                            this.curLocals=this.nearbys[0].childs;
                        },
                        //附近左边点击
                        tabNearby:function(idx){
                            this.localLeft=idx;
                            this.curLocals=this.nearbys[idx].childs;
                            
                        },
                        queryNearby:function(e,idx,city_id,localtype){
                            
                            var _this = this;
                            _this.localRight=idx;
                            var dom=e.target;
                            _this.tabtxt[1]=dom.innerText;
                           // _this.queryStoreList(2);
                            if(localtype == 1){
                                _this.city_id = city_id;
                            }
                            if(localtype == 2){
                                _this.metro_id = city_id;
                            }
                            _this.page = 1;
                            _this.getStoList(_this.page);
                        },
                        
                        //智能排序
                        sort:function(e,idx){
                            var _this = this;
                            _this.sortcur=idx;
                            var dom=e.target;
                            _this.tabtxt[2]=dom.innerText;
                            
                            if(idx == 0){
                                _this.order = '';
                            }
                            if(idx == 1){
                                _this.order = 'salecount';
                            }
                            if(idx == 2){
                                _this.order = 'scores';
                            }
                            if(idx == 3){
                                _this.order = 'reutnproportion';
                            }
                            _this.page = 1;
                            _this.showMask=false;
                            event.stopPropagation();
                            _this.curtab=0;
                            _this.getStoList(_this.page);

                        },
                        //筛选  --单项选择事件
                        tabCheck:function(event,index){

                            event.stopPropagation();
                            this.filters[index].i_check=!this.filters[index].i_check;

                        },
                        //筛选 -重置
                        uncheckAll:function(){
                            for(var i in this.filters){
                                this.filters[i].i_check=false;

                            }
                        },
                        
                        queryStoreList:function(idx){
                            //tab 红色消除
                            this.showMask=false;
                            event.stopPropagation();
                            this.curtab=0;
                            for(var i in this.filters){

                                if(this.filters[i].i_check==true){
                                    if(this.filters[i].val == 1){
                                        this.isbusiness = 1;
                                    }
                                    if(this.filters[i].val == 2){
                                        this.iswifi = 1;
                                    }
                                    if(this.filters[i].val == 3){
                                        this.isparking = 1;
                                    }
                                    if(this.filters[i].val == 4){
                                        this.isdelivery = 1;
                                    }
                                }else{
                                    if(this.filters[i].val == 1){
                                        this.isbusiness = -1;
                                    }
                                    if(this.filters[i].val == 2){
                                        this.iswifi = -1;
                                    }
                                    if(this.filters[i].val == 3){
                                        this.isparking = -1;
                                    }
                                    if(this.filters[i].val == 4){
                                        this.isdelivery = -1;
                                    }
                                }
                            }
                            this.page = 1;
                            this.getStoList(this.page);
                            
                        },
                        closeMask:function(){
                            this.showMask=false;
                            this.curtab=0;
                        },
                        returnCategory:function(){
                            var _this = this;
                        
                            _this.$http.post('/index/index/returnCategory',{
                               

                            }).then(
                                function (res) {
                                    // 处理成功的结果
                                    //console.log(res);
                                    //console.log("=============");
                                    data = eval("("+res.body+")");
                                    
                                    if(data.code=='200'){
                                        _this.cates = data.data;    
                                        _this.curCates =_this.cates[0].childs;
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
                        returnRegions:function(){
                            var _this = this;
                           
                            _this.$http.post('/index/index/returnRegions',{
                                city_id:_this.city_id,

                            }).then(
                                function (res) {
                                    // 处理成功的结果
                                    //console.log(res);
                                    //console.log("=============");
                                    data = eval("("+res.body+")");
                                    
                                    if(data.code=='200'){
                                        _this.regions = data.data; 
                                        _this.nearbys=_this.regions;
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
                        returnSubway:function(){
                            var _this = this;
                           
                            _this.$http.post('/index/index/returnSubway',{
                                city_id:_this.city_id,

                            }).then(
                                function (res) {
                                    // 处理成功的结果
                                    //console.log(res);
                                    //console.log("=============");
                                    data = eval("("+res.body+")");
                                    
                                    if(data.code=='200'){
                                        _this.subway = data.data; 
                                        _this.nearbys=_this.subway;
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
                    searchAction:function(value=''){

                        loadtip({
                            content:'加载中'
                        });
	                	$(".hot-list-wrap").hide();

	                	var _this=this;
                        _this.page  = 1;
	                    // 页面初始化时执行的方法
                        if(value == ''){
	                       _this.keywords = $("#search").val();
                        }else{
                            _this.keywords = value;
                        }
                        if(_this.city_id==''){
	                       _this.maps();
                        }else{
                            _this.getStoList(_this.page);
                        }
                        _this.getCategory();
                        
	                },
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
                        _this.getStoList(_this.page);
                    },
                    onError:function(data){
                        var str='定位失败';
                        var _this = this;
                        _this.lngx =  '113.94303';//data.position.getLng();
                        _this.laty =  '22.54023';//data.position.getLat();
                        if(_this.city_id == ''){
                            _this.city_id ='440305';
                        }
                        _this.getCityInfo();
                        _this.getStoList(_this.page);
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
                                    if(_this.city_id == ''){
                                        _this.city_id = data.data.citycode;
                                    }
                                    _this.getStobanner();
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
                                //console.log(res);
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
                        _this.page++;
                        
                        if(_this.page<=_this.maxPage){
                             _this.getStoList(_this.page);
                        }else{
                            _this.switchShow = false;
                            $(".loading").hide();
                            return ;
                        }
                        // _this.getStoList(_this.page);
                       
                    },
                    getStobanner:function(){
                        var _this = this;
                        
                        _this.$http.post('/index/index/getStobanner',{
                            city_id:_this.city_id,

                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                                //console.log("=============");
                                data = eval("("+res.body+")");
                                
                                if(data.code=='200'){
                                    _this.banners = data.data;    
                                   
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
                    },
                    getStoList:function(){
                        var _this=this;

                        
                        _this.$http.post(_this.apiUrl,{
                            metro_id: _this.metro_id,
                            city_id:_this.city_id,
                            lngx:_this.lngx,
                            laty:_this.laty,
                            page:_this.page,
                            keywords:_this.keywords,
                            categoryid:_this.categoryid,
                            order:_this.order,
                            isbusiness:_this.isbusiness,
                            isdelivery:_this.isdelivery,
                            isparking:_this.isparking,
                            iswifi:_this.iswifi
                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                                //console.log("=============");
                                data = eval("("+res.body+")");

                                loadtip({
                                    close:true,
                                    //alert:'查找成功'
                                });

                                if(data.code=='200'){
                                //console.log(data.data.businessList);
                                    if(_this.page == 1){
                                        _this.businessList = data.data.business.list;
                                    }else{
                                        _this.businessList = _this.businessList.concat(data.data.business.list);
                                    }
                                    //alert(res.body);
                                    //_this.personInfo=res.body.personInfo;
                                    _this.maxPage = data.data.business.maxPage;
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
                    getCategory:function(){
                        var _this = this;
                        
                        _this.$http.post('/index/index/getStoCategory',{
                           

                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                                //console.log("=============");
                                data = eval("("+res.body+")");
                                
                                if(data.code=='200'){
                                    _this.menus = data.data;    
                                   
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


		
		
		

