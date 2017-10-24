{include file="Pub/header" /}
<style type="text/css">
    .item-right button{
        float: right;
    }
   
    .order-detail-container .orderBasicInfo ul{
        height: 150px;
    }
    .page-container{
        margin-bottom: 150px;
    }

    .btn
    {
        margin-top: 50px;
    }
    .addOrder{
        padding-top: 0;
    }
    .order_detail_title{
        margin-top:0;
        font-size: 18px;

    }
</style>
<?php $orderStatus = array("0" => "待付款", "1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消"); ?>
<?php $payArr = array("1" => "微信支付", "2" => "支付宝支付", "3" => "银联支付");?>
<div class="page-container addOrder">
	<div class="font-bold subtitle order_detail_title">发货信息</div>
	<div class="agintsLi">
		<ul>
			<li><span>快递公司：</span><span class="c-999"><?=$logisticsData['express_name']?></span></li>
			<li><span>快递单号：</span><span class="c-999"><?=$logisticsData['express_no']?></span></li>
		</ul>
	</div>
	<?php 
		//print_r($expressData);
		if($expressData['status']!='200'){
			echo $expressData['message'];
		}
	?>
	<div class="purchaseProducts">
		<div class="mt-20">
			<table id="showproduct" class="table-bordered table table-border table-bg table-hover table-sort">
				<tr>
					<th>时间</th>
					<th>摘要</th>
				</tr>
				<?php if(!empty($expressData['data'])) {?>
					<?php foreach ($expressData['data'] as $express) {?>
						<tr>
							<td><?=$express['time']?></td>
							<td><?=$express['context']?></td>
						</tr>
					<?php }?>
				<?php }?>
			</table>
		</div>
	</div>
</div>
{include file="Pub/footer" /}