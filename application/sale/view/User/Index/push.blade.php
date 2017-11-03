{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header">		
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title" v-html="title"></div>
			
			<!-- <a href="" class="save">保存</a> -->
			
		</div>
	</header>
	<section class="QR-wrap">
		<!-- <div><img src="<?=$publicDomain?>/mobile/img/icon/ic_open_manager.png" /></div> -->
		<div><img :src="codeUrl" /></div>
		<!-- <a href="javascript:void(0)">分享</a> -->
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<style>
	.save{position: absolute;top: 0px;right: 10px;color: #666666;}
	.QR-wrap{width: 305px;height: 335px;position: fixed;top: 74px;left: 50%;margin-left: -152.5px;border-radius: 4px;}
	.QR-wrap img{width: 170px;height: 170px;vertical-align: middle;margin-left: 62px;margin-top: 40px;margin-bottom: 40px;}
	.QR-wrap a{display: inline-block;height: 44px;line-height: 44px;text-align: center;width: 260px;background: #CD9951;margin-left: 23px;border-radius: 4px;font-size: 16px;color: #FFFFFF;}
</style>
<script>
	var vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/user/index/getMyCode",
			codeUrl:"",
			title:'<?=$title?>',
			customerid:'<?=$customerid?>'
		},
		methods:{
			getMyCode:function() {
				this.$http.post(this.apiUrl,{
					recommendid:this.customerid
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							// console.log(data);
							this.codeUrl = data.data.htmlUrl;
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("数据查询异常");
					}
				);
			}
		},
		mounted:function(){
			this.getMyCode();
		},
		watch:{

		}
	});
</script>