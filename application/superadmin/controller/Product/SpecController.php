<?php
// +----------------------------------------------------------------------
// |  [ 商品规格管理]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-15
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Product;
use app\superadmin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\form\ProSpec\ProSpecAdd;
use app\form\ProSpecValue\ProSpecValueAdd;

class SpecController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 规格列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T19:25:45+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){

        //查询
        //$where['businessid']=$this->businessid;
        
        $where = $this->searchWhere([
                "specname"=>"like"
            ],$where);

        //品牌列表
        $list = Db::Model("ProSpec")->pageList($where,'*','id desc');
        
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );

        return $this->view($viewData);
    }

    /**
     * [addSpecAction 添加规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T19:41:23+0800
     */
    public function addSpecAction(){

        $action = '/Product/Spec/doaddOreditSpec';
        //form验证token
        $formtoken = $this->Btoken('Product-Spec-addSpec');
        $viewData = array(
                "title"=>"添加规格",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editSpecAction 编辑规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T19:41:42+0800
     * @return   [type]                   [description]
     */
    public function editSpecAction(){

        $id = $this->getParam('id');

        $action = '/Product/Spec/doaddOreditSpec';
        $specData =  Db::Model('ProSpec')->getRow(['id'=>$id]);
          //form验证token
        $formtoken = $this->Btoken('Product-Brand-editSpec');
        $viewData = array(
                "title"=>"编辑品牌",
                "specData"=>$specData,
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditSpecAction 修改或添加规格操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T19:42:51+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditSpecAction(){
        
        // if($this->Ctoken()){

            $post = $this->params;
            $id = $post['id'];
            
            $this->businessid = empty($post['businessid']) ? 0 : $post['businessid'];
            // $post['class_id'] = empty($post['class_id']) ?  0 : $post['class_id']; 
            // $post['class_name'] = empty($post['class_id']) ?  0 : $post['class_name']; 
             //自动验证表单 需要修改form对应表名字段
            $ProSpecAdd = new ProSpecAdd();
          
            $post = Db::Model('ProSpec')->_facade($post);
           
            //print_r($post);
            if(!$ProSpecAdd->isValid($post)){//验证是否正确 
                $this->showError($ProSpecAdd->getErr());//提示报错信息
            }else{
               
                if(empty($id)){
                    $data = Db::Model('ProSpec')->getRow(['specname'=>$post['specname'],'businessid'=>$this->businessid],'id');
                    if(!empty($data))
                        $this->showError('规格已存在');

                    $post['businessid'] = $this->businessid;
                    $data = Db::Model('ProSpec')->insert($post);  
                }else{
                    $data = Db::Model('ProSpec')->getRow(['specname'=>$post['specname'],'businessid'=>$this->businessid,'id'=>['<>',$id]],'id');
                    if(!empty($data))
                        $this->showError('规格已存在');
                    $data = Db::Model('ProSpec')->update($post,['id'=>$id]);  
                }
                if($data > 0){
                    $this->showSuccess('操作成功');
                }else{
                    $this->showError('操作错误，请联系管理员');
                }
            }

        // }else{
        //     $this->showError('token错误，禁止操作');
        // }

    }

    /**
     * [delSpecAction 删除规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T19:42:09+0800
     * @return   [type]                   [description]
     */
    public function delSpecAction(){

        $SpecId = $this->getParam('ids');

        if(empty($SpecId)){
            $this->showError('请选择需要删除的规格');
        }
     
   
        $SpecId = explode(',', $SpecId);
        //批量删除用户
        foreach ($SpecId as $value) {

            $specData = Db::Model('ProSpec')->delete(['id'=>$value]);
          
            $specValueData = Db::Model('ProSpecValue')->delete(['spec_id'=>$value]);
        }

       $this->showSuccess('成功删除');

    }

    /**
     * [specValueAction 规格值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T20:31:22+0800
     * @return   [type]                   [description]
     */
    public function specValueAction(){

        $specId = $this->getParam('specid');

            //查询
        $where['spec_id'] = $specId;
        
        $where = $this->searchWhere([
                "spec_value_name"=>"like",
            ],$where);
       
        //品牌列表
        $list = Db::Model("ProSpecValue")->pageList($where,'*','id desc');

        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                "specId"=>$specId,
                "urlPath"=>"?specid=".$specId
            );

        return $this->view($viewData);
    }

    /**
     * [addSpecValueAction 添加规格值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T20:53:01+0800
     */
    public function addSpecValueAction(){
        $specId = $this->getParam('specid');
        $specData = Db::Model('ProSpec')->getRow(['id'=>$specId]);
        if(empty($specData))
            $this->showError('不存在的规格');

        $action = '/Product/Spec/doaddOreditSpecValue';
        //form验证token
        $formtoken = $this->Btoken('Product-Spec-addSpecVlue');
        $viewData = array(
                "title"=>"添加规格",
                'formtoken'=>$formtoken,
                "specId"=>$specId,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editSepcValueAction 编辑规格值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T20:54:52+0800
     * @return   [type]                   [description]
     */
    public function editSpecValueAction(){
        
        $specId = $this->getParam('specid');
        $specData = Db::Model('ProSpec')->getRow(['id'=>$specId]);
        if(empty($specData))
            $this->showError('不存在的规格');

        $id = $this->getParam('id');

        $action = '/Product/Spec/doaddOreditSpecValue';
        $specValueData =  Db::Model('ProSpecValue')->getRow(['id'=>$id]);
          //form验证token
        $formtoken = $this->Btoken('Product-Brand-editSepcValue');
        $viewData = array(
                "title"=>"编辑品牌",
                "specValueData"=>$specValueData,
                'formtoken'=>$formtoken,
                "specId"=>$specId,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditSpecValueAction 修改或添加规格值操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T20:56:28+0800
     * @return   [type]                   [description]
     */
    public function doaddOreditSpecValueAction(){


        // if($this->Ctoken()){

            $post = $this->params;
            $id = $post['id'];
           
          
             //自动验证表单 需要修改form对应表名字段
            $ProSpecValueAdd = new ProSpecValueAdd();
          
            $post = Db::Model('ProSpecValue')->_facade($post);
           
            //print_r($post);
            if(!$ProSpecValueAdd->isValid($post)){//验证是否正确 
                $this->showError($ProSpecValueAdd->getErr());//提示报错信息
            }else{
                
                if(empty($id)){
                    $hasDdata =  Db::Model('ProSpecValue')->getRow(['spec_value_name'=>$post['spec_value_name']],'id');
                    if(!empty($hasDdata))
                        $this->showError('规格值已存在');
                    $data = Db::Model('ProSpecValue')->insert($post);  
                }else{
                    $hasDdata =  Db::Model('ProSpecValue')->getRow(['spec_value_name'=>$post['spec_value_name'],'id'=>['<>',$id]],'id');
                    if(!empty($hasDdata))
                        $this->showError('规格值已存在');
                    $data = Db::Model('ProSpecValue')->update($post,['id'=>$id]);  
                }
                if($data > 0){
                    $this->showSuccess('操作成功');
                }else{
                    $this->showError('操作错误，请联系管理员');
                }
            }

        // }else{
        //     $this->showError('token错误，禁止操作');
        // }

    }

    /**
     * [delSpecValueAction 删除规格值]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-15T21:00:50+0800
     * @return   [type]                   [description]
     */
    public function delSpecValueAction(){

        $SpecValueId = $this->getParam('ids');

        if(empty($SpecValueId)){
            $this->showError('请选择需要删除的规格');
        }
     
   
        $SpecValueId = explode(',', $SpecValueId);
        //批量删除用户
        foreach ($SpecValueId as $value) {
          
            $specValueData = Db::Model('ProSpecValue')->delete(['id'=>$value]);
        }

       $this->showSuccess('成功删除');
    }


}