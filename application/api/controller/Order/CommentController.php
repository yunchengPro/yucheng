<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 订单评价 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Order;

use app\api\ActionController;

//获取配置
use \think\Config;

//获取Db操作类
use app\lib\Db;



class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }
    
}