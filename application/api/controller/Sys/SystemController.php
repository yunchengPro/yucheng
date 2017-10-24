<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 购物车信息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Sys;
use app\api\ActionController;

use app\lib\Log;
use app\lib\Model;

class SystemController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    /**
     * [jumpurlAction 跳转]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-20T15:49:57+0800
     * @return   [type]                   [description]
     */
    public function jumpurlAction(){
        $code = $this->params['code'];
        $urlData = Model::ins('SysCodeUrl')->getRow(['code'=>$code],'url');
        $url = $urlData['url'];
        header('Location:'.$url);
    }
}