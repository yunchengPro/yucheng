<?php
// +----------------------------------------------------------------------
// |  [ 七夕活动 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月22日10:36:33}}
// +----------------------------------------------------------------------
namespace app\mobile\controller\Active;
use app\mobile\ActionController;

use app\model\Product\ProductModel;
use think\Config;

use app\lib\Model;

use app\lib\Img;

class DbseventhController extends ActionController{


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

   	public function indexAction(){
        $config = Config::get("webview");
        
    	return $this->view([
                "domain"=>$config['webviewUrl'],
            ]);
    } 

}