<?php
namespace app\superadmin\controller\Index;
use app\superadmin\ActionController;

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
    	
      
        //var_dump($loginname);
        $userData = array(
                'username'=> $this->username,
                'businessid'=> $this->businessid,
                'business_roleid'=> $this->business_roleid
            );
       
        //菜单动态数据 kinmos 2016.09.02
        //查询用户角色
        //查询用户角色所有的菜单
        $UserRole=Db::Table('SysUser')->getRow(array('id'=>$this->customerid),"roleid");
        //var_dump($UserRole);
        $UserRole['user_roleid'] = $UserRole['roleid'];
        if(empty($UserRole)||empty($UserRole['user_roleid'])||$UserRole['user_roleid']==0)
        {
            $menuData=array();
        }
        else
        {
            $RoleMenu=Db::Table('SysMenurole')->getList(array('roleid'=>$UserRole['user_roleid'],'isdelete'=>0),"menuid");

            if(empty($RoleMenu))
            {
                $menuData=array();
            }
            else
            {
                $ids="";// 权限菜单展示去掉
                foreach ($RoleMenu as $key => $value) {
                    $ids.=$value['menuid'].",";
                }
                $ids=rtrim($ids,',');
                //查询第一级菜单   //['id'=>['in',$ids], 权限菜单展示去掉
                if($this->username != 'SuperAdmin'){
                    $menuData=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>0,'id'=>['in',$ids]],'menuname,url,id,icon,class',"sort asc"); 
                }else{
                    $menuData=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>0],'menuname,url,id,icon,class',"sort asc"); 
                }
               
                foreach ($menuData as $key => $value) {
                    //['id'=>['in',$ids], 权限菜单展示去掉
                    if($this->username != 'SuperAdmin'){
                        $child=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$value['id'],'id'=>['in',$ids]],'menuname,url,id',"sort asc");
                    }else{
                        $child=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$value['id']],'menuname,url,id',"sort asc");
                    }
                    
                    $menuData[$key]['childMenu']=$child;
                }
            }

        }
       
        $childarr = [];
        $iframeUrl = '';
        $iframName = '';
        
      
        foreach ($menuData as $key => $value) {
            foreach ($value['childMenu'] as $k => $v) {
               //print_r($v);
                if($this->username != 'SuperAdmin'){
                    $child=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$v['id'],'id'=>['in',$ids]],'menuname,url,id',"sort asc");
                }else{
                    $child=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$v['id']],'menuname,url,id',"sort asc");
                }
                $childarr[] = $child;
            }
        }

        foreach ($childarr as $key => $value) {
            
            foreach ($value as $ko => $vo) {
                
                if($iframeUrl =='' && $iframName =='' && $vo['url'] != '/' && $vo['url'] != ''){
                   
                   $iframeUrl = $vo['url']; 
                   $iframName = $vo['menuname'];   
                   
                }
            }
          
        }
        $parentid   = $menuData[0]['id'];
       
        $viewData = array(
                "menuData"=>$menuData, //赋值菜单到模板
                "userData"=>$userData,
                "iframeUrl" => $iframeUrl,
                "iframName" => $iframName,
                'parentid' => $parentid,
                'customerid'=>$this->customerid
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

    /**
     * [childMenuAction 获取分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-28T10:23:01+0800
     * @return   [type]                   [description]
     */
    public function childMenuAction(){
        
        $UserRole=Db::Table('SysUser')->getRow(array('id'=>$this->customerid),"roleid");
        //var_dump($UserRole);
        $UserRole['user_roleid'] = $UserRole['roleid'];

        $RoleMenu=Db::Table('SysMenurole')->getList(array('roleid'=>$UserRole['user_roleid'],'isdelete'=>0),"menuid");
        $ids="";// 权限菜单展示去掉

        foreach ($RoleMenu as $key => $value) {
            $ids.=$value['menuid'].",";
        }
        $ids=rtrim($ids,',');

        $parentid = $this->getParam('parentid');

        if($this->username != 'SuperAdmin'){
            $menuData   = Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$parentid,'id'=>['in',$ids]],'menuname,url,id,icon,class',"sort asc"); 
        }else{
            $menuData   = Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$parentid],'menuname,url,id,icon,class',"sort asc"); 
        }

        foreach ($menuData as $key => $value) {

            if($this->username != 'SuperAdmin'){
                //['id'=>['in',$ids], 权限菜单展示去掉
                $child=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$value['id'],'id'=>['in',$ids]],'menuname,url,id',"sort asc");
            }else{
                $child=Db::Table('SysMenu')->getList(['isdelete'=>0,'enable'=>1,'parentid'=>$value['id']],'menuname,url,id',"sort asc");
            }
            $menuData[$key]['childMenu']=$child;
        }
        foreach ($menuData as $k => $v) {
            # code...
        
            echo '<dl id="menu-article">
                        <dt class="dt_click_tab">
                            <!--<i class="icon icon-home">&nbsp;&nbsp;</i>-->
                            <span>'.$v['menuname'].'</span>
                        </dt>
                        <dd   ';  
            if($ke == 1){ echo 'style="display: none;"';}else{ echo 'style="display: block;"'; }   

                        echo '>
                            <ul>';
                            foreach($v['childMenu'] as $vo){
                                echo '<li>';
                                echo '<a  _href="'.$vo['url'].'" data-title="'.$vo['menuname'].'" href="javascript:void(0);" class="">'.$vo['menuname'].'</a>';
                                echo '</li>';
                            }
            echo                '</ul>
                        </dd>
                    </dl>
                ';
        }

        echo'<script>
            $.Huifold(".menu_dropdown dl dt",".menu_dropdown dl dd","fast",1,"click");
        </script>
        ';
    }

}
