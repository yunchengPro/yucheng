{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header tl-fixed">	
		<div class="page-bar">	
			<a href="/user/index/index">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
			<!--
			<a href="#" class="c-666" style="position: fixed;right: 10px;">退款/售后</a>
			-->
		</div>
	</header>
	<section class="goods-order" v-scroll="getMore">
		<div class="order-head">
			<div v-for="(type, index) in types" v-bind:class="{'head-item':true,'active':current===index}" v-on:click="getType(type.num)">
				<a href="javascript:void(0);" v-html="type.tp"></a>
			</div>
		</div>
		<div class="order-body" style="margin-top: 90px;">
			<div class="order-list">
				<div class="one-order" v-for="list in lists">
					<div class="shop-stuas">
						<a href="javascript:void(0)" class="pos-r"><span v-html="list.businessname"></span><i></i></a>
						<div class="order-stuas" v-html="orderstatusType[list.orderstatus]"></div>
					</div>
					<a href="" v-for="item in list.orderitem">
						<div class="order-info-box">
							<div class="order-img">
								<img :src="item.thumb!=''?item.thumb:defaultsrc">
							</div>
							<div class="order-info">
								<div class="name tl-ellipsis-2" v-html="item.productname"></div>
								<div v-if="item.skudetail" class="cate">
									<div class="c-999"><span v-html="item.skudetail"></span></div>
									<div>X<span v-html="item.productnum"></span></div>
								</div>
								<div class="text-r" v-else>X<span v-html="item.productnum"></span></div>
								<div class="order-time"><span v-html="item.prouctprice"></span>元</div>
							</div>
						</div>
					</a>
					<div class="total">共<span v-html="list.productcount"></span>件商品 合计：<span v-html="list.totalamount"></span>(含运费<span v-html="list.actualfreight"></span>元)</div>
					<div class="order-oper" v-show="list.orderact">
						<!-- <a :href="acttypeUrl[orderact.acttype]+'?orderno='+list.orderno" class="again" v-for="orderact in list.orderact" v-html="orderact.actname"></a> -->
						<a href="javascript:void(0);" v-bind:class="{'again':true,'c-ddd':orderact.act==2}" v-for="orderact in list.orderact" v-html="orderact.actname" @click="getActType(orderact.acttype, list.orderno)"></a>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div> 
				<p class="loading" v-show="switchShow">加载更多</p>
            </div>
		</div>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
 <style>
 	body{position: static;}
 	.tl-toast-box{
	position: fixed;
    background: rgba(0, 0, 0, 0.75);
    color: #fff;
    max-width: 200px;
    transform: translate(-50%, -50%);
    padding: 10px 20px;
    text-align: center;
    font-size: 14px;
    border-radius: 8px;
    left: 50%;
    top: 50%;
}	
 	.one-order .order-oper a.c-ddd{color: #DDDDDD;}
	.one-order .order-oper {
	    display: -webkit-box;
	    -webkit-box-pack: end;
	    width: 100%;
	    font-size: 13px;
	    height: 50px;
	    line-height: 50px;
	    border-top: 1px solid #ddd;
	    -webkit-box-direction: reverse;
	    -webkit-box-align: center;
	}
	.goods-order .one-order .order-oper a {
	    font-size: 13px;
	    padding: 0;
	    border-radius: 4px;
	    width: 80px;
	    display: -webkit-box;
	    height: 30px;
	    line-height: 30px;
	    text-align: center;
	    margin-right: 5px;
	    -webkit-box-pack: center;
	    -webkit-box-align: center;
	}
	.goods-order .one-order .order-oper a:last-child {
	     margin-right: 5px; 
	}
	.goods-order .one-order .order-oper a:first-child {
	     margin-right: 0px; 
	}
</style>
<!-- end style -->
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			getDataUrl:'/order/index/getorderlistdata',
			extendUrl:'/order/index/extendedreceipt',
			remindshippingUrl:'/order/index/remindshipping',
			confirmorderUrl:'/order/index/confirmorder',
			deleteorderUrl:'/order/index/deleteorder',
			title:"<?=$title?>",
			type:1,
			lists:[],
			page:1,
			current:0,
			maxPage:0,
			orderstatusType:["待付款","商家未发货","商家已发货","确认收货"],
			defaultsrc:'<?=$publicDomain?>/mobile/img/icon/default.png',
			types:[
				{"tp":"全部", "num":1},
				{"tp":"待付款", "num":2},
				{"tp":"待发货", "num":3},
				{"tp":"待收货", "num":4},
				{"tp":"待评价", "num":5}
			],
			acttype:0,
			switchShow:false,
			requestFlag:true,
			orderno:''
			// acttypeUrl:[
			// 	{"url":"全部"},
			// 	{"url":"全部"},
			// 	{"url":"全部"},
			// 	{"url":"全部"},
			// 	{"url":"全部"},
			// 	{"url":"全部"},
			// 	{"url":"全部"},
			// 	{"url":"/order/index/logisticsinfo"},
			// ]
			// acttypeUrl:["","","","","","","","/order/express/logisticsinfo"]
		},
		mounted:function(){
			this.getOrderListData();
		},
		methods:{
			getOrderListData:function() {
				var _this = this;
				_this.requestFlag = false;
				_this.$http.post(_this.getDataUrl,{
					orderlisttype:_this.type
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							console.log(data);
							_this.maxPage = data.data.maxPage;
							if(_this.page == 1) {
								_this.lists = data.data.list;
							} else {
								_this.lists = _this.lists.concat(data.data.list);
							}
							// 回调最后重新设置请求属性
							_this.requestFlag = true;
						} else {
							toast(data.msg);
							_this.requestFlag = true;
						}
					}, function(res) {
						toast("查询有异");
						_this.requestFlag = true;
					}
				);
			},
			extendedreceipt:function() {
				this.$http.post(this.extendUrl,{
					orderno:this.orderno
				}).then(
					function(res) {
						data = cl(res);
						// console.log(data);
						if(data.code == "200") {
							// 刷新页面
							this.getOrderListData();
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作有异");
					}
				);
			},
			remindshipping:function() {
				this.$http.post(this.remindshippingUrl,{
					orderno:this.orderno
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							toast("提醒成功");
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作有异");
					}
				);
			},
			confirmorder:function() {
				this.$http.post(this.confirmorderUrl,{
					orderno:this.orderno
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							LinkTo("/order/index/goodsdealsuccess?orderno="+this.orderno);
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作有异");
					}
				);
			},
			deleteorder:function() {
				loadtip({content:'删除中...'});
				this.$http.post(this.deleteorderUrl,{
					orderno:this.orderno
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							loadtip({
								close:true,
                                alert:'删除成功',
                                urlto:'/order/index/orderlist'
							});
							// LinkTo("/order/index/")
						} else {
							loadtip({
                                close:true,
                                alert:data.msg
                            });
						}
					}, function(res) {
						loadtip({
                            close:true,
                            alert:'操作有异'
                        });
					}
				);
			},
			getType:function(type){
				this.type = type;
				this.current = type-1;
				this.getOrderListData();
			},
			getMore:function() {
				var _this = this;
				if(_this.requestFlag) {
					_this.switchShow = true;
					_this.page++;

					if(_this.page<=_this.maxPage){
						_this.getOrderListData();
					} else {
						_this.switchShow = false;
						$(".loading").hide();
						return ;
					}
				}
			},
			getActType:function(acttype, orderno) {
				var _this = this;
				_this.orderno = orderno;
				if(acttype == 3) {
					// 提醒发货
					_this.remindshipping();
				} else if(acttype == 6) {
					// 延长收货
					if(confirm("确定延长收货时间?")) {
						_this.extendedreceipt();
					}
				} else if(acttype == 7) {
					// 查看物流
					LinkTo("/order/express/logisticsinfo?orderno="+orderno);
				} else if(acttype == 8) {
					// 确认收货
					if(confirm("请收到货后，再确认收货! 否则您可能钱货两空!")) {
						_this.confirmorder();
					}
				} else if(acttype == 11) {
					// 删除订单
					_this.deleteorder();
				}
			}
		},
		directives:{
			scroll:{
				bind:function(el,binding) {
					window.addEventListener('scroll', function(){
						if(document.body.scrollTop + window.innerHeight+20 >= el.clientHeight) {
                            var fnc = binding.value;
                            fnc();
                        }
					});
				}
			}
		},
		watch:{},
	});
</script>