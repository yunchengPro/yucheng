<?php
namespace app\model\Conn;

use think\Config;

use app\lib\Model;

class OrderModel
{
        
    /**
     * 提交订单
     * @Author   zhuangqm
     * @Datetime 2017-10-07T17:40:15+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           amount
     *                                           pay_voucher
     *                                           mobile
     *                                           mobile_userid
     *                                           ]
     */
    public function addOrder($param){

        // 确认购买者身份 -只能是消费者
        $customer = MOdel::ins("CusCustomer")->getRow(["id"=>$param['customerid']],"id,role");

        if(empty($customer))
            return ["code"=>"2001"];

        // if($customer['role']!=1)
        //     return ["code"=>"1004"];

        $ConOrder = Model::ins("ConOrder");
        
        $orderno  = $ConOrder->getOrderNo();

        $con_config = Config::get("conn");

        $totalamount = $param['amount'];

        if($param['amount'] < 10000){
            $count = $param['amount']*$con_config['con_price'];
        }else{
            $count = $param['amount']*$con_config['con_more_price'];
        }

        $customer_row = Model::ins('CusCustomer')->getRow(['id'=>$param['customerid']],'username');
        $ret = $ConOrder->insert([
                "orderno"=>$orderno,
                "customerid"=>$param['customerid'],
                "cust_name"=>$customer_row['username'],
                "count"=>EnPrice($count),
                "totalamount"=>EnPrice($totalamount),
                "addtime"=>date("Y-m-d H:i:s"),
                "orderstatus"=>0,//1,
                "pay_voucher"=>$param['pay_voucher'],
                //"mobile"=>$param['mobile'],
                //"mobile_userid"=>$param['mobile_userid'],
                //"businessid"=>$param['businessid']
            ]);

        if($ret > 0){
            return [
                "code"=>"200",
                "orderno"=>$orderno,
            ];
            
        }else{
            return [
                "code"=>"400",
                "orderno"=>'',
                "msg"=>'添加错误'
            ];
        }
    }

    /**
     * 确认订单
     * @Author   zhuangqm
     * @Datetime 2017-10-07T17:57:31+0800
     * @param    [type]                   $param [
     *                                           orderno
     *                                     ]
     * @return   [type]                          [description]
     */
    public function confirmOrder($param){

        // 完结订单
        $ConOrder = Model::ins("ConOrder");

        $order = $ConOrder->getRow(["orderno"=>$param['orderno']],"*");
        // 订单不存在
        if(!empty($order))
            return ["code"=>"1001"];

        // 订单确认
        $ConOrder->update([
                "orderstatus"=>2,
                "confirmtime"=>date("Y-m-d H:i:s"),
            ],["orderno"=>$orderno]);

        $flowid = Model::new("Amount.Flow")->getFlowId($order['orderno']);

        // 增加砖石数量
        Model::new("Conn.Con")->addCon([
                "userid"=>$order['customerid'],
                "amount"=>$order['count'],
                "flowid"=>$flowid,
                "orderno"=>$orderno,
                "flowtype"=>10,
            ]);

        return ["code"=>"200"];

    }

    /**
     * 取消订单
     * @Author   zhuangqm
     * @Datetime 2017-10-08T00:32:22+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function cancelOrder($param){

        // 完结订单
        $ConOrder = Model::ins("ConOrder");

        $order = $ConOrder->getRow(["orderno"=>$param['orderno']],"*");
        // 订单不存在
        if(!empty($order))
            return ["code"=>"1001"];

        if($order['orderstatus']==2 || $order['orderstatus']==3)
            return ["code"=>"1002"];

        // 订单确认
        $ConOrder->update([
                "orderstatus"=>3,
                "confirmtime"=>date("Y-m-d H:i:s"),
            ],["orderno"=>$orderno]);


        return ["code"=>"200"];
    }
    
    /**
    * @user 检测用户是否有订单完结记录
    * @param $customerid 用户id
    * @author jeeluo
    * @date 2017年10月11日下午3:04:26
    */
    public function checkUserOrder($param) {
        
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        // 查询用户是否有完结的订单
        $where['customerid'] = $param['customerid'];
        $where['orderstatus'] = 2;
        
        $ConOrder = Model::ins("ConOrder");
        
        $order = $ConOrder->getRow($where,"id", "confirmtime desc, id desc");
        
        $orderid = !empty($order['id']) ? $order['id'] : 0;
        
        return ["code" => "200", "data" => $orderid];
    }
}
