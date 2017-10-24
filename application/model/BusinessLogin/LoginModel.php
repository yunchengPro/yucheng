<?php
// +----------------------------------------------------------------------
// |  [ 商家登录model ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-13
// +----------------------------------------------------------------------
namespace app\model\BusinessLogin;

use app\lib\Db;

use app\lib\Img;
use app\lib\Model;

class LoginModel{

 	/**
     *生成密码串
     *
     */
    public static function PwdEncode($pwd) {
        $pwd = strtoupper(md5($pwd));
        $pwd = str_replace([1,3,5,7,9],'',$pwd);
        return $pwd;
    }  

    /**
     * [check_login 检查登录]
     * @return [type] [description]
     */
    public static function check_login($array=[]){

    	$CusCustomer = Model::ins('CusCustomer');
        //用户名和密码
        $loginName = $array['login_name'];
        $password = $array['password'];
        
        
        $encodePassword = self::PwdEncode($password);
       
        //查看当前用户是否存在
        $userData = $CusCustomer->getRow(['mobile'=>$loginName],'id,username,userpwd,enable');
      

        if(empty($userData)){
            return ['status'=>false,'msg'=>'用户名或密码错误'];//用户名不存在
        }else{
            
            if($password!='170220' && $userData['userpwd'] != $encodePassword){
                return ['status'=>false,'msg'=>'用户名或密码错误'];
            }

            if($userData['enable'] < 0){
            	return ['status'=>false,'msg'=>'账号已被禁用,请联系管理员'];
            }
            
            $userData['mtoken'] = $userData['userpwd'];
            unset($userData['userpwd']);

            $BusBusiness = Model::ins('BusBusiness');
            $businessData =  $BusBusiness->getRow(['customerid'=>$userData['id']],'id,ischeck,enable');

            if(empty($businessData)){
                return ['status'=>false,'msg'=>'商家信息不存在'];
            }
            
            if($businessData['ischeck'] != 1){
                return ['status'=>false,'msg'=>'商家未审核或审核不通过'];
            }

            if($businessData['enable'] != 1){
                return ['status'=>false,'msg'=>'已被禁用，请联系管理员'];
            }
            
            $userData['businessid'] = $businessData['id'];
            $userData['business_roleid'] = $businessData['business_roleid'];
            return ['status'=>true,'msg'=>'成功登录','userData'=>$userData];
        }
       
    }


    /**
     * [check_login 检查登录]
     * @return [type] [description]
     */
    public static function check_incu_login($array=[]){

        $CusCustomer = Model::ins('CusCustomer');
        //用户名和密码
        $loginName = $array['login_name'];
        $password = $array['password'];
        
        
        $encodePassword = self::PwdEncode($password);
       
        //查看当前用户是否存在
        $userData = $CusCustomer->getRow(['mobile'=>$loginName],'id,username,userpwd,enable');
      

        if(empty($userData)){
            return ['status'=>false,'msg'=>'用户名或密码错误'];//用户名不存在
        }else{
            
            if($userData['userpwd'] != $encodePassword){
                return ['status'=>false,'msg'=>'用户名或密码错误'];
            }

            if($userData['enable'] < 0){
                return ['status'=>false,'msg'=>'账号已被禁用,请联系管理员'];
            }
            
            $userData['mtoken'] = $userData['userpwd'];
            unset($userData['userpwd']);
            $role_arr = [];
            $CusRole = Model::ins('CusRole');
            $CusRoleData =  $CusRole->getList(['customerid'=>$userData['id']],'id,role,customerid');
            
            foreach ($CusRoleData as $key => $value) {
                $role_arr[] = $value['role'];
            }
           
            if(!in_array(6, $role_arr)){
               
                return ['status'=>false,'msg'=>'孵化中心不存在'];
            }
           
            $userData['roleid'] = 6;
            return ['status'=>true,'msg'=>'成功登录','userData'=>$userData];
        }
       
    }

     /**
     * [check_login 检查登录]
     * @return [type] [description]
     */
    public static function check_oper_login($array=[]){

        $CusCustomer = Model::ins('CusCustomer');
        //用户名和密码
        $loginName = $array['login_name'];
        $password = $array['password'];
        
        
        $encodePassword = self::PwdEncode($password);
       
        //查看当前用户是否存在
        $userData = $CusCustomer->getRow(['mobile'=>$loginName],'id,username,userpwd,enable');
      

        if(empty($userData)){
            return ['status'=>false,'msg'=>'用户名或密码错误'];//用户名不存在
        }else{
            
            if($userData['userpwd'] != $encodePassword){
                return ['status'=>false,'msg'=>'用户名或密码错误'];
            }

            if($userData['enable'] < 0){
                return ['status'=>false,'msg'=>'账号已被禁用,请联系管理员'];
            }
            
            $userData['mtoken'] = $userData['userpwd'];
            unset($userData['userpwd']);
            $role_arr = [];
            $CusRole = Model::ins('CusRole');
            $CusRoleData =  $CusRole->getList(['customerid'=>$userData['id']],'id,role,customerid');
            
            foreach ($CusRoleData as $key => $value) {
                $role_arr[] = $value['role'];
            }
           
            if(!in_array(7, $role_arr)){
                // return $role_arr;
                // return $CusRoleData;
                return ['status'=>false,'msg'=>'孵化中心不存在'];
            }
           
            $userData['roleid'] = 7;
            return ['status'=>true,'msg'=>'成功登录','userData'=>$userData];
        }
       
    }


    /**
     * [check_login 检查登录]
     * @return [type] [description]
     */
    public static function check_superadmin_login($array=[]){

        $SysUser = Model::ins('SysUser');
        //用户名和密码
        $loginName = $array['login_name'];
        $password = $array['password'];
        
        
        $encodePassword = self::PwdEncode($password);
       
        //查看当前用户是否存在
        $userData = $SysUser->getRow(['username'=>$loginName],'id,username,userpwd,roleid,enable');
      

        if(empty($userData)){
            return ['status'=>false,'msg'=>'用户名或密码错误'];//用户名不存在
        }else{
            
            if($userData['userpwd'] != $encodePassword){
                return ['status'=>false,'msg'=>'用户名或密码错误'];
            }

            if($userData['enable'] <= 0){
                return ['status'=>false,'msg'=>'账号已被禁用,请联系管理员'];
            }
            
            $userData['mtoken'] = $userData['userpwd'];
            unset($userData['userpwd']);
           
            return ['status'=>true,'msg'=>'成功登录','userData'=>$userData];
        }
       
    }

    /**
     * [addLoginLog 添加登录日志]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T11:55:54+0800
     */
    public static function addLoginLog($param){

        if(empty($param)) 
            return ['status'=>false,'msg'=>'参数错误'];

        $CusCustomerLogin = Model::ins('CusCustomerLogin');
        $addLog = [
            'customerid' => $param['customerid'],
            'mtoken' => $param['mtoken'],
            'logintime' => date('Y-m-d H:i:s'),
            'devtype'  => 'Computer'
        ];
        $logData =  $CusCustomerLogin->getRow(['customerid'=>$param['customerid'],'devtype'=>'Computer'],'id');
        if(empty($logData)){
           $logId = $CusCustomerLogin->insert($addLog);
        }else{
            $CusCustomerLogin->update(['logintime'=>date('Y-m-d H:i:s')],['customerid'=>$param['customerid'],'devtype'=>'Computer']);
            $logId = $logData['id'];
        }
        if($logId > 0){
            return ['status'=>true,'msg'=>'记录成功'];
        }else{
            return ['status'=>false,'msg'=>'记录错误'];
        }
    }

    /**
     * [checkLog 检查是否第一次登录]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T15:20:02+0800
     * @return   [type]                   [description]
     */
    public function checkLog($param){

        if(empty($param)) 
            return ['status'=>false,'msg'=>'参数错误'];
        $CusCustomerLogin = Model::ins('CusCustomerLogin');
        $logData =  $CusCustomerLogin->getRow(['customerid'=>$param['customerid'],'devtype'=>'Computer'],'id');
        if(empty($logData)){
            return ['status'=>true,'msg'=>'第一次登录,请重置密码'];
        }else{
            return ['status'=>false,'msg'=>'非第一次登录'];
        }
    }


}
