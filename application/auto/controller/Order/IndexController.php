<?php
// +----------------------------------------------------------------------
// |  [ 订单处理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------
// 
namespace app\auto\controller\Order;
use app\auto\ActionController;
use app\lib\Model;
use think\Config;
use app\lib\Log;

class IndexController extends ActionController{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    } 

    /**
     * 自动取消订单 --2小时未付款订单自动取消
     * @Author   zhuangqm
     * @DateTime 2017-04-06T10:26:07+0800
     * @return   [type]                   [description]
     */
    public function cancelsorderAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        
        $endtime = date("Y-m-d H:i:s",strtotime($config['cancelsorder_time']));
        
        while(true){
            if(!empty($config['cancelsorder_time'])){

                $list = $OrdOrder->pageList("orderstatus=0 and addtime<'".$endtime."'","id,orderno","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){
                        $OrdOrder->update([
                                "orderstatus"=>5,
                            ],["id"=>$v['id']]);

                        $OrdOrderInfo->update([
                                "cancelsource"=>3,
                                "cancelreason"=>"自动取消",
                            ],["orderno"=>$v['orderno']]);
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }

    /**
     * 自动确认订单
     * @Author   zhuangqm
     * @DateTime 2017-04-06T10:28:04+0800
     * @return   [type]                   [description]
     */
    public function confirmorderAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrder       = Model::ins("OrdOrder");
        $orderModelOBJ  = Model::new("Order.Order");
        //$OrdOrderInfo   = Model::ins("OrdOrderInfo");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        //
        $endtime1 = date("Y-m-d H:i:s",strtotime($config['confirmorder'])); //15天自动确认收货
        $endtime2 = date("Y-m-d H:i:s",strtotime($config['longdateorder'])); //15天自动确认收货+延长收货
        
        while(true){
            if(!empty($config['confirmorder']) && !empty($config['longdateorder'])){

                

                $list = $OrdOrder->pageList("orderstatus=2 and ((delivertime<'".$endtime1."' and islongdate=0) or (delivertime<'".$endtime2."' and islongdate=1))","id,orderno,customerid","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 自动确认收货
                    foreach($list as $k=>$v){
                        $result = $orderModelOBJ->confirmOrder([
                            "customerid"=>$v['customerid'],
                            "orderno"=>$v['orderno'],
                            "auto_delivery_flag"=>1,
                        ]);
                        
                        // 自动同意退单提醒消息
                        Model::new("Sys.Mq")->add([
                            // "url"=>"Msg.SendMsg.orderAutoConfirm",
                            "url"=>"Order.OrderMsg.orderAutoConfirm",
                            "param"=>[
                                "orderno"=>$v['orderno'],
                            ],
                        ]);
                        Model::new("Sys.Mq")->submit();
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }


    /**
     * 订单关闭
     * @Author   zhuangqm
     * @DateTime 2017-04-06T10:28:04+0800
     * @return   [type]                   [description]
     */
    public function ordercloseAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrder       = Model::ins("OrdOrder");
        $orderModelOBJ  = Model::new("Order.Order");
        //$OrdOrderInfo   = Model::ins("OrdOrderInfo");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        //
        
        $endtime1 = date("Y-m-d H:i:s",strtotime($config['closeorder'])); //15天自动确认收货

        while(true){
            if(!empty($config['confirmorder']) && !empty($config['longdateorder'])){

                
                //echo "orderstatus=3 and confirm_time<'".$endtime1."'";
                $list = $OrdOrder->pageList("orderstatus=3 and confirm_time<'".$endtime1."'","id,orderno,customerid","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 自动确认收货
                    foreach($list as $k=>$v){
                        $result = $orderModelOBJ->closeorder([
                            "customerid"=>$v['customerid'],
                            "orderno"=>$v['orderno'],
                        ]);

                        //print_r($result);
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }


    /**
     * [autoEvaluateOrderAction 自动评价商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-20T09:56:57+0800
     * @return   [type]                   [description]
     */
    public function autoEvaluateOrderAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderItem       = Model::ins("OrdOrderItem");

        $ProProductInfo = Model::ins("ProProductInfo");
        $BusBusinessInfo = Model::ins("BusBusinessInfo");

        //获取用户名
        $UserModel = Model::new("User.User");

        $endtime1 = date("Y-m-d H:i:s",strtotime($config['closeorder'])); //15天自动确认收货

        while(true){
            if(!empty($config['confirmorder']) && !empty($config['longdateorder'])){

                
                //echo "orderstatus=3 and confirm_time<'".$endtime1."'";
                $list = $OrdOrder->pageList("orderstatus=3 and confirm_time<'".$endtime1."'","id,orderno,customerid","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 自动确认收货
                    foreach($list as $k=>$v){
                        $item = $OrdOrderItem->getRow(['orderno'=>$v['orderno']],'id,orderno,productid,customerid,skuid,prouctprice,productname');

                        foreach ($item as $key => $value) {
                            $pData = Model::ins('ProProduct')->getRow(['id'=>$value['productid']],'id,businessid');
                           
                            $userData = $UserModel->userInfo($value['customerid']);

                            if(!empty($pData)){
                           
                                $adData['frommemberid'] = $value['customerid'];
                                $adData['isanonymous'] = $value['isanonymous'] ;
                                $adData['frommembername'] = !empty($userData['frommembername']) ? $userData['frommembername'] : '匿名' ;
                                $adData['headpic'] = $userData['headpic'] ;
                                $adData['businessid'] = $value['businessid'] ;
                                $adData['productprice'] = $value['prouctprice'] ;
                                $adData['productid'] = $value['productid'] ;
                                $adData['productname'] =  $value['productname'] ;
                                $adData['scores'] =  5;
                                $adData['orderno'] = $value['orderno'] ;
                                $adData['content'] =  '自动评价商品';
                                $adData['addtime'] =  date('Y-m-d H:i:s');


                                $ProEvaluate = Db::Model('ProEvaluate');
                                $adData['evaluateauto'] = 1;

                                $data = $ProEvaluate->insert($adData);
                                
                                if($data > 0){
                                  

                                   
                                    $OrdOrderItem->update(['evaluate'=>1],['orderno'=>$adData['orderno'],'id'=>$value['id']]);//订单更新为已经评价


                                    $orderCount = $OrdOrder->getRow(['orderno'=>$adData['orderno']],'count(id) as count');

                                    $orderEvaluateCount = $OrdOrderItem->getRow(['orderno'=>$adData['orderno'],'evaluate'=>1],'count(id) as count');

                                    if($orderCount['count'] == $orderEvaluateCount['count']){
                                        $OrdOrder->update(['evaluate'=>1],['orderno'=>$adData['orderno']]);//订单更新为已经完成评价
                                    }


                                    $proArr = $ProProductInfo->getRow(['id'=>$value['productid']],"scores,salecount");

                                    $salecount = (int) $proArr['salecount'] + 1;
                                    
                                    $scores = (int) $proArr['scores'] + (int) $adData['scores'];
                                    
                                    $ProProductInfo->update(["scores"=>$scores,"salecount"=>$salecount,"lastevaluateid"=>$data],["id"=>$value['productid']]);

                                    $newproArr = $ProProductInfo->getRow(['id'=>$value['productid']],"scores,salecount");
                                    
                                    $busScores = round(($newproArr['scores']/$newproArr['salecount']),2);
                                    $ProProductInfo->update(['evaluatecount'=>$newproArr['salecount']],['id'=>$value['productid']]);
                                    //更新商家评分
                                    $BusBusinessInfo->update(['scores'=>$busScores],["id"=>$pData['businessid']]);

                                }
                            }

                        }
                       
                      
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }


    /**
     * 退款申请，7天没审核，自动退款
     * @Author   zhuangqm
     * @editor jeeluo 2017-04-19 11:21:42
     * @DateTime 2017-04-06T10:26:07+0800
     * @return   [type]                   [description]
     */
    public function return_price_dayAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;
        
        $OrdOrderReturn       = Model::ins("OrdOrderReturn");
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderItem   = Model::ins("OrdOrderItem");
        $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        $OrdReturnLog   = Model::ins("OrdReturnLog");
        $OrdOrderLogistics = Model::ins("OrdOrderLogistics");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        $endtime = date("Y-m-d H:i:s",strtotime($config['return_price_day']));
        while(true){
            if(!empty($config['return_price_day'])){

                $list = $OrdOrderReturn->pageList("return_type=1 AND orderstatus=1 AND starttime<'".$endtime."'","*","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    // 退款
                    foreach($list as $k=>$v){
                        
                        $selfItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND productid=".$v['productid']." AND skuid=".$v['skuid'], "id");
                        // 查询该订单下是否有未完结的商品
                        $otherItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND enable=1 AND id!=".$selfItem['id'], "id");
                        
                        Model::ins("AmoAmount")->startTrans();
                        try {
                            
                            // 写入记录表
                            $OrdReturnLog->insert([
                                "returnno"=>$v['returnno'],
                                "orderno"=>$v['order_code'],
                                "productid"=>$v['productid'],
                                "skuid"=>$v['skuid'],
                                "actionsource"=>3,
                                "customerid"=>$v['customerid'],
                                "businessid"=>$v['business_id'],
                                "orderstatus"=>4,
                                "content"=>"自动退款",
                                "addtime"=>date('Y-m-d H:i:s', time())
                            ]);
                            
                            // 查询订单是否有发货了
                            $orderLogistics = $OrdOrderLogistics->getRow(array("orderno" => $v['order_code']), "express_no");
                            
                            if(!empty($orderLogistics['express_no'])) {
                                // 已发货
                                Model::new("Business.Settlement")->returnpay(array("returnno"=>$v['returnno']));
                            } else {
								Model::new("Business.Settlement")->returnfutpay(array("returnno"=>$v['returnno']));
							}

                            //生成流水号
                            $flowid = Model::new("Amount.Flow")->getFlowId($v['order_code']);

                            $v['returnamount'] = $v['returnamount']+$v['freight'];
                            // 退款金额回到用户账号中
                            if($v['returnamount']>0)
                                Model::new("Amount.Amount")->add_cashamount([
                                                    "userid"=>$v['customerid'],
//                                                     "amount"=>EnPrice($v['returnamount']),
                                                    "amount" => $v['returnamount'],
                                                    "usertype"=>"2",
                                                    "orderno"=>$v['order_code'],
                                                    "flowtype"=>48,
                                                    "role"=>1,
                                                    "tablename"=>"AmoFlowCusCash",
                                                    "flowid"=>$flowid,
                                                ]);

                            if($v['returnbull']>0)
                                Model::new("Amount.Amount")->add_bullamount([
                                                    "userid"=>$v['customerid'],
//                                                     "amount"=>EnPrice($v['returnbull']),
                                                    "amount" => $v['returnbull'],
                                                    "usertype"=>"2",
                                                    "orderno"=>$v['order_code'],
                                                    "flowtype"=>49,
                                                    "role"=>1,
                                                    "tablename"=>"AmoFlowCusBull",
                                                    "flowid"=>$flowid,
                                                ]);
                            
                            // 调用退款分润方法
                            $profit['orderno'] = $v['order_code'];
                            $profit['userid'] = $v['customerid'];
                            Model::new("Amount.Profit")->deductionprofit($profit);
							
							// 退单结束
                            $OrdOrderReturn->modify([
                                "orderstatus" => 4,
                            ], ["id" => $v['id']]);
                            
                            // 订单商品状态修改
                            $OrdOrderItem->modify([
                                "enable" => -1,
                            ], ["id" => $selfItem['id']]);
                            
                            // 自动同意退单提醒消息
                            Model::new("Sys.Mq")->add([
                                // "url"=>"Msg.SendMsg.orderRefuseOragree",
                                "url"=>"Order.OrderMsg.orderRefuseOragree",
                                "param"=>[
                                    "returnno"=>$v['returnno'],
                                    "type"=>1,
                                ],
                            ]);
                            Model::new("Sys.Mq")->submit();
                            
                            if(empty($otherItem)) {
                                
                                $OrdOrderInfo->modify([
                                    "cancelsource"=>3,
                                    "cancelreason"=>"自动取消",
                                ],["orderno"=>$v['order_code']]);
                                // 订单结束
                                $OrdOrder->modify([
                                    "orderstatus" => 5,
                                ], ["orderno" => $v['order_code']]);
                                
                                // 订单关闭消息提醒
                                Model::new("Sys.Mq")->add([
                                    // "url"=>"Msg.SendMsg.ordercose",
                                    "url"=>"Order.OrderMsg.ordercose",
                                    "param"=>[
                                        "orderno"=>$v['order_code']
                                    ],
                                ]);
                                Model::new("Sys.Mq")->submit();
                            }
                            
                            Model::ins("AmoAmount")->commit();
                        } catch (\Exception $e) {
                            Model::ins("AmoAmount")->rollback();
                            Log::add($e,__METHOD__);
                        }
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }
    
    /**
    * @user 退货申请，3天没审核，自动审核通过
    * @param 
    * @author jeeluo
    * @date 2017年4月19日下午2:10:31
    */
    public function return_exam_dayAction() {
        $config = Config("order");
        
        $pagesize = 50;
        $page = 1;
        
        $OrdOrder       = Model::ins("OrdOrder");
//         $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        $OrdOrderReturn = Model::ins("OrdOrderReturn");
        $OrdOrderItem   = Model::ins("OrdOrderItem");
        $OrdReturnLog   = Model::ins("OrdReturnLog");
        $OrdOrderLogistics = Model::ins("OrdOrderLogistics");
        $endtime = date('Y-m-d H:i:s', strtotime($config['return_exam_day']));
        while(true) {
            if(!empty($config['return_exam_day'])) {
                $list = $OrdOrderReturn->pageList("((return_type=2 AND orderstatus=11) OR (return_type=1 AND orderstatus=1)) AND starttime<'".$endtime."'","id,customerid,productid,skuid,order_code,productnum,returnno,business_id,return_type,returnamount,returnbull","id asc",0,$page,$pagesize);
                $page+=1;
                
                if(!empty($list)) {
                    foreach ($list as $k => $v) {
                        $selfItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND productid=".$v['productid']." AND skuid=".$v['skuid'], "id,productnum");
                        
                        $goodsstatus = 1;
                        $returnstatus = 12;
                        $logstatus = 3;
                        
//                         $item_arr = array("productnum" => $selfItem['productnum']-$v['productnum']);
                        $item_arr = array("intended_return" => $v['productnum']);
                        if($selfItem['productnum'] - $v['productnum'] == 0) {
                            $otherItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND enable=1 AND id!=".$selfItem['id'], "id");
                            if($v['return_type'] == 1) {
                                $returnstatus = 4;
                                $logstatus = 4;
                                if(empty($otherItem)) {
                                    // 订单结束
                                    $goodsstatus = 2;
                                } else {
                                    // 商品结束
                                    $item_arr = array("enable" => "-1");
                                }
                            }
                        }
                        
                        Model::ins("AmoAmount")->startTrans();
                        try {
                            // 修改订单商品信息
                            $OrdOrderItem->modify($item_arr, ["id"=>$selfItem['id']]);
                            // 写入记录表
                            $OrdReturnLog->insert([
                                "returnno"=>$v['returnno'],
                                "orderno"=>$v['order_code'],
                                "productid"=>$v['productid'],
                                "skuid"=>$v['skuid'],
                                "actionsource"=>3,
                                "customerid"=>$v['customerid'],
                                "businessid"=>$v['business_id'],
                                "orderstatus"=>$logstatus,
                                "content"=>"自动审核通过",
                                "addtime"=>date('Y-m-d H:i:s', time())
                            ]);
                            
                            // 自动同意退单提醒消息
                            Model::new("Sys.Mq")->add([
                                // "url"=>"Msg.SendMsg.orderRefuseOragree",
                                "url"=>"Order.OrderMsg.orderRefuseOragree",
                                "param"=>[
                                    "returnno"=>$v['returnno'],
                                    "type"=>1,
                                ],
                            ]);
                            Model::new("Sys.Mq")->submit();
                            
                            // 修改订单状态
                            if($goodsstatus == 2) {
                                $OrdOrder->modify([
                                    "orderstatus"=>5,
                                ], ["orderno"=>$v['order_code']]);
                                
                                // 订单关闭消息提醒
                                Model::new("Sys.Mq")->add([
                                    // "url"=>"Msg.SendMsg.ordercose",
                                    "url"=>"Order.OrderMsg.ordercose",
                                    "param"=>[
                                        "orderno"=>$v['order_code']
                                    ],
                                ]);
                                Model::new("Sys.Mq")->submit();
                            }
                            
                            // 假如退单类型为退款，进行退款和退款分润
                            if($v['return_type'] == 1) {
                                
                                // 查询订单是否有发货了
                                $orderLogistics = $OrdOrderLogistics->getRow(array("orderno" => $v['order_code']), "express_no");
                                
                                if(!empty($orderLogistics['express_no'])) {
                                    // 已发货
                                    Model::new("Business.Settlement")->returnpay(array("returnno"=>$v['returnno']));
                                }

                                //生成流水号
                                $flowid = Model::new("Amount.Flow")->getFlowId($v['order_code']);
                                // 退款金额回到用户账号中
                                if($v['returnamount']>0)
                                    Model::new("Amount.Amount")->add_cashamount([
                                        "userid"=>$v['customerid'],
                                        "amount" => $v['returnamount'],
                                        "usertype"=>"2",
                                        "orderno"=>$v['order_code'],
                                        "flowtype"=>48,
                                        "role"=>1,
                                        "tablename"=>"AmoFlowCusCash",
                                        "flowid"=>$flowid,
                                    ]);
                                
                                if($v['returnbull']>0)
                                    Model::new("Amount.Amount")->add_bullamount([
                                        "userid"=>$v['customerid'],
                                        "amount" => $v['returnbull'],
                                        "usertype"=>"2",
                                        "orderno"=>$v['order_code'],
                                        "flowtype"=>49,
                                        "role"=>1,
                                        "tablename"=>"AmoFlowCusBull",
                                        "flowid"=>$flowid,
                                    ]);
                                
                                $profit['orderno'] = $v['order_code'];
                                $profit['userid'] = $v['customerid'];
                                Model::new("Amount.Profit")->deductionprofit($profit);
                            }
                            // 修改退单状态
                            $OrdOrderReturn->modify([
                                "orderstatus"=>$returnstatus,
                                "examinetime"=>getFormatNow(),
                            ], ["id"=>$v['id']]);
                            
                            Model::ins("AmoAmount")->commit();
                        } catch (\Exception $e) {
                            Model::ins("AmoAmount")->rollback();
                            Log::add($e,__METHOD__);
                        }
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;
            }else{
                break;
            }
        }
    }
    
    /**
    * @user 退货审核通过  用户不填写物流信息 自动取消退货
    * @param 
    * @author jeeluo
    * @date 2017年9月7日下午3:27:42
    */
    public function return_goods_expressAction() {
        $config = Config("order");
        
        $pagesize = 50;
        $page = 1;
        
        $OrdOrderReturn       = Model::ins("OrdOrderReturn");
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderItem   = Model::ins("OrdOrderItem");
        $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        $OrdReturnLog   = Model::ins("OrdReturnLog");
        $endtime = date("Y-m-d H:i:s",strtotime($config['return_goods_express']));
        while(true) {
            if(!empty($config['return_goods_express'])) {
                $list = $OrdOrderReturn->pageList("return_type=2 AND orderstatus=12 AND expressname = '' AND expressnumber = '' AND examinetime<'".$endtime."'","id,customerid,productid,skuid,order_code,productnum,returnno,business_id,returnamount,returnbull","id asc",0,$page,$pagesize);
                $page+=1;
                
                if(!empty($list)) {
                    foreach ($list as $k => $v) {
                        $selfItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND productid=".$v['productid']." AND skuid=".$v['skuid'], "id,productnum,returnnum");
                        $item_arr['intended_return'] = 0;
                        
                        Model::ins("AmoAmount")->startTrans();
                        try {
                            // 订单商品状态修改
                            $OrdOrderItem->modify($item_arr, ["id" => $selfItem['id']]);
                            // 写入记录表
                            $OrdReturnLog->insert([
                                "returnno"=>$v['returnno'],
                                "orderno"=>$v['order_code'],
                                "productid"=>$v['productid'],
                                "skuid"=>$v['skuid'],
                                "actionsource"=>3,
                                "customerid"=>$v['customerid'],
                                "businessid"=>$v['business_id'],
                                "orderstatus"=>5,
                                "content"=>"自动取消退货",
                                "addtime"=>date('Y-m-d H:i:s', time())
                            ]);
                            // 退单关闭消息提醒(不确定)
                            
                            // 退单结束
                            $OrdOrderReturn->modify([
                                "orderstatus" => 14,
                                "examinetime"=>date('Y-m-d H:i:s', time())
                            ], ["id" => $v['id']]);
                            
                            Model::ins("AmoAmount")->commit();
                        } catch (\Exception $e) {
                            Model::ins("AmoAmount")->rollback();
                            Log::add($e,__METHOD__);
                        }
                    } 
                }
                if(count($list)==0 || count($list)<$pagesize)
                    break;
            } else {
                break;
            }
        }
    }
    
    /**
    * @user 退货审核通过，15天未确认收货，自动确认收货
    * @param 
    * @author jeeluo
    * @date 2017年4月19日下午2:32:36
    */
    public function return_goods_dayAction() {
        $config = Config("order");
        
        $pagesize = 50;
        $page = 1;
        
        $OrdOrderReturn       = Model::ins("OrdOrderReturn");
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderItem   = Model::ins("OrdOrderItem");
        $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        $OrdReturnLog   = Model::ins("OrdReturnLog");
        $endtime = date("Y-m-d H:i:s",strtotime($config['return_goods_day']));
        while(true) {
            if(!empty($config['return_goods_day'])) {
                $list = $OrdOrderReturn->pageList("return_type=2 AND orderstatus=12 AND examinetime<'".$endtime."'","id,customerid,productid,skuid,order_code,productnum,returnno,business_id,returnamount,returnbull","id asc",0,$page,$pagesize);
                $page+=1;
                
                if(!empty($list)) {
                    foreach ($list as $k => $v) {
                        $selfItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND productid=".$v['productid']." AND skuid=".$v['skuid'], "id,productnum,returnnum");
                        // 查询该订单下是否有未完结的商品
                        $otherItem = $OrdOrderItem->getRow("orderno='".$v['order_code']."' AND enable=1 AND id!=".$selfItem['id'], "id");
                        
                        Model::ins("AmoAmount")->startTrans();
                        
                        $item_arr = array("returnnum" => $selfItem['returnnum']+$v['productnum'], "intended_return" => 0);
                        try {
                            
                            if($selfItem['productnum'] - $v['productnum'] == 0) {
                                
                                $item_arr['enable'] = -1;
                                
                                if(empty($otherItem)) {
                                
                                    $OrdOrderInfo->modify([
                                        "cancelsource"=>3,
                                        "cancelreason"=>"自动取消",
                                    ],["orderno"=>$v['order_code']]);
                                    // 订单结束
                                    $OrdOrder->modify([
                                        "orderstatus" => 5,
                                    ], ["orderno" => $v['order_code']]);
                                }
                            } else {
                                $item_arr['productnum'] = $selfItem['productnum'] - $v['productnum'];
                            }
                            
                            // 订单商品状态修改
                            $OrdOrderItem->modify($item_arr, ["id" => $selfItem['id']]);
                            // 写入记录表
                            $OrdReturnLog->insert([
                                "returnno"=>$v['returnno'],
                                "orderno"=>$v['order_code'],
                                "productid"=>$v['productid'],
                                "skuid"=>$v['skuid'],
                                "actionsource"=>3,
                                "customerid"=>$v['customerid'],
                                "businessid"=>$v['business_id'],
                                "orderstatus"=>4,
                                "content"=>"自动确认退货",
                                "addtime"=>date('Y-m-d H:i:s', time())
                            ]);
                            
                            Model::new("Business.Settlement")->returnpay(array("returnno"=>$v['returnno']));
                            
                            //生成流水号
                            $flowid = Model::new("Amount.Flow")->getFlowId($v['order_code']);
                            // 退款金额回到用户账号中
                            if($v['returnamount']>0)
                                Model::new("Amount.Amount")->add_cashamount([
                                    "userid"=>$v['customerid'],
                                    "amount" => $v['returnamount'],
                                    "usertype"=>"2",
                                    "orderno"=>$v['order_code'],
                                    "flowtype"=>48,
                                    "role"=>1,
                                    "tablename"=>"AmoFlowCusCash",
                                    "flowid"=>$flowid,
                                ]);
                            
                            if($v['returnbull']>0)
                                Model::new("Amount.Amount")->add_bullamount([
                                    "userid"=>$v['customerid'],
                                    "amount" => $v['returnbull'],
                                    "usertype"=>"2",
                                    "orderno"=>$v['order_code'],
                                    "flowtype"=>49,
                                    "role"=>1,
                                    "tablename"=>"AmoFlowCusBull",
                                    "flowid"=>$flowid,
                                ]);
                            
                            // 调用退款分润方法
                            $profit['orderno'] = $v['order_code'];
                            $profit['userid'] = $v['customerid'];
                            Model::new("Amount.Profit")->deductionprofit($profit);
                            
                            // 订单关闭消息提醒
                            Model::new("Sys.Mq")->add([
                                // "url"=>"Msg.SendMsg.ordercose",
                                "url"=>"Order.OrderMsg.ordercose",
                                "param"=>[
                                    "orderno"=>$v['order_code']
                                ],
                            ]);
                            Model::new("Sys.Mq")->submit();
                            
                            // 退单结束
                            $OrdOrderReturn->modify([
                                "orderstatus" => 14,
                                "examinetime"=>date('Y-m-d H:i:s', time())
                            ], ["id" => $v['id']]);
                            
                            Model::ins("AmoAmount")->commit();
                        } catch (\Exception $e) {
                            Model::ins("AmoAmount")->rollback();
                            Log::add($e,__METHOD__);
                        }
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;
            } else {
                break;
            }
        }
    }

    /**
     * 待发货状态 ，7天后未发货，自动退款
     * @Author   zhuangqm
     * @editor jeeluo 2017-04-19 11:21:57
     * @DateTime 2017-04-06T10:26:07+0800
     * @return   [type]                   [description]
     */
    public function return_fahuo_dayAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrder           = Model::ins("OrdOrder");
        $OrdOrderInfo       = Model::ins("OrdOrderInfo");
        $OrdOrderItem       = Model::ins("OrdOrderItem");
        $OrdOrderReturn     = Model::ins("OrdOrderReturn");
        $OrdReturnLog       = Model::ins("OrdReturnLog");
        $CusCustomer        = Model::ins("CusCustomer");
        $CusCustomerInfo    = Model::ins("CusCustomerInfo");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        $endtime = date("Y-m-d H:i:s",strtotime($config['return_fahuo_day']));
        while(true){
            if(!empty($config['return_fahuo_day'])){
                
                $list = $OrdOrder->pageList("orderstatus=1 AND isabroad=0 AND addtime<'".$endtime."'","*","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 退款
                    foreach($list as $k=>$v){

                    // 订单用户手机号
                        $cus = $CusCustomer->getRow(array("id"=>$v['customerid']),"mobile");
                        
                        // 用户详情表
                        $cusInfo = $CusCustomerInfo->getRow(array("id"=>$v['customerid']),"nickname");
                        
                        // 获取订单下所有的商品
                        $orderItem = $OrdOrderItem->getList(array("orderno"=>$v['orderno']),"businessid,businessname,id,productid,skuid,prouctprice,bullamount,productnum");
                        
                        $end = date('Y-m-d H:i:s', strtotime("+3 day"));
                        
                        $returnidArr = array();
                        
                        Model::ins("AmoAmount")->startTrans();
                        
                        try {

                            // 写入退单信息
                            foreach ($orderItem as $key => $item) {
                                $returnData = array("return_type"=>1,"business_id"=>$item['businessid'],"business_name"=>$item['businessname'],"orderid"=>$v['id'],"order_code"=>$v['orderno'],
                                    "returnno"=>Model::new("Sys.Common")->getReturnNo($v['orderno'], $key),"starttime"=>getFormatNow(),"actiontime"=>getFormatNow(),"endtime"=>$end,"returnreason"=>'自动取消',
                                    "remark"=>"","images"=>"","orderstatus"=>1,"customerid"=>$v['customerid'],"mobile"=>$cus['mobile'],"returnamount"=>$item['prouctprice']*$item['productnum'],
                                    "returnbull"=>$item['bullamount']*$item['productnum'],"productid"=>$item['productid'],"customer_name"=>$cusInfo['nickname'],"skuid"=>$item['skuid'],"productnum"=>$item['productnum']);
                            
                                // 计算退单运费
                                $freight = Model::new("Order.Order")->getReturnFreight(array("orderno"=>$v['orderno'],"productid"=>$item['productid'],"skuid"=>$item['skuid']));
                                $returnData['freight'] = $freight['freight'];
                                
                                $return_id = $OrdOrderReturn->insert($returnData);
                                
                                $returnidArr[$key] = $return_id;
                                
                                // 写入退单日志
                                $logData = array("returnno"=>$returnData['returnno'],"orderno"=>$v['orderno'],"productid"=>$item['productid'],"skuid"=>$item['skuid'],"actionsource"=>3,
                                    "customerid"=>$v['customerid'],"businessid"=>$item['businessid'],"orderstatus"=>4,"content"=>"系统自动取消订单","remark"=>"","addtime"=>getFormatNow());
                            
                                $OrdReturnLog->insert($logData);
                            }
                            
                            // 未发货状态下的用户退款处理
                            Model::new("Business.Settlement")->returnallfutpay(array("orderno"=>$v['orderno']));
                            
                            //生成流水号
                            $flowid = Model::new("Amount.Flow")->getFlowId($v['orderno']);
                            // 退款金额回到用户账号中
                            if($v['totalamount']>0)
                                Model::new("Amount.Amount")->add_cashamount([
                                    "userid"=>$v['customerid'],
                                    "amount" => $v['totalamount'],
                                    "usertype"=>"2",
                                    "orderno"=>$v['orderno'],
                                    "flowtype"=>48,
                                    "role"=>1,
                                    "tablename"=>"AmoFlowCusCash",
                                    "flowid"=>$flowid,
                                ]);
                            
                            if($v['bullamount']>0)
                                Model::new("Amount.Amount")->add_bullamount([
                                    "userid"=>$v['customerid'],
                                    "amount" => $v['bullamount'],
                                    "usertype"=>"2",
                                    "orderno"=>$v['orderno'],
                                    "flowtype"=>49,
                                    "role"=>1,
                                    "tablename"=>"AmoFlowCusBull",
                                    "flowid"=>$flowid,
                                ]);
                            
                            // 退款分润
                            $profit['orderno'] = $v['orderno'];
                            $profit['userid'] = $v['customerid'];
                            Model::new("Amount.Profit")->deductionprofit($profit);
                            
                            // 关闭订单详情
                            $OrdOrderInfo->modify([
                                "cancelsource"=>3,
                                "cancelreason"=>"自动取消",
                            ],["orderno"=>$v['orderno']]);
                            
                            // 关闭订单
                            $OrdOrder->modify([
                                "orderstatus"=>5,
                            ],["orderno"=>$v['orderno']]);
                            
                            // 订单关闭消息提醒
                            Model::new("Sys.Mq")->add([
                                // "url"=>"Msg.SendMsg.ordercose",
                                "url"=>"Order.OrderMsg.ordercose",
                                "param"=>[
                                    "orderno"=>$v['orderno']
                                ],
                            ]);
                            Model::new("Sys.Mq")->submit();
                            
                            // 修改退单状态
                            foreach ($returnidArr as $returnId) {
                                $OrdOrderReturn->modify(array("orderstatus"=>4,"examinetime"=>getFormatNow()), array("id"=>$returnId));
                            }
                            
                            Model::ins("AmoAmount")->commit();
                        } catch (\Exception $e) {
                            Model::ins("AmoAmount")->rollback();
                            Log::add($e,__METHOD__);
                        }
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }

    /**
     * 订单完成后，30天，自动好评
     * @Author   zhuangqm
     * @DateTime 2017-04-06T10:26:07+0800
     * @return   [type]                   [description]
     */
    public function evaluate_dayAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrderReturn       = Model::ins("OrdOrderReturn");
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        $endtime = date("Y-m-d H:i:s",strtotime($config['return_day']));
        while(true){
            if(!empty($config['return_day'])){

                $list = $OrdOrderReturn->pageList("return_type=1 and orderstatus=0 and addtime<'".$endtime."'","id,order_code","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 退款
                    foreach($list as $k=>$v){
                        
                        $OrdOrder->update([
                                "orderstatus"=>5,
                            ],["id"=>$v['id']]);

                        $OrdOrder->update([
                                "orderstatus"=>5,
                            ],["orderno"=>$v['order_code']]);
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }


    /**
     * 自动取消订单 --15分钟未付款订单自动取消
     * @Author   zhuangqm
     * @DateTime 2017-09-23
     * @return   [type]                   [description]
     */
    public function cancelsorderbuyAction(){
        $config = Config("order");
        
        $pagesize = 50;
        $page     = 1;

        
        $OrdOrderBuy    = Model::ins("OrdOrderBuy");
        $OrdOrder       = Model::ins("OrdOrder");
        $OrdOrderInfo   = Model::ins("OrdOrderInfo");
        $ProProductBuy  = Model::ins("ProProductBuy");
        $ProProductBuyRedis = Model::Redis("ProProductBuy");
        //$UserMtokenOBJ = Db::DbTable("UserMtoken");
        //$UserRemindLogOBJ = Db::DbTable("UserRemindLog");
        
        $endtime = date("Y-m-d H:i:s",strtotime($config['cancelsorderbuy_time']));
        
        while(true){
            if(!empty($config['cancelsorderbuy_time'])){

                $list = $OrdOrderBuy->pageList("status=0 and addtime<'".$endtime."'","*","id asc",0,$page,$pagesize);
                $page+=1;
                //print_r($list);
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){
                        $OrdOrder->update([
                                "orderstatus"=>5,
                            ],["orderno"=>$v['orderno']]);

                        $OrdOrderInfo->update([
                                "cancelsource"=>3,
                                "cancelreason"=>"自动取消",
                            ],["orderno"=>$v['orderno']]);
                        //抢购订单取消
                        $OrdOrderBuy->update([
                                "status"=>-1,
                            ],["orderno"=>$v['orderno']]);
                        // 恢复库存
                        $result = $ProProductBuy->update("productstorage_buy=productstorage_buy+".$v['productcount'],["id"=>$v['product_buy_id']]);
                        // redis扣减
                        if($result)
                            $ProProductBuyRedis->hincrby($v['productid'],["productstorage_buy"=>$v['productcount']]);
                    }
                }
                
                if(count($list)==0 || count($list)<$pagesize)
                    break;

            }else{
                break;
            }
        }
    }

}