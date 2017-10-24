<?php
namespace app\model\Order;

use app\model\OrdOrderModel;
use app\model\OrdOrderItemModel;
use app\model\ProProductSpecModel;
use app\model\ProProductModel;
use app\model\OrdOrderReturnModel;
use app\model\CusCustomerInfoModel;
use app\model\CusCustomerModel;
use app\model\OrdOrderInfoModel;
use app\model\Sys\CommonModel;
use app\model\BusBusinessInfoModel;
use app\model\OrdReturnLogModel;
use app\lib\Img;
use app\lib\Model;
use \think\Config;
use app\model\OrdUserCountModel;
use app\model\Business\BusinessModel;


class RefundModel {
    // 初始化值
    const initNumber = 0;
    // 退款失败
    const salePriceApplyFail = 3;
    // 退货失败
    const saleGoodsApplyFail = 13;
    // 退款等待审核
    const salePriceExamStatus = 1;
    // 退货等待审核
    const saleGoodsExamStatus = 11;
    
    // 退款成功
    const salePriceApplySuccess = 2;
    // 退货成功
    const saleGoodsApplySuccess = 12;
    
    const salePriceSuccess = 4;
    
    const saleGoodsSuccess = 14;
    
    const cancleExamStatus = 20;
    
    const saleRefundSuccess =32;
    // 非退款退货状态
    const initReturnStatus = 0;
    // 退款(退款中)状态
    const returnIng = 1;
    // 退款/退货成功状态
    const returnSuccess = 2;
    // 取消来源
    const cancleSourceCus = 1;
    // 取消订单
    const cancleOrder = 5;
    // 退货类型
    const price_type = 1;
    // 退款累心
    const goods_type = 2;
    
    // 用户操作者
    const cusActionSource = 1;
    // 商家操作者
    const busActionSource = 2;
    
    // 日志表 审核状态
    const logExamStatus = 1;
    
    const enableSuccess = 1;
    const enableFail = -1;
    /**
    * @user 返回商品信息
    * @param $params 商品信息
    * @author jeeluo
    * @date 2017年3月7日下午5:01:53
    */
    public function productInfo($params) {
        $customerid = $params['customerid'];
        $orderno = $params['orderno'];
        $productid = $params['productid'];
        $skuid = $params['skuid'];
        if(!empty($orderno)) {
            $orderOBJ = new OrdOrderModel();
            
            $order = $orderOBJ->getInfoByOrderNo($orderno);
            
            // 订单是否存在
            if(!empty($order)) {
                // 下单者和操作者是否一致
                if($order['customerid'] == $customerid) {
                    $productdetail = array();
                    // 有商品规格时
                    if(!empty($skuid)) {
                        $specOBJ = new ProProductSpecModel();
                        $goods = $specOBJ->setId($skuid)->getById($skuid, "productname, prouctprice, bullamount, spec, productimage");
                        $productdetail = $goods;
                    } else {
                        // 无商品规格时
                        $productOBJ = new ProProductModel();
                        $goods = $productOBJ->setId($productid)->getById($productid, "productname, prouctprice, bullamount, thumb");
                        $goods['spec'] = '';
                        $productdetail = $goods;
                    }
                    // 
                    $itemOBJ = new OrdOrderItemModel();
                    $item = $itemOBJ->getRow(array("orderno" => $orderno, "productid" => $productid, "skuid" => $skuid), "prouctprice, bullamount, productnum");
                    
                    $productdetail['productnum'] = $item['productnum'];
                    $productdetail['oneprice'] = DePrice($productdetail['prouctprice'] / $productdetail['productnum']);
                    $productdetail['onebull'] = DePrice($productdetail['bullamount'] / $productdetail['productnum']);
                    $productdetail['businessid'] = $order['businessid'];
                    $productdetail['businessname'] = $order['businessname'];
                    return ["code" => "200", "productdetail" => $productdetail];
                }
                // 操作者不一致
                return ["code" => "1001", "productdetail" => []];
            }
            // 订单不存在
            return ["code" => "1000", "productdetail" => []];
        }
        // 参数有误
        return ["code" => "404", "productdetail" => []];
    }
    
    /**
    * @user 申请信息处理
    * @param 
    * @author jeeluo
    * @date 2017年3月8日上午9:41:03
    */
    public function saleApplyInfo($params) {
        $customerid = $params['customerid'];
        $orderno = $params['orderno'];
        $productid = $params['productid'];
        
        if(!empty($orderno)) {
            $orderOBJ = new OrdOrderModel();
            
            $order = $orderOBJ->getInfoByOrderNo($orderno);
            // 订单是否存在
            if(!empty($order)) {
                // 下单者和操作者是否一致
                if($order['customerid'] == $customerid) {
                    // 查看该item是否存在
                    $orderItemOBJ = new OrdOrderItemModel();
                    $item_status = $orderItemOBJ->getIsOrderItem($params);
                    
                    if(!$item_status) {
                        return ["code" => "1000"];
                    }
                    // 获取最后一条退货订单信息
                    $lastReturn = $this->getLastReturnStatus($params);
                    
                    if(!empty($lastReturn)) {
//                         if($params['return_type'] == self::price_type) {
//                             if($lastReturn['orderstatus'] == self::salePriceExamStatus) {
//                                 return ["code" => "11001"];
//                             }
//                         } else if($params['return_type'] == self::goods_type) {
//                             if($lastReturn['orderstatus'] == self::saleGoodsExamStatus) {
//                                 return ["code" => "11001"];
//                             }
//                         }
                        if($lastReturn['orderstatus'] == self::salePriceExamStatus || $lastReturn['orderstatus'] == self::saleGoodsExamStatus) {
                            return ["code" => "11001"];
                        }
                    }
                    $end = date('Y-m-d H:i:s', strtotime("+3 day"));
                    
                    // 获取退货订单信息
                    $count = $this->getCountReturn($params);
                    
                    // 获取当前用户的基础信息
                    $cus = new CusCustomerModel();
                    $user = $cus->setId($params['customerid'])->getById($params['customerid']);
                    
                    // 获取当前用户昵称
                    $cusInfoOBJ = new CusCustomerInfoModel();
                    $simpleInfo = $cusInfoOBJ->setId($params['customerid'])->getSimpleById($params['customerid']);
                    
                    // 获取订单内某个项目商品的金额
                    
                    $item = $orderItemOBJ->getRow(array("orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']), "prouctprice, bullamount, productnum");
                    
                    // 判断用户是否传递个数(没有 取全部)
                    $refundCount = !empty($params['return_count']) ? $params['return_count'] : $item['productnum'];
                    $refundPrice = $item['prouctprice'] * $refundCount;
                    $refundbull = $item['bullamount'] * $refundCount;
                    
                    if($refundCount > $item['productnum']) {
                        return ["code" => 404];
                    }
                    
                    $data = array("return_type" => $params['return_type'], "business_id" => $order['businessid'], "business_name" => $order['businessname'], "orderid" => $order['id'],
                        "order_code" => $params['orderno'],"returnno" => CommonModel::getReturnNo($params['orderno'], $count), "starttime" => getFormatNow(), "actiontime" => getFormatNow(), "endtime" => $end, "returnreason" => $params['reason'],
                        "remark" => $params['remark'], "images" => $params['images'], "customerid" => $params['customerid'], "mobile" => $user['mobile'],
                        "returnamount" => $refundPrice, "returnbull" => $refundbull, "productid" => $params['productid'], "customer_name" => $simpleInfo["nickname"], "skuid" => $params['skuid'], "productnum" => $refundCount);
                    
                    $data['orderstatus'] = self::initNumber;
                    $typeText = '';
                    if($params['return_type'] == self::price_type) {
                        $data['orderstatus'] = self::salePriceExamStatus;
                        
                        // 计算退单运费
                        $freight = Model::new("Order.Order")->getReturnFreight(array("orderno"=>$params['orderno'],"productid"=>$params['productid'],"skuid"=>$params['skuid']));
                        $data['freight'] = $freight['freight'];
                        $typeText = "退款";
                    } else if($params['return_type'] == self::goods_type) {
                        $data['orderstatus'] = self::saleGoodsExamStatus;
                        $typeText = "退款/退货";
                    }
                    
                    $content = "买家(".$simpleInfo['nickname'].")于".getFormatNow()."创建了退款申请";
                    
                    $ordReturnOBJ = new OrdOrderReturnModel();
                    $msgType = '';
                    // 假如是修改退单  修改退单数据
                    if($lastReturn['orderstatus'] == self::salePriceApplyFail || $lastReturn['orderstatus'] == self::saleGoodsApplyFail) {
                        // 写入修改退单提醒
                        $msgType = 2;
                        $data['returnno'] = $lastReturn['returnno'];
                        $ordReturnOBJ->modify($data, array("id" => $lastReturn['id']));
                    } else {
                        // 写入退单提醒
                        $msgType = 1;
                        $ordReturnOBJ->add($data);
                    }
                    
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.applyReturn",
                        "url"=>"Order.OrderMsg.applyReturn",
                        "param"=>[
                            "returnno"=>$data['returnno'],
                            "type"=>$msgType,
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                    
                    // 写入日志表
                    $returnLogOBJ = new OrdReturnLogModel();
                    $logData = array("returnno" => CommonModel::getReturnNo($params['orderno'], $count), "orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid'], "actionsource" => self::cusActionSource,
                                    "customerid" => $params['customerid'], "businessid" => $order['businessid'], "orderstatus" => self::logExamStatus, "content" => $content, "remark" => $params['remark'], "addtime" => getFormatNow());
                    $returnLogOBJ->add($logData);
//                     if($status) {
                    return ["code" => 200];
//                     }
//                     return ["code" => 400];
                }
                return ["code" => 1001];
            }
            return ["code" => 1000];
        }
        return ["code" => 404];
    }
    
    /**
    * @user 获取个数
    * @param 
    * @author jeeluo
    * @date 2017年3月7日下午8:27:15
    */
    public function getCountReturn($params) {
        
        $orderno = $params['orderno'];
        $customerid = $params['customerid'];
        
        $orderReturnOBJ = new OrdOrderReturnModel();
        $returnList = $orderReturnOBJ->getOrderReturnList(array("order_code" => $orderno, "customerid" => $customerid), "id");
        
        $count = !empty($returnList) ? count($returnList) : self::initNumber;
        return $count;
    }
    
    /**
    * @user 用户该订单
    * @param 
    * @author jeeluo
    * @date 2017年3月8日上午10:02:06
    */
    public function getLastReturnStatus($params) {
        $orderno = $params['orderno'];
        $customerid = $params['customerid'];
        $skuid = $params['skuid'];
        
        $orderReturnOBJ = new OrdOrderReturnModel();
        $return = $orderReturnOBJ->getRow(array("order_code" => $orderno, "customerid" => $customerid, "productid" => $params['productid'], "skuid" => $skuid), "*", "id desc");
        return $return;
    }
    
    /**
    * @user 确认申请
    * @param 
    * @author jeeluo
    * @date 2017年3月8日下午2:02:10
    */
    public function returnPrice($params) {
        // 订单信息表
        $orderInfoOBJ = new OrdOrderInfoModel();
        
        $orderInfo = $orderInfoOBJ->getInfoRow(array("orderno" => $params['orderno'], "customerid" => $params['customerid']), "return_amount, return_bull, return_time, return_success_time");
        $infoReturn = array("return_status" => self::returnIng, "return_amount" => $orderInfo['return_amount']+$params['productamount'], 
                            "return_bull" => $orderInfo['return_bull']+$params['bullamount'], "return_time" => getFormatNow());
        
        $infostatus = $orderInfoOBJ->modify($infoReturn, array("orderno" => $params['orderno'], "customerid" => $params['customerid']));
        
        // 订单明细表
        $orderItemOBJ = new OrdOrderItemModel();
                
        $itemReturn = array("return_type" => $params['return_type'], "return_money" => $params['productamount'], "intended_money" => $params['productamount'],
                            "return_bull" => $params['bullamount'], "intended_bull" => $params['bullamount']);
        $itemstatus = $orderItemOBJ->modify($itemReturn, array("orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']));
        
        return true;
    }
    
    /**
     * @user 确认退款 取消订单
     * @param
     * @author jeeluo
     * @date 2017年3月8日下午2:02:10
     */
    public function confirmRefundPrice($params) {
        // 查询该订单的明细
        $orderItemOBJ = new OrdOrderItemModel();
        // 看这订单下是否还有剩余的商品
//         $otherItem = $orderItemOBJ->getRow(array("orderno" => $params['orderno'], "return_type" => self::initReturnStatus));
        $otherItem = $orderItemOBJ->getRow(array("orderno" => $params['orderno'], "enable" => self::enableSuccess));
        
        $infoReturn = array("return_status" => self::returnSuccess, "return_success_time" => getFormatNow());
        $returnOBJ = new OrdOrderReturnModel();
        // 查询申请信息
        $returnApplyInfo = $returnOBJ->getRow(array("order_code" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']), "returnreason, remark, id", "id desc");
        if(empty($otherItem)) {
            $infoReturn = array("return_status" => self::returnSuccess, "return_success_time" => getFormatNow(), "cancelsource" => self::cancleSourceCus, 
                                "cancelreason" => $returnApplyInfo['returnreason'], "audit_remark" => $returnApplyInfo['remark'], "finish_time" => getFormatNow());
            
            // 取消订单
//             $orderOBJ = new OrdOrderModel();
//             $orderOBJ->modify(array("orderstatus" => self::cancleOrder), array("orderno" => $params['orderno']));
        }
        
        // 订单信息表
        $orderInfoOBJ = new OrdOrderInfoModel();
        $infostatus = $orderInfoOBJ->modify($infoReturn, array("orderno" => $params['orderno']));
        // 查询退货个数
        $returnInfo = $returnOBJ->getRow(array("order_code" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']), "productnum");
        $selfItem = $orderItemOBJ->getRow(array("orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']));
        
        $item_arr = array("productnum" => $selfItem['productnum'] - $returnInfo['productnum']);
        $orderItemOBJ->modify($item_arr, array("orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']));

        $orderstatus = 0;
        if($params['return_type'] == self::price_type) {
            $orderstatus = self::salePriceSuccess;
        } else {
            $orderstatus = self::saleGoodsSuccess;
        }
        $return_arr = array("orderstatus" => $orderstatus, "actiontime" => getFormatNow());
        $returnOBJ->modify($return_arr, array("id" => $returnApplyInfo['id']));
        return true;
    }
    
    /**
    * @user 返回退货类型
    * @param 
    * @author jeeluo
    * @date 2017年3月8日下午3:58:40
    */
    public function getReturnType($params) {
        $returnOBJ = new OrdOrderReturnModel();
        $result = $returnOBJ->getRow(array("order_code" => $params['orderno'], "customerid" => $params['customerid'], "productid" => $params['productid'], "skuid" => $params['skuid']), "return_type", "id desc");
        return $result;
    }
    
    /**
    * @user 退货/款 详情页面文字内容
    * @author jeeluo
    * @date 2017年3月8日下午5:47:08
    */
    public function getApplyContent($string) {
        
        $result[0] = $string;
        $result[1] = "商家同意或者超时未处理，系统将退款给您";
        $result[2] = "如果商家拒绝，您可以修改退款申请后再次发起，商家会重新处理";
        
        return $result;
    }
    /**
    * @user 退货/款 详情页面状态内容
    * @param 
    * @author jeeluo
    * @date 2017年3月10日下午4:58:09
    */
    public function getApplyStatusStr($endtime) {
        $diff = CommonModel::timediff(getFormatNow(), $endtime);
        $result = "剩".$diff['days']."天".$diff['hours']."小时自动同意退款";
        return $result;
    }
    
    /**
    * @user 商家拒绝申请时 文字
    * @param 
    * @author jeeluo
    * @date 2017年3月9日上午10:55:09
    */
    public function getBusinessRefuseContent($endtime) {
        $diff = CommonModel::timediff(getFormatNow(), $endtime);
        $result = "剩".$diff['days']."天".$diff['hours']."小时，如果您逾期未修改，本次退款申请自动撤销";
        return $result;
    }

    public function getBusinessPassContent() {
        $result[0] = "商家已同意您的退货/退款申请，请尽快发货";
        $result[1] = "商家确认收货，系统将退款给您";
        return $result;
    }
    
    public function getNoExpressStatusStr($endtime) {
        $diff = CommonModel::timediff(getFormatNow(), $endtime);
        $result = '剩'.$diff['days']."天".$diff['hours']."小时自动取消退款";
        return $result;
    }
    
    public function getNoExpressContent($string) {
        $result[0] = $string;
        $result[1] = "72小时内未填写物流单号，系统将取消退款";
        return $result;
    }
    
    public function getExpressContent($string) {
        $result[0] = $string;
        $result[1] = "商家确认收货，系统将退款给您";
        $result[2] = "如果商家超时未确认收货，系统会在15天后将自动退款给您";
        return $result;
    }
    
    /**
    * @user 获取退款信息
    * @param 
    * @author jeeluo
    * @date 2017年3月9日下午3:40:08
    */
    public function getRefundInfo($params) {
        $returnInfo = $this->getLastReturnStatus($params);

        $productdetail = array();
        // 有商品规格时
        if(!empty($params['skuid'])) {
            $specOBJ = new ProProductSpecModel();
            $goods = $specOBJ->setId($params['skuid'])->getById($params['skuid'], "productname, prouctprice, bullamount, spec, productimage");
            
            $decodespec = json_decode($goods['spec']);
            $goods['skudetail'] = '';
            foreach ($decodespec as $v) {
                if($goods['skudetail'] != '') {
                    $goods['skudetail'] .= ";";
                }
                $goods['skudetail'] .= $v->name.":".$v->value;
            }
            $goods['thumb'] = $goods['productimage'];
            $productdetail = $goods;
        } else {
            // 无商品规格时
            $productOBJ = new ProProductModel();
            $goods = $productOBJ->setId($params['productid'])->getById($params['productid'], "productname, prouctprice, bullamount, thumb");
            $goods['skudetail'] = '';
            $goods['productimage'] = $goods['thumb'];
            $productdetail = $goods;
        }
        $orderact = array();
        $orderstatusstr = array();
        
        $orderact[0]['act'] = 1;
        $orderact[0]['acttype'] = 12;
        if($returnInfo['return_type'] == self::price_type) {
            $orderact[0]['actname'] = '取消退款';
        } else if($returnInfo['return_type'] == self::goods_type) {
            $orderact[0]['actname'] = '取消退货退款';
        }
        if($params['version'] >= "2.1.0") {
            $isreturnexpress = -1;
        }
        
        $type_string = ($returnInfo['return_type'] == self::price_type ? "退款" : "退货退款");
        $refundstatus = 0;
        // 退款审核
        if($returnInfo['orderstatus'] == self::salePriceExamStatus || $returnInfo['orderstatus'] == self::saleGoodsExamStatus) {
            
            $orderreturnOBJ = new OrdOrderReturnModel();
            $countReturn = count($orderreturnOBJ->getOrderReturnObj($params));
            if($count > self::initNumber) {
                $count_string = "修改";
                $refundstatus = 1;
            } else {
                $count_string = '成功发起';
            }
            // 审核内容
            $orderstatusstr['statusstr'] = "等待商家审核";
            $orderstatusstr['statusinfo'] = $this->getApplyStatusStr($returnInfo['endtime']);
            $string = "您已".$count_string.$type_string."申请，请耐心等待商家处理";
            $orderstatusstr['content'] = $this->getApplyContent($string);
        } else if($returnInfo['orderstatus'] == self::salePriceApplyFail || $returnInfo['orderstatus'] == self::saleGoodsApplyFail) {
            $refundstatus = 2;
            // 拒绝页面内容
            $orderstatusstr['statusstr'] = "卖家已拒绝";
            $orderstatusstr['statusinfo'] = $this->getBusinessRefuseContent($returnInfo['endtime']);
            $string = "卖家已拒绝您的".$type_string."申请，请及时处理";
            $orderstatusstr['content'] = $this->getApplyContent($string);
            
            $orderact[0]['act'] = 1;
            $orderact[0]['acttype'] = 13;
            $orderact[0]['actname'] = '修改申请';
            
            $orderact[1]['act'] = 1;
            $orderact[1]['acttype'] = 14;
            $orderact[1]['actname'] = '撤销申请';
        } else if($returnInfo['orderstatus'] == self::salePriceApplySuccess || $returnInfo['orderstatus'] == self::saleGoodsApplySuccess) {
            // 成功
            if($params['version'] >= "2.1.0") {
                $orderstatusstr['statusstr'] = $returnInfo['return_type'] == self::price_type ? '退款成功' : '商家审核通过,请填写物流单号';
                
                if($returnInfo['orderstatus'] == self::saleGoodsApplySuccess) {
                    if(!empty($returnInfo['expressname']) && !empty($returnInfo['expressnumber'])) {
                        $orderstatusstr['statusstr'] = '等待商家确认收货';
                        $orderstatusstr['statusinfo'] = '';
                        $string = '您已成功申请了退货退款';
                        $orderstatusstr['content'] = $this->getExpressContent($string);
                        $isreturnexpress = 1;
                    } else {
                        
                        $orderstatusstr['statusstr'] = '商家审核通过，请填写物流单号';
                        $orderstatusstr['statusinfo'] = $this->getNoExpressStatusStr(date('Y-m-d H:i:s', strtotime($returnInfo['starttime'])+86400*3));
                        $string = '商家已同意您的退货/退款申请，请填写物流单号';
                        $orderstatusstr['content'] = $this->getNoExpressContent($string);
                        
                        $isreturnexpress = 0;
                        $orderact[1]['act'] = 1;
                        $orderact[1]['acttype'] = 19;
                        $orderact[1]['actname'] = '提交物流';
                    }
                }
            } else {
                $orderstatusstr['statusstr'] = $returnInfo['return_type'] == self::price_type ? '退款成功' : '商家审核通过';
                $orderstatusstr['statusinfo'] = '';
                // $orderstatusstr['content'] = array();
                $orderstatusstr['content'] = $this->getBusinessPassContent();
            }
        } else if($returnInfo['orderstatus'] == self::salePriceSuccess || $returnInfo['orderstatus'] == self::saleGoodsSuccess) {
            // 成功
            $orderstatusstr['statusstr'] = ($returnInfo['return_type'] == self::price_type ? '退款' : '退款退货')."成功";
            $orderstatusstr['statusinfo'] = '';
            $orderstatusstr['content'] = array();
        } else if($returnInfo['orderstatus'] == self::cancleExamStatus) {
            // 关闭
            $orderstatusstr['statusstr'] = ($returnInfo['return_type'] == self::price_type ? '退款' : '退款退货')."关闭";
            $orderstatusstr['statusinfo'] = '';
            $orderstatusstr['content'] = array();
        }
        
        // 根据订单编号 获取订单之前状态
        $ordOBJ = new OrdOrderModel();
        $ordInfo = $ordOBJ->getRow(array("orderno" => $returnInfo['order_code']), "orderstatus");
        
        $result['return_type'] = $returnInfo['return_type'];
        $result['refundstatus'] = $refundstatus;
        $result['returnno'] = $returnInfo['returnno'];
        $result['orderstatus'] = $ordInfo['orderstatus'];
        $result['starttime'] = $returnInfo['starttime'];
        $result['returnreason'] = $returnInfo['returnreason'];
//         $result['returnamount'] = DePrice($returnInfo['returnamount']);
//         $result['returnbull'] = DePrice($returnInfo['returnbull']);
        $result['returnamount'] = DePrice($returnInfo['returnamount'] / $returnInfo['productnum']);
        $result['returnbull'] = DePrice($returnInfo['returnbull'] / $returnInfo['productnum']);
        $result['freight'] = !empty($returnInfo['freight']) ? DePrice($returnInfo['freight']) : '0.00';
        $phone = $this->getBusinessPhone($returnInfo['business_id']);
        $phone_mobile = explode(",", $phone['mobile']);
        $phone_servicetel = explode(",", $phone['servicetel']);
        
        $result['phone'] = array_merge($phone_servicetel, $phone_mobile);
        $result['company_phone'] = CommonModel::getCompanyPhone();
        $result['orderact'] = $orderact;
        $result['orderstatusstr'] = $orderstatusstr;
        
        $result['orderitem']['productname'] = $productdetail['productname'];
        $result['orderitem']['skudetail'] = $productdetail['skudetail'];
        
        $thumb_arr = array_filter(explode(",", $productdetail['thumb']));
        $result['orderitem']['thumb'] = Img::url($thumb_arr[0]);
//         $result['orderitem']['thumb'] = Img::url($productdetail['thumb']);
        $result['orderitem']['productnum'] = $returnInfo['productnum'];

        if($params['version'] >= "2.1.0") {
            $result['expresslist'] = array();
            $result['receipt_address'] = array("express_name"=>"", "express_no"=>"");
            $result['returnid'] = '';
            
            $result['isreturnexpress'] = $isreturnexpress;
            if($isreturnexpress == 0) {
                // 返回快递公司
                $expresslist = Config::get("express_name");
                $result['expresslist'] = array();
                foreach ($expresslist as $express) {
                    array_push($result['expresslist'],$express);
                }
                array_push($result['expresslist'],'其他');
                
                $result['returnid'] = $returnInfo['id'];
            } else if($isreturnexpress == 1) {
                $result['receipt_address']['express_name'] = $returnInfo['expressname'];
                $result['receipt_address']['express_no'] = $returnInfo['expressnumber'];
            }
        }
        return $result;
    }
    
    /**
     * @user 获取退款信息
     * @param
     * @author jeeluo
     * @date 2017年3月9日下午3:40:08
     */
    public function getOtherRefundInfo($params) {
//         $returnInfo = $this->getLastReturnStatus($params);
        $ordReturnOBJ = new OrdOrderReturnModel();
        if(!empty($params['returnid'])) {
            $returnInfo = $ordReturnOBJ->getRow(array("id" => $params['returnid']), "*");
        } else {
            $returnInfo = $this->getLastReturnStatus($params);
        }
        $orderact = array();
        $orderstatusstr = array();
        
        if($returnInfo['orderstatus'] == self::salePriceSuccess || $returnInfo['orderstatus'] == self::saleGoodsSuccess) {
            $orderstatusstr['statusstr'] = ($returnInfo['return_type'] == 1 ? '退款' : '退款退货')."成功";
            $orderstatusstr['statusinfo'] = '';
            $orderstatusstr['content'] = array();
        } else if($returnInfo['orderstatus'] == self::cancleExamStatus) {
            // 关闭
            $orderstatusstr['statusstr'] = ($returnInfo['return_type'] == 1 ? '退款' : '退款退货')."关闭";
            $orderstatusstr['statusinfo'] = '';
            $orderstatusstr['content'] = array();
        } else {
            $orderstatusstr['statusstr'] = '';
            $orderstatusstr['statusinfo'] = '';
            $orderstatusstr['content'] = array();
        }
        
       
        $orderitem['business_name'] = $returnInfo['business_name'];
        // 获取供应商电话号码
        $orderitem['business_tel'] = BusinessModel::getBusinessTel($returnInfo['business_id']);
        $orderitem['returnType'] = ($returnInfo['return_type'] == 1 ? '退款' : '退款退货');
        $orderitem['type'] = $returnInfo['return_type'];
        $orderitem['productnum'] = $returnInfo['productnum'];
        $orderitem['returnreason'] = $returnInfo['returnreason'];
        $orderitem['returnamount'] = DePrice($returnInfo['returnamount']);
        $orderitem['returnbull'] = DePrice($returnInfo['returnbull']);
        $orderitem['remark'] = $returnInfo['remark'];
        $orderitem['returnno'] = $returnInfo['returnno'];
        $orderitem['starttime'] = $returnInfo['starttime'];
        $orderitem['endtime'] = $returnInfo['endtime'];
    
        $orderact[0]['act'] = '';
        $orderact[0]['acttype'] = '';
        $orderact[0]['actname'] = '';
        $result['orderact'] = $orderact;
        $result['orderstatusstr'] = $orderstatusstr;
        $result['orderitem'] = $orderitem;
        return $result;
    }
    
    /**
    * @user 根据商家id 获取商家电话联系方式
    * @param $id 商家id值
    * @author jeeluo
    * @date 2017年3月9日上午9:48:43
    */
    public function getBusinessPhone($id) {
        $busInfoOBJ = new BusBusinessInfoModel();
        return $busInfoOBJ->getRow(array("id" => $id), "mobile, servicetel");
    }
    
    public function writeBusReturnLog($params) {
        $orderOBJ = new OrdOrderModel();
        
        $order = $orderOBJ->getInfoByOrderNo($params['orderno']);
        // 获取退货订单信息
        $count = $this->getCountReturn($params);
        
        $logData = array("returnno" => CommonModel::getReturnNo($params['orderno'], $count), "orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid'], "actionsource" => self::busActionSource,
            "customerid" => $params['customerid'], "businessid" => $order['businessid'], "orderstatus" => $params['logStatus'], "content" => $params['content'], "remark" => $params['remark'], "addtime" => getFormatNow());
        $returnLogOBJ = new OrdReturnLogModel();
        $returnLogOBJ->add($logData);
        return true;
    }
    
    public function writeCusReturnLog($params) {
        $orderOBJ = new OrdOrderModel();
    
        $order = $orderOBJ->getInfoByOrderNo($params['orderno']);
        // 获取退货订单信息
        $count = $this->getCountReturn($params);
        $logData = array("returnno" => CommonModel::getReturnNo($params['orderno'], $count), "orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid'], "actionsource" => self::cusActionSource,
            "customerid" => $params['customerid'], "businessid" => $order['businessid'], "orderstatus" => $params['logStatus'], "content" => $params["content"], "remark" => $params['remark'], "addtime" => getFormatNow());
        $returnLogOBJ = new OrdReturnLogModel();
        $returnLogOBJ->add($logData);
        return true;
    }
    
    /**
    * @user 获取退款分页列表
    * @param 
    * @author jeeluo
    * @date 2017年3月9日下午5:48:55
    */
    public function getReturnPageList($params) {
        $ordReturnOBJ = new OrdOrderReturnModel();
        $returnresult = $ordReturnOBJ->getReturnPageList(array("customerid" => $params['customerid']), "id, order_code, skuid, productid, returnamount, returnbull, productnum, return_type, orderstatus, business_id, business_name,expressname,expressnumber", "id desc,actiontime desc");
        
        $orderItemOBJ = new OrdOrderItemModel();
        $orderitem = array();
        $result = array();
        foreach ($returnresult['list'] as $k => $v) {
            // 商品信息
            // 有商品规格时
            if(!empty($v['skuid'])) {
                $specOBJ = new ProProductSpecModel();
//                 $goods = $specOBJ->getRow($v['skuid'], "productname, prouctprice, bullamount, spec, productimage");
                $goods = $specOBJ->getRow(array("id" => $v['skuid']), "productname, spec, productimage");
                $decodespec = json_decode($goods['spec']);
                $goods['skudetail'] = '';
                foreach ($decodespec as $vspec) {
                    if($goods['skudetail'] != '') {
                        $goods['skudetail'] .= ";";
                    }
                    $goods['skudetail'] .= $vspec->name.":".$vspec->value;
                }
                $goods['thumb'] = Img::url($goods['productimage']);
                unset($goods['spec']);
                unset($goods['productimage']);
                $orderitem = $goods;
            } else {
                // 无商品规格时
                $productOBJ = new ProProductModel();
//                 $goods = $productOBJ->getRow($v['productid'], "productname, prouctprice, bullamount, thumb");
                $goods = $productOBJ->getRow(array("id" => $v['productid']), "productname, thumb");
                $goods['skudetail'] = '';
                foreach ($decodespec as $vspec) {
                    if($goods['skudetail'] != '') {
                        $goods['skudetail'] .= ";";
                    }
                    $goods['skudetail'] .= $vspec->name.":".$vspec->value;
                }
                $goods['thumb'] = Img::url($goods['thumb']);
                unset($goods['spec']);
                $orderitem = $goods;
            }
            $returnParams = array("orderno" => $v['order_code'], "skuid" => $v['skuid'], "productid" => $v['productid']);
            $price = $orderItemOBJ->getItemPrice($returnParams);
            
            $orderitem['productid'] = $v['productid'];
            $orderitem['skuid'] = $v['skuid'];
            $orderitem['productnum'] = $v['productnum'] ? $v['productnum'] : 0;
            
            $orderitem['productamount'] = DePrice($price['prouctprice']);
            $orderitem['productbull'] = DePrice($price['bullamount']);
            
            $orderitem['returnamount'] = DePrice($v['returnamount']);
            $orderitem['returnbull'] = DePrice($v['returnbull']);
            
//             $orderitem['prouctprice'] = DePrice($price['productprice']);
//             $orderitem['bullamount'] = DePrice($price['productbull']);
            $orderitem['prouctprice'] = DePrice($price['productprice']);
            $orderitem['bullamount'] = DePrice($price['productbull']);
            
            
            $skip_type = 1;
            
            if($v['return_type'] == self::price_type) {
                if($v['orderstatus'] == self::salePriceExamStatus) {
                    $result[$k]['statusDes'] = '退款审核中';
                } else if($v['orderstatus'] == self::salePriceApplySuccess) {
                    $result[$k]['statusDes'] = '退款中';
                } else if($v['orderstatus'] == self::salePriceApplyFail) {
                    $result[$k]['statusDes'] = '卖家已拒绝，请处理';
                } else if($v['orderstatus'] == self::salePriceSuccess) {
                    $result[$k]['statusDes'] = '退款成功';
                    $skip_type = 2;
                }
            } else if($v['return_type'] == self::goods_type) {
                if($v['orderstatus'] == self::saleGoodsExamStatus) {
                    $result[$k]['statusDes'] = '退款/退货审核中';
                } else if($v['orderstatus'] == self::saleGoodsApplySuccess) {
                    $result[$k]['statusDes'] = '退款/退货中';
                } else if($v['orderstatus'] == self::saleGoodsApplyFail) {
                    $result[$k]['statusDes'] = '卖家已拒绝，请处理';
                } else if($v['orderstatus'] == self::saleGoodsSuccess) {
                    $result[$k]['statusDes'] = '退款/退货成功';
                    $skip_type = 2;
                }
            }
            if($v['orderstatus'] == self::cancleExamStatus) {
                if($v['return_type'] == self::price_type) {
                    $result[$k]['statusDes'] = '退款关闭';
                    $skip_type = 2;
                } else {
                    $result[$k]['statusDes'] = '退款/退货关闭';
                    $skip_type = 2;
                }
            }
            
            $result[$k]['returnid'] = $v['id'];
            $result[$k]['orderno'] = $v['order_code'];
            $result[$k]['businessid'] = $v['business_id'];
            $result[$k]['businessname'] = $v['business_name'];
            $result[$k]['skip_type'] = $skip_type;
            
            $orderact = array();
            $result[$k]['receipt_address'] = array("express_name"=>"","express_no"=>"");
            if($params['version'] >= "2.1.0") {
                $result[$k]['receipt_address']["express_name"] = "";
                $result[$k]['receipt_address']["express_no"] = "";
                // 商家审核通过，买家还未填写发货信息
                if($v['orderstatus'] == self::saleGoodsApplySuccess) {
                    if(empty($v['expressname']) || empty($v['expressnumber'])) {
                        $orderact[0] = array("act" => "1", "acttype" => "17", "actname" => "填写物流单号");
                    } else {
                        $result[$k]['receipt_address']["express_name"] = $v['expressname'];
                        $result[$k]['receipt_address']["express_no"] = $v['expressnumber'];
                        $orderact[0] = array("act"=>"1","acttype"=>"7","actname"=>"查看物流");
                    }
                }
            }
            $result[$k]['orderact'] = $orderact;
            $result[$k]['orderitem'] = $orderitem;
            $result[$k]['returnamount'] = DePrice($v['returnamount']);
            $result[$k]['returnbull'] = DePrice($v['returnbull']);
            
            $result[$k]['prouctprice'] = DePrice($price['prouctprice']);
            $result[$k]['bullamount'] = DePrice($price['bullamount']);
        }
        $resultlist['total'] = $returnresult['total'];
        $resultlist['list'] = $result;
        return $resultlist;
    }
    
    /**
    * @user 获取退货状态
    * @param 
    * @author jeeluo
    * @date 2017年3月10日上午9:43:20
    */
    public function getOrderReturnStatus($params) {
        $ordReturnOBJ = new OrdOrderReturnModel();
        $result = $ordReturnOBJ->getRow(array("order_code" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']), "orderstatus", "id desc");
        return $result;
    }
}