<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;

class AmountController extends ActionController {
    
    const pageSize = 20;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 充值页面
    * @param 
    * @author jeeluo
    * @date 2017年11月2日下午6:20:23
    */
    public function rechargeAction() {
        $title = "充值";
        
        $viewData = [
            "title" => $title
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 用户现金余额(回购余额)
    * @param 
    * @author jeeluo
    * @date 2017年10月12日上午10:56:27
    */
    public function myCashAmountAction() {
        $title = "钱包余额";
        
        $role = Model::new("User.User")->getUserRoleID(["customerid"=>$this->userid]);
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid,
            'role' => $role
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
        $role = $this->params['role'];
        
        // 分段余额
//         $result = Model::new("User.UserFlow")->getCusCashData(["customerid"=>$customerid]);

//         if($result["code"] != "200") {
//             return $this->json($result["code"]);
//         }
        
        // 余额
        $amount = Model::new("Amount.AmoAmount")->getAmount(["customerid"=>$customerid]);
        
        $childYesCashAmount['amount'] = 0;
        
        // 查看营业额 (只有当role为3时查询)
        $busshow = 0;
        if($role == 3) {
            $yestoday = date("Y-m-d", strtotime("-1 day"));
//             $childYesCashAmount = Model::new("Amount.AmoAmount")->getChildCashTypeAmount(["customerid"=>$customerid,"direction"=>1,"begintime"=>$yestoday,"role"=>2]);
            $cashAmount = Model::new("Amount.AmoAmount")->getChildCashTypeAmount(["customerid"=>$customerid,"direction"=>1,"begintime"=>$yestoday,"role"=>2]);
            
            $childYesCashAmount['amount'] = $cashAmount['data']['amount'];
            $busshow = $amount['data']['busamount'] > 0 ? 1 : 0;
        }
        
        // 流水列表
        $where['customerid'] = $customerid;
        $where['type'] = 1;
//         $begintime = date('Y-m-d', strtotime("-2 day"));
//         $endtime = date('Y-m-d', time());
        
        $where['flowtime'] = [[">=",date("Y-m-d 00:00:00", strtotime("-2 day"))],["<",date("Y-m-d 23:59:59", time())]];
        
        $list = Model::new("User.UserFlow")->getNoMonthFlowList($where);
        
        if($list["code"] != "200") {
            return $this->json($list["code"]);
        }
        
        $result['data'] = $list['data'];
        $result['data']['cashamount'] = $amount['data']['cashamount'];
        $result['data']['recamount'] = $amount['data']['recamount'];
        $result['data']['busamount'] = $amount['data']['busamount'];
        $result['data']['yesbusamount'] = !empty($childYesCashAmount['amount']) ? $childYesCashAmount['amount'] : '0.00';
        $result['data']['busshow'] = $busshow;
        
        $result['data']['maxPage'] = ceil($list['data']['total']/self::pageSize);
        
        return $this->json("200", $result['data']);
//         $result['data'] = array_merge($result['data'], $amount['data']);
        
//         return $this->json($result["code"], $result['data']);
    }
    
    /**
    * @user 获取流水列表数据
    * @param 
    * @author jeeluo
    * @date 2017年10月24日下午5:40:09
    */
    public function getflowlistdataAction() {
        $customerid = $this->params['customerid'];
        $type = $this->params['type'];
        $page = $this->params['page'];
        
        $where['customerid'] = $customerid;
        $where['type'] = $type;
        $where['flowtime'] = [[">=",date("Y-m-d 00:00:00", strtotime("-2 day"))],["<",date("Y-m-d 23:59:59", time())]];
        
        $list = Model::new("User.UserFlow")->getNoMonthFlowList($where);
        
        if($list["code"] != "200") {
            return $this->json($list["code"]);
        }
        
        $result['data'] = $list['data'];
        
        $result['data']['maxPage'] = ceil($list['data']['total']/self::pageSize);
        
        return $this->json("200", $result['data']);
    }
}