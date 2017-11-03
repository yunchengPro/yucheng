{include file="Pub/header" /}
<!-- content -->
	<header class="page-header">
		<div class="page-bar">
			<a href="/user/setting/safeindex">
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
		<a href="/user/setting/safeindex" class="back-btn">以后再说</a>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
<style>
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
			apiUrl:'/user/setting/setloginpwd',
			title:'<?=$title?>',
			loginpwd:'',
			confirmpwd:'',
			checktoken:'<?=$checktoken?>'
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
					loginpwd:_this.loginpwd,confirmpwd:_this.confirmpwd,checktoken:_this.checktoken
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							loadtip({
                                close:true,
                                alert:'设置成功',
                                urlto:"/user/setting/safeindex"
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