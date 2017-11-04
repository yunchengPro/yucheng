<?php
// +----------------------------------------------------------------------
// |  [ banner广告管理 ]
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

use app\form\MallBanner\MallBannerAdd;

class BannerController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction banner广告列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午2:40:44
     * @return [type]    [description]
     */
	public function indexAction(){	            
        //banner列表
        $type = $this->getParam('type', 1) ;
        
        $skipurl = Config::get('skipurl');

        $where = array('type' => $type);      
//         $where = $this->searchWhere([
//             "bname" => "like"
//         ],$where);
        //$list = Model::ins('SysBanner')->pageList($where,'*','id desc');
        $list = Model::ins('SysBanner')->getList($where, '*', 'sort asc');
        //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list, //列表数据
            'skipurl' => $skipurl
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

	/**
	 * [addBannerAction 添加banner广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function addBannerAction(){

        $skipurl = Config::get('skipurl');
        
        $action = '/Mall/Banner/doaddOreditBanner';
        //form验证token
        $formtoken = $this->Btoken('Mall-Banner-addBanner');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'skipurl' => $skipurl,
                "action"=>$action
            );
        return $this->view($viewData);
	}

	/**
	 * [editBannerAction 添加或修改banner广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
    public function editBannerAction(){

        $id = $this->getParam('id');

        $skipurl = Config::get('skipurl');

        $action = '/Mall/Banner/doaddOreditBanner';
        $bannerData =  Model::ins('SysBanner')->getRow(['id'=>$id]);
        //var_dump($bannerData); exit();
        
          //form验证token
        $formtoken = $this->Btoken('Mall-Banner-editBanner');
        $viewData = array(
                "title"=>"编辑广告",
                "bannerData"=>$bannerData,
                'formtoken'=>$formtoken,
                'skipurl' => $skipurl,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditBannerAction 添加或修改banner广告]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午4:57:59
     * @return [type]    [description]
     */
	public function doaddOreditBannerAction(){
     

        if($this->Ctoken()){
        //if(1){

            $post = $this->params;
            $id = $post['id'];

            //$post['businessid'] = $this->businessid; 
            $post = Model::ins('SysBanner')->_facade($post);
            //var_dump($post); exit();

            //自动验证表单 需要修改form对应表名
            // $MallBannerAdd = new MallBannerAdd();
            // if(!$MallBannerAdd->isValid($post)){//验证是否正确 
            //     $this->showError($MallBannerAdd->getErr());//提示报错信息
            // }else{   
                           
                if(empty($id)){
                  
                    $post['addtime'] = date('Y-m-d H:i:s');
                    $data = Model::ins('SysBanner')->insert($post);  
                }else{
                    $data = Model::ins('SysBanner')->update($post,['id'=>$id]);  
                }
              
              
                if($data > 0){
                    $this->showSuccess('操作成功');
                }else{
                    $this->showError('操作错误，请联系管理员');
                }
            // }

        }else{
            $this->showError('token错误，禁止操作');
        }
	}


	/**
	 * [delBannerAction 删除banner广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function delBannerAction(){

        $bannerId = $this->getParam('ids');

        if(empty($bannerId)){
            $this->showError('请选择需要删除的广告');
        }

        $bannerId = explode(',', $bannerId);
        //批量删除用户
        foreach ($bannerId as $value) {
            $data = Model::ins('SysBanner')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
	}

	
}