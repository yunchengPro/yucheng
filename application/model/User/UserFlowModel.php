<?php
namespace app\model\User;

use app\lib\Model;
use think\Config;
use app\model\Sys\CommonModel;

class UserFlowModel {
    
    /**
    * @user 获取用户消费流水列表数据
    * @param 
    * @author jeeluo
    * @date 2017年10月11日下午5:00:35
    */
    public function getCusFlowData($param) {
        // 传递过来用户id值 角色值
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }

        $type = $param['type'];
        
        $amoamount = Model::new("Amount.AmoAmount")->getAmount(["customerid"=>$param['customerid']]);
        $amount = 0;

        $flowObj;
        if($type == 1) {
            // 现金
            $flowObj = Model::ins("AmoFlowCash");
            $amount = $amoamount['data']['cashamount'];
            
        } else if($type == 2) {
            // 商家
            $flowObj = Model::ins("AmoFlowBus");
            $amount = $amoamount['data']['busamount'];
        } else if($type == 3) {
            // 消费
            $flowObj = Model::ins("AmoFlowCon");
            $amount = $amoamount['data']['conamount'];
        } else if($type == 4) {
            // 积分
            $flowObj = Model::ins("AmoFlowInt");
            $amount = $amoamount['data']['intamount'];
        } else if($type == 5) {
            // 充值消费
            $flowObj = Model::ins("AmoFlowRec");
            
            // $where['flowtype'] = ["in", "10,67"];
            $amount = $amoamount['data']['recamount'];
        }

        if(!empty($param['flowtime'])) {
            $where['flowtime'] = $param['flowtime'];
        }
        
        $where['userid'] = $param['customerid'];
        $where['isshow'] = 1;
        
        
        $flowlist = $flowObj->pageList($where, "id,orderno,flowtype,direction,amount,flowtime", "flowtime desc, id desc");
        
        $flowlist['amount'] = $amount;
        
        $result = array();
        
        if(!empty($flowlist['list'])) {
            $flowtype = Config::get("flowtype");
            foreach ($flowlist['list'] as $k => $v) {
                $flowlist['list'][$k]['amount'] = !empty($v['amount']) ? DePrice($v['amount']) : '0.00';
                $flowlist['list'][$k]['flowname'] = $flowtype[$v['flowtype']];
            }
        }
//         if(!empty($flowlist['list'])) {
//             $i = 0;
//             $j = 0;
//             $flowtype = Config::get("flowtype");
//             foreach ($flowlist['list'] as $k => $v) {
                
//                 $v['datetime'] = substr($v['flowtime'], 0, 7);
//                 $v['datetime'] = date('Y年m月',strtotime($v['datetime']));
//                 $v['amount'] = !empty($v['amount']) ? DePrice($v['amount']) : '0.00';
//                 $v['flowname'] = $flowtype[$v['flowtype']];
                
                
//                 if($result['list'][$i]['datetime'] == '') {
//                     $result['list'][$i]['datetime'] = $v['datetime'];
//                 } else if($result['list'][$i]['datetime'] != $v['datetime']) {
//                     $i++;
//                     $j=0;
//                     $result['list'][$i]['datetime'] = $v['datetime'];
//                 }
//                 $result['list'][$i]['data'][$j] = $v;
//                 $j++;
//             }
//         }
        
//         $flowlist['list'] = $result['list'];
        
//         if(empty($flowlist['list'])) {
//             if($flowlist['total'] == 0) {
//                 $showTime = date("Y年m月",time());
//                 $flowlist['list'][0] = array("datetime"=>$showTime,"data"=>array());
//             } else {
//                 $flowlist['list'] = array();
//             }
//         }
        
        return ["code" => "200", "data" => $flowlist];
    }
    
    public function getNoMonthFlowList($param) {
        // 传递过来用户id值 角色值
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        $type = $param['type'];
        
        $flowObj;
        if($type == 1) {
            // 现金
            $flowObj = Model::ins("AmoFlowCash");
        } else if($type == 2) {
            // 商家
            $flowObj = Model::ins("AmoFlowBus");
        } else if($type == 3) {
            // 消费
            $flowObj = Model::ins("AmoFlowCon");
        } else if($type == 4) {
            // 积分
            $flowObj = Model::ins("AmoFlowInt");
        } else if($type == 5) {
            // 充值消费
            $flowObj = Model::ins("AmoFlowRec");
        }
        
        if(!empty($param['flowtime'])) {
            $where['flowtime'] = $param['flowtime'];
        }
        
        $where['userid'] = $param['customerid'];
        $where['isshow'] = 1;

        $flowlist = $flowObj->pageList($where, "id,orderno,flowtype,direction,amount,flowtime", "flowtime desc, id desc");
        
        if(!empty($flowlist['list'])) {
            $flowType = Config::get("flowtype");
            foreach ($flowlist['list'] as $k => $v) {
                $flowlist['list'][$k]['flowname'] = $flowType[$v['flowtype']];
                $flowlist['list'][$k]['amount'] = DePrice($v['amount']);
            }
        }
        
        return ["code" => "200", "data" => $flowlist];
    }
    
    public function getNoMonthBusList($param) {
        if($param['customerid'] == "") {
            return ["code" => "404"];
        }
        
        $where['userid'] = $param['customerid'];
        
        if(!empty($param['begintime']) && !empty($param['endtime'])) {
            $where['flowtime'] = [[">=",$param['begintime']],["<",$param['endtime']]];
        }
        
        $where["isshow"] = 1;
        
        $flowlist = Model::ins("AmoFlowBus")->pageList($where,"id,orderno,flowtype,direction,amount,flowtime,fromuserid","flowtime desc, id desc");
        
        if(!empty($flowlist['list'])) {
            
            //
            $CusCustomer = Model::ins("CusCustomer");
            foreach ($flowlist['list'] as $k => $v) {
                // 
                $cus = $CusCustomer->getRow(["id"=>$v['fromuserid']],"mobile");
                
                $selfCus = $CusCustomer->getRow(["id"=>$param['customerid']],"mobile");
                
                $flowlist['list'][$k]['amount'] = DePrice($v['amount']);
                
                $flowlist['list'][$k]['flowname'] = !empty($cus['mobile']) ? CommonModel::mobile_format($cus['mobile']) : CommonModel::mobile_format($selfCus['mobile']);
//                 $flowlist['list'][$k]['flowname'] = 
            }
        }
        
        return ["code" => "200", "data" => $flowlist];
    }
    
    /**
    * @user 个人现金收益统计
    * @param $customerid 
    * @author jeeluo
    * @date 2017年10月12日上午11:36:47
    */
    public function getCusCashData($param) {
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        $where['userid'] = $param['customerid'];
        $where['direction'] = 1;
        
        // 总收益 不区分flowtype
        
        $AmoFlowCash = Model::ins("AmoFlowCash");
        
        // 累计收益
        $totalAmount = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        
        // 昨日收益
        $where['flowtime'] = [[">=",date("Y-m-d", strtotime("-1 day"))],["<",date("Y-m-d", time())]];
        $yesAmount = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        
        // 今日收益
        $where['flowtime'] = [[">=",date("Y-m-d", time())],["<", date("Y-m-d", strtotime("+1 day"))]];
        $todayAmount = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        
        $result['yesAmount'] = !empty($yesAmount['amount']) ? DePrice($yesAmount['amount']) : '0.00';
        $result['todayAmount'] = !empty($todayAmount['amount']) ? DePrice($todayAmount['amount']) : '0.00';
        $result['totalAmount'] = !empty($totalAmount['amount']) ? DePrice($totalAmount['amount']) : '0.00';
        
        return ["code" => "200", "data" => $result];
    }
}