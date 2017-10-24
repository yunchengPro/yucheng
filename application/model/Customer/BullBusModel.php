<?php
// +----------------------------------------------------------------------
// |  [ 商品相关模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-03-30
// +----------------------------------------------------------------------
namespace app\model\Customer;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;
use app\model\BusinessLogin\LoginModel;
use think\Config;
use app\lib\Sms;
use app\lib\ApiService\Sms as SmsApi;
use \think\Request;
use app\model\StoBusiness\StobusinessModel;
use app\model\Sys\CommonModel;

class BullBusModel
{
    const busRole = 4;
    const stoRole = 5;
    const orRole = 2;
    const defaultRole = 1;
    
    const ndRole = 8;
    
    const company_cus = -1;
    
    protected $status_arr = array(1, 2, 3); // 1待审核  2审核成功  3审核失败
    
    protected $enable_arr = array(1, -1); // 1为启用， -1为禁用
    
    /**
    * @user 修改审核操作
    * @param 
    * @author jeeluo
    * @date 2017年3月31日下午2:14:54
    */
    public function updateExamStatus($params) {
        // 确保状态修改
        if($params['status'] != 2 && $params['status'] != 3) {
            return ["code" => "400", "data" => "选择正确操作"];
        }
        
        $busUpdateData = array();
        
        if($params['status'] == 2) {
            // 假如审核成功
            $recoBusInfo = Model::ins("RoleRecoBus")->getRow(array("id" => $params['id']));
            $cusInfo = Model::ins("CusCustomer")->getRow(array("mobile" => $recoBusInfo['mobile']));

//             $randNumber = rand(100000, 999999);
            $randNumber = 123456;
            $userpwd = LoginModel::PwdEncode($randNumber);
            
            // 检查用户
            if(empty($cusInfo)) {
                // 查询数据对否
//                 if(!CommonModel::nameauth_validate(array("realname"=>$recoBusInfo['corporation'],"idnumber"=>$recoBusInfo['idnumber']))) {
//                     return ["code"=>"26001", "data"=>"实名认证错误"];
//                 }
                // 无此用户(添加用户表)
                $cusData['mobile'] = $cusData['username'] = $recoBusInfo['mobile'];
                $cusData['userpwd'] = $userpwd;
                $cusData['createtime'] = getFormatNow();
                $cusInfo['id'] = Model::ins("CusCustomer")->insert($cusData);
                
                // 添加用户信息表(不执行修改操作，怕影响用户自己添加的原数据)
                $infoData = array("id" => $cusInfo['id'], "realname" => $recoBusInfo['person_charge'], "nickname" => $recoBusInfo['company_name'], "headerpic" => $recoBusInfo['company_logo'],
                                "idnumber" => $recoBusInfo['idnumber'], "isnameauth"=>1, "area" => $recoBusInfo['area'], "area_code" => $recoBusInfo['area_code'], "address" => $recoBusInfo['company_area']);
                
//                 $infoData = array("id" => $cusInfo['id'], "realname" => $recoBusInfo['corporation'], "nickname" => $recoBusInfo['company_name'], "headerpic" => $recoBusInfo['company_logo'],
//                     "idnumber" => $recoBusInfo['idnumber'], "area" => $recoBusInfo['area'], "area_code" => $recoBusInfo['area_code'], "address" => $recoBusInfo['company_area'],"isnameauth"=>1);
                Model::ins("CusCustomerInfo")->insert($infoData);
            } else {
//                 $cusUserInfo = Model::ins("CusCustomerInfo")->getRow(array("id"=>$cusInfo['id']), "isnameauth");
//                 if(!empty($cusUserInfo)) {
//                     if($cusUserInfo['isnameauth'] != 1) {
//                         // 查询数据对否
//                         $authArr = array("realname"=>$recoBusInfo['corporation'], "idnumber"=>$recoBusInfo['idnumber'], "customerid"=>$cusInfo['id']);
//                         if(!CommonModel::nameauth_validate($authArr)) {
//                             return ["code"=>"26001", "data"=>"实名认证错误"];
//                         }
//                         CommonModel::userNameAuth($authArr);
//                     }
//                 } else {
//                     $authArr = array("realname"=>$recoBusInfo['corporation'], "idnumber"=>$recoBusInfo['idnumber'], "customerid"=>$cusInfo['id']);
//                     if(!CommonModel::nameauth_validate($authArr)) {
//                         return ["code"=>"26001", "data"=>"实名认证错误"];
//                     }
//                     CommonModel::userNameAuth($authArr);
//                 }
                
                $cusData['userpwd'] = $userpwd;
                Model::ins("CusCustomer")->modify($cusData, array("id" => $cusInfo['id']));
                $cusInfoData = Model::ins("CusCustomerInfo")->getRow(array("id"=>$cusInfo['id']));
                if(empty($cusInfoData)) {
                    $infoData = array("id" => $cusInfo['id'], "realname" => $recoBusInfo['person_charge'], "nickname" => $recoBusInfo['company_name'], "headerpic" => $recoBusInfo['company_logo'],
                        "idnumber" => $recoBusInfo['idnumber'], "isnameauth"=>1, "area" => $recoBusInfo['area'], "area_code" => $recoBusInfo['area_code'], "address" => $recoBusInfo['company_area']);
                    Model::ins("CusCustomerInfo")->insert($infoData);
                } else {
                    if($cusInfoData['isnameauth'] != 1) {
                        Model::ins("CusCustomerInfo")->modify(array("realname"=>$recoBusInfo['person_charge'],"idnumber"=>$recoBusInfo['idnumber'],"isnameauth"=>1),array("id"=>$cusInfo['id']));
                    }
                }
            }
            // 检查用户是否已经开启商家
            $busInfo = Model::ins("BusBusiness")->getRow(array("customerid" => $cusInfo['id']));
            if(!empty($busInfo)) {
                return ["code" => "400", "data" => "用户商家已存在，数据异常"];
            }
            
            // 检查角色
            $cusRoleInfo = Model::ins("CusRole")->getRow(array("customerid" => $cusInfo['id'], "role" => self::busRole));
            if(empty($cusRoleInfo)) {
                
                // 添加用户角色
                $busData['customerid'] = $stoData['customerid'] = $cusInfo['id'];
                $busData['area'] = $recoBusInfo['area'];
                $busData['address'] = $recoBusInfo['company_area'];
                $busData['area_code']  = $recoBusInfo['area_code'];
                $busData['addtime'] = $stoData['addtime'] = getFormatNow();
                $busData['role'] = self::busRole;
                $stoData['role'] = self::stoRole;
                $stoData['enable'] = 1;        // 推荐牛商 赠送的牛掌柜默认为未上架状态
//                 $stoData['enable'] = 1;
                
                // 检查牛粉角色是否存在
                $cusRoleFans = Model::ins('CusRole')->getRow(array("customerid" => $cusInfo['id'], "role" => self::defaultRole));
                if(empty($cusRoleFans)) {
                    $fansData['customerid'] = $cusInfo['id'];
                    $fansData['area'] = $recoBusInfo['area'];
                    $fansData['address'] = $recoBusInfo['company_area'];
                    $fansData['area_code']  = $recoBusInfo['area_code'];
                    $fansData['addtime'] = getFormatNow();
                    $fansData['role'] = self::defaultRole;
                    $fansData['enable'] = 1;        // 推荐牛商 赠送的牛掌柜默认为未上架状态
                    
                    Model::ins("CusRole")->insert($fansData);
                }
                
                // 检查牛掌柜角色是否存在
                $cusRoleSto = Model::ins("CusRole")->getRow(array("customerid" => $cusInfo['id'], "role" => self::stoRole));
                if(empty($cusRoleSto)) {
                    Model::ins("CusRole")->insert($stoData);
                }
                
                Model::ins("CusRole")->insert($busData);
            } else {
                // 角色存在，说明有异常
                return ["code" => "400", "data" => "用户角色已存在，数据异常"];
            }
            
            // 创建商家
            $insert_id = Model::ins("BusBusiness")->insert(array("price_type" => $recoBusInfo['price_type'],"businessname" => $recoBusInfo["company_name"], "ischeck" => 1, "enable" => 1, "customerid" => $cusInfo['id'], "addtime" => getFormatNow()));
            
            // 创建商家基本信息
            Model::ins("BusBusinessInfo")->insert(array("id" => $insert_id,"price_type" => $recoBusInfo['price_type'], "businessname" => $recoBusInfo["company_name"], "businesslogo" => $recoBusInfo['company_logo'], "realname" => $recoBusInfo['person_charge'],
                        "idnumber" => $recoBusInfo['idnumber'], "mobile" => $recoBusInfo['mobile'], "area" => $recoBusInfo['area'], "area_code" => $recoBusInfo['area_code'], "address" => $recoBusInfo['company_area'],
                        "servicetel" => $recoBusInfo['servicetel'], "lngx" => $recoBusInfo['longitude'], "laty" => $recoBusInfo['latitude'], "addtime" => getFormatNow()));

//             Model::ins("BusBusinessInfo")->insert(array("id" => $insert_id,"price_type" => $recoBusInfo['price_type'], "businessname" => $recoBusInfo["company_name"], "businesslogo" => $recoBusInfo['company_logo'], "realname" => $recoBusInfo['corporation'],
//                 "idnumber" => $recoBusInfo['idnumber'], "mobile" => $recoBusInfo['mobile'], "area" => $recoBusInfo['area'], "area_code" => $recoBusInfo['area_code'], "address" => $recoBusInfo['company_area'],
//                 "servicetel" => $recoBusInfo['servicetel'], "lngx" => $recoBusInfo['longitude'], "laty" => $recoBusInfo['latitude'], "addtime" => getFormatNow()));
            
            // 判断该用户是否已经有实体店了
            $stoInfo = Model::ins("StoBusiness")->getRow(array("customerid" => $cusInfo['id']));
            
            if(empty($stoInfo)) {
                // 写入实体店数据
//                 $stoData = array("customerid" => $cusInfo['id'], "businessname" => $recoBusInfo['company_name'], "addtime" => getFormatNow(), "ischeck"=>1, "nopasstype"=>0, "enable"=>-1);
                $stoData = array("customerid" => $cusInfo['id'], "businessname" => $recoBusInfo['company_name'], "addtime" => getFormatNow(), "ischeck"=>-1, "nopasstype"=>0, "enable"=>1);
                
                $sto_id = Model::ins("StoBusiness")->insert($stoData);
                
                // 写入实体店信息
//                 $stoInfoData = array("id" => $sto_id, "businessname" => $recoBusInfo['company_name'], "businesslogo" => $recoBusInfo['company_logo'],"enable"=>-1);
                $stoInfoData = array("id" => $sto_id, "businessname" => $recoBusInfo['company_name'], "businesslogo" => $recoBusInfo['company_logo'],"enable"=>1,"isshow"=>-1);
                Model::ins("StoBusinessInfo")->insert($stoInfoData);
                
                // 写入实体店基本信息
                $business_code = StobusinessModel::creatStoBusCode(array("businessid" => $sto_id));
                $stoBaseInfoData = array("id" => $sto_id, "businessname" => $recoBusInfo['company_name'], "business_code" => $business_code['business_code'], "discount" => 90);
                Model::ins("StoBusinessBaseinfo")->insert($stoBaseInfoData);
                
                
                // 判断平台号是否已经在 stoBusinessCode 表产生
                $stoBusinesCode = Model::ins("StoBusinessCode")->getRow(["business_code"=>$business_code['business_code']],"id,isuse");
                if(!empty($stoBusinesCode['isuse'])) {
                    
                    // 查看属性
                    if($stoBusinesCode['isuse'] == 1) {
                        return ["code" => "400", "data" => "商家平台号已经被绑定了"];
                    }
                    // 进行数据修改
                    Model::ins("StoBusinessCode")->modify(["business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$stoData['businessname'],"customerid"=>$cusInfo['id'],"usetime"=>$stoData['addtime']],["id"=>$stoBusinesCode['id']]);
                } else {
                    // 添加数据
                    $businessCodeData = array("business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$stoData['businessname'],"customerid"=>$cusInfo['id'],"addtime"=>$stoData['addtime'],"usetime"=>$stoData['addtime']);
                    Model::ins("StoBusinessCode")->insert($businessCodeData);
                }
            }
            
            // 建立角色关联关系
            // 查询推荐该用户的角色关系
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $recoBusInfo['cus_role_id']));
            
            $recoParentFans = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => self::defaultRole, "parentrole" => self::defaultRole), "parentid");
            
            $parentCusRela = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $recoParentInfo['role']), "parentid");
            
            // 判断该推荐用户 推荐牛商和牛掌柜数量
//             $recoBusParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::busRole), "COUNT(id) as num ");
            $recoBusParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::busRole, "parentrole" => $recoParentInfo['role']), "COUNT(id) as num ");

            $busParent = array("customerid" => $cusInfo['id'], "role" => self::busRole, "addtime" => getFormatNow());
            if($recoParentInfo['role'] == self::orRole) {
                if($recoBusParentCount['num'] < CommonRoleModel::orRecoMaxBus()) {
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::busRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::orRole, "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? self::orRole : self::company_cus, "addtime" => getFormatNow()));
                } else {
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::busRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => getFormatNow()));
                }
            } else if($recoParentInfo['role'] == self::ndRole) {
                if($recoBusParentCount['num'] < CommonRoleModel::ndRecoMaxSto()) {
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::busRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::ndRole, "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? self::ndRole : self::company_cus, "addtime" => getFormatNow()));
                } else {
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::busRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => getFormatNow()));
                }
            } else {
                Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::busRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => $recoParentInfo['role'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? $recoParentInfo['role'] : self::company_cus, "addtime" => getFormatNow()));
            }

            // 牛掌柜
            $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::stoRole), "COUNT(id) as num ");
            
            $cusRelaSto = Model::ins("CusRelation")->getRow(array("customerid" => $cusInfo['id'], "role" => self::stoRole));
            
            if(empty($cusRelaSto)) {
                $parentid = $parentRole = $grandpa = $grandpaRole = self::company_cus;
                
                // 查询推荐人是否有能推荐牛掌柜的角色范围
//                 $roleRange = Model::new("User.RoleReco")->getRoleRecoedType(array("selfRoleType" => self::stoRole));
                $roleRange = Model::new("User.RoleReco")->getNewRoleRecoedType(array("selfRoleType" => self::stoRole));
                
                foreach ($roleRange as $k => $v) {
                    // 查询推荐人是否有对应的角色
//                     $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $v, "parentrole" => $v), "parentid, parentrole");
                    $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $v), "parentid, parentrole");
                    
//                     if($v == )
                    
                    if($v == self::orRole) {
                        // 判断个数
                        $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::stoRole, "parentrole" => $v), "COUNT(id) as num ");
                        if($recoStoParentCount['num'] >= CommonRoleModel::orRecoMaxSto()) {
                            break;
                        }
                    }
                    
                    if(!empty($parentRoleInfo)) {
                        $parentid = $recoParentInfo['customerid'] ? $recoParentInfo['customerid'] : self::company_cus;
                        $parentRole = $v;
                        $grandpa = !empty($parentRoleInfo) ? $parentRoleInfo['parentid'] : self::company_cus;
                        $grandpaRole = !empty($parentRoleInfo) ? $parentRoleInfo['parentrole'] : self::company_cus;
                        break;
                    }
                    
                    if($v == self::orRole) {
                        $parentid = $recoParentInfo['customerid'];
                        $parentRole = $v;
                        $grandpa = self::company_cus;
                        $grandpaRole = self::company_cus;
                    }
                }
                Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => $parentid, "parentrole" => $parentRole, "grandpaid" => $grandpa, "grandparole" => $grandpaRole, "addtime" => getFormatNow()));
            }
            
//             $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::stoRole, "parentrole" => $recoParentInfo['role']), "COUNT(id) as num ");
            
//             if($recoParentInfo['role'] == self::orRole) {
//                 if($recoStoParentCount['num'] < CommonRoleModel::orRecoMaxSto()) {
//                     Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::orRole, "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? self::orRole : self::company_cus, "addtime" => getFormatNow()));
//                 } else {
//                     // 推荐的这个牛商 归属公司
//                     Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => getFormatNow()));
//                 }
//             } else {
//                 Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => $recoParentInfo['role'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? $recoParentInfo['role'] : self::company_cus, "addtime" => getFormatNow()));
//             }
            
            $nfRelationInfo = Model::ins("CusRelation")->getRow(array("customerid" => $cusInfo['id'],"role"=>self::defaultRole), "id");
            if(empty($nfRelationInfo)) {
                // 写入牛粉表
                Model::ins("CusRelationNf")->insert(array("customerid" => $cusInfo['id'], "parentid" => $recoParentInfo['customerid'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "addtime" => getFormatNow()));
                
                Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::defaultRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::defaultRole, "grandpaid" => !empty($recoParentFans['parentid']) ? $recoParentFans['parentid'] : self::company_cus, "grandparole" => !empty($recoParentFans['parentid']) ? self::defaultRole : self::company_cus, "addtime" => getFormatNow()));
            
                Model::new("Amount.Role")->share_role(["userid"=>$recoParentInfo['customerid'],"customerid"=>$cusInfo['id']]);
                // Model::new("Sys.Mq")->submit();
            }
            // 发送手机提示短信(暂时屏蔽)
//             $busSms = Config::get('sms.bus');
//             Sms::send($recoBusInfo['mobile'], [$busSms['url'], $randNumber], $busSms['tempId']);
            CommonRoleModel::roleRelationRole(array("userid" => $recoParentInfo['customerid'], "roleid" => 1, "customerid" => $cusInfo['id']));
            
            $parentUserInfo = Model::ins("CusCustomer")->getRow(["id"=>$recoParentInfo['customerid']],"mobile");
            
            if(!empty($parentUserInfo['mobile'])) {
                $smsresult = SmsApi::api(([
                    "param" => json_encode([
                        "name" => $recoBusInfo['mobile']
                    ],JSON_UNESCAPED_UNICODE),
                    "mobile" => $parentUserInfo['mobile'],
                    "code" => "SMS_94820021"
                ]));
                if(!$smsresult)
                    return ["code" => "400", "data" => "审核成功，通知短信发送失败"];
            }
        } else {
            // 假如审核失败
            $busUpdateData['remark'] = $params['remark']; 
            
            $recoBusInfo = Model::ins("RoleRecoBus")->getRow(array("id" => $params['id']),"cus_role_id,mobile");
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $recoBusInfo['cus_role_id']));
            $parentUserInfo = Model::ins("CusCustomer")->getRow(["id"=>$recoParentInfo['customerid']],"mobile");
            
            if(!empty($parentUserInfo['mobile'])) {
                $smsresult = SmsApi::api(([
                    "param" => json_encode([
                        "name" => $recoBusInfo['mobile'],
                        "content"=> $params['remark']
                    ],JSON_UNESCAPED_UNICODE),
                    "mobile" => $parentUserInfo['mobile'],
                    "code" => "SMS_94695022"
                ]));
                if(!$smsresult)
                    return ["code" => "400", "data" => "审核成功，通知短信发送失败"];
            }
        }
        $busUpdateData['status'] = $params['status'];
        $busUpdateData['examinetime'] = getFormatNow();
        
        $status = Model::ins("RoleRecoBus")->modify($busUpdateData, array("id" => $params['id']));
        if($status) {
            return ["code" => "200"];
        }
        return ["code" => "400", "data" => "数据添加异常，请联系管理员"];
    }
    
    /**
    * @user 写入供应商
    * @param 
    * @author jeeluo
    * @date 2017年4月28日上午10:36:57
    */
    public function addBus($params) {
        
        $cusUser = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
        
        if(!empty($cusUser)) {
            // 根据填写的手机号码和选择的角色  获取该用户是否已经有此角色
            $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $cusUser['id'], "role" => self::busRole));
            
            if(!empty($cusRole)) {
                // 该用户已经开通供应商，无法重复申请
                return ["code" => "20001", "data" => "您填写的手机号码 已经是供应商了"];
            }
        }
        
        $params['area'] = '';
        $sysarea = CommonModel::getSysArea($params['area_county']);
        
        if($sysarea["code"] == "200") {
            $params['area'] = $sysarea['data'];
        }
        
        // 写入推荐信息
        $recoResult = self::addRecoBus($params);

        if($recoResult["code"] != "200") {
            return ["code" => $recoResult["code"], "data" => $recoResult["data"]];
        }
        
        // 添加用户、供应商、牛店
        $addUser = self::addCheckCustomer($recoResult['data'], $params['type']);
        
        // 用户角色开启
        self::addRoleOpen($recoResult['data'], $params['type']);
        
        // 建立角色关系
        self::addCusRelation($recoResult['data']);
        
        $busUpdateData['status'] = 2;
        $busUpdateData['examinetime'] = getFormatNow();
        
        Model::ins("RoleRecoBus")->modify($busUpdateData, array("id" => $recoResult['data']['id']));
        
        return ["code" => "200"];
    }
    
    /**
    * @user 写入牛商推荐信息
    * @param 
    * @author jeeluo
    * @date 2017年4月27日下午2:26:29
    */
    private function addRecoBus($params) {
        // 查看引荐信息
        $instroducerCus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['instroducerMobile']), "id");
        
        if(empty($instroducerCus)) {
            return ["code" => "20103", "data" => "引荐人信息不存在"];
        }
        
        // 查看选择的角色和填写的手机号 是否有该角色
        $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $instroducerCus['id'], "role" => $params['instroducerRole']), "id");
        
        if(empty($cusRole)) {
            return ["code" => "20103", "data" => "用户角色信息不存在"];
        }
        
        // 查看数据库是否存在该身份证号码数据
//         $busInfo = Model::ins("RoleRecoBus")->getRow(array("cus_role_id" => $cusRole['id'], "idnumber" => $params['idnumber']), "id,status", "id desc");
//         if(!empty($busInfo)) {
//             if($busInfo['status'] != 3) {
//                 return ["code" => "20014", "data" => "该身份证号码已被推荐"];
//             }
//         }
        
        /*
         * 高德api 获取经纬度信息
         */
        $areaname = CommonModel::getSysArea($params['area_county']);
        $map = CommonModel::getAddressLngLat($areaname['data'].$params['company_area']);
        $longitude = $map['data']['lngx'];
        $latitude = $map['data']['laty'];
        
        $price_type = '';
        foreach ($params['price_type'] as $v) {
            $price_type .= $v.",";
        }
        $price_type = substr($price_type, 0, -1);
        
//         $recoData = array("cus_role_id"=>$cusRole['id'], "company_name"=>$params['company_name'], "person_charge"=>$params['person_charge'], "mobile"=>$params['mobile'], "corporation"=>$params['corporation'],
//             "area"=>$params['area'], "area_code"=>$params['area_county'], "company_area" => $params['company_area'], "longitude"=>$longitude, "latitude"=>$latitude, "price_type"=> $price_type,
//             "idnumber"=>$params['idnumber'], "licence_image"=>$params['licence_image'], "idnumber_image"=>$params['idnumber_image'], "company_logo"=>$params['company_logo'], "addtime"=>getFormatNow());

        $recoData = array("cus_role_id"=>$cusRole['id'], "company_name"=>$params['company_name'], "person_charge"=>$params['person_charge'], "mobile"=>$params['mobile'], "corporation"=>$params['corporation'],
            "area"=>$params['area'], "area_code"=>$params['area_county'], "company_area" => $params['company_area'], "longitude"=>$longitude, "latitude"=>$latitude, "price_type"=> $price_type,
            "licence_image"=>$params['licence_image'],"company_logo"=>$params['company_logo'], "addtime"=>getFormatNow());
        // 写入推荐表
        $recoDataId = Model::ins("RoleRecoBus")->insert($recoData);
        if($recoDataId) {
            $recoData['id'] = $recoDataId;
            return ["code" => "200", "data" => $recoData];
        }
        return ["code" => "400", "data" => "推荐数据添加异常，请联系管理员"];
    }
    
    /**
    * @user 添加用户(供应商/牛店)
    * @param 
    * @author jeeluo
    * @date 2017年4月27日下午1:56:05
    */
    private function addCheckCustomer($params, $type) {
        // 检查用户
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']));
        
//         $randNumber = rand(100000, 999999);
        $randNumber = 123456;
        $userpwd = LoginModel::PwdEncode($randNumber);

        if(empty($cus)) {
            // 查询数据对否
//             if(!CommonModel::nameauth_validate(array("realname"=>$params['corporation'],"idnumber"=>$params['idnumber']))) {
//                 return ["code"=>"26001", "data"=>"实名认证错误"];
//             }
            // 无此用户(添加用户表)
            $cusData['mobile'] = $cusData['username'] = $params['mobile'];
            $cusData['userpwd'] = $userpwd;
            $cusData['createtime'] = getFormatNow();
            
            $cus['id'] = Model::ins("CusCustomer")->insert($cusData);
            
            // 添加用户信息表(不执行修改操作，怕影响用户自己添加的原数据)
//             $infoData = array("id" => $cus['id'], "realname" => $params['person_charge'], "nickname" => $params['company_name'], "headerpic" => $params['company_logo'],
//                 "idnumber" => $params['idnumber'], "isnameauth"=>1, "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area']);

            $infoData = array("id" => $cus['id'], "realname" => $params['person_charge'], "nickname" => $params['company_name'], "headerpic" => $params['company_logo'],
                "isnameauth"=>1, "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area']);

//             $infoData = array("id" => $cus['id'], "realname" => $params['corporation'], "nickname" => $params['company_name'], "headerpic" => $params['company_logo'],
//                 "idnumber" => $params['idnumber'], "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area'],"isnameauth"=>1);
            
            Model::ins("CusCustomerInfo")->insert($infoData);
        } else {
//             $cusUserInfo = Model::ins("CusCustomerInfo")->getRow(array("id"=>$cus['id']), "isnameauth");
//             if(!empty($cusUserInfo)) {
//                 if($cusUserInfo['isnameauth'] != 1) {
//                     // 查询数据对否
//                     $authArr = array("realname"=>$params['corporation'], "idnumber"=>$params['idnumber'], "customerid"=>$cus['id']);
//                     if(!CommonModel::nameauth_validate($authArr)) {
//                         return ["code"=>"26001", "data"=>"实名认证错误"];
//                     }
//                     CommonModel::userNameAuth($authArr);
//                 }
//             } else {
//                 $authArr = array("realname"=>$params['corporation'], "idnumber"=>$params['idnumber'], "customerid"=>$cus['id']);
//                 if(!CommonModel::nameauth_validate($authArr)) {
//                     return ["code"=>"26001", "data"=>"实名认证错误"];
//                 }
//                 CommonModel::userNameAuth($authArr);
//             }
            $cusData['userpwd'] = $userpwd;
            Model::ins("CusCustomer")->modify($cusData, array("id" => $cus['id']));
            $cusInfoData = Model::ins("CusCustomerInfo")->getRow(array("id"=>$cus['id']));
            if(empty($cusInfoData)) {
//                 $infoData = array("id" => $cus['id'], "realname" => $params['person_charge'], "nickname" => $params['company_name'], "headerpic" => $params['company_logo'],
//                     "idnumber" => $params['idnumber'], "isnameauth"=>1, "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area']);
                $infoData = array("id" => $cus['id'], "realname" => $params['person_charge'], "nickname" => $params['company_name'], "headerpic" => $params['company_logo'],
                    "isnameauth"=>1, "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area']);
                Model::ins("CusCustomerInfo")->insert($infoData);
            } else {
                if($cusInfoData['isnameauth'] != 1) {
//                     Model::ins("CusCustomerInfo")->modify(array("realname"=>$params['person_charge'],"idnumber"=>$params['idnumber'],"isnameauth"=>1),array("id"=>$cus['id']));
                    Model::ins("CusCustomerInfo")->modify(array("realname"=>$params['person_charge'],"isnameauth"=>1),array("id"=>$cus['id']));
                }
            }
        }
        
        // 检查用户是否已经开启商家
        $busInfo = Model::ins("BusBusiness")->getRow(array("customerid" => $cus['id']));
        if(empty($busInfo)) {
            // 创建商家
//             $insert_id = Model::ins("BusBusiness")->insert(array("price_type" => $params['price_type'],"businessname" => $params["company_name"], "ischeck" => 1, "enable" => 1, "customerid" => $cus['id'], "addtime" => $params['addtime']));
            $insert_id = Model::ins("BusBusiness")->insert(array("price_type" => $params['price_type'],"businessname" => $params["company_name"], "ischeck" => 1, "enable" => 1, "isvip" => $type, "customerid" => $cus['id'], "addtime" => $params['addtime']));
            // 创建商家基本信息
//             Model::ins("BusBusinessInfo")->insert(array("id" => $insert_id,"price_type" => $params['price_type'], "businessname" => $params["company_name"], "businesslogo" => $params['company_logo'], "realname" => $params['person_charge'],
//                 "idnumber" => $params['idnumber'], "mobile" => $params['mobile'], "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area'], "isvip" => $type,
//                 "lngx" => $params['longitude'], "laty" => $params['latitude'], "addtime" => $params['addtime']));

            Model::ins("BusBusinessInfo")->insert(array("id" => $insert_id,"price_type" => $params['price_type'], "businessname" => $params["company_name"], "businesslogo" => $params['company_logo'], "realname" => $params['person_charge'],
                "mobile" => $params['mobile'], "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['company_area'], "isvip" => $type,
                "lngx" => $params['longitude'], "laty" => $params['latitude'], "addtime" => $params['addtime']));
        }
        
        // 判断该用户是否已经有实体店了
        $stoInfo = Model::ins("StoBusiness")->getRow(array("customerid" => $cus['id']));
        
        if(empty($stoInfo)) {
            // 写入实体店数据
//             $stoData = array("customerid" => $cus['id'], "businessname" => $params['company_name'], "addtime" => $params['addtime'], "ischeck"=>1, "nopasstype"=>0, "enable"=>-1);
            $stoData = array("customerid" => $cus['id'], "businessname" => $params['company_name'], "addtime" => $params['addtime'], "ischeck"=>0, "nopasstype"=>0, "enable"=>1);
            $sto_id = Model::ins("StoBusiness")->insert($stoData);
            
            // 写入实体店信息
//             $stoInfoData = array("id" => $sto_id, "businessname" => $params['company_name'], "businesslogo" => $params['company_logo'],"enable"=>-1);
            $stoInfoData = array("id" => $sto_id, "businessname" => $params['company_name'], "businesslogo" => $params['company_logo'],"enable"=>1,"isshow"=>-1);
            Model::ins("StoBusinessInfo")->insert($stoInfoData);
            
//             // 写入实体店基本信息
            $business_code = StobusinessModel::creatStoBusCode(array("businessid" => $sto_id));
            
            $businessCodeData = Model::ins("StoBusinessBaseinfo")->getRow(["business_code"=>$business_code['business_code']],"count(*) as count");
//             if($businessCodeData['count'] > 0) {
//                 // 删除实体店信息表数据
//                 Model::ins("StoBusinessInfo")->delete(["id"=>$sto_id]);
                
//                 // 删除实体店数据
//                 Model::ins("StoBusiness")->delete(["id"=>$sto_id]);
//             }
            
            $stoBaseInfoData = array("id" => $sto_id, "businessname" => $params['company_name'], "business_code" => $business_code['business_code'], "discount" => 90);
            Model::ins("StoBusinessBaseinfo")->insert($stoBaseInfoData);

            // 判断平台号是否已经在 stoBusinessCode 表产生
            $stoBusinesCode = Model::ins("StoBusinessCode")->getRow(["business_code"=>$business_code['business_code']],"id,isuse");
            if(!empty($stoBusinesCode['isuse'])) {
                // 查看属性
                if($stoBusinesCode['isuse'] == 1) {
                    return ["code" => "400", "data" => "商家平台号已经被绑定了"];
                }
                // 进行数据修改
                Model::ins("StoBusinessCode")->modify(["business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$stoData['businessname'],"customerid"=>$cus['id'],"usetime"=>$stoData['addtime']],["id"=>$stoBusinesCode['id']]);
            } else {
                // 添加数据
                $businessCodeData = array("business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$stoData['businessname'],"customerid"=>$cus['id'],"addtime"=>$stoData['addtime'],"usetime"=>$stoData['addtime']);
                Model::ins("StoBusinessCode")->insert($businessCodeData);
            }
        }
        
        return ["code" => "200"];
    }
    
    /**
    * @user 用户角色开启
    * @param 
    * @author jeeluo
    * @date 2017年4月27日下午3:24:43
    */
    private function addRoleOpen($params, $type) {
        // 角色开启
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
        
        $data['customerid'] = $cus['id'];
        $data['area'] = $params['area'];
        $data['address'] = $params['company_area'];
        $data['area_code'] = $params['area_code'];
        $data['addtime'] = $params['addtime'];
        $data['enable'] = 1;
        
        
        // 判断牛粉是否存在
        $cusRoleFans = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => self::defaultRole));
        
        if(empty($cusRoleFans)) {
            $data['role'] = self::defaultRole;
            Model::ins("CusRole")->insert($data);
        }
        
        // 判断牛掌柜是否存在
        $cusRoleSto = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => self::stoRole));
        if(empty($cusRoleSto)) {
            $data['role'] = self::stoRole;
            $data['enable'] = 1;
            Model::ins("CusRole")->insert($data);
        }
        
        // 添加当前角色
        $data['role'] = self::busRole;
        $data['enable'] = 1;
        $data['grade'] = $type != -1 ? 1 : 0;
        Model::ins("CusRole")->insert($data);
    }
    
    /**
    * @user 建立用户角色关系
    * @param 
    * @author jeeluo
    * @date 2017年4月27日下午4:47:12
    */
    private function addCusRelation($params) {
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
        
        $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $params['cus_role_id']));
        $parentCusRela = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $recoParentInfo['role']), "parentid");
        
        
        // 牛商
        $recoBusParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::busRole, "parentrole" => $recoParentInfo['role']), "COUNT(id) as num ");
        
        if($recoParentInfo['role'] == self::orRole) {
            if($recoBusParentCount['num'] < CommonRoleModel::orRecoMaxBus()) {
                Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::busRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::orRole, 
                    "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela) ? self::orRole : self::company_cus, "addtime" => $params['addtime']));
            } else {
                Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::busRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => $params['addtime']));
            }
        } else {
            Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::busRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => $recoParentInfo['role'], "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela) ? $recoParentInfo['role'] : self::company_cus, "addtime" => $params['addtime']));
        }
        
        // 牛掌柜
        $cusRelaSto = Model::ins("CusRelation")->getRow(array("customerid" => $cus['id'], "role" => self::stoRole));
        if(empty($cusRelaSto)) {
            $parentid = $parentRole = $grandpa = $grandpaRole = self::company_cus;
            
            // 查询推荐人是否有能推荐牛掌柜的角色范围
            $roleRange = Model::new("User.RoleReco")->getRoleRecoedType(array("selfRoleType" => self::stoRole));
            foreach ($roleRange as $k => $v) {
                // 查询推荐人是否有对应的角色
//                 $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $v, "parentrole" => $v), "parentid, parentrole");
                $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $v), "parentid, parentrole");
                
                if($v == self::orRole) {
                    // 判断个数
                    $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::stoRole, "parentrole" => $v), "COUNT(id) as num ");
                    if($recoStoParentCount['num'] >= CommonRoleModel::orRecoMaxSto()) {
                        break;
                    }
                }
                
                if(!empty($parentRoleInfo)) {
                    $parentid = $recoParentInfo['customerid'] ? $recoParentInfo['customerid'] : self::company_cus;
                    $parentRole = $v;
                    $grandpa = !empty($parentRoleInfo) ? $parentRoleInfo['parentid'] : self::company_cus;
                    $grandpaRole = !empty($parentRoleInfo) ? $parentRoleInfo['parentrole'] : self::company_cus;
                    break;
                }
                
                if($v == self::orRole) {
                    $parentid = $recoParentInfo['customerid'];
                    $parentRole = $v;
                    $grandpa = self::company_cus;
                    $grandpaRole = self::company_cus;
                }
            }
            Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::stoRole, "parentid" => $parentid, "parentrole" => $parentRole, "grandpaid" => $grandpa, "grandparole" => $grandpaRole, "addtime" => $params['addtime']));
        }
        
        // 牛粉表
        $nfRelationInfo = Model::ins("CusRelationNf")->getRow(array("customerid" => $cus['id']), "id");
        if(empty($nfRelationInfo)) {
            // 写入牛粉表
            Model::ins("CusRelationNf")->insert(array("customerid" => $cus['id'], "parentid" => $recoParentInfo['customerid'], "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "addtime" => $params['addtime']));
        }
        
        $fansCusRelation = Model::ins("CusRelation")->getRow(array("customerid" => $cus['id'], "role" => self::defaultRole), "id");
        
        if(empty($fansCusRelation)) {
            
            $recoParentFans = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => self::defaultRole, "parentrole" => self::defaultRole), "parentid");
            
            Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::defaultRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::defaultRole, 
                "grandpaid" => !empty($recoParentFans) ? $recoParentFans['parentid'] : self::company_cus, "grandparole" => !empty($recoParentFans) ? self::defaultRole : self::company_cus, "addtime" => $params['addtime']));
        }
        
        CommonRoleModel::roleRelationRole(array("userid" => $recoParentInfo['customerid'], "roleid" => 1, "customerid" => $cus['id']));
    }
    
    /**
    * @user 有条件的查询
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午3:07:43
    */
    public function getWhereList($where) {
        
        $busWhere['ischeck'] = $where['ischeck'];
        $busWhere['enable'] = $where['enable'];
        $busList = Model::ins("BusBusiness")->getList($busWhere, "*", "id desc", 100);
        $busList = self::getRelatedList($busList);
        
        unset($where['ischeck']);
        unset($where['enable']);
        
        // 商家信息表
        $busInfoList = Model::ins("BusBusinessInfo")->getList($where, "*", "id desc", 100);
        $busInfoList = self::getRelatedList($busInfoList);
        
        $list = self::getIntersecCustomerIds($busList, $busInfoList);
        
        $list = self::pageList($list);
        
        return $list;
    }
    
    /**
    * @user 获取id为索引，关联列表
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午3:32:33
    */
    public function getRelatedList($customerList, $key = 'id')
    {
        $list = array();
        foreach ($customerList as $customer){
            $id        = $customer[$key];
            $list[$id] = $customer;
        }
    
        return $list;
    }
    
    public function getInverseList($customerList, $key = "id") {
        $list = array();
        foreach ($customerList as $customer) {
            $list[] = $customer[$key];
        }
        
        print_r($list);
        exit;
        
        return $list;
    }
    
    /**
    * @user 合并数据信息
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午3:33:43
    */
//     private function getIntersecCustomerIds($cusList, $cusInfoList) {
//         $result = array();
//         if(!empty($cusList)) {
//             $count = 0;
//             foreach ($cusList as $key => $cus) {
//                 if(!empty($cusInfoList[$key])) {
//                     $result[$count] = array_merge($cus, $cusInfoList[$key]);
//                     $count++;
//                 }
//             }
//         }
//         return $result;
//     }

    private function getIntersecCustomerIds($cusLists, $key) {
        $result = array();
        foreach ($cusLists as $cusList) {
            $temp = self::getInverseList($cusLists, $key);
            
            $result = array_merge($result, $temp);
            $result = array_unique($result);
        }
        
        return $result;
    }
    
    /**
    * @user 分页数据
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午3:33:52
    */
    public function pageList($list, $flag=1)
    {
        $count = count($list);
    
        $page       = Request::instance()->param('page');
        $page       = !empty($page)&&is_numeric($page)?$page:1;
        $pagesize   = Request::instance()->param('pagesize');
        $pagesize   = !empty($pagesize)&&is_numeric($pagesize)?$pagesize:20;
    
        $list = array_slice($list, (($page-1)*$pagesize), $pagesize);
        if($flag==1){
            return array(
                "total" => $count,
                "list"  => $list,
            );
        }else{
            return $list;
        }
    }
    
    /**
    * @user 禁用操作
    * @param 
    * @author jeeluo
    * @date 2017年4月7日下午7:43:02
    */
    public function enable($params) {
        // 确保属性值修改
        if(!in_array($params['enable'], $this->enable_arr)) {
            return ["code" => "400", "data" => "选择正确操作"];
        }
        
        // 执行修改操作
        Model::ins("BusBusiness")->modify(array("enable" => $params['enable']), array("id" => $params['id']));
        return ["code" => "200"];
    }
    
    public function updateRecoInfo($params) {
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['instroducerMobile']), "id");
        
        $updateData['cus_role_id'] = -1;
        
        if(!empty($cus['id'])) {
            // 查看用户角色
            $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => $params['instroducerRole']));
            if(!empty($cusRole)) {
                $updateData['cus_role_id'] = $cusRole['id'];
            }
        }
        
        $price_type = '';
        foreach ($params['price_type'] as $v) {
            $price_type .= $v.",";
        }
        
        $updateData['price_type'] = substr($price_type, 0, -1);
        
        // 执行修改操作
        Model::ins("RoleRecoBus")->modify($updateData, array("id" => $params['id']));
        return ["code" => "200"];
    }
    
    
    public function examSend($params) {
        $recoId = $params['recoId'];
        
        if(!empty($recoId)) {
            $title = '';
            $content = '';
            $recoBusInfo = Model::ins("RoleRecoBus")->getRow(["id"=>$recoId],"cus_role_id,company_name,status,remark");
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $recoBusInfo['cus_role_id']));
        
            if($recoBusInfo['status'] == 2) {
                $title = "分享成功";
                $content = "您分享".$recoBusInfo['company_name']."为牛商,已审核成功!";
            } else {
                $title = "分享失败";
                $content = "您分享".$recoBusInfo['company_name']."为牛商,未通过审核!原因:".$recoBusInfo['remark'];
            }
        
            Model::new("Msg.SendMsg")->SendSysMsg(["userid"=>$recoParentInfo['customerid'],"title"=>$title,"content"=>$content]);
        }
        return true;
    }
}