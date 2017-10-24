<?php
// +----------------------------------------------------------------------
// |  [ 订单处理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------
// 
namespace app\auto\controller\Index;
use app\auto\ActionController;

use think\Config;

class IndexController extends ActionController{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }  

    public function indexAction(){
        echo "####Index.Index.index test###";
    }
}