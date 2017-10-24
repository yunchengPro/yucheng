<?php
namespace app\model\Sys;

use app\model\CusRelationOrModel;
use app\model\CusRelationEnModel;
use app\model\CusRelationModel;
use app\model\AmoFlowCusCashModel;
use app\model\AmoFlowCusProfitModel;
use app\model\AmoFlowCusBullModel;
use app\model\CusRoleModel;
use app\model\CusCustomerModel;
use app\lib\Model;
use app\model\User\FlowModel;
use app\model\AmoFlowFutCusCashModel;
use think\Config;
use app\lib\Img;
use app\model\AmoFlowCusComCashModel;

class CommonRoleModel
{
    const orRecoMaxBusiness = 30;
//     const orRecoLevelSto = 50;
    const orRecoLevelSto = 60;
    const orRecoLevelRole = 30;
    const ndRecoLevelRole = 60;
    
    const ndRecoMaxBusiness = 60;
//     const ndRecoLevelSto = 100;
    const ndRecoLevelSto = 120;
    /**
    * @user 获取用户角色牛粉列表
    * @param 
    * @author jeeluo
    * @date 2017年3月29日上午10:34:35
    */
    public static function getUserRoleFans($customerid, $role) {
//         $where = "role = 1 AND parentrole = ".$role." AND (parentid = ".$customerid." OR grandpaid = ".$customerid.")";
//         $where = "role = 1 AND (parentid = ".$customerid." OR grandpaid = ".$customerid.")";
//         $where = "(role = 1 AND parentrole = ".$role." AND parentid = ".$customerid.") OR (role = 1 AND grandparole = ".$role." AND grandpaid =".$customerid.")";
        
        $where['role'] = 1;
        $where['parentrole'] = $role;
        $where['parentid'] = $customerid;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, customerid, parentid, grandpaid", "id desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = $v['parentid'] == $customerid ? 1 : 2;
            // $userRoleList['list'][$key]['flowName'] = $v['parentid'] == $customerid ? '牛粉A' : '牛粉B';
//             $userRoleList['list'][$key]['flowName'] = '消费分享运营者';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户消费者角色关系链
    * @param 
    * @author jeeluo
    * @date 2017年3月28日上午10:20:14
    */
    public static function getUserRoleOr($customerid, $role) {
//         $where = array("parentid" => $customerid, "grandpaid" => array("=", $customerid, "or"));
//         $where = "role = 2 AND parentrole = ".$role." AND (parentid = ".$customerid." OR grandpaid = ".$customerid.")";
        $where['role'] = 2;
        $where['parentrole'] = $role;
        $where['parentid'] = $customerid;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, customerid, parentid, grandpaid", "addtime desc, id desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = $v['parentid'] == $customerid ? 1 : 2;
            // $userRoleList['list'][$key]['flowName'] = $v['parentid'] == $customerid ? '牛人A' : '牛人B';
//             $userRoleList['list'][$key]['flowName'] = '消费分享运营者';
        }
        return $userRoleList;
    }
    
    public static function getUserRoleND($customerid,$role) {
//         $where = "role = 8 AND parentrole = ".$role." AND (parentid = ".$customerid." OR grandpaid = ".$customerid.")";

        $where['role'] = 8;
        $where['parentrole'] = $role;
        $where['parentid'] = $customerid;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, customerid, parentid, grandpaid", "addtime desc, id desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = $v['parentid'] == $customerid ? 1 : 2;
//             $userRoleList['list'][$key]['flowName'] = '消费分享运营者';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户创业者角色关系链
    * @param 
    * @author jeeluo
    * @date 2017年3月28日上午10:20:37
    */
    public static function getUserRoleEn($customerid, $role) {
//         $where = array("parentid" => $customerid, "grandpaid" => array("=", $customerid, "or"));
//         $where = "role = 3 AND parentrole = ".$role." AND (parentid = ".$customerid." OR grandpaid = ".$customerid.")";
        $where['role'] = 3;
        $where['parentrole'] = $role;
        $where['parentid'] = $customerid;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, customerid, parentid, grandpaid", "addtime desc");
    
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = $v['parentid'] == $customerid ? 1 : 2;
            // $userRoleList['list'][$key]['flowName'] = $v['parentid'] == $customerid ? '牛创客A' : '牛创客B';
//             $userRoleList['list'][$key]['flowName'] = '消费分享运营者';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户牛商角色关系链
    * @param 
    * @author jeeluo
    * @date 2017年3月28日下午5:45:12
    */
    public static function getUserRoleBus($customerid) {
        $where['parentid'] = $customerid;
        $where['role'] = 4;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, parentid, grandpaid", "addtime desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = 1;
            $userRoleList['list'][$key]['flowName'] = '';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户牛掌柜角色关系链
    * @param 
    * @author jeeluo
    * @date 2017年3月28日下午5:45:24
    */
    public static function getUserRoleSto($customerid) {
        $where['parentid'] = $customerid;
        $where['role'] = 5;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, parentid, grandpaid", "addtime desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = 1;
            $userRoleList['list'][$key]['flowName'] = '';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户孵化中心角色关系链
    * @param 
    * @author jeeluo
    * @date 2017年3月29日上午9:34:51
    */
    public static function getUserCounty($customerid) {
        $where['parentid'] = $customerid;
        $where['role'] = 6;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, parentid, grandpaid", "addtime desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = 1;
            $userRoleList['list'][$key]['flowName'] = '';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户运营中心角色关系链
    * @param 
    * @author jeeluo
    * @date 2017年3月29日上午9:35:24
    */
    public static function getUserCity($customerid) {
        $where['parentid'] = $customerid;
        $where['role'] = 7;
        $roleRelaOBJ = new CusRelationModel();
        $userRoleList = $roleRelaOBJ->pageList($where, "id, parentid, grandpaid", "addtime desc");
        
        foreach ($userRoleList['list'] as $key => $v) {
            $userRoleList['list'][$key]['level'] = 1;
            $userRoleList['list'][$key]['flowName'] = '';
        }
        return $userRoleList;
    }
    
    /**
    * @user 获取用户角色的阶段性现金收益
    * @param 
    * @author jeeluo
    * @date 2017年3月29日下午5:51:41
    */
    public static function getUserRoleCash($params) {
        $where['userid'] = $params['customerid'];
        $where['direction'] = $params['direction'];
        if(!empty($params['role'])) {
            $flowOBJ = new FlowModel();
//             $where['role'] = $params['role'];
            $where['profit_role'] = array("in", $flowOBJ->recoProfitRole($params['role']));
        }
        
        if(!empty($params['flowtype'])) {
            $where['flowtype'] = array("in", $params['flowtype']);
        }
        if(!empty($params['begintime'])) {
            $where['flowtime'] = array(array(">=", $params['begintime']));
        }
        if(!empty($params['endtime'])) {
            if(!empty($params['begintime'])) {
                $beginstrto = strtotime($params['begintime']);
                $endstrto = strtotime($params['endtime']);
                
                if($beginstrto < $endstrto) {
                    $where['flowtime'] = array(array(">=", $params['begintime']), array("<", $params['endtime']));
                }
            } else {
                $where['flowtime'] = array(array("<", $params['endtime']));
            }
        }
        
        if($params['role'] != 6 && $params['role'] != 7) {
            if($params['selfRole'] == 2 || $params['selfRole'] == 3 || $params['selfRole'] == 8) {
                $where['role'] = $params['selfRole'];
            }
        }
        
        $flowCashOBJ = new AmoFlowCusCashModel();
        if(($params['selfRole'] == 5 && $params['role'] == 1)) {
            $flowCashOBJ = new AmoFlowCusComCashModel();
        } else {
            $flowCashOBJ = new AmoFlowCusCashModel();
        }
//         $flowCashOBJ = new AmoFlowFutCusCashModel();
        $flowlist = $flowCashOBJ->getRow($where, "SUM(amount) as amount ");
        
//         return !empty($flowlist['amount']) ? DePrice($flowlist['amount']) : '0.00';
        return !empty($flowlist['amount']) ? $flowlist['amount'] : 0;
    }

    /**
    * @user 查看下级收益
    * @param 
    * @author jeeluo
    * @date 2017年4月12日下午4:10:41
    */
    public static function getRecoUserCash($params) {
        
        $flowUserOBJ = new FlowModel();
        $where['userid'] = $params['parentid'];
        $where['parent_userid'] = $params['customerid'];
//         $where['role'] = $params['role'];
        $where['profit_role'] = array("in", $flowUserOBJ->recoProfitRole($params['role']));
        $where['direction'] = $params['direction'];
        if(!empty($params['flowtype'])) {
            $where['flowtype'] = array("in", $params['flowtype']);
        }
        if(!empty($params['begintime'])) {
            $where['flowtime'] = array(array(">=", $params['begintime']));
        }
        if(!empty($params['endtime'])) {
            if(!empty($params['begintime'])) {
                $beginstrto = strtotime($params['begintime']);
                $endstrto = strtotime($params['endtime']);
        
                if($beginstrto < $endstrto) {
                    $where['flowtime'] = array(array(">=", $params['begintime']), array("<", $params['endtime']));
                }
            } else {
                $where['flowtime'] = array(array("<", $params['endtime']));
            }
        }
        if($params['role'] != 6 && $params['role'] != 7) {
            if($params['selfRole'] == 2 || $params['selfRole'] == 3 || $params['selfRole'] == 8) {
                $where['role'] = $params['selfRole'];
            }
        }
        
        $flowOBJ = new AmoFlowCusCashModel();
        if(($params['selfRole'] == 5 && $params['role'] == 1)) {
            $flowOBJ = new AmoFlowCusComCashModel();
        } else {
            $flowOBJ = new AmoFlowCusCashModel();
        }
        
//         $flowOBJ = new AmoFlowFutCusCashModel();
        $flowlist = $flowOBJ->getRow($where, "SUM(amount) as amount ");
        
        return !empty($flowlist['amount']) ? $flowlist['amount'] : 0;
        
//         return !empty($flowlist['amount']) ? DePrice($flowlist['amount']) : '0.00';
    }
    
    /**
    * @user 获取用户角色的阶段性收益现金收益
    * @param 
    * @author jeeluo
    * @date 2017年3月30日上午9:30:37
    */
    public static function getUserRoleProfit($params) {
        $where['userid'] = $params['customerid'];
        if(!empty($params['role'])) {
            $where['role'] = $params['role'];
        }
        $where['direction'] = $params['direction'];
//         if(!empty($params['flowtype'])) {
//             $where['flowtype'] = array("in", $params['flowtype']);
//         }
        if(!empty($params['begintime'])) {
            $where['flowtime'] = array(array(">=", $params['begintime']));
        }
        if(!empty($params['endtime'])) {
            if(!empty($params['begintime'])) {
                $beginstrto = strtotime($params['begintime']);
                $endstrto = strtotime($params['endtime']);
    
                if($beginstrto < $endstrto) {
                    $where['flowtime'] = array(array(">=", $params['begintime']), array("<", $params['endtime']));
                }
            } else {
                $where['flowtime'] = array(array("<", $params['endtime']));
            }
        }
    
        $flowOBJ = new AmoFlowCusProfitModel();
        $flowlist = $flowOBJ->getRow($where, "SUM(amount) as amount ");
    
        return !empty($flowlist['amount']) ? $flowlist['amount'] : 0;
//         return !empty($flowlist['amount']) ? DePrice($flowlist['amount']) : '0.00';
    }
    
    /**
    * @user 获取用户角色的阶段性牛币收益
    * @param 
    * @author jeeluo
    * @date 2017年3月30日上午9:31:03
    */
    public static function getUserRoleBull($params) {
        $where['userid'] = $params['customerid'];
        if(!empty($params['role'])) {
            $where['role'] = $params['role'];
        }
        $where['direction'] = $params['direction'];
//         if(!empty($params['flowtype'])) {
//             $where['flowtype'] = array("in", $params['flowtype']);
//         }
        if(!empty($params['begintime'])) {
            $where['flowtime'] = array(array(">=", $params['begintime']));
        }
        if(!empty($params['endtime'])) {
            if(!empty($params['begintime'])) {
                $beginstrto = strtotime($params['begintime']);
                $endstrto = strtotime($params['endtime']);
    
                if($beginstrto < $endstrto) {
                    $where['flowtime'] = array(array(">=", $params['begintime']), array("<", $params['endtime']));
                }
            } else {
                $where['flowtime'] = array(array("<", $params['endtime']));
            }
        }
    
        $flowOBJ = new AmoFlowCusBullModel();
        $flowlist = $flowOBJ->getRow($where, "SUM(amount) as amount ");
    
        return !empty($flowlist['amount']) ? $flowlist['amount'] : 0;
//         return !empty($flowlist['amount']) ? DePrice($flowlist['amount']) : '0.00';
    }
    
    /**
     * @user 获取用户角色表以及手机号码信息
     * @param
     * @author jeeluo
     * @date 2017年3月30日下午8:13:53
     */
    public static function getCusRole($params) {
        $cusRoleOBJ = new CusRoleModel();
        $cusRoleInfo = $cusRoleOBJ->getInfoRow(array("id" => $params['cus_role_id']), "customerid, role");
        if(empty($cusRoleInfo)) {
            return false;
        }
        
        $cusOBJ = new CusCustomerModel();
        $cusInfo = $cusOBJ->getRow(array("id" => $cusRoleInfo['customerid']), "mobile");
    
        $result['customerid'] = $cusRoleInfo['customerid'];
        $result['role'] = $cusRoleInfo['role'];
        $result['mobile'] = $cusInfo['mobile'];
        return $result;
    }
    
    /**
    * @user 牛人最多推荐牛商数
    * @param 
    * @author jeeluo
    * @date 2017年3月31日下午1:56:52
    */
    public static function orRecoMaxBus() {
        return self::orRecoMaxBusiness;
    }
    
    public static function ndRecoMaxBus() {
        return self::ndRecoMaxBusiness;
    }
    
    /**
    * @user 牛人最多推荐牛掌柜数
    * @param 
    * @author jeeluo
    * @date 2017年3月31日下午1:57:17
    */
    public static function orRecoMaxSto() {
        return self::orRecoMaxBusiness;
    }
    
    public static function orRecoLevelSto() {
        return self::orRecoLevelSto;
    }
    
    public static function orRecoLevelRole() {
        return self::orRecoLevelRole;
    }
    
    public static function ndRecoLevelRole() {
        return self::ndRecoLevelRole;
    }
    
    public static function ndRecoMaxSto() {
        return self::ndRecoMaxBusiness;
    }
    
    public static function ndRecoLevelSto() {
        return self::ndRecoLevelSto;
    }
    
    /**
    * @user 分润表的关联关系建立
    * @param 
    * @author jeeluo
    * @date 2017年4月8日上午10:57:58
    */
    public static function roleRelationRole($params) {
//         $role_range = array(1, 5);
        
//         if(!in_array($params['roleid'], $role_range)) {
//             return ["code" => "1001", "data" => "范围错误"];
//         }
        
        $cusRelaOBJ = new CusRelationModel();
        
        $relaData = $cusRelaOBJ->getRow(array("customerid" => $params['userid'], "role" => 1), "parentid, parentrole");
        
        $insertData = array("id" => $params['customerid'], "parent_id" => !empty($params['userid']) ? $params['userid'] : -1, "grandpa_id" => !empty($relaData['parentid']) ? $relaData['parentid'] : -1);
        
        $roleRela = Model::ins("RoleRelation")->getRow(array("id" => $params['customerid']));
        
        if(empty($params['stocode'])) {
            $stoData = array("business_id" => -1, "business_recomid" => -1, "business_precomid" => -1, "business_pprecomid" => -1, "city_code" => -1, "county_code" => -1, "cityagent_id" => -1, "cityagent_recomid" => -1, "countyagent_id" => -1, "countyagent_recomid" => -1,"business_role" => 8, "business_prole" => 8, "business_pprole" => 8);
            
            $insertData = array_merge($insertData, $stoData);
            
            if(empty($roleRela)) {
                Model::ins("RoleRelation")->insert($insertData);
//             } else {
//                 Model::ins("RoleRelation")->modify($insertData, array("id" => $params['customerid']));
            }
        } else {
            if(!empty($roleRela)) {
                if($roleRela['business_id'] > -1) {
                    return ["code" => "20106"];
                }
                
                if($roleRela['parent_id'] > -1) {
                    $insertData = array();
                }
                
                // $insertData = array();
            }
            
            // 绑定实体店
            $stoRelaData = $cusRelaOBJ->getRow(array("customerid" => $params['userid'], "role" => $params['roleid']), "parentid, parentrole");
            
            // 获取推荐该实体店的用户的父祖级关系
            $stoRecoRela = $cusRelaOBJ->getRow(array("customerid" => $stoRelaData['parentid'], "role" => $stoRelaData['parentrole']), "parentid, parentrole, grandpaid, grandparole");
            
            $insertData['business_id'] = $params['userid'];
            $insertData['business_recomid'] = !empty($stoRelaData['parentid']) ? $stoRelaData['parentid'] : -1;
            $insertData['business_role'] = !empty($stoRelaData['parentrole']) ? $stoRelaData['parentrole'] : 8;
            $insertData['business_precomid'] = !empty($stoRecoRela['parentid']) ? $stoRecoRela['parentid'] : -1;
            $insertData['business_prole'] = !empty($stoRecoRela['parentrole']) ? $stoRecoRela['parentrole'] : 8;
            $insertData['business_pprecomid'] = !empty($stoRecoRela['grandpaid']) ? $stoRecoRela['grandpaid'] : -1;
            $insertData['business_pprole'] = !empty($stoRecoRela['grandparole']) ? $stoRecoRela['grandparole'] : 8;
            
            // 获取实体店的编号信息
            $stoBusiness = Model::ins("StoBusiness")->getRow(array("customerid" => $params['userid']), "id");
            
            // 获取实体店的区县编号
            $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $stoBusiness['id']), "area_code");
            
            $insertData['county_code'] = $stoInfo['area_code'] ? $stoInfo['area_code'] : -1;
            
            $countyagent = Model::ins("CusCustomerAgent")->getRow(array("join_code" => ['like','%'.$insertData['county_code'].'%']), "id,customerid");
            
            $insertData['countyagent_id'] = !empty($countyagent) ? $countyagent['customerid'] : -1;
            
            $insertData['countyagent_recomid'] = -1;
            if($insertData['countyagent_id'] > -1) {
                // 查询推荐该代理的人
                $recoCounty = Model::ins("CusRelation")->getRow(array("customerid" => $countyagent['customerid'], "role" => 6), "parentid");
            
                $insertData['countyagent_recomid'] = !empty($recoCounty) ? $recoCounty['parentid'] : -1;
            }
            
            $insertData['city_code'] = !empty($stoInfo['area_code']) ? CommonModel::updateCityCode($stoInfo['area_code']) : -1;
            
            $cityagent = Model::ins("CusCustomerAgent")->getRow(array("join_code" => ['like','%'.$insertData['city_code'].'%']), "id,customerid");
            
            $insertData['cityagent_id'] = !empty($cityagent) ? $cityagent['customerid'] : -1;
            
            $insertData['cityagent_recomid'] = -1;
            if($insertData['cityagent_id'] > -1) {
                // 查询推荐该代理的人
                $recoCounty = Model::ins("CusRelation")->getRow(array("customerid" => $cityagent['customerid'], "role" => 7), "parentid");
            
                $insertData['cityagent_recomid'] = !empty($recoCounty) ? $recoCounty['parentid'] : -1;
            }
            
            if(empty($roleRela)) {
                Model::ins("RoleRelation")->insert($insertData);
            } else {
                Model::ins("RoleRelation")->modify($insertData, array("id" => $params['customerid']));
            }
        }
        
//         if($params['roleid'] == 1) {

//             $stoData = array("business_id" => -1, "business_recomid" => -1, "business_precomid" => -1, "business_pprecomid" => -1, "city_code" => -1, "county_code" => -1, "cityagent_id" => -1, "cityagent_recomid" => -1, "countyagent_id" => -1, "countyagent_recomid" => -1);
            
//             $insertData = array_merge($insertData, $stoData);
            
//             // 没有绑定实体店
//             if(empty($roleRela)) {
//                 Model::ins("RoleRelation")->insert($insertData);
//             } else {
// //                 Model::ins("RoleRelation")->modify($insertData, array("id" => $nowUserId));
//             }
//         } else if($params['roleid'] == 5) {
//             // 绑定实体店
//             $stoRelaData = $cusRelaOBJ->getRow(array("customerid" => $params['userid'], "role" => $params['roleid']), "parentid, parentrole");
            
//             // 获取推荐该实体店的用户的父祖级关系
//             $stoRecoRela = $cusRelaOBJ->getRow(array("customerid" => $stoRelaData['parentid'], "role" => $stoRelaData['parentrole']), "parentid, grandpaid");
            
//             $insertData['business_id'] = $params['userid'];
//             $insertData['business_recomid'] = $stoRelaData['parentid'];
//             $insertData['business_precomid'] = !empty($stoRecoRela['parentid']) ? $stoRecoRela['parentid'] : -1;
//             $insertData['business_pprecomid'] = !empty($stoRecoRela['grandpaid']) ? $stoRecoRela['grandpaid'] : -1;
            
//             // 获取实体店的编号信息
//             $stoBusiness = Model::ins("StoBusiness")->getRow(array("customerid" => $params['userid']), "id");
            
//             // 获取实体店的区县编号
//             $stoInfo = Model::ins("StoBusinessInfo")->getRow(array("id" => $stoBusiness['id']), "area_code");
            
//             $insertData['county_code'] = $stoInfo['area_code'] ? $stoInfo['area_code'] : 0;
            
//             $insertData['city_code'] = CommonModel::updateCityCode($stoInfo['area_code']);
            
//             $countyagent = Model::ins("CusCustomerAgent")->getRow(array("join_code" => $insertData['county_code']), "id");
            
//             $insertData['countyagent_id'] = !empty($countyagent) ? $countyagent['id'] : -1;
            
//             $insertData['countyagent_recomid'] = -1;
//             if($insertData['countyagent_id'] != -1) {
//                 // 查询推荐该代理的人
//                 $recoCounty = Model::ins("CusRelation")->getRow(array("customerid" => $countyagent['id'], "role" => 6), "parentid");
                
//                 $insertData['countyagent_recomid'] = !empty($recoCounty) ? $recoCounty['parentid'] : -1;
//             }
            
//             $cityagent = Model::ins("CusCustomerAgent")->getRow(array("join_code" => $insertData['city_code']), "id");
            
//             $insertData['cityagent_id'] = !empty($cityagent) ? $cityagent['id'] : -1;
            
//             $insertData['cityagent_recomid'] = -1;
//             if($insertData['cityagent_id'] != -1) {
//                 // 查询推荐该代理的人
//                 $recoCounty = Model::ins("CusRelation")->getRow(array("customerid" => $cityagent['id'], "role" => 7), "parentid");
            
//                 $insertData['cityagent_recomid'] = !empty($recoCounty) ? $recoCounty['parentid'] : -1;
//             }
            
//             if(empty($roleRela)) {
//                 Model::ins("RoleRelation")->insert($insertData);
//             } else {
// //                 Model::ins("RoleRelation")->modify($insertData, array("id" => $nowUserId));
//             }
//         }
        return ["code" => "200"];
    }
    
    /**
    * @user 修改分润表的牛粉归属关系
    * @param 
    * @author jeeluo
    * @date 2017年8月4日下午3:24:51
    */
    public static function updateFansRoleRelation($params) {
        
        $cusRelaOBJ = new CusRelationModel();
        
        $relaData = $cusRelaOBJ->getRow(array("customerid" => $params['userid'], "role" => 1), "parentid, parentrole");
        
        $insertData = array("id" => $params['customerid'], "parent_id" => !empty($params['userid']) ? $params['userid'] : -1, "grandpa_id" => !empty($relaData['parentid']) ? $relaData['parentid'] : -1);
        
        $roleRela = Model::ins("RoleRelation")->getRow(array("id" => $params['customerid']),"parent_id");
        
        if(empty($params['stocode'])) {
            $stoData = array("business_id" => -1, "business_recomid" => -1, "business_precomid" => -1, "business_pprecomid" => -1, "city_code" => -1, "county_code" => -1, "cityagent_id" => -1, "cityagent_recomid" => -1, "countyagent_id" => -1, "countyagent_recomid" => -1,"business_role" => 8, "business_prole" => 8, "business_pprole" => 8);
            
            $insertData = array_merge($insertData, $stoData);
            
            if(!empty($roleRela)) {
                if($roleRela['parent_id'] == -1) {
                    // 修改数据
                    Model::ins("RoleRelation")->update($insertData,["id"=>$params['customerid']]);
                }
            } else {
                // 添加数据
                Model::ins("RoleRelation")->insert($insertData);
            }
        }
    }
    
    public static function getRoleRecoList($role) {
        switch ($role) {
            case 1:
                return "1,6,7";
                break;
            case 2:
                return "2,4,5,6,7";
                break;
            case 3:
                return "3,4,5,6,7";
                break;
            case 4:
                return "6,7";
                break;
            case 5:
                return "1,6,7";
                break;
            case 6:
                return "6,7";
                break;
            case 7:
                return "6,7";
                break;
        }
    }
    
    public static function getPresentList($role) {
        
        $list = array();
        
        $where['enable'] = 1;
        $where['role'] = $role;
        
        $list = Model::ins("RoleProduct")->getList($where, "id,productname,thumb,prouctprice", "sort asc, id asc", 10);
        
        foreach ($list as $k => $v) {
            $list[$k]['productid'] = $v['id'];
            $list[$k]['thumb'] = Img::url($v['thumb']);
            $list[$k]['prouctprice'] = DePrice($v['prouctprice']);
//             $list[$k]['prouctprice'] = "0.00";
            $list[$k]['bullamount']= '0.00';
        }
        
        return $list;
    }
    
    /**
    * @user 获取用户角色是否赠送(true 为否 false 为是 或者异常操作)
    * @param 
    * @author jeeluo
    * @date 2017年7月4日下午12:00:31
    */
    public static function getUserRoleGive($params) {
        // 查询申请表和推荐表 是否有对应的用户数据
        // 申请表数据
        $applyData = Model::ins("RoleApplyLog")->getRow(["customerid"=>$params['customerid'],"pay_status"=>1,"role_type"=>$params['role']],"id");
        if(!empty($applyData)) {
            return true;
        }
        // 推荐表数据
        $recoOBJ;
        if($params['role'] == 2)
            $recoOBJ = Model::ins("RoleRecoOr");
        else if($params['role'] == 3) 
            $recoOBJ = Model::ins("RoleRecoEn");
        else if($params['role'] == 8)
            $recoOBJ = Model::ins("RoleRecoTalent");
        else
            return false;
        
       $recoData = $recoOBJ->getRow(["customerid"=>$params['customerid'],"pay_status"=>[">",0],"status"=>2],"id");
       
       if(empty($recoData)) {
           return false;
       }
       return true;
    }
    
    /**
    * @user 处理新版牛人小组操作
    * @param 
    * @author jeeluo
    * @date 2017年8月14日上午11:05:05
    */
    public static function getUserOrRoleGroup($params) {
        $userid = $params['userid'];
        $role = $params['role'];
        
        $parent = Model::ins("CusRelation")->getRow(["customerid"=>$userid,"role"=>$role],"parentrole,parentid");
        
        if(!empty($parent)) {
            if($parent['parentrole']==2) {
                $grandpa = Model::ins("CusRelation")->getRow(["customerid"=>$parent['parentid'],"role"=>$parent['parentrole']],"parentrole,parentid");
                
                if($grandpa['parentrole']==2)
                    return ["code"=>"200","type"=>2,"level"=>3];
                else if($grandpa['parentrole']==3)
                    return ["code"=>"200","type"=>3,"level"=>3];
                else if($grandpa['parentrole']==8)
                    return ["code"=>"200","type"=>8,"level"=>3];
                else
                    return ["code"=>"200","type"=>2,"level"=>2];
            } else if($parent['parentrole']==3) {
                return ["code"=>"200","type"=>3,"level"=>2];
            } else if($parent['parentrole']==8) {
                return ["code"=>"200","type"=>8,"level"=>2];
            }
            
            if($parent['parentid'] == -1 || $parent['parentrole'] == -1) {
                return ["code"=>"200","type"=>$role,"level"=>1];
            }
        }
        return ["code"=>"1001"];
    }
    
    /**
    * @user 实体店员工流水类型值
    * @param 
    * @author jeeluo
    * @date 2017年8月18日下午4:33:43
    */
    public static function comServiceFlowType() {
        return 17;
    }
    
    public static function validateShareCusRole($params) {
        $role = $params['selfRole'];
        $instroducerMobile = $params['instroducerMobile'];
        
        $instroducer = Model::ins("CusCustomer")->getRow(["mobile"=>$instroducerMobile],"id");
        if(empty($instroducer['id'])) {
            return false;
        }
        
        $where['customerid'] = $instroducer['id'];
        
        // 检验角色权限
        if($role == 2) {
            $where['role'] = ["in","2,3,8"];
        } else if($role == 3) {
            $where['role'] = ["in","3"];
        } else if($role == 8) {
            $where['role'] = ["in","8"];
        }
        
        $cusRole = Model::ins("CusRole")->getRow($where,"id");
        if(!empty($cusRole['id'])) {
            return true;
        }
        return false;
    }
    
    /**
    * @user 获取分享人手机号码 分享对应角色是否有权利(false 为无  true为有)
    * @param 
    * @author jeeluo
    * @date 2017年9月4日上午11:52:59
    */
    public static function getShareRoleMobile($params) {
        $applyRole = $params['applyRole'];
        $instoducerMobile = $params['instoducerMobile'];
        
        $instoducer = Model::ins("CusCustomer")->getRow(["mobile"=>$instoducerMobile],"id");
        $instoducerRoleData = array();
        
        if($applyRole == 2) {
            // 成为牛人,查询引荐人是否有创客身份(牛人、牛达人、牛创客)
            $instoducerRoleData = Model::ins("CusRole")->getRow(["customerid"=>$instoducer['id'],"role"=>["in","2,3,8"]],"id");
        } else if($applyRole == 3) {
            // 成为牛创客，查询引荐人是否有牛创客身份
            $instoducerRoleData = Model::ins("CusRole")->getRow(["customerid"=>$instoducer['id'],"role"=>3],"id");
        } else if($applyRole == 8) {
            // 成为牛达人，查询引荐人是否有牛达人身份
            $instoducerRoleData = Model::ins("CusRole")->getRow(["customerid"=>$instoducer['id'],"role"=>8],"id");
        }
        
        if(!empty($instoducerRoleData)) {
            return true;
        }
        return false;
    }
}