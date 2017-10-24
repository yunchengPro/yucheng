<?php
namespace app\model\Sys;

class PayOrderTypeModel
{
    /**
    * @user 微信类型
    * @param 
    * @author jeeluo
    * @date 2017年4月20日下午9:30:36
    */
    public static function weixinType() {
//         return 'weixin, weixin_app, weixin_web';
        return array("weixin", "weixin_app", "weixin_web","allinpay_weixin");
    }
    
    /**
    * @user 支付宝类型
    * @param 
    * @author jeeluo
    * @date 2017年4月20日下午9:30:44
    */
    public static function aliType() {
//         return 'ali, ali_app, ali_web';
        return array("ali", "ali_app", "ali_web","allinpay_ali");
    }
    
    /**
    * @user 银联支付
    * @param 
    * @author jeeluo
    * @date 2017年8月8日上午10:02:16
    */
    public static function quickType() {
        return array("allinpay_quick","allinpay_quick_web");
    }
}