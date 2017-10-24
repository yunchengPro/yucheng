<?php
namespace app\model\Sys;

use app\lib\Model;

class LogModel
{

    /**
     * 添加支付回调日志--写mongodb
     * @Author   zhuangqm
     * @DateTime 2017-03-22T17:33:21+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function paylog($param){

        Model::ins("SysPayLog")->insert([
                "orderno"=>$param['orderno'],
                "paytype"=>$param['paytype'],
                "content"=>json_encode($param['param'],JSON_UNESCAPED_UNICODE),
                "addtime"=>date("Y-m-d H:i:s"),
                "trade_no"=>$param['trade_no'],
                "pay_amount"=>$param['pay_amount'],
            ]);

       /* Model::Mongo("SysPayLog")->insert([
                "orderno"=>$param['orderno'],
                "paytype"=>$param['paytype'],
                "content"=>json_encode($param['param'],JSON_UNESCAPED_UNICODE),
                "addtime"=>date("Y-m-d H:i:s"),
                "trade_no"=>$param['trade_no'],
                "pay_amount"=>$param['pay_amount'],
            ]);*/
    }

    

}