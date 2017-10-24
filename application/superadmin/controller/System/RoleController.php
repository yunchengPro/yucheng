<?php
// +----------------------------------------------------------------------
// |  [ 角色管理  ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-4-27 20:38:37}}
// +----------------------------------------------------------------------

namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;

class RoleController extends ActionController{


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    public function indexAction(){
        
        $where = $this->searchWhere([
                "name"=>"like",
            ],$where);

       	$roleData= Model::ins('SysRole')->getList($where); 
        // print_r($roleData);
        //var_dump($loginname);
        $viewData = array(
                'pagelist'=> $roleData,
            );

        return $this->view($viewData);
    }

    /**
     * [roleuserAction 角色管理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-28T10:51:13+0800
     * @return   [type]                   [description]
     */
    public function roleuserAction(){
        
        $roleid = $this->getParam('roleid');
        if(!empty($roleid))
            $where['roleid'] = $roleid;

        $where = $this->searchWhere([
                "username"=>"like",
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysUser')->pageList($where);

        $viewData =[ 
               
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                "roleid"=>$roleid
            ];

        return $this->view($viewData);
      
    }

    /**
     * [disroleAction 移除角色]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-28T15:30:19+0800
     * @return   [type]                   [description]
     */
    public function disroleAction(){
        $roleid = $this->getParam('roleid');
        $userid = $this->getParam('userid');
        Model::ins('SysUser')->update(['roleid'=>0],['id'=>$userid]);
        $this->showSuccess('成功移除');
    }

    /**
     * [addRoleAction 添加角色]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T16:58:42+0800
     */
    public function addRoleAction(){
        $action = '/System/Role/doaddRole';
        $viewData = [
            'action' => $action
        ];
        return $this->view($viewData);
    }

    /**
     * [doaddRoleAction 添加角色]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:01:37+0800
     * @return   [type]                   [description]
     */
    public function doaddRoleAction(){
        $post = $this->params;
        
        if(empty($post['name']))
            $this->showError('角色名不能为空');

        $insert = [
            'name' => $post['name'],
            'remark' => $post['remark'],
            'enable' => $post['enable'],
            'createby'=>$this->customerid,
            'createtime' => date('Y-m-d H:i:s')
        ];
        Model::ins('SysRole')->insert($insert);
        $this->showSuccess('添加成功');
    }

    /**
     * [setMenuRole 设置菜单权限]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-28T16:17:24+0800
     */
    public function setMenuRoleAction(){

        $roleid = $this->getParam('roleid');

        $menuData= Model::ins('SysMenu')->getList(['isdelete'=>0,'enable'=>1]); 

        $roleList   = Model::ins('SysMenurole')->getList(['roleid'=>$roleid],'menuid');
        $role_arr = [];
        foreach ($roleList as $key => $value) {
             $role_arr[] = $value['menuid'];
        }

        $viewData = array(
                'roleid' => $roleid,
                'list'=> $menuData,
                'role_arr' => $role_arr
            );
        return $this->view($viewData);
    }

    /**
     * [saveMenuRoleAction 設置權限]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-29T16:01:04+0800
     * @return   [type]                   [description]
     */
    public function saveMenuRoleAction(){
            $roleid = $this->getParam('roleid');
            $menuid = $this->getParam('menuid');

            if(empty($roleid))
                return $this->json("404");

            Model::ins('SysMenurole')->delete(['roleid'=>$roleid]);

            $menuid = substr($menuid,0,1)==","?substr($menuid,1):$menuid;

            $menuarr = explode(",",$menuid);
            $addtime = date('Y-m-d');
            foreach($menuarr as $id){
                if($id>0){

                    $insert = [
                        'menuid' => $id,
                        'roleid' => $roleid,
                        'isdelete' =>0,
                        'createby' => $this->customerid,
                        'createtime' => $addtime,
                    ];
                    //Model::ins('SysMenurole')->delete(['roleid'=>$roleid]);
                    Model::ins('SysMenurole')->insert($insert);
                }
            }

            
            return $this->json("200");
    }



    public function delMenuRoleAction(){
         $roleid = $this->getParam('roleid');
         Model::ins('SysMenurole')->delete(['roleid'=>$roleid]);
         return json_encode(true);
    }

    /**
     * [choseuserAction 选择用户]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T15:30:55+0800
     * @return   [type]                   [description]
     */
    public function choseuserAction(){
        $roleid = $this->getParam('roleid');
        $where = $this->searchWhere([
                "username"=>"like",
                "mobile" => "like"
            ],$where);

        $list  = Model::ins('SysUser')->pageList($where);

        $viewData =[ 
               
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                "roleid" => $roleid
            ];

        return $this->view($viewData);

    }


    /**
     * [checkMenuRoleAction 检查用户是否存在]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T15:50:22+0800
     * @return   [type]                   [description]
     */
    // public function checkRoleAction(){
    //     $roleid = $this->getParam('roleid');
    //     $userid = $this->getParam('userid');
    //     $roleData = Model::ins('SysUser')->getRow(['roleid'=>$roleid,'userid'=>$userid]);
        
    //     if(!empty($roleData)){
    //         return json_encode(true);
    //     }else{
    //         return json_encode(false);
    //     }

    // }

    /**
     * [addRoleAction 添加角色]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T15:57:46+0800
     */
    public function setRoleAction(){
        $roleid = $this->getParam('roleid');
        $userid = $this->getParam('userid');
       
        
        if(empty($roleid) || empty($userid))
                return $this->json("404");


        $userid = substr($userid,0,1)==","?substr($userid,1):$userid;

        $useridarr = explode(",",$userid);

        foreach ($useridarr as $key => $value) {
            
            $roleData = Model::ins('SysUser')->getRow(['roleid'=>$roleid,'id'=>$value]);

            if(!empty($roleData))
                return $this->json("404",[],$roleData['username']);
            
            $userData = Model::ins('SysUser')->getRow(['id'=>$value]);

            if(!empty($userData)){
                Model::ins('SysUser')->update(['roleid'=>$roleid],['id'=>$value]);
            }
        }

        return $this->json("200");
    }


}