<?php
namespace app\model\User;

use app\lib\Model;

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
        }

        if(!empty($param['flowtime'])) {
            $where['flowtime'] = $param['flowtime'];
        }
        
        $where['userid'] = $param['customerid'];
        $where['isshow'] = 1;
        
        $flowlist = $flowObj->pageList($where, "id,orderno,flowtype,direction,amount,flowtime", "flowtime desc, id desc");
        
        $result = array();
        if(!empty($flowlist['list'])) {
            $i = 0;
            $j = 0;
            foreach ($flowlist['list'] as $k => $v) {
                
                $v['datetime'] = substr($v['flowtime'], 0, 7);
                $v['datetime'] = date('Y年m月',strtotime($v['datetime']));
                $v['amount'] = !empty($v['amount']) ? DePrice($v['amount']) : '0.00';
                
                if($result['list'][$i]['datetime'] == '') {
                    $result['list'][$i]['datetime'] = $v['datetime'];
                } else if($result['list'][$i]['datetime'] != $v['datetime']) {
                    $i++;
                    $j=0;
                    $result['list'][$i]['datetime'] = $v['datetime'];
                }
                $result['list'][$i]['data'][$j] = $v;
                $j++;
            }
        }
        
//         print_r($result);
//         exit;
        
        $flowlist['list'] = $result['list'];
        
        if(empty($flowlist['list'])) {
            if($flowlist['total'] == 0) {
                $showTime = date("Y年m月",time());
                $flowlist['list'][0] = array("datetime"=>$showTime,"data"=>array());
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