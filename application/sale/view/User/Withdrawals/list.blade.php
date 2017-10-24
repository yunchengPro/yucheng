{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link href="<?=$publicDomain?>/mobile/mobiscroll/dev/css/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="<?=$publicDomain?>/mobile/mobiscroll/dev/css/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="<?=$publicDomain?>/mobile/mobiscroll/dev/css/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css" />
<!-- content -->
	<header class="page-header">			
		<div class="page-bar">
			<a href="/user/amount/myCashAmount">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			<div class="bar-title" v-html="title"></div>
			<a class="filter-a" href="#" @click="showDatePicker">筛选</a>
		</div>
	</header>
	<section class="fans-list-wrap" v-scroll="getMore">
		<div class="fans-body">
			<div class="fans-item" v-for="item in list">
				<a href="#">
					<div class="one-fans">
						<div class="fan-2">
							<div class="name">提现-<span v-html="item.bank_name"></span>(<span v-html="item.account_number.substring(item.account_number.length-4)"></span>)</div>
							<div class="type" v-html="item.addtime"></div>
						</div>
						<div class="fan-3">
							<div class="text-r"><span class="tip">-<span v-html="item.amount"></span></span></div>
							<div class="text-r c-999" v-html="item.statusStr"></div>
						</div>
					</div>
					<i class="ico"></i>
				</a>
			</div>
		</div>
		<div>
			<div>
				<p class="loading" v-show="switchShow">加载更多</p>
			</div>
		</div>
	</section>
	<input type="hidden" id="dateTime"/>
<!-- end content -->
{include file="Pub/tail" /}
<style>
	.filter-a{position: absolute;right: 10px;top: 0px;color: #666666;}
</style>
<script src="<?=$publicDomain?>/mobile/mobiscroll/dev/js/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="<?=$publicDomain?>/mobile/mobiscroll/dev/js/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
<script src="<?=$publicDomain?>/mobile/mobiscroll/dev/js/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="<?=$publicDomain?>/mobile/mobiscroll/dev/js/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>
<!-- S 可根据自己喜好引入样式风格文件 -->
<script src="<?=$publicDomain?>/mobile/mobiscroll/dev/js/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>
<script>
	var vm = new Vue({
		el:'#app',
		data:{
			apiUrl:"/user/withdrawals/getListData",
			list:[],
			title:'<?=$title?>',
			customerid:'<?=$customerid?>',
			begintime:"",
			switchShow:false,
			maxPage:'',
			page:1
		},
		mounted:function() {
			var _this = this;

			// 初始化数据
			_this.getDataList();

			var currYear = (new Date()).getFullYear();	
			var opt={};
			opt.date = {preset : 'date'};
			//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
			opt.datetime = {preset : 'datetime'};
			opt.time = {preset : 'time'};
			opt.default = {
				theme: 'android-ics light', //皮肤样式
		        display: 'modal', //显示方式 
		        mode: 'scroller', //日期选择模式
				lang:'zh',
				display: 'bottom', //显示方【modal】【inline】【bubble】【top】【bottom】
				  dateFormat: 'yyyy-mm', // 日期格式
	//		    setText: '确定', //确认按钮名称
	//		    cancelText: '清空',//取消按钮名籍我
			    dateOrder: 'yymm', //面板中日期排列格
			    dayText: '日', 
			    monthText: '月',
			    yearText: '年', //面板中年月日文字
		        startYear:currYear - 10, //开始年份
		        endYear:currYear + 10, //结束年份
		        //选择时事件（点击确定后），valueText 为选择的时间，
		        onSelect: function (valueText, inst) {
		        	_this.begintime = valueText;
		        	_this.page = 1;
		        	_this.getDataList();
		                // vm.getDataList();
		        }
			};

			$("#dateTime").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
		},
		methods:{
			showDatePicker: function () {
				$("#dateTime").mobiscroll('show');
			},
			getDataList:function(){
				var _this = this;
				_this.$http.post(_this.apiUrl,{
					customerid:_this.customerid,begintime:_this.begintime,page:_this.page
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							_this.maxPage = data.data.maxPage;
							_this.switchShow = false;
							if(_this.page == 1) {
								_this.list = data.data.list;
							} else {
								// 直接拼接
								_this.list = _this.list.concat(data.data.list);
							}
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("数据查询异常");
					}
				);
			},
			getMore:function(){
				var _this = this;
				_this.switchShow = true;
				_this.page++;

				if(_this.page <= _this.maxPage) {
					_this.getDataList();
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