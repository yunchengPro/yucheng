<?php
// +----------------------------------------------------------------------
// |  [ 商品分类管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-13
// +----------------------------------------------------------------------

namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\model\Product\ProductModel;
use app\form\ProCategoty\ProCategotyAdd;

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
        $list = ProductModel::formart_category($this->businessid);

        //print_r($list);
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

        $action = '/Product/Category/doAddCategory';

        $category = ProductModel::returnSelectTopCate();

        $viewData = array(
                "title"=>"添加商品分类",
                "category"=>$category,
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
        $category = ProductModel::returnSelectTopCate($id);
        
        //商城分类模型
        $ProCategory = Model::ins("ProCategory");

        //分类管理
        $localCategory =  $ProCategory->getRow(['id'=>$id]);

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
       

        $id = $post['id'];
        $post = Model::ins('ProCategory')->_facade($post);

        //自动验证表单 需要修改form对应表名
        $ProCategotyAdd = new ProCategotyAdd();

        if(!$ProCategotyAdd->isValid($post)){//验证是否正确 
            $this->showError($ProCategotyAdd->getErr());//提示报错信息
        }else{
            //商城分类模型
            $ProCategory = Model::ins('ProCategory');
            
            if(empty($id)){
                $cateName = $ProCategory->getRow(['name'=>$post['name']],'id');
                
                if(empty($cateName)){
                    $data = $ProCategory->insert($post);  
                }else{
                    $this->showError($post['name'].'已存在');
                }
            }else{

                $cateName = $ProCategory->getRow(['name'=>$post['name'],'id'=>['<>',$id]],'id');
                if(empty($cateName)){
                    if($post['parent_id'] == $id)
                        $this->showError('不能选择自己为上级分类');
                    $data = $ProCategory->update($post,['id'=>$id]);
                }else{
                    $this->showError($post['name'].'已存在');
                }
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
        $ProCategory = Model::ins('ProCategory');

        $updateData = array(
                'status'=> -1
            );
        $cateId = explode(',', $cateId);
        //批量删除用户
        foreach ($cateId as $value) {
            $gData = Model::ins('ProProduct')->getRow(['categoryid'=>$value],'id');

            if(!empty($gData))
                $this->showError('当前分类下有商品不能删除');

            $sonCate = $ProCategory->getList(['parent_id'=>$value,'status'=>[">=",0]],'id');
            if(empty($sonCate)){
                $cateData = $ProCategory->update($updateData,['id'=>$value]);
            }else{
                // $cids = '';
                // foreach ($sonCate as $sonk => $sonv) {
                //    if(!empty($sonv['id'])){
                //         $cids .= $sonv['id'].',';
                //    }
                // }
                // $cids = rtrim($cids,',')
                // $gData = Model::ins('ProProduct')->getList(['categoryid'=>['in',$cids]],'id');
               
                // if(!empty($gData))
                //     $this->showError('当前分类下有商品不能删除');

                $this->showError('所选分类下有子分类，请先删除子分类');
            }
        }

        $this->showSuccess('成功删除');
    }

}