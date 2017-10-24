<?php
// +----------------------------------------------------------------------
// |  [ 汇总流水 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Flow;

use app\lib\Model;

class AmoFlowModel{

	/**
	 * 现金流水
	 * @Author   zhuangqm
	 * @DateTime 2017-03-16T09:54:52+0800
	 * @return   [type]                   [description]
	 */
    public function cashflow(){

    }


    /**
     * 收益现金流水
     * @Author   zhuangqm
     * @DateTime 2017-03-16T09:55:14+0800
     * @return   [type]                   [description]
     */
    public function profitflow(){

    }

    /**
     * 牛豆流水
     * @Author   zhuangqm
     * @DateTime 2017-03-16T09:55:36+0800
     * @return   [type]                   [description]
     */
    public function bullflow(){

    }


    public function test(){
    	Model::ins("CusFlow")->add([
                    "id"=>"14",
                    "customerid"=>"14",
                    "addtime"=>date("Y-m-d H:i:s"),
                ]);

    	echo "|AmoFlow-test|";
    }
}