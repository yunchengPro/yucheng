<?php
namespace app\superadmin\controller\Configure;
use app\superadmin\ActionController;

use app\lib\Db;
use app\lib\Model;

class MenuController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
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
                $menu = Model::ins("BusSysMenu")->getRow(array('id'=>$value),"menuname");
                $parantname.=$menu['menuname']."--";
                $parent_id = $value;
            }
            $parantname =substr($parantname,0,-2);
        }



        $viewData = array(
            'action' => '/configure/menu/saveMenu',
            'parent_id'=>$parent_id,
            'parantname'=>$parantname
            );
        return $this->view($viewData);
    }

    /**
     * [editMenu 编辑菜单]
     * @return [type] [description]
     * @author [kinmos] 
     */
    public function editMenuAction(){
        $id = $this->getParam('id');

        $where = array('id'=>$id);
        $menuData = Model::ins("BusSysMenu")->getRow($where);
        $parantname="";
        $parent_id=0;
        if(!empty($menuData)&&!empty($menuData['parentid'])&&$menuData['parentid']!=0)
        {
            $parmenu=Model::ins('BusSysMenu')->getRow(array('id'=>$menuData['parentid']),'id,menuname');
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
            'action' => '/configure/menu/saveMenu',
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
            $userData = Model::ins("BusSysMenu")->update($updateData,array('id'=>$value));
        }

        $this->showSuccess('成功删除');
    }

    /**
     * [saveMenu 保存菜单]
     */
    public function saveMenuAction(){

        $post = $this->params;
        if(!empty($post)){
            $data['parentid']=$post['parentid'];
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
                Model::ins('BusSysMenu')->insert($data);
            }
            else
            {
             
                Model::ins('BusSysMenu')->update($data,array('id'=>$post['id']));
            }
            $this->showSuccess("保存成功");
        }else{
            exit();
        }
    }

    /**
     * [menuList 菜单列表]
     * @return [type] [description]
     * @author [kinmos]
     */
    public function menuListAction(){

        $where = "isdelete=0";
        if($this->params['keyword']!='')
            $where.=" and menuname like '%".$this->params['keyword']."%'";
        $field = "*";
        
        //获取列表数据
        $list = Model::ins("BusSysMenu")->getList($where,$field,"sort asc");

        //print_r($pagelist);
        $viewData = array(
                "pagelist"=>$list, //列表数据
            );
        return $this->view($viewData);
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
                Model::ins("BusSysMenu")->update($upData,array('id'=>$id));

                $this->showSuccess('设置成功');
            }catch (Exception $e){
                $this->showError('设置失败');
            }
        }else{
            $this->showError('设置失败');
        }
    }

    //角色菜单权限管理页面
    public function menuRoleAction()
    {
        //获取角色id
        $roleid = $this->getParam("roleid");

        $menuData = Model::ins('BusSysMenu')->getList('isdelete=0 and enable=1','*','enable desc,sort asc');
        
        foreach($menuData as $k => $v){
            // $menuActionData = $this->_Table_menuaction->fetchAll($this->_db->quoteInto("f_menuid=?",$v['id']))->toArray(); 
            $menuData[$k]['menuActionData'] = array();
        }
        
        //获取当前角色拥有菜单权限
        $menuroleData = Model::ins('BusSysMenuRole')->getList(array('roleid'=>$roleid,'isdelete'=>0)); 
        
        foreach ($menuroleData as $v) {
            $menutype .= $v['menuid'] . ',';
        }

        $menutype = rtrim($menutype,',');

        $viewData = array(
                "pagelist"=>$menuData, //列表数据
                'roleid'=>$roleid,
                'menutype'=>$menutype,
                'action'=>"Configure/menu/saveMenuRole"
            );
        return $this->view($viewData);
    }
    //保存菜单权限
    public function saveMenuRoleAction()
    {
        //获取post数据
        $post = $this->params;
       
        //$where = "roleid = ".$post['roleid'];
        if(!isset($post['roleid'])||empty($post['roleid']))
        {
            echo "操作失败";die;
        }
       
        //重置权限
        Model::ins('BusSysMenuRole')->delete(array('roleid'=>$post['roleid']));



        //添加权限
        if(!empty($post['menuid'])){
            //组合添加权限数据
            foreach ($post['menuid'] as $v ) {
                $addData = array(
                    'roleid' => $post['roleid'],
                    'menuid' => $v,
                    'isdelete' => 0,
                    'createby' => $this->session('userid'),
                    'createtime' => date('Y-m-d H:i:s',time())
                    );
                
                Model::ins('BusSysMenuRole')->insert($addData);
            }
        }
        echo "授权成功";
        //$this->showSuccess('设置成功');
    }
}
