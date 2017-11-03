{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content --> 
	<section class="rechange-wrap">
		<span class="money-icon"></span>
		<input type="text" maxlength="30" placeholder="请输入充值金额" class="money" v-model.trim="money"/>
	</section>
	
	<div class="rechange-oper">
		<button class="rechange-btn" @click="choosePayWay" v-bind:disabled="confirmDis" type="button">确定</button>
	</div>

	<div class="tl-mask"></div>
	<section class="choose-type-wrap">
		<div class="choose-header">
			<span>付款详情</span>
			<i class="close"></i>
		</div>
		<div class="choose-type">
			<div class="type-item active">
				<div><img src="<?=$publicDomain?>/mobile/img/icon/ali.png" /> 支付宝</div>
				<i class="chosen"></i>
			</div>
			<div class="type-item">
				<div><img src="<?=$publicDomain?>/mobile/img/icon/weixin.png" /> 微信支付</div>
				<i class="chosen"></i>
			</div>
			<div class="type-item">
				 <div><img src="<?=$publicDomain?>/mobile/img/icon/union.png" class="vertical-middle" /> 银联支付</div>
				 <i class="chosen"></i>
			</div>
		</div>
		<div class="pay-money">
			<div class="money">
				<div>支付金额</div>
				<div><span class="red">1</span>元</div>
			</div>
			<div class="oper">
				<button class="to-pay">确认支付</button>
			</div>
		</div>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<!-- style -->
<style>
	body{background: #EEEEEE;}
</style>
<!-- end style -->
<script>
	var Vm = new Vue({
        el:'#app',
        data:{
        	apiUrl:"/user/recharge/addrecharge",
        	money:'',
        	confirmDis:true
        },
        mounted:function(){
        },
        methods:{
        	choosePayWay:function(){
        		var _this = this;
        		if(_this.money == "") {
        			toast("充值金额不能为空");
        			return false;
        		}
        		if(_this.money <= 0) {
        			toast("充值金额必须大于0");
        			return false;
        		}
        		_this.$http.post(_this.apiUrl,{
        			amount:_this.money
        		}).then(
        			function(res) {
        				data = cl(res);
        				if(data.code == "200") {
        					var url = "/Sys/Pay/paymethod/?orderno="+data.data.orderno;
        					LinkTo(url);
        				} else {
        					toast(data.msg);
        				}
        			}, function(res) {
        				toast("操作有异");
        			}
        		);
        	}
        },
        watch:{
        	money:{
        		handler:function(val,oldVal) {
        			if(val.length != 0) {
        				this.confirmDis = false;
        			} else {
        				this.confirmDis = true;
        			}
        		}
        	}
        }
    });
</script>