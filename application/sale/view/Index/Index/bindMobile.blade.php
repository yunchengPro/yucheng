{include file="Pub/header" /}
<!-- content -->
 	<a href="#" @click="prev" class="prev"></a>
	<div class="login-wrap">

		<div class="login-main">
			<div class="login-item logo">
				<img src="<?=$publicDomain?>/mobile/img/icon/login_icon.png" class=""/>
				<p>牛牛汇商家版</p>
			</div>
			<div class="login-item">
				<input type="tel" placeholder="请输入邀请人手机号" maxlength="11" v-model.trim="recommendMobile"/>
				<!-- <input type="tel" placeholder="请输入您的手机号"  maxlength="11" v-model.trim="mobile"/> -->
			</div>
			<div class="login-item">
				<button class="login-btn" @click="login">绑定</button>
			</div>
			
		</div>
	</div>
	<!--
	<footer class="register-footer">
		<input type="checkbox" checked="checked" value="" v-model="registerCheck" @click="updateCheck"/>我已阅读并同意<a href="#">《牛牛汇用户注册协议》</a>
	</footer>
	-->
<!-- end content -->
{include file="Pub/tail" /}
<style>
	body{background: url(../../mobile/img/icon/login_bg@2x.png) no-repeat;background-size:cover ;position: static;}
	.prev{width: 22px;height: 22px;display: block;background: url(../../mobile/img/icon/ic_login_back@2x.png) no-repeat;background-size:100% ;position: absolute;top:12px;left: 15px;}
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
			apiUrl:"/index/index/bindRecomend",
			customerid:'<?=$customerid?>',
			mobile:'<?=$mobile?>',
			encrypt:'<?=$encrypt?>',
			recommendMobile:''
			//registerCheck:true
		},
		mounted:function(){},
		methods:{
			login:function(){
				var _this = this;
				if(_this.recommendMobile == "") {
					toast("邀请人手机号不能为空");
					return false;
				}
				// if(_this.registerCheck == false) {
				// 	toast("请同意注册协议");
				// 	return false;
				// }
				_this.checktoken = '<?=$checktoken?>';
				loadtip({content:'加载中...'});
				_this.$http.post(_this.apiUrl,{
					customerid:_this.customerid,recommendMobile:_this.recommendMobile,checktoken:_this.checktoken
				}).then(
					function(res){
						data = cl(res);
						if(data.code == "200") {
							// 跳转到设置页
							var url = "/user/index/index";
							if(data.data.isloginpwd == 0) {
								var url = "/index/index/updateloginnumber?recommendid=&checkcode=&mobile="+_this.mobile+"&encrypt="+_this.encrypt+"&returnType=2";
							}
							loadtip({
                                close:true,
                                alert:'绑定成功',
                                urlto:url
                                // urlto:'/user/index/index'
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
			},
			updateCheck:function() {
				this.registerCheck != this.registerCheck;
			},

			prev:function(){
				if(confirm("确定要放弃注册吗？")){
					//window.history.go(-1);
					window.location.href="/index/index/login";
				}
			}
		},
		watch:{}
	});
</script>