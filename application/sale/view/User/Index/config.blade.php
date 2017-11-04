{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="/user/index/index">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
	</header>
	<section class="config-wrap">
		<a href="/user/setting/myinfo">
			<div class="config-item">
				<div class="config-tip">个人设置</div>
				<i></i>
			</div>
		</a>
		<a href="/user/setting/safeindex">
			<div class="config-item">
				<div class="config-tip">账户安全</div>
				<i></i>
			</div>
		</a>
	</section>
	<div class="sign-out-wrap">
		<button type="button" class="sign-out" @click="logout">退出登录</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			loginOutUrl:"/user/index/loginout",
			title:'<?=$title?>'
			// customerid:'<?=$customerid?>'
		},
		mounted:function(){},
		methods:{
			logout:function(){
				var _this = this;
				if(confirm("确定退出登录吗?")) {
					loadtip({content:'退出登录'});
					_this.$http.post(_this.loginOutUrl,{
						// customerid:_this.customerid
					}).then(
						function(res) {
							data = cl(res);
							if(data.code == "200") {
								loadtip({
	                                close:true,
	                                alert:'退出成功',
	                                urlto:'/Index/Index/login'
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
		watch:{}
	});
</script>