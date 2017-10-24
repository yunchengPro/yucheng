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

class BullCountyController extends ActionController {
    const title = "孵化中心管理";
    const countyRole = 6;
    protected $_roleLookup = array('1' => '牛粉', '2' => '牛人', '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心");
    
    public function __construct() {
        parent::__construct();
    }
    
    public function layoutAction() {
        return $this->view();
    }
    
    /**
    * @user 审核成功列表
    * @param 
    * @author jeeluo
    * @date 2017年4月29日下午4:22:14
    */
    public function indexAction() {
        //  已审核列表
        $where = array();
        $where = $this->searchWhere([
            "join_code_county" => "=",
            "mobile" => "=",
        ],$where);
        
        if(!empty($where['join_code_county'])) {
            $where['id'] = ["in", "select id from cus_customer_agent where join_code like '%".$where['join_code_county']."%'"];
            unset($where['join_code_county']);
        }
        
        $where['enable'] = 1;
        $where['agent_type'] = 2;
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $list = Model::ins("CusCustomerAgent")->pageList($where, "*", "id desc");
        
        foreach ($list['list'] as $k => $v) {
            $join_area = '';
//             if($v['type'] == 2) {
//                 $list['list'][$k]['mobile'] = $v['charge_mobile'];
//             }
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $list['list'][$k]['mobile'] = $cus['mobile'];
            
            //  切割加盟区县
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
    
    /**
    * @user 待审核列表
    * @param 
    * @author jeeluo
    * @date 2017年4月29日下午4:22:27
    */
    public function waitAuditAction() {
        // 查询待审核列表
        $where = array();
        $where = $this->searchWhere([
            "join_code_county" => "=",
            "mobile" => "=",
            "addtime" => "times",
        ], $where);
        
        $recombineWhere = array();
        
        if(!empty($where['mobile'])) {
            
            $recombineWhere = array(
                "mobile" => $where['mobile'],
                "charge_mobile" => array("=", $where['mobile'], "or"),
            );
//             $where['charge_mobile'] = $where['mobile'];
//             $where['charge_mobile'][2] = "or";
        }
        
        if(!empty($where['join_code_county'])) {
            $recombineWhere['id'] = ["in", "select id from role_reco_county where join_code like '%".$where['join_code_county']."%'"];
//             $where['id'] = ["in", "select id from role_reco_county where join_code like '%".$where['join_code_county']."%'"];
//             unset($where['join_code_county']);
        }
        
        if(!empty($where['addtime'])) {
            $recombineWhere['addtime'] = $where['addtime'];
        }
        
        $recombineWhere['status'] = 1;
        
        $list = Model::ins("RoleRecoCounty")->pageList($recombineWhere, "*", "addtime desc, id desc");
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
    * @user 审核失败列表
    * @param 
    * @author jeeluo
    * @date 2017年4月29日下午4:24:51
    */
    public function examFailListAction() {
        // 查询审核失败列表
        $where = array();
        $where = $this->searchWhere([
            "join_code_county" => "=",
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
        
        if(!empty($where['join_code_county'])) {
            $recombineWhere['id'] = ["in", "select id from role_reco_county where join_code like '%".$where['join_code_county']."%'"];
        }
        
        if(!empty($where['addtime'])) {
            $recombineWhere['addtime'] = $where['addtime'];
        }
        
        $recombineWhere['status'] = 3;
        
        $list = Model::ins("RoleRecoCounty")->pageList($recombineWhere, "*", "addtime desc, id desc");
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
        
        $result = Model::new("Customer.BullCounty")->updateExamStatus($params);
        
        if($result["code"] != 200) {
            $this->showError($result['data']);
        }
        
        Model::new("Sys.Mq")->add([
            "url"=>"Customer.BullCounty.examSend",
            "param"=>[
                "recoId"=>$params['id'],
            ],
        ]);
        Model::new("Sys.Mq")->submit();
        
        Model::ins("RoleRecoCounty")->modify(array("examinetime" => getFormatNow(), "status" => $params['status'], "remark" => $params['remark']), array("id" => $params['id']));
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
        $action_arr['updateExam'] = "/Customer/BullCounty/updateExamStatus?status=".$this->getParam("status")."&id=".$this->getParam("id");
    
        $viewData = array(
            "action_arr" => $action_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 添加孵化操作请求
    * @param unknowtype
    * @author jeeluo
    * @date 2017年5月17日下午5:35:51
    */
    public function addCountyAgentAction() {
        $action = "/Customer/BullCounty/addAgent";
        // 获取可推荐孵化中心的角色范围
        $params['selfRoleType'] = self::countyRole;
        
        $roleReco = Model::new("User.RoleReco")->getNewRoleRecoedType($params);
        
        $roleRecoList = array();
        foreach ($roleReco as $k => $v) {
            $roleRecoList[$v] = $this->_roleLookup[$v];
        }
        
        $viewData = array(
            "title" => "添加孵化中心",
            "actionUrl" => $action,
            "roleRecoList" => $roleRecoList,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user  编辑推荐信息操作
    * @param 
    * @author jeeluo
    * @date 2017年6月17日下午3:18:12
    */
    public function editRecoInfoAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        // 读取推荐信息
        $recoInfo = Model::ins("RoleRecoCounty")->getRow(array("id" => $this->getParam("id")));
        if(empty($recoInfo)) {
            $this->showError("数据不存在");
        }

        $action = "/Customer/BullCounty/editRecoCounty?id=".$recoInfo["id"];
        
        // 分享者信息
        $cusRoleData = CommonRoleModel::getCusRole(array("cus_role_id"=>$recoInfo['cus_role_id']));
        
        $roleReco = Model::new("User.RoleReco")->getRoleRecoedType(array("selfRoleType"=>self::countyRole));
        
        $roleRecoList = array();
        foreach ($roleReco as $k => $v) {
            $roleRecoList[$v] = $this->_roleLookup[$v];
        }
        
        $viewData = array(
            "title" => "编辑孵化中心",
            "actionUrl" => $action,
            "roleRecoList" => $roleRecoList,
            "recoInfo" => $recoInfo,
            "cusRoleData" => $cusRoleData,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 编辑孵化中心信息
    * @param 
    * @author jeeluo
    * @date 2017年6月17日下午3:18:32
    */
    public function editCountyInfoAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        // 读取孵化中心信息
        $agentInfo = Model::ins("CusCustomerAgent")->getRow(["id"=>$this->getParam("id"),"agent_type"=>2]);
        if(empty($agentInfo)) {
            $this->showError("数据不存在");
        }
        
        $cus = Model::ins("CusCustomer")->getRow(['id'=>$agentInfo['introducerid']],"mobile");
        
        $corporation_image_arr = explode(",",$agentInfo['corporation_image']);
        
        $action = "/Customer/BullCounty/editAgent?id=".$agentInfo['id'];
        
        $viewData = array(
            "title" => "编辑孵化中心",
            "actionUrl" => $action,
            "agentInfo" => $agentInfo,
            "cus" => $cus,
            "corporation_image" => $corporation_image_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 编辑推荐孵化中心信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:33:45
    */
    public function editRecoCountyAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        // 读取推荐信息
        $recoInfo = Model::ins("RoleRecoCounty")->getRow(array("id" => $this->getParam("id")));
        if(empty($recoInfo)) {
            $this->showError("数据不存在");
        }
        
        if(empty($this->params['type'] || empty($this->params['instroducerMobile'])) || empty($this->params['instroducerRole'])) {
            $this->showError("请填写好类型或分享人信息");
        }
        $params['id'] = $this->getParam("id");
        $params['type'] = $this->params['type'];
        if($params['type'] == 1) {
            // 个人类型
            if(empty($this->params['realname']) || empty($this->params['mobile']) || empty($this->params['area_county']) || empty($this->params['address']) || empty($this->params['join_area_county'])) {
                $this->showError("请填写完整的信息");
            }
            if(phone_filter($this->params['mobile'])) {
                $this->showError("手机号码格式不正确");
            }
            
            $params['realname'] = $this->params['realname'];
            $params['mobile'] = $this->params['mobile'];
            $params['area_code'] = $this->params['area_county'];
            $params['address'] = $this->params['address'];
            $params['join_code'] = $this->params['join_area_county'];
        } else {
            // 公司类型
            if(empty($this->params['company_name']) || empty($this->params['charge_idnumber']) || empty($this->params['charge_name']) || empty($this->params['charge_mobile']) || empty($this->params['area_county'])
                || empty($this->params['corporation_name']) || empty($this->params['corporation_idnumber']) || empty($this->params['licence_image']) || empty($this->params['corporation_image']) || empty($this->params['join_area_county']) || empty($this->params['address'])) {
                    $this->showError("请填写完整的信息");
                }
            if(!CommonModel::validation_filter_idcard($this->params['charge_idnumber'])) {
                $this->showError("负责人身份证号码有误");
            }
            if(phone_filter($this->params['charge_mobile'])) {
                $this->showError("负责人手机号码格式不正确");
            }
            
            $idnumber_image = explode(",", $this->params['corporation_image']);
            if(count($idnumber_image) != 2) {
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
            $params['join_code'] = $this->params['join_area_county'];
        }
        
        $result = Model::new("Customer.BullCounty")->editReco($params);
        if($result['code'] != "200") {
            $this->showError($result['data']);
        }
        $this->showSuccess("修改成功");
    }
    
    /**
    * @user 添加孵化中心
    * @param 
    * @author jeeluo
    * @date 2017年5月17日下午5:36:08
    */
    public function addAgentAction() {
        if(empty($this->params['type']) || empty($this->params['instroducerMobile']) || empty($this->params['instroducerRole'])) {
            $this->showError("请填写好类型或分享人信息");
        }
        $params['type'] = $this->params['type'];
        $params['instroducerMobile'] = $this->params['instroducerMobile'];
        $params['instroducerRole'] = $this->params['instroducerRole'];
        if($params['type'] == 1) {
            // 个人类型
            if(empty($this->params['realname']) || empty($this->params['mobile']) || empty($this->params['area_county']) || empty($this->params['address']) || empty($this->params['join_area_county'])) {
                $this->showError("请填写完整的信息");
            }
            if(phone_filter($this->params['mobile'])) {
                $this->showError("手机号码格式不正确");
            }
            
            $params['realname'] = $this->params['realname'];
            $params['mobile'] = $this->params['mobile'];
            $params['area_code'] = $this->params['area_county'];
            $params['address'] = $this->params['address'];
            $params['join_code'] = $this->params['join_area_county'];
        } else {
            // 公司类型
            if(empty($this->params['company_name']) || empty($this->params['charge_idnumber']) || empty($this->params['charge_name']) || empty($this->params['charge_mobile']) || empty($this->params['area_county'])
                || empty($this->params['corporation_name']) || empty($this->params['corporation_idnumber']) || empty($this->params['licence_image']) || empty($this->params['corporation_image']) || empty($this->params['join_area_county']) || empty($this->params['address'])) {
                    $this->showError("请填写完整的信息");
                }
            if(!CommonModel::validation_filter_idcard($this->params['charge_idnumber'])) {
                $this->showError("负责人身份证号码有误");
            }
            if(phone_filter($this->params['charge_mobile'])) {
                $this->showError("负责人手机号码格式不正确");
            }
            
            $idnumber_image = explode(",", $this->params['corporation_image']);
            if(count($idnumber_image) != 2) {
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
            $params['join_code'] = $this->params['join_area_county'];
        }
        
        $result = Model::new("Customer.BullCounty")->addAgent($params);
        
        if($result["code"] != "200") {
            $this->showError($result['data']);
        }
        $this->showSuccess("添加成功");
    }
    
    /**
    * @user 修改代理信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午11:33:25
    */
    public function editAgentAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        
        // 读取孵化中心信息
        $agentInfo = Model::ins("CusCustomerAgent")->getRow(["id"=>$this->getParam("id"),"agent_type"=>2],"type");
        if(empty($agentInfo)) {
            $this->showError("数据不存在");
        }
        
        $type = $agentInfo['type'];
        
        if($type == 1) {
            if(empty($this->params['realname']) || empty($this->params['mobile']) || empty($this->params['address'])) {
                $this->showError("请填写完整的信息");
            }
            
            if(phone_filter($this->params['mobile'])) {
                $this->showError("手机格式不正确");
            }
            
            $params['realname'] = $this->params['realname'];
            $params['mobile'] = $this->params['mobile'];
            $params['address'] = $this->params['address'];
        } else {
            if(empty($this->params['company_name']) || empty($this->params['charge_idnumber']) || empty($this->params['charge_name']) || empty($this->params['charge_mobile'])
                || empty($this->params['corporation_name']) || empty($this->params['corporation_idnumber']) || empty($this->params['address'])) {
                    $this->showError("请填写完整的信息");
                }
            
            if(!CommonModel::validation_filter_idcard($this->params['charge_idnumber'])) {
                $this->showError("负责人身份证号码有误");
            }
            if(phone_filter($this->params['charge_mobile'])) {
                $this->showError("负责人手机号码格式不正确");
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
            
            $params['company_name'] = $this->params['company_name'];
            $params['charge_idnumber'] = $this->params['charge_idnumber'];
            $params['charge_name'] = $this->params['charge_name'];
            $params['charge_mobile'] = $this->params['charge_mobile'];
            $params['address'] = $this->params['address'];
            $params['corporation_name'] = $this->params['corporation_name'];
            $params['corporation_idnumber'] = $this->params['corporation_idnumber'];
            
            if(!empty($this->params['licence_image'])) {
                $params['licence_image'] = $this->params['licence_image'];
            }
        }
        
        $params['id'] = $this->getParam("id");
        
        $result = Model::new("Customer.BullCounty")->editAgent($params);
        if($result['code'] != "200") {
            $this->showError($result['data']);
        }
        $this->showSuccess("修改成功");
    }
}