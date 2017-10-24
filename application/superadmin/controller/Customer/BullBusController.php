<?php
// +----------------------------------------------------------------------
// |  [ 牛商管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-03-30
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;

use app\superadmin\ActionController;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;
use think\Config;
use app\model\Sys\CommonModel;

class BullBusController extends ActionController {
    
    const title = "牛商管理";
    const busRole = 4;
    protected $_roleLookup = array('1' => '牛粉', '2' => '牛人', '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心", "8" => "牛达人");

    public function __construct() {
        parent::__construct();
    }
    
    public function layoutAction() {
        return $this->view();
    }
    
    public function vipLayoutAction() {
        return $this->view();
    }
    
    public function indexAction() {
        $where = array();
        $where = $this->searchWhere([
            "businessname" => "like",
            "mobile" => "=",
            "area_code_county" => "=",
            "area_code_city" => "=",
            "area_code_province" => "=",
            "addtime" => "times"
        ], $where);
        
//         $area_code = !empty($where['area_code_county']) ? $where['area_code_county'] : !empty($where['area_code_city']) ? $where['area_code_city'] : !empty($where['area_code_province']) ? $where['area_code_province'] : '';
        $area_code = empty($where['area_code_county']) ? empty($where['area_code_city']) ? empty($where['area_code_province']) ? '' : $where['area_code_province'] : $where['area_code_city'] : $where['area_code_county'];

        // 因为功能，暂时用范围取值
        if(!empty($where['area_code_province'])) {
            if(!empty($where['area_code_city'])) {
                if(!empty($where['area_code_county'])) {
                    $where['id'] = ["in", "select id from bus_business_info where area_code = ".$where['area_code_county']];
                } else {
                    $ceil = substr($area_code,0,4)+1;
                    
                    $ceil_code = $ceil."00";
                }
            } else {
                
                $ceil = substr($area_code,0,2)+1;
                
                $ceil_code = $ceil."0000";
            }
        }
        
        if(!empty($ceil_code)) {
            $where['id'] = ["in", "select id from bus_business_info where area_code >= ".$area_code." and area_code < ".$ceil_code];
        }
        
        
//         if(!empty($where['area_code_county'])) {
//             $where['id'] = ["in", "select id from bus_business_info where area_code = ".$where['area_code_county']];
//         }

        unset($where['area_code_county']);
        unset($where['area_code_city']);
        unset($where['area_code_province']);
        
        $where['ischeck'] = 1;
        $where['enable'] = 1;
        $where['isvip'] = -1;
        
        
        if(!empty($where['mobile'])) {
//             if(!empty($where['id'])) {
//                 $where['id'] = ["in", "select id from bus_business_info where area_code = ".$where['area_code_county']." AND mobile like '%".$where['mobile']."%'"];
//             } else {
//                 $where['id'] = ["in", "select id from bus_business_info where mobile like '%".$where['mobile']."%'"];
//             }
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        unset($where['area_code_county']);
        
        $busList = Model::ins("BusBusiness")->pageList($where, "*", "addtime desc, id desc");
        
        $list['total'] = $busList['total'];
        $list['list'] = array();
        foreach ($busList['list'] as $key => $v) {
            $busInfo = Model::ins("BusBusinessInfo")->getRow(array("id" => $v['id']));
            $busInfo['enable'] = $v['enable'];
            $list['list'][$key] = $busInfo;
            
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $list['list'][$key]['mobile'] = $cus['mobile'];
            
            // 查看归属
            $cusRela = Model::ins("CusRelation")->getRow(array("customerid" => $v['customerid'], "role" => self::busRole), "parentid");
            if($cusRela['parentid'] > -1) {
                $parentInfo = Model::ins("CusCustomer")->getRow(array("id" => $cusRela['parentid']), "mobile");
            }
            $list['list'][$key]['parentMobile'] = $cusRela['parentid'] > -1 ? $parentInfo['mobile'] : '公司';
//             $areaname = CommonModel::getSysArea($busInfo['area_code']);
//             if($areaname["code"] == "200") {
//                 $list['list'][$key]['areaname'] = $areaname['data'];
//             } else {
//                 $list['list'][$key]['areaname'] = '';
//             }
        }
        
        
        
//         $bus = Model::ins("BusBusiness")->getRow(["id"=>$busInfo['id']],"customerid");
//         $parentBus = Model::ins("CusRelation")->getRow(["customerid"=>$bus['customerid'],"role"=>self::busRole],"parentid");
        
//         $busInfo['parentMobile'] = '';
//         if(!empty($parentBus)) {
//             if($parentBus['parentid'] > -1) {
//                 $parentInfo = Model::ins("CusCustomer")->getRow(["id"=>$parentBus['parentid']],"mobile");
//                 $busInfo['parentMobile'] = $parentInfo['mobile'] ?: '';
//             }
//         }
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 编辑售价方式
    * @param 
    * @author jeeluo
    * @date 2017年9月6日下午6:11:40
    */
    public function editPriceTypeAction() {
        if(!$this->getParam("id")) {
            $this->showError("请选择正确商家");
        }
        
        // 获取牛商的售价方式
        $bus = Model::ins("BusBusiness")->getRow(["id"=>$this->getParam("id")],"price_type");
        
        $actionUrl = "/Customer/BullBus/updatePriceType?id=".$this->getParam("id");
        
        $viewData = [
            "actionUrl" => $actionUrl,
            "bus" => $bus
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改售价方式
    * @param 
    * @author jeeluo
    * @date 2017年9月6日下午6:37:53
    */
    public function updatePriceTypeAction() {
        if(!$this->getParam("id")) {
            $this->showError("请选择正确商家");
        }
        
        if(empty($this->params['price_type'])) {
            $this->showError("请填写完信息");
        }
        
        $typeArr = $this->params['price_type'];
        $price_type = '';
        
        foreach ($typeArr as $type) {
            $price_type .= $type.",";
        }
        
        if($price_type != "") {
            substr($price_type, -1);
        }
        
        // 更新主表
        Model::ins("BusBusiness")->update(["price_type"=>$price_type],["id"=>$this->getParam("id")]);
        // 更新详情表
        Model::ins("BusBusinessInfo")->update(["price_type"=>$price_type],["id"=>$this->getParam("id")]);
        
        $this->showSuccess("修改成功");
    }
    
    /**
    * @user vip 牛商列表
    * @param 
    * @author jeeluo
    * @date 2017年5月8日下午9:13:31
    */
    public function vipIndexAction() {
        $where = array();
        $where['enable'] = 1;
        $where['isvip'] = 1;
        
        $where = $this->searchWhere([
            "businessname" => "like",
            "mobile" => "=",
            "area_code_county" => "=",
            "addtime" => "times"
        ],$where);
        
        if(!empty($where['area_code_county'])) {
            $where['id'] = ["in", "select id from bus_business_info where area_code = ".$where['area_code_county']];
        }
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $busList = Model::ins("BusBusiness")->pageList($where, "*", "id desc");
        
        $list['list'] = $busList['list'];
        $list['total'] = $busList['total'];
        foreach ($busList['list'] as $key => $v) {
            $busInfo = Model::ins("BusBusinessInfo")->getRow(array("id" => $v['id']));
            $busInfo['enable'] = $v['enable'];
            $list['list'][$key] = $busInfo;
            
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $list['list'][$key]['mobile'] = $cus['mobile'];
            
            // 查看归属
            $cusRela = Model::ins("CusRelation")->getRow(array("customerid" => $v['customerid'], "role" => self::busRole), "parentid");
            if($cusRela['parentid'] > -1) {
                $parentInfo = Model::ins("CusCustomer")->getRow(array("id" => $cusRela['parentid']), "mobile");
            }
            $list['list'][$key]['parentMobile'] = $cusRela['parentid'] > -1 ? $parentInfo['mobile'] : '公司';
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user vip 黑名单列表
    * @param 
    * @author jeeluo
    * @date 2017年5月11日下午5:21:40
    */
    public function vipBlackListAction() {
        $where = array();
        $where['isvip'] = 1;
        $where['enable'] = -1;
        
        $where = $this->searchWhere([
            "businessname" => "like",
            "mobile" => "=",
            "area_code_county" => "=",
            "addtime" => "times"
        ],$where);
        
        if(!empty($where['area_code_county'])) {
            $where['id'] = ["in", "select id from bus_business_info where area_code = ".$where['area_code_county']];
        }
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $busList = Model::ins("BusBusiness")->pageList($where, "*", "id desc");
        
        $list['list'] = $busList['list'];
        $list['total'] = $busList['total'];
        foreach ($busList['list'] as $key => $v) {
            $busInfo = Model::ins("BusBusinessInfo")->getRow(array("id" => $v['id']));
            $busInfo['enable'] = $v['enable'];
            $list['list'][$key] = $busInfo;
            
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $list['list'][$key]['mobile'] = $cus['mobile'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 待审核牛商列表
    * @param 
    * @author jeeluo
    * @date 2017年3月31日上午10:24:37
    */
    public function waitAuditAction() {
        // 查询待审核列表
        $where = array();
        $where = $this->searchWhere([
            "company_name" => "like",
            "mobile" => "like",
            "area_code_county" => "=",
            "area_code_city" => "=",
            "area_code_province" => "=",
            "addtime" => "times",
        ], $where);
        
        $where['status'] = 1;
        
        $area_code = empty($where['area_code_county']) ? empty($where['area_code_city']) ? empty($where['area_code_province']) ? '' : $where['area_code_province'] : $where['area_code_city'] : $where['area_code_county'];
        
        // 因为功能，暂时用范围取值
        if(!empty($where['area_code_province'])) {
            if(!empty($where['area_code_city'])) {
                if(!empty($where['area_code_county'])) {
                    $where['area_code'] = $area_code;
                } else {
                    $ceil = substr($area_code,0,4)+1;
        
                    $ceil_code = $ceil."00";
                }
            } else {
        
                $ceil = substr($area_code,0,2)+1;
        
                $ceil_code = $ceil."0000";
            }
        }
        
        if(!empty($ceil_code)) {
            $where['area_code'] = [[">=", $area_code],["<",$ceil_code]];
        }
        
//         if(!empty($where['area_code_county'])) {
//             $where['area_code'] = $where['area_code_county'];
//         }
        
        unset($where['area_code_county']);
        unset($where['area_code_city']);
        unset($where['area_code_province']);
        
        $list = Model::ins("RoleRecoBus")->pageList($where, "*", "addtime desc, id desc");
        
        foreach ($list['list'] as $key => $v) {
//             $areaname = CommonModel::getSysArea($v['area_code']);
//             if($areaname["code"] == "200") {
//                 $list['list'][$key]['areaname'] = $areaname['data'];
//             } else {
//                 $list['list'][$key]['areaname'] = '';
//             }
            $list['list'][$key]['instroducermobile'] = CommonRoleModel::getCusRole(array("cus_role_id" => $v['cus_role_id']))['mobile'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 审核失败列表页
    * @param 
    * @author jeeluo
    * @date 2017年4月1日下午6:02:33
    */
    public function examFailListAction() {
        // 查询待审核列表
        $where = array();
        $where = $this->searchWhere([
            "company_name" => "like",
            "mobile" => "like",
            "area_code_county" => "=",
            "area_code_city" => "=",
            "area_code_province" => "=",
            "addtime" => "times",
        ], $where);
        
        $where['status'] = 3;
        
        $area_code = empty($where['area_code_county']) ? empty($where['area_code_city']) ? empty($where['area_code_province']) ? '' : $where['area_code_province'] : $where['area_code_city'] : $where['area_code_county'];
        
        // 因为功能，暂时用范围取值
        if(!empty($where['area_code_province'])) {
            if(!empty($where['area_code_city'])) {
                if(!empty($where['area_code_county'])) {
                    $where['area_code'] = $area_code;
                } else {
                    $ceil = substr($area_code,0,4)+1;
        
                    $ceil_code = $ceil."00";
                }
            } else {
        
                $ceil = substr($area_code,0,2)+1;
        
                $ceil_code = $ceil."0000";
            }
        }
        
        if(!empty($ceil_code)) {
            $where['area_code'] = [[">=", $area_code],["<",$ceil_code]];
        }
        
        unset($where['area_code_county']);
        unset($where['area_code_city']);
        unset($where['area_code_province']);
        
        $list = Model::ins("RoleRecoBus")->pageList($where, "*", "addtime desc, id desc");
        
        foreach ($list['list'] as $key => $v) {
            $list['list'][$key]['instroducermobile'] = CommonRoleModel::getCusRole(array("cus_role_id" => $v['cus_role_id']))['mobile'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }

    /**
    * @user 黑名单列表
    * @param 
    * @author jeeluo
    * @date 2017年4月7日下午3:10:07
    */
    public function blackListAction() {
        // 黑名单列表
        $where = array();
        $where = $this->searchWhere([
            "businessname" => "like",
            "addtime" => "times",
        ], $where);
        
        $where['enable'] = -1;
        $where['isvip'] = -1;
        
        $busList = Model::ins("BusBusiness")->pageList($where, "*", "addtime desc, id desc");
        
        $list['total'] = $busList['total'];
        $list['list'] = array();
        foreach ($busList['list'] as $key => $v) {
            $busInfo = Model::ins("BusBusinessInfo")->getRow(array("id" => $v['id']));
        
            $list['list'][$key] = $busInfo;
            
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']), "mobile");
            $list['list'][$key]['mobile'] = $cus['mobile'];
        
            // 查看归属
            $cusRela = Model::ins("CusRelation")->getRow(array("customerid" => $v['customerid'], "role" => self::busRole), "parentid");
            if($cusRela['parentid'] > -1) {
                $parentInfo = Model::ins("CusCustomer")->getRow(array("id" => $cusRela['parentid']), "mobile");
            }
            $list['list'][$key]['parentMobile'] = $cusRela['parentid'] > -1 ? $parentInfo['mobile'] : '公司';
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改审核状态操作
    * @param 
    * @author jeeluo
    * @date 2017年3月31日下午2:17:11
    */
    public function updateExamStatusAction() {
        if(!$this->getParam('id') || !$this->getParam('status')) {
            $this->showError("请正确操作");
        }
        
        $params['id'] = $this->getParam('id');
        $params['status'] = $this->getParam("status");
        $params['remark'] = $this->getParam('remark');

        $result = Model::new("Customer.BullBus")->updateExamStatus($params);
        
        if($result["code"] != 200) {
            $this->showError($result['data']);
        }
        
        Model::new("Sys.Mq")->add([
            "url"=>"Customer.BullBus.examSend",
            "param"=>[
                "recoId"=>$params['id'],
            ],
        ]);
        Model::new("Sys.Mq")->submit();
        
//         if($params['status'] == 2) {
//             return "<script>var tip = new top.YSL.Tip('操作成功',1);parent.goto2('/Customer/BullBus/waitAudit');parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));</script>";
//         } else if($params['status'] == 3) {
//             return "<script>var tip = new top.YSL.Tip('操作成功',1);parent.goto2('/Customer/BullBus/waitAudit');parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));</script>";
//         }
        $this->showSuccess("操作成功");
//         $this->showSuccess("/Customer/BullBus/waitAudit");
    }
    
    /**
    * @user 审核失败操作 
    * @param 
    * @author jeeluo
    * @date 2017年4月1日上午9:59:57
    */
    public function examFailAction() {
        if(!$this->getParam('id') || !$this->getParam('status')) {
            $this->showError("请正确操作");
        }
        
        $action_arr['updateExam'] = "/Customer/BullBus/updateExamStatus?status=".$this->getParam("status")."&id=".$this->getParam("id");
        
        $viewData = array(
            "action_arr" => $action_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 查看推荐牛商信息操作
    * @param 
    * @author jeeluo
    * @date 2017年4月1日下午3:32:43
    */
    public function editRecoBusInfoAction() {
        // 获取推荐信息表中数据
        if(!$this->getParam('id')) {
            $this->showError("请正确操作");
        }
        
        // 因为推荐信息审核通过之后，查看之后看到的数据非推荐表中数据，所以在这里需先确保该信息id 为非审核通过
        $recoInfo = Model::ins("RoleRecoBus")->getRow(array("id" => $this->getParam("id")));
        
        if(empty($recoInfo)) {
            $this->showError("数据不存在");
        }
        if($recoInfo['status'] == 2) {
            $this->showError("请选择正确操作");
        }

        $idnumber_image = array_filter(explode(",", $recoInfo['idnumber_image']));

        foreach ($idnumber_image as $key => $image) {
            $recoInfo['idnumber_arr'][$key] = $image ?: '';
        }
        
        $recoRole = CommonRoleModel::getCusRole(array("cus_role_id" => $recoInfo['cus_role_id']));
        
        $recoInfo['instroducerRole'] = $recoRole['role'];
        $recoInfo['instroducerMobile'] = $recoRole['mobile'];
        
        $viewData = array(
            "recoInfo" => $recoInfo,
            "actionUrl" => "/Customer/BullBus/recoBusUpdate?id=".$this->getParam("id"),
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 修改推荐信息
    * @param 
    * @author jeeluo
    * @date 2017年5月6日下午2:14:55
    */
    public function recoBusUpdateAction() {
        if(empty($this->params['instroducerRole']) || empty($this->params['instroducerMobile']) || empty($this->params['price_type'])) {
            $this->showError("请填写必填信息");
        }
        
        $result = Model::new("Customer.BullBus")->updateRecoInfo($this->params);
        if($result["code"] != "200") {
            $this->showError($result['data']);
        }
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 查看商家信息
    * @param 
    * @author jeeluo
    * @date 2017年4月1日下午4:23:46
    */
    public function editBusInfoAction() {
        // 获取商家信息表中数据
        if(!$this->getParam('id')) {
            $this->showError("请正确操作");
        }
        
        $bus = Model::ins("BusBusiness")->getRow(array("id" => $this->getParam("id")));
        
        if(empty($bus)) {
            $this->showError("数据不存在");
        }
        
        $busInfo = Model::ins("BusBusinessInfo")->getRow(array("id" => $this->getParam('id')));
        
        $busInfo['servicetel'] = !empty($bus['servicetel']) ? $bus['servicetel'] : '无';
        $busInfo['description'] = !empty($bus['description']) ? $bus['description'] : '无';
        $busInfo['businessintro'] = !empty($bus['businessintro']) ? $bus['businessintro'] : '无';
        $busInfo['scores'] = scoresFormat($busInfo['scores']);
        
        $viewData = array(
            "busInfo" => $busInfo,
            "actionUrl" => "/Customer/BullBus/busInfoUpdate?id=".$this->getParam("id"),
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 禁用操作
    * @param 
    * @author jeeluo
    * @editor isir 2017-4-24 17:03:02 
    * @date 2017年4月1日下午5:37:46
    */
    public function enableAction() {

        $enable = $this->getParam('enable');

        // 获取商家信息表中数据
        if(!$this->getParam("id") || !$this->getParam('enable')) {
            $this->showError("请正确操作");
        }
        

        $busInfo = Model::ins("BusBusiness")->getRow(array("id" => $this->getParam("id")));
        if(empty($busInfo)) {
            $this->showError("数据不存在");
        }

        $params['id'] = $this->getParam("id");
        $params['enable'] = $this->getParam("enable");



        // 修改商家的禁用状态
        $status = Model::ins("BusBusiness")->modify(array("enable" => $enable), array("id" => $this->getParam("id")));
       
        if($status) {
               $proData = Model::ins('ProProduct')->getList(['businessid'=>$this->getParam("id")],'id');


                foreach ($proData as $key => $value) {

                    Model::ins('ProProduct')->update(['enable'=>$enable],['id'=>$value['id']]);
                    Model::Es("ProProduct")->update(['enable'=>$enable],['id'=>$value['id']]);
                }
       
            $this->showSuccess("操作成功");
        }
    }
    
    /**
    * @user 设置vip
    * @param 
    * @author jeeluo
    * @date 2017年5月9日上午10:01:30
    */
    public function isvipAction() {
        if(!$this->getParam("id") || !$this->getParam("status")) {
            $this->showError("请正确操作");
        }
        
        $busInfo = Model::ins("BusBusiness")->getRow(array("id" => $this->getParam("id")));
        if(empty($busInfo)) {
            $this->showError("数据不存在");
        }
        
        $id = $this->getParam("id");
        
        $isvip = $this->getParam("status");
        
        Model::ins("BusBusiness")->modify(array("isvip" => $isvip), array("id" => $id));
        
        $status = Model::ins("BusBusinessInfo")->modify(array("isvip" => $isvip), array("id" => $id));
        
        //暂时为1
        Model::ins("CusRole")->modify(array("grade" => $isvip == 1 ? $isvip : 0), array("customerid" => $busInfo['customerid'], "role" => self::busRole));
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 添加供应商
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午3:44:36
    */
    public function addBullBusAction() {
        
        // 获取添加类型
        $type = !empty($this->params['type']) ? $this->params['type'] : -1;
        $action = "/Customer/BullBus/addBus?type=".$type;
        
        // 获取可推荐牛商的角色范围
        $params['selfRoleType'] = self::busRole;
        
        $roleReco = Model::new("User.RoleReco")->getNewRoleRecoedType($params);
        
        $roleRecoList = array();
        foreach ($roleReco as $k => $v) {
            $roleRecoList[$v] = $this->_roleLookup[$v];
        }
        
        $viewData = array(
            "title" => "添加供应商",
            "actionUrl" => $action,
            "roleRecoList" => $roleRecoList,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 添加供应商
    * @param 
    * @author jeeluo
    * @date 2017年4月28日上午10:44:48
    */
    public function addBusAction() {
//         if(empty($this->params['instroducerRole']) || empty($this->params['instroducerMobile']) || empty($this->params['company_name']) || empty($this->params['person_charge']) || empty($this->params['mobile'])
//             || empty($this->params['corporation']) || empty($this->params['company_area']) || empty($this->params['price_type']) || empty($this->params['idnumber']) || empty($this->params['licence_image'])
//             || empty($this->params['idnumber_image']) || empty($this->params['company_logo']) || empty($this->params['type'])) {
//                 $this->showError("请填写完整信息");
//             }

        if(empty($this->params['instroducerRole']) || empty($this->params['instroducerMobile']) || empty($this->params['company_name']) || empty($this->params['person_charge']) || empty($this->params['mobile'])
            || empty($this->params['corporation']) || empty($this->params['company_area']) || empty($this->params['price_type']) || empty($this->params['licence_image'])
            || empty($this->params['company_logo']) || empty($this->params['type'])) {
                $this->showError("请填写完整信息");
            }
            
//         $idnumber_arr = explode(",", $this->params['idnumber_image']);
//         if(count($idnumber_arr) != 2) {
//             $this->showError("请上传身份证正反面");
//         }
            
        if(empty($this->params['area_county'])) {
            $this->showError("请填写地区详细到县");
        }
            
        if(phone_filter($this->params['mobile'])) {
            $this->showError("手机号码规格有误");
        }
        
//         if(!CommonModel::validation_filter_idcard($this->params['idnumber'])) {
//             $this->showError("身份证号码有误");
//         }       
        
        $this->params['orderno'] = CommonModel::getRoleOrderNo("NNHTBUS");
        
        $result = Model::new("Customer.BullBus")->addBus($this->params);
        
        if($result["code"] != "200") {
            $this->showError($result["data"]);
        }
        
        $this->showSuccess("添加成功");
    }
    
    /**
    * @user 快速添加供应商
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午3:36:47
    */
    public function quickAddBullBusAction() {
        $action = "/Customer/BullBus/quickBus";
        
        $viewData = array(
            "title" => "快速添加供应商",
            "actionUrl" => $action,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 快速添加供应商
    * @param 
    * @author jeeluo
    * @date 2017年4月25日下午2:13:05
    */
    public function quickBusAction() {
        if(empty($this->params['mobile']) || empty($this->params['company'])) {
            $this->showError("请填写完整信息");
        }
        
        if(phone_filter($this->params['mobile'])) {
            $this->showError("请填写正确的手机号码");
        }
        
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $this->params["mobile"]));
        
        if(empty($cus)) {
            $insertArr = array("mobile" => $this->params['mobile'], "username" => $this->params['mobile'], "userpwd" => "E0ADC4BAABBE6E0F20F88E", "enable" => 1, "createtime" => getFormatNow());
            $insert_id = Model::ins("CusCustomer")->insert($insertArr);
            
            $bus = Model::ins("BusBusiness")->getRow(array("customerid" => $insert_id));
            if(empty($bus)) {
                $busArr = array("price_type" => '1,2', "businessname" => $this->params['company'], "addtime" => getFormatNow(), "ischeck" => 1, "enable" => 1, "customerid" => $insert_id);
                $bus_id = Model::ins("BusBusiness")->insert($busArr);
                
                $busInfoArr = array("id" => $bus_id, "price_type" => "1,2", "businessname" => $this->params['company'], "addtime" => getFormatNow());
                Model::ins("BusBusinessInfo")->insert($busInfoArr);
                
                $this->showSuccess("添加成功");
            } else {
                $this->showError("数据异常，请联系管理员");
            }
        } else {
            $this->showError("该手机用户已存在，添加失败");
        }
    }
}