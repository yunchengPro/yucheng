<?php
namespace app\model\Order;

use think\Config;
use app\lib\Model;
use app\model\Pay\PayModel;

use app\lib\Pay\Weixin\WeixinApp;
use app\lib\Pay\WeixinWeb\WeixinWeb;

use app\lib\Pay\Ali\alipay_app;
use app\lib\Pay\AliWap\AliWap;

use app\lib\Log;
use app\lib\ApiService\Sms as SmsApi;
use app\model\Sys\BountyUserModel;

class OrderPayModel
{
    
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
        $orderno = $param['out_trade_no'];

        $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");
        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']>0){
            //订单已支付
            $result['code'] = 200;
            return $result;
        }

        $verify = PayModel::aliPayNotify($param);

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
        //print_r($update_order);
        return $result;
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
        $orderno = $param['order_sn'];
       	
       	//获取订单信息
        //$order_info = Db::Table("DaysplanOrder")->getRow(['orderid'=>$order_sn],"*");
        $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");
        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']>0){
            //没有该订单
            $result['code'] = "6002";
            return $result;
        }

        //回调判断处理
        $verify = PayModel::weixnPayNotify([
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
     * 微信订单支付处理结果
     * @Author   zhuangqm
     * @Datetime 2016-11-08T11:47:06+0800
     * @return   [type]                   [description]
     */
    public static function weixinwebPay($param){
        $result = array('code'=>'200', 'data'=>array());
        //$db_SysPayproviderapi = Db::DbTable('SysPayproviderapi');
        $payOBJ = new WeixinWeb();

        //对进入的参数进行远程数据判断
        //校验支付是否成功，和前面是否正确
        $verify = $payOBJ->web_notify();

        if($verify){
            $orderno = $param['out_trade_no'];

            if(!empty($orderno)){

                $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");

                if(empty($order_info)){
                    //没有该订单
                    $result['code'] = 302;
                    return $result;
                }

                if($order_info['pay_status']>0){
                    //没有该订单
                    $result['code'] = "6002";
                    return $result;
                }

                //修改订单支付成功后的状态
                $update_order = self::updateOrderBuy($order_info, 'weixin_web', $param);

                $result['data'] = $update_order;

                return $result;
            }else{
                $result['code'] = "7001";
                return $result;
            }
        }else{
            $result['code'] = "7002";
            return $result;
        }

        return $result;
    }


    /**
     * 支付宝wap支付回调
     * @Author   zhuangqm
     * @Datetime 2016-12-26T11:51:04+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function aliwapPay($param){

        $result = array('code'=>200,'server'=>10, 'data'=>array());

        if(empty($param['out_trade_no'])){
            //请勿提交非法参数
            $result['code'] = "7001";
            return $result;
        }


        //检验回调是否正确
        $payOBJ = new AliWap();

        //对进入的参数进行远程数据判断
        //校验支付是否成功，和前面是否正确
        $verify = $payOBJ->notify_verify($param);

        if($verify){

            $orderno = $param['out_trade_no'];

            if(!empty($orderno)){

                $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");
                if(empty($order_info)){
                    //没有该订单
                    return "fail";
                }

                if($order_info['pay_status']>0){
                    return "success";
                }

                //修改订单支付成功后的状态
                $update_order = self::updateOrderBuy($order_info, 'ali_web', $param);

                $result['data'] = $update_order;

                return "success";
            }else{
                return "fail";
            }
                
            return "success";
        }else{
            return "fail";
        }

        return "fail";
        
    }


    /**
     * 通联微信支付
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:49:12+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function allinpayweixinorderPay($param){

        $result = array('code'=>'200', 'data'=>array());

        if(empty($param['cusorderid'])){
            //请勿提交非法参数
            $result['code'] = "7001";
            return $result;
        }

        // 只有0000表示交易成功或下单成功，其他为失败
        if($param['trxstatus']!='0000'){
            return ["code"=>"200"];
        }

        
        //订单号
        $orderno = $param['cusorderid'];
        
        //获取订单信息
        //$order_info = Db::Table("DaysplanOrder")->getRow(['orderid'=>$order_sn],"*");
        $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");
        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']>0){
            //没有该订单
            $result['code'] = "6002";
            return $result;
        }

        //回调判断处理
        $verify = PayModel::allinpayweixnPayNotify($param);


        if(!$verify){
            //验证失败
            $result['code'] = "7002";
            return $result;
        }
 
        //修改订单支付成功后的状态
        $update_order = self::updateOrderBuy($order_info, 'allinpay_weixin', $param);

        $result['data'] = $update_order;

        return $result;
    }


    /**
     * 通联支付宝支付
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:49:12+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function allinpayaliorderPay($param){

        $result = array('code'=>'200', 'data'=>array());

        if(empty($param['cusorderid'])){
            //请勿提交非法参数
            $result['code'] = "7001";
            return $result;
        }

        // 只有0000表示交易成功或下单成功，其他为失败
        if($param['trxstatus']!='0000'){
            return ["code"=>"200"];
        }

        //订单号
        $orderno = $param['cusorderid'];
        
        //获取订单信息
        //$order_info = Db::Table("DaysplanOrder")->getRow(['orderid'=>$order_sn],"*");
        $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");
        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']>0){
            //没有该订单
            $result['code'] = "6002";
            return $result;
        }
        //print_r($param);
        //回调判断处理      
        $verify = PayModel::allinpayaliPayNotify($param);


        if(!$verify){
            //验证失败
            $result['code'] = "7002";
            return $result;
        }
 
        //修改订单支付成功后的状态
        $update_order = self::updateOrderBuy($order_info, 'allinpay_ali', $param);

        $result['data'] = $update_order;

        return $result;
    }


    /**
     * 通联快捷支付
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:49:12+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function allinpayquickorderPay($param){

        $result = array('code'=>'200', 'data'=>array());

        if(empty($param['orderNo'])){
            //请勿提交非法参数
            $result['code'] = "7001";
            return $result;
        }

        // 只有0000表示交易成功或下单成功，其他为失败
        if($param['payResult']!='1'){
            return ["code"=>"200"];
        }

        $payment_code = !empty($param['payment_code'])?$param['payment_code']:'allinpay_quick';
        unset($param['payment_code']);
        
        //订单号
        $orderno = $param['orderNo'];
        
        //获取订单信息
        //$order_info = Db::Table("DaysplanOrder")->getRow(['orderid'=>$order_sn],"*");
        $order_info = Model::ins("PayOrder")->getRow(['orderno'=>$orderno],"*");
        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']>0){
            //没有该订单
            $result['code'] = "6002";
            return $result;
        }

        //回调判断处理      
        $verify = PayModel::allinpayquickPayNotify($param);

        if(!$verify){
            //验证失败
            $result['code'] = "7002";
            return $result;
        }
 
        //修改订单支付成功后的状态
        $update_order = self::updateOrderBuy($order_info, $payment_code, $param);

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

        $pay_num    = ''; // 第三方支付ID
        $pay_code   = ''; //第三方支付单号

        //支付金额
        if($paymethod=='weixin_app'){
            $total_fee = $param['total_fee']/100;

            $pay_num  = $param['openid'];
            $pay_code = $param['transaction_id'];
        }
        
        if($paymethod=='ali_app'){
            $total_fee = $param['total_amount'];

            $pay_num  = $param['buyer_logon_id'];
            $pay_code = $param['trade_no'];
        }

        if($paymethod=='weixin_web'){
            $total_fee = $param['total_fee']/100;

            $pay_num  = $param['openid'];
            $pay_code = $param['transaction_id'];
        }

        if($paymethod=='ali_web'){
            $total_fee = $param['total_amount'];

            $pay_num  = $param['buyer_logon_id'];
            $pay_code = $param['trade_no'];
        }

        if($paymethod=='allinpay_weixin'){
            $total_fee = $param['trxamt']/100;

            $pay_num  = $param['acct'];
            $pay_code = $param['cusorderid'];
        }

        if($paymethod=='allinpay_ali'){
            $total_fee = $param['trxamt']/100;

            $pay_num  = $param['acct'];
            $pay_code = $param['cusorderid'];
        }

        if($paymethod=='allinpay_quick'){
            $total_fee = $param['payAmount']/100;

            $pay_num  = '';
            $pay_code = $param['orderNo'];
        }

        if($paymethod=='allinpay_quick_web'){
            $total_fee = $param['payAmount']/100;

            $pay_num  = '';
            $pay_code = $param['orderNo'];
        }

        //更新支付请求状态
        Model::new("Pay.Pay")->updatePayOrder([
                "orderno"=>$order_info['orderno'],
                "pay_money"=>EnPrice($total_fee),
                "pay_type"=>$paymethod,
                "pay_num"=>$pay_num,
                "pay_code"=>$pay_code,
            ]);

        //生成流水号
        $flowid = Model::new("Amount.Flow")->getFlowId($order_info['orderno']);


        $flowtype = 9;
        
        // 判断重复充值
        $check_result = Model::new("Amount.Amount")->check_add_cashamount([
                                                "tablename"=>"AmoFlowCash",
                                                "userid"=>$order_info['userid'],
                                                "amount"=>EnPrice($total_fee),
                                                "orderno"=>$order_info['orderno'],
                                                "flowtype"=>$flowtype,
                                            ]);         

        if($check_result['code']!='200')
            return $check_result;

        //余额充值
        Model::new("Amount.Amount")->add_cashamount([
                                                "userid"=>$order_info['userid'],
                                                "amount"=>EnPrice($total_fee),
                                                "usertype"=>"2",
                                                "orderno"=>$order_info['orderno'],
                                                "flowtype"=>$flowtype,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCash",
                                                "flowid"=>$flowid,
                                            ]);
        
        $orderno = $order_info['orderno'];
        if(substr($orderno,0,3)=='CON'){
            // 购买钻石
            $orderpay_result = self::orderpay_con([
                "orderno"=>$order_info['orderno'],
                "userid"=>$order_info['userid'],
                "flowid"=>$flowid,
                "paymethod"=>$paymethod,
            ]);

        }

        if($orderpay_result['code']!='200')
            return ["code"=>$orderpay_result['code']];
        
		//更新日志
		//ModelLog::updateLog($param['out_trade_no']);

		return ["code"=>"200"];
	}
	
	/**
	* @user 快捷支付H5 页面跳转
	* @param 
	* @author jeeluo
	* @date 2017年7月28日下午6:05:51
	*/
	public static function allinpayquickOrderReturnView($params) {
	    $orderno = $params['orderno'];
	    $paystatus = $params['paystatus'];
	    $customerid = $params['customerid'];
	    $url = '';
	    
	    if($paystatus == 1) {
	        // 支付成功
	        if(substr($orderno,0,6)=='NNHSTO' || substr($orderno,0,6)=='NNHSTB'){
	            // 跳转到优惠付款成功页面
	            $url = '/StoBusiness/Index/paysuccess?orderno='.$orderno;
	        } else if(substr($orderno,0,5)=='NNHNR' || substr($orderno,0,5)=='NNHND' || substr($orderno,0,5)=='NNHNC'){
	            $url = '/Customer/Index/confirmSuccess?orderno='.$orderno.'&customerid='.$customerid;
	        }
	    } else {
	        // 支付失败
	        if(substr($orderno,0,6)=='NNHSTO' || substr($order,0,6)=='NNHSTB'){
	            // 根据id 获取实体店id值
	            $stoBusiness = Model::ins("StoBusiness")->getRow(["customerid"=>$customerid],"id");
	            $stoBusinessBase = Model::ins("StoBusinessBaseinfo")->getRow(["id"=>$stoBusiness['id']],"business_code");
	            $url = '/StoBusiness/Index/setpayamount?business_code='.$stoBusinessBase['business_code'];
	        } else if(substr($orderno,0,5)=='NNHNR' || substr($orderno,0,5)=='NNHND' || substr($orderno,0,5)=='NNHNC'){
	            $content = '支付有误';
	            $url = '/Customer/Index/error?content='.$content;
	        }
	    }
	    
	    return $url;
	}

    // 计算余额扣减方式
    protected static function balancepay($param){

        $orderno        = $param['orderno'];
        $userid         = $param['userid'];
        $order_amount   = $param['order_amount'];
        
        $amountModel = Model::ins("AmoAmount");
        $amount = $amountModel->getAmount($userid,"cashamount,profitamount,bullamount,comamount");

        $cashamount     = 0;
        $profitamount   = 0;
        $bullamount     = 0;
        $comamount     = 0;

        if(substr($orderno,0,6)=='NNHSTO' || substr($orderno,0,6)=='NNHSTB' || substr($orderno,0,6)=='NNHOTO'){
            // 实体店订单
            // 余额支付 优先扣牛粮
            if($amount['profitamount']>0){
                if($order_amount<=$amount['profitamount']){
                    $profitamount   = $order_amount;
                }else{
                    $profitamount   = $amount['profitamount'];
                    if($amount['cashamount']>=($order_amount-$profitamount)){
                        $cashamount     = $order_amount-$profitamount;
                    }else{
                        $cashamount     = $amount['cashamount'];
                        $comamount      = $order_amount-$profitamount-$cashamount;
                    }
                }
            }else{
                if($order_amount<=$amount['cashamount']){
                    $cashamount     = $order_amount;
                }else{
                    $cashamount     = $amount['cashamount'];
                    $comamount      = $order_amount-$cashamount;
                }
            }
            
        }else if(substr($orderno,0,5)=='NNHNR' || substr($orderno,0,5)=='NNHND' || substr($orderno,0,5)=='NNHNC' || substr($orderno,0,6)=='NNHTNC' || substr($orderno,0,6)=='NNHTNR' || substr($orderno,0,6)=='NNHTND'){
            // 牛人申请
            if($order_amount<=$amount['cashamount']){
                $cashamount     = $order_amount;
            }else{
                $cashamount     = $amount['cashamount'];
                $comamount      = $order_amount-$cashamount;
            }
            
        }else if(substr($orderno,0,5)=='NNHRE'){
            // 充值
            
        }else if(substr($orderno,0,6)=='NNHMOR'){
            // 多订单合并支付  
            

        }else{
            // 商城
            if($order_amount<=$amount['cashamount']){
                $cashamount     = $order_amount;
            }else{
                $cashamount     = $amount['cashamount'];
                $comamount      = $order_amount-$cashamount;
            }
        }

        return [
            "cashamount"=>$cashamount,
            "profitamount"=>$profitamount,
            "bullamount"=>$bullamount,
            "comamount"=>$comamount,
        ];
    }


    /**
     * 多订单合并支付流程
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @return   [type]                   [description]
     */
    public static function orderpay_more($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $orderModel = Model::ins("OrdOrderMore");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面

        //获取订单信息
        $ordermore_row = $orderModel->getByNo($orderno,"id,child_orderno,customerid,orderstatus,productamount,bullamount,totalamount");

        if(empty($ordermore_row) || empty($ordermore_row['child_orderno']))
            return ["code"=>"40002"];

        
        $orderno_arr = explode(",",$ordermore_row['child_orderno']);

        foreach($orderno_arr as $order_no){
            if($order_no!=''){
                $result = self::orderpay([
                        "orderno"=>$order_no,
                        "userid"=>$userid,
                        "flowid"=>$flowid,
                        "paymethod"=>$paymethod,
                    ]);
            }
        }
        
        $orderModel->update([
                "orderstatus"=>1,
            ],["orderno"=>$orderno]); 

        return ["code"=>"200"];
    }

    /**
     * 订单支付流程
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @return   [type]                   [description]
     */
    public static function orderpay($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $balancepay = $param['balancepay']; // 使用余额支付 1

        $orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        $order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");

        if(empty($order_row))
            return ["code"=>"40002"];

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付

        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();
        
        try{
            
            $cashamount     = 0;
            $profitamount   = 0;
            $bullamount     = 0;
            $comamount      = 0;

            // 余额支付
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['totalamount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $profitamount       = $payamount['profitamount'];
                $comamount          = $payamount['comamount'];

            }else{
                $cashamount = $order_row['totalamount'];
                
            }

            $bullamount = $order_row['bullamount'];

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "bullamount"=>$bullamount,
                    "profitamount"=>$profitamount,
                    "comamount"=>$comamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"20",
                    "flowtype_profit"=>"25",
                    "flowtype_bull"=>"33",
                    "flowtype_comcash"=>"20",
                ]);

            
            //判断是否已扣款
            if($result['code']=='200'){

                if(($order_row['productamount']>0) || ($order_row['productamount']>0 && $order_row['bullamount']>0)){
                    //进行分润
                    Model::new("Amount.Profit")->profit([
                            "userid"=>$userid,
                            "orderno"=>$orderno,
                            "flowid"=>$flowid,
                        ]);
                }
                //设置订单状态    
                $orderModel->update(["orderstatus"=>1],["orderno"=>$orderno]);
                //抢购订单状态
                Model::ins("OrdOrderBuy")->update(["status"=>1],["orderno"=>$orderno]);
                //订单支付信息
                Model::ins("OrdOrderPay")->insert([
                        "orderno"=>$orderno,
                        "totalamount"=>$order_row['totalamount'],
                        "bullamount"=>$order_row['bullamount'],
                        "pay_status"=>1,
                        "paytime"=>date("Y-m-d H:i:s"),
                        "pay_money"=>$order_row['totalamount'],
                        "pay_type"=>$paymethod,
                        "pay_bull"=>$order_row['bullamount'],
                    ]);

                //商家待收货款
                Model::new("Business.Settlement")->futpay(["orderno"=>$orderno]);

                // Model::new("Order.OrderCount")->deCount($userid,"count_pay");
                // Model::new("Order.OrderCount")->addCount($userid,"count_deliver");

                // 提交事务
                $amountModel->commit();   

                //发送消息
                // 操作成功  发送消息 用户下单发送消息给商家
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderConfirm",
                    "url"=>"Order.OrderMsg.orderbuswaitfahuo",
                    "param"=>[
                        "orderno"=>$orderno,
                    ],
                ]);
                Model::new("Sys.Mq")->submit();

                
                return ["code"=>"200"];

            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }


            
        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }


    /**
     * 购买钻石
     * @Author   zhuangqm
     * @DateTime 2017-10-12T15:00:34+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function orderpay_con($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("ConOrder")->getRow(['orderno'=>$orderno],"*");
        if(empty($order_row))
            return ["code"=>"40002"];

        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();

        try{
            //$amount = $amountModel->getAmount($userid,"cashamount,conamount");

            $pay_amount     = $order_row['totalamount'];

            // 增加销售额
            Model::ins("AmoAmount")->AddSaleAmount($userid,$pay_amount);

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "fromuserid"=>$userid,
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$pay_amount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"10",
                ]);

            //判断是否已扣款
            if($result['code']=='200'){

                // 增加钻石
                Model::new("Amount.Amount")->add_conamount([
                                                "userid"=>$order_row['customerid'],
                                                "amount"=>$order_row['count'],
                                                "usertype"=>"1",
                                                "orderno"=>$order_row['orderno'],
                                                "flowtype"=>10,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCon",
                                                "flowid"=>$flowid,
                                            ]);

                if($pay_amount>0){
                    //进行分润
                    Model::new("Amount.Profit")->add_con_profit([
                        "userid"=>$userid,
                        "amount"=>$pay_amount,
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);
                }
                
                //设置订单状态    
                Model::ins("ConOrder")->update([
                    "orderstatus"=>1,
                    "paytime"=>date("Y-m-d H:i:s"),
                    "payamount"=>$pay_amount,
                ],["orderno"=>$orderno]);
                
                // 提交事务
                $amountModel->commit();  

                return ["code"=>"200"];
            }else{
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }

        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }


    /**
     * 订单支付流程
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @return   [type]                   [description]
     */
    public static function orderpay_sto($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("StoPayFlow")->getRow(['pay_code'=>$orderno],"*");
       
        if(empty($order_row))
            return ["code"=>"40002"];

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付

        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();
        
        try{
            $amount = $amountModel->getAmount($userid,"cashamount,profitamount");

            $cashamount     = 0;
            $profitamount   = 0;
            $comamount      = 0;

            $pay_amount = $order_row['amount'];
            $bounty_amount = 0; //优惠金额
            $bonusamount   = 0; //奖励金
                
            if(substr($orderno, 0,6) =='NNHSTB'){
                $data = BountyUserModel::userGetBounty(['orderno'=>$orderno]);
                //var_dump( $data );
                if($data['code'] == 200 && $data['amount'] > 0 && $data['amount'] <= $pay_amount ){
                   $pay_amount =  $pay_amount - $data['amount'];
                   $bounty_amount = $data['amount'];
                }
            }

            // 判断是否使用了奖励金
            $bonus = Model::ins("BonusOrder")->getRow(["orderno"=>$orderno],"id,bonusamount");
            if(!empty($bonus)){
                $bonusamount = $bonus['bonusamount'];
                $pay_amount = $pay_amount-$bonusamount;
            }
          

            // 余额支付 优先扣牛粮
            if($balancepay==1){
                /*if($amount['profitamount']>0){
                    if($order_row['amount']<=$amount['profitamount']){
                        $cashamount     = 0;
                        $profitamount   = $order_row['amount'];
                    }else{
                        $profitamount   = $amount['profitamount'];
                        $cashamount     = $order_row['amount']-$profitamount;
                    }
                }else{
                    $cashamount         = $order_row['amount'];
                    $profitamount       = 0;
                }*/
                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$pay_amount,
                                        
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $profitamount       = $payamount['profitamount'];
                $comamount          = $payamount['comamount'];

            }else{
                // 第三方支付优先扣
                if($amount['cashamount']>0){
                    if($pay_amount<=$amount['cashamount']){
                        $cashamount     = $pay_amount;
                        $profitamount   = 0;
                    }else{
                        $cashamount     = $amount['cashamount'];
                        $profitamount   = $pay_amount-$cashamount;
                    }
                }else{
                    $cashamount         = 0;
                    $profitamount       = $pay_amount;
                }
            }

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "fromuserid"=>$userid,
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "profitamount"=>$profitamount,
                    "bullamount"=>$bullamount,
                    "comamount"=>$comamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"21",
                    "flowtype_profit"=>"25",
                    "flowtype_comcash"=>"21",
                ]);
           
            //判断是否已扣款
            if($result['code']=='200'){

                //优惠金额
                if($bounty_amount>0){
                    // 优惠补贴，从公司账户扣除
                    //公司扣减牛票
                    Model::new("Amount.Amount")->pay_com_cashamount([
                            "amount"=>$bounty_amount,
                            "flowid"=>$flowid,
                            "orderno"=>$orderno,
                            "flowtype"=>87,
                        ]);

                }

                // 扣除奖励金
                if($bonusamount>0){

                    Model::new("Amount.Bonus")->pay_bonusamount([
                            "userid"=>$userid,
                            "amount"=>$bonusamount,
                            "flowid"=>$flowid,
                            "orderno"=>$orderno,
                            "flowtype"=>143,
                        ]);


                }

                // 用户绑定实体店关系
                Model::new("StoBusiness.Stobusiness")->bindStoBusiness([
                        "businessid"=>$order_row['businessid'],
                        "customerid"=>$userid,
                    ]);

                
                if($pay_amount>0){
                    //进行分润
                    Model::new("Amount.Profit")->profit([
                            "userid"=>$userid,
                            "orderno"=>$orderno,
                            "flowid"=>$flowid,
                        ]);
                }
                

                //设置订单状态    
                Model::ins("StoPayFlow")->update([
                    "status"=>1,
                    "paytime"=>date("Y-m-d H:i:s"),
                    "payamount"=>$pay_amount,
                ],["pay_code"=>$orderno]);


               


                $Sto         = Model::ins("StoBusiness")->getRow(["id"=>$order_row['businessid']],"customerid");
                $stobusiness = Model::ins("StoPayFlowDiscount")->getRow(["orderno"=>$orderno,"businessid"=>$order_row['businessid']],"id,discount");
                if(empty($stobusiness))
                    $stobusiness = Model::ins("StoBusinessBaseinfo")->getRow(["id"=>$order_row['businessid']],"discount");

                $shouru = EnPrice(DePrice($order_row['amount']-$order_row['noinvamount'])*($stobusiness['discount']/100))+$order_row['noinvamount'];
                // 增加营业收入
                Model::new("Amount.Amount")->add_comcashamount([
                                                "fromuserid"=>$userid,
                                                "userid"=>$Sto['customerid'],
                                                "amount"=>$shouru,
                                                "orderno"=>$orderno,
                                                "flowtype"=>17,
                                                "tablename"=>"AmoFlowCusComCash",
                                                "flowid"=>$flowid,
                                                "parent_userid"=>$order_row['mangercustomerid'],
                                            ]);

                if($shouru>0){
                    //商家结算
                    Model::ins("AmoFlowStoCash")->insert([
                        "flowid"=>$flowid,
                        "userid"=>$Sto['customerid'], //属于用户
                        "businessid"=>$order_row['businessid'],
                        "fromuserid"=>$userid,
                        "orderno"=>$orderno,
                        "flowtype"=>17,
                        "direction"=>1,
                        "flowtime"=>date("Y-m-d H:i:s"),
                        "amount"=>$shouru,
                        "remark"=>$stobusiness['discount'],
                        //"amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                    ]);

                }
                // 提交事务
                $amountModel->commit();  

                //------------------发送消息-------------------
                if($shouru>0){
                    Model::new("Order.OrderPayMsg")->sto_msg([
                            "orderno"=>$orderno,
                            "userid"=>$Sto['customerid'],
                            "amount"=>$shouru,
                            "businessid"=>$order_row['businessid'],
                        ]);
                }
                //------------------发送消息-------------------

                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }


            
        } catch (\Exception $e) {
            // print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }


    /**
     * 充值支付流程
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @return   [type]                   [description]
     */
    public static function orderpay_re($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("CusRecharge")->getRow(['orderno'=>$orderno],"*");

        if(empty($order_row))
            return ["code"=>"40002"];

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付

        Model::ins("CusRecharge")->update([
                    "pay_status"=>1,
                    "pay_time"=>date("Y-m-d H:i:s"),
                    "pay_money"=>EnPrice($payamount),
                ],["orderno"=>$orderno]);

        

        return ["code"=>"200"];
    }

    /**
     * 牛创客申请
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @editor jeeluo 2017-04-05 20:56:25
     * @return   [type]                   [description]
     */
    public static function orderpay_nc($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面

        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");

        if(empty($order_row))
            return ["code"=>"40002"];
        
        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$order_row['mobile']],"id");
        $cus_relation = Model::ins("CusRelation")->getRow(["customerid"=>$cus['id'],"role"=>1,"parentrole"=>1],"parentid");

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{    

            $cashamount = 0;
            $comamount      = 0;
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['amount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $comamount          = $payamount['comamount'];

            }else{
                $cashamount = $order_row['amount'];
            }

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"46",
                    "comamount"=>$comamount,
                    "flowtype_comcash"=>"46",
                ]);

            //判断是否已扣款
            if($result['code']=='200'){

                Model::new("Customer.BullenRole")->apply($order_row);
                
                // 查询牛粉归属人
                if($cus_relation['parentid'] > -1) {
                    $parentCus = Model::ins("CusCustomer")->getRow(["id"=>$cus_relation['parentid']],"mobile");
                    // 因为apply有更新字段数据，所以重新获取role_apply_log数据
                    $newOrderRow = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
                    if($parentCus['mobile'] != $order_row['instrodcermobile'] || $newOrderRow['instrodcerrole'] == -1) {
                        $smsresult = SmsApi::api([
                            "param"=>json_encode([
                                "name"=>$newOrderRow['mobile']
                            ],JSON_UNESCAPED_UNICODE),
                            "mobile"=>$parentCus['mobile'],
                            "code"=>"SMS_94800057"
                        ]);
                    }
                }
                
                Model::ins("RoleApplyLog")->update([
                            "pay_status"=>1,
                            "pay_time"=>date("Y-m-d H:i:s"),
                            "pay_amount"=>$order_row['amount'],
                        ],["orderno"=>$orderno]);

                Model::new("Amount.Role")->pay_nc([
                        "userid"=>$userid,
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);
                /*//牛创客申请按1:1返回牛豆
                Model::new("Amount.Role")->roleapply_nc([
                        "userid"=>$userid,
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);

                //推荐牛创客返还
                Model::new("Amount.Role")->role_reco_nc([
                        "userid"=>$userid,
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);*/

                $amountModel->commit();   
                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }

        } catch (\Exception $e) {
            // print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }

    /**
     * 牛人申请
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @editor jeeluo 2017-04-05 20:56:35
     * @return   [type]                   [description]
     */
    public static function orderpay_nr($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");

        if(empty($order_row))
            return ["code"=>"40002"];
        
        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$order_row['mobile']],"id");
        $cus_relation = Model::ins("CusRelation")->getRow(["customerid"=>$cus['id'],"role"=>1,"parentrole"=>1],"parentid");

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付
        
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{

            $cashamount = 0;
            $comamount      = 0;
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['amount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $comamount          = $payamount['comamount'];

            }else{
                $cashamount = $order_row['amount'];
            }


            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"46",
                    "comamount"=>$comamount,
                    "flowtype_comcash"=>"46",
                ]);
            
            //判断是否已扣款
            if($result['code']=='200'){
                Model::new("Customer.BullPeoRole")->apply($order_row);

                // 查询牛粉归属人
                if($cus_relation['parentid'] > -1) {
                    $parentCus = Model::ins("CusCustomer")->getRow(["id"=>$cus_relation['parentid']],"mobile");
                    // 因为apply有更新字段数据，所以重新获取role_apply_log数据
                    $newOrderRow = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
                    if($parentCus['mobile'] != $order_row['instrodcermobile'] || $newOrderRow['instrodcerrole'] == -1) {
                        $smsresult = SmsApi::api([
                            "param"=>json_encode([
                                "name"=>$newOrderRow['mobile']
                            ],JSON_UNESCAPED_UNICODE),
                            "mobile"=>$parentCus['mobile'],
                            "code"=>"SMS_94705040"
                        ]);
                    }
                }

                Model::ins("RoleApplyLog")->update([
                            "pay_status"=>1,
                            "pay_time"=>date("Y-m-d H:i:s"),
                            "pay_amount"=>$order_row['amount'],
                        ],["orderno"=>$orderno]);

                Model::new("Amount.Role")->pay_nr([
                        "userid"=>$userid,
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);
                /*//牛人申请按1:1返回牛豆
                Model::new("Amount.Role")->roleapply_nr([
                        "userid"=>$userid,
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);

                //推荐牛人返还
                Model::new("Amount.Role")->role_reco_nr([
                        "userid"=>$userid,
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);*/

                $amountModel->commit(); 
                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }
        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }

    /**
     * 推荐牛人
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @editor jeeluo 2017-04-05 20:56:07
     * @return   [type]                   [description]
     */
    public static function orderpay_tnr($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("RoleRecoOr")->getRow(['orderno'=>$orderno],"*");

        if(empty($order_row))
            return ["code"=>"40002"];

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付
        
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{

            $cashamount = 0;
            $comamount      = 0;
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['amount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $comamount          = $payamount['comamount'];
            }else{
                $cashamount = $order_row['amount'];
            }

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"46",
                    "comamount"=>$comamount,
                    "flowtype_comcash"=>"46",
                ]);

            //判断是否已扣款
            if($result['code']=='200'){
                Model::new("Customer.BullPeoRole")->payPass($order_row);

                Model::ins("RoleRecoOr")->update([
                            "pay_status"=>1,
                            "status"=>2,
                            "examinetime" => date("Y-m-d H:i:s"),
                            "pay_time"=>date("Y-m-d H:i:s"),
                            "pay_amount"=>$order_row['amount'],
                        ],["orderno"=>$orderno]);

                $user = Model::ins("CusCustomer")->getIdByMobile($order_row['mobile']);
                Model::new("Amount.Role")->pay_tnr([
                        "userid"=>$user['id'],
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);
                /*//牛人申请按1:1返回牛豆
                Model::new("Amount.Role")->roleapply_nr([
                        "userid"=>$user['id'],
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);

                //推荐牛人返还
                Model::new("Amount.Role")->role_reco_nr([
                        "userid"=>$user['id'],
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);*/

                $amountModel->commit();   
                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();

                return ["code"=>$result['code']];
            }

        } catch (\Exception $e) {
            // print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }
    
    /**
    * @user 牛达人申请
    * @param 
    * @author jeeluo
    * @date 2017年6月12日下午4:01:00
    */
    public static function orderpay_nd($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];
    
        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1
    
        //$orderModel = Model::ins("OrdOrder");
    
        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
    
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
    
        if(empty($order_row))
            return ["code"=>"40002"];
        
        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$order_row['mobile']],"id");
        $cus_relation = Model::ins("CusRelation")->getRow(["customerid"=>$cus['id'],"role"=>1,"parentrole"=>1],"parentid");
    
        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
        // return ["code"=>"40001"]; //订单已支付

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{

            $cashamount = 0;
            $comamount      = 0;
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['amount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $comamount          = $payamount['comamount'];
            }else{
                $cashamount         = $order_row['amount'];
            }


            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                "userid"=>$userid,
                "orderno"=>$orderno,
                "cashamount"=>$cashamount,
                "flowid"=>$flowid,
                "flowtype_cash"=>"46",
                "comamount"=>$comamount,
                "flowtype_comcash"=>"46",
            ]);

            //判断是否已扣款
            if($result['code']=='200'){
                Model::new("Customer.BullTalentRole")->apply($order_row);
                
                // 查询牛粉归属人
                if($cus_relation['parentid'] > -1) {
                    $parentCus = Model::ins("CusCustomer")->getRow(["id"=>$cus_relation['parentid']],"mobile");
                    // 因为apply有更新字段数据，所以重新获取role_apply_log数据
                    $newOrderRow = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
                    if($parentCus['mobile'] != $order_row['instrodcermobile'] || $newOrderRow['instrodcerrole'] == -1) {
                        $smsresult = SmsApi::api([
                            "param"=>json_encode([
                                "name"=>$newOrderRow['mobile']
                            ],JSON_UNESCAPED_UNICODE),
                            "mobile"=>$parentCus['mobile'],
                            "code"=>"SMS_94755050"
                        ]);
                    }
                }

                Model::ins("RoleApplyLog")->update([
                    "pay_status"=>1,
                    "pay_time"=>date("Y-m-d H:i:s"),
                    "pay_amount"=>$order_row['amount'],
                ],["orderno"=>$orderno]);

                Model::new("Amount.Role")->pay_nd([
                    "userid"=>$userid,
                    "amount"=>$order_row['amount'],
                    "orderno"=>$orderno,
                    "flowid"=>$flowid,
                ]);
                /*//牛人申请按1:1返回牛豆
                 Model::new("Amount.Role")->roleapply_nr([
                     "userid"=>$userid,
                     "amount"=>$order_row['amount'],
                     "orderno"=>$orderno,
                     "flowid"=>$flowid,
                 ]);

                 //推荐牛人返还
                Model::new("Amount.Role")->role_reco_nr([
                    "userid"=>$userid,
                    "amount"=>$order_row['amount'],
                    "orderno"=>$orderno,
                    "flowid"=>$flowid,
                ]);*/

                $amountModel->commit();
                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }
        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }
    
    /**
    * @user 推荐牛达人
    * @param 
    * @author jeeluo
    * @date 2017年6月12日下午4:02:46
    */
    public static function orderpay_tnd($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];
    
        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1
    
        //$orderModel = Model::ins("OrdOrder");
    
        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
    
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("RoleRecoTalent")->getRow(['orderno'=>$orderno],"*");
    
        if(empty($order_row))
            return ["code"=>"40002"];
    
        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
        // return ["code"=>"40001"]; //订单已支付

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{

            $cashamount     = 0;
            $comamount      = 0;
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['amount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $comamount          = $payamount['comamount'];
            }else{
                $cashamount         = $order_row['amount'];
            }

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                "userid"=>$userid,
                "orderno"=>$orderno,
                "cashamount"=>$cashamount,
                "flowid"=>$flowid,
                "flowtype_cash"=>"46",
                "comamount"=>$comamount,
                "flowtype_comcash"=>"46",
            ]);

            //判断是否已扣款
            if($result['code']=='200'){
                Model::new("Customer.BullTalentRole")->payPass($order_row);

                Model::ins("RoleRecoTalent")->update([
                    "pay_status"=>1,
                    "status"=>2,
                    "examinetime" => date("Y-m-d H:i:s"),
                    "pay_time"=>date("Y-m-d H:i:s"),
                    "pay_amount"=>$order_row['amount'],
                ],["orderno"=>$orderno]);

                $user = Model::ins("CusCustomer")->getIdByMobile($order_row['mobile']);
                Model::new("Amount.Role")->pay_tnd([
                    "userid"=>$user['id'],
                    "amount"=>$order_row['amount'],
                    "orderno"=>$orderno,
                    "flowid"=>$flowid,
                ]);
                /*//牛人申请按1:1返回牛豆
                 Model::new("Amount.Role")->roleapply_nr([
                     "userid"=>$user['id'],
                     "amount"=>$order_row['amount'],
                     "orderno"=>$orderno,
                     "flowid"=>$flowid,
                 ]);

                 //推荐牛人返还
                Model::new("Amount.Role")->role_reco_nr([
                    "userid"=>$user['id'],
                    "amount"=>$order_row['amount'],
                    "orderno"=>$orderno,
                    "flowid"=>$flowid,
                ]);*/

                $amountModel->commit();
                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();

                return ["code"=>$result['code']];
            }

        } catch (\Exception $e) {
            // print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }

    /**
     * 推荐牛创客
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @editor jeeluo 2017-04-05 20:56:12
     * @return   [type]                   [description]
     */
    public static function orderpay_tnc($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $payamount  = $param['payamount']; //实际支付金额

        $balancepay = $param['balancepay']; // 使用余额支付 1

        //$orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        //$order_row = $orderModel->getByNo($orderno,"id,customerid,orderstatus,productamount,bullamount,totalamount");
        $order_row = Model::ins("RoleRecoEn")->getRow(['orderno'=>$orderno],"*");

        if(empty($order_row))
            return ["code"=>"40002"];

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付
        
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{

            $cashamount     = 0;
            $comamount      = 0;
            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$order_row['amount'],
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $comamount          = $payamount['comamount'];
            }else{
                $cashamount         = $order_row['amount'];
            }

            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"46",
                    "comamount"=>$comamount,
                    "flowtype_comcash"=>"46",
                ]);

            //判断是否已扣款
            if($result['code']=='200'){
                Model::new("Customer.BullenRole")->payPass($order_row);

                Model::ins("RoleRecoEn")->update([
                            "pay_status"=>1,
                            "status"=>2,
                            "examinetime" => date("Y-m-d H:i:s"),
                            "pay_time"=>date("Y-m-d H:i:s"),
                            "pay_amount"=>$order_row['amount'],
                        ],["orderno"=>$orderno]);

                $user = Model::ins("CusCustomer")->getIdByMobile($order_row['mobile']);
                Model::new("Amount.Role")->pay_tnc([
                        "userid"=>$user['id'],
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);
                /*//牛创客申请按1:1返回牛豆
                Model::new("Amount.Role")->roleapply_nc([
                        "userid"=>$user['id'],
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);

                //推荐牛创客返还
                Model::new("Amount.Role")->role_reco_nc([
                        "userid"=>$user['id'],
                        "amount"=>$order_row['amount'],
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);*/

                $amountModel->commit(); 
                return ["code"=>"200"];
            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }
        } catch (\Exception $e) {
            // print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }


    /**
     * 外卖订单支付流程
     * @Author   zhuangqm
     * @DateTime 2017-03-21T12:01:37+0800
     * @return   [type]                   [description]
     */
    public static function orderpay_oto($param){
        $orderno    = $param['orderno'];
        $userid     = $param['userid'];
        $flowid     = $param['flowid'];
        $paymethod  = $param['paymethod'];

        $balancepay = $param['balancepay']; // 使用余额支付 1

        $orderModel = Model::ins("StoOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包余额里面
        
        //获取订单信息
        $order_row = $orderModel->getRow(['orderno'=>$orderno],"id,customerid,orderstatus,productamount,totalamount");

        if(empty($order_row))
            return ["code"=>"40002"];

        //获取订单状态 判断订单是否已支付
        //if($order_row['orderstatus']>0)
           // return ["code"=>"40001"]; //订单已支付

        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();
        
        try{

            $cashamount = 0;
            $profitamount = 0;
            $comamount  = 0;

            $pay_amount = $order_row['totalamount'];
            $bonusamount   = 0; //奖励金

            // 判断是否使用了奖励金
            $bonus = Model::ins("BonusOrder")->getRow(["orderno"=>$orderno],"id,bonusamount");
            if(!empty($bonus)){
                $bonusamount = $bonus['bonusamount'];
                $pay_amount = $pay_amount-$bonusamount;
            }


            if($balancepay==1){

                $payamount = self::balancepay([
                                        "orderno"=>$orderno,
                                        "userid"=>$userid,
                                        "order_amount"=>$pay_amount,
                                    ]);

                $cashamount         = $payamount['cashamount'];
                $profitamount       = $payamount['profitamount'];
                $comamount          = $payamount['comamount'];
            }else{
                $cashamount         = $pay_amount;
            }
            
            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$userid,
                    "orderno"=>$orderno,
                    "cashamount"=>$cashamount,
                    "flowid"=>$flowid,
                    "flowtype_cash"=>"21",
                    "profitamount"=>$profitamount,
                    "flowtype_profit"=>"21",
                    "comamount"=>$comamount,
                    "flowtype_comcash"=>"21",
                ]);

            
            //判断是否已扣款
            if($result['code']=='200'){

                /*if($order_row['totalamount']>0){
                    //进行分润
                    Model::new("Amount.Profit")->profit([
                            "userid"=>$userid,
                            "orderno"=>$orderno,
                            "flowid"=>$flowid,
                        ]);
                }*/
                //设置订单状态     商家自动接单状态
                //$orderModel->update(["orderstatus"=>1,"payamount"=>$pay_amount],["orderno"=>$orderno]);
                
                $orderModel->update(["orderstatus"=>2,'ordertime'=>date('Y-m-d H:i:s'),"payamount"=>$pay_amount],["orderno"=>$orderno]);

                // 扣除奖励金
                if($bonusamount>0){

                    Model::new("Amount.Bonus")->pay_bonusamount([
                            "userid"=>$userid,
                            "amount"=>$bonusamount,
                            "flowid"=>$flowid,
                            "orderno"=>$orderno,
                            "flowtype"=>144,
                        ]);
                }


                // 提交事务
                $amountModel->commit();   


                //发送消息
                // 操作成功  发送消息 用户下单发送消息给商家
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderConfirm",
                    "url"=>"StoOrder.OrderMsg.orderstoautocancle",
                    "param"=>[
                        "orderno"=>$orderno,
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
                
                return ["code"=>"200"];

            }else{
                $amountModel->delRedis($userid);
                $amountModel->rollback();
                return ["code"=>$result['code']];
            }


            
        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userid);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
    }

}
