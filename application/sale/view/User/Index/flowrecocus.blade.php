{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="niu-coin">
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_back_white@2x.png" class="back-ico"/>
			</a>
			<div class="bar-title">单个牛粉明细</div>
		</div>
		<div class="coin-body">
			<div class="coin-num" v-html="amount"></div>
			<div class="coin-type">奖励牛票总额</div>
		</div>
		<div class="coin-bottom">
			<div class="one-c">
				<div class="c-num" v-html="yesamount"></div>
				<div class="c-tip">昨日奖励牛票</div>
			</div>
			<div class="one-c">
				<div class="c-num" v-html="todayamount"></div>
				<div class="c-tip">今日奖励牛票</div>
			</div>
			<div class="one-c">
				<div class="c-num" v-html="monthamount"></div>
				<div class="c-tip">本月奖励牛票</div>
			</div>
		</div>
	</header>
	
	<section class="deal-list-wrap" v-scroll="getMore">
		<div class="deal-records">
			<div class="deal-block" v-for="flow in flowlist">
				<div class="month-sum">
					<span class="month" v-html="flow.datetime"></span>
					<span class="count">收入<span v-html="flow.revenueAmount"></span> 支出<span v-html="flow.expenseAmount"></span></span>
					<span class="date-picker"></span>
				</div>
				<div class="one-deal" v-for="flowdata in flow.data">
					<div class="desc-time">
						<div class="desc" v-html="flowdata.flowname"></div>
						<div class="time" v-html="flowdata.flowtime"></div>
					</div>
					<div class="sum">
						<div v-if="directiontype[flowdata.direction] === '-'" class="minus" v-html="directiontype[flowdata.direction]+flowdata.amount"></div>
						<div v-else class="plus" v-html="directiontype[flowdata.direction]+flowdata.amount"></div>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div> 
				<p class="loading" v-show="switchShow">加载更多</p>
            </div>
		</div>
	</section>
	<!--
	<section class="deal-list-wrap">
		<div class="deal-records">
			<div class="deal-block">
				<div class="month-sum">
					<span class="month">2017年2月</span>
					<span class="count">收入4.00 支出4.00</span>
					<span class="date-picker"></span>
				</div>
				<div class="one-deal">
					<div class="desc-time">
						<div class="desc">消费-牛店消费</div>
						<div class="time">2017-8-23 14:30:45</div>
					</div>
					<div class="sum">
						<div class="plus">+2.00</div>
					</div>
				</div>
				<div class="one-deal">
					<div class="desc-time">
						<div class="desc">消费-支付</div>
						<div class="time">2017-8-23 14:30:45</div>
					</div>
					<div class="sum">
						<div class="minus">-2.00</div>
					</div>
				</div>
			</div>
			
			<div class="deal-block">
				<div class="month-sum">
					<span class="month">2017年2月</span>
					<span class="count">收入4.00 支出4.00</span>
					<span class="date-picker"></span>
				</div>
				<div class="one-deal">
					<div class="desc-time">
						<div class="desc">消费-牛店消费</div>
						<div class="time">2017-8-23 14:30:45</div>
					</div>
					<div class="sum">
						<div class="plus">+2.00</div>
					</div>
				</div>
				<div class="one-deal">
					<div class="desc-time">
						<div class="desc">消费-支付</div>
						<div class="time">2017-8-23 14:30:45</div>
					</div>
					<div class="sum">
						<div class="minus">-2.00</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	-->
<!-- end content -->
{include file="Pub/tail" /}
<script>
	var vm=new Vue({
		el:"#app",
		data:{
			apiUrl:"/user/index/getFlowRecoCusData",
			role:"<?=$role?>",
			userids:"<?=$userids?>",
			page:1,
			amount:'0.00',
			todayamount:"0.00",
            yesamount:"0.00",
            monthamount:"0.00",
            flowlist:[],
            maxPage:1,
            directiontype:["","+","-"],
            switchShow:false
		},
		mounted:function(){
			this.getFlowRecoCusData();
		},
		methods:{
			getFlowRecoCusData:function() {
				var _this = this;
                _this.$http.post(_this.apiUrl,{
                    selfRoleType:_this.role,customerid:_this.userids,role:_this.role,page:_this.page
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.amount = data.data.amount;
                        	_this.todayamount = data.data.todayamount;
                        	_this.yesamount = data.data.yesamount;
                        	_this.monthamount = data.data.monthamount;

                        	_this.maxPage = data.maxPage;
                        	_this.switchShow = false;

                        	if(_this.page == 1) {
                        		_this.flowlist = data.data.list;
                        	} else {
                        		// 新页面数据
                        		var newPageList = data.data.list;
                        		// console.log(newPageList.length);
                        		if(newPageList.length > 0) {
                        			var keylength = _this.flowlist.length;
                        			// 获取当前页面列表长度
                        			var length = _this.flowlist[keylength-1].data.length;

                        			// // 获取当前页面最后一条数据
                        			var lastData = _this.flowlist[keylength-1].data[length-1];
                        			if(newPageList[0].datetime != lastData.datetime) {
                        			// 	// 直接拼接写入
                        				_this.flowlist = _this.flowlist.concat(data.data.list);
                        			} else {
                        				_this.flowlist[keylength-1].data = _this.flowlist[keylength-1].data.concat(newPageList[0].data);
                        				newPageList.shift();
                        				_this.flowlist = _this.flowlist.concat(newPageList);
                        			}
                        		}
                        	}
                        } else {
                            toast(data.msg);
                        }
                    },function(res){
                        toast("数据查询有异");
                    }
                );
			},
			getMore:function(){
            	var _this = this;
            	_this.switchShow = true;
            	_this.page++;

            	if(_this.page<=_this.maxPage) {
            		_this.getFlowRecoCusData();
            	} else {
            		_this.switchShow = false;
                    $(".loading").hide();
                    return ;
            	}
            }
		},
		directives:{
			scroll: {
                bind: function(el, binding) {
                    window.addEventListener('scroll', function() {
                        if(document.body.scrollTop + window.innerHeight+20 >= el.clientHeight) {
                            var fnc = binding.value;
                            fnc();
                        }
                    })
                }
            }
		},
		watch:{
			
		}
	});
</script>