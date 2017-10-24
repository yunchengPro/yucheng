<?php
// +----------------------------------------------------------------------
// |  [ 用户余额 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use app\lib\Model;

class ComUserModel{

    /**
     * 获取新增的公司用户
     * @Author   zhuangqm
     * @DateTime 2017-08-17T14:16:58+0800
     * @return   [type]                   [description]
     */
    public function getComUser(){

        return [
            "-2"=>[
                "name"=>"牛店全国运营公司",
                "profitname"=>"stoOperate",
                "flowtype"=>"158",
            ],
            "-3"=>[
                "name"=>"牛品全国运营公司",
                "profitname"=>"busOperate",
                "flowtype"=>"159",
            ],
            "-4"=>[
                "name"=>"商学院",
                "profitname"=>"busCollege",
                "flowtype"=>"160",
            ],
            "-5"=>[
                "name"=>"牛人全国运营中心",
                "profitname"=>"orOperate",
                "flowtype"=>"161",
            ],
            "-6"=>[
                "name"=>"文化传媒运营中心",
                "profitname"=>"mediaOperate",
                "flowtype"=>"162",
            ],
            "-7"=>[
                "name"=>"云牛惠科技",
                "profitname"=>"technology",
                "flowtype"=>"163",
            ],
            "-8"=>[
                "name"=>"集团",
                "profitname"=>"group",
                "flowtype"=>"164",
            ],
            "-9"=>[
                "name"=>"全国区运营中心",
                "profitname"=>"countyOperate",
                "flowtype"=>"165",
            ],
            "-10"=>[
                "name"=>"全国市运营中心",
                "profitname"=>"cityOperate",
                "flowtype"=>"166",
            ],
        ];
    }
}