<?php
// +----------------------------------------------------------------------
// |  [ 快捷方式-广告管理 ]
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

use app\form\MallSaleway\MallSalewayAdd;

class SalewayController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 快捷方式-广告列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午2:40:44
     * @return [type]    [description]
     */
	public function indexAction(){	  

        $skipurl = Config::get('skipurl');

        //快捷方式-列表
        $type = $this->getParam('type', 1) ;
        
        $where = array('type' => $type);      
//         $where = $this->searchWhere([
//             "bname" => "like"
//         ],$where);
        //$list = Model::ins("MallSaleway")->pageList($where,'*','id desc');
        $list = Model::ins("MallSaleway")->getList($where, '*', 'sort asc');
        //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list, //列表数据
            'skipurl' => $skipurl
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

	/**
	 * [addSalewayAction 添加快捷方式-广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function addSalewayAction(){

        $action = '/Mall/Saleway/doaddOreditSaleway';

        $skipurl = Config::get('skipurl');

        //form验证token
        $formtoken = $this->Btoken('Mall-Saleway-addSaleway');
        $viewData = array(
                "title"=>"添加品牌",
                'formtoken'=>$formtoken,
                'skipurl' => $skipurl,
                "action"=>$action
            );
        return $this->view($viewData);
	}

	/**
	 * [editSalewayAction 添加或修改快捷方式-广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
    public function editSalewayAction(){

        $id = $this->getParam('id');

        $skipurl = Config::get('skipurl');

        $action = '/Mall/Saleway/doaddOreditSaleway';
        $salewayData =  Model::ins('MallSaleway')->getRow(['id'=>$id]);
        //var_dump($salewayData); exit();
        
          //form验证token
        $formtoken = $this->Btoken('Mall-Saleway-editSaleway');
        $viewData = array(
                "title"=>"编辑广告",
                "salewayData"=>$salewayData,
                'formtoken'=>$formtoken,
                'skipurl' => $skipurl,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [doaddOreditSalewayAction 添加或修改快捷方式-广告]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午4:57:59
     * @return [type]    [description]
     */
	public function doaddOreditSalewayAction(){
     

        if($this->Ctoken()){
        //if(1){

            $post = $this->params;
            $id = $post['id'];

            //$post['businessid'] = $this->businessid; 
            $post = Model::ins('MallSaleway')->_facade($post);
            //var_dump($post); exit();

            //自动验证表单 需要修改form对应表名
            $MallSalewayAdd = new MallSalewayAdd();
            if(!$MallSalewayAdd->isValid($post)){//验证是否正确 
                $this->showError($MallSalewayAdd->getErr());//提示报错信息
            }else{                
                if(empty($id)){
                    $post['addtime'] = date('Y-m-d H:i:s');
                    $data = Model::ins('MallSaleway')->insert($post);  
                }else{
                    $data = Model::ins('MallSaleway')->update($post,['id'=>$id]);  
                }

                Model::Redis("MallSaleway")->del($post['type']);
                
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
	 * [delSalewayAction 删除快捷方式-广告]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月5日 下午4:57:59
	 * @return [type]    [description]
	 */
	public function delSalewayAction(){

        $salewayId = $this->getParam('ids');

        if(empty($salewayId)){
            $this->showError('请选择需要删除的广告');
        }

        $salewayId = explode(',', $salewayId);
        //批量删除用户
        foreach ($salewayId as $value) {
            $data = Model::ins('MallSaleway')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
	}

	
}