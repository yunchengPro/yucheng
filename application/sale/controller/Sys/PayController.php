<?php
// +----------------------------------------------------------------------
// |  [ 实体店支付提交付款页面 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------


namespace app\sale\controller\Sys;
use app\sale\ActionController;

use think\Config;

use app\lib\Model;
use \think\Cookie;

use app\lib\Pay\WeixinWeb\WeixinWeb;
use app\lib\Pay\AliWap\AliWap;

use app\lib\Pay\Allinpay\Allinpay;

class PayController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * 选择支付方式
     * @Author   zhuangqm
     * @DateTime 2017-10-11T14:38:41+0800
     * @return   [type]                   [description]
     */
    public function paymethodAction(){
        
         $orderno = $this->params['orderno'];


        if(empty($orderno))
            exit("订单不存在");

        $amount = 0; //支付金额

        if(substr($orderno,0,3)=='CON'){

            $orderItem = Model::ins("ConOrder")->getRow(["orderno"=>$orderno],"*");

            if(empty($orderItem))
                exit("订单不存在");

            $amount = DePrice($orderItem['totalamount']);

        } else if(substr($orderno,0,3)=='REC') {
            
            $orderItem = Model::ins("CusRecharge")->getRow(["orderno"=>$orderno],"*");
            if(empty($orderItem)) {
                header('Content-type:application/json;charset=utf-8');
                exit($this->json("408",[],"请求无效，无效订单"));
            }
            
            $amount = DePrice($orderItem['amount']);
        } else if(substr($orderno,0,4)=='MALL') {
            
            $orderItem = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"*");
            if(empty($orderItem)) {
                header('Content-type:application/json;charset=utf-8');
                exit($this->json("408",[],"请求无效，无效订单"));
            }
            
            $amount = DePrice($orderItem['totalamount']);
        }
       
        return $this->view([
            "amount"=>$amount,
            "orderno"=>$orderno,
            "pay_test"=>$this->params['pay_test'],
        ]);
    }

    /**
     * 微信发起支付
     * @Author   zhuangqm
     * @DateTime 2017-10-11T14:52:33+0800
     * @return   [type]                   [description]
     */
    public function callpayAction(){

        $orderno = $this->params['orderno'];
        
        $customerid = $this->userid;

        $openid = $this->openid;

        //$openid = "o22U3wA-WoGJu7Qva0lkGEApLRVM";
        
        //$amount =0.01;
        if(empty($orderno)){
            //echo "参数有误";
            return $this->json("404");
            exit;
        }

        $order_amount = 0;

        //判断订单是否存在

        if(substr($orderno,0,3)=='CON'){

            $order_row = Model::ins("ConOrder")->getRow(["orderno"=>$orderno],"*");

            if($order_row['orderstatus']==3){
                //echo "订单已支付";
                return $this->json("4003");
                exit;
            }

            if($order_row['orderstatus']>0){
                //echo "订单已支付";
                return $this->json("4001");
                exit;
            }

            $order_row['amount'] = $order_row['totalamount'];

            $order_amount = $order_row['totalamount'];

            $customerid = $order_row['customerid'];
        } else if(substr($orderno,0,3)=='REC') {

            $order_row = Model::ins("CusRecharge")->getRow(["orderno"=>$orderno],"*");
            if(empty($order_row)) {
                return $this->json("408");
                exit;
            }
            
            if($order_row['pay_status'] == 1) {
                //echo "订单已支付";
                return $this->json("4001");
                exit;
            }
            
            if($order_row['pay_status'] > 0) {
                return $this->json("4003");
                exit;
            }
            
            $order_amount = $order_row['amount'];
            
            $customerid = $order_row['customerid'];
        }
  
        if(empty($order_row)){
            //echo "参数有误";
            return $this->json("404");
            exit;
        }

        // 检测订单是否是当前操作用户下的订单
        if($order_row['customerid'] != $this->userid) {
            echo "无操作权限";
            exit;
        }
        

        $weixin_config = Config::get("weixin");

        $payamount = $order_amount;

        //添加支付请求记录
        $result = Model::new("Pay.Pay")->addPayOrder([
                "orderno"=>$orderno,
                "amount"=>$payamount,
                "pay_type"=>"weixin_web",
                "userid"=>$customerid,
            ]);
        
        $WeixinWeb = new WeixinWeb();
        $jsApiParameters = $WeixinWeb->getWeixinPay([
                "openid"=>$openid,
                "total_fee"=>$payamount,//$order_row['amount'],
                "notify_url"=>$weixin_config['notify_url'],
                "body"=>'订单支付-'.$orderno,
                "out_trade_no"=>$orderno,
            ]);

        //echo $jsApiParameters;
        return $this->json("200",[
            "jsApiParameters"=>$jsApiParameters,
        ]);
        exit();
    }


    /**
     * 阿里wap支付
     * @Author   zhuangqm
     * @DateTime 2017-10-18T15:53:27+0800
     * @return   [type]                   [description]
     */
    public function aliwappayorderAction(){

        $orderno = $this->params['orderno'];
        
        $customerid = $this->userid;


        //$amount =0.01;
        if(empty($orderno)){
            echo "参数有误";
            exit;
        }
        
        $order_amount = 0;

        //判断订单是否存在

        if(substr($orderno,0,3)=='CON'){

            $order_row = Model::ins("ConOrder")->getRow(["orderno"=>$orderno],"*");

            if($order_row['orderstatus']>0){
                //echo "订单已支付";
                echo "订单已支付";
                exit;
            }

            $order_row['amount'] = $order_row['totalamount'];

            $order_amount = $order_row['totalamount'];

            $customerid = $order_row['customerid'];
        } else if(substr($orderno,0,3)=='REC') {

            $order_row = Model::ins("CusRecharge")->getRow(["orderno"=>$orderno],"*");
            if(empty($order_row)) {
                return $this->json("408");
                exit;
            }
            
            if($order_row['pay_status'] == 1) {
                //echo "订单已支付";
                return $this->json("4001");
                exit;
            }
            
            if($order_row['pay_status'] > 0) {
                return $this->json("4003");
                exit;
            }
            
            $order_amount = $order_row['amount'];
            
            $customerid = $order_row['customerid'];
        }

        if(empty($order_row)){
            echo "参数有误";
            exit;
        }

        // 检测订单是否是当前操作用户下的订单
        if($order_row['customerid'] != $this->userid) {
            echo "无操作权限";
            exit;
        }

        $payamount = $order_amount;


        //添加支付请求记录
        $result = Model::new("Pay.Pay")->addPayOrder([
                "orderno"=>$orderno,
                "amount"=>$payamount,
                "pay_type"=>"ali_web",
                "userid"=>$customerid ,
            ]);

        $ali_config = Config::get("pay_ali");

        $AliWap = new AliWap();

        $AliWap->addorder([
                "WIDtotal_amount"=>DePrice($payamount),//$amount,//DePrice($order_row['amount']), //支付金额
                "WIDout_trade_no"=>$orderno, //订单号
                "WIDsubject"=>'订单支付-'.$orderno,
                "WIDbody"=>'订单支付-'.$orderno,
                "return_url"=>$ali_config['return_order_url'].$orderno,
                "passback_params"=>urlencode(json_encode(["orderno"=>$orderno])),
                "noityUrl"=>$ali_config['web_noityUrl'],
            ]);
        exit;
    }

    public function quickpayAction() {
        $orderno = $this->params['orderno'];
        
        if(empty($orderno)) {
            echo "参数有误";
            exit;
        }

        $order_amount = 0;

        //判断订单是否存在

        if(substr($orderno,0,3)=='CON'){

            $order_row = Model::ins("ConOrder")->getRow(["orderno"=>$orderno],"*");

            if($order_row['orderstatus']>0){
                //echo "订单已支付";
                echo "订单已支付";
                exit;
            }

            $order_row['amount'] = $order_row['totalamount'];

            $order_amount = $order_row['totalamount'];

            $customerid = $order_row['customerid'];
        } else if(substr($orderno,0,3)=='REC') {

            $order_row = Model::ins("CusRecharge")->getRow(["orderno"=>$orderno],"*");
            if(empty($order_row)) {
                return $this->json("408");
                exit;
            }
            
            if($order_row['pay_status'] == 1) {
                //echo "订单已支付";
                return $this->json("4001");
                exit;
            }
            
            if($order_row['pay_status'] > 0) {
                return $this->json("4003");
                exit;
            }
            
            $order_amount = $order_row['amount'];
            
            $customerid = $order_row['customerid'];
        }

        if(empty($order_row)){
            echo "参数有误";
            exit;
        }

        $payamount = $order_amount;
        
        
        // 检测订单是否是当前操作用户下的订单
        if($order_row['customerid'] != $this->userid) {
            echo "无操作权限";
            exit;
        }
        
       
        $result = Allinpay::QuickWeb(["orderno"=>$orderno,"pay_price"=>$payamount,"userid"=>$customerid,"order_time"=>$order_row['addtime'],"actiontype"=>1]);
        
        echo $result;
        exit;
    }


    /**
     * 支付结果
     * @Author   zhuangqm
     * @DateTime 2017-10-11T14:58:51+0800
     * @return   [type]                   [description]
     */
    public function payresultAction(){
        return $this->view([
            "orderno"=>$this->params['orderno'],
        ]);
    }

    public function getpayresultAction(){
        $orderno = $this->params['orderno'];

        $item = []; 

        if(substr($orderno,0,3)=='CON'){

            $order_row = Model::ins("ConOrder")->getRow(["orderno"=>$orderno],"*");

            if($order_row['customerid'] != $this->userid)
                return $this->json("1001",'','无权操作');
            
            $busItem = Model::ins("CusCustomer")->getRow(["id"=>$order_row['businessid']],"mobile");
            $order_row['payamount'] = DePrice($order_row['payamount']);
          
        } else if(substr($orderno,0,3)=='REC') {
            
            $order_row = Model::ins("CusRecharge")->getRow(["orderno"=>$orderno],"*");
            
            if($order_row['customerid'] != $this->userid)
                return $this->json("1001",'','无权操作');
            
            $busItem = Model::ins("CusCustomer")->getRow(["id"=>$this->userid],"mobile");
            $order_row['payamount'] = DePrice($order_row['payamount']);
        }

        return $this->json("200",$order_row);
    }
    
}