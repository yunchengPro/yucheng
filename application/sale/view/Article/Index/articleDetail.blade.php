<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$article['title']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/information.css?v201709051016" />
    	<style>
    	
    </style>
</head>

<body>
   

   	
   	<section class="information-detail">
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
   	
   	<script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
	<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/layer.js" ></script>

   	
</body>


</html>
