<?php
// +----------------------------------------------------------------------
// |  [ 牛创客模型]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月30日 21:12:02
// +----------------------------------------------------------------------
namespace app\model\Customer;

use \think\Request;
//获取配置
use \think\Config;
//Config::get('webview.webviewUrl');

//use app\lib\Db;
use app\lib\Model;
//use app\lib\Img;

use app\model\Customer\CustomerModel;

class BullenRoleModel extends CustomerModel
{ 
    protected $_role = 'bullenRole';
    protected $_recoModelName     = 'RoleRecoEn';
    protected $_relationModelName = 'CusRelationEn';
    
    /**
     * [getWaitAuditList 获取待审核列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 21:12:02
     * @return [type]    [description]
     */
    public function getWaitAuditList($where)
    {
        $list = parent::_getWaitAuditList($where);
    
        return $list;
    }
    
    /**
     * [getNoPassList 获取待审核列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 16:19:02
     * @return [type]    [description]
     */
    public function getNoPassList($where)
    {
        $list = $this->_getNoPassList($where);
    
        return $list;
    }
    
    /**
     * [pass 通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 21:12:02
     * @return [type]    [description]
     */
    public function pass($roleReco)
    {
        //var_dump('BullenRoleModel pass'); exit();
        
        $result = $this->_pass($roleReco);
        
        // 生成流水号
        $flowid = Model::new("Amount.Flow")->getFlowId($roleReco['orderno']);
        
        $user = Model::ins("CusCustomer")->getIdByMobile($roleReco['mobile']);
        Model::new("Amount.Role")->pay_tnc([
            "userid"=>$user['id'],
            "amount"=>$roleReco['amount'],
            "orderno"=>$roleReco['orderno'],
            "flowid"=>$flowid,
        ]);
        
        return $result;
    }
    
    public function payPass($roleReco) {
        $result = $this->_pass($roleReco);
        return $result;
    }
    
    /**
    * @user 申请成为牛创客操作
    * @param $applyInfo 牛创客信息
    * @author jeeluo
    * @date 2017年4月5日下午8:55:10
    */
    public function apply($applyInfo) {
        $result = $this->_apply($applyInfo);
        
        return $result;
    }
    
    /**
     * [noPass 不通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午4:03:04
     * @return [type]    [description]
     */
    public function noPass($roleReco)
    {
        //var_dump('BullPeoRoleModel noPass'); exit();
    
        $result = $this->_noPass($roleReco);
    
        return $result;
    }

    /**
    * @user 获取用户详情
    * @param 
    * @author jeeluo
    * @date 2017年4月26日 14:07:14
    */
    public function getCusCustomerInfo($customerid) {
        return $this->_getCusCustomerInfo($customerid);
    }

    /**
    * @user 获取推荐牛创客填写的资料
    * @param 
    * @author jeeluo
    * @date 2017年4月26日 14:07:32
    */
    public function getRecoEnInfo($id) {
        $recoInfo = Model::ins("RoleRecoEn")->getRow(array("id" => $id));
        if(empty($recoInfo)) {
            return ["code" => "1000", "data" => "记录不存在"];
        }

        if($recoInfo['instroducerid'] == -1) {
            $recoInfo['instroducerMobile'] = '';
        } else {
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $recoInfo['instroducerid']), "mobile");
            $recoInfo['instroducerMobile'] = $cus['mobile'];
        }

        return ["code" => "200", "data" => $recoInfo];
    }
    
    /**
    * @user 添加用户角色
    * @param 
    * @author jeeluo
    * @date 2017年4月26日上午10:56:42
    */
    public function addCusRole($params) {
        $result = $this->_addCusRole($params);
        
//         if($result["code"] == "200") {
//             // 生成流水号
//             $flowid = Model::new("Amount.Flow")->getFlowId($result['data']['orderno']);
            
//             $user = Model::ins("CusCustomer")->getIdByMobile($result['data']['mobile']);
//             Model::new("Amount.Role")->pay_tnc([
//                 "userid"=>$user['id'],
//                 "amount"=>$result['data']['amount'],
//                 "orderno"=>$result['data']['orderno'],
//                 "flowid"=>$flowid,
//             ]);
//         }
        
        return $result;
    }
    
    public function examSend($params) {
        $recoId = $params['recoId'];
        
        if(!empty($recoId)) {
            $title = '';
            $content = '';
            $roleRecoEn = Model::ins("RoleRecoEn")->getRow(["id"=>$params['recoId']],"instroducerid,realname,status,remark");
        
            if($roleRecoEn['status'] == 2) {
                $title = "分享成功";
                $content = "您分享".$roleRecoEn['realname']."为牛创客,已审核成功!";
            } else {
                $title = "分享失败";
                $content = "您分享".$roleRecoEn['realname']."为牛创客,未通过审核!原因:".$roleRecoEn['remark'];
            }
        
            Model::new("Msg.SendMsg")->SendSysMsg(["userid"=>$roleRecoEn['instroducerid'],"title"=>$title,"content"=>$content]);
        }
        return true;
    }
}