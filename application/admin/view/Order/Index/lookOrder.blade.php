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
</style>
<?php $orderStatus = array("0" => "待付款", "1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消"); ?>
<?php $payArr = array("weixin_app" => "微信支付", "ali_app" => "支付宝支付", "3" => "银联支付", "ali_web"=>"支付宝支付", "weixin_web"=>"微信支付","allinpay_quick"=>"银联支付", "allinpay_quick_web" => "银联支付", "allinpay_weixin"=>"微信支付", "allinpay_ali"=>"支付宝支付");?>
<div class="page-container addOrder">

	<div class="font-bold subtitle order_detail_title"><?="订单详情-".$orderStatus[$orderData['orderstatus']]?></div>
	<div class="f-16">

		<ul>
			<li class="mb-10"><span>订单编号：</span><span class="c-666"><b><?=$orderData['orderno'];?></b></span></li>
			<li class="mb-10"><span>买家手机：</span><span class="c-666"><b><?=$orderData['mobile'];?></b></span></li>
			<li class="mb-10"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;买家：</span><span class="c-666"><b><?=$orderData['cust_name'];?></b></span></li>
			<li class="mb-10"><span>&nbsp;&nbsp;&nbsp;&nbsp;收货人：</span><span class="c-666"><b><?=$logisticsData['realname'].', '.$logisticsData["mobile"]?></b></span></li>
			
			<li class="mb-10"><span>下单时间：</span><span class="c-666"><b><?=$orderData['addtime']?></b></span></li>
			<li class="mb-10"><span>订单状态：</span><span class="c-666"><b><?=$orderStatus[$orderData['orderstatus']]?></b></span></li>
			<li class="mb-10"><span>收货地址：</span><span class="c-666"><b><?=$logisticsData['city'].$logisticsData['address']?></b></span></li>
		</ul>
	</div>
	<!-- 
	<div class="remark ">
		<span class="f-16">买家留言：</span>
		<span class="f-16" style="color: #F13437"><?=$logisticsData['leavemessage']?><?=$logisticsData['orderremark']?></span>
	</div>
	 -->
	<?php if(!empty($nameauthInfo)) {?>
    	<div class="font-bold subtitle order_detail_title">实名信息 </div>
    	<div class="f-16">
    		<ul>
    			<li class="mb-10"><span>姓名：</span><span class="c-666"><b><?=$nameauthInfo['realname']?></b></span></li>
    			<li class="mb-10"><span>身份证号：</span><span class="c-666"><b><?=$nameauthInfo['idnumber']?></b></span></li>
    			<li class="mb-10"><span>身份证附件：</span><span class="c-666"><a href="<?=$nameauthInfo['positive_image']?>" target="_blank"><img src="<?=$nameauthInfo['positive_image']?>" height="150px" width="200px"></a> <a href="<?=$nameauthInfo['opposite_image']?>" target="_blank"><img src="<?=$nameauthInfo['opposite_image']?>" height="150px" width="200px"></a></span></li>
    		</ul>
    	</div>
    <?php }?>
	<div class="font-bold subtitle order_detail_title">订单商品</div>
	<div class="purchaseProducts">
		<div class="mt-20">
			<table id="showproduct" class="table-bordered table table-border table-bg table-hover table-sort">
				<tr class="text-c">
					<th>商品名称</th>
					<th>商家名称</th>
					<th>商家电话</th>
					<th>商品属性</th>
					<th>商品价格</th>
					<th>购买量</th>
					<th>总金额</th>
					<th>总牛豆</th>
					<td>买家留言</td>
				</tr>
				<?php if(!empty($itemData)) {?>
					<?php foreach ($itemData as $item) {?>
						<tr class="text-c">
							<td><?=$item['productname'] ?></td>
							<td><?=$item['businessname'] ?></td>
							<td><?=$item['mobile'] ?></td>
							<td><?=$item['skudetail']?></td>
							<td><?=DePrice($item['prouctprice'])?></td>
							<td><?=$item['productnum']?></td>
							<td><?php echo DePrice($item['prouctprice'] * $item['productnum']);?></td>
							<td><?php echo DePrice($item['bullamount'] * $item['productnum']);?></td>
							<td><?=$item['remark']?></td>
						</tr>
					<?php }?>
				<?php }?>
				
				<tr>
					<td colspan="10" style="text-align: right;">
						商品金额合计：<span id="countNumber" class="font-gold" style="font-size: 18px;font-weight: bold;"><?=DePrice($orderData['productamount'])?>
						快递费：<span id="countNumber" class="font-gold" style="font-size: 18px;font-weight: bold;"><?=DePrice($orderData['actualfreight'])?>
						牛豆总计：<span id="countNumber" class="font-gold" style="font-size: 18px;font-weight: bold;"><?=DePrice($orderData['bullamount'])?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?php if($orderData['orderstatus'] > 0) { // 已经付款(付款信息) ?>
		<div class="font-bold subtitle order_detail_title">付款信息</div>
		<div class="f-16">
			<ul>
				<!-- 
				<li><span>支付方式：</span><span class="c-999"><?=$payArr[$payData['pay_type']]?></span></li>
				 -->
				<li><span>支付方式：</span><span class="c-666">
				<?php 
				if(!empty($payData))
					echo $payData['pay_type'] != '' ? $payArr[$payData['pay_type']] : '余额支付';
				else
					echo "未支付";
				?>
				</span></li>
				<!-- <li><span>付款帐号：</span><span class="c-666"><?=$payData['pay_num']?></span></li> -->
				<li><span>付款时间：</span><span class="c-666"><?=$payData['paytime']?></span></li>
			</ul>
			<ul>
				<li><span>付款金额：</span><span class="c-666"><?=DePrice($payData['pay_money'])?></span></li>
				<li><span>付款牛豆：</span><span class="c-666"><?=DePrice($payData['pay_bull'])?></span></li>
			</ul>
		</div>
	<?php }?>
	<?php if($orderData['orderstatus'] == 1) { // 已付款 填写发货信息?>
		<div class="font-bold subtitle order_detail_title">填写发货信息</div>
		<div class="agintsLi f-16">
			<form action="<?=$actionArr['setExpress']?>" method="post" id="expressform">
				<ul>
    				<li><span>快递公司：</span><span class="pos-r"><input type="text" class="input-text width250 keyword" name="express_name" id="express_name">
    					<ul class="keyword-list">
    						<?php if(!empty($expressList)) {?>
    							<?php foreach ($expressList as $k => $express) {?>
    								<li><?php echo $express;?></li>
    							<?php }?>
    						<?php }?>
                        </ul>
    				</span></li>
    				<li><span>快递单号：</span><input type="text" class="input-text width250 " name="express_no" id="express_no"></li>
    				<li><input type="hidden" name="orderno" class="input-text width250 " value="<?=$orderData['orderno'] ?>"></li>
    				<li><input type="button" value="发货" class="input-text" id="delivery"></li>
    			</ul>
    		</form>
		</div>
	<?php }?>
	<?php if($orderData['orderstatus'] == 3) {?>
		<div class="font-bold subtitle order_detail_title">收货信息</div>
		<div class="agintsLi f-16">
			<ul>
				<?php if(!empty($orderInfo['actual_delivery_time'])) {?>
					<li><span>确认收货时间：</span><span class="c-666"><?=$orderInfo['actual_delivery_time']?></span></li>
				<?php } else {?>
					<li><span>自动收货时间：</span><span class="c-666"><?=$orderInfo['auto_delivery_time']?></span></li>
				<?php }?>
			</ul>
		</div>
	<?php }?>

		<div class="font-bold subtitle order_detail_title">评价信息</div>
		<div class="purchaseProducts">
			<div class="mt-20">
				<table id="showproduct" class="table-bordered table table-border table-bg table-hover table-sort">
					<tr>
						<th>产品名称</th>
						<th>评价评分</th>
						<th>评价内容</th>
						<th>评价图片</th>
						<th>评价时间</th>
					</tr>
					<?php foreach ($orderItemEvaluate as $evaluate) {?>
						<tr>

							<td><?=$evaluate['productname']?></td>
							<td><?=$evaluate['evaluate']['scores']?></td>
							<td><?=$evaluate['evaluate']['content']?></td>
							<td>
								<?php foreach ($evaluate['evaluate']['images'] as $image) {?>
									<img alt="" src="<?=$image['thumb']?>" width="120px" hegiht="80px" />
								<?php }?>
							</td>
							<td><?=$evaluate['evaluate']['addtime']?></td>
						</tr>
					<?php }?>
				</table>
			</div>
		</div>


	<?php if(!empty($logisticsData['express_name'])){ ?>
	<div class="font-bold subtitle order_detail_title">发货信息</div>
		<div class="agintsLi f-16">
			<ul>
				<li><span>快递公司：</span><span class="c-666"><?=$logisticsData['express_name']?></span></li>
				<li><span>快递单号：</span><span class="c-666"><?=$logisticsData['express_no']?></span></li>
			</ul>
		</div>
		<?php 
			//print_r($expressData);
			if($expressData['status']!='200'){
				echo $expressData['message'];
			}else{
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
		<?php } ?>
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
<script type="text/javascript" src="/js/keywords.js"></script>