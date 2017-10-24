<?php
// +----------------------------------------------------------------------
// |  [ 店铺管理控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-23
// +----------------------------------------------------------------------

namespace app\admin\controller\Business;
use app\admin\ActionController;

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
        
        //var_dump($businessid);
        $business = BusinessModel::getBusinessInfoById($businessid,'id,businessname,businesslogo,mobile,area,area_code,address,realname,idnumber,lngx,laty,description,businessintro,servicetel');
        //print_r($business);
        $business['businesslogo'] = str_replace('?x-oss-process=image/quality,q_80', '',  $business['businesslogo']);
        $business['businesslogo'] = str_replace('https://nnhtest.oss-cn-shenzhen.aliyuncs.com/', '',  $business['businesslogo']);
        
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
        $businessid = $this->businessid;
        $post = $this->params;

        $sysarea =  Model::ins('SysArea');

        $province = $sysarea->getRow(['id'=>$post['area_code_province']],'areaname')['areaname'];
        $city = $sysarea->getRow(['id'=>$post['area_code_city']],'areaname')['areaname'];
        $county = $sysarea->getRow(['id'=>$post['area_code_county']],'areaname')['areaname'];
        $post['area'] = $province .'/'.$city .  '/' . $county;
        $post['area_code'] = $post['area_code_county'];
        unset($post['area_code_province']);
        unset($post['area_code_city']);
        unset($post['area_code_county']);
        //print_r($post);
        if(!empty($post['id'])){

            $id = $post['id'];
            unset($post['id']);
            unset($post['file']);
            $BusBusinessInfoEdit = new BusBusinessInfoEdit();
            if($BusBusinessInfoEdit->isValid($post)){

                $data = BusinessModel::updateBuinessInfo($businessid,$post);
               
                $this->showSuccess('成功修改');
            }else{
                $this->showError($BusBusinessInfoEdit->getErr());
            }
        }else{
        
            $post['id'] = $businessid;
            $post['price_type'] = '1,2';
            $post['addtime'] = date('Y-m-d H:i:s');
            unset($post['file']);

            $BusBusinessInfoEdit = new BusBusinessInfoEdit();
            if($BusBusinessInfoEdit->isValid($post)){

                Model::ins("BusBusinessInfo")->insert($post);
                $this->showSuccess('成功添加');
            }else{
                $this->showError($BusBusinessInfoEdit->getErr());
            }
           
           
        }
    }

    /**
     * [busfreightAction 商家满包邮]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-19T10:18:43+0800
     * @return   [type]                   [description]
     */
    public function busfreightAction(){
        //form验证token
        $formtoken = $this->Btoken('Business-busfreight');
        $freight_row = Model::ins('BusBusinessFreight')->getRow(['id'=>$this->businessid]);
        if($freight_row['freight_type'] == 2){
            $freight_row['freight'] = DePrice($freight_row['freight']);
        }
        
        $viewData = [
            'title'  => '满包邮设置',
            'action' => '/Business/Business/dobusfreight',
            'formtoken' => $formtoken,
            'Freight' => $freight_row 

        ];
       
        return $this->view($viewData);
    }

    /**
     * [dobusfreightAction 设置包邮]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-19T11:22:53+0800
     * @return   [type]                   [description]
     */
    public function dobusfreightAction(){

        if($this->Ctoken()){
            //获取参数
            $post = $this->params;

            if(empty($post['freight_type'])){
                $this->showErrorPage('请选择包邮方式','/Business/Business/busfreight');
            }
            if(empty($post['freight'])){
                $this->showErrorPage('请填写包邮条件','/Business/Business/busfreight');
            }
            
            if(empty($post['enable'])){
                $this->showErrorPage('请填选择启用状态','/Business/Business/busfreight');
            }

            if($post['freight_type'] == 2){
                $freight = EnPrice($post['freight']);
            }else{
                $freight = $post['freight'];
            }
            //包邮设置
            $data = [
                'freight_type' => $post['freight_type'],
                'freight'      => $freight,
                'enable'       => $post['enable']  
            ];
            $freight_row = Model::ins('BusBusinessFreight')->getRow(['id'=>$this->businessid]);
            if(empty($freight_row)){
                $data['id'] = $this->businessid;
               $ret = Model::ins('BusBusinessFreight')->insert($data);
            }else{
               $ret = Model::ins('BusBusinessFreight')->update($data,['id'=>$this->businessid]);
            }
            $this->showSuccessPage('设置成功','/Business/Business/busfreight');
        }else{
            $this->showErrorPage('token错误，禁止操作','/Business/Business/busfreight');
        }
    }
}

