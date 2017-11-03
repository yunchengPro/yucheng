{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
<header class="page-header">
				
	<div class="page-bar">
		
		<a href="javascript:history.go(-1)">
			<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
		</a>
		
		<div class="bar-title">待赠明细</div>
		<!--
		<a href="#" class="filter" id="filter">筛选</a>
		<div class="filter-list" id="filter-list">
			<span class="arrow"></span>
			<div class="filter-item">
				全部
			</div>
			<div class="filter-item">
				收入
			</div>
			<div class="filter-item">
				支出
			</div>
			
			<div class="filter-item">
				充值提现
			</div>
		</div>
		-->
	</div>
</header>

<section class="deal-list-wrap" v-scroll="getMore">
	<div class="deal-items">
		<div class="fans-header">
			<div v-for="(type, index) in types" v-bind:class="{'fans-hd':true,'active':current === index}" v-on:click="getType(index)">
				<div class="status" v-html="type.tp"></div>
			</div>
		</div>
		<!--
		<div class="d-item active">
			<a href="#">牛票</a>
		</div>
		<div class="d-item">
			<a href="#">牛粮</a>
		</div>
		<div class="d-item">
			<a href="#">牛豆</a>
		</div>
		-->
	</div>
	<div class="deal-records" v-if="current===0">
		<div class="deal-block" v-for="menu in menus">
			<div class="month-sum">
				<span class="month" v-html="menu.datetime"></span>
				<!--
				<span class="count">收入{{menu.revenueAmount}} 支出{{menu.expenseAmount}}</span>
				-->
				<span class="date-picker"></span>
			</div>
			<div class="one-deal" v-for="flowdata in menu.data">
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

	<div class="deal-records" v-if="current===1">
		<div class="deal-block" v-for="menu in menus">
			<div class="month-sum">
				<span class="month" v-html="menu.datetime"></span>
				<!--
				<span class="count">收入<span v-html="menu.revenueAmount"></span> 支出<span v-html="menu.expenseAmount"></span></span>
				-->
				<span class="date-picker"></span>
			</div>
			<div class="one-deal" v-for="flowdata in menu.data">
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
	<div class="deal-records" v-if="current===2">
		<div class="deal-block" v-for="menu in menus">
			<div class="month-sum">
				<span class="month" v-html="menu.datetime"></span>
				<!--
				<span class="count">收入<span v-html="menu.revenueAmount"></span> 支出<span v-html="menu.expenseAmount"></span></span>
				-->
				<span class="date-picker"></span>
			</div>
			<div class="one-deal" v-for="flowdata in menu.data">
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
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/flow/futflowcusData",
            type:1,
            screen:'',
            begintime:'',
            endtime:'',
            page:1,
            menus:[],
            directiontype:["","+","-"],
            //3种类型
			types:[
				{"tp":"牛票","num":0,"type":1},
				{"tp":"牛粮","num":0,"type":2},
				{"tp":"牛豆","num":0,"type":3}
			],
			current:0,//默认牛票
			switchShow:false,
			maxPage:''
        },
        mounted:function(){
            // 初始化方法
            this.getflowcusData();
        },
        methods:{
        	getType:function(index) {
        		var _this = this;
            	_this.current = index;

            	_this.type = _this.types[_this.current].type;
            	_this.page = 1;
            	_this.getflowcusData();
        	},
            getflowcusData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                    type:_this.type,screen:_this.screen,begintime:_this.begintime,endtime:_this.endtime,page:_this.page
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.maxPage = data.maxPage;
                        	_this.switchShow = false;
                        	if(_this.page == 1) {
                        		_this.menus = data.data.list;
                        	} else {
                        		// 新页面数据
                        		var newPageList = data.data.list;
                        		// console.log(newPageList.length);
                        		if(newPageList.length > 0) {
                        			var keylength = _this.menus.length;
                        			// 获取当前页面列表长度
                        			var length = _this.menus[keylength-1].data.length;

                        			// // 获取当前页面最后一条数据
                        			var lastData = _this.menus[keylength-1].data[length-1];
                        			if(newPageList[0].datetime != lastData.datetime) {
                        			// 	// 直接拼接写入
                        				_this.menus = _this.menus.concat(data.data.list);
                        			} else {
                        				_this.menus[keylength-1].data = _this.menus[keylength-1].data.concat(newPageList[0].data);
                        				newPageList.shift();
                        				_this.menus = _this.menus.concat(newPageList);
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
            		_this.getflowcusData();
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
        watch:{}
    });
</script>
<script type="text/javascript">
		window.onload = function(){
			var obj=document.getElementById("filter-list");
		    var oBtn=document.getElementById("filter");
		   // searchBox.style.display = "none";
		    oBtn.onclick=function(){
		       obj.style.display="block";
		    }    
		    document.onclick=function(event){
		       //
		       var e=event || window.event;//兼容ie和非ie的event
		       var aim=e.srcElement || e.target; //兼容ie和非ie的事件源
		       //
		        if(e.srcElement){
		        var aim=e.srcElement;
		         if(aim!=oBtn && aim!=obj){
		           obj.style.display="none";
		         }
		       }else{
		         var aim=e.target;
		         if(aim!=oBtn && aim!=obj){
		           obj.style.display="none";
		         }
		       }
		    }     
		}
</script>