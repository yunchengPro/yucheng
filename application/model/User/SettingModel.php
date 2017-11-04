<?php
namespace app\model\User;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\lib\ApiService\IdCartd;

class SettingModel
{
    
    public function updateInfo($data) {
        // 获取数据
        $update = [];
        $updateRedis = [];
        
        if(!empty($data['nickname']))
            $update['nickname'] = $updateRedis['nickname'] = $data['nickname'];
        if(!empty($data['sex']))
            $update['sex'] = $updateRedis['sex'] = $data['sex'];
        if(!empty($data['headerpic']))
            $update['headerpic'] = $updateRedis['headerpic'] = $data['headerpic'];
        
        if(!empty($updateRedis) && !empty($data['mtoken'])) {
            $updateRedis['mtoken'] = $data['mtoken'];
            // 更新redis数据
            $result = Model::new("User.UserToken")->updateMtokenRedis($updateRedis);
            
            if($result["code"] != "200") {
                return ["code" => $result["code"]];
            }
        }
        
        Model::ins("CusCustomerInfo")->update($update, ["id"=>$data['customerid']]);

        return ["code" => "200"];
    }
    
    /**
    * @user 实名认证操作
    * @param 
    * @author jeeluo
    * @date 2017年10月30日下午2:45:10
    */
    public function userAuth($param) {
        if(empty($param['realname']) || empty($param['idnumber']) || empty($param['customerid'])) {
            return ["code"=>"404"];
        }
        
        if(!CommonModel::validation_filter_idcard($param['idnumber'])) {
            return ["code" => "20003"];
        }
        
        $customerid = $param['customerid'];
        
        $checkuser_error_limit = 3;
        $ActLimitOBJ = Model::new("Sys.ActLimit");
        // 如果判断错误次数过多，就不再使用api接口进行检测
        $check_actlimit = $ActLimitOBJ->check("checkuser".$customerid,$checkuser_error_limit);
        
        if(!$check_actlimit['check']) {
            return ["code"=>"20023"];
        }
        
        // 使用api接口进行判断
        $result = IdCartd::api([
            "cardno"=>$param['idnumber'],
            "name"=>$param['realname']
        ]);
        
        
        if($result['resp']['code'] == "0") {
            
            // 校验通过，修改数据库
            Model::ins("CusCustomerInfo")->update(["idnumber"=>$param['idnumber'],"realname"=>$param['realname'],"isnameauth"=>1],["id"=>$customerid]);
            
            return ["code" => "200"];
        } else {
        
            // 更新错误次数
            $ActLimitOBJ->update("checkuser".$customerid,3600);
        
            if($result['resp']['code'] == "14") {
                return ["code"=>"20021"];
            } else if($result['resp']['code'] == "5") {
                return ["code"=>"20022"];
            }
            return ["code"=>"400"];
        }
    }
    
    /**
    * @user 修改手机号码
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午5:03:04
    */
    public function updatePhone($param) {
        if(empty($param['mobile']) || empty($param['valicode']) || empty($param['customerid'])) {
            return ["code" => "404"];
        }
        if(phone_filter($param['mobile'])) {
            return ["code" => "20006"];
        }
        
        $CusCustomer = Model::ins("CusCustomer");
        $mobileInfo = $CusCustomer->getIdByMobile($param['mobile']);
        
        if($mobileInfo) {
            return ["code" => "20011"];
        }
        
        $param['valicode'] = strtoupper($param['valicode']);
        // 验证手机号码
        $isUser = $CusCustomer->compare($param, "update_phone_");
        if($isUser) {
            // 验证码正确
            $phoneArr = ["mobile" => $param['mobile'],"username" => $param['mobile']];
            // 修改
            $status = $CusCustomer->update($phoneArr, ["id" => $param['customerid']]);
            if($status) {
                return ["code" => "200"];
            } else {
                return ["code" => "400"];
            }
        } else {
            return ["code" => "20005"];
        }
        return ["code" => "406"];
    }
    
    /**
    * @user 设置支付密码
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午7:08:43
    */
    public function setPay($param) {
        if(empty($param['paypwd']) || empty($param['customerid'])) {
            return ["code" => "404"];
        }
        
        $cusPay = Model::ins("CusCustomerPaypwd")->getRow(["id"=>$param['customerid']],"*");
        
        if(!empty($cusPay)) {
            if($cusPay['paypwd'] != '') {
                return ["code" => "408"];
            }
        }
        
        // 设置加密支付密码
        $data['paypwd'] = md5($param['paypwd'].getConfigKey());
        
        $data['id'] = $param['customerid'];
        
        $CusPayModel = Model::ins("CusCustomerPaypwd");
        
        $cuspwd = $CusPayModel->getRow(["id"=>$data['id']],"paypwd");
        
        if(empty($cuspwd)) {
            $status = $CusPayModel->add($data);
            if($status) {
                return ["code" => "200"];
            }
            return ["code" => "400"];
        }
        return ["code" => "10005"];
    }
    
    /**
    * @user 验证手机支付密码
    * @param 
    * @author jeeluo
    * @date 2017年10月30日上午11:19:25
    */
    public function validPhonePay($param) {
        
        if(empty($param['valicode']) || empty($param['customerid'])) {
            return ["code" => "404"];
        }

        // 验证手机号码
        $CusModel = Model::ins("CusCustomer");

        $cus = $CusModel->getRow(["id" => $param['customerid']],"mobile");
        
        if(phone_filter($cus['mobile'])) {
            return ["code" => "20006"];
        }

        $param['mobile'] = $cus['mobile'];
        
        
        $status = $CusModel->compare($param, "update_pay_");
        
        if($status) {
            return ["code" => "200"];
        } else {
            return ["code" => "20005"];
        }
        return ["code" => "400"];
    }
    
    /**
    * @user 验证支付密码
    * @param 
    * @author jeeluo
    * @date 2017年10月30日下午5:53:50
    */
    public function validPayPwd($param) {
        if(empty($param['paypwd']) || empty($param['customerid'])) {
            return ["code" => "404"];
        }
        
        $customerid = $param['customerid'];
        
        $cusPwdModel = Model::ins("CusCustomerPaypwd");
        $cuspwd = $cusPwdModel->getRow(["id" => $customerid],"id,paypwd");
        
        if(empty($cuspwd['id'])) {
            return ["code" => "10006"];
        }
        
        if(empty($cuspwd['paypwd'])) {
            return ["code" => "50001"];
        }
        
        $paypwd_error_limit = 3;
        $ActLimitOBJ = Model::new("Sys.ActLimit");
        //支付密码错误3次，就提示给用户
        $check_actlimit = $ActLimitOBJ->check("paypwd".$customerid,$paypwd_error_limit);
        if(!$check_actlimit['check']) {
            return ["code" => "50000"];
        }
        
        $paypwd = md5($param['paypwd'].getConfigKey());
        
        if($paypwd != $cuspwd['paypwd']) {
            $ActLimitOBJ->update("paypwd".$customerid,3600); //冻结一小时
            
            return ["code" => "50002"];
        }
        return ["code" => "200"];
    }
    
    /**
    * @user 修改支付密码操作
    * @param 
    * @author jeeluo
    * @date 2017年10月30日上午11:40:42
    */
    public function updatePayPwd($param) {
        if(empty($param['paypwd']) || empty($param['customerid'])) {
            return ["code" => "404"];
        }
        
        $cusPay = Model::ins("CusCustomerPaypwd")->getRow(["id"=>$param['customerid']],"*");
        
        if(empty($cusPay)) {
            return ["code" => "408"];
        } else {
            if($cusPay['paypwd'] == '') {
                // 密码还未设置了，杜绝url强制访问
                return ["code" => "408"];
            }
        }
        
        $customerid = $param['customerid'];
        
        $cusPwdModel = Model::ins("CusCustomerPaypwd");
        $oldData = $cusPwdModel->getRow(["id" => $customerid],"paypwd");
        
        if(empty($oldData)) {
            return ["code" => "10006"];
        }
        
        $paypwd = md5($param['paypwd'].getConfigKey());

        if($paypwd != $oldData['paypwd']) {
            // 操作成功，进行修改数据
            $status = $cusPwdModel->updatemodify(["paypwd"=>$paypwd],["id"=>$customerid]);
            if($status) {
                //清空支付密码的限制
                Model::new("Sys.ActLimit")->del("paypwd".$customerid);
                return ["code" => "200"];
            }
            return ["code" => "400"];
        }
        return ["code" => "10007"];
    }
    
    /**
    * @user 校验找回密码 修改密码 短信验证
    * @param 
    * @author jeeluo
    * @date 2017年11月1日上午10:17:16
    */
    public function validLoginPwd($param) {
        if(empty($param['mobile']) || empty($param['valicode'])) {
            return ["code" => "404"];
        }
        
        if(phone_filter($param['mobile'])) {
            return ["code" => "20006"];
        }
        
        $status = Model::ins("CusCustomer")->compare($param, "update_loginpwd_");
        
        if($status) {
            $data['mobile'] = $param['mobile'];
            $data['encrypt'] = md5($data['mobile'].getConfigKey());
            return ["code" => "200", "data"=>$data];
        } else {
            return ["code" => "20005"];
        }
        return ["code" => "400"];
    }
    
    /**
    * @user 找回登录密码/修改登录密码 操作
    * @param 
    * @author jeeluo
    * @date 2017年11月1日上午11:03:58
    */
    public function updateLoginPwd($param) {
        if(empty($param['mobile']) || empty($param['encrypt']) || empty($param['loginpwd']) || empty($param['confirmpwd'])) {
            return ["code" => "404"];
        }
        
        if(phone_filter($param['mobile'])) {
            return ["code" => "20006"];
        }
        
        if(md5($param['mobile'].getConfigKey()) != $param['encrypt']) {
            return ["code" => "408"];
        }
        
        if($param['loginpwd'] != $param['confirmpwd']) {
            return ["code" => "2004"];
        }

        $param['loginpwd'] = strtoupper($param['loginpwd']);
        
        // 查看用户是否已经设置密码
        $CusCustomer = Model::ins("CusCustomer");
        
        $cus = $CusCustomer->getRow(["mobile" => $param['mobile']],"id,loginpwd");
        
        if(!empty($cus['id'])) {
            $loginpwd = strtoupper(md5($param['loginpwd'].getConfigPwd()));

            // 执行修改操作
            $CusCustomer->update(["loginpwd"=>$loginpwd],["id"=>$cus['id']]);
            
            return ["code" => "200"];
        }
        
        return ["code" => "2001"];
    }
}