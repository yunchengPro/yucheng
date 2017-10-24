<?php
// +----------------------------------------------------------------------
// |  [ 店铺管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-04-22
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Amount;

use app\superadmin\ActionController;
use app\lib\Model;

class RechargeController extends ActionController
{
    protected $rechargeType = array(1, 2, 3, 4);
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 充值记录
    * @param 
    * @author jeeluo
    * @date 2017年5月3日上午10:36:02
    */
    public function listAction() {
        $where = array();
        $where = $this->searchWhere([
            "orderno" => "like",
            "pay_status" => "=",
            "mobile" => "=",
            "addtime" => "times",
        ], $where);
        
        // 获取充值列表
//         if(empty($where)) {
//             $list = Model::ins("CusRecharge")->pageList($where, "*", "id desc");
//         } else {
//             $list = Model::new("Amount.SuperRecharge")->getWhereList($where);
//         }

        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $list = Model::ins("CusRecharge")->pageList($where, "*", "id desc");
        
        foreach($list['list'] as $k => $v) {
            // 根据用户id 查询数据库手机号码
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            
            $list['list'][$k]['pay_money'] = !empty($v['pay_money']) ? DePrice($v['pay_money']) : '0.00';
            $list['list'][$k]['mobile'] = $cus['mobile'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 系统充值
    * @param 
    * @author jeeluo
    * @date 2017年5月4日上午9:55:02
    */
    public function sysRechargeAction() {
        $where = array();
        $where = $this->searchWhere([
            "recharge_type" => "=",
            "orderno" => "like",
            "mobile" => "like",
            "addtime" => "times",
        ],$where);
        
        // 获取系统充值列表
        $list = Model::ins("SysRecharge")->pageList($where, "*", "id desc");
        
        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]['pay_money'] = DePrice($v['pay_money']);
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 用户充值页面
    * @param 
    * @author jeeluo
    * @date 2017年5月4日上午10:38:16
    */
    public function addSysRechargeAction() {
        $action = "/Amount/Recharge/addRecharge";
        $viewData = array(
            "title" => "用户充值",
            "actionUrl" => $action,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 系统充值页面
    * @param 
    * @author jeeluo
    * @date 2017年5月4日下午3:18:49
    */
    public function addSysComRechargeAction() {
        $action = "/Amount/Recharge/addComRecharge";
        $viewData = array(
            "title" => "系统公司充值",
            "actionUrl" => $action,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 添加系统充值
    * @param 
    * @author jeeluo
    * @date 2017年5月4日上午10:54:14
    */
    public function addRechargeAction() {
//         exit;
        if(empty($this->params['recharge_type']) || empty($this->params['mobile']) || empty($this->params['amount'])) {
            $this->showError("请填写完整信息");
        }
        
        if(!in_array($this->params['recharge_type'], $this->rechargeType)) {
            $this->showError("类型范围有误");
        }
        
        if(phone_filter($this->params['mobile'])) {
            $this->showError("手机号码有误");
        }
        
        $result = Model::new("Amount.SuperRecharge")->addSysRecharge($this->params);
        
        if($result["code"] != "200") {
            $this->showError($result["data"]);
        }
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 系统公司充值
    * @param 
    * @author jeeluo
    * @date 2017年5月4日下午3:23:42
    */
    public function addComRechargeAction() {
        if(empty($this->params['recharge_type']) || empty($this->params['amount'])) {
            $this->showError("请填写完整信息");
        }
        
        if(!in_array($this->params['recharge_type'], $this->rechargeType)) {
            $this->showError("类型范围有误");
        }
        
        $result = Model::new("Amount.SuperRecharge")->addSysComRecharge($this->params);
        
        if($result["code"] != "200") {
            $this->showError($result["data"]);
        }
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 充值卡
    * @param 
    * @author jeeluo
    * @date 2017年4月22日下午6:08:28
    */
    public function bullcodeAction() {
        
        $where = array();
        $where = $this->searchWhere([
            "show_code" => "like"
        ],$where);
        
        // 获取牛豆充值卡编号
        $list = Model::ins("BullCodeCode")->pageList($where, "*", "id desc");
        
        foreach ($list['list'] as $k => $v) {
            // 对该编号查询使用表
            $rechargeBull = Model::ins("CusRechargeBull")->getRow(array("bull_code" => $v['bull_code']), "addtime,amount");
            
            $list['list'][$k]['amount'] = !empty($rechargeBull) ? $rechargeBull['amount'] : $v['amount'];
            $list['list'][$k]['addtime'] = !empty($rechargeBull) ? $rechargeBull['addtime'] : '';
            $list['list'][$k]['status'] = !empty($rechargeBull) ? 1 : 0;
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
}