{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<section class="ucenter-block info-wrap">
		<a href="/user/index/config"><img src="<?=$publicDomain?>/mobile/img/icon/ic_center_setting.png" class="config"></a>
		<a :href="'tel:'+companyMobile" ><img src="<?=$publicDomain?>/mobile/img/icon/ic_service@2x.png" class="call-kf"/></a>
		<div class="info">
			<a href="#"><img :src="headerpic" class="avatar"></a>
			<div class="base">
				<div class="name" v-html="nickname"></div>
				<div class="phone" v-html="phone"></div>
			</div>
			<div class="role"><span v-html="roleName"></span></div>
		</div>
	</section>
	
	
	<section class="ucenter-block  option-2">
		<div class="block-body">
			
			
	 
			<div class="tl-flex">
				<div class="b-center">
					<a href="#">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_store_order_center.png"> <em>20</em></div>
						<div class="txt">待付款订单</div>
					</a>
				</div>
				<div class="b-center">
					<a href="#">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_order_center.png"><em>20</em></div>
						<div class="txt">待发货订单</div>
					</a>
				</div>
				<div class="b-center">
					<a href="#">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_shopcart_center.png"><em>2</em></div>
						<div class="txt">商城购物车</div>
					</a>
				</div>
			</div>
			<div class="tl-flex" style="margin-top: 10px;">
				<div class="b-center">
					<a href="#">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_heart_center.png"></div>
						<div class="txt">我的评价</div>
					</a>
				</div>
				<div class="b-center">
					<a href="#">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_star_center.png"></div>
						<div class="txt">我的收藏</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/user/userlogistics/logisticslist">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_address_center.png"></div>
						<div class="txt">收货地址管理</div>
					</a>
				</div>
			</div>
			
			<!-- <div class="tl-flex" style="margin-top: 10px;">
			
				
				<div class="b-center">
					<a href="takeOutOrder.html">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_integration_center.png"></div>
						<div class="txt">积分商城</div>
					</a>
				</div>
				<div class="b-center">
					<a href="deliveryAddress.html">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_store_center.png"></div>
						<div class="txt">金牛商城</div>
					</a>
				</div>
			</div> -->
				<!-- 
				<div class="b-center">
					<a href="#">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_store_center.png"></div>
						<div class="txt">购物券商城</div>
					</a>
				</div>
				<div class="b-center">
					<a href="deliveryAddress.html">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_consume_center.png"></div>
						<div class="txt">实体店消费</div>
					</a>
				</div>
				<div class="b-center">
						
				</div>
			</div>
			 -->
		</div>
	</section>
<!-- end content -->
{include file="Pub/havenav" /}
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/user/index/getIndexData",
			push:"/user/index/push",
			title:'<?=$title?>',
			customerid:'<?=$customerid?>',
			role:'<?=$role?>',
			headerpic:'<?=$publicDomain?>/mobile/img/icon/default_head.png',
			nickname:"",
			phone:"",
			roleName:"消费者",
			conamount:"0.00",
			yesincomecon:"0.00",
			yesexpendcon:"0.00",
			cashamount:"0.00",
			bonus:"0.00",
			mallamount:"0.00",
			stoamount:"0.00",
			direct:"0",
			totaldirect:"0",
			busamount:"0.00",
			yesconamount:"0.00",
			companyMobile:"",
			pushUrl:"",
		},
		mounted:function(){
			this.getIndexData();
		},
		methods:{
			getIndexData:function() {
				var _this = this;
				_this.$http.post(_this.apiUrl,{
					customerid:_this.customerid,role:_this.role
				}).then(
					function(res) {

						data = cl(res);

						if(data.code == "200") {
							
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("数据查询异常");
					}
				);
			}
		},
		watch:{
		}
	});
</script>