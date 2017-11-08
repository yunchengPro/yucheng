{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
	</header>
	<section class="logiistics">
		<img :src="thumb" />
		<div class="base-wrap">
			<div><span>物流状态：</span><span class="red" v-html="state"></span></div>
			<div class="c-999"><span>承运来源：</span><span v-html="company_code"></span></div>
			<div class="c-999"><span>运单编号：</span><span v-html="express_number"></span></div>
			<div class="c-999"><span>官方电话：</span><span>暂无</span></div>
		</div>
	</section>
	<section class="logiistics-detail" v-if="status==200">
		<div class="logiistics-list">
			<div v-for="(list, index) in lists" v-bind:class="{'one-log':true, 'active':active===index}">
				<div class="flag">
					<i></i>
				</div>
				<div class="log-desc">
					<div v-html="list.context"></div>
					<div v-html="list.time"></div>
				</div>
			</div>
		</div>
	</section>
<!-- end content -->
{include file="Pub/havenav" /}
<!-- style -->
<style>
	.tl-fixed{position: fixed;width: 100%;top:0;z-index: 1;}
	
	.logiistics{padding: 12px;display: -webkit-box;margin-bottom: 9px;}
	.logiistics img{width:75px;}
	.logiistics .base-wrap{display: -webkit-box;-webkit-box-orient: vertical;margin-left: 10px;}
	
	.delivery-man{height: 50px;line-height: 50px;padding: 0 12px;margin-bottom: 9px;}
	.delivery-man .avatar{width: 40px;vertical-align: middle;margin-right: 20px;margin-top: 5px;float:left;}
	.delivery-man .delivery-info{display: -webkit-box;-webkit-box-pack: justify;line-height: 25px;}
	.delivery-man .delivery-info img{width: 20px;margin-top: 15px;}
	
	.logiistics-detail{}
	.logiistics-detail .logiistics-bar{height: 44px;line-height: 44px;font-size: 13px;padding-left: 20px;border-bottom: 1px solid #DDDDDD;}
	.logiistics-detail .logiistics-bar span{color: #2689ed;}
	.logiistics-list{}
	.logiistics-list .one-log{display: -webkit-box;padding-right: 12px;}
	.logiistics-list .line{border-left: 1px solid #DDDDDD;height: 100%;}
	.logiistics-list .one-log .flag{width: 50px;    position: relative;}
	.logiistics-list .one-log .flag:after{    border-right: 1px solid #DDDDDD;
content: '';
height: 100%;
position: absolute;left: 50%;}
.logiistics-list .one-log.active .flag i{
		    background: url(../../mobile/img/icon/ic_express_green_spot@2x.png) no-repeat;
	    background-size: 100%;
	    width: 20px;
	    height: 20px;
	    display: block;
	    position: relative;
	    left: 50%;
	    margin-left: -10px;
	    z-index: 10;
	    top: 10px;
	}
.logiistics-list .one-log .flag i{
		    background: url(../../mobile/img/icon/ic_express_sopt@2x.png) no-repeat;
	    background-size: 100%;
	    width: 20px;
	    height: 20px;
	    display: block;
	    position: absolute;
	    left: 50%;
	    margin-left: -10px;
	    z-index: 10;
	    top: 10px;
	}

	
	.logiistics-list .one-log .log-desc{font-size: 13px;border-bottom: 1px solid #DDDDDD;padding: 10px 0;display: -webkit-box;width: 100%;-webkit-box-flex: 1;    -webkit-box-orient: vertical;color: #999999;}
	.logiistics-list .one-log.active .log-desc{color: #1ba753;}
</style>
<!-- end style -->
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/order/express/getexpresslist",
			title:'<?=$title?>',
			orderno:'<?=$orderno?>',
			logisticsstatus:'',
			express_number:'',
			company_code:'',
			status:'',
			state:'',
			active:0,
			thumb:'<?=$publicDomain?>/mobile/img/icon/default.png',
			lists:[]
		},
		mounted:function(){
			this.getExpressListData();
		},
		methods:{
			getExpressListData:function() {
				var _this = this;
				_this.$http.post(_this.apiUrl,{
					orderno:_this.orderno
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							// console.log(data);
							_this.status = data.data.status;
							_this.state = data.data.state;
							_this.express_number = data.data.nu;
							_this.company_code = data.data.company_code;
							_this.lists = data.data.data;
							_this.thumb = data.data.thumb!=""?data.data.thumb:_this.thumb;
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("查询有异");
					}
				);
			}
		},
		watch:{},
	});
</script>