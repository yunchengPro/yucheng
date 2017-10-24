<?php
// +----------------------------------------------------------------------
// |  [ 牛店员工管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月10日09:27:26}}
// +----------------------------------------------------------------------
namespace app\api\controller\Stobusiness;
use app\api\ActionController;

use app\lib\Model;
use app\model\StoBusiness\StoBusinessStaffModel;

class StoBusinessStaffController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [addpersonnelAction 添加]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-10T09:49:12+0800
     * @return   [type]                   [description]
     */
    public function  addstaffAction(){
        $params['mobile'] = $this->params['mobile'];
        $params['staffname']   = $this->params['staffname'];
        $params['userid'] = $this->userid;
        $params['devicetoken'] = $this->params['devicetoken'];
        $params['devtype'] = $this->params['devtype'];
        $params['valicode'] = $this->params['valicode'];
        $params['version'] = $this->getVersion();
        
        if(empty($params['mobile']) || empty($params['staffname']) || empty($params['devtype']) || empty($params['valicode']))
            return $this->json(404);

        $result = StoBusinessStaffModel::addstaff($params);

        return $this->json($result['code']);

    }

    /**
     * [stafflistAction 员工列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-10T17:17:42+0800
     * @return   [type]                   [description]
     */
    public function stafflistAction(){
        $params['keywords'] = $this->params['keywords'];
        $params['userid'] = $this->userid;
        
        $result = StoBusinessStaffModel::stafflist($params);

        if($result['code'] != 200)
            return $this->json($result['code']);

        return $this->json(200,$result['data']);
    }
    
    /**
    * @user 员工首页
    * @param 
    * @author jeeluo
    * @date 2017年8月15日上午11:35:07
    */
    public function staffindexAction() {
        if(empty($this->params['customerid'])) {
            return $this->json(404);
        }
        
        $params['userid'] = $this->userid;
        $params['customerid'] = $this->params['customerid'];
        
        $result = StoBusinessStaffModel::staffindex($params);
        
        if($result['code'] != "200") {
            return $this->json($result["code"]);
        }
        
        return $this->json($result['code'],$result['data']);
    }

    /**
     * [deletestaffAction 删除员工]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-10T18:32:54+0800
     * @return   [type]                   [description]
     */
    public function deletestaffAction(){
        $params['rid'] = $this->params['rid'];
        $params['userid'] = $this->userid;
        if(empty($params['rid']))
            return $this->json(404);
        $result = StoBusinessStaffModel::deletestaff($params);

        return $this->json($result['code']);
    }

    /**
     * [binduserstaffAction 绑定员工与用户关系]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-10T20:16:06+0800
     * @return   [type]                   [description]
     */
    public function binduserstaffAction(){
        $params['userid'] = $this->userid;
        $params['service_code'] = $this->params['service_code'];
        $params['businessid'] = $this->params['businessid'];
        $params['business_code'] = $this->params['business_code'];
        if(empty($params['userid']) || empty($params['service_code']))
            return $this->json(404);

        $result = StoBusinessStaffModel::binduserstaff($params);

        return $this->json($result['code']);
    }

    /**
     * [hasbinduserstaffAction 检查用户是否绑定]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-15T20:05:26+0800
     * @return   [type]                   [description]
     */
    public function hasbinduserstaffAction(){
        $params['userid'] = $this->userid;
       
        if(empty($params['userid']))
            return $this->json(404);

        $result = StoBusinessStaffModel::hasbinduserstaff($params);
       
        return $this->json($result['code'],$result['data']);
    }

   
}