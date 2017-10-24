<?php
	/**
	 * 微信公众号设置
	 */
	return [
		"appid"=>"wx953616e7fe9b7536",
		"appsecret"=>"3bb52c9714b07ba923392546e32f1475",
		"mchid"=>"1489839392",
		"mch_id"=>"1489839392", // 1489839392
		'api_key' => "sale2302234150cgz198212359552056",    
		
		//微信支付回调地址
		"notify_url"=>"http://1x67662h57.iask.in/Payment/Noity/weixinWebNotifyUrl/",

		//'apiclient_cert'=>"D:/Wnmp/html/weixin_certs/apiclient_cert.pem",
        'apiclient_cert'=>"/home/weixin_certs/apiclient_cert.pem",
        //'apiclient_key'=>"D:/Wnmp/html/weixin_certs/apiclient_key.pem",
        'apiclient_key'=>"/home/weixin_certs/apiclient_key.pem",

        'oauth_url'=>"http://1x67662h57.iask.in/", //微信授权的认证域名
	];