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
        //$where['businessid'] = $this->businessid;

        $where = $this->searchWhere([
                "modulename"=>"like"
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

    /**
     * [doaddOreditModuleAction 添加编辑商品类型操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T09:47:39+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditModuleAction(){
        $post = $this->params;
        $id = $post['id'];
        $this->businessid = empty($post['businessid']) ? 0 : $post['businessid'];
        //产品类型规格属性关系表 1类型与规格关系2类型与属性关系3类型与分类关系
        $ProModuleRelation = Model::ins('ProModuleRelation');

        //产品类型表
        $ProModule = Model::ins('ProModule');
        if(empty($post['modulename']))
            $this->showError('属性名称不能为空');
        if(empty($id)){

            $moduleData = $ProModule->getRow(['modulename'=>$post['modulename'],'businessid'=>$this->businessid]);

            if(!empty($moduleData))
                $this->showError('属性名已存在');

            $addModuleData = ['modulename'=>$post['modulename'],'businessid'=>$this->businessid];
            $data = $ProModule->insert($addModuleData);

            if($data > 0){
                // //关联分类
                // $categoryRelation = $post['categoryid'];

                // foreach ($categoryRelation as $key => $value) {
                //     $cateRelation = $ProModuleRelation->insert(['module_id'=>$data,'obj_id'=>$value,'type'=>3]);
                // }

                //关联规格
                $specRelation = $post['spec_type'];

                foreach ($specRelation as $key => $value) {
                   $cateRelation = $ProModuleRelation->insert(['module_id'=>$data,'obj_id'=>$value,'type'=>1]);
                }

                //关联属性
                // $attrRelation = $post['attr_type'];

                // foreach ($attrRelation as $key => $value) {
                //    $cateRelation = $ProModuleRelation->insert(['module_id'=>$data,'obj_id'=>$value,'type'=>2]);
                // }
            }

        }else{
          
            $updateModuleData = ['modulename'=>$post['modulename']];

            $data = $ProModule->update($updateModuleData,['id'=>$id]);
            
            // if($data > 0){
               
                //关联分类
                // $categoryRelation = $post['categoryid'];

                // $ProModuleRelation->delete(['type'=>3,'module_id'=>$id]);
              
                // if(!empty($categoryRelation) && is_array($categoryRelation)){
                //     foreach ($categoryRelation as $key => $value) {
                //         if(!empty($value))
                //             $cateRelation = $ProModuleRelation->insert(['module_id'=>$id,'obj_id'=>$value,'type'=>3]);
                //     }
                // }

                //关联规格
                $specRelation = $post['spec_type'];
                $ProModuleRelation->delete(['type'=>1,'module_id'=>$id]);

                if(!empty($specRelation) && is_array($specRelation)){
                    foreach ($specRelation as $key => $value) {
                       if(!empty($value))
                            $cateRelation = $ProModuleRelation->insert(['module_id'=>$id,'obj_id'=>$value,'type'=>1]);
                    }
                }
               
                //关联属性
                // $attrRelation = $post['attr_type'];
                // $ProModuleRelation->delete(['type'=>2,'module_id'=>$id]);

                // if(!empty($attrRelation) && is_array($attrRelation)){
                //     foreach ($attrRelation as $key => $value) {
                //         if(!empty($value))
                //             $cateRelation = $ProModuleRelation->insert(['module_id'=>$id,'obj_id'=>$value,'type'=>2]);
                //     }
                // }
             
                
            // }
        }
        $this->showSuccess('操作成功');
    }

    /**
     * [delModuleAction 删除商品类型]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T09:49:34+0800
     * @return   [type]                   [description]
     */
    public function delModuleAction(){

        $moduleId = $this->getParam('ids');

        if(empty($moduleId)){
            $this->showError('请选择需要删除的类型');
        }

    

        $updateData = array(
                'is_delete'=> -1
            );
        $moduleId = explode(',', $moduleId);
        //批量删除用户
        foreach ($moduleId as $value) {
            $proData = Model::ins('ProProduct')->getRow(['moduleid'=>$value],'id');
            if(!empty($proData))
                $this->showError('当前类型下有商品数据，请先删除商品');
            Model::ins('ProModuleRelation')->delete(['module_id'=>$value]);
            $moduelData = Model::ins('ProModule')->delete(['id'=>$value]);
            

        }

        $this->showSuccess('成功删除');
    }

    /**
     * [chose_produc_spec 选择规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T17:00:56+0800
     * @return   [type]                   [description]
     */
    public function chose_produc_specAction(){
        
        //$where['businessid'] = $this->businessid;

        $Spec = Model::ins('ProSpec');

        $where = $this->searchWhere(array(
                "specname"=>"like"
            ),$where);
       
        $field = "id,specname";
       
        //获取列表数据
        $list = $Spec->pagelist($where,$field,"sort asc");
        
        //print_r($pagelist);
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );
        return $this->view($viewData);
    }

    /**
     * [chose_produc_attr 选择属性]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T17:01:09+0800
     * @return   [type]                   [description]
     */
    public function chose_produc_attrAction(){
        $Attribute = Model::ins('ProAttribute');
        $where['businessid'] = $this->businessid;
        $where = $this->searchWhere(array(
                "attr_name"=>"like"
            ),$where);
       
        $field = "id,attr_name";
        //获取列表数据
        $list = $Attribute->pagelist($where,$field,"sort asc");
        
        //print_r($pagelist);
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );
        return $this->view($viewData);
    }

}