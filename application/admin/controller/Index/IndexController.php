<?php
namespace app\admin\controller\Index;
use app\admin\ActionController;

use app\lib\Db;

class IndexController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){
    	
        $title = '供应商后台';
        //var_dump($loginname);
        $userData = array(
                'username'=> $this->username,
                'businessid'=> $this->businessid,
                'business_roleid'=> $this->business_roleid
            );
       
        //菜单动态数据 kinmos 2016.09.02
        //查询用户角色
        //查询用户角色所有的菜单
        //$UserRole=Db::Table('SysUser')->getRow(array('id'=>$this->session('userid')),"user_roleid");
        $UserRole['user_roleid'] = 1;
        if(empty($UserRole)||empty($UserRole['user_roleid'])||$UserRole['user_roleid']==0)
        {
            $menuData=array();
        }
        else
        {
            $RoleMenu=Db::Table('BusSysMenurole')->getList(array('roleid'=>$UserRole['user_roleid'],'isdelete'=>0),"menuid");
            if(empty($RoleMenu))
            {
                $menuData=array();
            }
            else
            {
                // $ids=""; 权限菜单展示去掉
                // foreach ($RoleMenu as $key => $value) {
                //     $ids.=$value['menuid'].",";
                // }
                // $ids=rtrim($ids,',');
                //查询第一级菜单   //['id'=>['in',$ids], 权限菜单展示去掉
                $menuData=Db::Table('BusSysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>0],'menuname,url,id,icon,class',"sort asc"); 
                foreach ($menuData as $key => $value) {
                    //['id'=>['in',$ids], 权限菜单展示去掉
                    $child=Db::Table('BusSysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$value['id']],'menuname,url,id',"sort asc");
                    $menuData[$key]['childMenu']=$child;
                }
            }
        }

        $viewData = array(
                "menuData"=>$menuData, //赋值菜单到模板
                "userData"=>$userData,
                'title'=>$title
            );

        return $this->view($viewData);
    }

    /**
     * [dashboardAction 控制台]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-13T16:31:08+0800
     * @return   [type]                   [description]
     */
    public function dashboardAction(){
        echo "<h1>控制台首页信息</h1>";
        exit();
    }
}
