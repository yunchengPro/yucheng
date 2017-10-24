<?php
	return [
		/*
		"cancelsorder_time"=>"-2 hour", // 2小时未付款取消订单
		"confirmorder"=>"-15 day", // 15天确认收货
		"longdateorder"=>"-23 day", // 延长收货
		*/
		"payorder_time"=>"+15 minutes", // 15分钟后自动取消
		"cancelsorder_time"=>"+5 minutes", // 商家未接单订单5分钟后自动取消
		"returnorder_time"=>"+24 hour", // 24小时内未处理，系统自动同意退款
		"refundorder_time"=>"+24 hour",//商家拒绝用户退款单 24小时内未处理，系统自动取消退款,
		"cancelsorder_mnum"=>5*60 ,// 商家未接单订单5分钟后自动取消
		"payorder_mnum"=>15*60 ,// 用户未付款15分钟未付款
		"cancelsorder_delivelertime"=>3600, // 商家确认配送后，1小时内不可取消订单
	];