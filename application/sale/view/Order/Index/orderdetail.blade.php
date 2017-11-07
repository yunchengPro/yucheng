{include file="Pub/header" /}
			<header class="page-header">	
				<div class="page-bar">	
					<a href="/index/index/index">
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
					<div class="view-log">查看物流信息<i></i></div>
					
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
									<div class="name tl-ellipsis-2" v-html="goods.productnum"></div>
									<div class="text-r">X<span v-html="goods.productnum"></span></div>
									<div class="order-time"><span v-html="goods.prouctprice">198.00</span>元</div>
								</div>
								
							</div>

							<div class="oper-xx">
									<a href="#">评价</a>
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
					<div class="c-666">订单编号：<span v-html="orderdetail.orderno"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666">创建时间：<span v-html="orderdetail.addtime"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666">付款时间：<span v-html="orderdetail.paytime"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666">发货时间：<span v-html="orderdetail.actual_delivery_time"></span></div>
					
				</div>
				<div class="goods-order-item">
					<div class="c-666">成交时间：<span v-html="orderdetail.delivertime"></span></div>
					
				</div>
				
				
			</section>
			<footer class="goods-order-oper" style="-webkit-box-pack: end">
							<a href="#" style="margin-right: 10px;">删除订单</a>
							<a href="viewLogistics.html">查看物流</a>
			</footer>

		
		
<script>
	new Vue({
		el: '#app',
		data: {
		  orderdetail:[],
		  orderno:"<?=$orderno?>"
		},
		mounted:function(){
		  	var _this = this;
        	_this.getOrderDetail();
		},
		methods:{
			  	
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
		}
	});
</script>

