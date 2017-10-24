<?php
// +----------------------------------------------------------------------
// |  [ 实体店外卖订单列表]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月5日17:45:46}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Order;

use app\superadmin\ActionController;
use app\lib\Model;
use app\model\StoBusiness\StoOrdOrderModel;

class StoOrderController extends ActionController {


    public function __construct() {
        parent::__construct();
    }

      /**
    * @user 全部订单管理
    * @param 
    * @author jeeluo
    * @date 2017年3月16日下午2:36:53
    */
    public function listAction() {
        $orderstatus = [
           	"0"=>"待付款",
           	"1"=>"已付款待接单",
           	"2"=>"已接单待配送",
           	"3"=>"已配送",
           	"4"=>"已送达",
           	"5"=>"订单完结",
           	"6"=>"拒绝接单",
           	"7"=>"取消"
        ];
        $return_status = [
        	"0"=>"无退款",
        	"1"=>"退款中",
        	"2"=>"退款完成"
        ];
        $mobile = $this->getParam('mobile');
        if(!empty($mobile)){
           
            $cus = Model::ins('CusCustomer')->getRow(['mobile'=>$mobile],'id');
          
            $customerid = $cus['id'];
            if(!empty($customerid)){
               
                $where['customerid'] = $customerid;
            }
        }
         
        $where = $this->searchWhere([
            "orderno" => "=",
            "businessname"=>"like",
            "orderstatus" => "=",
            "return_status" => "=",
            "addtime" => "times",
        ], $where);

        $list = Model::ins("StoOrder")->pageList($where, "*", "addtime desc");

        foreach ($list['list'] as $k => $v) {

            $logistics = Model::ins('StoOrderLogistics')->getRow(['orderno'=>$v['orderno']],'mobile,realname,city,address');
            $list['list'][$k]['mobile'] = $logistics['mobile'];
            $list['list'][$k]['realname'] = $logistics['realname'];
            $list['list'][$k]['adress'] = $logistics['city'].$logistics['adress'];
        	$list['list'][$k]['orderstatus']  = $orderstatus[$v['orderstatus']];
        	$list['list'][$k]['return_status']  = $return_status[$v['return_status']];
            $list['list'][$k]['totalamount'] = DePrice($v['totalamount']);
            $list['list'][$k]['productamount'] = DePrice($v['productamount']);
            $list['list'][$k]['actualfreight'] = DePrice($v['actualfreight']);
         }

        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            'orderstatuss' => $orderstatus,
            'return_statuss' => $return_status
        );
        
        return $this->view($viewData);
    }


     /**
     * [lookOrderAction 订单详情 查看订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-05T18:32:35+0800
     * @return   [type]                   [description]
     */
    /**
    * @user 查看订单详情
    * @param 
    * @author jeeluo
    * @date 2017年3月17日下午3:45:49
    */
    public function lookOrderAction() {
        // 订单详情
        if(!$this->getParam('id')) {
            $this->showError("请选择正确操作");
        }
        $orderData = Model::ins('StoOrder')->getRow(['id'=>$this->getParam("id")]);
        $orderInfo = Model::ins('StoOrderInfo')->getRow(['id'=>$this->getParam("id")],'remark');
        $logistics = Model::ins('StoOrderLogistics')->getRow(['orderno'=>$orderData['orderno']]);
        $itemData = Model::ins('StoOrderItem')->getList(['orderno'=>$orderData['orderno']],'businessname,productname,productnum,prouctprice');
       // print_r($orderData);

        $evaluate = Model::ins('StoEvaluate')->getList(['orderno'=>$orderData['orderno']],'orderno,frommembername,scores,content,addtime');

        $orderstatus = [
            "0"=>"待付款",
            "1"=>"已付款待接单",
            "2"=>"已接单待配送",
            "3"=>"已配送",
            "4"=>"已送达",
            "5"=>"订单完结",
            "6"=>"拒绝接单",
            "7"=>"取消"
        ];
        //print_r($logistics);
        $viewData = [
            'orderData' => $orderData,
            'logistics' => $logistics,
            'orderstatus'=>$orderstatus,
            'orderInfo' => $orderInfo,
            'itemData' => $itemData,
            'evaluate' => $evaluate
        ];
        return $this->view($viewData);
    }

    /**
     * [returnOrderAction 退款操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-21T17:00:14+0800
     * @return   [type]                   [description]
     */
    public function returnOrderAction(){
        $orderno = $this->getParam('orderno');
        $order = Model::ins('StoOrder')->getRow(['orderno'=>$orderno],'id,orderno,orderstatus,return_status,businessid,businessname,customerid,customer_name,totalamount,actualfreight');
        if($order['orderstatus'] != 5)
            $this->showError('只有待评价、已评价的订单才能退款');
        $StoOrderOBJ           = Model::ins('StoOrder');
        $StoOrderinfoOBJ       = Model::ins('StoOrderInfo');
        $StoOrderReturnOBJ     = Model::ins('StoOrderReturn');
        $StoOrdOrderModel = new StoOrdOrderModel();
        if($StoOrderOBJ->update(["orderstatus"=>7,"cancelsource"=>3,'return_status'=>1],["id"=>$order['id']])){
                            
            //取消订单 生成退款单
            $StoOrderinfoOBJ->update(["cancelreason"=>$param['cancelreason']],["id"=>$order['id']]);
            

            $returnOder = [
                'businessid' => $order['businessid'],
                'businessname' => $order['businessname'],
                'orderid' => $order['id'],
                'orderno' => $order['orderno'],
                'returnreason' => '商家操作退款',
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
                     
        }
        $this->showSuccess('操作成功');
    }

}