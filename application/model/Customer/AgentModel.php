<?php
// +----------------------------------------------------------------------
// |  [ 牛人模型]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-04-07
// +----------------------------------------------------------------------
namespace app\model\Customer;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;
use app\model\StoBusiness\StobusinessModel;
use app\model\BusinessLogin\LoginModel;
use app\model\Sys\CommonModel;

class AgentModel
{
    const company_cus = -1;
    const defaultRole = 1;
    const orRole = 2;
    const enRole = 3;
    const busRole = 4;
    const stoRole = 5;
    const countyRole = 6;
    const cityRole = 7;
    const ndRole = 8;
    const personType = 1;
    const companyType = 2;
    protected $_recoModelName = '';
    protected $_agentType = '';
    
    protected function _pass($recoInfo, $agent_type) {
        
        // 判断推荐代理是否已经存在
        $agent = Model::ins("CusCustomerAgent")->getRow(array("enable" => 1, "join_code" => ["like","%".$recoInfo['join_code']."%"]));
        if(!empty($agent)) {
            return ["code" => "400", "data" => "地区代理已经存在"];
        }
        
        // 写入用户方面数据
        $customerid = self::cusCustomer($recoInfo);
        
        // 代理用户
        $agentInfo = Model::ins("CusCustomerAgent")->getRow(array("customerid" => $customerid, "agent_type" => $agent_type));
        if(!empty($agentInfo)) {
            return ["code" => "400", "data" => "用户代理已存在"];
        }
        
        // 角色开启
        $roleOpen = self::roleOpen($recoInfo, $customerid, $agent_type);
        if(!$roleOpen) {
            return ["code" => "400", "data" => "用户角色已存在，数据异常"];
        }
        
        // 创建代理信息
        $agentStatus = self::createCusAgent($recoInfo, $customerid, $agent_type);
        if(!$agentStatus) {
            return ["code" => "400", "data" => "代理信息数据添加异常，请联系管理员"];
        }
        
        $recoInfo['source'] = !empty($recoInfo['source']) ? $recoInfo['source'] : 1;
        // 建立角色关系
        self::createCusRelation($recoInfo, $customerid, $agent_type);
        
        return ["code" => "200"];
    }
    
    /**
    * @user 添加操作
    * @param 
    * @author jeeluo
    * @date 2017年5月17日下午4:58:58
    */
    protected function _addAgent($params) {
        $recoResult = self::addRecoAgent($params);
        if($recoResult["code"] != "200") {
            return ["code" => $recoResult["code"], "data" => $recoResult["data"]];
        }
        
        // 标识来源字段
        $recoResult["data"]['source'] = 2;
        
        $passResult = self::_pass($recoResult["data"], $this->_agentType);
        if($passResult["code"] != "200") {
            return ["code" => $passResult["code"], "data" => $passResult["data"]];
        }
        
        // 修改推荐表数据
        $recoUpdateData['status'] = 2;
        $recoUpdateData['examinetime'] = getFormatNow();
        
        $recoModel = $this->_getRecoModel();
        $recoModel->modify($recoUpdateData, array("id" => $recoResult['data']['id']));
        
        return ["code" => "200"];
    }
    
    /**
    * @user 修改审核信息
    * @param 
    * @author jeeluo
    * @date 2017年6月20日上午9:47:26
    */
    protected function _editReco($params) {
        $recoModel = $this->_getRecoModel();
        
        // 高德api 获取经纬度信息
        $areaname = CommonModel::getSysArea($params['area_code']);
        $map = CommonModel::getAddressLngLat($areaname['data'].$params['address']);
        if($map['code'] == "200") {
            $params['longitude'] = $map['data']['lngx'];
            $params['latitude'] = $map['data']['laty'];
        } else {
            $map = CommonModel::getAddressLngLat($areaname['data']);
            $params['longitude'] = $map['data']['lngx'];
            $params['latitude'] = $map['data']['laty'];
        }
        // 修改数据库
        $status = $recoModel->modify($params, ["id"=>$params['id']]);
//         if($status) {
            return ["code" => "200"];
//         }
//         return ["code" => "400", "result" => "修改失败，请联系管理员"];
    }
    
    /**
    * @user 修改代理信息
    * @param 
    * @author jeeluo
    * @date 2017年6月19日下午3:58:03
    */
    protected function _editAgent($params) {
        // 根据代理id 获取信息
        $agentInfo = Model::ins("CusCustomerAgent")->getRow(["id"=>$params['id']],"area_code");
        
        // 高德api 获取经纬度信息
        $areaname = CommonModel::getSysArea($agentInfo['area_code']);
        $map = CommonModel::getAddressLngLat($areaname['data'].$params['address']);
        if($map['code'] == "200") {
            $params['lngx'] = $map['data']['lngx'];
            $params['laty'] = $map['data']['laty'];
        } else {
            $map = CommonModel::getAddressLngLat($areaname['data']);
            $params['lngx'] = $map['data']['lngx'];
            $params['laty'] = $map['data']['laty'];
        }
        // 修改数据库
        $status = Model::ins("CusCustomerAgent")->modify($params, ["id"=>$params['id']]);
//         if($status) {
            return ["code" => "200"];
//         }
//         return ["code" => "400","result"=>"修改失败，请联系管理员"];
    }
    
    /**
    * @user 写入推荐信息
    * @param 
    * @author jeeluo
    * @date 2017年5月17日下午4:40:02
    */
    private function addRecoAgent($params) {
        $recoModel = $this->_getRecoModel();
        
        // 查看引荐人信息
        $instroducerCus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['instroducerMobile']), "id");
        if(empty($instroducerCus)) {
            return ["code" => "20103", "data" => "分享人信息不存在"];
        }
        
        $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $instroducerCus['id'], "role"=>$params["instroducerRole"]), "id");
        if(empty($cusRole['id'])) {
            return ["code" => "20103", "data" => "用户角色信息不存在"];
        }
        
        if($params["type"] == self::companyType) {
            // 查看数据库是否存在该身份证号码数据
            $busInfo = $recoModel->getRow(array("cus_role_id" => $cusRole["id"], "charge_idnumber" => $params['charge_idnumber']),"id,status","id desc");
            if(!empty($busInfo)) {
                if($busInfo['status'] != 3) {
                    return ["code" => "20014", "data" => "您推荐该身份证号码用户了"];
                }
            }
        }
        
        $params['cus_role_id'] = $cusRole['id'];
        $joinAreaName = CommonModel::getSysArea($params['join_code']);

        $params['join_area'] = $joinAreaName['data'];
        /*
         * 高德api 获取经纬度信息
         */
        $areaname = CommonModel::getSysArea($params['area_code']);
        $map = CommonModel::getAddressLngLat($areaname['data'].$params['address']);
        
        $params['area'] = $areaname['data'];
        $params['longitude'] = $map['data']['lngx'];
        $params['latitude'] = $map['data']['laty'];
        $params['addtime'] = getFormatNow();
        
        unset($params['instroducerMobile']);
        unset($params['instroducerRole']);
        
        // 写入推荐表
        $recoDataId = $recoModel->insert($params);
        if($recoDataId) {
            $params['id'] = $recoDataId;
            return ["code" => "200", "data" => $params];
        }
        return ["code" => "400", "data" => "推荐数据添加异常，请联系管理员"];
    }
    
    protected function _getRecoModel() {
        return Model::ins($this->_recoModelName);
    }
    
    /**
    * @user 用户信息创建
    * @param 
    * @author jeeluo
    * @date 2017年4月7日上午11:08:54
    */
    private function cusCustomer($recoInfo) {
        $mobile = 0;
        if($recoInfo['type'] == 1) {
            $mobile = $recoInfo['mobile'];
        } else {
            $mobile = $recoInfo['charge_mobile'];
        }
        
        $randNumber = 123456;
        $userpwd = LoginModel::PwdEncode($randNumber);
        
        $cusInfo = Model::ins('CusCustomer')->getRow(array("mobile" => $mobile));
        
        $realname = '';
        $idnumber = '';
        if($recoInfo['type'] == 1) {
            // 个人类型
            $realname = $recoInfo['realname'];
            $isnameauth = 0;
        } else {
            $realname = $recoInfo['charge_name'];
            $idnumber = $recoInfo['charge_idnumber'];
            $isnameauth = 1;
        }
        
        // 检查用户
        if(empty($cusInfo)) {
            $cusData['mobile'] = $cusData['username'] = $mobile;
            $cusData['userpwd'] = $userpwd;
            $cusData['createtime'] = getFormatNow();
            $cusData['id'] = Model::ins("CusCustomer")->insert($cusData);
//                 $authArr = array("realname"=>$recoInfo['corporation_name'],"$idnumber"=>$recoInfo['corporation_idnumber']);
//                 if(!CommonModel::nameauth_validate($authArr)) {
//                     return ["code"=>"26001","data"=>"实名认证错误"];
//                 }
//                 $isnameauth=1;
            
            $infoData = array("id" => $cusData['id'], "realname" => $realname, "idnumber" => $idnumber, "isnameauth"=>$isnameauth, "area" => $recoInfo['area'], "area_code" => $recoInfo['area_code'], "address" => $recoInfo['address']);
            
            Model::ins("CusCustomerInfo")->insert($infoData);
            
            return $cusData['id'];
        } else {
//             $cusUserInfo = Model::ins("CusCustomerInfo")->getRow(array("id"=>$cusInfo['id']), "isnameauth");
//             if(!empty($cusUserInfo)) {
//                 if($cusUserInfo['isnameauth'] != 1) {
//                     // 查询数据对否
//                     $authArr = array("realname"=>$recoInfo['corporation'], "idnumber"=>$recoInfo['idnumber'], "customerid"=>$cusInfo['id']);
//                     if(!CommonModel::nameauth_validate($authArr)) {
//                         return ["code"=>"26001", "data"=>"实名认证错误"];
//                     }
//                     CommonModel::userNameAuth($authArr);
//                 }
//             } else {
//                 $authArr = array("realname"=>$recoInfo['corporation'], "idnumber"=>$recoInfo['idnumber'], "customerid"=>$cusInfo['id']);
//                 if(!CommonModel::nameauth_validate($authArr)) {
//                     return ["code"=>"26001", "data"=>"实名认证错误"];
//                 }
//                 CommonModel::userNameAuth($authArr);
//             }
            if(empty($cusInfo['userpwd'])) {
                $cusData['userpwd'] = $userpwd;
                Model::ins("CusCustomer")->modify($cusData, array("id" => $cusInfo['id']));
            }
            $cusInfoData = Model::ins("CusCustomerInfo")->getRow(array("id"=>$cusInfo['id']),"isnameauth");
            if(empty($cusInfoData)) {
                $infoData = array("id" => $cusInfo['id'], "realname" => $realname, "idnumber" => $idnumber, "isnameauth"=>$isnameauth, "area" => $recoInfo['area'], "area_code" => $recoInfo['area_code'], "address" => $recoInfo['address']);
                Model::ins("CusCustomerInfo")->insert($infoData);
            } else {
                if($cusInfoData['isnameauth'] != 1) {
                    Model::ins("CusCustomerInfo")->modify(array("realname"=>$realname,"idnumber"=>$idnumber,"isnameauth"=>$isnameauth),array("id"=>$cusInfo['id']));
                }
            }
        }
        return $cusInfo['id'];
    }
    
    /**
    * @user 角色开启
    * @param 
    * @author jeeluo
    * @date 2017年4月7日上午11:10:24
    */
    private function roleOpen($recoInfo, $customerid, $agent_type) {
        // 检查当前角色
        $role = self::defaultRole;
        if($agent_type == 1) {
            $role = self::cityRole;
        } else {
            $role = self::countyRole;
        }
        
        $cusRoleInfo = Model::ins("CusRole")->getRow(array("customerid" => $customerid, "role" => $role));
        if(empty($cusRoleInfo)) {
            
            // 公共字段值
            $data['customerid'] = $customerid;
            $data['area'] = $recoInfo['area'];
            $data['address'] = $recoInfo['company_area'];
            $data['area_code']  = $recoInfo['area_code'];
            $data['addtime'] = getFormatNow();
            $data['enable'] = 1;
            
            // 判断牛粉是否存在
            $cusRoleFans = Model::ins('CusRole')->getRow(array("customerid" => $customerid, "role" => self::defaultRole));
            
            if(empty($cusRoleFans)) {
                $data['role'] = self::defaultRole;
                Model::ins("CusRole")->insert($data);
            }
            
            // 判断牛达人是否存在
            $cusRoleEn = Model::ins('CusRole')->getRow(array("customerid" => $customerid, "role" => self::ndRole));
            if(empty($cusRoleEn)) {
                $data['role'] = self::ndRole;
                Model::ins("CusRole")->insert($data);
            }
            
            // 判断牛创客是否存在
            $cusRoleEn = Model::ins('CusRole')->getRow(array("customerid" => $customerid, "role" => self::enRole));
            if(empty($cusRoleEn)) {
                $data['role'] = self::enRole;
                Model::ins("CusRole")->insert($data);
            }
            
            // 判断牛商是否存在
            $cusRoleBus = Model::ins('CusRole')->getRow(array("customerid" => $customerid, "role" => self::busRole));
            if(empty($cusRoleBus)) {
                $data['role'] = self::busRole;
                $data['enable'] = 1;
                Model::ins("CusRole")->insert($data);
            }
            
            // 判断牛掌柜是否存在
            $cusRoleSto = Model::ins('CusRole')->getRow(array("customerid" => $customerid, "role" => self::stoRole));
            if(empty($cusRoleSto)) {
                $data['role'] = self::stoRole;
                $data['enable'] = 1;
                Model::ins("CusRole")->insert($data);
            }

            
            // 添加当前角色
            $data['role'] = $role;
            $data['enable'] = 1;
            Model::ins("CusRole")->insert($data);
            
            return true;
        } else {
            // 当前角色存在，说明有异常
            return false;
        }
    }
    
    /**
    * @user 创建代理信息
    * @param 
    * @author jeeluo
    * @date 2017年4月7日上午11:10:32
    */
    private function createCusAgent($recoInfo, $customerid, $agent_type) {
        
        $type_data = array();
        if($recoInfo['type'] == 1) {
            $type_data = array("realname" => $recoInfo['realname']);
        } else {
            $type_data = array("realname" => $recoInfo['company_name'], "company_name"=>$recoInfo['company_name'], "charge_idnumber" => $recoInfo['charge_idnumber'], "charge_name" => $recoInfo['charge_name'], "charge_mobile" => $recoInfo['charge_mobile'],
                "corporation_name" => $recoInfo['corporation_name'], "corporation_idnumber" => $recoInfo['corporation_idnumber'], "licence_image" => $recoInfo['licence_image'],"corporation_image" => $recoInfo['corporation_image']);
        }
        
        // 判断该用户是否已经有实体店了
        $stoInfo = Model::ins("StoBusiness")->getRow(array("customerid" => $customerid));
        
        if(empty($stoInfo)) {
            // 写入实体店数据
            $stoData = array("customerid" => $customerid, "businessname" => $type_data['realname'], "addtime" => getFormatNow(), "ischeck"=>1, "nopasstype"=>0, "enable"=>-1);
            
            $sto_id = Model::ins("StoBusiness")->insert($stoData);
            
            // 写入实体店信息
            $stoInfoData = array("id" => $sto_id, "businessname" => $type_data['realname'], "businesslogo"=>'', "enable"=>-1);
            Model::ins("StoBusinessInfo")->insert($stoInfoData);
            
            // 写入实体店基本信息
            $business_code = StobusinessModel::creatStoBusCode(array("businessid" => $sto_id));
            $stoBaseInfoData = array("id" => $sto_id, "businessname" => $type_data['realname'], "business_code" => $business_code['business_code'], "discount" => 90);
            Model::ins("StoBusinessBaseinfo")->insert($stoBaseInfoData);

            // 判断平台号是否已经在 stoBusinessCode 表产生
            $stoBusinesCode = Model::ins("StoBusinessCode")->getRow(["business_code"=>$business_code['business_code']],"id,isuse");
            if(!empty($stoBusinesCode['isuse'])) {
                // 查看属性
                if($stoBusinesCode['isuse'] == 1) {
                    return ["code" => "400", "data" => "商家平台号已经被绑定了"];
                }
                // 进行数据修改
                Model::ins("StoBusinessCode")->modify(["business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$stoData['businessname'],"customerid"=>$customerid,"usetime"=>$stoData['addtime']],["id"=>$stoBusinesCode['id']]);
            } else {
                // 添加数据
                $businessCodeData = array("business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$stoData['businessname'],"customerid"=>$customerid,"addtime"=>$stoData['addtime'],"usetime"=>$stoData['addtime']);
                
                Model::ins("StoBusinessCode")->insert($businessCodeData);
            }
        }
        
        // 判断该用户是否已经有牛商
        $busInfo = Model::ins("BusBusiness")->getRow(array("customerid" => $customerid));
        if(empty($busInfo)) {
            
            $cus = Model::ins("CusCustomer")->getRow(["id"=>$customerid],"mobile");
            // 写入牛商数据
            $busData = array("price_type"=>"1,2","businessname"=>$type_data['realname'],"addtime"=>getFormatNow(),"enable"=>1,"customerid"=>$customerid);
            $busid = Model::ins("BusBusiness")->insert($busData);
            
            // 写入牛商信息表数据
            $busInfoData = array("id"=>$busid,"price_type"=>"1,2","businessname"=>$type_data['realname'],"mobile"=>$cus['mobile'],"addtime"=>getFormatNow());
            Model::ins("BusBusinessInfo")->insert($busInfoData);
        }
        
        // 查询推荐该用户的角色关系
        $recoParent = CommonRoleModel::getCusRole(array("cus_role_id" => $recoInfo['cus_role_id']));
        $recoParentInfo = Model::ins('CusCustomerInfo')->getRow(array("id" => $recoParent['customerid']), "realname");
        
        $public_data = array("customerid" => $customerid, "agent_type" => $agent_type, "type" => $recoInfo['type'], "introducerid" => $recoParent['customerid'], "introducername" => $recoParentInfo['realname'],
                            "mobile" => $recoInfo['mobile'], "area" => $recoInfo['area'], "area_code" => $recoInfo['area_code'], "address" => $recoInfo['address'], "lngx" => $recoInfo['longitude'], "laty" => $recoInfo['latitude'],
                            "join_code" => $recoInfo['join_code'], "join_area" => $recoInfo['join_area']);
        
        // 拼接数组
        $data = array_merge($public_data, $type_data);
        
        // 写入数据
        $status = Model::ins('CusCustomerAgent')->insert($data);
        if($status) {
            return true;
        }
        return false;
    }
    
    /**
    * @user 建立用户角色关联关系
    * @param 
    * @author jeeluo
    * @date 2017年4月7日上午11:10:40
    */
    private function createCusRelation($recoInfo, $customerid, $agent_type) {
        // 检查当前角色
        $role = self::defaultRole;
        if($agent_type == 1) {
            $role = self::cityRole;
        } else {
            $role = self::countyRole;
        }
        // 查询推荐该用户的角色关系
        $recoParent = CommonRoleModel::getCusRole(array("cus_role_id" => $recoInfo['cus_role_id']));
        
        // 牛粉关联关系建立
        $cusRelaNf = Model::ins("CusRelation")->getRow(array("customerid" => $customerid, "role" => self::defaultRole));
        if(empty($cusRelaNf)) {
            
            // 该推荐者的推荐情况
            $recoParentRela = Model::ins("CusRelation")->getRow(array("customerid" => $recoParent['customerid'], "role" => self::defaultRole, "parentrole" => self::defaultRole), "parentid, parentrole");
            
            $grandpaid = !empty($recoParentRela['parentid']) ? $recoParentRela['parentid'] : self::company_cus;
            $grandparole = !empty($recoParentRela['parentid']) ? $recoParentRela['parentrole'] : self::company_cus;
            
            Model::ins("CusRelationNf")->insert(array("customerid" => $customerid, "parentid" => $recoParent['customerid'], "grandpaid" => $grandpaid, "addtime" => getFormatNow()));
            Model::ins("CusRelation")->insert(array("customerid" => $customerid, "role" => self::defaultRole, "parentid" => $recoParent['customerid'], "parentrole" => self::defaultRole, "grandpaid" => $grandpaid, "grandparole" => $grandparole, "addtime" => getFormatNow()));
            
            if($recoInfo['source'] == 1) {
                // 审核产生的 牛粉关系  产生分润
                Model::new("Amount.Role")->share_role(["userid"=>$recoParent['customerid'],"customerid"=>$customerid]);
                // Model::new("Sys.Mq")->submit();
            }
        }
        
        // 牛达人关联关系建立
        $cusRelaNd = Model::ins("CusRelation")->getRow(array("customerid"=>$customerid,"role"=>self::ndRole));
        if(empty($cusRelaNd)) {
            // 查询推荐人是否有牛达人角色(没有的话 归属公司)
            $parentNdRole = Model::ins('CusRelation')->getRow(array("customerid" => $recoParent['customerid'], "role" => self::ndRole), "parentid, parentrole");
            $parentid = $parentRole = $grandpa = $grandpaRole = self::company_cus;
            if(!empty($parentNdRole)) {
                $parentid = $recoParent['customerid'] ? $recoParent['customerid'] : self::company_cus;
                $parentRole = !empty($parentNdRole) ? self::ndRole : self::company_cus;
                $grandpa = !empty($parentNdRole) ? $parentNdRole['parentid'] : self::company_cus;
                $grandpaRole = !empty($parentNdRole) ? $parentNdRole['parentrole'] : self::company_cus;
            }
            Model::ins("CusRelation")->insert(array("customerid" => $customerid, "role" => self::ndRole, "parentid" => $parentid, "parentrole" => $parentRole, "grandpaid" => $grandpa, "grandparole" => $grandpaRole, "addtime" => getFormatNow()));
        }
        
        // 牛创客关联关系建立
        $cusRelaEn = Model::ins("CusRelation")->getRow(array("customerid" => $customerid, "role" => self::enRole));
        if(empty($cusRelaEn)) {
            // 查询推荐人是否有牛创客角色(没有的话 归属公司)
//             $parentEnRole = Model::ins('CusRelation')->getRow(array("customerid" => $recoParent['customerid'], "role" => self::enRole, "parentrole" => self::enRole), "parentid, parentrole");
            $parentEnRole = Model::ins('CusRelation')->getRow(array("customerid" => $recoParent['customerid'], "role" => self::enRole), "parentid, parentrole");
            
            $parentid = $parentRole = $grandpa = $grandpaRole = self::company_cus;
            if(!empty($parentEnRole)) {
                $parentid = $recoParent['customerid'] ? $recoParent['customerid'] : self::company_cus;
                $parentRole = !empty($parentEnRole) ? self::enRole : self::company_cus;
                $grandpa = !empty($parentEnRole) ? $parentEnRole['parentid'] : self::company_cus;
                $grandpaRole = !empty($parentEnRole) ? $parentEnRole['parentrole'] : self::company_cus;
            }
            Model::ins("CusRelationEn")->insert(array("customerid" => $customerid, "parentid" => $parentid, "grandpaid" => $grandpa, "addtime" => getFormatNow()));
            Model::ins("CusRelation")->insert(array("customerid" => $customerid, "role" => self::enRole, "parentid" => $parentid, "parentrole" => $parentRole, "grandpaid" => $grandpa, "grandparole" => $grandpaRole, "addtime" => getFormatNow()));
        }
        
        // 牛商关联关系建立
        $cusRelaBus = Model::ins("CusRelation")->getRow(array("customerid"=>$customerid,"role"=>self::busRole));
        if(empty($cusRelaBus)) {
            $parentid = $parentRole = $grandpa = $grandpaRole = self::company_cus;
            $roleRange = Model::new("User.RoleReco")->getNewRoleRecoedType(["selfRoleType"=>self::busRole]);
            foreach ($roleRange as $k => $v) {
                $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParent['customerid'], "role" => $v), "parentid, parentrole");
                
                if($v == self::ndRole) {
                    // 判断个数
                    $recoBusNdParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParent['customerid'], "role" => self::busRole, "parentrole" => $v), "COUNT(id) as num ");
                    if($recoBusNdParentCount['num'] >= CommonRoleModel::ndRecoMaxBus()) {
                        continue;
                    }
                } else if($v == self::orRole) {
                    // 判断个数
                    $recoBusOrParentCount = Model::ins("CusRelation")->getRow(array("parentid"=>$recoParent['customerid'],"role"=>self::busRole,"parentrole"=>$v), "COUNT(id) as num ");
                    if($recoBusOrParentCount['num'] >= CommonRoleModel::orRecoMaxBus()) {
                        break;
                    }
                }
                if(!empty($parentRoleInfo)) {
                    $parentid = $recoParent['customerid'] ? $recoParent['customerid'] : self::company_cus;
                    $parentRole = $v;
                    $grandpa = !empty($parentRoleInfo) ? $parentRoleInfo['parentid'] : self::company_cus;
                    $grandpaRole = !empty($parentRoleInfo) ? $parentRoleInfo['parentrole'] : self::company_cus;
                    break;
                }
            }
            Model::ins("CusRelation")->insert(array("customerid" => $customerid, "role" => self::busRole, "parentid" => $parentid, "parentrole" => $parentRole, "grandpaid" => $grandpa, "grandparole" => $grandpaRole, "addtime" => getFormatNow()));
        }
        
        // 牛掌柜关联关系建立
        $cusRelaSto = Model::ins("CusRelation")->getRow(array("customerid" => $customerid, "role" => self::stoRole));
        if(empty($cusRelaSto)) {
            $parentid = $parentRole = $grandpa = $grandpaRole = self::company_cus;

            // 查询推荐人是否有能推荐牛掌柜的角色范围
//             $roleRange = Model::new("User.RoleReco")->getRoleRecoedType(array("selfRoleType" => self::stoRole));
            $roleRange = Model::new("User.RoleReco")->getNewRoleRecoedType(array("selfRoleType" => self::stoRole));
            
            foreach ($roleRange as $k => $v) {
                // 查询推荐人是否有对应的角色
//                 $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParent['customerid'], "role" => $v, "parentrole" => $v), "parentid, parentrole");
                $parentRoleInfo = Model::ins('CusRelation')->getRow(array("customerid" => $recoParent['customerid'], "role" => $v), "parentid, parentrole");
                
                if($v == self::orRole) {
                    // 判断个数
                    $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParent['customerid'], "role" => self::stoRole, "parentrole" => $v), "COUNT(id) as num ");
                    if($recoStoParentCount['num'] >= CommonRoleModel::orRecoMaxSto()) {
                        break;
                    }
                }
                
                if(!empty($parentRoleInfo)) {
                    $parentid = $recoParent['customerid'] ? $recoParent['customerid'] : self::company_cus;
                    $parentRole = $v;
                    $grandpa = !empty($parentRoleInfo) ? $parentRoleInfo['parentid'] : self::company_cus;
                    $grandpaRole = !empty($parentRoleInfo) ? $parentRoleInfo['parentrole'] : self::company_cus;
                    break;
                }
                
//                 if($v == self::orRole) {
//                     $parentid = $recoParent['customerid'];
//                     $parentRole = $v;
//                     $grandpa = self::company_cus;
//                     $grandpaRole = self::company_cus;
//                 }
            }
            Model::ins("CusRelation")->insert(array("customerid" => $customerid, "role" => self::stoRole, "parentid" => $parentid, "parentrole" => $parentRole, "grandpaid" => $grandpa, "grandparole" => $grandpaRole, "addtime" => getFormatNow()));
        }
        
        // 代理关联关系建立
        $cusRelaAgent = Model::ins("CusRelation")->getRow(array("customerid" => $customerid, "role" => $role));
        if(empty($cusRelaAgent)) {
            Model::ins("CusRelation")->insert(array("customerid" => $customerid, "role" => $role, "parentid" => $recoParent['customerid'], "parentrole" => $recoParent['role'], "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => getFormatNow()));
        }
        
        CommonRoleModel::roleRelationRole(array("userid" => $recoParent['customerid'], "roleid" => 1, "customerid" => $customerid));
        
        return true;
    }
}