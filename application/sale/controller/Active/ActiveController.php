<?php
// +----------------------------------------------------------------------
// |  [ 中秋节活动 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年9月13日11:01:50}}
// +----------------------------------------------------------------------
namespace app\mobile\controller\Active;
use app\mobile\ActionController;

use app\model\Product\ProductModel;
use think\Config;

use app\lib\Model;

use app\lib\Img;

class ActiveController extends ActionController{
		/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [midautumnAction 中秋]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-13T11:02:27+0800
     * @return   [type]                   [description]
     */
   	public function midautumnAction(){
   		$config = Config::get("webview");
   		return $this->view([
                "domain"=>$config['webviewUrl']
            ]);
   	}

    /**
     * [nationaldayAction 国庆活动]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-15T11:28:24+0800
     * @return   [type]                   [description]
     */
    public function  nationaldayAction(){
        $config = Config::get("webview");
        return $this->view([
                "domain"=>$config['webviewUrl']
            ]);
    } 
}