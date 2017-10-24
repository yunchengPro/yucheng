{include file="Pub/header" /}


	<header class="page-header">
			
			<div class="page-bar">
			
			<a href="javascript:history.go(-1)">
				<img src="/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			
			<div class="bar-title">核对订单</div>
			
		</div>
	</header>
	
	
	<section class="taurus-deal">
		<div class="deal-item" style="-webkit-box-pack: justify;">
			<span class="item-tip">金牛数量</span>
			<div>
				<img src="/mobile/img/icon/taurus.png" class="taurus" />
			 	<span>x</span><span v-html="orderdetail.count" ></span><span></span>
			</div>
		</div>
			<div class="deal-item" style="-webkit-box-pack: justify;">
			<span class="item-tip">购买金额</span>
			<div ><span v-html="orderdetail.totalamount"></span>元</div>
		</div>
		<div class="deal-item" style="-webkit-box-pack: justify;">
			<span class="item-tip">订单编号</span>
			<div><span  v-html="orderdetail.orderno"></span> </div>
		</div>
		<div class="deal-item" style="-webkit-box-pack: justify;">
			<span class="item-tip">购买时间</span>
			<div><span  v-html="orderdetail.addtime"></span></div>
		</div>
		
	
		
	</section>
	
	
	
	<div class="taurus-deal-oper"  style="-webkit-box-pack: center;">
		<a href="#" @click="sureOrder" style="width: 100%;">去支付</a>
		
		
	</div>
	
	
	<div class="tl-select-mask" v-show="maskShow" @click="closeMask"></div>
	<div class="sure-info" v-show="maskShow">
		<div class="info-item-list">
			<div class="info-item">
			<span><strong>账号信息</strong></span>
			</div>
			<div class="info-item">
			<span>收款人：</span><span><?=$con_config['payee']?><!-- talon --></span>
			</div>
			<div class="info-item">
				<span>收款账号：</span><span><?=$con_config['pay_account']?><!-- 621483655711xxxx --></span>
			</div>
			<div class="info-item">
				<span>收款银行：</span><span><?=$con_config['pay_bank']?><!-- 招商银行 --></span>
			</div>
			<div class="info-item">
				<span>支行名称：</span><span><?=$con_config['pay_bank_son']?><!-- 深圳翠竹支行 --></span>
			</div>
		</div>
		<div class="remark">
			<div class="c-999" style="font-size: 12px;">请按此账号打款，打款时在备注输入订单号</div>
			<div>您的订单编号为：<span v-html="orderdetail.orderno"></span></div>
		</div>
		<div class="info-oper">
			<a href="#" @click="closeMask">请截图保存</a>
		</div>
	</div>
				
{include file="Pub/tail" /}

<link rel="stylesheet" href="/mobile/css/usercenter.css" />			
<style>
	
	.taurus-deal{margin-top: 9px;}
	.taurus-deal .deal-item{
		    display: -webkit-box;
    position: relative;
    height: 44px;
    line-height: 44px;
    border-bottom: 0.5px solid #DDDDDD;
    padding: 0 10px;
    -webkit-box-align: center;
     font-size: 14px;
	}
	.taurus-deal .deal-item .item-tip{margin-right: 10px;}
	.taurus-deal .deal-item input[type=text],
	.taurus-deal .deal-item input[type=tel]{
		    width: 100%;
	    /* padding-left: 60px; */
	    height: 35px;
	    display: -webkit-box;
	    -webkit-box-flex: 1;
	}
	.taurus-deal .deal-item .area_inp {
	    text-align: right;
	    margin-right: 10px;
	    width: 200px;
	}
	.taurus-deal .deal-item i {
    background: url(/mobile/img/icon/ic-right.png) no-repeat;
    background-size: 100%;
    height: 22px;
    width: 22px;
    position: absolute;
    top: 12px;
    right: 5px;
}
.taurus-deal .deal-item .taurus{width: 40px;margin-top: -5px;}
	.taurus-deal .deal-item  select{
		position: absolute;
		right: 20px;
		top: 1px;
		height: 40px;
		-webkit-appearance: none;
	}
	.taurus-deal-oper{margin-top: 60px;padding: 0 15px;display: -webkit-box;-webkit-box-pack: justify;width: 100%;-webkit-box-flex: 1;}
	.taurus-deal-oper a{background: #cd9951;color: #FFFFFF;width: 48%;height: 44px;line-height: 44px;border-radius: 4px;font-size: 16px;display: -webkit-box;-webkit-box-pack: center;text-align: center;}
	
	.sure-info .remark{line-height:25px;padding: 5px 0;padding-left:10px;font-size: 14px;}
	.sure-info{background: #FFFFFF;position: fixed;z-index: 100;width: 300px;margin-left: -150px;left: 50%;border-radius: 4px;top:150px;}
	.sure-info .info-item-list{padding:10px 15px 0;font-size: 14px;}
	.sure-info .info-item-list .info-item{height: 30px;line-height: 30px;}
	.sure-info .info-item-list .info-item:last-child {border-bottom: 1px solid #E5E5E5;}
	.sure-info .info-oper{height: 40px;line-height: 40px;text-align: center;border-top: 1px solid #DDDDDD;}
	.sure-info .info-oper a{color: #0296ff;font-size: 15px;}
</style>
<script type="text/javascript">

	var vm=	new Vue({
	  	el: '#app',
		data: {
		  	orderno:'<?=$orderno?>',
		  	orderdetail:[],
		   	maskShow:false
		},
		 
		methods:{
		  		sureOrder:function(){
		  			var _this = this;
		  			window.location.href="/Sys/Pay/paymethod/?orderno="+_this.orderno;
		  			//this.maskShow=true;
		  		},
		  		closeMask:function(){
		  			this.maskShow=false;
		  		},
		  		getconorder:function(){
		  			var _this=this;

                    _this.$http.post('/Conn/Conn/getbuyconorder',{
                    	orderno:_this.orderno
                    }).then(
                        function (res) {
                           	var _this=this;
                            data = eval("("+res.body+")");
                           	//data = cl(res);
                            if(data.code=='200'){
                            	
                               _this.orderdetail = data.data;

                           	}else{
                           		toast(data.msg);
                           	}
                            //$("#city").val('');
                        },function (res) {
                            // 处理失败的结果
                            //console.log(res);
                            toast('加载数据错误！请重新请求');
                            
                        }
                    );
		  		}
		},
		mounted:function(){
	  		var _this = this;
	  		_this.getconorder();
	  	},
		watch:{
	  		
	  	
	  	}
	});
</script>

