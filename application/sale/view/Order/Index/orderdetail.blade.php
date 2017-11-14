{include file="Pub/header" /}
			<header class="page-header">	
				<div class="page-bar">	
					<a href="/order/index/orderlist">
						<span class="back-ico"></span>
					</a>
					<span class="bar-title">订单详情</span>
				</div>
			</header>
			
			<section class="order-bolck order-detail" style="margin-bottom: 0;">
				<div class="detail-status clock">
					<div class="status " v-html="orderdetail.orderstatusstr.statusstr">交易完结</div>
					<div class="time" v-html="orderdetail.orderstatusstr.statusinfo" ></div>
				</div>
			</section>
			<section class="order-bolck">
				<a href="viewLogistics.html">
					<div class="view-log" v-if="orderdetail.orderstatus > 2 && orderdetail.orderstatus < 5">
					<a :href="'/order/express/logisticsinfo?orderno='+orderdetail.orderno">查看物流信息<i></i></a></div>
					
				</a>
			</section>
			<section class="order-bolck address-line">
				<div class="receipt">
					<div>收货人：<span v-html="orderdetail.receipt_address.realname"></span> </div>
					<div><span v-html="orderdetail.receipt_address.mobile"></span></div>
				</div>
				<div class="receipt-address tl-ellipsis-2">
					<span v-html="orderdetail.receipt_address.city"></span><span v-html="orderdetail.receipt_address.address"></span>
				</div>
				<div class=""></div>
			</section>
			
			<section class="order-bolck goods-order">
				
				<div class="order-body">
					<div class="order-list">
						<!--已付款-->
						<div class="one-order">
							<div class="shop-stuas" style="padding-left: 0;">
								<a href="" class="pos-r"><span >牛牛汇日用家居商城</span><i></i></a>
								<!--<div class="order-stuas" v-html="orderdetail.businessname">已付款</div>-->
							</div>
							
							<div class="order-info-box" v-for="goods in orderdetail.orderitem">
								<div class="order-img">
									<img :src="goods.thumb" />
								</div>
								<div class="order-info">
									<div class="name tl-ellipsis-2" v-html="goods.productname"></div>
									<div :id="goods.skuid" class="c-999 sepcifi" v-html="goods.skudetail"></div>
									<div class="text-r">X<span v-html="goods.productnum"></span></div>
									<div class="order-time"><span v-html="goods.prouctprice"></span>元</div>
								</div>
								
							</div>

							<div class="oper-xx">
								<!-- 	<a href="#">评价</a> -->
							</div>
							<div style="padding: 10px 0;">
								<div class="goods-order-item">
									<div class="c-666">商品总价</div>
									<div class="c-666" v-html="orderdetail.productamount">198.00元</div>
								</div>
							<!-- 	<div class="goods-order-item">
									<div class="c-666">牛豆</div>
									<div class="c-666">98</div>
								</div> -->
								<div class="goods-order-item">
									<div class="c-666">运费快递</div>
									<div class="c-666"><span v-html="orderdetail.actualfreight"></span>元</div>
								</div>
								<div class="goods-order-item">
									<div>订单总价</div>
									<div><span v-html="orderdetail.totalamount"></span>元</div>
								</div>
							</div>
							
							
							<div class="goods-order-total">
								<div>已付款</div>
								<div><strong class="red"  v-html="orderdetail.totalamount">208.00</strong>元</div>
							</div>
						</div>
						
						
						
					</div>
				</div>
			</section>
			<div class="goods-order-call"><a href="tel:10086">拨打商家电话</a></div>
			<section class="goods-order-no">
				<div class="goods-order-item">
					<div class="c-666" >订单编号：<span v-html="orderdetail.orderno"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666"  v-if="orderdetail.addtime != ''">创建时间：<span v-html="orderdetail.addtime"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666" v-if="orderdetail.paytime != ''">付款时间：<span v-html="orderdetail.paytime"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666" v-if="orderdetail.actual_delivery_time != ''">发货时间：<span v-html="orderdetail.actual_delivery_time"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666"  v-if="orderdetail.delivertime != ''">成交时间：<span v-html="orderdetail.delivertime"></span></div>
					
				</div>
				
				
			</section>

			<div class="tl-select-mask" v-show="showMual" @click="hideMualBox"></div>
			<div class="mutual-choice-wrap" v-show="showMual">
				<div class="mutual-choice-header tl-grid">
					<div class="tl-grid-1-4 blue" @click="hideMualBox">取消</div>
					<div class="tl-grid-1-2">请选择</div>
					<div class="tl-grid-1-4 blue" @click="sureChoice">确定</div>
					
				</div>
				<div class="mutual-choice-body">
					<div class="mutual-single">
						<!--<div class="single-item">香蜜湖</div>
						<div class="single-item">香蜜湖</div>-->
						<div :class="['single-item',{'active':choiceCur==index}]" @click="chioceMual(index)" v-for="(item,index) in choiceData">{{item.name}}</div>
					</div>
				</div>
			</div>

			<footer class="goods-order-oper" style="-webkit-box-pack: end">
				<a style="margin-right: 10px;"   href="javascript:void(0);" v-bind:class="{'again':true,'c-ddd':orderact.act==2}" v-for="orderact in orderdetail.orderact" v-html="orderact.actname" @click="getActType(orderact.acttype, orderdetail.orderno)" > </a>
						
			</footer>
	
		
		
<script>
	new Vue({
		el: '#app',
		data: {
		  	orderdetail:[],
		  	orderno:"<?=$orderno?>",
		  	remindshippingUrl:'/order/index/remindshipping',
			confirmorderUrl:'/order/index/confirmorder',
			deleteorderUrl:'/order/index/deleteorder',
			cancelsorderUrl:'/order/index/cancelsorder',
			choiceData:[
		    	{"name":"我不想买了","id":1},
		    	{"name":"信息写错误，重新拍","id":2},
		    	{"name":"卖家缺货","id":3},
		    	{"name":"同城见面交易","id":4},
		    	{"name":"其他原因","id":5}
		    ],
		    orderIdCur:-1,
		    showMual:false,
		    choiceCur:0,
		},
		mounted:function(){
		  	var _this = this;
        	_this.getOrderDetail();
		},
		methods:{
			//显示
		  	showMualBox:function(orderId){
		  		this.showMual=true;
		  		this.orderIdCur=orderId;

		  	},
		  	//隐藏
		  	hideMualBox:function(){
		  		this.showMual=false;
		  	},
		  	chioceMual:function(index){
		  		this.choiceCur=index;
		  	},
		  	sureChoice:function(){
		  		this.showMual=false;
		  		//this.orderIdCur=orderId;
		  		var _this = this;
		  		

		  		_this.cancelsorder($('.mutual-single .active').text());
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
				} else if(acttype == 2){
					// 取消订单
					_this.showMualBox(1)
					//_this.cancelsorder();
				} else if(acttype == 1){
					// 查看物流
					LinkTo("/sys/pay/paymethod?orderno="+orderno);
				}
			},
		 	getOrderDetail:function(){
				

				var _this = this;
	            	
	            _this.$http.post('/order/index/getorderdetail',{
	            	orderno:_this.orderno,
	            	
	            }).then(
	                function (res) {
	                    // 处理成功的结果
	                    //console.log(res);
	                    //console.log("=============");
	                    data = cl(res);
	                    
	                    if(data.code=='200'){
	                       
	                        _this.orderdetail = data.data.orderdetail;
	                        console.log(_this.orderdetail);
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
							LinkTo("/order/index/orderdetail?orderno="+this.orderno);
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
			cancelsorder:function(concelmsg){
				loadtip({content:'取消中...'});
				this.$http.post(this.cancelsorderUrl,{
					orderno:this.orderno,
					cancelreason:concelmsg
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							loadtip({
								close:true,
                                alert:'取消成功',
                                urlto:'/order/index/orderdetail?orderno='+this.orderno
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
		}
	});
</script>

