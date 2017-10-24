{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
<header class="page-header">
				
		<div class="page-bar">
		
		<a href="javascript:history.go(-1)">
			<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
		</a>
		
		<div class="bar-title">我的余额</div>
		
		
	</div>
</header>

<!--我的余额-->
	<section class="ucenter-block">
		<div class="bar-box text-center">
			<img src="<?=$publicDomain?>/mobile/img/icon/ic_money_red@2x.png" class="m-ico" style=""/><span class="title">余额</span>
			
		</div>
		<div class="block-body">
			<div class="tl-flex">
				<div class="b-center">
					<a href="/user/index/cashindex">
						<div class="num" v-html="cashamount"></div>
						<div class="txt">牛票（元）</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/user/index/profitindex">
						<div class="num" v-html="profitamount"></div>
						<div class="txt">牛粮（元）</div>
					</a>
				</div>
				<div class="b-center">
					<a href="/user/index/bullindex">
						<div class="num" v-html="bullamount"></div>
						<div class="txt">牛豆</div>
					</a>
				</div>
			</div>
			<div class="detail-box"><a href="/user/flow/flowcusindex" class="red">交易明细</a></div>
		</div>
	</section>
	<!--/end 我的余额-->

	<!--待赠奖励-->
	<section class="ucenter-block">
		<div class="bar-box text-center">
			<img src="<?=$publicDomain?>/mobile/img/icon/ic_money_yellow@2x.png" class="m-ico" style=""/><span class="title">待赠奖励</span>
			
		</div>
		
		<div class="block-body">
			<div class="tl-flex">
				<div class="b-center">
					<a href="">
						<div class="num" v-html="futcashamount"></div>
						<div class="txt">牛票（元）</div>
					</a>
				</div>
				<div class="b-center">
					<a href="">
						<div class="num" v-html="futprofitamount"></div>
						<div class="txt">牛粮（元）</div>
					</a>
				</div>
				<div class="b-center">
					<a href="">
						<div class="num" v-html="futbullamount"></div>
						<div class="txt">牛豆</div>
					</a>
				</div>
			</div>
			<div class="detail-box"><a href="/user/flow/futflowindex" class="orange">交易明细</a></div>
		</div>
		
	</section>
	<!--/end 待赠奖励-->
<!-- end content -->

{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/index/mybalanceData",
            cashamount:'',
            profitamount:'',
            bullamount:'',
            futcashamount:'',
            futprofitamount:'',
            futbullamount:'',
        },
        mounted:function(){
            // 初始化方法
            this.getBalanceData();
        },
        methods:{
            getBalanceData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                            _this.cashamount = data.data.amountInfo.cashamount;
                            _this.profitamount = data.data.amountInfo.profitamount;
                            _this.bullamount = data.data.amountInfo.bullamount;
                            _this.futcashamount = data.data.futList.cash;
                            _this.futprofitamount = data.data.futList.profit;
                            _this.futbullamount = data.data.futList.bull;
                        } else {
                        	toast(data.msg);
                        }
                    },function(res){
                    	toast("数据查询有异");
                    }
                );
            }
        },
        watch:{}
    });
</script>
<!-- start style -->
<style>
	.ucenter-block .bar-box .m-ico{
		    vertical-align: middle;
	    width: 20px;
	    margin-right: 5px;
	    margin-top: -3px;
	}
	.ucenter-block .bar-box.text-center{
		    text-align: center;
		    display: -webkit-box;
		    -webkit-box-align: center;
		    -webkit-box-pack: center;
	}
	
	
	
	.block-body .detail-box{
		text-align: center;
		margin: 15px 0 5px;
	}
	.block-body .detail-box a.red{
	    border-radius: 10px;
	    border: 1px solid #f13437;
	    color: #f13437;
	    padding: 1px 15px;
	    font-size: 12px;
	}
	.block-body .detail-box a.orange{
		 border-radius: 10px;
	    border: 1px solid #fe8520;
	    color: #fe8520;
	    padding: 1px 15px;
	    font-size: 12px;
	}
	
</style>
<!-- end style -->