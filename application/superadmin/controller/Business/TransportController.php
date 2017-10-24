<?php
// +----------------------------------------------------------------------
// |  [ 商品品牌管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-15
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Business;
use app\superadmin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
//use app\form\ProBrand\ProBrandAdd;
//use app\form\ProBrandProBrandAdd;

class TransportController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

	/**
	 * [indexAction 运费模板列表]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017-03-25T10:47:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
        
	    $where = array();
	    $where = $this->searchWhere([
	        "title"=>"like"
	    ],$where);
	    //var_dump($where); exit();
       
        //运费模板列表
        //$list = Model::ins("OrdTransport")->pageList(['isdelete'=>['>=',0]],'*','id desc');
        $list = Model::ins("OrdTransport")->pageList($where,'*','id desc');
        //var_dump($list); exit();
        
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
	public function addTransportAction(){

        $action = '/Business/Transport/doaddOreditTransport';
        //var_dump($action); exit();
        
        //form验证token
        $formtoken = $this->Btoken('Business-Transport-addTransport');
        $viewData = array(
                "title"=>"添加运费模板",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
	}

    /**
     * [editTransportExtendAction 运费模板配置参数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-25T14:29:31+0800
     * @return   [type]                   [description]
     */
    public function editTransportExtendAction(){

        $tid = $this->getParam('tid');
        $info = Model::ins('OrdTransport')->getRow(['id'=>$tid]);

        //print_r($info);
        $transport_extend = Model::ins('OrdTransportExtend')->getList(['transport_id'=>$info['id'],'business_id'=>$this->businessid]);

        $action = '/';

        $viewData = [
            'action'=>$action,
            'info'=>$info,
            'transport_extend'=>$transport_extend
        ];
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
                    $this->showSuccess('添加成功');
                }else{
                    $this->showError('添加错误，请联系管理员');
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