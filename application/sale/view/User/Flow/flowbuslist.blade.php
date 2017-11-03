{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
	</header>

	<section class="details-box" v-scroll="getMore">
		<!--<div class="dt-bar">钱包明细</div>-->
		<div class="detail-list">
			<div class="dt-item" v-for="list in lists">
				<div class="dt-left">
					<div class="txt" v-html="list.flowname"></div>
					<div class="time" v-html="list.flowtime"></div>
				</div>
				<div class="dt-right">
					<div v-if="list.direction==1" class="plus"><span v-html="directionType[list.direction]"></span><span v-html="list.amount"></span></div>
					<div v-else class="minus"><span v-html="directionType[list.direction]"></span><span v-html="list.amount"></span></div>
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
<!-- style -->
<style> 	
	.details-box>.dt-bar{background: #EEEEEE;height: 32px;line-height: 32px;padding-left: 12px;border-bottom: 0.5px solid #ddd;}
	.detail-list>.dt-item{height: 60px;border-bottom: 0.5px solid #DDDDDD;display: -webkit-box;-webkit-box-pack: justify;-webkit-box-align: center;padding: 0 12px;}
	.dt-item>.dt-left>.txt{font-size: 14px;margin-bottom: 5px;}
	.dt-item>.dt-left>.time{font-size: 12px;color: #999999;}
	.dt-item>.dt-right{font-size: 15px;}
	.dt-item>.dt-right>.plus{color: #fb6d20;}
</style>
<!-- end style -->
<script>
	var Vm = new Vue({
        el:'#app',
        data:{
        	apiUrl:"/user/flow/getflowbuslistdata",
        	title:'<?=$title?>',
        	customerid:'<?=$customerid?>',
        	page:1,
        	lists:[],
        	maxPage:'',
        	directionType:["","+","-"],
        	switchShow:false
        },
        mounted:function(){
        	this.getflowbuslistdata();
        },
        methods:{
        	getflowbuslistdata:function() {
        		var _this = this;
	        	_this.$http.post(_this.apiUrl,{
	        		customerid:_this.customerid,page:_this.page
	        	}).then(
	        		function(res){
	        			data = cl(res);
	        			if(data.code == "200") {
	        				_this.maxPage = data.data.maxPage;
	        				if(_this.page == 1) {
	        					_this.lists = data.data.list;
	        				} else {
	        					_this.lists = _this.lists.concat(data.data.list);
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

        		if(_this.page<=_this.maxPage) {
        			_this.getflowbuslistdata();
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