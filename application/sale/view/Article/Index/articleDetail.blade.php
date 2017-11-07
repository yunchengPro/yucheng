{include file="Pub/header" /}
   	<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/information.css?v201709051016" />

    <header class="information-type page-header">
        
         <div class="page-bar">
        
            <a href="/Article/Index/articleList?newstype=1">
                <img src="/mobile/img/icon/back@2x.png" class="back-ico">
            </a>
            
            <div class="bar-title">资讯详情</div>
            
        </div>
    </header>
   	<section class="information-detail" style="margin-top: 40px;">
   		<div class="tl-ellipsis-2 main-title">
   			<?=$article['title']?>
   		</div>
   		<div class="date">
   			<span><?=$article['addtime']?></span>
   			<span> <?=$article['author']?></span>
   		</div>
      <div class="short-title">
        <?=$article['shorttitle']?>
      </div>
   		<div class="info-content">
   			<?=$article['content']?>
   		</div>
   	</section>
   	      <!--导航-->
        <nav class="nav-wrap">
            <div class="nav-item ">
                <a href="/">
                    <span class="mall"></span>
                    <p>商城</p>
                </a>
            </div>
            <div class="nav-item active">
                <a href="/Article/Index/articleList?newstype=1">
                    <span class="news"></span>
                    <p>资讯</p>
                </a>
            </div>
            <div class="nav-item">
                <a href="/user/shopcart/index">
                    <span class="cart"></span>
                    <p>购物车</p>
                </a>
            </div>
                
            <div class="nav-item ">
                <a href="/user/index/index">
                    <span class="niu"></span>
                    <p>我的</p>
                </a>
            </div>  
        </nav>
    <script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
	<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/layer.js" ></script>

   	
</body>


</html>
