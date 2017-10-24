{include file="Pub/headeruser" /}
<!-- content -->
	<header class="withdraw-header">	
		<div class="page-bar">
		
			<a href="#">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>

			<div class="bar-title">提现</div>
			</div>
	</header>

	<!--提现-->
	<section class="withdraw-wrap" id="withdraw">
		<div class="wd-item account">
			<span class="ac-tip">提现账户</span>
			<select>
				<option>企业账户</option>
				<option>个人账户</option>
			</select>
			<i class="ico"></i>
		</div>
		<div class="wd-item bank-card">
			<a href="#">
				
				<div class="card-name">中国建设银行</div>
				<div class="card-no">个人账户（尾号6342）</div>
				
				<!--<div class="no-card">点击添加银行卡</div>-->
			</a>
			<i class="ico"></i>
		</div>
		<div class="wd-item money">
			<div class="input-money">
				<div class="input-txt">提现金额</div>
				<div><input type="text" placeholder="单笔金额不得低于100元" v-model="withdrawMoney" maxlength="10"></div>
			</div>
			<div class="account-money">可提现金额<span class="red">{{accountMoney}}</span>元  <span class="withdraw-all" v-on:click="withdrawAll">全部提现</span></div>
		</div>
	</section>

	<div class="withdraw-desc">
		<div class="tip">提现说明</div>
		<div>1、个人账户提现免手续费额度积累为2000.00元；</div>
		<div>2、超过2000.00元以上按提现金额1%收取手续费；</div>
		<div>3、手续费从提现金额中扣除；</div>
		<div>4、企业账户提现免手续费。</div>
	</div>
	<div class="withdraw-oper">
		<button class="withdraw-btn">确认</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
