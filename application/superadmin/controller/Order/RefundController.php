<?php
// +----------------------------------------------------------------------
// |  [ 订单管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-03-18
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Order;

use app\lib\Model;
use app\superadmin\ActionController;
use app\form\OrdReturnLog\OrdReturnLogAdd;
use app\model\OrdOrderItemModel;
use app\lib\Log;
use app\lib\Db;
use app\model\Profit\Cash_abstract;
use app\lib\Express;

class RefundController extends ActionController {
    
    // 退单未审核
    const price_type = 1;
    const goods_type = 2;
    
    const priceExamStatus = 1;
    const goodsExamStatus = 11;
    
    const priceExamSuccess = 2;
    const goodsExamSuccess = 12;
    
    const priceExamFail = 3;
    const goodsExamFail = 13;
    
    const salePriceSuccess = 4;
    const saleGoodsSuccess = 14;
    
    const initNum = 0;
    const logStatusFail = 2;
    const logStatusSuccess = 3;
    
    const enableSuccess = 1;
    const enableFail = -1;
    
    const returnSuccess = 2;
    const cancleSourceBus = 2;
    const cancleOrder = 5;
    
    const key = 'order_return_';
    
    public function __construct() {
        parent::__construct();
    }
    
    /** 
    * @user 待审核列表
    * @param 
    * @author jeeluo
    * @date 2017年3月18日上午11:30:52
    */
    public function indexAction() {
        // 查询 获取所有的待审核的退款 退货订单
//         $where['business_id'] = $this->businessid;
        $where['orderstatus'] = array(
            array("=", "1"),
            array("=", "11", "or"),
        );
        
        $where = $this->searchWhere([
            "order_code" => "like",
            "returnno" => "like",
        ], $where);
        
        $list = Model::ins("OrdOrderReturn")->getReturnPageList($where, "*", "starttime desc");
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        
        return $this->view($viewData);
    }

        /**
     * [extportsuccessAction 导出订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-06T14:30:21+0800
     * @return   [type]                   [description]
     */
    public function extportindexAction(){
        $offset     = $this->getParam("offset",0);
        $count      = 30; //每次导出的数据条数 可以设更高都可以
          // 查询 获取所有的待审核的退款 退货订单
        // 查询 获取所有的待审核的退款 退货订单
        $filename = '退货订单导出列表';
        $where['orderstatus'] = array(
            array("=", "1"),
            array("=", "11", "or"),
        );
        
        $where = $this->searchWhere([
            "order_code" => "like",
            "returnno" => "like",
        ], $where);
        //退货单状态：1 退款等待审核 2退款审核通过 3退款审核不通过 4退款成功 11 退货退款等待审核 12 退款审核通过 13 退款不通过 14退款成功 20取消退货/退款 31 退款中 32 退款成功
        $orderstatus_arr = [
            '1'  => '退款等待审核',
            '2'  => '退款审核通过',
            '3'  => '退款审核不通过',
            '4'  => '退款成功',
            '11' => '退货退款等待审核',
            '12' => '退款审核通过',
            '13' => '退款不通过',
            '14' => '退款成功',
            '20' => '取消退货/退款',
            '31' => '退款中',
            '32' => '退款成功'
        ];
        $list = Model::ins("OrdOrderReturn")->getList($where, "order_code,returnno,returnreason,business_name,customer_name,orderstatus,productname,productnum,skudetail,return_address,returnamount,returnbull,freight,actualamount,expressname,expressnumber", "examinetime desc",$count,$offset*$count);
        foreach ($list as $key => $value) {
            $list[$key]['orderstatus'] = $orderstatus_arr[$value['orderstatus']];
        }
        echo $this->iniExcelData(array(
                            "filename"=>$filename,
                            "head"=>array("订单号","退货单号","退货原因","商家名称","用户名称","订单状态","商品名称","商品数量","商品属性","退货地址","退回金额","退回牛豆","退货运费","退货订单总额","快递名称","快递单号"), //excel表头
                            "list"=>$list,
                            "offset"=>$offset,
                        ));
        exit;
    }

    
    public function successAction() {
        // 查询 获取所有的待审核的退款 退货订单
//         $where['business_id'] = $this->businessid;
        $where['return_type'] = 2;
        $where['orderstatus'] = array(
            array("=", "2"),
            array("=", "12", "or"),
            array("=", "4", "or"),
            array("=", "14", "or"),
        );
        
        $where = $this->searchWhere([
            "order_code" => "like",
            "returnno" => "like",
        ], $where);
        
        $list = Model::ins("OrdOrderReturn")->getReturnPageList($where, "*", "examinetime desc");
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 查看退货物流信息
    * @param 
    * @author jeeluo
    * @date 2017年9月6日下午7:38:43
    */
    public function lookLogisticsInfoAction() {
        // 订单详情
        if(!$this->getParam("id")) {
            $this->showError("请选择正确操作");
        }
        $where['id'] = $this->getParam('id');
        //         $where['business_id'] = $order_where['orderno'] = $this->businessid;
        $where['return_type'] = 2;
        $where['orderstatus'] = array(
            array("=", "2"),
            array("=", "12", "or"),
            array("=", "4", "or"),
            array("=", "14", "or"),
        );
        // 查看退单信息
        $returnOrder = Model::ins("OrdOrderReturn")->getRow($where, "id,expressname,expressnumber");
        if(empty($returnOrder['id'])) {
            $this->showError("请选择正确退单");
        }
        
        // 发货之后 产生快递信息
        $expressInfo = Express::search($returnOrder['expressname'], $returnOrder['expressnumber']);
        $viewData = array(
            "expressData" => $expressInfo,
            "returnOrder" => $returnOrder,
        );
        return $this->view($viewData);
    }

    /**
     * [extportsuccessAction 导出订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-06T14:30:21+0800
     * @return   [type]                   [description]
     */
    public function extportsuccessAction(){
        $offset     = $this->getParam("offset",0);
        $count      = 30; //每次导出的数据条数 可以设更高都可以
        $filename = '退货订单导出列表';
          // 查询 获取所有的待审核的退款 退货订单
       
        $where['return_type'] = 2;
        $where['orderstatus'] = array(
            array("=", "2"),
            array("=", "12", "or"),
            array("=", "4", "or"),
            array("=", "14", "or"),
        );
        
        $where = $this->searchWhere([
            "order_code" => "like",
            "returnno" => "like",
        ], $where);
        //退货单状态：1 退款等待审核 2退款审核通过 3退款审核不通过 4退款成功 11 退货退款等待审核 12 退款审核通过 13 退款不通过 14退款成功 20取消退货/退款 31 退款中 32 退款成功
        $orderstatus_arr = [
            '1'  => '退款等待审核',
            '2'  => '退款审核通过',
            '3'  => '退款审核不通过',
            '4'  => '退款成功',
            '11' => '退货退款等待审核',
            '12' => '退款审核通过',
            '13' => '退款不通过',
            '14' => '退款成功',
            '20' => '取消退货/退款',
            '31' => '退款中',
            '32' => '退款成功'
        ];
        $list = Model::ins("OrdOrderReturn")->getList($where, "order_code,returnno,returnreason,business_name,customer_name,orderstatus,productname,productnum,skudetail,return_address,returnamount,returnbull,freight,actualamount,expressname,expressnumber", "examinetime desc",$count,$offset*$count);
        foreach ($list as $key => $value) {
            $list[$key]['orderstatus'] = $orderstatus_arr[$value['orderstatus']];
        }
        echo $this->iniExcelData(array(
                            "filename"=>$filename,
                            "head"=>array("订单号","退货单号","退货原因","商家名称","用户姓名","订单状态","商品名称","商品数量","商品属性","退货地址","退回金额","退回牛豆","退货运费","退货订单总额","快递名称","快递单号"), //excel表头
                            "list"=>$list,
                            "offset"=>$offset,
                        ));
        exit;
        exit;
    }
    
    /**
    * @user 查看审核状态
    * @param 
    * @author jeeluo
    * @date 2017年3月20日上午11:04:08
    */
    public function editRefundAction() {
        // 订单详情
        if(!$this->getParam("id")) {
            $this->showError("请选择正确操作");
        }
        $where['id'] = $this->getParam("id");
//         $where['business_id'] = $order_where['businessid'] = $this->businessid;
        $where['orderstatus'] = array(
            array("=", "1"),
            array("=", "11", "or"),
        );
        // 查看退单信息
        $returnOrder = Model::ins("OrdOrderReturn")->getRow($where, "return_type, returnreason, remark, images, productnum, returnamount, returnbull, order_code, productid, skuid, id, starttime");
        if(empty($returnOrder)) {
            $this->showError("您无权限访问此处");
        }
        
        $order_where['orderno'] = $returnOrder['order_code'];
//         $order_where['businessid'] = $this->businessid;
        $order = Model::ins("OrdOrder")->getRow($order_where, "actualfreight, productamount, bullamount");
        
        // 订单详细商品
        $itemInfo = Model::ins("OrdOrderItem")->getAllItemDetailByOrderNO(array("orderno" => $returnOrder['order_code'], "enable" => 1));
        
        // 退单详细商品
        $returnItemInfo = Model::ins("OrdOrderItem")->getItemDetailBySkuid(array("productid" => $returnOrder['productid'], "skuid" => $returnOrder['skuid'], "enable" => 1));
        
        $action_arr['refundExam'] = "/Order/Refund/setRefund";
        
        $viewData = array(
            "title" => "查看退单详情",
            "orderData" => $order,
            "returnData" => $returnOrder,
            "itemData" => $itemInfo,
            "returnItemData" => $returnItemInfo,
            "actionArr" => $action_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 设置退单信息
    * @param 
    * @author jeeluo
    * @date 2017年3月20日下午1:56:04
    */
    public function setRefundAction() {
        if(empty($this->getParam('return_id'))) {
            $this->showError("请选择正确信息");
        }
        $returnInfo = Model::ins("OrdOrderReturn")->getRow(array("id" => $this->getParam("return_id")));
        if(empty($returnInfo)) {
            $this->showError("请选择正确信息");
        }
        $order = Model::ins("OrdOrder")->getRow(array("orderno" => $returnInfo['order_code']));
        if(empty($order)) {
            $this->showError("您无权限访问此处");
        }
        // 避免重复修改，查看该退单信息是否已经修改了
        if($returnInfo['return_type'] == self::price_type) {
            if($returnInfo['orderstatus'] != self::priceExamStatus) {
                $this->showError("您无法重复操作此处");
            }
        } else if($returnInfo['return_type'] == self::goods_type) {
            if($returnInfo['orderstatus'] != self::goodsExamStatus) {
                $this->showError("您无法重复操作此处");
            }
        }
        
        $key = self::key.$this->getParam("return_id");
        $returnRedis = Model::Redis("OrderReturn");
        if($returnRedis->exists($key)) {
            $this->showError("您无法重复操作此处");
            exit;
        } else {
            $returnRedis->set($key, 1, 60);
        }
        
        $orderstatus = $this->getParam('orderstatus');
        
        $logStatus = self::initNum;
        $logContent = '';
        if($orderstatus == self::priceExamSuccess || $orderstatus == self::goodsExamSuccess) {
            $logStatus = self::logStatusSuccess;
            $logContent = '卖家同意申请';
            if($returnInfo['return_type'] == 1) {
                $orderstatus = self::salePriceSuccess;
                $logStatus = 4;
                $logContent = "卖家退款成功";
            }
        } else if($orderstatus == self::priceExamFail || $orderstatus == self::goodsExamFail) {
            $logStatus = self::logStatusFail;
            $logContent = '卖家拒绝申请';
        }
        
        $end = date('Y-m-d H:i:s', strtotime("+3 day"));
        
        
        // 写入协商记录
        $returnlog = array("returnno" => $returnInfo['returnno'], "orderno" => $returnInfo['order_code'], "productid" => $returnInfo['productid'], "skuid" => $returnInfo['skuid'], "actionsource" => 2, 
                        "customerid" => $returnInfo['customerid'], "businessid" => $returnInfo['business_id'], "orderstatus" => $logStatus, "content" => $logContent, "remark" => $this->getParam("reason"), "addtime" => getFormatNow());
        
        $goodsstatus = 1;
        // 修改退单状态
        $updateReturn = array("examinetime" => getFormatNow(), "actiontime" => getFormatNow(), "endtime" => $end, "orderstatus" => $orderstatus, "audit_remark" => $this->getParam("reason"));
        $selfItem = Model::ins("OrdOrderItem")->getRow(array("orderno" => $returnInfo['order_code'], "productid" => $returnInfo['productid'], "skuid" => $returnInfo['skuid']));
        
        $infoReturn = array("return_status" => self::returnSuccess, "return_success_time" => getFormatNow());

        $item_arr = array("intended_return" => $returnInfo['productnum']);
        if($selfItem['productnum'] - $returnInfo['productnum'] == 0) {
            // 查询该订单下 还有其它未完结的商品退单
            $order_where = array("orderno" => array("=", $returnInfo['order_code']), "enable" => array("=", 1), "id" => array("!=", $selfItem['id']));
            $orderItem = Model::ins("OrdOrderItem")->getRow($order_where);
            
            if($returnInfo['return_type'] == self::price_type) {
                if(empty($orderItem)) {
                    $goodsstatus = 2;
                    $infoReturn = array("return_status" => self::returnSuccess, "return_success_time" => getFormatNow(), "cancelsource" => self::cancleSourceBus,
                        "cancelreason" => $returnInfo['returnreason'], "audit_remark" => $returnInfo['remark'], "finish_time" => getFormatNow());
                } else {
                    $goodsstatus = 3;
                }
                
                $item_arr['enable'] = -1;
            }
        }
        
        $ordReturnLog = new OrdReturnLogAdd();
        if(!$ordReturnLog->isValid($returnlog)) {
            // 验证是否正确
            $this->showError($ordReturnLog->getErr());
        } else {
            
            $amountModel = Model::ins("AmoAmount");
            $amountModel->startTrans();
            
            try {
                
                if($orderstatus == self::priceExamFail || $orderstatus == self::goodsExamFail) {
                    Model::ins("OrdOrderReturn")->modify($updateReturn, array("id" => $this->getParam("return_id")));
                    Model::ins("OrdOrderInfo")->modify($infoReturn, array("orderno" => $returnInfo['order_code']));
                    
                    $status = Model::ins("OrdReturnLog")->add($returnlog);
                    $amountModel->commit();
                    
                    // 用户拒绝退单提醒消息
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.orderRefuseOragree",
                        "url"=>"Order.OrderMsg.orderRefuseOragree",
                        "param"=>[
                            "returnno"=>$returnInfo['returnno'],
                            "type"=>2,
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                    $this->showSuccessPage('操作成功', '/Order/Refund/index');
                }
                
                if($returnInfo['return_type'] == self::price_type) {
                    
                    // 查询订单是否有发货了
                    $orderLogistics = Model::ins("OrdOrderLogistics")->getRow(array("orderno" => $returnInfo['order_code']), "express_no");
                    
                    if(!empty($orderLogistics['express_no'])) {
                        // 已发货
                        Model::new("Business.Settlement")->returnpay(array("returnno"=>$returnInfo['returnno']));
                    } else {
                        Model::new("Business.Settlement")->returnfutpay(array("returnno"=>$returnInfo['returnno']));
                    }
                    //生成流水号
                    $flowid = Model::new("Amount.Flow")->getFlowId($returnInfo['order_code']);
                    
                    $returnInfo['returnamount'] = $returnInfo['returnamount']+$returnInfo['freight'];
                    // 退款金额回到用户账号中
                    if($returnInfo['returnamount']>0)
                        Model::new("Amount.Amount")->add_cashamount([
                            "userid"=>$returnInfo['customerid'],
                            "amount" => $returnInfo['returnamount'],
                            "usertype"=>"2",
                            "orderno"=>$returnInfo['order_code'],
                            "flowtype"=>48,
                            "role"=>1,
                            "tablename"=>"AmoFlowCusCash",
                            "flowid"=>$flowid,
                            "remark"=>"1",
                        ]);
                    
                    if($returnInfo['returnbull']>0)
                        Model::new("Amount.Amount")->add_bullamount([
                            "userid"=>$returnInfo['customerid'],
                            "amount" => $returnInfo['returnbull'],
                            "usertype"=>"2",
                            "orderno"=>$returnInfo['order_code'],
                            "flowtype"=>49,
                            "role"=>1,
                            "tablename"=>"AmoFlowCusBull",
                            "flowid"=>$flowid,
                            "remark"=>"2",
                        ]);
                            
                    // 调用退款分润方法
                    $profit['orderno'] = $returnInfo['order_code'];
                    $profit['userid'] = $returnInfo['customerid'];
                    Model::new("Amount.Profit")->deductionprofit($profit);
                    
                    $updateReturn['endtime'] = $updateReturn['examinetime'];
                }
                
                // 用户同意退单提醒消息
                Model::new("Sys.Mq")->add([
                    // "url"=>"Msg.SendMsg.orderRefuseOragree",
                    "url"=>"Order.OrderMsg.orderRefuseOragree",
                    "param"=>[
                        "returnno"=>$returnInfo['returnno'],
                        "type"=>1,
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
                
                Model::ins("OrdOrderReturn")->modify($updateReturn, array("id" => $this->getParam("return_id")));
                Model::ins("OrdOrderInfo")->modify($infoReturn, array("orderno" => $returnInfo['order_code']));

                if($goodsstatus == 2) {
                    // 订单关闭消息提醒
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.ordercose",
                        "url"=>"Order.OrderMsg.ordercose",
                        "param"=>[
                            "orderno"=>$returnInfo['order_code']
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                    Model::ins("OrdOrder")->modify(array("orderstatus" => self::cancleOrder), array("orderno" => $returnInfo['order_code']));
                }
                Model::ins("OrdOrderItem")->modify($item_arr, array("orderno" => $returnInfo['order_code'], "productid" => $returnInfo['productid'], "skuid" => $returnInfo['skuid']));
                $status = Model::ins("OrdReturnLog")->add($returnlog);
                
                $amountModel->commit();
                
                // 清楚缓存
                $returnRedis->del($key);
            } catch (\Exception $e) {
                $amountModel->rollback();
                // 清楚缓存
                $returnRedis->del($key);
                Log::add($e,__METHOD__);
                $this->showError("操作错误，请联系管理员");
            }
            if($status) {
                $this->showSuccessPage('操作成功', '/Order/Refund/index');
            } else {
                $this->showError("操作错误，请联系管理员");
            }
        }
    }
    
    /**
     * @user 确认退单状态
     * @param
     * @author jeeluo
     * @date 2017年3月20日上午11:04:08
     */
    public function confirmRefundAction() {
        // 订单详情
        if(!$this->getParam("id")) {
            $this->showError("请选择正确操作");
        }
        $where['id'] = $this->getParam('id');
//         $where['business_id'] = $order_where['orderno'] = $this->businessid;
        $where['orderstatus'] = array(
            array("=", "2"),
            array("=", "12", "or"),
            array("=", "4", "or"),
            array("=", "14", "or"),
        );
        // 查看退单信息
        $returnOrder = Model::ins("OrdOrderReturn")->getRow($where, "return_type, returnreason, remark, images, productnum, returnamount, returnbull, order_code, productid, skuid, id, orderstatus, examinetime, actiontime");
        if(empty($returnOrder)) {
            $this->showError("您无权限访问此处");
        }
    
        $order_where['orderno'] = $returnOrder['order_code'];
        $order = Model::ins("OrdOrder")->getRow($order_where, "actualfreight, productamount, bullamount");
    
        // 订单详细商品
        $itemInfo = Model::ins("OrdOrderItem")->getAllItemDetailByOrderNO(array("orderno" => $returnOrder['order_code'], "enable" => 1));
    
        // 退单详细商品
        $returnItemInfo = Model::ins("OrdOrderItem")->getItemDetailBySkuid(array("productid" => $returnOrder['productid'], "skuid" => $returnOrder['skuid'], "enable" => 1));
    
        $action_arr['confirmRefund'] = "/Order/Refund/setConfirmRefund";
        
        $viewData = array(
            "title" => "查看退单详情",
            "orderData" => $order,
            "returnData" => $returnOrder,
            "itemData" => $itemInfo,
            "returnItemData" => $returnItemInfo,
            "actionArr" => $action_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 设置审核订单状态
    * @param 
    * @author jeeluo
    * @date 2017年3月20日下午4:41:38
    */
    public function setConfirmRefundAction() {
        if(empty($this->getParam('return_id'))) {
            $this->showError("请选择正确信息");
        }
        $returnInfo = Model::ins("OrdOrderReturn")->getRow(array("id" => $this->getParam("return_id")));
        if(empty($returnInfo)) {
            $this->showError("请选择正确信息");
        }
        $order = Model::ins("OrdOrder")->getRow(array("orderno" => $returnInfo['order_code']));
        if(empty($order)) {
            $this->showError("您无权限访问此处");
        }
        $orderstatus = 0;
        // 避免重复修改，查看该退单信息是否已经修改了
        if($returnInfo['return_type'] == self::price_type) {
            if($returnInfo['orderstatus'] != self::priceExamSuccess) {
                $this->showError("您无法重复操作此处");
            }
            $orderstatus = self::salePriceSuccess;
        } else if($returnInfo['return_type'] == self::goods_type) {
            if($returnInfo['orderstatus'] != self::goodsExamSuccess) {
                $this->showError("您无法重复操作此处");
            }
            $orderstatus = self::saleGoodsSuccess;
        }
        
        $key = self::key.$this->getParam("return_id");
        $returnRedis = Model::Redis("OrderReturn");
        if($returnRedis->exists($key)) {
            $this->showError("您无法重复操作此处");
            exit;
        } else {
            $returnRedis->set($key, 1, 60);
        }
        
        $confirm = $this->getParam('confirm');
        
        if($confirm) {
            $goodsstatus = 1;
            // 看这订单下是否还有剩余商品
//             $otherItem = Model::ins("OrdOrderItem")->getRow(array("orderno" => $returnInfo['order_code'], "enable" => self::enableSuccess));
            $selfItem = Model::ins("OrdOrderItem")->getRow(array("orderno" => $returnInfo['order_code'], "productid" => $returnInfo['productid'], "skuid" => $returnInfo['skuid']));
            
            $infoReturn = array("return_status" => self::returnSuccess, "return_success_time" => getFormatNow());
            
            $item_arr = array("returnnum" => $selfItem['returnnum']+$returnInfo['productnum'], "intended_return" => 0);
//             $item_arr = array("productnum" => $selfItem['productnum'] - $returnInfo['productnum']);
            if($selfItem['productnum'] - $returnInfo['productnum'] == 0) {
                // 查询该订单下 还有其它未完结的商品退单
                $order_where = array("orderno" => array("=", $returnInfo['order_code']), "enable" => array("=", 1), "id" => array("!=", $selfItem['id']));
                $orderItem = Model::ins("OrdOrderItem")->getRow($order_where);
                
                if(empty($orderItem)) {
                    $goodsstatus = 2;
                }
                
//                 $orderItem = Model::ins("OrdOrderItem")->getRow(array("orderno" => $returnInfo['order_code'], "enable" => 1));
                
//                 if(empty($orderItem)) {
//                     $goodsstatus = 2;
//                 } else {
//                     $goodsstatus = 3;
//                 }
                $infoReturn = array("return_status" => self::returnSuccess, "return_success_time" => getFormatNow(), "cancelsource" => self::cancleSourceBus, 
                                "cancelreason" => $returnInfo['returnreason'], "audit_remark" => $returnInfo['remark'], "finish_time" => getFormatNow());
                
                $item_arr['enable'] = -1;
            } else {
                $item_arr['productnum'] = $selfItem['productnum'] - $returnInfo['productnum'];
            }
            
            $return_arr = array("orderstatus" => $orderstatus, "actiontime" => getFormatNow(), "endtime" => getFormatNow());
            
            $logContent = "卖家确认退款";
            // 写入协商记录
            $returnlog = array("returnno" => $returnInfo['returnno'], "orderno" => $returnInfo['order_code'], "productid" => $returnInfo['productid'], "skuid" => $returnInfo['skuid'], "actionsource" => 2,
                "customerid" => $returnInfo['customerid'], "businessid" => $returnInfo['business_id'], "orderstatus" => 4, "content" => $logContent, "addtime" => getFormatNow());
            
            $returnlogOBJ = new OrdReturnLogAdd();
            if(!$returnlogOBJ->isValid($returnlog)) {
                // 验证是否正确
                $this->showError($returnlogOBJ->getErr());
            } else {
                
                $amountModel = Model::ins("AmoAmount");
                $amountModel->startTrans();
                
                try {
                    // 扣减商家钱
                    Model::new("Business.Settlement")->returnpay(array("returnno"=>$returnInfo['returnno']));
                    //生成流水号
                    $flowid = Model::new("Amount.Flow")->getFlowId($returnInfo['order_code']);
                    // 退款金额回到用户账号中
                    if($returnInfo['returnamount']>0)
                        Model::new("Amount.Amount")->add_cashamount([
                            "userid"=>$returnInfo['customerid'],
                            "amount" => $returnInfo['returnamount'],
                            "usertype"=>"2",
                            "orderno"=>$returnInfo['order_code'],
                            "flowtype"=>48,
                            "role"=>1,
                            "tablename"=>"AmoFlowCusCash",
                            "flowid"=>$flowid,
                            "remark"=>"3",
                        ]);
                    
                    if($returnInfo['returnbull']>0)
                        Model::new("Amount.Amount")->add_bullamount([
                            "userid"=>$returnInfo['customerid'],
                            "amount" => $returnInfo['returnbull'],
                            "usertype"=>"2",
                            "orderno"=>$returnInfo['order_code'],
                            "flowtype"=>49,
                            "role"=>1,
                            "tablename"=>"AmoFlowCusBull",
                            "flowid"=>$flowid,
                            "remark"=>"4",
                        ]);
                    
                    // 调用退款分润方法
                    $profit['orderno'] = $returnInfo['order_code'];
                    $profit['userid'] = $returnInfo['customerid'];
                    Model::new("Amount.Profit")->deductionprofit($profit);
                    
                    // 订单信息表
                    if($goodsstatus == 2) {
                        // 订单关闭消息提醒
                        Model::new("Sys.Mq")->add([
                            // "url"=>"Msg.SendMsg.ordercose",
                            "url"=>"Order.OrderMsg.ordercose",
                            "param"=>[
                                "orderno"=>$returnInfo['order_code']
                            ],
                        ]);
                        Model::new("Sys.Mq")->submit();
                        Model::ins("OrdOrder")->modify(array("orderstatus" => self::cancleOrder), array("orderno" => $returnInfo['order_code']));
                    }
                    if(!empty($item_arr)) {
                        Model::ins("OrdOrderItem")->modify($item_arr, array("orderno" => $returnInfo['order_code'], "productid" => $returnInfo['productid'], "skuid" => $returnInfo['skuid']));
                    }
                    Model::ins("OrdOrderInfo")->modify($infoReturn, array("orderno" => $returnInfo['order_code']));
                    Model::ins("OrdOrderReturn")->modify($return_arr, array("id" => $returnInfo['id']));

                    $amountModel->commit();
                    $returnRedis->del($key);
                    $this->showSuccessPage("操作成功", "/Order/Refund/success");
                } catch (\Exception $e) {
                    $amountModel->rollback();
                    $returnRedis->del($key);
                    Log::add($e,__METHOD__);
                    $this->showError("操作错误，请联系管理员");
                }
            }
        } else {
            $this->showError("操作错误，请联系管理员");
        }
    }
    
    
    
    /**
     * @user 对已存在的 已审核通过的订单或者已成功的订单 进行余额流水
     * @param
     * @author jeeluo
     * @date 2017年5月5日下午2:15:28
     */
    public function refundAmountAction() {
        $OrdOrderReturn = Model::ins("OrdOrderReturn");
    
        $pagesize = 50;
        $page = 1;
    
        while(true) {
            $where = "(return_type = 1 AND (orderstatus = 2 OR orderstatus = 4)) OR (return_type = 2 AND orderstatus = 14)";
            $list = $OrdOrderReturn->pageList($where, "*", "id desc", 0, $page, $pagesize);
    
            $page += 1;
            if(!empty($list)) {
                foreach ($list as $k => $v) {
                    Model::ins("AmoAmount")->startTrans();
    
                    try {
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

                        Model::ins("AmoAmount")->commit();
                    } catch (\Exception $e) {
                        Model::ins("AmoAmount")->rollback();
                        Log::add($e,__METHOD__);
                    }
                }
            }
    
            if(count($list) == 0 || count($list) < $pagesize)
                break;
        }

        echo "OK";
        exit;
    }

    /**
     * 对已发货的订单给商家增加营业收入
     * @Author   zhuangqm
     * @DateTime 2017-05-05T14:55:54+0800
     * @return   [type]                   [description]
     */
    public function buspayAction(){
        exit;
        $orderlist = Model::ins("OrdOrder")->getList(["orderstatus"=>[">=",2]],"orderno","id desc");

        foreach($orderlist as $order){

            $count = Model::ins("AmoFlowBusCash")->getRow(["orderno"=>$order['orderno']],"count(*) as count");

            if($count['count']==0){

                Model::ins("AmoAmount")->startTrans();
                try {

                    // 商家结算
                    Model::new("Business.Settlement")->pay([
                                            "orderno"=>$order['orderno'],
                                        ]);
                    Model::ins("AmoAmount")->commit();

                } catch (\Exception $e) {

                    Model::ins("AmoAmount")->rollback();

                    Log::add($e,__METHOD__);
                }
                
            }
        }
        echo "OK";
        exit;
    }

    /**
     * 数据回滚
     * @Author   zhuangqm
     * @DateTime 2017-05-06T12:19:51+0800
     * @return   [type]                   [description]
     */
    public function reAction(){
        
        exit;
        $list = Model::ins("AmoFlowCusComCash")->getList([],"*","id desc");
        print_r($list);
        foreach($list as $k=>$v){

            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$v['orderno']],"orderstatus");

            if($order['orderstatus']==5){

                //把钱扣掉
                Model::ins("AmoAmount")->DedComAmount($v['userid'],$v['amount']);

                Model::ins("AmoFlowCusComCash")->del(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowBusCash")->del(["orderno"=>$v['orderno']]);
            }

            
        }
        echo "OK";
        exit;
    }

    public function paybusAction(){
        exit;
        Model::ins("AmoAmount")->DedComAmount(32,21800);
        echo "OK";
        exit;
    }

    /**
     * 订单取消以后返还用户金额
     * @Author   zhuangqm
     * @DateTime 2017-05-09T11:47:59+0800
     * @return   [type]                   [description]
     */
    public function returnuserpayAction(){

        //所有取消订单都找出来 并且找到自动取消的订单
        $list = Model::ins("OrdOrder")->getList("orderstatus=5","*");

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{
            foreach($list as $k=>$v){

                //查询自动取消的订单
                $canlorder = Model::ins("OrdOrderInfo")->getRow(["orderno"=>$v['orderno']],"id,cancelsource");

                if($canlorder['cancelsource']==3){
                    print_r($v);
                    //生成流水号
                    $flowid = Model::new("Amount.Flow")->getFlowId($v['orderno']);
                    if($v['totalamount']>0){
                        // 查询有没流水记录
                        $row = Model::ins("AmoFlowCusCash")->getRow(["orderno"=>$v['orderno'],"flowtype"=>48],"count(*) as count");

                        if($row['count']==0){
                            echo "=====totalamount没有给钱=====<br>";
                            
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
                        }else{
                            echo "=====totalamount给钱=====<br>";
                        }
                    }


                    if($v['bullamount']>0){

                        // 查询有没流水记录
                        $row = Model::ins("AmoFlowCusBull")->getRow(["orderno"=>$v['orderno'],"flowtype"=>49],"count(*) as count");

                        if($row['count']==0){
                            echo "=====bullamount没有给钱=====<br>";

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

                        }else{
                            echo "=====bullamount给钱=====<br>";
                        }
                    }
                }
            }

            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK";
        exit;
    }

    public function aaAction(){
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{
           /* $list = Model::ins("AmoFlowCusCash")->getList(["flowtype"=>48],"*");
            print_r($list);
            foreach($list as $k=>$v){
               $row = Model::ins("OrdOrderPay")->getRow(["orderno"=>$v['orderno']],"count(*) as count");

               if($row['count']==0){
                    Model::ins("AmoAmount")->DedCashAmount($v['userid'],$v['amount']);

                    Model::ins('AmoFlowCusCash')->delete(["orderno"=>$v['orderno'],"flowtype"=>48]);
                    Model::ins('AmoFlowCash')->delete(["orderno"=>$v['orderno'],"flowtype"=>48]);
               }
            }
*/
            $list = Model::ins("AmoFlowCusBull")->getList(["flowtype"=>49],"*");
            print_r($list);
            foreach($list as $k=>$v){
               $row = Model::ins("OrdOrderPay")->getRow(["orderno"=>$v['orderno']],"count(*) as count");

               if($row['count']==0){
                    Model::ins("AmoAmount")->DedBullAmount($v['userid'],$v['amount']);

                    Model::ins('AmoFlowCusBull')->delete(["orderno"=>$v['orderno'],"flowtype"=>49]);
                    Model::ins('AmoFlowBull')->delete(["orderno"=>$v['orderno'],"flowtype"=>49]);
               }
            }
            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK";
        exit;
    }
    
    public function bbAction() {
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();
        
        $updateRecord = Model::ins("AmoFlowCash")->getRow(["id"=>18856],"userid,flowid");
        
        $deleteRecord = Model::ins("AmoFlowCash")->getRow(["id"=>18855],"flowid");
        try {
            if(!empty($updateRecord)) {
                Model::ins("AmoFlowCash")->update(["amount"=>3000],["flowid"=>$updateRecord['flowid']]);
                
                Model::ins("AmoFlowCusCash")->update(["amount"=>3000],["flowid"=>$updateRecord['flowid']]);
            }
            
            if(!empty($deleteRecord)) {
                Model::ins("AmoFlowCash")->delete(["id"=>18855]);
                Model::ins("AmoFlowCusCash")->delete(["id"=>18855]);
            }
            
            Model::ins("AmoAmount")->DedCashAmount($updateRecord['userid'],1800);
            
            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->delRedis($updateRecord['userid']);
            $amountModel->rollback();
            echo "ERROR";
            exit;
        }
        echo "OK";
        exit;
    }

    public function userstoprofitAction(){
        exit;
        echo "dddddddddddddddd";
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{
        
            $list = Model::ins("StoPayFlow")->getList(["customerid"=>513],"*");
           
            /*foreach($list as $k=>$v){
                $v['orderno'] = $v['pay_code'];
                //清数据
                $amo_profit = Model::ins("AmoProfit")->getList(["orderno"=>$v['orderno']],"*");

                $profit = [];
                foreach($amo_profit as $key=>$value){
                    $profit[$value['flowtype']] = $value;
                }

                print_r($profit);

                
                if(!empty($profit['22']) && $profit['22']['profit_amount']>0){
                    Model::ins("AmoAmount")->DedProfitAmount($profit['22']['userid'],$profit['22']['profit_amount']);
                    
                    Model::ins("AmoComAmount")->AddProfitAmount($profit['22']['profit_amount']);
                }
                
                if(!empty($profit['26']) && $profit['26']['profit_amount']>0){

                    Model::ins("AmoComAmount")->DedProfitAmount($profit['26']['profit_amount']);
                    
                }
                // -----------------------------------------------
                
                if(!empty($profit['28']) && $profit['28']['profit_amount']>0){

                    Model::ins("AmoAmount")->DedBullAmount($profit['28']['userid'],$profit['28']['profit_amount']);
                    
                    Model::ins("AmoComAmount")->AddBullAmount($profit['28']['profit_amount']);
                    
                }

                if(!empty($profit['34']) && $profit['34']['profit_amount']>0){

                    Model::ins("AmoComAmount")->DedBullAmount($profit['34']['profit_amount']);
                }
  

                if(!empty($profit['11']) && $profit['11']['profit_amount']>0){

                    Model::ins("AmoAmount")->DedComAmount($profit['11']['userid'],$profit['11']['profit_amount']);
                }

                if(!empty($profit['12']) && $profit['12']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['12']['userid'],$profit['12']['profit_amount']);
                }
                
                if(!empty($profit['3']) && $profit['3']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['3']['userid'],$profit['3']['profit_amount']);
                }

                if(!empty($profit['4']) && $profit['4']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['4']['userid'],$profit['4']['profit_amount']);
                }

                if(!empty($profit['7']) && $profit['7']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedComAmount($profit['7']['userid'],$profit['7']['profit_amount']);
                }


                if(!empty($profit['8']) && $profit['8']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['8']['userid'],$profit['8']['profit_amount']);
                }

                if(!empty($profit['9']) && $profit['9']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedComAmount($profit['9']['userid'],$profit['9']['profit_amount']);
                }

                if(!empty($profit['10']) && $profit['10']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['10']['userid'],$profit['10']['profit_amount']);
                }

                if(!empty($profit['1']) && $profit['1']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['1']['userid'],$profit['1']['profit_amount']);
                }

                if(!empty($profit['2']) && $profit['2']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['2']['userid'],$profit['2']['profit_amount']);
                }

                if(!empty($profit['14']) && $profit['14']['profit_amount']>0){
                    
                    Model::ins("AmoComAmount")->DedCounterAmount($profit['14']['profit_amount']);
                }

                if(!empty($profit['15']) && $profit['15']['profit_amount']>0){
                    
                    Model::ins("AmoComAmount")->DedCharitableAmount($profit['15']['profit_amount']);
                }

                if(!empty($profit['5']) && $profit['5']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['5']['userid'],$profit['5']['profit_amount']);
                }

                if(!empty($profit['6']) && $profit['6']['profit_amount']>0){
                    
                    Model::ins("AmoAmount")->DedCashAmount($profit['6']['userid'],$profit['6']['profit_amount']);
                }

                if(!empty($profit['13']) && $profit['13']['profit_amount']>0){
                    
                    Model::ins("AmoComAmount")->DedCashAmount($profit['13']['profit_amount']);
                }
        

                Model::ins("AmoFlowCusProfit")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowProfit")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowComProfit")->delete(["orderno"=>$v['orderno']]);

                Model::ins("AmoFlowCusBull")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowBull")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowComBull")->delete(["orderno"=>$v['orderno']]);

                Model::ins("AmoFlowCusComCash")->delete(["orderno"=>$v['orderno']]);

                Model::ins("AmoFlowComCash")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowCharitable")->delete(["orderno"=>$v['orderno']]);

                Model::ins("AmoFlowCusCash")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowCash")->delete(["orderno"=>$v['orderno']]);
                Model::ins("AmoFlowComCash")->delete(["orderno"=>$v['orderno']]);

                
                Model::ins("AmoProfit")->delete(["orderno"=>$v['orderno']]);

            }*/

            //重新分润
            foreach($list as $k=>$v){
                echo "-----".$v['pay_code']."-----";
                $flowid = Model::new("Amount.Flow")->getFlowId($v['pay_code']);
                Model::new("Amount.Profit")->profit([
                        "orderno"=>$v['pay_code'],
                        "userid"=>$v['customerid'],
                        "flowid"=>$flowid,
                    ]);
            }

            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK1111";
        exit;

    }


    public function delbullamountAction(){
        $list = Model::ins("CusCustomer")->getList("mobile in('18099135552','13999962621','18129226292','18167958777','13999104827','15292888888','15099111111')","id");

        print_r($list);

        foreach($list as $k=>$v){

            Model::ins("AmoAmount")->DedBullAmount($v['id'],'1980000');

            //Model::ins("AmoFlowCusBull")->delete(["userid"=>$v['id']]);
            //Model::ins("AmoFlowBull")->delete(["userid"=>$v['id']]);
        }

        echo "OK";
        exit;
    }


    public function delcashamountAction(){
        $list = Model::ins("RoleRecoEn")->getList("instroducerid=411 and pay_status=1","orderno");

        print_r($list);

        foreach($list as $k=>$v){

            Model::ins("AmoAmount")->DedCashAmount(411,'297000');
        }

        echo "OK";
        exit;
        //select * from role_reco_en where instroducerid=411 and pay_status=1;
    }

    /**
     * 订单取消以后返还用户金额
     * @Author   zhuangqm
     * @DateTime 2017-05-09T11:47:59+0800
     * @return   [type]                   [description]
     */
    public function returnuserpaynewAction(){

        //所有取消订单都找出来 并且找到自动取消的订单
        $list = Model::ins("OrdOrder")->getList("orderno='NNH20170503143857311764'","*");

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        //print_r($list);
        try{
            foreach($list as $k=>$v){

                //查询自动取消的订单
                //$canlorder = Model::ins("OrdOrderInfo")->getRow(["orderno"=>$v['orderno']],"id,cancelsource");

                //if($canlorder['cancelsource']==3){
                    print_r($v);
                    //生成流水号
                    $flowid = Model::new("Amount.Flow")->getFlowId($v['orderno']);
                    if($v['totalamount']>0){
                        // 查询有没流水记录
                        $row = Model::ins("AmoFlowCusCash")->getRow(["orderno"=>$v['orderno'],"flowtype"=>48],"count(*) as count");

                        if($row['count']==0){
                            echo "=====totalamount没有给钱=====<br>";
                            
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
                        }else{
                            echo "=====totalamount给钱=====<br>";
                        }
                    }


                    if($v['bullamount']>0){

                        // 查询有没流水记录
                        $row = Model::ins("AmoFlowCusBull")->getRow(["orderno"=>$v['orderno'],"flowtype"=>49],"count(*) as count");

                        if($row['count']==0){
                            echo "=====bullamount没有给钱=====<br>";

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

                        }else{
                            echo "=====bullamount给钱=====<br>";
                        }
                    }
                //}
            }

            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK";
        exit;
    }


    public function orderreturnAction(){
       

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{
           /* $list = Model::ins("AmoFlowCusCash")->getList(["flowtype"=>48],"*");
            print_r($list);
            foreach($list as $k=>$v){
               $row = Model::ins("OrdOrderPay")->getRow(["orderno"=>$v['orderno']],"count(*) as count");

               if($row['count']==0){
                    Model::ins("AmoAmount")->DedCashAmount($v['userid'],$v['amount']);

                    Model::ins('AmoFlowCusCash')->delete(["orderno"=>$v['orderno'],"flowtype"=>48]);
                    Model::ins('AmoFlowCash')->delete(["orderno"=>$v['orderno'],"flowtype"=>48]);
               }
            }
*/
            $list = Model::ins("AmoFlowFutBusCash")->getList("futstatus=0 and orderno in(select order_code from ord_order_return where orderstatus=4)","*");
            print_r($list);
            
            //退款的钱返还给用户
            foreach($list as $k=>$v){
                
                if($v['amount']>0){

                    $flowid = Model::new("Amount.Flow")->getFlowId($v['orderno']);

                    Model::new("Amount.Amount")->add_cashamount([
                                "userid"=>$v['userid'],
                                "amount" => $v['amount'],
                                "usertype"=>"2",
                                "orderno"=>$v['orderno'],
                                "flowtype"=>48,
                                "role"=>1,
                                "tablename"=>"AmoFlowCusCash",
                                "flowid"=>$flowid,
                            ]);
                }

                Model::ins("AmoFlowFutBusCash")->modify(["futstatus"=>2,"amount"=>0],["id"=>$v['id']]);  
               
            }

            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK";
        exit;
    }


    public function orderreturnnewAction(){
       

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{
           
            
            $orderno_arr = ['NNH20170506100742680370','NNH20170602110044251930','NNH20170613165927404028'];
            
            //退款的钱返还给用户
            foreach($orderno_arr as $k=>$v){
                $row = Model::ins("OrdOrderPay")->getRow("orderno='".$v."'","*");

                echo "======".$v."======<br>\n";

                $flowid = Model::new("Amount.Flow")->getFlowId($v);

                $orderitem = Model::ins("OrdOrder")->getRow(["orderno"=>$v],"customerid");

                if($row['pay_money']>0){

                    Model::new("Amount.Amount")->add_cashamount([
                                "userid"=>$orderitem['customerid'],
                                "amount" => $row['pay_money'],
                                "usertype"=>"2",
                                "orderno"=>$v,
                                "flowtype"=>48,
                                "role"=>1,
                                "tablename"=>"AmoFlowCusCash",
                                "flowid"=>$flowid,
                            ]);
                }

                if($row['pay_bull']>0){

                    Model::new("Amount.Amount")->add_bullamount([
                                "userid"=>$orderitem['customerid'],
                                "amount" => $row['pay_bull'],
                                "usertype"=>"2",
                                "orderno"=>$v,
                                "flowtype"=>49,
                                "role"=>1,
                                "tablename"=>"AmoFlowCusBull",
                                "flowid"=>$flowid,
                            ]);

                    if($v=='NNH20170506100742680370'){

                        Model::new("Amount.Amount")->add_bullamount([
                                "userid"=>$orderitem['customerid'],
                                "amount" => $row['pay_bull'],
                                "usertype"=>"2",
                                "orderno"=>$v,
                                "flowtype"=>49,
                                "role"=>1,
                                "tablename"=>"AmoFlowCusBull",
                                "flowid"=>$flowid,
                            ]);
                    }
                }

                // 分润明细清除
                if($v=='NNH20170602110044251930'){
                    Model::ins("AmoFlowFutCusCash")->delete(["flowid"=>"NNH201706021100442519307653","orderno"=>"NNH20170602110044251930"]);
                    Model::ins("AmoFlowFutComCash")->delete(["flowid"=>"NNH201706021100442519307653","orderno"=>"NNH20170602110044251930"]);
                    Model::ins("AmoFlowFutCouCash")->delete(["flowid"=>"NNH201706021100442519307653","orderno"=>"NNH20170602110044251930"]);
                    Model::ins("AmoFlowFutChaCash")->delete(["flowid"=>"NNH201706021100442519307653","orderno"=>"NNH20170602110044251930"]);
                }

                //Model::ins("AmoFlowFutBusCash")->modify(["futstatus"=>2,"amount"=>0],["id"=>$v['id']]);  
               
            }

            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK";
        exit;
    }


    public function orderreturnaaAction(){
       

        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try{

            $list = Model::ins("AmoFlowCusProfit")->getList("userid =574 and flowtype in(72,76)","*");
           
            
            foreach($list as $k=>$v){

                Model::ins("AmoAmount")->DedProfitAmount($v['userid'],$v['amount']);

                Model::ins("AmoFlowCusProfit")->delete(["urderid"=>$v["userid"],"flowtype"=>$v['flowtype'],"orderno"=>$v['orderno']]);

                Model::ins("AmoFlowProfit")->delete(["urderid"=>$v["userid"],"flowtype"=>$v['flowtype'],"orderno"=>$v['orderno']]);
            } 
           

            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            echo "ERROR";
        }
        echo "OK";
        exit;
    }


    public function rolereturnamountAction(){

        //牛人
        $row = Model::ins("CusRelation")->getList([
                "parentid"=>"-1",
                "role"=>"2",
            ],"*");
        echo "=====牛人===\n";
        foreach($row as $k=>$v){
            if($v['parentid']=='-1'){
                $r = Model::ins("AmoFlowCusCash")->getRow(["fromuserid"=>$v['customerid'],"flowtype"=>35],"count(*) as count");

                if($r['count']==0 ){
                    $tmp = Model::ins("AmoFlowCusBull")->getRow(["userid"=>$v['customerid'],"flowtype"=>"29"],"flowid,orderno,flowtime");
                    print_r($v);
                    /*Model::new("Amount.Role")->role_reco_nr([
                            "userid"=>$v['customerid'],
                            "orderno"=>$tmp['orderno'],
                            "flowid"=>$tmp['flowid'],
                            "flowtime"=>$tmp['flowtime'],
                        ]);*/

                }
            }
        }

        //牛创客
        $row = Model::ins("CusRelation")->getList([
                "parentid"=>"-1",
                "role"=>"3",
            ],"*");
        echo "=====牛创客===\n";
        foreach($row as $k=>$v){
            if($v['parentid']=='-1'){
                $r = Model::ins("AmoFlowCusCash")->getRow(["fromuserid"=>$v['customerid'],"flowtype"=>36],"count(*) as count");

                if($r['count']==0 ){
                    $tmp = Model::ins("AmoFlowCusBull")->getRow(["userid"=>$v['customerid'],"flowtype"=>"30"],"flowid,orderno,flowtime");
                    print_r($v);
                    /*Model::new("Amount.Role")->role_reco_nc([
                            "userid"=>$v['customerid'],
                            "orderno"=>$tmp['orderno'],
                            "flowid"=>$tmp['flowid'],
                            "flowtime"=>$tmp['flowtime'],
                        ]);*/

                }
            }
        }

        //牛达人
        $row = Model::ins("CusRelation")->getList([
                "parentid"=>"-1",
                "role"=>"8",
            ],"*");
        echo "=====牛达人===\n";
        foreach($row as $k=>$v){
            if($v['parentid']=='-1'){
                $r = Model::ins("AmoFlowCusCash")->getRow(["fromuserid"=>$v['customerid'],"flowtype"=>58],"count(*) as count");

                if($r['count']==0 ){
                    $tmp = Model::ins("AmoFlowCusBull")->getRow(["userid"=>$v['customerid'],"flowtype"=>"62"],"flowid,orderno,flowtime");
                    print_r($v);
                    /*Model::new("Amount.Role")->role_reco_nd([
                            "userid"=>$v['customerid'],
                            "orderno"=>$tmp['orderno'],
                            "flowid"=>$tmp['flowid'],
                            "flowtime"=>$tmp['flowtime'],
                        ]);*/

                }
            }
        }


    }

      public function bonusAction(){

        $list = Model::ins("BonusOrder")->getList([
            "addtime"=>[
                [">=","2017-08-23 15:07:54"],
                ["<","2017-08-31 00:42:23"],
            ],
        ],"*","id desc");

        $stopayflow = Model::ins("StoPayFlow");
        $comamount  = Model::ins("AmoFlowCusComCash");
        $stobusiness = Model::ins("StoBusinessBaseinfo");

        $total1=0;
        $total2=0;

        $count = 0;

        foreach($list as $k=>$v){

            $pay_flow = $stopayflow->getRow(["pay_code"=>$v['orderno'],"status"=>1],"*");

            if(!empty($pay_flow)){

                $sto = $stobusiness->getRow(["id"=>$v['businessid']],"discount");

                echo $v['orderno']."----";
                echo "[奖励金：".DePrice($v['bonusamount'])."]";
                echo "[".DePrice($pay_flow['amount'])."]";
                echo "[".DePrice($pay_flow['noinvamount'])."]";
                echo "[折扣：".$sto['discount']."]";
                //查营业收入
                $flow = $comamount->getRow(["orderno"=>$v['orderno'],"flowtype"=>17],"amount");

                echo "企业营业收入：".DePrice($flow['amount']);

                $total1+=$flow['amount'];

                //实际支付的钱
                echo "实际支付：".DePrice($pay_flow['payamount']);

                if($flow['amount']>$pay_flow['payamount'])
                    $total1 = $flow['amount']-$pay_flow['payamount'];

                //重新算折扣
                /*$discount = ($flow['amount']-$pay_flow['noinvamount'])/($pay_flow['amount']-$pay_flow['noinvamount']);
                $discount = intval($discount*100);
                echo "====折扣：".$discount."========";*/
                

                // 计算最大可用奖励金
                //$goods = [];
                
                
                // $goods['sell'] = $pay_flow['amount'];

                // $goods['cost'] = ($pay_flow['amount']-$pay_flow['noinvamount'])*(intval($stobusiness['discount'])/100)+$pay_flow['noinvamount'];
                
                //$goods['noinvamount'] = $pay_flow['noinvamount'];
                
                //$goods['bonusamount'] = 0;
                
                //$goods['type'] = 2;
                
                //$goods['discount'] = intval($stobusiness['discount'])/10;

                /*$t_amount = floatval(DePrice($pay_flow['amount']));
                $t_noinvamount = floatval(DePrice($pay_flow['noinvamount']));

                $goods = [];
                $goods['sell'] = $t_amount;
               
                $goods['cost'] = ( $t_amount-$t_noinvamount)*(intval($sto['discount'])/100)+$t_noinvamount;
               
                $goods['noinvamount'] = DePrice($pay_flow['noinvamount']);
               
                $goods['bonusamount'] = 0;
               
                $goods['type'] =2;
                
                $goods['discount'] = intval($sto['discount'])/10;
                
                $result = Cash_abstract::getMaxBonusReturn($goods);
                $amount = $result['cashTotal'];



                echo "----".$amount."---";

                if(DePrice($v['bonusamount'])>$amount){
                    echo "====".DePrice($v['bonusamount'])."-".$amount."=".(DePrice($v['bonusamount'])-$amount)."=======";
                    $total1+=DePrice($v['bonusamount'])-$amount;
                }

                if($sto['discount']!=$discount)
                    echo "!!!!!!!!!!!!!!!!!!!!!!";*/

                //重新计算

                /*$shouru = EnPrice(DePrice(($pay_flow['amount']-$v['bonusamount'])-$pay_flow['noinvamount'])*($sto['discount']/100))+$pay_flow['noinvamount'];

                $total2+=$shouru;

                echo "--重新计算：".DePrice($shouru);

                $temp_shouru = EnPrice(DePrice($pay_flow['amount']-$pay_flow['noinvamount'])*($sto['discount']/100))+$pay_flow['noinvamount'];

                echo "---".DePrice($temp_shouru)."---";

                if($flow['amount']!=$temp_shouru)
                    echo "@@@@@@@@@@@@@";

                if($flow['amount']>$temp_shouru)
                    echo "!!!!!!!!!!!!!!!";*/

                echo "<br>------------<br>";
            }
        }


        echo "<br>";
        echo "total1:".$total1;
        echo "--------";
        exit;
        /*echo "total2:".$total2;
        echo "最终差：".($total1-$total2)/100;*/
    }


    public function stoflowAction(){

        $list = Model::ins("StoPayFlow")->getList(["status"=>1],"*","id desc");

        $stopayflow = Model::ins("StoPayFlow");
        $comamount  = Model::ins("AmoFlowCusComCash");
        $stobusiness = Model::ins("StoBusinessBaseinfo");

        $total1=0;
        $total2=0;

        $count = 0;

        $business = [];

        foreach($list as $k=>$v){

            //$pay_flow = $stopayflow->getRow(["pay_code"=>$v['orderno'],"status"=>1],"*");

            //if(!empty($pay_flow)){

                $sto = $stobusiness->getRow(["id"=>$v['businessid']],"discount");

                echo $v['pay_code']."----";
                echo "[".$v['addtime']."]";
                echo "[".DePrice($v['amount'])."]";
                echo "[".DePrice($v['noinvamount'])."]";
                echo "[折扣：".$sto['discount']."]";
                //查营业收入
                $flow = $comamount->getRow(["orderno"=>$v['pay_code'],"flowtype"=>17],"amount");

                echo "企业营业收入：".DePrice($flow['amount']);

                //重新计算
                $temp_shouru = EnPrice(DePrice($v['amount']-$v['noinvamount'])*($sto['discount']/100))+$v['noinvamount'];

                echo "---".DePrice($temp_shouru)."---";

                if($flow['amount']!=$temp_shouru)
                    echo "@@@@@@@@@@@@@";

                if($flow['amount']>$temp_shouru){
                    echo "!!!!!!!!!!!!!!!";
                    $total1++;
                    $total2+=$flow['amount']-$temp_shouru;
                }

                $business[$v['businessid']]=1;

                echo "<br>------------<br>";
            //}
        }


        echo "<br>";
        echo "记录数：".$total1;
        echo "<br>";
        echo "记录数：".DePrice($total2);
        echo "<br>";
        echo "商家数".count($business);
        echo "<br>";
        print_r($business);
    }


    public function shouruAction(){

        $list = Model::ins("AmoFlowCusComCash")->getList([
            "flowtime"=>[
                [">=","2017-08-30 19:00:00"],
                ["<","2017-08-31 01:00:00"],
            ],
            "flowtype"=>17,
        ],"*","id desc");

        $stopayflow = Model::ins("StoPayFlow");
        $comamount  = Model::ins("AmoFlowCusComCash");
        $stobusiness = Model::ins("StoBusinessBaseinfo");
        $stobusinessobj = Model::ins("StoBusiness");

        $total1=0;
        $total2=0;

        $count = 0;

        foreach($list as $k=>$v){

            $pay_flow = $stopayflow->getRow(["pay_code"=>$v['orderno'],"status"=>1],"*");

            if(!empty($pay_flow)){

                $sto = $stobusiness->getRow(["id"=>$pay_flow['businessid']],"discount");

                $row = $stobusinessobj->getRow(["id"=>$pay_flow['businessid']],"businessname");

                echo "[".$row['businessname']."]----";

                echo $v['addtime']."----";

                echo $v['orderno']."----";

                echo "企业营业收入：".DePrice($v['amount']);

                //重新计算营业收入
                $shouru = DePrice($pay_flow['amount']-$pay_flow['noinvamount'])*($sto['discount']/100)+DePrice($pay_flow['noinvamount']);

                echo "====重新计算：".$shouru."=====";

                $v['amount'] = DePrice($v['amount']);

                if($v['amount']<$shouru){
                    echo "差：".($shouru-$v['amount']);
                    $total1+=($shouru-$v['amount']);
                }

                //echo "[奖励金：".DePrice($v['bonusamount'])."]";
                echo "[".DePrice($pay_flow['amount'])."]";
                echo "[".DePrice($pay_flow['noinvamount'])."]";
                echo "[折扣：".$sto['discount']."]";
                /*
                //查营业收入
                $flow = $comamount->getRow(["orderno"=>$v['orderno'],"flowtype"=>17],"amount");*/

                

                /*$total1+=$flow['amount'];

                //实际支付的钱
                echo "实际支付：".DePrice($pay_flow['payamount']);

                if($flow['amount']>$pay_flow['payamount'])
                    $total1 = $flow['amount']-$pay_flow['payamount'];
*/
                

                echo "<br>------------<br>";
            }
        }


        echo "<br>";
        echo "total1:".$total1;
        echo "--------";
        exit;
        /*echo "total2:".$total2;
        echo "最终差：".($total1-$total2)/100;*/
    }
}