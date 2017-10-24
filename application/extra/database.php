<?php 
	/**
	 * mysql数据库配置
	 */
	
	return [
		//数据库配置
	    'nnh_db'        => [
	        // 数据库类型
	        'type'        => 'mysql',
	        // 服务器地址

	        'hostname'    => '127.0.0.1',
	        //'hostname'    => '192.168.1.16',
	        
	        // 数据库名
	        'database'    => 'yuncheng',
	        // 数据库用户名
	        'username'    => 'root',
	        // 数据库密码
	        //'password'    => '123456',
	        'password'    => '',
	        // 数据库编码默认采用utf8
	        'charset'     => 'utf8',
	        // 数据库表前缀
	        'prefix'      => '',
	    ],

	    
	];