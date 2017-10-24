<?php
// +----------------------------------------------------------------------
// |  [ 产品类型控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------

namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\model\Business\BusinessCategoryModel;
use app\form\BusBusinessCategory\BusBusinessCategoryAdd;

class ModuleController extends ActionController{

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
        
//         print_r($viewData);
//         exit;

        return $this->view($viewData);

    }

    /**
     * [addModuleAction 添加商品类型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T09:45:41+0800
     */
    public function addModuleAction(){
       
        //获取分类数据
        $category = BusinessCategoryModel::formart_category($this->businessid);
        $categoryArr = [];
   
        
        foreach ($category as $key => $value) {

            $categoryArr[$value['id']] = $value['_category_name'];
        }

        $where['businessid'] = $this->businessid;

        //规格数据
        $specData = Model::ins('ProSpec')->getList($where,'id,specname');

        //属性数据
        $attrData = Model::ins('ProAttribute')->getList('1=1','id,attr_name');

        $action = '/Product/Module/doaddOreditModule';

        $viewData = [
            'categoryArr' => $categoryArr,
            'specData' => $specData, 
            'attrData' => $attrData,
            'action'   => $action
        ];
        return $this->view($viewData);
        
    }

    /**
     * [editModuleAction 编辑商品类型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T09:46:17+0800
     * @return   [type]                   [description]
     */
    public function editModuleAction(){
        $id = $this->getParam('id');
        //获取分类数据
        $category = BusinessCategoryModel::formart_category($this->businessid);
        $categoryArr = [];
        
        foreach ($category as $key => $value) {

            $categoryArr[$value['id']] = $value['_category_name'];
        }

        //当前类型数据
        $moduleData = Model::ins('ProModule')->getRow(['id'=>$id]);

        //关联的分类数据
        $relationCate = Model::ins('ProModuleRelation')->getList(['module_id'=>$id,"type"=>3],"id,module_id,obj_id");
        $relationCateData = [];
       
        foreach ($relationCate as $key => $value) {
           $cateData = Model::ins('BusBusinessCategory')->getRow(['id'=>$value['obj_id']],'id');
           $relationCateData[] = $cateData;
        }
     
        
        foreach ($relationCateData as $key => $value) {
           $relationCateDataId[] = $value['id'];
        }
       
        //关联规格数据
        $relationSpec = Model::ins('ProModuleRelation')->getList(['module_id'=>$id,"type"=>1],"id,module_id,obj_id");
        $specData = [];

        foreach ($relationSpec as $key => $value) {
            $relationSpecData = Model::ins('ProSpec')->getRow(['id'=>$value['obj_id']],'id,specname');
            $specData[] = $relationSpecData;
        }
      

        //关联属性数据
        $relationAttr = Model::ins('ProModuleRelation')->getList(['module_id'=>$id,"type"=>2],"id,module_id,obj_id");
        $attrData = [];

        foreach ($relationAttr as $key => $value) {
            $relationAttrData = Model::ins('ProAttribute')->getRow(['id'=>$value['obj_id']],'id,attr_name');
            $attrData[] = $relationAttrData;
        }
       

        $action = '/Product/Module/doaddOreditModule';

        $viewData = [
            'categoryArr' => $categoryArr,
            'specData' => $specData, 
            'attrData' => $attrData,
            'moduleData' => $moduleData,
            'relationCateDataId' => $relationCateDataId,
            'action'   => $action
        ];

        return $this->view($viewData);
    }



}