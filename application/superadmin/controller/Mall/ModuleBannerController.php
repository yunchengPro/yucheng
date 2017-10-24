<?php
// +----------------------------------------------------------------------
// |  [ 模块-推荐单元广告管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年4月5日 下午2:22:50
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Mall;
use app\superadmin\ActionController;
//use app\superadmin\controller\Customer\CustomerController;

use \think\Config;
//use app\lib\Db;
use app\lib\Model;

use app\form\MallModuleBanner\MallModuleBannerAdd;
use app\model\Rd\ActiveBannerRD;


class ModuleBannerController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 模块-推荐单元广告列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月6日 上午11:44:31
     * @return [type]    [description]
     */
	public function indexAction(){	            
        //模块-推荐单元列表
        $moduleId = $this->getParam('moduleid') ;
        if(empty($moduleId)){
            $this->showError('请选择模块');
        }

        $skipurl = Config::get('skipurl');

        $moduleData =  Model::ins('MallModule')->getRow(['id'=>$moduleId]);
        
        $where = array('moduleid' => $moduleId);      
        $where = $this->searchWhere([
            "bname" => "like"
        ],$where);
        //$list = Model::ins("MallModuleBanner")->pageList($where,'*','id desc');
        $list = Model::ins("MallModuleBanner")->getList($where, '*', 'sort asc');
        //var_dump($list); exit();
        
        $viewData = array(
            'title'   => $moduleData['modulename'] . ' - 推荐列表',
            'moduleId' => $moduleId,
            'skipurl'=>$skipurl,
            //'moduleData' => $moduleData,            
            "pagelist"=>$list, //列表数据
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

	/**
	 * [addModuleBannerAction 添加模块-推荐单元广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月6日 上午11:44:31
	 * @return [type]    [description]
	 */
	public function addModuleBannerAction(){

	    $moduleId = $this->getParam('moduleid') ;
	    if(empty($moduleId)){
	        $this->showError('请选择模块');
	    }
	    //var_dump($moduleId); exit();
	    $skipurl = Config::get('skipurl');
        $action = '/Mall/ModuleBanner/doaddOreditModuleBanner';
        //form验证token
        $formtoken = $this->Btoken('Mall-ModuleBanner-addModuleBanner');
        $viewData = array(              
                "title"=>"添加推荐",
                'moduleId' => $moduleId,
                'formtoken'=>$formtoken,
                'skipurl'=>$skipurl,
                "action"=>$action
            );
        return $this->view($viewData);
	}

	/**
	 * [editModuleBannerAction 添加或修改模块-推荐单元广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月6日 上午11:44:31
	 * @return [type]    [description]
	 */
    public function editModuleBannerAction(){        
        $id = $this->getParam('id');

        $action = '/Mall/ModuleBanner/doaddOreditModuleBanner';
        $moduleBannerData =  Model::ins('MallModuleBanner')->getRow(['id'=>$id]);
        //var_dump($moduleBannerData); exit();
        $skipurl = Config::get('skipurl');
          //form验证token
        $formtoken = $this->Btoken('Mall-ModuleBanner-editModuleBanner');
        $moduleId  = $moduleBannerData['moduleid'];
        $viewData = array(
                "title"=>"编辑推荐",
                'moduleId' => $moduleId,
                "moduleBannerData"=>$moduleBannerData,
                'skipurl'=>$skipurl,
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditModuleBannerAction 添加或修改模块-推荐单元广告]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月6日 上午11:44:31
     * @return [type]    [description]
     */
	public function doaddOreditModuleBannerAction(){
	    $moduleId = $this->getParam('moduleid') ;
	    if(empty($moduleId)){
	        $this->showError('请选择模块');
	    }
	    
	    $moduleData =  Model::ins('MallModule')->getRow(['id'=>$moduleId]);
	    if(empty($moduleData)){
	        $this->showError('不存在该模块');
	    }	    

        if($this->Ctoken()){
        //if(1){

            $post = $this->params;
            $id = $post['id'];

            //$post['businessid'] = $this->businessid; 
            $post = Model::ins('MallModuleBanner')->_facade($post);
            //var_dump($post); exit();

            //自动验证表单 需要修改form对应表名
            $MallModuleBannerAdd = new MallModuleBannerAdd();
            if(!$MallModuleBannerAdd->isValid($post)){//验证是否正确 
                $this->showError($MallModuleBannerAdd->getErr());//提示报错信息
            }else{                
                if(empty($id)){
                    //$post['addtime'] = date('Y-m-d H:i:s');
                    $data = Model::ins('MallModuleBanner')->insert($post);  
                }else{
                    $data = Model::ins('MallModuleBanner')->update($post,['id'=>$id]);  
                }
                $ActiveBannerRD = new ActiveBannerRD();
                $ActiveBannerRD->del(1);
                $ActiveBannerRD->del($moduleId);
                
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
	 * [delModuleBannerAction 删除模块-推荐单元广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月6日 上午11:44:31
	 * @return [type]    [description]
	 */
	public function delModuleBannerAction(){

        $moduleBannerId = $this->getParam('ids');

        if(empty($moduleBannerId)){
            $this->showError('请选择需要删除的广告');
        }

        $moduleBannerId = explode(',', $moduleBannerId);
        //批量删除用户
        foreach ($moduleBannerId as $value) {
            $data = Model::ins('MallModuleBanner')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
	}

	
}