<?php
// +----------------------------------------------------------------------
// |  [ 店铺管理控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-23
// +----------------------------------------------------------------------

namespace app\superadmin\controller\Business;
use app\superadmin\ActionController;

use app\lib\Db;
use app\model\Business\BusinessModel;
use app\form\BusBusinessInfo\BusBusinessInfoEdit;
use app\lib\Model;

class BusinessController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [indexAction 店铺资料管理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-23T13:56:07+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
    	$businessid = $this->businessid;
        $business = BusinessModel::getBusinessInfoById($businessid,'id,businessname,businesslogo,mobile,area,area_code,address,realname,idnumber,lngx,laty,description,businessintro,servicetel');
       
        $viewData = [
            'action' => '/Business/Business/doeditBusiness',
            'business'=>$business
        ];
        return $this->view($viewData);
    }

    /**
     * [editBusinessAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-23T15:20:27+0800
     * @return   [type]                   [description]
     */
    public function doeditBusinessAction(){
        $post = $this->params;
        //print_r($post);
        if(!empty($post['id'])){

            $id = $post['id'];
            unset($post['id']);
            unset($post['file']);
            $BusBusinessInfoEdit = new BusBusinessInfoEdit();
            if($BusBusinessInfoEdit->isValid($post)){
                $data = BusinessModel::updateBuinessInfo($id,$post);
                $this->showSuccess('成功修改');
            }else{
                $this->showError($BusBusinessInfoEdit->getErr());
            }
        }else{
            $this->showError('请选择需要修改的商家');
        }
    }
}

