<?php
// +----------------------------------------------------------------------
// |  [ 实体店订单处理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月3日17:05:47}}
// +----------------------------------------------------------------------
namespace app\auto\controller\StoOrder;
use app\auto\ActionController;
use app\lib\Model;
use think\Config;
use app\lib\Log;
use app\model\StoBusiness\StoOrdOrderModel;

class IndexController extends ActionController{

	
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    } 

   /**
    * [cancelsorderAction 15分钟未付款取消订单]
    * @Author   ISir<673638498@qq.com>
    * @DateTime 2017-07-05T20:36:39+0800
    * @return   [type]                   [description]
    */
    public function cancelsorderAction(){
        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");
        
        $nowTime = time();
           
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['cancelsorder_time']));
        
        while(true){
            if(!empty($config['cancelsorder_time'])){

                $list = $StoOrderOBJ->pageList("orderstatus=0 and addtime<'".$endtime."'","id,orderno,addtime","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){

                            $StoOrderOBJ->update([
                                    "orderstatus"=>7,
                                    "cancelsource"=>3,
                                    "finish_time"=>date("Y-m-d H:i:s"),
                                ],["id"=>$v['id']]);

                            $StoOrderinfoOBJ->update([
                                    "cancelreason"=>"支付超时，订单已取消",
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
     * [cancelreturnOrderAction 取消订单商家超时未接单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-05T20:43:42+0800
     * @return   [type]                   [description]
     */
    public function cancelreturnOrderAction(){

        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");
        $StoOrderReturnOBJ     = Model::ins("StoOrderReturn");
        $StoOrdOrderOBJ        = new StoOrdOrderModel();
        $nowTime = time();
           
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['missorder_time']));
        
        while(true){
            if(!empty($config['missorder_time'])){

                $list = $StoOrderOBJ->pageList("orderstatus=1 and addtime<'".$endtime."'","*","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                   
                    foreach($list as $k=>$v){
                      
                        $StoOrderOBJ->update([
                                "orderstatus"=>7,
                                "return_status"=>1,
                                "cancelsource"=>3,
                            ],["id"=>$v['id']]);

                        $StoOrderinfoOBJ->update([
                                "cancelreason"=>"商家超时未接单，订单自动退款",
                            ],["orderno"=>$v['orderno']]);

                        $return = [
                            'businessid' => $v['businessid'],
                            'businessname' => $v['businessname'],
                            'orderid' => $v['id'],
                            'orderno' => $v['orderno'],
                            'customerid' => $v['customerid'],
                            'customer_name' => $v['customer_name'],
                            'returnamount' => $v['totalamount']
                        ];
                        
                        $returnOrderData = $StoOrderReturnOBJ->getRow(['orderno'=>$v['orderno']],'id');

                        if(empty($returnOrderData)){
                            $return['addtime'] = date('Y-m-d H:i:s');
                            $returnid = $StoOrderReturnOBJ->insert($return);
                        }else{
                            $StoOrderReturnOBJ->update($return,$returnOrderData['id']);
                            $returnid = $returnOrderData['id'];
                        }

                        $return = $StoOrdOrderOBJ->returnPay(['orderno'=>$v['orderno']]);

                        if($return['code'] ==200){
                            /*
                            //更新退款单状态
                            $returnStatus = [
                                'actualamount' => $v['totalamount'],
                                'freight' => $v['actualfreight'],
                                'return_success_time' => date('Y-m-d H:i:s')
                            ];
                            $StoOrderReturnOBJ->update($returnStatus,['id'=>$returnid]);

                            //更新订单状态
                            $orderStatus = [
                                'return_time' => date('Y-m-d H:i:s'),
                                'return_status' => 2,
                                'orderstatus' => 5,
                                'finish_time' => date('Y-m-d H:i:s')
                            ];
                            $StoOrderOBJ->update($orderStatus,,['orderno'=>$v['orderno']]);
                            */
                            //更新退款单状态
                            $returnStatus = [
                                'actualamount' => $v['totalamount'],
                                'freight' => intval($v['actualfreight']),
                                'return_success_time' => date('Y-m-d H:i:s'),
                                'orderstatus' => 4,
                                'audit_remark'=>"超时未接单,自动退款",
                            ];
                            /*print_r($returnStatus);
                            echo $order['orderno']."-------";*/
                            $StoOrderReturnOBJ->update($returnStatus,['orderno'=> $v['orderno']]);
                            //更新订单状态
                            $orderStatus = [
                                'return_time' => date('Y-m-d H:i:s'),
                                'finish_time' => date('Y-m-d H:i:s')
                            ];
                            $StoOrderOBJ->update($orderStatus,['id'=>$v['id']]);
                        }
                        
                        // 操作成功  发送消息 商家超时未接单发送消息给用户
                        Model::new("Sys.Mq")->add([
                            // "url"=>"Msg.SendMsg.orderConfirm",
                            "url"=>"StoOrder.OrderMsg.ordertimeoutaccept",
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
     * [cancelrerefuseorderAction 商家拒绝退款,用户超时未处理关闭订单 24小时]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-05T21:09:46+0800
     * @return   [type]                   [description]
     */
    public function cancelrerefuseorderAction(){
        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");
        $StoOrderReturnOBJ       = Model::ins("StoOrderReturn");

        $nowTime = time();
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['refuseorder_time']));
        
        while(true){
            if(!empty($config['refuseorder_time'])){

                $list = $StoOrderReturnOBJ->pageList("orderstatus=3 and addtime<'".$endtime."'","*","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){
                        
                        /*$StoOrderOBJ->update([
                                "orderstatus"=>5,
                                "cancelsource"=>3,
                                'finish_time'=>date('Y-m-d H:i:s')
                            ],["id"=>$v['orderid']]);*/
                        
                        $StoOrderinfoOBJ->update([
                                "audit_remark"=>"拒绝退款超时",
                            ],["id"=>$v['orderid']]);
                        
                        $StoOrderReturnOBJ->update([
                            'orderstatus'=>5
                        ],["id"=>$v['id']]);

                        $StoOrderOBJ->update(["return_status"=>0],["orderno"=>$v['orderno']]);
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
     * [autoreturnOrderAction 用户申请退款超时商家未处理同意退款]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-07T16:06:31+0800
     * @return   [type]                   [description]
     */
    public function autoreturnOrderAction(){

        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");
        $StoOrderReturnOBJ       = Model::ins("StoOrderReturn");

        $StoOrdOrderOBJ        = Model::new("StoBusiness.StoOrdOrder");

        $nowTime = time();
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['notagreeorder_time']));
        
        while(true){
            if(!empty($config['notagreeorder_time'])){

                $list = $StoOrderReturnOBJ->pageList("orderstatus=1 and addtime<'".$endtime."'","*","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){
                        
                        $return = $StoOrdOrderOBJ->returnPay(['orderno'=>$v['orderno']]);

                        if($return['code'] ==200){
                            
                            //更新退款单状态
                            $returnStatus = [
                                'actualamount' => $v['totalamount'],
                                'freight' => $v['actualfreight'],
                                'examinetime'=>date('Y-m-d H:i:s'),
                                'return_success_time' => date('Y-m-d H:i:s'),
                                'orderstatus' => 4,
                                'audit_remark'=>"用户申请退款,超时未处理同意退款",
                            ];
                            $StoOrderReturnOBJ->update($returnStatus,['orderno'=>$v['orderno']]);
                            //更新订单状态
                            $orderStatus = [
                                'return_time' => date('Y-m-d H:i:s'),
                                'return_status' => 2,
                                'orderstatus' => 5,
                                'finish_time' => date('Y-m-d H:i:s')
                            ];
                            $StoOrderOBJ->update($orderStatus,['orderno'=>$v['orderno']]);

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
    * [cancelsorderAction 下单后5小时内未点确认送达，则系统自动确认]
    * @Author   ISir<673638498@qq.com>
    * @DateTime 2017-07-05T20:36:39+0800
    * @return   [type]                   [description]
    */
    public function confirmationorderAction(){
        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");

        $StoOrdOrderOBJ        = new StoOrdOrderModel();
        
        $nowTime = time();
           
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['confirmorder_time']));
        
        while(true){
            if(!empty($config['confirmorder_time'])){

                $list = $StoOrderOBJ->pageList("orderstatus in (2,3) and addtime<'".$endtime."'","id,orderno,customerid,addtime","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){

                            /*$addOrder = $StoOrdOrderOBJ->addOrderAmount(['orderno'=>$v['orderno']]);
                            
                            $StoOrderOBJ->update([
                                    "orderstatus"=>5,
                                    "confirm_time" => date('Y-m-d H:i:s')
                                ],["id"=>$v['id']]);*/

                            $StoOrdOrderOBJ->confirmationorder([
                                    "orderno"=>$v['orderno'],
                                    "customerid"=>$v['customerid'],
                                ]);
                        
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
    * [cancelsorderAction 用户订单关闭五分钟前发送消息 您的订单将在5分钟后自动关闭 ]
    * @Author   ISir<673638498@qq.com>
    * @DateTime 2017-07-05T20:36:39+0800
    * @return   [type]                   [description]
    */
    public function cancelsordermsgAction(){
        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");
        
        $nowTime = time();
           
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['cancelsorder_msg_time']));
        
        while(true){
            if(!empty($config['cancelsorder_msg_time'])){

                $list = $StoOrderOBJ->pageList("orderstatus=0 and addtime<'".$endtime."'","id,orderno,addtime","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){

                           
                          // 操作成功  发送消息 您的订单将在5分钟后自动关闭
                        Model::new("Sys.Mq")->add([
                            // "url"=>"Msg.SendMsg.orderConfirm",
                            "url"=>"StoOrder.OrderMsg.orderwaitpay",
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
    * [cancelsorderAction 下单后5小时内未点确认送达，则系统自动确认---暂时没有用]
    * @Author   ISir<673638498@qq.com>
    * @DateTime 2017-07-05T20:36:39+0800
    * @return   [type]                   [description]
    */
    public function confirmationordermsgAction(){
        $config = Config("stoorderauto");
        
        $pagesize = 50;
        $page     = 1;

        
        $StoOrderOBJ           = Model::ins("StoOrder");
        $StoOrderinfoOBJ       = Model::ins("StoOrderInfo");
        
        $nowTime = time();
           
      

        $endtime = date("Y-m-d H:i:s",strtotime($config['refuseorder_msg_time']));
        
        while(true){
            if(!empty($config['confirmorder_time'])){

                $list = $StoOrderOBJ->pageList("orderstatus in (2,3) and addtime<'".$endtime."'","id,orderno,addtime","id asc",0,$page,$pagesize);

                $page+=1;
                
                if(!empty($list)){
                    
                    // 取消订单
                    foreach($list as $k=>$v){

                       // 操作成功  发送消息 您的订单将在30分钟后自动确认送达
                        Model::new("Sys.Mq")->add([
                            // "url"=>"Msg.SendMsg.orderConfirm",
                            "url"=>"StoOrder.OrderMsg.orderautoconfirmdispatch",
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



}