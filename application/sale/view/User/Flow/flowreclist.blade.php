{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<!-- content -->
	<header class="niu-coin">
		<div class="page-bar">
			<a href="/user/amount/myCashAmount">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_back_white@2x.png" class="back-ico"/>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
		<div class="coin-body">
			<div class="coin-num" v-html="amount"></div>
			<div class="cb-oper">
				<a href="/user/amount/recharge" class="c-btn">充值</a>
				<a href="/Conn/Conn/buycon" class="c-btn">购消费券</a>
			</div>
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
					<div v-if="directiontype[list.direction]==='-'" class="minus" v-html="directiontype[list.direction]+list.amount"></div>
					<div v-else class="plus" v-html="directiontype[list.direction]+list.amount"></div>
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
	.niu-coin {height: 170px;}
	.niu-coin .page-bar{padding: 10px 12px;}
	.niu-coin .coin-body .coin-num{font-size:30px;margin-top: 25px;}
	.coin-body .cb-oper .c-btn {
		width: 90px;
		height: 25px;
		line-height: 23px;
		border: 1px solid #fff;
		border-radius: 2.5px;
		font-size: 13px;
		color: #af8138;
		display: inline-block;
		background: rgba(255, 255, 255, 0.5);
		text-align: center;
		margin-right: 5px;
	}
	.coin-list>.list-item {
		display: -webkit-box;
		-webkit-box-pack: justify;
		-webkit-box-align: center;
		height: 44px;
		padding: 0 12px;
		border-bottom: 0.5px solid #ddd;
	}
	.coin-list>.list-item>.item-tip{font-size: 13px;}
	.coin-list>.list-item>.item-num{color: #fb6d20;font-size: 15px;position: relative; display: -webkit-box;-webkit-box-align: center;}
	.coin-list>.list-item>.item-num a{    
		color: #fb6d20;
		 -webkit-box-align: center; 
    display: -webkit-box;
    /* -webkit-box-pack: center; */
    line-height: 29px;
    }
	.coin-list>.list-item>.item-num i {
		    height: 22px;
		    width: 22px;
		    background: url(../../mobile/img/icon/ic_arrow.png) no-repeat;
		    background-size: 100%;
		    display: inline-block;
		    position: relative;
		    top: 5px;
		    margin-left: -9px;
		    right: -10px;
	}
	.details-box{margin-top: 9px;}
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
            apiUrl:"/user/flow/getflowreclistdata",
            cashListUrl:"/user/amount/getflowlistdata",
        	customerid:'<?=$customerid?>',
        	amount:'0.00',
        	// role:'<?=$role?>',
        	title:'<?=$title?>',
        	// cashamount:'0.00',
        	// recamount:'0.00',
        	// busshow:false,
        	// busamount:'0.00',
        	// yesbusamount:'0.00',
        	lists:[],
        	directiontype:["","+","-"],
        	page:1,
        	maxPage:'',
        	switchShow:false,
        	type:5
        },
        mounted:function(){
        	this.getFlowRecListData();
        },
        methods:{
        	getFlowRecListData:function() {
        		var _this = this;
        		_this.$http.post(_this.apiUrl,{
        			customerid:_this.customerid
        		}).then(
        			function(res) {
        				data = cl(res);
        				if(data.code == "200") {
        					_this.page = 1;
        					_this.maxPage = data.data.maxPage;
        					_this.amount = data.data.amount;
        					_this.lists = data.data.list;
        				} else {
        					toast(data.msg);
        				}
        			}, function(res) {
        				toast("查询异常");
        			}
        		);
        	},
        	getCashAmountListData:function() {
        		var _this = this;
        		_this.$http.post(_this.cashListUrl,{
        			customerid:_this.customerid,type:_this.type,page:_this.page
        		}).then(
        			function(res) {
        				data = cl(res);
        				if(data.code == "200") {
        					_this.lists = _this.lists.concat(data.data.list);
        				} else {
        					toast(data.msg);
        				}
        			}, function(res) {
        				toast("查询异常");
        			}
        		);
        	},
        	getMore:function(){
        		var _this = this;
        		_this.switchShow = true;
        		_this.page++;

        		if(_this.page<=_this.maxPage) {
        			_this.getCashAmountListData();
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