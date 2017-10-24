{include file="Pub/headeruser" /}
<!-- content -->
	<header class="niu-coin">
		<div class="page-bar">
			
			<a href="#">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_back_white@2x.png" class="back-ico"/>
			</a>
			
			<div class="bar-title">我的牛粉</div>
			
		</div>
		<div class="coin-body">
			<div class="coin-num">4.00</div>
			<div class="coin-type">奖励牛票总额</div>
		</div>
		<div class="coin-bottom">
			<div class="one-c">
				<div class="c-num">0.00</div>
				<div class="c-tip">昨日奖励牛票</div>
			</div>
			<div class="one-c">
				<div class="c-num">100.00</div>
				<div class="c-tip">今日奖励牛票</div>
			</div>
			<div class="one-c">
				<div class="c-num">498.00</div>
				<div class="c-tip">本月奖励 牛票</div>
			</div>
		</div>
	</header>
	
	<section class="fans-list-wrap">
		<div class="fans-header">
			<div class="fans-hd active"><div class="status">已审核(2)</div></div>
			<div class="fans-hd"><div class="status">待审核(2)</div></div>
			<div class="fans-hd"><div class="status">未通过(2)</div></div>
		</div>
		<div class="fans-body">
			<div class="fans-item">
				<a href="#">
					<div class="one-fans">
						<div class="fan-avatar">
							<img src="<?=$publicDomain?>/mobile/img/icon/ic_center_head@2x.png" />
						</div>
						<div class="fan-2">
							<div class="name">不要以为是</div>
							<div class="type">消费分享运营者</div>
						</div>
						<div class="fan-3">
							<div><span class="tip">赠-消费分享奖励 </span><span class="red">20.00</span></div>
							<div class="reg-time">注册时间：2017-8-30 </div>
						</div>
						
					</div>
					<i class="ico"></i>
				
				
			</a>
			</div>
		</div>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/index/getFlowRecoData",
            mtoken:"<?=$mtoken?>",
            role:"<?=$role?>",
            recorole:"<?=$recorole?>"
        },
        mounted:function(){
            // 初始化方法
            this.getFlowRecoData();
        },
        methods:{
            getFlowRecoData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                    mtoken:_this.mtoken,role:_this.role,recorole:_this.recorole
                }).then(
                    function(res){
                        data = eval("("+res.body+")");
                        // if(data.code == "200") {
                        	console.log(data.data);
                        // } else {
                            // l(data.msg);
                        // }
                    },function(res){
                        // l("数据查询有异");
                    }
                );
            }
        },
        watch:{}
    });
</script>