<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$version['title']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}

    <style>
    	.version-desc{padding: 10px;}
    	.version-desc .v-no{color: #000000;font-size: 15px;}
    	.version-desc .v-date{color: #999999;font-size: 12px;}
    	.version-desc .desc-cont{border-top: 1px solid #ddd;margin-top: 10px;padding-top: 10px;}
    	.desc-cont p{margin-bottom: 10px;}
    	.desc-cont img{width: 40%;}
    </style>
</head>

<body>
   
    <div class="version-desc">
    	<div class="v-no"><?=$version['title']?></div>
    	<div class="v-date"><?=$version['addtime']?></div>
    	<div class="desc-cont">
    		<?=$version['content']?>
    	</div>
    	
    </div>
    

</body>

</html>
