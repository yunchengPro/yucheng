{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
	</header>
	<section class="order-bolck order-detail" style="margin-bottom: 0;">
		<div class="detail-status success">
			<div class="status">交易成功</div>
			<div class="time">卖家将收到您的货款</div>
		</div>
	</section>
	<section class="deal-suc-oper">
		<a :href="'/order/index/gogoodscomment?orderno='+orderno">立即评价</a>
		<a href="goodsOrderDetail-1.html">查看订单</a>
	</section>
	<div class="deal-suc-tip">交易成功7天内，无退货退款可获得牛豆！</div>
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
<style>
	.deal-suc-oper {
		padding: 14px 12px;
		display: -webkit-box;
		-webkit-box-pack: justify;
	}

	.deal-suc-oper a {
		display: -webkit-box;
		border: 1px solid #CCCCCC;
		width: 48%;
		-webkit-box-pack: center;
		text-align: center;
		height: 32px;
		line-height: 30px;
		font-size: 16px;
		border-radius: 4px;
	}
	.deal-suc-tip{text-align: center;color: #999999;margin-top: 15px;}

</style>
<!-- end style -->
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			title:'<?=$title?>',
			orderno:'<?=$orderno?>'
		},
		mounted:function(){
		},
		methods:{
		},
		watch:{},
	});
</script>