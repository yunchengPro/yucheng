<?php
// +----------------------------------------------------------------------
// |  [ 牛商管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-04-07
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;

use app\superadmin\ActionController;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;
use app\model\Sys\CommonModel;

class BullCityController extends ActionController {
    
    const title = "运营中心管理";
    const cityRole = 7;
    protected $_roleLookup = array('1' => '牛粉', '2' => '牛人', '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心", "8"=>"牛达人");
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 审核通过列表
    * @param 
    * @author jeeluo
    * @date 2017年4月29日下午4:27:35
    */
    public function indexAction() {
        //  已审核列表
        $where = array();
        $where = $this->searchWhere([
            "join_code_city" => "=",
            "mobile" => "=",
        ],$where);
        
        $where['enable'] = 1;
        $where['agent_type'] = 1;
        
        if(!empty($where['join_code_city'])) {
            
            $where['id'] = ["in", "select id from cus_customer_agent where join_code like '%".$where['join_code_city']."%'"];
            unset($where['join_code_city']);
        }
        
        if(!empty($where['mobile'])) {
//             $where['charge_mobile'] = $where['mobile'];
//             $where['charge_mobile'][2] = "or";
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $list = Model::ins("CusCustomerAgent")->pageList($where, "*", "id desc");
        
        foreach ($list['list'] as $k => $v) {
//             if($v['type'] == 2) {
//                 $list['list'][$k]['mobile'] = $v['charge_mobile'];
//             }
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $list['list'][$k]['mobile'] = $cus['mobile'];
            
            $area = CommonModel::getSysArea($v['area_code']);
            $list['list'][$k]['area'] = $area["code"] == "200" ? $area['data'] : $v['area'];
            
            //  切割加盟区县
            $join_area = '';
            $joinCodeArr = array_filter(explode(",", $v['join_code']));
            foreach ($joinCodeArr as $joinCode) {
                $area = CommonModel::getSysArea($joinCode);
                if($area["code"] == "200") {
                    $join_area .= $area["data"].",";
                }
            }
            $list['list'][$k]['join_area'] = substr($join_area, 0, -1);
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    public function layoutAction() {
        return $this->view();
    }
    
    /**
    * @user 待审核列表
    * @param 
    * @author jeeluo
    * @date 2017年4月29日下午4:27:45
    */
    public function waitAuditAction() {
        // 查询待审核列表
        $where = array();
        $where = $this->searchWhere([
            "join_code_city" => "=",
            "mobile" => "=",
            "addtime" => "times",
        ], $where);
        
        if(!empty($where['mobile'])) {
        
            $recombineWhere = array(
                "mobile" => $where['mobile'],
                "charge_mobile" => array("=", $where['mobile'], "or"),
            );
        }
        
        if(!empty($where['role_reco_city'])) {
            $recombineWhere['id'] = ["in", "select id from role_reco_city where join_code like '%".$where['join_code_city']."%'"];
        }
        
        if(!empty($where['addtime'])) {
            $recombineWhere['addtime'] = $where['addtime'];
        }
        
        $recombineWhere['status'] = 1;
        
        $list = Model::ins("RoleRecoCity")->pageList($recombineWhere, "*", "addtime desc, id desc");
        foreach ($list['list'] as $key => $v) {
            $list['list'][$key]['instroducermobile'] = CommonRoleModel::getCusRole(array("cus_role_id" => $v['cus_role_id']))['mobile'];
            if($v['type'] == 2) {
                $list['list'][$key]['mobile'] = $v['charge_mobile'];
            }
            
            $area = CommonModel::getSysArea($v['area_code']);
            $list['list'][$k]['area'] = $area["code"] == "200" ? $area['data'] : $v['area'];
            
            //  切割加盟区县
            $join_area = '';
            $joinCodeArr = array_filter(explode(",", $v['join_code']));
            foreach ($joinCodeArr as $joinCode) {
                $area = CommonModel::getSysArea($joinCode);
                if($area["code"] == "200") {
                    $join_area .= $area["data"].",";
                }
            }
            $list['list'][$key]['join_area'] = substr($join_area, 0, -1);
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    /**
     * @user 审核失败列表
     * @param
     * @author jeeluo
     * @date 2017年4月29日下午4:24:51
     */
    public function examFailListAction() {
        // 查询审核失败列表
        $where = array();
        $where = $this->searchWhere([
            "join_code_city" => "=",
            "mobile" => "=",
            "addtime" => "times",
        ], $where);
    
        $recombineWhere = array();
        
        if(!empty($where['mobile'])) {
            $recombineWhere = array(
                "mobile" => $where['mobile'],
                "charge_mobile" => array("=", $where['mobile'], "or"),
            );
        }
        
        if(!empty($where['join_code_city'])) {
            $recombineWhere['id'] = ["in", "select id from role_reco_city where join_code like '%".$where['join_code_city']."%'"];
        }
        
        if(!empty($where['addtime'])) {
            $recombineWhere['addtime'] = $where['addtime'];
        }
        
        $recombineWhere['status'] = 3;
        
        $list = Model::ins("RoleRecoCity")->pageList($recombineWhere, "*", "addtime desc, id desc");
        
        foreach ($list['list'] as $key => $v) {
            $list['list'][$key]['instroducermobile'] = CommonRoleModel::getCusRole(array("cus_role_id" => $v['cus_role_id']))['mobile'];
            if($v['type'] == 2) {
                $list['list'][$key]['mobile'] = $v['charge_mobile'];
            }
            $area = CommonModel::getSysArea($v['area_code']);
            $list['list'][$key]['area'] = $area["code"] == "200" ? $area['data'] : $v['area'];
            
            //  切割加盟区县
            $join_area = '';
            $joinCodeArr = array_filter(explode(",", $v['join_code']));
            foreach ($joinCodeArr as $joinCode) {
                $area = CommonModel::getSysArea($joinCode);
                if($area["code"] == "200") {
                    $join_area .= $area["data"].",";
                }
            }
            $list['list'][$key]['join_area'] = substr($join_area, 0, -1);
        }
    
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
    
        return $this->view($viewData);
    }
    
    /**
     * @user 审核成功
     * @param
     * @author jeeluo
     * @date 2017年4月7日上午11:14:33
     */
    public function updateExamStatusAction() {
        if(!$this->getParam('id') || !$this->getParam('status')) {
            $this->showError("请正确操作");
        }
    
        $params['id'] = $this->getParam("id");
        $params['status'] = $this->getParam("status");
        $params['remark'] = $this->getParam('remark');
    
        $result = Model::new("Customer.BullCity")->updateExamStatus($params);
    
        if($result["code"] != 200) {
            $this->showError($result['data']);
        }
        
        Model::new("Sys.Mq")->add([
            "url"=>"Customer.BullCity.examSend",
            "param"=>[
                "recoId"=>$params['id'],
            ],
        ]);
        Model::new("Sys.Mq")->submit();
    
        Model::ins("RoleRecoCity")->modify(array("examinetime" => getFormatNow(), "status" => $params['status'], "remark" => $params['remark']), array("id" => $params['id']));
        $this->showSuccess("操作成功");
    }
    
    /**
     * @user 审核失败
     * @param
     * @author jeeluo
     * @date 2017年4月7日上午11:14:25
     */
    public function examFailAction() {
        if(!$this->getParam("id") || !$this->getParam("status")) {
            $this->showError("请正确操作");
        }
        $action_arr['updateExam'] = "/Customer/BullCity/updateExamStatus?status=".$this->getParam("status")."&id=".$this->getParam("id");
    
        $viewData = array(
            "action_arr" => $action_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 添加城市代理
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午9:50:02
    */
    public function addCityAgentAction() {
        $action = "/Customer/BullCity/addAgent";
        // 获取可推荐孵化中心的角色范围
        $params['selfRoleType'] = self::cityRole;
    
        $roleReco = Model::new("User.RoleReco")->getNewRoleRecoedType($params);
    
        $roleRecoList = array();
        foreach ($roleReco as $k => $v) {
            $roleRecoList[$v] = $this->_roleLookup[$v];
        }
    
        $viewData = array(
            "title" => "添加运营中心",
            "actionUrl" => $action,
            "roleRecoList" => $roleRecoList,
        );
    
        return $this->view($viewData);
    }
    
    /**
    * @user 添加代理
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:05:09
    */
    public function addAgentAction() {
        $baseResult = $this->baseCheckAction($this->params);
        if($baseResult["code"] != "200") {
            $this->showError($baseResult['data']);
        }
        
        $params['type'] = $this->params['type'];
        $params['instroducerMobile'] = $this->params['instroducerMobile'];
        $params['instroducerRole'] = $this->params['instroducerRole'];
        
        if($params['type'] == 1) {
            $personResult = $this->personCheckAction($this->params);
            if($personResult["code"] != "200") {
                $this->showError($personResult['data']);
            }
            $params['realname'] = $this->params['realname'];
            $params['mobile'] = $this->params['mobile'];
            $params['area_code'] = $this->params['area_county'];
            $params['address'] = $this->params['address'];
            $params['join_code'] = $this->params['join_area_city'];
        } else {
            if(empty($this->params['licence_image']) || empty($this->params['corporation_image'])) {
                $this->showError("请填写完整的信息");
            }
            $companyResult = $this->companyCheckAction($this->params);
            if($companyResult["code"] != "200") {
                $this->showError($companyResult['data']);
            }
            
            $corporation_image = explode(",", $this->params['corporation_image']);
            if(count($corporation_image) != 2) {
                $this->showError("请上传身份证正反面");
            }
            
            $params['company_name'] = $this->params['company_name'];
            $params['charge_idnumber'] = $this->params['charge_idnumber'];
            $params['charge_name'] = $this->params['charge_name'];
            $params['charge_mobile'] = $this->params['charge_mobile'];
            $params['corporation_name'] = $this->params['corporation_name'];
            $params['corporation_idnumber'] = $this->params['corporation_idnumber'];
            $params['area_code'] = $this->params['area_county'];
            $params['address'] = $this->params['address'];
            $params['licence_image'] = $this->params['licence_image'];
            $params['corporation_image'] = $this->params['corporation_image'];
            $params['join_code'] = $this->params['join_area_city'];
        }
        $result = Model::new("Customer.BullCity")->addAgent($params);
        if($result["code"] != "200") {
            $this->showError($result['data']);
        }
        $this->showSuccess("添加成功");
    }
    
    /**
    * @user 编辑运营中心信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:13:39
    */
    public function editCityInfoAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        // 读取运营中心信息
        $agentInfo = Model::ins("CusCustomerAgent")->getRow(["id"=>$this->getParam("id"),"agent_type"=>1]);
        if(empty($agentInfo)) {
            $this->showError("数据不存在");
        }
        
        $cus = Model::ins("CusCustomer")->getRow(["id"=>$agentInfo['introducerid']],"mobile");
        $corporation_image_arr = explode(",",$agentInfo['corporation_image']);
        
        $action = "/Customer/BullCity/editAgent?id=".$agentInfo['id'];
        $viewData = array(
            "title" => "编辑运营中心",
            "actionUrl" => $action,
            "agentInfo" => $agentInfo,
            "cus" => $cus,
            "corporation_image" => $corporation_image_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 修改运营中心信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:59:10
    */
    public function editAgentAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        
        // 读取运营中心信息
        $agentInfo = Model::ins("CusCustomerAgent")->getRow(["id"=>$this->getParam("id"),"agent_type"=>1],"type");
        if(empty($agentInfo)) {
            $this->showError("数据不存在");
        }
        
        $type = $agentInfo['type'];
        if($type == 1) {
            $personResult = $this->personCheckAction($this->params);
            if($personResult["code"] != "200") {
                $this->showError($personResult['data']);
            }
            $params['realname'] = $this->params['realname'];
            $params['mobile'] = $this->params['mobile'];
            $params['address'] = $this->params['address'];
        } else {
            $companyResult = $this->companyCheckAction($this->params);
            if($companyResult["code"] != "200") {
                $this->showError($companyResult['data']);
            }
            if(!empty($this->params['corporation_image'])) {
                $corporation_image = explode(",", $this->params['corporation_image']);
                if(count($corporation_image) != 2) {
                    $this->showError("请上传身份证正反面");
                }
                
                $corporation_image_str = '';
                foreach ($corporation_image as $v) {
                    $corporation_image_str .= $v.",";
                }
                substr($corporation_image_str, 0, -1);
                $params['corporation_image'] = $corporation_image_str;
            }
            if(!empty($this->params['licence_image'])) {
                $params['licence_image'] = $this->params['licence_image'];
            }
            $params['company_name'] = $this->params['company_name'];
            $params['charge_idnumber'] = $this->params['charge_idnumber'];
            $params['charge_name'] = $this->params['charge_name'];
            $params['charge_mobile'] = $this->params['charge_mobile'];
            $params['address'] = $this->params['address'];
            $params['corporation_name'] = $this->params['corporation_name'];
            $params['corporation_idnumber'] = $this->params['corporation_idnumber'];
        }
        $params['id'] = $this->getParam("id");
        $result = Model::new("Customer.BullCity")->editAgent($params);
        if($result["code"] != "200") {
            $this->showError($result['data']);
        }
        $this->showSuccess("修改成功");
    }
    
    /**
    * @user 编辑推荐运营中心信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:59:28
    */
    public function editRecoInfoAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        // 读取推荐信息
        $recoInfo = Model::ins("RoleRecoCity")->getRow(["id"=>$this->getParam("id")]);
        if(empty($recoInfo)) {
            $this->showError("数据不存在");
        }
        
        $action = "/Customer/BullCity/editRecoCity?id=".$recoInfo['id'];
        
        // 分享者信息
        $cusRoleData = CommonRoleModel::getCusRole(array("cus_role_id"=>$recoInfo['cus_role_id']));
        
        $roleReco = Model::new("User.RoleReco")->getRoleRecoedType(array("selfRoleType"=>self::cityRole));
        $roleRecoList = array();
        foreach ($roleReco as $k => $v) {
            $roleRecoList[$v] = $this->_roleLookup[$v];
        }
        
        $viewData = array(
            "title" => "编辑运营中心",
            "actionUrl" => $action,
            "roleRecoList" => $roleRecoList,
            "recoInfo" => $recoInfo,
            "cusRoleData" => $cusRoleData,
        );
        return $this->view($viewData);
    }
    
    public function editRecoCityAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        // 读取推荐信息
        $recoInfo = Model::ins("RoleRecoCity")->getRow(["id"=>$this->getParam("id")]);
        if(empty($recoInfo)) {
            $this->showError("数据不存在");
        }
        
        $baseResult = $this->baseCheckAction($this->params);
        if($baseResult["code"] != "200") {
            $this->showError($baseResult["data"]);
        }
        $params['id'] = $this->getParam("id");
        $params['type'] = $this->params['type'];
        if($params['type'] == 1) {
            $personResult = $this->personCheckAction($this->params);
            if($personResult["code"] != "200") {
                $this->showError($personResult['data']);
            }
            $params['realname'] = $this->params['realname'];
            $params['mobile'] = $this->params['mobile'];
            $params['area_code'] = $this->params['area_county'];
            $params['address'] = $this->params['address'];
            $params['join_code'] = $this->params['join_area_city'];
        } else {
            if(empty($this->params['licence_image']) || empty($this->params['corporation_image'])) {
                $this->showError("请填写完整的信息");
            }
            $companyResult = $this->companyCheckAction($this->params);
            if($companyResult["code"] != "200") {
                $this->showError($companyResult["data"]);
            }
            $corporation_image = explode(",", $this->params['corporation_image']);
            if(count($corporation_image) != 2) {
                $this->showError("请上传身份证正反面");
            }
            $params['company_name'] = $this->params['company_name'];
            $params['charge_idnumber'] = $this->params['charge_idnumber'];
            $params['charge_name'] = $this->params['charge_name'];
            $params['charge_mobile'] = $this->params['charge_mobile'];
            $params['corporation_name'] = $this->params['corporation_name'];
            $params['corporation_idnumber'] = $this->params['corporation_idnumber'];
            $params['area_code'] = $this->params['area_county'];
            $params['address'] = $this->params['address'];
            $params['licence_image'] = $this->params['licence_image'];
            $params['corporation_image'] = $this->params['corporation_image'];
            $params['join_code'] = $this->params['join_area_city'];
        }
        
        $result = Model::new("Customer.BullCity")->editReco($params);
        if($result['code'] != "200") {
            $this->showError($result['data']);
        }
        $this->showSuccess("修改成功");
    }

    /**
    * @user 基本校验
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午10:21:53
    */
    private function baseCheckAction($params) {
        if(empty($params['type']) || empty($params['instroducerMobile']) || empty($params['instroducerRole'])) {
            return ["code" => "400", "data" => "请填写好类型或分享人信息"];
        }
        return ["code" => "200"];
    }
    
    /**
    * @user 个人类型校验
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午10:28:18
    */
    private function personCheckAction($params) {
        if(empty($params['realname']) || empty($params['mobile']) || empty($params['area_county']) || empty($params['address']) || empty($params['join_area_city'])) {
            return ["code" => "400", "data" => "请填写完整的信息"];
        }
        
        if(phone_filter($params['mobile'])) {
            return ["code" => "400", "data" => "手机号码格式不正确"];
        }
        
        return ["code" => "200"];
    }
    
    /**
    * @user 公司类型校验
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午10:42:17
    */
    private function companyCheckAction($params) {
        if(empty($params['company_name']) || empty($params['charge_idnumber']) || empty($params['charge_name']) || empty($params['charge_mobile']) || empty($params['area_county'])
            || empty($params['corporation_name']) || empty($params['corporation_idnumber']) || empty($params['join_area_city']) || empty($params['address'])) {
                return ["code" => "400", "data" => "请填写完整的信息"];
            }
        if(!CommonModel::validation_filter_idcard($params['charge_idnumber'])) {
            return ["code" => "400", "data" => "负责人身份证号码有误"];
        }
        if(phone_filter($params['charge_mobile'])) {
            return ["code" => "400", "data" => "负责人手机号码格式不正确"];
        }
        return ["code" => "200"];
    }
}