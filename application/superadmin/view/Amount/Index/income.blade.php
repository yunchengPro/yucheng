<?php
    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "pay_type" => array("name" => "支付方式", "data_arr" => array("weixin_app" => "微信支付", "ali_app" => "支付宝支付", "3" => "银联支付", "ali_web"=>"支付宝支付", "weixin_web"=>"微信支付","allinpay_quick"=>"银联支付", "allinpay_quick_web" => "银联支付", "allinpay_weixin"=>"微信支付-通联", "allinpay_ali"=>"支付宝支付-通联")),
        "pay_money" => array("name" => "金额(元)", "sort"=>true),
        "addtime" => array("name" => "时间", "sort" => true),
    );
    
    $search = array(
        "type" => array("type" => "select", "name" => "支付方式", "option" => ['1' => '微信', '2' => '支付宝', '3' => '银联']),
        "addtime" => array("type" => "times", "name" => "时间"),
    );
?>
<?php if ($full_page){?>
<!---头部-->
{include file="Pub/header" /}
<div class="page-container">
	<div class="orderAddUpWraper">
		<!--代发货-->
		<div class="row cl">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-header"></div>
					<div class="panel-body">
						<div class="order-money">
							
								<div class="col-md-4 border-r">
									<div class="text-c"><span class="f-32"><?=$amount['weixin']?></span>元</div>
									<div class="text-c">微信支付</div>
								</div>
								<div class="col-md-4">
									<div class="text-c"><span class="f-32"><?=$amount['ali']?></span>元</div>
									<div class="text-c">支付宝支付</div>
								</div>
								
								<div class="col-md-4">
									<div class="text-c"><span class="f-32"><?=$amount['union']?></span>元</div>
									<div class="text-c">银联支付</div>
								</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{include file="Pub/pubHead" /}
<!---头部-->
			<!---搜索条件-->
			{include file="Pub/pubSearch" /}
			<!---搜索条件-->
            
            <!---这里可以写操作按钮等-->
                  
           
                  
            <!---这里可以写操作按钮等-->
<?php }?>
			<?php if(!empty($timeIncome)) {?>
<!--             	<div class="orderAddUpWraper"> -->
            		<!--代发货-->
<!--         			<div class="col-md-6"> -->
<!-- 						<div class="order-money"> -->
<!-- 							<div class="col-md-12 border-r"> -->
<!-- 								<div class="text-c"><span class="f-32"></?=$timeIncome['amount']?></span>元</div> -->
<!-- 								<div class="text-c">支付金额</div> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!--         			</div> -->
<!--             	</div> -->
			当前收入总额：<?=$timeIncome['amount']?>元
            <?php }?>
			<!---列表+分页-->		
			{include file="Pub/pubList" /}
			<!---列表+分页-->
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->
<?php }?>