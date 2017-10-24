<?php
/**
 * 支付回调
 */
namespace app\api\controller\Thirdparty;
use app\api\ActionController;
use app\model\StoBusiness\StoOrdOrderModel;

class NoityController extends ActionController
{
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * 订单状态回调
     * @Author   zhuangqm
     * @DateTime 2017-09-18T16:21:25+0800
     * @return   [type]                   [description]
     */
    /*
    蜂鸟配送"系统已接单"状态返回JSON数据如下:
    {
        "open_order_code": "160103",
        "partner_order_code": "BG658907200991",
        "order_status": 1,
        "push_time": 1466095163344,
        "carrier_driver_name": "",
        "carrier_driver_phone": "",
        "description": "",
        "error_code":""
    }
    订单"已分配骑手"状态返回JSON数据如下:

    {
        "open_order_code": "160103",
        "partner_order_code": "BG658907200991",
        "order_status": 20,
        "push_time": 1466095163344,
        "carrier_driver_name": "张三",
        "carrier_driver_phone": "18602030493",
        "description": "",
        "station_name":"亦庄配送站",
        "station_tel":"18602393333",
        "error_code":""
    }
    订单"骑手已到店"状态返回JSON数据如下:

    {
        "open_order_code": "160103",
        "partner_order_code": "BG658907200991",
        "order_status": 80,
        "push_time": 1466095163344,
        "carrier_driver_name": "张三",
        "carrier_driver_phone": "18602030493",
        "description": "",
        "station_name":"亦庄配送站",
        "station_tel":"18602393333",
        "error_code":""
    }
    订单"配送中"的状态返回JSON数据如下:

    {
        "open_order_code": "160093",
        "partner_order_code": "BG659915200312",
        "order_status": 2,
        "push_time": 1466129638461,
        "carrier_driver_name": "周智伟",
        "carrier_driver_phone": "18501336429",
        "description": "",
        "error_code":""
    }
    订单"已取消"状态返回JSON数据如下:

    {
        "open_order_code": "160093",
        "partner_order_code": "BG659915200312",
        "order_status": 4,
        "push_time": 1466132529983,
        "carrier_driver_name": "周智伟",
        "carrier_driver_phone": "18501336429",
        "cancel_reason": 1,
        "description": "",
        "error_code":""
    }
    订单"系统拒单/配送异常"状态返回JSON数据如下:

    {
        "open_order_code": "160093",
        "partner_order_code": "BG659915200312",
        "order_status": 5,
        "push_time": 1466132529983,
        "carrier_driver_name": "",
        "carrier_driver_phone": "",
        "description": "订单重复",
        "error_code":"ORDER_REPETITION"
    }

     */
    public function orderstatusAction(){

        $param = $_POST;
        // print_r($param);
        $data = $param['data'];

        $data = json_decode($data,true);

        // 添加回调日志
        Model::new("Thirdparty.Thirdparty")->addNotifyLog([
            "partner_order_code"=>$data['partner_order_code'],
            "order_status"=>$data['order_status'],
            "description"=>$data['description'],
            "return_content"=>$param['data'],
        ]);

        // 不同订单状态，不同的订单处理
        print_r($data);

        switch ($data['order_status']) {
            case '1':
                // 蜂鸟配送"系统已接单"状态 发送系统消息
               // Model::ins('StoOrder')->update(['orderstatus'=>8],['orderno'=>$data['partner_order_code']]);
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderConfirm",
                    "url"=>"StoOrder.OrderMsg.orderacceptThirdparty",
                    "param"=>[
                        "orderno"=>$data['partner_order_code'],
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
                break;
            case '20': 
                // 订单"已分配骑手"状态 发送系统消息
                //Model::ins('StoOrder')->update(['orderstatus'=>20],['orderno'=>$data['partner_order_code']]);
                Model::ins('StoOrder')->update(['orderstatus'=>8],['orderno'=>$data['partner_order_code']]);
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderConfirm",
                    "url"=>"StoOrder.OrderMsg.orderallotThirdparty",
                    "param"=>[
                        "orderno"=>$data['partner_order_code'],
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
                break;
            case '80':
                // 订单"骑手已到店"状态 发送系统消息
                //Model::ins('StoOrder')->update(['orderstatus'=>80],['orderno'=>$data['partner_order_code']]);
                Model::ins('StoOrder')->update(['orderstatus'=>9],['orderno'=>$data['partner_order_code']]);
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderConfirm",
                    "url"=>"StoOrder.OrderMsg.ordertoshopThirdparty",
                    "param"=>[
                        "orderno"=>$data['partner_order_code'],
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
                break;
            case '2':
                // 订单"配送中"的状态 发送系统消息
                Model::ins('StoOrder')->update(['orderstatus'=>3,'delivertime'=>date('Y-m-d H:i:s')],['orderno'=>$data['partner_order_code']]);
                break;
            case '4':
                // 订单"已取消"状态 发送系统消息
                $StoOrderOBJ = Model::ins('StoOrder');
                $StoOrderReturnOBJ = Model::ins('StoOrderReturn');
                $StoOrdOrderModel = new StoOrdOrderModel();
                $order = $StoOrderOBJ->getRow(['orderno'=>$data['partner_order_code']]);
                if($StoOrderOBJ->update(["orderstatus"=>7,"cancelsource"=>3,'return_status'=>1],["id"=>$order['id']])){
                            
                    //取消订单 生成退款单
                    $StoOrderinfoOBJ->update(["cancelreason"=>$param['cancelreason']],["id"=>$order['id']]);
                    

                    $returnOder = [
                        'businessid' => $order['businessid'],
                        'businessname' => $order['businessname'],
                        'orderid' => $order['id'],
                        'orderno' => $order['orderno'],
                        'returnreason' => '配送订单被蜂鸟取消',
                        'customerid' => $order['customerid'],
                        'customer_name' => $order['customer_name'],
                        'returnamount' => $order['totalamount'],
                     ];
                    $returnOrderData =  $StoOrderReturnOBJ->getRow(['orderno'=>$order['orderno']],'id');
                    if(empty($returnOrderData)){
                        $returnOder['addtime'] = date('Y-m-d H:i:s');
                        $returnid = $StoOrderReturnOBJ->insert($returnOder);
                    }else{
                       
                        $StoOrderReturnOBJ->update($returnOder,['id'=>$returnOrderData['id']]);
                        $returnid = $returnOrderData['id'];
                    }

                    $return = $StoOrdOrderModel->returnPay(['orderno'=>$orderno]);
                    if($return['code'] == 200){
                        //更新退款单状态
                        $returnStatus = [
                            'actualamount' => $order['totalamount'],
                            'freight' => $order['actualfreight'],
                            'return_success_time' => date('Y-m-d H:i:s'),
                            'orderstatus' => 4,
                        ];
                        $StoOrderReturnOBJ->update($returnStatus,['id'=> $returnid]);
                        //更新订单状态
                        $orderStatus = [
                            'return_time' => date('Y-m-d H:i:s'),
                            'return_status' => 2,
                            'finish_time' => date('Y-m-d H:i:s')
                        ];
                        $StoOrderOBJ->update($orderStatus,['id'=>$order['id']]);

                    }
                           
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.orderConfirm",
                        "url"=>"StoOrder.OrderMsg.ordercancelThirdparty",
                        "param"=>[
                            "orderno"=>$data['partner_order_code'],
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                }
                break;
            case '5':
                // 订单"系统拒单/配送异常"状态
                //Model::ins('StoOrder')->update(['orderstatus'=>9],['orderno'=>$data['partner_order_code']]);
                $StoOrderOBJ = Model::ins('StoOrder');
                $StoOrderReturnOBJ = Model::ins('StoOrderReturn');
                $StoOrdOrderModel = new StoOrdOrderModel();
                $order = $StoOrderOBJ->getRow(['orderno'=>$data['partner_order_code']]);
                if($StoOrderOBJ->update(["orderstatus"=>7,"cancelsource"=>3,'return_status'=>1],["id"=>$order['id']])){
                            
                    //取消订单 生成退款单
                    $StoOrderinfoOBJ->update(["cancelreason"=>$param['cancelreason']],["id"=>$order['id']]);
                    

                    $returnOder = [
                        'businessid' => $order['businessid'],
                        'businessname' => $order['businessname'],
                        'orderid' => $order['id'],
                        'orderno' => $order['orderno'],
                        'returnreason' => '系统拒单/配送异常，取消订单',
                        'customerid' => $order['customerid'],
                        'customer_name' => $order['customer_name'],
                        'returnamount' => $order['totalamount'],
                     ];
                    $returnOrderData =  $StoOrderReturnOBJ->getRow(['orderno'=>$order['orderno']],'id');
                    if(empty($returnOrderData)){
                        $returnOder['addtime'] = date('Y-m-d H:i:s');
                        $returnid = $StoOrderReturnOBJ->insert($returnOder);
                    }else{
                       
                        $StoOrderReturnOBJ->update($returnOder,['id'=>$returnOrderData['id']]);
                        $returnid = $returnOrderData['id'];
                    }

                    $return = $StoOrdOrderModel->returnPay(['orderno'=>$orderno]);
                    if($return['code'] == 200){
                        //更新退款单状态
                        $returnStatus = [
                            'actualamount' => $order['totalamount'],
                            'freight' => $order['actualfreight'],
                            'return_success_time' => date('Y-m-d H:i:s'),
                            'orderstatus' => 4,
                        ];
                        $StoOrderReturnOBJ->update($returnStatus,['id'=> $returnid]);
                        //更新订单状态
                        $orderStatus = [
                            'return_time' => date('Y-m-d H:i:s'),
                            'return_status' => 2,
                            'finish_time' => date('Y-m-d H:i:s')
                        ];
                        $StoOrderOBJ->update($orderStatus,['id'=>$order['id']]);

                    }
                           
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.orderConfirm",
                        "url"=>"StoOrder.OrderMsg.ordercancelThirdparty",
                        "param"=>[
                            "orderno"=>$data['partner_order_code'],
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                }
                break;
            default:
                // 不做处理
                break;
        }

        exit;
    }
}
