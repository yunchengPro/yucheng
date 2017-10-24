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
use app\lib\ApiService\Sms as SmsApi;
use app\model\Sys\CommonModel;
use app\model\Sys\CommonRoleModel;
use app\model\StoBusiness\StobusinessModel;
use app\model\Customer\BullStoModel;
class BullStoController extends ActionController {
    
    const title = "牛掌柜管理";
    const stoRole = 5;
    const minDisable = 1;
    const maxDisable = 9;
    const apisource = 1;
    const supersource = 2;
    
    protected $status_arr = array(1, 2, 3);
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
    
    /**
    * @user 已审核的列表
    * @param 
    * @author jeeluo
    * @date 2017年4月27日下午7:43:51
    */
    public function indexAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
            "businessname" => "like",
            "area_code_county" => "=",
            "area_code_city" => "=",
            "area_code_province" => "=",
//             "addtime" => "times",
        ], $where);
        
        $area_code = empty($where['area_code_county']) ? empty($where['area_code_city']) ? empty($where['area_code_province']) ? '' : $where['area_code_province'] : $where['area_code_city'] : $where['area_code_county'];
        
        // 因为功能，暂时用范围取值
        if(!empty($where['area_code_province'])) {
            if(!empty($where['area_code_city'])) {
                if(!empty($where['area_code_county'])) {
                    $where['id'] = ["in", "select id from sto_business_info where area_code = ".$area_code];
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
            $where['id'] = ["in", "select id from sto_business_info where area_code >= ".$area_code." and area_code < ".$ceil_code];
        }
        
        unset($where['area_code_county']);
        unset($where['area_code_city']);
        unset($where['area_code_province']);
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $where['ischeck'] = 1;
        $where['enable'] = 1;
        $where['isvip'] = -1;
        
        $stoList = Model::ins("StoBusiness")->pageList($where, "*", "addtime desc, id desc");
        $list['total'] = $stoList['total'];
        $list['list'] = array();
        foreach ($stoList['list'] as $key => $v) {
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $v['id']));
            $cus = Model::ins("CusCustomer")->getRow(array("id"=>$v['customerid']),"mobile");
            
            $list['list'][$key] = $stoInfo;
            $list['list'][$key]['mobile'] = $cus['mobile'];
            
            // 查看归属
            $cusRela = Model::ins("CusRelation")->getRow(array("customerid" => $v['customerid'], "role" => self::stoRole), "parentid");
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
    * @user vip牛掌柜列表
    * @param 
    * @author jeeluo
    * @date 2017年5月11日下午5:33:19
    */
    public function vipIndexAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
            "businessname" => "like",
        ], $where);
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $where['isvip'] = 1;
        $where['enable'] = 1;
        
        $stoList = Model::ins("StoBusiness")->pageList($where, "*", "id desc");
        $list['total'] = $stoList['total'];
        $list['list'] = array();
        
        foreach ($stoList['list'] as $key => $v) {
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $v['id']));
            
            $list['list'][$key] = $stoInfo;
            
            // 查看归属
            $cusRela = Model::ins("CusRelation")->getRow(array("customerid" => $v['customerid'], "role" => self::stoRole), "parentid");
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
    * @user vip 牛掌柜黑名单列表
    * @param 
    * @author jeeluo
    * @date 2017年5月11日下午5:33:48
    */
    public function vipBlackListAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
            "businessname" => "like",
        ], $where);
    
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
    
        $where['isvip'] = 1;
        $where['enable'] = -1;
    
        $stoList = Model::ins("StoBusiness")->pageList($where, "*", "id desc");
        $list['total'] = $stoList['total'];
        $list['list'] = array();
    
        foreach ($stoList['list'] as $key => $v) {
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $v['id']));
    
            $list['list'][$key] = $stoInfo;
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
    * @date 2017年4月27日下午7:44:09
    */
    public function waitAuditAction() {
        // 查询待审核列表
        $where = array();
        $where = $this->searchWhere([
            "sto_name" => "like",
            "mobile" => "like",
            "area_code" => "=",
            "addtime" => "times"
        ], $where);
        $where['status'] = 1;
        
        $list = Model::ins("RoleRecoSto")->pageList($where, "*", "addtime desc, id desc");
        
        foreach ($list['list'] as $key => $v) {
            $areaname = CommonModel::getSysArea($v['area_code']);
            if($areaname["code"] == "200") {
                $list['list'][$key]['areaname'] = $areaname['data'];
            } else {
                $list['list'][$key]['areaname'] = '';
            }
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
    * @user 审核失败列表
    * @param 
    * @author jeeluo
    * @date 2017年4月28日上午9:40:12
    */
    public function examFailListAction() {
        $where = array();
        $where = $this->searchWhere([
            "sto_name" => "like",
            "mobile" => "like",
            "area_code" => "=",
            "addtime" => "times"
        ], $where);
        $where['status'] = 3;
        
        $list = Model::ins("RoleRecoSto")->pageList($where, "*", "addtime desc, id desc");
        
        foreach ($list['list'] as $key => $v) {
            $areaname = CommonModel::getSysArea($v['area_code']);
            if($areaname["code"] == "200") {
                $list['list'][$key]['areaname'] = $areaname['data'];
            } else {
                $list['list'][$key]['areaname'] = '';
            }
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
    * @user 未上架牛掌柜
    * @param 
    * @author jeeluo
    * @date 2017年5月6日下午9:45:47
    */
    public function noShelvesAction() {
        $where = array();
        $where['ischeck'] = ["in", "-1,0,2"];
        $where = $this->searchWhere([
            "ischeck" => "=",
            "businessname" => "like",
            "mobile" => "=",
        ],$where);
        
        $where['enable'] = 1;
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $list = Model::ins("StoBusiness")->pageList($where, "*", "id desc");
        foreach ($list['list'] as $k => $sto) {
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $sto['customerid']));
            
            $list['list'][$k]['mobile'] = $cus['mobile'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 黑名单列表
    * @param 
    * @author jeeluo
    * @date 2017年4月28日上午9:39:04
    */
    public function blacklistAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
            "businessname" => "like",
        ],$where);
        $where['ischeck'] = 1;
        $where['enable'] = -1;
        $where['isvip'] = -1;
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile like '%".$where['mobile']."%'"];
            unset($where['mobile']);
        }
        
        $stoList = Model::ins("StoBusiness")->pageList($where, "*", "addtime desc, id desc");
        $list['total'] = $stoList['total'];
        $list['list'] = array();
        foreach ($stoList['list'] as $key => $v) {
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $v['id']));
        
            $list['list'][$key] = $stoInfo;
        }
         
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => self::title,
        );
        
        return $this->view($viewData);
    }
    
    public function updateExamStatusAction() {
        if(!$this->getParam('id') || !$this->getParam('status')) {
            $this->showError("请正确操作");
        }
        
        $params['id'] = $this->getParam("id");
        $params['status'] = $this->getParam("status");
        $params['remark'] = $this->getParam('remark');
        
        $result = Model::new("Customer.BullSto")->updateExamStatus($params);
        
        if($result["code"] != 200) {
            $this->showError($result['data']);
        }
        
        $this->showSuccess("操作成功");
    }
    
    public function examFailAction() {
        if(!$this->getParam("id") || !$this->getParam("status")) {
            $this->showError("请正确操作");
        }
        $action_arr['updateExam'] = "/Customer/BullSto/updateExamStatus?status=".$this->getParam("status")."&id=".$this->getParam("id");
        
        $viewData = array(
            "action_arr" => $action_arr,
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 设置vip
    * @param 
    * @author jeeluo
    * @date 2017年5月10日下午4:26:25
    */
    public function isvipAction() {
        if(!$this->getParam("id") || !$this->getParam("status")) {
            $this->showError("请正确操作");
        }
        
        $stoInfo = Model::ins("StoBusiness")->getRow(array("id" => $this->getParam("id")));
        
        if(empty($stoInfo)) {
            $this->showError("数据不存在");
        }
        
        $id = $this->getParam("id");
        $isvip = $this->getParam("status");
        
        $hasData = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$this->getParam("id")], "id");
        // 修改mogo数据
        if(empty($hasData)) {
            $insertStoMG =  Model::ins("StoBusinessInfo")->getRow(['id'=>$this->getParam("id")]);
            
            $insertStoMG['businessid'] = $insertStoMG['id'];
//             $StoBusinessImage = Model::ins('StoBusinessImage')->getRow(['business_id'=>$insertStoMG['id']],'thumb');
            
            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$insertStoMG['id']]);
          

         
                // 因为轮播图代替主图 所以功能屏蔽
//             if(empty($insertStoMG['businesslogo']))
//                 $insertStoMG['businesslogo'] = $StoBusinessImage['thumb'];
        
                $insertStoMG['loc'] =  [
                    'type' => 'Point',
                    'coordinates' => [
                        doubleval($insertStoMG['lngx']),//经度
                        doubleval($insertStoMG['laty'])//纬度
                    ]
                ];
                $insertStoMG['isshow'] = $isvip == 1 ? -1 : 1;

                $int_arr = [
                    'reutnproportion' => intval($reutnproportion),
                    'salecount' =>       intval($insertStoMG['salecount']),
                    'scores' =>         intval($insertStoMG['scores']),
                    'busstartime' =>     intval($insertStoMG['busstartime']),
                    'busendtime' =>        intval($insertStoMG['busendtime']),
                    'area_code' =>      intval($insertStoMG['area_code'])
                ];
                
                Model::Mongo('StoBusinessInfo')->insert($insertStoMG,$int_arr);

        } else {

            $insertStoMG =  Model::ins("StoBusinessInfo")->getRow(['id'=>$this->getParam("id")]);
            
            $insertStoMG['businessid'] = $insertStoMG['id'];
//             $StoBusinessImage = Model::ins('StoBusinessImage')->getRow(['business_id'=>$insertStoMG['id']],'thumb');
            
            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$insertStoMG['id']]);

            $int_arr = [
                    'reutnproportion' => intval($reutnproportion),
                    'salecount' =>       intval($insertStoMG['salecount']),
                    'scores' =>         intval($insertStoMG['scores']),
                    'busstartime' =>    intval($insertStoMG['busstartime']),
                    'busendtime' =>     intval($insertStoMG['busendtime']),
                    'area_code' =>      intval($insertStoMG['area_code'])
                ];
            
            Model::Mongo('StoBusinessInfo')->update(["id" => $this->getParam("id")], ["isvip"=>$isvip,"isshow"=>$isvip == 1 ? -1 : 1,"businessid"=>$hasData['id']],$int_arr);
        }
        
        Model::ins("StoBusiness")->modify(array("isvip" => $isvip), array("id" => $id));
        
        $status = Model::ins("StoBusinessInfo")->modify(array("isvip" => $isvip, "isshow" => $isvip == 1 ? -1 : 1), array("id" => $id));
        
        // 暂时为1
        Model::ins("CusRole")->modify(array("grade" => $isvip == 1 ? $isvip : 0), array("customerid" => $stoInfo['customerid'], "role" => self::stoRole));
        
        $this->showSuccess("操作成功");
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
        
        $busInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $this->getParam("id")));
        if(empty($busInfo)) {
            $this->showError("数据不存在");
        }
        
        $sto = Model::ins("StoBusiness")->getRow(array("id" => $this->getParam("id")));

        // 修改商家的禁用状态
        $status = Model::ins("StoBusinessInfo")->update(array("enable" => $enable), array("id" => $this->getParam("id")));
        $hasData = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$this->getParam("id")]);
    
        if($enable==-1){
            
            // 查询实体店基本信息
            $stoBaseInfo = Model::ins("StoBusinessBaseinfo")->getRow(["id"=>$sto['id']],"mobile");
            
            // 查询推荐记录
            $reco_sto = Model::ins("RoleRecoSto")->getRow(["mobile"=>$stoBaseInfo['mobile']],"cus_role_id,orderno");
            
            $parentUser = CommonRoleModel::getCusRole(["cus_role_id"=>$reco_sto['cus_role_id']]);
            
            if(!empty($reco_sto['orderno'])) {
//                 // 查询profit 流水记录
//                 $profitRecord = Model::ins("AmoFlowCusProfit")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>1,"userid"=>$parentUser['customerid']],"id");
//                 if(!empty($profitRecord['id'])) {
//                     // 查询是否已经扣除费用
//                     $profitReturnRecord = Model::ins("AmoFlowCusProfit")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>2,"userid"=>$parentUser['customerid']],"id");
                    
//                     $sharestode = Model::ins("LogSharestode")->getRow(["orderno"=>$reco_sto['orderno'],"userid"=>$parentUser['customerid']],"id");
//                     if(empty($profitReturnRecord) && empty($sharestode)) {
//                         // 没有返的记录 执行扣减
//                         Model::new("Amount.Role")->share_sto_de(["userid"=>$parentUser['customerid'],"orderno"=>$reco_sto['orderno'],"usertype"=>$parentUser['role']]);
//                     }
//                 }
                // v2.0.0
                // 查询 cash bull bonus 流水记录
                $cashRecord = Model::ins("AmoFlowCusCash")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>1,"userid"=>$parentUser['customerid']],"id");
                $bonusRecord = Model::ins("AmoFlowCusBonus")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>1,"userid"=>$parentUser['customerid']],"id");
                $bullRecord = Model::ins("AmoFlowCusBull")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>1,"userid"=>$parentUser['customerid']],"id");

                if(!empty($cashRecord['id']) || !empty($bonusRecord['id']) || !empty($bullRecord['id'])) {
                    // 查询是否已经扣除费用
                    $cashReturnRecord = Model::ins("AmoFlowCusCash")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>2,"userid"=>$parentUser['customerid']],"id");
                    $bonusReturnRecord = Model::ins("AmoFlowCusBonus")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>2,"userid"=>$parentUser['customerid']],"id");
                    $bullReturnRecord = Model::ins("AmoFlowCusBull")->getRow(["orderno"=>$reco_sto['orderno'],"direction"=>2,"userid"=>$parentUser['customerid']],"id");

                    $sharestode = Model::ins("LogSharestode")->getRow(["orderno"=>$reco_sto['orderno'],"userid"=>$parentUser['customerid']],"id");

                    if((empty($cashReturnRecord) || empty($bonusReturnRecord) || empty($bullReturnRecord)) && empty($sharestode)) {
                        // 没有返的记录 执行扣减
                        Model::new("Amount.Role")->share_sto_de(["userid"=>$parentUser['customerid'],"orderno"=>$reco_sto['orderno'],"usertype"=>$parentUser['role'],"customerid"=>$sto['customerid']]);
                    }
                }
            }
            
            // Model::Mongo('StoBusinessInfo')->delete(['id'=>$this->getParam("id")]);
            Model::ins("StoBusiness")->modify(["enable" => "-1"], ["id" => $this->getParam("id")]);
            Model::ins("CusRole")->modify(["enable" => "-1"], ["customerid" => $sto['customerid'], "role" => self::stoRole]);
            // 禁用实体店
            Model::ins("CusRelationStoEnable")->forbiddenSto(["customerid"=>$sto['customerid'],"role"=>self::stoRole]);
            Model::Mongo('StoBusinessInfo')->update(['id'=>$this->getParam("id")],['enable'=>"-1"]);
        }

        if($enable == 1){

            $insertStoMG =  Model::ins("StoBusinessInfo")->getRow(['id'=>$this->getParam("id")]);
           
            $insertStoMG['businessid'] =  $insertStoMG['id'];
//             $StoBusinessImage = Model::ins('StoBusinessImage')->getRow(['business_id'=>$insertStoMG['id']],'thumb');

            $business_code = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$insertStoMG['id']],'business_code');
            $insertStoMG['business_code'] = $business_code['business_code'];

            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$insertStoMG['id']]);
          
         
            // 因为轮播图代替主图 所以功能屏蔽
//             if(empty($insertStoMG['businesslogo']))
//                 $insertStoMG['businesslogo'] = $StoBusinessImage['thumb'];

            $insertStoMG['loc'] =  [
                 'type' => 'Point', 
                 'coordinates' => [
                     doubleval($insertStoMG['lngx']),//经度
                     doubleval($insertStoMG['laty'])//纬度
                 ]
            ];
            $hasData = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$this->getParam("id")]);
            Model::ins("StoBusiness")->modify(["enable" => "1"], ["id" => $this->getParam("id")]);
            Model::ins("CusRole")->modify(["enable" => 1], ["customerid" => $sto['customerid'], "role" => self::stoRole]);

            if(empty($hasData)){
                $int_arr = [
                    'reutnproportion' =>  intval($reutnproportion),
                    'salecount' =>        intval($insertStoMG['salecount']),
                    'scores' =>           intval($insertStoMG['scores']),
                    'busstartime' =>      intval($insertStoMG['busstartime']),
                    'busendtime' =>       intval($insertStoMG['busendtime']),
                    'area_code' =>        intval($insertStoMG['area_code'])
                ];
                Model::Mongo('StoBusinessInfo')->insert($insertStoMG,$int_arr);
            }else{
                $int_arr = [
                    'reutnproportion' =>  intval($reutnproportion),
                    'salecount' =>        intval($insertStoMG['salecount']),
                    'scores' =>           intval($insertStoMG['scores']),
                    'busstartime' =>      intval($insertStoMG['busstartime']),
                    'busendtime' =>       intval($insertStoMG['busendtime']),
                    'area_code' =>        intval($insertStoMG['area_code'])
                ];
                $insertStoMG['enable'] = 1;
                Model::Mongo('StoBusinessInfo')->update(['id'=>$this->getParam("id")],$insertStoMG,$int_arr);
            }
            
            // 启用实体店
            Model::ins("CusRelationStoEnable")->startUsingSto(["customerid"=>$sto['customerid'],"role"=>self::stoRole]);
        }
       
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 下架功能
    * @param 
    * @author jeeluo
    * @date 2017年6月14日下午8:12:41
    */
    public function ischeckAction() {
        if(!$this->getParam("id") || !$this->getParam("ischeck")) {
            $this->showError("请正确操作");
        }
        $sto = Model::ins("StoBusiness")->getRow(["id"=>$this->getParam("id")],"id,customerid");
        if(empty($sto)) {
            $this->showError("数据不存在");
        }
        
        $ischeck = $this->getParam("ischeck");
        
        $params['ischeck'] = $ischeck == 1 ? $ischeck : -1;

        $infoExam = Model::ins("StoBusinessInfoexam")->getRow(["customerid"=>$sto['customerid']],"id","id desc");
        $time = getFormatNow();
        if($ischeck == 1) {
            // 修改数据库数据
            $examResult = Model::new("Customer.BullSto")->examCheck(["ischeck"=>$ischeck,"id"=>$this->getParam("id")]);
            if($examResult['code'] != "200") {
                $this->showError($examResult['data']);
            }
        } else if($ischeck == -1){
            // 下架
            Model::ins("StoBusiness")->modify(["ischeck"=>2],["id"=>$sto['id']]);
            Model::ins("StoBusinessInfo")->modify(["isshow"=>-1],["id"=>$this->getParam("id")]);
            Model::ins("StoBusinessInfoexam")->modify(["status"=>1],["id"=>$infoExam['id']]);
            
            
            $stoUser = Model::ins("CusCustomer")->getRow(["id"=>$sto['customerid']],"mobile");
            if(!empty($stoUser['mobile'])) {
                // 发送短信通知
                $smsresult = SmsApi::api(([
                    "param" => json_encode([
                        "content" => "请规范信息"
                    ],JSON_UNESCAPED_UNICODE),
                    "mobile" => $stoUser['mobile'],
                    "code" => "SMS_94785035"
                ]));
                if(!$smsresult)
                    $this->showError("下架成功，短信发送失败");
            }
        } else if($ischeck == 3) {
            // 审核失败
            Model::ins("StoBusinessInfoexam")->modify(["status"=>3,"examinetime"=>$time],["id"=>$infoExam['id']]);
            Model::ins("StoBusiness")->modify(["ischeck"=>2],["id"=>$this->getParam("id")]);
            $this->showSuccess("操作成功");
        } else {
            $this->showError("请正确操作");
        }
                
        $hasData = Model::Mongo("StoBusinessInfo")->getRow(['id'=>$this->getParam('id')],"id");
        
        $insertStoMG = Model::ins("StoBusinessInfo")->getRow(["id"=>$this->getParam("id")]);
        //提交门店到蜂鸟审核
        $Baseinfo = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$insertStoMG['id']],'mobile,address');
        $chan_arr = [
            'businessname' => $insertStoMG['businessname'],
            'mobile'       => $Baseinfo['mobile'],
            'address'      => $insertStoMG['area'].$Baseinfo['address'],
            'lngx'         => $insertStoMG['lngx'],
            'laty'         => $insertStoMG['laty']
        ];
        BullStoModel::chainstore($chan_arr);
        // 修改mogo数据
        if(empty($hasData)) {
            $insertStoMG['businessid'] = $insertStoMG['id'];
        
            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$insertStoMG['id']]);
        
            $insertStoMG['loc'] = [
                'type' => 'Point',
                'coordinates' => [
                    doubleval($insertStoMG['lngx']),
                    doubleval($insertStoMG['laty'])
                ]
            ];
            $insertStoMG['isshow'] = $params['ischeck'];
            $int_arr = [
                'reutnproportion' =>    intval($reutnproportion),
                'salecount' =>          intval($insertStoMG['salecount']),
                'scores' =>             intval($insertStoMG['scores']),
                'busstartime' =>        intval($insertStoMG['busstartime']),
                'busendtime' =>         intval($insertStoMG['endtime']),
                'area_code' =>          intval($insertStoMG['area_code'])
            ];
            Model::Mongo('StoBusinessInfo')->insert($insertStoMG, $int_arr);
        } else {
            $int_arr = [
                'reutnproportion' =>    intval($reutnproportion),
                'salecount' =>          intval($insertStoMG['salecount']),
                'scores' =>             intval($insertStoMG['scores']),
                'busstartime' =>        intval($insertStoMG['busstartime']),
                'busendtime' =>         intval($insertStoMG['busendtime']),
                'area_code' =>          intval($insertStoMG['area_code'])
            ];
            Model::Mongo('StoBusinessInfo')->update(["id" => $this->getParam("id")], ["isshow"=>$params['ischeck'],"enable"=>$params['ischeck'],"businessname"=>$insertStoMG['businessname'],"businessid"=>$hasData['id'],'loc'=>[
                'type' => 'Point',
                'coordinates' => [
                    doubleval($insertStoMG['lngx']),
                    doubleval($insertStoMG['laty'])
                ]
            ],'lngx'=>$insertStoMG['lngx'],'laty'=>$insertStoMG['laty']],$int_arr);
        }
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 显示隐藏功能
    * @param 
    * @author jeeluo
    * @date 2017年5月12日下午4:09:20
    */
    public function isShowAction() {
        // 获取商家信息表数据
        if(!$this->getParam("id") || !$this->getParam('isshow')) {
            $this->showError("请正确操作");
        }
        
        $busInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $this->getParam("id")), "id,lngx,laty");
        if(empty($busInfo)) {
            $this->showError("数据不存在");
        }
        $params['id'] = $this->getParam("id");
        $params['isshow'] = $this->getParam("isshow");
        
        $hasData = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$this->getParam("id")], "id");
        // 修改mogo数据
        if(empty($hasData)) {
            $insertStoMG =  Model::ins("StoBusinessInfo")->getRow(['id'=>$this->getParam("id")]);
           
            $insertStoMG['businessid'] = $insertStoMG['id'];
//             $StoBusinessImage = Model::ins('StoBusinessImage')->getRow(['business_id'=>$insertStoMG['id']],'thumb');
            
            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$insertStoMG['id']]);
            
          
            // 因为轮播图代替主图 所以功能屏蔽
//             if(empty($insertStoMG['businesslogo']))
//                 $insertStoMG['businesslogo'] = $StoBusinessImage['thumb'];
            
            $insertStoMG['loc'] =  [
                'type' => 'Point',
                'coordinates' => [
                    doubleval($insertStoMG['lngx']),//经度
                    doubleval($insertStoMG['laty'])//纬度
                ]
            ];
            $insertStoMG['isshow'] = $params['isshow'];
            $int_arr = [
                    'reutnproportion' => intval($reutnproportion),
                    'salecount' =>       intval($insertStoMG['salecount']),
                    'scores' =>          intval($insertStoMG['scores']),
                    'busstartime' =>     intval($insertStoMG['busstartime']),
                    'busendtime' =>      intval($insertStoMG['busendtime']),
                    'area_code' =>       intval($insertStoMG['area_code'])
                ];
            Model::Mongo('StoBusinessInfo')->insert($insertStoMG,$int_arr);
        } else {

            $insertStoMG =  Model::ins("StoBusinessInfo")->getRow(['id'=>$this->getParam("id")]);
           
            $insertStoMG['businessid'] = $insertStoMG['id'];
//             $StoBusinessImage = Model::ins('StoBusinessImage')->getRow(['business_id'=>$insertStoMG['id']],'thumb');
            
            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$insertStoMG['id']]);

            $int_arr = [
                    'reutnproportion' => intval($reutnproportion),
                    'salecount' =>       intval($insertStoMG['salecount']),
                    'scores' =>          intval($insertStoMG['scores']),
                    'busstartime' =>     intval($insertStoMG['busstartime']),
                    'busendtime' =>      intval($insertStoMG['busendtime']),
                    'area_code' =>       intval($insertStoMG['area_code'])
                ];
            Model::Mongo('StoBusinessInfo')->update(["id" => $this->getParam("id")], ["isshow"=>$params['isshow'],"businessid"=>$hasData['id']],$int_arr);
        }
        // 修改数据库数据
        Model::ins("StoBusinessInfo")->modify(array("isshow" => $params['isshow']), array("id" => $params['id']));
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 编辑推荐牛掌柜信息操作
    * @param 
    * @author jeeluo
    * @date 2017年5月6日下午4:11:59
    */
    public function editRecoStoInfoAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        // 查询是否有该推荐信息
        $recoInfo = Model::ins("RoleRecoSto")->getRow(array("id" => $this->getParam("id")));
        
        if(empty($recoInfo)) {
            $this->showError("数据不存在");
        }
        
        if($recoInfo['status'] == 2) {
            $this->showError("请选择正确操作");
        }
        
        // 店铺类型
        $category = Model::ins("StoCategory")->getRow(array("id" => $recoInfo['sto_type_id']), "categoryname");
        
        $recoInfo['category_name'] = !empty($category['categoryname']) ? $category['categoryname'] : '无';
        
        $idnumber_image = array_filter(explode(",", $recoInfo['idnumber_image']));
        
        foreach ($idnumber_image as $key => $image) {
            $recoInfo['idnumber_arr'][$key] = !empty($image) ? $image : '';
        }
        
        // 处理折扣
        $recoInfo['discount'] /= 10;
        
        // 处理营业时间
        $recoInfo['sto_hour_begin'] = business_hour_format($recoInfo['sto_hour_begin']);
        $recoInfo['sto_hour_end'] = business_hour_format($recoInfo['sto_hour_end']);
        
        $recoInfo['delivery'] = !empty($recoInfo['delivery']) ? DePrice($recoInfo['delivery']) : '0.00';
        
        $recoRole = CommonRoleModel::getCusRole(array("cus_role_id" => $recoInfo['cus_role_id']));
        
        $recoInfo['instroducerRole'] = $recoRole['role'];
        $recoInfo['instroducerMobile'] = $recoRole['mobile'];
        
        $viewData = array(
            "recoInfo" => $recoInfo,
            "actionUrl" => "/Customer/BullSto/recoStoUpdate?id=".$this->getParam("id"),
        );
        
        return $this->view($viewData);
    }
    
    public function recoStoUpdateAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        if(empty($this->params['discount']) || empty($this->params['sto_hour_begin']) || empty($this->params['sto_hour_end']) || empty($this->params['service_type']) || empty($this->params['nearby_village'])) {
                $this->showError("请填写完整信息");
            }
            
        if(empty($this->params['area_county'])) {
            $this->showError("请填写地区详细信息");
        }
        
        $params['discount'] = intval($this->params['discount']*10);
        
        if($params['discount'] < 0 || $params['discount'] > 100) {
            $this->showError("折扣价有误");
        }
        
        $params['sto_hour_begin'] = business_int_datetime($this->params['sto_hour_begin']);
        $params['sto_hour_end'] = business_int_datetime($this->params['sto_hour_end']);
        
        if($params['sto_hour_begin'] % 5 != 0 || $params['sto_hour_end'] % 5 != 0) {
            $this->showError("营业时间 必须为09:00格式，而且必须以5分钟为一调整");
        }
        
        $params['area_code'] = $this->params['area_county'];
        $area = CommonModel::getSysArea($params['area_code']);
        $params['area'] = $area['data'];
        
        $temp_type = array("1" => 0, "2" => 0, "3" => 0);
        foreach ($this->params['service_type'] as $type) {
            $temp_type[$type] = $type;
        }
        $params['service_type'] = implode(",", $temp_type);
        $params['nearby_village'] = $this->params['nearby_village'];
        if(!empty($this->params['metro_id'])) {
            $params['metro_id'] = $this->params['metro_id'];
        }
        
        if(!empty($this->params['district_id'])) {
            $params['bus_district'] = $this->params['district_id'];
        }
        
        // 修改推荐表信息
        Model::ins("RoleRecoSto")->modify($params, array("id" => $this->getParam("id")));
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 添加牛掌柜页面
    * @param 
    * @author jeeluo
    * @date 2017年4月27日下午8:02:12
    */
    public function addBullStoAction() {
        // 获取添加类型
        $type = !empty($this->params['type']) ? $this->params['type'] : -1;
        $action = "/Customer/BullSto/addSto?type=".$type;
        
        // 获取可推荐牛掌柜的角色范围
        $params['selfRoleType'] = self::stoRole;
        
        $roleReco = Model::new("User.RoleReco")->getNewRoleRecoedType($params);
        
        $roleRecoList = array();
        foreach ($roleReco as $k => $v) {
            $roleRecoList[$v] = $this->_roleLookup[$v];
        }
        
        // 获取牛店类型
        $category = Model::new("StoBusiness.StoCategory")->formart_StoCategory();
        
        $optionCate = array();
        foreach ($category as $k => $v) {
            $optionCate[$v['id']] = $v['_categoryname'];
        }
        
        $viewData = array(
            "title" => "添加牛掌柜",
            "actionUrl" => $action,
            "roleRecoList" => $roleRecoList,
            "optionCate" => $optionCate,
        );
        
        return $this->view($viewData);
    }

    /**
    * @user 添加牛掌柜
    * @param 
    * @author jeeluo
    * @date 2017年5月6日下午3:40:30
    */
    public function addStoAction() {
        if(empty($this->params['instroducerRole']) || empty($this->params['instroducerMobile']) || empty($this->params['sto_type_id']) || empty($this->params['sto_name'])
            || empty($this->params['discount']) || empty($this->params['sto_hour_begin']) || empty($this->params['sto_hour_end']) || empty($this->params['nearby_village'])
            || empty($this->params['sto_mobile']) || empty($this->params['address']) || empty($this->params['idnumber']) || empty($this->params['sto_image'] || empty($this->params['main_image']))
            || empty($this->params['licence_image']) || empty($this->params['idnumber_image']) || empty($this->params['description']) || empty($this->params['type'])) {
            $this->showError("请填写完整信息");
        }
        
        $idnumber_arr = explode(",", $this->params['idnumber_image']);
        if(count($idnumber_arr) != 2) {
            $this->showError("请上传身份证正反面");
        }
        
        if(phone_filter($this->params['instroducerMobile'])) {
            $this->showError("引荐人手机号码规格有误");
        }

        if(empty($this->params['area_county'])) {
            $this->showError("请填写地区详细信息");
        }

        if(phone_filter($this->params['mobile'])) {
            $this->showError("手机号码规格有误");
        }
        
        $this->params['discount'] = intval($this->params['discount']*10);

        if($this->params['discount'] < 0 || $this->params['discount'] > 100) {
            $this->showError("折扣价有误");
        }
        
        $this->params['sto_begin'] = business_int_datetime($this->params['sto_hour_begin']);
        $this->params['sto_end'] = business_int_datetime($this->params['sto_hour_end']);
        
        if($this->params['sto_begin'] % 5 != 0 || $this->params['sto_end'] % 5 != 0) {
            $this->showError("营业时间 必须为09:00格式，而且必须以5分钟为一调整");
        }
        
        if(!CommonModel::validation_filter_idcard($this->params['idnumber'])) {
            $this->showError("身份证号码有误");
        }

        $this->params['orderno'] = CommonModel::getRoleOrderNo("NNHTSTO");
        
        $this->params['source'] = self::supersource;    // 标识来源 
        
        $result = Model::new("Customer.BullSto")->addSto($this->params);
        
        if($result['code'] != "200") {
            $this->showError($result['data']);
        }
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 异步获取地铁 商圈信息
    * @param 
    * @author jeeluo
    * @date 2017年4月29日上午11:02:07
    */
    public function getSysInfoAction() {
        $area_code = CommonModel::updateCityCode($this->params['area_code']);
        
        $result['metro'] = Model::new("User.RoleReco")->getMetroInfo($area_code);
        
        $result['district'] = Model::new("User.RoleReco")->getDistrictInfo($area_code);
        
        echo json_encode($result);
    }
    
    public function examStoInfoAction() {
        if(empty($this->params['customerid'])) {
            $this->showError("请选择正确操作");
        }
        
        // 用户最新一条记录
        $stoExamInfo = Model::ins("StoBusinessInfoexam")->getRow(["customerid"=>$this->params['customerid']],"*","id desc");
        
        if(empty($stoExamInfo)) {
            $sto = Model::ins("StoBusiness")->getRow(["customerid"=>$this->params['customerid']]);
            if(empty($sto)) {
                $this->showError("请选择正确数据");
            }
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(["id"=>$sto['id']],"busstartime,busendtime,isparking,iswifi,isdelivery,nearby_village,businesslogo");
            $stoBaseInfo = Model::ins("StoBusinessBaseinfo")->getRow(["id"=>$sto['id']],"discount,delivery,servicetel");
            $stoImage = Model::ins("StoBusinessImage")->getRow(["business_id"=>$sto['id']],"thumb","id asc");
            $albumImage = Model::ins("StoBusinessAlbum")->getRow(["business_id"=>$sto['id']],"thumb","id asc");
            $service_type = '';
            if($stoInfo['iswifi'] == 1) {
                $service_type .= "1,";
            }
            if($stoInfo['isparking'] == 1) {
                $service_type .= "2,";
                // $service_type .= "1,";
            }
            if($stoInfo['isdelivery'] == 1) {
                $service_type .= "3,";
                // $service_type .= "1,";
            }
            if($service_type != '') {
                $service_type = substr($service_type, 0, -1);
            }
            
            $stoExamInfo['sto_name'] = $sto['businessname'];
            $stoExamInfo['discount'] = $stoBaseInfo['discount'];
            $stoExamInfo['sto_hour_begin'] = $stoInfo['busstartime'];
            $stoExamInfo['sto_hour_end'] = $stoInfo['endtime'];
            $stoExamInfo['service_type'] = $service_type;
            $stoExamInfo['delivery'] = $stoBaseInfo['delivery'];
            $stoExamInfo['nearby_village'] = $stoInfo['nearby_village'];
            $stoExamInfo['sto_mobile'] = $stoBaseInfo['servicetel'];
            $stoExamInfo['main_image'] = $stoInfo['businesslogo'];
            $stoExamInfo['sto_image'] = $stoImage;
            $stoExamInfo['album_image'] = $albumImage;
        }
        
        $stoExamInfo['discount'] /= 10;
        // 处理营业时间
        $stoExamInfo['sto_hour_begin'] = business_hour_format($stoExamInfo['sto_hour_begin']);
        $stoExamInfo['sto_hour_end'] = business_hour_format($stoExamInfo['sto_hour_end']);
        $stoExamInfo['delivery'] = !empty($stoExamInfo['delivery']) ? DePrice($stoExamInfo['delivery']) : '0.00';
        
        $stoExamInfo['sto_image'] = array_filter(explode(",",$stoExamInfo['sto_image']));
        
        $viewData = array(
            "stoExamInfo"=>$stoExamInfo,
            "actionUrl"=>"/Customer/BullSto/examStoUpdate?id=".$this->params['id'],
        );
        return $this->view($viewData);
    }
    
    public function updateCheckAction() {
        if(empty($this->params['customerid']) || empty($this->params['status'])) {
            $this->showError("请选择正确操作");
        }
        
        // 用户最新一条记录
        $stoExamInfo = Model::ins("StoBusinessInfoexam")->getRow(["customerid"=>$this->params['customerid'],"status"=>1],"*","id desc");
        if(!empty($stoExamInfo)) {
            if($stoExamInfo['status'] == 2 && $this->params['status'] == -1) {
                // 记录已经通过，可还是执行拒绝操作
                $this->showError("操作有误，请选择正确操作");
            }
            
            $examResult = Model::new("Customer.BullSto")->examCheck($this->params);
            if($examResult['code'] != "200") {
                $this->showError($examResult['data']);
            }
        }
//         if(empty($stoExamInfo)) {
//             $this->showError("请选择正确的数据");
//         }
//         $this->params['examId'] = $stoExamInfo['id'];
        
//         if($stoExamInfo['status'] == 2 && $this->params['status'] == -1) {
//             // 记录已经通过，可还是执行拒绝操作
//             $this->showError("操作有误，请选择正确操作");
//         }
        
//         $examResult = Model::new("Customer.BullSto")->examCheck($this->params);
//         if($examResult["code"] != "200") {
//             $this->showError($examResult["data"]);
//         }
        
        $this->showSuccess("操作成功!");
    }
    
//     public function getAreaCodeAction() {
//         $position = Model::ins("SysArea")->getRow(array("id" => $this->params['area_code']), "position");
        
//         $parent_positionArr = explode(" ", $position['position']);
        
//         $result = array();
//         foreach ($parent_positionArr as $parent) {
//             // 拆分code值
//             $positionCode = explode("_", $parent);
//             if($positionCode[1] != 0) {
//                 $result[] = $positionCode[1];
//             }
//         }
        
//         $area_code = CommonModel::updateCityCode($this->params['area_code']);
        
//         $result['metro'] = Model::new("User.RoleReco")->getMetroInfo($area_code);
        
//         $result['district'] = Model::new("User.RoleReco")->getDistrictInfo($area_code);
        
//         echo json_encode($result);
//     }
}