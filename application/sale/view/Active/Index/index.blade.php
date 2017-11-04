<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>家居家纺</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    <style>
    	body {
            background: #EEEEEE;
        }
        .tl-ellipsis,/* 单行 */
.tl-ellipsis-2 { 
  display: -webkit-box;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
}
.tl-ellipsis-2{ /* 两行 */
  -webkit-line-clamp: 2;
}
        .topic-banner {
            margin-bottom: 10px;
        }
        
        .one-topic {
            margin-bottom: 9px;
            background: #FFFFFF;
        }
        
        .one-topic:last-child {
            margin-bottom: 0;
        }
        
        .topic-list {
            width: 100%;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            -ms-scroll-chaining: chained;
            -webkit-transform: translateZ(0);
            overflow: auto;
            background: #FFFFFF;
            font-size: 0;
            padding: 0 10px;
        }
        
        .one-good {
            display: inline-block;
            width: 40%;
            margin-right: 10px;
        }
        
        .one-good img,
        .one-good-2 img {
            width: 100%;
            margin-bottom: .5rem;
        }
        
        .one-good .g-price,
        .one-good-2 .g-price {
            margin-top: .5rem;
            color: #F13437;
            font-size: 1.4rem;
            margin: 0;
            text-align: center;
            margin-bottom: .2rem;
        }
        
        .one-good .g-name,
        .one-good-2 .g-name {
            color: #333;
            font-size: 1.2rem;
            margin: 0;
            text-align: center;
            margin-bottom: .8rem;
        }
        
        .topic-list-2 {
            padding: 0 .5rem;
        }
        
        .one-topic .am-u-sm-4 {
            padding: .4rem;
        }
         .one-topic .am-u-sm-4:last-child{
            float: left;
        }
        .one-good-3 {
            border-bottom: 1px solid #EEEEEE;
            padding: 1rem;
                position: relative;
        }
        
        .one-good-3 img {}
        
        .one-good-3 .g-price {
                color: #F13437;
            font-size: 1.4rem;
            margin: 0;
            /* margin-bottom: .2rem; */
            position: absolute;
            bottom: 1rem;
        }
        
        .one-good-3 .g-name {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 1rem;
              display: -webkit-box;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
        }
        
        .one-good-3 .g-desc {
            color: #999;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            margin-top: 0;
        }
        
        .one-good-3 .am-u-sm-8 {
                position: absolute;
            left: 33.333%;
            bottom: 0;
            top: 0;
            padding: 1rem;
        }
        
        .one-good-3 .one-topic .am-u-sm-4 {
            padding: 0 10px;
        }
        
        .one-topic .one-good-3 .am-u-sm-4 {
            padding: 0rem;
        }
    </style>
</head>

<body>
 <!--    <header data-am-widget="header" class="am-header am-header-default am-header-fixed">
        <div class="am-header-left am-header-nav">
            <a href="pro-detail.html">
                <i class="am-icon-angle-left am-icon-md gray"></i>
            </a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link">家居家纺</a>
        </h1>
    </header> -->
    
    <div class="topic-wrap">
    	<!--毛巾-->
    	<div class="am-g one-topic">
    		<img src="<?=$publicDomain?>/mobile/img/towel-banner.png"  class="topic-banner"/>
    		<div class="topic-list">
    			<div class="one-good">
    				<a href="<?=$domain?>?goodsid=3&urltype=1" onclick="window.goToApp.clickOnAndroid('1','3')">
    					<img src="<?=$publicDomain?>/mobile/img/towel-1.png"/>
    					<p class="g-price">139.00元</p>
    					<p class="g-name">通用洗澡大号毛巾</p>
    				</a>
    				
    			</div>
    			<div class=" one-good">
    				<a href="<?=$domain?>?goodsid=43&urltype=1" onclick="window.goToApp.clickOnAndroid('1','43')">
    					<img src="<?=$publicDomain?>/mobile/img/towel-2.png"/>
    					<p  class="g-price">26.00元</p>
    					<p class="g-name">新疆长毛绒洗脸方巾</p>
    				</a>
    				
    			</div>
    			<div class="one-good">
    				<a href="<?=$domain?>?goodsid=52&urltype=1" onclick="window.goToApp.clickOnAndroid('1','52')">
    					<img src="<?=$publicDomain?>/mobile/img/towel-2.png"/>
    					<p  class="g-price">26.00元</p>
    					<p class="g-name">纯棉方巾</p>
    				</a>
    				
    			</div>
    			<div class="one-good">
    				<a href="<?=$domain?>?goodsid=53&urltype=1" onclick="window.goToApp.clickOnAndroid('1','53')">
    					<img src="<?=$publicDomain?>/mobile/img/towel-1.png"/>
    					<p class="g-price">139.00元</p>
    					<p class="g-name">通用洗澡大号毛巾</p>
    				</a>
    				
    			</div>
    			<div class=" one-good">
    				<a href="<?=$domain?>?goodsid=57&urltype=1" onclick="window.goToApp.clickOnAndroid('1','57')">
    					<img src="<?=$publicDomain?>/mobile/img/towel-2.png"/>
    					<p  class="g-price">26.00元</p>
    					<p class="g-name">新疆长毛绒洗脸方巾</p>
    				</a>
    				
    			</div>
    			<div class="one-good">
    				<a href="<?=$domain?>?goodsid=60&urltype=1" onclick="window.goToApp.clickOnAndroid('1','60')">
    					<img src="<?=$publicDomain?>/mobile/img/towel-2.png"/>
    					<p  class="g-price">26.00元</p>
    					<p class="g-name">纯棉方巾</p>
    				</a>
    				
    			</div>
    			
    		</div>
    	</div>
    	<!--/end毛巾-->
    	
    	<!--被子-->
    	<div class=" one-topic">
    		<img src="<?=$publicDomain?>/mobile/img/quilt-banner-.png" class="topic-banner"/>
    		<div class="am-g topic-list-2">
                <a href="<?=$domain?>?goodsid=63&urltype=1" onclick="window.goToApp.clickOnAndroid('1','63')">
        			<div class="am-u-sm-4 one-good-2">
        			  	<img src="<?=$publicDomain?>/mobile/img/quilt-1.png" />
        			  	<p class="g-price">759.00元</p>
            					<p class="g-name">纯棉气孔纤维被</p>
        			</div>
                </a>  
                <a href="<?=$domain?>?goodsid=64&urltype=1" onclick="window.goToApp.clickOnAndroid('1','64')">
        			<div class="am-u-sm-4 one-good-2">
        			  	
        			  	<img src="<?=$publicDomain?>/mobile/img/quilt-2.png" />
        			  	<p class="g-price">690.00元</p>
            					<p class="g-name">婉悦冬厚被</p>
        			</div>
                </a>
                <a href="<?=$domain?>?goodsid=65&urltype=1" onclick="window.goToApp.clickOnAndroid('1','65')">
    			     <div class="am-u-sm-4 one-good-2">
    				  	<img src="<?=$publicDomain?>/mobile/img/quilt-3.png" />
    				  	<p class="g-price">99.00元</p>
    	    			<p class="g-name">全免夏被空调被</p>
    			     </div>
                </a>
			 
                <a href="<?=$domain?>?goodsid=67&urltype=1" onclick="window.goToApp.clickOnAndroid('1','67')">
                    <div class="am-u-sm-4 one-good-2">
        			  	<img src="<?=$publicDomain?>/mobile/img/quilt-1.png" />
        			  	<p class="g-price">759.00元</p>
            					<p class="g-name">纯棉气孔纤维被</p>
        			</div>
                </a>    
    			
                <a href="<?=$domain?>?goodsid=68&urltype=1" onclick="window.goToApp.clickOnAndroid('1','68')">
                    <div class="am-u-sm-4 one-good-2">
        			  	<img src="<?=$publicDomain?>/mobile/img/quilt-2.png" />
        			  	<p class="g-price">690.00元</p>
            					<p class="g-name">婉悦冬厚被</p>
        			</div>
                </a>

                <a href="<?=$domain?>?goodsid=69&urltype=1" onclick="window.goToApp.clickOnAndroid('1','69')">
    			    <div class="am-u-sm-4 one-good-2">
    				  	<img src="<?=$publicDomain?>/mobile/img/quilt-3.png" />
    				  	<p class="g-price">99.00元</p>
    	    			<p class="g-name">全免夏被空调被</p>
    			    </div>
                </a>
			    
                <a href="<?=$domain?>?goodsid=70&urltype=1" onclick="window.goToApp.clickOnAndroid('1','70')">
                    <div class="am-u-sm-4 one-good-2">
        			  	<img src="<?=$publicDomain?>/mobile/img/quilt-1.png" />
        			  	<p class="g-price">759.00元</p>
            			<p class="g-name">纯棉气孔纤维被</p>
        			</div>
                </a>
                <a href="<?=$domain?>?goodsid=87&urltype=1" onclick="window.goToApp.clickOnAndroid('1','71')">
    			    <div class="am-u-sm-4 one-good-2">
    			  	    <img src="<?=$publicDomain?>/mobile/img/quilt-2.png" />
    			  	    <p class="g-price">690.00元</p>
        			    <p class="g-name">婉悦冬厚被</p>
    			    </div>
                </a>

                <a href="<?=$domain?>?goodsid=88&urltype=1" onclick="window.goToApp.clickOnAndroid('1','88')">
    			    <div class="am-u-sm-4 one-good-2">
    				  	<img src="<?=$publicDomain?>/mobile/img/quilt-3.png" />
    				  	<p class="g-price">99.00元</p>
    	    			<p class="g-name">全免夏被空调被</p>
    			    </div>
                </a>
			</div>
    	</div>
    	<!--/end被子-->
    	
    	<!--四件套-->
    	<div class="one-topic">
    		<img src="<?=$publicDomain?>/mobile/img/fourset-banner.png" class="topic-banner"/>
    		<div class="topic-list-3">
    			<div class="am-g  one-good-3">
    				<a href="<?=$domain?>?goodsid=89&urltype=1" onclick="window.goToApp.clickOnAndroid('1','89')">
    					<div class="am-u-sm-4">
    						<img src="<?=$publicDomain?>/mobile/img/fourset-1.png" />
    					</div>
    					<div class="am-u-sm-8">
    						<p class="g-name">简约纯棉床上用品四件套纯棉床单被套笠被子被套三件套1.5m 1.8m</p>
	    					<p class="g-desc">纯棉保证 假一赔三 送运费险 包邮</p>
	    					<p class="g-price">176.00元</p>
    					</div>
    				</a>
    			</div>
    			<div class="am-g  one-good-3">
    				<a href="<?=$domain?>?goodsid=90&urltype=1" onclick="window.goToApp.clickOnAndroid('1','90')">
    					<div class="am-u-sm-4">
    						<img src="<?=$publicDomain?>/mobile/img/fourset-2.png" />
    					</div>
    					<div class="am-u-sm-8">
    						<p class="g-name">简约纯棉床上用品四件套纯棉床单被套笠被子被套三件套1.5m 1.8m</p>
	    					<p class="g-desc">纯棉保证 假一赔三 送运费险 包邮</p>
	    					<p class="g-price">176.00元</p>
    					</div>
    				</a>
    			</div>
    			<div class="am-g  one-good-3">
    				<a href="<?=$domain?>?goodsid=91&urltype=1" onclick="window.goToApp.clickOnAndroid('1','91')">
    					<div class="am-u-sm-4">
    						<img src="<?=$publicDomain?>/mobile/img/fourset-3.png" />
    					</div>
    					<div class="am-u-sm-8">
    						<p class="g-name">简约纯棉床上用品四件套纯棉床单被套笠被子被套三件套1.5m 1.8m</p>
	    					<p class="g-desc">纯棉保证 假一赔三 送运费险 包邮</p>
	    					<p class="g-price">176.00元</p>
    					</div>
    				</a>
    			</div>
    			
    			<div class="am-g  one-good-3">
    				<a href="<?=$domain?>?goodsid=92&urltype=1" onclick="window.goToApp.clickOnAndroid('1','92')">
    					<div class="am-u-sm-4">
    						<img src="<?=$publicDomain?>/mobile/img/fourset-1.png" />
    					</div>
    					<div class="am-u-sm-8">
    						<p class="g-name">简约纯棉床上用品四件套纯棉床单被套笠被子被套三件套1.5m 1.8m</p>
	    					<p class="g-desc">纯棉保证 假一赔三 送运费险 包邮</p>
	    					<p class="g-price">176.00元</p>
    					</div>
    				</a>
    			</div>
    			<div class="am-g  one-good-3">
    				<a href="<?=$domain?>?goodsid=93&urltype=1" onclick="window.goToApp.clickOnAndroid('1','93')">
    					<div class="am-u-sm-4">
    						<img src="<?=$publicDomain?>/mobile/img/fourset-2.png" />
    					</div>
    					<div class="am-u-sm-8">
    						<p class="g-name">简约纯棉床上用品四件套纯棉床单被套笠被子被套三件套1.5m 1.8m</p>
	    					<p class="g-desc">纯棉保证 假一赔三 送运费险 包邮</p>
	    					<p class="g-price">176.00元</p>
    					</div>
    				</a>
    			</div>
    			<div class="am-g  one-good-3">
    				<a href="<?=$domain?>?goodsid=94&urltype=1" onclick="window.goToApp.clickOnAndroid('1','94')">
    					<div class="am-u-sm-4">
    						<img src="<?=$publicDomain?>/mobile/img/fourset-3.png" />
    					</div>
    					<div class="am-u-sm-8">
    						<p class="g-name">简约纯棉床上用品四件套纯棉床单被套笠被子被套三件套1.5m 1.8m</p>
	    					<p class="g-desc">纯棉保证 假一赔三 送运费险 包邮</p>
	    					<p class="g-price">176.00元</p>
    					</div>
    				</a>
    			</div>
    		</div>
    	</div>
    	<!--四件套-->
    </div>
   
</body>
<script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
<script>
    $(function(){
       
    });
</script>
</html>
