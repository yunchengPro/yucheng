<?php
	return [
		"app_id"=>"2017101809370045",
		"method"=>"alipay.trade.app.pay",
		"format"=>"json",
		"charset"=>"utf-8",
		"sign_type"    => 'RSA2',
		"version"=>"1.0",
		"product_code"=>"QUICK_MSECURITY_PAY",
		//回调地址
		"notify_url"=>"http://www.niuniuhuiapp.net:82/Payment/Noity/alipayAppNotifyUrl",
		//回调地址--旧的参数
		"noityUrl"=>"http://www.niuniuhuiapp.net:82/Payment/Noity/alipayAppNotifyUrl",
		//页面跳转同步通知页面路径
		"return_url"=>"http://www.niuniuhuiapp.net:82/Payment/Noity/alipayAppReturnUrl",
		//合作身份者id，以2088开头的16位纯数字 也叫角色身份，在“服务市场-账户管理-合作伙伴管理”
		"partner"=>"2088721733801130",
		"seller_id"=>"227748657@qq.com",
		"service"=>"mobile.securitypay.pay", //接口名称，固定值
		//商户的私钥（后缀是.pen）文件相对路径
		//"private_key_path"=>'D:\wamp\www\alipay_certs_sale\rsa_private_key.pem',
		"private_key_path"=>'/data/alipay_certs_sale/rsa_private_key.pem', ///home/alipay_certs/rsa_private_key.pem
		//支付宝公钥（后缀是.pen）文件相对路径
		"ali_public_key_path"=>'/data/alipay_certs_sale/alipay_public_key.pem', ///home/alipay_certs/alipay_public_key.pem
		//"ali_public_key_path"=>'D:\wamp\www\alipay_certs_sale\alipay_public_key.pem',
		//签名方式 不需修改
		
		"input_charset"=>"utf-8",
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		"cacert"=>"",
		"transport"=>"http",

		// web端的支付宝回调
		"web_noityUrl"=>"http://www.niuniuhuiapp.net:92/Payment/Noity/aliWebNotifyUrl",

		"return_order_url"=>"http://www.niuniuhuiapp.net:83/StoBusiness/Index/paysuccess?orderno=",
		"return_becomeTarent_url"=>"http://www.niuniuhuiapp.net:83/Customer/Index/confirmSuccess?orderno=",
	];