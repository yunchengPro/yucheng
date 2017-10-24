<?php
namespace app\api\controller\User;

use app\api\ActionController;

use app\lib\Model;

use app\model\StoBusiness\StobusinessModel;

/**
* @user 余额处理
* @param 
* @author jeeluo
* @date 2017年3月21日下午4:18:59
*/
class BonusController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
        
    /**
     * 实体店优惠付款-判断可用奖励金金额
     * @Author   zhuangqm
     * @Datetime 2017-08-12T10:41:58+0800
     * @return   [type]                   [description]
     */
    public function checkstopayfollowAction(){

        $userid         = $this->userid;

        $businessid     = $this->params['businessid'];
        $business_code  = $this->params['business_code'];
        $amount         = $this->params['amount']; // 支付金额

        $noinvamount    = $this->params['noinvamount']; // 不参与优惠金额
        
        if($amount==0 || empty($amount))
            return $this->json("60015");
       
        $bonus = StobusinessModel::checkstopayfollow([
                "userid"=>$userid,
                "business_code"=>$business_code,
                "businessid" => $businessid,
                "amount"=>$amount,
                "noinvamount"=>$noinvamount,
            ]);

        return $this->json($bonus['code'],[
                "bonusamount" =>$bonus['bonusamount'],
                "maxbonusamount"=>$bonus['maxbonusamount'],
            ]);
    }
}