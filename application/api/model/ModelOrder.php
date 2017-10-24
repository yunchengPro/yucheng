<?php
namespace app\api\model;

use app\model\Pay\PayModel;

use app\lib\Pay\Weixin\WeixinApp;
use app\lib\Pay\Ali\alipay_app;

class ModelOrder
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
        $order_sn = $param['out_trade_no'];

        //获取订单信息
        $order_info = Db::DbTable("DaysplanOrder")->getRow(['orderid'=>$param['out_trade_no']],"id,price,pay_status");

        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']==10){
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
        $order_info = Db::DbTable("DaysplanOrder")->getRow(['orderid'=>$order_sn],"*");

        
        if(empty($order_info)){
            //没有该订单
            $result['code'] = 302;
            return $result;
        }

        if($order_info['pay_status']=='10'){
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
		$pay_status = 0; //0未支付10支付成功20支付失败
        $status = -1; //订单状态0未发货10待发货20已发货30订单完成40订单取消
        //支付金额
        if($paymethod=='weixin_app')
            $total_fee = $param['total_fee']/100;
        
        if($paymethod=='ali_app')
            $total_fee = $param['total_amount'];
        
		if($order_info['price']>0 && $total_fee<$order_info['price'] ){
			$pay_status = 20;
		}else{
			$pay_status = 10;
            $status = 10;
		}

		$update['pay_status'] = $pay_status; 
		$update['pay_type'] = $paymethod;
		$update['pay_price'] = $total_fee;
		$update['pay_time'] = date("Y-m-d H:i:s");
        if($status>0)
            $update['status'] = $status;

		Db::DbTable("DaysplanOrder")->update($update,["id"=>$order_info['id']]);

		//更新日志
		//ModelLog::updateLog($param['out_trade_no']);

		return ["code"=>"200"];
	}
}
