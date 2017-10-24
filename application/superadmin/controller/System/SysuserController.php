<?php
// +----------------------------------------------------------------------
// |  [ 用户管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-3 17:32:42}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;
use app\model\BusinessLogin\LoginModel;

class SysuserController extends ActionController{



	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    public function indexAction(){
        
        
        $where['id'] = ['<>',2]; 
        $where = $this->searchWhere([
                "username"=>"like",
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysUser')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
               
            ];

        return $this->view($viewData);
    }


    /**
     * [adduserAction 添加用户]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:38:44+0800
     * @return   [type]                   [description]
     */
    public function adduserAction(){

    	$action = '/System/Sysuser/doadduser';
        $roleData = Model::ins('SysRole')->getList([],'id,name');
       
        $roleOption = [];
        foreach ($roleData as $key => $value) {
            $roleOption[$value['id']] = $value['name'];
        }
    	$viewData =[ 
            "action"=>$action, //列表数据
            'roleOption' => $roleOption
        ];

        return $this->view($viewData);
    }

    /**
     * [doadduserAction 添加用户]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:40:15+0800
     * @return   [type]                   [description]
     */
    public function doadduserAction(){
    	$post = $this->params;
        
        if(empty($post['username']))
            $this->showError('登录名不能为空');

        $post['roleid'] = empty($post['roleid']) ? 0 : $post['roleid'];

    	$insert = [
    		'mobile' => $post['mobile'],
    		'username' => $post['username'],
    		'userpwd'  => LoginModel::PwdEncode($post['userpwd']),
    		'roleid'   => $post['roleid'],
    		'createtime' => date('Y-m-d H:i:s'),
    		'enable' => $post['enable']
    	];
    	Model::ins('SysUser')->insert($insert);
    	$this->showSuccess('添加成功');
    }

    /**
     * [setenableAction 启用停用]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:49:18+0800
     * @return   [type]                   [description]
     */
    public function setenableAction(){
    	$enable = $this->getParam('enable');
    	$userid = $this->getParam('userid');
    	Model::ins('SysUser')->update(['enable'=>$enable],['id'=>$userid]);
    	$this->showSuccess('设置成功');
    }

    /**
     * [setPwdAction 修改密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:57:18+0800
     */
    public function setPwdAction(){
    	$userid = $this->getParam('userid');

    	$action = '/System/Sysuser/dosetPwd';
    	$viewData = [
    		'action' => $action,
    		'userid' => $userid
    	];
    	return $this->view($viewData);
    }

    /**
     * [setPwdAction 设置密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:53:10+0800
     */
    public function dosetPwdAction(){
    	$userid = $this->getParam('userid');
      


    	$pwd1 = $this->getParam('pwd1');
    	$pwd2 = $this->getParam('pwd2');

    	if(empty($pwd1))
    		$this->showError('设置密码不能为空');

    	if(empty($pwd2))
    		$this->showError('重复密码不能为空');

    	if($pwd1 == $pwd2){
    		Model::ins('SysUser')->update(['userpwd'=>LoginModel::PwdEncode($pwd1)],['id'=>$userid]);
    		$this->showSuccess('修改成功');
    	}else{
    		$this->showError('两次密码不一样');
    	}
    }

    public function deluserAction(){
        $userid = $this->getParam('userid');
        Model::ins('SysUser')->delete(['id'=>$userid]);
        $this->showSuccess('成删除');
    }

}