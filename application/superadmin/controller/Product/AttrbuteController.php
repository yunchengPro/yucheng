<?php
// +----------------------------------------------------------------------
// |  [ 产品属性管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-16
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

use \think\Config;

use app\lib\Db;
use app\lib\Model;

class AttrbuteController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 模型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T21:21:02+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){

        //查询
        $where['isdelete']=['>=',0];
        
        $where = $this->searchWhere([
                "modulename"=>"="
            ],$where);

        //品牌列表 
        $list = Model::ins("ProModule")->pageList($where,'*','id desc');
        
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );

        return $this->view($viewData);

    }

    
}