<?php
namespace app\model\User;

use app\lib\Model;

use think\Config;

class PaypwdModel
{

    /**
     * 判断支付密码是否正确
     * @Author   zhuangqm
     * @DateTime 2017-03-20T20:06:47+0800
     * @param    [type]                   $userid [description]
     * @param    [type]                   $paypwd [description]
     * @return   [type]                           [description]
     */
    public static function checkpaypwd($userid,$paypwd){
        if(!empty($userid) && !empty($paypwd)){

            $user_paypwd = Model::ins("CusCustomerPaypwd")->getRow(['id'=>$userid],"id,paypwd");

            if(!empty($user_paypwd)){
                if($user_paypwd['paypwd']!=''){
                    $app_key = Config::get("key.app_key");
                    $paypwd_code = md5($paypwd.$app_key);
                    if($paypwd_code==$user_paypwd['paypwd']){
                        return ["code"=>"200"];
                    }else{
                        return ["code"=>"50002"];
                    }
                }else{
                    return ["code"=>"50001"];
                }
            }else{
                return ["code"=>"50001"];
            }

        }else{
            return ['code'=>"404"];
        }
    }

    /**
     * 判断是否设置了支付密码
     * @Author   zhuangqm
     * @DateTime 2017-03-29T20:16:59+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function issetpaypwd($param){
        $userid = $param['userid'];

        $user_paypwd = Model::ins("CusCustomerPaypwd")->getRow(['id'=>$userid],"id,paypwd");

        if(!empty($user_paypwd))
            return ["code"=>"200"];
        else
            return ["code"=>"50001"];
    }
}