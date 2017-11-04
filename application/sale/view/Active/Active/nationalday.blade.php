<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>国庆嗨翻天</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    <style>
    	 body { background: #FFFFFF;}
		.activity-wrap .activity-banner img{width: 100%;}
    	.activity-wrap .activity-goods-list{
    		padding: 15px 8px 5px;
    		
    		background: #ff5551 url(<?=$publicDomain?>/mobile/img/icon/bian@@2x.png) no-repeat;
    		background-size:100% ;
    	}
    	.activity-wrap .activity-goods-list .one-good{
    		    display: -webkit-box;
			    width: 100%;
			    padding:3px 8px;
			    margin-bottom: 5px;
			    position: relative;
			    background: #fff;
			    border-radius: 4px;
    	}
    	.activity-wrap .activity-goods-list .one-good:last-child{
    		margin-bottom: 0;
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
		    margin-bottom: 4px;
		    word-break: break-all;
    	 }
    	 .one-good .good-info  .slogan{
    	 	color: #7e7ff6;
    	 	font-size: 12px;
    	 }
    	  .one-good .good-info .gift{
    	  	background: url(/mobile/img/icon/ic_zeng@2x.png) left center no-repeat;
    	  	background-size:14px 14px ;
    	  	padding-left: 20px;
    	  	font-size: 11px;
    	  	color: #ff4c2e;
    	  }
    	  .one-good .good-info .market-price{
    	  	   position: absolute;
			   bottom: 28px;
			   color: #999;
			   font-size: 10px;
    	  }
    	 .one-good .good-info .price{
    	 	    position: absolute;
    			bottom: 5px;
    
    	 }
    	 .one-good .good-info .price .num{font-size: 18px;font-weight: bold;}
    	 .one-good .good-info .price .yuan{font-size: 12px;font-weight: bold;}
    	 .one-good .good-info .price.mark .num{font-size: 13px;font-weight: bold;}
    	 .one-good .good-info .price.mark .yuan{font-size: 10px;font-weight: bold;}
	    .one-good .good-info .price del{
	    	font-size: 10px;
	    	color: #999999;
	    }
    	 .one-good .good-info .go-grab{
    	 	    background: url(<?=$publicDomain?>/mobile/img/icon/button_guoqing.png) no-repeat;
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
    		<img src="<?=$publicDomain?>/mobile/img/nationalday/banner.png" />
    		<!--<img src="<?=$publicDomain?>/mobile/img/icon/bian@@2x.png" />-->
    	</div>
    	<div class="activity-goods-list" id="good-list">
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6135&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6135')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6135.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">蓝旅 迷你便携 旅行户外书灯蓝旅 迷你便携 旅行户外书灯</div>
	    					<div class="gift tl-ellipsis">赠牛粮23.52  牛豆74.48</div>
	    					<div class="price">
	    						<span class="red num">98.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥239</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6114&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6114')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6114.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">蓝旅 旅行减压袜</div>
	    					<!--<div class="slogan tl-ellipsis">每天握住的不仅是包，更像牵着你的手</div>-->
	    					<!--<div class="market-price">市场价：<del>￥699</del></div>-->
	    					<div class="gift tl-ellipsis">赠牛粮45.12 牛豆142.88</div>
	    					<div class="price">
	    						<span class="red num">188.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥468</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6112&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6112')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6112.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">蓝旅 减压手腕</div>
	    				<div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div>
	    				
	    					<!--<div class="slogan tl-ellipsis">每天握住的不仅是包，更像牵着你的手</div>-->
	    					<div class="price">
	    						<span class="red num">78.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥468</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6108&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6108')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6108.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">蓝旅 舒适眼罩</div>
	    					<!--<div class="slogan tl-ellipsis">每分每秒我愿与你永不分离</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮16.32 牛豆51.68</div>
	    					<div class="price">
	    						<span class="red num">68.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1550</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6104&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6104')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6104.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">蓝旅 TA记忆棉颈枕</div>
	    					<!--<div class="slogan tl-ellipsis">告诉他，从此包容你的女人心</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮47.52 牛豆150.48</div>
	    					<div class="price">
	    						<span class="red num">198.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥888</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=8283&urltype=1" onclick="window.goToApp.clickOnAndroid('1','8283')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/8283.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">ODINK奥鼎康 颈肩按摩器（家车两用） A-K301B</div>
	    					<!--<div class="slogan tl-ellipsis">时间为我作证，爱你的初衷永不改变</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮80.00 牛豆148.00</div>
	    					<div class="price">
	    						<span class="red num">228.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥2500</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6658&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6658')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6658.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">公牛 全球通用转换插头 转换器L07U（带USB）</div>
	    					<!--<div class="slogan tl-ellipsis">有你的日子，希望你每一天都如花般绽放</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮44.80 牛豆83.20</div>
	    					<div class="price">
	    						<span class="red num">128.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥599</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7634&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7634')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7634.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">李宁 双人单层帐篷 户外露营休闲帐篷AQTJ046-1</div>
	    					<!--<div class="slogan tl-ellipsis">愿它分分秒秒替我</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮116.11 牛豆153.00</div>
	    					<div class="price">
	    						<span class="red num">269.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥298</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7632&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7632')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7632.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">李宁 野餐垫 户外手提折叠野餐垫AQTH084</div>
	    					<!--<div class="slogan tl-ellipsis">火机是烟头的希望，我愿做你幸福的起航</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮58.40 牛豆82.60</div>
	    					<div class="price">
	    						<span class="red num">141.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥299</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7629&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7629')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7629.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">李宁 便携式不锈钢烧烤炉AQTM016-1000</div>
	    					<!--<div class="slogan tl-ellipsis">让你以后的美丽成为我的责任吧</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮90.40 牛豆121.60</div>
	    					<div class="price">
	    						<span class="red num">212.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥139</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>	
    		</div>
    		
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6388&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6388')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6388.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">卓一生活（ZUEI） 乐途 折叠洗漱包ZY-XS100</div>
	    					<!--<div class="slogan tl-ellipsis">戴上它，从此你的世界只有我</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮54.00 牛豆102.00</div>
	    					<div class="price">
	    						<span class="red num">156.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥129</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6387&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6387')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6387.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">卓一生活（ZUEI）奔跑 运动臂包ZY-YD101</div>
	    					<!--<div class="slogan tl-ellipsis">心疼你的每一天</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮45.60 牛豆53.40</div>
	    					<div class="price">
	    						<span class="red num">99.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥298</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6351&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6351')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6351.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">卓一生活（ZUEI）便携桌子 椅子 快乐时光休闲五件套 ZY-XX105</div>
	    					<!--<div class="slogan tl-ellipsis">只愿时光驻留，锁定美丽的你</div>-->
	    					<!-- <div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div> -->
	    					<div class="market-price">市场价：<del>￥699</del></div>
	    					
	    					<div class="price mark">
	    						<span class="red num">240.00</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">459.00</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=6260&urltype=1" onclick="window.goToApp.clickOnAndroid('1','6260')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/6260.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">纵贯线 旅行家 商旅系列五件套SW-007</div>
	    					<!--<div class="slogan tl-ellipsis">一杯子，一辈子</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮28.80 牛豆79.20</div>
	    					<div class="price">
	    						<span class="red num">108.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥148.5</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=5266&urltype=1" onclick="window.goToApp.clickOnAndroid('1','5266')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/5266.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">酷龙达 户外登山杖CL-D02（发货颜色随机）</div>
	    					<!--<div class="slogan tl-ellipsis">我愿包容你的一切</div>-->
	    					<!-- <div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div> -->
	    					<div class="market-price">市场价：<del>￥129</del></div>
	    					<div class="price mark">
	    						<span class="red num">38.40</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">90.60</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<!-- <div class="price">
	    						<span class="red num">129.00</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> -->
	    					<span  class="go-grab">立即订购</span>
	    			</div>
    			</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=5251&urltype=1" onclick="window.goToApp.clickOnAndroid('1','5251')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/5251.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">酷龙达 户外休闲双人吊床CL-D002</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<!-- <div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div>
	    					<div class="price">
	    						<span class="red num">119.00</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> -->
	    					<div class="market-price">市场价：<del>￥119</del></div>
	    					<div class="price mark">
	    						<span class="red num">56.40</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">62.60</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=721&urltype=1" onclick="window.goToApp.clickOnAndroid('1','721')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/721.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">wissBlue/维仕蓝 超轻柔软亲肤睡袋TG-WA8019-B</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮54.40 牛豆133.60</div>
	    					<div class="price">
	    						<span class="red num">188.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=8857&urltype=1" onclick="window.goToApp.clickOnAndroid('1','8857')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/8857.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">笑咪咪 婚庆六件套十件套-报告我们结婚了</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮384.80 牛豆577.20</div>
	    					<div class="price">
	    						<span class="red num">962.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=8854&urltype=1" onclick="window.goToApp.clickOnAndroid('1','8854')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/8854.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">笑咪咪 婚庆十件套-华丽尊宠</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮710.40 牛豆1065.60</div>
	    					<div class="price">
	    						<span class="red num">1776.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9045&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9045')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/9045.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安绨缦 天丝贡缎提花四件套 200*230CM 4号</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<!-- <div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div>
	    					<div class="price">
	    						<span class="red num">1680.00</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> -->
	    					<div class="market-price">市场价：<del>￥1680</del></div>
	    					<div class="price mark">
	    						<span class="red num">241.92</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">1438.08</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10225&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10225')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/10225.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">博洋家纺 戴乐云享被200*230cm</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮84.00 牛豆231.00</div>
	    					<div class="price">
	    						<span class="red num">315.80</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> 

	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7452&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7452')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7452.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">吾之语 竹节棉蚕丝被</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮100.00 牛豆275.00</div>
	    					<div class="price">
	    						<span class="red num">375.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9102&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9102')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/9102.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">安绨缦 决明子枕芯 保健护颈枕头 2号</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<!-- <div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div>
	    					<div class="price">
	    						<span class="red num">298.00</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> -->
	    					<div class="market-price">市场价：<del>￥298</del></div>
	    					<div class="price mark">
	    						<span class="red num">42.91</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">255.09</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    					
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=10468&urltype=1" onclick="window.goToApp.clickOnAndroid('1','10468')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/10468.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">啄木鸟PLOVER 商务海关锁拉杆箱 万向轮旅行箱GD2582 20寸/24寸</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮62.80 牛豆172.70</div>
	    					<div class="price">
	    						<span class="red num">235.50</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7589&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7589')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7589.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">POLO商务登机箱20寸 时尚旅行拉杆箱IR-KE20</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<!-- <div class="price">
	    						<span class="red num">1175.00</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> -->
	    					<div class="market-price">市场价：<del>￥1175</del></div>
	    					<div class="price mark">
	    						<span class="red num">276.00</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">899.00</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7619&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7619')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7619.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">新百伦New Balance 2017时尚双肩背包TCH107-RD</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮162.40 牛豆206.60</div>
	    					<div class="price">
	    						<span class="red num">369.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=7647&urltype=1" onclick="window.goToApp.clickOnAndroid('1','7647')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/7647.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">李宁 探索者登山背包ABSJ456</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮278.40 牛豆300.60</div>
	    					<div class="price">
	    						<span class="red num">579.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=730&urltype=1" onclick="window.goToApp.clickOnAndroid('1','730')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/730.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">wissBlue/维仕蓝 户外徒步包曙光TG-WB1019</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<div class="gift tl-ellipsis">赠牛粮56.80 牛豆156.20</div>
	    					<div class="price">
	    						<span class="red num">213.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">立即订购</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9952&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9952')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/nationalday/9952.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">凤凰之翔 阳澄湖大闸蟹 多种套装</div>
	    					<!--<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>-->
	    					<!-- <div class="gift tl-ellipsis">赠牛粮18.72 牛豆59.28</div>
	    					<div class="price">
	    						<span class="red num">338.00</span>
	    						<span class="red yuan">元</span>
	    						
	    					</div> -->
	    					<div class="market-price">市场价：<del>￥338</del></div>
	    					<div class="price mark">
	    						<span class="red num">162.00</span>
	    						<span class="red yuan">元+</span>
	    						<span class="red num">176.00</span>
	    						<span class="red yuan">牛豆</span>
	    						<!--<del>￥329</del>-->
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
