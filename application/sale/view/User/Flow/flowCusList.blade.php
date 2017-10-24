{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header">
		<div class="page-bar">
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title" v-html="title"></div>
		</div>
	</header>
	<section class="deal-list-wrap"  v-scroll="getMore">
		<div class="deal-records">
			<div class="deal-block" v-for="list in lists">
				<div class="month-sum">
					<span class="month" v-html="list.datetime"></span>
				</div>
				<div class="one-deal" v-for="item in list.data">
					<div class="desc-time">
						<div class="desc" v-html="directionStrType[item.direction]"></div>
						<div class="time" v-html="item.flowtime"></div>
					</div>
					<div class="sum">
						<div v-if="item.direction==1" class="plus" v-html="directionType[item.direction]+item.amount"></div>
						<div v-else-if="item.direction==2" class="minus" v-html="directionType[item.direction]+item.amount"></div>
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
<!-- end content -->
{include file="Pub/tail" /}
<script>
	var Vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/user/flow/flowCusData",
			title:'<?=$title?>',
			customerid:'<?=$customerid?>',
			type:'<?=$type?>',
			begintime:'<?=$begintime?>',
			endtime:'<?=$endtime?>',
			lists:[],
			page:1,
			maxPage:'',
			directionStrType:["","收益","支出"],
			directionType:["","+","-"],
			switchShow:false
		},
		mounted:function(){
			this.getFlowCusData();
		},
		methods:{
			getFlowCusData:function() {
				var _this = this;
				_this.$http.post(_this.apiUrl,{
					customerid:_this.customerid,type:_this.type,begintime:_this.begintime,endtime:_this.endtime,page:_this.page
				}).then(
					function(res) {
						data = cl(res);
						// console.log(data);
						if(data.code == "200") {
							_this.maxPage = data.data.maxPage;
							_this.switchShow = false;
							if(_this.page == 1) {
								_this.lists = data.data.list;
							} else {
								// 因为有月份 不能直接拼接
								// 新页面数据
								var newPageList = data.data.list;
								if(newPageList.length > 0) {
									var keylength = _this.lists.length;

									// 当前页面列表长度
									var length = _this.lists[keylength-1].data.length;

									// 获取当前页面最后一条数据
									var lastData = _this.lists[keylength-1].data[length-1];
									if(newPageList[0].datetime != lastData.datetime) {
										// 当前页面最后一条数据月份和新页面第一条数据月份不相同，直接拼接
										_this.lists = _this.lists.concat(data.data.list);
									} else {
										// 不同
										_this.lists[keylength-1].data = _this.lists[keylength-1].data.concat(newPageList[0].data);
										newPageList.shift();
										_this.lists = _this.lists.concat(newPageList);
									}
								}
							}
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("数据查询异常");
					}
				);
			},
			getMore:function() {
				var _this = this;
				_this.switchShow = true;
				_this.page++;

				if(_this.page <= _this.maxPage) {
					_this.getFlowCusData();
				} else {
					_this.switchShow = false;
					$(".loading").hide();
					return ;
				}
			}
		},
		directives:{
			scroll:{
				bind:function(el,binding) {
					window.addEventListener('scroll', function(){
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