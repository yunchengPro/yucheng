<?php
// +----------------------------------------------------------------------
// |  [ 产品属性管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-16
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

use \think\Config;

use app\lib\Db;
use app\lib\Model;
use app\form\ProAttribute\ProAttributeAdd;
use app\form\ProAttributeValue\ProAttributeValueAdd;

class AttributeController extends ActionController{

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
        $list = Model::ins("ProAttribute")->pageList($where,'*','id desc');
        
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );

        return $this->view($viewData);

    }

    /**
     * [addAttrbuteAction 添加属性]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:08:51+0800
     */
    public function addAttributeAction(){


        $action = '/Product/Attribute/doaddOreditAttribute';
        //form验证token
        $formtoken = $this->Btoken('Product-Attribute-addAttrbute');
        $viewData = array(
                "title"=>"添加规格",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);

    }

    /**
     * [editAttrbuteAction 编辑属性]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:09:24+0800
     * @return   [type]                   [description]
     */
    public function editAttributeAction(){

        $id = $this->getParam('id');

        $action = '/Product/Attribute/doaddOreditAttribute';
        $attrbuteData =  Model::ins('ProAttribute')->getRow(['id'=>$id]);

        //form验证token
        $formtoken = $this->Btoken('Product-Attribute-editAttrbute');
        $viewData = array(
                "title"=>"编辑品牌",
                "attrbuteData"=>$attrbuteData,
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditAttrbuteAction 添加或编辑属性操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:11:20+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditAttributeAction(){

        if($this->Ctoken()){

            $post = $this->params;
            $id = $post['id'];
             //自动验证表单 需要修改form对应表名字段
            $ProAttributeAdd = new ProAttributeAdd();
          
            $post = Model::ins('ProAttribute')->_facade($post);
           
            //print_r($post);
            if(!$ProAttributeAdd->isValid($post)){//验证是否正确 
                $this->showError($ProAttributeAdd->getErr());//提示报错信息
            }else{
                
                if(empty($id)){
                    $data = Model::ins('ProAttribute')->insert($post);  
                }else{
                    $data = Model::ins('ProAttribute')->update($post,['id'=>$id]);  
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
     * [delAttrbuteAction 删除属性]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:12:12+0800
     * @return   [type]                   [description]
     */
    public function delAttributeAction(){

        $attrId = $this->getParam('ids');

        if(empty($attrId)){
            $this->showError('请选择需要删除的规格');
        }
     
   
        $attrId = explode(',', $attrId);
        //批量删除用户
        foreach ($attrId as $value) {

            $attributeData = Model::ins('ProAttribute')->delete(['id'=>$value]);
          
            $attributeValueData = Model::ins('ProAttributeValue')->delete(['attr_id'=>$value]);
        }

       $this->showSuccess('成功删除');

    }

    /**
     * [attrbuteValueAction 属性值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:12:52+0800
     * @return   [type]                   [description]
     */
    public function attributeValueAction(){

        $attrId = $this->getParam('attrid');

            //查询
        $where['attr_id'] = $attrId;
        
        $where = $this->searchWhere([
                "attr_value_name"=>"="
            ],$where);

        //品牌列表
        $list = Model::ins("ProAttributeValue")->pageList($where,'*','id desc');

        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                "attrId"=>$attrId
            );

        return $this->view($viewData);
    }

    /**
     * [addAttrbuteValueAction 添加属性值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:13:39+0800
     */
    public function addAttributeValueAction(){

        $attrId = $this->getParam('attrid');
        $attrData = Model::ins('ProAttribute')->getRow(['id'=>$attrId]);
        if(empty($attrData))
            $this->showError('不存在的属性');

        $action = '/Product/Attribute/doaddOreditAttributeValue';
        //form验证token
        $formtoken = $this->Btoken('Product-Attrbute-addAttrbuteValue');
        $viewData = array(
                "title"=>"添加属性值",
                'formtoken'=>$formtoken,
                "attrId"=>$attrId,
                "action"=>$action
            );
        return $this->view($viewData);

    }

    /**
     * [editArrtbuteValueAction 编辑属性值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:14:09+0800
     * @return   [type]                   [description]
     */
    public function editAttributeValueAction(){

        $attrId = $this->getParam('attrid');
        $attrData = Model::ins('ProAttribute')->getRow(['id'=>$attrId]);
        if(empty($attrData))
            $this->showError('不存在的属性');

        $id = $this->getParam('id');

        $action = '/Product/Attribute/doaddOreditAttributeValue';
        $attrValueData =  Model::ins('ProAttributeValue')->getRow(['id'=>$id]);
          //form验证token
        $formtoken = $this->Btoken('Product-Attrbute-editAttrbuteValue');
        $viewData = array(
                "title"=>"编辑属性值",
                "attrValueData"=>$attrValueData,
                'formtoken'=>$formtoken,
                "attrId"=>$attrId,
                "action"=>$action
            );
        return $this->view($viewData);

    }

    /**
     * [doaddOreditAttrbuteValueAction 添加或编辑属性值操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:14:54+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditAttributeValueAction(){


        if($this->Ctoken()){

            $post = $this->params;
            $id = $post['id'];
           
          
             //自动验证表单 需要修改form对应表名字段
            $ProAttributeValueAdd = new ProAttributeValueAdd();
          
            $post = Model::ins('ProAttributeValue')->_facade($post);
           
            //print_r($post);
            if(!$ProAttributeValueAdd->isValid($post)){//验证是否正确 
                $this->showError($ProAttributeValueAdd->getErr());//提示报错信息
            }else{
                
                if(empty($id)){
                    $data = Model::ins('ProAttributeValue')->insert($post);  
                }else{
                    $data = Model::ins('ProAttributeValue')->update($post,['id'=>$id]);  
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
     * [delArrtbuteValueAction 删除属性值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-16T11:16:36+0800
     * @return   [type]                   [description]
     */
    public function delAttributeValueAction(){

        $attrValueId = $this->getParam('ids');

        if(empty($attrValueId)){
            $this->showError('请选择需要删除的属性值');
        }
     
   
        $attrValueId = explode(',', $attrValueId);
        //批量删除用户
        foreach ($attrValueId as $value) {
          
            $specValueData = Model::ins('ProAttributeValue')->delete(['id'=>$value]);
        }

       $this->showSuccess('成功删除');
    }
    
}