<?php
// +----------------------------------------------------------------------
// |  [ 流水 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Flow;

use app\lib\Model;

class FlowModel{

    /**
     * 支付流水
     * @Author   zhuangqm
     * @DateTime 2017-03-15T11:25:45+0800
     */
	public static function payflow(){

    }

    public function test(){
    	Model::ins("CusMtoken")->add([
                    "id"=>"1",
                    "mtoken"=>"1",
                    "addtime"=>date("Y-m-d H:i:s"),
                ]);

    	echo "|Flow-test|";
    }
}