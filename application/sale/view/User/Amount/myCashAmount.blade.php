{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="niu-coin">
		<div class="page-bar">
			<a href="/user/index/index">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_back_white@2x.png" class="back-ico"/>
			</a>
			<span class="bar-title">金牛回购余额</span>
			<a href="/user/flow/flowCusList?type=1" class="pos-ab">明细</a>
		</div>
		<div class="coin-body">
			<div class="coin-num" v-html="cashamount"></div>
			<div class="coin-type">回购余额</div>
		</div>
		<div class="coin-bottom">
			<div class="one-c">
				<div class="c-num" v-html="yesamount"></div>
				<div class="c-tip">昨日收益</div>
			</div>
			<div class="one-c">
				<div class="c-num" v-html="todayamount"></div>
				<div class="c-tip">今日收益</div>
			</div>
			<div class="one-c">
				<div class="c-num" v-html="totalamount"></div>
				<div class="c-tip">累计奖收益</div>
			</div>
		</div>
	</header>

	<section class="coin-oper">
		<a href="/user/withdrawals/withdrawalsIndex" class="oper-btn orange">提现</a>
		<a href="/user/withdrawals/list" class="link">提现明细</a>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/user/Amount/getMyCashAmountData",
			title:'<?=$title?>',
			customerid:'<?=$customerid?>',
			cashamount:"0.00",
			yesamount:"0.00",
			todayamount:"0.00",
			totalamount:"0.00"
		},
		mounted:function(){
			this.getCusCashData();
		},
		methods:{
			getCusCashData:function() {
				var _this = this;
				_this.$http.post(_this.apiUrl,{
					customerid:_this.customerid
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							_this.cashamount = data.data.cashamount;
							_this.yesamount = data.data.yesAmount;
							_this.todayamount = data.data.todayAmount;
							_this.totalamount = data.data.totalAmount;
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