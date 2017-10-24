<?php
namespace app\sale\controller\User;

use app\sale\ActionController;

class RechargeController extends ActionController {
    /**
    * @user 构造函数
    * @param 
    * @author jeeluo
    * @date 2017年8月30日下午6:31:39
    */
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 牛票充值页面
    * @param 
    * @author jeeluo
    * @date 2017年8月30日下午6:34:02
    */
    public function cashrechargeAction() {
        $mtoken = $this->mtoken;
        
        $viewData = [
            "title" => "牛票充值",
            "mtoken" => $mtoken
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 提现页面
    * @param 
    * @author jeeluo
    * @date 2017年8月30日下午6:54:23
    */
    public function withdrawAction() {
        $mtoken = $this->mtoken;
        
        $viewData = [
            "title" => "提现",
            "mtoken" => $mtoken
        ];
        return $this->view($viewData);
    }
}