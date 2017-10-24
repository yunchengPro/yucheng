
<!---头部-->
{include file="Pub/header" /}
<div class="page-container">
	<div class="finaceCountWraper">
		
		<div class="row cl">
			
			<div class="panel panel-default">
				<div class="panel-header">货款+分润+收益现金+返牛豆=用户收入-用户退款</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$buscash_amount,"_width"=>"1200","_height"=>"800","_title"=>"货款","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=1"])?></span></div>
								<div class="text-c c-666 f-18">货款</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$busprofit_amount,"_width"=>"1200","_height"=>"800","_title"=>"分润","_url"=>"/Amount/Index/balance"])?></span></div>
								<div class="text-c c-666 f-18">分润</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$busprofitamount_amount,"_width"=>"1200","_height"=>"800","_title"=>"收益现金","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">收益现金</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$busbullamount_amount,"_width"=>"1200","_height"=>"800","_title"=>"返牛豆","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">返牛豆</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$buscuscash_in,"_width"=>"1200","_height"=>"800","_title"=>"用户收入","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">用户收入</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num"><span class="f-32"><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$buscuscash_out,"_width"=>"1200","_height"=>"800","_title"=>"用户收入","_url"=>"/Amount/Index/flowlist?table=AmoFlowCusCash&direction=2"])?></span></div>
								<div class="text-c c-666 f-18">用户退款</div>
							</div>
							<div class="col-md-4">
								<div class="text-c f-num">
								<span class="f-32">
								<?php echo $buscash_amount+$busprofit_amount+$busprofitamount_amount+$busbullamount_amount-($buscuscash_in-$buscuscash_out);?>
								</span>
								</div>
								<div class="text-c c-666 f-18">系统差</div>
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
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-header">公司总账户</div>
				<div class="panel-body">
					<div class="order-money pb-30">
						<div class="row cl">
							<div class="col-md-4 ">
								<div class="text-c f-num"><span class=""><?=Html::a(["type"=>"popup_listpage","name"=>"button","value"=>$com_amount['cashamount'],"_width"=>"1200","_height"=>"800","_title"=>"公司现金余额","_url"=>"/Amount/Index/flowlist?table=AmoFlowComCash"])?></span></div>
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
	</div>
	
</div>

			
			<!---列表+分页-->		
			
			<!---列表+分页-->

<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->


