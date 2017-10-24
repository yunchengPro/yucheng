<?php
// +----------------------------------------------------------------------
// |  [ 订单管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-03-16
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Order;

use app\superadmin\ActionController;
use app\lib\Model;


class StoController extends ActionController {
    
    
    
    public function __construct() {
        parent::__construct();
    }
    
    
    /**
    * @user 全部订单管理
    * @param 
    * @author jeeluo
    * @date 2017年3月16日下午2:36:53
    */
    public function listAction() {
        $pay_type = [
            "weixin_app"=>"微信APP支付",
            "weixin_web"=>"微信Web支付",
            "ali_app"=>"支付宝APP支付",
            "ali_web"=>"支付宝Web支付",
            "allinpay_quick"=>"快捷支付",
            "allinpay_ali"=>"支付宝快捷支付",
            "allinpay_weixin"=>"微信快捷支付",
        ];
        // 查询
        
        $where['status'] = [">=",1];
        
        if(!empty($this->params['businessname']))
            $where['businessid'] = ["in","select id from sto_business_baseinfo where businessname like '%".$this->params['businessname']."%'"];
        if(!empty($this->params['business_code']))
            $where['businessid'] = ["in","select id from sto_business_baseinfo where business_code= '".$this->params['business_code']."'"];

        $where = $this->searchWhere([
            "pay_code" => "like",
            "customername"=>"like",
            "addtime" => "times",
        ], $where);
        
//         $list = Model::ins("OrdOrder")->pageList($where, "*", "addtime desc");

//         if(!empty($where)) {
//             $list = Model::new("Order.SuperIndex")->getWhereList($where);
//         } else {
//             $list = Model::ins("OrdOrder")->pageList($where, "*", "addtime desc");
//         }

        $cusOBJ = Model::ins("CusCustomer");

        $StoOBJ = Model::ins("StoBusinessBaseinfo");

        $payOBJ = Model::ins("PayOrder");

        $list = Model::ins("StoPayFlow")->pageList($where, "*", "addtime desc");
        
        foreach ($list['list'] as $k => $v) {
            
            /*$cusinfo = $cusOBJ->getRow(["id"=>$v['customerid']]);
            $list['list'][$k]['cust_name'] = $cusinfo['mobile'];*/

            if($v['mangercustomerid']>0){
                $cusinfo = $cusOBJ->getRow(["id"=>$v['mangercustomerid']],"mobile");
                $list['list'][$k]['mangercustomer'] = $cusinfo['mobile'];
            }

            $stoinfo = $StoOBJ->getRow(["id"=>$v['businessid']],"business_code,businessname");

            $list['list'][$k]['business_code'] = $stoinfo['business_code'];
            $list['list'][$k]['businessname'] = $stoinfo['businessname'];

            $list['list'][$k]['amount'] = DePrice($v['amount']);
            $list['list'][$k]['noinvamount'] = DePrice($v['noinvamount']);

//             $list['list'][$k]['payamount'] = DePrice($payorder['payamount']);
            
            $payorder = $payOBJ->getRow(["orderno"=>$v['pay_code']],"pay_type,pay_money,pay_time");
            if(!empty($payorder)){
                $list['list'][$k]['pay_type'] = $pay_type[$payorder['pay_type']];
                //$list['list'][$k]['pay_money'] = DePrice($payorder['pay_money']);
                //$list['list'][$k]['pay_time'] = $payorder['pay_time'];
            }else{
                $list['list'][$k]['pay_type'] = "余额支付";
            }
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
    
    
}