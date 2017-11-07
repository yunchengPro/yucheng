
{include file="Pub/header" /}

<header class="page-header">
		
		<div class="page-bar">
		
		<a href="/index/index/index">
			<img src="/mobile/img/icon/back@2x.png" class="back-ico">
		</a>
		
		<div class="bar-title">购物车({{goodsSum}})</div>
		
	</div>
</header>


<div class="shopping-cart-list">
	<section class="shopping-goods" v-for="(item,index) in carts.goodsList">
		<div class="shop-bar">
			<div>
				<i  v-bind:class="{'checkbox':true,'checked':item.isChecked}" @click="selectShopGoods(item)"></i>
				<a href="#"><span>{{item.businessname}}</span><i class="ico"></i></a></div>
			<div @click="edit(item)" v-show="item.isShow==void 0">编辑</div>
			<div @click="complete(item)" v-show="item.isShow">完成</div>
		</div>
		
		
		<div class="goods-info-box" v-for="(good,idx) in item.goodsData">
			<div class="input-box">
				<i  v-bind:class="{'checkbox':true,'checked':good.isChecked}" @click="selectGood(item,good)"></i>
			</div>
			
			<a class="goods-img" :href="'/product/index/goodsDetail?goods_id='+good.productid">
				<img :src="good.productimage" />
			</a>
			<a class="goods-info" v-show="item.isShow==void 0">
				<div>
					<div class="tl-ellipsis-2 good-name">{{good.productname}}</div>
					<div class="c-999 sepcifi" :id="good.cartid" >{{good.spec}}</div>
				</div>
				<div>
					<div class="text-r">X{{good.productnum}}</div>
					<div class="price"><span class="red">{{good.prouctprice}}</span>元</div>
				</div>
			</a>
				
			<div class="rechoice"  v-show="item.isShow">
				
				<div class="pieces">
					<div class="mins" @click="minsGoods(good)"></div>
					<div class="num">{{good.productnum}}</div>
					<div class="plus" @click="plusGoods(good)"></div>
				</div>
				<div class="norm"><a href="#" class="tl-ellipsis-2	" >{{good.spec}}</a><i @click="showNorms(good.productid,good.cartid)"></i></div>
				
				
			</div>
			<div class="delete" @click="deleteGoods(item,good)" v-show="item.isShow">删除</div>
		</div>
		
	</section>
	
	
</div>

<footer class="cart-footer">
	<div class="cart-l">
		<label @click="selectAll"><i v-bind:class="{'checkbox':true,'checked':isSelectAll}" ></i>全选</label>
	</div>
	<div class="cart-r">
		<div>
			合计：<span class="red">{{totalPrice}}</span>元
		</div>
		<div class="settlement">
			<a href="#" @click="addorder()">
				<div>结算(<span id="settleNum">{{checkNum}}</span>)</div>
				<div class="fee">不含邮费</div>
			</a>
		</div>
	</div>
	
</footer>


<div class="tl-select-mask" v-show="normMaks" @click="closeNorms"></div>
<div class="norm-box"  v-show="normMaks">
	<div>
		<div v-for="sku in goodNorm.sku">
			<div >
				<img :src="sku.productimage" class="good-img" />
			</div>
			<div class="norm-good-info" >
				<div class="red" v-html="sku.prouctprice"></div>
				<div >剩余：<span v-html="sku.productstorage"></span>件</div>
			</div>
		</div>
		<div class="norm-list">
			<div class="norm-item" v-for="spec in goodNorm.spec">
				<div v-html="spec.spec_name"></div>
				<div class="norms">
					<span v-for="(spec_value,idx) in spec.value"  :class="{'active':spec_value.check==idx}"  @click="chioceNorm(spec_value,idx)" :_id="spec_value.id">
						{{spec_value.spec_value}}
					</span>
				</div>
			</div>
			
		</div>
	
		<button class="norm-btn" @click="sureNrom">确定</button>
	</div>
</div>


			
{include file="Pub/tail" /}
		
			    
<style>
	body{background: #FFFFFF;}
	.shopping-cart-list{margin-bottom: 50px;}
	.shopping-cart-list>.shopping-goods{/*margin-bottom: 9px;*/}
	.shop-bar{display: -webkit-box;-webkit-box-pack: justify;height: 44px;-webkit-box-align: center;padding: 0 12px;}
	.shopping-cart-list i.checkbox{-webkit-appearance: none;width: 14px;height: 14px;background: url(/mobile/img/icon/check_none@2x.png) no-repeat;background-size:100% ;position: relative;    display: -webkit-inline-box;}
	.shopping-cart-list i.checked{-webkit-appearance: none;width: 14px;height: 14px;background: url(/mobile/img/icon/checked@2x.png) no-repeat;background-size:100% ;position: relative;}
	.shop-bar  i.checkbox{top: 2px;}
	.shop-bar i.ico{background: url(/mobile/img/icon/more.png) no-repeat; background-size:100% ;width: 6px;height: 10px;display: inline-block;margin-left: 8px;}
	.shopping-cart-list>.shopping-goods>.goods-info-box{background: #eee;padding:8px 12px;display: -webkit-box;/*height: 85px;*/position: relative;}
	.shopping-cart-list>.shopping-goods>.goods-info-box{border-bottom: 1px solid #FFFFFF;}
	.shopping-cart-list>.shopping-goods>.goods-info-box:last-child{border-bottom: none;}
	.goods-info-box>.input-box{width: 20px;display: -webkit-box;-webkit-box-align: center;}
	.goods-info-box>.goods-img{display: -webkit-box;-webkit-box-flex: 3;width: 100%;}
	.goods-info-box>.goods-img img{padding-top: 12px;}
	.goods-info-box>.goods-info{display: -webkit-box;-webkit-box-flex: 1;width: 100%;-webkit-box-orient: vertical;padding-left: 10px;-webkit-box-pack: justify;}
	.goods-info-box>.goods-info .good-name{font-size: 14px;}
	.goods-info-box>.goods-info .sepcifi{font-size: 12px;}
	
	.cart-footer{position: fixed;bottom: 0;width: 100%;height: 50px;line-height: 50px;display: -webkit-box;background: #FFFFFF;-webkit-box-pack: justify;border-top: 0.5px solid #E5E5E5;}
	.cart-footer i.checkbox{-webkit-appearance: none;width: 14px;height: 14px;background: url(/mobile/img/icon/check_none@2x.png) no-repeat;background-size:100% ;position: relative;top: 3px;margin-right: 5px;    display: -webkit-inline-box;}
	.cart-footer i.checked{-webkit-appearance: none;width: 14px;height: 14px;background: url(/mobile/img/icon/checked@2x.png) no-repeat;background-size:100% ;position: relative;}
	.cart-footer .cart-l{margin-left: 12px;}
	.cart-footer .cart-r{display: -webkit-box;}
	.cart-footer .settlement{
		width: 80px;
	    text-align: center;
	    background: #34aedd;
	    color: #fff;
	    font-size: 14px;
	    line-height: 18px;
	    padding-top: 10px;
	    margin-left: 10px;
	}
	.cart-footer .settlement a{color: #FFFFFF;}
	.cart-footer .settlement .fee{font-size: 10px;}
	
	.rechoice{
		    margin-right: 55px;
	    display: -webkit-box;
	    -webkit-box-flex: 2;
	    width: 100%;
	    -webkit-box-orient: vertical;
	    padding-left: 10px;
	}
	.goods-info-box .delete{
		width: 60px;
    position: absolute;
    right: 0;
    height: 100%;
    top: 0;
    /* margin-left: 60px; */
    display: -webkit-box;
    -webkit-box-pack: center;
    -webkit-box-align: center;
    background: #34aedd;
    color: #fff;
    font-size: 14px;
	}
	.pieces{
		height: 35px;
	    line-height: 35px;
	    
	    font-size: 17px;
	    border-bottom: 1px solid #ddd;
	    display: -webkit-box;
	    -webkit-box-pack: justify;
	}
	.pieces .mins{height: 33px;width: 33px;background: url(/mobile/img/icon/mins@2x.png) 50% no-repeat;background-size:20px ;}
	.pieces .plus{height: 33px;width: 33px;background: url(/mobile/img/icon/plus@2x.png) 50% no-repeat;background-size:20px ;}
	.pieces .num{    border-left: 1px solid #DDDDDD;
    border-right: 1px solid #DDDDDD;
    width: 100%;
    display: -webkit-box;
    -webkit-box-flex: 1;
    -webkit-box-pack: center;
    -webkit-box-align: center;
    margin-bottom: 5px;
    }
    .norm{padding: 10px 15px 0 0px;position: relative;}
    .norm i {
    	width: 14px;
    	height: 7px;
    	display: block;
    	position: absolute;
    	right: 0;
    	background: url(/mobile/img/icon/down@2x.png) no-repeat;
    	background-size:100% ;
    }
    
	.norm-box{background: #fff;position: fixed;width: 100%;z-index: 200;height: 400px;bottom: 0;}
	.norm-box img.good-img{width: 100px;position: absolute;top: 20px;left: 25px;}
	.norm-box .norm-btn{background: #F13437;width: 100%;height: 44px;color: #fff;position: fixed;bottom: 0;}
	.norm-box .norm-good-info{margin-left: 150px;margin-top: 20px;line-height: 30px;font-size: 14px;}
	.norm-list{margin-top: 20px;}
	.norm-list .norm-item{border-bottom: 1px solid #DDDDDD;padding-left: 25px;padding-top: 5px;}
	.norm-list .norm-item:last-child{border-bottom: none;}
	.norm-list .norm-item .norms{line-height:35px;}     
	.norm-list .norm-item span{background: #EEEEEE;padding: 5px; border-radius: 4px;margin-right:10px ;font-size: 10px;}
	.norm-list .norm-item span.active{background: #F13437;color: #FFFFFF;} 
</style>
<script type="text/javascript">
	/*
	 * talon 2017-10-19 15:55:58
	 * 购物车vue 基本交互
	 */
	var vm = new Vue({
		el: '#app',
		data: {
			goodsSum:0,
			isSelectAll:false,
			carts:[],
			goodNorm:[],
			normMaks:false,
			oldcartid:0,
			productnum:1,
			buyIds:[]
		},
		mounted: function() {
			var _this = this;
        
        	_this.getgoodsList();
			
		},
		
		computed:{
			//合计总价
			totalPrice:function(){
				var _this=this;
				var total = 0;//_this.carts.cardata.pricecount;
				
				if(this.carts.goodsList){

					this.carts.goodsList.forEach(function(shop){
						
						shop.goodsData.forEach(function(good){
							if(good.isChecked){
								total += parseFloat(good.prouctprice) * parseInt(good.productnum);
								_this.buyIds.push(good.skuid);
							}	
						});

					});
				}
				return total;
			},
			//结算商品数量
			checkNum:function(){
				
				var num = 0;
				
				if(this.carts.goodsList){
					this.carts.goodsList.forEach(function(shop){
						
						shop.goodsData.forEach(function(good){
							if(good.isChecked){
								num+=  parseInt(good.productnum);
							}	
						});

					});
				}
				return num;
			}
		},
		methods: {
			
			//单个商品选择
			selectGood:function(shop,goodObj,gIdx){
				if(goodObj.isChecked == void 0){
					this.$set(goodObj,"isChecked",true)
				} else {
					goodObj.isChecked = !goodObj.isChecked;
				}
				this.isAllShopGoods(shop,goodObj);
				this.isCheckAll();
			},
			//选择单个商品是否是商店商品全部
			isAllShopGoods:function(shop,goodObj){
				var flag = true;
				shop.goodsData.forEach(function(good){
					if(!good.isChecked){
						flag = false;
					}
				});
				if(!flag){
					shop.isChecked = false;
				} else {
					shop.isChecked = true;
				}
			},
			//选择店铺下的所有商品
			selectShopGoods:function(shop){

				if(shop.isChecked == void 0){
					this.$set(shop,"isChecked",true)
				} else {
					shop.isChecked = !shop.isChecked;
				}
				
				shop.goodsData.forEach(function(good){
					if(shop.isChecked){
						//good.isChecked = true;
						vm.$set(good,"isChecked",true)
					}else{
						good.isChecked = false;
					}
					
				});
				
				this.isCheckAll();
			},
			//检测是否已全选
			isCheckAll:function(){
				var flag = true;
				this.carts.goodsList.forEach(function(shop){
					shop.goodsData.forEach(function(good){
						if(!good.isChecked){
							flag = false;
						}
					});
					
				});
				if(!flag){
					this.isSelectAll = false;
				} else {
					this.isSelectAll = true;
				}
			},
			//全选
			selectAll:function(){
				
				this.isSelectAll = !this.isSelectAll;
				this.carts.goodsList.forEach(function(shop){
					if(vm.isSelectAll){
						if(shop.isChecked == void 0||shop.isChecked==false){
							vm.$set(shop,"isChecked",true)
						}
						//shop.isChecked=true;
					}else{
						shop.isChecked=false;
					}
					
					shop.goodsData.forEach(function(good){
						if(vm.isSelectAll){
							//good.isChecked = true;
							vm.$set(good,"isChecked",true)
						}else{
							good.isChecked = false;
						}
						
					});
					
				});
			},
			//编辑
			edit:function(shop){
				if(shop.isShow==void 0){
					this.$set(shop,"isShow",true);
				}
			},
			//完成
			complete:function(shop){
				if(shop.isShow){
					this.$set(shop,"isShow",void 0);
				}
				//数据提交操作
			},
			//加商品
			plusGoods:function(goodObj){
				var _this = this;
				goodObj.productnum++;
				_this.productnum = goodObj.productnum;
			},
			//减少商品
			minsGoods:function(goodObj){
				var _this = this;
				if(goodObj.productnum>1){
					goodObj.productnum--;
				}
				_this.productnum = goodObj.productnum;
			},
			//删除商品
			deleteGoods:function(shop,good){
				shop.goodsData.splice(good,1);
				//如果该店铺下没商品，连同店铺删除
				if(shop.goodsData.length==0){
					this.carts.splice(shop,1);
				}
			},
			showNorms:function(gid,cartid){
				

				var _this = this;
	            	_this.oldcartid =  cartid;
	            _this.$http.post('/user/shopcart/chosespec',{
	            	productid:gid,
	            	
	            }).then(
	                function (res) {
	                    // 处理成功的结果
	                    //console.log(res);
	                    //console.log("=============");
	                    data = cl(res);
	                    
	                    if(data.code=='200'){
	                       
	                        _this.goodNorm = data.data;
	                        console.log(_this.goodNorm);
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
	            
				this.normMaks=true;
				//根据gid 查信息
				// this.goodNorm={
				// 	"good_img":"/mobile/img/food2.png",
				// 	"lave":1520,
				// 	"price":170,
				// 	"norms":[
				// 		{"name":"尺码","vals":["红色","蓝色"]},
				// 		{"name":"规格","vals":["S","L"]}
				// 	]
				// }
			},
			sureNrom:function(){
				this.normMaks=false;
				var _this = this;
				var chosespec = '';
				var haschosespec = '';
				$(".norms span.active").each(function() {
					
					haschosespec +=  $.trim($(this).parent().parent().find('div').text()) + ":" +  $.trim($(this).text())+';';
					chosespec += $(this).attr("_id") + ',';
				});
				
				chosespec = chosespec.substring(0,chosespec.length-1);
				// $("#"+_this.cartid).html(haschosespec);
			
	            var chosespecpro = _this.goodNorm.sku[chosespec];

	            _this.$http.post('/user/shopcart/updatespecgoods',{
	            	productnum:_this.productnum,
	            	productid:chosespecpro.productid,
	            	skuid: chosespecpro.id,
	            	oldcartid:_this.oldcartid
	            }).then(
	                function (res) {
	                    // 处理成功的结果
	                    // console.log(res);
	                    //console.log("=============");
	                    data = cl(res);
	                    
	                    if(data.code=='200'){
	                    	window.location.href = '/user/shopcart/index';
	                        // _this.carts = data.data;
	                        // _this.goodsSum = data.data.cardata.cargoodsgount;
	                        // // console.log(_this.carts);
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
			closeNorms:function(){
				this.normMaks=false;
				//清空
				this.goodNorm={};
			},
			chioceNorm:function(obj,idx){
//						if(obj.check==void 0){
//							
//							vm.$set(obj,"check",idx)
//						}
				vm.$set(obj,"check",idx)
			},
			getgoodsList:function(){
	            var _this = this;
	            
	            _this.$http.post('/user/shopcart/goodslist',{

	            }).then(
	                function (res) {
	                    // 处理成功的结果
	                    //console.log(res);
	                    //console.log("=============");
	                    data = cl(res);
	                    
	                    if(data.code=='200'){
	                        _this.carts = data.data;
	                        _this.goodsSum = data.data.cardata.cargoodsgount;
	                        // console.log(_this.carts);
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
        	addorder:function(){

        		var _this = this;
        		if(_this.buyIds == ''){
        			layer.open({
	                        content: '请选择需要购买的商品',
	                        skin: 'msg',
	                        time: 2 
	                    });
        			return false;
        		}
        		//alert(_this.buyIds);
        		window.location.href = '/order/index/showorder?skuid='+_this.buyIds+"&productnum="+ _this.productnum;
        	}

		}
	});
	
</script>

