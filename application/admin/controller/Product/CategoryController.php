<?php
// +----------------------------------------------------------------------
// |  [ 商品分类管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-13
// +----------------------------------------------------------------------

namespace app\admin\controller\Product;
use app\admin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\model\Business\BusinessCategoryModel;
use app\form\BusBusinessCategory\BusBusinessCategoryAdd;

class CategoryController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

	/**
	 * [indexAction description]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-13T21:12:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){


        //获取列表数据
        $list = BusinessCategoryModel::formart_category($this->businessid);

        //print_r($pagelist);
        $viewData = array(
        		"title"=>"分类管理",
                "pagelist"=>$list, //列表数据
            );

        //$this->test($name);

        return $this->view($viewData);
	}

    /**
     * [addCategoryAction 添加商品分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T16:29:17+0800
     */
    public function addCategoryAction(){

        $topid = $this->getParam('topid');
        $localCategory['parent_id'] = $topid ;
        $action = '/Product/Category/doAddCategory';

        $category = BusinessCategoryModel::returnSelectTopCate($this->businessid);

        $viewData = array(
                "title"=>"添加商品分类",
                "category"=>$category,
                'localCategory'=>$localCategory,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editCategoryAction 编辑商品分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T20:19:08+0800
     * @return   [type]                   [description]
     */
    public function editCategoryAction(){

        $id = $this->getParam('id');
        $action = '/Product/Category/doAddCategory';
        $category = BusinessCategoryModel::returnSelectTopCate($this->businessid,$id);
        
        //商城分类模型
        $BusBusinessCategory = Model::ins('BusBusinessCategory');

        //分类管理
        $localCategory =  $BusBusinessCategory->getRow(['id'=>$id]);

        $viewData = array(
                "title"=>"添加商品分类",
                "category"=>$category,
                "localCategory"=>$localCategory,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doAddCategoryAction 添加或编辑商品分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T20:17:37+0800
     * @return   [type]                   [description]
     */
    public function doAddCategoryAction(){

        $post = $this->params;

        if(empty($post['parent_id']))
            $post['parent_id'] = 0;
        $post['businessid'] = intval($this->businessid);

        $id = $post['id'];
        $post = Model::ins('BusBusinessCategory')->_facade($post);

        //自动验证表单 需要修改form对应表名
        $BusBusinessCategoryAdd = new BusBusinessCategoryAdd();

        if(!$BusBusinessCategoryAdd->isValid($post)){//验证是否正确 
            $this->showError($BusBusinessCategoryAdd->getErr());//提示报错信息
        }else{
            //商城分类模型
            $BusBusinessCategory = Model::ins('BusBusinessCategory');
            
            if(empty($id)){
                $categoryData = $BusBusinessCategory->getRow(['category_name'=>$post['category_name'],'businessid'=>$this->businessid,'is_delete'=>['<>',-1]],'id');
                if(!empty($categoryData))
                    $this->showError('分类名称已存在');
                $data = $BusBusinessCategory->insert($post);  
            }else{
                $categoryData = $BusBusinessCategory->getRow(['category_name'=>$post['category_name'],'businessid'=>$this->businessid,'id'=>['<>',$id],'is_delete'=>['<>',-1]],'id');
                if(!empty($categoryData))
                    $this->showError('分类名称已存在');
                $data = $BusBusinessCategory->update($post,['id'=>$id]);  
            }

            if($data > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showError('操作错误，请联系管理员');
            }
           
        }
      
    }

	/**
     * [delCategoryAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-13T11:37:06+0800
     * @return   [type]                   [description]
     */
    public function delCategoryAction(){

        $cateId = $this->getParam('ids');
   

        if(empty($cateId)){
            $this->showError('请选择需要删除的分类');
        }

        //商城分类模型
        $BusBusinessCategory = Model::ins('BusBusinessCategory');

        $updateData = array(
                'is_delete'=> -1
            );
        $cateId = explode(',', $cateId);
        //批量删除用户
        foreach ($cateId as $value) {
            $sonCate = $BusBusinessCategory->getRow(['parent_id'=>$value,'is_delete'=>['<>',-1]],'id');
            if(empty($sonCate)){
                $cateData = $BusBusinessCategory->update($updateData,['id'=>$value]);
            }else{
                $this->showError('所选分类下有子分类，请先删除子分类');
            }
        }

        $this->showSuccess('成功删除');
    }

}