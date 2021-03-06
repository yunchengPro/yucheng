<?php
namespace app\model\User;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\User\UserModel;

class UserBankModel
{

    /**
    * @user 获取用户银行卡信息
    * @param $customerid 用户id
    * @author jeeluo
    * @date 2017年10月13日下午2:13:43
    */
    public function getUserBankInfo($param)
    {
        $customerid = $param['customerid'];
        
        if($customerid == '') {
            return ["code" => "404"];
        }
        
        // 获取到默认的信息，假如没有返回为空信息
        $where['customerid'] = $customerid;
        $where['enable'] = 1;

        if(!empty($param['bankid'])) {
            $where['id'] = $param['bankid'];
        }
        
        // 标识
        $result['bankStatus'] = 0;
        $bankInfo = Model::ins("CusBank")->getRow($where,"id,bank_type_name,account_type,account_number","is_default desc, sort desc, addtime desc");
        if(!empty($bankInfo['id'])) {
            $result['bankStatus'] = 1;
        }
        if(empty($bankInfo)) {
            $bankInfo['id'] = "";
            $bankInfo['bank_type_name'] = "";
            $bankInfo['account_type'] = "";
            $bankInfo['account_number'] = "";
        }
        $result['bankInfo'] = $bankInfo;
        
        return ["code" => "200", "data" => $result];
    }

    /**
    * @user 获取用户银行卡列表数据
    * @param $customerid 用户id
    * @author jeeluo
    * @date 2017年10月13日下午2:16:10
    */
    public function getUserBankList($param)
    {
        $customerid = $param['customerid'];
        
        if($customerid == '') {
            return ["code" => "404"];
        }
        
        $where['customerid'] = $customerid;
        $where['enable'] = 1;
        
        $result = Model::ins("CusBank")->pageList($where,"id,bank_type_name,account_type,account_number","is_default desc, sort desc ,addtime desc");

        foreach ($result['list'] as $k => $v) {
            // 格式化银行卡号码

            $result['list'][$k]['account_number'] = CommonModel::bank_format($v['account_number']);
        }
        
        return ["code" => "200", "data" => $result];
    }

    /**
     * [addBankNumber 添加银行卡]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-16T14:34:30+0800
     * @param    [type]                   $param [description]
     */
    public function addBankNumber($param){
       
        $customerid = $param['customerid'];
        $bank_type_name = $param['bank_type_name'];
        $account_type = $param['account_type'];
        $account_name = $param['account_name'];
        $account_number = $param['account_number'];
        $branch = $param['branch'];
        $mobile = $param['mobile'];

       

        if(empty($customerid))
            return ['code'=>404,'data'=>[],'msg'=>'用户信息不存在，请重新登录'];

        if(empty($bank_type_name))
            return ['code'=>404,'data'=>[],'msg'=>'开户银行名称不能为空'];

        if(empty($account_name))
            return ['code'=>404,'data'=>[],'msg'=>'银行开户名'];

        if(empty($account_type))
            return ['code'=>404,'data'=>[],'msg'=>'请选择账户类型'];

        if(empty($account_number))
            return ['code'=>404,'data'=>[],'msg'=>'银行卡号不能为空'];

          // 当是个人帐号时才去校验银行卡号(对公帐号规则 太杂了。没标准)；
        if($param['account_type'] == 1) {
            if(!CommonModel::account_bank_validate($param['account_number'])) {
                return  ['code'=>20012,'data'=>[],'msg'=>'银行卡号码不正确']; 
            }
        }



        if(empty($branch))
            return ['code'=>404,'data'=>[],'msg'=>'请填写支行名称'];

        if(empty($mobile))
            return ['code'=>404,'data'=>[],'msg'=>'银行预留手机号码不能为空'];
        
        $insert = [
            'customerid' => $customerid,
            'bank_type_name' => $bank_type_name,
            'account_type' => $account_type,
            'account_name' => $account_name,
            'account_number' => $account_number,
            'branch' => $branch,
            'mobile' => $mobile,
            'addtime' => date('Y-m-d H:i:s')
        ];



        $bank_row = Model::ins('CusBank')->getRow(['account_number'=>$account_number,'customerid'=>$customerid],'id,customerid,enable');


         $userOBJ = new UserModel();
       
        if($param['account_type'] == 1) {
        // 校验银行卡和用户名是否一致--通过api接口进行校验
            $checkbankresult = $userOBJ->checkbankInfo([
                    "userid"=>$customerid,
                    "account_name"=>$insert['account_name'],
                    "account_number"=>$insert['account_number'],
                ]);
         
            if($checkbankresult["code"]!='200')
                return ['code'=>$checkbankresult["code"]];
        }
        
        if(!empty($bank_row)){
            if($bank_row['enable'] == 1){
                return ['code'=>404,'data'=>[],'msg'=>'该银行卡已经添加'];
            }else{
                if($customerid == $bank_row['customerid']) {
                    // 重新启用
                    $ret = Model::ins("CusBank")->modify(["enable"=>1],['id'=>$bank_row['id']]);
                } else {
                    $ret = Model::ins("CusBank")->add($insert);
                }
                // $insert['enable'] =1;
                // $ret = Model::ins("CusBank")->modify($insert,['id'=>$bank_row['id']]);
            }
        }else{

            $ret = Model::ins("CusBank")->add($insert);
        }
        if($ret > 0){
            // return ['code'=>200,'data'=>$ret,'msg'=>'添加成功'];
            return ['code'=>200,'data'=>[],'msg'=>'添加成功'];
        }else{
            return ['code'=>400,'data'=>[],'msg'=>'添加错误，请重新提交'];
        }

    }

    /**
     * [unbindBank 解绑银行卡]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-16T18:02:52+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function unbindBank($param){
        $cusBankOBJ =  Model::ins('CusBank');
        // 查询数据库该卡号是否已经存在(已激活的)
        $cusBankInfo = $cusBankOBJ->getRow(array("id" => $param['bank_id'], "enable" => 1), "id, customerid");
        if(empty($cusBankInfo)) {
            return ["code" => 10003,'data'=>[],'msg'=>'信息不存在'];
        }
        if($cusBankInfo['customerid'] != $param['customerid']) {
            return ["code" => 4004,'data'=>[],'msg'=>'无权操作'];
        }
        
        // 进行解绑操作
        $data['enable'] = '-1';
        $data['addtime'] = getFormatNow();
        $status = $cusBankOBJ->modify($data, array("id" => $param['bank_id']));

//         $status = $cusBankOBJ->delete(array("id" => $params["id"]));
        if($status) {
            return ["code" => 200,'data'=>[],'msg'=>'解绑成功'];
        }
        return ["code" => 400,'data'=>[],'msg'=>'操作有误'];
    }
}