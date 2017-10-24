<?php
// +----------------------------------------------------------------------
// |  [ 商品分类管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-13
// +----------------------------------------------------------------------

namespace app\superadmin\controller\Mall;
use app\superadmin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\model\Product\ProductModel;
use app\model\StoBusiness\StoCategoryModel;

class StoCategoryController extends ActionController{

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
        $list = StoCategoryModel::formart_StoCategory();
      
        //print_r($list);
        $viewData = array(
        		"title"=>"分类管理",
                "pagelist"=>$list, //列表数据
            );

        //$this->test($name);

        return $this->view($viewData);
	}

    /**
     * [addStoCategoryAction 添加商品分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T16:29:17+0800
     */
    public function addStoCategoryAction(){

        $action = '/Mall/StoCategory/doAddStoCategory';

        $topid = $this->getParam('topid');
        $localCategory['parentid'] = $topid ;

        $StoCategory = StoCategoryModel::returnSelectTopCate();

        $viewData = array(
                "title"=>"添加商品分类",
                "StoCategory"=>$StoCategory,
                'localStoCategory'=>$localCategory,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editStoCategoryAction 编辑商品分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T20:19:08+0800
     * @return   [type]                   [description]
     */
    public function editStoCategoryAction(){

        $id = $this->getParam('id');
        $action = '/Mall/StoCategory/doAddStoCategory';
        $StoCategory = StoCategoryModel::returnSelectTopCate();
       
        //商城分类模型
        $StoCategorys = Model::ins("StoCategory");

        //分类管理
        $localStoCategory =  $StoCategorys->getRow(['id'=>$id]);

        $viewData = array(
                "title"=>"添加商品分类",
                "StoCategory"=>$StoCategory,
                "localStoCategory"=>$localStoCategory,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doAddStoCategoryAction 添加或编辑商品分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-14T20:17:37+0800
     * @return   [type]                   [description]
     */
    public function doAddStoCategoryAction(){

        $post = $this->params;

        if(empty($post['parentid']))
            $post['parentid'] = 0;
       

        $id = $post['id'];
        unset($post['file']);

            //商城分类模型
        $StoCategory = Model::ins('StoCategory');
        
        if(empty($id)){
          
            unset($post['id']);
            $data = $StoCategory->insert($post);  
        }else{
            $data = $StoCategory->update($post,['id'=>$id]);  
        }
       
        if($data > 0){
            $this->showSuccess('操作成功');
        }else{
            $this->showError('操作错误，请联系管理员');
        }
           
        
      
    }

	/**
     * [delStoCategoryAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-13T11:37:06+0800
     * @return   [type]                   [description]
     */
    public function delStoCategoryAction(){

        $cateId = $this->getParam('ids');
   

        if(empty($cateId)){
            $this->showError('请选择需要删除的分类');
        }

        //商城分类模型
        $StoCategory = Model::ins('StoCategory');

       
        $cateId = explode(',', $cateId);
        //批量删除用户
        foreach ($cateId as $value) {
            $sonCate = $StoCategory->getRow(['parentid'=>$value],'id');
            if(empty($sonCate)){
                $cateData = $StoCategory->delete(['id'=>$value]);
            }else{
                $this->showError('所选分类下有子分类，请先删除子分类');
            }
        }

        $this->showSuccess('成功删除');
    }

     /**
     * [sortAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T13:08:26+0800
     * @return   [type]                   [description]
     */
    public function sortAction(){
        $ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

           Model::ins('StoCategory')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }


}