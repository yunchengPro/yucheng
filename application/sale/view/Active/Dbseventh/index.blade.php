<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>七夕活动</title>
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
    			bottom: 0;
    
    	 }
    	 .one-good .good-info .price .num{font-size: 18px;font-weight: bold;}
    	 .one-good .good-info .price .yuan{font-size: 12px;font-weight: bold;}
	    .one-good .good-info .price del{
	    	font-size: 10px;
	    	color: #999999;
	    }
    	 .one-good .good-info .go-grab{
    	 	    background: url(<?=$publicDomain?>/mobile/img/icon/button_qixi.png) no-repeat;
			    background-size: 100%;
			    padding: 4px 17px;
			    font-size: 12px;
			    border-radius: 2px;
			    position: absolute;
			    right: 0;
			    bottom: 3px;
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
    		<img src="<?=$publicDomain?>/mobile/img/icon/banner_qixi.png" />
    	</div>
    	<div class="activity-goods-list" id="good-list">
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9649&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9649')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/01.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">飞科 剃须刀全身水洗飞科电动剃须刀男士刮胡刀充电式胡须刀FS378</div>
	    					<div class="slogan tl-ellipsis">刮走的是岁月,留下的是青春</div>
	    					<div class="price">
	    						<span class="red num">193.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥239</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9653&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9653')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/02.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">菲安妮 手拿包FPLLPBRNCML15SH01 </div>
	    					<div class="slogan tl-ellipsis">每天握住的不仅是包，更像牵着你的手</div>
	    					<div class="price">
	    						<span class="red num">248.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥468</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9657&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9657')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/03.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">Folli Follie 女士手表希腊轻奢时尚商务牛皮镀玫瑰金石英腕表37mm 橙色 WF16R035SPS-ORANGE </div>
	    					<div class="slogan tl-ellipsis">每分每秒我愿与你永不分离</div>
	    					<div class="price">
	    						<span class="red num">605.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1550</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
    			</a>
    		</div>
    		
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9659&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9659')" >
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/04.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">外交官 纤薄手机钱包 DS-1289</div>
	    					<div class="slogan tl-ellipsis">告诉他，从此包容你的女人心</div>
	    					<div class="price">
	    						<span class="red num">303.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥888</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a href="/index/index/goodsdetail?goodsid=9662&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9662')" >
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/05.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">丹尼尔惠灵顿DW 多彩尼龙 情侣对表   石英手表</div>
	    					<div class="slogan tl-ellipsis">时间为我作证，爱你的初衷永不改变</div>
	    					<div class="price">
	    						<span class="red num">1309.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥2500</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9664&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9664')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/06.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">SULWHASOO/雪花秀滋盈水乳礼盒套装6件装 滋盈水/乳+精华+面霜+水乳</div>
	    					<div class="slogan tl-ellipsis">有你的日子，希望你每一天都如花般绽放</div>
	    					<div class="price">
	    						<span class="red num">308.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥599</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9767&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9767')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/07.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">韩国Sulwhasoo雪花秀 气垫BB霜CC霜防晒遮瑕霜 底妆 樱花蝴蝶限量版</div>
	    					<div class="slogan tl-ellipsis">愿它分分秒秒替我亲吻你</div>
	    					<div class="price">
	    						<span class="red num">141.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥298</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9671&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9671')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/08.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">芝宝(Zippo) 防风打火机 红心</div>
	    					<div class="slogan tl-ellipsis">火机是烟头的希望，我愿做你幸福的起航</div>
	    					<div class="price">
	    						<span class="red num">216.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥299</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9681&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9681')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/09.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">倍轻松黄金美容棒</div>
	    					<div class="slogan tl-ellipsis">让你以后的美丽成为我的责任吧</div>
	    					<div class="price">
	    						<span class="red num">95.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥139</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>	
    		</div>
    		
    		
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9684&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9684')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/10.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">I-Mu幻响 无线运动耳机跑步立体声蓝牙音乐耳机通用双耳塞入耳式</div>
	    					<div class="slogan tl-ellipsis">戴上它，从此你的世界只有我</div>
	    					<div class="price">
	    						<span class="red num">88.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥129</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9686&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9686')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/11.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">ODINK奥鼎康 捶捶乐按摩披肩A-K380A </div>
	    					<div class="slogan tl-ellipsis">心疼你的每一天</div>
	    					<div class="price">
	    						<span class="red num">141.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥298</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9688&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9688')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/12.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">ODINK奥鼎康 纳米离子蒸汽美容仪（出水芙蓉）A-K2331</div>
	    					<div class="slogan tl-ellipsis">只愿时光驻留，锁定美丽的你</div>
	    					<div class="price">
	    						<span class="red num">185.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥329</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
    			</a>
    		</div>
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9691&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9691')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/13.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">Hello Kitty 轻量真空保温杯KT-3721</div>
	    					<div class="slogan tl-ellipsis">一杯子，一辈子</div>
	    					<div class="price">
	    						<span class="red num">109.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥148.5</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
	    			</div>
	    		</a>
    		</div>
    		
    		<div class="one-good">
    			<a  href="/index/index/goodsdetail?goodsid=9694&urltype=1" onclick="window.goToApp.clickOnAndroid('1','9694')">
	    			<div class="good-img">
	    				<img src="<?=$publicDomain?>/mobile/img/default.png" data-original="<?=$publicDomain?>/mobile/img/qixi/15.png"/>
	    			</div>
	    			<div class="good-info">
	    				<div class="g-name tl-ellipsis-2">FION/菲安妮 手提/单肩/斜挎FAAFPLL068BRNP</div>
	    					<div class="slogan tl-ellipsis">听说"包"治百病，我愿做你生命中的扁鹊</div>
	    					<div class="price">
	    						<span class="red num">820.00</span>
	    						<span class="red yuan">元</span>
	    						<!--<del>￥1598</del>-->
	    					</div>
	    					<span  class="go-grab">马上抢</span>
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
    // var stop=true; 
	// $(window).scroll(function(){ 
	   
	//     if($(window).scrollTop() == $(document).height() - $(window).height()){ 
	//     	if($(".no-data").is(":hidden")){
	//         //if(stop==true){ 
	//            // stop=false; 
	           
	//            var limit=parseInt($("#limit").val());
	//            $.ajax({
	//            	type:"get",
	//            	url:"test.json",
	//            	data:{"p":limit},
	//            	async:true,
	//            	success:function(data){
	//            		$("#loading").fadeOut(1000);
	//            		//stop=true;
	//            		var html="";
	//            		if(data&&data.length){
	// 	           		for(var i in data){
	// 		           		html+='<div class="am-g  one-good-3">'+
	// 						    '<a href="#">'+
	// 						        '<div class="am-u-sm-4">'+
	// 						            '<img src="'+data[i].img+'" />'+
	// 						        '</div>'+
	// 						       ' <div class="am-u-sm-8">'+
	// 						            '<p class="g-name tl-ellipsis">'+data[i].good_name+'</p>'+
							           
							          
	// 						               ' <p class="g-desc tl-ellipsis">'+data[i].desc+'</p>'+
							         
							            
	// 						           ' <p class="g-price">'+data[i].price+'</p>'+
	// 						        '</div>'+
	// 						    '</a>'+
	// 						'</div>';
	// 					}
	// 	           		$("#good-list").append(html);
	// 	           		$("#limit").val(limit+1);
	//            		}else{
	//            			//没有数据了
	//            			$(".no-data").show();
	//            		}
	//            	},
	//            	beforeSend:function(){
	           		
	//            		$("#loading").fadeIn(1000);
	//            	},
	//            	error:function(data){}
	           	
	//            });
	//         } 
	//     } 
	// });
</script>
</html>
