{include file="Pub/header" /}
<!-- content -->
	<div class="login-wrap">
		<div class="login-main">
			<div class="login-item logo">
				<img src="<?=$publicDomain?>/mobile/img/icon/login_icon@2x.png" class=""/>
				<p>牛牛汇商家版</p>
			</div>
			<div class="login-item">
				<input type="tel" placeholder="请输入您的手机号" maxlength="11" v-model.trim="mobile"/>
			</div>
			<div class="login-item" style="margin-bottom: 0;">
				<input type="text" id="pwd-input"  maxlength="50" v-model.trim="loginpwd"/>
				<input type="password" class="showBox" placeholder="请输入登录密码" maxlength="50"/>
				<i class="eye-half" id="eye"></i>
			</div>
			<div class="forget-box"><a :href="'/index/index/validloginpwd?recommendid='+recommendid+'&checkcode='+checkcode">忘记密码？</a></div>
			<div class="login-item">
				<button class="login-btn" id="login-btn" v-bind:disabled="loginDis" @click="login">登录</button>
			</div>
			<div class="login-item">
				<div class="tab-login"><a :href="'/index/index/login?recommendid='+recommendid+'&checkcode='+checkcode" class="c-333">注册/使用手机短信登录</a></div>
			</div>
		</div>
	</div>	
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
<style>
	body{background: url(../../mobile/img/icon/login_bg@2x.png) no-repeat;background-size:cover ;position: static;}
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
			apiUrl:"/index/index/dologin",
			title:'<?=$title?>',
			redirectUri:'<?=$redirect_uri?>',
			checkcode:'<?=$checkcode?>',
			recommendid:'<?=$recommendid?>',
			mobile:'',
			loginpwd:'',
			loginDis:true,
			flag:true,
		},
		mounted:function(){
			$("#eye").on("click",function(){
				$(this).toggleClass("eye","eye-half");
				if($(this).hasClass("eye")){
					$(".showBox").attr("type","text");
				}else{
					$(".showBox").attr("type","password");
				}
			});
					
					
			var $input = $(".showBox");  
		    $("#pwd-input").on("input", function() {  
		        var pwd = $(this).val().trim();  
		        $input.val(pwd);
		        // if($input.val().length==6){
		        // 	$("#login-btn").attr("disabled",false);
		        // }
		    });  
		},
		methods:{
			login:function() {
				var _this = this;
				if(_this.mobile == "") {
					toast("手机号码不能为空");
					return false;
				}
				if(_this.loginpwd == "") {
					toast("登录密码不能为空");
					return false;
				}
				if(_this.flag){
					_this.flag = false;
					_this.checktoken = '<?=$checktoken?>';

					_this.$http.post(_this.apiUrl,{
						mobile:_this.mobile,valicode:_this.valicode,redirectUri:_this.redirectUri,checkcode:_this.checkcode,recommendid:_this.recommendid,checktoken:_this.checktoken,
						logintype:2,loginpwd:_this.loginpwd
					}).then(
						function(res) {
							data = cl(res);
							// console.log(data);
							if(data.code == "200") {
								LinkTo("/user/index/index");
							} else {
								_this.flag = true;
								toast(data.msg);
								return false;
							}
						}, function(res) {
							_this.flag = true;
							toast("操作有异");
							return false;
						}
					);
				}
			},
		},
		watch:{
			mobile:{
				handler:function(val,oldVal) {
					if(val.length == 11 && this.loginpwd.length >= 6) {
						this.loginDis = false;
					} else {
						this.loginDis = true;
					}
				}
			},
			loginpwd:{
				handler:function(val,oldVal) {
					if(this.mobile.length == 11 && val.length >= 6) {
						this.loginDis = false;
					} else {
						this.loginDis = true;
					}
				}
			},
		}
	});
</script>