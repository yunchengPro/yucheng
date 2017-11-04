<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2//mobile/css/amazeui.min.css"> -->
     {include file="Pub/assetcss" /}
     {include file="Pub/assetjs" /}
</head>

<body>
<div id='productView'>
	<div style='max-width:750px;margin:0 auto;font-size:14px;padding-right: 10px; padding-left: 10px;overflow: hidden;'>
		<?php //print_r($data["data"]['f_appdescription']); ?>	
		<?php echo $data['description']; ?>
	</div>
	
</div>
<script src="<?=$publicDomain?>/mobile/js/jquery.lazyload.js"></script>
<script>
    $(function(){
        $("img").lazyload({
            threshold : 200,
            effect: "fadeIn"
        });
       
    });
 
</script>
</body>

</html>
