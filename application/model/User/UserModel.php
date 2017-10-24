<?php
namespace app\model\User;
use think\Cookie;
use think\Config;
use app\lib\Model;
use app\lib\Log;


use app\lib\ApiService\Bank;
use app\lib\QRcode;

class UserModel
{
    protected $mobileDomain;
    protected $domain;

    public function __construct(){

        $this->domain = Config::get("stobusiness.domain");
        $this->mobileDomain = Config::get("stobusiness.mobiledomain");
    }
    
    /**
    * @user 登录 注册
    * @param mobile 手机号码
    * @param sendType 短信类型前缀
    * @param redirectUri 回跳地址
    * @param valicode 加密验证码值
    * @author jeeluo
    * @date 2017年10月10日下午4:14:30
    */
    public function login($param) {
        if($param['mobile'] == '') {
            return ["code" => "404"];
        }
        
        if(phone_filter($param['mobile'])) {
            return ["code" => "404"];
        }
        if(empty($param['valicode']))
            return ['code'=>404];
       

        return ["code" => 200,"data" => []];
        
    }
    
    /**
     * 添加用户接口
     * @Author   zhuangqm
     * @Datetime 2017-10-07T16:17:21+0800
     * @param    [type]                   $param [
     *                                           mobile
     *                                           role
     *                                         //  businessid 商家ID
     *                                         recommendrole 推荐人角色值
     *                                           recommendid 推荐人ID
     *                                          openid 授权登录id  非必填
     *                                           
     *                                           
     * ]
     */
    public function addUser($param){
      
        if($param['role'] != 4){
            if($param['mobile']=='' || $param['recommendid'] == '' || $param['recommendrole'] == '') {
                return ["code"=>"404"];
            }
        }else{
            if($param['mobile']=='' ) {
                return ["code"=>"404"];
            }
        }
        if(phone_filter($param['mobile'])) {
            return ["code" => "20006"];
        }
        
        // 查看手机号码是否已经被注册
        // $checkCus = Model::ins("CusCustomer")->getRow(["mobile"=>$param['mobile']],"id");
        // if(!empty($checkCus['id'])) {
        //     return ["code" => "10000"];
        // }

        $param['role'] = !empty($param['role'])?$param['role']:1; // 默认用户角色

        $CusCustomer = Model::ins("CusCustomer");

        // 查询是否注册了
        $customerRecord = $CusCustomer->getRow([
            "mobile" => $param['mobile'],
        ],"id");
        
        if(empty($customerRecord['id'])) {
            // 注册用户
            $customerid = $CusCustomer->insert([
                    "mobile"=>$param['mobile'],
                    "username"=>$param['mobile'],
                    "createtime"=>getFormatNow(),
                    "enable"=>1,
                    "role"=>$param['role'],
                ]);

            // 绑定openid
            $bindResult = Model::new("User.Open")->bindOpenid(["customerid"=>$customerid,"openid"=>$param["openid"]]);

            $openInfo = array();
            if($bindResult["code"] == "200") {
                $openInfo = $bindResult["data"];
            }

            // 写入用户表信息
            $CusCustomerInfo = Model::ins("CusCustomerInfo");
            $customerInfo = $CusCustomerInfo->insert([
                "id" => $customerid,
                // "nickname" => $param['mobile']
                "nickname" => !empty($openInfo["nickname"]) ? $openInfo["nickname"] : $param["mobile"],
                "sex" => !empty($openInfo["sex"]) ? $openInfo["sex"] : 0,
                "headerpic" => !empty($openInfo["headimgurl"]) ? $openInfo["headimgurl"] : ''
            ]);
            
            // 写入用户表信息
            // $CusCustomerInfo = Model::ins("CusCustomerInfo");
            // $customerInfo = $CusCustomerInfo->insert([
            //     "id" => $customerid,
            //     "nickname" => $param['mobile']
            // ]);
        } else {
            // 更新用户角色值
            $CusCustomer->update(["role"=>$param['role']],["id"=>$customerRecord['id']]);
            $customerid = $customerRecord['id'];
        }

        // 查询用户关系
        $customer_relation = Model::ins("CusRelation")->getRow([
            "customerid" => $customerid
        ],"id");
        
        if(empty($customer_relation['id'])) {
            // 成为消费者
            if($param['role']==1){
                $this->addConsumptionRelation([
                        "customerid"=>$customerid,
                        "recommendrole"=>$param['recommendrole'],
                        "recommendid"=>$param['recommendid']
                    ]);
            }
            
            // 成为商家
            if($param['role']==2){
                $this->addBusinessRelation([
                    "customerid"=>$customerid,
                    "recommendid"=>$param['recommendid'],
                    "recommendrole"=>$param['recommendrole']
                ]);
            }
            
            // 成为经理
            if($param['role']==3){
                $this->addDirectorRelation([
                    "customerid"=>$customerid,
                    "recommendid"=>$param['recommendid'],
                    "recommendrole"=>$param['recommendrole']
                ]);
            }
            
            // 成为总监
            if($param['role']==4){
                // 处理无归属关系
                $this->addChiefRelation([
                    "customerid"=>$customerid,
                    "recommendid"=>$param['recommendid']
                ]);
            }
        
            // 写入用户关系表
            $CusRelationList = Model::ins("CusRelationList");
            $parentRelationList = array();
            // 查看父类数据
            $parentRelation = $CusRelationList->getRow([
                    "customerid" => $param['recommendid'],
                    "role" => !empty($param['recommendrole']) ? $param['recommendrole'] : -1
                ],"id,parentid,parentrole");
            
            if(!empty($parentRelation['id'])) {
                // 去除parentid 为0
                if($parentRelation['parentid'] != 0) {
                    $parentRelationList = $CusRelationList->getList([
                        "customerid" => $param['recommendid'],
                        "role" => !empty($param['recommendrole']) ? $param['recommendrole'] : -1
                    ],"id,parentid,parentrole","id asc");
                }
            }
            
            if(!empty($parentRelationList)) {
                foreach ($parentRelationList as $k => $v) {
                    $CusRelationList->insert([
                        "customerid" => $customerid,
                        "role" => $param['role'],
                        "parentrole" => $v['parentrole'],
                        "parentid" => $v['parentid'],
                        "addtime" => getFormatNow(),
                        "parentotherrole" => 1
                    ]);
                }
            } 
            
            $CusRelationList->insert([
                "customerid" => $customerid,
                "role" => $param['role'],
                "parentrole" => !empty($param['recommendrole']) ? $param['recommendrole'] : -1,
                "parentid" => $param['recommendid'] != -1 ? $param['recommendid'] : 0,
                "addtime" => getFormatNow(),
                "parentotherrole" => 1
            ]);
        } else {
            // 修改用户关系表中的用户角色值
            
            $this->updateRelationRole(["customerid"=>$customerid,"role"=>$param['role']]);
        }

        return ["code"=>"200", "data"=>["customerid"=>$customerid]];
    }

    
    /**
    * @user 添加用户基础信息(不产生关联关系)
    * @param $mobile 用户的手机号码
    * @param $role 用户的角色值 默认为1
    * @param $openid 授权登录的id 值  非必传
    * @author jeeluo
    * @date 2017年10月18日上午10:15:50
    */
    public function addBasicUser($param) {
        if($param['mobile']=='') {
            return ["code"=>"404"];
        }
        
        if(phone_filter($param['mobile'])) {
            return ["code" => "20006"];
        }

        // 查看手机号码是否已经被注册
        $checkCus = Model::ins("CusCustomer")->getRow(["mobile"=>$param['mobile']],"id");
        if(!empty($checkCus['id'])) {
            return ["code" => "10000"];
        }

        $param['role'] = !empty($param['role'])?$param['role']:1; // 默认用户角色

        $CusCustomer = Model::ins("CusCustomer");

        // 注册用户
        $customerid = $CusCustomer->insert([
                "mobile"=>$param['mobile'],
                "username"=>$param['mobile'],
                "createtime"=>getFormatNow(),
                "enable"=>0,
                "role"=>$param['role'],
            ]);

        // 绑定openid
        $bindResult = Model::new("User.Open")->bindOpenid(["customerid"=>$customerid,"openid"=>$param["openid"]]);

        $openInfo = array();
        if($bindResult["code"] == "200") {
            $openInfo = $bindResult["data"];
        }

        // 写入用户表信息
        $CusCustomerInfo = Model::ins("CusCustomerInfo");
        $customerInfo = $CusCustomerInfo->insert([
            "id" => $customerid,
            // "nickname" => $param['mobile']
            "nickname" => !empty($openInfo["nickname"]) ? $openInfo["nickname"] : $param["mobile"],
            "sex" => !empty($openInfo["sex"]) ? $openInfo["sex"] : 0,
            "headerpic" => !empty($openInfo["headimgurl"]) ? $openInfo["headimgurl"] : ''
        ]);

        return ["code"=>"200", "data"=>["customerid"=>$customerid]];
    }

    /**
     * 添加消费者
     * @Author   zhuangqm
     * @Datetime 2017-10-07T16:49:44+0800
     */
    public function addConsumptionRelation($param){

        $customerid = $param['customerid'];

        // 绑定推荐关系
        $CusRelation = Model::ins("CusRelation");

        // new version
        if(!empty($param['recommendid'])) {
            
            $recommend_relation = $CusRelation->getRow([
                    "customerid" => $param['recommendid'],
                    "role" => $param['recommendrole']
                ],"*");

            // 写入关联关系数据
            $CusRelation->insert([
                    "customerid" => $customerid,
                    "role" => 1,
                    "parentrole" => $param['recommendrole'],
                    "parentid" => $param['recommendid'],
                    "grandparole" => $recommend_relation['parentrole'],
                    "grandpaid" => !empty($recommend_relation['parentid']) ? $recommend_relation['parentid'] : -1,
                    "ggrandparole" => $recommend_relation['grandparole'],
                    "ggrandpaid" => !empty($recommend_relation['grandpaid']) ? $recommend_relation['grandpaid'] : -1,
                    "addtime" => getFormatNow(),
                ]);
        }

        // 推荐人有两种角色，商家和消费者
        
        // 推荐人：商家
        // if(!empty($param['businessid'])){

        //     $CusRelation->insert([
        //             "customerid"=>$customerid,
        //             "role"=>1,
        //             "parentrole"=>1,
        //             "parentid"=>-1,
        //             "grandparole"=>1,
        //             "grandpaid"=>-1,
        //             "addtime"=>getFormatNow(),
        //         ]);

        //     $CusRelation->insert([
        //             "customerid"=>$customerid,
        //             "role"=>1,
        //             "parentrole"=>2,
        //             "parentid"=>$param['businessid'],
        //             "addtime"=>getFormatNow(),
        //         ]);            

        // }

        // // 推荐人：消费者
        // if(!empty($param['recommendid'])){
            
        //     $recommend_relation = $CusRelation->getRow([
        //             "customerid"=>$param['recommendid'],
        //             "role"=>1,
        //             "parentrole"=>1,
        //         ],"*");

        //     $CusRelation->insert([
        //             "customerid"=>$customerid,
        //             "role"=>1,
        //             "parentrole"=>1,
        //             "parentid"=>$param['recommendid'],
        //             "grandparole"=>1,
        //             "grandpaid"=>!empty($recommend_relation)?$recommend_relation['parentid']:-1,
        //             "addtime"=>getFormatNow(),
        //         ]);

        //     // 查询商家
        //     $recommend_relation = $CusRelation->getRow([
        //             "customerid"=>$param['recommendid'],
        //             "role"=>1,
        //             "parentrole"=>2,
        //         ],"*");
            

        //     // 添加所属商家关系
        //     $CusRelation->insert([
        //             "customerid"=>$customerid,
        //             "role"=>1,
        //             "parentrole"=>2,
        //             "parentid"=>$recommend_relation['parentid'],
        //             "addtime"=>getFormatNow(),
        //         ]);   
//         }  
    }
    
    /**
    * @user 添加商家所属经理关系
    * @param $customerid 商家用户id
    * @param $businessid 经理用户id
    * @author jeeluo
    * @date 2017年10月10日下午6:24:52
    */
    public function addBusinessRelation($param) {
        $customerid = $param['customerid'];
        
        // 绑定推荐关系
        $CusRelation = Model::ins("CusRelation");
        
//         // 推荐人 上级角色
//         if(!empty($param['businessid'])) {
            
//             // 添加所属经理关系
//             $CusRelation->insert([
//                 "customerid"=>$customerid,
//                 "role"=>2,
//                 "parentrole"=>3,
//                 "parentid"=>$param['businessid'],
//                 "addtime"=>getFormatNow()
//             ]);
//         }

        if(!empty($param['recommendid'])) {
            $recommend_relation = $CusRelation->getRow([
                "customerid" => $param['recommendid'],
                "role" => $param['recommendrole']
            ],"*");
            // 添加所属关系
            $CusRelation->insert([
                "customerid"=>$customerid,
                "role"=>2,
                "parentrole"=>$param['recommendrole'],
                "parentid"=>$param['recommendid'],
                "grandparole"=>$recommend_relation['parentrole'],
                "grandpaid"=>!empty($recommend_relation['parentid']) ? $recommend_relation['parentid'] : -1,
                "ggrandparole"=>$recommend_relation['grandparole'],
                "ggrandpaid"=>!empty($recommend_relation['grandpaid']) ? $recommend_relation['grandpaid'] : -1,
                "addtime"=>getFormatNow()
            ]);
        }
    }
    
    /**
    * @user 添加经理所属总监关系
    * @param $customerid 经理用户id
    * @param $businessid 总监用户id
    * @author jeeluo
    * @date 2017年10月10日下午7:01:37
    */
    public function addDirectorRelation($param) {
        $customerid = $param['customerid'];
        
        // 绑定推荐关系
        $CusRelation = Model::ins("CusRelation");
        
        // 推荐人 上级角色
        if(!empty($param['recommendid'])) {
            $recommend_relation = $CusRelation->getRow([
                "customerid" => $param['recommendid'],
                "role" => $param['recommendrole']
            ],"*");
            // 添加所属总监关系
            $CusRelation->insert([
                "customerid"=>$customerid,
                "role"=>3,
                "parentrole"=>$param['recommendrole'],
                "parentid"=>$param['recommendid'],
                "grandparole"=>$recommend_relation['parentrole'],
                "grandpaid"=>!empty($recommend_relation['parentid']) ? $recommend_relation['parentid'] : -1,
                "ggrandparole"=>$recommend_relation['grandparole'],
                "ggrandpaid"=>!empty($recommend_relation['grandpaid']) ? $recommend_relation['grandpaid'] : -1,
                "addtime"=>getFormatNow()
            ]);
        }
    }
    
    /**
    * @user 添加总监推荐关系
    * @param 
    * @author jeeluo
    * @date 2017年10月18日上午11:44:47
    */
    public function addChiefRelation($param) {
        $customerid = $param['customerid'];
        
        // 绑定推荐关系
        $CusRelation = Model::ins("CusRelation");
        // 推荐人 上级角色
        if(!empty($param['recommendid'])) {
            // 添加所属总监关系
            $CusRelation->insert([
                "customerid"=>$customerid,
                "role"=>4,
                "parentrole"=>1,
                "parentid"=>-1,
                "grandparole"=>1,
                "grandpaid"=>-1,
                "ggrandparole"=>1,
                "ggrandpaid"=>-1,
                "addtime"=>getFormatNow()
            ]);
        }
    }
    
    /**
    * @user 修改关联关系
    * @param $customerid 用户id值
    * @param $role 用户角色值
    * @author jeeluo
    * @date 2017年10月18日下午2:51:20
    */
    public function updateRelationRole($param) {

        $CusCustomer = Model::ins("CusCustomer");
//         // 查询是否注册了
//         $customerRecord = $CusCustomer->getRow([
//             "mobile" => $param['mobile'],
//         ],"id");

        $oldRole = 1;
        if(empty($param['customerid'])) {
            return ["code" => "404"];
        } else {
            $oldRole = $this->getUserRoleID(["customerid"=>$param['customerid']]);
            $CusCustomer->update(["role"=>$param['role']],["id"=>$param['customerid']]);
        }
        
//         if(empty($customerRecord['id'])) {
//             return ["code" => "10001"];
//         } else {
//             // 更新用户角色值
//             $CusCustomer->update(["role"=>$param['role']],["id"=>$customerRecord['id']]);
//         }
        $CusRelation = Model::ins("CusRelation");
        
        $CusRelationList = Model::ins("CusRelationList");
        
        $CusRelation->update(["role"=>$param['role']],["customerid"=>$param['customerid']]);
        $CusRelation->update(["parentrole"=>$param['role']],["parentid"=>$param['customerid']]);
        $CusRelation->update(["grandparole"=>$param['role']],["grandpaid"=>$param['customerid']]);
        $CusRelation->update(["ggrandparole"=>$param['role']],["ggrandpaid"=>$param['customerid']]);
        
        $CusRelationList->update(["role"=>$param['role']],["customerid"=>$param['customerid']]);
        $CusRelationList->update(["parentrole"=>$param['role'],"parentotherrole"=>$oldRole],["parentid"=>$param['customerid']]);
        
        return ["code"=>"200"];
    }
    
    /**
    * @user 操作用户登录
    * @param 
    * @author jeeluo
    * @date 2017年10月18日下午3:07:35
    */
    public function cusLogin($param) {
        // 登录写入记录表
        $CusCustomerLogin = Model::ins("CusCustomerLogin");
        
        $customerid = $param['customerid'];
        
        $status = $CusCustomerLogin->insert([
            "customerid" => $customerid,
            "logintime" => getFormatNow(),
            "login_type" => "mobile",
            "login_account" => $param['mobile']
        ]);
        
        if($status) {
            // 更新用户信息表部分数据
            $CusCustomerInfo = Model::ins("CusCustomerInfo");
            $customerInfo = $CusCustomerInfo->getRow([
                "id" => $customerid
            ], "loginnum");
        
            $CusCustomerInfo->update([
                "lastlogintime" => getFormatNow(),
                "loginnum" => $customerInfo['loginnum']+1
            ],["id"=>$customerid]);
        }
        
        // 查询用户角色
        $role = $this->getUserRoleID(["customerid"=>$customerid]);
        
        // 登录成功，设置cookie
        Cookie::set('customerid',$customerid,3600*24*300);
        Cookie::set('mtoken',md5($customerid.getConfigKey()),3600*24*300);
//         Cookie::set('userrole',$role,3600*24*300);
        
        $url = '';
        if(!empty($param['redirectUri'])) {
            $url = $param['redirectUri'];
        } else {
            $url = "/user/index/index";
        }
        
        return ["code"=>"200","data" => urldecode($url),"msg" => "操作成功"];
    }
    
    /**
    * @user 登录用户
    * @param login_type 登录类型
    * @param login_account 登录帐号
    * @author jeeluo
    * @date 2017年10月10日上午9:53:18
    */
    public function loginUser($param) {
        if($param['login_type'] == '' || $param['login_account'] == '') {
            return ["code" => "404"];
        }
        
        // 根据type 类型 查询不同用户信息记录表
        $customerid = 0;
        if($param['login_type'] == "mobile") {
            $CusCustomer = Model::ins("CusCustomer");
            
            $customer = $CusCustomer->getRow([
                "mobile" => $param['login_account']
            ], "id,role,enable");
            if($customer['enable'] == 2) {
                return ["code" => "10002"];
            }
            $customerid = !empty($customer['id']) ? $customer['id'] : 0;
            
        } else if($param['login_type'] == "weixin") {
//             $CusCustomerOpen = Model::ins("CusCustomerOpen");
            
//             $customer = $CusCustomerOpen->getRow([
//                 "openid" => $param['login_account']
//             ], "customerid");
//             $customerid = !empty($customer['customerid']) ? $customer['customerid'] : 0;
            
        } else {
            return ["code" => "400"];
        }
        
        if($customerid == 0) {
            // 无效用户
            return ["code" => "1000"];
        }

        // 绑定openid
        $bindResult = Model::new("User.Open")->bindOpenid(["openid"=>$param['openid'],"customerid"=>$customerid]);
        
        return ["code" => "200", "data" => ["customerid"=>$customerid]];
    }
    
    /**
    * @user 绑定用户操作
    * @param 
    * @author jeeluo
    * @date 2017年10月18日下午3:15:11
    */
    public function bindUser($param) {
        if($param['customerid'] == '' || $param['recommendMobile'] == '') {
            return ["code" => "404"];
        }
        
        // 识别手机规格
        if(phone_filter($param['recommendMobile'])) {
            return ["code" => "20006"];
        }
        
        // 查询手机是否存在
        $CusCustomer = Model::ins("CusCustomer");
        $recommend = $CusCustomer->getRow([
            "mobile" => $param['recommendMobile'],
        ],"id,enable,role");
        
        if(empty($recommend['id'])) {
            return ["code" => "10001"];
        }
        
        if($recommend['enable'] == 2) {
            return ["code" => "10002"];
        }
        
        if($recommend['enable'] == 0) {
            return ["code" => "10003"];
        }

        $customer = $CusCustomer->getRow([
                "id" => $param["customerid"]
            ],"mobile");
        
        // 执行事务处理
        $CusCustomer->startTrans();
        
        try {
            // 修改用户enable 状态
            $CusCustomer->update(["enable"=>1],["id"=>$param['customerid']]);
            
            $result = $this->addUser(["mobile"=>$customer['mobile'],"recommendid"=>$recommend['id'],"recommendrole"=>$recommend['role']]);
            $CusCustomer->commit();
        } catch (\Exception $e) {
            $CusCustomer->rollback();
            
            Log::add($e,__METHOD__);
            
            return ["code"=>"400"];
        }
        
        if($result["code"] == "200") {
            $loginResult = $this->cusLogin(["customerid"=>$result['data']['customerid'],"mobile"=>$param['mobile']]);
            
            return ["code"=>"200","data" => urldecode($loginResult['data'])];
        }
        
        return ["code" => $result["code"]];
    }

    /**
     * 获取用户角色ID
     * @Author   zhuangqm
     * @DateTime 2017-10-10T17:28:38+0800
     * @param    [type]                   $param [
     *                                           customerid
     * ]
     * @return   [type]                          [description]
     */
    public function getUserRoleID($param){
        $row = Model::ins("CusCustomer")->getRow(["id"=>$param['customerid']],"role");
        return $row['role'];
    }
    
    /**
    * @user 返回分享
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017年10月12日下午3:39:56
    */
    public function pushUrl($param) {
        if(empty($param['recommendid']) || empty($param['checkcode'])) {
            return ["code" => "404"];
        }
        
        $paramString = "?";
        foreach ($param as $k => $v) {
            $paramString .= "$k=$v&";
        }
        
        $paramString = substr($paramString, 1);
        
        return ["code" => "200", "data" => $this->domain."/user/index/mycode?".$paramString];
//         $share = Config::get('shareparma');
//         $code_url = $share['code_url'];
        
//         $checkcode = md5($param['customerid'].getConfigKey());
//         $shareUrl = $code_url.'&checkcode='.$checkcode.'&parentid='.$param['customerid'];
        
//         return ["code" => "200", "data" => $shareUrl];
    }
    
    /**
    * @user 生成二维码
    * @param $parentid 用户id
    * @param $checkcode 用户加密校验码
    * @author jeeluo
    * @date 2017年10月12日下午4:00:39
    */
    public function myCode($paramArr) {
        $paramString = "";
        foreach ($paramArr as $k => $v) {
            $paramString .= "&$k=$v";
        }
        
        $paramString = substr($paramString, 1);
        
        $img = QRcode::png($this->mobileDomain."/user/index/index?".$paramString);
        
        echo $img;
        exit;
    }
}