<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>我的订单</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	    <meta name="renderer" content="webkit">
	    <meta name="author" content="talon">
	    <meta name="application-name" content="niuniuhui-wap">
	    <meta http-equiv="Cache-Control" content="no-siteapp" />
	    <meta name="format-detection" content="telephone=no" />
	    <link rel="stylesheet" href="/mobile/css/amazeui.min.css" />
	    <link rel="stylesheet" type="text/css" href="/mobile/css/talon_3g.css"/>
	    <link rel="stylesheet" type="text/css" href="/mobile/css/swiper.min.css"/>
	    <link rel="stylesheet" href="/mobile/css/usercenter.css" />
	    <script type="text/javascript" src="/mobile/js/jquery.min.js" ></script>
	    
	    <script type="text/javascript" src="/mobile/js/vue.min.js" ></script>
	     <script type="text/javascript" src="/mobile/js/vue-resource.min.js" ></script>
	    <style>
	    	
	    
	   </style>
	</head>
	<body >
		<div id="app">
			<header class="page-header">	
				<div class="page-bar">	
					<a href="javascript:history.go(-1)">
						<span class="back-ico"></span>
					</a>
					<span class="bar-title">确认订单</span>
				</div>
			</header>
			
			
			
			<section class="order-bolck address-line">
				<div class="receipt">
					<div>收货人：talon</div>
					<div>13684911212</div>
				</div>
				<a href="../usercenter/deliveryAddress.html"><div class="receipt-address tl-ellipsis-2 pos-r" style="padding-right: 18px;">
					广州省深圳市南山区软件园一期1栋3楼  云牛惠科技有限公司广州省深圳市南山区软件园一期1栋3楼  云牛惠科公司
					<i></i>
				</div>
				</a>
				<div class=""></div>
			</section>
			
			<!--没有收货地址-->
			<!--<section class="order-bolck address-line">
				<a href="#">
				<div class="receipt-address tl-ellipsis-2 pos-r" style="padding-right: 18px;">
					<div class="c-666" style="margin-top: 5px;">暂无地址</div>
					<i></i>
				</div>
				<div class=""></div>
				</a>
			</section>-->
			
			<section class="order-bolck goods-order" style="padding: 0;">
				
				<div class="order-body">
					<div class="order-list">
						<!--已付款-->
						<div class="one-order">
							<div class="shop-stuas">
								<a href="" class="pos-r"><img src="/mobile/img/icon/shop@2x.png" style="width: 25px;margin-right: 10px;"/><span>牛牛汇日用家居商城</span><i></i></a>
								<!--<div class="order-stuas">已付款</div>-->
							</div>
							
							<div class="order-info-box">
								<div class="order-img">
									<img src="/mobile/img/food2.png" />
								</div>
								<div class="order-info">
									<div class="name tl-ellipsis-2">木瓜雪蛤 等2件商品木瓜雪蛤 等2件商品木瓜雪蛤 商品木瓜雪蛤 等2件商品</div>
									<div class="text-r">X<span>1</span></div>
									<div class="order-time"><span>198.00</span>元+<span>98</span>牛豆</div>
								</div>
								
							</div>
							
							<div>
								<div class="sure-item" style="-webkit-box-pack: justify;">
									<div>购买数量<span class="c-999">（每人限购24件）</span></div>
									<div class=""><span class="minus">-</span><span class="number">1</span><span class="plus">+</span></sp></div>
								</div>
								<div class="sure-item" style="-webkit-box-pack: justify;">
									<div>店铺邮费</div>
									<div>8.00元</div>
								</div>
								<div class="sure-item">
									<div>买家留言</div>
									<div><input type="text" class="remark" placeholder="选填，对本次交易的说明"/></div>
								</div>
								
								
							</div>
							
							
							<div class="goods-order-total" style="-webkit-box-pack: end;">
								
								<div>共<span>1</span>件商品&nbsp;&nbsp;小计： <span class="red">208.00</span>元+<span class="red">50</span>牛豆</div>
							</div>
						</div>
						
						
						
					</div>
				</div>
			</section>
			
			<footer class="goods-order-oper" style="-webkit-box-pack: end;padding: 0;">
							合计：<span class="red">504.10</span>元+<span class="red">200</span>牛豆
							<a href="#" class="sub-btn" @click="showPay">提交订单</a>
			</footer>
			
			
			<div class="tl-select-mask" v-show="isShowStep"></div>
			<div class="list-wrap" v-if="payStep.one==1">
				<div class="list-head"><span>付款详情</span><a href="javascript:void(0)" class="close-ico" @click="closeWrap"></a></div>
				<div class="list-body">
					<!--<div class="list-item active">
						<a href="#"><img src="/mobile/img/icon/balance.png">余额支付<i></i></a>
					</div>
					<div class="list-item">
						<a href="#"><img src="/mobile/img/icon/ali.png">支付宝支付<i></i></a>
					</div>
					<div class="list-item">
						<a href="#"><img src="/mobile/img/icon/weixin.png">微信支付<i></i></a>
					</div>
					<div class="list-item">
						<a href="#"><img src="/mobile/img/icon/union.png"/>银联支付<i></i></a>
					</div>-->
					<div :class="['list-item',{'active':curWay==index}]" v-for="(item,index) in payWays" @click="wayTab(index)">
						<a href="#"><img :src="item.payIco">{{item.payName}}<i></i></a>
					</div>
				</div>
				<div class="list-foot">
					<div class="foot-item">
						<div>支付金额</div>
						<div><span class="red">125</span>元</div>
					</div>
					<button type="button" @click="showPassword">确定付款</button>
				</div>
			
			</div>
			
			<div class="pay-pwd-wrap" v-show="payStep.two==1">
				<div class=" text-center" style="position:relative">
					<div class="pay-pwd-title">输入支付密码</div>
					<div class="pwd-colse" @click="closeWrap"></div>
				</div>
				<div class="pwd-box">
					<input type="tel" maxlength="6" class="pwd-input" id="pwd-input">
					<div class="fake-box">
						<input type="password" readonly="">
						<input type="password" readonly="">
						<input type="password" readonly="">
						<input type="password" readonly="">
						<input type="password" readonly="">
						<input type="password" readonly="">
					</div>
				</div>
			</div>		 
			
		</div>
		
		
		<script>
		new Vue({
		  el: '#app',
		  data: {
		  	
			 curWay:0,
			   // isShowType:false,
			    payWays:[
			    	{"payIco":"/mobile/img/icon/balance.png","payName":"余额支付"},
			    	{"payIco":"/mobile/img/icon/ali.png","payName":"支付宝支付"},
			    	{"payIco":"/mobile/img/icon/weixin.png","payName":"微信支付"},
			    	{"payIco":"/mobile/img/icon/union.png","payName":"银联支付"}
			    	
			    ],
			    payStep:{"one":0,"two":0},
			    
			    isShowStep:false
		  
		  },
		  mounted:function(){
		  		
		  		$(".plus").click(function(){
		  			var num=parseInt($(".number").html());
		  			num++;
		  			$(".number").html(num);
		  		});
		  		$(".minus").click(function(){
		  			var num=parseInt($(".number").html());
		  			if(num>1){
		  				num--;
		  				$(".number").html(num);
		  			}
		  			
		  			
		  		});
		  },
		  methods:{
			  	showPay:function(){
			  		this.isShowStep=true;
			  		 this.payStep.one=1;
			  	},
			  	showPassword:function(){
			  		 this.payStep.one=0;
			  		 this.payStep.two=1;
			  		 
			  		   var $input = $(".fake-box input");  
			            $("#pwd-input").on("input", function() {  
			                var pwd = $(this).val().trim();  
			                for (var i = 0, len = pwd.length; i < len; i++) {  
			                    $input.eq("" + i + "").val(pwd[i]);  
			                }  
			                $input.each(function() {  
			                    var index = $(this).index();  
			                    if (index >= len) {  
			                        $(this).val("");  
			                    }  
			                });  
			                if (len == 6) {  
			                    //执行其他操作  
			                    alert("支付校验。。。");
			                }
			            });    
			  	},
			  		wayTab:function(index){
			  		this.curWay=index;
			  	},
			  	closeWrap:function(){
			  		if(confirm("你确定要放弃付款嘛？")){
			  			this.isShowStep=false;
			  			 this.payStep.one=0;
			  			  this.payStep.two=0;
			  		}
			  	}
		  	 	
		  }
		});
 	</script>
	</body>
</html>
