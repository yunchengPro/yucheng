{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="niu-coin">
		<div class="page-bar">
			
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_back_white@2x.png" class="back-ico"/>
			</a>
			
			<span class="bar-title">牛豆</span>
			<a href="/user/flow/flowcusindex?type=3" class="detail">明细</a>
		</div>
		<div class="coin-body">
			<div class="coin-num" v-html="amount"></div>
			<div class="coin-type">当前牛豆余额</div>
		</div>
		<div class="coin-bottom">
			<div class="one-c">
				<div class="c-num" v-html="yesamount"></div>
				<div class="c-tip">昨日所得牛豆</div>
			</div>
			<div class="one-c">
				<div class="c-num" v-html="monthamount"></div>
				<div class="c-tip">本月所得牛豆</div>
			</div>
			<div class="one-c">
				<div class="c-num" v-html="totalamount"></div>
				<div class="c-tip">累计所得牛豆</div>
			</div>
		</div>
	</header>
	
	<section class="coin-oper">
		<a href="javascript:toast('请您前往APP进行操作或查看');" class="oper-btn red">充值</a>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/index/mycashData",
            type:"<?=$type?>",
            amount:'',
            yesamount:'',
            monthamount:'',
            totalamount:''
        },
        mounted:function(){
            // 初始化方法
            this.getmyCashData();
        },
        methods:{
            getmyCashData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                    type:_this.type
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                            _this.amount = data.data.amount;
                            _this.yesamount = data.data.yesamount;
                            _this.monthamount = data.data.monthamount;
                            _this.totalamount = data.data.totalamount;
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