<?php
	return [
		"add_con_bus_profit"=>"0.5", //商家享受消费金额50%分润
		"add_con_parent_profit"=>"0.1", // 消费者上级享受5%分润
		"add_con_grandpa_profit"=>"0.02", // 消费者上上级享受3%分润
		"add_con_ggrandpa_profit"=>"0.004", // 消费者上上级享受3%分润
		"add_con_manager_profit"=>"0.03", // 区县经理市场销售分润业绩3%
		"add_con_chief_profit"=>"0.02", // 市级总监市场销售分润业绩2%
		"add_con_manager_parent_profit"=>"0.03", // 区县经理的直接上级经理得市场销售分润业绩3%*3%
		
		// 回购分红分润
		"add_bonus_parent_profit"=>"0.2", // 消费者上级享受20%分润
		"add_bonus_grandpa_profit"=>"0.04", // 消费者上上级享受20%*20%分润
		"add_bonus_ggrandpa_profit"=>"0.008", // 消费者上上级享受0%*20%*20%分润
	];