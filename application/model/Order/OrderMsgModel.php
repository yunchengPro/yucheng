<?php
namespace app\model\Order;
use app\lib\Model;
use app\model\Sys\CommonModel;

class OrderMsgModel {
    
    public function orderfahuo($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"customerid");
            $userid = $order['customerid'];
            
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
            
            Model::new("Msg.SendMsg")->orderfahuo(["userid"=>$userid,"orderno"=>$orderno,"imageUri"=>$orderitem['thumb'],"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    public function ordercose($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"customerid");
            $userid = $order['customerid'];
            
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
            
            Model::new("Msg.SendMsg")->ordercose(["userid"=>$userid,"orderno"=>$orderno,"imageUri"=>$orderitem['thumb'],"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    public function orderRemindFahuo($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"businessid,customerid");
            $bus = Model::ins("BusBusiness")->getRow(["id"=>$order['businessid']],"customerid");
            
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(["id"=>$order['customerid'],"nickname"]);
            $cus = Model::ins("CusCustomer")->getRow(["id"=>$order['customerid'],"mobile"]);
            $nickname = !empty($cusInfo['nickname']) ? $cusInfo['nickname'] : $cus['mobile'];
            
//             $title = $nickname."发起提醒发货";
//             $content = $nickname."购买的".$orderitem['productname']."提醒您发货";
            $title = "买家(".$cus['mobile'].")提醒您发货";
            $content = "买家(".$cus['mobile'].')购买的'.$orderitem['productname']."提醒您发货";
            Model::new("Msg.SendMsg")->orderRemindFahuo(["userid"=>$bus['customerid'],"orderno"=>$orderno,"imageUri"=>$orderitem['thumb'],"title"=>$title,"content"=>$content,"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    public function applyReturn($param) {
        $returnno = $param['returnno'];
        if(!empty($returnno)) {
            $returnInfo = Model::ins("OrdOrderReturn")->getRow(["returnno"=>$returnno],"order_code,return_type,business_id,customerid,productid,skuid");
            $bus = Model::ins("BusBusiness")->getRow(["id"=>$returnInfo['business_id']],"customerid");
            
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$returnInfo['order_code'],"productid"=>$returnInfo['productid'],"skuid"=>$returnInfo['skuid']],"id,productname,thumb");
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(["id"=>$returnInfo['customerid']],"nickname");
            $cus = Model::ins("CusCustomer")->getRow(["id"=>$returnInfo['customerid']],"mobile");
            $nickname = !empty($cusInfo['nickname']) ? $cusInfo['nickname'] : $cus['mobile'];
            
            $type = $param['type'];
            
            $title = '';
            $content = '';
            $typeStr = '申请';
            if($type == 1) {
                $typeStr = '申请';
            } else if($type == 2) {
                $typeStr = '修改';
            } else if($type == 3) {
                $typeStr = '撤销';
            } else if($type == 4) {
                $typeStr = "取消";
            }
            
//             if($returnInfo['return_type'] == 1) {
//                 $title = $nickname."发起".$typeStr."退款";
//                 $content = $nickname."购买的".$orderitem['productname'].$typeStr."退款";
//             } else {
//                 $title = $nickname."发起".$typeStr."退货/退款";
//                 $content = $nickname."购买的".$orderitem['productname'].$typeStr."退货/退款";
//             }
            
            if($returnInfo['return_type'] == 1) {
                $title = "买家(".$cus['mobile'].")发起".$typeStr."退款";
                $content = "买家(".$cus['mobile'].")购买的".$orderitem['productname'].$typeStr."退款";
            } else {
                $title = "买家(".$cus['mobile'].")发起".$typeStr."退货/退款";
                $content = "买家(".$cus['mobile'].")购买的".$orderitem['productname'].$typeStr."退货/退款";
            }
            
            Model::new("Msg.SendMsg")->applyReturn(["userid"=>$bus['customerid'],"returnno"=>$returnno,"imageUri"=>$orderitem['thumb'],"title"=>$title,"content"=>$content,"orderno"=>$returnInfo['order_code'],"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    public function orderRefuseOragree($param) {
        $returnno = $param['returnno'];
        if(!empty($returnno)) {
            $returnInfo = Model::ins("OrdOrderReturn")->getRow(["returnno"=>$returnno],"return_type,order_code,customerid,productid,skuid");
            
            $title = '';
            $content = '';
            $returnType = '';
            if($returnInfo['return_type'] == 1) {
                $returnType = "退款";
            } else if($returnInfo['return_type'] == 2) {
                $returnType = "退货";
            }
            
            if($param['type'] == 1) {
                $title = "卖家已同意".$returnType;
//                 $content = '您申请的'.$returnType.'单，卖家已同意'.$returnType;
                $content = '您的牛品订单(订单号：'.$returnInfo['order_code'].')'.$returnType.'申请，卖家已同意'.$returnType;
            } else if($param['type'] == 2) {
                $title = '卖家拒绝'.$returnType;
//                 $content = '您申请的'.$returnType.'单，卖家拒绝'.$returnType;
                $content = '您的牛品订单(订单号：'.$returnInfo['order_code'].')'.$returnType.'申请，卖家已拒绝您的'.$returnType.'申请，请及时处理';
            } else {
                $title = "卖家已处理".$returnType;
                $content = "卖家已处理".$returnType;
            }
            
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$returnInfo['order_code'],"productid"=>$returnInfo['productid'],"skuid"=>$returnInfo['skuid']],"id,productname,thumb","id asc");

            Model::new("Msg.SendMsg")->orderRefuseOragree(["userid"=>$returnInfo['customerid'],"orderno"=>$returnInfo['order_code'],"returnno"=>$returnno,"imageUri"=>$orderitem['thumb'],"title"=>$title,"content"=>$content,"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    public function orderConfirm($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"customerid");
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
            
            Model::new("Msg.SendMsg")->orderConfirm(["userid"=>$order['customerid'],"orderno"=>$orderno,"imageUri"=>$orderitem['thumb'],"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    public function orderAutoConfirm($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"customerid");
            $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
            
            Model::new("Msg.SendMsg")->orderAutoConfirm(["userid"=>$order['customerid'],"orderno"=>$orderno,"imageUri"=>$orderitem['thumb'],"productname"=>$orderitem['productname']]);
        }
        return true;
    }
    
    /**
    * @user 牛商收到新订单，进行消息提醒
    * @param 
    * @author jeeluo
    * @date 2017年7月20日下午5:26:17
    */
    public function orderbuswaitfahuo($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            // 获取订单的商家id值
            $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"businessid");
            // 根据牛商id值 获取用户id
            $bus = Model::ins("BusBusiness")->getRow(["id"=>$order['businessid']],"customerid");
            
            $orderItem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
            
            $title = "您有一笔新的待发货订单";
            $content = "您有一笔新的待发货订单";
            $voice_content = "您有一笔新的牛品订单";
            Model::new("Msg.SendMsg")->ordersbusmsg(["userid"=>$bus['customerid'],"orderno"=>$orderno,"imageUri"=>$orderItem['thumb'],"productname"=>$orderItem['productname'],"title"=>$title,"content"=>$content,"voice_content"=>$voice_content]);
        }
        return true;
    }
    
    /**
    * @user 用户提醒商家发货
    * @param 
    * @author jeeluo
    * @date 2017年7月20日下午5:33:47
    */
//     public function orderbusremindfahuo($param) {
//         $orderno = $param['orderno'];
//         if(!empty($orderno)) {
//             // 获取订单的商家id值
//             $order = Model::ins("OrdOrder")->getRow(["orderno"=>$orderno],"customerid,businessid");
//             // 根据牛商id值 获取用户id
//             $bus = Model::ins("BusBusiness")->getRow(["id"=>$order['businessid']],"customerid");
            
//             $cus = Model::ins("CusCustomer")->getRow(["id"=>$order['customerid']],"mobile");
        
//             $orderItem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$orderno],"id,productname,thumb","id asc");
        
//             $title = "买家(".$cus['mobile'].")提醒您发货";
//             $content = "买家(".$cus['mobile'].')购买的'.$orderItem['productname']."提醒您发货";
//             Model::new("Msg.SendMsg")->ordersbusmsg(["userid"=>$bus['customerid'],"orderno"=>$orderno,"imageUri"=>$orderItem['thumb'],"productname"=>$orderItem['productname'],"title"=>$title,"content"=>$content,"type"=>3]);
//         }
//         return true;
//     }
    
    /**
    * @user 用户操作退货/退款操作  对应商家收到的提醒
    * @param 
    * @author jeeluo
    * @date 2017年7月20日下午5:35:43
    */
//     public function applyReturnbus($param) {
//         $returnno = $param['returnno'];
//         if(!empty($returnno)) {
//             $returnInfo = Model::ins("OrdOrderReturn")->getRow(["returnno"=>$returnno],"order_code,return_type,business_id,customerid,productid,skuid");
//             $bus = Model::ins("BusBusiness")->getRow(["id"=>$returnInfo['business_id']],"customerid");
    
//             $orderitem = Model::ins("OrdOrderItem")->getRow(["orderno"=>$returnInfo['order_code'],"productid"=>$returnInfo['productid'],"skuid"=>$returnInfo['skuid']],"id,productname,thumb");
//             $cusInfo = Model::ins("CusCustomerInfo")->getRow(["id"=>$returnInfo['customerid']],"nickname");
//             $cus = Model::ins("CusCustomer")->getRow(["id"=>$returnInfo['customerid']],"mobile");
//             $nickname = !empty($cusInfo['nickname']) ? $cusInfo['nickname'] : $cus['mobile'];
    
//             $type = $param['type'];
    
//             $title = '';
//             $content = '';
//             $typeStr = '申请';
//             if($type == 1) {
//                 $typeStr = '申请';
//             } else if($type == 2) {
//                 $typeStr = '修改';
//             } else if($type == 3) {
//                 $typeStr = '撤销';
//             } else if($type == 4) {
//                 $typeStr = "取消";
//             }
    
//             if($returnInfo['return_type'] == 1) {
//                 $title = "买家(".$cus['mobile'].")发起".$typeStr."退款";
//                 $content = "买家(".$cus['mobile'].")购买的".$orderitem['productname'].$typeStr."退款";
//             } else {
//                 $title = "买家(".$cus['mobile'].")发起".$typeStr."退货/退款";
//                 $content = "买家(".$cus['mobile'].")购买的".$orderitem['productname'].$typeStr."退货/退款";
//             }
    
//             Model::new("Msg.SendMsg")->applyReturn(["userid"=>$bus['customerid'],"returnno"=>$returnno,"imageUri"=>$orderitem['thumb'],"title"=>$title,"content"=>$content,"orderno"=>$returnInfo['order_code'],"productname"=>$orderitem['productname']]);
//         }
//         return true;
//     }
    
    
    public function withdrawals($param) {
        $orderno = $param['orderno'];
        if(!empty($orderno)) {
            $order = Model::ins("CusWithdrawals")->getRow(["orderno"=>$orderno],"id,status,pay_money,amount,customerid,remark,addtime,account_name,account_number");

            $title = '';
            $content = '';
            $last4_bank = CommonModel::last4_bank($order['account_number']);
            $company_phone = CommonModel::getCompanyPhone();
            if($order['status'] == 1) {
                $title = "提现成功";
                
                if($order['amount'] > $order['pay_money']) {
                    $poundage = $order['amount'] - $order['pay_money'];
                    $content = "尊敬的用户，您于".date("Y年m月d日 H:i:s",strtotime($order['addtime']))."发起一笔".DePrice($order['amount'])."到".$order['account_name']."(".$last4_bank.")的提现申请提现成功,手续费".DePrice($poundage)."元:如有疑问请拨打".$company_phone[0];
                } else {
//                 $content = "尊敬的用户，您的提现申请".DePrice($order['pay_money'])."元,提现成功";
                    $content = "尊敬的用户，您于".date("Y年m月d日 H:i:s",strtotime($order['addtime']))."发起一笔".DePrice($order['amount'])."到".$order['account_name']."(".$last4_bank.")的提现申请提现成功:如有疑问请拨打".$company_phone[0];
                }
            } else if($order['status'] == 2) {
                $title = "提现失败";
//                 $content = "尊敬的用户，您的提现申请".DePrice($order['amount'])."元,提现失败!原因：".$order['remark'];
//                 $content = "尊敬的用户，您于".date("Y年m月d日 H:i:s",strtotime($order['addtime']))."发起一笔".DePrice($order['amount'])."的提现申请到".$order['account_name']."(".$last4_bank.")申请失败：未通过原因：".$order['remark']."。如有疑问请拨打".$company_phone[0];
                $content = "尊敬的用户，您于".date("Y年m月d日 H:i:s",strtotime($order['addtime']))."发起一笔".DePrice($order['amount'])."到".$order['account_name']."(".$last4_bank.")的提现申请申请失败：未通过原因：".$order['remark']."。如有疑问请拨打".$company_phone[0];
            } else if($order['status'] == 0) {
                $title = "提现申请";
//                 $content = "尊敬的用户，您于".date('Y年m月d日 H:i:s',strtotime($order['addtime']))."发起一笔".DePrice($order['amount'])."的提现申请到".$order['account_name']."(".$last4_bank."):如有疑问请拨打".$company_phone[0];
                
                $content = "尊敬的用户，您于".date('Y年m月d日 H:i:s',strtotime($order['addtime']))."发起一笔".DePrice($order['amount'])."到".$order['account_name']."(".$last4_bank.")的提现申请:如有疑问请拨打".$company_phone[0];
            }
        
            Model::new("Msg.SendMsg")->withdrawals(["userid"=>$order['customerid'],"orderno"=>$orderno,"status"=>$order['status'],"title"=>$title,"content"=>$content]);
        }
        return true;
    }

    public function sysAreaerror($params) {
        $title = '';
        if($params['type'] == 1) {
            $title = 'area_code 为空';
        } else if($params['type'] == 2) {
            $title = '经纬度为空';
        } else if($params['type'] == 3) {
            $title = 'area_code 不为空，数据库查无此地';
        }
        
        $content = "数据id：".$params['id']."手机号码:".$params['mobile'];
        
        $user = Model::ins("CusCustomer")->getRow(["mobile"=>"15013883804"],"id");
//         $user = Model::ins("CusCustomer")->getRow(["mobile"=>"13265411023"],"id");
        
        Model::new("Msg.SendMsg")->SendSysMsg(["title"=>$title,"content"=>$content,"userid"=>$user['id']]);
        return true;
    }
}
?>