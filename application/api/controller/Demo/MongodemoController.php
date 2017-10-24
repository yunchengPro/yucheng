<?php
// +----------------------------------------------------------------------
// |  [ mongoDB LBS 示例 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-04-06
// +----------------------------------------------------------------------
namespace app\api\controller\Demo;
use app\api\ActionController;
//获取配置
use \think\Config;

//获取Db操作类
use app\lib\Db;

use app\lib\Model;

//获取Redis操作类
use app\lib\Redis;

//获取MongoDb操作类
use app\lib\MongoDb;

use app\model\Demo\MongoDemoModel;

class MongodemoController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [addLBSAction 添加坐标]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-06T10:11:04+0800
     */
    public function addLBSAction(){
    	
    	// 随机插入100条坐标纪录 
		for($i=0; $i<100; $i++){ 
			$longitude = '113.3'.mt_rand(10000, 99999); 
			$latitude = '23.15'.mt_rand(1000, 9999); 
			$name = 'name'.mt_rand(10000,99999); 
			MongoDemoModel::add($longitude, $latitude, $name); 
		} 
	  
    }
	  
	/**
	 * [getLBSAction 获取妇女节]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-04-06T10:33:25+0800
	 * @return   [type]                   [description]
	 */
	public function getLBSAction(){
		// 搜寻一公里内的点 
		$longitude = 113.323568; 
		$latitude = 23.146436; 
		$maxdistance = 1; 
		$result = MongoDemoModel::query($longitude, $latitude, $maxdistance); 
		print_r($result); 
	} 

	public function testAction(){
		$row = Model::Mongo("StoBusinessInfo")->getRow(["id"=>87]);
		print_r($row);

		Model::Mongo("StoBusinessInfo")->update(["id"=>87],["enable"=>'-1']);

		$row = Model::Mongo("StoBusinessInfo")->getRow(["id"=>87]);
		print_r($row);
		exit;
	}
	
}