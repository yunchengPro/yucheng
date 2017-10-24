<!DOCTYPE html>
<html style="height: 100%;">

<head>
	<meta charset="UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$title?>_店铺相册</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="format-detection" content="telephone=no" />
	 <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/baguettebox.min.css">
	 {include file="Pub/assetcss" /}
	
    {include file="Pub/assetjs" /}
    <script src="<?=$publicDomain?>/mobile/js/baguettebox.min.js"></script>
		<style>

		.album-wrap{
			padding: 9px 14px;
		}
		.album-wrap .am-u-sm-6{
			padding: 0;
			float: ;
		}
		.album-wrap .am-u-sm-6:last-child{
			float: left;
		}
		.album-wrap .one-store{
			margin-bottom: 9px;
		}
		.album-wrap  .am-u-sm-6:nth-child(even) .one-store{
			padding-left: 4.5px;
		}
		.album-wrap .am-u-sm-6:nth-child(odd) .one-store{
			
			padding-right: 4.5px;
		}
	</style>


	<script type="text/javascript">
        (function () {
             window.addEventListener("DOMContentLoaded",function(){
                 var img_with=$(".one-store img").width();
                $(".one-store img").css({
                	"height":0.625*img_with+'px'
                });
             },false);
        })();
        $(function(){
        	$(window).resize(function(){
        		var img_with=$(".one-store img").width();
                $(".one-store img").css({
                	"height":0.625*img_with+'px'
                });
        	});

        	 baguetteBox.run('.baguetteBox', {
	        	//buttons: true,
				animation: 'fadeIn'
				
			});
        });
    </script>
    
<style type="text/css">
    .am-slider .am-slides img {
    display: block;
      width: 100vw;
    height: 66.67vw;
}
.a_ic_store{
        position: absolute;
    bottom: 10px;
    z-index: 100;
    right: 10px
}
.ic_store{
    background:url(<?=$publicDomain?>/mobile/img/icon/ic_store_top_picture@2x.png) no-repeat;
    background-size: 100%;
    width: 45px;
    height: 45px;
}
.ic_store .ic_count{
       font-size: 12px;
    position: absolute;
    bottom: 2px;
    right: 10px;
    color: #dedbdb;
}
.page-header{
    background: #F9F9F9;
    border-bottom: 1px solid #CCCCCC;
}
.page-header .page-bar{
    text-align: center;
    padding: 10px;
    font-size: 14px;
    color: #333;
    position: relative;
}
.page-header .page-bar a{
    
}

.page-header .page-bar .back-ico{
    width: 10px;
    height: 17px;
    background: url(<?=$publicDomain?>/mobile/img/icon/back@2x.png) no-repeat;
    background-size:100% ;
    display: block;
    position: absolute;
}
.page-header .page-bar .bar-title{
    font-size: 16px;

    width: 100%;
    text-align: center;
}
</style>
</head>

<body>
 	<header class="page-header">    
        <div class="page-bar">  
            <a href="javascript:history.go(-1)">
                <span class="back-ico"></span>
            </a>
            <span class="bar-title"><?=$title?></span>
        </div>
    </header>
	
	<div class="album-wrap">
		<div class="am-g album-list baguetteBox">
			<?php foreach($albumList as $key=>$value){ ?>
			<div class="am-u-sm-6">
				
					<div class="one-store">
						<a href="<?=$value['thumb']?>"><img src="<?=$value['thumb']?>" /></a>
					</div>
				
			</div>
			<?php } ?>
			
		</div>
	</div>
	
	<!-- <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt"><div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer  am-g login-note" id="navbar">
	 <img src="<?=$publicDomain?>/mobile/img/icon/LOGO2.png" class="am-u-sm-2" />
        <div class="footer-des flex-column am-u-sm-6">
            <div>你买单，我送钱</div>
            <div>赶快下载<label class="red"></label>手机客户端</div>
        </div>
        <div class="am-u-sm-4 text-right">
            <span class="download">点击下载</span>
        </div>
	</div></a> -->
	
	
</body>

	<!-- <script src="http://cdn.amazeui.org/amazeui/2.7.2/<?=$publicDomain?>/mobile/js/amazeui.min.js"></script> -->

</html>