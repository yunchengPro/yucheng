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
use app\model\Sys\CommonModel;

use app\lib\Log;

class SuperRechargeModel
{
    /**
    * @user 待条件查询
    * @param 
    * @author jeeluo
    * @date 2017年5月3日上午10:15:23
    */
    public function getWhereList($where) {
        
        $cusWhere = array();
        if(!empty($where['mobile'])) {
            $cusWhere['mobile'] = $where['mobile'];
            
            unset($where['mobile']);
        }
        
        $rechargeList = Model::ins("CusRecharge")->getList($where, "*", "id desc", 100);
        $rechargeList = Model::new("Customer.BullBus")->getRelatedList($rechargeList);
        
        $cusList = array();
        if(!empty($cusWhere)) {
            $cusList = Model::ins("CusCustomer")->getList($cusWhere, "*", "id desc", 100);
            $cusList = Model::new("Customer.BullBus")->getRelatedList($cusList);
        }
        
        $list = self::getIntersecCustomerIds($rechargeList, $cusList);
        
        $list = Model::new("Customer.BullBus")->pageList($list);
        
        return $list;
    }
    
    /**
    * @user 用户充值
    * @param 
    * @author jeeluo
    * @date 2017年5月4日下午3:23:31
    */
    public function addSysRecharge($params) {

        // 查询手机号码是否存在
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id, enable");
        
        if(empty($cus)) {
            return ["code" => "1000", "data" => "手机号码不存在，无法充值"];
        }
        
        if($cus['enable'] != 1) {
            return ["code" => "1002", "data" => "该手机用户被禁用"];
        }
        // 确保金额足够(暂时跳过)
        
        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();

        try{

            $insertData['orderno'] = CommonModel::getRoleOrderNo("NNHRESYS");
            $insertData['recharge_type'] = $params['recharge_type'];
            $insertData['mobile'] = $params['mobile'];
            $insertData['customerid'] = $cus['id'];
            $insertData['amount'] = $insertData['pay_money'] = EnPrice($params['amount']);
            $insertData['pay_status'] = 1;
            $insertData['pay_time'] = $insertData['addtime'] = getFormatNow();
            $insert_id = Model::ins("SysRecharge")->insert($insertData);

            //生成流水号
            $flowid = Model::new("Amount.Flow")->getFlowId($insertData['orderno']);
            switch ($params['recharge_type']) {
                case '1':
                    // 现金
                    Model::new("Amount.Amount")->add_cashamount([
                                                "userid"=>$cus['id'],
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>47,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusCash",
                                                "flowid"=>$flowid,
                                            ]);
                    Model::new("Amount.Amount")->pay_com_cashamount([
                                                "amount"=>EnPrice($params['amount']),
                                                "flowid"=>$flowid,
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>191,
                                            ]);
                    break;
                case '2':
                    // 绑定现金
                    Model::new("Amount.Amount")->add_profitamount([
                                                "userid"=>$cus['id'],
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>24,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusProfit",
                                                "flowid"=>$flowid,
                                            ]);
                    Model::new("Amount.Amount")->pay_com_profitamount([
                                                "amount"=>EnPrice($params['amount']),
                                                "flowid"=>$flowid,
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>192,
                                            ]);
                    break;
                case '3':
                    // 牛豆
                    Model::new("Amount.Amount")->add_bullamount([
                                                "userid"=>$cus['id'],
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>31,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusBull",
                                                "flowid"=>$flowid,
                                            ]);
                    Model::new("Amount.Amount")->pay_com_bullamount([
                                                "amount"=>EnPrice($params['amount']),
                                                "flowid"=>$flowid,
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>193,
                                            ]);
                    break;
                case '4':
                    Model::new("Amount.Bonus")->add_bonusamount([
                                                "userid"=>$cus['id'],
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>157,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusBonus",
                                                "flowid"=>$flowid,
                                            ]);
                    Model::new("Amount.Bonus")->pay_com_bonusamount([
                                                "amount"=>EnPrice($params['amount']),
                                                "flowid"=>$flowid,
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>194,
                                            ]);
                    break;
            }


            $amountModel->commit();   

            if($insert_id) {
                return ["code" => "200"];
            }

        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($cus['id']);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code" => "400", "data" => "操作有误"];
        }


        return ["code" => "400", "data" => "操作有误"];
    }
    
    /**
    * @user 系统公司充值
    * @param 
    * @author jeeluo
    * @date 2017年5月4日下午3:23:22
    */
    public function addSysComRecharge($params) {
        // 金额 流水等处理(暂时跳过)
        
        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();

        try{

            $insertData['orderno'] = CommonModel::getRoleOrderNo("NNHRESYS");
            $insertData['recharge_type'] = $params['recharge_type'];
            $insertData['mobile'] = $insertData['customerid'] = "-1";
            $insertData['amount'] = $insertData['pay_money'] = EnPrice($params['amount']);
            $insertData['pay_status'] = 1;
            $insertData['pay_time'] = $insertData['addtime'] = getFormatNow();
            
            $insert_id = Model::ins("SysRecharge")->insert($insertData);

            //生成流水号
            $flowid = Model::new("Amount.Flow")->getFlowId($insertData['orderno']);
            switch ($params['recharge_type']) {
                case '1':
                    // 现金
                    Model::new("Amount.Amount")->add_com_cashamount([
                                                "userid"=>-1,
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>47,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowComCash",
                                                "flowid"=>$flowid,
                                            ]);
                    break;
                case '2':
                    // 绑定现金
                    Model::new("Amount.Amount")->add_com_profitamount([
                                                "userid"=>-1,
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>24,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowComProfit",
                                                "flowid"=>$flowid,
                                            ]);
                    break;
                case '3':
                    // 牛豆
                    Model::new("Amount.Amount")->add_com_bullamount([
                                                "userid"=>-1,
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>31,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowComBull",
                                                "flowid"=>$flowid,
                                            ]);
                    break;
                case '4':
                    // 牛粮奖励金
                    Model::new("Amount.Bonus")->add_com_bonusamount([
                                                "userid"=>-1,
                                                "amount"=>EnPrice($params['amount']),
                                                "usertype"=>"2",
                                                "orderno"=>$insertData['orderno'],
                                                "flowtype"=>157,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowComBonus",
                                                "flowid"=>$flowid,
                                            ]);
                    break;
            }


            $amountModel->commit();   

            if($insert_id) {
                return ["code" => "200"];
            }


        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code" => "400", "data" => "操作有误"];
        }


        return ["code" => "400", "data" => "操作有误"];
    }
    
    /**
    * @user 合并数据信息
    * @param 
    * @author jeeluo
    * @date 2017年5月3日上午10:13:38
    */
    private function getIntersecCustomerIds($cusList, $cusInfoList) {
        $result = array();
        if(!empty($cusList)) {
            $count = 0;
            if(!empty($cusInfoList)) {
                foreach ($cusList as $key => $cus) {
                    if(!empty($cusInfoList[$cus['customerid']])) {
                        $result[$count] = $cus;
                        $count++;
                    }
                }
            } else {
                $result = $cusList;
            }
        }
        return $result;
    }
}