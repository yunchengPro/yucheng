<?php
// +----------------------------------------------------------------------
// |  [ 商品品牌管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-15
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

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

           Model::ins('ProBrand')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
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

            $post['businessid'] = !empty($this->businessid) ? $this->businessid : 0; 
            $post = Model::ins('ProBrand')->_facade($post);
           // print_r($post);

            //print_r($post);
            if(!$ProBrandAdd->isValid($post)){//验证是否正确 
                $this->showError($ProBrandAdd->getErr());//提示报错信息
            }else{
                
                if(empty($id)){
                    $hadName = Model::ins('ProBrand')->getRow(["brandname"=>$post['brandname'],'isdelete'=>0],'id');
                    if(empty($hadName)){
                        $data = Model::ins('ProBrand')->insert($post);
                    }else{
                        $this->showError($post['brandname'].'品牌已存在');
                    }
                }else{
                    $hadName = Model::ins('ProBrand')->getRow(["brandname"=>$post['brandname'],'id'=>['<>',$id]],'id');
                    if(empty($hadName)){
                        $data = Model::ins('ProBrand')->update($post,['id'=>$id]);  
                    }else{
                        $this->showError($post['brandname'].'品牌已存在');
                    }
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
                'isdelete'=> -1
            );
        $brandId = explode(',', $brandId);
        
        $i = 0;
        $msg = '';
        //批量删除用户
        foreach ($brandId as $value) {
            $proData = Model::ins('ProProduct')->getRow(['brandid'=>$value],'id');

            if(empty($proData)){
                $brandData = Model::ins('ProBrand')->update($updateData,['id'=>$value]);
                $i ++;
            }else{
                $brandData = Model::ins('ProBrand')->getRow(['id'=>$value],'brandname');
                $msg .= $brandData['brandname'] . "&nbsp;|&nbsp;";
            }
        }

        if(!empty($msg)){
            $msg = '其中'.$msg.'品牌下有商品存在不能删除';
        }


        $this->showSuccess('成功删除'.$i.'条记录'.$msg);
	}

	
}