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
	<div v-if="role==1">
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">消费劵</div>
				<div><a href="/user/flow/flowCusList?type=3"><span class="number" v-html="conamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<!--
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesconamount"></div>
							<div class="txt">昨日金牛数</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todayconamount"></div>
							<div class="txt">今日金牛数</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalconamount"></div>
							<div class="txt">累计金牛数</div>
						</a>
					</div>
				</div>
				-->
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="yesincomecon"></div>
						<div class="txt">昨日消费劵</div>
					</a>
				</div>
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="yesexpendcon"></div>
						<div class="txt">昨日消费</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/user/amount/myCashAmount">
						<div class="num" v-html="cashamount"></div>
						<div class="txt">钱包余额</div>
					</a>
				</div>
			</div>
			<div class="tl-flex mt-10">
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="bonus"></div>
						<div class="txt">消费指数</div>
					</a>
				</div>
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="mallamount"></div>
						<div class="txt">商城消费余额</div>
					</a>
				</div>
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="stoamount"></div>
						<div class="txt">实体店消费余额</div>
					</a>
				</div>
			</div>
		</section>
		<!--
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">金牛回购余额</div>
				<div><a href="/user/amount/myCashAmount"><span class="number" v-html="cashamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesBonus"></div>
							<div class="txt">昨日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todayBonus"></div>
							<div class="txt">今日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalBonus"></div>
							<div class="txt">累计收益</div>
						</a>
					</div>
				</div>
				<div class="tl-flex mt-10">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesCommiss"></div>
							<div class="txt">昨日提成</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todayCommiss"></div>
							<div class="txt">今日提成</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalCommiss"></div>
							<div class="txt">累计提成</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		-->
		<!--
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">积分钱包</div>
				<div><a href="/user/flow/flowCusList?type=4"><span class="number" v-html="intamount"></span><i></i></a></div>
			</div>
		</section>
		<section class="ucenter-block option-1">
				
			<div class="block-body">
				
				<div class="tl-flex">
					<div class="b-center" v-if="shareStatus==1">
						<a href="/user/index/push">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code.png"></div>
							<div class="txt">分享 二维码</div>
						</a>
					</div>
					<div class="b-center" v-else>
						<a href="javascript:toast('需要购买才能点亮')">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code_unpressed.png"></div>
							<div class="txt">分享 二维码</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val" v-html="childRelation"></div>
							<div class="txt">我的社群</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_phone_store.png"></div>
							<div class="txt">联系商家</div>
						</a>
					</div>
					<div class="b-center">
						<a href="/Conn/Conn/buycon">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_buy_jinniu.png"></div>
							<div class="txt">购买金牛</div>
						</a>
					</div>
					<div class="b-center">
						<a href="/Conn/Conn/buyconlist">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_record.png"></div>
							<div class="txt">购买记录</div>
						</a>
					</div>
				</div>
			</div>
		</section>
	</div>
	-->
		<section class="ucenter-block option-l">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/Conn/Conn/buycon">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_recharge.png"></div>
							<div class="txt">购物充值</div>
						</a>
					</div>
					<div class="b-center">
						<!-- <a href="/user/index/push"> -->
						<a :href="pushUrl">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code.png"></div>
							<div class="txt">分享二维码</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val"><span v-html="direct"></span>/<span v-html="totaldirect"></span></div>
							<div class="txt">消费者社群</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block option-l">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/user/apply/applyToBus">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_opern_store.png"></div>
							<div class="txt">我要申请成为商家</div>
						</a>
					</div>
					<div class="b-center">
						<a href="/user/apply/applyToManager">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_open_manager.png"></div>
							<div class="txt">我要申请成为区代理</div>
						</a>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div v-else-if="role==2">
		<!--
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">待分红业绩余额</div>
				<div><a href="/user/flow/flowCusList?type=2"><span class="number" v-html="busamount"></span><i></i></a></div>
				
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesbusamount"></div>
							<div class="txt">昨日业绩</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todaybusamount"></div>
							<div class="txt">今日业绩</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalbusamount"></div>
							<div class="txt">累计业绩</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">金牛回购余额</div>
				<div><a href=""><span class="number" v-html="cashamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesBonus"></div>
							<div class="txt">昨日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todayBonus"></div>
							<div class="txt">今日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalBonus"></div>
							<div class="txt">累计收益</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">积分余额</div>
				<div><a href="/user/flow/flowCusList?type=4"><span class="number" v-html="intamount"></span><i></i></a></div>
			</div>
		</section>
		<section class="ucenter-block  option-1">
			<div class="block-body">
				<div class="tl-flex">
				-->
					<!--
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val">800.88</div>
							<div class="txt">金牛存量</div>
						</a>
					</div>
					<div class="b-center">
						<a href="">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_buy_jinniu.png"></div>
							<div class="txt">金牛购买</div>
						</a>
					</div>
					<div class="b-center">
						<a href="">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_transfer_jinniu.png"></div>
							<div class="txt">金牛转让</div>
						</a>
					</div>
					-->
					<!--
					<div class="b-center">
						<a href="/user/index/push">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code.png"></div>
							<div class="txt">分享 二维码</div>
						</a>
					</div>
					<div class="b-center">
						<a href="">
							<div class="val" v-html="childRelation"></div>
							<div class="txt">我的社群</div>
						</a>
					</div>
					<div class="b-center">
						<a href="buyTaurusRecords-2.html">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_record.png"></div>
							<div class="txt">消费者订单记录</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		-->
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">消费券</div>
				<div><a href="/user/flow/flowCusList?type=3"><span class="number" v-html="conamount"></span><i></i></a></div>
			</div>
		</section>
		<div class="block-body">
			<div class="tl-flex">
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="yesincomecon"></div>
						<div class="txt">昨日消费券</div>
					</a>
				</div>
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="yesexpendcon"></div>
						<div class="txt">昨日消费</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/user/amount/myCashAmount">
						<div class="num" v-html="cashamount"></div>
						<div class="txt">钱包余额</div>
					</a>
				</div>
			</div>
			<div class="tl-flex mt-10">
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="bonus"></div>
						<div class="txt">消费指数</div>
					</a>
				</div>
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="mallamount"></div>
						<div class="txt">商城消费余额</div>
					</a>
				</div>
				<div class="b-center">
					<a href="javascript:void(0)">
						<div class="num" v-html="stoamount"></div>
						<div class="txt">实体店消费余额</div>
					</a>
				</div>
			</div>
		</div>
		<section class="ucenter-block option-1">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/Conn/Conn/buycon">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_recharge.png"></div>
							<div class="txt">购物充值</div>
						</a>
					</div>
					<div class="b-center">
						<!-- <a href="/user/index/push"> -->
						<a :href="pushUrl">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code.png"></div>
							<div class="txt">分享二维码</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val"><span v-html="direct"></span>/<span v-html="totaldirect"></span></div>
							<div class="txt">消费者社群</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block option-1">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/user/apply/applyToManager">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_open_manager.png"></div>
							<div class="txt">我要申请成为区代理</div>
						</a>
					</div>
					<div class="b-center">
						<a href="/user/flow/flowbuslist">
							<div class="val" v-html="busamount"></div>
							<div class="txt">营业额</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val" v-html="yesconamount"></div>
							<div class="txt">昨日营业额</div>
						</a>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div v-else-if="role==3">
		<!--
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">金牛回购余额</div>
				<div><a href=""><span class="number" v-html="cashamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesBonus"></div>
							<div class="txt">昨日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todayBonus"></div>
							<div class="txt">今日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalBonus"></div>
							<div class="txt">累计收益</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">积分余额</div>
				<div><a href="/user/flow/flowCusList?type=4"><span class="number" v-html="intamount"></span><i></i></a></div>
			</div>
		</section>
		<section class="ucenter-block option-1">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/user/apply/applyToBus">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_opern_store.png"></div>
							<div class="txt">开通商家</div>
						</a>
					</div>
					<div class="b-center">
						<a href="">
							<div class="val" v-html="childRelation"></div>
							<div class="txt">商家数量</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		-->
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">消费券</div>
				<div><a href="/user/flow/flowCusList?type=3"><span class="number" v-html="conamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesincomecon"></div>
							<div class="txt">昨日消费券</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesexpendcon"></div>
							<div class="txt">昨日消费</div>
						</a>
					</div>
					<div class="b-center">
						<a href="/user/amount/myCashAmount">
							<div class="num" v-html="cashamount"></div>
							<div class="txt">钱包余额</div>
						</a>
					</div>
				</div>
				<div class="tl-flex mt-10">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="bonus"></div>
							<div class="txt">消费指数</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="mallamount"></div>
							<div class="txt">商城消费余额</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="stoamount"></div>
							<div class="txt">实体店消费余额</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">昨日销售额</div>
				<div><a href="javascript:void(0)"><span class="number" v-html="yesconamount"></span></a></div>
				<!-- <div><a href="/user/flow/flowCusList?type=2&begintime=<?=date("Y-m-d", strtotime("-1 days"))?>&endtime=<?=date("Y-m-d", time())?>"><span class="number" v-html="conamount"></span><i></i></a></div> -->
				<!-- <div><a href="/user/flow/flowCusList?type=2&begintime="><span class="number" v-html="conamount"></span><i></i></a></div> -->
			</div>
		</section>
		<section class="ucenter-block option-1">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/Conn/Conn/buycon">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_recharge.png"></div>
							<div class="txt">购物充值</div>
						</a>
					</div>
					<div class="b-center">
						<!-- <a href="/user/index/push"> -->
						<a :href="pushUrl">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code.png"></div>
							<div class="txt">分享二维码</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val"><span v-html="direct"></span>/<span v-html="totaldirect"></span></div>
							<div class="txt">消费者社群</div>
						</a>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div v-else-if="role==4">
		<!--
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">金牛回购余额</div>
				<div><a href="#"><span class="number" v-html="cashamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesBonus"></div>
							<div class="txt">昨日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="todayBonus"></div>
							<div class="txt">今日收益</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="totalBonus"></div>
							<div class="txt">累计收益</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">积分余额</div>
				<div><a href="#"><span class="number" v-html="intamount"></span><i></i></a></div>
			</div>
		</section>
		<section class="ucenter-block option-1">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/user/apply/applyToManager">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_open_manager.png"></div>
							<div class="txt">开通区县经理</div>
						</a>
					</div>
					<div class="b-center">
						<a href="">
							<div class="val" v-html="childRelation"></div>
							<div class="txt">区县经理数量</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		-->
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">消费券</div>
				<div><a href="/user/flow/flowCusList?type=3"><span class="number" v-html="conamount"></span><i></i></a></div>
			</div>
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesincomecon"></div>
							<div class="txt">昨日消费券</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="yesexpendcon"></div>
							<div class="txt">昨日消费</div>
						</a>
					</div>
					<div class="b-center">
						<a href="/user/amount/myCashAmount">
							<div class="num" v-html="cashamount"></div>
							<div class="txt">钱包余额</div>
						</a>
					</div>
				</div>
				<div class="tl-flex mt-10">
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="bonus"></div>
							<div class="txt">消费指数</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="mallamount"></div>
							<div class="txt">商城消费余额</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="num" v-html="stoamount"></div>
							<div class="txt">实体店消费余额</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section class="ucenter-block">
			<div class="bar-box">
				<div class="title">昨日销售额</div>
				<div><a href="javascript:void(0)"><span class="number" v-html="yesconamount"></span></a></div>
				<!-- <div><a href="/user/flow/flowCusList?type=2&begintime=<?=date("Y-m-d", strtotime("-1 days"))?>&endtime=<?=date("Y-m-d", time())?>"><span class="number" v-html="conamount"></span><i></i></a></div> -->
				<!-- <div><a href="/user/flow/flowCusList?type=3"><span class="number" v-html="conamount"></span><i></i></a></div> -->
			</div>
		</section>
		<section class="ucenter-block option-1">
			<div class="block-body">
				<div class="tl-flex">
					<div class="b-center">
						<a href="/Conn/Conn/buycon">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_recharge.png"></div>
							<div class="txt">购物充值</div>
						</a>
					</div>
					<div class="b-center">
						<!-- <a href="/user/index/push"> -->
						<a :href="pushUrl">
							<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_share_code.png"></div>
							<div class="txt">分享二维码</div>
						</a>
					</div>
					<div class="b-center">
						<a href="javascript:void(0)">
							<div class="val"><span v-html="direct"></span>/<span v-html="totaldirect"></span></div>
							<div class="txt">消费者社群</div>
						</a>
					</div>
				</div>
			</div>
		</section>
	</div>
	<section class="ucenter-block  option-2">
		<div class="block-body">
			<div class="tl-flex">
				<div class="b-center">
					<a href="">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_order_center.png"><!--<em>20</em>--></div>
						<div class="txt">商城订单</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/User/Userlogistics/logisticslist">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_address_center.png"></div>
						<div class="txt">收货地址管理</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/index/index/download">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_consume_center.png"></div>
						<div class="txt">实体店消费</div>
					</a>
				</div>
			</div>
			<!-- 
			<div class="tl-flex">
				<div class="b-center">
					<a href="takeOutOrder.html">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_store_order_center.png"> <em>20</em></div>
						<div class="txt">实体店订单</div>
					</a>
				</div>
				<div class="b-center">
					<a href="goodsOrders.html">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_order_center.png"><em>20</em></div>
						<div class="txt">商城订单</div>
					</a>
				</div>
				<div class="b-center">
					<a href="shoppingCart.html">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_shopcart_center.png"><em>20</em></div>
						<div class="txt">商城购物车</div>
					</a>
				</div>
			</div>
			<div class="tl-flex" style="margin-top: 10px;">
				<div class="b-center">
					<a href="takeOutOrder.html">
						<div class="pos-r"><img src="<?=$publicDomain?>/mobile/img/icon/ic_heart_center.png"></div>
						<div class="txt">我的评价</div>
					</a>
				</div>
				<div class="b-center">
					<a href="deliveryAddress.html">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_star_center.png"></div>
						<div class="txt">我的收藏</div>
					</a>
				</div>
				<div class="b-center">
					<a href="deliveryAddress.html">
						<div><img src="<?=$publicDomain?>/mobile/img/icon/ic_address_center.png"></div>
						<div class="txt">收货地址管理</div>
					</a>
				</div>
			</div>
			
			<div class="tl-flex" style="margin-top: 10px;">
			-->
				<!--
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
				-->
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
			roleName:"",
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
							// console.log(data);
							_this.headerpic = data.data.userInfo.headerpic != '' ? data.data.userInfo.headerpic : _this.headerpic;
							_this.nickname = data.data.userInfo.nickname;
							_this.phone = data.data.userInfo.phone;
							_this.roleName = data.data.userInfo.roleName;
							_this.conamount = data.data.userAmount.conamount;
							_this.cashamount = data.data.userAmount.cashamount;
							_this.yesincomecon = data.data.incomeConAmount.amount;
							_this.yesexpendcon = data.data.expendConAmount.amount;
							_this.bonus = data.data.bonus;
							_this.pushUrl = _this.push+"?customerid="+_this.customerid+"&checkcode="+data.data.checkcode;
							_this.mallamount = data.data.userAmount.mallamount;
							_this.stoamount = data.data.userAmount.stoamount;
							_this.direct = data.data.childRelation.direct;
							_this.totaldirect = data.data.childRelation.total;
							_this.busamount = data.data.userAmount.busamount;
							_this.yesconamount = data.data.childYesCashAmount.amount;
							_this.busamount = data.data.userAmount.busamount;
							_this.companyMobile = data.data.companyMobile;
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