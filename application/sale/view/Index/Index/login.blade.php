{include file="Pub/header" /}
<!-- content -->
	<div class="login-wrap">
		<div class="login-main">
			<div class="login-item logo">
				<!-- <img src="<?=$publicDomain?>/mobile/img/icon/login_icon@2x.png" class=""/> -->
				<p>鱼呈一品</p>
			</div>
			<div class="login-item">
				<input type="tel" placeholder="请输入您的手机号"  maxlength="11" v-model.trim="mobile"/>
			</div>
			<div class="login-item">
				<input type="tel" placeholder="请输入验证码" maxlength="6" v-model.trim="valicode" />
				<button class="get-code" @click="sendValicode(event)" v-bind:disabled="valicodeDis">获取验证码</button>
			</div>
			<!--
			<div class="login-item" v-show="register">
				<input type="tel" placeholder="请输入邀请人手机号" maxlength="11" v-model.trim="recommendMobile"/>
			</div>
			-->
			<div class="login-item">
				<button class="login-btn" @click="login">登录/注册</button>
			</div>
			<div class="login-item">
				<div class="register-pact"><input type="checkbox" v-model="registerCheck" @click="updateCheck" checked="checked" value="" />我已阅读并同意<a href="#">《用户注册协议》</a></div>
			</div>
		</div>
	</div>
	
<!-- end content -->

{include file="Pub/tail" /}
<!--
<style>
	body{background: url(../../mobile/img/icon/login-bg.png) no-repeat;
	    background-size: cover;
	    position: absolute;
	    height: 100%;
	    width: 100%;
	    overflow: hidden;
	    bottom: 0;
	    left: 0;
	    top: 0;
	}
	.login-wrap{top: 140px;position: fixed;width: 100%;display: -webkit-box;-webkit-box-pack: center;}
	.login-wrap .login-main{width: 85%;}
	.login-main .login-item{margin-bottom: 25px;position: relative;}
	.login-main .login-item input{width: 100%;height: 40px;padding-left: 20px;border-radius: 4px;font-size: 14px;border: 1px solid #DDDDDD;}
	.login-main .login-item .get-code{width: 100px;font-size: 12px;height: 40px;position: absolute;top: 0px;right: 0;border-radius: 0 4px 4px 0;color: #FFFFFF;background:#CEA15A ;}
	.login-main .login-item .get-code:disabled{background: #CCCCCC;}
	.login-main .login-item .login-btn{width: 100%;height: 40px;border-radius: 4px;background: #CEA15A;font-size: 16px;color: #fff;}
</style>
-->
<style>
	body{background: url(../../mobile/img/icon/login_bg@2x.png) no-repeat;background-size:cover ;position: static;}
	.login-wrap{top: 66px;position: fixed;width: 100%;display: -webkit-box;-webkit-box-pack: center;}
	.login-wrap .login-main{width: 85%;}
	.login-main .login-item{margin-bottom: 25px;position: relative;}
	.login-main .login-item.logo{display: -webkit-box;-webkit-box-pack: center;-webkit-box-align: center;-webkit-box-orient: vertical;}
	.login-main .login-item.logo img{width: 60px;margin-bottom: 5px;}
	.login-main .login-item.logo p{font-size: 13px;color: #bf9148;}
	.login-main .login-item input{width: 100%;height: 40px;padding-left: 20px;border-radius: 4px;font-size: 14px;border: 1px solid #DDDDDD;}
	.login-main .login-item .get-code{width: 100px;font-size: 12px;height: 40px;position: absolute;top: 0px;right: 0;border-radius: 0 4px 4px 0;color: #FFFFFF;background:#CEA15A ;}
	.login-main .login-item .get-code:disabled{background: #CCCCCC;}
	.login-main .login-item .login-btn{width: 100%;height: 40px;border-radius: 4px;background: #CEA15A;font-size: 16px;color: #fff;}
	.register-a{font-size: 13px;color: #9c640b !important;bottom: 15px;position:absolute;left: 50%;margin-left: -26px;text-decoration: underline;}
	
	.register-pact{display: -webkit-box;-webkit-box-pack: center;margin-top: -10px;}
	.register-pact input[type=checkbox]{
		 -webkit-appearance: none;
	    width: 14px;
	    height: 14px;
	    background: url(../../mobile/img/icon/ic_login_not_check@2x.png) -4px -4px no-repeat;
	    background-size: 22px;
	    position: relative;
	    top: 2px;
	    margin-right: 3px;
	    outline: 0;
	    border: 0;
	}
	.register-pact input[type=checkbox]:checked{   
		 -webkit-appearance: none;
	    width: 14px;
	    height: 14px;
	    background: url(../../mobile/img/icon/ic_login_check@2x.png) -4px -4px no-repeat;
	    background-size: 22px;
    
    }
	.register-pact a{color: #925e0a;}
</style>
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/index/index/dologin",
			sendValicodeUrl:"/index/index/sendValicode",
			title:'<?=$title?>',
			redirectUri:'<?=$redirect_uri?>',
			sendType:'<?=$sendType?>',
			checkcode:'<?=$checkcode?>',
			recommendid:'<?=$recommendid?>',
			valicodeDis:true,
			mobile:'',
			valicode:'',
			waits:60,
			timeouts:{},
			// register:true,
			recommendMobile:'',
			registerCheck:true
		},
		mounted:function(){
			// if(this.recommendid) {
			// 	this.register = false;
			// }
		},
		methods:{
			login:function() {
				var _this = this;
				if(_this.mobile == "") {
					toast("手机号码不能为空");
					return false;
				}
				if(_this.valicode == "") {
					toast("验证码不能为空");
					return false;
				}

				if(_this.registerCheck == false) {
					toast("请同意注册协议");
					return false;
				}
				_this.$http.post(_this.apiUrl,{
					mobile:_this.mobile,valicode:_this.valicode
					
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							// 登录成功，页面跳转
							LinkTo(data.data);
						} else if(data.code == "305") {
							// 重定向跳转，到绑定页
							LinkTo(data.data);
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作异常");
					}
				);
			},
			sendValicode:function(e) {
				var _this = this;
				if(_this.mobile == "") {
					toast("手机号码不能为空");
					return false;
				}
				if(_this.mobile.length != 11) {
					toast("手机号码位数有异");
					return false;
				}
				_this.$http.post(_this.sendValicodeUrl,{
					mobile:_this.mobile,sendType:_this.sendType
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							toast("发送成功");
							// 触发倒计时
							_this.wait(e);
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作异常");
					}
				);
			},

			updateCheck:function() {
				this.registerCheck != this.registerCheck;
			},

			wait:function(e) {
				var _this = this;
				var dom = e.target;

				if(_this.waits == 0) {
					dom.innerText = "获取验证码";
					_this.valicodeDis = false;
					_this.waits = 60;
					clearTimeout(_this.timeouts);
					return false;
				} else {
					dom.innerText = "重新发送("+_this.waits+")";
					_this.valicodeDis = true;
					_this.waits--;
					_this.timeouts = setTimeout(function(){
						_this.wait(e);
					},1000);
				}
			}
		},
		watch:{
			mobile:{
				handler:function(val,oldVal) {
					if(val.length == 11) {
						this.valicodeDis = false;
					} else {
						this.valicodeDis = true;
					}
				}
			}
		}
	});
</script>