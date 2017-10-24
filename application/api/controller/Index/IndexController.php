<?php
// +----------------------------------------------------------------------
// |  [ 商城综合首页 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-27
// +----------------------------------------------------------------------
namespace app\api\controller\Index;

use app\api\ActionController;

class IndexController extends ActionController{
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [index 返回域名接口]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-27T21:47:54+0800
     * @return   [type]                   [description]
     */
    public function returnurlAction(){
        $data = ['mobileurl'=>'http://mobile.niuniuhuiapp.com'];
        return $this->json(200,$data);
    }
}