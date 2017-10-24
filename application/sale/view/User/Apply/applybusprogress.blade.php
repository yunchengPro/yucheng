{include file="Pub/header" /}
<header class="page-header">
		
		<div class="page-bar">
		
		<a href="/user/apply/applyToBus">
			<img src="/mobile/img/icon/back@2x.png" class="back-ico">
		</a>
		
		<div class="bar-title">申请商家进度</div>
		
	</div>
</header>
	

<section class="apply-progress">
	<div class="am-text-center"><img src="/mobile/img/icon/success.png" /></div>
	
	<div class="apply-tip" >您已成功提交申请，正在审核中……</div>
<!-- 	<div class="apply-tip" v-if="orderDetail.status == 2">您已成功提交申请，审核已通过……</div>
	<div class="apply-tip" v-if="orderDetail.status == 3">您已成功提交申请，申请已被拒绝……</div> -->

	<div class="apply-items">
		<div class="item">
			<div class="item-l">
				<i class="point-suc"></i>
				<div class="line-suc-half"></div>
			</div>
			<div class="item-r">
				<div class="f-14">提交申请</div>
				<div class="c-999" v-html="orderDetail.addtime"></div>
			</div>
		</div>
		<div class="item">
			<div class="item-l">
				<i class="point-suc"></i>
				<div class="line-suc"></div>
			</div>
			<div class="item-r">
				<div class="f-14">平台审核</div>
				<div class="c-999" v-html="orderDetail.examinetime"></div>
			</div>
		</div>
		<div class="item">
			<div class="item-l">
				<i class="point-df"></i>
				<div class="line-df-half"></div>
				
			</div>
			<div class="item-r">
				<div class="f-14"  v-if="orderDetail.status == 1">正在审核</div>
				<div class="f-14"  v-if="orderDetail.status == 2">申请成功</div>
				<div class="f-14"  v-if="orderDetail.status == 3">审核失败</div>
			</div>
		</div>
		
	</div>
</section>
{include file="Pub/tail" /}
<style>
	.apply-progress{padding-top: 45px;}
	.apply-progress .f-14{font-size: 14px;}
	.apply-progress img{width: 90px;margin-bottom: 25px;}
	.apply-progress .apply-tip{text-align: center;font-size: 17px; margin-bottom: 25px;}
	.apply-items>.item{height: 44px;line-height: 44px;padding-right: 15px;display: -webkit-box;}
	.apply-items>.item>.item-l{width: 40px;display: -webkit-box;position: relative;-webkit-box-pack: center;-webkit-box-align: center;}
	.apply-items>.item:first-child>.item-r{border-top: 0.5px solid #E5E5E5;}
	.apply-items>.item>.item-r{border-bottom: 0.5px solid #E5E5E5;display: -webkit-box;-webkit-box-pack: justify;width: 100%;-webkit-box-flex: 1;}
	.apply-items>.item>.item-l>.point-suc{background: #CEA15A;width: 8px;height: 8px;display: block;border-radius: 50%;}
	.apply-items>.item>.item-l>.line-suc-half{height: 22px;width: 1px;background: #CEA15A;position: absolute;bottom:0;left: 50%;margin-left: -0.5px;}
	.apply-items>.item>.item-l>.line-suc{height: 44px;width: 1px;background: #CEA15A;position: absolute;bottom:0;left: 50%;margin-left: -0.5px;}
	.apply-items>.item>.item-l>.point-df{background: #CCCCCC;width: 8px;height: 8px;display: block;border-radius: 50%;}
	.apply-items>.item>.item-l>.line-df-half{height: 22px;width: 1px;background: #CCCCCC;position: absolute;top:0;left: 50%;margin-left: -0.5px;}
</style>
<script type="text/javascript">

	var vm=	new Vue({
	  	el: '#app',
	  	data: {
	  	
   			apiUrl:'/User/Apply/getbusorder',	
		   	orderno:'<?=$orderno?>',
		   	orderDetail:[]
	  	},
	 
	  	methods:{
	  		getOrderRow:function(){
	  			var _this=this;
	  			loadtip({
                    content:'加载中..'
                });
                _this.$http.post(_this.apiUrl,{
                	orderno:_this.orderno,
                	
                }).then(
                    function (res) {
                    	loadtip({
                            close:true
                        });
                       	var _this=this;
                        data = eval("("+res.body+")");
                       	//data = cl(res);
                        if(data.code=='200'){
                        	
                        	_this.orderDetail = data.data;
                        	
                       	}else{
                       		toast(data.msg);
                       		window.location.href = '/user/apply/applyToBus';
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
		  	_this.getOrderRow();
	  	},
		watch:{
		  		
		  	
		}
	});
</script>

