<?php
// +----------------------------------------------------------------------
// |  [ 牛人管理]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-06-12 17:09:04
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;
use app\lib\Model;
use app\superadmin\ActionController;
use \think\Config;

class BullTalentController extends ActionController {
    public function __construct() {
        parent::__construct();
    }
    
    public function layoutAction() {
        return $this->view(array("title"=>"牛达人管理"));
    }
    
    public function indexAction() {
        $username = strval($this->getParam('username'));
        
        $where = array();
        if(!empty($username)){
            $where = array_merge($where, array('username' => $username));
        }
        
        $bullTalentRole = Model::new('Customer.BullTalentRole');
        if(!empty($where)){
            $list = $bullTalentRole->getWhereList($where);
        }else{
            $list = $bullTalentRole->getSimpleList();
        }
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total']+5123, //总数
        );
        
        return $this->view($viewData);
    }
    
    public function waitAuditAction() {
        $where = array();
        
        $where = $this->searchWhere([
            "mobile" => "like",
        ], $where);
        
        $list  = Model::new('Customer.BullTalentRole')->getWaitAuditList($where);
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        );
        
        return $this->view($viewData);
    }
    
    public function passAction() {
        $bullTalentId = $this->getParam('id');
        
        if(empty($bullTalentId)){
            $this->showError('请选择用户');
        }
        
        $roleRecoTalentModel = Model::ins('RoleRecoTalent');
        $roleRecoTalent      = $roleRecoTalentModel->getRow(["id"=>$bullTalentId]);
        //var_dump($roleRecoOr); exit();
        if(empty($roleRecoTalent)){
            $this->showError('不存在该用户');
        }
        
        $result = Model::new('Customer.BullTalentRole')->pass($roleRecoTalent);
        if($result["code"] != 200) {
            $this->showError($result["data"]);
        }
        
//         Model::new("Sys.Mq")->add([
//             "url"=>"Customer.BullPeoRole.examSend",
//             "param"=>[
//                 "recoId"=>$bullPeoId,
//             ],
//         ]);
//         Model::new("Sys.Mq")->submit();
        //var_dump($result); exit();
        
        $this->showSuccess('成功通过审核');
    }
    
    /**
     * @user 编辑牛人信息
     * @param
     * @author jeeluo
     * @date 2017年4月24日下午2:29:06
     */
    public function editBullTalentInfoAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        $customerid = $this->getParam("id");
        $result = Model::new("Customer.BullTalentRole")->getCusCustomerInfo($customerid);
    
        if($result['code'] != "200") {
            $this->showError($result['data']);
        }
    
        $action = "/Customer/BullTalent/updateInfo?id=".$customerid;
    
        $viewData = array(
            "cusInfo" => $result['data'],
            "actionUrl" => $action,
        );
    
        return $this->view($viewData);
    }
    
    /**
     * @user 更改用户数据
     * @param
     * @author jeeluo
     * @date 2017年4月24日下午3:07:45
     */
    public function updateInfoAction() {
        if(empty($this->params['id'])) {
            $this->showError("请选择正确用户");
        }
        $params = array();
        if(!empty($this->params['nickname'])) {
            $params['nickname'] = $this->params['nickname'];
        }
        if(!empty($this->params['realname'])) {
            $params['realname'] = $this->params['realname'];
        }
    
        Model::ins("CusCustomerInfo")->modify($params, array("id" => $this->params['id']));
    
        return $this->showSuccess("操作成功");
    }
    
    /**
    * @user 添加牛达人角色页面
    * @param 
    * @author jeeluo
    * @date 2017年7月4日上午11:40:23
    */
    public function addBullTalentInfoAction() {
        $action = "/Customer/BullTalent/addBullTalent";
        $role_money = DePrice(Config::get("role_money.bullTalentMoney"));
        
        $viewData = array(
            "title" => "添加牛达人角色",
            "actionUrl" => $action,
            "role_money" => $role_money,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 添加牛达人操作
    * @param 
    * @author jeeluo
    * @date 2017年7月4日上午11:44:44
    */
    public function addBullTalentAction() {
        if(empty($this->params['realname']) || empty($this->params['mobile']) || empty($this->params['area_province']) || empty($this->params['area_city']) || empty($this->params["area_county"])
            || empty($this->params['address']) || empty($this->params['instroducerMobile'])) {
                $this->showError("请填写必填选项");
            }
            
//         if($this->params['role_status'] == 1) {
//             if(empty($this->params['amount'])) {
//                 $this->showError("请填写必填选项");
//             }
//         }
            
        if(phone_filter($this->params['mobile'])) {
            $this->showError("手机号码规格有误");
        }
        
        if(phone_filter($this->params['instroducerMobile'])) {
            $this->showError("手机号码规格有误");
        }
        
//         if(!empty($this->params['instroducerMobile'])) {
//             $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $this->params['instroducerMobile']), "id");
//             if(!empty($cus["id"])) {
//                 // 查询用户填写的分享人有对应的分享角色么
//                 $isIntroducerRole = Model::ins("CusRole")->getRow(["customerid"=>$cus["id"],"role"=>8],"id");
//                 if(empty($isIntroducerRole['id'])) {
//                     $this->showError("用户无对应角色，无法分享");
//                 }
//             } else {
//                 $this->showError("无此分享人");
//             }
//         }
        
        $result = Model::new("Customer.BullTalentRole")->addCusRole($this->params);
        
        if($result["code"] != "200") {
            $this->showError($result['data']);
        }
        
        $this->showSuccess("操作成功");
    }
}