
<!-- 第三方支付+公司现金支出+用户收入=用户余额+用户支出+用户待收现金<br>
用户支出=提现+牛品/牛店订单支付+角色申请/推荐支付<br> -->

<!-- 第三方支付收入：<?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$pay_money,"_width"=>"1000","_height"=>"500","_title"=>"总收入","_url"=>"/Amount/Index/flowlist?table="])?><br> -->

<!---头部-->
{include file="Pub/header" /}
<div class="page-container">
	<div class="finaceCountWraper">
		
		
		<div class="row cl">
			
			
			<div class="panel panel-default">
				<div class="panel-header">现金（用户现金收入=用户现金余额+用户现金支出）</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$user_cash_in,"_width"=>"1200","_height"=>"800","_title"=>"用户现金收入","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=1"])?></span></div>
								<div class="text-c c-666 f-18">用户现金收入</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$cashamount,"_width"=>"1200","_height"=>"800","_title"=>"用户现金余额","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">用户现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$user_cash,"_width"=>"1200","_height"=>"800","_title"=>"用户现金支出","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">用户现金支出</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?php echo $user_cash_in-($cashamount+$user_cash);?></span></div>
								<div class="text-c c-666 f-18">系统差（正数表示系统多出金额，负数表示系统少出金额）</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>($cashamount-$cus_com_amount['cashamount']),"_width"=>"1200","_height"=>"800","_title"=>"用户现金余额","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">用户现金余额(排除公司)</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-header">收益现金（用户收益现金收入 = 用户收益现金余额 + 用户收益现金支出）</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$profitflowamount_in,"_width"=>"1200","_height"=>"800","_title"=>"用户收益现金收入","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusProfit&direction=1"])?></span></div>
								<div class="text-c c-666 f-18">用户收益现金收入</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$profitamount,"_width"=>"1200","_height"=>"800","_title"=>"用户收益现金余额","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">用户收益现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$profitflowamount,"_width"=>"1200","_height"=>"800","_title"=>"用户收益现金支出","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusProfit&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">用户收益现金支出</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?php echo $profitflowamount_in-($profitamount+$profitflowamount);?></span></div>
								<div class="text-c c-666 f-18">系统差</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>($profitamount-$cus_com_amount['profitamount']),"_width"=>"1200","_height"=>"800","_title"=>"用户收益现金余额","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">用户收益现金余额(排除公司)</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-header">牛豆（用户牛豆收入 = 用户牛豆余额 + 用户牛豆支出）</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$bullflowamount_in,"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆收入","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusBull&direction=1"])?></span></div>
								<div class="text-c c-666 f-18">用户牛豆收入</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$bullamount,"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆余额","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">用户牛豆余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$bullflowamount,"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆支出","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusBull&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">用户牛豆支出</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?php echo $bullflowamount_in-($bullamount+$bullflowamount);?></span></div>
								<div class="text-c c-666 f-18">系统差</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>($bullamount-$cus_com_amount['bullamount']),"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆余额","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">用户牛豆余额(排除公司)</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="panel panel-default">
				<div class="panel-header">公司总账户</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['cashamount'],"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆收入","_url"=>"/Amount/Index/flowlist?table=AmoFlowComCash"])?></span></div>
								<div class="text-c c-666 f-18">现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['profitamount'],"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowComProfit"])?></span></div>
								<div class="text-c c-666 f-18">收益现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['bullamount'],"_width"=>"1200","_height"=>"800","_title"=>"用户牛豆支出","_url"=>"/Amount/Index/flowlist?table=AmoFlowComBull"])?></span></div>
								<div class="text-c c-666 f-18">牛豆余额</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
			<div class="panel panel-default">
				<div class="panel-header">公司总账户</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['cashamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowComCash&userid=0"])?></span></div>
								<div class="text-c c-666 f-18">公司现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['profitamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司收益现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowComProfit"])?></span></div>
								<div class="text-c c-666 f-18">公司收益现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['bullamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司牛豆余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowComBull"])?></span></div>
								<div class="text-c c-666 f-18">公司牛豆余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['counteramount'],"_width"=>"1200","_height"=>"800","_title"=>"公司手续费余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowCounter"])?></span></div>
								<div class="text-c c-666 f-18">公司手续费余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['charitableamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司慈善余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowCharitable"])?></span></div>
								<div class="text-c c-666 f-18">公司慈善余额</div>
							</div>

							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['bonusamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司慈善余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowComBonus"])?></span></div>
								<div class="text-c c-666 f-18">公司奖励金余额</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-header">公司用户账户</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$cus_com_amount['cashamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&userid=-1"])?></span></div>
								<div class="text-c c-666 f-18">现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$cus_com_amount['profitamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司收益现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusProfit&userid=-1"])?></span></div>
								<div class="text-c c-666 f-18">收益现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$cus_com_amount['bullamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司牛豆余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusBull&userid=-1"])?></span></div>
								<div class="text-c c-666 f-18">牛豆余额</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-header">公司用户账户-待收</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$fut_cus_com_amount['cashamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowFutCusCash&userid=-1"])?></span></div>
								<div class="text-c c-666 f-18">现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$fut_cus_com_amount['profitamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司收益现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowFutCusProfit&userid=-1"])?></span></div>
								<div class="text-c c-666 f-18">收益现金余额</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$fut_cus_com_amount['bullamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司牛豆余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowFutCusBull&userid=-1"])?></span></div>
								<div class="text-c c-666 f-18">牛豆余额</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-header">公司账户充值统计</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=$com_recharge_amount['cashamount']?></span></div>
								<div class="text-c c-666 f-18">充值-现金</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=$com_recharge_amount['profitamount']?></span></div>
								<div class="text-c c-666 f-18">充值-收益现金</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=$com_recharge_amount['bullamount']?></span></div>
								<div class="text-c c-666 f-18">充值-牛豆</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=$bull_code_amount?></span></div>
								<div class="text-c c-666 f-18">牛豆充值卡总额</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="panel panel-default">
				<div class="panel-header">公司统计</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<?php foreach($user_com_amount as $k=>$v){?>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$v['cashamount'],"_width"=>"1200","_height"=>"800","_title"=>$user_com_arr[$v['id']],"_url"=>"/Amount/Index/flowlist?table=AmoFlowComCash&userid=".$v['id']])?></span></div>
								<div class="text-c c-666 f-18"><?=$user_com_arr[$v['id']]?></div>
							</div>
							<?php }?>
					</div>
				</div>
			</div>

	</div>
	
</div>

			
			<!---列表+分页-->		
			
			<!---列表+分页-->

<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->


