<?php
namespace app\lib\Pay\WeixinWeb;

use JsApiPay;
use WxPayUnifiedOrder;
use WxPayConfig;
use WxPayApi;

use PayNotifyCallBack;

require_once "../application/lib/Pay/WeixinWeb/lib/WxPay.Api.php";
require_once "../application/lib/Pay/WeixinWeb/WxPay.JsApiPay.php";

require_once "../application/lib/Pay/WeixinWeb/lib/WxPay.Notify.php";
require_once "../application/lib/Pay/WeixinWeb/notify.php";

class WeixinWeb{
	/**
     * H5微信支付
     * @Author   zhuangqm
     * @Datetime 2016-11-22T14:29:00+0800
     * @param    [type]                   $param [description]
     *                                           openid
     *                                           total_fee 打赏金额
     *                                           notify_url 回调地址
     * @return   [type]                          [description]
     */
    public function getWeixinPay($param){
        //①、获取用户openid
        $tools = new JsApiPay();
        //$openId = $tools->GetOpenid();
        $openid = $param['openid'];
       
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($param['body']);
        $input->SetAttach($param['attach']); //附加数据
        $input->SetOut_trade_no($param['out_trade_no']);
        $input->SetTotal_fee($param['total_fee']);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("pay");
        $input->SetNotify_url($param['notify_url']);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $order = WxPayApi::unifiedOrder($input);
        //echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        //var_dump($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);
       // var_dump($jsApiParameters);
    
        return $jsApiParameters;
    }

    /**
     * web支付回调处理
     * @Author   zhuangqm
     * @Datetime 2016-11-23T14:20:06+0800
     * @return   [type]                   [description]
     */
    public function web_notify(){

        $notify = new PayNotifyCallBack();
        return $notify->Handle(false);

    }
}