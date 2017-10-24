<?php
// +----------------------------------------------------------------------
// |  [ 资讯分类管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月28日18:17:25}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Article;
use app\superadmin\ActionController;

use app\lib\Db;
use app\lib\Model;

class CategoryController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [listAction 资讯分类管理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T18:18:37+0800
     * @return   [type]                   [description]
     */
    public function listAction(){

        $type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('分类类型不存在');
        }
        $where['isdelete'] = 0;
        $where['catetype']= $type;
        $where = $this->searchWhere([
                "categoryname"=>"like",
                "catetype"=>"=",
                "enable" => "=",
                "addtime" => "times",
            ],$where);

    	
        $type_info = [
            '1'=>'牛品分类',
            '2'=>'牛店分类'
        ];
        $enable_arr = [
            '-1'=>'禁用',
            '1'=>'启用'
        ];
       
        //资讯分类列表 
    	$catelist = Model::ins('ArtCategory')->getList($where,'id,categoryname,catetype,enable,sort','sort asc,addtime desc');
        
        foreach ($catelist as $key => $value) {
          
            $catelist[$key]['catetype'] = $type_info[$value['catetype']];
        }

        $viewData = [
            'title'=>'资讯分类管理',
            'pagelist'=>$catelist,
            'enable_arr'=>$enable_arr,
            'type_info' =>$type_info,
            'type' => $type
        ];
        return $this->view($viewData);
    }

    /**
     * [sortAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T18:45:10+0800
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

           Model::ins('ArtCategory')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

    /**
     * [addCategoryAction 添加分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T18:57:15+0800
     */
    public function addCategoryAction(){
        $type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('分类类型不存在');
        }
        $enable_arr = [
            '-1'=>'禁用',
            '1'=>'启用'
        ];
        //form验证token
        $formtoken = $this->Btoken('Article-Category-addCategory');
        $viewData = [
            'title' =>'添加分类',
            'action' => '/Article/Category/doaddoreditCategory',
            'type' => $type,
            'enable_arr'=>$enable_arr,
            'formtoken'=>$formtoken
        ];
        return $this->view($viewData);
    }

    /**
     * [editCategoryAction 修改分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T19:43:07+0800
     * @return   [type]                   [description]
     */
    public function editCategoryAction(){

        $id = $this->getParam('id');
        if($id =='')
            $this->showError('请选择需要修改的分类');
        
        $type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('分类类型不存在');
        }
        $where['id'] = $id;
        $where['catetype'] = $type;
        
        $Category = Model::ins('ArtCategory')->getRow($where,'id,categoryname,category_icon,sort,catetype,isdelete,enable');
       
        if(empty($Category) || $Category['isdelete'] == 1)
            $this->showError('分类不存在，或已被删除');
        $enable_arr = [
            '-1'=>'禁用',
            '1'=>'启用'
        ];
        //form验证token
        $formtoken = $this->Btoken('Article-Category-editCategory');
        $viewData = [
            'title' =>'修改分类',
            'action' => '/Article/Category/doaddoreditCategory',
            'type' => $type,
            'enable_arr'=>$enable_arr,
            'Category'=>$Category,
            'formtoken'=>$formtoken
        ];
        return $this->view($viewData);
    }

    /**
     * [doaddoreditCategoryAction 添加或修改资讯分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T19:05:35+0800
     * @return   [type]                   [description]
     */
    public function doaddoreditCategoryAction(){
        $type = $this->getParam('catetype');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('分类类型不存在');
        }

        if($this->Ctoken()){
            $post = $this->params;
            $id = $post['id'];

            if(empty($post['categoryname']))
                $this->showError('分类名称不能为空');
            if(empty($post['catetype']))
                $this->showError('分类类型不能为空');
            if(empty($post['enable']))
                $this->showError('请选择启用状态');

            $addCate = [
                'categoryname' =>$post['categoryname'],
                'sort' => empty($post['sort']) ? 0 : $post['sort'],
                'category_icon'=>$post['category_icon'],
                'catetype' => $type,
                'enable'=> $post['enable']
            ];

            if(empty($id)){
                
                $cateData = Model::ins('ArtCategory')->getRow(['categoryname'=>$post['categoryname'],'catetype'=>$type],'id');
                if(!empty($cateData))
                    $this->showError('此分类已添加');

                $addCate['addtime'] = date('Y-m-d H:i:s'); 
               
                $ret = Model::ins('ArtCategory')->insert($addCate);
            }else{
                $editCateData = Model::ins('ArtCategory')->getRow(['id'=>$id],'id,categoryname');
                if(empty($editCateData))
                    $this->showError('分类信息不存在');
                $hasData = Model::ins('ArtCategory')->getRow(['categoryname'=>$post['categoryname'],'catetype'=>$type,'id'=>['<>',$id]],'id');
               
                if(!empty($hasData))
                    $this->showError('此分类已存在');

                $ret = Model::ins('ArtCategory')->update($addCate,['id'=>$id]);
            }
          
            if($ret > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showSuccess('操作有误，请重新提交');
            }

        }else{
            $this->showError('token错误，禁止操作');
        }
    }

    /**
     * [setenbaleAction 启用禁用]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T20:01:38+0800
     * @return   [type]                   [description]
     */
    public function setenbaleAction(){
        $id = $this->getParam('id');
        if($id =='')
            $this->showError('请选择需要修改的分类');
        $enable = $this->getParam('enable');
        $enable_arr = [-1,1];
        if(!in_array($enable, $enable_arr))
            $this->showError('禁用状态错误');
        $type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('分类类型不存在');
        }
        $where['id']=$id;
        $where['catetype']=$type;
        $Category = Model::ins('ArtCategory')->getRow($where,'id,isdelete');
       
        if(empty($Category) || $Category['isdelete'] == 1)
            $this->showError('分类不存在，或已被删除');
        $update = [
            'enable' => $enable
        ];
        if($enable == -1){
            $ArticleCont = Model::ins('ArtArticle')->getRow(['categoryid'=>$id,'isdelete'=>0],'count(*) as count');
            
            if($ArticleCont['count'] > 0)
                $this->showError('当前分类下还有资讯不能禁用');
        }
        $ret = Model::ins('ArtCategory')->update($update,$where);
        if($ret > 0){
            $this->showSuccess('操作成功');
        }else{
            $this->showError('操作有误，请重新提交');
        }
    }

    /**
     * [delCategoryAction 删除分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-28T20:21:38+0800
     * @return   [type]                   [description]
     */
    public function delCategoryAction(){
        $ids = $this->getParam('ids');
        if($ids =='')
            $this->showError('请选择需要修改的分类');
      
        $type = $this->getParam('type');
        $type_arr = [1,2];
        if(!in_array($type, $type_arr)){
            $this->showError('分类类型不存在');
        }

        $where['id']=$ids;
        $where['catetype']=$type;
        $Category = Model::ins('ArtCategory')->getRow($where,'id,isdelete');
        
        $ArticleCont = Model::ins('ArtArticle')->getRow(['categoryid'=>$ids,'isdelete'=>0],'count(*) as count');
        
        if($ArticleCont['count'] > 0)
            $this->showError('当前分类下还有资讯不能删除');

        if(empty($Category) || $Category['isdelete'] == 1)
            $this->showError('分类不存在，或已被删除');
        $update = [
            'isdelete' => 1
        ];
        $ret = Model::ins('ArtCategory')->update($update,$where);
        if($ret > 0){
            $this->showSuccess('操作成功');
        }else{
            $this->showError('操作有误，请重新提交');
        }
    }
}