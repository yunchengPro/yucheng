<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 积分导入记录 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年10月13日17:04:22}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Amount;
use app\superadmin\ActionController;

use app\lib\Db;
use app\model\User\AmountModel;
use app\lib\Model;

class IntegralController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [importListAction 导入记录]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-13T16:58:57+0800
     * @return   [type]                   [description]
     */
    public function importListAction(){

        $where = array();
        $where = $this->searchWhere([
            "orderno" => "=",
        ], $where);
        
        $list = Model::ins("OpenIntegralImport")->pageList($where, "*", "id desc");

        print_r($list);

    }
}