{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="niu-coin">
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_back_white@2x.png" class="back-ico"/>
			</a>
			<div class="bar-title">我的牛粉</div>
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
	
	<section class="fans-list-wrap"  v-scroll="getMore">
		<!--3种类型-->
		<div class="fans-header">
			<div v-for="(type, index) in types" v-bind:class="{'fans-hd':true,'active':current === index}" v-on:click="getType(index)">
				<div class="status" v-html="type.tp+'('+type.num+')'"></div>
			</div>
		</div>
		
		<!--已审核-->
		<div class="fans-body" v-if="current===0" >
			<div class="fans-item" v-for="item in list">
				<!-- <a :href="'myFansDetail.html?'+item.no"> -->
				<a :href="'/user/index/flowrecocus?userid='+item.id">
					<div class="one-fans">
						<div class="fan-avatar">
							<img :src="item.thumb" />
						</div>
						<div class="fan-2">
							<div class="name" v-html="item.nickname"></div>
							<div class="type" v-html="item.flowName"></div>
						</div>
						<div class="fan-3">
							<div><span class="tip">赠-消费分享奖励</span><span class="red" v-html="item.total_amount"></span></div>
							<div class="reg-time text-r">注册时间：<span v-html="item.addtime"></span></div>
						</div>
					</div>
					<i class="ico"></i>
				</a>
			</div>
			
			<!--没有牛粉-->
			<div class="fans-no" v-show="fansnoShow">
				<p>暂无牛粉</p>
				<a href="/user/index/mycode?role=1">立即分享</a>
			</div>
			
		</div>
		<!--待审核-->
		<div class="fans-body" v-else-if="current===1">
			<div class="fans-item-2">
				<div class="fan-it" v-for="item in list">
					<span v-html="item.name"></span>
					<span class="sub-time">
						提交时间：<span v-html="item.sub_time"></span>
					</span>
				</div>
			</div>
			
			<!--没有牛粉-->
			<div class="fans-no" v-show="fansnoShow">
				<p>暂无牛粉</p>
				<a href="/user/index/mycode?role=1">立即分享</a>
			</div>
		</div>
		<!--未通过-->
		<div class="fans-body" v-else>
			<div class="fans-item-3">
				<div class="fan-it" v-for="item in list">
					<div class="left">
						<div class="name" v-html="item.name"></div>
						<div class="reason tl-ellipsis"><span>原因：</span><span v-html="item.reason"></span></div>
					</div>
					<span class="sub-time">
						提交时间：<span v-html="item.sub_time"></span>
					</span>
				</div>
			</div>
			<!--没有牛粉-->
			<div class="fans-no" v-show="fansnoShow">
				<p>暂无牛粉</p>
				<a href="/user/index/mycode?role=1">立即分享</a>
			</div>
		</div>

		<div>
			<div> 
				<p class="loading" v-show="switchShow">加载更多</p>
	        </div>
		</div>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
	var vm=new Vue({
		el:"#app",
		data:{
			apiUrl:"/user/index/getFlowPublicData",
			flowrecoUrl:"/user/index/getFlowRecoData",
            role:"<?=$role?>",
            recorole:"<?=$recorole?>",
            amount:"0.00",
            todayamount:"0.00",
            yesamount:"0.00",
            monthamount:"0.00",
            switchShow:false,
            page:1,
            maxPage:"",
            fansnoShow:false,
			//3种类型
			types:[
				{"tp":"已审核","num":0,"status":2},
				{"tp":"待审核","num":0,"status":1},
				{"tp":"未通过","num":0,"status":3}
			],
			current:0,//默认已审核
			list:[]	//主体数据
			
		},
		mounted:function(){
			this.getFlowPubData();
			this.getFlowRecoData(this.current);
		},
		methods:{
			getType:function(index) {
        		var _this = this;
            	_this.current = index;

            	_this.page = 1;
            	_this.getFlowRecoData(_this.current);
        	},
			getFlowPubData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                    role:_this.role,recorole:_this.recorole
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.amount = data.data.amount;
                        	_this.todayamount = data.data.todayamount;
                        	_this.yesamount = data.data.yesamount;
                        	_this.monthamount = data.data.monthamount;
                        	_this.types[0].num = data.data.examsuccess;
                        	_this.types[1].num = data.data.examwait;
                        	_this.types[2].num = data.data.examfail;
                        } else {
                            toast(data.msg);
                        }
                    },function(res){
                        toast("数据查询有异");
                    }
                );
            },
            getFlowRecoData:function(index) {
            	var _this = this;
            	_this.current = index;

            	var status = _this.types[_this.current].status;
            	_this.$http.post(_this.flowrecoUrl,{
            		role:_this.role,recorole:_this.recorole,status:status,page:_this.page
            	}).then(
            		function(res) {
            			data = cl(res);
            			if(data.code == "200") {
            				_this.maxPage = data.maxPage;
                        	_this.switchShow = false;
            				if(_this.page == 1) {
	            				if(data.data.list.length == 0) {
	                        		_this.fansnoShow = true;
	                        		_this.list = [];
	                        	} else {
	                        		_this.list = data.data.list;
	                        	}
            				} else {
            					_this.list = _this.list.concat(data.data.list);
            				}
            			} else {
            				toast(data.msg);
            			}
            		}, function(res) {
            			toast("数据查询有异");
            		}
            	);
            },
            getMore:function(){
            	var _this = this;
            	_this.switchShow = true;
            	_this.page++;

            	if(_this.page<=_this.maxPage) {
            		_this.getFlowRecoData(_this.current);
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