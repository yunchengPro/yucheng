<?php
// +----------------------------------------------------------------------
// |  [ 牛粉模型]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月30日 21:12:02
// +----------------------------------------------------------------------
namespace app\model\Customer;

use \think\Request;
//获取配置
use \think\Config;
//Config::get('webview.webviewUrl');

//use app\lib\Db;
use app\lib\Model;
//use app\lib\Img;

use app\model\Customer\CustomerModel;

class BullCusRoleModel extends CustomerModel
{    
    protected $_role = 'bullCusRole';
    
    /**
    * @user 禁用操作
    * @param enable 状态值 id 用户id值
    * @author jeeluo
    * @date 2017年4月18日上午9:47:18
    */
    public function enable($params) {
        return $this->_enable($params);
    }
    
    public function getCusCustomerInfo($customerid) {
        return $this->_getCusCustomerInfo($customerid);
    }
    
    public function getRoleRelation($customerid) {
        return $this->_getRoleRelation($customerid);
    }
    
    public function flowRecoFans($params) {
        return $this->_flowRecoFans($params);
    }
}