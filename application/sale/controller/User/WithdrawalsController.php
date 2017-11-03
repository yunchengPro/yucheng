<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;
use think\Config;

class WithdrawalsController extends ActionController {
    
    const pageNum = 20;
    
    public function __construct() {
        parent::__construct();
    }
    
    
    /**
    * @user 提现明细列表页面
    * @param 
    * @author jeeluo
    * @date 2017年10月12日下午12:02:30
    */
    public function listAction() {
        $title = "提现明细";
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 获取列表数据
    * @param $customerid 用户id
    * @author jeeluo
    * @date 2017年10月12日下午2:19:23
    */
    public function getListDataAction() {
        $customerid = $this->params['customerid'];
        
        if($customerid == '') {
            return $this->json(404);
        }
        
        $begintime = !empty($this->params['begintime']) ? $this->params['begintime'] : '';
        
        $result = Model::new("User.Withdrawals")->getList(["customerid"=>$customerid,"begintime"=>$begintime]);
        
        $allCountNum = $result['total'];
        
        $result['maxPage'] = ceil($allCountNum/self::pageNum);
//         print_r($result);
//         exit;
        
        return $this->json("200", $result);
    }
    
    /**
    * @user 提现申请页面
    * @param 
    * @author jeeluo
    * @date 2017年10月13日上午10:20:54
    */
    public function withdrawalsIndexAction() {
        $title = "提现";
        
        $withdrawals_config = Config::get("withdrawals");
        
        $bankid = !empty($this->params['bankid']) ? $this->params['bankid'] : '';
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid,
            'multiple' => $withdrawals_config['multiple'],
            'proportion' => $withdrawals_config['service_proportion'],
            'bankid' => $bankid
        ];
        
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 获取银行卡 余额等信息
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017年10月13日上午11:47:59
    */
    public function getBankAmountAction() {
        $customerid = $this->params['customerid'];

        // 获取银行卡id
        $bankid = !empty($this->params['bankid']) ? $this->params['bankid'] : 0;
        
        // 获取银行卡信息
        $bankInfo = Model::new("User.UserBank")->getUserBankInfo(["customerid"=>$customerid,"bankid"=>$bankid]);
        if($bankInfo["code"] != "200") {
            return $this->json($bankInfo["code"]);
        }
        
        // 获取用户余额
        $amount = Model::new("Amount.AmoAmount")->getAmount(["customerid"=>$customerid]);
        if($amount["code"] != "200") {
            return $this->json($amount["code"]);
        }
        
        $result['bankInfo'] = $bankInfo['data'];
        $result['amount'] = $amount['data'];

        return $this->json(200, $result);
    }
    
    
    /**
    * @user 添加记录
    * @param 
    * @author jeeluo
    * @date 2017年10月13日下午6:08:12
    */
    public function addAction() {
        $this->checktokenHandle();
        $customerid = $this->params['customerid'];
        $bankId = $this->params['bankId'];
        $accountNumber = $this->params['accountNumber'];
        
        $paypwd = $this->params['paypwd'];
        $encryptpaypwd = md5($paypwd);
        
        // 写入记录
        $result = Model::new("User.Withdrawals")->addWithdrawals(["amount"=>$accountNumber,"customerid"=>$customerid,"bankid"=>$bankId, "paypwd"=>$encryptpaypwd]);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 我的银行卡页面
    * @param 
    * @author jeeluo
    * @date 2017年10月16日上午10:53:45
    */
    public function mybanklistAction() {
        $title = "我的银行卡";
        $viewData = [
            'title' => $title
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 添加银行卡页面
    * @param 
    * @author jeeluo
    * @date 2017年10月16日上午11:09:12
    */
    public function addbankAction() {
        $title = "添加银行卡";
        $viewData = [
            'title' => $title
        ];
        return $this->view($viewData);
    }
}