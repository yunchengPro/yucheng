<?php
// +----------------------------------------------------------------------
// |  [ 商品品牌管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-15
// +----------------------------------------------------------------------
namespace app\admin\controller\Product;
use app\admin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\form\ProBrand\ProBrandAdd;
use app\form\ProBrandProBrandAdd;

class BrandController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

	/**
	 * [indexAction 品牌列表]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-13T21:12:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
        
        $where['isdelete'] = ['>=',0];
        $where['businessid'] = $this->businessid;
        $where = $this->searchWhere([
                "brandname"=>"like",
            ],$where);
        //品牌列表
        $list = Model::ins("ProBrand")->pageList($where,'*','id desc');
        
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );

        return $this->view($viewData);
	}

	/**
	 * [addBrandAction 添加品牌]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-15T14:19:59+0800
	 */
	public function addBrandAction(){

        $action = '/Product/Brand/doaddOreditBrand';
        //form验证token
        $formtoken = $this->Btoken('Product-Brand-addbrand');
        $viewData = array(
                "title"=>"添加品牌",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
	}

    /**
     * [editBrandAction 编辑品牌]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T14:57:56+0800
     * @return   [type]                   [description]
     */
    public function editBrandAction(){

        $id = $this->getParam('id');

        $action = '/Product/Brand/doaddOreditBrand';
        $brandData =  Model::ins('ProBrand')->getRow(['id'=>$id]);
          //form验证token
        $formtoken = $this->Btoken('Product-Brand-editBrand');
        $viewData = array(
                "title"=>"编辑品牌",
                "brandData"=>$brandData,
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditBrandAction 添加或修改品牌信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T15:01:53+0800
     * @return   [type]                   [description]
     */
	public function doaddOreditBrandAction(){
     

        if($this->Ctoken()){

            $post = $this->params;
            $id = $post['id'];
             //自动验证表单 需要修改form对应表名
            $ProBrandAdd = new ProBrandAdd();

            $post['businessid'] = $this->businessid; 
            $post = Model::ins('ProBrand')->_facade($post);
           
            //print_r($post);
            if(!$ProBrandAdd->isValid($post)){//验证是否正确 
                $this->showError($ProBrandAdd->getErr());//提示报错信息
            }else{
                
                if(empty($id)){
                    $data = Model::ins('ProBrand')->insert($post);  
                }else{
                    $data = Model::ins('ProBrand')->update($post,['id'=>$id]);  
                }
                if($data > 0){
                    $this->showSuccess('操作成功');
                }else{
                    $this->showError('操作错误，请联系管理员');
                }
            }

        }else{
            $this->showError('token错误，禁止操作');
        }
	}


	/**
	 * [delBrandAction 删除品牌]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-15T14:16:26+0800
	 * @return   [type]                   [description]
	 */
	public function delBrandAction(){

        $brandId = $this->getParam('ids');
        
        if(empty($brandId)){
            $this->showError('请选择需要删除的品牌');
        }

    

        $updateData = array(
                'is_delete'=> -1
            );
        $brandId = explode(',', $brandId);
        //批量删除用户
        foreach ($brandId as $value) {

            $brandData = Model::ins('ProBrand')->update($updateData,['id'=>$value]);

        }

        $this->showSuccess('成功删除');
	}

	
}