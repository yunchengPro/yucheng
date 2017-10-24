<?php
namespace app\api\controller\Order;

use app\api\ActionController;
use app\model\Order\RefundModel;
use app\model\OrdOrderReturnModel;
use app\model\OrdOrderModel;
use think\Config;
use app\model\OrdReturnLogModel;
use app\lib\Model;

class RefundController extends ActionController
{
    
    // 退款审核成功
    const salePriceExamSuccess = 2;
    // 退货审核成功
    const saleGoodsExamSuccess = 12;
    
    const salePriceApplySuccess = 4;
    
    const saleGoodsApplySuccess = 14;
    
    // 退款失败
    const salePriceApplyFail = 3;
    // 退货失败
    const saleGoodsApplyFail = 13;

    // 退款取消
    const saleApplyCancle = 20;
    
    const saleRefundSuccess = 32;
    // 退货类型
    const goods_type = 2;
    // 退款累心
    const price_type = 1;
    // 初始化值
    const initNumber = 0;
    
    // 售中退款
    const salPricetype = 1;
    // 售后退款
    const cusPricetype = 2;
    // 售后退货
    const cusGoodstype = 3;
    // 日志表状态
    const logExamFail = 2;
    const logExamSuccess = 3;
    const logSuccess = 4;
    const logCancle = 20;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 退款页面 产品详情
    * @param 
    * @author jeeluo
    * @date 2017年3月7日下午5:31:54
    */
    public function salerefundAction() {
        $orderno = $this->params['orderno'];
        $productid = $this->params['productid'];
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;

        if(empty($orderno) || empty($productid)) {
            return $this->json(404);
        }
        $this->params['customerid'] = $this->userid;
        $refundOBJ = new RefundModel();
        $result = $refundOBJ->productInfo($this->params);
        
        $result['productdetail']['reasonlist'] = Config::get('salereason');
        if($this->params['type'] == self::salPricetype) {
            $result['productdetail']['reasonlist'] = Config::get('salereason');
        } else if($this->params['type'] == self::cusPricetype) {
            $result['productdetail']['reasonlist'] = Config::get('returnprice');
        } else if($this->params['type'] == self::cusGoodstype) {
            $result['productdetail']['reasonlist'] = Config::get('returngoods');
        }
        
        return $this->json($result['code'], $result['productdetail']);
    }
    
    /**
    * @user 退款申请操作
    * @param 
    * @author jeeluo
    * @date 2017年3月7日下午8:30:21
    */
    public function refersaleapplyAction() {
        if(empty($this->params['reason']) || empty($this->params['orderno']) || empty($this->params['productid']) || empty($this->params['return_type'])) {
            return $this->json(404);
        }
        // return_count
        
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $this->params['customerid'] = $this->userid;
        
        $refundOBJ = new RefundModel();
        
        $result = $refundOBJ->saleApplyInfo($this->params);
        return $this->json($result['code']);
    }
    
    /**
    * @user 审核失败(商家)
    * @param 
    * @author jeeluo
    * @date 2017年3月8日上午10:03:56
    */
    public function saleapplyfailAction() {
        
        if(empty($this->params['orderno']) || empty($this->params['productid'])) {
            return $this->json(404);
        }
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $this->params['customerid'] = $this->userid;
        $orderOBJ = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($this->params['orderno']);
        if(!empty($order)) {
            
            $ordreturnOBJ = new OrdOrderReturnModel();
            $returnObj = $ordreturnOBJ->getRow(array("order_code" => $this->params['orderno'], "productid" => $this->params['productid'], "skuid" => $this->params['skuid']), "orderstatus, id", "id desc");
            
            $refundOBJ = new RefundModel();
            $return_type = $refundOBJ->getReturnType($this->params);
            
            $order_status = $returnObj['orderstatus'];
            
            if($order_status == self::saleApplyCancle) {
                return $this->json(11002);
            } else if($order_status == self::saleGoodsApplyFail || $order_status == self::salePriceApplyFail) {
                return $this->json(11004);
            }
            
            if($return_type['return_type'] == self::price_type) {
                if($order_status == self::salePriceApplyFail) {
                    return $this->json(11001);
                }
                $order_status = self::salePriceApplyFail;
            } else if($return_type['return_type'] == self::goods_type) {
                if($order_status == self::saleGoodsApplyFail) {
                    return $this->json(11001);
                }
                $order_status = self::saleGoodsApplyFail;
            }
            
            $end = date('Y-m-d H:i:s', strtotime("+3 day"));
            
            $status = $ordreturnOBJ->modify(array("examinetime" => getFormatNow(), "actiontime" => getFormatNow(), "endtime" => $end, "orderstatus" => $order_status, "audit_remark" => $this->params['remark']), 
                    array("id" => $returnObj['id']));
            
            $this->params['logStatus'] = self::logExamFail;
            $this->params['content'] = "卖家拒绝申请";
            $refundOBJ->writeBusReturnLog($this->params);
            if($status) {
                return $this->json(200);
            }
            return $this->json(400);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 审核成功(商家)
    * @param 
    * @author jeeluo
    * @date 2017年3月8日上午10:54:42
    */
    public function saleapplysuccessAction() {
        if(empty($this->params['orderno']) || empty($this->params['productid'])) {
            return $this->json(404);
        }
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $this->params['customerid'] = $this->userid;
        $orderOBJ = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($this->params['orderno']);
        if(!empty($order)) {
            
            $ordreturnOBJ = new OrdOrderReturnModel();
            $returnObj = $ordreturnOBJ->getRow(array("order_code" => $this->params['orderno'], "productid" => $this->params['productid'], "skuid" => $this->params['skuid']), "orderstatus, id", "id desc");
            
            if($returnObj['orderstatus'] == self::saleApplyCancle) {
                return $this->json(11002);
            } else if($returnObj['orderstatus'] == self::saleGoodsExamSuccess || $returnObj['orderstatus'] == self::salePriceExamSuccess) {
                return $this->json(11004);
            }
            
            $refundOBJ = new RefundModel();
            $return_type = $refundOBJ->getReturnType($this->params);
 
            $order_status = self::initNumber;
            if($return_type['return_type'] == self::price_type) {
                $order_status = self::salePriceExamSuccess;
            } else if($return_type['return_type'] == self::goods_type) {
                $order_status = self::saleGoodsExamSuccess;
            }
            
            $ordreturnOBJ->modify(array("examinetime" => getFormatNow(), "actiontime" => getFormatNow(), "orderstatus" => $order_status, "audit_remark" => $this->params['remark']),
                array("id" => $returnObj['id']));
            
            $order['return_type'] = $return_type['return_type'];
            $refundOBJ = new RefundModel();
            $status = $refundOBJ->returnPrice($order);
            
            $this->params['logStatus'] = self::logExamSuccess;
            $this->params['content'] = "卖家同意申请";

//             $this->params['logStatus'] = self::logSuccess;
//             $this->params['content'] = "卖家已退款/退货";
            
            $refundOBJ->writeBusReturnLog($this->params);
            if($status) {
                return $this->json(200);
            }
            return $this->json(400);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 商家确认退款 取消订单
    * @param 
    * @author jeeluo
    * @date 2017年3月8日下午2:45:41
    */
    public function confirmrefundAction() {
        if(empty($this->params['orderno']) || empty($this->params['productid'])) {
            return $this->json(404);
        }
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $orderOBJ = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($this->params['orderno']);
        if(!empty($order)) {
            // 修改订单的状态

            $ordreturnOBJ = new OrdOrderReturnModel();
            $returnObj = $ordreturnOBJ->getRow(array("order_code" => $this->params['orderno'], "productid" => $this->params['productid'], "skuid" => $this->params['skuid']), "orderstatus, id, customerid, return_type", "id desc");
            
            if($returnObj['orderstatus'] == self::salePriceApplySuccess || $returnObj['orderstatus'] == self::saleGoodsApplySuccess) {
                return $this->json(11005);
            } else if($returnObj['orderstatus'] != self::salePriceExamSuccess && $returnObj['orderstatus'] != self::saleGoodsExamSuccess) {
                return $this->json(1001);
            }
            $this->params['customerid'] = $returnObj['customerid'];
            $this->params['return_type'] = $returnObj['return_type'];
            
            $refundOBJ = new RefundModel();
            $status = $refundOBJ->confirmRefundPrice($this->params);
            
            $this->params['logStatus'] = self::logSuccess;
            $this->params['content'] = "卖家已退款/退货";
            
            $refundOBJ->writeBusReturnLog($this->params);
            if($status) {
                return $this->json(200);
            }
            return $this->json(400);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 用户自己取消申请
    * @param 
    * @author jeeluo
    * @date 2017年3月8日上午10:42:56
    */
    public function cancleapplyAction() {
        if(empty($this->params['orderno']) || empty($this->params['productid'])) {
            return $this->json(404);
        }
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $orderOBJ = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($this->params['orderno']);
        if(!empty($order)) {
            if($order['customerid'] == $this->userid) {
                $ordreturnOBJ = new OrdOrderReturnModel();
                
                $returnInfo = $ordreturnOBJ->getRow(["order_code"=>$this->params['orderno'],"productid"=>$this->params['productid'],"skuid"=>$this->params['skuid']],"returnno,id","id desc");
                
                $this->params['customerid'] = $this->userid;
                $refundOBJ = new RefundModel();
                $orderstatus = $refundOBJ->getOrderReturnStatus($this->params);
                
                if($orderstatus['orderstatus'] == self::saleApplyCancle) {
                    return $this->json(11002);
                } else if($orderstatus['orderstatus'] == self::saleRefundSuccess) {
                    return $this->json(11003);
                }
                
//                 $status = $ordreturnOBJ->modify(array("endtime" => getFormatNow(), "actiontime" => getFormatNow(), "orderstatus" => self::saleApplyCancle, "audit_remark" => $this->params['remark']),
//                     array("order_code" => $this->params['orderno'], "productid" => $this->params['productid'], "skuid" => $this->params['skuid']));

                $status = $ordreturnOBJ->modify(array("endtime" => getFormatNow(), "actiontime" => getFormatNow(), "orderstatus" => self::saleApplyCancle, "audit_remark" => $this->params['remark']),
                    array("id"=>$returnInfo['id']));
                
                $this->params['logStatus'] = self::logCancle;
                $this->params['content'] = "您取消了申请";
                // 写入日志表
                
                $refundOBJ->writeCusReturnLog($this->params);
                if($status) {
//                     echo 11;exit;
//                     Model::new("Order.OrderMsg.applyReturn")->applyReturn(["returnno"=>$returnInfo['returnno'],"type"=>4]);
                    Model::new("Sys.Mq")->add([
                        // "url"=>"Msg.SendMsg.applyReturn",
                        "url"=>"Order.OrderMsg.applyReturn",
                        "param"=>[
                            "returnno"=>$returnInfo['returnno'],
                            "type"=>4,
                        ],
                    ]);
                    Model::new("Sys.Mq")->submit();
                    return $this->json(200);
                }
                return $this->json(400);
            }
            return $this->json(1001);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 退款详情
    * @param 
    * @author jeeluo
    * @date 2017年3月8日下午4:32:30
    */
    public function refundinfoAction() {
        if(empty($this->params['orderno']) || empty($this->params['productid'])) {
            return $this->json(404);
        }
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $this->params['customerid'] = $this->userid;
        
        $orderOBJ = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($this->params['orderno']);
        if(!empty($order)) {
            if($order['customerid'] == $this->userid) {
                // 退款详情页面
                
                $this->params['version'] = $this->getVersion();
                $refundOBJ = new RefundModel();
                $infoResult = $refundOBJ->getRefundInfo($this->params);
                
                $result['orderdetail'] = $infoResult; 
                
                return $this->json(200, $result);
            }
            return $this->json(1001);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 写入物流信息
    * @param 
    * @author jeeluo
    * @date 2017年8月28日下午8:37:21
    */
    public function refundexpressAction() {
        $returnid = $this->params['returnid'];
        $params['expressname'] = $this->params['expressname'];
        $params['expressnumber'] = $this->params['expressnumber'];
        
        if(empty($returnid) || empty($params['expressname']) || empty($params['expressnumber'])) {
            return $this->json(404);
        }
        
        // 查询退单的申请者
        $returnInfo = Model::ins("OrdOrderReturn")->getRow(["id"=>$returnid],"customerid,expressname,expressnumber");
        if(!empty($returnInfo["customerid"])) {
            if($returnInfo['customerid'] != $this->userid) {
                // 无操作权限
                return $this->json(1001);
            }
            
            if(!empty($returnInfo['expressname']) || !empty($returnInfo['expressnumber'])) {
                return $this->json(12002);
            }
            $params['expresstime'] = $params['examinetime'] = getFormatNow();
            // 写入数据库
            Model::ins("OrdOrderReturn")->update($params, ["id"=>$returnid]);
            return $this->json(200);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 退款成功 详情页
    * @param 
    * @author jeeluo
    * @date 2017年3月11日下午1:59:47
    */
    public function refundotherinfoAction() {
//         if(empty($this->params['returnid'])) {
//             return $this->json(404);
//         }
//         if(empty($this->params['orderno']) || empty($this->params['productid'])) {
//             return $this->json(404);
//         }
//         $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $this->params['customerid'] = $this->userid;
        
        if(!empty($this->params['returnid'])) {
            $ordReturnOBJ = new OrdOrderReturnModel();
            $returnInfo = $ordReturnOBJ->getRow(array("id" => $this->params['returnid']), "order_code");
            
            $this->params['orderno'] = $returnInfo['order_code'];
        }
    
        $orderOBJ = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($this->params['orderno']);
        if(!empty($order)) {
            if($order['customerid'] == $this->userid) {
                // 退款详情页面
    
                $refundOBJ = new RefundModel();
                $result = $refundOBJ->getOtherRefundInfo($this->params);
                return $this->json(200, $result);
            }
            return $this->json(1001);
        }
        return $this->json(1000);
    }
    
    /**
    * @user 协商历史
    * @param 
    * @author jeeluo
    * @date 2017年3月9日下午4:33:51
    */
    public function consulelogAction() {
        if(empty($this->params["orderno"]) || empty($this->params["productid"])) {
            return $this->json(404);
        }
        $this->params['skuid'] = $this->params['skuid'] ?: self::initNumber;
        $this->params['customerid'] = $this->userid;
        
        $returnLogOBJ = new OrdReturnLogModel();
        $result = $returnLogOBJ->getLogList(array("orderno" => $this->params['orderno'], "productid" => $this->params['productid'], "skuid" => $this->params['skuid'], "customerid" => $this->userid), "*", "id desc");
        
        return $this->json(200, $result);
    }
    
    /**
    * @user 退款/退货列表
    * @param 
    * @author jeeluo
    * @date 2017年3月9日下午5:59:13
    */
    public function returnlistAction() {
        $this->params['customerid'] = $this->userid;
        $this->params['version'] = $this->getVersion();
        $refundOBJ = new RefundModel();
        $result = $refundOBJ->getReturnPageList($this->params);
        return $this->json(200, $result);
    }
}