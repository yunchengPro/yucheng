<?php
// +----------------------------------------------------------------------
// |  [ 广告启动页接口 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-6-22 11:11:30}}
// +----------------------------------------------------------------------
namespace app\api\controller\Sys;
use app\api\ActionController;

use app\lib\Log;
use app\lib\Model;
use app\lib\Img;

class SysAdvertController extends ActionController{
	
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [startupBannerAction 启动页广告列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-22T15:09:59+0800
     * @return   [type]                   [description]
     */
   	public function startupBannerAction(){
   		$banner = Model::ins('SysStartupBanner')->getRow(['enable'=>1],'id,bname,thumb,urltype,url,time,banner_type','id desc');
   	
   		$banner['thumb'] = Img::url($banner['thumb']);
   	
   		if(empty($banner))
            $banner['thumb'] = "";
        
   		return $this->json("200",$banner);
   	}
}