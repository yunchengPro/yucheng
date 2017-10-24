{include file="Pub/header" /}
	
				<header class="page-header">
						
						<div class="page-bar">
						
						<a href="/user/index/index">
							<img src="/mobile/img/icon/back@2x.png" class="back-ico">
						</a>
						
						<div class="bar-title">购物充值</div>
						<a href="/Conn/Conn/buyconlist" class="buy-record">购买记录</a>
					</div>
				</header>
				
				<section class="coupons-banner">
					<img src="/mobile/img/icon/coupons.png" />
				</section>
				
				<section class="buy-coupons">
					<div class="bar">购物券金额</div>
					<div class="face-values">
						<div class="tl-grid">
							
							<?php foreach($conn_coupon as $coupon){ ?>
								<div class="tl-grid-1-3">
									<label <?php if($coupon == 1000){ ?> class="active" <?php } ?>>￥<input type="radio" name="face" <?php if($coupon == 1000){ ?> checked="checked" <?php } ?>  value="<?=$coupon?>" /> <?=$coupon?> </label>
								</div>
							<?php } ?>
			
							<!-- <div class="tl-grid-1-3">
								<label class="active"><input type="radio" name="face" checked="checked" value="1000"/>1000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="3000"/>3000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="5000"/>5000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="10000"/>10000</label>
							</div> -->


							<!-- <div class="tl-grid-1-3">
								<label><input type="radio" name="face" checked="checked" value="1"/>1</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" checked="checked" value="1000"/>1000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="3000"/>3000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="5000"/>5000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="10000"/>10000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="30000"/>30000</label>
							</div>
							<div class="tl-grid-1-3">
								<label><input type="radio" name="face" value="50000"/>50000</label>
							</div> -->
						</div>
						
						<div class="face-item first">
							<div>购买数量</div>
							<div class="number-box">
								<div class="mins"></div>
								<div class="number">1</div>
								<div class="plus"></div>
							</div>
						</div>
						<div class="face-item">
							<div>支付总金额</div>
							<div >￥<span class="total_amount">1000</span></div>
						</div>
						<div class="face-item">
							<div>购买总面值</div>
							<div class="total">2000</div><input type="hidden" id="amount" name="amount" value="1000" />
						</div>
						
					</div>
					
					<div class="taurus-deal-oper">
						<a href="#" v-on:click="buyConOline()">在线购买</a>
		
						<a href="#" v-on:click="buyConReturn()">转账购买</a>
					</div>
					
				</section>
				
				
				<div class="tl-select-mask" v-show="maskShow" @click="closeMask"></div>
					<div class="sure-info sure-info2" v-show="maskShow">
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
							
							<div ><span>您的订单编号为：</span><span id="orderNo" v-html="orderno"></span></div>
						</div>
						<div class="info-oper">
							
							<button  class="copy-btn" data-clipboard-action="copy" data-clipboard-target="#orderNo">复制订单号</button>
							<button  @click="closeMask">已保存</div>

							
						</div>
					</div>
				
<!-- 				

	<section class="ucenter-block info-wrap">
		<a href="/user/index/index"><img src="/mobile/img/icon/ic_center_setting.png" class="config"></a>
		<div class="info">

			<a href="#"><img src="/mobile/img/icon/default_head.png" class="avatar"></a>
			<div class="base">
				<div class="name"><?=$userinfo['nickname']?></div>
				<div class="phone"><?=$userinfo['phone']?></div>
			</div>
			<div class="role"><span><?=$role?></span></div>
		</div>
	</section>


	<div class="taurus-deal">
		
		<div class="taurus-ico">
			<img src="/mobile/img/icon/jiniu_buy.png" />
		</div>
		<div class="deal-item">
			<span class="item-tip">购买金额</span>
			<input type="text" placeholder="请输入购买金额" maxlength="20" v-model="amount"/>
		</div>
		<div class="deal-item" style="-webkit-box-pack: justify;">
			<span class="item-tip">金牛数量</span>
			<div>
				<!--<img src="/mobile/img/icon/taurus.png" class="taurus" />-->
			<!--<span>x</span>--><!-- <span  v-html="taurus"></span> -->
			<!--</div>
		</div>
	</div> -->


<!-- 	<div class="taurus-deal-oper">
		<a href="#" v-on:click="buyConOline()">在线购买</a>
		
		<a href="#" v-on:click="buyConReturn()">转账购买</a>
	</div>		 -->
				
{include file="Pub/tail" /}
<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/clipboard.min.js" ></script>
<link rel="stylesheet" href="/mobile/css/usercenter.css" />
<style>
 	body{background: #FFFFFF;}
	.buy-record{position: absolute;right: 12px;top: 0;color: #666;font-size: 12px;}
	.coupons-banner{padding: 15px;border-bottom: 9px solid #EEEEEE;}
	.buy-coupons .bar{height: 44px;line-height: 44px;padding-left: 15px;font-size: 14px;}
	.buy-coupons .face-values{padding: 0px 20px;}
	.buy-coupons .face-list{
		    display: -webkit-box;
			-webkit-box-pack: justify;
	}
	.buy-coupons label {
	       display: -webkit-box;
	       -webkit-box-pack: center;
		    margin-bottom: 0;
		    font-weight: normal;
		    line-height: 30px;
		    font-size: 15px;
		    /* margin-top: 8px; */
		    border: 1px solid #CEA15A;
		    width: 100%;
		    border-radius: 4px;
		  /*  background: #fff3e3;*/
		    color: #cea15a;
		    padding: 4px 0;

	}
	.buy-coupons label.active{ background: #fff3e3;}
	.buy-coupons .face-values input[type=radio] {
	    -webkit-appearance: none;
	    width: 15px;
	    height: 15px;
	    background: url(/mobile/img/icon/ic_not_check@2x.png) no-repeat;
	    background-size: 100%;
	    position: relative;
	    top: 2px;
	    margin-right: 10px;
	        border-radius: 0;
	        display: none;
	}
	.buy-coupons .face-values input[type=radio]:checked {
		 -webkit-appearance: none;
	    width: 15px;
	    height: 15px;
	    background: url(/mobile/img/icon/ic_check@2x.png) no-repeat;
	    background-size: 100%;
	}
	.face-values .tl-grid-1-3{    padding: 8px;}
	.face-values .face-item.first{border-top: 1px solid #DDDDDD;margin-top: 10px;}
	.face-values .face-item{display: -webkit-box;-webkit-box-pack: justify;font-size: 14px;border-bottom: 1px solid #DDDDDD;height: 44px;line-height: 44px;}
	.face-values .face-item .number-box{display: -webkit-box;-webkit-box-align: center;}
	.face-values .face-item .plus{   background: url(/mobile/img/icon/plus.png) no-repeat;background-size:100% ;width: 30px;height: 30px;}
	.face-values .face-item .mins{    background: url(/mobile/img/icon/mins.png) no-repeat;background-size:100% ;width: 30px;height: 30px;}
	.face-values .face-item .number{width: 42px;height: 30px;line-height: 30px;text-align: center;font-size: 13px;}
	.taurus-deal-oper{margin-top: 40px;padding: 0 15px;display: -webkit-box;-webkit-box-pack: justify;width: 100%;-webkit-box-flex: 1;padding-bottom: 40px;}
	.taurus-deal-oper a{background: #cd9951;color: #FFFFFF;width: 48%;height: 44px;line-height: 44px;border-radius: 4px;font-size: 16px;display: -webkit-box;-webkit-box-pack: center;text-align: center;}
	.sure-info{background: #FFFFFF;position: fixed;z-index: 100;width: 250px;margin-left: -125px;left: 50%;border-radius: 4px;top:200px;}
			.sure-info .info-item-list{padding:10px 15px;font-size: 14px;border-bottom: 1px solid #E5E5E5;}
			.sure-info .info-item-list .info-item{height: 30px;line-height: 30px;}
			.sure-info .info-oper{height: 40px;line-height: 40px;}
			.sure-info .info-oper a{color: #0296ff;font-size: 15px;}
			.sure-info2{background: #FFFFFF;position: fixed;z-index: 100;width: 300px;margin-left: -150px;left: 50%;border-radius: 4px;top:150px;}
			.sure-info2 .remark{line-height:25px;padding: 5px 0;padding-left:10px;font-size: 14px;}
			.sure-info2 .info-oper {
			    height: 40px;
			    line-height: 40px;
			    border-top: 1px solid #DDDDDD;
			  
				  
			}
			.sure-info2 .info-oper button {
				height: 40px;
			    width: 50%;
			  	float: left;
			    border-right: 0.5px solid #DDDDDD;
			        color: #0296ff;
    			font-size: 15px;
    			border-radius: 4px;
    			background: #fff;
    			text-align: center;
			}
			.sure-info2 .info-oper button:last-child{border-right: 0;}
			/*.sure-info .info-oper a{color: #0296ff;font-size: 15px;}*/

</style>
<script type="text/javascript">
		
		
	
	var vm=	new Vue({
			el: '#app',
			data: {
			   	payDefault:true,
			   	money:'',
			   	taurus:0,
			   	maskShow:false,
			   	amount:'',
			   	mobile:'<?=$mobile?>',
			   	pay_voucher:1,
		   		businessid:'<?=$businessid?>',
		   		con_price:'<?=$con_price?>',
		   		con_maxamout:'<?=$con_maxamout?>',
		   		con_minamout:'<?=$con_minamout?>',
		   		maskShow:false,
		   		orderno:''
			},
		 
		    methods:{
		  		showInfo:function(){
		  			this.maskShow=true;
		  		},
		  		forwardOrder:function(){
		  			this.closeMask();
		  			
		  		},
		  		sureOrder:function(){
		  			this.maskShow=true;
		  		},
		  		closeMask:function(){
		  			this.maskShow=false;
		  		},
		  		buyConOline:function(){
		  			var _this=this;
		  			if(confirm("确定购买?")) {
			  			_this.amount = $("#amount").val();
			  			// if(_this.amount < _this.con_minamout || _this.amount > _this.con_maxamout){
			  			// 	toast('购买金额范围在1000~50000');
			  			// 	return false;
			  			// }
			  			loadtip({
	                        content:'加载中..'
	                    });
	                    _this.$http.post('/Conn/Conn/buyconSub',{
	                    	amount:_this.amount,
	                    	//mobile:_this.mobile,
	                    	pay_voucher:1,
	                    	businessid:_this.businessid
	                    }).then(
	                        function (res) {
	                        	loadtip({
	                                close:true
	                            });
	                           	var _this=this;
	                            data = eval("("+res.body+")");
	                           	//data = cl(res);
	                            if(data.code=='200'){
	                            	
	                                toast('提交成功');
	                                window.location.href="/Conn/Conn/showbuycon?orderno="+data.data;
	                                //window.location.href="/Sys/Pay/paymethod/?orderno="+data.data;

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
		  		buyConReturn:function(){
		  			var _this=this;
		  			if(confirm("确定购买?")) {
		  			_this.amount = $("#amount").val();
		  			loadtip({
                        content:'加载中..'
                    });
		  			// if(_this.amount < _this.con_minamout || _this.amount > _this.con_maxamout){
		  			// 	toast('购买金额范围在1000~50000');
		  			// 	return false;
		  			// }

                    _this.$http.post('/Conn/Conn/buyconSub',{
                    	amount:_this.amount,
                    	mobile:_this.mobile,
                    	pay_voucher:2,
                    	businessid:_this.businessid
                    }).then(
                        function (res) {
                        	loadtip({
                                close:true
                            });
                           	var _this=this;
                            data = eval("("+res.body+")");
                           	//data = cl(res);
                            if(data.code=='200'){
                            	
                                toast('提交成功');
                                _this.orderno = data.data;
                                _this.sureOrder();
                                //window.location.href="/Conn/Conn/showbuycon?orderno="+data.data;
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
		  		}
		    },
		    mounted:function(){

		    		//复制订单号
		    		var clipboard = new Clipboard('.copy-btn');

				    clipboard.on('success', function(e) {
				        //console.log(e);
				        alert("复制成功！")
				    });
				
				    clipboard.on('error', function(e) {
				       // console.log(e);
				       alert("复制失败，请手动复制！")
				    });


		    			$(".tl-grid-1-3 label").click(function(){
					   		$(this).addClass("active").parent().siblings().find("label").removeClass("active");
					   	});
		    			$("#amount").val(1000);
	  			 		$(".plus").click(function(){
					  	 	var num=parseInt($(".number").html());
					  	 	var price=parseFloat($("input[name='face']:checked").val());
					  	 	var con_price = "<?=$con_price?>";
					  	 	var con_more_price = "<?=$con_more_price?>";
					  	 	if(price >= 10000){
					  	 		con_price = con_more_price;
					  	 	}
					  	 	num++;
					  	 	$.get("/Conn/Conn/ajaxcanbuycon?customerid=<?=$customerid?>&amount="+num*price, function(result){
							    console.log(result);
								result = eval("("+result+")");
								
								if(result.code == "200"){
									$(".number").html(num);

									if(num*price >= 10000)
										con_price = con_more_price;

							  	 	$(".total").html(num*price*con_price);
							  	 	$("#amount").val(num*price);
							  	 	$(".total_amount").html(num*price);
								}else{
									toast(result.msg);
								}
							});
					  	 	
					  	});
					  	 
					  	$(".mins").click(function(){
					  	 	var num=parseInt($(".number").html());
					  	 	var price=parseFloat($("input[name='face']:checked").val());
					  	 	var con_price = "<?=$con_price?>";
					  	 	var con_more_price = "<?=$con_more_price?>";
					  	 	if(price >= 10000){
					  	 		con_price = con_more_price;
					  	 	}
					  	 	if(num>1){
					  	 		num--;
					  	 		$.get("/Conn/Conn/ajaxcanbuycon?customerid=<?=$customerid?>&amount="+num*price, function(result){
								    console.log(result);
								    result = eval("("+result+")");
								    
								    if(result.code == "200"){
										$(".number").html(num);
										if(num*price >= 10000)
											con_price = con_more_price;
								  	 	$(".total").html(num*price*con_price);
								  	 	$("#amount").val(num*price);
								  	 	$(".total_amount").html(num*price);
									}else{
										toast(result.msg);
									}
								});
					  	 		
					  	 	}
					  	 	
					  	 });
					  	 
					  	$("input[name='face']").change(function(){
						  	 	var num=parseInt($(".number").html());
						  	 	var price=parseFloat($("input[name='face']:checked").val());
						  	 	var con_price = "<?=$con_price?>";
						  	 	var con_more_price = "<?=$con_more_price?>";
						  	 	if(price >= 10000){
						  	 		con_price = con_more_price;
						  	 	}
						  	 	$.get("/Conn/Conn/ajaxcanbuycon?customerid=<?=$customerid?>&amount="+num*price, function(result){
								    console.log(result);
									result = eval("("+result+")");
									
									if(result.code == "200"){
										$(".number").html(num);
										if(num*price >= 10000)
											con_price = con_more_price;
								  	 	$(".total").html(num*price*con_price);
								  	 	$("#amount").val(num*price);
								  	 	$(".total_amount").html(num*price);
									}else{
										toast(result.msg);
									}
								});
						  	 
					  	});
	  		},
		    watch:{
		  		
		  		
		  	// 	amount:{
		  	// 		handler:function(val,oldVal){
		  	// 			var num=parseInt(val);
		  	// 			var con_price = this.con_price;
		  	// 			if(num){
		  	// 				this.taurus=num*con_price;
		  	// 			}else{
		  	// 				this.taurus=0;
		  	// 			}
						
					// }
		  	// 	}
		  	
		    }
		});
</script>

