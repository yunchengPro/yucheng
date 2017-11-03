{include file="Pub/header" /}
<!-- content -->
	<a href="javascript:history.go(-1);" class="prev"></a>
	<div class="login-wrap">
		<div class="login-main">
			<div class="login-item logo">
				<img src="<?=$publicDomain?>/mobile/img/icon/login_icon@2x.png" class=""/>
				<p>牛牛汇商家版</p>
			</div>
			<div class="login-item">
				<input type="tel" placeholder="请输入您的手机号"  maxlength="11" v-model.trim="mobile"/>
			</div>
			<div class="login-item">
				<input type="tel" placeholder="请输入验证码" maxlength="6" v-model.trim="valicode" />
				<button class="get-code" @click="sendValicode($event)" v-bind:disabled="valicodeDis">获取验证码</button>
			</div>
			<div class="login-item">
				<button class="login-btn" @click="next">下一步</button>
			</div>
		</div>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
<style>
	body{background: url(../../mobile/img/icon/login_bg@2x.png) no-repeat;background-size:cover ;position: static;}
	.prev{width: 22px;height: 22px;display: block;background: url(../../mobile/img/icon/ic_login_back@2x.png) no-repeat;background-size:100% ;position: absolute;top:12px;left: 15px;}
	.login-wrap{top: 66px;position: fixed;width: 100%;display: -webkit-box;-webkit-box-pack: center;}
	.login-wrap .login-main{width: 85%;}
	.login-main .login-item{margin-bottom: 25px;position: relative;}
	.login-main .login-item.logo{display: -webkit-box;-webkit-box-pack: center;-webkit-box-align: center;-webkit-box-orient: vertical;}
	.login-main .login-item.logo img{width: 60px;margin-bottom: 5px;}
	.login-main .login-item.logo p{font-size: 13px;color: #bf9148;}
	.login-main .login-item input{width: 100%;height: 40px;padding-left: 20px;border-radius: 4px;font-size: 14px;border: 0.5px solid #DDDDDD;}
	.login-main .login-item .get-code{width: 100px;font-size: 12px;height: 40px;position: absolute;top: 0px;right: 0;border-radius: 0 4px 4px 0;color: #FFFFFF;background:#CEA15A ;}
	.login-main .login-item .get-code:disabled{background: #CCCCCC;}
	.login-main .login-item .login-btn{width: 100%;height: 40px;border-radius: 4px;background: #CEA15A;font-size: 16px;color: #fff;}
	.login-main .login-item .login-btn:disabled{background: #CCCCCC;}
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
	.tab-login{
		    display: -webkit-box;
			-webkit-box-pack: center;
	}
	.tab-login a{text-decoration: underline;}
	.eye-half{
		position: absolute;
		top: 10px;
		right: 10px;
		width: 22px;
		height: 22px;
		display: block;
		background: url(../../mobile/img/icon/ic_login_close_eyes@2x.png) no-repeat;
		background-size:100% ;
		z-index: 20;
	}
	.eye{
		position: absolute;
		top: 10px;
		right: 10px;
		width: 22px;
		height: 22px;
		display: block;
		background: url(../../mobile/img/icon/ic_login_open_eyes@2x.png) no-repeat;
		background-size:100% ;
	}
	.forget-box{
		    height: 30px;
		    line-height: 30px;
		    text-align: right;
	}
	#pwd-input{
		    width: 100%;
		    height: 30px;
		    color: transparent;
		    position: absolute;
		    top: 7px;
		    left: 0;
		    border: none;
		    font-size: 14px;
		    opacity: 0;
		    z-index: 10;
		    letter-spacing: 5px;
		    padding-left: 6px;
	}
	.showBox {
	    width: 100%;
	    height: 30px;
	    font-size: 14px;
	    position: relative;
	    z-index: 1;
	    letter-spacing: 2px;
    }
</style>
<!-- end style -->
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			sendValicodeUrl:"/index/index/sendloginpwd",
			apiUrl:"/index/index/validateloginpwd",
			title:'<?=$title?>',
			checktoken:'<?=$checktoken?>',
			recommendid:'<?=$recommendid?>',
			checkcode:'<?=$checkcode?>',
			mobile:'',
			valicode:'',
			valicodeDis:true,
			flag:true,
			waits:60,
			timeouts:{}
		},
		mounted:function(){},
		methods:{
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
					mobile:_this.mobile
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
			next:function() {
				var _this = this;
				if(_this.mobile == "") {
					toast("手机号码不能为空");
					return false;
				}
				if(_this.valicode == "") {
					toast("验证码不能为空");
					return false;
				}
				// console.log(_this.flag);
				if(_this.flag) {
					_this.flag = false;
					_this.$http.post(_this.apiUrl,{
						mobile:_this.mobile,valicode:_this.valicode,checktoken:_this.checktoken
					}).then(
						function(res) {
							data = cl(res);
							if(data.code == "200") {
								var url = "/index/index/updateloginnumber?recommendid="+_this.recommendid+"&checkcode="+_this.checkcode+"&mobile="+_this.mobile+"&encrypt="+data.data.encrypt+"&returnType=1";
								LinkTo(url);
							} else {
								_this.flag = true;
								toast(data.msg);
								return false;
							}
						}, function(res) {
							console.log(2);
							_this.flag = true;
							toast("操作有异");
							return false;
						}
					);
				}
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