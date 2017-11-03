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
	<style type="text/css">		
		.agreement{
			padding-bottom: 20px;
			padding-top: 20px;
		}	
		.topnav {
			position: relative;
			width: 100%;
			padding-top: 12px;
			padding-bottom: 12px;
			border-bottom: 1px solid #e5e5e5;
			text-indent: 20px;
		}	
		.topnav strong{
			font-weight: normal;
			line-height: 20px;
			
		}
		
		.topnav span{
			background: url('<?php echo $config["webview_url"];?>public/images/ic_arrow_right_agreement.svg') no-repeat;
			height: 22px;
			width: 22px;
			display: block;
			float:right;
			position: absolute;
			right: 0px;
			top: 5px;
			text-indent: -999px;
		}
		.hover span {
			background: url('<?php echo $config["webview_url"];?>public/images/ic_arrow_down_agreement.svg') no-repeat;
			height: 22px;
			width: 22px;
			display:block;
			position: absolute;
			right: 0px;
			top: 5;
			text-indent:-999px;
		}
	
	
		#dealView .agreement{
			background-color: #f8f9fb;
		}
		#dealView .agreement p {
			font-size: 13px;
			line-height: 30px;
			color: #858080;
			padding-left: 20px;
			padding-right: 20px;
			text-align: justify;
		}
	</style>
<div style='max-width:450px;margin:0 auto;font-size:14px;background-color:#FFF;'>
	<div class="wrap" >
		<?php if(!empty($brand)){ ?>
		<div class="topnav" id="link10" style="" >
			<strong style="">品牌：</strong><?=$brand;?>
		</div>
		<?php } ?>
		<div class="topnav" id="link10" style="" >
			<strong style="">上市时间：</strong><?=$addtime;?>
		</div>
		<?php foreach($specs  as $key=>$value){   ?>
			<div class="topnav" id="link10" style="" >
				<strong style=""><?=$value['spec_name'];?>：</strong>
				<?php foreach($value['value'] as $ka=>$va){ ?> 
					<?=$va['spec_value'];?> 
				<?php } ?>
			</div>
		<?php } ?>
		
	</div>
</div>


</body>

</html>
