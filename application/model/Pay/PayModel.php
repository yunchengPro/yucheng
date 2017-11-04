<?php
// +----------------------------------------------------------------------
// |  [ 支付 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Pay;

use app\lib\Model;
//use app\model\PayModel;
use app\lib\Pay\Weixin\WeixinApp;
use app\lib\Pay\Ali\alipay_app;

use app\lib\Pay\Allinpay\Weixin as Allinpay_weixin;
use app\lib\Pay\Allinpay\Ali as Allinpay_ali;
use app\lib\Pay\Allinpay\Quick as Allinpay_quick;

use app\model\User\PaypwdModel;
use app\model\Order\OrderPayModel;

class PayModel{

    /**
	 * 微信支付请求
	 * @Author   zhuangqm
	 * @DateTime 2017-02-24T10:36:53+0800
	 * @param    [type]                   $param [description]
	 * @return   [type]                          [description]
	 */
    public static function weixinPayRequest($param){
    	$payOBJ = new WeixinApp([
                    "orderid"=>$param['orderid'],
                    "pay_price"=>$param['pay_price'],
                    "orderBody"=>$param['orderBody'],
                ]);

        return $payOBJ->getPayOrder();
    }

    /**
     * 微信支付回调处理
     * @Author   zhuangqm
     * @DateTime 2017-02-24T11:30:36+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function weixnPayNotify($param){
    
        $payOBJ = new WeixinApp([
                "payment_info"=> $param['payment_info'],
                "order_info"=>$param['order_info'],
            ]);

        //对进入的参数进行远程数据判断
        //校验支付是否成功，和前面是否正确
        return $payOBJ->notify_verify($param['param']);
    }

    /**
     * 阿里支付请求
     * @Author   zhuangqm
     * @DateTime 2017-02-24T10:51:33+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function aliPayRequest($param){
    	$payOBJ = new alipay_app([
                "orderid"=>$order['orderid'],
                "pay_price"=>$order['pay_price'],
            ]);

        $data = $payOBJ->get_payurl();
    }

    /**
     * 阿里支付回调处理
     * @Author   zhuangqm
     * @DateTime 2017-02-24T11:35:09+0800
     * @return   [type]                   [description]
     */
    public static function aliPayNotify($param){

        //检验回调是否正确
        $payOBJ = new alipay_app($param);

        //对进入的参数进行远程数据判断
        //校验支付是否成功，和前面是否正确
        return $payOBJ->notify_verify($param);
    }

    /**
     * 通联微信支付回调处理
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:57:04+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function allinpayweixnPayNotify($param){

        $payOBJ = new Allinpay_weixin();

        return $payOBJ->notify_verify($param);
    }

    /**
     * 通联支付宝支付回调处理
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:57:35+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function allinpayaliPayNotify($param){
        $payOBJ = new Allinpay_ali();

        return $payOBJ->notify_verify($param);
    }

    /**
     * 通联快捷支付回调处理
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:57:35+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function allinpayquickPayNotify($param){
        $payOBJ = new Allinpay_quick();

        return $payOBJ->notify_verify($param);
    }

	/**
     * 支付宝支付结果回调处理
     * @Author   zhuangqm
     * @DateTime 2017-02-24T11:20:12+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
	public static function aliorderPay($param){

		$result = array('code'=>200,'server'=>10, 'data'=>array());

        if(empty($param['out_trade_no'])){
            //请勿提交非法参数
            $result['code'] = "7001";
            return $result;
        }

        
        
        //订单号
        $order_sn = $param['out_trade_no'];

        //获取订单信息
        //$order_info = Db::Table("DaysplanOrder")->getRow(['orderid'=>$param['out_trade_no']],"id,price,pay_status");

        $order_info = Model::ins("OrdOrder")->getRow(['orderno'=>$param['out_trade_no']],"*");

        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['orderstatus']>0){
            //订单已支付
            $result['code'] = 200;
            return $result;
        }

        $verify = self::aliPayNotify($param);

        //echo "======".$verify."=======";
        if(!$verify){
            //验证失败
            $result['code'] = "7002";
            return $result;
        }


        //修改订单支付成功后的状态
        //$update_order = $db_SysPayproviderapi->updateOrderBuy($order_info, $payment_info['f_paymethod'], $param);

        //修改订单支付成功后的状态
        $update_order = self::updateOrderBuy($order_info, 'ali_app', $param);
        
        return $update_order;
	}


	/**
	 * 微信订单支付处理结果
	 * @Author   zhuangqm
	 * @Datetime 2017-02-24T11:20:12+0800
	 * @return   [type]                   [description]
	 */
	public static function weixinorderPay($param){

		$result = array('code'=>'200', 'data'=>array());

        if(empty($param['order_sn'])){
            //请勿提交非法参数
            $result['code'] = "7001";
            return $result;
        }

        
        //订单号
        $order_sn = $param['order_sn'];
       	
       	//获取订单信息
        //$order_info = Db::Table("DaysplanOrder")->getRow(['orderid'=>$order_sn],"*");
        $order_info = Model::ins("OrdOrder")->getRow(['orderno'=>$order_sn],"*");
        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['orderstatus']>0){
            //没有该订单
            $result['code'] = "6002";
            return $result;
        }

        //回调判断处理
        $verify = self::weixnPayNotify([
        		"payment_info"=>$payment_info,
        		"order_info"=>$order_info,
        		"param"=>$param,
        	]);

       
        if(!$verify){
            //验证失败
            $result['code'] = "7002";
            return $result;
        }
 
        //修改订单支付成功后的状态
        $update_order = self::updateOrderBuy($order_info, 'weixin_app', $param);

        $result['data'] = $update_order;

        return $result;
	}

	/**
	 * 订单结果处理
	 * @Author   zhuangqm
	 * @Datetime 2017-02-24T11:20:12+0800
	 * @param    [type]                   $order_info    [description]
	 * @param    [type]                   $paymethod  [description]
	 * @param    [type]                   $param      [description]
	 * @return   [type]                               [description]
	 */
	public static function updateOrderBuy($order_info, $paymethod, $param){
		$update = [];
		//$pay_status = 0; //0未支付10支付成功20支付失败
        $status = -1; //订单状态0待付款1已付款待发货2已发货3确认收货4订单完结5取消
        //支付金额
        if($paymethod=='weixin_app')
            $total_fee = $param['total_fee']/100;
        
        if($paymethod=='ali_app')
            $total_fee = $param['total_amount'];
        
        /*
		if($order_info['price']>0 && $total_fee<$order_info['price'] ){
			$pay_status = 20;
		}else{
			$pay_status = 10;
            $status = 1;
		}

		$update['pay_status'] = $pay_status; 
		$update['pay_type'] = $paymethod;
		$update['pay_price'] = $total_fee;
		$update['pay_time'] = date("Y-m-d H:i:s");
        if($status>0)
            $update['status'] = $status;

		Model::ins("OrdOrder")->update($update,["id"=>$order_info['id']]);
        */
        
        Model::new("Amount.Amount")->add_cashamount([
                                                "userid"=>$order_info['customerid'],
                                                "amount"=>EnPrice($total_fee),
                                                "usertype"=>"2",
                                                "orderno"=>$order_info['orderno'],
                                                "flowtype"=>1,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusCash",
                                            ]);

        //执行订单支付流程
        

		//更新日志
		//ModelLog::updateLog($param['out_trade_no']);

		return ["code"=>"200"];
	}

    /**
     * 生成支付请求记录
     * @Author   zhuangqm
     * @DateTime 2017-04-01T11:56:23+0800
     * @param    [type]                   $param [description]
     */
    public function addPayOrder($param){
        
        $orderno = $param['orderno'];

        $payorderOBJ = Model::ins("PayOrder");

        $payorder = $payorderOBJ->getRow(["orderno"=>$param['orderno']],"id,pay_status");

        if(empty($payorder)){
            Model::ins("PayOrder")->insert([
                "orderno"=>$param['orderno'],
                "amount"=>$param['amount'],
                "pay_type"=>$param['pay_type'],
                "addtime"=>date("Y-m-d H:i:s"),
                "userid"=>$param['userid'],
            ]);
            return ["code"=>"200"];
        }else{

            if($payorder['pay_status']=='0'){
                return ["code"=>"200"];
            }else{
                return ["code"=>"30005"];
            }
        }

        return ["code"=>"400"];
    }

    /**
     * 获取支付记录
     * @Author   zhuangqm
     * @DateTime 2017-04-05T15:47:28+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function getPayOrder($param){
        $orderno = $param['orderno'];

        $payorderOBJ = Model::ins("PayOrder");

        $payorder = $payorderOBJ->getRow(["orderno"=>$param['orderno']],"*");

        if(empty($payorder)){

            return ["code"=>"1000","data"=>[]];
        }else{
            switch($payorder['pay_type']){
                case 'weixin_app':
                    $payorder['pay_type_name'] = "微信支付";
                    break;
                case 'ali_app':
                    $payorder['pay_type_name'] = "支付宝支付";
                    break;
                case 'allinpay_quick':
                    $payorder['pay_type_name'] = "快捷支付";
                    break;
                case 'allinpay_ali':
                    $payorder['pay_type_name'] = "支付宝支付";
                    break;
                case 'allinpay_weixin':
                    $payorder['pay_type_name'] = "微信支付";
                    break; 
                case 'balance':
                    $payorder['pay_type_name'] = "余额支付";
                    break;    
                default:
                    $payorder['pay_type_name'] = "";
                    break;
            }

            switch($payorder['pay_status']){
                case '0':
                    $payorder['pay_status_name'] = "支付失败";
                    break;
                case '1':
                    $payorder['pay_status_name'] = "支付成功";
                    break;
                case '2':
                    $payorder['pay_status_name'] = "支付成功";
                    break;
            }

            $payorder['amount']     = DePrice($payorder['amount']);
            $payorder['pay_money']  = DePrice($payorder['pay_money']);
        }

        return ["code"=>"200","data"=>$payorder];
    }

    // 更新支付请求状态
    public function updatePayOrder($param){

        $orderno    = $param['orderno'];

        $pay_money  = $param['pay_money'];

        $pay_type  = $param['pay_type'];

        $pay_num    = $param['pay_num']; //第三方支付ID

        $pay_code   = $param['pay_code']; //第三方支付单号

        $payorderOBJ = Model::ins("PayOrder");

        $payorder = $payorderOBJ->getRow(["orderno"=>$param['orderno']],"id,pay_status,amount");

        if(!empty($payorder) && $payorder['pay_status']==0){

            $payorderOBJ->update([
                    "pay_status"=>($pay_money>=$payorder['amount']?1:2),
                    "pay_time"=>date("Y-m-d H:i:s"),
                    "pay_money"=>$pay_money,
                    "pay_type"=>$pay_type,
                    "pay_num"=>$pay_num, 
                    "pay_code"=>$pay_code, 
                ],["orderno"=>$orderno]);
        }
    }

    /**
     * 判断是否余额不足
     * @Author   zhuangqm
     * @DateTime 2017-07-19T17:37:54+0800
     * @return   [type]                   [description]
     */
    public function checkbalance($param){

        $orderno        = $param['orderno'];
        $userid         = $param['userid'];
        $balancetype    = $param['balancetype']; // conamount,mallamount,recamount

        if(substr($orderno,0,4)=='MALL'){
            // 商城
            $order = Model::ins("OrdOrder")->getByNo($orderno,"id,customerid,totalamount");
        }

        // if(substr($orderno,0,3)=='REC'){
        //     $order = Model::ins("CusRecharge")->getRow(['orderno'=>$orderno],"id,customerid,amount");

        //     $order['totalamount'] = $order['amount'];
        // }

        if(substr($orderno,0,3)=='CON'){
            $order = Model::ins("ConOrder")->getRow(['orderno'=>$orderno],"id,customerid,totalamount");
        }

        if(!empty($order)){
            if($order['customerid'] == $userid){
                
                if(substr($orderno,0,4)=='MALL' || substr($orderno,0,3)=='CON'){
                    
                    // 商城
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$userid,
                        $balancetype=>$order['totalamount'],
                    ]);
                }

                //判断是否设置了支付密码
                $check_result = PaypwdModel::issetpaypwd(["userid"=>$userid]);
                

                return ["code"=>"200","data"=>[
                        "balance"=>$result['balance'],
                        "balanceinfo"=>$result['balanceinfo'],
                        "issetpaypwd"=>$check_result['code']=='200'?1:0,
                    ]];

            }else{
                return ["code"=>"1001","data"=>[]];
            }
        }else{
            return ["code"=>"40002","data"=>[]];
        }
    }
    
    /**
    * @user 余额支付
    * @param 
    * @author jeeluo copy zhuangqm
    * @date 2017年7月19日下午8:40:09
    */
    public function balancepay($param) {
        $paypwd         = $param['paypwd'];
        $orderno        = $param['orderno'];
        $userid         = $param['userid'];
        $balancetype    = $param['balancetype'];
        
        $paypwd_error_limit = 3;
        $ActLimitOBJ = Model::new("Sys.ActLimit");
        //支付密码错误3次，就提示给用户
        $check_actlimit = $ActLimitOBJ->check("paypwd".$userid,$paypwd_error_limit);
        if(!$check_actlimit['check']){
            return ["code"=>"50000"];
        }
        
        //判断支付密码
        $check_result = PaypwdModel::checkpaypwd($userid,$paypwd);

        if($check_result['code']=='200'){
            //校验成功
            
            // 判断余额是否足够
            $result = $this->checkbalance([
                "orderno"=>$orderno,
                "userid"=>$userid,
                "balancetype"=>$balancetype,
            ]);

            if($result['code']!='200' || $result['data']['balance']!=1)
                return ["code"=>"1003"];

            $flowid = Model::new("Amount.Flow")->getFlowId($orderno);
        
        
            if(substr($orderno,0,4)=='MALL'){

                $order_info = Model::ins("OrdOrder")->getRow(['orderno'=>$orderno],"id,customerid,totalamount");

                $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$orderno,
                    "amount"=>$order_info['totalamount'],
                    "pay_type"=>"balance",
                    "userid"=>$userid,
                ]);
        
                // 商城
                $result = OrderPayModel::orderpay([
                    "orderno"=>$orderno,
                    "userid"=>$userid,
                    "flowid"=>$flowid,
                    "balancepay"=>1,
                    "balancetype"=>$balancetype, // 余额支付类型
                ]);

                if($result['code']=='200')
                    Model::new("Pay.Pay")->updatePayOrder([
                            "orderno"=>$orderno,
                            "pay_money"=>$order_info['totalamount'],
                            "pay_type"=>"balance",
                        ]);

            }

            if(substr($orderno,0,3)=='CON'){
                
                if($balancetype!='recamount')
                    return ["code"=>"404"];

                $order_info = Model::ins("ConOrder")->getRow(['orderno'=>$orderno],"id,customerid,totalamount");

                $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$orderno,
                    "amount"=>$order_info['totalamount'],
                    "pay_type"=>"balance",
                    "userid"=>$userid,
                ]);

                // 商城
                $result = OrderPayModel::orderpay_con([
                    "orderno"=>$orderno,
                    "userid"=>$userid,
                    "flowid"=>$flowid,
                    "balancepay"=>1,
                    "balancetype"=>$balancetype, // 余额支付类型
                ]);

                if($result['code']=='200')
                    Model::new("Pay.Pay")->updatePayOrder([
                            "orderno"=>$orderno,
                            "pay_money"=>$order_info['totalamount'],
                            "pay_type"=>"balance",
                        ]);
            }

            if($result['code']!='200')
                return ["code"=>$result['code']];
//                 return $this->json($result['code']);
        
            return ["code"=>"200"];
//                 return $this->json("200");
        
        }else{
            if($check_result['code']=='50002'){
        
                $ActLimitOBJ->update("paypwd".$userid,3600); //冻结一小时
        
//                 return $this->json($check_result['code'],[],"密码输入错误，您还可以输入".($paypwd_error_limit>$check_actlimit['limitcount']?$paypwd_error_limit-$check_actlimit['limitcount']:0)."次");
                return ["code"=>$check_result['code'],"data"=>[],"msg"=>"密码输入错误，您还可以输入".($paypwd_error_limit>$check_actlimit['limitcount']?$paypwd_error_limit-$check_actlimit['limitcount']:0)."次"];
            }else{
//                 return $this->json($check_result['code']);
                return ["code"=>$check_result['code']];
            }
        }
    }
}