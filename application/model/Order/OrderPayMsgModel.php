<?php
namespace app\model\Order;

use think\Config;
use app\lib\Model;


class OrderPayMsgModel
{
    

    /**
     * 实体店消费消息推送
     * @Author   zhuangqm
     * @DateTime 2017-06-06T11:23:17+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function sto_msg($param){

        $orderno = $param['orderno'];

        $userid = $param['userid'];

        $amount = $param['amount'];

        $businessid = $param['businessid'];

        //发送分润消息
        Model::new("Sys.Mq")->add([
            "url"=>"Amount.Profit.sengProfitMsg",
            "param"=>[
                "orderno"=>$orderno
            ],
        ]);
        //发送营业收入消息
        Model::new("Sys.Mq")->add([
            "url"=>"Msg.SendMsg.income",
            "param"=>[
                "userid"=>$userid,
                "amount"=>$amount,
                "bustype"=>2,
            ],
        ]);
        //更新实体店销量
        Model::new("Sys.Mq")->add([
            "url"=>"StoBusiness.Stobusiness.updateStoBusinessSalecount",
            "param"=>$businessid,
        ]);
        Model::new("Sys.Mq")->submit();

        return true;
    }

}
