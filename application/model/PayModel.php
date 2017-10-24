<?php
/**
 * 支付------------已经不用
 */
namespace app\model;

use app\lib\Pay\Weixin\WeixinApp;

use app\lib\Pay\Ali\alipay_app;

class PayModel
{
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
}
