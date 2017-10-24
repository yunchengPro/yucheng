<?php

namespace app\lib\Pay\Weixin;

use app\lib\Pay\Weixin\WxPayHelper;

/**
 * 微信支付
 */
class WeixinApp{

	protected $param;

	protected $orderBody = "";

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

	public function getPayOrder(){
		
		
		//订单编号
		$tade_no = $this->param['orderno'];

		$orderBody = $this->param['orderBody']!=''?$this->param['orderBody']:$this->orderBody;

		//价格
		$total_fee = intval(strval($this->param['pay_price'] * 100));
		
		$WxPayHelper = new WxPayHelper();
		$response = $WxPayHelper->getPrePayOrder($orderBody, $tade_no, $total_fee);
		
		$ret = $WxPayHelper->getOrder($response['prepay_id']);

		return $ret;
	}

	/**
     * 下载对账单
     * @Author   zhuangqm
     * @DateTime 2017-07-03T15:01:16+0800
     * @return   [type]                   [description]
     */
	public function downOrderList($param=[]){
		$WxPayHelper = new WxPayHelper();
		$response = $WxPayHelper->downOrderList($param);

		return $response;
	}

	/**
	 * 
	 * @Author   zhuangqm
	 * @Datetime 2016-11-29T11:15:15+0800
	 * @return   [type]                   [description]
	 */
	public function redpack(){
		$tade_no = $this->param['orderno'];
		$total_fee = intval(strval($this->param['pay_price'] * 100));

		$WxPayHelper = new WxPayHelper();
		$response = $WxPayHelper->getRedpack('红包测试', $tade_no, $total_fee);

		//$ret = $WxPayHelper->getOrder($response['prepay_id']);

		return $response;
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