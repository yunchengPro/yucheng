<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>扫一扫关注我_</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="format-detection" content="telephone=no" />
		 {include file="Pub/assetcss" /}
		
		<style>
		
			.share-bg{
				background: url(<?=$publicDomain?>/mobile/img/bg.png) no-repeat;
				background-size:100% ;
				
			}
			.share-wrap{
			    background: url(<?=$publicDomain?>/mobile/img/sao1sao.png)no-repeat;
			    background-size: 100%;
			    position: fixed;
			    top: 50%;
			    left: 50%;
			    width: 300px;
			        min-height: 390px;
			        margin-top: -210px;
			  /* height: 60%;*/
			    margin-left: -150px;
			    border-radius: 1rem;
			}
			.share-wrap .erweima{
				    width: 65%;
				    position: absolute;
				    top: 20%;
				    margin-left: -32.5%;
				    left: 50%;

			}
			.niu-name{
				    text-align: center;
				    margin-top: 3rem;
				    color: #333;
				    font-weight: bold;
				    font-size: 1.6rem;
			}
		</style>
	</head>

	<body class="share-bg">
		<div class="share-wrap">
			<p class="niu-name"><?=$businessname?><br/>平台号:<?=$business_code?></p>
			<img src="<?php echo $img; ?>" class="erweima" />
			
		</div>
		
	</body>
	<!--<script src="js/jquery.min.js"></script>
	<script src="js/amazeui.min.js"></script>-->
	
</html>