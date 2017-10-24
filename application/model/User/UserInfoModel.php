<?php
namespace app\model\User;
use app\lib\Model;
use \think\Config;
use app\model\Sys\CommonModel;

class UserInfoModel {
    
    /**
    * @user 获取用户基本信息
    * @param customerid 用户id值
    * @author jeeluo
    * @date 2017年10月10日上午10:39:42
    */
    public function userBaseInfo($param) {
        // 传递过来用户id值
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        // 获取用户手机号码
        $CusCustomer = Model::ins("CusCustomer");
        // 获取用户基本信息
        $CusCustomerInfo = Model::ins("CusCustomerInfo");
        
        $customer = $CusCustomer->getRow([
            "id" => $param['customerid']
        ], "mobile,role");
        
        $customerInfo = $CusCustomerInfo->getRow([
            "id" => $param['customerid']
        ],"realname,nickname,headerpic");
        
        $config = Config("rolename");
        
        $result['mobile'] = $customer['mobile'];
        $result['phone'] = CommonModel::mobile_format($result['mobile']);
        $result['role'] = $customer['role'];
        $result['roleName'] = $config[$result['role']];
        $result['realname'] = $customerInfo['realname'];
        $result['nickname'] = $customerInfo['nickname'];
        $result['headerpic'] = $customerInfo['headerpic'];
        
        return ["code" => "200", "data" => $result];
    }
    
    
}