<?php
// +----------------------------------------------------------------------
// |  [ 订单管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-03-18
// +----------------------------------------------------------------------
namespace app\admin\controller\Order;

use app\lib\Model;
use app\admin\ActionController;
use app\form\OrdReturnLog\OrdReturnLogAdd;
use app\model\OrdOrderItemModel;
use app\lib\Log;
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
        $where['business_id'] = $this->businessid;
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
        $where['business_id'] = $this->businessid;
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
        $where['business_id'] = $this->businessid;
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
        $where['business_id'] = $this->businessid;
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
        $where['business_id'] = $this->businessid;
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
        $where['business_id'] = $order_where['businessid'] = $this->businessid;
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
        $order = Model::ins("OrdOrder")->getRow(array("orderno" => $returnInfo['order_code'], "businessid" => $this->businessid));
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
                        "customerid" => $returnInfo['customerid'], "businessid" => $this->businessid, "orderstatus" => $logStatus, "content" => $logContent, "remark" => $this->getParam("reason"), "addtime" => getFormatNow());
        
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
                    } else{
                        Model::new("Business.Settlement")->returnfutpay(array("returnno"=>$returnInfo['returnno']));
                    }
                    //把钱退还给用户
                    //生成流水号
                    $flowid = Model::new("Amount.Flow")->getFlowId($returnInfo['order_code']);
                    
                    //运费也退还给用户
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
                $this->showSuccessPage("操作成功", "/Order/Refund/success");
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
        $where['business_id'] = $order_where['orderno'] = $this->businessid;
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
        $order = Model::ins("OrdOrder")->getRow(array("orderno" => $returnInfo['order_code'], "businessid" => $this->businessid));
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
                "customerid" => $returnInfo['customerid'], "businessid" => $this->businessid, "orderstatus" => 4, "content" => $logContent, "addtime" => getFormatNow());
            
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
}