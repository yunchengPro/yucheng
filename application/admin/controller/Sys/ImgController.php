<?php
// +----------------------------------------------------------------------
// |  [ 上传图片控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-04-11
// +----------------------------------------------------------------------
namespace app\admin\controller\Sys;
use app\admin\ActionController;

use \think\Config;

use DateTime;

class ImgController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [addimgAction 添加信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-11T10:42:35+0800
     * @return   [type]                   [description]
     */
   public function addimgAction(){
		$image_config = Config::get("image");
		return $this->view(array(
				"imgurl"=>$image_config['domain'],
			));
	}

}
