<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>中秋好礼</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    <style>
    	 body { background: #FFFFFF;}
		.activity-wrap .activity-banner img{width: 100%;}
    	.activity-wrap .activity-goods-list{padding: 0 8px;}
    	.activity-wrap .activity-goods-list .one-good{
    		display: -webkit-box;
    		width: 100%;
    		
    		padding: 8px 0;
    		border-bottom: 0.5px solid rgba(221, 221, 221, 1);
    		position: relative;
    	}
    	.activity-wrap .activity-goods-list .one-good a{
    		display: -webkit-box;
    		width: 100%;
    	}
    	 .activity-goods-list .one-good .good-img{
    	 	width: 100%;
    	 	-webkit-box-flex:2;
    	 	 display: -webkit-box;
    	 }
    	 .activity-goods-list .one-good .good-info{
    	 	padding: 5px 0;
    	 	width: 100%;
    	 	-webkit-box-flex:1;
    	 	margin-left: 10px;
    	 	 position: relative;
    	 	 display: -webkit-box;
    	 	-webkit-box-orient: vertical;
    	 }
    	 .one-good .good-info .g-name{
		    font-size: 14px;
		    color: #333;
		    font-weight: bold;
		    margin-bottom: 8px;
		    word-break: break-all;
    	 }
    	 .one-good .good-info  .slogan{
    	 	color: #7e7ff6;
    	 	font-size: 12px;
    	 }
    	 .one-good .good-info .price{
    	 	    position: absolute;
    			bottom: 5px;
    
    	 }
    	 .one-good .good-info .price .num{font-size: 18px;font-weight: bold;}
    	 .one-good .good-info .price .yuan{font-size: 12px;font-weight: bold;}
	    .one-good .good-info .price del{
	    	font-size: 10px;
	    	color: #999999;
	    }
    	 .one-good .good-info .go-grab{
    	 	    background: url(<?=$publicDomain?>/mobile/img/icon/button_zhongqiu.png) no-repeat;
			    background-size: 100%;
			    padding: 4px 17px;
			    font-size: 12px;
			    border-radius: 2px;
			    position: absolute;
			    right: 0;
			    bottom: 8px;
			    color: #FFFFFF;
			      /*  display: -webkit-box;*/
			     float: right;
    	 }
    	 @media only screen and (min-width: 320px) and (max-width: 360px) {
    	 	
    	 	.one-good .good-info .g-name{
    	 		margin-bottom: 3px;
    	 	}
    	 	.one-good .good-info .price .num{
    	 		font-size: 16px;
    	 	}
    	 	 .one-good .good-info .go-grab{
    	 	 	bottom: 0;
    	 	 }
    	 }
    </style>
</head>

<body>
  
    <style>
    	
    </style>
    <section class="activity-wrap">      
    	<div class="activity-banner">
    		<img src="<?=$publicDomain?>/mobile/img/zhongqiu/banner.png" />
    	</div>
    	<div class="activity-goods-list" id="good-list">
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10312&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10312')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/01.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 月满家园月饼C版552g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">刮走的是岁月,留下的是青春</div>-->
	    					<div class="price">
	    						<span class="red num">158.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥239</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10311&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10311')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/02.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 锦绣月月饼C版710g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">每天握住的不仅是包，更像牵着你的手</div>-->
	    					<div class="price">
	    						<span class="red num">168.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥468</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10310&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10310')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/03.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 礼颂金秋月饼817g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">每分每秒我愿与你永不分离</div>-->
	    					<div class="price">
	    						<span class="red num">198.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1550</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10309&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10309')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/04.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 旺财临门月饼735g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">告诉他，从此包容你的女人心</div>-->
	    					<div class="price">
	    						<span class="red num">188.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥888</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10308&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10308')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/05.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 福月盈门月饼778g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">时间为我作证，爱你的初衷永不改变</div>-->
	    					<div class="price">
	    						<span class="red num">178.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥2500</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10307&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10307')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/06.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 花开富贵月饼705g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">有你的日子，希望你每一天都如花般绽放</div>-->
	    					<div class="price">
	    						<span class="red num">178.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥599</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10306&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10306')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/07.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 团圆礼月饼705g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">愿它分分秒秒替我</div>-->
	    					<div class="price">
	    						<span class="red num">168.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥298</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10305&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10305')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/08.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 如意礼月月饼646g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">火机是烟头的希望，我愿做你幸福的起航</div>-->
	    					<div class="price">
	    						<span class="red num">148.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥299</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10304&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10304')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/09.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 美满祝福月饼554g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">让你以后的美丽成为我的责任吧</div>-->
	    					<div class="price">
	    						<span class="red num">138.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥139</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>	
    		</div>
    		
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10303&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10303')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/10.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 金秋如意月饼710g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">戴上它，从此你的世界只有我</div>-->
	    					<div class="price">
	    						<span class="red num">148.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥129</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10301&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10301')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/11.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 流心奶黄月饼480g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">心疼你的每一天</div>-->
	    					<div class="price">
	    						<span class="red num">188.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥298</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10299&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10299')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/12.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 水果月饼705g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">只愿时光驻留，锁定美丽的你</div>-->
	    					<div class="price">
	    						<span class="red num">168.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10296&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10296')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/13.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 富贵浓情月饼528g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">一杯子，一辈子</div>-->
	    					<div class="price">
	    						<span class="red num">148.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥148.5</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10295&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10295')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/14.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 水晶月饼 (椭圆盒）647g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">我愿包容你的一切</div>-->
	    					<div class="price">
	    						<span class="red num">158.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1998</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10293&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10293')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/15.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安琪 双黄红莲蓉月饼710g/盒</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">158.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9384&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9384')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/16.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 感恩尚礼月饼</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">88.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9383&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9383')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/17.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 吉祥年港香双黄伍仁月饼</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">98.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9381&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9381')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/18.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 至尊蛋黄白莲蓉味月饼650克装</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">88.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9380&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9380')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/19.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 港式双黄白莲蓉味月饼</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">88.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9379&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9379')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/20.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 至尊蛋黄白莲蓉味月饼600克装/盒</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">88.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9000&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9000')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/21.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 感谢礼月饼整箱装(36盒/箱)</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">712.80</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=8993&urltype=1" onclick="window.goToApp.clickOnAndroid('1','8993')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/22.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 金尊双黄白莲蓉月饼广式月饼</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">98.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=8990&urltype=1" onclick="window.goToApp.clickOnAndroid('1','8990')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/zhongqiu/23.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">琪华 迷你双黄三色月饼 广式月饼</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="price">
	    						<span class="red num">58.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		
    		
    		
    		
    		
    		
    	</div>
    </section>
    
    
    <!--<p class="no-data">亲！没有数据了哦~</p>
	<input type="hidden" id="limit" value="2">
	<div id="loading">
			<p>玩命加载中…</p>
	</div>-->
</body>
<script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/jquery.lazyload.js" ></script>
<script>
  /* $(function(){
        
        setProductImgSize();
        $(window).resize(function(){
            setProductImgSize();
        });
    });
    
    //动态设置商品图片宽高
    function setProductImgSize(){
        pro_with=$(".good-img").width();
        //这里设置宽高一样
        $(".good-img img").css({
            "with":pro_with,
            'height':pro_with
        });
    }*/
</script>
<script>
   $(function() {
          $("img").lazyload({ 
          		 placeholder : "<?=$publicDomain?>/mobile/img/loading.gif",
          		 failurelimit : 10,  
                 effect: "fadeIn"
           });  
      });
       var stop=true; 
	$(window).scroll(function(){ 
	   
	    if($(window).scrollTop() == $(document).height() - $(window).height()){ 
	    	if($(".no-data").is(":hidden")){
	        //if(stop==true){ 
	           // stop=false; 
	           
	           var limit=parseInt($("#limit").val());
	           $.ajax({
	           	type:"get",
	           	url:"test.json",
	           	data:{"p":limit},
	           	async:true,
	           	success:function(data){
	           		$("#loading").fadeOut(1000);
	           		//stop=true;
	           		var html="";
	           		if(data&&data.length){
		           		for(var i in data){
			           		html+='<div class="am-g  one-good-3">'+
							    '<a href="#">'+
							        '<div class="am-u-sm-4">'+
							            '<img src="'+data[i].img+'" />'+
							        '</div>'+
							       ' <div class="am-u-sm-8">'+
							            '<p class="g-name tl-ellipsis">'+data[i].good_name+'</p>'+
							           
							          
							               ' <p class="g-desc tl-ellipsis">'+data[i].desc+'</p>'+
							         
							            
							           ' <p class="g-price">'+data[i].price+'</p>'+
							        '</div>'+
							    '</a>'+
							'</div>';
						}
		           		$("#good-list").append(html);
		           		$("#limit").val(limit+1);
	           		}else{
	           			//没有数据了
	           			$(".no-data").show();
	           		}
	           	},
	           	beforeSend:function(){
	           		
	           		$("#loading").fadeIn(1000);
	           	},
	           	error:function(data){}
	           	
	           });
	        } 
	    } 
	});
</script>
</html>
