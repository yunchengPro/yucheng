<?php
// +----------------------------------------------------------------------
// |  [ 用户余额 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-05-03
// +----------------------------------------------------------------------
namespace app\model\Order;
use app\lib\Model;

class SuperIndexModel
{
    /**
    * @user 带条件查询
    * @param 
    * @author jeeluo
    * @date 2017年5月3日下午8:19:58
    */
    public function getWhereList($where) {
        $logisticsWhere = array();
        if(!empty($where['realname'])) {
            $logisticsWhere['realname'] = $where['realname'];
            
            unset($where['realname']);
        }
        
        if(!empty($where['mobile'])) {
            $logisticsWhere['mobile'] = $where['mobile'];
            unset($where['mobile']);
        }
        
        $orderList = Model::ins("OrdOrder")->getList($where, "*", "id desc",100);
        $orderList = Model::new("Customer.BullBus")->getRelatedList($orderList, "orderno");
        
        $logisticsList = array();
        if(!empty($logisticsWhere)) {
            $logisticsList = Model::ins("OrdOrderLogistics")->getList($logisticsWhere, "*", "id desc", 100);
            $logisticsList = Model::new("Customer.BullBus")->getRelatedList($logisticsList, "orderno");
        }
        
        $list = self::getIntersecCustomerIds($orderList, $logisticsList);
        
//         $list_ids = self::test(array($orderList, $logisticsList));
        
        $list = Model::new("Customer.BullBus")->pageList($list);
        
        return $list;
    }
    
    /**
    * @user 合并数据信息
    * @param 
    * @author jeeluo
    * @date 2017年5月3日下午8:19:22
    */
    private function getIntersecCustomerIds($orderList, $logisticsList) {
        $result = array();
        if(!empty($orderList)) {
            $count = 0;
            if(!empty($logisticsList)) {
                foreach ($orderList as $key => $cus) {
                    if(!empty($logisticsList[$key])) {
                        $result[$count] = $cus;
                        $count++;
                    }
                }
            } else {
                $result = $orderList;
            }
        }
        return $result;
    }

    public function test($lists, $isIdRelated = true, $key = "id") {
        $tempList = array();
        foreach ($lists as $list) {
//             $temp = array_keys($list);

            $temp = self::getInverseList($list, "orderno");
            
            $tempList = array_merge($tempList, $temp);
            
            $tempList = array_unique($tempList);
        }
        
        return $tempList;
    }

    public function getInverseList($customerList, $key = "id") {
        $list = array();
        foreach ($customerList as $customer) {
            $list[] = $customer[$key];
        }
        
        return $list;
    }
}