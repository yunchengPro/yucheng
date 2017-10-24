<?php
	return [
		/*
		"cancelsorder_time"=>"-2 hour", // 2小时未付款取消订单
		"confirmorder"=>"-15 day", // 15天确认收货
		"longdateorder"=>"-23 day", // 延长收货
		*/
		"cancelsorder_time"=>"-20 min", // 2小时未付款取消订单
		"confirmorder"=>"-50 min", // 15天确认收货
		"longdateorder"=>"-40 min", // 延长收货

		"closeorder"=>"-30 min", // -7 day确认收货后，

		// 退款申请，7天没审核，自动退款
		"return_price_day"=>"-30 min",
	    // 退货申请，3天没审核，自动审核通过
	    "return_exam_day"=>"-30 min",
	    // 退货审核通过，15天未确认收货，自动确认收货
	    "return_goods_day"=>"-30 min",
		// 待发货状态 ，7天后未发货，自动退款
		"return_fahuo_day"=>"-30 min",
		// 确认收货后，30天未评价，自动默认评价，好评
		"evaluate_day"=>"-30 min",
	    // 退货审核通过，3天不填写物流信息，取消退货
	    "return_goods_express"=>"-10 min",
	    // 抢购15分钟未支付，系统自动取消订单，同时恢复库存
	    "cancelsorderbuy_time"=>"-15 min",
	];