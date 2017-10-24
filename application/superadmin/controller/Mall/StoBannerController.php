<?php
// +----------------------------------------------------------------------
// |  [ Stobanner广告管理 ]
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

use app\form\StoBanner\StoBannerAdd;
use app\model\Sys\AreaModel;

class StoBannerController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction Stobanner广告列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午2:40:44
     * @return [type]    [description]
     */
	public function indexAction(){

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }

        $type = $this->getParam('type') ;

        $where = [];
        if(!empty($type)){
            $where['type'] = $type;
        }

        $skipurl = Config::get('skipurl');

        $list = Model::ins("StoBanner")->getList($where, '*', 'sort asc');
        //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list, //列表数据
            "city"=> $city_arr,
            'skipurl' => $skipurl
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

	/**
	 * [addStobannerAction 添加Stobanner广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function addStoBannerAction(){
        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }
       
        $skipurl = Config::get('skipurl');
        $action = '/Mall/StoBanner/doaddOreditStobanner';
        //form验证token
        $formtoken = $this->Btoken('Mall-Stobanner-addStobanner');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'city' => $city_arr,
                "action"=>$action,
                'skipurl' => $skipurl
            );
        return $this->view($viewData);
	}

	/**
	 * [editStobannerAction 添加或修改Stobanner广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
    public function editStoBannerAction(){
        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }

        $id = $this->getParam('id');
        $skipurl = Config::get('skipurl');
        $action = '/Mall/StoBanner/doaddOreditStoBanner';
        $StobannerData =  Model::ins('StoBanner')->getRow(['id'=>$id]);
        //var_dump($StobannerData); exit();
        
          //form验证token
        $formtoken = $this->Btoken('Mall-Stobanner-editStobanner');
        $viewData = array(
                "title"=>"编辑广告",
                "StobannerData"=>$StobannerData,
                'formtoken'=>$formtoken,
                'city' => $city_arr,
                "action"=>$action,
                'skipurl' => $skipurl
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditStobannerAction 添加或修改Stobanner广告]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午4:57:59
     * @return [type]    [description]
     */
	public function doaddOreditStoBannerAction(){
     

        if($this->Ctoken()){
        //if(1){

            $post = $this->params;
            $id = $post['id'];

            //$post['businessid'] = $this->businessid; 
            $post = Model::ins('StoBanner')->_facade($post);
            //var_dump($post); exit();
            $areaOBJ =  new AreaModel();
            $city = $areaOBJ->getCity();
            $city_arr = [];
            foreach ($city as $key => $value) {
                $city_arr[$key] = $value['areaname'];
            }
            //自动验证表单 需要修改form对应表名
            $StoBannerAdd = new StoBannerAdd();
            if(!$StoBannerAdd->isValid($post)){//验证是否正确 
                $this->showError($StoBannerAdd->getErr());//提示报错信息
            }else{   
                           
                if(empty($id)){
                    $post['city'] = $city_arr[$post['city_id']];
                    $post['addtime'] = date('Y-m-d H:i:s');
                    $data = Model::ins('StoBanner')->insert($post);  
                }else{
                    $post['city'] = $city_arr[$post['city_id']];
                    $data = Model::ins('StoBanner')->update($post,['id'=>$id]);  
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
	 * [delStobannerAction 删除Stobanner广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function delStoBannerAction(){

        $StobannerId = $this->getParam('ids');

        if(empty($StobannerId)){
            $this->showError('请选择需要删除的广告');
        }

        $StobannerId = explode(',', $StobannerId);
        //批量删除用户
        foreach ($StobannerId as $value) {
            $data = Model::ins('StoBanner')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
	}

	
}