{include file="Pub/headeruser" /}
<!-- content -->
	<header class="deal-header">	
		<div class="page-bar">
			
			<a href="javascript:history.go(-1)">
				<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			
			<div class="bar-title">交易明细</div>
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
		</div>
	</header>

	<section class="deal-list-wrap">
		<div class="deal-items">
			
			<div class="d-item active">
				<a href="#">牛票</a>
			</div>
			<div class="d-item">
				<a href="#">牛粮</a>
			</div>
			<div class="d-item">
				<a href="#">牛豆</a>
			</div>
		</div>

		<div class="deal-records">
			<div class="deal-block" v-for="menu in menus">
				<div class="month-sum">
					<span class="month">{{menu.datetime}}</span>
					<span class="count">收入{{menu.revenueAmount}} 支出{{menu.expenseAmount}}</span>
					<span class="date-picker"></span>
				</div>
				<div class="one-deal" v-for="flowdata in menu.data">
					<div class="desc-time">
						<div class="desc">{{flowdata.flowname}}</div>
						<div class="time">{{flowdata.flowtime}}</div>
					</div>
					<div class="sum">
						<div v-if="directiontype[flowdata.direction] === '-'" class="minus">{{directiontype[flowdata.direction]+flowdata.amount}}</div>
						<div v-else class="plus">{{directiontype[flowdata.direction]+flowdata.amount}}</div>
						<!-- <div class="plus">{{directiontype[flowdata.direction]+flowdata.amount}}</div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/flow/flowcusData",
            mtoken:"<?=$mtoken?>",
            type:'',
            screen:'',
            begintime:'',
            endtime:'',
            page:'',
            menus:[],
            directiontype:["","+","-"]
        },
        mounted:function(){
            // 初始化方法
            this.getflowcusData();
        },
        methods:{
            getflowcusData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                    mtoken:_this.mtoken,type:1,screen:_this.screen,begintime:_this.begintime,endtime:_this.endtime,page:1
                }).then(
                    function(res){
                        data = eval("("+res.body+")");
                        if(data.code == "200") {
                        	_this.menus = data.data.list;
                        } else {
                        	l(data.msg);
                        }
                    },function(res){
                    	l("数据查询有异");
                    }
                );
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