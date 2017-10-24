<?php
// +----------------------------------------------------------------------
// |  [ 订单管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-03-16
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Order;

use app\superadmin\ActionController;
use app\lib\Model;
use app\form\OrdOrderLogistics\OrdOrderLogisticsEdit;
use app\lib\Express;
use app\lib\Log;
use app\model\Sys\CommonModel;
use think\Config;
use app\lib\Img;

class IndexController extends ActionController {
    
    const payOrderStatus = 1;
    const deliveryOrderStatus = 2;
    const confirmOrderStatus = 3;
    const completeOrderStatus = 4;
    
    const returnIng = 1;
    const returnSuccess = 2;
    
    const priceWaitStatus = 1;
    const priceExamSuccess = 2;
    const priceExamFail = 3;
    const priceSuccess = 4;
    
    public function __construct() {
        parent::__construct();
    }
    
    
    /**
    * @user 全部订单管理
    * @param 
    * @author jeeluo
    * @date 2017年3月16日下午2:36:53
    */
    public function indexAction() {
        // 查询
        
//         $where["businessid"] = $this->businessid;
        if($this->getParam("type")) {
            $where['orderstatus'] = $this->getParam("type")-1;
        }
        $businessname = $this->getParam('businessname');
        if(!empty($businessname)){

            $businessData = Model::ins('BusBusiness')->getList(['businessname'=>['like','%'.$businessname.'%']],'id');
           
            if(!empty($businessData)){

                $bids = '';
                foreach ($businessData as $key => $value) {
                   $bids.= $value['id'].',';
                }
                $bids = rtrim($bids,',');
                if(!empty($bids)){
                    $where['businessid'] = ['in',$bids];
                }
            }
        }

        $where = $this->searchWhere([
            "orderno" => "like",
            "isabroad" => "=",
            "cust_name" => "like",
//             "realname" => "like",
            "mobile" => "=",
            "addtime" => "times",
            "evaluate" => "="
        ], $where);
        
        if($where['evaluate'] == 0) {
            unset($where['evaluate']);
        }
        
        if($where['isabroad'] == -1) {
            unset($where['isabroad']);
        }
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
//         $list = Model::ins("OrdOrder")->pageList($where, "*", "addtime desc");

//         if(!empty($where)) {
//             $list = Model::new("Order.SuperIndex")->getWhereList($where);
//         } else {
//             $list = Model::ins("OrdOrder")->pageList($where, "*", "addtime desc");
//         }

        $list = Model::ins("OrdOrder")->pageList($where, "*", "id desc");
        
        foreach ($list['list'] as $k => $v) {
                


            $data = Model::ins("OrdOrderItem")->getRow(["orderno" => $v['orderno']], "businessname")['businessname'];
            
            $list['list'][$k]['businessname'] = Model::ins("OrdOrderItem")->getRow(["orderno" => $v['orderno']], "businessname")['businessname'];
            $list['list'][$k]['productname'] = Model::ins("OrdOrderItem")->getRow(["orderno" => $v['orderno']], "productname")['productname'];
           
            $list['list'][$k]['prouctprice'] = Model::ins("OrdOrderItem")->getRow(["orderno" => $v['orderno']], "productname")['prouctprice'];

            $list['list'][$k]['totalamount'] = Model::ins("OrdOrder")->getRow(["orderno" => $v['orderno']], "totalamount")['totalamount'];
//             $list['list'][$k]['returnstatus'] = Model::ins("OrdOrderInfo")->getRow(["orderno" => $v['orderno']], "return_status")['return_status'];
            // 只显示售中退款
            $OrdPriceReturn = Model::ins("OrdOrderReturn")->getRow(["order_code" => $v['orderno']], "orderstatus");
            if($OrdPriceReturn['orderstatus'] == self::priceExamSuccess || $OrdPriceReturn['orderstatus'] == self::priceSuccess) {
                $list['list'][$k]['returnstatus'] = 2;
            } else if($OrdPriceReturn['orderstatus'] == self::priceWaitStatus || $OrdPriceReturn['orderstatus'] == self::priceExamFail) {
                $list['list'][$k]['returnstatus'] = 1;
            } else {
                $list['list'][$k]['returnstatus'] = 0;
            }
            
            // 获取订单的收货信息
            $logisticsInfo = Model::ins("OrdOrderLogistics")->getRow(["orderno" => $v['orderno']], "id,realname,mobile");
            
            
            $list['list'][$k]['logisticsid'] = !empty($logisticsInfo['id']) ? $logisticsInfo['id'] : '';
            
//             $list['list'][$k]['realname'] = $logisticsInfo['realname'];
//             $list['list'][$k]['mobile'] = $logisticsInfo['mobile'];
            $payInfo = Model::ins("OrdOrderPay")->getRow(array("orderno" => $v['orderno']), "id,pay_type, paytime, pay_money, pay_bull, pay_num");
            if(!empty($payInfo)){
                $list['list'][$k]['payInfo'] = $payInfo['pay_type']!=''?$payInfo['pay_type']:"余额支付";

            }else{
                $list['list'][$k]['payInfo'] = "未支付";
            }
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "orderstatus" => $this->getParam("type") ? $this->getParam("type")-1 : $this->getParam("type"),
            'type'=>$this->getParam("type"),
        );
        
        return $this->view($viewData);
    }
   
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
        $where['id'] = $this->getParam("id");
//         $where['businessid'] = $this->businessid;
        // 查看该订单信息
        $order = Model::ins("OrdOrder")->getRow($where);
        
        $cus = Model::ins("CusCustomer")->getRow(["id"=>$order['customerid']],"mobile");
        
        $order['mobile'] = $cus['mobile'];
        
//         if(empty($order)) {
//             $this->showError("您无权限访问此处");
//         }
        
        // 订单收货信息
        $logistics = Model::ins("OrdOrderLogistics")->getRow(array("orderno" => $order['orderno']), "*");
        //print_r($logistics);

        // 订单详细商品
        // 
        $itemInfo = Model::ins("OrdOrderItem")->getAllItemDetailByOrderNO(array("orderno" => $order['orderno'], "enable" => 1));
        
        foreach ($itemInfo as $key => $item) {
           $busData = Model::ins('BusBusiness')->getRow(['id'=>$item['businessid']],'customerid');
           $cusData = Model::ins('CusCustomer')->getRow(['id'=>$busData['customerid']],'mobile');
           $itemInfo[$key]['mobile'] = $cusData['mobile'];
        }

        $payInfo = array();
//         $expressInfo = array();
        $orderInfo = array();
        $orderItemList = array();
        if($order['orderstatus'] >= self::payOrderStatus) {
            // 假如商品已支付，获取支付信息
            $payInfo = Model::ins("OrdOrderPay")->getRow(array("orderno" => $order['orderno']), "id,pay_type, paytime, pay_money, pay_bull, pay_num");
//             if($order['orderstatus'] >= self::deliveryOrderStatus) {
                // 发货之后 产生快递信息
//                 $expressInfo = Express::search($logistics['express_name'], $logistics['express_no']);
                if($order['orderstatus'] >= self::confirmOrderStatus) {
                    // 确认收货之后 产生收货信息
                    $orderInfo = Model::ins("OrdOrderInfo")->getInfoRow(array("orderno" => $order['orderno']), "actual_delivery_time, auto_delivery_time");
                    
                    if($order['orderstatus'] >= self::completeOrderStatus) {
                        // 订单已完成，获取评价信息
                        $orderItemList = Model::ins("OrdOrderItem")->getAllItemDetailByOrderNO(array("orderno" => $order['orderno'], "enable" => 1));
                        foreach ($orderItemList as $key => $orderItem) {
                          
                            $orderItemList[$key]['evaluate'] = Model::ins("ProEvaluate")->getOrderProList(array("productid" => $orderItem['productid'], "skuid" => $orderItem['skuid'], "orderno" => $order['orderno']));
                        }
                    }
                }
//             }
        }
        
        $expressList = array();
        if($order['orderstatus'] == self::payOrderStatus) {
            $expressList = Config::get("express_name");
        }
       
        // 订单收货信息
        //$logistics = Model::ins("OrdOrderLogistics")->getRow(array("orderno" => $order['orderno']), "express_name, express_no,leavemessage");
        
        // 发货之后 产生快递信息
//         $expressInfo = Express::search($logistics['express_name'], $logistics['express_no']);


        $action_arr['setExpress'] = "/Order/Index/setExpress";
        
        
        $nameauthInfo = array();
        if($order['isabroad'] == 1 && $order['orderstatus'] == 1) {
            // 海外购订单
            $nameauthInfo['realname'] = $logistics['realname'];
            $nameauthInfo['idnumber'] = $logistics['idnumber'];
            $nameauthInfo['positive_image'] = Img::url($logistics['positive_image']);
            $nameauthInfo['opposite_image'] = Img::url($logistics['opposite_image']);
        }
        
        $viewData = array(
            "title" => "查看订单详情",
            "orderData" => $order,
            "logisticsData" => $logistics,
            "nameauthInfo" => $nameauthInfo,
            'itemData' => $itemInfo,
            'payData' => $payInfo,
            'actionArr' => $action_arr,
            "expressList" => $expressList,
//             'expressData' => $expressInfo['data'],
            "orderInfo" => $orderInfo,
            "orderItemEvaluate" => $orderItemList,
//             "expressData" => $expressInfo,
        );
        return $this->view($viewData);
    }

     // 数据导出操作
    public function exportAction(){
        $offset     = $this->getParam("offset",0);
        $type = $this->getParam("type",1);

        $filename = '商品订单导出列表';
        // if($type == 1){
        //     $filename = '待付款订单列表';
        // }
        // if($type == 2){
        //     $filename = '待发货订单列表';
        // }
        $count      = 30; //每次导出的数据条数 可以设更高都可以
        if($type) {
            $where['orderstatus'] = $this->getParam("type")-1;
        }
        
        //数据获取和数据处理
        /*
        可以和列表页面公用一个action
        如 $list = $this->getlist();
        */
        $OrdOrder = Model::ins("OrdOrder");
        $OrdOrderItem = Model::ins('OrdOrderItem');
        $OrdOrderPay = Model::ins('OrdOrderPay');
        $OrdOrderLogistics = Model::ins('OrdOrderLogistics');
        $businessname = $this->getParam('businessname');
        if(!empty($businessname)){

            $businessData = Model::ins('BusBusiness')->getList(['businessname'=>['like','%'.$businessname.'%']],'id');
           
            if(!empty($businessData)){

                $bids = '';
                foreach ($businessData as $key => $value) {
                   $bids.= $value['id'].',';
                }
                $bids = rtrim($bids,',');
                if(!empty($bids)){
                    $where['businessid'] = ['in',$bids];
                }
            }
        }
        $where = $this->searchWhere([
            "orderno" => "=",
            "cust_name" => "=",
            "addtime" => "times",
        ], $where);
        
        $orderstatus_arr = [
            '0'=>'待付款',
            '1'=>'已付款待发货',
            '2'=>'已发货',
            '3'=>'确认收货',
            '4'=>'已完结',
            '5'=>'已取消',
        ];
        $ids = $this->getParam('ids');
        if(!empty($ids))
            $where = ['id'=>['in',$ids]];
        $order_no = $OrdOrder->getList($where,'id','id desc');
        $order_no = $OrdOrder->getList($where,'id','id desc');
       
        $orderno_str = '';
        foreach ($order_no as $key => $value) {

            if(!empty($value['id']))
                $orderno_str .= $value['id'] . ',';
            
        }
        $orderno_str = rtrim($orderno_str,',');
        $abroad_arr = [
            '0' => '国内订单',
            '1' => '国外订单'
        ];
        // if(!empty($orderno_str))
        $item_list = $OrdOrderItem->getList(['orderid'=>['in',$orderno_str]],'orderno,productname,skuid,productid,productnum,prouctprice,bullamount,remark','id desc',$count,$offset*$count);

            foreach ($item_list as $key => $value) {
                $spec = Model::ins('ProProductSpec')->getRow(['id'=>$value['skuid']],'spec,serialnumber,barcode');
               
                $spec_arr = json_decode($spec['spec'],true);
                $spec_str = '';
                foreach ($spec_arr as $spev ) {
                   $spec_str .= $spev['name'] . ':' .$spev['value'].',';
                }
                //echo $spec_str;
                $item_list[$key]['skuid'] = $spec_str;
                $order = $OrdOrder->getRow(['orderno'=>$value['orderno']],'isabroad,addtime,orderstatus,businessname,businessid,cust_name,productamount,totalamount,bullamount,actualfreight');
              
                $busData = Model::ins('BusBusinessInfo')->getRow(['id'=>$order['businessid']],'mobile');
                $Logistics = $OrdOrderLogistics->getRow(['orderno'=>$value['orderno']],'realname,mobile,idnumber,city,address,leavemessage');

                $item_list[$key]['prouctprice'] = DePrice($value['prouctprice']);
                $item_list[$key]['bullamount'] = DePrice($value['bullamount']);

                $item_list[$key]['serialnumber'] = $spec['serialnumber'];
                $item_list[$key]['barcode'] = $spec['barcode'];

                $item_list[$key]['isabroad'] = $abroad_arr[$order['isabroad']];
                $item_list[$key]['addtime'] = $order['addtime'];
                $item_list[$key]['orderstatus'] =  $orderstatus_arr[$order['orderstatus']];
                $item_list[$key]['businessname'] = $order['businessname'];
                $item_list[$key]['busmobile'] = $busData['mobile'];
               

                $item_list[$key]['realname'] = $Logistics['realname'];
                $item_list[$key]['mobile'] = $Logistics['mobile'];
                $item_list[$key]['idnumber'] = $Logistics['idnumber'];
                $item_list[$key]['aread'] = $Logistics['city'].$Logistics['address'];
                $item_list[$key]['leavemessage'] = $value['remark'];
                $item_list[$key]['actualfreight'] = DePrice($order['actualfreight']);
                $item_list[$key]['productamount'] = DePrice($order['productamount']);
                $item_list[$key]['totalamount'] = DePrice($order['totalamount']);
                $item_list[$key]['orderbullamount'] = DePrice($order['bullamount']);
                 unset($item_list[$key]['remark']);
                unset($item_list[$key]['productid']);
            }
           
            echo $this->iniExcelData(array(
                                "filename"=>$filename,
                                // "head"=>array("订单号","商品名称","商品属性","购买数量","商品价格","商品牛豆数","商品货号","商品条形码","订单类型","下单时间","订单状态","商家名称","商家电话","收货人姓名","收货人电话","收货人身份证","收货地址","留言","运费","订单商品总额","订单总金额","订单总牛豆"), //excel表头
                                "field"=>[
                                    "orderno"=>"订单号",
                                    "orderstatus" =>"订单状态",
                                    "isabroad"=>"订单类型",
                                    "addtime"=>"下单时间",
                                    "actualfreight" =>"运费",
                                    "totalamount"=>"订单总金额",
                                    "productamount"=>"订单商品总额",
                                    "orderbullamount" =>"订单总牛豆",
                                    "productname"=>"商品名称",
                                    "skuid" =>"商品属性",
                                    "barcode"=>"商品条形码",
                                    "serialnumber"=>"商品货号",
                                    "productnum"=>"购买数量",
                                    "prouctprice" =>"商品价格",
                                    "bullamount"=>"商品牛豆数",
                                    "businessname" =>"商家名称",
                                    "busmobile"=>"商家电话",
                                    "realname" =>"收货人姓名",
                                    "mobile"=>"收货人电话",
                                    "idnumber" =>"收货人身份证",
                                    "aread"=>"收货地址",
                                    "leavemessage" =>"留言",
                                ],
                                "list"=>$item_list,
                                "offset"=>$offset,
                            ));
        
        //print_r($item_list);
        exit();


        // $list = $OrdOrder->getList($where,'orderno,isabroad,addtime,orderstatus,businessname,businessid,cust_name,productamount,totalamount,bullamount,actualfreight','id asc',$count,$offset*$count);
        //  $abroad_arr = [
        //         '0' => '国内订单',
        //         '1' => '国外定'
        //     ];
        // foreach($list as $key=>$value){
        //     $list[$key]['isabroad'] = $abroad_arr[$value['isabroad']];
        //     $busData = Model::ins('BusBusinessInfo')->getRow(['id'=>$value['businessid']],'mobile');
        //     $list[$key]['businessid'] = $busData['mobile'];

        //     $pronames = '';
        //     $pronum = '';
        //     $proprice = '';
        //     $probull = '';
        //     $list[$key]['productamount'] =  DePrice($value['productamount']);
        //     $list[$key]['totalamount'] =  DePrice($value['totalamount']);
        //     $list[$key]['bullamount'] =  DePrice($value['bullamount']);
        //     $list[$key]['actualfreight'] = DePrice($value['actualfreight']);
        //     $itemData = $OrdOrderItem->getList(['orderno'=>$value['orderno']],'skuid,productid,productname,productnum,prouctprice,bullamount');
        //     foreach ($itemData as $itk => $itv) {
        //         $spec = Model::ins('ProProductSpec')->getRow(['id'=>$itv['skuid']],'spec');
        //         $spec_arr = json_decode($spec['spec'],true);
               
        //         foreach ($spec_arr as $spev ) {
        //            $spec_str .= $spev['name'] . ':' .$spev['value'];
        //         }
        //         $pro_product = Model::ins('ProProduct')->getRow(['id'=>$itv['productid']],'barcode,serialnumber');
        //         $barcodes .= $pro_product['barcode'] .',';
        //         $serialnumbers .= $pro_product['serialnumber'] .',';
        //         $pronames .= $itv['productname'].',';
        //         $pronum .= $itv['productnum'] .',';
        //         $proprice .= DePrice($itv['prouctprice']) .',';
        //         $probull .= DePrice($itv['bullamount']) .',';
        //     }
           
        //     $list[$key]['pronames'] = rtrim($pronames,',');
        //     $list[$key]['spec'] = $spec_str; 
        //     $list[$key]['barcode'] = rtrim($barcodes,',');
        //     $list[$key]['serialnumber'] = rtrim($serialnumbers,',');
        //     $list[$key]['productnum'] = rtrim($pronum,',');
        //     $list[$key]['prouctprice'] = rtrim($proprice,',');
        //     $list[$key]['probull'] = rtrim($probull,',');
        //     $list[$key]['orderstatus'] = $orderstatus_arr[$value['orderstatus']];
            
        //     $payData = $OrdOrderPay->getRow(['orderno'=>$value['orderno']],'pay_type,paytime,pay_money,pay_bull');
        //     $Logistics = $OrdOrderLogistics->getRow(['orderno'=>$value['orderno']],'realname,mobile,idnumber,city,address,leavemessage');

        //     // switch ($payData['pay_type']) {
        //     //     case '':
        //     //         $list[$key]['pay_type'] = '余额支付';
        //     //         break;
        //     //     case 'weixin_app':
        //     //         $list[$key]['pay_type'] = '微信支付';
        //     //         break;
        //     //     case 'ali_app':
        //     //         $list[$key]['pay_type'] = '支付宝支付';
        //     //         break;
        //     //      case '3':
        //     //         $list[$key]['pay_type'] = '银联支付';
        //     //         break;
        //     //     default:
        //     //         $list[$key]['pay_type'] ='';
        //     //         break;
        //     // }
        //     // $list[$key]['paytime'] = $payData['paytime'];
        //     // $list[$key]['pay_money'] = DePrice($payData['pay_money']);
        //     // $list[$key]['pay_bull'] = DePrice($payData['pay_bull']);
        //     $list[$key]['realname'] = $Logistics['realname'];
        //     $list[$key]['mobile'] = $Logistics['mobile'];
        //     $list[$key]['idnumber'] = $Logistics['idnumber'];
        //     $list[$key]['aread'] = $Logistics['city'].$Logistics['address'];
        //     $list[$key]['leavemessage'] = $Logistics['leavemessage'];

        // }

        // /*
        // array(
        //     "filename"=>"文件名"
        //     "head"=>"",
        //     "list"=>"",
        //     "offset"=>"",
        // )
        // */
        // echo $this->iniExcelData(array(
        //                     "filename"=>$filename,
        //                     "head"=>array("订单号","订单类型","下单时间","订单状态","商家名称","商家电话","买家名称","商品总金额","订单总金额","订单总牛豆数","运费","商品名称","商品属性","商品条形码","商品货号","商品数量","商品价格","商品牛豆数",'收货人姓名','收货人电话','身份证号码','收货地址','留言'), //excel表头
        //                     "list"=>$list,
        //                     "offset"=>$offset,
        //                 ));
        // exit;
    }


     public function exportGiftAction(){
        $offset     = $this->getParam("offset",0);
        $count      = 30; //每次导出的数据条数 可以设更高都可以
        $where = array();
        $where['orderstatus'] = ["in", "1,2"];
        $where = $this->searchWhere([
            "role" => "=",
            "orderstatus" => "=",
            "orderno" => "like",
            "addtime" => "times",
            "productname" => "=",
            "cust_name" => "like",
            "mobile" => "=",
        ], $where);
        
        if(!empty($where['role'])) {
            $where['id'] = ["in", "select orderid from role_order_item where productid in (select id from role_product where role = ".$where['role'].")"];
        }
        
        if(!empty($where['productname'])) {
            if(!empty($where['id'])) {
                $where['id'] = ["in", "select orderid from role_order_item where productid in (select id from role_product where role = ".$where['role'].") and productname like '%".$where['productname']."%'"];
            } else {
                $where['id'] = ["in", "select orderid from role_order_item where productname like '%".$where['productname']."%'"];
            }
            unset($where['productname']);
        }
        
        unset($where['role']);
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }

        //$where['businessid'] = $this->businessid;
        
        $orderList = Model::ins("RoleOrder")->getList($where, "orderno,cust_name,orderstatus", "id desc",$count,$offset*$count);

        $orderStat = ["1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消"];
        $role_arr = ["2" => "牛人", "3" => "牛创客"];
        foreach ($orderList as $k => $v) {
            
            $orderList[$k]['orderstatus'] =$orderStat[$v['orderstatus']] ;

            $orderItem = Model::ins("RoleOrderItem")->getRow(array("orderno" => $v['orderno']), "productname,thumb,productid");
          
            $orderList[$k]['productname'] = $orderItem['productname'];
            
            $roleProduct = Model::ins("RoleProduct")->getRow(array("id" => $orderItem['productid']), "role");
            $orderList[$k]['role'] = $role_arr[$roleProduct['role']];
            
            $orderLogistics = Model::ins("RoleOrderLogistics")->getRow(array("orderno" => $v['orderno']), "realname,mobile,address,city_id");
            $orderList[$k]['logisticsRealname'] = $orderLogistics['realname'];
            $orderList[$k]['logisticsMobile'] = $orderLogistics['mobile'];
            $areaname = CommonModel::getSysArea($orderLogistics['city_id']);
            $orderList[$k]['logisticsAddress'] = $areaname['data'].$orderLogistics['address'];
//             $orderList['list'][$k]['logisticsAddress'] = $orderLogistics['address'];
            
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $orderList[$k]['mobile'] = !empty($cus['mobile']) ? $cus['mobile'] : '';
        }
        
         //print_r($orderList);
        /*
        array(
            "filename"=>"文件名"
            "head"=>"",
            "list"=>"",
            "offset"=>"",
        )
        */
        echo $this->iniExcelData(array(
                            "filename"=>"待发货订单列表",
                            "head"=>array("订单号","姓名","订单状态","商品名称","角色身份","收货人","收货人电话","收货地址"), //excel表头
                            "list"=>$orderList,
                            "offset"=>$offset,
                        ));
        exit;
    }
    
    /**
    * @user 查看物流信息
    * @param 
    * @author jeeluo
    * @date 2017年3月18日上午11:14:47
    */
    public function lookLogisticsInfoAction() {
        if(!$this->getParam('id')) {
            $this->showError("请选择正确操作");
        }
        $where['id'] = $this->getParam("id");
//         $where['businessid'] = $this->businessid;

        // 查看该订单信息
        $order = Model::ins("OrdOrder")->getRow($where);
       
//         if(empty($order)) {
//             $this->showError("您无权限访问此处");
//         }
        if($order['orderstatus'] != self::deliveryOrderStatus && $order['orderstatus'] != self::confirmOrderStatus && $order['orderstatus'] != self::completeOrderStatus) {
            $this->showError("您无权限访问此处");
        }
        // 订单收货信息
        $logistics = Model::ins("OrdOrderLogistics")->getRow(array("orderno" => $order['orderno']), "express_name, express_no,leavemessage");
        
        // 发货之后 产生快递信息
        $expressInfo = Express::search($logistics['express_name'], $logistics['express_no']);
        
        $viewData = array(
            "expressData" => $expressInfo,
            "logisticsData" => $logistics,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 修改订单的快递信息
    * @param 
    * @author jeeluo
    * @date 2017年3月17日下午2:59:05
    */
    public function setExpressAction() {
        
//         $this->showError("入口禁止");
        
        if(empty($this->getParam('orderno'))) {
            $this->showError("请选择正确信息");
        }
        $order = Model::ins("OrdOrder")->getRow(array("orderno" => $this->getParam('orderno')));
        if(empty($order)) {
            $this->showError("您无权限访问此处");
        }
        // 识别订单是否已经被申请退款
//         $orderInfo = Model::ins("OrdOrderInfo")->getInfoRow(array("orderno" => $this->getParam("orderno")), "return_status");
//         if(empty($orderInfo)) {
//             $this->showError("请选择正确信息");
//         }

        $ordReturnInfo = Model::ins("OrdOrderReturn")->getRow(array("order_code" => $this->getParam("orderno")), "orderstatus", "id desc");
        if(!empty($ordReturnInfo)) {
            if($ordReturnInfo['orderstatus'] == self::priceWaitStatus) {
                // 已经申请退款操作  不能进行发货操作
                $this->showError("买家已申请退款，无法进行发货");
//             } else if($ordReturnInfo['orderstatus'] == self::priceExamSuccess || $ordReturnInfo['orderstatus'] == self::priceSuccess) {
//                 $this->showError("您已同意退款，无法进行发货");
            } else if($ordReturnInfo['orderstatus'] == self::priceExamFail) {
                // 异常状态
                $this->showError("订单状态异常");
            }
        }
        
//         if($orderInfo['return_status'] == self::returnIng || $orderInfo['return_status'] == self::returnSuccess) {
//             $this->showError("订单已申请退款/退货。无法进行发货");
//         }
        
        if(empty($this->getParam('express_name')) || empty($this->getParam('express_no'))) {
            $this->showError('请填写完整信息');
        }
        // 修改订单物流表数据
        $logistics['express_name'] = $this->getParam('express_name');
        $logistics['express_no'] = $this->getParam('express_no');
        $logistics['delivery_time'] = getFormatNow();
        
        $OrdOrderLogisticsEdit = new OrdOrderLogisticsEdit();
        
        if(!$OrdOrderLogisticsEdit->isValid($logistics)) {
            // 验证是否正确
            $this->showError($OrdOrderLogisticsEdit->getErr());
        } else {
            
            $amountModel = Model::ins("AmoAmount");
            $amountModel->startTrans();
            try {
                // 修改订单状态
                Model::ins("OrdOrder")->modify(array("orderstatus" => self::deliveryOrderStatus, "delivertime" => $logistics['delivery_time']), array("orderno" => $this->getParam('orderno')));
                // 修改物流数据
                $status = Model::ins("OrdOrderLogistics")->modify($logistics, array("orderno" => $this->getParam('orderno')));
    
                // Model::new("Order.OrderCount")->deCount($order['customerid'],"count_deliver");
                // Model::new("Order.OrderCount")->addCount($order['customerid'],"count_receipt");
                
                //商家结算
                Model::new("Business.Settlement")->pay([
                    "orderno"=>$this->getParam('orderno'),
                ]);
                
                $amountModel->commit();
                
                if($status) {
                    // 订单发货消息提醒
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.orderfahuo",
                        "url"=>"Order.OrderMsg.orderfahuo",
                        "param"=>[
                            "orderno"=>$order['orderno']
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                    $this->showSuccessPage('操作成功', '/Order/Index/index?type=2');
                } else {
                    $this->showError("操作错误, 请联系管理员");
                }
            } catch (\Exception $e) {
                $amountModel->rollback();
                Log::add($e,__METHOD__);
                $this->showError("操作错误，请联系管理员");
            }
        }
    }
    
    /**
     * @user 修改订单物流页面
     * @param
     * @author jeeluo
     * @date 2017年9月6日上午10:19:08
     */
    public function updateExpressInfoAction() {
        if(!$this->getParam('id')) {
            $this->showError("请选择正确操作");
        }
//         $where['id'] = $this->getParam('id');
//         $where['businessid'] = $this->businessid;
    
//         // 查看该订单信息
//         $order = Model::ins("OrdOrder")->getRow($where);
    
//         if(empty($order)) {
//             $this->showError("您无权限访问此处");
//         }
    
        $actionUrl = "/Order/Index/updateExpress?id=".$this->getParam('id');
        
        // 读取物流公司配置文件
        $expressList = Config::get("express_name");
    
        $viewData = array(
            "expressList" => $expressList,
            "actionUrl" => $actionUrl,
            "title" => "修改物流信息"
        );
    
        return $this->view($viewData);
    }
    
    /**
    * @user 修改快递信息
    * @param 
    * @author jeeluo
    * @date 2017年9月6日上午10:38:17
    */
    public function updateExpressAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        $orderLogistics = Model::ins("OrdOrderLogistics")->getRow(array("id" => $this->getParam("id")),"orderno");
        
        $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderLogistics['orderno']],"businessid");
    
        if(empty($order)) {
            $this->showError("请选择正确订单");
        }
        
        if(empty($this->params['express_name']) || empty($this->params['express_no'])) {
            $this->showError("请填写完整信息");
        }
        
        $logistics['express_name'] = $this->params['express_name'];
        $logistics['express_no'] = $this->params['express_no'];
        
        Model::ins("OrdOrderLogistics")->update($logistics, array("id" => $this->getParam("id")));
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 订单管理列表
    * @param 
    * @author jeeluo
    * @date 2017年5月11日上午10:26:16
    */
    public function giftOrderListAction() {
        $where = array();
        $where['orderstatus'] = ["in", "1,2"];
        $where = $this->searchWhere([
            "role" => "=",
            "orderstatus" => "=",
            "orderno" => "like",
            "addtime" => "times",
            "productname" => "=",
            "cust_name" => "like",
            "mobile" => "=",
        ], $where);
        
        if(!empty($where['role'])) {
            $where['id'] = ["in", "select orderid from role_order_item where productid in (select id from role_product where role = ".$where['role'].")"];
        }
        
        if(!empty($where['productname'])) {
            if(!empty($where['id'])) {
                $where['id'] = ["in", "select orderid from role_order_item where productid in (select id from role_product where role = ".$where['role'].") and productname like '%".$where['productname']."%'"];
            } else {
                $where['id'] = ["in", "select orderid from role_order_item where productname like '%".$where['productname']."%'"];
            }
            unset($where['productname']);
        }
        
        unset($where['role']);
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $orderList = Model::ins("RoleOrder")->pageList($where, "*", "id desc");
        foreach ($orderList['list'] as $k => $v) {
            $orderItem = Model::ins("RoleOrderItem")->getRow(array("orderno" => $v['orderno']), "productname,thumb,productid");
            $orderList['list'][$k]['thumb'] = $orderItem['thumb'];
            $orderList['list'][$k]['productname'] = $orderItem['productname'];
            
            $roleProduct = Model::ins("RoleProduct")->getRow(array("id" => $orderItem['productid']), "role");
            $orderList['list'][$k]['role'] = $roleProduct['role'];
            
            $orderLogistics = Model::ins("RoleOrderLogistics")->getRow(array("orderno" => $v['orderno']), "realname,mobile,address,city_id");
            $orderList['list'][$k]['logisticsRealname'] = $orderLogistics['realname'];
            $orderList['list'][$k]['logisticsMobile'] = $orderLogistics['mobile'];
            $areaname = CommonModel::getSysArea($orderLogistics['city_id']);
            $orderList['list'][$k]['logisticsAddress'] = $areaname['data'].$orderLogistics['address'];
            
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $orderList['list'][$k]['mobile'] = !empty($cus['mobile']) ? $cus['mobile'] : '';
        }
        
        $viewData = array(
            "pagelist" => $orderList['list'],
            "total" => $orderList['total'],
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 礼品快递页面
    * @param 
    * @author jeeluo
    * @date 2017年5月11日上午10:38:42
    */
    public function setGiftExpressAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        $roleOrder = Model::ins("RoleOrder")->getRow(array("id" => $this->getParam("id")));
        
        if(empty($roleOrder)) {
            $this->showError("请选择正确订单");
        }
        
        if($roleOrder['orderstatus'] != 1 && $roleOrder['orderstatus'] != 2) {
            $this->showError("无访问权限");
        }
        $action = "/Order/Index/updateGiftExpress?id=".$this->getParam("id");
        
        $orderLogistics = Model::ins("RoleOrderLogistics")->getRow(array("orderid" => $this->getParam("id")), "express_name,express_no");
        
        // 读取物流公司配置文件
        $expressList = Config::get("express_name");
        
        $viewData = array(
            "orderLogistics" => $orderLogistics,
            "actionUrl" => $action,
            "expressList" => $expressList,
            "title" => "物流信息",
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 设置快递信息
    * @param 
    * @author jeeluo
    * @date 2017年5月11日上午10:56:44
    */
    public function updateGiftExpressAction() {
        
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        $roleOrder = Model::ins("RoleOrder")->getRow(array("id" => $this->getParam("id")));
        
        if(empty($roleOrder)) {
            $this->showError("请选择正确订单");
        }
        
        if($roleOrder['orderstatus'] != 1 && $roleOrder['orderstatus'] != 2) {
            $this->showError("无访问权限");
        }
        
        if(empty($this->params['express_name']) || empty($this->params['express_no'])) {
            $this->showError("请填写完整信息");
        }
        
        $logistics['express_name'] = $this->params['express_name'];
        $logistics['express_no'] = $this->params['express_no'];
        
        $time = getFormatNow();
        if($roleOrder['orderstatus'] == 1) {
            $logistics['delivery_time'] = $time;
            
            Model::ins("RoleOrder")->modify(array("delivertime" => $time, "orderstatus" => 2), array("id" => $this->getParam("id")));
        }
        
        Model::ins("RoleOrderLogistics")->modify($logistics, array("orderid" => $this->getParam("id")));
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 查看礼品订单信息
    * @param 
    * @author jeeluo
    * @date 2017年5月11日上午11:34:41
    */
    public function lookGiftLogisticsAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        // 查看订单状态
        $orderInfo = Model::ins("RoleOrder")->getRow(array("id" => $this->getParam("id")), "orderstatus");
        
        if($orderInfo['orderstatus'] == 0 || $orderInfo['orderstatus'] == 1) {
            $this->showError("无访问权限");
        }
        
        // 订单收货信息
        $logistics = Model::ins("RoleOrderLogistics")->getRow(array("orderid" => $this->getParam("id")), "express_name,express_no");
        
        // 发货之后 产生快递信息
        $expressInfo = Express::search($logistics['express_name'], $logistics['express_no']);
        
        $viewData = array(
            "logisticsData" => $logistics,
            "expressData" => $expressInfo,
        );
        
        return $this->view($viewData);
    }
    
    public function reportGiftDataAction() {
        print_r($this->getParam("wherefields"));
        exit;
    }

    public function exportproductlistAction(){
        $offset     = $this->getParam("offset",0);
        $count      = 30; //每次导出的数据条数 可以设更高都可以
       
        
        // 查询
        
        $where["businessid"] = $this->businessid;
        if($this->getParam("orderstatus")!='') {
            $where['orderno'] = ["in","select orderno from ord_order where orderstatus='".$this->getParam("orderstatus")."'"];
        }

        if($this->getParam("cust_name")!='') {
            $where['orderno'] = ["in","select orderno from ord_order where cust_name like '%".$this->getParam("cust_name")."%'"];
        }

        $where = $this->searchWhere([
            "orderno" => "=",
            "addtime" => "times",
        ], $where);

        $orderList = Model::ins("OrdOrderItem")->getList($where, "orderno,addtime,productname,productnum,prouctprice,bullamount,skuid,businessid,productid", "addtime desc,orderno asc",$count,$offset*$count);
        $orderstatus_arr = [
            '0'=>'待付款',
            '1'=>'已付款待发货',
            '2'=>'已发货',
            '3'=>'确认收货',
            '4'=>'已完结',
            '5'=>'已取消',
        ];

        foreach ($orderList as $k => $v) {
            $orderList[$k]['prouctprice'] = DePrice($v['prouctprice']);
            $orderList[$k]['bullamount'] = DePrice($v['bullamount']);
            $order = Model::ins('OrdOrder')->getRow(['orderno'=>$v['orderno']],'orderstatus,cust_name,productamount,totalamount,bullamount as totalbullamount,actualfreight');  
            $orderList[$k]['orderstatus'] = $orderstatus_arr[$order['orderstatus']];

            $spec = Model::ins('ProProductSpec')->getRow(['id'=>$v['skuid']],'spec');
            $spec_arr = json_decode($spec['spec'],true);
            $spec_str = '';
            foreach ($spec_arr as  $value) {
               $spec_str .= $value['name'].':'.$value['value'].',';
            }
            $orderList[$k]['skuid'] = $spec_str;

            $product = Model::ins('ProProduct')->getRow(['id'=>$v['productid']],'serialnumber,barcode');
            $orderList[$k]['serialnumber'] = $product['serialnumber'];
            $orderList[$k]['barcode'] = $product['barcode'];
            unset($orderList[$k]['productid']);
                
            $business = Model::ins('BusBusinessInfo')->getRow(['id'=>$v['businessid']],'businessname,servicetel');

            $orderList[$k]['businessname'] =  $business['businessname'];
            $orderList[$k]['servicetel'] =  $business['servicetel'];
            $orderList[$k]['cust_name'] =  $order['cust_name'];
            $orderList[$k]['productamount'] = DePrice($order['productamount']);
            $orderList[$k]['totalamount'] = DePrice($order['totalamount']);
            $orderList[$k]['totalbullamount'] = DePrice($order['totalbullamount']);
            $orderList[$k]['actualfreight'] = DePrice($order['actualfreight']);
            unset($orderList[$k]['businessid']);

            $logistics = Model::ins('OrdOrderLogistics')->getRow(['orderno'=>$v['orderno']],'realname,mobile,city,address,leavemessage');
            $orderList[$k]['realname'] = $logistics['realname'];
            $orderList[$k]['mobile'] = $logistics['mobile'];
            $orderList[$k]['address'] = $logistics['city'] . $logistics['address'];
            $orderList[$k]['leavemessage'] = $logistics['leavemessage'];
        }
         
       
        /*
        array(
            "filename"=>"文件名"
            "head"=>"",
            "list"=>"",
            "offset"=>"",
        )
        */
        echo $this->iniExcelData(array(
                            "filename"=>"商品订单导出列表",
                            "head"=>array("订单号","下单时间","商品名称","购买数量","商品价格","牛豆数量","商品属性","商品货号","商品条形码","订单状态","商家名称","商家电话","买家名称","商品总额","订单总额","订单总牛豆数","运费","收货人姓名","收货人电话","收货地址","留言"), //excel表头
                            "list"=>$orderList,
                            "offset"=>$offset,
                        ));
        exit;
    }
}