<?php
	/**
	 * 通联支付配置文件
	 */
	return [
		"cusid_weixin"=>"342584053117023", // 微信-商户号 正式 342584053117023 测试 990581007426001
		"cusid_ali"=>"332584053118891", // 支付宝-商户号
		"cusid_quick"=>"009440353114121", // 快捷支付-商户号
		"cusid_servlet"=>"200584000014066", // 通联通代付-商户号 200584000014066

		// 收银宝平台appid
		"appid_weixin"=>"00011144", // 正式 00011144 测试 00000051
		"appid_ali"=>"00010806",
		"appid_quick"=>"",

		"key_weixin"=>"piqc9u4kc5930jmaqiu5yh8", // 正式 piqc9u4kc5930jmaqiu5yh8 测试 allinpay888
		"key_ali"=>"tkpb128kc1053jmakiu5yh7",
		"key_quick"=>"7654936119",

		"version"=>"", // 接口版本号
		
		"sub_appid"=>"wxc40515b32a4c77cb",/*微信app支付必填, 开发者在微信开放平台申请*/ //wx55ae6dfbf257c95a
		"sub_mchid"=>"342584053117023", /*微信app支付必填,由通联平台分配*/ //1449245302

		"weixin_pay_url"=>"https://vsp.allinpay.com/apiweb/weixin/pay", // 微信支付申请请求接口 -- 正式
		//"weixin_pay_url"=>"http://113.108.182.3:10080/apiweb/weixin", // 微信支付申请请求接口   -- 测试
		"weixin_notify_url"=>"http://www.niuniuhuiapp.net:15004/Payment/Noity/allinpayweixinAppNotifyUrl/", // 微信支付回调地址

		"ali_pay_url"=>"https://vsp.allinpay.com/apiweb/unitorder/pay", // 支付宝请求接口  
		"ali_notify_url"=>"http://www.niuniuhuiapp.net:15004/Payment/Noity/allinpayaliAppNotifyUrl/", // 支付宝支付回调地址
		
		"get_userid_url"=>"https://cashier.allinpay.com/usercenter/merchant/UserInfo/reg.do", // 快捷支付获取通联用户IDURL
		//"merchantId"=>"", // 商户号
		"quick_notify_url"=>"http://www.niuniuhuiapp.net:15004/Payment/Noity/allinpayquickAppNotifyUrl/", // 快捷支付支付回调地址
		"quick_web_notify_url"=>"http://www.niuniuhuiapp.net:15004/Payment/Noity/allinpayquickWebNotifyUrl/", // 快捷支付H5支付回调地址
		"quick_web_url"=>"https://cashier.allinpay.com/mobilepayment/mobile/SaveMchtOrderServlet.action", // 快捷支付h5页面支付 跳转的地址

		"ProcessServlet_username"=>"20058400001406604", // 系统对接用户
		"ProcessServlet_userpwd"=>"111111", // 系统对接用户密码
		"ProcessServlet_businesscode"=>"09900", // 业务代码
		"ProcessServlet_apiurl"=>"https://tlt.allinpay.com/aipg/ProcessServlet", //（生产环境地址，上线时打开该注释）
		//"ProcessServlet_apiurl"=>"http://113.108.182.3:8083/aipg/ProcessServlet", //通联系统对接请求地址（外网,商户测试时使用）
		"ProcessServlet_certFile"=>"D:\wamp\www\Allinpay\allinpay-pds.pem", //通联公钥证书
		//"ProcessServlet_certFile"=>"/home/Allinpay/allinpay-pds.pem", //通联公钥证书
		"ProcessServlet_privateKeyFile"=>"D:\wamp\www\Allinpay\\20058400001406604.pem",//商户私钥证书
		//"ProcessServlet_privateKeyFile"=>"/home/Allinpay/20058400001406604.pem", //通联公钥证书
		"ProcessServlet_password"=>"111111",//商户私钥密码以及用户密码


		// 下载对账单
		"bill_url"=>"https://vsp.allinpay.com/apiweb/trxfile/get",

		"productName"=>"交易订单支付-测试",
	];