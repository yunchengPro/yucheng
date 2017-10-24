{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header">
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title">提现</div>
		</div>
	</header>
	<section class="withdraw-wrap" id="withdraw">
		<!--
		<div v-if="bankInfoStatus==1">
			<div class="wd-item account" @click="showSexSelect">
				<span class="ac-tip">提现账户</span>
				
				<div class="c-666">企业账户</div>
				<!--
				<select>
					<option>企业账户</option>
					<option>个人账户</option>
				</select>
				-->
				<!--
				<i class="ico"></i>
			</div>
		</div>
		-->
		<div class="wd-item bank-card">
			
				<div v-if="bankInfoStatus==1">
					<a href="/user/setting/mybanklist"><div class="card-name"><span v-html="bankName"></span></div>
					<div class="card-no"><span v-html="accountType[bankType]"></span>(<span v-html="accountNumber.substring(accountNumber.length-4)"></span>)</div></a>
				</div>
				<div v-else>
					<a href="/user/setting/addbank"><div class="no-card">点击添加银行卡</div></a>
				</div>
				<!--
				<div class="card-name">中国建设银行</div>
				<div class="card-no">个人账户（尾号6342）</div>
				-->
				
				<!--<div class="no-card">点击添加银行卡</div>-->
			
			<i class="ico"></i>
		</div>
		<div class="wd-item money" style="padding: 0;">
			<div class="input-money">
				<div class="input-txt">提现金额</div>
				<div><input type="text" placeholder="单笔金额不得低于100元" v-model.trim="withdrawMoney" maxlength="10"></div>
			</div>
			<div class="fee-wrap">
				手续费 <span id="fee" v-html="poundage"></span>
			</div>
			<div class="account-money">可提现金额<span class="red" v-html="accountMoney"></span>元  <span class="withdraw-all" v-on:click="withdrawAll">全部提现</span></div>
		</div>
	</section>
	<div class="withdraw-desc">
		<div class="tip">提现说明</div>
		<!-- 
		<div>1、个人账户提现免手续费额度积累为2000.00元；</div>
		<div>2、超过2000.00元以上按提现金额1%收取手续费；</div>
		<div>3、手续费从提现金额中扣除；</div>
		<div>4、企业账户提现免手续费。</div>
		 -->
		 <div>1、提现资金流向10%商城,10%线下,70%提现,手续费10%</div>
		 <div>2、100整数倍提现</div>
		 <div>3、每周一提现一次</div>
		 <div>4、T+1到账</div>
	</div>
	<div class="withdraw-oper">
		<button class="withdraw-btn" v-bind:disabled="dis" @click="addRecord">确认</button>
	</div>

	<!--
	<div class="tl-select-mask" v-show="isShowSex"></div>
	<div class="tl-bottom-select" v-show="isShowSex">
		<div class="select-wrap">
			<div class="select-item">
				<label><input type="radio"  v-model="accounttype" value="1">个人账户</label>
			</div>
			<div class="select-item">
				<label><input type="radio"  v-model="accounttype" value="2">企业账户</label>
			</div>

		</div>
		<div class="cancel-wrap">
			<div class="select-item" @click="hideSexSelect">取消</div>
		</div>
	</div>
	-->
<!-- end content -->
{include file="Pub/tail" /}
<style>
	.withdraw-wrap{
		
		margin-top: 9px;
	}
	.withdraw-wrap .wd-item{
		position: relative;
		padding: 0 15px;
		border-bottom: 0.5px solid #DDDDDD;
	}
	.withdraw-wrap .wd-item  i.ico{
		    background: url(/mobile/img/icon/ic-right.png) no-repeat;
		    background-size: 100%;
		    height: 22px;
		    width: 22px;
		    position: absolute;
		    top: 50%;
		    right: 5px;
		    margin-top: -12px;
	}
	.withdraw-wrap .account{
		height: 44px;
		line-height: 44px;
		display: -webkit-box;
		-webkit-box-pack:justify;
	}
	.withdraw-wrap .account .ac-tip{
		font-size: 14px;
	}
	.withdraw-wrap .account select{
		-webkit-appearance: none;
	    float: right;
	    margin-top: 10px;
	    margin-right: 10px;
	    font-size: 13px;
	    color: #666;
	}
	.withdraw-wrap .bank-card{
		height: 60px;
		
	}
	.withdraw-wrap .card-name{
		font-size: 14px;
		color: #333333;
		margin: 10px 0;
	}
	.withdraw-wrap .card-no{
		font-size: 13px;
		color: #666666;
	}
	.withdraw-wrap .no-card{
		line-height: 60px;
			font-size: 14px;

	}
	
	.withdraw-oper{margin-top: 40px;padding: 0 15px;}
	.withdraw-oper .withdraw-btn{
		font-size: 17px;
		/*background: #CEA15A;*/
		background: #F13437;
		color: #FFFFFF;
		outline: 0;
		width: 100%;
		border-radius: 4px;
		height: 44px;
	}
	.withdraw-oper .withdraw-btn:disabled{
		background: #CCCCCC;
	}
	.withdraw-wrap .account-money{
	    height: 30px;
	    line-height: 30px;
	    font-size: 12px;
	    color: #999;
	    padding: 0 15px;
	}
	.withdraw-all{color: #1788f1;margin-left: 20px;}
	.withdraw-desc .tip{
		color: #13px;
		color: #666666;
		margin-bottom: 5px;
	}
	.input-money{
		height: 90px;
		padding: 0 15px;
	}
	.input-money .input-txt{
		margin-top: 10px;
	    font-size: 14px;
	    color: #333;
	    margin-bottom: 26px;
	}
	.input-money input::-webkit-input-placeholder{
		font-size: 14px;
		line-height: 44px;
	}
	.input-money input{
		height: 44px;
	   /* border-bottom: 1px solid #ddd;*/
	    width: 100%;
	    outline: 0;
	    padding-left: 35px;
	    background: url(/mobile/img/icon/ic_yuan.png)left center no-repeat;
			background-size: 20px 20px;
			font-size: 30px;

	}
	.fee-wrap{
		height: 30px;
	    line-height: 30px;
	    background: #F5F5F5;
	    padding: 0 15px;
	}
	.withdraw-desc{
		    padding: 15px;
	    color: #999;
	    font-size: 12px;
	}
	button, input, optgroup, select, textarea {
		font:initial;
	}
</style>
<script>
	var vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/user/withdrawals/getBankAmount",
			addUrl:"/user/withdrawals/add",
			customerid:'<?=$customerid?>',
			multiple:'<?=$multiple?>',
			proportion:'<?=$proportion?>',
			bankId:'<?=$bankid?>',
			withdrawMoney:"",
			accountMoney:"0.00",
			dis:true,
			// isShowSex:false,
			accounttype:1,
			bankInfoStatus:0,
			poundage:"0.00",
			bankName:"",
			bankType:0,
			accountNumber:"",
			accountType:["","个人账户","企业账户"]
		},
		mounted:function() {
			this.getBankAmount();
		},
		methods:{
			withdrawAll:function() {
				this.withdrawMoney = this.accountMoney
			},
			// showSexSelect:function() {
			// 	this.isShowSex = true;
			// },
			// hideSexSelect:function() {
			// 	this.isShowSex = false;
			// },
			getBankAmount:function() {
				var _this = this;
				_this.$http.post(_this.apiUrl,{
					customerid:_this.customerid,bankid:_this.bankId
				}).then(
					function(res){
						data = cl(res);
						if(data.code == "200") {
							// console.log(data);
							// console.log(_this.customerid);
							_this.bankInfoStatus = data.data.bankInfo.bankStatus;
							if(_this.bankInfoStatus == 1) {
								// 有银行卡信息 赋值
								_this.bankId = data.data.bankInfo.bankInfo.id;
								_this.bankName = data.data.bankInfo.bankInfo.bank_type_name;
								_this.bankType = data.data.bankInfo.bankInfo.account_type;
								_this.accountNumber = data.data.bankInfo.bankInfo.account_number;
							}
							_this.accountMoney = data.data.amount.cashamount;
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("数据查询异常");
					}
				);
			},
			addRecord:function(){
				var _this = this;
				if(_this.bankId == "") {
					toast("银行卡不能为空");
					return false;
				}
				if(_this.withdrawMoney == "" || _this.withdrawMoney == 0) {
					toast("提现金额不能为空");
					return false;
				}
				if(_this.withdrawMoney > parseInt(_this.accountMoney)) {
					// toast(_this.withdrawMoney);

					toast("提现金额不能大于可提现金额");
					return false;
				}

				if(confirm("确定提交提现申请")) {
					loadtip({content:'提交数据'});
					_this.$http.post(_this.addUrl,{
						customerid:_this.customerid,bankId:_this.bankId,accountNumber:_this.withdrawMoney
					}).then(
						function(res) {
							data = cl(res);
							if(data.code == "200") {
								loadtip({
									close:true,
									alert:"提现申请提交成功",
									// urlto:""
								});
							} else {
								loadtip({
	                                close:true,
	                                alert:data.msg
	                            });
							}
						}, function(res) {
							loadtip({
								close:true,
								alert:'操作有异'
							});
						}
					);
				}
			}
		},
		watch:{
			withdrawMoney:{
				handler:function(val,oldVal) {
					if(val%this.multiple==0){
						// 开始计算手续费
						this.poundage = val*this.proportion+".00";
					} else {
						this.poundage = "0.00";
					}

					if(val > 0 && val%100==0) {
						this.dis = false;
					} else {
						this.dis = true;
					}
				}
			}
		}
	});
</script>