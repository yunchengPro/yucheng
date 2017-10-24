{include file="Pub/header" /}
<style type="text/css">
/*     .item-right button{ */
/*         float: right; */
/*     } */
   
/*     .order-detail-container .orderBasicInfo ul{ */
/*         height: 150px; */
/*     } */
    .page-container{
        margin-bottom: 150px;
    }

/*     .btn */
/*     { */
/*         margin-top: 50px; */
/*     } */
    .addOrder{ 
        padding-top: 0;
    }
    .order_detail_title{
        margin-top:0;
        font-size: 18px;

    }
    *{
    	font-size: 16px;
    }
</style>
<?php $orderStatus = array("0" => "待付款", "1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消"); ?>
<?php $payArr = array("weixin_app" => "微信支付", "ali_app" => "支付宝支付", "3" => "银联支付");?>
<div class="page-container addOrder">
	<div class="font-bold subtitle order_detail_title"><?="订单详情-".$orderStatus[$orderData['orderstatus']]?></div>
	<div class="f-16">
		<ul><li  class="mb-10"><span>订单编号：</span><span class="c-666"><b><?=$orderData['orderno'];?></b></span></li>
		<li  class="mb-10"><span>收货人：</span><span class="c-666"><b><?=$logistics['realname'].', '.$logistics["mobile"]?></b></span></li>
		<li  class="mb-10"><span>收货地址：</span><span class="c-666"><b><?=$logistics['city'].$logistics['address']?></b></span></li>
		<li  class="mb-10"><span>下单时间：</span><span class="c-666"><b><?=$orderData['addtime']?></b></span></li>
		<li  class="mb-10"><span>订单状态：</span><span class="c-666"><b><?=$orderstatus[$orderData['orderstatus']]?></b></span></li></ul>
	</div>
	<div class="remark">
		<span class="f-16">买家留言：</span>
		<span class="f-16" style="color: #F13437"><?=$orderInfo['remark']?></span>
	</div>
	<div class="font-bold subtitle order_detail_title">订单商品</div>
	<div class="purchaseProducts">
		<div class="mt-20">
			<table id="showproduct" class="table-bordered table table-border table-bg table-hover table-sort">
				<tr class="text-c">
					<th>商品名称</th>
					<th>商家名称</th>
					<th>商品价格</th>
					<th>购买量</th>
					<th>总金额</th>
				</tr>
				<?php if(!empty($itemData)) {?>
					<?php foreach ($itemData as $item) {?>
						<tr class="text-c">
							<td><?=$item['productname'] ?></td>
							<td><?=$item['businessname'] ?></td>
							<td>￥<?=DePrice($item['prouctprice'])?></td>
							<td><?=$item['productnum']?></td>
							<td>￥<?php echo DePrice($item['prouctprice'] * $item['productnum']);?></td>
						</tr>
					<?php }?>
				<?php }?>
				
				<tr>
					<td colspan="10" style="text-align: right;">
						商品金额合计：<span id="countNumber" class="font-gold" style="font-size: 18px;font-weight: bold;">￥<?=DePrice($orderData['productamount'])?></span>
						快递费：<span id="countNumber" class="font-gold" style="font-size: 18px;font-weight: bold;">￥<?=DePrice($orderData['actualfreight'])?></span>
						订单总额：<span id="countNumber" class="font-gold" style="font-size: 18px;font-weight: bold;">￥<?=DePrice($orderData['totalamount'])?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	

<?php if(!empty($evaluate)){ ?> 
	<div class="font-bold subtitle order_detail_title">评价信息</div>
		<div class="purchaseProducts">
			<div class="mt-20">
				<table id="showproduct" class="table-bordered table table-border table-bg table-hover table-sort">
					<tr>
						<th>订单编号</th>
						<th>会员名称</th>
						<th>评价评分</th>
						<th>评价内容</th>
						<th>评价时间</th>
					</tr>
					<?php foreach ($evaluate as $evaluate) {?>
						<tr>
							<td><?=$orderData['orderno']?></td>
							<td><?=$evaluate['frommembername']?></td>
							<td><?=$evaluate['scores']?></td>
							<td>
								<?=$evaluate['content']?>
							</td>
							<td><?=$evaluate['addtime']?></td>
						</tr>
					<?php }?>
				</table>
			</div>
		</div>
<?php } ?>	
</div>


<script type="text/javascript">
$("#delivery").click(function(){
	var express_name = $("#express_name").val();
	var express_no = $("#express_no").val();

	if(express_name == "") {
		alert("快递公司名称不能为空");
		return false;
	}
	if(express_no == "") {
		alert("快递单号不能为空");
		return false;
	}
	$("#expressform").submit();
});
</script>
{include file="Pub/footer" /}