<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title>{$title}</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp" />
	    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/<?=$publicDomain?>/mobile/css/amazeui.min.css"> -->
        <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/baguettebox.min.css">
        
	    {include file="Pub/assetcss" /}
	   
	    {include file="Pub/assetjs" /}
	    <script src="<?=$publicDomain?>/mobile/js/baguettebox.min.js"></script>
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
		<div class="store-comment-list">
			                <?php foreach($EvaluateList['list'] as $v){ ?>
                    <div class="store-comment">
                        <div class="am-g flex-center" style="align-items: initial;">
                            <div class="am-u-sm-2">
                                <?php if(empty($v['headpic'])){ ?>
                                    <img src="<?=$publicDomain?>/mobile/img/user.jpg" class="avator">
                                <?php }else{ ?>
                                    <img src="<?=$v['headpic']?>" class="avator">
                                <?php } ?>
                            </div>
                            <div class="am-u-sm-4">
                                <span> <?=$v['frommembername']?></span>
                                <div class="red">
                                   
                                    <?php $star = explode('.',$v['scores']); ?>

                                     <?php 
                                        for($i =1; $i <= 5; $i++) {
                                            if($star[0] >= $i) { ?>
                                                <!--  quan -->
                                                 <i class="icon icon-jude active"></i>
                                            <?php  } else {
                                                if($star[1] > 0) {?>
                                                  <!-- half -->
                                                    <i class="icon icon-jude half"></i>
                                               <?php  } else {?>
                                                     <i class="icon icon-jude gray"></i>
                                                 <?php }
                                            }
                                        }?>
                               
                                    <span class="gray-more"><?=$v['scores']?></span>
                                </div>
                            </div>
                            <div class="am-u-sm-6 gray-more text-right">
                                <?=$v['addtime']?>
                            </div>
                        </div>
                        <div class="am-g">
                            <div class="am-u-sm-2"></div>
                            <div class="am-u-sm-10">
                                {$v['content']}
                                <div class="baguetteBox">
                                    <?php foreach($v['img_arr'] as $value){ ?>
                                        <a href="<?=$value?>"><img src="<?=$value?>" style="width: 50px;height: 50px;" alt="sdasf"/></a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>  

		</div>

        <script>
            $(function(){
                 baguetteBox.run('.baguetteBox', {
                        //buttons: true,
                        captions:true,
                        animation: 'fadeIn'
                        
                    });
            });
        </script>
	</body>

</html>