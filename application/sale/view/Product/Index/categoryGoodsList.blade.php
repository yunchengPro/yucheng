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
	  	<link rel="stylesheet" href="/mobile/css/mall_wap.css" />
	    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/jquery.min.js" ></script>
	    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/vue.min.js" ></script>
	    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/tools.js" ></script>
	    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/vue-resource.min.js" ></script>
		<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/layer.js"></script>
		<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/jquery.lazyload.js"></script>
		<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
		<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/common.js"></script>
	</head>
	<body>
		<div id="app">
			<header class="mall-goods-search">
				<div class="search-bar">
					<a href="#" class="go-back"></a>
					<div>
						<input type="search" class="search-inp" placeholder="店铺名称或分类、商圈" value="川菜"/>
					</div>
					<span class="search">搜索</span>
				</div>
				<div class="tl-grid category-wrap">
					<div :class="['tl-grid-1-3',{'active':curtab==1}] " @click="tab(1)">
						<span class="c-name">{{tabtxt[0]}}</span>
						<i></i>
						<div class="filter-wrap cate-wrap">
							<div class="filter-left">
								
								
								<div :class="['l-item',{'active':leftcur==index}]" v-for="(item,index) in cates" @click="tabCate(item.cateId)">{{item.cateName}}</div>
							</div>
							<div class="filter-right">
								
								
								<div :class="['r-item',{'active':rightcur==index}]" @click="tabCateChild($event,cur.cid)" v-for="(cur,index) in curCates">{{cur.cname}}</div>
							</div>
						</div>
					</div>
					<div :class="['tl-grid-1-3',{'active':high2low}] "  @click="tab(2)">
						<span class="c-name">{{tabtxt[1]}}</span>
						<i></i>
						
					</div>
					<div :class="['tl-grid-1-3',{'active':curtab==3}] " @click="tab(3)">
						<span class="c-name">{{tabtxt[2]}}</span>
						<i></i>
						<div class="filter-wrap">
							
							<div :class="['f-item',{'active':sortcur==index}]" v-for="(sortItem,index) in smartSorts" @click="sort($event,index)">{{sortItem.name}}</div>
							
						</div>
					</div>
					
				</div>
			</header>
			<div class="tl-select-mask" v-show="showMask" @click="closeMask"></div>
			
			
			<!--全部商品-->
			<section class="mall-goods-wrap" style="margin-top: 85px;">
				<!--<div class="recom-bar">
					<div class="line"></div>
					<div class="title">全部商品</div>
				</div>-->
				
				<div class="mall-goods-list"  v-scroll="getMore">

					<a v-bind:href="'/product/index/goodsDetail?goodsid='+goods.productid" v-for="goods in goodsList">
						<div class="one-good">
							<div class="good-img">
								<img  :src="goods.thumb"  />
								<!-- <div class="reward tl-flex">
									<div class="tl-flex-1">赠消费金<span>20.00</span></div>
									<div  class="tl-flex-1">牛豆<span>46.00</span></div>
								</div> -->
							</div>
							<div class="good-desc">
								<div class="tl-ellipsis-2 good-name" v-html="goods.productname"></div>
								<div class="good-price">
									<span class="red" v-html="goods.prouctprice" ></span>元
								</div>
							</div>
						
						</div>
					</a>

				</div>
			</section>
			<!--/end 全部商品-->
		
		

		
	</div>
		
		<!--底部导航-->
		<style>
			.nav-wrap {
				height: 50px;
				border-top: 1px solid #DDDDDD;
				position: fixed;
				width: 100%;
				bottom: 0;
				display: -webkit-box;
				background: #FFFFFF;
			}
			
			.nav-wrap .nav-item {
				display: -webkit-box;
				-webkit-box-flex: 1;
				text-align: center;
				-webkit-box-pack: center;
				-webkit-box-align: center;
				width: 0;
			}
			
			.nav-wrap .nav-item.active p {
				color: #F13437;
			}
			
			.nav-wrap .nav-item span {
				height: 30px;
				width: 30px;
				display: block;
			}
			
			.nav-wrap .nav-item .shop {
				background: url(/mobile/img/icon/ic_store@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item.active .shop {
				background: url(/mobile/img/icon/ic_store_pressed@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item .bag {
				background: url(/mobile/img/icon/ic_commodity@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item.active .bag {
				background: url(/mobile/img/icon/ic_commodity_pressed@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item .rang {
				background: url(/mobile/img/icon/ic_message@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item.active .rang {
				background: url(/mobile/img/icon/ic_message_pressed@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item .niu {
				background: url(/mobile/img/icon/ic_mine_eye@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item.active .niu {
				background: url(/mobile/img/icon/ic_mine_eye_pressed@2x.png) no-repeat;
				background-size: 100%;
			}
			
			.nav-wrap .nav-item .logo {
				width: 55px;
				position: absolute;
				left: 50%;
				margin-left: -26.5px;
				bottom: 3px;
			}
			
			.niu-area{
				display: none;
				position: fixed;
			    bottom: 0;
			    z-index: 100;
			    width: 100%;
			    background: transparent;
			}
			.area-list{
				display: -webkit-box;
				width: 100%;
				-webkit-box-pack: center;
			}
			.area-list .one-area{
				-webkit-box-flex: 1;
				display: -webkit-box;
				width: 100%;
				-webkit-box-pack: center;
				-webkit-box-align: center;
				    text-align: center;
				
			}
			.area-list .one-area img{
				width: 64px;
			}
			.area-list .one-area p{
				color: #FFFFFF;
				text-align: center;
				font-size: 14px;
				margin-top: 5px;
			}
			.area-colse{
				    text-align: center;
				    margin-top: 30px;
			}
			.area-colse img{
				width: 55px;
			}
		</style>
		<nav class="nav-wrap">
			<div class="nav-item "><a href="#">
				<span class="shop"></span>
				<p>牛店</p>
			</a></div>
			<div class="nav-item active"><a href="#">
				<span class="bag"></span>
				<p>牛品</p>
			</a></div>
			<div class="nav-item"><a href="javasrcipt:void(0)">
				<img src="/mobile/img/icon/ic_home_logo@2x.png" class="logo"/>
			</a></div>
			<div class="nav-item"><a href="#">
				<span class="rang"></span>
				<p>消息</p>
			</a></div>
			<div class="nav-item "><a href="#">
				<span class="niu"></span>
				<p>我的</p>
			</a></div>
			
			
		</nav>
		
		
		<section class="tl-mask" id="area-mask"></section>
		<section class="niu-area">
			<div class="area-list">
				<div class="one-area">
					<a href="#">
						<p><img src="/mobile/img/icon/ic_division_cash@2x.png" /></p>
						<p>牛票专区</p>
					</a>
				</div>
				<div class="one-area">
						<a href="#">
						<p><img src="/mobile/img/icon/ic_division_dou@2x.png" /></p>
						<p>牛豆专区</p>
					</a>
				</div>
				<div class="one-area">
						<a href="#">
						<p><img src="/mobile/img/icon/ic_division_cash_dou@2x.png" /></p>
						<p>牛票+牛豆专区</p>
					</a>
				</div>
			</div>
			<div class="area-colse">
				<img src="/mobile/img/icon/ic_home_close@2x.png" id="close-x"/>
			</div>
		</section>
		<script>
		 $(function(){
	    	$(".logo").on("click",function(){
	    		$("#area-mask,.niu-area").show();
	    	});
	    	$("#area-mask,#close-x").click(function(){
	    		$("#area-mask,.niu-area").hide();
	    	});
	    });
		</script>
		<!--/end 底部导航-->
		
		
		
		<script>
		   new Vue({
            el:'#app',
            
            data:{
            	curtab:0,
            	tabtxt:["全部","价格","所有"],
            	leftcur:-1,//分类左边
            	rightcur:-1,//分类右边
            	sortcur:-1,//智能排序
            	showMask:false,//蒙蔽显示
            	high2low:false,//默认从低到高
            	switchShow:false, 
            	page:1,
            	maxPage:'',
            	categoryid:0,
            	smartSorts:[
					{"name":'全部'},	
					{"name":'牛票专区'},
					{"name":'牛票+牛豆专区'}
       			],
            	filters:[
            		{"type":"正在营业","i_check":true,"val":1},
            		{"type":"有免费WIFI","i_check":false,"val":2},
            		{"type":"可免费停车","i_check":false,"val":3},
            		{"type":"支持外送","i_check":false,"val":4},
            		{"type":"全部","i_check":false,"val":5}
            	],
            	cates:[],
            	curCates:[],
            	goodsList:[]

            },
            created:function(){
          		
          		var _this=this;
          		
				_this.getCategory();
				_this.getGoodsList();
            },
            methods:{
            	// 过滤条件 tab 切换
            	tab:function(idx){
            		
            		this.curtab=idx;
            		
            		if(idx==2){
            			//按价格排序
            			this.showMask=false;
            			this.high2low=!this.high2low;
            			
            		}else{
            			this.showMask=true;
            		}
            		
            	},
            	//分类左边
            	tabCate:function(idx){
            		
            		this.leftcur=idx-1;
            		this.rightcur=-1;
            		this.curCates=this.cates[idx].childs;
            	},
            	//分类-右边
            	tabCateChild:function(e,idx){
            		
            		this.rightcur=idx;
            		this.queryStoreList(1);
            		var dom=e.target;
            		this.tabtxt[0]=dom.innerText;
            		this.categoryid = idx;
            		this.page = 1;
            		this.getGoodsList();
            	},
            	
            	
            	
            	//智能排序
            	sort:function(e,idx){
            		this.sortcur=idx;
            		var dom=e.target;
            		this.tabtxt[2]=dom.innerText;
            		this.queryStoreList(3);
            	},
            	
            	
            	queryStoreList:function(idx){
            		//tab 红色消除
            		this.showMask=false;
            		event.stopPropagation();
            		this.curtab=0;
            	},
            	closeMask:function(){
            		this.showMask=false;
            		this.curtab=0;
            	},
            	getCategory:function(){
                    var _this = this;
                    
                    _this.$http.post('/Product/Category/getCategory',{
                       

                    }).then(
                        function (res) {
                            // 处理成功的结果
                            //console.log(res);
                            //console.log("=============");
                            data = eval("("+res.body+")");
                            
                            if(data.code=='200'){
                                _this.cates = data.data;    
                               	_this.curCates=_this.cates[0].childs;

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
                getGoodsList:function(){
                	var _this = this;

                	_this.$http.post('/Product/Index/goodsList',{
                    	page:_this.page,
                    	categoryid:_this.categoryid
                    }).then(
                        function (res) {
                            // 处理成功的结果
                            //console.log(res);
                            //console.log("=============");
                            data = eval("("+res.body+")");
                            
                            if(data.code=='200'){
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
                 // 自定义方法
                getMore: function() {
                    var _this = this;
                  
                    _this.switchShow = !_this.switchShow;
                  
                    _this.page++;
                    if(_this.page<=_this.maxPage){
                        _this.getGoodsList(_this.page);

                    }else{
                        _this.switchShow = false;
                        $(".loading").hide();
                        return ;
                    }
                    // _this.getStoList(_this.page);
                   
                }
            },
            watch:{
            	
            		
            },
            directives: { // 自定义指令
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
	</body>
</html>
