<?php
// +----------------------------------------------------------------------
// |  [ 菜单管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-028
// +----------------------------------------------------------------------

namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;

class MenuController extends ActionController{
	
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){

       	$menuData= Model::ins('SysMenu')->getList(['isdelete'=>0],'*','sort asc'); 
        //print_r($menuData);
        //var_dump($loginname);
        $viewData = array(
                'list'=> $menuData,
            );

        return $this->view($viewData);
    }

     /**
     * [addMenu 添加菜单表单页面]
     */
    public function addMenuAction(){

        $parentid_path = $this->getParam('parentid_path');
        $parent_id=0;
        $parantname="";

        if($parentid_path=='' || $parentid_path=='0'){
            $parantname="顶级菜单";
        }else{
            $arr = explode("_",$parentid_path);
            foreach($arr as $value){
                $menu = Model::ins("SysMenu")->getRow(array('id'=>$value),"menuname");
                $parantname.=$menu['menuname']."--";
                $parent_id = $value;
            }
            $parantname =substr($parantname,0,-2);
        }



        $viewData = array(
            'action' => '/System/Menu/saveMenu',
            'parent_id'=>$parent_id,
            'parantname'=>$parantname
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

           Model::ins('SysMenu')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

    /**
     * [editMenu 编辑菜单]
     * @return [type] [description]
     * @author [kinmos] 
     */
    public function editMenuAction(){
        $id = $this->getParam('id');

        $where = array('id'=>$id);
        $menuData = Model::ins("SysMenu")->getRow($where);
        $parantname="";
        $parent_id=0;
        if(!empty($menuData)&&!empty($menuData['parentid'])&&$menuData['parentid']!=0)
        {
            $parmenu=Model::ins('SysMenu')->getRow(array('id'=>$menuData['parentid']),'id,menuname');
            $parantname=$parmenu['menuname'];
            $parent_id = $parmenu['id'];
        }
        if($parantname=="")
        {
            $parantname="顶级菜单";
        }


        //print_r($menuData);

        $viewData = array(
            'menuData' => $menuData,
            'action' => '/System/Menu/saveMenu',
            'id'=>$id,
            'parent_id'=>$parent_id,
            'parantname'=>$parantname
            );
        //print_r($viewData);
        return $this->view($viewData);
    }

      /**
     * [delMenu 删除菜单]
     * @return [type] [description]
     */
    public function delMenuAction(){
        $id = $this->getParam('id');
        
        if(empty($id)){
            $this->showError('请选择菜单');
        }
        $updateData = array(
                'isdelete'=>1
            );
        $uids = explode(',', $id);
        //批量删除用户
        foreach ($uids as $value) {
            $userData = Model::ins("SysMenu")->update($updateData,array('id'=>$value));
        }

        $this->showSuccess('成功删除');
    }

    /**
     * [saveMenu 保存菜单]
     */
    public function saveMenuAction(){

        $post = $this->params;
        if(!empty($post)){
            $data['parentid']= empty($post['parentid']) ? 0 : $post['parentid'];
            $data['menuname']=$post['menuname'];
            $data['url']=$post['url'];
            $data['enable']=$post['enable'];
            $data['sort']=$post['sort'];
            $data['icon']=$post['icon'];
            $data['class']=$post['class'];
            $data['createby']= empty($this->session('userid')) ? 0 : $this->session('userid');

            //$data['ispage']=$post['ispage'];
            //$data['isshow']=$post['isshow'];
            $data['isdelete']=0;
            if(!isset($post['id'])||empty($post['id'])||$post['id']==0)
            {
                $data['createtime']=date('Y-m-d H:i:s');
                Model::ins('SysMenu')->insert($data);
            }
            else
            {
             
                Model::ins('SysMenu')->update($data,array('id'=>$post['id']));
            }
            $this->showSuccess("保存成功");
        }else{
            exit();
        }
    }

      /*
      菜单启用停用
    */
    public function setEnableAction(){
        $id = $this->params["id"];
        if(!empty($id)){
            try {
                $upData = array( 
                    'enable' =>$this->params["enable"],
                );
                //更新
                Model::ins("SysMenu")->update($upData,array('id'=>$id));

                $this->showSuccess('设置成功');
            }catch (Exception $e){
                $this->showError('设置失败');
            }
        }else{
            $this->showError('设置失败');
        }
    }

}