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

class FutModel{

    
    /*
        现金余额支付 --带返回收入
     */
    public function add_fut_cashamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "usertype"=>$usertype,
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        收益现金余额支付 --收入
     */
    public function add_fut_profitamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "usertype"=>$usertype,
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>2, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        现金余额支付 --收入
     */
    public function add_fut_bullamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "usertype"=>$usertype,
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>3, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        企业账户现金余额支付 --带返回收入
     */
    public function add_fut_comcashamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "usertype"=>$usertype,
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }



    /*
        公司 现金余额支付 --收入
     */
    public function add_fut_com_cashamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
           
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "userid"=>$userid,
                            "fromuserid"=>$fromuserid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        公司 收益现金余额支付 --收入
     */
    public function add_fut_com_profitamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "userid"=>$userid,
                            "fromuserid"=>$fromuserid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>2, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        公司 现金余额支付 --收入
     */
    public function add_fut_com_bullamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            

            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>3, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        公司 增加手续费余额 --收入
     */
    public function add_fut_com_counteramount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        公司 增加慈善余额 --收入
     */
    public function add_fut_com_charitableamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>1,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }


    /*
        公司 收益现金余额支付 --支出
     */
    public function pay_fut_com_profitamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            
            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "userid"=>$userid,
                            "fromuserid"=>$fromuserid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>2,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>2, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }

    /*
        公司 现金余额支付 --支出
     */
    public function pay_fut_com_bullamount($param){
        $fromuserid     = $param['fromuserid'];
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            

            //生成收入流水
            Model::new("Amount.Flow")->flowpush([
                        "flowid"=>$flowid,
                        "tablename"=>$tablename,
                        "data"=>[
                            "fromuserid"=>$fromuserid,
                            "userid"=>$userid,
                            "usertype"=>$usertype,
                            "orderno"=>$param['orderno'],
                            "flowtype"=>$param['flowtype'],
                            "direction"=>2,
                            "amount"=>abs($amount),
                            "remark"=>$param['remark'],
                            "amounttype"=>3, // amounttype  1现金 2收益现金 3牛币
                            "role"=>$role,
                            "profit_role"=>$profit_role,
                            "parent_userid"=>$parent_userid,
                        ],
                ]);

            return ['code'=>"200"];
            
        }

        return ['code'=>"200"];
    }
    
}