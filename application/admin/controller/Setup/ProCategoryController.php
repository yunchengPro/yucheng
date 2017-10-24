<?php
// +----------------------------------------------------------------------
// |  [ 分类管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-4-27 17:24:16}}
// +----------------------------------------------------------------------

namespace app\admin\controller\Setup;
use app\admin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\model\Sys\CategoryModel;

class ProCategoryController extends ActionController{


   /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


     //获取省数据
    public function getCategoryAction(){
        $categoryid = $this->getParam('categoryid');
        $DbTable_syscate = new CategoryModel();
        $cate = $DbTable_syscate->getCategory();

        $html = "<option value=''>选择分类</option>";
        foreach($cate as $key=>$value){
            $html .= "<option value='".$key."'".($key==$categoryid?" selected":"").">".$value['name']."</option>"; 
        }

        echo $html;
        exit;
    }


    public function getCategorynoAction(){
        $categoryid = $this->getParam('categoryid');
        $DbTable_syscate = new CategoryModel();
        $cate = $DbTable_syscate->getCategoryno();

        $html = "<option value=''>选择分类</option>";
        foreach($cate as $key=>$value){
            $html .= "<option value='".$key."'".($key==$categoryid?" selected":"").">".$value['name']."</option>"; 
        }

        echo $html;
        exit;
    }

    public function getsSonCategoryAction(){
    	$categoryid = $this->getParam('categoryid');
        $sonid  = $this->getParam('sonid');
        $DbTable_syscate = new CategoryModel();
        $cate = $DbTable_syscate->getSonCategory($categoryid);

        $html = "<option value=''>选择分类</option>";
        foreach($cate as $key=>$value){
            $html .= "<option value='".$key."'".($key==$sonid?" selected":"").">".$value['name']."</option>"; 
        }

        echo $html;
        exit;
    }


}