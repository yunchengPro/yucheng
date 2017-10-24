{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title">设置支付密码</div>
		</div>
	</header>

	<section class="config-wrap set-pay-password">
		<div class="config-item">
			<input id="pwd-input" type="tel" v-model.trim="inputNumber" maxlength="6" />
			<input :type="inputType" class="showBox" v-model.trim="payNumber" placeholder="请输入6位数字支付密码" maxlength="6"/>
			<span id="eye" :class="eyeClass" @click="changeInputType"></span>
		</div>
	</section>
	
	<div class="modify-wrap">
		<button type="button" class="sure-btn" id="sure-btn" v-bind:disabled="dis" @click="submitSet">确定</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}

<script type="text/javascript">
	var vm=new Vue({
		el:"#app",
		data:{
			checkcode:"<?=$checkcode?>",
			dis:true,
			inputNumber:"",
			payNumber:"",
			inputType:"password",
			eyeClass:"eye-half"
		},
		methods:{
			changeInputType:function(){
				if(this.inputType=="password"){
					this.inputType="text";
					this.eyeClass="eye";
				}else{
					this.inputType="password";
					this.eyeClass="eye-half";
				}
			},
			submitSet:function(){
				var _this = this;
				var setUrl = "/user/setting/submitPay";
				loadtip({content:'设置中'});
				_this.$http.post(setUrl,{
					paypwd:_this.payNumber,checkcode:_this.checkcode
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							loadtip({
                                close:true,
                                alert:'设置成功',
                                urlto:'/user/setting/safeindex'
                            });
							// toast("设置成功");
							// LinkTo("/user/setting/safeindex");
						} else {
							loadtip({
                                close:true,
                                alert:data.msg
                            });
							// toast(data.msg);
						}
					}, function(res) {
						// toast("操作有异");
						loadtip({
                            close:true,
                            alert:'操作有异'
                        });
					}
				);
			}
		},
		watch:{
			inputNumber:{
				handler:function(val,oldVal) {
					if(this.inputNumber != "") {
						this.dis = false;
					} else {
						this.dis = true;
					}
					this.payNumber = this.inputNumber;
				}
			}
		},
	});
</script>