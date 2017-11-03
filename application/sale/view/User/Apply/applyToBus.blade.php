{include file="Pub/header" /}
	<header class="page-header">
		<div class="page-bar">
			<a href="/user/index/index">
				<img src="/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title">我要申请成为商家</div>
		</div>
	</header>
	
	<section class="register-role">
		<div class="register-item">
			<span class="item-tip">姓名</span>
			<input type="text" id="name" name="name" v-model.trim="name" placeholder="请输入姓名" maxlength="11">
		</div>
		<!-- <div class="register-item">
			<span class="item-tip">手机号码</span> 
			<input type="tel" id="mobile" name="mobile" v-model.trim="mobile" placeholder="请输入手机号码" maxlength="11">
		</div> -->
		<div class="register-item">
			<span class="item-tip">所属省市区</span> 
			<input type="text" placeholder="商家所在的省市区" id="txt_area" class="area_inp" readonly="readonly"> 
				<input type="hidden" id="hd_area" value="<?=$hd_area?>" > <i></i>
		</div>
		
		<div class="register-item">
			<span class="item-tip">详细地址</span>
			<input type="text" placeholder="请输入详细地址"  id="address" name="address" v-model.trim="address">
		</div>
		<!-- <div class="register-item">
			<span class="item-tip">加盟方式</span>
			<select v-model.trim="join_type">
				<option value="1">业绩扣除</option>
				<option value="2">折现金支付</option>
			</select>
			<i></i>
		</div> -->
	</section>
	
	<div class="register-role-oper">
		<button v-on:click="applyToBus()">提交</button>
	</div>
	
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
			<button  @click="closeMask">已保存</button>
		</div>
	</div>
		
				

{include file="Pub/tail" /}

<link rel="stylesheet" href="/mobile/css/usercenter.css" />

<link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/dialog.css"/>
<link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/mobile-select-area.css"/>

<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/dialog.js" ></script>
<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/mobile-select-area.js"></script>
<style>
	.register-role{margin-top: 9px;}
	.register-role .register-item{
		    display: -webkit-box;
    position: relative;
    height: 44px;
    line-height: 44px;
    border-bottom: 0.5px solid #DDDDDD;
    padding: 0 10px;
    -webkit-box-align: center;
    font-size: 14px;
	}
	.register-role .register-item .item-tip{margin-right: 10px;}
	.register-role .register-item input[type=text],
	.register-role .register-item input[type=tel]{
		    width: 100%;
	    /* padding-left: 60px; */
	    height: 35px;
	    display: -webkit-box;
	    -webkit-box-flex: 1;
	}
	.register-role .register-item .area_inp {
	    text-align: right;
	    margin-right: 10px;
	    width: 200px;
	}
	.register-role .register-item i {
	    background: url(/mobile/img/icon/ic-right.png) no-repeat;
	    background-size: 100%;
	    height: 22px;
	    width: 22px;
	    position: absolute;
	    top: 12px;
	    right: 5px;
	}
	.register-role .register-item  select{
		position: absolute;
		right: 20px;
		top: 1px;
		height: 40px;
		-webkit-appearance: none;
	}
	.register-role-oper{margin-top: 60px;padding: 0 15px;}
	.register-role-oper button{background: #cd9951;color: #FFFFFF;width: 100%;height: 44px;border-radius: 4px;font-size: 16px;}

	.sure-info .remark{line-height:25px;padding: 5px 0;padding-left:10px;font-size: 14px;}

	.sure-info .info-oper a{color: #0296ff;font-size: 15px;}
	
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

</style>
<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/clipboard.min.js" ></script>
<script type="text/javascript">
	
	var vm=	new Vue({
			el: '#app',
			data: {
			   	apiUrl:'/User/Apply/doapplyToBus',	
			   	name:'',
			   	// mobile:'',
			   	address:'',
			   	join_type:1,
			   	orderno:'',
			   	maskShow:false,
			   	checktoken:'<?=$checktoken?>'
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
		  			window.location.href = '/user/apply/applybusprogress?orderno='+this.orderno;
		  		},
			  	applyToBus:function(){
		  			var _this=this;

		  			if(_this.name == ''){
		  				toast('姓名不能为空');
		  				return false;
		  			}

		  			if(_this.address == ''){
		  				toast('请填写详细地址');
		  				return false;
		  			}

		  			loadtip({
                        content:'加载中..'
                    });
                    _this.$http.post(_this.apiUrl,{
                    	name:_this.name,
                    	// mobile:_this.mobile,
                    	address:_this.address,
                    	//join_type:_this.join_type,
                    	hd_area:$('#hd_area').val(),
                    	checktoken:_this.checktoken
                    }).then(
                        function (res) {
                        	loadtip({
                                close:true
                            });
                           	var _this=this;
                           	data  = res.body 
                           	if(data.code != 408){
                            	data = eval("("+res.body+")");
                           	}
                           	//data = cl(res);
                            if(data.code=='200'){
                            	toast('申请成功');
                            	_this.orderno = data.data.orderno;
                            	_this.sureOrder();
                            	//window.location.href = '/user/apply/applybusprogress?orderno='+_this.orderno;
                           	}else{

                           		toast(data.msg);
                           		_this.orderno = data.data.orderno;
                           		
                           		if(data.code==30011 && _this.orderno != '')
                           			window.location.href = '/user/apply/applybusprogress?orderno='+_this.orderno;
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
	  			var selectArea = new MobileSelectArea();
				selectArea.init({
					trigger:'#txt_area',
					value:$('#hd_area').val(),
					text:['北京', '北京市', '东城区'],
					data:'<?=$publicDomain?>/mobile/data/getAreaJson.json',
					default:0,
					position:"bottom",
					callback:function(scroller,text,value){
						//alert(text);
						//alert(value);
					}
				});

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
	  		},
		  	watch:{
		  	
		  	}
		});
 	</script>
</script>

