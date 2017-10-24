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
use app\model\StoBusiness\StobusinessModel;
use app\model\Sys\CommonRoleModel;
use app\model\Sys\CommonModel;
use app\lib\Log;
use app\model\StoBusiness\PhysicalShopModel;
use app\lib\Thirdparty\Api as ThirdpartyApi;

class BullStoModel
{
    const stoRole = 5;
    const defaultRole = 1;
    const orRole = 2;
    
    const ndRole = 8;
    
    const company_cus = -1;
    const apisource = 1;
    const supersource = 2;
    protected $status_arr = array(1, 2, 3); // 1待审核  2审核成功  3审核失败
    
    public function updateExamStatus($params) {
        // 确保状态修改
        if($params['status'] != 2 && $params['status'] != 3) {
            return ["code" => "400", "data" => "选择正确操作"];
        }
        
        $stoUpdateData = array();
        if($params['status'] == 2) {
            // 假如审核成功
            $recoStoInfo = Model::ins("RoleRecoSto")->getRow(array("id" => $params['id']));
            $cusInfo = Model::ins("CusCustomer")->getRow(array("mobile" => $recoStoInfo['mobile']));
        
            // 检查用户
            if(empty($cusInfo)) {
                // 无此用户(添加用户表)
                $cusData['mobile'] = $cusData['username'] = $recoStoInfo['mobile'];
                $cusData['createtime'] = getFormatNow();
                $cusInfo['id'] = Model::ins("CusCustomer")->insert($cusData);
            
                // 添加用户信息表(不执行修改操作，怕影响用户自己添加的原数据)  缺少平台号
                $infoData = array("id" => $cusInfo['id'], "nickname" => $recoStoInfo['sto_name'], "idnumber" => $recoStoInfo['idnumber'], "area" => $recoStoInfo['area'], "area_code" => $recoStoInfo['area_code'], "address" => $recoStoInfo['address']);
            
                Model::ins("CusCustomerInfo")->insert($infoData);
            }
            
            // 检查用户是否已经开启商家
            $busInfo = Model::ins("StoBusiness")->getRow(array("customerid" => $cusInfo['id']));
            if(!empty($busInfo)) {
                return ["code" => "400", "data" => "用户商家已存在，数据异常"];
            }
            
            // 检查角色
            $cusRoleInfo = Model::ins("CusRole")->getRow(array("customerid" => $cusInfo['id'], "role" => self::stoRole));
            if(empty($cusRoleInfo)) {
            
                // 添加用户角色
                $stoData['customerid'] = $cusInfo['id'];
                $stoData['area'] = $recoStoInfo['area'];
                $stoData['address'] = $recoStoInfo['address'];
                $stoData['area_code']  = $recoStoInfo['area_code'];
                $stoData['addtime'] = getFormatNow();
                $stoData['role'] = self::stoRole;
                $stoData['enable'] = 1;        // 推荐牛商 赠送的牛掌柜默认为未上架状态
            
                // 检查牛粉角色是否存在
                $cusRoleFans = Model::ins('CusRole')->getRow(array("customerid" => $cusInfo['id'], "role" => self::defaultRole));
                if(empty($cusRoleFans)) {
                    $fansData['customerid'] = $cusInfo['id'];
                    $fansData['area'] = $recoStoInfo['area'];
                    $fansData['address'] = $recoStoInfo['address'];
                    $fansData['area_code']  = $recoStoInfo['area_code'];
                    $fansData['addtime'] = getFormatNow();
                    $fansData['role'] = self::defaultRole;
                    $fansData['enable'] = 1;        
            
                    Model::ins("CusRole")->insert($fansData);
                }
                Model::ins("CusRole")->insert($stoData);
            } else {
                // 角色存在，说明有异常
                return ["code" => "400", "data" => "用户角色已存在，数据异常"];
            }
            
            // 创建牛掌柜
            $insert_id = Model::ins("StoBusiness")->insert(array("businessname" => $recoStoInfo["sto_name"], "ischeck" => 1, "enable" => 1, "customerid" => $cusInfo['id'], "addtime" => getFormatNow()));
            
            $business_code = StobusinessModel::creatStoBusCode(array("businessid" => $insert_id));
            // 创建商家基本信息表
            Model::ins("StoBusinessBaseinfo")->insert(array("id" => $insert_id, "business_code" => $business_code['business_code'],"businessname" => $recoStoInfo["sto_name"], "idnumber" => $recoStoInfo['idnumber'], "mobile" => $recoStoInfo['mobile'], "address" => $recoStoInfo['address'],
                                    "servicetel" => $recoStoInfo['sto_mobile'], "description" => $recoStoInfo['description'], "typeid" => 1, "typename" => "实体", "discount" => $recoStoInfo['discount'], "delivery" => $recoStoInfo['delivery']));
            
            $cateData = StobusinessModel::getCategoryName($recoStoInfo['sto_type_id']);
            
            $stoInfoData = array("id" => $insert_id, "businessname" => $recoStoInfo['sto_name'], "businesslogo" => !empty($recoStoInfo['main_image']) ? $recoStoInfo['main_image'] : '', "categoryid" => $recoStoInfo['sto_type_id'], "categoryname" => !empty($cateData) ? $cateData['categoryname'] : '', "area" => $recoStoInfo['area'], "area_code" => $recoStoInfo['area_code'], "lngx" => $recoStoInfo['longitude'],
                                    "laty" => $recoStoInfo['latitude'], "metro_id" => $recoStoInfo['metro_id'], "district_id" => $recoStoInfo['bus_district'], "nearby_village" => $recoStoInfo['nearby_village'], "busstartime" => $recoStoInfo['sto_hour_begin'], "busendtime" => $recoStoInfo['sto_hour_end'],
                                    "licence_image" => $recoStoInfo['licence_image'], "idnumber_image" => $recoStoInfo['idnumber_image']);
            
            if(!empty($recoStoInfo['service_type'])) {
                $service_arr = array_filter(explode(",", $recoStoInfo['service_type']));
                if($service_arr[0] == 1) {
                    $stoInfoData['iswifi'] = '1';
                }

                if($service_arr[1] == 1 || $service_arr[1] == 2) {
                    $stoInfoData['isparking'] = '1';
                }

                if($service_arr[2] == 1 || $service_arr[2] == 3) {
                    $stoInfoData['isdelivery'] = '1';
                }
                // if(in_array('1', $service_arr)) {
                //     $stoInfoData['iswifi'] = '1';
                // }
                
                // if(in_array('2', $service_arr)) {
                //     $stoInfoData['isparking'] = '1';
                // }
                
                // if(in_array('3', $service_arr)) {
                //     $stoInfoData['isdelivery'] = '1';
                // }
            }
            // 创建商家信息表
            Model::ins("StoBusinessInfo")->insert($stoInfoData);

            if(!empty($recoStoInfo['sto_image'])) {
                $image_arr = array_filter(explode(",", $recoStoInfo['sto_image']));
                
                $imageData['business_id'] = $insert_id;
                $imageData['addtime'] = $recoStoInfo['addtime'];
                foreach ($image_arr as $image) {
                    $imageData['thumb'] = $image;
                    Model::ins("StoBusinessImage")->insert($imageData);
                }
            }
            
            // 判断是否有传递相册图片
            if(!empty($recoStoInfo['album_image'])) {
                $album_arr = array_filter(explode(",", $recoStoInfo['album_image']));
                
                $albumData['business_id'] = $insert_id;
                $albumData['addtime'] = $recoStoInfo['addtime'];
                foreach ($album_arr as $album) {
                    $albumData['thumb'] = $album;
                    Model::ins("StoBusinessAlbum")->insert($albumData);
                }
            }
            
            // 建立角色关联关系
            // 查询推荐该用户的角色关系
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $recoStoInfo['cus_role_id']));
            
            $recoParentFans = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => self::defaultRole, "parentrole" => self::defaultRole), "parentid");

            // 牛掌柜
            $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::stoRole, "parentrole" => $recoParentInfo['role']), "COUNT(id) as num ");
            $parentCusRela = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $recoParentInfo['role']), "parentid, parentrole");
            
            if($recoParentInfo['role'] == self::orRole) {
                if($recoStoParentCount['num'] < CommonRoleModel::orRecoMaxSto()) {
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::orRole, "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela) ? $parentCusRela['parentrole'] : self::company_cus, "addtime" => getFormatNow()));
                } else {
                    // 推荐的这个牛商 归属公司
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => getFormatNow()));
                }
            } else if($recoParentInfo['role'] == self::ndRole) {
                if($recoStoParentCount['num'] < CommonRoleModel::ndRecoMaxSto()) {
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::ndRole, "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela) ? $parentCusRela['parentrole'] : self::company_cus, "addtime" => getFormatNow()));
                } else {
                    // 推荐的这个牛商 归属公司
                    Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => getFormatNow()));
                }
            } else {
                Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => $recoParentInfo['role'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentrole'] : self::company_cus, "addtime" => getFormatNow()));
            }
            $nfRelationInfo = Model::ins("CusRelationNf")->getRow(array("customerid" => $cusInfo['id']), "id");
            if(empty($nfRelationInfo)) {
                // 写入牛粉表
                Model::ins("CusRelationNf")->insert(array("customerid" => $cusInfo['id'], "parentid" => $recoParentInfo['customerid'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "addtime" => getFormatNow()));
                
                Model::ins("CusRelation")->insert(array("customerid" => $cusInfo['id'], "role" => self::defaultRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::defaultRole, "grandpaid" => !empty($recoParentFans['parentid']) ? $recoParentFans['parentid'] : self::company_cus, "grandparole" => !empty($recoParentFans['parentid']) ? self::defaultRole : self::company_cus, "addtime" => getFormatNow()));
            }
            CommonRoleModel::roleRelationRole(array("userid" => $recoParentInfo['customerid'], "roleid" => 1, "customerid" => $cusInfo['id']));
        } else {
            // 假如审核失败
            $stoUpdateData['remark'] = $params['remark'];
        }
        $stoUpdateData['status'] = $params['status'];
        $stoUpdateData['examinetime'] = getFormatNow();
        
        $status = Model::ins("RoleRecoSto")->modify($stoUpdateData, array("id" => $params['id']));
        if($status) {
             //写入mongoDB
            $StoBusinessInfoMG = Model::Mongo('StoBusinessInfo');
           
           
            $busInfoMGData = Model::ins("StoBusinessInfo")->getRow(['id'=>$insert_id],'*');
           
            $busInfoMGData['businessid'] = $busInfoMGData['id'];

            $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$busInfoMGData['id']]);
          


            $business_code = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$busInfoMGData['id']],'business_code');
            $busInfoMGData['business_code'] = $business_code['business_code'];


            if(empty($busInfoMGData['businesslogo']))
//                 $busInfoMGData['businesslogo'] = $image_arr[0];
                $busInfoMGData['businesslogo'] = !empty($params['businesslogo']) ? $params['businesslogo'] : '';

            $busInfoMGData['loc'] =  [
                 'type' => 'Point', 
                 'coordinates' => [
                     doubleval($busInfoMGData['lngx']),//经度
                     doubleval($busInfoMGData['laty'])//纬度
                 ]
            ];
            $StoBusinessInfoMG->delete(['id'=>$busInfoMGData['id']]);
            
            $int_arr = [
                'reutnproportion' => intval($reutnproportion),
                'salecount' =>       intval($busInfoMGData['salecount']),
                'scores' =>          intval($busInfoMGData['scores']),
                'busstartime' =>     intval($busInfoMGData['busstartime']),
                'busendtime' =>      intval($busInfoMGData['busendtime']),
                'area_code' =>       intval($busInfoMGData['area_code']),
                // "enable"=>1,
                // "isshow"=>1,
            ];

            $StoBusinessInfoMG->insert($busInfoMGData,$int_arr);
            //提交门店到蜂鸟审核
            $Baseinfo = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$busInfoMGData['id']],'mobile,address');
            $chan_arr = [
                'businessname' => $busInfoMGData['businessname'],
                'mobile'       => $Baseinfo['mobile'],
                'address'      => $busInfoMGData['area'].$Baseinfo['address'],
                'lngx'         => $busInfoMGData['lngx'],
                'laty'         => $busInfoMGData['laty']
            ];
            self::chainstore($chan_arr);

            return ["code" => "200"];
        }
        return ["code" => "400", "data" => "数据添加异常，请联系管理员"];
    }
    
    public function addSto($params) {
        $cusUser = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
//         if(!empty($cusUser)) {
//             $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $cusUser['id'], "role" => self::stoRole));
//             if(!empty($cusRole)) {
//                 // 该用户已经开通牛掌柜，无法重复申请
//                 return ["code" => "20001", "data" => "您填写的手机号码 已经是供应商了"];
//             }
//         }

        if(!empty($cusUser)) {
            $cusRole = Model::ins("CusRole")->getRow(array("customerid" => $cusUser['id'], "role" => self::stoRole));
            if(!empty($cusRole)) {
                // 查询推荐者是否推荐其总店的
                $cusRelation = Model::ins("CusRelation")->getRow(["customerid"=>$cusUser['id'],"role"=>self::stoRole],"parentid");
                
                $instroducer = Model::ins("CusCustomer")->getRow(["mobile" => $params['instroducerMobile']],"id");
                if($cusRelation['parentid'] != $instroducer['id']) {
                    return ["code" => "20302", "data" => "该用户已被其它用户分享"];
                }
                
                // 查询该手机号码有多少个相似的
                $where['mobile'] = ["like", "%".$params['mobile']."_"."%"];
                $similar = Model::ins("CusCustomer")->getRow($where,"count(*) as count");
                
                $params['mobile'] .= "_".($similar['count']+1);
            }
        }
        
        $params['area'] = '';
        $sysarea = CommonModel::getSysArea($params['area_county']);
        if($sysarea['code'] == "200") {
            $params['area'] = $sysarea['data'];
        }
        
        // 写入推荐信息
        $recoResult = self::addRecoSto($params);
        
        if($recoResult["code"] != "200") {
            return ["code" => $recoResult["code"], "data" => $recoResult["data"]];
        }
        
        // 添加用户、牛店
        $addUser = self::addCheckCustomer($recoResult['data'], $params['type']);
        if($addUser["code"] != "200") {
            return ["code" => $addUser["code"], "data" => $addUser['data']];
        }

        // 添加门店
        self::addStoStore($recoResult['data']);
        
        // 用户角色开启
        self::addRoleOpen($recoResult['data'], $params['type']);
        
        // 查看是否有标识来源字段
        $recoResult['data']['source'] = !empty($params['source']) ? $params['source'] : 1;
        // 建立角色关系
        self::addCusRelation($recoResult['data']);
        
        $stoUpdateData['status'] = 2;
        $stoUpdateData['examinetime'] = $recoResult['data']['addtime'];
        
        Model::ins("RoleRecoSto")->modify($stoUpdateData, array("id" => $recoResult['data']['id']));
        
        return ["code" => "200"];
    }
    
    /**
    * @user 添加牛掌柜推荐表数据
    * @param 
    * @author jeeluo
    * @date 2017年4月29日上午11:59:10
    */
    private function addRecoSto($params) {
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
        // $stoInfo = Model::ins("RoleRecoSto")->getRow(array("cus_role_id" => $cusRole['id'], "idnumber" => $params['idnumber']), "id,status", "id desc");
        // if(!empty($stoInfo)) {
        //     if($stoInfo['status'] != 3) {
        //         return ["code" => "20014", "data" => "该身份证号码已被推荐"];
        //     }
        // }

        if(!empty($params['idnumber'])) {
            $stoInfo = Model::ins("RoleRecoSto")->getRow(array("cus_role_id" => $cusRole['id'], "idnumber" => $params['idnumber']), "id,status", "id desc");
            if(!empty($stoInfo)) {
                if($stoInfo['status'] != 3) {
                    return ["code" => "20014", "data" => "该身份证号码已被推荐"];
                }
            }
        }
        
        /*
         * 高德api 获取经纬度信息
         */
        $areaname = CommonModel::getSysArea($params['area_county']);
        $map = CommonModel::getAddressLngLat($areaname['data'].$params['address']);
        
        $longitude = $map['data']['lngx'];
        $latitude = $map['data']['laty'];
        
        $service_type = "0,0,0";
        $temp_type = array("1" => 0, "2" => 0, "3" => 0);
        if(!empty($params['service_type'])) {
            if(in_array($params['service_type'])) {
                foreach ($params['service_type'] as $v) {
                    $temp_type[$v] = $v;
                }
                
                $service_type = implode(",", $temp_type);
            } else {
                $service_type = $params['service_type'];
            }
        }
        
//         $recoData = array("cus_role_id" => $cusRole['id'], "sto_type_id" => $params['sto_type_id'], "sto_name" => $params['sto_name'], "mobile" => $params['mobile'], "discount" => $params['discount'],
//             "sto_hour_begin" => $params['sto_begin'], "sto_hour_end" => $params['sto_end'], "service_type" => $service_type, "delivery" => !empty($params['delivery']) ? EnPrice($params['delivery']) : 0, 
//             "nearby_village" => $params['nearby_village'], "sto_mobile" => $params['sto_mobile'], "area" => $params['area'], "area_code" => $params['area_county'], "address" => $params['address'],
//             "longitude" => $longitude, "latitude" => $latitude, "metro_id" => $params['metro_id'], "bus_district" => $params['district_id'], "idnumber" => $params['idnumber'], "sto_image" => $params['sto_image'],
//             "licence_image" => $params['licence_image'], "idnumber_image" => $params['idnumber_image'], "description" => $params['description'], "addtime" => getFormatNow());

        $recoData = array("cus_role_id" => $cusRole['id'], "sto_type_id" => $params['sto_type_id'], "main_image" => $params['main_image'], "sto_name" => $params['sto_name'], "mobile" => $params['mobile'], "discount" => $params['discount'],
            "sto_hour_begin" => $params['sto_begin'], "sto_hour_end" => $params['sto_end'], "service_type" => $service_type, "delivery" => !empty($params['delivery']) ? EnPrice($params['delivery']) : 0, "dispatch" => !empty($params['dispatch']) ? EnPrice($params['dispatch']) : 0,
            "nearby_village" => $params['nearby_village'], "sto_mobile" => $params['sto_mobile'], "area" => $params['area'], "area_code" => $params['area_county'], "address" => $params['address'],
            "longitude" => $longitude, "latitude" => $latitude, "metro_id" => $params['metro_id'], "bus_district" => $params['district_id'], "idnumber" => $params['idnumber'], "sto_image" => $params['sto_image'],
            "licence_image" => $params['licence_image'], "idnumber_image" => $params['idnumber_image'], "album_image" => !empty($params['album_image']) ? $params['album_image'] : '', "description" => $params['description'], "addtime" => getFormatNow(),"orderno"=>$params['orderno'], 
            "businesscode"=>!empty($params['businesscode']) ? $params['businesscode'] : 0);
        
        // 写入推荐表
        $recoDataId = Model::ins("RoleRecoSto")->insert($recoData);
        if($recoDataId) {
            $recoData['id'] = $recoDataId;
            return ["code" => "200", "data" => $recoData];
        }
        return ["code" => "400", "data" => "推荐数据添加异常，请联系管理员"];
    }
    
    /**
    * @user 用户开启
    * @param 
    * @author jeeluo
    * @date 2017年5月6日下午3:03:51
    */
    private function addCheckCustomer($params, $type) {
        // 检查用户
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']));
        
        if(empty($cus)) {
            // 无此用户(添加用户表)
            $cusData['mobile'] = $cusData['username'] = $params['mobile'];
            $cusData['createtime'] = getFormatNow();
            $cus['id'] = Model::ins("CusCustomer")->insert($cusData);
            
            // 添加用户信息表(不执行修改操作，怕影响用户自己添加的原数据)
            $infoData = array("id" => $cus['id'], "nickname" => $params['sto_name'], "idnumber" => $params['idnumber'], "area" => $params['area'], "area_code" => $params['area_code'], "address" => $params['address']);
            Model::ins("CusCustomerInfo")->insert($infoData);
        }
        
        // 判断该用户是否已经有实体店了
        $stoInfo = Model::ins("StoBusiness")->getRow(array("customerid" => $cus['id']));
        
        if(empty($stoInfo)) {
            // 写入实体店数据
//             $sto_id = Model::ins("StoBusiness")->insert(array("businessname" => $params["sto_name"], "ischeck" => 1, "enable" => 1, "customerid" => $cus['id'], "addtime" => $params['addtime']));
            $sto_id = Model::ins("StoBusiness")->insert(array("businessname" => $params["sto_name"], "ischeck" => 1, "isvip" => $type, "enable" => 1, "customerid" => $cus['id'], "addtime" => $params['addtime']));
            
            $business_code = array();
            
            if(!empty($params['businesscode'])) {
                $business_code = array("code"=>"200", "business_code"=>$params['businesscode']);
            } else {
                $business_code = StobusinessModel::creatStoBusCode(["businessid"=>$sto_id]);
            }
            if($business_code["code"] != "200") {
                return ["code" => "400", "data" => "获取平台号失败"];
            }
//             $business_code = StobusinessModel::creatStoBusCode(array("businessid" => $sto_id));

            // 写入实体店基本信息
            Model::ins("StoBusinessBaseinfo")->insert(array("id" => $sto_id, "business_code" => $business_code['business_code'],"businessname" => $params["sto_name"], "idnumber" => $params['idnumber'], "mobile" => $params['mobile'], "address" => $params['address'],
                                    "servicetel" => $params['sto_mobile'], "description" => $params['description'], "typeid" => 1, "typename" => "实体", "discount" => $params['discount'], "delivery" => $params['dispatch']));
            
            $cateData = StobusinessModel::getCategoryName($params['sto_type_id']);
            
//             $stoInfoData = array("id" => $sto_id, "businessname" => $params['sto_name'], "categoryid" => $params['sto_type_id'], "categoryname" => !empty($cateData) ? $cateData['categoryname'] : '', "area" => $params['area'], "area_code" => $params['area_code'], "lngx" => $params['longitude'],
//                 "laty" => $params['latitude'], "metro_id" => $params['metro_id'], "district_id" => $params['bus_district'], "nearby_village" => $params['nearby_village'], "busstartime" => $params['sto_hour_begin'], "busendtime" => $params['sto_hour_end'],
//                 "licence_image" => $params['licence_image'], "idnumber_image" => $params['idnumber_image'], "isvip" => $type);

            $stoInfoData = array("id" => $sto_id, "businessname" => $params['sto_name'], "businesslogo" => !empty($params['main_image']) ? $params['main_image'] : '', "categoryid" => $params['sto_type_id'], "categoryname" => !empty($cateData) ? $cateData['categoryname'] : '', "area" => $params['area'], "area_code" => $params['area_code'], "lngx" => $params['longitude'],
                "laty" => $params['latitude'], "metro_id" => $params['metro_id'], "district_id" => $params['bus_district'], "nearby_village" => $params['nearby_village'], "busstartime" => $params['sto_hour_begin'], "busendtime" => $params['sto_hour_end'],
                "licence_image" => $params['licence_image'], "idnumber_image" => $params['idnumber_image'], "isvip" => $type, "actualfreight"=>$params['delivery']);
            
            if(!empty($params['service_type'])) {
                $service_arr = array_filter(explode(",", $params['service_type']));

                if($service_arr[0] == 1) {
                    $stoInfoData['iswifi'] = '1';
                }
            
                if($service_arr[1] == 1 || $service_arr[1] == 2) {
                    $stoInfoData['isparking'] = '1';
                }
            
                if($service_arr[2] == 1 || $service_arr[2] == 3) {
                    $stoInfoData['isdelivery'] = '1';
                }
                
//                 if(in_array('1', $service_arr)) {
//                     $stoInfoData['iswifi'] = '1';
//                 }
            
//                 if(in_array('2', $service_arr)) {
//                     $stoInfoData['isparking'] = '1';
//                 }
            
//                 if(in_array('3', $service_arr)) {
//                     $stoInfoData['isdelivery'] = '1';
//                 }
            }
            
            $stoInfoData['isshow'] = $type == 1 ? -1 : 1;
            
            // 判断平台号是否已经在 stoBusinessCode 表产生
            $stoBusinesCode = Model::ins("StoBusinessCode")->getRow(["business_code"=>$business_code['business_code']],"id,isuse");
            if(!empty($stoBusinesCode['isuse'])) {
                // 查看属性
                if($stoBusinesCode['isuse'] == 1) {
                    return ["code" => "400", "data" => "商家平台号已经被绑定了"];
                }
                // 进行数据修改
                Model::ins("StoBusinessCode")->modify(["business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$params['sto_name'],"customerid"=>$cus['id'],"usetime"=>$params['addtime']],["id"=>$stoBusinesCode['id']]);
            } else {
                // 添加数据
                $businessCodeData = array("business_code"=>$business_code['business_code'],"isuse"=>1,"businessid"=>$sto_id,"businessname"=>$params['sto_name'],"customerid"=>$cus['id'],"addtime"=>$params['addtime'],"usetime"=>$params['addtime']);
                
                Model::ins("StoBusinessCode")->insert($businessCodeData);
            }
            
            // 创建商家信息表数据
            $status = Model::ins("StoBusinessInfo")->insert($stoInfoData);
            
            if(!empty($params['sto_image'])) {
                $image_arr = array_filter(explode(",", $params['sto_image']));
            
                $imageData['business_id'] = $sto_id;
                $imageData['addtime'] = $params['addtime'];
                foreach ($image_arr as $image) {
                    $imageData['thumb'] = $image;
                    Model::ins("StoBusinessImage")->insert($imageData);
                }
            }
            
            // 判断是否有传递相册图片
            if(!empty($params['album_image'])) {
                $album_arr = array_filter(explode(",", $params['album_image']));
                
                $albumData['business_id'] = $sto_id;
                $albumData['addtime'] = $params['addtime'];
                foreach ($album_arr as $album) {
                    $albumData['thumb'] = $album;
                    Model::ins("StoBusinessAlbum")->insert($albumData);
                }
            }
            
            // 冗余表数据
            $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $params['cus_role_id']));
            $recoCount = Model::ins("CusRecoCount")->getRow(["customerid"=>$recoParentInfo['customerid'],"role"=>$recoParentInfo['role']],"id,reco_sto_count");
            
            if(empty($recoCount)) {
                $parent = Model::new("User.Relation")->getAllRelation($recoParentInfo['customerid'],$recoParentInfo['role']);
                Model::ins("CusRecoCount")->insert(array("customerid"=>$recoParentInfo['customerid'],"role"=>$recoParentInfo['role'],"parentid"=>$parent['parentid'],"parentrole"=>!empty($parent['parentrole']) ? $parent['parentrole'] : -1,"reco_sto_count"=>1));
            } else {
                Model::ins("CusRecoCount")->modify(["reco_sto_count"=>$recoCount['reco_sto_count']+1],["id"=>$recoCount['id']]);
            }

            if($status) {
                //写入mongoDB
                $StoBusinessInfoMG = Model::Mongo('StoBusinessInfo');
                
                $busInfoMGData = Model::ins("StoBusinessInfo")->getRow(['id'=>$sto_id],'*');
                $busInfoMGData['businessid'] = $sto_id;
                
                $reutnproportion = StobusinessModel::getBusReturnBull(['businessid'=>$sto_id]);
             
                $business_code = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$busInfoMGData['id']],'business_code');
                $busInfoMGData['business_code'] = $business_code['business_code'];

                if(empty($busInfoMGData['businesslogo']))
//                     $busInfoMGData['businesslogo'] = $image_arr[0];
                        $busInfoMGData['businesslogo'] = !empty($params['main_image']) ? $params['main_image'] : $image_arr[0];
                
                $busInfoMGData['loc'] =  [
                    'type' => 'Point',
                    'coordinates' => [
                        doubleval($busInfoMGData['lngx']),//经度
                        doubleval($busInfoMGData['laty'])//纬度
                    ]
                ];
                
                $int_arr = [
                    'reutnproportion' => (int) $reutnproportion,
                    'salecount' =>       (int) $busInfoMGData['salecount'],
                    'scores' =>         (int) $busInfoMGData['scores'],
                    'busstartime' =>     (int) $busInfoMGData['busstartime'],
                    'busendtime' =>      (int) $busInfoMGData['busendtime'],
                    'area_code' =>       (int) $busInfoMGData['area_code'],
                    //'enable' => (int) 1
                ];

                $StoBusinessInfoMG->delete(['id'=>$sto_id]);
                
                $StoBusinessInfoMG->insert($busInfoMGData,$int_arr);
                
                //提交门店到蜂鸟审核
                $Baseinfo = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$busInfoMGData['id']],'mobile,address');
                $chan_arr = [
                    'businessname' => $busInfoMGData['businessname'],
                    'mobile'       => $Baseinfo['mobile'],
                    'address'      => $busInfoMGData['area'].$Baseinfo['address'],
                    'lngx'         => $busInfoMGData['lngx'],
                    'laty'         => $busInfoMGData['laty']
                ];
                self::chainstore($chan_arr);

                return ["code" => "200"];
            }
        }
        return ["code" => "400", "data" => "数据添加异常，请联系管理员"];
    }
    
    /**
    * @user 添加实体店数据
    * @param 
    * @author jeeluo
    * @date 2017年6月30日下午7:46:23
    */
    private function addStoStore($params) {
        // 判断手机号码是否正规的
        if(phone_filter($params['mobile'])) {
            // 不正规，说明有进行_处理过(进行门店归属)
            PhysicalShopModel::autoAddStore($params);
        }
    }
    
    /**
    * @user 角色开启
    * @param 
    * @author jeeluo
    * @date 2017年5月6日下午3:08:33
    */
    private function addRoleOpen($params, $type) {
        // 角色开启
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
        $data['customerid'] = $cus['id'];
        $data['area'] = $params['area'];
        $data['address'] = $params['address'];
        $data['area_code'] = $params['area_code'];
        $data['addtime'] = $params['addtime'];
        $data['enable'] = 1;
        
        // 判断牛粉是否存在
        $cusRoleFans = Model::ins("CusRole")->getRow(array("customerid" => $cus['id'], "role" => self::defaultRole));
        
        if(empty($cusRoleFans)) {
            $data['role'] = self::defaultRole;
            Model::ins("CusRole")->insert($data);
        }
        
        // 添加当前角色
        $data['role'] = self::stoRole;
        $data['grade'] = $type != -1 ? 1 : 0;
        Model::ins("CusRole")->insert($data);
        
        return ["code" => "200"];
    }
    
    /**
    * @user 添加实体店时 添加实体店关联关系
    * @param 
    * @author jeeluo
    * @date 2017年7月3日下午2:19:54
    */
    private function addCusRelation($params) {
        $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $params['mobile']), "id");
        
        $recoParentInfo = CommonRoleModel::getCusRole(array("cus_role_id" => $params['cus_role_id']));
        // 牛掌柜
        $recoStoParentCount = Model::ins("CusRelation")->getRow(array("parentid" => $recoParentInfo['customerid'], "role" => self::stoRole, "parentrole" => $recoParentInfo['role']), "COUNT(id) as num ");
        $parentCusRela = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => $recoParentInfo['role']), "parentid, parentrole");
        
        $parentid = $recoParentInfo['customerid'];
        $parentrole = self::company_cus;
        if($recoParentInfo['role'] == self::orRole) {
            if($recoStoParentCount['num'] < CommonRoleModel::orRecoMaxSto()) {
                $parentrole = self::orRole;
                Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::orRole, "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela) ? $parentCusRela['parentrole'] : self::company_cus, "addtime" => $params['addtime']));
            } else {
                // 推荐的这个牛商 归属公司
                Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::stoRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => $params['addtime']));
                $parentid = self::company_cus;
            }
        } else if($recoParentInfo['role'] == self::ndRole) {
            if($recoStoParentCount['num'] < CommonRoleModel::ndRecoMaxSto()) {
                $parentrole = self::ndRole;
                Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::ndRole, "grandpaid" => !empty($parentCusRela) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela) ? $parentCusRela['parentrole'] : self::company_cus, "addtime" => $params['addtime']));
            } else {
                // 推荐的这个牛店 归属公司
                Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::stoRole, "parentid" => self::company_cus, "parentrole" => self::company_cus, "grandpaid" => self::company_cus, "grandparole" => self::company_cus, "addtime" => $params['addtime']));
                $parentid = self::company_cus;
            }
        } else {
            $parentrole = $recoParentInfo['role'];
            Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::stoRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => $recoParentInfo['role'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "grandparole" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentrole'] : self::company_cus, "addtime" => $params['addtime']));
        }

        Model::new("User.Role")->stoLevelRole(["id"=>$params['id']]);
        
        // 写入牛粉
        $nfRelationInfo = Model::ins("CusRelationNf")->getRow(array("customerid" => $cus['id']), "id");
        if(empty($nfRelationInfo)) {
            // 写入牛粉表
            Model::ins("CusRelationNf")->insert(array("customerid" => $cus['id'], "parentid" => $recoParentInfo['customerid'], "grandpaid" => !empty($parentCusRela['parentid']) ? $parentCusRela['parentid'] : self::company_cus, "addtime" => $params['addtime']));
        }
        
        $recoParentFans = Model::ins("CusRelation")->getRow(array("customerid" => $recoParentInfo['customerid'], "role" => self::defaultRole, "parentrole" => self::defaultRole), "parentid");
        $recoFans = Model::ins("CusRelation")->getRow(array("customerid"=>$cus["id"], "role"=>self::defaultRole),"parentid");
        if(empty($recoFans)) {
            // 写入牛粉表
            Model::ins("CusRelation")->insert(array("customerid" => $cus['id'], "role" => self::defaultRole, "parentid" => $recoParentInfo['customerid'], "parentrole" => self::defaultRole, "grandpaid" => !empty($recoParentFans['parentid']) ? $recoParentFans['parentid'] : self::company_cus, "grandparole" => !empty($recoParentFans['parentid']) ? self::defaultRole : self::company_cus, "addtime" => getFormatNow()));
        
            if($params['source'] == self::apisource) {
                // 由api产生的 牛粉关系  产生分润
                Model::new("Amount.Role")->share_role(["userid"=>$recoParentInfo['customerid'],"customerid"=>$cus['id']]);
            }
        }
        
        CommonRoleModel::roleRelationRole(array("userid" => $recoParentInfo['customerid'], "roleid" => 1, "customerid" => $cus['id']));
        
        if($parentid > 0) {
            // 确保不归属公司
            // 查询推荐人用此角色 是否是赠送的角色(成为表和推荐表都无数据)
            // if(CommonRoleModel::getUserRoleGive(["customerid"=>$parentid,"role"=>$parentrole])) {
            
                Model::new("Amount.Role")->share_sto(["userid"=>$parentid,"usertype"=>$parentrole,"orderno"=>$params['orderno'],"customerid"=>$cus['id']]);
            // }
        }
    }
    
    public function examCheck($params) {
        if($params['ischeck'] == 1) {
            // 上架(识别商家是否提交了审核信息)
            $stoBusiness = Model::ins("StoBusiness")->getRow(["id"=>$params['id']],"id,customerid");
            $stoInfoExam = Model::ins("StoBusinessInfoexam")->getRow(["customerid"=>$stoBusiness['customerid'],"status"=>1],"*","id desc");
            $time = getFormatNow();
            if(!empty($stoInfoExam)) {
                // 审核通过，上架  同步数据
                $infoData['categoryid'] = $stoInfoExam['sto_type_id'];
                $cateData = StobusinessModel::getCategoryName($stoInfoExam['sto_type_id']);
                $infoData['categoryname'] = $cateData['categoryname'];
                
                $infoData['businessname'] = $stoInfoExam['sto_name'];
                $infoData['businesslogo'] = $stoInfoExam['main_image'];
                $infoData['nearby_village'] = $stoInfoExam['nearby_village'];
                $infoData['busstartime'] = $stoInfoExam['sto_hour_begin'];
                $infoData['busendtime'] = $stoInfoExam['sto_hour_end'];
                $infoData['actualfreight'] = $stoInfoExam['delivery'];
                
                if(!empty($stoInfoExam['metro_id'])) {
                    $infoData['metro_id'] = $stoInfoExam['metro_id'];
                }
                
                if(!empty($stoInfoExam['district_id'])) {
                    $infoData['district_id'] = $stoInfoExam['district_id'];
                }
                
                if(!empty($stoInfoExam['service_type'])) {
                    $service_arr = array_filter(explode(",", $stoInfoExam['service_type']));
                    if($service_arr[0] == 1) {
                        $infoData['iswifi'] = '1';
                    }
                
                    if($service_arr[1] == 1 || $service_arr[1] == 2) {
                        $infoData['isparking'] = '1';
                    }
                
                    if($service_arr[2] == 1 || $service_arr[2] == 3) {
                        $infoData['isdelivery'] = '1';
                    }
                    // if(in_array('1', $service_arr)) {
                    //     $infoData['iswifi'] = '1';
                    // }
                
                    // if(in_array('2', $service_arr)) {
                    //     $infoData['isparking'] = '1';
                    // }
                
                    // if(in_array('3', $service_arr)) {
                    //     $infoData['isdelivery'] = '1';
                    // }
                }
                $infoData['isshow'] = 1;
                $infoData['area'] = $stoInfoExam['area'];
                $infoData['area_code'] = $stoInfoExam['area_code'];
                
                
                $baseInfoData['businessname'] = $stoInfoExam['sto_name'];
                $baseInfoData['mobile'] = $stoInfoExam['mobile'];
                $baseInfoData['servicetel'] = $stoInfoExam['sto_mobile'];
                $baseInfoData['description'] = $stoInfoExam['description'];
                $baseInfoData['discount'] = $stoInfoExam['discount'];
                $baseInfoData['delivery'] = $stoInfoExam['dispatch'];
                $baseInfoData['address'] = $stoInfoExam['address'];
                
                if(!empty($stoInfoExam['sto_image'])) {
                    PhysicalShopModel::addStoImages(["businessid"=>$stoBusiness['id'],"thumb"=>$stoInfoExam['sto_image']]);
//                     $image_arr = array_filter(explode(",", $stoInfoExam['sto_image']));
                
//                     $imageData['business_id'] = $stoBusiness['id'];
//                     $imageData['addtime'] = $time;
//                     foreach ($image_arr as $image) {
//                         $imageData['thumb'] = $image;
//                         Model::ins("StoBusinessImage")->insert($imageData);
//                     }
                }
                
                // 判断是否有传递相册图片
                if(!empty($stoInfoExam['album_image'])) {
                    PhysicalShopModel::addStoAlbum(["businessid"=>$stoBusiness['id'],"thumb"=>$stoInfoExam['album_image']]);
//                     $album_arr = array_filter(explode(",", $stoInfoExam['album_image']));
                
//                     $albumData['business_id'] = $stoBusiness['id'];
//                     $albumData['addtime'] = $time;
//                     foreach ($album_arr as $album) {
//                         $albumData['thumb'] = $album;
//                         Model::ins("StoBusinessAlbum")->insert($albumData);
//                     }
                }
                
                $stoBusinessModel = Model::ins("StoBusiness");
                $stoBusinessModel->startTrans();
                
                try {
                    Model::ins("StoBusinessInfo")->modify($infoData,["id"=>$stoBusiness['id']]);
                    Model::ins("StoBusinessBaseinfo")->modify($baseInfoData,["id"=>$stoBusiness['id']]);
                
                    Model::ins("StoBusinessInfoexam")->modify(["status"=>2,"examinetime"=>$time],["id"=>$stoInfoExam['id']]);
                    Model::ins("StoBusiness")->modify(["ischeck"=>1,"businessname"=>$stoInfoExam['sto_name']],["id"=>$stoBusiness['id']]);
                
                    $stoBusinessModel->commit();
                } catch (\Exception $e) {
                    $stoBusinessModel->rollback();
                    Log::add($e,__METHOD__);
                    return ["code"=>"400","data"=>"操作数据异常，请联系管理员"];
                }
            } else {
                $stoInfoExam = Model::ins("StoBusinessInfoexam")->getRow(["customerid"=>$stoBusiness['customerid']],"id","id desc");
                // 直接上架
                Model::ins("StoBusiness")->modify(["ischeck"=>$params['ischeck']],["id"=>$stoBusiness['id']]);
                Model::ins("StoBusinessInfo")->modify(["isshow"=>1],["id"=>$stoBusiness['id']]);
                Model::ins("StoBusinessInfoexam")->modify(["status"=>2,"examinetime"=>$time],["id"=>$stoInfoExam['id']]);
            }
        } else {
            return ["code"=>"400","data"=>"操作有误"];
        }
        return ["code"=>"200"];
    }

    /**
     * [chainstore 实体店提交门店审核]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-20T14:31:48+0800
     * @param    [type]                   $params [description]
     * @return   [type]                           [description]
     */
    public static function chainstore($params){
        $name = $params['businessname'];
        $mobile = $params['mobile'];
        $address = $params['address'];
        $lngx = $params['lngx'];
        $laty = $params['laty'];
        $ThirdpartyApi = new ThirdpartyApi();

        $result = $ThirdpartyApi->chain_store([
            "name"=>$name,
            "contactPhone"=>$mobile,
            "address"=>$address,
            "longitude"=>$lngx,
            "latitude"=>$laty,
        ]);

        //print_r($result);
        return $result;
    }
}