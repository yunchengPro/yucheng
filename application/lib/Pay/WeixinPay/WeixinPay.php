<?php

namespace App\Lib\Pay\WeixinPay;

use App\Lib\Pay\WeixinPay\WxPayHelper;

/**
 * 微信支付
 */
class WeixinPay{

	protected $param;

	/**
	 * 构造函数
	 * @Author   zhuangqm
	 * @Datetime 2016-11-07T17:39:49+0800
	 * @param    array                   $param 
	 *           							orderid
	 *           							pay_price
	 */
	public function __construct($param){
		$this->param = $param;
	}

	/**
	 * 给对应的openid用户付款
	 * @Author   zhuangqm
	 * @Datetime 2016-11-23T16:41:18+0800
	 * @param    [type]                   $openid [description]
	 * @return   [type]                           [description]
	 */
	public function paytouser(){
		if($this->param['openid']!=''){

			$WxPayHelper = new WxPayHelper();
			$response = $WxPayHelper->paytouser($this->param);
			print_r($response);
		}

	}

	public function getPayOrder(){
		
		$orderBody = $this->orderBody;
		//订单编号
		$tade_no = $this->param['orderid'];
		//价格
		
		$total_fee = intval(strval($this->param['pay_price'] * 100));
		
		$WxPayHelper = new WxPayHelper();
		$response = $WxPayHelper->getPrePayOrder($orderBody, $tade_no, $total_fee);

		$ret = $WxPayHelper->getOrder($response['prepay_id']);

		return $ret;
	}

	/**
	 * [notify_verify description]
	 * @Author   zhuangqm
	 * @Datetime 2016-11-08T13:58:46+0800
	 * @return   [type]                   [description]
	 */
	public function notify_verify($param){
		
		if(strtoupper($param['result_code'])!='SUCCESS')
            return false;

        
        unset($param['uid']);
        unset($param['order_sn']);
        unset($param['payment_code']);
        unset($param['pay_price']);
        unset($param['pay_sn']);
        unset($param['pay_time']);
        unset($param['f_pay_num']);

        $wxPayHelper = new WxPayHelper();
        $verify_result = $wxPayHelper->verifyNotify($param);
       
        return $verify_result; 
	}
}