<?php
// +----------------------------------------------------------------------
// |  [ 流水 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use app\lib\Model;

class FlowModel{

    //生成订单流水号
    public function getFlowId($prefix=''){
        if($prefix=='')
            return rand(1000,9999).time().rand(1000,9999);
        else
            return $prefix.rand(1000,9999);
    }

    public function addflow($param){

        $tablename  = $param['tablename'];

        $data       = $param['data']; 

        $flowid     = !empty($param['flowid'])?$param['flowid']:$this->getFlowId();

        $flowtime   = !empty($param['flowtime'])?$param['flowtime']:date("Y-m-d H:i:s");

        $insertData1 = [
                        "flowid"=>$flowid,
                        "flowtime"=>$flowtime,
                    ];

        if(!empty($data['usertype']))
            $insertData1['usertype'] = $data['usertype'];
        if(!empty($data['userid']))
            $insertData1['userid'] = $data['userid'];
        if(!empty($data['orderno']))
            $insertData1['orderno'] = $data['orderno'];
        if(!empty($data['flowtype']))
            $insertData1['flowtype'] = $data['flowtype'];
        if(!empty($data['direction']))
            $insertData1['direction'] = $data['direction'];
        if(!empty($data['amount']))
            $insertData1['amount'] = $data['amount'];
        if(!empty($data['remark']))
            $insertData1['remark'] = $data['remark'];
        if(!empty($data['fromuserid']))
            $insertData1['fromuserid'] = $data['fromuserid'];
        if(!empty($data['role']))
            $insertData1['role'] = $data['role'];
        if(!empty($data['profit_role']))
            $insertData1['profit_role'] = $data['profit_role'];
        if(!empty($data['parent_userid']))
            $insertData1['parent_userid'] = $data['parent_userid'];

        
        $insertData2 = [
                        "flowid"=>$flowid,
                        "flowtime"=>$flowtime,
                    ];

        if(!empty($data['userid']))
            $insertData2['userid'] = $data['userid'];
        if(!empty($data['orderno']))
            $insertData2['orderno'] = $data['orderno'];
        if(!empty($data['flowtype']))
            $insertData2['flowtype'] = $data['flowtype'];
        if(!empty($data['direction']))
            $insertData2['direction'] = $data['direction'];
        if(!empty($data['amount']))
            $insertData2['amount'] = $data['amount'];
        if(isset($data['remark']))
            $insertData2['remark'] = $data['remark'];
        // if(isset($data['businessid']))
        //     $insertData2['businessid'] = $data['businessid'];
        if(!empty($data['fromuserid']))
            $insertData2['fromuserid'] = $data['fromuserid'];
        if(!empty($data['role']))
            $insertData2['role'] = $data['role'];
        if(!empty($data['profit_role']))
            $insertData2['profit_role'] = $data['profit_role'];
        if(!empty($data['parent_userid']))
            $insertData2['parent_userid'] = $data['parent_userid'];

        //写入总记录表
        //1消费 2现金 3商家 4积分
        switch ($data['amounttype']){
            case '1':
                // echo "--------cash---------\n<br>";
                // echo "--------insertData1---------\n<br>";
                // print_r($insertData1);
                // echo "--------insertData2---------\n<br>";
                // echo "--------$tablename---------\n<br>";
                // print_r($insertData2);
                // if(strpos($tablename,"AmoFlowFut")===false)
                    Model::ins("AmoFlowCon")->insert($insertData1);
                // if(!empty($tablename))
                //     Model::ins($tablename)->insert($insertData2);
                break;

            case '2':
                // echo "--------cash---------\n<br>";
                // echo "--------insertData1---------\n<br>";
                // print_r($insertData1);
                // echo "--------insertData2---------\n<br>";
                // echo "--------$tablename---------\n<br>";
                // print_r($insertData2);
                // if(strpos($tablename,"AmoFlowFut")===false)
                    Model::ins("AmoFlowCash")->insert($insertData1);
                // if(!empty($tablename))    
                //     Model::ins($tablename)->insert($insertData2);
                break;

            case '3':

                // echo "--------cash---------\n<br>";
                // echo "--------insertData1---------\n<br>";
                // print_r($insertData1);
                // echo "--------insertData2---------\n<br>";
                // echo "--------$tablename---------\n<br>";
                // print_r($insertData2);
                // if(strpos($tablename,"AmoFlowFut")===false)
                    Model::ins("AmoFlowBus")->insert($insertData1);
                // if(!empty($tablename))
                //     Model::ins($tablename)->insert($insertData2);
                break;
            case '4':
                // if(strpos($tablename,"AmoFlowFut")===false)
                    Model::ins("AmoFlowInt")->insert($insertData1);
                // if(!empty($tablename))    
                //     Model::ins($tablename)->insert($insertData2);
                break;
            case '5':
                Model::ins("AmoFlowInt")->insert($insertData1);
                // if(!empty($tablename))    
                //     Model::ins($tablename)->insert($insertData2);
                break;
            case '6':
                Model::ins("AmoFlowInt")->insert($insertData1);
                // if(!empty($tablename))    
                //     Model::ins($tablename)->insert($insertData2);
                break;
            default:

                break;
        }

        return true;

    }

    //提交push
    public function flowpush($param){

        $tablename  = $param['tablename'];

        $data       = $param['data']; 

        $flowid     = $param['flowid'];

        $flowtime   = $param['flowtime'];

        /*
        "usertype" 所属对象1公司2用户3商家4交易手续费5慈善
        "userid" 用户ID
        "orderno" 订单ID
        "flowtype" 流水类型1订单支付2角色分润3店铺结算..
        "direction" 1收入2支出
        "amount" 金额
        "remark" 备注
        "amounttype"   1现金 2收益现金 3牛币
         */
        Model::new("Sys.Mq")->push([
                        "url"=>"Amount.Flow.addflow",
                        "param"=>[
                            "tablename"=>$tablename,
                            "data"=>$data,
                            "flowid"=>$flowid,
                            "flowtime"=>$flowtime,
                        ],
                    ]);
    }
}