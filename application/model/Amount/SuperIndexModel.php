<?php
// +----------------------------------------------------------------------
// |  [ 用户余额 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-04-22
// +----------------------------------------------------------------------
namespace app\model\Amount;
use app\lib\Model;

class SuperIndexModel
{
    /**
    * @user 获取支付方式类型总支付额
    * @param 
    * @author jeeluo
    * @date 2017年4月22日下午3:31:31
    */
    public function getPayTypeAmount($typeArr) {
        $where['pay_status'] = 1;
        $where['pay_type'] = array();
        foreach ($typeArr as $k => $v) {
            if($k == 0) {
                $where['pay_type'][$k] = array("=", $v);
            } else {
                $where['pay_type'][$k] = array("=", $v, "or");
            }
        }
        
        $result = Model::ins("PayOrder")->getRow($where, "SUM(pay_money) as money ");
        
        return !empty($result['money']) ? DePrice($result['money']) : '0.00';
    }
}