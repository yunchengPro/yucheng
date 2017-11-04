<?php
namespace app\model\User;
use app\lib\Model; 

class UserRoleModel
{
    /**
    * @user 获取用户角色是否存在
    * @param 
    * @author jeeluo
    * @date 2017年10月26日下午4:55:31
    */
    public function getUserRole($param){
        $result = Model::ins("CusRole")->getRow(["customerid"=>$param['customerid'],"role"=>$param['role']],"id");
        return $result['id'];
    }
    
    /**
    * @user 写入角色记录
    * @param 
    * @author jeeluo
    * @date 2017年10月26日下午5:07:42
    */
    public function addUserRole($param) {
        // 判断角色是否存在
        if(!$this->getUserRole($param)) {
            // 写入记录
            $param['enable'] = 1;
            $param['addtime'] = getFormatNow();
            
            Model::ins("CusRole")->insert($param);
        }
    }
    
    /**
    * @user 获取用户其它角色(角色等级降序)
    * @param 
    * @author jeeluo
    * @date 2017年10月26日下午5:11:05
    */
    public function getUserOtherRole($param) {
//         print_r($param);
//         exit;
//         // 判断角色是否存在
//         if($this->getUserRole($param)) {
            
            $role_row = Model::ins("CusRole")->getRow(["customerid"=>$param['customerid'], "role"=>["!=",$param['role']]],"role","role desc");
            return !empty($role_row['role']) ? $role_row['role'] : 1;
//         }
//         return 1;
    }
}