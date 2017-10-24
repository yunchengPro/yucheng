<?php
	return [
			"user"=>[
				"name"=>"用户",
				"api"=>[
				    "user.login.send" =>[
				                "name" => "登录/注册 发送短信(已开发)",
				                "param"=>[
				                    ["mobile", "手机号", "string", "必填"],
				                    ["devicenumber", "设备唯一号", "string", "必填"],
				                    ["privatekey", "md5(手机号+私钥)", "string", "必填"],
				                ],
				                "info" => '
				                        code 200 --发送成功  2001 --发送失败 403 --手机号码不正确 404 --参数有误  405 --手机今天无法频繁使用验证码<br>',
				    ],
					"user.login.login"=>[
								"name"=>"用户登录/注册(已开发)",
								"param"=>[
// 										["checkcode","校验码-6位随机码","string","必填"],
// 										["code","认证码=md5(checkcode+phone+私钥)","string","必填"],
								        ["valicode","md5(校验码+私钥)","string","必填"],
								        ["devtype", "设备类型(安卓为A， ios为I)", "string", "必填"],
										["mobile","手机号","string","必填"],
								        ["parentid", "引荐人id", "int", "非必填"],
								        ["checkcode", "引荐人校验码", "string", "非必填"],
								        ["stocode", "平台号", "string", "非必填"],
								        ["devicetoken","设备token--用于消息推送","string","非必填"],
										["logintype","第三方登录方式:weixin,qq,weibo","string","非必填"],
										["openid","第三方openid","string","非必填"],
										["nickname","第三方昵称","string","非必填"],
										["headerpic","第三方头像","string","非必填"],
								        ["isonline", "是否线上(针对于ios 2.0.0以上 app store版本     1为线上 0为企业版)","int","非必填"]
									],
								"info"=>'
										code 200 --操作成功 1001--校验失败 404--参数有误 400--频繁操作 403 --验证已过期 20006 --手机验证码错误
					                    type   -- (0为不进行处理,1为分享提示失败页面,2为分享提示成功页面) <br>
										user_status ---login表示已注册用户 register 表示未注册用户进入注册步骤<br>
                        				mtoken ---登录token值<br>
					                    role -- 登录角色值<br>
                        				userinfo -- 用户基本信息 <br>
                        				crowdtype 所属人群 1-学生 2-白领 3-产后女性 4全职太太 city 所在城市 city_code城市邮编',
							],

// 					"user.login.signout"=>[
// 								"name"=>"退出登录(暂时关闭)",
// 								"param"=>[
// 										["mtoken","用户mtoken","string","必填"],
// 									],
// 								"info"=>'code 200 --操作成功 404 --参数有误 403 --退出失败',
// 							],
				    "user.index.index" => [
				                "name" => "会员中心首页 --有订单数据的页面(已开发)",
				                "param"=>[
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                    ["role", "角色值(1牛粉2牛人3牛创客4牛商5牛掌柜6孵化中心7运营中心8牛达人)", "string", "非必填(默认为1)"],
				                ],
// 				                "info" => 'code 200 --操作成功 104 --未登录或mtoken错误   ps* orderCount 为空时 所有订单数据为0<br>
// 				                            role            当前角色值 <br>
// 				                            applylist       申请列表<br>
// 				                            &nbsp;&nbsp;role    角色值<br>
// 				                            &nbsp;&nbsp;status  角色状态值(1为未申请， 2为已申请)<br>',
                                "info" => 'code 200 --操作成功 104 --未登录或mtoken错误 <br>
				                            userinfo                        用户信息<br>
				                            &nbsp;&nbsp;customer_code       平台号<br>
				                            &nbsp;&nbsp;nickname            昵称<br>
				                            &nbsp;&nbsp;realname            姓名<br>
				                            &nbsp;&nbsp;sex                 性别(1为男 2为女 0为未设置)<br>
				                            &nbsp;&nbsp;headerpic           头像图片地址<br>
				                            &nbsp;&nbsp;isnameauth          是否实名认证(1为是 0为否)<br>
				                            &nbsp;&nbsp;idnumber            身份证号码<br>
				                            &nbsp;&nbsp;logisticsDec        是否有收货地址(1为已设置， 0为未设置)<br>
				                            &nbsp;&nbsp;banknumber          银行卡数量<br>
				                            &nbsp;&nbsp;mobile              手机号码<br>
				                            &nbsp;&nbsp;payDec              支付设置(1为已设置，0为未设置)<br>
				                            &nbsp;&nbsp;role                用户角色值<br>
				                            &nbsp;&nbsp;rechargeStr         充值预留字段名称值<br>
				                            amountInfo                      用户余额信息<br>
				                            &nbsp;&nbsp;cashamount          现金<br>
				                            &nbsp;&nbsp;profitamount        收益现金<br>
				                            &nbsp;&nbsp;bullamount          牛豆数<br>
				                            profit                          昨日收益<br>
				                            &nbsp;&nbsp;cash                现金收益<br>
				                            &nbsp;&nbsp;profit              收益金收益<br>
				                            &nbsp;&nbsp;bull                牛豆收益<br>
				                            orderCount                      购物车订单数量<br>
				                            &nbsp;&nbsp;count_pay           待收货数量<br>
				                            &nbsp;&nbsp;count_deliver       待发货数量<br>
				                            &nbsp;&nbsp;count_receipt       待收货数量<br>
				                            &nbsp;&nbsp;count_evaluate      待评论数量<br>
				                            &nbsp;&nbsp;count_cart          购物车数量<br>
				                            &nbsp;&nbsp;count_return        退单数量<br>
				                            busOrderCount                   牛商订单信息<br>
				                            &nbsp;&nbsp;unpaySum            未付款订单<br>
				                            &nbsp;&nbsp;unfilledSum         待发货订单<br>
				                            &nbsp;&nbsp;confirmSum          已完成订单<br>
				                            &nbsp;&nbsp;returnSum           退款/售后订单<br>
				                            recoCash                        推荐收益<br>
				                            &nbsp;&nbsp;recoRole            推荐角色值<br>
				                            &nbsp;&nbsp;amount              推荐角色的收益<br>
				                            applylist                       申请列表<br>
				                            &nbsp;&nbsp;role                申请角色值<br>
				                            &nbsp;&nbsp;status              是否已经拥有该角色(1为无 2为有)<br>
				                            ----- version 1.0.4 ------      新版本<br>
				                            buskeepreco                     牛商是否能继续推荐(1为能 0为否)
				                            stokeepreco                     牛掌柜是否能继续推荐(1为能 0为否)
				                            stoOrderCount                   牛店订单数量<br>
				                            busOrderCount                   牛商订单数量<br>
				                            goodsCartCount                  购物车数量<br>
				                            sto                             牛掌柜方面数据<br>
				                            &nbsp;&nbsp;stoFlow             交易流水奖励<br>
				                            &nbsp;&nbsp;stoFlowShare        消费分享奖励<br>
				                            &nbsp;&nbsp;stoFlowCom          营业总额<br>
				                            &nbsp;&nbsp;storeCount          门店数<br>
				                            bus                             牛商方面数据<br>
				                            &nbsp;&nbsp;busCash             已收货款<br>
				                            &nbsp;&nbsp;futBusCash          待收货款<br>
				                            agent                           孵化中心/运营中心方面数据<br>
				                            &nbsp;&nbsp;stoCount            本地实体店个数<br>
				                            &nbsp;&nbsp;stoFlow             交易流水奖励<br>
				                            &nbsp;&nbsp;stoFlowShare        消费分享奖励<br>
				                            -------- version 2.0.0 --------------
				                            &nbsp;&nbsp;profitBounty        奖励金金额数<br>
				                            &nbsp;&nbsp;orCommunity         牛人社群 牛人数<br>',
				            ],
				    "user.index.Introduction" => [
				                "name" => "用户注册协议等H5文字介绍页面URL",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "非必填"],
				                ],
				                "info" => 'code 200 --操作成功<br/> 
			                            registDeal            注册协议<br>
			                            BusAccountIntro       企业账户介绍<br>
			                            bigMan                牛人介绍<br>
			                            niuChongKe            牛创客介绍<br>
			                            cattleBusiness        牛商介绍<br>
			                            cowShopkeepe          牛掌柜介绍<br>
			                            incubatorCentre       孵化中心介绍<br>
			                            operationCenter       运营中心介绍<br>'
				            ],
				    "user.index.myevaluate" => [
				                "name" => "评价管理列表",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                    ["type", "类型(1为商品，2为店铺)", "int", "必填"],
				                    ["page", "页数", "int", "非必填"],
				                ],
				            ],
				    "user.index.delevaluate" => [
				                "name" => "删除评价操作",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                    ["type", "类型(1为商品，2为店铺)", "int", "必填"],
				                    ["evaluate_id", "评价id值", "int", "必填"],
				                ],
				                "info" => "code 200 --操作成功 400 --操作失败 404 参数有误",
				            ],
				    "user.index.push" => [
				                "name" => "我的二维码",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                    ["recorole", "被分享人的角色值", "int", "非必填"]
				                ],
				                "info" => "code 200 -- 操作成功<br>
				                        urltype         跳转类型(34 分享牛人 35 分享牛达人 36 分享牛创客)<br>
				                        userid          二维码用户者",
				            ],
// 				    "user.index.myCode" => [
//         				        "name" => "我的二维码(需求未确定)",
//         				    ],
				    "user.user.index" => [
				                "name" => "我的资料页面(已开发)",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                ],
				                "info" => 'code 200 --操作成功 104 --未登录或mtoken错误<br>
				                            customer_code       平台号<br>
				                            nickname            昵称<br>
				                            realname            真实姓名<br>
				                            sex                 性别(1为男 2为女 0为未设置)<br>
				                            headerpic           头像地址<br>
				                            isnameauth          实名认证状态(1为已认证 0为未认证)<br>
				                            idnumber            身份证号码<br>
				                            banknumber          银行卡数量<br>
				                            logisticsDec        是否有收货地址(1为已设置， 0为未设置)<br>
				                            mobile              手机号码<br>
				                            payDec              支付设置(1为已设置，0为未设置)',
				            ],
				    "user.user.updateInfo" => [
				                "name" => "我的资料设置(已开发)",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                    ["nickname", "昵称", "string", "非必填"],
				                    ["sex", "性别(1为男 2为女)", "string", "非必填"],
				                    ["headerpic", "头像(先存放图片地址， 后续再出图片上传接口)", "string", "非必填"],
				                ],
				                "info" => 'code 200 --操作成功 104 -- 未登录或mtoken错误',
				            ],
// 				    "user.user.auth" => [
//         				        "name" => "实名认证(需求未确定)",
//         				    ],
				    "user.user.sendvalidate" => [
				        "name" => "发送短信验证码",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["mobile", "手机号码", "string", "必填"],
				            ["privatekey", "md5(手机号码+私钥)", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功 404 --参数有误 2001 --短信发送失败 20006 --手机号码不正确<br>
				                                                                                自行补上该参数sendType string 必填(reco_bus_ 牛商, reco_sto_ 牛掌柜, sto_store_ 牛掌柜添加子店,sto_stoper_ 牛掌柜添加员工)",
				    ],
					"user.user.send" => [
					           "name" => "修改手机的发送短信(已开发)",
					           "param" => [
					               ["mtoken", "用户mtoken", "string", "必填"],
					               ["mobile", "手机号码", "string", "必填"],
					               ["devicenumber", "设备唯一号", "string", "必填"],
					               ["privatekey", "md5(手机号+私钥)", "string", "必填"],
					           ],
					           "info" => 'code 200 --发送成功  2001 --发送失败 104 -- 未登录或mtoken错误 403 --手机号码不正确 404 --参数有误  405 --手机今天无法频繁使用验证码',
					       ],
				    "user.user.updatePhone" => [
        				        "name" => "修改手机操作(已开发)",
        				        "param" => [
        				            ["mtoken", "用户mtoken", "string", "必填"],
        				            ["mobile", "手机号码", "string", "必填"],
        				            ["valicode", "md5(校验码+私钥)", "string", "必填"],
        				        ],
        				        "info" => 'code 200 --发送成功  2001 --发送失败 104 -- 未登录或mtoken错误 403 --手机号码不正确 404 --参数有误  405 --手机今天无法频繁使用验证码',
        				    ],
				    "user.user.setPay" => [
				                "name" => "设置支付密码(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["paypwd", "md5(支付密码)", "string", "必填"],
				                ],
				        "info" => 'code 200 --发送成功   104 -- 未登录或mtoken错误 404 --参数有误  10002 --支付密码已设置，请勿重复请求',
				            ],
				    "user.user.auth" => [
				                "name" => "实名认证操作",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["realname", "真实姓名", "string", "必填"],
				                    ["idnumber", "身份证号码", "string", "必填"],
				                ],
				            ],
				    "user.user.banklist" => [
				                "name" => "银行卡列表(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                ],
				                "info" => "code 200 --操作成功<br>
				                            total 个数
				                            list 列表数据
				                            &nbsp;&nbsp;id              银行卡id值<br>
				                            &nbsp;&nbsp;bank_name       银行名<br>
				                            &nbsp;&nbsp;account_type    银行账号类型(1为个人 2为公司)<br>
				                            &nbsp;&nbsp;account_number  卡号",
				            ],
				    "user.user.checkBankNumber" => [
				                "name" => "根据银行卡号码识别对应银行",
				                "param" => [
				                    ["account_number", "银行卡卡号", "string", "必填"],
				                ],
				                "info" => "code 200 -- 操作成功<br>
				                            data        开户银行<br>"
				            ],
				    "user.user.addbank" => [
				                "name" => "添加银行卡操作(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["account_type", "账户类型(1为个人 2为公司)", "int", "必填"],
				                    ["account_name", "银行开户名", "string", "必填"],
				                    ["account_number", "银行帐号", "string", "必填"],
				                    ["bank_type_name", "开户银行名称", "string", "必填"],
				                    ["branch", "开户行支行", "string", "必填"],
				                    ["mobile", "手机号码", "string", "必填"],
				                ],
				                "info" => "code 200 --操作成功, 400 --操作失败, 1001 --无操作权限, 20006 --手机号码不正确, 20012 --银行卡号码不正确, 20013 --银行卡号已经被使用<br>",
				            ],
				    "user.user.unband" => [
				                "name" => "解绑银行卡操作(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["bank_id", "银行卡列表返回的id值", "int", "必填"],
				                ],
				                "info" => "code 200 --操作成功, 400 --操作失败, 1001 --无操作权限, 10003 --银行卡数据未存在",
				            ],
				    "user.user.shop" => [
				                "name" => "店铺资料",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["customerid", "查看的实体店用户id值", "int", "非必填"],
				                ],
				                "info" => "code 200 --操作成功  1001 --无操作权限<br>
				                            sto_name                店铺名称<br>
				                            idnumber                身份证<br>
				                            mobile                  注册手机号码<br>
				                            address                 营业地址<br>
				                            servicetel              服务电话<br>
				                            description             购买须知<br>
				                            discount                折扣数<br>
				                            delivery                配送费<br>
				                            dispatch                起送费<br>
				                            sto_type_id             分类id<br>
				                            categoryname            分类名称<br>
				                            area                    省市区<br>
				                            area_code               省市区编号<br>
				                            metro_id                地铁id<br>
				                            metro_name              地铁名称<br>
				                            district_id             商圈id<br>
				                            district_name           商圈名称<br>
				                            nearby_village          附近楼宇或小区名<br>
				                            sto_hour_begin          营业开始时间<br>
				                            sto_hour_end            营业结束时间<br>
				                            isparking               是否为免费停车(1为是 -1为否)<br>
				                            iswifi                  是否免费wifi(1为是 -1为否)<br>
				                            isdelivery              是否送货上门(1为是 -1为否)<br>
				                            licenceImage            营业执照<br>
				                            &nbsp;&nbspthumb        执照图片地址<br>
				                            idnumberImage           身份证<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            stoImage                轮播图图片<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            mainImage               主图<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            albumImage              相册图<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            businessid              店铺id<br>
				                            isvip                   是否vip(1是 -1否)<br>",
				            ],
				    "user.user.updateshop" => [
				                "name" => "修改店铺信息",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["customerid", "修改实体店用户id值", "int", "非必填"],
				                    ["sto_type_id", "商家分类id值","int", "非必填"],
				                    ["sto_name", "店铺名称", "string", "必填"],
				                    ["discount", "结算折扣", "string", "必填"],
				                    ["sto_hour_begin", "营业开始时间(09:00 传递为900)", "string", "必填"],
				                    ["sto_hour_end", "营业结束时间(24:00 传递为2400)", "string", "必填"],
				                    ["service_type", "商家服务(1为免费wifi，2为免费停车, 3为送货上门) 用,进行拼接 ", "string", "非必填"],
				                    ["delivery", "配送费", "int", "非必填"],
				                    ["dispatch", "起送费", "int", "非必填"],
				                    ["area", "所属省市区", "string", "非必填"],
				                    ["area_code", "所属地区编号", "int", "非必填"],
				                    ["address", "详细地址", "string", "非必填"],
				                    ["nearby_village", "附近楼宇或小区名", "string", "必填"],
				                    ["metro_id", "地铁id", "int", "非必填"],
				                    ["district_id", "商圈id", "int", "非必填"],
				                    ["sto_mobile", "商家服务电话(用,进行拼接)", "string", "必填"],
				                    ["description", "描述", "string", "必填"],
				                    ["main_image", "店铺主图", "string", "非必填"],
				                    ["sto_image", "店铺轮播图(用,进行拼接)", "string", "非必填"],
				                    ["album_image", "店铺相册图(用,进行连接)", "string", "非必填"],
				                ],
				                "info" => "code 200 --操作成功 1001 --无操作权限 3004 --折扣过高/过低 20007 --营业时间错误",
				            ],
				   	"user.logistics.logisticsList" => [
				                "name" => "收货地址列表(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"]
				                ],
				                "info" => 'code 200 --操作成功<br/>
								data 收货地址列表--<br/>
								&nbsp;&nbsp;address_id---收货地址id<br/>
								&nbsp;&nbsp;customerid---客户id<br/>
								&nbsp;&nbsp;mobile---收货人手机<br/>
								&nbsp;&nbsp;realname---收货人姓名<br/>
								&nbsp;&nbsp;city_id---收货人城市编码<br/>
								&nbsp;&nbsp;city---收货人城市名称<br/>
								&nbsp;&nbsp;address---收货人详细地址<br/>
								&nbsp;&nbsp;isdefault--是否为默认地址1是-1否<br/>
				                ',
				            ],
				   "user.logistics.setDefaultlogistic" => [
				                "name" => "设置默认收货地址(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                   	["logisticid", "收货地址列表返回的id", "int", "必填"]
				                ],
				                "info" => 'code 200 --操作成功<br/>
				                ',
				            ],
				    "user.logistics.addCustomerLogistic" => [
				                "name" => "添加默认收货地址(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                   	["mobile", "手机号码", "string", "必填"],
				                   	["realname", "收货人姓名", "string", "必填"],
				                   	["city_id", "所在地区编号", "string", "必填"],
				                   	["city", "所在地区名称", "string", "必填"],
				                   	["address", "收货详细地址", "string", "必填"],
				                   	["isdefault", "是否为默认收货地址", "int", "非必填-1否1是"],
				                ],
				                "info" => 'code 200 --操作成功<br/>
				                ',
				            ],
				    "user.logistics.updateCustomerLogistic" => [
				                "name" => "修改默认收货地址(已开发)",
				                "param" => [
				                	["logisticid", "需要修改的收货地址id", "int", "必填"],
				                    ["mtoken", "用户token", "string", "必填"],
				                   	["mobile", "手机号码", "string", "非必填"],
				                   	["realname", "收货人姓名", "string", "非必填"],
				                   	["city_id", "所在地区编号", "string", "非必填"],
				                   	["city", "所在地区名称", "string", "非必填"],
				                   	["address", "收货详细地址", "string", "非必填"],
				                   	["isdefault", "收货详细地址", "int", "非必填-1否1是"],
				                ],
				                "info" => 'code 200 --操作成功<br/>
				                ',
				            ],
				   	"user.logistics.delCustomerLogistic" => [
				                "name" => "删除收货地址(已开发)",
				                "param" => [
				                	["logisticid", "需要修改的收货地址id", "int", "必填"],
				                    ["mtoken", "用户token", "string", "必填"]
				                ],
				                "info" => 'code 200 --操作成功<br/>
				                ',
				            ],
				    "user.user.sendPay" => [
				                "name" => "修改支付密码的短信验证(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["devicenumber", "设备唯一号", "string", "必填"],
				                ],
				                "info" => "code 200 --操作成功 404 --参数有误 405 -- 设备今天发送信息已达上限 --2001 短信发送失败"
				            ],
				    "user.user.validPhonePay" => [
				                "name" => "校验支付手机验证码(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["valicode", "md5(校验码+私钥)", "string", "必填"],
				                ],
				                "info" => "code 200 --操作成功 403 --验证码过期 1001 --校验失败"
				            ],
				    "user.user.updatePayPwd" => [
				                "name" => "修改支付密码(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["paypwd", "md5(支付密码)", "string", "必填"],
				                ],
				                "info" => "code 200 --操作成功 400 --操作失败 404 --参数有误 10001 --不能与原密码一致 10003 请先设置支付密码"
				            ],
				    "user.role.applyinfo" => [
				                "name" => "角色申请页面(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["role", "角色值", "int", "必填"],
				                    ["parentid", "引荐人id值", "int", "非必填"],
				                ],
				                "info" => "code 200 --操作成功 404 --参数有误 1001 --无操作权限<br>
				                            isrole          是否有此角色值(1为有  跳转提示页  -1为无 跳转填写页)
				                            mobile          手机号码<br>
				                            idnumber        身份证号码<br>
				                            realname        姓名<br>
				                            role_money      需要缴费的费用<br>
				                            instroducerMobile 引荐者手机号码<br>
				                            company_phone   公司号码<br>
				                            goodsList       商品列表<br>
				                            returnbull      返回牛豆数<br>
				                            -----------v2.0.0 ---------<br>
				                            returnbonus     返回牛粮奖励金数<br>
				                            -----------v2.1.0 ----------<br>
				                            roleupdateauth  返回可否能进行修改分享人手机号码权限(0为否 1为可)<br>
				                            errormsg        返回提示内容<br>",
				            ],
				    "user.role.beforesendapply" => [
				                "name" => "发送申请操作 支付前 --牛人 牛创客牛达人的(已开发)",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["role_type", "角色类型值(2为牛人，3为牛创客，8为牛达人)", "int", "必填"],
				                    ["realname", "姓名", "string", "必填"],
				                    ["idnumber", "身份证号", "string", "必填"],
				                    ["mobile", "联系电话", "string", "必填"],
				                    ["area", "省市区", "string", "必填"],
				                    ["area_code", "市区编号", "string", "必填"],
				                    ["address", "详细地址", "string", "必填"],
				                    ["introducermobile", "引荐人手机号码", "string", "必填"],
				                    ["introducerrole", "引荐人角色值","int","非必填"],
// 				                    ["productid", "礼品id", "int", "必填"],
// 				                    ["logisticsName", "收货人姓名", "string", "必填"],
// 				                    ["logisticsMobile", "收货人电话", "string", "必填"],
// 				                    ["logisticsArea", "收货人省市区(直接拼接)", "string", "必填"],
// 				                    ["logisticsAreaCode", "地区编号", "string", "必填"],
// 				                    ["logisticsAddress", "详细地址", "string", "必填"],
				                ],
				                "info" => "code 200 --操作成功 400 --操作失败 404 --参数有误 1000 --引荐人记录不存在",
				            ],
				    "user.role.aftersendapply" => [
				        "name" => "发送申请操作 支付后 --牛人 牛创客的(已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["id", "前面的申请返回的id值", "int", "必填"]
				        ],
				        "info" => "code 200 --操作成功 400 --操作失败 404 --参数有误 1000 --引荐人记录不存在",
				    ],
				    "user.role.applyShare" => [
				        "name" => "分享申请角色",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["userid", "引荐人用户id值", "int", "必填"],
				            ["introRoleType", "引荐人角色值", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>
				                    type                标识字段(-1为跳转到填写页面 1为结果提示页面,2为牛粉绑定牛店成功页面)<br>",
				    ],
				    "user.role.recommendinfo" => [
				        "name" => "推荐请求操作页面(已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "int", "必填"],
				            ["recoRoleType", "推荐类型role", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功 404 -- 参数有误 1000 -- 用户角色错误 1001 --推荐范围有误 <br>
				                    money       加盟费<br>
				                    selfRoleType    用户自己role值<br>
				                    recoRoleType    用户推荐role值<br>
				                    goodsList       商品列表<br>
				                    returnbull      返回牛豆数<br>
				                    returncash      返回牛票数<br>
				                    returnprofit    返回牛粮数<br>
				                    --------------- v2.0.0 -------------- <br>
				                    returnbonus     返回牛粮奖励金数<br>",
				    ],
				    "user.role.recommendStepInfo" => [
				        "name" => "步骤推荐请求操作页面",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "int", "必填"],
				            ["recoRoleType", "推荐类型role", "int", "必填"],
				        ],
				        "info" => "code 200 -- 操作成功
				                    step            当前步骤<br>
				                    sto_type_id     店铺分类id<br>
				                    sto_name        店铺名称<br>
				                    mobile          注册手机号码<br>
				                    discount        折扣<br>
				                    sto_hour_begin  开始时间<br>
				                    sto_hour_end    结束时间<br>
				                    service_type    服务类型<br>
				                    dispatch        起送价<br>
				                    delivery        配送费<br>
				                    nearby_village  附近楼宇<br>
				                    sto_mobile      商家电话<br>
				                    area            商家所属地区<br>
				                    area_code       商家地区编号<br>
				                    address         商家详细地址<br>
				                    metro_id        地铁编号<br>
				                    district_id     商圈编号<br>
				                    main_image      店铺主图<br>
				                    sto_image       店铺轮播图<br>
				                    licence_image   营业执照<br>
				                    album_image     店铺相册<br>
				                    description     购买须知<br>
				        
				        
				                    -------------v2.2.0--------------
				                    deliveryDis     是否可以编辑配送费(1为否 0为可以)<br>",
				    ],
				    "user.role.afreshShare" => [
				        "name" => "一键分享牛店",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				        ],
				    ],
				    "user.role.beforesendreco" => [
				        "name" => "推荐提交操作(牛人 牛创客 牛达人已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role(2为牛人，3为牛创客，8为牛达人)", "string", "必填"],
				            ["realname", "姓名", "string", "必填"],
				            ["mobile", "手机号码", "string", "必填"],
				            ["pay_type", "支付类型(1为不代付，2为代付)", "int", "必填"],
				            ["area", "所属省市区", "string", "必填"],
				            ["area_code", "市区编号", "string", "必填"],
				            ["address", "详细地址", "string", "必填"],
				            ["productid", "礼品id", "int", "必填"],
				            ["logisticsName", "收货人姓名", "string", "必填"],
				            ["logisticsMobile", "收货人电话", "string", "必填"],
				            ["logisticsArea", "收货人省市区(直接拼接)", "string", "必填"],
				            ["logisticsAreaCode", "地区编号", "string", "必填"],
				            ["logisticsAddress", "详细地址", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功  404 -- 参数有误 400 --操作错误 1001 --无操作权限 20001 --用户角色已存在 20002 --你已推荐该用户 20006 --手机号码不正确<br>
				                    reco_id     推荐id(用户后续的支付功能)<br>",
				    ],
				    "user.role.aftersendreco" => [
				        "name" => "推荐提交操作 支付类型为代付操作(仅限牛人 牛创客 已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["reco_id", "推荐id值", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功  404 -- 参数有误 1001 --无操作权限 <br>",
				    ],
				    "user.role.sendrecobus" => [
				        "name" => "推荐提交操作(牛商已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["area", "所属省市区", "string", "必填"],
				            ["area_code", "市区编号", "string", "必填"],
				            ["company_name", "公司名称", "string", "必填"],
				            ["person_charge", "负责人姓名", "string", "必填"],
				            ["mobile", "联系电话", "string", "必填"],
				            ["valicode", "验证码", "string", "必填"],
				            ["corporation", "公司法人", "string", "必填"],
				            ["company_area", "公司详细地址", "string", "必填"],
				            ["price_type", "售价方式(1现金，2现金+牛豆/牛豆) 用,拼接 ", "string", "必填"],
				            ["idnumber", "身份证号码", "string", "必填"],
// 				            ["business_licence", "营业执照编号", "string", "必填"],
				            ["licence_image", "营业执照图片地址 用,拼接", "string", "必填"],
				            ["idnumber_image", "身份证图片地址 用,拼接 ", "string", "必填"],
				            ["company_logo", "公司logo", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功 404 -- 参数有误 400 --操作错误 1001 --无操作权限  20002 --你已推荐该用户 20003 --身份证号码错误 20004 --验证号码已过期 20005 --验证号码错误 20006 --手机号码不正确",
				    ],
				    "stobusiness.index.stocategory" => [
				        "name" => "牛掌柜分类",
				        "param" => [],
				        "info" => "code 200 --操作成功<br>
				                    categoryid              分类id<br>
				                    categoryname            分类名字<br>
				                    soncate                 下级信息<br>
				                    &nbsp;&nbsp;categoryid  分类id<br>
				                    &nbsp;&nbsp;categoryname分类名字<br>",
				    ],
				    "user.role.getsysinfo" => [
				        "name" => "牛掌柜获取地铁、商圈信息",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["area_code", "市区编号", "string", "必填"],
// 				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 <br>
				                    metro 地铁信息<br>
				                    &nbsp;&nbsp;id          地铁站id值<br>
				                    &nbsp;&nbsp;linename    地铁线路<br>
				                    &nbsp;&nbsp;metroname   地铁站<br>
				                    district 商圈信息<br>
				                    &nbsp;&nbsp;id              商圈id<br>
				                    &nbsp;&nbsp;district_name   商圈名称",
				    ],
				    
				    "user.role.sendrecosto" => [
				        "name" => "推荐提交操作(牛掌柜已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["area", "商家所在地区", "string", "必填"],
				            ["area_code", "市区编号", "string", "必填"],
				            ["sto_type_id", "类型", "int", "必填"],
				            ["sto_name", "商家名称", "string", "必填"],
				            ["mobile", "商家注册手机", "string", "必填"],
				            ["valicode", "验证码", "string", "必填"],
				            ["discount", "结算折扣", "string", "必填"],
				            ["sto_hour_begin", "营业开始时间(09:00 传递为09:00)", "string", "必填"],
				            ["sto_hour_end", "营业结束时间(24:00 传递为24:00)", "string", "必填"],
				            ["service_type", "商家服务(1为免费wifi，2为免费停车, 3为送货上门) 用,进行拼接 ", "string", "非必填"],
				            ["delivery", "配送金额", "string", "非必填"],
				            ["nearby_village", "附近楼宇或小区名", "string", "必填"],
				            ["sto_mobile", "商家服务电话(用,进行拼接)", "string", "必填"],
				            ["address", "详细地址", "string", "必填"],
				            ["metro_id", "地铁id", "int", "非必填"],
				            ["district_id", "商圈id", "int", "非必填"],
				            ["idnumber", "身份证号码", "string", "必填"],
				            ["main_image", "主图", "string", "必填"],
				            ["sto_image", "轮播图(用,进行拼接)", "string", "必填"],
				            ["licence_image", "营业执照图片(用,进行拼接)", "string", "必填"],
				            ["idnumber_image", "身份证图片(用,进行拼接)", "string", "必填"],
				            ["album_image", "相册图(用,进行连接)", "string", "非必填"],
				            ["description", "描述(购买须知)", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功 404 -- 参数有误 400 --操作错误 1001 --无操作权限  20002 --你已推荐该用户 20003 --身份证号码错误 20006 --手机号码不正确 20007 --营业时间错误"
				    ],
				    "user.role.sendrecostepsto" => [
				        "name" => "步骤提交分享操作(牛掌柜)",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["selfRoleType", "分享人role", "string", "必填"],
				            ["recoRoleType", "被分享人role", "string", "必填"],
				            ["step", "当前步骤", "int", "必填"],
				            ["submitAction", "提交操作(1 提交,2 返回)", "int", "必填"],
				            ["sto_type_id", "类型", "int", "非必填"],
				            ["sto_name", "商家名称", "string", "非必填"],
				            ["mobile", "注册手机", "string", "非必填"],
				            ["valicode", "验证码", "string", "非必填"],
				            ["discount", "结算折扣", "string", "非必填"],
				            ["sto_hour_begin", "营业开始时间(09:00 传递为09:00)", "string", "非必填"],
				            ["sto_hour_end", "营业结束时间(24:00 传递为24:00)", "string", "非必填"],
				            ["service_type", "商家服务(1为免费wifi,2为免费停车,3为送货上门)用,进行拼接", "string", "非必填"],
				            ["delivery", "配送费", "string", "非必填"],
				            ["dispatch", "起送金额", "string", "非必填"],
				            ["sto_mobile", "商家服务电话(用,进行拼接)", "string", "非必填"],
				            ["description", "描述(购买须知)", "string", "非必填"],
				            ["area", "所属地区", "string", "非必填"],
				            ["area_code", "地区编号", "string", "非必填"],
				            ["address", "详细地址", "string", "非必填"],
				            ["nearby_village", "附近楼宇或小区名", "string", "非必填"],
				            ["district_id", "商圈id", "int", "非必填"],
				            ["metro_id", "地铁id", "int", "非必填"],
				            ["main_image", "主图", "string", "非必填"],
				            ["sto_image", "店铺轮播图(用,进行拼接)", "string", "非必填"],
				            ["album_image", "店铺相册图(用,进行连接)", "string", "非必填"],
				            ["licence_image", "营业执照(用,进行拼接)", "string", "非必填"],
				            ["idnumber_image", "法人身份证图片(用,进行拼接)", "string", "非必填"],
				            ["businesscode", "平台号(暂时限定最大8位数)", "int", "非必填"],
				        ],
				        "info" => "code 200 <br>
				                    ------------------- v2.0.0 -------------<br>
				                    (* 当执行到第三步提交后才返回以下3个数据) <br>
				                    returncash          返回牛票数<br>
				                    returnbonus         返回牛粮奖励金数<br>
				                    returnbull          返回牛豆数<br>",
				    ],
				    "user.role.agentFindCode" => [
				        "name" => "校验加盟区市",
				        "param" => [
				            ["join_code", "加盟区市编号(假如是运营中心  需要传递城市编号)", "string", "必填"],
				            ["agent_type","代理类型(运营传递为1)", "int", "非必填"],
				        ],
				        "info" => "status   是否可以加盟(1 为是 -1为否)"
				    ],
				    "user.role.sendrecocounty" => [
				        "name" => "推荐提交操作(孵化中心)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["type", "所属类型(1为个人 2为公司)", "int", "必填"],
				            ["realname", "姓名", "string", "非必填(类型为1时 必填)"],
				            ["mobile", "手机号码", "string", "非必填(类型为1时 必填)"],
				            ["area", "所属省市区", "string", "必填"],
				            ["area_code", "市区编号", "string", "必填"],
				            ["address", "详细地址", "string", "必填"],
				            ["join_area", "加盟区县(用,拼接)", "string", "必填"],
				            ["join_code", "加盟市区编号(用,拼接)", "string", "必填"],
				            ["company_name", "公司名称", "string", "非必填(类型为2时 必填)"],
				            ["charge_idnumber", "负责人身份证 ", "string", "非必填(类型为2时 必填)"],
				            ["charge_name", "负责人姓名", "string", "非必填(类型为2时 必填)"],
				            ["charge_mobile", "负责人手机号码 ", "string", "非必填(类型为2时 必填)"],
				            ["corporation_name", "法人姓名", "string", "非必填(类型为2时 必填)"],
				            ["corporation_idnumber", "法人身份证", "string", "非必填(类型为2时 必填)"],
// 				            ["business_licence", "营业执照编号", "string", "非必填(类型为2时 必填)"],
				            ["licence_image", "营业执照图片(,拼接)", "string", "非必填(类型为2时 必填)"],
				            ["corporation_image", "法人身份证图片(,拼接)", "string", "非必填(类型为2时 必填)"],
				        ],
				        "info" => "code 200 --操作成功 400 - 操作有误 404 --参数有误 10002 --加盟市区已存在 20006 --手机号码不正确 20009 --负责人身份证错误 20008 --代理已经存在 20010 --法人身份证错误",
				    ],
				    "user.role.sendrecocity" => [
				        "name" => "推荐提交操作(运营中心)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["type", "所属类型(1为个人 2为公司)", "int", "必填"],
				            ["realname", "姓名", "string", "非必填(类型为1时 必填)"],
				            ["mobile", "手机号码", "string", "非必填(类型为1时 必填)"],
				            ["area", "所属省市区", "string", "必填"],
				            ["area_code", "市区编号", "string", "必填"],
				            ["address", "详细地址", "string", "必填"],
				            ["join_area", "加盟区县(用,拼接)", "string", "必填"],
				            ["join_code", "加盟市区编号(用,拼接)", "string", "必填"],
				            ["company_name", "公司名称", "string", "非必填(类型为2时 必填)"],
				            ["charge_idnumber", "负责人身份证 ", "string", "非必填(类型为2时 必填)"],
				            ["charge_name", "负责人姓名", "string", "非必填(类型为2时 必填)"],
				            ["charge_mobile", "负责人手机号码 ", "string", "非必填(类型为2时 必填)"],
				            ["corporation_name", "法人姓名", "string", "非必填(类型为2时 必填)"],
				            ["corporation_idnumber", "法人身份证", "string", "非必填(类型为2时 必填)"],
// 				            ["business_licence", "营业执照编号", "string", "非必填(类型为2时 必填)"],
				            ["licence_image", "营业执照图片(,拼接)", "string", "非必填(类型为2时 必填)"],
				            ["corporation_image", "法人身份证图片(,拼接)", "string", "非必填(类型为2时 必填)"],
				        ],
				        "info" => "code 200 --操作成功 400 - 操作有误 404 --参数有误 10002 --加盟市区已存在 20006 --手机号码不正确 20009 --负责人身份证错误 20008 --代理已经存在 20010 --法人身份证错误",
				    ],
				    "user.role.orderlist" => [
				        "name"=>"订单列表(已开发)",
				        "param"=>[
				            ["mtoken","用户mtoken","string","必填"],
				            ["orderlisttype","订单列表类型1未付款2待发货3已完成","int","必填"],
				            ["page","第几页：1,2,3默认第1页","int","非必填"],
				        ],
				        "info"=>"
									返回 orderlist 二维数组<br>
									返回参数的说明：<br>
									`actualfreight`  '实际运费',<br>
									`productcount`  '商品总数量',<br>
									`productamount`  '商品总金额',<br>
									`bullamount`  '商品总牛币数',<br>
									`totalamount`  '订单总金额=实际运费+商品总金额',<br>
									`addtime`  '订单添加时间',<br>
									 evaluate-是否已评价<br>
									 return_status 退款状态，0无退款1退款中2退款完成<br>
									`orderstatus` '订单状态0待付款1已付款待发货2已发货3确认收货4订单完结5取消',<br>
									-----------订单状态--------<br>
									0待付款---待收款<br>
									1已付款待发货---已付款<br>
									2已发货---已发货<br>
									3已确认确认收货 --已发货<br>
									  evaluate-是否已评价(1为已评价, -1为未)<br>
									  return_status 退款状态，0无退款1退款中2退款完成<br>
									4订单完结 --已完结<br>
									5取消', ---订单关闭<br>
									-----------订单状态--------<br>
									-----------订单操作说明--------<br>
									orderact 订单操作按钮字段<br>
									orderact:act说明：1表示操作按钮2显示文字<br>
									orderact:actname:按钮值<br>
									orderact:acttype说明：<br>
									1 付款<br>
									2 取消订单<br>
									3 提醒商家发货<br>
									4 退款--申请退款<br>
									5 订单详情-取消退款<br>
									6 延长收货<br>
									7 查看物流<br>
									8 确认收货<br>
									9 评价<br>
									10 售后<br>
									11 删除订单<br>
				                    12 退款详情-取消退款<br>
				                    13 退款详情-修改申请<br>
				                    14 退款详情-撤销申请<br>
				                    15 订单详情-退款中<br>
									16 订单详情-已退款<br>
									17 填写物流单号<br>
									18 退单列表-查看物流(和7重复了，去除)<br>
									19 提交物流<br>
									------------------------------<br>
									`businessid`  '商店ID',<br>
									`businessname` '商店名称',<br>
									<br>
									orderitem 商品明细 二维数组<br>
									productid 商品ID<br>
									productname 商品名称<br>
									productnum 商品数量<br>
									thumb 商品图片<br>
									prouctprice 商品价格<br>
									bullamount 商品牛币数<br>
								",
				    ],
				    "user.amount.mybalance" => [
				        "name" => "我的余额",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>
				                    amountInfo                  余额数<br>
				                    &nbsp;&nbsp;cashamount      现金余额<br>
				                    &nbsp;&nbsp;profitamount    收益现金余额<br>
				                    &nbsp;&nbsp;bullamount      牛豆余额<br>
				                    futList                     待返收益<br>
				                    &nbsp;&nbsp;cash            待返现金<br>
				                    &nbsp;&nbsp;profit          待返收益<br>
				                    &nbsp;&nbsp;bull            待返牛豆<br>
				                    busCash                     营业总额<br>
				                    futBusCash                  待返营业总额<br>
				                    bankNumber                  银行卡个数",
				    ],
				    "user.amount.cash" => [
				        "name" => "余额详情页",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["type", "访问类型(1为现金 2为收益现金 3为牛豆)", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>
				                    amount          余额数<br>
				                    comamount       公司余额数(role 为 4, 5, 6, 7才显示)<br>
				                    yesamount       昨日分润金额<br>
				                    monthamount     本月分润现金<br>
				                    totalamount     累计分润现金<br>
				                    rechargeUrl     充值跳转页面<br>",
				    ],
				    "user.amount.flowcus" => [
				        "name" => "交易明细",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["type", "类型(1为现金 2为收益现金 3为牛豆)", "int", "必填"],
				            ["screen", "筛选(1为收入, 2为支出, 3为充值体现)", "int", "非必填"],
				            ["role", "用户角色", "int", "非必填(推荐列表中的用户 需要必须填写)"],
				            ["begintime", "开始时间(Y-m-d)", "string", "非必填"],
				            ["endtime", "结束时间(Y-m-d)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 404 --参数有误 20007 --时间有误<br>
				                        datetime        年月份<br>
				                        revenueAmount   收入<br>
				                        expenseAmount   支出<br>
                                            id                  <br>
                                            flowtype            流水类型(预留字段)<br>
                                            direction           (1为收入 + 2为支出 -)<br>
                                            amount              金额<br>
                                            flowtime            流水时间<br>
                                            flowkey             圈圈文字<br>
                                            flowname            流水文字<br>
                                            datetime            时间<br>"
				    ],
				    "user.amount.getstoflow" => [
				        "name" => "交易流水奖励",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["selfRoleType", "当前用户角色值", "int", "必填"],
				            ["type", "类型(1为牛票,3为牛豆)","int", "必填"],
				            ["stoflowtype", "实体店交易类型(1为流水，2为分享  默认为1)", "int", "非必填"],
				            ["customerid", "(实体店用户id值)", "int", "非必填"],
				            ["begintime", "开始时间(Y年m月)", "string", "非必填"],
				            ["endtime", "结束时间(Y年m月)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				    ],
// 				    "user.amount.getstoflowshare" => [
// 				        "name" => "消费分享奖励",
// 				        "param" => [
// 				            ["mtoken", "用户mtoken", "string", "必填"],
// // 				            ["customerid", "(实体店用户id值)", "int", "非必填"],
// 				            ["begintime", "开始时间(Y年m月)", "string", "非必填"],
// 				            ["endtime", "结束时间(Y年m月)", "string", "非必填"],
// 				            ["page", "页数", "int", "非必填"],
// 				        ],
// 				    ],
				    "user.amount.flowcom" => [
				        "name" => "企业账户现金流水",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["begintime", "开始时间(Y-m-d)", "string", "非必填"],
				            ["endtime", "结束时间(Y-m-d)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 404 --参数有误 20007 --时间有误<br>
                                            id                  <br>
                                            flowtype            流水类型(预留字段)<br>
                                            direction           (1为收入 + 2为支出 -)<br>
                                            amount              金额<br>
                                            flowtime            流水时间<br>
                                            flowid            流水文字<br>"
				    ],
				    "user.amount.flowbusincome" => [
				        "name" => "营业收入",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["type", "类型(1为现金 3为牛豆)", "int", "必填"],
				            ["incometype", "营业类型(1为营业收入类型,2为待返营业收入类型)", "int", "非必填"],
				            ["customerid", "用户id值", "int", "非必填"],
				            ["begintime", "开始时间(Y-m-d)", "string", "非必填"],
				            ["endtime", "结束时间(Y-m-d)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				    ],
				    "user.amount.flowfutcus" => [
				        "name" => "待返明细",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["type", "类型(1为现金 2为收益现金 3为牛豆)", "int", "必填"],
				            ["screen", "筛选(1为支出, 2为收入, 3为充值体现)", "int", "非必填"],
				            ["begintime", "开始时间(Y-m-d)", "string", "非必填"],
				            ["endtime", "结束时间(Y-m-d)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 404 --参数有误 20007 --时间有误<br>
				                                    datetime        年月份<br>
                                                    id                  <br>
                                                    flowtype            流水类型(预留字段)<br>
                                                    direction           (1为收入 + 2为支出 -)<br>
                                                    amount              金额<br>
                                                    flowtime            流水时间<br>
                                                    flowkey             圈圈文字<br>
                                                    flowname            流水文字<br>
                                                    datetime            时间<br>"
				    ],
				    "user.amount.flowrecocus" => [
				        "name" => "推荐收益",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["status", "状态(1为待审核 2为审核通过 3为审核失败)", "int", "必填"],
				            ["page", "分页", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 1001 --无操作权限 20102 --角色范围不正确<br>
				                    &nbsp;&nbsp;id          用户id<br>
				                    &nbsp;&nbsp;parentid    用户上级id(预留字段)<br>
				                    &nbsp;&nbsp;grandpaid   用户上上级id(预留字段)<br>
				                    &nbsp;&nbsp;level       用户分润等级(预留字段)<br>
				                    &nbsp;&nbsp;flowName    角色流水类型名称<br>
				                    &nbsp;&nbsp;mobile      手机号码<br>
				                    &nbsp;&nbsp;nickname    昵称名称<br>
				                    &nbsp;&nbsp;thumb       头像<br>
				                    &nbsp;&nbsp;addtime     时间<br>
				                    &nbsp;&nbsp;total_amount    总分润金额<br>
				                    &nbsp;&nbsp;remark      审核失败描述<br>
				                    &nbsp;&nbsp;role        用户角色值<br>"
// 				                    examsuccess             审核成功个数<br>
// 				                    examwait                待审核个数<br>
// 				                    examfail                审核失败个数<br>
// 				                    amount                  用户总分润金额<br>
// 				                    todayamount             用户今日分润金额<br>
// 				                    yesamount               用户昨日分润金额<br>
// 				                    monthamount             用户本月分润金额<br>"
				    ],
				    "user.amount.flowrecocuspublic" => [
				        "name" => "推荐收益头部公共部分信息",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐类型role", "string", "必填"],
				            ["entrance", "入口(1为分享入口，2为牛店分享入口)", "int", "非必填(默认为1)"],
				            ["page", "分页", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 1001 --无操作权限 20102 --角色范围不正确<br>
				                    examsuccess             审核成功个数<br>
				                    examwait                待审核个数<br>
				                    examfail                审核失败个数<br>
				                    amount                  用户总分润金额<br>
				                    todayamount             用户今日分润金额<br>
				                    yesamount               用户昨日分润金额<br>
				                    monthamount             用户本月分润金额<br>
				                    busCount                牛商个数<br>
				                    stoCount                牛店个数<br>"
				    ],
				    "user.amount.flowrecocuscash" => [
				        "name" => "推荐用户的交易明细",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "当前用户角色值", "int", "必填"],
				            ["customerid", "推荐用户id", "int", "必填"],
				            ["role", "用户角色", "int", "必填"],
				            ["type", "类型(1为牛掌柜/为消费分享奖励，2为牛商/为流水奖励)", "int", "非必填(默认为1)"],
				            ["begintime", "开始时间(Y-m-d)", "string", "非必填"],
				            ["endtime", "结束时间(Y-m-d)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "code 200 --操作成功 404 --参数有误 20007 --时间有误<br>
				                            datetime        年月份<br>
				                            revenueAmount   收入<br>
                                            id                  <br>
                                            flowtype            流水类型(预留字段)<br>
                                            direction           (1为收入 + 2为支出 -)<br>
                                            amount              金额<br>
                                            flowtime            流水时间<br>
                                            flowkey             圈圈文字<br>
                                            flowname            流水文字<br>
                                            datetime            时间<br>
				                            amount              角色总分润(推荐那边的)<br>
				                            todayamount         角色今天分润(推荐那边的)<br>
				                            yesamount           角色昨日分润(推荐那边的)<br>
				                            monthamount         角色本月分润(推荐那边的)<br>",
				    ],
				    "user.amount.flowrecocuscashpublic" => [
				        "name" => "推荐用户的交易明细",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户role", "string", "必填"],
				            ["recoRoleType", "推荐role", "string", "必填"],
				            ["customerid", "推荐用户id", "int", "必填"],
				            ["type","类型(1为消费分享奖励,2为流水奖励)", "int", "非必填(默认为1)"]
				        ],
				        "info" => "code 200 -- 操作成功",
				    ],
				    "user.withdrawals.index" => [
				        "name" => "提现申请页面",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				        ],
				        "info" => "
				            code 200 --操作成功<br>
                            defaultBank 默认银行卡信息<br>
                            &nbsp;&nbsp;id              银行卡id值<br>
                            &nbsp;&nbsp;bank_name       银行名<br>
                            &nbsp;&nbsp;account_type    银行账号类型(1为个人 2为公司)<br>
                            &nbsp;&nbsp;account_number  卡号<br> 
                            cashamount 个人账户金额<br>
				            issetpaypwd  是否设置了支付密码 1设置 0未设置<br>
				            comamount   企业账户余额<br>
				            authStatus  实名认证状态(-1 未实名 1已实名)<br>
				            isUserComStatus 用户是否有企业账户(-1 为无 1为有)<br>
				            personLimit 个人账户提现限额<br>
				            personPro   手续费比例<br>
				            userWithdrawals 用户申请提现总金额(包括审核通过)<br>
				            withdrawalsstr 提现信息<br>
				            &nbsp;&nbsp;titlestr 提现标题<br>
				            &nbsp;&nbsp;content 提现内容<br>
				        ",
				                            
				    ],	
				    "user.withdrawals.add" => [
				        "name" => "添加提现申请",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ['paypwd',"支付密码=md5(支付密码)", "string", "必填"],
				            ["bankid", "银行卡id值", "int", "必填"],
				            ["accountType", "账户类型(1为企业账户  -1为个人账户)", "string", "必填"],
				            ["amount", "提现金额", "float", "必填"],
				        ],
				        "info" => "
				            code 200--操作成功   400--操作有误   404--参数有误   1001--无操作权限   50001--支付密码未设置   50002--支付密码不正确   10003 数据未存在<br>
                            id 提现申请id<br>
				            poundage 手续费金额<br>
				        ",
				    ],
				    "user.withdrawals.info" => [
				        "name" => "提现申请详情",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["id", "提现申请id值", "int", "必填"]
				        ],
				        "info" => "
				            code 200--操作成功   400--操作有误   404--参数有误<br>
                            id                提现申请id值<br>
                            amount            提现金额<br>
				            bank_name         银行名<br>
                            account_number    卡号<br>
							account_name      用户名<br>
				            status            提现状态  0处理中 1到帐成功 2提现失败<br>
				            statusStr           提现状态描述(v2.0.0 之后取该值)<br>
                            cashamount        可提现金额<br>
    				        addtime           提交时间<br>
    				        due_pay_time      预计到帐时间<br>
				            pay_time          到帐时间<br>
				        ",
				    
				    ],
				    "user.withdrawals.list" => [
				        "name" => "提现申请详情",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["begintime", "时间(Y-m)", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "
				            code 200--操作成功   400--操作有误   404--参数有误<br>
				            list              提现申请列表信息<br>
                            &nbsp;&nbsp;id                提现申请id值<br>
                            &nbsp;&nbsp;amount            提现金额<br>
				            &nbsp;&nbsp;bank_name         银行名<br>
                            &nbsp;&nbsp;account_number    卡号<br>
							&nbsp;&nbsp;account_name      用户名<br>
				            &nbsp;&nbsp;status            提现状态  0处理中 1到帐成功 2提现失败<br>
				            &nbsp;&nbsp;statusStr           提现状态描述(v2.0.0 之后取这个字段)<br>
                            &nbsp;&nbsp;cashamount        可提现金额<br>
    				        &nbsp;&nbsp;addtime           提交时间<br>
    				        &nbsp;&nbsp;due_pay_time      预计到帐时间<br>
				            &nbsp;&nbsp;pay_time          到帐时间<br>
				        ",
				    
				    ],
				    
				    "user.role.examfaildel" => [
				        "name" => "推荐收益审核未通过的牛商 牛掌柜删除操作",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["selfRoleType", "用户角色值", "int", "必填"],
				            ["recoRoleType", "推荐角色值", "int", "必填"],
				            ["mobile", "删除对象的手机号码", "string", "必填"],
				        ],
				        "info" => "code 200--操作成功 400 --操作有误 404 --参数有误 1001 --无操作权限 10003 数据未存在",
				    ],
				    "user.recharge.addrecharge" => [
				        "name" => "充值请求",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["amount", "充值金额", "string", "必填"],
				        ],
				        "info" => "返回orderno<br>",
				    ],
				    "user.recharge.rechargebull" => [
				        "name" => "牛豆充值请求",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["recharge_code", "充值号", "string", "必填"],
				            ["checkcode", "校验码=md5(recharge_code+私钥)", "string", "必填"],
				        ],
				        "info" => "返回200成功<br>",
				    ],
			    	"User.Collection.collectionList" => [
				           "name" => "我的收藏列表",
				           "param" => [
				               ["mtoken", "用户mtoken", "string", "必填"],
				               ["type", "收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店", "int", "必填"],
				               ["page", "分页页码", "int", "非必填"],
				           ],
				           "info" => '
				           code 200 --<br/>
				           ---收藏的牛品<br/>
				           id--商品id<br/>
				           thumb--商品图片<br/>
				           productname--商品名称<br/>
				           bullamount--商品牛豆<br/>
				           prouctprice--商品价格<br/>
				           ---收藏的供应商<br/>
				           id--实体店d<br/>
				           businesslogo--实体店图片logo<br/>
				           businessname--实体店名称<br/>
				           scores--实体店评分<br/>
				           isdelivery--是否配送1是<br/>
				           delivery--起送费<br/>
				           ---收藏的供应商<br/>
				           id--实体店id<br/>
				           businesslogo--供应商图片logo<br/>
				           businessname--供应商名称<br/>
				           scores--供应商评分<br/>
				           collectcount--供应被收藏的次数<br/>
			 	
				           ',
				       ],
				       "User.Collection.addCollection" => [
				           "name" => "添加收藏",
				           "param" => [
				               ["mtoken", "用户mtoken", "string", "必填"],
				               ["type", "收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店", "int", "必填"],
				               ["obj_id", "收藏的对象id", "int", "必填"],
				           ],
				           "info" => '
				           code 200 --<br/>
				           ',
				       ],
				       "User.Collection.cancelCollection" => [
				           "name" => "取消收藏",
				           "param" => [
				               ["mtoken", "用户mtoken", "string", "必填"],
				               ["type", "收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店", "int", "必填"],
				               ["obj_id", "收藏的对象id", "int", "必填"],
				           ],
				           "info" => '
				           code 200 --<br/>
				           ',
				       ],
				       "user.RecoStoBusiness.StoBusinessList" => [
				                "name" => "实体店信息",
				                "param" => [
				                    ["mtoken", "用户token", "string", "必填"],
				                    ["roleid", "角色值", "int", "必填"],
				                    ["type", "1通过审核的2未通过审核的", "int", "非必填"],
				                    ["page", "分页页码", "int", "非必填"],
				                ],
				                "info" => "code 200 --操作成功 404 --参数有误 <br>
	                            businessname          商家名称<br>
	                            addtime        添加时间<br>
	                            ischeck          审核状态（0为待审核1为通过2为未通过）<br>
	                            nopassReason      未通过审核原因<br>
	                            businesslogo  商家logo<br>
	                            mobile  手机号码<br>
		                        checkTotal  通过审核数量<br>
		                        noCheckTotal  未通过审数量<br/>  
	                            ",

				            ],
				       "user.RecoStoBusiness.recoshop" => [
				                "name" => "获取推荐实体店详细信息",
				                "param" => [
				                    ["customerid", "用户id", "int", "必填"],
				                ],
				                "info" => "code 200 --操作成功  1001 --无操作权限<br>
				                            sto_name                店铺名称<br>
				                            idnumber                身份证<br>
				                            mobile                  注册手机号码<br>
				                            address                 营业地址<br>
				                            servicetel              服务电话<br>
				                            description             购买须知<br>
				                            discount                折扣数<br>
				                            delivery                起送费<br>
				                            sto_type_id             分类id<br>
				                            categoryname            分类名称<br>
				                            area                    省市区<br>
				                            area_code               省市区编号<br>
				                            metro_id                地铁id<br>
				                            metro_name              地铁名称<br>
				                            district_id             商圈id<br>
				                            district_name           商圈名称<br>
				                            nearby_village          附近楼宇或小区名<br>
				                            sto_hour_begin          营业开始时间<br>
				                            sto_hour_end            营业结束时间<br>
				                            isparking               是否为免费停车(1为是 -1为否)<br>
				                            iswifi                  是否免费wifi(1为是 -1为否)<br>
				                            isdelivery              是否送货上门(1为是 -1为否)<br>
				                            licenceImage            营业执照<br>
				                            &nbsp;&nbspthumb        执照图片地址<br>
				                            idnumberImage           身份证<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            stoImage                轮播图图片<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            mainImage               主图<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            albumImage              相册图<br>
				                            &nbsp;&nbsp;thumb       图片地址<br>
				                            businessid              店铺id<br>
				                            isvip                   是否vip(1是 -1否)<br>",
				            ],
				       "user.RecoStoBusiness.recoupdateshop" => [
				                "name" => "编辑推荐的实体店",
				                "param" => [
				                    ["customerid", "用户id", "int", "必填"],
				                    ["sto_name", "店铺名称", "string", "必填"],
				                    ["discount", "结算折扣", "string", "必填"],
				                    ["sto_hour_begin", "营业开始时间(09:00 传递为900)", "string", "必填"],
				                    ["sto_hour_end", "营业结束时间(24:00 传递为2400)", "string", "必填"],
				                    ["service_type", "商家服务(1为免费wifi，2为免费停车, 3为送货上门) 用,进行拼接 ", "string", "非必填"],
				                    ["delivery", "起送费", "int", "非必填"],
				                    ["nearby_village", "附近楼宇或小区名", "string", "必填"],
				                    ["metro_id", "地铁id", "int", "非必填"],
				                    ["district_id", "商圈id", "int", "非必填"],
				                    ["sto_mobile", "商家服务电话(用,进行拼接)", "string", "必填"],
				                    ["description", "描述", "string", "必填"],
				                    ["main_image", "店铺主图", "string", "非必填"],
				                    ["sto_image", "店铺轮播图(用,进行拼接)", "string", "非必填"],
				                    ["album_image", "店铺相册图(用,进行连接)", "string", "非必填"],
				                ],
				                "info" => "code 200 --操作成功 1001 --无操作权限 3004 --折扣过高/过低 20007 --营业时间错误",
				            ],
				        "user.RecoStoBusiness.recoexamfaildel" => [
					        "name" => "我推荐的实体店删除 推荐收益审核未通过的牛商 牛掌柜删除操作",
					        "param" => [
					            ["customerid", "用户id", "int", "必填"],
					            ["selfRoleType", "用户角色值", "int", "必填"],
					            ["recoRoleType", "推荐角色值", "int", "必填"],
					            ["mobile", "删除对象的手机号码", "string", "必填"],
					        ],
					        "info" => "code 200--操作成功 400 --操作有误 404 --参数有误 1001 --无操作权限 10003 数据未存在",
					    ],
					    "user.BusinessEvaluate.evaluateList" => [
					        "name" => "牛商评价列表",
					        "param" => [
					        	["mtoken", "用户mtoken", "string", "必填"],
					        	["starttime", "用户mtoken", "date", "非必填"],
					        	["endtime", "用户mtoken", "date", "非必填"],
					        	["page","当前页码默认为1","int","非必填"]
					        ],
					        "info" => "code 200--操作成功 400 --操作有误 404 --参数有误 1001 ",
					    ],
					    "user.BusinessEvaluate.relpyEvaluate" => [
					        "name" => "牛商回复评价",
					        "param" => [
					        	["mtoken", "用户mtoken", "string", "必填"],
					        	["content","回复内容",'string','必填'],
					        	["evaluateid","评论id","int",'必填']
					        ],
					        "info" => "code 200--操作成功 400 --操作有误 404 --参数有误 1001 ",
					    ],
                        "user.amount.localSto" => [
                            "name" => "本地牛店",
                            "param" => [
                                ["mtoken", "用户mtoken", "string", "必填"],
                                ["role", "角色值", "int", "必填"],
                                ["page", "分页", "int", "非必填"]
                            ],
                            "info" => "code 200 -- 操作成功<br>
                                thumb               牛店logo<br>
                                businessname        牛店店名<br>
                                areaname            牛店位置<br>
                                stoFlowShare        消费分享奖励<br>
                                fansCount           粉丝个数<br>
                                customerid          牛店用户id值<br>"
                        ],
				        "user.amount.localStoFans" => [
				            "name" => "牛店粉丝",
				            "param" => [
				                ["mtoken", "用户mtoken", "string", "必填"],
				                ["role", "当前用户角色值", "int", "必填"],
				                ["customerid", "用户id", "int", "必填"],
				                ["page", "分页", "int", "非必填"]
				            ],
				            "info" => "code 200 -- 操作成功<br>
				                list                        列表数据<br>
				                &nbsp;&nbsp;thumb           粉丝头像<br>
				                &nbsp;&nbsp;nickname        粉丝昵称<br>
				                &nbsp;&nbsp;shareAmount     粉丝消费分享奖励<br>
				                &nbsp;&nbsp;stoShareAmount  牛店消费奖励<br>
				                &nbsp;&nbsp;busShareAmount  牛品消费奖励<br>
				                amount                      奖励总金额<br>
				                fansCount                   粉丝总数<br>"
				        ],
				    
    				    "user.community.orcommunity" => [
    				        "name" => "牛人列表",
    				        "param" => [
    				            ["mtoken", "用户mtoken", "string", "必填"],
    				            ["selfRoleType", "用户当前角色值", "string", "必填"],
    				            ["page","页数","int","非必填"],
    				        ],
    				        "info" => "code 200 --操作成功<br>
    				                    total                   列表个数(头部)<br>
    				                    list                    列表数据<br>
    				                    &nbsp;&nbsp;customerid  用户id值<br>
    				                    &nbsp;&nbsp;headerpic   用户头像<br>
    				                    &nbsp;&nbsp;nickname    姓名(昵称)<br>
    				                    &nbsp;&nbsp;mobile      手机号码<br>
    				                    &nbsp;&nbsp;stoCount    牛店个数",
    				    ],
				    
				        "user.community.orprofit" => [
				            "name" => "牛人收益",
				            "param" => [
				                ["mtoken", "用户mtoken", "string", "必填"],
				                ["selfRoleType", "用户当前角色值", "string", "必填"],
				                ["type", "收益类型(1 分享收益，2 拓店奖励收益    (默认为1，针对于孵化中心和运营中心进入))", "int", "必填"],
				                ["begintime", "开始时间(Y年m月)", "string", "非必填"],
				                ["endtime", "结束时间(Y年m月)", "string", "非必填"],
				                ["page", "页数", "int", "非必填"],
				            ],
				            "info" => "code 200 --操作成功 <br>
				                             datetime        年月份<br>
                                                    id                  <br>
                                                    flowtype            流水类型(预留字段)<br>
                                                    direction           (1为收入 + 2为支出 -)<br>
                                                    cashamount          牛票金额<br>
				                                    profitcommunity     牛粮奖励金<br>
				                                    bullamount          牛豆<br>
                                                    flowtime            流水时间<br>
                                                    datetime            时间<br>
				                                    mobile              手机号码<br>
				                                    headerpic           头像地址<br>
				                                    nickname            用户昵称<br>"
				        ],
				    
				        "user.community.flowbonus" => [
				            "name" => "牛粮奖励金流水",
				            "param" => [
				                ["mtoken", "用户mtoken", "string", "必填"],
				                ["selfRoleType", "当前用户role值", "string", "必填"],
				                ["begintime", "开始时间(Y年m月)", "string", "非必填"],
				                ["endtime", "结束时间(Y年m月)", "string", "非必填"],
				                ["page", "页数", "int", "非必填"],
				            ],
				            "info" => "code 200",
				        ],
				    
				        "user.community.communitystolist" => [
				            "name" => "牛人小组中的牛店列表",
				            "param" => [
				                ["mtoken", "用户mtoken", "string", "必填"],
				                ["selfRoleType", "当前用户role值", "string", "必填"],
				                ["customerid","牛人小组下的用户id值", "int", "必填"],
				                ["role", "小组下的用户role值(预留字段 方便拓展  暂时都传递为2)","int","必填"],
				                ["page", "页数", "int", "非必填"],
				            ],
				            "info" => "code 200 <br>
				                        customerid          用户id值<br>
				                        mobile              店铺号码<br>
				                        id                  店铺id(预留字段)<br>
				                        businessname        店铺名称<br>
				                        addtime             时间<br>
				                        businesslogo        店铺logo<br>",
				        ],
				    "user.login.thirdparty" => [
				        "name" => "第三方授权登录",
				        "param" => [
				            ["open_type", "授权登录类型(weixin,qq,sina等  暂时只weixin)","string", "必填"],
				            ["openid","openid值","string","必填"],
				            ["openencrypt", "md5(授权openid值+私钥)","string","必填"],
// 				            ["isbindMobile","是否绑定手机号码操作(1为是，0为否)","int","必填"],
// 				            ["mobile", "手机号码", "string", "非必填(绑定时为必填)"],
// 				            ["valicode", "md5(校验码+私钥)", "string", "非必填(绑定时为必填)"],
				            ["devtype", "设备类型(安卓为A ios 为I)", "string", "必填"],
				            ["parentid", "引荐人id", "int", "非必填"],
				            ["checkcode", "引荐人校验码", "string", "非必填"],
				            ["stocode","平台号","string","非必填"],
				            ["nickname", "昵称值", "string", "非必填"],
				            ["sex", "性别(1为男，2为女，0为未知)", "int", "非必填"],
				            ["province", "省份", "string", "非必填"],
				            ["city", "城市", "string", "非必填"],
				            ["country", "国家", "string", "非必填"],
				            ["headimgurl", "用户头像", "string", "非必填"],
				            ["unionid", "用户统一标识", "string", "非必填"],
				            ["privilege", "特权信息", "string", "非必填"]
				        ],
				        "info" => "code 200 <br>
				                    mtoken ---登录token值<br>
				                    role -- 登录角色值<br>
				                    isBindMobile    是否执行绑定手机号码操作(1为是 0为否)"
				    ],
				    "user.role.checkrecorole"=>[
				        "name" => "检测是否有分享权限",
				        "param" => [
				            ["selfRoleType", "成为的角色值(牛人2 牛创客3 牛达人8)", "int", "必填"],
				            ["mobile", "分享人手机号码", "string", "非必填"]
				        ],
				        "info" => "code 200 <br>
				                roleupdateauth  返回可否能进行修改分享人手机号码权限/是否进行友情提示(0为否 1为可)<br>
				                errormsg        返回消息提示<br>",
				    ],
				],
			],
			"mall"=>[
				"name"=>"商城首页",
				"api"=>[
					"mall.index.indexGoodslist"=>[
								"name"=>"商城首页获取所有商品列表(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["page","当前页码默认为1","int","非必填"]
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				proData---- 商品列表信息<br>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;bullamount---商品牛积分<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/> 
								",
							],
					"mall.index.indexCategory"=>[
								"name"=>"商城首页获取所有分类(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["page","当前页码默认为1","int","非必填"],
										["businessid","id","int","非必填"],
										["showall","是否显示全部1显示0不显示","int","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				proData---- 商品列表信息<br>
                    				&nbsp;&nbsp;&nbsp;&nbsp;id---分类id<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;parent_id---上级分类id<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;name---分类名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;status---状态<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;sonCate---子分类返回字段和父级分类id<br/> 
								",
							],
					"mall.index.indexBannerAndSaleway"=>[
								"name"=>"商城首页获取banner图片和快捷方式(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				banner ---轮播图内容<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;bname---广告名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---图片链接<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;sort--排序<br/>
                    				saleWay ---快捷方式<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;bname---广告名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---图片链接<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;sort--排序<br/>
								",
							],
					"mall.index.indexAnnounAndActive"=>[
								"name"=>"商城首页获取公告和活动信息(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["citycode","城市编码","string","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				announcement ---公告内容<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;title---公告内容<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;sort--排序<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;mark---标识1为热门，2位最新等。。<br/> 
					                goodweeklygoods   -- 每周好货<br>
					                publicurl          -- 每周好货公共跳转地址<br>
					                goodslist          -- 每周好货列表<br>
					                &nbsp;&nbsp;&nbsp;&nbsp;goodsid            商品id<br>
					                &nbsp;&nbsp;&nbsp;&nbsp;saleprice          销售价(原价)<br>
					                &nbsp;&nbsp;&nbsp;&nbsp;prouctprice        商品价格(折扣价)<br>
					                &nbsp;&nbsp;&nbsp;&nbsp;productname        商品名称<br>
					                &nbsp;&nbsp;&nbsp;&nbsp;thumb              商品缩略图<br>
					                &nbsp;&nbsp;&nbsp;&nbsp;url                点击跳转地址(预留字段)<br>
                    				modulebanner ---活动列表分组显示<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;modulename---模块名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;modulecode---模块编码（唯一）<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;sort---排序<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;banner---模块内容
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bname---广告名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;thumb---图片链接<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sort--排序<br/>
								",
							],
					"mall.index.categoryGoodsList"=>[
								"name"=>"分类下商品列表(已开发)",
								"param"=>[
										["cid","分类id","int","必填"],
										["page","当前页码默认为1","int","非必填"]
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				prolist ---商品列表数据<br>
                    				&nbsp;&nbsp;&nbsp;&nbsp;total---显示条数<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;list---显示列表<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bullamount---商品牛积分<br/>
								",
							],
					"mall.index.mallKeywords"=>[
								"name"=>"获取关键词",
								"param"=>[
										["mtoken","登录token值","string","非必填"],
										['type',"类型1为牛掌柜2为牛品","int","非必填"]
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				name--输入框热词<br/>
                    				kewords--列表热词<br/>
								",
							],
// 				    "mall.index.goodweeklygoods"=>[
// 				        "name"=>"每周好货(首页)",
// 				        "param"=>[
// 				            ["page","页数","int","非必填"]
// 				        ],
// 				        "info" => "code 200 --操作成功<br>
// 				                    goodsid         商品id<br>
// 				                    saleprice       销售价(折扣价)<br>
// 				                    prouctprice     商品价格(原价)<br>
// 				                    productname     商品名称<br>
// 				                    thumb           商品缩略图",
// 				    ],
				    
				    "mall.index.goodsactivelist"=>[
				        "name"=>"每周好货(列表)",
				        "param"=>[
				            ["type", "活动类型", "int", "非必填(默认为0，下期为1)"],
				            ["goodsid", "商品id", "int", "非必填"],
				            ["customerid", "用户id", "int", "非必填"],
				            ["page", "页数", "int", "非必填"]
				        ],
				        "info" => "code 200 --操作成功<br>
				                    goodsid             商品id<br>
				                    saleprice           销售价(原价)<br>
				                    prouctprice         商品价格(折扣价)<br>
				                    productname         商品名称<br>
				                    thumb               商品图片<br>
				                    roundPro            商品已售比例(% 当100%时 需要处理按钮和文字)<br>
				                    activeproductnum    参与活动的商品数量<br>",
				    ],
					
				],

			],
			"product"=>[
				"name"=>"商品",
				"api"=>[

					"product.index.goodsEvaluate"=>[
								"name"=>"商品评价信息(已开发)",
								"param"=>[
										["goodsid","商品的id","int","必填"],
										["mtoken","用户mtoken","string","非必填"],
										["page","第几页：1,2,3默认第1页","int","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				prodata----当前商品信息<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productid----商品id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productname----名称<br/>
                    				commnentData----评价列表
                    				&nbsp;&nbsp;&nbsp;&nbsp;evaluate_id----评价id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productid----商品id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productname----商品名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;scores----评分<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;content----内容<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;frommemberid----评价人id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;frommembername----评价人名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;headpic----评价人头像<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;img_arr----相关图片（保留）<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;reply----掌柜回复<br/>
								",
					],

					"product.search.index"=>[
								"name"=>"商品搜索(已开发)",
								"param"=>[
										["cid","商品分类id","int","非必填"],
										["mtoken","用户mtoken","string","非必填"],
										["keywords","搜索关键字","string","非必填"],
										["price_sort","价格排序 desc|asc","string","非必填"],
										["producttype","专区 1现金专区 2现金+牛豆专区 3牛豆专区","string","非必填"],
										["businessid","店铺ID","string","非必填"],
										["page","第几页：1,2,3默认第1页","int","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				prolist----商品列表<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;total----总记录条数<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;list----记录列表<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;bullamount---商品牛积分<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/> 
								",
							],
					"product.index.goodsDetail"=>[
								"name"=>"商品详情(已开发)",
								"param"=>[
										["goodsid","商品的id","int","必填"],
										["mtoken","用户mtoken","string","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
									transport---商品运费<br/>
                    				productid---商品id<br/>
                    				bannerimg---商品轮播图<br/>
                    				productname---商品名称<br/>
                    				prouctprice---商品价格<br/>
                    				bullamount---商品牛积分<br/>
                    				businessid---商家id<br/>
                    				spec---商品规格<br/>
			                        sku---商品sku信息<br/>
			                        salecount--销量<br/>
			                        description--图文详情<br/>
			                        area---所在地区<br/>
			                        enable---0为下架，1为上架，2为违规下架<br/>
			                        iscollect--是否收藏（1为已收藏-1没有收藏）<br/>
			                        business---商家信息<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;businessid---商家id<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;businessname---商家名称<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;businesslogo---商家logo<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;mobile---商家手机<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;area---商家地址<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;goodscount---宝贝数量<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;collectcount---收藏次数<br/>
			                        &nbsp;&nbsp;&nbsp;&nbsp;scores---店铺星级<br/>
					                sharecontent--分享内容<br/>
					                &nbsp;&nbsp;&nbsp;&nbsp;url---分享链接<br/>
					                &nbsp;&nbsp;&nbsp;&nbsp;title---分享标题<br/>
					                &nbsp;&nbsp;&nbsp;&nbsp;description---分享描述<br/>
					                &nbsp;&nbsp;&nbsp;&nbsp;image---分享图片<br/>

					                ---------抢购活动--------<br/>
					                qianggou--qianggou_flag 判断是否有抢购活动，1表示抢购活动0表示非抢购活动<br/>
					                qianggou--抢购信息<br/>
					                qianggou--status 抢购状态是否开始 0抢购结束1抢购未开始2抢购中<br/>
					                qianggou--starttime 活动开始时间<br/>
					                qianggou--endtime 活动时间<br/>
					                qianggou--nowtime 服务器当前时间<br/>
					                qianggou--prouctprice 抢购商品销售价<br/>
					                qianggou--productstorage 剩余购买库存<br/>
					                qianggou--limitbuy 单人限购-1表示不限购0表示不能购买N表示限购数量<br/>
								",
							],
					
				],

			],
			"order"=>[
				"name"=>"订单",
				"api"=>[
					"order.index.orderlist"=>[
								"name"=>"订单列表(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderlisttype","订单列表类型1全部2待付款3待发货4待收货5待评价","int","必填"],
										["page","第几页：1,2,3默认第1页","int","非必填"],
									],
								"info"=>"
									返回 orderlist 二维数组<br>
									返回参数的说明：<br>
									`actualfreight`  '实际运费',<br>
									`productcount`  '商品总数量',<br>
									`productamount`  '商品总金额',<br>
									`bullamount`  '商品总牛币数',<br>
									`totalamount`  '订单总金额=实际运费+商品总金额',<br>
									`addtime`  '订单添加时间',<br>
									 evaluate-是否已评价<br>
									 return_status 退款状态，0无退款1退款中2退款完成<br>
									`orderstatus` '订单状态0待付款1已付款待发货2已发货3确认收货4订单完结5取消',<br>
									-----------订单状态--------<br>
									0待付款---待收款<br>
									1已付款待发货---待发货<br>
									2已发货---待收货<br>
									3已确认确认收货 --交易成功<br>
									  evaluate-是否已评价<br>
									  return_status 退款状态，0无退款1退款中2退款完成<br>
									4订单完结 --已完结<br>
									5取消', ---订单关闭<br>
									-----------订单状态--------<br>
									-----------订单操作说明--------<br>
									orderact 订单操作按钮字段<br>
									orderact:act说明：1表示操作按钮2显示文字<br>
									orderact:actname:按钮值<br>
									orderact:acttype说明：<br>
									1 付款<br>
									2 取消订单<br>
									3 提醒商家发货<br>
									4 退款--申请退款<br>
									5 订单详情-取消退款<br>
									6 延长收货<br>
									7 查看物流<br>
									8 确认收货<br>
									9 评价<br>
									10 售后<br>
									11 删除订单<br>
				                    12 退款详情-取消退款<br>
				                    13 退款详情-修改申请<br>
				                    14 退款详情-撤销申请<br>
				                    15 订单详情-退款中<br>
									16 订单详情-已退款<br>
									17 退单列表-填写物流单号<br>
									18 退单列表-查看物流(和7重复了，去除)<br>
									19 退款详情-提交物流<br>
									------------------------------<br>
									`businessid`  '商店ID',<br>
									`businessname` '商店名称',<br>
									<br>
									orderitem 商品明细 二维数组<br>
									productid 商品ID<br>
									productname 商品名称<br>
									productnum 商品数量<br>
									thumb 商品图片<br>
									prouctprice 商品价格<br>
									bullamount 商品牛币数<br>
								",
					],
				    
				    "order.index.ordercount" => [
				                "name" => "个人首页订单个数",
				                "param" => [
				                    ["mtoken", "用户mtoken", "string", "必填"],
				                ],
				                "info" => "code 200 <br>
				                            count_pay       待收货数量<br>
				                            count_deliver   待发货数量<br>
				                            count_receipt   待收货数量<br>
				                            count_evaluate  待评论数量<br>
				                            count_cart      购物车数量<br>
				                            count_return    退单数量",
				    ],

					"order.index.showorder"=>[
								"name"=>"提交订单前的确认订单详情",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["cartitemids","cartitemids(多个cartitemid用','分隔)","string","cartitemids和skuid二选一"],
										["skuid","skuid","string","cartitemids和skuid二选一"],
										["productnum","商品数量","string","和skuid时一起是必填"],
										["logisticsid","收货地址ID","string","非必填"],
									],
								"info"=>"
									loginstics 数组 用户默认物流信息，如果为空，说明用户没有设置物流信息：<br>

									`totalactualfreight`  '总运费',<br>
									`totalcount`  '商品总数量',<br>
									`totalamount`  '商品总金额',<br>
									`totalbull`  '商品总牛币数',<br>
									`total`  '订单总金额=实际运费+商品总金额',<br>

									
									orderlist 二维数组
									<br>
									`businessid`  '商店ID',<br>
									`businessname` '商店名称',<br>
									productlist 商品明细 二维数组<br>
									productid 商品ID<br>
									productname 商品名称<br>
									productnum 商品数量<br>
									thumb 商品图片<br>
									prouctprice 商品价格<br>
									bullamount 商品牛币数<br>
					               ----------------v2.1.0--------------<br>
					               abroad                  不为空时 是海外购订单<br>
					               &nbsp;&nbsp;titleStr    标题描述<br>
					               &nbsp;&nbsp;status      是否已经填写了收货人的详细信息(1为已填写，-1为未, 为空时  不为海外购)<br>
					               &nbsp;&nbsp;            身份证号码(status 为1时 显示)<br>
								",
							],
					"order.index.addorder"=>[
								"name"=>"提交订单(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["sign","签名=md5(按业务字段排序(address_id+items)+私钥)","string","必填"],
										["address_id","物流ID","string","必填"],
										["items","商品明细","json,内容包括：商品ID（productid），skuID（skuid），商品数量（productnum），商品在购物车中的ID（cartid），商品备注（remark）","必填"],
								        ["isabroad", "是否海外购(1是 0为否 默认为0)", "int", "非必填"],
								        ["qianggouid", "抢购活动ID,多个id用,分隔", "string", "非必填"],
									],
								"info"=>"
									返回orderidstr，用于支付请求使用<br>
								",
							],
					"order.index.orderdetail"=>[
								"name"=>"订单详情(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"],
									],
								"info"=>"
									logistics 物流信息<br>
									返回orderdetail的参数的说明：<br>
									`actualfreight`  '实际运费',<br>
									`productcount`  '商品总数量',<br>
									`productamount`  '商品总金额',<br>
									`bullamount`  '商品总牛币数',<br>
									`totalamount`  '订单总金额=实际运费+商品总金额',<br>
									`addtime`  '订单添加时间',<br>
									`orderstatus` '订单状态0待付款1已付款待发货2已发货3部分发货4订单完结5确认收货6取消',<br>
									`businessid`  '商店ID',<br>
									`businessname` '商店名称',<br>
									<br>
									orderitem 商品明细 二维数组<br>
									productid 商品ID<br>
									productname 商品名称<br>
									productnum 商品数量<br>
									thumb 商品图片<br>
									prouctprice 商品价格<br>
									bullamount 商品牛币数<br>

									delivertime 发货时间<br>
									paytime 支付时间<br>
									-----------订单操作说明--------<br>
									orderact 订单操作按钮字段<br>
									orderact:act说明：1表示操作按钮2显示文字<br>
									orderact:actname:按钮值<br>
									orderact:acttype说明：<br>
									1 付款<br>
									2 取消订单<br>
									3 提醒商家发货<br>
									4 退款--申请退款<br>
									5 订单详情-取消退款<br>
									6 延长收货<br>
									7 查看物流<br>
									8 确认收货<br>
									9 评价<br>
									10 售后<br>
									11 删除订单<br>
				                    12 退款详情-取消退款<br>
				                    13 退款详情-修改申请<br>
				                    14 退款详情-撤销申请<br>
				                    15 订单详情-退款中<br>
									16 订单详情-已退款<br>
									17 退单列表-填写物流单号<br>
									18 退单列表-查看物流(和7重复了，去除)<br>
									19 退款详情-提交物流<br>
									------------------------------<br>
									business_tel 供应商电话 数组格式如：['07985632154','07985632155']<br>
					               -----------------v2.1.0-----------<br>
					               isabroad        标识是否海外购订单(1为海外购 0为否)<br>
					               receipt_address <br>
					               &nbsp;&nbsp;    idnumber 身份证号码<br>
								",
							],
					"order.index.cancelsorder"=>[
						"name"=>"取消订单",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"],
										["cancelreason","取消订单原因","string","必填"],
									],
								"info"=>"
									
								",
					],
					"order.index.confirmorder"=>[
						"name"=>"确认收货",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"],
									],
								"info"=>"
									
								",
					],

					"order.evaluateorder.EvaluateOrderInfo"=>[
						"name"=>"评价订单详情(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"]
									],
								"info"=>"
									businessid----商家id<br/>
									businessname----商家名称<br/>
									productid----商品id<br/>
									productname----商品名称<br/>
									skuid----商品skuid<br/>
									prouctprice----商品价格<br/>
									bullamount----商品牛积分<br/>
									thumb----商品图片<br/>
									orderno----订单号<br/>
									productnum----购买数量<br/>
								",
					],
					"order.evaluateorder.addEvaluateOrder"=>[
						"name"=>"评价订单(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"],
										["EvaluateArr","评价内容","json","必填"],
										["evaluatearr","评价内容","json","非必填"]
									],
								"info"=>'
									200----添加成功<br>
									EvaluateArr提交
									json数据格式：[{customerid:1,isanonymous:1,productid:1,scores:4,orderno:nnhpro-124,content:56asdfasdf748,skuid:1,evaluate_images=>}]<br/>
			                        参数字段说明--------<br/>
			                        ["isanonymous","是否匿名1是-1否默认1","int","非必填"],<br/>
									["orderno","订单编号","string","必填"],<br/>
									["productid","商品id","int","必填"],<br/>
									["skuid","商品skuid","int","非必填"],<br/>
									["scores","评分","int","必填"],<br/>
									["content","评价内容","int","必填"],<br/>
								',
					],
// 					"order.index.refund"=>[
// 						"name"=>"订单退款详情(已开发)",
// 								"param"=>[
// 										["mtoken","用户mtoken","string","必填"],
// 										["orderno","订单编号","string","必填"],
// 										["returnreason","退款|退货原因","string","必填"],
// 										["returnamount","申请退款金额","float","必填"],
// 										["returnbull","申请退还牛币","int","必填"],
// 										["images","退货图片,多张图片用‘,’分隔","string","非必填"],
// 									],
// 								"info"=>"
									
// 								",
// 					],
//                     "order.refund.salerefund" => [
//                         "name" => "申请退款/退货页面(已开发)",
//                         "param" => [
//                                 ["mtoken", "用户mtoken", "string", "必填"],
//                                 ["orderno", "订单编号", "string", "必填"],
//                                 ["productid", "商品id", "int", "必填"],
//                                 ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
//                                 ["refund_type", "(售中退款1 售后退款2 售后退货3)默认为1", "int", "非必填"],
//                             ],
//                         "info" => "code 200 --添加成功  404 --参数有误 1000 -- 记录不存在 1001 --无操作权限<br>
//                                     productid       商品id<br>
//                                     productname     商品名称<br>
//                                     productimage    商品图片<br>
//                                     spec            商品规格<br>
//                                     productnum      商品总个数<br>
//                                     oneprice        商品单价金额<br>
//                                     onebull         商品单价牛币金额<br>
//                                     reasonlist      退款原因列表"
//                     ],
				    "order.refund.refersaleapply" => [
				        "name" => "退款/退货申请(已开发)",
				        "param" => [
				                ["mtoken", "用户mtoken", "string", "必填"],
				                ["orderno", "订单编号", "string", "必填"],
				                ["productid", "商品id", "int", "必填"],
				                ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				                ["return_type", "类型(退款为1, 退货退款为2)", "int", "必填"],
				                ["reason", "原因", "string", "必填"],
				                ["return_count", "个数(退货时选择个数)", "int", "(退货为必填)非必填"],
				                ["remark", "说明", "string", "非必填"],
				                ["images", "凭证图片地址", "string", "非必填"],
				            ],
				        "info" => "code 200 --添加成功 400 --操作异常  404 --参数有误 1000 -- 记录不存在 1001 --无操作权限 11001 --请勿重复提交申请<br>",
				    ],
				    "order.refund.refundinfo" => [
				        "name" => "退款/退货详情(已开发)",
				        "param" => [
				                ["mtoken", "用户token", "string", "必填"],
				                ["orderno", "订单编号", "string", "必填"],
				                ["productid", "商品id", "int", "必填"],
				                ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				            ],
				        "info" => "code 200 --操作成功 1000 --记录不存在 1001 --无操作权限<br>
				                    refundstatus    拒绝状态(0为正常，1为重申， 2为拒绝)<br>
				                    content         文字描述<br>
				                    productimage    商品图片地址<br>
				                    productname     商品名称<br>
				                    returnreason    退款原因<br>
				                    returnamount    退款金额(现金)<br>
				                    returnbull      退款牛币<br>
				                    freight         退款运费金额<br>
				                    starttime       申请时间<br>
				                    returnno        退款订单编号<br>
				                    phone           <br>
				                    refundstatus    拒绝状态 标识拒绝和重申请求<br>
				                    &nbsp;&nbsp;mobile      手机号码<br>
				                    &nbsp;&nbsp;servicetel  服务电话<br>
				                    -----------订单操作说明--------<br>
									orderact 订单操作按钮字段<br>
									orderact:act说明：1表示操作按钮2显示文字<br>
									orderact:actname:按钮值<br>
									orderact:acttype说明：<br>
									1 付款<br>
									2 取消订单<br>
									3 提醒商家发货<br>
									4 退款--申请退款<br>
									5 订单详情-取消退款<br>
									6 延长收货<br>
									7 查看物流<br>
									8 确认收货<br>
									9 评价<br>
									10 售后<br>
									11 删除订单<br>
				                    12 退款详情-取消退款<br>
				                    13 退款详情-修改申请<br>
				                    14 退款详情-撤销申请<br>
				                    15 订单详情-退款中<br>
									16 订单详情-已退款<br>
									17 退单列表-填写物流单号<br>
									18 退单列表-查看物流<br>
									19 退款详情-提交物流<br>
									------------------------------<br>
				                    ----------v2.1.0--------------<br>
				                    expresslist             快递公司列表<br>
				                    returnid                退单id值<br>
				                    isreturnexpress         标识是否填写(-1为审核未通过，不显示关于物流的相关内容,0为未填写物流，显示物流信息，1为已填写物流)<br>
				                    receipt_address         快递物流信息<br>
				                    ",
				    ],
				    "order.refund.refundexpress"=>[
				        "name"=>"退货审核成功，填写物流信息",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["returnid", "退单id值", "int", "必填"],
				            ["expressname", "快递公司", "string", "必填"],
				            ["expressnumber", "快递编号", "string", "必填"],
				        ],
				        "info"=>"code 200 --操作成功",
				    ],
				    "order.refund.refundotherinfo" => [
				        "name" => "退款成功 以及关闭退款  详情页(已开发)",
				        "param" => [
				                ["mtoken", "用户token", "string", "必填"],
				                ["returnid", "退款/退货id", "string", "非必填(填写之后 orderno, productid, skuid为非必填  列表用此id)"],
				                ["orderno", "订单编号", "string", "非必填(订单详情用此)"],
				                ["productid", "商品id", "int", "非必填(订单详情用此)"],
				                ["skuid", "商品规格id(无规格可填0)", "int", "非必填(订单详情用此)"],
				            ],
				        "info" => "code 200 --操作成功 1000 --记录不存在 1001 --无操作权限<br>
				                    business_name       商家名称<br>
				                    returnType          退款类型文字描述<br>
				                    type                退款类型(1为退款 2为退货)<br>
				                    productnum          退货个数<br>
				                    returnreason        原因<br>
				                    returnamount        退款金额<br>
				                    returnbull          退款牛币<br>
				                    remark              说明<br>
				                    returnno            退款单号<br>
				                    starttime           申请时间<br>
				                    endtime             退款时间<br>
				                    orderact            按钮信息(预留)<br>
				                    orderstatusstr      提示信息<br>
				                    business_tel        商家电话数组格式如：['07985632154','07985632155']<br><br/> 
				                    &nbsp;&nbsp;statusstr       标题",

				    ],
				    "order.refund.cancleapply" => [
				        "name" => "用户取消退款/退货申请操作(已开发)",
				        "param" => [
				                ["mtoken", "用户token", "string", "必填"],
				                ["orderno", "订单编号", "string", "必填"],
				                ["productid", "商品id", "int", "必填"],
				                ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				            ],
				        "info" => "code 200 --操作成功 400 --操纵失败 1000 -- 记录不存在 1001 --无操作权限 11002 --已经取消申请了，请勿重复提交 11003--退款已成功，无法进行取消",
				    ],
				    "order.refund.saleapplyfail" => [
				        "name" => "商家申请审核失败(已开发)",
				        "param" => [
    				            ["mtoken", "用户token", "string", "必填"],
    				            ["orderno", "订单编号", "string", "必填"],
    				            ["productid", "商品id", "int", "必填"],
    				            ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				                ["remark", "备注描述", "string", "非必填"],
				            ],
				        "info" => "code 200 --操作成功 400 --操纵失败 1000 -- 记录不存在 1001 --无操作权限 11001 --请勿重复申请退款 11002 --已经取消申请了，请勿重复提交 11003--退款已成功，无法进行取消",
				    ],
				    "order.refund.saleapplysuccess" => [
				        "name" => "商家申请审核成功(已开发)",
				        "param" => [
    				            ["mtoken", "用户token", "string", "必填"],
    				            ["orderno", "订单编号", "string", "必填"],
    				            ["productid", "商品id", "int", "必填"],
    				            ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				                ["remark", "备注描述", "string", "非必填"],
				            ],
				        "info" => "code 200 --操作成功 400 --操纵失败 1000 -- 记录不存在 11002 --已经取消申请了，请勿重复提交 11004--已经审核，无法重复提交",
				    ],
				    "order.refund.consulelog" => [
				        "name" => "协商历史(已开发)",
				        "param" => [
    				            ["mtoken", "用户token", "string", "必填"],
    				            ["orderno", "订单编号", "string", "必填"],
    				            ["productid", "商品id", "int", "必填"],
    				            ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				            ],
				        "info" => "code 200 --操作成功<br>
				                    actionsource 操作来源(1为自己 2为商家)<br>
				                    addtime 添加时间<br>
				                    content 内容<br>
				                    remark 描述<br>",
				    ],
				    "order.refund.returnlist" => [
				        "name" => "退款/退货列表(已开发)",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["page", "分页", "string", "非必填(默认为1)"],
				            ],
				        "info" => "code 200 -- 操作成功<br>
				                    returnid                退款编号id<br>
				                    skip_type               标识跳转详情页类型(1为 退款中详情页  2为关闭页面)<br>
				                    productdetail           商品信息<br>
				                    &nbsp;&nbsp;productid   商品id<br>
				                    &nbsp;&nbsp;productname 商品名称<br>
				                    &nbsp;&nbsp;spec        商品规格<br>
				                    &nbsp;&nbsp;productimage商品图片<br>
				                    &nbsp;&nbsp;sku         商品sku值<br>
				                    productnum              商品个数<br>
				                    returnamount            商品退款金额<br>
				                    returnbull              商品退款牛币<br>
				                    statusDes               商品退货/退款状态<br>
				                    orderno                 商品订单编号<br>
				                    -----------------v2.1.0---------------
				                    receipt_address         物流信息<br>
				                    &nbsp;&nbsp;express_name    物流公司<br>
				                    &nbsp;&nbsp;express_no      物流单号<br>",
				    ],
				    "order.refund.confirmrefund" => [
				        "name" => "商家确认退款(已开发)",
				        "param" => [
				                ["mtoken", "用户token", "string", "必填"],
    				            ["orderno", "订单编号", "string", "必填"],
    				            ["productid", "商品id", "int", "必填"],
    				            ["skuid", "商品规格id(无规格可填0)", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功 400 --操作异常 -- 404 参数有误 1000 --记录不存在 1001 -- 无权限操作 11005 --已经确认，无法重复提交",
				    ],
					"order.index.remindshipping"=>[
						"name"=>"提醒商家发货(开发中。。。)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"]
									],
								"info"=>"
									200----修改成功<br>
								",
					],
					"order.index.extendedreceipt"=>[
						"name"=>"订单延长收货(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"]
									],
								"info"=>"
									200----修改成功<br>
								",
					],
					"order.index.deleteorder"=>[
						"name"=>"删除订单(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["orderno","订单编号","string","必填"]
									],
								"info"=>"
									200----修改成功<br>
								",
					],
				    "order.index.addlogisticsinfo"=>[
				        "name"=>"海外购添加收货人详细信息",
				        "param"=>[
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["address_id", "收货信息id值", "int", "必填"],
				            ["realname", "收货人姓名", "string", "必填"],
				            ["idnumber", "身份证号码", "string", "必填"],
				            ["positiveImage", "正面身份证(无域名地址)","string", "必填"],
				            ["oppositeImage", "反面身份证(无域名地址)","string", "必填"],
				        ],
				        "info"=>"code 200",
				    ],
				],

			],

			"business"=>[
				"name"=>"店铺",
				"api"=>[
					"business.index.home"=>[
								"name"=>"店铺首页",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["businessid","店铺编号","string","必填"],
										["page","第几页：1,2,3默认第1页","int","非必填"],
									],
								"info"=>"
									第1页返回店铺信息+店铺商品信息，第2页以后只返回商品信息
									iscollect--是否收藏（1为已收藏-1没有收藏）<br/>
								",
							],
					"business.index.goodsList"=>[
								"name"=>"获取店铺全部宝贝",
								"param"=>[
										["businessid","店铺id","int","必填"],
										["cid","分类id","int","非必填"],
										["mtoken","用户mtoken","string","非必填"],
									],
								"info"=>"
									返回店铺商品信息
								",
							],
					"business.index.searchGoods"=>[
								"name"=>"获取店铺全部宝贝",
								"param"=>[
										["businessid","店铺id","int","必填"],
										["cid","分类id","int","非必填"],
										["keywords","关键词","string","非必填"],
										["order","排序","string","非必填"],
										["mtoken","用户mtoken","string","非必填"],
									],
								"info"=>"
									返回店铺商品信息
								",
							],
					"business.Category.getBusinessCategory"=>[
								"name"=>"获取店铺所有分类",
								"param"=>[
										["businessid","id","int","必填"],
										["mtoken","用户mtoken","string","非必填"],
									],
								"info"=>"
									第1页返回店铺信息+店铺商品信息，第2页以后只返回商品信息
								",
							],
					
				],

				

			],

			"Shopingcart"=>[
					"name"=>"购物车",
					"api"=>[
						"shopingcart.index.goodslist"=>[
									"name"=>"购物车商品列表",
									"param"=>[
											["mtoken","登录token值","string","必填"],
											["page","当前页码默认为1","string","非必填"],
										],
									"info"=>'
										code 200 --操作成功 <br>
	                    				cardata ---购物车信息<br>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;cargoodsgount---购物车商品总数量<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;pricecount---购物车商品总价<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;bullamountcount---购物车商品总价牛积分<br/>
	                    				goodsList----购物车商品列表<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;id---购物车id<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;customerid---客户id<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;businessid---商店id<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;businessname---商店名称<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;addtime---购物车添加时间<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;goodsData---商品明细<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;businessid---商家id<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productnum---商品数量<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productstorage---商品库存<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;skuid---商品skuid<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;productimage---商品图片<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bullamount---商品牛积分<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;spec---商品规格<br/>
	                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;enable---0为下架，1为上架，2为违规下架<br/>
									',
								],
						"shopingcart.index.addgoods"=>[
									"name"=>"添加商品",
									"param"=>[
											["mtoken","登录token值","string","必填"],
											["productnum","加入数量","int","必填"],
											["productid","商品id","int","必填"],
											["skuid","商品Skuid","int","非必填"],
											["sku_code","商品Sku编码","string","非必填"],
											
										],
									"info"=>'
										code 200 --操作成功 <br>
									',
								],
						"shopingcart.index.updateSpecgoods"=>[
									"name"=>"编辑购物车单条信息",
									"param"=>[
											["mtoken","登录token值","string","必填"],
											["productnum","加入数量","int","必填"],
											["productid","商品id","int","必填"],
											["skuid","商品Skuid","int","非必填"],
											["sku_code","商品Sku编码","string","非必填"],
											["oldcartid","编辑的购物车id","string","必填"]
										],
									"info"=>'
										code 200 --操作成功 <br>
									',
								],
						"shopingcart.index.choseGoosSpec"=>[
									"name"=>"购物车编辑规格",
									"param"=>[
											["mtoken","登录token值","string","非必填"],
											["productid","商品id","int","必填"],
											["skuid","商品skuid","int","非必填"],
										],
									"info"=>'
										code 200 --操作成功 <br>
	                    				productid---商品id<br/>
	                    				bannerimg---商品轮播图<br/>
	                    				productname---商品名称<br/>
	                    				prouctprice---商品价格<br/>
	                    				bullamount---商品牛积分<br/>
	                    				businessid---商家id<br/>
	                    				productimage---sku商品图片<br/>
	                    				spec---商品规格<br/>
				                        sku---商品sku信息<br/>
				                        salecount--销量<br/>
									',
								],
						"shopingcart.index.updateCartNum"=>[
									"name"=>"修改购物车数量",
									"param"=>[
											["cartid","购物车id","int","必填"],
											["num","修改数量","int","必填"],
											["mtoken","登录token值","string","必填"]
										],
									"info"=>'
										code 200 --操作成功 <br>
									',
								],
								
						"shopingcart.index.delgoods"=>[
									"name"=>"删除购物车商品",
									"param"=>[
											["mtoken","登录token值","string","必填"],
											["cartid","购物车id","int","必填"],
											["businessid","商家id","int","必填"]
										],
									"info"=>'
										code 200 --操作成功 <br>
									',
								],
						
					],

			],



			"sys"=>[
				"name"=>"上传图片",
				"api"=>[
					"sys.upload.sts"=>[
								"name"=>"获取认证参数(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
									],
								"info"=>'
									dir 这个参数，服务端只返回“nnh/images/2017-03-15/1489557805svjs9762”,app端接口到以后，再拼上图片的后缀，上传完成以后把最终的图片路径给回服务端，最终图片路径“nnh/images/2017-03-15/1489557805svjs9762.png”
								',
							],
					
				],

			],
			"withdraw"=>[
				"name"=>"提现",
				"api"=>[
					"withdraw.user.getinfo"=>[
						"name"=>"获取绑定的信息",
						"param"=>[
								["mtoken","用户mtoken","string","必填"],
								
							],
						"info"=>"
						account 支付宝账号<br>
                    	name 支付宝名字<br>
						",
					],
					/*
					"withdraw.user.addinfo"=>[
						"name"=>"绑定提现信息",
						"param"=>[
								["mtoken","用户mtoken","string","必填"],
								["account","支付宝账号","string","必填"],
								["name","昵称","string","必填"],
								
							],
						"info"=>"
						",
					],
					*/
					"withdraw.user.withdraw"=>[
						"name"=>"提现申请",
						"param"=>[
								["mtoken","用户mtoken","string","必填"],
								["account","支付宝账号","string","必填"],
								["name","支付宝姓名","string","必填"],
								["money","提现金额","string","必填"],
							],
						"info"=>"
						",
					],

					"withdraw.user.list"=>[
						"name"=>"提现列表",
						"param"=>[
								["mtoken","用户mtoken","string","必填"],
								["page","第几页：1,2,3默认第1页","int","非必填"],
							],
						"info"=>"
							account 支付宝账号<br>
                    		name 支付宝名字<br>
                    		money 提现金额 <br>
                    		status 提现状态0未处理10已支付20失败<br>
                    		addtime 提现时间<br>

						",
					],
				],

			],

			

			"Push"=>[
				"name"=>"推送",
				"api"=>[
					
				],

			],
			"Saleway"=>[
				"name"=>"售卖专区",
				"api"=>[
					// "Saleway.index.cash"=>[
					// 			"name"=>"现金专区(已开发)",
					// 			"param"=>[
					// 					["mtoken","用户mtoken","string","非必填"],
					// 					["page","当前页码默认为1","int","非必填"]
					// 				],
					// 			"info"=>"
					// 				code 200 --操作成功 <br>
     //                				banner ---轮播图内容<br>
     //                				saleWay --- 分类信息（售卖方式） <br>
     //                				proData---- 商品列表信息<br>
     //                				category----商品分类信息
					// 			",
					// 		],
					"Saleway.index.index"=>[
								"name"=>"现金专区获取所有商品列表(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["page","当前页码默认为1","int","非必填"],
										["type","售卖类型（2现金专区，3现金加牛豆专区，4牛豆专区）",'int',"非必填"]
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				proData---- 商品列表信息<br>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;bullamount---商品牛积分<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/> 
								",
							],
					"Saleway.search.index"=>[
								"name"=>"现金专区获取所有商品列表(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["page","当前页码默认为1","int","非必填"],
										['keywords',"关键词搜索","string","必填"],
										['cid',"分类id","int","非必填"],
										["type","售卖类型（2现金专区，3现金加牛豆专区，4牛豆专区）",'int',"非必填"]
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				proData---- 商品列表信息<br>
                    				&nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---商品名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;bullamount---商品牛积分<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/> 
								",
							],

				]
			],
			"stobusiness"=>[
				"name"=>"实体店",
				"api"=>[
					"stobusiness.index.index"=>[
								"name"=>"实体店首页接口",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["city_id","城市编码","string","必填"],
										["lngx","经度","string","非必填"],
										["laty","纬度","string","非必填"],
										["page","页码","int","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				banner ---轮播图内容<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;city_id---所属地区编码<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;bname---广告名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---图片链接<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>
                    				category ---分类<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;categoryid---分类id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;categoryname---分类名<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;category_icon---分类图标<br/> 

                    				businessList--实体列表<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;businessid ---实体店id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;businessname ---实体店名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;businesslogo ---实体店logo<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;categoryid ---分类id<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;categoryname ---分类名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;area ---所在地区名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;area_code ---所在地区编码<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;lngx ---经度<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;laty ---纬度<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;scores ---店铺评分<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;busstartime ---营业开始时间<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;busendtime ---营业结束时间<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;isdelivery ---是否送货上门1是-1否<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;delivery----起送费<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;isbusiness--1正在营业-1不在营业时间<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;reutnproportion--返现比例整数计算式要除以100<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;distance--距离<br/>
                    				adv---活动广告<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;city_id---城市编码<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;city---城市名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bname---广告名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;thumb---图片链接<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>

								",
							],
					"stobusiness.index.getCity"=>[
								"name"=>"切换城市(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["city_id","城市编码","string","必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				local ---当前城市<br/>
                    				&nbsp;&nbsp;&nbsp;city_id ---地区编号<br/>
                    				&nbsp;&nbsp;&nbsp;city ---地区名称<br/>
                    				&nbsp;&nbsp;&nbsp;sort ---排序<br/>
                    				&nbsp;&nbsp;&nbsp;businesscount ---开通实体店数量<br/>
                    				host ---热门城市列表<br/>
                    				&nbsp;&nbsp;&nbsp;city_id ---地区编号<br/>
                    				&nbsp;&nbsp;&nbsp;city ---地区名称<br/>
                    				&nbsp;&nbsp;&nbsp;sort ---排序<br/>
                    				&nbsp;&nbsp;&nbsp;businesscount ---开通实体店数量<br/>
								",
							],
					"stobusiness.index.setGatherMonet"=>[
								"name"=>"设置收款金额--商家收款(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["business_code","店铺平台号","string","必填"],
									],
								"info"=>"
									code 200  --操作成功 <br/>
                    				businessname --商家名称<br/> 
                    				business_code --平台号<br/>
                    				discountremark --优惠说明<br/> 
								",
							],

					"stobusiness.index.getStoPayCode"=>[
								"name"=>"获取付款二维码--设置金额弹出二维码(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["business_code","店铺平台号","string","必填"],
										["amount","消费金额","float","必填"],
										["noinvamount","不参数消费金额","float","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				businessname --商家名称<br/> 
                    				business_code --平台号<br/>
                    				amount --消费金额<br/> 
                    				noinvamount--不参数消费金额<br/> 
                    				url--二维码地址<br/>	
								",
							],
					"stobusiness.index.businessGathering"=>[
								"name"=>"我要收款--商家收款页面--用户付款款(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["business_code","店铺平台号","string","必填"],
									],
								"info"=>"
									code 200  --操作成功 <br/>
                    				businessname --商家名称<br/> 
                    				business_code --平台号<br/>
                    				url--二维码地址<br/>
                    				flowData--订单流水<br>
                    				&nbsp;&nbsp;&nbsp;&nbsp;pay_code--流水号<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;addcustomername--操作员姓名<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;payamount--付款总额<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;paytime--付款时间<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;remark--备注说明<br/>

								",
							],
					"stobusiness.index.setPayMonet"=>[
								"name"=>"设置付款金额--用户付款款(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","必填"],
										["business_code","店铺平台号","string","必填"],
									],
								"info"=>"
									code 200  --操作成功 <br/>
                    				businessname --商家名称<br/> 
                    				business_code --平台号<br/>
                    				discountremark --优惠说明<br/> 
								",
							],
							
					"stobusiness.search.index"=>[
								"name"=>"实体店列表页获取商品列表(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["city_id","城市编码","string","非必填"],
										["lngx","经度","string","非必填"],
										["laty","纬度","string","非必填"],
										["distance","距离","string","非必填(1KM,2KM,3KM,5KM,10KM)"],
										["metro_id","地铁id","string","非必填"],
										["keywords","关键词（分类名称或者商家名称）","string","非必填"],
										["page","当前页码默认为1","string","非必填"],
										['iswifi',"是否免费wifi1是-1否","int","非必填"],
										['isparking',"是否免费停车1是-1否","int","非必填"],
										['isdelivery',"是否送货上门-1否","int","非必填"],
										['isbusiness',"1正在营业-1不在营业","int","非必填"],
										['order',"智能排序","string","非必填"],
										['categoryid',"分类id","int","非必填"],
										["area_code","区域编码","string","非必填"]
									],
								"info"=>"
									code 200 --操作成功 <br/>
									order----智能排序salecount(销量最好)scores(评分最高)reutnproportion(折扣最优)<br/>
									筛选isparking ---'是否免费停车1是-1否' 、iswifi---是否免费wifi1是-1否、isdelivery---是否送货上门-1否
									isbusiness--'1正在营业-1不在营业'--<br/>
                    				businessid ---实体店id<br/>
                    				businessname ---实体店名称<br/>
                    				businesslogo ---实体店logo<br/>
                    				categoryid ---分类id<br/>
                    				categoryname ---分类名称<br/>
                    				area ---所在地区名称<br/>
                    				area_code ---所在地区编码<br/>
                    				lngx ---经度<br/>
                    				laty ---纬度<br/>
                    				scores ---店铺评分<br/>
                    				busstartime ---营业开始时间<br/>
                    				busendtime ---营业结束时间<br/>
                    				isdelivery ---是否送货上门1是-1否<br/>
                    				delivery----起送费<br/>
                    				isbusiness--1正在营业-1不在营业时间<br/>
                    				reutnproportion--返现比例整数计算式要除以100<br/>
								",
							],
					"stobusiness.search.bannerList"=>[
								"name"=>"实体店列表页获取banner(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["city_id","城市编码","string","必填"],
										["categoryid","分类id","string","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;city_id---所属地区编码<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;city---所属地区名称<br/>
                    				&nbsp;&nbsp;&nbsp;&nbsp;bname---广告名称<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;thumb---图片链接<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;urltype---跳转类型1为跳原生app2为跳转H5等<br/> 
                    				&nbsp;&nbsp;&nbsp;&nbsp;url--跳转链接有可能是H5页面url也有可能是id具体看app跳转方式<br/>
								",
							],
					"stobusiness.search.categoryList"=>[
								"name"=>"实体店列表页获取分类(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["city_id","城市编码","string","非必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				category---分类<br/>
                    				categoryid---分类id<br/>
                    				categoryname--分类名称<br/>
                    				soncate--子分类
								",
							],

					"stobusiness.search.getlocalcity"=>[
								"name"=>"实体店列表页城市列表(已开发)",
								"param"=>[
										["mtoken","用户mtoken","string","非必填"],
										["city_id","城市编码","string","必填"],
									],
								"info"=>"
									code 200 --操作成功 <br/>
                    				city_id---地区编码<br/>
                    				areaname--地区名称<br/>
				                    parentid--上级id<br/>
								",
							],
                    "stobusiness.physicalShop.shopDetails"=>[
                        "name"=>"实体店详情页",
                        "param"=>[
                            ["shop_id","店铺ID","string","必填"],
                            ["mtoken","用户mtoken","string","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									 id -- 商家ID<br/>
									 business_code -- 商家平台号<br/>
									 businessname -- 商家名称<br/>
									 mobile -- 手机号<br/>
									 address -- 地址<br/>
									 description -- 描述<br/>
									 typeid -- 所属类型ID<br/>
									 typename -- 所属类型名称<br/>
									 discount -- 折扣(%)<br/>
									 scorescount -- 评分次数<br/>
									 delivery -- 起送费用<br/>
									 actualfreight--配送费<br/>
									 productcount -- 商品数量<br/>
									 servicetel -- 一维数组 例如： array('1866493838**')<br/>
									 categoryid -- 所属分类<br/>
									 categoryname -- 分类名称<br/>
									 area -- 所在地区名称<br/>
									 area_code -- 地区ID<br/>
									 lngx -- 经度<br/>
									 laty -- 纬度<br/>
									 salecount -- 销售量<br/>
									 hits -- 浏览量<br/>
									 fanscount -- 粉丝数<br/>
									 scores -- 店铺评分（1-5）<br/>
									 isparking -- 是否免费停车1是-1否<br/>
									 iswifi -- 是否免费wifi1是-1否<br/>
									 isdelivery -- 是否送货上门-1否<br/>
									 work_time -- 工作时间<br/>
									 recommendPro--推荐商品列表<br/>
									 businessid -- 商家ID<br/>
								     businessname -- 店铺名称<br/>
									categoryname -- 分类名称<br/>
									thumb -- 图片<br/>
									prouctprice -- 价格<br/>
									is_recommend -- 是否推荐是否推荐，1：推荐；0：不推荐<br/>
									businessname -- 店铺名称<br/>
									productunit -- 单位<br/>
									iscollect--是否收藏（1为已收藏-1没有收藏）<br/>

								",
                    ],
                    "stobusiness.physicalShop.stoAlbum"=>[
                        "name"=>"实体店详相册",
                        "param"=>[
                            ["businessid","店铺ID","string","必填"],
                            ["mtoken","登录token值","string","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									business_id -- 商家ID<br/>
									thumb -- 图片<br/>

								",
                    ],
                    "stobusiness.physicalShop.addStoAlbum"=>[
                        "name"=>"添加实体店详相册",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["thumb","图片路径","string","必填"],
                            ["businessid","商家ID","int","非必填"],
                            ["roleid","角色id","int","非必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>

								",
                    ],
                    "stobusiness.physicalShop.delStoAlbum"=>[
                        "name"=>"删除实体店相册",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["id","图片id","int","必填"],
                            ["businessid","商家ID","int","非必填"],
                            ["roleid","角色id","int","非必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>

								",
                    ],
                    "stobusiness.physicalShop.stoImages"=>[
                        "name"=>"实体店轮播图",
                        "param"=>[
                            ["businessid","店铺ID","string","必填"],
                            ["mtoken","登录token值","string","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									business_id -- 商家ID<br/>
									thumb -- 图片<br/>

								",
                    ],
                    "stobusiness.physicalShop.addStoImages"=>[
                        "name"=>"添加实体店详相册",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["thumb","图片路径","string","必填"],
                            ["businessid","商家ID","int","非必填"],
                            ["roleid","角色id","int","非必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>

								",
                    ],
                    "stobusiness.physicalShop.delStoImages"=>[
                        "name"=>"实体店轮播图",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["id","图片id","int","必填"],
                            ["businessid","商家ID","int","非必填"],
                            ["roleid","角色id","int","非必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>

								",
                    ],
                  
                    "stobusiness.physicalShop.stoBusinessLogo"=>[
                        "name"=>"实体店主图",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["businessid","商家ID","int","非必填"],
                            ["roleid","角色id","int","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									business_id -- 商家ID<br/>
									businesslogo -- 图片<br/>

								",
                    ],
                    "stobusiness.physicalShop.alterStoBusinessLogo"=>[
                        "name"=>"修改实体店主图",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["thumb","店铺主图路径","string","必填"],
                            ["businessid","商家ID","int","非必填"],
                            ["roleid","角色id","int","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									business_id -- 商家ID<br/>
									businesslogo -- 图片<br/>

								",
                    ],
                    "stobusiness.physicalShop.sotCodeUrl"=>[
                        "name"=>"实体店详情二维码",
                        "param"=>[
                            ["businessid","店铺ID","string","必填"],
                            ["mtoken","登录token值","string","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									url--二维码地址<br/>

								",
                    ],
                    "stobusiness.physicalShop.stoProductList"=>[
                        "name"=>"实体店商品列表",
                        "param"=>[
                            ["businessid","店铺ID","string","必填"],
                            ["mtoken","登录token值","string","非必填"],
                            ["page","分页页码","int","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									businessid -- 商家ID<br/>
									businessname -- 店铺名称<br/>
									categoryname -- 分类名称<br/>
									thumb -- 图片<br/>
									prouctprice -- 价格<br/>
									is_recommend -- 是否推荐是否推荐，1：推荐；0：不推荐<br/>
									businessname -- 店铺名称<br/>
									productunit -- 单位<br/>

								",
                    ],
                    "stobusiness.physicalShop.ownStoProductList"=>[
                        "name"=>"实体店商品列表（查看自己）",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["page","分页页码","int","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									businessid -- 商家ID<br/>
									businessname -- 店铺名称<br/>
									categoryname -- 分类名称<br/>
									thumb -- 图片<br/>
									prouctprice -- 价格<br/>
									is_recommend -- 是否推荐是否推荐，1：推荐；0：不推荐<br/>
									businessname -- 店铺名称<br/>
									productunit -- 单位<br/>

								",
                    ],
                    "stobusiness.physicalShop.addStoProduct"=>[
                        "name"=>"添加实体店商品",
                        "param"=>[
                            ["productname","商品名称","string","必填"],
                            ["categoryid","分类id","int","必填"],
                            ["thumb","商品图片","string","必填"],
                            ["prouctprice","商品价格","float","必填"],
                            ["productunit","商品单位","string","非必填"],
                            ["enable","是否显示-1不显示，1显示","非必填"],
                            ["is_recommend","是否推荐到首页1：推荐；0：不推荐","非必填"],
                            ["mtoken","登录token值","string","必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>
									

								",
                    ],
                    "stobusiness.physicalShop.editStoProduct"=>[
                        "name"=>"修改实体店商品",
                        "param"=>[
                            ["id","商品id","int","必填"],
                            ["productname","商品名称","string","非必填"],
                            ["categoryid","分类id","int","非必填"],
                            ["thumb","商品图片","string","非必填"],
                            ["prouctprice","商品价格","float","非必填"],
                            ["productunit","商品单位","string","非必填"],
                            ["enable","是否显示-1不显示，1显示","非必填"],
                            ["is_recommend","是否推荐到首页1：推荐；0：不推荐","非必填"],
                            ["mtoken","登录token值","string","必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>
									

								",
                    ],
                     
                    "stobusiness.physicalShop.delStoProduct"=>[
                        "name"=>"删除实体店商品",
                        "param"=>[
                            ["id","商品ID","int","必填"],
                            ["mtoken","登录token值","string","必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>
									

								",
                    ],
                    "stobusiness.physicalShop.stoProductCategoryList"=>[
                        "name"=>"实体商品分类列表",
                        "param"=>[
                            ["businessid","店铺ID","string","必填"],
                            ["mtoken","登录token值","string","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									id -- 分类id<br/>
									parent_id -- 上级分类id<br/>
									name -- 分类名称<br/>
								",
                    ],
                    "stobusiness.physicalShop.ownStoProductCategoryList"=>[
                        "name"=>"实体商品分类列表（查看自己）",
                        "param"=>[
                            ["mtoken","登录token值","string","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
									id -- 分类id<br/>
									parent_id -- 上级分类id<br/>
									name -- 分类名称<br/>
								",
                    ],
                    "stobusiness.physicalShop.addStoProductCategory"=>[
                        "name"=>"添加实体商品分类",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["name","分类名称","string","必填"],
                            ["parent_id","上级分类id","int","非必填"],
                        ],
                        "info"=>"
									code 200 --操作成功 <br/>
								
								",
                    ],
                    "stobusiness.physicalShop.editStoProductCategory"=>[
                        "name"=>"修改实体商品分类",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["name","分类名称","string","必填"],
                            ["parent_id","上级分类id","int","非必填"],
                            ["id","分类id","int","必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>
								",
                    ],
                    "stobusiness.physicalShop.delStoProductCategory"=>[
                        "name"=>"删除实体商品分类",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["id","分类id","int","必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>
								",
                    ],
                    "stobusiness.physicalShop.setRecommend"=>[
                        "name"=>"推荐商品取消推荐商品",
                        "param"=>[
                            ["mtoken","登录token值","string","必填"],
                            ["id","商品id","int","必填"],
                            ["recommend","是否推荐，1：推荐；0：不推荐","int","必填"],
                        ],
                        "info"=>"
								code 200 --操作成功 <br/>
								",
                    ],
                    "stobusiness.physicalShop.commentList"=>[
                        "name"=>"获取评论列表",
                        "param"=>[
                            ["shop_id","店铺ID","int","必填"],
                            ["page","分页页码","int","非必填"],
                        ],
                        "info"=>"
                             code ---- 200 成功
                             id-- 评论id<br/>   
                             scores-- 用户评分<br/>   
                             isanonymous-- 是否匿名用户<br/>   
                             addtime-- 添加时间<br/>   
                             frommemberid-- 评论人ID<br/>   
                             frommembername-- 评论人名称<br/>   
                             headpic-- 评论人图像<br/>   
                             content-- 评论内容<br/> 
                             img_arr<br/>
                             &nbsp;&nbsp;&nbsp;&nbsp;id --唯一标识id<br/>
                             &nbsp;&nbsp;&nbsp;&nbsp;evaluate_id --评价id<br/>
                             &nbsp;&nbsp;&nbsp;&nbsp;thumb --图片<br/>
                        ",
                    ],
                    "stobusiness.physicalShop.writeComment"=>[
                        "name"=>"用户评论",
                        "param"=>[
                            ["order_no","订单号","string","必填"],
                            ["shop_id","店铺id","int","必填"],
                            ["scores","评分(1-5)","int","必填"],
                            ["is_anonymous","是否匿名","int","非必填"],
                            ["mtoken","登录token值","string","必填"],
                            ["frommembername","用户名","string","非必填"],
                            ["content","评论内容","string","必填"],
                            ["thumb","评论图片多张用逗号隔开","string","非必填"],
                        ],
                        "info"=>"
                             code 200 成功
                        ",
                    ],
                    "stobusiness.physicalShop.writeProposal"=>[
                        "name"=>"用户投诉",
                        "param"=>[
                            ["shop_id","店铺ID","int","必填"],
                            ["mtoken","登录token值","int","必填"],
                            ["content","投诉内容","string","必填"],
                        ],
                        "info"=>"
                             code 200 成功
                        ",
                    ],
                    "stobusiness.index.addpayfollow"=>[
                        "name"=>"支付订单接口（已开发）",
                        "param"=>[
	                    	
                            ["managerid","店铺管理用户id","int","必填"],
                            ["amount","支付金额","int","必填"],
                            ["noinvamount","不参与优惠金额","int","必填"],
                            ['business_code',"商家平台号","string","必填"],
                            ['items',"商品信息","json,内容包括：商品ID（id），商品数量（num）","非必填"],
                            ['bonusamount','用户奖励金','int','必填'],
                            ['service_code','服务员号','int','非必填'],
                            ['sign',"[签名=md5(按业务字段排序(amount+bonusamount+business_code+items+managerid+noinvamount+service_code)+私钥)][string][必填]","string","必填"],
                            ['mtoken','登录token值','string',"必填"]
                        ],
                        "info"=>"
                             code 200 成功
                        ",
                    ], 
                    "stobusiness.index.stoOrderDetail"=>[
                        "name"=>"支付订单详情接口（已开发）",
                        "param"=>[
	                    	
                            ["managerid","店铺管理用户id","int","必填"],
                            ['business_code',"商家平台号","string","必填"],
                            ['mtoken','登录token值','string',"必填"],
                            ['pay_code','订单流水号','string',"必填"],
                        ],
                        "info"=>"
                            code 200 成功
                            pay_code--订单号<br/>
                            businessid---商家id<br/>
                            shop_id---商家id评论需要<br/>
                            businessname---商家名称<br/>
                            customerid--支付订单客户id<br/>
                            status--订单状态0未支付1已支付2支付异常<br/>
                            amount--需要支付总额<br/>
                            payamount--实际支付总额<br/>
                            paytime--支付订单时间<br/>
                            addtime--生成订单时间<br/>
                            returnBullamont--返回牛豆数量<br/>
		
                        ",
                    ],
                   
					"stobusiness.physicalShop.storeList" => [
				        "name" => "实体店门店列表(包括搜索)",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["selfRoleType", "角色类型值", "int", "必填"],
				            ["storename", "门店名称", "string", "非必填"],
				            ["page", "页数", "int", "非必填"],
				        ],
				        "info" => "code 200 -- 操作成功",
				    ],
				    "stobusiness.physicalShop.addStore" => [
				        "name" => "添加实体店子店",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["selfRoleType", "角色类型值", "int", "必填"],
				            ["mobile", "手机号码", "string", "必填"],
				            ["valicode", "md5(验证码)", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.physicalShop.storeInfo" => [
				        "name" => "实体店子店信息",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["id", "子店id值(列表的id值)", "int", "必填"]
				        ],
				        "info" => "code 200 --操作成功
				            amountInfo                  余额<br>
				            &nbsp;&nbsp;cashamount      牛票<br>
				            &nbsp;&nbsp;profitamount    牛粮<br>
				            &nbsp;&nbsp;bullamount      牛豆<br>
				            stoFlow                     交易流水奖励<br>
				            stoShare                    交易分享奖励<br>
				            stoFlowCom                  营业总额<br>
				            stoUserCount                牛店用户数量<br>
				            stoUserId                   牛店用户id值<br>",
				    ],
				],
			],
			"pay"=>[
                "name"=>"支付",
                "api"=>[
                	"pay.pay.checkbalance" => [
				        "name" => "判断用户账号余额是否足够",
				        "param" => [
				            ["mtoken", "用户token", "string", "必填"],
				            ["orderno","订单编号","string","必填"],
				        ],
				        "info" => "
				        	返回
				        	balance      判断余额是否足够 1表示余额足够 0表示余额不足<br>
				        	issetpaypwd  是否设置了支付密码 1设置 0未设置<br>
				        ",
				    ],
                    "pay.pay.request"=>[
                        "name"=>"第三方支付请求",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                            ["orderno","订单编号","string","必填"],
                            ["pay_type","支付方式:weixin,ali,allinpay_weixin,allinpay_ali,allinpay_quick","string","必填"],
                        ],
                        "info"=>"
                        		allinpay_ali  通联支付宝扫码支付<br>
                        		接口返回：<br><br>
                        		param:payinfo  跳转地址，浏览器打开该地址，调起支付宝app<br>
                        		<br>
                        		allinpay_quick 快捷支付<br>
                        		接口返回：<br>
                        		param:需要的参数<br>
                        		<br>
								",
                    ],
                    "pay.pay.storequest"=>[
                        "name"=>"第三方支付请求-实体店",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                            ["orderno","订单编号","string","必填"],
                            ["pay_type","支付方式:weixin,ali","string","必填"],
                        ],
                        "info"=>"
                        
								",
                    ],
                    "pay.pay.rerequest"=>[
                        "name"=>"第三方支付请求-充值",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                            ["orderno","订单编号","string","必填"],
                            ["pay_type","支付方式:weixin,ali","string","必填"],
                        ],
                        "info"=>"
                        
								",
                    ],
                    "pay.pay.balancepay"=>[
                        "name"=>"余额支付",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                        	['paypwd',"支付密码=md5(支付密码)", "必填"],
                            ["orderno","订单编号","string","必填"],
                        ],
                        "info"=>"
                        	code:50001  支付密码未设置<br>
                        	code:50002  支付密码不正确<br>
								",
                    ],

                    "pay.pay.paystatus"=>[
                        "name"=>"支付结果",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                            ["orderno","订单编号","string","必填"],
                        ],
                        "info"=>"
                        	pay_status 支付状态 0未支付 1或者2已支付<br>
                        	pay_money 支付金额<br>
                        	pay_time 支付时间<br>
                        	pay_type 支付类型<br>
								",
                    ],
                    
                ],
            
            ],
            "exprss"=>[
                "name"=>"快递单号查询",
                "api"=>[
                    "order.express.getExpress"=>[
                        "name"=>"查询最新的一条快递信息",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                            ["express_number","快递单号","string","必填"],
                            ["company_code","快递公司名称","string","必填"],
                        ],
                        "info"=>"
                        state	快递单当前的状态 ：　 <br>
						nu   物流单号<br>
						time 时间<br>
						context 物流内容<br>
								",
                    ],
                    "order.express.getExpressList"=>[
                        "name"=>"获得快递信息列表",
                        "param"=>[
                        	["mtoken", "用户mtoken", "string", "必填"],
                            ["express_number","快递单号","string","必填"],
                            ["company_code","快递公司名称","string","必填"],
                        ],
                        "info"=>"
                        state	快递单当前的状态 ：　 <br>
						nu   物流单号<br>
						time 时间<br>
						context 物流内容<br>
								",
                    ]
                ],
            
            ],
            "msg"=>[
				"name"=>"消息--融云",
				"api"=>[
				    "msg.index.gettoken" => [
				        "name" => "获取融云token",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>"
				                
				    ],
					"msg.index.getbusinesstoken" => [
				        "name" => "获取商家的融云token",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["businessid", "供应商ID", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>"
				                
				    ],
				    "msg.index.getuserinfo" => [
				        "name" => "获取用户信息",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["userid", "融云userID", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>"
				                
				    ],
				],

			],
			"sys"=>[
				"name"=>"系统接口",
				"api"=>[
				    "sys.index.versionupdate" => [
				        "name" => "判断是否有版本更新提示",
				        "param" => [
				            ["version", "当前app的版本号", "string", "必填"],
				        ],
				        "info" => "返回 update 1 有更新 0没有更新 ，url 跳转的地址"
				                
				    ],
					
				],

			],
			"retururl"=>[
				"name"=>"域名返回接口",
				"api"=>[
				    "index.index.returnurl" => [
				        "name" => "返回域名",
				        "param" => [
				            ["mtoken", "登录token值", "string", "非必填"],
				        ],
				        "info" => "返回 mobileurl---注册协议信息"
				                
				    ],
					
				],

			],
			"apilog"=>[
				"name"=>"api错误日志",
				"api"=>[
				    "sys.log.errorlog" => [
				        "name" => "返回域名",
				        "param" => [
				            ["content", "错误信息", "string", "必填"],
				        ],
				        "info" => "返回 mobileurl---注册协议信息"
				                
				    ],
					
				],

			],
			"test"=>[
				"name"=>"测试专用模块",
				"api"=>[
				    "demo.test.privatekey" => [
				        "name" => "发送验证码时需要的privatekey值",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				        ],
				        "info" => "code 200 --操作成功<br>
				                    privatekey  加密值",
				    ],
					"demo.test.valicode"=>[
								"name"=>"生成注册登录的valicode校验码",

								"param"=>[
										["code","校验码","int","必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				prolist----商品列表<br/>
								",
							],
					"demo.es.bulkinsert"=>[
								"name"=>"生成注册登录的valicode校验码",
								"param"=>[
										["code","校验码","int","必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				prolist----商品列表<br/>
								",
							],
					"demo.test.addorder"=>[
								"name"=>"生成注册登录的valicode校验码",

								"param"=>[
										["address_id","物流ID","string","必填"],
										["items","商品明细","json,内容包括：商品ID（productid），skuID（skuid），商品数量（productnum），商品在购物车中的ID（cartid），商品备注（remark）","必填"],
									],
								"info"=>"
									code 200 --操作成功 <br>
                    				prolist----商品列表<br/>
								",
					],
				    "demo.test.valicodephone" => [
				        "name" => "检测手机号码",
				        "param"=>[
				            ["mobile","手机号码","string","必填"],
				        ],
				    ],


				    "stobusiness.index.updateMgData" => [
				        "name" => "快速添加",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],

				    "user.test.quickBus" => [
				        "name" => "快速添加",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],

				    "user.test.quickBus" => [
				        "name" => "快速添加",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],
				    "product.index.updateIntoProSpecVale" => [
				        "name" => "快速添加",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],
				    "demo.test.mqtest" => [
				        "name" => "mq测试",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],
				    "demo.test.gettokenbuild1" => [
				        "name" => "创建消息用户1",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],
				    "demo.test.gettokenbuild2" => [
				        "name" => "创建消息用户2",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],
				    "demo.test.gettokenbuild3" => [
				        "name" => "创建消息用户3",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],
				    "demo.test.gettokenbuild4" => [
				        "name" => "创建消息用户4",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				            ["company", "公司", "string", "必填"],
				        ],
				    ],

				    "demo.test.alibill" => [
				        "name" => "ali对账单",
				        "param" => [
				            ["mobile", "手机号码", "string", "必填"],
				        ],
				        "info" => "",
				    ],


				    "demo.test.getgiveuser" => [
				        "name" => "检测是否赠送",
				        "param" => [
				            ["customerid", "用户id", "string", "必填"],
				            ["role", "角色值(只校验2牛人  3牛创客 8牛达人)", "int", "必填"],
				        ],
				    ],
				],

			],
			"SysAdvert"=>[
				"name"=>"启动广告",
				"api"=>[
				    "Sys.SysAdvert.startupBanner" => [
				        "name" => "启动广告",
				        "param" => [
				            ["mtoken", "登录token值", "string", "非必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
                    		bname----banner名称<br/>
                    		thumb----banner链接<br/>
                    		urltype----跳转类型<br/>
                    		url----跳转地址<br/>
                    		time----显示时间单位秒<br/>
				        "
				                
				    ],
					
				],

			],
			"StoBusinessTakeOut"=>[
				"name"=>"外卖订单",
				"api"=>[
				    
				    "stobusiness.Shoppingcart.goodsList"=>[
                        "name"=>"实体店购物车商品列表",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['businessid',"商家ID","int","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                            businessid---商家id<br/>
			                productid---商品id<br/>
			                productnum---商品数量<br/>
			                productname---商品名称<br/>
			                prouctprice---商品价格<br/>
                        ",
                    ],
                    "stobusiness.Shoppingcart.addgoods"=>[
                        "name"=>"实体店购物车添加商品",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['productid',"商品id","int","必填"],
                            ['productnum',"商品数量","int","必填"],
                            ['businessid',"商家id",'int',"必填"]
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        
                        ",
                    ],
                    "stobusiness.Shoppingcart.updategoods"=>[
                        "name"=>"实体店购物车修改商品信息",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['productid',"商品id","int","必填"],
                            ['productnum',"商品数量","int","必填"],
                            ['businessid',"商家id",'int',"必填"]
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        
                        ",
                    ],
                    "stobusiness.Shoppingcart.deletegoods"=>[
                        "name"=>"实体店购物车是删除商品信息",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['cartid',"购物车id","int","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        
                        ",
                    ],
                    "stobusiness.Shoppingcart.deleteallgoods"=>[
                        "name"=>"实体店购物车清空购物车",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        
                        ",
                    ],
                    "stobusiness.StoUserOrder.orderlist"=>[
                        "name"=>"实体店订单列表",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['orderlisttype',"订单列表类型1全部2待评价3退款售后","int","必填"],
                            ['page',"当前页码默认为1","int","非必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                            orderno---订单编号<br/>
                            addtime---下单时间<br/>
                            actualfreight---配送费<br/>	
                            productcount---商品总数<br/>	
                            productamount---商品总价格<br/>	
                            totalamount---订单总额<br/>	
                            orderstatus---订单状态0待付款1已付款待接单2已接单待配送3已配送4已送达5订单完结6拒绝接单7取消<br/>	
                            return_status---退款状态，0无退款1退款中2退款完成<br/>	
                            businessname---商家名称<br/>
                            productname---商品名称<br/>	
                            orderstatus_str---订单状态说明<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.orderdetail"=>[
                        "name"=>"实体店订单详情",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['orderno',"订单编号","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                            orderno---订单编号<br/>
                            addtime---下单时间<br/>
                            actualfreight---配送费<br/>	
                            totalamount---订单总额<br/>	
                            orderstatus---订单状态0待付款1已付款待接单2已接单待配送3已配送4已送达5订单完结6拒绝接单7取消<br/>	
                            return_status---退款状态，0无退款1退款中2退款完成<br/>	
                            businessname---商家名称<br/>
                            productname---商品名称<br/>	
                            orderstatus_str---订单状态说明<br/>
                            receipt_address---收货信息<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;realname---真实姓名<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;mobile---手机号码<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;address---收货地址<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;city---城市名称<br/>
                            orderitem----商品信息<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;thumb---商品图片<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;productnum---商品数量<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.showorder"=>[
                        "name"=>"实体店提交订单前的订单详情",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ["cartitemids","cartitemids(多个cartitemid用','分隔)","string","cartitemids和skuid二选一"],
							["productarr","productarr数组包含商品id和数量[{id:1,num:2},{id:2,num:1}]","string","cartitemids和productarr二选一"],
							["logisticsid","收货地址ID","string","非必填"],
							["cusdelivertime","要求送达时间","string","非必填"]
                        ],
                        "info"=>"
                            code 200 成功<br/>
                            businessname---商家名称<br/>
                            productname---商品名称<br/>	
                            orderstatus_str---订单状态说明<br/>
                            receipt_address---收货信息<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;realname---真实姓名<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;mobile---手机号码<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;address---收货地址<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;city---城市名称<br/>
                            orderitem----商品信息<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;productid---商品id<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;productname---商品名称<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;thumb---商品图片<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;productnum---商品数量<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;prouctprice---商品价格<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.addorder"=>[
                        "name"=>"实体店提交订单",
                        "param"=>[
                           	["mtoken","用户mtoken","string","必填"],
							["sign","签名=md5(按业务字段排序(address_id+bonusamount+cusdelivertime+items+remark)+私钥)","string","必填"],
							["address_id","物流ID","string","必填"],
							['bonusamount','用户奖励金','int','必填'],
							["items","商品明细","json,内容包括：商品ID（id），商品数量（num），商品在购物车中的ID（cartid）","必填"],
							["remark","备注","string","非必填"],
							["cusdelivertime","要求送达时间","string","非必填"]
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.cancelsorder"=>[
                        "name"=>"实体店取消订单",
                        "param"=>[
                          	["mtoken","用户mtoken","string","必填"],
							["orderno","订单编号","string","必填"],
							["cancelreason","取消订单原因","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.refundorder"=>[
                        "name"=>"实体店用户订单申请退款",
                        "param"=>[
                          	["mtoken","用户mtoken","string","必填"],
							["orderno","订单编号","string","必填"],
							["cancelreason","取消订单原因","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        ",
                    ],
                     "stobusiness.StoUserOrder.cancelrefundorder"=>[
                        "name"=>"实体店用户订单取消申请退款",
                        "param"=>[
                          	["mtoken","用户mtoken","string","必填"],
							["orderno","订单编号","string","必填"],
							["cancelreason","取消订单原因","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.onemoreorder"=>[
                        "name"=>"实体店用户订单再来一单",
                        "param"=>[
                          	["mtoken","用户mtoken","string","必填"],
							["orderno","订单编号","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.confirmationorder"=>[
                        "name"=>"实体店用户订单确认送达",
                        "param"=>[
                          	["mtoken","用户mtoken","string","必填"],
							["orderno","订单编号","string","必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                        ",
                    ],
                    "stobusiness.StoUserOrder.deleteorder" => [
				        "name" => "用户删除订单",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
                     "stobusiness.StoBusinessOrder.orderlist"=>[
                        "name"=>"实体店订单列表",
                        "param"=>[
                            ["mtoken","用户mtoken","string","必填"],
                            ['orderlisttype',"订单列表类型1已付款待接单2已接单待配送3已配送4已消费5退款订单","int","必填"],
                            ['page',"当前页码默认为1","int","非必填"],
                        ],
                        "info"=>"
                            code 200 成功<br/>
                            orderno---订单编号<br/>
                            addtime---下单时间<br/>
                            actualfreight---配送费<br/>	
                            productcount---商品总数<br/>	
                            productamount---商品总价格<br/>	
                            totalamount---订单总额<br/>	
                            orderstatus---订单状态0待付款1已付款待接单2已接单待配送3已配送4已送达5订单完结6拒绝接单7取消<br/>	
                            return_status---退款状态，0无退款1退款中2退款完成<br/>	
                            businessname---商家名称<br/>
                            productname---商品名称<br/>	
                            orderstatus_str---订单状态说明<br/>
                            returnamount---退款金额<br/>
                            returnreason---退款原因<br/>
                            realname---收货人姓名<br/>
                            mobile---收货人电话<br/>
                            address---收货人地址<br/>
                        ",
                    ],
                    
                    "stobusiness.StoBusinessOrder.refuseorder" => [
				        "name" => "商家拒绝接单",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				            ["refusereason", "拒绝理由", "string", "非必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.takingorder" => [
				        "name" => "商家接单",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.deliveryorder" => [
				        "name" => "商家配送订单",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.refusereturnorder" => [
				        "name" => "商家拒绝退款",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				            ["remark", "拒绝理由", "string", "非必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.agreetorefundorder" => [
				        "name" => "商家同意退款",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.businessorderdetail" => [
				        "name" => "商家订单详情",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.preparationOrder" => [
				        "name" => "商家订单备菜",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				        ],
				        "info" => "code 200 --操作成功",
				    ],
				    "stobusiness.StoBusinessOrder.businessrefundorder" => [
				        "name" => "商家订单退款",
				        "param" => [
				            ["mtoken", "mtoken值", "string", "必填"],
				            ["orderno", "订单编号", "int", "必填"],
				            ["paypwd","支付密码","string","必填"]
				        ],
				        "info" => "code 200 --操作成功",
				    ],
					
				],

			],
			"SysBounty"=>[
				"name"=>"用户奖励金",
				"api"=>[
				    "User.Bounty.hasbounty" => [
				        "name" => "用户是否有未查看奖励金数据",
				        "param" => [
				            ["mtoken", "登录token值", "string", "必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
                    		hasbounty----是否有奖励金1标识有0表示没有<br/>
				        "    
				    ],
				     "User.Bounty.bountylist" => [
				        "name" => "用户获得未查看奖励金列表",
				        "param" => [
				            ["mtoken", "登录token值", "string", "必填"],
				            ["page", "分页页码", "int", "非必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
                    		cashamount----获得现金奖励<br/>
                    		profitamount----获得牛粮奖励<br/>
                    		bullamount----获得牛豆奖励<br/>
                    		getbountydate----获得奖励日期<br/>
				        "    
				    ],
					
				],

			],
			"SysSendMsg"=>[
				"name"=>"系统消息发送",
				"api"=>[
				    "Sys.SendMsg.sendOneSystemMsg" => [
				        "name" => "群发系统消息",
				        "param" => [
				            ["msg", "消息内容", "string", "非必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        "    
				    ]
				],

			],
			"StoBusinessStaff"=>[
				"name"=>"实体店员工管理",
				"api"=>[
				    "Stobusiness.StoBusinessStaff.addstaff" => [
				        "name" => "实体店添加员工",
				        "param" => [
				        	["mtoken", "用户token", "string", "必填"],
				        	["staffname", "员工姓名", "string", "必填"],
				        	["mobile", "手机号码", "string", "必填"],
				            ["valicode", "验证码", "string", "必填"],
				            ["devtype",'设备类型(安卓为A， ios为I)',"string", "必填"],
				            ["devicetoken",'设备token--用于消息推送',"string", "非必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        "    
				    ],
				     "Stobusiness.StoBusinessStaff.stafflist" => [
				        "name" => "实体店员工列表",
				        "param" => [
				        	["mtoken", "用户token", "string", "必填"],
				        	["keywords", "关键词员工姓名或者电话号码", "string", "非必填"],
				        	["page", "页码", "int", "非必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        "    
				    ],
				    "Stobusiness.StoBusinessStaff.staffindex" => [
				        "name" => "实体店员工首页",
				        "param" => [
				            ["mtoken", "用户mtoken", "string", "必填"],
				            ["customerid", "员工用户id值(员工列表中有此值)", "int", "必填"],
				        ],
				        "info" => "code 200 <br>
				                    fansCount           粉丝个数<br>
				                    amount              总营业额<br>
				                    service_code        员工服务号<br>",
				    ],
				    "Stobusiness.StoBusinessStaff.deletestaff" => [
				        "name" => "实体店删除员工",
				        "param" => [
				        	["mtoken", "用户token", "string", "必填"],
				        	["rid", "需要删除的记录id", "int", "必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        "    
				    ],
				    "Stobusiness.StoBusinessStaff.binduserstaff" => [
				        "name" => "绑定实体店员工与用户关系",
				        "param" => [
				        	["mtoken", "用户token", "string", "必填"],
				        	["service_code", "服务员号", "int", "必填"],
				        	["businessid", "实体店商家id", "int", "必填"],
				        	["business_code", "实体店商家平台号", "int", "必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        "    
				    ],
				    "Stobusiness.StoBusinessStaff.hasbinduserstaff" => [
				        "name" => "检查用户是否绑定",
				        "param" => [
				        	["mtoken", "用户token", "string", "必填"]
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        	hasbind---1已经绑定0未绑定
				        "    
				    ]
				],

			],
			"Bonus"=>[
				"name"=>"奖励金",
				"api"=>[
				    "User.Bonus.checkstopayfollow" => [
				        "name" => "实体店优惠付款-判断可用奖励金金额",
				        "param" => [
				        	['mtoken','登录token值','string',"必填"],
                            ["amount","支付金额","int","必填"],
                            ["noinvamount","不参与优惠金额","int","必填"],
                            ['business_code',"商家平台号","int","必填"],
                            ['businessid',"商家id","int","必填"],
				        ],
				        "info" => "返回 
				        	code 200 --操作成功 <br>
				        "    
				    ],
				    
				],

			],
		];