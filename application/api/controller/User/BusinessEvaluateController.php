<?php
// +----------------------------------------------------------------------
// |  [ 供应商评价管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-6-26 15:00:22}}
// +----------------------------------------------------------------------
namespace app\api\controller\User;
use app\api\ActionController;

use app\model\Business\BusinessModel;


class BusinessEvaluateController extends ActionController {

	public function __construct() {
        parent::__construct();
    }

    /**
     * [evaluateListAction 供应商评价列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-26T15:00:05+0800
     * @return   [type]                   [description]
     */
    public function evaluateListAction(){
    	
    	$businessid = BusinessModel::getCustomerBusiness($this->userid);
       
    	if($businessid > 0){
    		$params['businessid'] = $businessid;
    		$params['starttime'] = $this->params['starttime'];
    		$params['endtime'] = $this->params['endtime'];
    		$list = BusinessModel::getBusinessEvaluateList($params);
    		if($list){
    			return $this->json(200,$list);
    		}
    	}else{
            return  $this->json(404);
        }

    }



    /**
     * [relpyEvaluateAction 回复评论信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-26T16:54:15+0800
     * @return   [type]                   [description]
     */
    public function relpyEvaluateAction(){
    	$params['userid'] = $this->userid;
    	$businessid = BusinessModel::getCustomerBusiness($this->userid);
    	$params['businessid'] = $businessid;
    	$params['evaluateid'] = $this->params['evaluateid'];
    	$params['content'] = $this->params['content'];
    	$ret = BusinessModel::relpyEvaluate($params);
    	if($ret['code'] != 200){
    		return $this->json($ret['code']);
    	}else{
    		return $this->json(200,$ret['data']);
    	}
    }
}