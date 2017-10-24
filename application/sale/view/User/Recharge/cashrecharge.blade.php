{include file="Pub/headeruser" /}
<!-- content -->
	<section class="rechange-wrap">
		<span class="money-icon"></span>
		<input type="text" maxlength="30" placeholder="请输入充值金额" class="money"  onkeyup="checkMoney()"/>
	</section>

	<div class="rechange-oper">
		<button class="rechange-btn" onclick="choosePayWay()" disabled="disabled" type="button">确定</button>
	</div>

	<div class="tl-mask"></div>
	<section class="choose-type-wrap">
		<div class="choose-header">
			<span>付款详情</span>
			<i class="close"></i>
		</div>
		<div class="choose-type">
			<div class="type-item active">
				<div><img src="<?=$publicDomain?>/mobile/img/icon/ali.png" /> 支付宝</div>
				<i class="chosen"></i>
			</div>
			<div class="type-item">
				<div><img src="<?=$publicDomain?>/mobile/img/icon/weixin.png" /> 微信支付</div>
				<i class="chosen"></i>
			</div>
			<div class="type-item">
				 <div><img src="<?=$publicDomain?>/mobile/img/icon/union.png" class="vertical-middle" /> 银联支付</div>
				 <i class="chosen"></i>
			</div>
		</div>
		<div class="pay-money">
			<div class="money">
				<div>支付金额</div>
				<div><span class="red">1</span>元</div>
			</div>
			<div class="oper">
				<button class="to-pay">确认支付</button>
			</div>
		</div>
		
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
	function checkMoney(){
		var val=$(".money").val();
		//非空
		if(val!=""){
			$(".rechange-btn").attr("disabled",false);	
		}
	}
	
	function choosePayWay(){
		$(".tl-mask").show();
		$(".choose-type-wrap").slideDown();
		var val = $(".money").val();
		$(".red").html(val);
	}
	$(function(){
		//选择支付方式
		$(".type-item").on("click",function(){
			$(this).addClass("active").siblings().removeClass("active");
			
		});
		
		//关闭按钮
		$(".choose-header .close").on("click",function(){
			$(".tl-mask").hide();
			$(".choose-type-wrap").slideUp();
		});
	});
</script>