<?php
// +----------------------------------------------------------------------
// |  [ 用户角色公共控制器]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月28日 21:17:18
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;
use app\superadmin\ActionController;

// use \think\Config;
// use app\lib\Db;
use app\lib\Model;



abstract class CustomerController extends ActionController{ 

    protected $_role = '';    //子类必须设定角色值
	
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();      
    }
    
    /**
     * [layoutAction 页面tab布局]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    public function layoutAction(){
        return $this->view(array('title' => '用户管理'));
    }

	/**
	 * [indexAction 用户列表]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017-03-25T10:47:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
	    //var_dump('/Customer/Customer/index'); exit();
        
	    $username = strval($this->getParam('username'));
        
	    $where = array();
// 	    $where = $this->searchWhere([
// 	        "username"=>"like"
// 	    ],$where);
	    if(!empty($username)){
	        $where = array_merge($where, array('username' => $username));
	    }
	    //var_dump($where); exit();
	    
	    $role      = $this->_role;
	    $bullModel = Model::new('Customer.' . ucfirst($role));
	    if(!empty($where)){
	        $list = $bullModel->getWhereList($where);
	    }else{
	        $list = $bullModel->getSimpleList();
	    }	    	    
	    //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        );

        return $this->view($viewData);
	}
}