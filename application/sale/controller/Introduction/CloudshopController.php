<?php
// +----------------------------------------------------------------------
// |  [ 大数据云铺 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月12日09:47:43}}
// +----------------------------------------------------------------------
namespace app\mobile\controller\Introduction;
use app\mobile\ActionController;
use think\Config;

class CloudshopController extends ActionController{

		/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [registDeal 大数据云铺高级版]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T16:51:44+0800
     * @return   [type]                   [description]
     */
    public function  topgradeEditionAction(){

    	
        $title =  "大数据云铺 高级版";
    	$viewData = [
            'title' => $title,
    	];
    	return $this->view($viewData);
    }

     /**
     * [registDeal 大数据云铺基础版]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T16:51:44+0800
     * @return   [type]                   [description]
     */
    public function  foundationEditionAction(){

    	
        $title =  "大数据云铺 基础版";
    	$viewData = [
            'title' => $title,
    	];
    	return $this->view($viewData);
    }

     /**
     * [registDeal 大数据云铺企业版]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T16:51:44+0800
     * @return   [type]                   [description]
     */
    public function   businessEditionAction(){

    	
        $title =  "大数据云铺 企业版";
    	$viewData = [
            'title' => $title,
    	];
    	return $this->view($viewData);
    }

}