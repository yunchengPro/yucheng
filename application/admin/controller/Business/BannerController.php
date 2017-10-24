<?php
// +----------------------------------------------------------------------
// |  [ banner广告管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年4月5日 下午2:22:50
// +----------------------------------------------------------------------
namespace app\admin\controller\Business;
use app\admin\ActionController;
//use app\superadmin\controller\Customer\CustomerController;

use \think\Config;
//use app\lib\Db;
use app\lib\Model;

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
     
        
        $where = array('businessid' => $this->businessid);      
        $where = $this->searchWhere([
            "bname" => "like"
        ],$where);
        //$list = Model::ins("BusBusinessBanner")->pageList($where,'*','id desc');
        $list = Model::ins("BusBusinessBanner")->getList($where, '*', 'sort asc');
        //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list, //列表数据
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
        //print_r($skipurl);
        $action = '/Business/Banner/doaddOreditBanner';
        //form验证token
        $formtoken = $this->Btoken('Business-Banner-addBanner');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                "skipurl"=>$skipurl,
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

        $action = '/Business/Banner/doaddOreditBanner';
        $bannerData =  Model::ins('BusBusinessBanner')->getRow(['id'=>$id]);
        //var_dump($bannerData); exit();
        $skipurl = Config::get('skipurl');
          //form验证token
        $formtoken = $this->Btoken('Business-Banner-editBanner');
        $viewData = array(
                "title"=>"编辑广告",
                "bannerData"=>$bannerData,
                'formtoken'=>$formtoken,
                "skipurl" => $skipurl,
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
            $post = Model::ins('BusBusinessBanner')->_facade($post);
            //var_dump($post); exit();

           
              
                            
            if(empty($id)){
                $post['addtime'] = date('Y-m-d H:i:s');
                $post['businessid'] = $this->businessid;
                $data = Model::ins('BusBusinessBanner')->insert($post);  
            }else{
                $data = Model::ins('BusBusinessBanner')->update($post,['id'=>$id]);  
            }
            if($data > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showError('操作错误，请联系管理员');
                }
            

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
            $data = Model::ins('BusBusinessBanner')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
	}

	
}