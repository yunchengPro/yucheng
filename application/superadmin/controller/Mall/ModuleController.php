<?php
// +----------------------------------------------------------------------
// |  [ 模块-广告管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年4月5日 下午2:22:50
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Mall;
use app\superadmin\ActionController;
//use app\superadmin\controller\Customer\CustomerController;

//use \think\Config;
//use app\lib\Db;
use app\lib\Model;

use app\form\MallModule\MallModuleAdd;

class ModuleController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 模块广告列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午2:40:44
     * @return [type]    [description]
     */
	public function indexAction(){	            
        //模块列表
        $type = $this->getParam('type', 1) ;
        
        $where = array('type' => $type);      
//         $where = $this->searchWhere([
//             "bname" => "like"
//         ],$where);
        //$list = Model::ins("MallModule")->pageList($where,'*','id desc');
        $list = Model::ins("MallModule")->getList($where, '*', 'sort asc');
        //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list, //列表数据
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

	/**
	 * [addModuleAction 添加模块广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function addModuleAction(){

        $action = '/Mall/Module/doaddOreditModule';
        //form验证token
        $formtoken = $this->Btoken('Mall-Module-addModule');
        $viewData = array(
                "title"=>"添加模块",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
	}

	/**
	 * [editModuleAction 添加或修改模块广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
    public function editModuleAction(){

        $id = $this->getParam('id');

        $action = '/Mall/Module/doaddOreditModule';
        $moduleData =  Model::ins('MallModule')->getRow(['id'=>$id]);
        //var_dump($moduleData); exit();
        
          //form验证token
        $formtoken = $this->Btoken('Mall-Module-editModule');
        $viewData = array(
                "title"=>"编辑广告",
                "moduleData"=>$moduleData,
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditModuleAction 添加或修改模块广告]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午4:57:59
     * @return [type]    [description]
     */
	public function doaddOreditModuleAction(){
     

        if($this->Ctoken()){
        //if(1){

            $post = $this->params;
            $post['isshow'] = intval($post['isshow']);
            $id = $post['id'];

            //$post['businessid'] = $this->businessid; 
            $post = Model::ins('MallModule')->_facade($post);
            //var_dump($post); exit();

            //自动验证表单 需要修改form对应表名
            $MallModuleAdd = new MallModuleAdd();
            if(!$MallModuleAdd->isValid($post)){//验证是否正确 
                $this->showError($MallModuleAdd->getErr());//提示报错信息
            }else{                
                if(empty($id)){
                    //$post['addtime'] = date('Y-m-d H:i:s');
                    $data = Model::ins('MallModule')->insert($post);  
                }else{
                    $data = Model::ins('MallModule')->update($post,['id'=>$id]);  
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
	 * [delModuleAction 删除模块广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function delModuleAction(){

        $moduleId = $this->getParam('ids');

        if(empty($moduleId)){
            $this->showError('请选择需要删除的广告');
        }

        $moduleId = explode(',', $moduleId);
        //批量删除用户
        foreach ($moduleId as $value) {
            $moduleBannerData = Model::ins('MallModuleBanner')->getRow(['moduleid' => $value]);
            if(!empty($moduleBannerData)){
                $this->showError('当前推荐模块下推荐单元，请先删除推荐单元');
            }else{
                $data = Model::ins('MallModule')->delete(['id'=>$value]);
            }
        }

        $this->showSuccess('成功删除');
	}

	
}