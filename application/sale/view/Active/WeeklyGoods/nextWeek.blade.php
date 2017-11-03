<?php
use app\model\Sys\CommonModel;
?>
<!DOCTYPE html>
<html style="height: 100%;">

<head>
	<meta charset="UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>每周好货</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/talon_3g.css"/>
	<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
	<link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/baguettebox.min.css"/>
	<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/layer.css" />
	<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
	
	<script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
	<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/layer.js" ></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/baguettebox.min.js" ></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/vue.min.js" ></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/vue-resource.min.js" ></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/tools.js" ></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/common.js"></script>

	<style>
		body{background: #EEEEEE;}
		.tl-toast-box{top: 45%;}
		.weekly-goods{padding:0 8px;background: #FFFFFF;}
		.weekly-header{background: #FFFFFF;height: 44px;line-height: 44px;border-bottom: 1px solid #DDDDDD;position: fixed;display: -webkit-box;    top: 0;width: 100%;left: 0;z-index: 2;}
		.weekly-header .weekly-tab{display: -webkit-box;-webkit-box-flex: 1;width: 100%;-webkit-box-pack: center;}
		.weekly-header .weekly-tab span.active{color: #F13437;border-bottom: 2px solid #F13437;padding: 10px;}
		.weekly-body{margin-top: 44px;}
		.weekly-goods .weekly-one{display: -webkit-box;padding: 8px 0;border-bottom: 0.5px solid #DDDDDD;}
		.weekly-goods a:last-child .weekly-one{border-bottom: none;}
		.weekly-one .good-name{font-size: 13px;color: #333333;}
		
		.weekly-one .countdown{color: #F13437;font-size: 13px;margin-top: 2px;}
		.weekly-one .countdown .day{margin-right: 2px;}
		.weekly-one .countdown .hour{margin-left: 5px;}
		.weekly-one .good-img{display: -webkit-box;-webkit-box-flex: 2;width: 100%;position: relative;}
		.weekly-one .good-img .over-img{position: absolute;width: 65px;left: 50%;margin-left: -32.5px;top: 50%;margin-top: -32.5px;z-index: 1;}
		.weekly-one .good-img .hot-img{position: absolute;width: 50px;left: 8px;top: 8px;z-index: 1;}
		.weekly-one .good-desc{display: -webkit-box;-webkit-box-flex: 1;-webkit-box-pack:justify ;-webkit-box-orient: vertical;
								width: 100%;padding:0px 10px 0;}
		.weekly-one .price-buy{display: -webkit-box;-webkit-box-orient: horizontal;-webkit-box-pack:justify ;-webkit-box-align: end;}
		.weekly-one .price-buy .buy-box{display: -webkit-box;-webkit-box-orient: vertical;-webkit-box-align: end;}
		.buy-box .buy-tip{ color: #333; font-size: 11px;display: -webkit-box;-webkit-box-align: center;margin-bottom: 5px;}
		.buy-box .progress-box{width: 75px;height: 10px;border: 1px solid #F13437;margin-left: 5px;border-radius: 8px;position: relative;}
		.buy-box .progress-box .progress{    background: #f9aeaf; height: 8px; border-radius: 8px 0 0 8px; position: absolute;top:0px;left: 0;border: 0.5px solid #f9aeaf;}
		.buy-box .over-btn{background: #CCCCCC;width: 70px;height: 25px;color: #FFFFFF;border-radius: 3px;font-size: 12px;}
		.buy-box .remind-btn{    background: #529720; width: 70px; height: 25px; color: #FFFFFF; border-radius: 3px; font-size: 12px;}
	    .buy-box .unremind-btn{    background: #FFFFFF;width: 70px;height: 25px; color: #529720; border: 1px solid #529720; border-radius: 3px; font-size: 12px;}
	    
		.buy-box .to-buy-btn{background: #F13437;width: 70px;	height: 25px;color: #FFFFFF;border-radius: 3px;font-size: 12px;}
		.price-box .good-pirce{font-size: 20px;margin-bottom: 0px;}
		.price-box del{font-size: 13px;color: #333333;}
	</style>
</head>

<body>
	<div id="app">
		<section class="weekly-goods">
			<div class="weekly-header">
				<div class="weekly-tab">
					<span :class="{'active':type==0}" v-on:click="weeklyTab(0)">正在抢购</span>
				</div>
				<div class="weekly-tab">
					<span :class="{'active':type==1}" v-on:click="weeklyTab(1)">下期预售</span>
				</div>
			</div>
			<div class="weekly-body">
			<div class="next-week">
				<a :href="'/index/index/goodsdetail?goodsid='+goods.goodsid+'&urltype=1'" v-for="goods in goodslist" :onclick="'window.goToApp.clickOnAndroid(1,'+goods.goodsid+')'">
					<div class="weekly-one">
						<div class="good-img">
							<img :src="goods.thumb" />
							<img v-if="goods.roundPro==100" src="<?=$publicDomain?>/mobile/img/icon/ic_sold_out@2x.png" class="over-img" />
						</div>
						<div class="good-desc">
							<div>
								<div class="tl-ellipsis-2 good-name" v-html="goods.productname"></div>
								<div class="countdown" :data-end-time="goods.strstarttime">
									<span>开始抢购：</span><span v-if="goods.time.days>0"><span class="day" v-html="goods.time.days"></span>天</span><span class="hour" v-html="goods.time.hours"></span>:<span class="minues" v-html="goods.time.mins"></span>:<span class="seconds" v-html="goods.time.secs"></span>
								</div>
							</div>
							<div class="price-buy">
								<div class="price-box">
									<div class="red"><span class="good-pirce" v-html="goods.prouctprice"></span><span>元</span></div>
									<del><span v-html="goods.saleprice"></span><span>元</span></del>
								</div>
								<div class="buy-box">
									<button v-if="goods.remindStatus!=-1" type="button" :class="{'remind-btn':goods.remindStatus!=-1,'unremind-btn':goods.remindStatus==-1}" @click="remindTab($event,goods.id,-1)">取消提醒</button>
									<button v-else type="button" :class="{'remind-btn':goods.remindStatus!=-1,'unremind-btn':goods.remindStatus==-1}" @click="remindTab($event,goods.id,1)">提醒我</button>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		</section>
	</div>

	<script>
		function secondTimer(seconds) {
			var timer={};		//返回对象
			var seconds=seconds;//秒数
			var d=0,
				h=0,
				m=0,
				s=0;
		
			d=parseInt(seconds/(60*60*24));//天
			d=d+'';
		
			h=parseInt(seconds%(60*60*24)/3600);//时
			h=(h<10?'0'+h:h+'');//优化
		
			m=parseInt(seconds%(60*60*24)%3600/60);//分
			m=(m<10?'0'+m:m+'');//优化
		
			s=parseInt(seconds%(60*60*24)%3600%60);//秒
			s=(s<10?'0'+s:s+'');//优化
				
			timer={
				"day":d,
				"hour":h,
				"minute":m,
				"seconds":s
			}
			return timer;
		}
		var timeout;

		function endTiming(){
			$(".countdown").each(function(index,ele){
				var endtime = $(ele).data("endTime")*1000;//取结束日期(毫秒值)
				var nowtime = new Date().getTime();        //今天的日期(毫秒值)
				var youtime = endtime-nowtime;		//还有多久(毫秒值)
				if(youtime<0){
					youtime=0;
					clearTimeout(timeout);
					$(ele).hide().prev().html("活动已结束").css("color","red");
				}
				var seconds = youtime/1000;
		  		var timer=secondTimer(seconds);		//天时分秒对象

		  		$(ele).find(".day").html(timer.day);
		  		$(ele).find(".hour").html(timer.hour);
		  		
		  		$(ele).find(".minues").html(timer.minute);
		  		$(ele).find(".seconds").html(timer.seconds);
			});
				
			setTimeout("endTiming()",1000);
		}
    var Vm = new Vue({
        el:'#app',
        data:{
        	apiUrl:"/active/WeeklyGoods/getListData",
        	remindUrl:"/active/WeeklyGoods/updateRemind",
        	title:"<?=$title?>",
        	page:1,
        	goodsid:"<?=$goodsid?>",
        	type:1,
        	goodslist:[],
        	item:[
        		{status:1}
        	]
        },
        mounted:function(){
        	// 初始化数据
        	this.getListData();
        },
        methods:{
        	getListData:function() {
        		var _this = this;
        		_this.$http.post(_this.apiUrl,{
        			type:_this.type,goodsid:_this.goodsid,page:_this.page
        		}).then(
        			function(res) {
        				data = cl(res);
//         				console.log(data);
        				if(data.code == "200") {
        					_this.goodslist = data.data.list;

        					// 渲染页面后 启用倒计时
        					 // _this.$nextTick(function () {
        						endTiming();
				        		
				        	 // });
        				} else {
        					toast(data.msg);
        				}
        			}, function(res) {
        				toast("数据获取异常");
        			}
        		)
        	},
        	weeklyTab:function(tabIdx) {
        		if(tabIdx == 0) {
        			LinkTo("/active/WeeklyGoods/list");
        		} else if(tabIdx == 1){
        			LinkTo("/active/WeeklyGoods/nextWeek");
        		}
        		// this.type = tabIdx;
        		
        		// this.getListData();
        	},
        	remindTab:function(e,idx,status){
				e.stopPropagation();
				e.preventDefault();

				// 修改提醒状态
				var _this = this;
				_this.$http.post(_this.remindUrl,{
					buyid:idx,remindStatus:status
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							if(status == 1) {
								toast("<p>设置成功<p><p style='font-size:11px;margin-top:5px;'>将会在开抢前3分钟提醒</p>");
								window.location.reload();
							} else {
								toast("已取消提醒");
								window.location.reload();
							}
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作有异");
					}
				);
			}
        },
        watch:{
        }
    });
</script>
</body>
</html>
