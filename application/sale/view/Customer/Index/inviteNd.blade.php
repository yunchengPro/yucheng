<!DOCTYPE html>
<html>
	<head>
		  <meta charset="UTF-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <title>邀请注册</title>
		    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		    <meta name="renderer" content="webkit">
		    <meta name="format-detection" content="telephone=no" />
		    <meta http-equiv="Cache-Control" content="no-siteapp" />
		    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
		    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
		    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
		    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/invite.css">
		    <script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
			<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
			<script src="<?=$publicDomain?>/mobile/js/layer.js"></script>
	</head>
	<body class="invite-ndr-bg">
		<div class=""></div>
		<div class="invite-wrap">
			<div class="invite-desc">
				<div>您受邀成为创业牛达人，</div>
				<div>开始创业；</div>
				<div class="mg-top">您每邀请一名朋友成为创业牛达人，</div>
				<div>可赚取牛票和牛粮，</div>
				<div>并可享创业牛达人得消费分享收益。</div>
			</div>
			<div class="invite-oper">
				<button type="button" class="go-invtite ndr-btn">马上接受邀请</button>
			</div>
			<div class="invite-rule">
				<div><strong>活动规则：</strong></div>
				<div>1、牛票在平台可提现可消费；</div>
				<div>2、牛粮在平台的联盟商家消费并获赠牛豆；</div>
				<div>3、享受分享的创业牛达人拓展实体店粉丝得全国联盟商家消费分润；</div>
				<div>4、每位牛达人最多可拓展60家实体店和60家工厂入驻平台；</div>
				<div>5、活动最终解释权归平台所有。</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	$(function(){
		$(".ndr-btn").click(function(){
			var url = "/Customer/Index/becomeTarent?role="+"<?php echo $inviteData['role']?>"+"&userid="+"<?php echo $inviteData['userid']?>"+"&checkcode="+"<?php echo $inviteData['checkcode']?>";
			window.location.href=url;
		});
	});
</script>