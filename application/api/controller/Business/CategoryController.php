<?php
// +----------------------------------------------------------------------
// |  [ 店铺分类管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------
// 
namespace app\api\controller\Business;
use app\api\ActionController;

use app\lib\Db;
use app\model\Business\BusinessModel;

class CategoryController extends ActionController{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }  

    /**
     * [getBusinessCategory 获取店铺分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-04T14:59:03+0800
     * @return   [type]                   [description]
     */
    public function getBusinessCategoryAction(){
    	if(!empty($this->params['businessid'])){
	    	$businessid     = $this->params['businessid'];
	    	$data = BusinessModel::getBusinessCategoryById($businessid);
	    	return $this->json("200",[
	                "category"=>$data
	            ]);
    	}else{
    		return $this->json(404);
    	}
    }
}