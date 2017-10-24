<?php
	return [
		/*
		"cancelsorder_time"=>"-2 hour", // 2小时未付款取消订单
		"confirmorder"=>"-15 day", // 15天确认收货
		"longdateorder"=>"-23 day", // 延长收货
		*/
		/*"cancelsorder_time"=>"-15 minutes", // 15分钟未付款取消订单
		"missorder_time"=>"-15 minutes", // 商家超时未接单
		"refuseorder_time"=>"-24 hour", // 商家拒绝退款超时未处理关闭订单
		"notagreeorder_time" => "-24 hour", //用户申请退款超时商家未处理同意退款
		"confirmorder_time" => "-5 hour", //下单后5小时内未点确认送达，则系统自动确认
		"cancelsorder_msg_time"=>"-10 minutes", // 用户订单关闭五分钟前发送消息 您的订单将在5分钟后自动关闭
		"refuseorder_msg_time"=>"-1410 minutes", // 商家拒绝退款超时未处理关闭订单--暂时没有用*/

		"cancelsorder_time"=>"-10 minutes", // 15分钟未付款取消订单
		"missorder_time"=>"-3 minutes", // 商家超时未接单
		"refuseorder_time"=>"-10 minutes", // 商家拒绝退款超时未处理关闭订单
		"notagreeorder_time" => "-10 minutes", //用户申请退款超时商家未处理同意退款
		"confirmorder_time" => "-3 minutes", //下单后5小时内未点确认送达，则系统自动确认
		"cancelsorder_msg_time"=>"-3 minutes", // 用户订单关闭五分钟前发送消息 您的订单将在5分钟后自动关闭
		"refuseorder_msg_time"=>"-3 minutes", // 商家拒绝退款超时未处理关闭订单 --暂时没有用
	];	