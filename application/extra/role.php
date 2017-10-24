<?php
	
	return [
		"manager_count"=>10, //总监下面最多10个经理

		// 角色费用
		"chief_cost"=>50000000, //总监升级费用 50w
		"manager_cost"=>30000000, // 经理升级费用 30w
		"bus_cost"=>10000000, // 商家升级费用 10w
	

		// 推商家
		"total_to_bus"=>0.4,
		// 消费者A直推商家，消费者A得10%，消费者A的上级（经理或者总监）得30%
		"cus_to_bus"=>0.1,
		//"cus_to_bus_parent"=>0.3,

		// 商家A直推商家B，商家A和商家A的上级（经理或者总监）各得20%
		"bus_to_bus"=>0.2,
		//"bus_to_bus_parent"=>0.2,

		// 经理直推商家，经理得40%
		"manager_to_bus"=>0.4,

		// 总监直推商家，总监得40%；
		"chief_to_bus"=>0.3,

		// 推经理
		"total_to_manager"=>0.4,
		// 消费者A直推经理，消费者A得10%，消费者A的上级（总监）得30%
		"cus_to_manager"=>0.1,
		//"cus_to_manager_parent"=>0.3,

		// 商家A直推经理，商家A和商家A的上级（总监）各得20%
		"bus_to_manager"=>0.2,
		//"bus_to_manager_parent"=>0.2,

		// 经理A直推经理B，经理A和经理A的上级（总监）各得20%
		"manager_to_manager"=>0.2,
		//"manager_to_manager_parent"=>0.2,

		// 总监直推经理，总监得40%；
		"chief_to_manager"=>0.4,
	];