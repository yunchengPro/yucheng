{include file="Pub/header" /}
<!-- content -->
	<header class="page-header">
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title" v-html="title"></div>
		</div>
	</header>
	<div class="login-password">
		<div class="item">
			<input type="text" placeholder="请输入登录密码" maxlength="30" v-model.trim="loginpwd"/>
		</div>
		<div class="item">
			<input type="text" placeholder="请再次输入登录密码" maxlength="30" v-model.trim="confirmpwd"/>
		</div>
	</div>
	<div class="password-oper">
		<a href="javascript:void(0);" class="sure-btn" @click="confirm">确定</a>
		<a :href="backbtn" class="back-btn">以后再说</a>
	</div>

	<!--
	<a href="javascript:history.go(-1);" class="prev"></a>
	<div class="login-wrap">
		<div class="login-main">
			<div class="login-item logo">
				<img src="<?=$publicDomain?>/mobile/img/icon/login_icon@2x.png" class=""/>
				<p>牛牛汇商家版</p>
			</div>
			<div class="login-password">
				<div class="item">
					<input type="text" placeholder="请输入登录密码" maxlength="30" v-model.trim="loginpwd"/>
				</div>
				<div class="item">
					<input type="text" placeholder="请再次输入登录密码" maxlength="30" v-model.trim="confirmpwd"/>
				</div>
			</div>
			<div class="password-oper">
				<a href="javascript:void(0);" class="sure-btn" @click="confirm">确定</a>
				<a href="#" class="back-btn">以后再说</a>
			</div>
		</div>
	</div>
	-->
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
<style>
	/*body{background: url(../../mobile/img/icon/login_bg@2x.png) no-repeat;background-size:cover ;position: static;}
	.prev{width: 22px;height: 22px;display: block;background: url(../../mobile/img/icon/ic_login_back@2x.png) no-repeat;background-size:100% ;position: absolute;top:12px;left: 15px;}
	.login-wrap{top: 66px;position: fixed;width: 100%;display: -webkit-box;-webkit-box-pack: center;}
	.login-wrap .login-main{width: 85%;}
	.login-main .login-item{margin-bottom: 25px;position: relative;}
	.login-main .login-item.logo{display: -webkit-box;-webkit-box-pack: center;-webkit-box-align: center;-webkit-box-orient: vertical;}
	.login-main .login-item.logo img{width: 60px;margin-bottom: 5px;}
	.login-main .login-item.logo p{font-size: 13px;color: #bf9148;}*/
	.login-password{margin-top: 40px;padding: 0 15px;}
	.login-password>.item{margin-bottom: 10px;}
	.login-password>.item>input{width: 100%;height: 44px;line-height: 44px;border-radius: 4px;font-size: 13px;padding-left: 15px;}
	.login-password>.item>.tip{color: #ff0000;font-size: 12px;}
	.password-oper{margin-top:30px;padding: 0 15px;display: -webkit-box;-webkit-box-pack: justify;}
	.password-oper>a{display: -webkit-box;width: 48.5%;height: 44px;line-height: 44px;-webkit-box-pack: center;font-size: 16px;border-radius: 4px;}
	.password-oper>a.sure-btn{background: #CD9951;color: #FFFFFF;}
	.password-oper>a.back-btn{background: #CCCCCC;color: #666666;}
</style>
<!-- end style -->
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/index/index/updateloginpwd",
			title:'<?=$title?>',
			mobile:'<?=$mobile?>',
			encrypt:'<?=$encrypt?>',
			checktoken:'<?=$checktoken?>',
			recommendid:'<?=$recommendid?>',
			checkcode:'<?=$checkcode?>',
			returnType:'<?=$returnType?>',
			backbtn:'/user/index/index',
			loginpwd:'',
			confirmpwd:''
		},
		mounted:function(){},
		methods:{
			confirm:function() {
				var _this = this;
				if(_this.loginpwd == "") {
					toast("登录密码不能为空");
					return false;
				}
				if(_this.confirmpwd == "") {
					toast("确认密码不能为空");
					return false;
				}

				if(_this.loginpwd != _this.confirmpwd) {
					toast("两次密码不一致");
					return false;
				}

				loadtip({content:'提交中'});
				_this.$http.post(_this.apiUrl,{
					mobile:_this.mobile,encrypt:_this.encrypt,loginpwd:_this.loginpwd,confirmpwd:_this.confirmpwd,checktoken:_this.checktoken
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							// 跳转到密码登录页
							var url = "/user/index/index";
							if(_this.returnType == 1) {
								// 找回密码方式
								var url = "/index/index/loginbypwd?recommendid="+_this.recommendid+"&checkcode="+_this.checkcode;
								_this.backbtn = "/index/index/loginbypwd?recommendid="+_this.recommendid+"&checkcode="+_this.checkcode;
							}
							loadtip({
                                close:true,
                                alert:'设置成功',
                                urlto:url
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
		},
		watch:{}
	});
</script>