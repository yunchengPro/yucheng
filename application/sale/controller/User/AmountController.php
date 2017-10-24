<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;

class AmountController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 用户现金余额(回购余额)
    * @param 
    * @author jeeluo
    * @date 2017年10月12日上午10:56:27
    */
    public function myCashAmountAction() {
        $title = "金牛回购余额";
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 获取用户现金余额数据
    * @param $customerid 用户id
    * @author jeeluo
    * @date 2017年10月12日上午11:41:46
    */
    public function getMyCashAmountDataAction() {
        $customerid = $this->params['customerid'];
        
        // 分段余额
        $result = Model::new("User.UserFlow")->getCusCashData(["customerid"=>$customerid]);

        if($result["code"] != "200") {
            return $this->json($result["code"]);
        }
        
        // 余额
        $amount = Model::new("Amount.AmoAmount")->getAmount(["customerid"=>$customerid]);
        
        $result['data'] = array_merge($result['data'], $amount['data']);
        
        return $this->json($result["code"], $result['data']);
    }
}